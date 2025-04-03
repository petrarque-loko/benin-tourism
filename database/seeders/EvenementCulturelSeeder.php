<?php

namespace Database\Seeders;

use App\Models\EvenementCulturel;
use Illuminate\Database\Seeder;

class EvenementCulturelSeeder extends Seeder
{
    public function run()
    {
        $evenements = [
            [
                'titre' => 'Festival Wémèxwé 2025',
                'description' => 'Une célébration identitaire dans la commune des Aguégués, mettant en avant les arts et la culture Wémè avec des spectacles et des expositions artisanales.',
                'lieu' => 'Goukon, Aguégués, Zou, Benin',
                'latitude' => 6.407, // Approximation pour Aguégués
                'longitude' => 2.826,
                'date_debut' => '2025-04-15 09:00:00',
                'date_fin' => '2025-04-19 18:00:00',
                'categorie_id' => 1, // Festival
                'medias' => [
                    '/images/evenements/wemexwe_2025_1.jpg',
                    '/images/evenements/wemexwe_2025_2.jpg',
                    '/images/evenements/wemexwe_2025_3.jpg',
                    '/images/evenements/wemexwe_2025_4.jpg',
                ],
            ],
            [
                'titre' => 'Festi Œil d’Afrique',
                'description' => 'Un festival artistique à Cotonou axé sur le développement par l’art, avec des défilés de mode et des projections culturelles.',
                'lieu' => 'Cotonou, Littoral, Benin',
                'latitude' => 6.3652,
                'longitude' => 2.4183,
                'date_debut' => '2025-05-10 10:00:00',
                'date_fin' => '2025-05-12 22:00:00',
                'categorie_id' => 1,
                'medias' => [
                    '/images/evenements/festi_oeil_1.jpg',
                    '/images/evenements/festi_oeil_2.jpg',
                    '/images/evenements/festi_oeil_3.jpg',
                    '/images/evenements/festi_oeil_4.jpg',
                ],
            ],
            [
                'titre' => 'Festival International des Arts du Bénin (FInAB)',
                'description' => 'Un grand rendez-vous artistique au Palais des Congrès, mêlant spectacles, rencontres et immersion culturelle.',
                'lieu' => 'Palais des Congrès, Cotonou, Littoral, Benin',
                'latitude' => 6.3652,
                'longitude' => 2.4183, // Position approximative du centre de Cotonou
                'date_debut' => '2025-06-01 08:00:00',
                'date_fin' => '2025-06-10 20:00:00',
                'categorie_id' => 1,
                'medias' => [
                    '/images/evenements/finab_2025_1.jpg',
                    '/images/evenements/finab_2025_2.jpg',
                    '/images/evenements/finab_2025_3.jpg',
                    '/images/evenements/finab_2025_4.jpg',
                ],
            ],
            [
                'titre' => 'Soirée d’Humour à Lokossa',
                'description' => 'Une soirée de rires avec des humoristes locaux et internationaux dans une ambiance conviviale.',
                'lieu' => 'Lokossa, Mono, Benin',
                'latitude' => 7.42,
                'longitude' => 2.03,
                'date_debut' => '2025-07-05 19:00:00',
                'date_fin' => '2025-07-05 23:00:00',
                'categorie_id' => 2, // Spectacle
                'medias' => [
                    '/images/evenements/humour_lokossa_1.jpg',
                    '/images/evenements/humour_lokossa_2.jpg',
                    '/images/evenements/humour_lokossa_3.jpg',
                    '/images/evenements/humour_lokossa_4.jpg',
                ],
            ],
            [
                'titre' => 'Concert Nord Bénin 2025',
                'description' => 'Une série de concerts gratuits mettant en vedette des artistes locaux au cœur de Parakou.',
                'lieu' => 'Place Tabera, Parakou, Borgou, Benin',
                'latitude' => 9.33,
                'longitude' => 2.63,
                'date_debut' => '2025-08-15 20:00:00',
                'date_fin' => '2025-08-17 02:00:00',
                'categorie_id' => 3, // Concert
                'medias' => [
                    '/images/evenements/concert_parakou_1.jpg',
                    '/images/evenements/concert_parakou_2.jpg',
                    '/images/evenements/concert_parakou_3.jpg',
                    '/images/evenements/concert_parakou_4.jpg',
                ],
            ],
            [
                'titre' => 'Exposition Photo - Kolawolé Atcho',
                'description' => 'Une exposition captivante explorant le quotidien passé et présent à travers les clichés du photographe Kolawolé Atcho.',
                'lieu' => 'Haie Vive, Cotonou, Littoral, Benin',
                'latitude' => 6.36,
                'longitude' => 2.44,
                'date_debut' => '2025-09-01 18:00:00',
                'date_fin' => '2025-10-15 17:00:00',
                'categorie_id' => 4, // Exposition
                'medias' => [
                    '/images/evenements/expo_photo_1.jpg',
                    '/images/evenements/expo_photo_2.jpg',
                    '/images/evenements/expo_photo_3.jpg',
                    '/images/evenements/expo_photo_4.jpg',
                ],
            ],
            [
                'titre' => 'Fête des Masques Vodoun',
                'description' => 'Une célébration traditionnelle des masques vodoun avec danses et rituels à Ouidah.',
                'lieu' => 'Ouidah, Zou, Benin',
                'latitude' => 6.35,
                'longitude' => 2.78,
                'date_debut' => '2025-10-10 09:00:00',
                'date_fin' => '2025-10-11 18:00:00',
                'categorie_id' => 5, // Tradition
                'medias' => [
                    '/images/evenements/masques_vodoun_1.jpg',
                    '/images/evenements/masques_vodoun_2.jpg',
                    '/images/evenements/masques_vodoun_3.jpg',
                    '/images/evenements/masques_vodoun_4.jpg',
                ],
            ],
            [
                'titre' => 'Salon de l’Artisanat Béninois',
                'description' => 'Un salon mettant en avant les créations artisanales du Bénin, avec ateliers et ventes.',
                'lieu' => 'Porto-Novo, Ouémé, Benin',
                'latitude' => 6.48,
                'longitude' => 2.63,
                'date_debut' => '2025-11-05 10:00:00',
                'date_fin' => '2025-11-08 19:00:00',
                'categorie_id' => 6, // Artisanat
                'medias' => [
                    '/images/evenements/artisanat_2025_1.jpg',
                    '/images/evenements/artisanat_2025_2.jpg',
                    '/images/evenements/artisanat_2025_3.jpg',
                    '/images/evenements/artisanat_2025_4.jpg',
                ],
            ],
            [
                'titre' => 'Festival de Danse Traditionnelle',
                'description' => 'Un festival célébrant les danses traditionnelles béninoises avec des troupes de tout le pays.',
                'lieu' => 'Abomey, Zou, Benin',
                'latitude' => 7.20,
                'longitude' => 2.32,
                'date_debut' => '2025-12-01 14:00:00',
                'date_fin' => '2025-12-03 21:00:00',
                'categorie_id' => 7, // Danse
                'medias' => [
                    '/images/evenements/danse_trad_1.jpg',
                    '/images/evenements/danse_trad_2.jpg',
                    '/images/evenements/danse_trad_3.jpg',
                    '/images/evenements/danse_trad_4.jpg',
                ],
            ],
            [
                'titre' => 'Journée du Patrimoine Béninois',
                'description' => 'Une journée dédiée à la découverte du patrimoine culturel et historique du Bénin.',
                'lieu' => 'Cotonou, Littoral, Benin & Porto-Novo, Ouémé, Benin',
                'latitude' => 6.425, // Point médian approximatif entre Cotonou et Porto-Novo
                'longitude' => 2.525,
                'date_debut' => '2025-12-20 08:00:00',
                'date_fin' => '2025-12-20 18:00:00',
                'categorie_id' => 8, // Patrimoine
                'medias' => [
                    '/images/evenements/patrimoine_2025_1.jpg',
                    '/images/evenements/patrimoine_2025_2.jpg',
                    '/images/evenements/patrimoine_2025_3.jpg',
                    '/images/evenements/patrimoine_2025_4.jpg',
                ],
            ],
        ];

        foreach ($evenements as $data) {
            $evenement = EvenementCulturel::create([
                'titre' => $data['titre'],
                'description' => $data['description'],
                'lieu' => $data['lieu'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'date_debut' => $data['date_debut'],
                'date_fin' => $data['date_fin'],
                'categorie_id' => $data['categorie_id'],
            ]);

            foreach ($data['medias'] as $mediaUrl) {
                $evenement->medias()->create([
                    'type' => 'image', // Valeur par défaut pour le type
                    'url' => $mediaUrl,
                ]);
            }
        }
    }
}
