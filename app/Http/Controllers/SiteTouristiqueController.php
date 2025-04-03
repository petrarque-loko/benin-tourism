<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteTouristique;
use App\Models\Categorie;

class SiteTouristiqueController extends Controller
{
    // SiteTouristiqueController.php

public function index(Request $request)
{
    $categorie_id = $request->input('categorie_id');
    $search = $request->input('search');
    
    $query = SiteTouristique::with(['categorie', 'medias']);
    
    if ($categorie_id) {
        $query->where('categorie_id', $categorie_id);
    }
    
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('localisation', 'like', "%{$search}%");
        });
    }
    
    $sites = $query->paginate(40);
    $categories = Categorie::all();
    
    return view('sites.index', compact('sites', 'categories'));
}

public function show($id)
{
    $site = SiteTouristique::with(['categorie', 'medias', 'commentaires.user'])
                           ->findOrFail($id);
    
    // Calculer la note moyenne
    $noteAverage = $site->commentaires->avg('note');
    
    return view('sites.show', compact('site', 'noteAverage'));
}
}