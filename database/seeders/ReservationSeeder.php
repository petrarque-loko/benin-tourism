<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Chambre;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run()
    {
        // Sélectionner des utilisateurs (touristes)
        $touriste = User::find(13); // "Andre kim", rôle Touriste (ID 12)

        // Sélectionner des chambres existantes
        $chambres = Chambre::all(); // Récupère les 3 chambres existantes

        // Données des réservations
        $reservations = [
            [
                'date_debut' => Carbon::now()->addDays(1),
                'date_fin' => Carbon::now()->addDays(3),
                'statut' => 'en_attente',
                'raison_annulation' => null,
                'chambre_id' => 1,
            ],
            [
                'date_debut' => Carbon::now()->addDays(5),
                'date_fin' => Carbon::now()->addDays(7),
                'statut' => 'annulée',
                'raison_annulation' => 'Changement de plan',
                'chambre_id' => 2,
            ],
            [
                'date_debut' => Carbon::now()->addDays(10),
                'date_fin' => Carbon::now()->addDays(12),
                'statut' => 'confirmée',
                'raison_annulation' => null,
                'chambre_id' => 3,
            ],
            [
                'date_debut' => Carbon::now()->addDays(15),
                'date_fin' => Carbon::now()->addDays(17),
                'statut' => 'en_attente',
                'raison_annulation' => null,
                'chambre_id' => 1,
            ],
            [
                'date_debut' => Carbon::now()->addDays(20),
                'date_fin' => Carbon::now()->addDays(22),
                'statut' => 'annulée',
                'raison_annulation' => 'Indisponibilité',
                'chambre_id' => 2,
            ],
        ];

        // Créer les réservations
        foreach ($reservations as $data) {
            Reservation::create([
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'],
                'statut' => $data['statut'],
                'raison_annulation' => $data['raison_annulation'],
                'nombre_personnes' => 2,
                'user_id' => $touriste->id,
                'chambre_id' => $data['chambre_id'],
                'reservable_id' => $data['chambre_id'],
                'reservable_type' => 'App\\Models\\Chambre',
                'statut_paiement' => 'en_attente',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}