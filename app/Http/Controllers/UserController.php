<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Circuit;
use App\Models\Hebergement;
use App\Models\Commentaire;


class UserController extends Controller
{
    // UserController.php

public function show($id)
{
    $user = User::findOrFail($id);
    
    // Si c'est un administrateur, rediriger
    if ($user->role_id == 9) { // 9 est Administrateur
        return redirect()->back()->with('error', 'Ce profil n\'est pas accessible.');
    }
    
    // Récupérer les commentaires et notes du profil (si c'est un guide)
    if ($user->role_id == 10) { // Guide
        $commentaires = Commentaire::where('commentable_id', $user->id)
                                  ->where('commentable_type', 'App\\Models\\User')
                                  ->with('user')
                                  ->paginate(10);
        
        // Récupérer circuits proposés par ce guide
        $circuits = Circuit::where('guide_id', $user->id)->get();
        
        return view('users.guide-profile', compact('user', 'commentaires', 'circuits'));
    }
    
    // Si propriétaire d'hébergement
    if ($user->role_id == 11) {
        $hebergements = Hebergement::where('proprietaire_id', $user->id)->get();
        return view('users.hebergement-profile', compact('user', 'hebergements'));
    }
    
    // Si touriste
    return view('users.tourist-profile', compact('user'));
}

public function index () {
    return view('index');
}
}
