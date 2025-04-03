<?php

namespace App\Http\Controllers\Admin\Hebergement;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Nous allons utiliser les policies au lieu de simplement restreindre par rôle
        // $this->middleware('role:admin'); // Retiré car nous utilisons les policies
    }
    
    public function index(Request $request)
    {
        // Vérifier si l'utilisateur a le droit de voir la liste des réservations
        $this->authorize('viewAny', Reservation::class);
        
        // Initialiser la requête
        $query = Reservation::with(['chambre.hebergement', 'user']);
        
        // Filtrer par statut
        if ($request->has('statut') && $request->statut) {
            $query->where('statut', $request->statut);
        }
        
        // Filtrer par statut de paiement
        if ($request->has('statut_paiement') && $request->statut_paiement) {
            $query->where('statut_paiement', $request->statut_paiement);
        }
        
        // Filtrer par hébergement
        if ($request->has('hebergement_id') && $request->hebergement_id) {
            $query->whereHas('chambre', function($q) use ($request) {
                $q->where('hebergement_id', $request->hebergement_id);
            });
        }
        
        // Filtrer par utilisateur
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        
        // Filtrer par période
        if ($request->has('periode')) {
            $aujourd_hui = Carbon::today();
            switch ($request->periode) {
                case 'today':
                    $query->whereDate('created_at', $aujourd_hui);
                    break;
                case 'week':
                    $query->whereBetween('created_at', [$aujourd_hui->copy()->startOfWeek(), $aujourd_hui->copy()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', $aujourd_hui->month)
                          ->whereYear('created_at', $aujourd_hui->year);
                    break;
                case 'year':
                    $query->whereYear('created_at', $aujourd_hui->year);
                    break;
            }
        }
        
        // Tri
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        
        $reservations = $query->orderBy($sortField, $sortDirection)
                             ->paginate(15);
        
        $statuts = [
            'en_attente' => 'En attente',
            'confirmee' => 'Confirmée',
            'annulee' => 'Annulée',
            'terminee' => 'Terminée'
        ];
        
        $statutsPaiement = [
            'en_attente' => 'En attente',
            'paye' => 'Payé',
            'rembourse' => 'Remboursé',
            'ajustement_requis' => 'Ajustement requis',
            'echoue' => 'Échoué'
        ];
        
        return view('admin.reservations.index', compact(
            'reservations', 
            'statuts', 
            'statutsPaiement'
        ));
    }
    
    public function show($id)
    {
        $reservation = Reservation::with([
            'chambre.hebergement',
            'chambre.medias',
            'user'
        ])->findOrFail($id);
        
        // Vérifier si l'utilisateur a le droit de voir cette réservation
        $this->authorize('view', $reservation);
        
        return view('admin.reservations.show', compact('reservation'));
    }
    
    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Vérifier si l'utilisateur a le droit d'annuler cette réservation
        $this->authorize('cancel', $reservation);
        
        $validated = $request->validate([
            'raison_annulation' => 'required|string|max:255',
        ]);
        
        $reservation->statut = 'annulee';
        $reservation->raison_annulation = $validated['raison_annulation'];
        
        // Si paiement fait, marquer comme à rembourser
        if ($reservation->statut_paiement === 'paye') {
            $reservation->statut_paiement = 'rembourse';
        }
        
        $reservation->save();
        
        return redirect()->route('admin.reservations.show', $reservation->id)
                         ->with('success', 'Réservation annulée avec succès.');
    }
    
    public function statistics()
    {
        // Vérifier si l'utilisateur a le droit d'accéder aux statistiques des réservations
        $this->authorize('viewStatistics', Reservation::class);
        
        // Statistiques globales des réservations
        $totalReservations = Reservation::count();
        $reservationsConfirmees = Reservation::where('statut', 'confirmee')->count();
        $reservationsAnnulees = Reservation::where('statut', 'annulee')->count();
        $reservationsTerminees = Reservation::where('statut', 'terminee')->count();
        
        $reservationsParMois = Reservation::selectRaw('MONTH(created_at) as mois, YEAR(created_at) as annee, count(*) as total')
                                        ->whereYear('created_at', Carbon::now()->year)
                                        ->groupBy('mois', 'annee')
                                        ->orderBy('annee')
                                        ->orderBy('mois')
                                        ->get();
        
        $paiementTotal = Reservation::where('statut_paiement', 'paye')->sum(\DB::raw('DATEDIFF(date_fin, date_debut) * chambre_id'));
        
        return view('admin.reservations.statistics', compact(
            'totalReservations',
            'reservationsConfirmees',
            'reservationsAnnulees',
            'reservationsTerminees',
            'reservationsParMois',
            'paiementTotal'
        ));
    }
}