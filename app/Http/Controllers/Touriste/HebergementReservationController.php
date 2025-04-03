<?php

namespace App\Http\Controllers\Touriste;

use App\Models\Chambre;
use App\Models\Hebergement;
use App\Models\Reservation;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HebergementReservationController extends Controller
{
    /**
     * Affiche la liste des réservations du touriste connecté
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->where('reservable_type', 'App\\Models\\Chambre')
            ->with(['reservable.hebergement']) // Charge la chambre et son hébergement associé
            ->latest()
            ->paginate(10);
            
        return view('reservations.hebergements.index', compact('reservations'));
    }

    
    
    /**
     * Affiche le formulaire de création d'une réservation pour une chambre spécifique
     */
    public function create($chambre_id)
    {
        $chambre = Chambre::with('hebergement')->findOrFail($chambre_id);
        
        // Vérifier si la chambre est disponible et visible
        if (!$chambre->est_disponible || !$chambre->est_visible) {
            return redirect()->route('hebergements.show', $chambre->hebergement_id)
                ->with('error', 'Cette chambre n\'est pas disponible pour réservation.');
        }
        
        return view('reservations.hebergements.create', compact('chambre'));
    }
    
    /**
     * Traite la création d'une nouvelle réservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'chambre_id' => 'required|exists:chambres,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'nombre_personnes' => 'required|integer|min:1',
        ]);
        
        $chambre = Chambre::findOrFail($validated['chambre_id']);
        
        // Vérifier la capacité de la chambre
        if ($validated['nombre_personnes'] > $chambre->capacite) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Le nombre de personnes dépasse la capacité de la chambre.');
        }
        
        // Vérifier la disponibilité pour la période demandée
        $dateDebut = Carbon::parse($validated['date_debut']);
        $dateFin = Carbon::parse($validated['date_fin']);
        
        if (!$chambre->estDisponible($dateDebut, $dateFin)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'La chambre n\'est pas disponible pour les dates sélectionnées.');
        }
        
        // Créer la réservation avec les champs polymorphiques
        $reservation = new Reservation([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'statut' => 'en_attente',
            'nombre_personnes' => $validated['nombre_personnes'],
            'user_id' => Auth::id(),
            'statut_paiement' => 'en_attente',
            'reference_paiement' => 'RES-' . Str::random(10),
            'reservable_id' => $chambre->id,
            'reservable_type' => get_class($chambre),
        ]);
        
        $reservation->save();
        
        return redirect()->route('touriste.reservations.hebergements.show', $reservation->id)
            ->with('success', 'Votre réservation a été créée avec succès. Veuillez procéder au paiement pour la confirmer.');
    }
    
    /**
     * Affiche les détails d'une réservation spécifique
     */
    public function show($id)
    {
        $reservation = $this->getUserReservation($id);
        
        return view('reservations.hebergements.show', compact('reservation'));
    }
    
    /**
     * Affiche le formulaire pour modifier une réservation
     */
    public function edit($id)
    {
        $reservation = $this->getUserReservation($id);
        
        // Vérifier si la réservation peut être modifiée
        if ($reservation->statut === 'annulee' || $reservation->statut === 'terminee') {
            return redirect()->route('touriste.reservations.hebergements.show', $reservation->id)
                ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }
        
        return view('reservations.hebergements.edit', compact('reservation'));
    }
    
    /**
     * Traite la modification d'une réservation
     */
    public function update(Request $request, $id)
    {
        $reservation = $this->getUserReservation($id);
        
        // Vérifier si la réservation peut être modifiée
        if ($reservation->statut === 'annulee' || $reservation->statut === 'terminee') {
            return redirect()->route('touriste.reservations.hebergements.show', $reservation->id)
                ->with('error', 'Cette réservation ne peut plus être modifiée.');
        }
        
        $validated = $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'nombre_personnes' => 'required|integer|min:1',
        ]);
        
        $reservable = $reservation->reservable;
        
        if ($reservable instanceof Chambre) {
            // Vérifier la capacité de la chambre
            if ($validated['nombre_personnes'] > $reservable->capacite) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Le nombre de personnes dépasse la capacité de la chambre.');
            }
            
            // Vérifier la disponibilité pour la période demandée (en excluant la réservation actuelle)
            $dateDebut = Carbon::parse($validated['date_debut']);
            $dateFin = Carbon::parse($validated['date_fin']);
            
            // Requête pour vérifier les conflits de réservation en excluant la réservation actuelle
            $reservationsConflictuelles = Reservation::where('reservable_id', $reservable->id)
                ->where('reservable_type', get_class($reservable))
                ->where('id', '!=', $reservation->id)
                ->where('statut', '!=', 'annulee')
                ->where(function ($query) use ($dateDebut, $dateFin) {
                    $query->where(function ($q) use ($dateDebut, $dateFin) {
                        $q->where('date_debut', '<=', $dateFin)
                          ->where('date_fin', '>=', $dateDebut);
                    });
                })
                ->count();
                
            if ($reservationsConflictuelles > 0) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'La chambre n\'est pas disponible pour les dates sélectionnées.');
            }
        }
        
        // Mettre à jour la réservation
        $reservation->date_debut = $dateDebut;
        $reservation->date_fin = $dateFin;
        $reservation->nombre_personnes = $validated['nombre_personnes'];
        $reservation->save();
        
        return redirect()->route('touriste.reservations.hebergements.show', $reservation->id)
            ->with('success', 'Votre réservation a été mise à jour avec succès.');
    }
    
    /**
     * Affiche la page de confirmation d'annulation
     */
    public function confirmCancel($id)
    {
        $reservation = $this->getUserReservation($id);
        
        // Vérifier si la réservation peut être annulée
        if ($reservation->statut === 'annulee' || $reservation->statut === 'terminee') {
            return redirect()->route('touriste.reservations.hebergements.show', $reservation->id)
                ->with('error', 'Cette réservation ne peut plus être annulée.');
        }
        
        return view('reservations.hebergements.confirm-cancel', compact('reservation'));
    }
    
    /**
     * Traite l'annulation d'une réservation
     */
    public function cancel(Request $request, $id)
    {
        $reservation = $this->getUserReservation($id);
        
        // Vérifier si la réservation peut être annulée
        if ($reservation->statut === 'annulee' || $reservation->statut === 'terminee') {
            return redirect()->route('touriste.reservations.hebergements.show', $reservation->id)
                ->with('error', 'Cette réservation ne peut plus être annulée.');
        }
        
        $validated = $request->validate([
            'raison_annulation' => 'required|string|max:255',
        ]);
        
        // Annuler la réservation
        $reservation->annuler($validated['raison_annulation']);
        
        return redirect()->route('touriste.reservations.hebergements.index')
            ->with('success', 'Votre réservation a été annulée avec succès.');
    }
    
    /**
     * Récupère une réservation appartenant à l'utilisateur connecté
     */
    private function getUserReservation($id)
    {
        $reservation = Reservation::with(['reservable'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
            
        return $reservation;
    }
}