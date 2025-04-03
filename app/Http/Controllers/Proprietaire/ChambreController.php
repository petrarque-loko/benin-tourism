<?php

namespace App\Http\Controllers\Proprietaire;

use App\Http\Controllers\Controller;
use App\Models\Chambre;
use App\Models\Equipement;
use App\Models\Hebergement;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ChambreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:11');
    }
    
    public function index($hebergementId)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        
        // Autorisation
        $this->authorize('view', $hebergement);
        
        $chambres = $hebergement->chambres;
        
        return view('proprietaire.chambres.index', compact('hebergement', 'chambres'));
    }
    
    public function create($hebergementId)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        
        // Autorisation
        $this->authorize('create', [Chambre::class, $hebergementId]);
        
        $equipements = Equipement::all();
        
        return view('proprietaire.chambres.create', compact('hebergement', 'equipements'));
    }
    
    public function store(Request $request, $hebergementId)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        
        // Autorisation
        $this->authorize('create', [Chambre::class, $hebergementId]);
        
        $validated = $request->validate([
            'numero' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'type_chambre' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'equipements' => 'nullable|array',
            'equipements.*' => 'exists:equipements,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $validated['est_disponible'] = $request->has('est_disponible');
        $validated['est_visible'] = $request->has('est_visible');
        $validated['hebergement_id'] = $hebergement->id;
        
        $chambre = Chambre::create($validated);
        
        // Associer les équipements
        if (isset($validated['equipements'])) {
            $chambre->equipements()->attach($validated['equipements']);
        }
        
        // Gérer les images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('chambres', 'public');
                
                $chambre->medias()->create([
                    'type' => 'image',
                    'url' => $path,
                ]);
            }
        }
        
        return redirect()->route('proprietaire.hebergements.chambres.index', $hebergement->id)
                         ->with('success', 'Chambre créée avec succès.');
    }
    
    public function show($hebergementId, $id)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        $chambre = $hebergement->chambres()->with([
            'equipements', 
            'medias',
            'reservations' => function($q) {
                $q->with('user');
            }
        ])->findOrFail($id);
        
        // Autorisation
        $this->authorize('view', $chambre);
        
        return view('proprietaire.chambres.show', compact('hebergement', 'chambre'));
    }
    
    public function edit($hebergementId, $id)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        $chambre = $hebergement->chambres()->findOrFail($id);
        
        // Autorisation
        $this->authorize('update', $chambre);
        
        $equipements = Equipement::all();
        $chambreEquipements = $chambre->equipements->pluck('id')->toArray();
        
        return view('proprietaire.chambres.edit', compact(
            'hebergement', 
            'chambre', 
            'equipements', 
            'chambreEquipements'
        ));
    }
    
    public function update(Request $request, $hebergementId, $id)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        $chambre = $hebergement->chambres()->findOrFail($id);
        
        // Autorisation
        $this->authorize('update', $chambre);
        
        $validated = $request->validate([
            'numero' => 'nullable|string|max:255',
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'type_chambre' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'prix' => 'required|numeric|min:0',
            'equipements' => 'nullable|array',
            'equipements.*' => 'exists:equipements,id',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $validated['est_disponible'] = $request->has('est_disponible');
        $validated['est_visible'] = $request->has('est_visible');
        
        $chambre->update($validated);
        
        // Mettre à jour les équipements
        if (isset($validated['equipements'])) {
            $chambre->equipements()->sync($validated['equipements']);
        } else {
            $chambre->equipements()->detach();
        }
        
        // Gérer les images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('chambres', 'public');
                
                $chambre->medias()->create([
                    'type' => 'image',
                    'url' => $path,
                ]);
            }
        }
        
        return redirect()->route('proprietaire.hebergements.chambres.index', $hebergement->id)
                         ->with('success', 'Chambre mise à jour avec succès.');
    }
    
    public function destroy($hebergementId, $id)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        $chambre = $hebergement->chambres()->findOrFail($id);
        
        // Autorisation
        $this->authorize('delete', $chambre);
        
        // Vérifier s'il y a des réservations actives
        $reservationsActives = $chambre->reservations()
                                      ->whereIn('statut', ['confirmee', 'en_attente'])
                                      ->exists();
        
        if ($reservationsActives) {
            return redirect()->back()->with('error', 
                'Impossible de supprimer cette chambre car elle a des réservations actives.');
        }
        
        // Supprimer les images associées
        foreach ($chambre->medias as $media) {
            Storage::disk('public')->delete($media->url);
            $media->delete();
        }
        
        // Détacher les équipements
        $chambre->equipements()->detach();
        
        $chambre->delete();
        
        return redirect()->route('proprietaire.hebergements.chambres.index', $hebergement->id)
                         ->with('success', 'Chambre supprimée avec succès.');
    }
    
    public function toggleVisibility($hebergementId, $id)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        $chambre = $hebergement->chambres()->findOrFail($id);
        
        // Autorisation
        $this->authorize('toggleVisibility', $chambre);
        
        $chambre->est_visible = !$chambre->est_visible;
        $chambre->save();
        
        $status = $chambre->est_visible ? 'visible' : 'masquée';
        
        return redirect()->back()->with('success', "La chambre est maintenant $status.");
    }
    
    public function toggleAvailability($hebergementId, $id)
    {
        $hebergement = Hebergement::findOrFail($hebergementId);
        $chambre = $hebergement->chambres()->findOrFail($id);
        
        // Autorisation
        $this->authorize('toggleAvailability', $chambre);
        
        $chambre->est_disponible = !$chambre->est_disponible;
        $chambre->save();
        
        $status = $chambre->est_disponible ? 'disponible' : 'indisponible';
        
        return redirect()->back()->with('success', "La chambre est maintenant $status.");
    }
    
    public function deleteMedia(Request $request, $hebergementId, $chambreId, $mediaId)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Cette action nécessite une requête AJAX'
            ], 403);
        }

        try {
            $hebergement = Hebergement::findOrFail($hebergementId);
            $chambre = $hebergement->chambres()->findOrFail($chambreId);
            $media = $chambre->medias()->findOrFail($mediaId);

            $this->authorize('manageMedia', $chambre);

            if (!empty($media->url) && Storage::disk('public')->exists($media->url)) {
                Storage::disk('public')->delete($media->url);
            }

            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ressource non trouvée.'
            ], 404);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas l\'autorisation de supprimer ce média.'
            ], 403);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'image: ' . $e->getMessage()
            ], 500);
        }
    }

}