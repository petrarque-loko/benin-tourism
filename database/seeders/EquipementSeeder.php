<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipement;

class EquipementSeeder extends Seeder
{
    public function run()
    {
        $equipements = [
            ['nom' => 'Wi-Fi', 'icone' => 'wifi'],
            ['nom' => 'Télévision', 'icone' => 'tv'],
            ['nom' => 'Climatisation', 'icone' => 'air-conditioner'],
            ['nom' => 'Sèche-cheveux', 'icone' => 'hair-dryer'],
            ['nom' => 'Mini bar', 'icone' => 'drink'],
            ['nom' => 'Coffre-fort', 'icone' => 'safe'],
            ['nom' => 'Balcon', 'icone' => 'balcony'],
            ['nom' => 'Vue sur mer', 'icone' => 'beach'],
            ['nom' => 'Piscine', 'icone' => 'pool'],
            ['nom' => 'Parking', 'icone' => 'parking'],
            ['nom' => 'Salle de fitness', 'icone' => 'fitness'],
            ['nom' => 'Petit-déjeuner inclus', 'icone' => 'breakfast'],
        ];
        
        foreach ($equipements as $equipement) {
            Equipement::create($equipement);
        }
    }
}
