<?php

namespace Database\Seeders;

use App\Models\CategorieEvenement;
use App\Models\CategorieTradition;
use App\Models\Commentaire;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            CategorieSeeder::class, 
            TypeHebergementSeeder::class,
            EquipementSeeder::class,
            ReservationSeeder::class,
            SitesTouristiquesSeeder::class,
            HebergementsSeeder::class,
            ChambreSeeder::class,
            TouristeSeeder::class,
            CommentaireHebergementSeeder::class,
            CategorieEvenement::class,
            EvenementCulturelSeeder::class,
            CategorieTradition::class,
            CircuitSeeder::class,
            // Autres seeders ici
        ]);
    }
}