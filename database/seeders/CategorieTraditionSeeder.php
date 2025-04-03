<?php

namespace Database\Seeders;

use App\Models\CategorieTradition;
use Illuminate\Database\Seeder;

class CategorieTraditionSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['id' => 1, 'nom' => 'Rites et Cérémonies Traditionnelles'],
            ['id' => 2, 'nom' => 'Danses Traditionnelles'],
            ['id' => 3, 'nom' => 'Musiques Traditionnelles'],
            ['id' => 4, 'nom' => 'Festivals et Célébrations'],
            ['id' => 5, 'nom' => 'Coutumes Alimentaires'],
            ['id' => 6, 'nom' => 'Artisanat et Savoir-Faire'],
            ['id' => 7, 'nom' => 'Habillement et Mode Traditionnelle'],
        ];

        foreach ($categories as $categorie) {
            CategorieTradition::updateOrCreate(['id' => $categorie['id']], ['nom' => $categorie['nom']]);
        }
    }
}