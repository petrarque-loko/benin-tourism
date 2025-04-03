<?php

namespace Database\Seeders;

use App\Models\Commentaire;
use App\Models\SiteTouristique;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CommentaireSiteSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('fr_FR'); // Générateur en français

        // Récupérer tous les sites touristiques
        $sites = SiteTouristique::all();

        // Récupérer les utilisateurs touristes (role_id = 12)
        $touristes = User::where('role_id', 12)->get();

        // Vérifier qu'il y a assez d'utilisateurs
        if ($touristes->count() < 10) {
            throw new \Exception('Il faut au moins 10 utilisateurs touristes.');
        }

        // Liste de 10 commentaires attractifs (2-3 phrases)
        $commentairesAttractifs = [
            "Ce site est une merveille à découvrir ! Les paysages sont splendides et l’ambiance est apaisante.",
            "Une visite incroyable qui m’a marqué. Le guide était passionné et le lieu est plein d’histoire.",
            "Un endroit parfait pour une journée en famille. Les activités proposées sont variées et amusantes.",
            "J’ai été émerveillé par la beauté naturelle du site. C’est un trésor caché à explorer absolument !",
            "Une expérience culturelle enrichissante. Les locaux sont chaleureux et l’organisation est impeccable.",
            "Ce lieu offre des vues à couper le souffle. Idéal pour les amateurs de photo et de nature.",
            "Un site touristique qui vaut le détour ! L’histoire qu’il raconte est captivante et bien mise en valeur.",
            "J’ai adoré la tranquillité de cet endroit. Parfait pour se détendre tout en apprenant quelque chose.",
            "Une escapade mémorable dans un cadre magnifique. Les services sur place sont de grande qualité.",
            "Ce site m’a donné envie de revenir. Chaque coin réserve une surprise agréable à découvrir."
        ];

        foreach ($sites as $site) {
            // Sélectionner 10 utilisateurs uniques
            $utilisateursSelectionnes = $touristes->random(10);

            foreach ($utilisateursSelectionnes as $index => $utilisateur) {
                // Générer une note entre 3 et 5
                $note = $faker->numberBetween(3, 5);

                // Utiliser un commentaire unique de la liste
                $commentaireTexte = $commentairesAttractifs[$index];

                // Créer le commentaire
                Commentaire::create([
                    'contenu' => $commentaireTexte,
                    'note' => $note,
                    'user_id' => $utilisateur->id,
                    'commentable_id' => $site->id,
                    'commentable_type' => 'App\\Models\\SiteTouristique',
                    'is_hidden' => 0,
                ]);
            }
        }
    }
}