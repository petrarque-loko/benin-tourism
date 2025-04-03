<?php

namespace Database\Seeders;

use App\Models\Commentaire;
use App\Models\Hebergement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentaireHebergementSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR'); // Générateur en français

        // Récupérer tous les hébergements
        $hebergements = Hebergement::all();

        // Récupérer les utilisateurs touristes (role_id = 12)
        $touristes = User::where('role_id', 12)->get();

        // Vérifier qu'il y a assez d'utilisateurs
        if ($touristes->count() < 7) {
            throw new \Exception('Il faut au moins 7 utilisateurs touristes.');
        }

        // Liste de 15 commentaires attractifs (3-4 phrases)
        $commentairesAttractifs = [
            "Un séjour inoubliable dans cet hébergement ! Le personnel est accueillant et les chambres sont confortables. Idéal pour se détendre après une journée de visite.",
            "J’ai adoré mon séjour ici. L’emplacement est parfait, à proximité des sites touristiques. Le petit-déjeuner est délicieux et varié.",
            "Cet hébergement offre un excellent rapport qualité-prix. Les chambres sont propres et bien équipées. Le service est impeccable.",
            "Une expérience formidable ! Le cadre est magnifique et l’ambiance est chaleureuse. Je recommande vivement cet endroit.",
            "Parfait pour les familles ! Les enfants ont adoré la piscine et les activités proposées. Le personnel est aux petits soins.",
            "Un havre de paix loin de l’agitation. Les chambres sont spacieuses et décorées avec goût. Le restaurant propose des plats locaux savoureux.",
            "Je suis tombé sous le charme de cet hébergement. L’accueil est chaleureux et les conseils du personnel sont précieux pour découvrir la région.",
            "Un lieu idéal pour se ressourcer. La vue depuis la terrasse est à couper le souffle. Le spa est un vrai plus.",
            "Cet hébergement a dépassé mes attentes. Le design est moderne et élégant. Le service est de grande qualité.",
            "Une adresse à retenir ! L’emplacement est central et pratique. Les chambres sont confortables et bien insonorisées.",
            "J’ai passé un excellent séjour ici. Le personnel est attentionné et discret. Le petit-déjeuner est copieux et varié.",
            "Un cadre enchanteur pour des vacances réussies. Les jardins sont magnifiques et bien entretenus. La piscine est un vrai régal.",
            "Cet hébergement offre tout le confort nécessaire. Les chambres sont lumineuses et aérées. Le service de navette est très pratique.",
            "Une expérience authentique dans un cadre traditionnel. Le personnel est aux petits soins et les repas sont délicieux. Je reviendrai avec plaisir.",
            "Parfait pour un séjour romantique ! L’ambiance est intimiste et le service est personnalisé. La vue sur la mer est sublime."
        ];

        foreach ($hebergements as $hebergement) {
            // Sélectionner 7 utilisateurs uniques aléatoirement
            $utilisateursSelectionnes = $touristes->random(7);

            // Sélectionner 7 commentaires aléatoirement parmi les 15
            $commentairesSelectionnes = collect($commentairesAttractifs)->random(7);

            foreach ($utilisateursSelectionnes as $index => $utilisateur) {
                // Générer une note entre 3 et 5
                $note = $faker->numberBetween(3, 5);

                // Utiliser un commentaire de la sélection
                $commentaireTexte = $commentairesSelectionnes[$index];

                // Créer le commentaire
                Commentaire::create([
                    'contenu' => $commentaireTexte,
                    'note' => $note,
                    'user_id' => $utilisateur->id,
                    'commentable_id' => $hebergement->id,
                    'commentable_type' => 'App\\Models\\Hebergement',
                    'is_hidden' => 0,
                ]);
            }
        }
    }
}