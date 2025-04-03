<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Monuments historiques',
                'description' => 'Sites et bâtiments d\'importance historique et culturelle.'
            ],
            [
                'nom' => 'Parcs naturels',
                'description' => 'Espaces naturels protégés offrant une biodiversité unique.'
            ],
            [
                'nom' => 'Musées',
                'description' => 'Institutions présentant des collections d\'art, d\'histoire ou de science.'
            ],
            [
                'nom' => 'Plages',
                'description' => 'Zones côtières aménagées pour la baignade et les loisirs.'
            ],
            [
                'nom' => 'Sites religieux',
                'description' => 'Lieux de culte et sites spirituels importants.'
            ],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
}