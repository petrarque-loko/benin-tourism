<?php

namespace App\Http\Controllers\Admin\Hebergement;

use App\Http\Controllers\Controller;
use App\Models\Hebergement;
use App\Models\TypeHebergement;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class HebergementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Nous allons utiliser les policies au lieu de simplement restreindre par rôle
        // $this->middleware('role:admin'); // Retiré car nous utilisons les policies
    }
    
    public function index()
    {
        $this->authorize('viewAny', Hebergement::class);
        
        // Charger tous les hébergements avec leurs relations
        $hebergements = Hebergement::with(['typeHebergement', 'proprietaire'])->get();
        $typesHebergement = TypeHebergement::all();
        
        return view('admin.hebergements.index', compact('hebergements', 'typesHebergement'));
    }
    
    public function show($id)
    {
        $hebergement = Hebergement::with([
            'typeHebergement',
            'proprietaire',
            'chambres',
            'medias',
            'commentaires' => function($query) {
                $query->with('user');
            }
        ])->findOrFail($id);
        
        // Vérifier si l'utilisateur a le droit de voir cet hébergement
        $this->authorize('view', $hebergement);
        
        return view('admin.hebergements.show', compact('hebergement'));
    }
    
    public function toggleVisibility($id)
    {
        $hebergement = Hebergement::findOrFail($id);
        
        // Vérifier si l'utilisateur a le droit de mettre à jour cet hébergement
        $this->authorize('update', $hebergement);
        
        $hebergement->disponibilite = !$hebergement->disponibilite;
        $hebergement->save();
        
        $status = $hebergement->disponibilite ? 'disponible' : 'indisponible';
        
        return redirect()->back()->with('success', "L'hébergement est maintenant $status.");
    }
    
    public function hideComment($hebergementId, $commentId)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        $comment = $hebergement->commentaires()->findOrFail($commentId);
        
        // Vérifier si l'utilisateur a le droit de masquer ce commentaire
        $this->authorize('hide', $comment);
        
        $comment->is_hidden = !$comment->is_hidden;
        $comment->save();
        
        $status = $comment->is_hidden ? 'masqué' : 'visible';
        
        return redirect()->back()->with('success', "Le commentaire est maintenant $status.");
    }
    
    public function statistics()
    {
        // Vérifier si l'utilisateur a le droit d'accéder aux statistiques
        $this->authorize('view-statistics');
        
        // Statistiques globales des hébergements
        $totalHebergements = Hebergement::count();
        $hebergementsParType = Hebergement::selectRaw('type_hebergement_id, count(*) as total')
                                         ->groupBy('type_hebergement_id')
                                         ->with('typeHebergement')
                                         ->get();
        
        $hebergementsParVille = Hebergement::selectRaw('ville, count(*) as total')
                                          ->whereNotNull('ville')
                                          ->groupBy('ville')
                                          ->orderBy('total', 'desc')
                                          ->limit(10)
                                          ->get();
        
        return view('admin.hebergements.statistics', compact(
            'totalHebergements',
            'hebergementsParType',
            'hebergementsParVille'
        ));
    }


    public function disponibiliteParMois($year, $month)
    {
        // Récupérer les données de disponibilité pour chaque jour du mois
        $data = Hebergement::selectRaw('DAY(created_at) as day, disponibilite, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy('day', 'disponibilite')
            ->get();

        // Calculer le nombre de jours dans le mois
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // Initialiser des tableaux pour les disponibles et indisponibles
        $disponibles = array_fill(1, $daysInMonth, 0);
        $indisponibles = array_fill(1, $daysInMonth, 0);

        // Remplir les tableaux avec les données récupérées
        foreach ($data as $item) {
            $day = $item->day;
            if ($item->disponibilite) {
                $disponibles[$day] = $item->total;
            } else {
                $indisponibles[$day] = $item->total;
            }
        }

        // Retourner les données au format JSON
        return response()->json([
            'days' => range(1, $daysInMonth),
            'disponibles' => array_values($disponibles),
            'indisponibles' => array_values($indisponibles),
        ]);
    }
}

