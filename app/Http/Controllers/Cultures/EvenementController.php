<?php

namespace App\Http\Controllers\Cultures;

use App\Models\EvenementCulturel;
use App\Http\Controllers\Controller;
use App\Models\CategorieEvenement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EvenementController extends Controller
{
    /**
     * Affiche tous les événements à venir
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $evenements = EvenementCulturel::with('categorie', 'medias')->get();
        $categories = CategorieEvenement::all();
        return view('evenements.index', compact('evenements', 'categories'));
    }

    /**
     * Affiche les détails d'un événement spécifique
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Récupérer l'événement avec ses relations
        $evenement = EvenementCulturel::with(['categorie', 'medias'])
            ->findOrFail($id);
        
        return view('evenements.show', compact('evenement'));
    }
}