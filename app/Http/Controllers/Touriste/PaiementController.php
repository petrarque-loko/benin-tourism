<?php


namespace App\Http\Controllers\Touriste;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaiementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function initier($reservationId)
    {
        $reservation = auth()->user()->reservations()
                                  ->with('chambre.hebergement')
                                  ->findOrFail($reservationId);
        
        if ($reservation->statut_paiement === 'paye') {
            return redirect()->route('touriste.reservations.show', $reservation->id)
                             ->with('info', 'Cette réservation a déjà été payée.');
        }
        
        $montant = $reservation->prixTotal();
        
        // Générer une référence unique pour la transaction
        $reference = 'RESV-' . strtoupper(Str::random(8));
        
        return view('touriste.paiements.initier', compact('reservation', 'montant', 'reference'));
    }
    
    public function confirmer(Request $request, $reservationId)
    {
        $reservation = auth()->user()->reservations()->findOrFail($reservationId);
        
        // Normalement ici vous traiteriez les données renvoyées par KKiaPay
        // Pour ce prototype, nous allons simuler une confirmation de paiement réussie
        
        $validated = $request->validate([
            'transaction_id' => 'required|string',
            'reference' => 'required|string',
        ]);
        
        $reservation->statut_paiement = 'paye';
        $reservation->reference_paiement = $validated['transaction_id'];
        $reservation->statut = 'confirmee';
        $reservation->save();
        
        return redirect()->route('touriste.reservations.show', $reservation->id)
                         ->with('success', 'Paiement effectué avec succès. Votre réservation est confirmée.');
    }
    
    public function webhook(Request $request)
    {
        // Endpoint pour recevoir les notifications de KKiaPay
        // Dans un environnement réel, vous devriez vérifier la signature
        
        $payload = $request->all();
        
        // Traiter la notification
        if (isset($payload['reference']) && isset($payload['status'])) {
            $reference = $payload['reference'];
            $status = $payload['status'];
            
            // Rechercher la réservation correspondante
            $reservation = Reservation::where('reference_paiement', $reference)->first();
            
            if ($reservation) {
                if ($status === 'SUCCESS') {
                    $reservation->statut_paiement = 'paye';
                    $reservation->statut = 'confirmee';
                } elseif ($status === 'FAILED') {
                    $reservation->statut_paiement = 'echoue';
                }
                
                $reservation->save();
            }
        }
        
        return response()->json(['success' => true]);
    }
    
    public function historique()
    {
        $paiements = auth()->user()->reservations()
                                 ->where('statut_paiement', '!=', 'en_attente')
                                 ->with('chambre.hebergement')
                                 ->latest()
                                 ->get();
        
        return view('touriste.paiements.historique', compact('paiements'));
    }
}
