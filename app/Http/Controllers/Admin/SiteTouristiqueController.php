<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteTouristique;
use App\Models\Categorie;
use App\Models\Media;
use App\Models\Commentaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SiteTouristiqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = SiteTouristique::with('categorie')->get();
        $categories = Categorie::all();
        return view('admin.sites.index', compact('sites', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Categorie::all();
        return view('admin.sites.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'categorie_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $site = SiteTouristique::create($validated);
        
        // Upload and save images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('sites', 'public');
                
                Media::create([
                    'type' => 'image',
                    'url' => $path,
                    'mediable_id' => $site->id,
                    'mediable_type' => SiteTouristique::class
                ]);
            }
        }
        
        return redirect()->route('admin.sites.index')
            ->with('success', 'Site touristique créé avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SiteTouristique  $site
     * @return \Illuminate\Http\Response
     */
    public function show(SiteTouristique $site)
    {
        $site->load('categorie', 'medias', 'commentaires.user', 'reservations.user');
        return view('admin.sites.show', compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SiteTouristique  $site
     * @return \Illuminate\Http\Response
     */
    public function edit(SiteTouristique $site)
    {
        $categories = Categorie::all();
        $site->load('medias');
        return view('admin.sites.edit', compact('site', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SiteTouristique  $site
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SiteTouristique $site)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'localisation' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'categorie_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $site->update($validated);
        
        // Upload and save new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('sites', 'public');
                
                Media::create([
                    'type' => 'image',
                    'url' => $path,
                    'mediable_id' => $site->id,
                    'mediable_type' => SiteTouristique::class
                ]);
            }
        }
        
        return redirect()->route('admin.sites.index')
            ->with('success', 'Site touristique mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SiteTouristique  $site
     * @return \Illuminate\Http\Response
     */
    public function destroy(SiteTouristique $site)
    {
        // Delete associated images from storage
        foreach ($site->medias as $media) {
            Storage::disk('public')->delete($media->url);
            $media->delete();
        }
        
        $site->delete();
        
        return redirect()->route('admin.sites.index')
            ->with('success', 'Site touristique supprimé avec succès.');
    }
    
    /**
     * Remove an image from a tourist site.
     *
     * @param  \App\Models\Media  $media
     * @return \Illuminate\Http\Response
     */
    public function destroyMedia(Media $media)
    {
        $siteId = $media->mediable_id;
        
        Storage::disk('public')->delete($media->url);
        $media->delete();
        
        return redirect()->route('admin.sites.edit', $siteId)
            ->with('success', 'Image supprimée avec succès.');
    }
    
    /**
     * Hide/unhide a comment.
     *
     * @param  \App\Models\Commentaire  $commentaire
     * @return \Illuminate\Http\Response
     */
    public function toggleCommentVisibility(Commentaire $commentaire)
    {
        $commentaire->is_hidden = !$commentaire->is_hidden;
        $commentaire->save();
        
        return back()->with('success', 'Visibilité du commentaire modifiée avec succès.');
    }
    
    /**
     * Show all reservations for all sites.
     *
     * @return \Illuminate\Http\Response
     */
    public function allReservations(Request $request)
    {
        $query = \App\Models\Reservation::with(['user', 'reservable'])
            ->whereHasMorph('reservable', [SiteTouristique::class]);
        
        // Apply filters if provided
        if ($request->has('status') && $request->status) {
            $query->where('statut', $request->status);
        }
        
        if ($request->has('date_debut') && $request->date_debut) {
            $query->whereDate('date_debut', '>=', $request->date_debut);
        }
        
        if ($request->has('date_fin') && $request->date_fin) {
            $query->whereDate('date_fin', '<=', $request->date_fin);
        }
        
        $reservations = $query->latest()->paginate(15);
        
        return view('admin.sites.reservations', compact('reservations'));
    }
    
    /**
     * Show details of a specific reservation.
     *
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function showReservation(\App\Models\Reservation $reservation)
    {
        $reservation->load(['user', 'reservable']);
        
        return view('admin.sites.reservation-details', compact('reservation'));
    }
    
    /**
     * Cancel a reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservation  $reservation
     * @return \Illuminate\Http\Response
     */
    public function cancelReservation(Request $request, \App\Models\Reservation $reservation)
    {
        $validated = $request->validate([
            'raison_annulation' => 'required|string'
        ]);
        
        $reservation->statut = 'annulé';
        $reservation->raison_annulation = $validated['raison_annulation'];
        $reservation->save();
        
        // Notify the tourist and guide
        $touriste = $reservation->user;
        $site = $reservation->reservable;
        
        // Notification logic (using Laravel's notification system)
        $touriste->notify(new \App\Notifications\ReservationCancelled($reservation));
        
        // If there's a guide involved, notify them too
        if ($reservation->guide_id) {
            $guide = \App\Models\User::find($reservation->guide_id);
            $guide->notify(new \App\Notifications\ReservationCancelled($reservation));
        }
        
        return redirect()->route('admin.sites.reservations')
            ->with('success', 'Réservation annulée avec succès.');
    }
}