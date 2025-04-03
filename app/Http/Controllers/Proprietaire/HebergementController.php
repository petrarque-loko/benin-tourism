<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Hebergement;
use App\Models\Media;
use App\Models\TypeHebergement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class HebergementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // La policy s'occupera de vérifier si l'utilisateur est propriétaire
        // plutôt que d'utiliser un middleware de rôle global
    }
    
    public function index()
    {
        // Utiliser la policy viewAny
        if (Gate::denies('viewAny', Hebergement::class)) {
            abort(403, 'Action non autorisée.');
        }
        
        $hebergements = auth()->user()->hebergements()->with('typeHebergement')->get();
        return view('proprietaire.hebergements.index', compact('hebergements'));
    }
    
    public function create()
    {
        // Utiliser la policy create
        if (Gate::denies('create', Hebergement::class)) {
            abort(403, 'Action non autorisée.');
        }
        
        $typesHebergement = TypeHebergement::all();
        return view('proprietaire.hebergements.create', compact('typesHebergement'));
    }
    
    public function store(Request $request)
    {
        // Utiliser la policy create
        if (Gate::denies('create', Hebergement::class)) {
            abort(403, 'Action non autorisée.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'prix_min' => 'required|numeric|min:0',
            'prix_max' => 'required|numeric|min:0|gte:prix_min',
            'type_hebergement_id' => 'required|exists:types_hebergement,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $validated['proprietaire_id'] = auth()->id();
        $validated['disponibilite'] = $request->has('disponibilite');
        
        $hebergement = Hebergement::create($validated);
        
        // Gérer les images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('hebergements', 'public');
                
                $hebergement->medias()->create([
                    'type' => 'image',
                    'url' => $path,
                ]);
            }
        }
        
        return redirect()->route('proprietaire.hebergements.index')
                         ->with('success', 'Hébergement créé avec succès.');
    }
    
    public function show(Hebergement $hebergement)
    {
        // Utiliser la policy view
        if (Gate::denies('view', $hebergement)) {
            abort(403, 'Action non autorisée.');
        }
        
        $hebergement->load([
            'typeHebergement',
            'chambres',
            'medias',
            'commentaires' => function($query) {
                $query->with('user');
            }
        ]);
        
        return view('proprietaire.hebergements.show', compact('hebergement'));
    }
    
    public function edit(Hebergement $hebergement)
    {
        // Utiliser la policy update
        if (Gate::denies('update', $hebergement)) {
            abort(403, 'Action non autorisée.');
        }
        
        $typesHebergement = TypeHebergement::all();
        return view('proprietaire.hebergements.edit', compact('hebergement', 'typesHebergement'));
    }
    
    public function update(Request $request, Hebergement $hebergement)
    {
        // Utiliser la policy update
        if (Gate::denies('update', $hebergement)) {
            abort(403, 'Action non autorisée.');
        }
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'prix_min' => 'required|numeric|min:0',
            'prix_max' => 'required|numeric|min:0|gte:prix_min',
            'type_hebergement_id' => 'required|exists:types_hebergement,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $validated['disponibilite'] = $request->has('disponibilite');
        
        $hebergement->update($validated);
        
        // Gérer les images
        if ($request->hasFile('images')) {
            // Vérifier si l'utilisateur peut gérer les médias
            if (Gate::allows('manageMedia', $hebergement)) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('hebergements', 'public');
                    
                    $hebergement->medias()->create([
                        'type' => 'image',
                        'url' => $path,
                    ]);
                }
            }
        }
        
        return redirect()->route('proprietaire.hebergements.index')
                         ->with('success', 'Hébergement mis à jour avec succès.');
    }
    
    public function destroy(Hebergement $hebergement)
    {
        // Utiliser la policy delete
        if (Gate::denies('delete', $hebergement)) {
            abort(403, 'Action non autorisée.');
        }
        
        // Supprimer les images associées
        foreach ($hebergement->medias as $media) {
            Storage::disk('public')->delete($media->url);
            $media->delete();
        }
        
        $hebergement->delete();
        
        return redirect()->route('proprietaire.hebergements.index')
                         ->with('success', 'Hébergement supprimé avec succès.');
    }
    
    public function deleteMedia(Request $request, Hebergement $hebergement, Media $media)
    {
        // Utiliser la policy manageMedia
        if (Gate::denies('manageMedia', $hebergement)) {
            abort(403, 'Action non autorisée.');
        }
        
        // Vérifier que le média appartient bien à cet hébergement
        if ($media->mediable_id !== $hebergement->id || $media->mediable_type !== Hebergement::class) {
            abort(404, 'Média non trouvé pour cet hébergement.');
        }
        
        // Supprimer le fichier du stockage
        Storage::disk('public')->delete($media->url);
        
        // Supprimer l'enregistrement du média
        $media->delete();
        
        // Vérifier si la requête attend une réponse JSON (AJAX)
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Image supprimée avec succès.',
            ], 200);
        }
        
        // Comportement par défaut pour les requêtes non-AJAX (optionnel)
        return redirect()->back()->with('success', 'Image supprimée avec succès.');
    }
}