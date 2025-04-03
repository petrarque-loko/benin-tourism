<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TypeHebergement;

class TypeHebergementSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['nom' => 'Hôtel', 'description' => 'Établissement offrant des chambres meublées en location'],
            ['nom' => 'Auberge', 'description' => 'Établissement simple offrant gîte et couvert'],
            ['nom' => 'Gîte', 'description' => 'Logement meublé, généralement dans un environnement rural'],
            ['nom' => 'Motel', 'description' => 'Établissement hôtelier situé le long des routes'],
            ['nom' => 'Résidence', 'description' => 'Ensemble d\'appartements meublés'],
            ['nom' => 'Chambre d\'hôtes', 'description' => 'Chambre meublée chez l\'habitant'],
            ['nom' => 'Villa', 'description' => 'Maison individuelle de luxe'],
            ['nom' => 'Appartement', 'description' => 'Logement indépendant dans un immeuble'],
        ];
        
        foreach ($types as $type) {
            TypeHebergement::create($type);
        }
    }
}
