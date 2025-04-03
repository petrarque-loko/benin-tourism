<?php

namespace App\Http\Controllers;

use App\Models\Chambre;
use App\Models\Hebergement;
use App\Models\TypeHebergement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class ChambreCatalogueController extends Controller
{
    /**
     * Affiche le catalogue des chambres et gère les recherches/filtres
     */
    public function index(Request $request)
    {
        // Vérification des autorisations - utilisation du Gate défini dans AuthServiceProvider
        if (!Gate::allows('filter-chambres')) {
            return $this->handleUnauthorized($request);
        }

        // Récupérer tous les types d'hébergement pour les filtres
        $typesHebergement = TypeHebergement::all();
        
        // Initialiser la requête
        $query = $this->buildChambreQuery($request);
        
        // Si c'est une requête AJAX, retourner uniquement les données
        if ($request->ajax()) {
            $chambres = $query->paginate(12);
            return response()->json([
                'html' => view('chambres.partials.liste-chambres', compact('chambres'))->render(),
                'pagination' => view('chambres.partials.pagination', compact('chambres'))->render(),
                'count' => $chambres->total()
            ]);
        }
        
        // Pour les requêtes normales, charger la page complète
        $chambres = $query->paginate(12);
        
        // Liste des types de chambres pour les filtres
        $typeChambres = Chambre::distinct('type_chambre')->pluck('type_chambre');
        
        // Liste des villes disponibles pour les filtres
        $villes = Hebergement::distinct('ville')->whereNotNull('ville')->pluck('ville');
        
        return view('chambres.catalogue', compact(
            'chambres', 
            'typesHebergement', 
            'typeChambres', 
            'villes'
        ));
    }
    
    /**
     * Affiche les détails d'une chambre spécifique
     */
    public function show(Request $request, $id)
    {
        $chambre = Chambre::with([
            'hebergement.typeHebergement',
            'equipements',
            'medias',
            'hebergement.medias',
            'hebergement.commentaires' => function($query) {
                $query->where('is_hidden', false)
                      ->with('user');
            }
        ])->findOrFail($id);
        
        // Vérification des autorisations avec la policy
        if (!Gate::allows('viewDetails', $chambre)) {
            return $this->handleUnauthorized($request);
        }
        
        // Vérifier si l'utilisateur est autorisé à réserver (policy ReservationPolicy)
        $userCanReserve = auth()->check() && Gate::allows('create', [Reservation::class]);
        
        // Pour les requêtes AJAX, retourner seulement les données nécessaires
        if ($request->ajax()) {
            return response()->json([
                'chambre' => $chambre,
                'userCanReserve' => $userCanReserve
            ]);
        }
        
        return view('chambres.show', compact('chambre', 'userCanReserve'));
    }
    
    /**
     * Endpoint de recherche pour le champ de recherche
     */
    public function search(Request $request)
    {
        if (!Gate::allows('filter-chambres')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $query = $this->buildChambreQuery($request);
        
        // Ajouter la recherche par terme si disponible
        if ($request->has('search_term') && !empty($request->search_term)) {
            $searchTerm = $request->search_term;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nom', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('hebergement', function($hq) use ($searchTerm) {
                      $hq->where('nom', 'like', '%' . $searchTerm . '%')
                         ->orWhere('ville', 'like', '%' . $searchTerm . '%')
                         ->orWhere('adresse', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        
        $chambres = $query->paginate(12);
        
        return response()->json([
            'chambres' => $chambres->items(),  // Renvoyer seulement les items, pas l'objet pagination complet
            'count' => $chambres->total(),
            'pagination' => [
                'current_page' => $chambres->currentPage(),
                'last_page' => $chambres->lastPage(),
                'per_page' => $chambres->perPage(),
            ]
        ]);
    }
    
    /**
     * Construit la requête de base pour les chambres avec tous les filtres
     */
    private function buildChambreQuery(Request $request)
    {
        $query = Chambre::with(['hebergement.typeHebergement',
                                 'equipements', 'medias', 
                                'hebergement.commentaires'
                                ])
                        ->where('est_visible', true)
                        ->where('est_disponible', true);
        
        // Filtrer par type d'hébergement
        if ($request->has('type_hebergement_id') && $request->type_hebergement_id) {
            $query->whereHas('hebergement', function($q) use ($request) {
                $q->where('type_hebergement_id', $request->type_hebergement_id);
            });
        }
        
        // Filtrer par type de chambre
        if ($request->has('type_chambre') && $request->type_chambre) {
            $query->where('type_chambre', $request->type_chambre);
        }
        
        // Filtrer par capacité (nombre de personnes)
        if ($request->has('capacite') && $request->capacite) {
            $query->where('capacite', '>=', $request->capacite);
        }
        
        // Filtrer par localisation (ville)
        if ($request->has('ville') && $request->ville) {
            $query->whereHas('hebergement', function($q) use ($request) {
                $q->where('ville', 'like', '%' . $request->ville . '%');
            });
        }
        
        // Filtrer par prix minimum et maximum
        if ($request->has('prix_min') && is_numeric($request->prix_min)) {
            $query->where('prix', '>=', $request->prix_min);
        }
        
        if ($request->has('prix_max') && is_numeric($request->prix_max)) {
            $query->where('prix', '<=', $request->prix_max);
        }
        
        // Filtrer par dates de disponibilité
        if ($request->has('date_debut') && $request->has('date_fin') && 
            $request->date_debut && $request->date_fin) {
            
            $dateDebut = Carbon::parse($request->date_debut);
            $dateFin = Carbon::parse($request->date_fin);
            
            // Exclure les chambres qui ont des réservations qui chevauchent la période
            $query->whereDoesntHave('reservations', function($q) use ($dateDebut, $dateFin) {
                $q->where(function($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                })->where('statut', '!=', 'annulee');
            });
        }

        // Filtrer par équipements
        if ($request->has('equipements') && is_array($request->equipements)) {
            foreach ($request->equipements as $equipementId) {
                $query->whereHas('equipements', function($q) use ($equipementId) {
                    $q->where('equipements.id', $equipementId);
                });
            }
        }
        
        // Tri par prix
        if ($request->has('tri') && in_array($request->tri, ['asc', 'desc'])) {
            $query->orderBy('prix', $request->tri);
        } else {
            $query->orderBy('prix', 'asc'); // Tri par défaut
        }
        
        return $query;
    }
    
    /**
     * Gère les réponses pour les utilisateurs non autorisés
     */
    private function handleUnauthorized(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(['error' => 'Unauth)orized'], 403);
        }
        
        abort(403, 'Cette action n\'est pas autorisée');
    }  
}