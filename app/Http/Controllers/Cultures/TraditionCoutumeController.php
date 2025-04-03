<?php

namespace App\Http\Controllers\Cultures;

use App\Models\TraditionCoutume;
use App\Models\CategorieTradition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TraditionCoutumeController extends Controller
{
    /**
     * Affiche la liste des traditions et coutumes
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Récupération de toutes les traditions/coutumes avec leurs catégories
        $traditions = TraditionCoutume::with('categorie', 'medias')->get();
        
        // Récupération de toutes les catégories pour le filtre
        $categories = CategorieTradition::all();
        
        return view('traditions.index', [
            'traditions' => $traditions,
            'categories' => $categories
        ]);
    }

    /**
     * Affiche les détails d'une tradition ou coutume spécifique
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Récupération de la tradition/coutume avec sa catégorie et ses médias
        $tradition = TraditionCoutume::with(['categorie', 'medias'])->findOrFail($id);
        
        // Séparation des médias par type
        $images = $tradition->medias->where('type', 'image');
        $video = $tradition->medias->where('type', 'video')->first();
        
        return view('traditions.show', [
            'tradition' => $tradition,
            'images' => $images,
            'video' => $video
        ]);
    }
}