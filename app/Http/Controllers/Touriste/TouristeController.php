<?php

namespace App\Http\Controllers\Touriste;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class TouristeController extends Controller
{
    // TouristeController.php

    public function dashboard()
    {
        // Stats pour le touriste
        $totalReservations = Reservation::where('user_id', auth()->id())->count();
        $sitesVisites = Reservation::where('user_id', auth()->id())
                                ->where('statut', 'terminé')
                                ->count();
        
        // Réservations à venir
        $prochaines = Reservation::with(['reservable', 'guide'])
                                ->where('user_id', auth()->id())
                                ->where('statut', 'approuvé')
                                ->where('date_debut', '>', now())
                                ->orderBy('date_debut', 'asc')
                                ->take(5)
                                ->get();
        
        // Dernières réservations
        $dernieres = Reservation::with(['reservable', 'guide'])
                            ->where('user_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        return view('touriste.dashboard', compact(
            'totalReservations', 
            'sitesVisites', 
            'prochaines',
            'dernieres'
        ));
    }
}
