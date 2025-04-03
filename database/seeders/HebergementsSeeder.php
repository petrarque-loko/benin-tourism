<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HebergementsSeeder extends Seeder
{
    public function run()
    {
        $hebergements = [
            [
                'nom' => 'Hôtel Azalaï',
                'description' => 'Hôtel de luxe situé au cœur de Cotonou, offrant des chambres élégantes et des services haut de gamme. Parfait pour les voyageurs d\'affaires et les touristes exigeants.',
                'adresse' => 'Boulevard de la Marina, Cotonou',
                'prix_min' => 100.00,
                'prix_max' => 300.00,
                'disponibilite' => 1,
                'proprietaire_id' => 9, // ID d'un utilisateur existant (propriétaire)
                'type_hebergement_id' => 1, // Hôtel
                'latitude' => 6.3528,
                'longitude' => 2.3990,
                'ville' => 'Cotonou',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Auberge de Grand-Popo',
                'description' => 'Auberge charmante située près de la plage, idéale pour un séjour détente au bord de la mer. Profitez de l\'hospitalité locale et des couchers de soleil magnifiques.',
                'adresse' => 'Route des Pêches, Grand-Popo',
                'prix_min' => 30.00,
                'prix_max' => 80.00,
                'disponibilite' => 1,
                'proprietaire_id' => 9, // ID d'un utilisateur existant (propriétaire)
                'type_hebergement_id' => 2, // Auberge
                'latitude' => 6.2800,
                'longitude' => 1.8225,
                'ville' => 'Grand-Popo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Gîte de la Pendjari',
                'description' => 'Gîte rustique situé à proximité du Parc National de la Pendjari, parfait pour les amateurs de nature et de safari. Vivez une expérience authentique au plus près de la faune africaine.',
                'adresse' => 'Tanguiéta',
                'prix_min' => 50.00,
                'prix_max' => 120.00,
                'disponibilite' => 1,
                'proprietaire_id' => 9, // ID d'un utilisateur existant (propriétaire)
                'type_hebergement_id' => 3, // Gîte
                'latitude' => 10.9956,
                'longitude' => 1.4406,
                'ville' => 'Tanguiéta',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('hebergements')->insert($hebergements);
    }
}