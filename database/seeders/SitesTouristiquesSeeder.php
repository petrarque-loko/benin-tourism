<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SitesTouristiquesSeeder extends Seeder
{
    public function run()
    {
        $sites = [
            [
                'nom' => 'Palais Royal d\'Abomey',
                'description' => 'Explorez le Palais Royal d\'Abomey, classé au patrimoine mondial de l\'UNESCO, témoignant de la grandeur du royaume du Dahomey. Admirez les bas-reliefs complexes et plongez dans des siècles d\'histoire.',
                'localisation' => 'Abomey',
                'latitude' => 7.1829,
                'longitude' => 1.9912,
                'categorie_id' => 1, // Monuments historiques
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Parc National de la Pendjari',
                'description' => 'Découvrez le havre de la faune ouest-africaine au Parc National de la Pendjari. Rencontrez des éléphants, des lions et des oiseaux rares lors d\'une aventure safari inoubliable.',
                'localisation' => 'Tanguiéta',
                'latitude' => 10.9956,
                'longitude' => 1.4406,
                'categorie_id' => 2, // Parcs naturels
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Musée Historique d\'Abomey',
                'description' => 'Plongez dans le passé au Musée Historique d\'Abomey, où des artefacts et des expositions donnent vie à l\'histoire du royaume du Dahomey.',
                'localisation' => 'Abomey',
                'latitude' => 7.1833,
                'longitude' => 1.9910,
                'categorie_id' => 3, // Musées
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Plage de Fidjrosse',
                'description' => 'Détendez-vous sur les sables dorés de la Plage de Fidjrosse, la plage la plus populaire de Cotonou. Nagez, bronzez et savourez des fruits de mer frais avec vue sur l\'océan.',
                'localisation' => 'Cotonou',
                'latitude' => 6.3479,
                'longitude' => 2.3923,
                'categorie_id' => 4, // Plages
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Temple des Pythons',
                'description' => 'Visitez le Temple des Pythons à Ouidah, un site unique où les pythons sont vénérés dans la tradition vaudou. Une expérience culturelle fascinante.',
                'localisation' => 'Ouidah',
                'latitude' => 6.3631,
                'longitude' => 2.0849,
                'categorie_id' => 5, // Sites religieux
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Porte du Non-Retour',
                'description' => 'Réfléchissez à l\'histoire à la Porte du Non-Retour, un mémorial émouvant dédié au commerce transatlantique des esclaves. Un incontournable pour comprendre le passé du Bénin.',
                'localisation' => 'Ouidah',
                'latitude' => 6.3547,
                'longitude' => 2.0851,
                'categorie_id' => 1, // Monuments historiques
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Lac Ahémé',
                'description' => 'Découvrez la beauté sereine du Lac Ahémé, un lac naturel idéal pour l\'observation des oiseaux, la pêche et une immersion dans la vie locale.',
                'localisation' => 'Possotomè',
                'latitude' => 6.4833,
                'longitude' => 1.9667,
                'categorie_id' => 2, // Parcs naturels
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Musée Ethnographique de Porto-Novo',
                'description' => 'Explorez le riche patrimoine culturel du Bénin au Musée Ethnographique de Porto-Novo, avec des expositions sur les traditions et artefacts locaux.',
                'localisation' => 'Porto-Novo',
                'latitude' => 6.4973,
                'longitude' => 2.6051,
                'categorie_id' => 3, // Musées
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Plage de Grand-Popo',
                'description' => 'Reposez-vous sur la Plage de Grand-Popo, un joyau caché avec des sables immaculés et des eaux propices à la baignade et à la détente.',
                'localisation' => 'Grand-Popo',
                'latitude' => 6.2800,
                'longitude' => 1.8225,
                'categorie_id' => 4, // Plages
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Cathédrale Notre-Dame de Cotonou',
                'description' => 'Admirez l\'architecture impressionnante de la Cathédrale Notre-Dame de Cotonou, un lieu de foi et d\'histoire au cœur de la ville.',
                'localisation' => 'Cotonou',
                'latitude' => 6.3579,
                'longitude' => 2.4366,
                'categorie_id' => 5, // Sites religieux
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Forêt Sacrée de Kpassè',
                'description' => 'Parcourez la mystique Forêt Sacrée de Kpassè à Ouidah, un site spirituel alliant beauté naturelle et signification culturelle.',
                'localisation' => 'Ouidah',
                'latitude' => 6.3660,
                'longitude' => 2.0830,
                'categorie_id' => 5, // Sites religieux
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Musée de la Fondation Zinsou',
                'description' => 'Découvrez l\'art africain contemporain au Musée de la Fondation Zinsou, présentant des œuvres d\'artistes locaux et internationaux.',
                'localisation' => 'Cotonou',
                'latitude' => 6.3528,
                'longitude' => 2.3990,
                'categorie_id' => 3, // Musées
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Village Souterrain d\'Agongointo',
                'description' => 'Explorez les mystères du Village Souterrain d\'Agongointo, un site historique avec une architecture unique en son genre.',
                'localisation' => 'Bohicon',
                'latitude' => 7.1783,
                'longitude' => 2.0667,
                'categorie_id' => 1, // Monuments historiques
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Parc National du W',
                'description' => 'Aventurez-vous dans le Parc National du W, un parc transfrontalier offrant des écosystèmes variés et des opportunités d\'observation de la faune.',
                'localisation' => 'Kandi',
                'latitude' => 11.1300,
                'longitude' => 2.9300,
                'categorie_id' => 2, // Parcs naturels
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Plage de Ouidah',
                'description' => 'Profitez de la Plage de Ouidah, où l\'histoire rencontre la mer. Un lieu parfait pour la réflexion et la détente.',
                'localisation' => 'Ouidah',
                'latitude' => 6.3540,
                'longitude' => 2.0840,
                'categorie_id' => 4, // Plages
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Mosquée Centrale de Porto-Novo',
                'description' => 'Visitez la Mosquée Centrale de Porto-Novo, une merveille architecturale et un centre de la foi islamique dans la capitale.',
                'localisation' => 'Porto-Novo',
                'latitude' => 6.4960,
                'longitude' => 2.6050,
                'categorie_id' => 5, // Sites religieux
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Musée Honmè',
                'description' => 'Remontez le temps au Musée Honmè, ancien palais du roi Toffa, aujourd\'hui un musée exposant des artefacts royaux et l\'histoire locale.',
                'localisation' => 'Porto-Novo',
                'latitude' => 6.4970,
                'longitude' => 2.6045,
                'categorie_id' => 3, // Musées
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Statue de Bio Guerra',
                'description' => 'Rendez hommage à Bio Guerra, héros national, à sa statue à Porto-Novo, symbole de résistance et de courage.',
                'localisation' => 'Porto-Novo',
                'latitude' => 6.4965,
                'longitude' => 2.6055,
                'categorie_id' => 1, // Monuments historiques
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Lac Nokoué',
                'description' => 'Explorez le Lac Nokoué, abritant le célèbre village sur pilotis de Ganvié. Un mélange unique de nature et de culture.',
                'localisation' => 'Cotonou',
                'latitude' => 6.4000,
                'longitude' => 2.4333,
                'categorie_id' => 2, // Parcs naturels
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nom' => 'Église Saint-Michel de Cotonou',
                'description' => 'Visitez l\'Église Saint-Michel de Cotonou, une église historique connue pour son architecture magnifique et sa communauté vibrante.',
                'localisation' => 'Cotonou',
                'latitude' => 6.3580,
                'longitude' => 2.4360,
                'categorie_id' => 5, // Sites religieux
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('sites_touristiques')->insert($sites);
    }
}