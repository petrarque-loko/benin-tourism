<?php

namespace App\Http\Controllers;

use App\Models\Circuit;
use App\Models\SiteTouristique;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CircuitController extends Controller
{
    /**
     * Affiche la liste des circuits touristiques
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $circuits = Circuit::where('est_actif', true)
            ->with(['sitesTouristiques.medias', 'guide'])
            ->get();

        $circuits->each(function ($circuit) {
            $circuit->note_moyenne = $circuit->noteMoyenne();
            $circuit->images_sites = $circuit->sitesTouristiques->map(function ($site) {
                $premierMedia = $site->medias->where('type', 'image')->first();
                return [
                    'site_id' => $site->id,
                    'site_nom' => $site->nom,
                    'media_url' => $premierMedia ? asset('storage/' . $premierMedia->url) : null
                ];
            })->filter(function ($item) {
                return !is_null($item['media_url']);
            });
        });

        return view('circuits.index', compact('circuits'));
    }

    /**
     * Affiche les détails d'un circuit spécifique
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Récupérer le circuit avec ses relations
        $circuit = Circuit::with([
            'sitesTouristiques.medias', 
            'sitesTouristiques.categorie',
            'guide',       
        ])->findOrFail($id);

        // Récupérer les commentaires du circuit
        $commentaires = Commentaire::where('commentable_id', $circuit->id)
            ->where('commentable_type', 'App\\Models\\Circuit')
            ->where('is_hidden', false)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculer la note moyenne
        $noteMoyenne = $circuit->noteMoyenne();

        // Récupérer tous les médias des sites touristiques inclus dans le circuit
        $allSiteMedias = collect();
        foreach ($circuit->sitesTouristiques as $site) {
            $allSiteMedias = $allSiteMedias->merge($site->medias);
        }

        // Regrouper les médias par type
        $mediasByType = $allSiteMedias->groupBy('type');
        
        // Préparer les données pour la vue
        $images = $mediasByType->get('image', collect());
        $videos = $mediasByType->get('video', collect());
        
        // Organiser les sites touristiques par ordre de visite
        $sitesOrdre = $circuit->sitesTouristiques->sortBy('pivot.ordre');

        return view('circuits.show', compact(
            'circuit', 
            'commentaires', 
            'noteMoyenne', 
            'images', 
            'videos', 
            'sitesOrdre'
        ));
    }
    
    /**
     * Ajouter un commentaire à un circuit
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addComment(Request $request, $id)
    {
        $circuit = Circuit::findOrFail($id);
        
        $validated = $request->validate([
            'contenu' => 'required|string|max:1000',
            'note' => 'required|integer|min:1|max:5',
        ]);
        
        $commentaire = new Commentaire([
            'contenu' => $validated['contenu'],
            'note' => $validated['note'],
            'user_id' => auth()->id(),
            'is_hidden' => false
        ]);
        
        $circuit->commentaires()->save($commentaire);
        
        return redirect()->back()->with('success', 'Votre commentaire a été ajouté avec succès.');
    }
    
    /**
     * Recherche de circuits par critères
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function searchAjax(Request $request)
    {
        $query = Circuit::where('est_actif', true);
        
        if ($request->has('difficulte') && $request->difficulte != '') {
            $query->where('difficulte', $request->difficulte);
        }
        
        if ($request->has('prix_min') && $request->has('prix_max')) {
            $query->whereBetween('prix', [$request->prix_min, $request->prix_max]);
        }
        
        if ($request->has('duree_max') && $request->duree_max != '') {
            $query->where('duree', '<=', $request->duree_max);
        }
        
        if ($request->has('site_touristique') && $request->site_touristique != '') {
            $query->whereHas('sitesTouristiques', function($q) use ($request) {
                $q->where('sites_touristiques.id', $request->site_touristique);
            });
        }
        
        $circuits = $query->with(['sitesTouristiques.medias', 'guide'])->get();
        
        $circuits->each(function ($circuit) {
            $circuit->note_moyenne = $circuit->noteMoyenne();
            $circuit->images_sites = $circuit->sitesTouristiques->map(function ($site) {
                $premierMedia = $site->medias->where('type', 'image')->first();
                return [
                    'site_id' => $site->id,
                    'site_nom' => $site->nom,
                    'media_url' => $premierMedia ? asset('storage/' . $premierMedia->url) : null
                ];
            })->filter(function ($item) {
                return !is_null($item['media_url']);
            });
        });
        
        return response()->json($circuits);
    }
}