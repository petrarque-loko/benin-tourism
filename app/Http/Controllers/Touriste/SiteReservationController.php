<?php

namespace App\Http\Controllers\Touriste;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteTouristique;
use App\Models\Reservation;
use DateTime;
use App\Models\User;
use App\Notifications\NouvelleReservation;
use App\Notifications\ReservationModifiee;
use App\Notifications\ReservationAnnulee;

class SiteReservationController extends Controller
{
    // ReservationController.php

    public function index()
    {
        $reservations = Reservation::where('user_id', auth()->id())
                                    ->where('reservable_type', 'App\Models\SiteTouristique')
                                    ->with(['reservable', 'guide'])
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10);
        
        return view('reservations.sites.index', compact('reservations'));
    }

    public function show($id)         
    {
        // Récupérer la réservation avec toutes les relations nécessaires
        $reservation = Reservation::where('user_id', auth()->id())
                                ->with([
                                    'reservable.categorie', 
                                    'guide', 
                                ])
                                ->findOrFail($id);
        
        // Vérifier les permissions (s'assurer que l'utilisateur peut voir cette réservation)
        $this->authorize('view', $reservation);
        
        return view('reservations.sites.show', compact('reservation'));
    }

    public function create($siteId)
    {
        $site = SiteTouristique::findOrFail($siteId);
        
        // Récupérer les dates de la réservation depuis la requête
        $dateDebut = request('date_debut');
        $dateFin = request('date_fin');
        
        if (!$dateDebut || !$dateFin) {
            return redirect()->back()->with('error', 'Veuillez sélectionner les dates de réservation.');
        }
        
        // Convertir en objets DateTime
        $dateDebutObj = new DateTime($dateDebut);
        $dateFinObj = new DateTime($dateFin);
        
        // Trouver les guides disponibles qui n'ont pas de réservations en conflit
        $guidesOccupes = Reservation::where('date_debut', '<=', $dateFin)
                                ->where('date_fin', '>=', $dateDebut)
                                ->where('statut', 'approuvé')
                                ->pluck('guide_id')
                                ->toArray();
                                
        $guides = User::where('role_id', 10) // Guide role
                    ->where('status', 'active')
                    ->whereNotIn('id', $guidesOccupes)
                    ->get();
     // Affichera tous les guides
        
        return view('reservations.sites.create', compact('site', 'guides', 'dateDebut', 'dateFin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'guide_id' => 'required|exists:users,id',
            'reservable_id' => 'required|integer',
            'reservable_type' => 'required|string',
        ]);
        
        // Vérifier si le guide est disponible entre les dates
        $guideDisponible = !Reservation::where('guide_id', $request->guide_id)
                                    ->where('date_debut', '<=', $request->date_fin)
                                    ->where('date_fin', '>=', $request->date_debut)
                                    ->where('statut', 'approuvé')
                                    ->exists();
        
        if (!$guideDisponible) {
            return redirect()->back()->with('error', 'Ce guide n\'est plus disponible aux dates sélectionnées.');
        }
        
        $reservation = new Reservation();
        $reservation->date_debut = $request->date_debut;
        $reservation->date_fin = $request->date_fin;
        $reservation->statut = 'en_attente';
        $reservation->user_id = auth()->id();
        $reservation->guide_id = $request->guide_id;
        $reservation->reservable_id = $request->reservable_id;
        $reservation->reservable_type = $request->reservable_type;
        $reservation->save();
        
        // Notifier le guide
        $guide = User::find($request->guide_id);
        $guide->notify(new NouvelleReservation($reservation));
        
        return redirect()->route('touriste.reservations.sites.index')
                        ->with('success', 'Votre demande de réservation a été envoyée.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
        ]);
        
        $reservation = Reservation::where('user_id', auth()->id())->findOrFail($id);
        
        // Vérifier si la réservation peut être modifiée
        if (!in_array($reservation->statut, ['en_attente', 'approuvé'])) {
            return redirect()->back()->with('error', 'Cette réservation ne peut plus être modifiée.');
        }
        
        // Vérifier si le guide est disponible pour les nouvelles dates
        $guideDisponible = !Reservation::where('guide_id', $reservation->guide_id)
                                    ->where('id', '!=', $id)
                                    ->where('date_debut', '<=', $request->date_fin)
                                    ->where('date_fin', '>=', $request->date_debut)
                                    ->where('statut', 'approuvé')
                                    ->exists();
        
        if (!$guideDisponible) {
            return redirect()->back()->with('error', 'Le guide n\'est pas disponible aux dates sélectionnées.');
        }
        
        $reservation->date_debut = $request->date_debut;
        $reservation->date_fin = $request->date_fin;
        
        // Si la réservation était déjà approuvée, on la remet en attente
        if ($reservation->statut == 'approuvé') {
            $reservation->statut = 'en_attente';
        }
        
        $reservation->save();
        
        // Notifier le guide
        $reservation->guide->notify(new ReservationModifiee($reservation));
        dd(route('touriste.reservations.sites.index'));
        return redirect()->route('touriste.reservations.sites.index')
                        ->with('success', 'Votre réservation a été mise à jour.');
    }

    public function cancel($id)
    {
        $reservation = Reservation::where('user_id', auth()->id())->findOrFail($id);
        
        // Vérifier si la réservation peut être annulée
        if (in_array($reservation->statut, ['annulé', 'rejeté', 'terminé'])) {
            return redirect()->back()->with('error', 'Cette réservation ne peut plus être annulée.');
        }
        
        $reservation->statut = 'annulé';
        $reservation->raison_annulation = "Annulée par le touriste";
        $reservation->save();
        
        // Notifier le guide
        $reservation->guide->notify(new ReservationAnnulee($reservation));
        
        return redirect()->route('touriste.reservations.sites.index')
                        ->with('success', 'Votre réservation a été annulée.');
    }

    public function edit($id)
    {
        // Récupérer la réservation de l'utilisateur connecté
        $reservation = Reservation::where('user_id', auth()->id())
                                ->with(['reservable', 'guide'])
                                ->findOrFail($id);
        
        // Vérifier si la réservation peut être modifiée
        if (!in_array($reservation->statut, ['en_attente', 'approuvé'])) {
            return redirect()->route('touriste.reservations.sites.index')
                            ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }
        
        // Récupérer le site associé à la réservation
        $site = $reservation->reservable;
        
        // Trouver les guides disponibles qui n'ont pas de réservations en conflit
        $guidesOccupes = Reservation::where('date_debut', '<=', $reservation->date_fin)
                                    ->where('date_fin', '>=', $reservation->date_debut)
                                    ->where('statut', 'approuvé')
                                    ->where('id', '!=', $reservation->id) // Exclure la réservation actuelle
                                    ->pluck('guide_id')
                                    ->toArray();
        
        // Récupérer les guides disponibles
        $guides = User::where('role_id', 10) // Guide role
                    ->whereNotIn('id', $guidesOccupes)
                    ->where('status', 'active')
                    // Ajouter le guide actuel de la réservation même s'il est occupé
                    ->orWhere('id', $reservation->guide_id)
                    ->get();
        
        // Formater les dates pour les champs du formulaire
        $dateDebut = $reservation->date_debut;
        $dateFin = $reservation->date_fin;
        
        return view('reservations.sites.edit', compact(
            'reservation', 
            'site', 
            'guides', 
            'dateDebut', 
            'dateFin'
        ));
    }
}     
