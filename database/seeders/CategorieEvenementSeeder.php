<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategorieEvenement;

class CategorieEvenementSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Festival',
            'Concert',
            'Exposition',
            'Danse',
            'Coutume',
            'Conférence',
            'Autre',
        ];

        foreach ($categories as $categorie) {
            CategorieEvenement::create(['nom' => $categorie]);
        }
    }
}