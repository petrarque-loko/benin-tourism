<?php

namespace App\Http\Controllers\Touriste;

use App\Models\Circuit;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CircuitReservationController extends Controller
{
    /**
     * Affiche la liste des réservations de circuits du touriste connecté
     */
    public function index()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->where('reservable_type', 'App\\Models\\Circuit')
            ->with('reservable')
            ->latest()
            ->paginate(10);
            
        return view('reservations.circuits.index', compact('reservations'));
    }

    /**
     * Affiche le formulaire de création d'une réservation de circuit
     */
    public function create(Circuit $circuit)
    {
        
        return view('reservations.circuits.create', compact('circuit'));
    }


    /**
     * Enregistre une nouvelle réservation de circuit
     */
    public function store(Request $request)
    {
        $request->validate([
            'circuit_id' => 'required|exists:circuits,id',
            'date_debut' => 'required|date|after_or_equal:today',
            'nombre_personnes' => 'required|integer|min:1',
        ]);
        
        $circuit = Circuit::findOrFail($request->circuit_id);
        
        // Vérification si le circuit est actif
        if (!$circuit->est_actif) {
            return redirect()->back()->with('error', 'Ce circuit n\'est pas disponible actuellement.');
        }
        
        // Calcul de la date de fin en ajoutant la durée du circuit à la date de début
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = $dateDebut->copy()->addDays($circuit->duree - 1);
        
        // Vérification de la disponibilité du circuit
        if (!$circuit->estDisponible($dateDebut, $dateFin, $circuit->guide_id)) {
            return redirect()->back()->with('error', 'Ce circuit n\'est pas disponible aux dates sélectionnées.');
        }
        
        // Création de la réservation
        $reservation = new Reservation([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'statut' => 'en_attente',
            'user_id' => Auth::id(),
            'guide_id' => $circuit->guide_id,
            'nombre_personnes' => $request->nombre_personnes,
            'statut_paiement' => 'en_attente',
        ]);
        
        $circuit->reservations()->save($reservation);
        
        return redirect()->route('touriste.reservations.circuits.show', $reservation->id)
            ->with('success', 'Votre réservation a été enregistrée avec succès.');
    }

    /**
     * Affiche les détails d'une réservation spécifique
     */
    public function show($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('reservable_type', 'App\\Models\\Circuit')
            ->with(['reservable', 'guide'])
            ->firstOrFail();
            
        return view('reservations.circuits.show', compact('reservation'));
    }

    /**
     * Affiche le formulaire de modification d'une réservation
     */
    public function edit($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('reservable_type', 'App\\Models\\Circuit')
            ->where('statut', 'en_attente') // On ne peut modifier que les réservations en attente
            ->with('reservable')
            ->firstOrFail();
            
        return view('reservations.circuits.edit', compact('reservation'));
    }

    /**
     * Met à jour une réservation existante
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('reservable_type', 'App\\Models\\Circuit')
            ->where('statut', 'en_attente') // On ne peut modifier que les réservations en attente
            ->with('reservable')
            ->firstOrFail();
        
        $request->validate([
            'date_debut' => 'required|date|after_or_equal:today',
            'nombre_personnes' => 'required|integer|min:1',
        ]);
        
        $circuit = $reservation->reservable;
        
        // Calcul de la date de fin en ajoutant la durée du circuit à la date de début
        $dateDebut = Carbon::parse($request->date_debut);
        $dateFin = $dateDebut->copy()->addDays($circuit->duree - 1);
        
        // Vérification de la disponibilité du circuit (en excluant la réservation actuelle)
        $reservationsConflictuelles = Reservation::where('reservable_id', $circuit->id)
            ->where('reservable_type', 'App\\Models\\Circuit')
            ->where('id', '!=', $reservation->id)
            ->where('statut', 'approuvé')
            ->where('date_debut', '<=', $dateFin)
            ->where('date_fin', '>=', $dateDebut)
            ->exists();
            
        if ($reservationsConflictuelles) {
            return redirect()->back()->with('error', 'Ces dates ne sont pas disponibles pour ce circuit.');
        }
        
        // Mise à jour de la réservation
        $reservation->date_debut = $dateDebut;
        $reservation->date_fin = $dateFin;
        $reservation->nombre_personnes = $request->nombre_personnes;
        $reservation->save();
        
        return redirect()->route('touriste.reservations.circuits.show', $reservation->id)
            ->with('success', 'Votre réservation a été modifiée avec succès.');
    }

    /**
     * Annule une réservation
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'raison_annulation' => 'required|string|max:255',
        ]);
        
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('reservable_type', 'App\\Models\\Circuit')
            ->whereIn('statut', ['en_attente', 'approuvé']) // On ne peut annuler que les réservations en attente ou approuvées
            ->firstOrFail();
        
        $reservation->annuler($request->raison_annulation);
        
        return redirect()->route('touriste.reservations.circuits.index')
            ->with('success', 'Votre réservation a été annulée avec succès.');
    }
    
    /**
     * Affiche le formulaire de confirmation d'annulation
     */
    public function confirmCancel($id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('reservable_type', 'App\\Models\\Circuit')
            ->whereIn('statut', ['en_attente', 'approuvé']) // On ne peut annuler que les réservations en attente ou approuvées
            ->with('reservable')
            ->firstOrFail();
            
        return view('reservations.circuits.confirm-cancel', compact('reservation'));
    }
}   