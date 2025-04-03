<?php

namespace Database\Seeders;

use App\Models\Chambre;
use App\Models\Equipement;
use App\Models\Hebergement;
use Illuminate\Database\Seeder;

class ChambreSeeder extends Seeder
{
    public function run()
    {
        // Récupérer tous les hébergements
        $hebergements = Hebergement::all();

        // Vérifier s'il y a des hébergements
        if ($hebergements->isEmpty()) {
            $this->command->info('Aucun hébergement trouvé. Veuillez d\'abord créer des hébergements.');
            return;
        }

        // Définir les types de chambres
        $typesChambres = ['Standard', 'Twin', 'Suite', 'Deluxe', 'Familiale'];

        // Récupérer tous les équipements
        $equipements = Equipement::all();

        // Vérifier s'il y a des équipements
        if ($equipements->isEmpty()) {
            $this->command->info('Aucun équipement trouvé. Veuillez d\'abord créer des équipements.');
            return;
        }

        // Parcourir chaque hébergement
        foreach ($hebergements as $hebergement) {
            // Créer 5 chambres pour cet hébergement
            for ($i = 1; $i <= 5; $i++) {
                $typeChambre = $typesChambres[($i - 1) % count($typesChambres)];
                $numero = str_pad($i, 3, '0', STR_PAD_LEFT); // Ex: 001, 002, etc.

                // Créer la chambre
                $chambre = Chambre::create([
                    'numero' => $numero,
                    'nom' => $typeChambre . ' ' . $hebergement->nom,
                    'description' => 'Chambre ' . $typeChambre . ' confortable avec vue sur la ville.',
                    'type_chambre' => $typeChambre,
                    'capacite' => rand(1, 4), // Entre 1 et 4 personnes
                    'prix' => rand(50, 200), // Entre 50 et 200
                    'est_disponible' => true,
                    'est_visible' => true,
                    'hebergement_id' => $hebergement->id,
                ]);

                // Assigner des équipements aléatoires
                $equipementsAleatoires = $equipements->random(rand(2, 5)); // Entre 2 et 5 équipements
                $chambre->equipements()->attach($equipementsAleatoires->pluck('id'));
            }
        }

        $this->command->info('5 chambres ont été ajoutées pour chaque hébergement avec des équipements.');
    }
}