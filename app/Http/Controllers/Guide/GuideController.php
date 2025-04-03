<?php

namespace App\Http\Controllers\Guide;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Commentaire;
use App\Notifications\ReservationStatusChanged;

class GuideController extends Controller
{
    // GuideController.php

    public function reservations()
    {
        $reservations = Reservation::with(['user', 'reservable'])
                                ->where('guide_id', auth()->id())
                                ->orderBy('created_at', 'desc')
                                ->paginate(10);
                                
        return view('guide.reservations', compact('reservations'));
    }

    public function showReservation($id)
    {
        $reservation = Reservation::with(['user', 'reservable'])
                                ->where('guide_id', auth()->id())
                                ->findOrFail($id);
                                
        return view('guide.reservation-details', compact('reservation'));
    }

    public function updateReservationStatus(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:approuvé,rejeté,annulé',
            'raison_annulation' => 'required_if:statut,rejeté,annulé',
        ]);
        
        $reservation = Reservation::where('guide_id', auth()->id())
                                ->findOrFail($id);
                                
        $reservation->statut = $request->statut;
        
        if (in_array($request->statut, ['rejeté', 'annulé'])) {
            $reservation->raison_annulation = $request->raison_annulation;
        }
        
        $reservation->save();
        
        // Envoyer notification au touriste
        $reservation->user->notify(new ReservationStatusChanged($reservation));
        
        return redirect()->route('guide.reservations')
                        ->with('success', 'Statut de la réservation mis à jour avec succès.');
    }

    // GuideController.php

    public function dashboard()
    {
        // Stats pour le guide
        $totalReservations = Reservation::where('guide_id', auth()->id())->count();
        $reservationsApprouvees = Reservation::where('guide_id', auth()->id())
                                            ->where('statut', 'approuvé')
                                            ->count();
        $commentaires = Commentaire::where('commentable_id', auth()->id())
                                ->where('commentable_type', 'App\\Models\\User')
                                ->count();
        $noteMoyenne = Commentaire::where('commentable_id', auth()->id())
                                ->where('commentable_type', 'App\\Models\\User')
                                ->avg('note');
        
        // Réservations à venir
        $prochaines = Reservation::with('reservable')
                                ->where('guide_id', auth()->id())
                                ->where('statut', 'approuvé')
                                ->where('date_debut', '>', now())
                                ->orderBy('date_debut', 'asc')
                                ->take(5)
                                ->get();
        
        // Derniers commentaires
        $derniersCommentaires = Commentaire::where('commentable_id', auth()->id())
                                        ->where('commentable_type', 'App\\Models\\User')
                                        ->with('user')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
        
        return view('guide.dashboard', compact(
            'totalReservations', 
            'reservationsApprouvees', 
            'commentaires', 
            'noteMoyenne',
            'prochaines',
            'derniersCommentaires'
        ));
    }
}
