<?php

namespace App\Http\Controllers;

use App\Models\SiteTouristique;
use App\Models\Circuit;
use App\Models\TraditionCoutume;
use App\Models\EvenementCulturel;
use App\Models\Chambre;
use App\Models\Commentaire;
use App\Models\Hebergement;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    public function index()
    {
        // Sites Touristiques : 4 éléments avec note moyenne et médias
        $sites = SiteTouristique::with('medias', 'commentaires')
            ->withAvg('commentaires', 'note') // Calcule la moyenne de la colonne 'note'
            ->take(4)
            ->get();

        // Circuits Touristiques : 4 éléments avec note moyenne et médias
        $circuits = Circuit::where('est_actif', true)
            ->with(['sitesTouristiques.medias', 'guide', 'commentaires'])
            ->withAvg('commentaires', 'note') // Calcule la moyenne de la colonne 'note'
            ->take(4)
            ->get();

        $circuits->each(function ($circuit) {
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

        // Traditions et Coutumes : 4 éléments avec note moyenne et médias
        $traditions = TraditionCoutume::with('medias')
            ->take(4)
            ->get();

        // Événements à venir : 4 éléments futurs avec note moyenne et médias
        $evenements = EvenementCulturel::with('medias')
            ->where('date_debut', '>', now()) // Filtre pour les événements futurs
            ->take(4)
            ->get();

        // Chambres : 4 éléments avec note moyenne et médias
        $chambres = Chambre::with('medias')
            ->take(4)
            ->get();

        // Témoignages : 2 commentaires par type d'élément
        $temoignages = [
            'sites' => Commentaire::where('commentable_type', SiteTouristique::class)
                ->where('is_hidden', false) // Exclut les commentaires masqués
                ->with('user') // Charge l'utilisateur pour afficher son nom
                ->take(4)
                ->get(),
            'hebergements' => Commentaire::where('commentable_type', Hebergement::class) // Suppose que Chambre = Hébergement
                ->where('is_hidden', false)
                ->with('user')
                ->take(4)
                ->get(),
            'circuits' => Commentaire::where('commentable_type', Circuit::class)
                ->where('is_hidden', false)
                ->with('user')
                ->take(4)
                ->get(),
        ];

        // Retourne les données à la vue 'accueil'
        return view('accueil', compact('sites', 'circuits', 'traditions', 'evenements', 'chambres', 'temoignages'));
    }
}