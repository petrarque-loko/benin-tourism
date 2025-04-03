<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Hebergement;
use App\Models\Chambre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:11'); // Maintient le middleware de rôle pour une double sécurité
    }
    
    public function index()
    {
        // Vérifie si l'utilisateur a le droit de voir les réservations
        if (Gate::denies('viewAny', Reservation::class)) {
            abort(403, 'Accès non autorisé.');
        }
        
        $reservations = Reservation::whereHas('chambre.hebergement', function($query) {
            $query->where('proprietaire_id', auth()->id());
        })->with(['chambre.hebergement', 'user'])->latest()->get();
        
        return view('proprietaire.reservations.index', compact('reservations'));
    }
    
    public function show($id)
    {
        $reservation = Reservation::with(['chambre.hebergement', 'user'])->findOrFail($id);
        
        // Vérifie si l'utilisateur a le droit de voir cette réservation spécifique
        if (Gate::denies('view', $reservation)) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('proprietaire.reservations.show', compact('reservation'));
    }
    
    public function byHebergement($hebergementId)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        
        // Vérifie si l'utilisateur a le droit de voir cet hébergement
        if (Gate::denies('view', $hebergement)) {
            abort(403, 'Accès non autorisé.');
        }
        
        $reservations = Reservation::whereHas('chambre', function($query) use ($hebergementId) {
            $query->where('hebergement_id', $hebergementId);
        })->with(['chambre', 'user'])->latest()->get();
        
        return view('proprietaire.reservations.by_hebergement', compact('hebergement', 'reservations'));
    }
    
    public function byChambre($hebergementId, $chambreId)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        
        // Vérifie si l'utilisateur a le droit de voir cet hébergement
        if (Gate::denies('view', $hebergement)) {
            abort(403, 'Accès non autorisé.');
        }
        
        $chambre = Chambre::where('hebergement_id', $hebergementId)
                         ->findOrFail($chambreId);
        
        // Vérifie si l'utilisateur a le droit de voir cette chambre
        if (Gate::denies('view', $chambre)) {
            abort(403, 'Accès non autorisé.');
        }
        
        $reservations = $chambre->reservations()
                              ->with('user')
                              ->latest()
                              ->get();
        
        return view('proprietaire.reservations.by_chambre', compact('hebergement', 'chambre', 'reservations'));
    }
}