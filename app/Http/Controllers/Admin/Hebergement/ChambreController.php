<?php

namespace App\Http\Controllers\Admin\Hebergement;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\Equipement;
use App\Models\Hebergement;
use Illuminate\Http\Request;

class ChambreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Nous allons utiliser les policies au lieu de simplement restreindre par rôle
        // $this->middleware('role:admin'); // Retiré car nous utilisons les policies
    }

    
    public function show($id)
    {
        $chambre = Chambre::with([
            'hebergement.typeHebergement',
            'hebergement.proprietaire',
            'equipements',
            'medias',
            'reservations' => function($query) {
                $query->with('user');
            }
        ])->findOrFail($id);
        
        // Vérifier si l'utilisateur a le droit de voir cette chambre
        $this->authorize('view', $chambre);
        
        return view('admin.chambres.show', compact('chambre'));
    }
    
    public function toggleVisibility($id)
    {
        $chambre = Chambre::findOrFail($id);
        
        // Vérifier si l'utilisateur a le droit de modifier la visibilité de cette chambre
        $this->authorize('toggleVisibility', $chambre);
        
        $chambre->est_visible = !$chambre->est_visible;
        $chambre->save();
        
        $status = $chambre->est_visible ? 'visible' : 'masquée';
        
        return redirect()->back()->with('success', "La chambre est maintenant $status.");
    }
    
    public function toggleAvailability($id)
    {
        $chambre = Chambre::findOrFail($id);
        
        // Vérifier si l'utilisateur a le droit de modifier la disponibilité de cette chambre
        $this->authorize('toggleAvailability', $chambre);
        
        $chambre->est_disponible = !$chambre->est_disponible;
        $chambre->save();
        
        $status = $chambre->est_disponible ? 'disponible' : 'indisponible';
        
        return redirect()->back()->with('success', "La chambre est maintenant $status.");
    }
    
   
}