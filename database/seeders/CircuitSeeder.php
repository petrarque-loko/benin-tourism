<?php

namespace Database\Seeders;

use App\Models\Circuit;
use App\Models\SiteTouristique;
use App\Models\User;
use App\Models\Media;
use App\Models\Commentaire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CircuitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Guides disponibles (role_id = 10)
        $guides = User::where('role_id', 10)->get();

        // Sites touristiques disponibles
        $sites = SiteTouristique::all();

        // Utilisateurs pour les commentaires (role_id = 12)
        $users = User::where('role_id', 12)->get();

        // Liste des commentaires génériques
        $commentairesGeneriques = [
            "Une expérience inoubliable ! Le guide était très compétent et passionné.",
            "Les sites visités étaient magnifiques et bien préservés. Je recommande vivement.",
            "Un circuit bien organisé avec un bon équilibre entre culture et détente.",
            "J'ai adoré découvrir l'histoire locale grâce à ce circuit. Le guide a rendu l'expérience encore plus enrichissante.",
            "Les paysages étaient à couper le souffle. Une escapade parfaite pour se ressourcer.",
            "Un excellent rapport qualité-prix. Les activités proposées étaient variées et intéressantes.",
            "Le circuit m'a permis de rencontrer des artisans talentueux et d'en apprendre plus sur leur métier.",
            "Une immersion totale dans la culture béninoise. Je suis ravi de cette expérience.",
            "Le guide a su captiver notre attention avec des anecdotes fascinantes. Bravo !",
            "Les plages étaient sublimes et peu fréquentées. Un vrai paradis.",
            "J'ai particulièrement apprécié la visite des sites historiques. Très instructif.",
            "Un circuit qui convient à tous les âges. Ma famille a adoré.",
            "Les hébergements étaient confortables et bien situés. Rien à redire.",
            "Une organisation impeccable du début à la fin. Je n'hésiterai pas à refaire un circuit avec vous.",
            "Les repas inclus étaient délicieux et authentiques. Un vrai plus !"
        ];

        // Données des circuits basées sur les sites réels
        $circuitsData = [
            [
                'nom' => 'Circuit Historique d\'Abomey',
                'description' => 'Découvrez l\'histoire fascinante du royaume du Dahomey à travers les sites emblématiques d\'Abomey, berceau de la culture béninoise.',
                'duree' => 2,
                'prix' => 125.00,
                'difficulte' => 'Facile',
                'guide_id' => $guides->random()->id,
                'sites' => [6, 8, 18], // Palais Royal d'Abomey, Musée Historique d'Abomey, Village Souterrain d'Agongointo
            ],
            [
                'nom' => 'Route des Esclaves - Ouidah',
                'description' => 'Suivez l\'émouvant parcours de la Route des Esclaves à Ouidah, un voyage mémoriel à travers l\'histoire du commerce triangulaire.',
                'duree' => 1,
                'prix' => 85.00,
                'difficulte' => 'Facile',
                'guide_id' => $guides->random()->id,
                'sites' => [10, 11, 16, 20], // Temple des Pythons, Porte du Non-Retour, Forêt Sacrée de Kpassè, Plage de Ouidah
            ],
            [
                'nom' => 'Safari dans la Pendjari',
                'description' => 'Vivez une expérience unique au cœur du Parc National de la Pendjari, à la découverte de la faune sauvage africaine.',
                'duree' => 3,
                'prix' => 280.00,
                'difficulte' => 'Moyenne',
                'guide_id' => $guides->random()->id,
                'sites' => [7, 19], // Parc National de la Pendjari, Parc National du W
            ],
            [
                'nom' => 'Porto-Novo Culturel',
                'description' => 'Explorez la capitale administrative du Bénin et imprégnez-vous de son riche patrimoine culturel et architectural.',
                'duree' => 1,
                'prix' => 95.00,
                'difficulte' => 'Facile',
                'guide_id' => $guides->random()->id,
                'sites' => [13, 21, 22, 23], // Musée Ethnographique de Porto-Novo, Mosquée Centrale de Porto-Novo, Musée Honmè, Statue de Bio Guerra
            ],
            [
                'nom' => 'Détente sur la Côte Atlantique',
                'description' => 'Profitez des magnifiques plages du littoral béninois et détendez-vous face à l\'océan Atlantique.',
                'duree' => 2,
                'prix' => 110.00,
                'difficulte' => 'Facile',
                'guide_id' => $guides->random()->id,
                'sites' => [9, 14, 20], // Plage de Fidjrosse, Plage de Grand-Popo, Plage de Ouidah
            ],
            [
                'nom' => 'Cotonou Découverte',
                'description' => 'Explorez la capitale économique du Bénin, entre modernité et tradition, à travers ses sites emblématiques.',
                'duree' => 1, 
                'prix' => 75.00,
                'difficulte' => 'Facile',
                'guide_id' => $guides->random()->id,
                'sites' => [2, 15, 17, 25], // Place de l'Amazone, Cathédrale Notre-Dame de Cotonou, Musée de la Fondation Zinsou, Église Saint-Michel de Cotonou
            ],
            [
                'nom' => 'Circuit des Lacs',
                'description' => 'Naviguez sur les lacs du sud du Bénin et découvrez les villages lacustres traditionnels.',
                'duree' => 2,
                'prix' => 130.00,
                'difficulte' => 'Facile',
                'guide_id' => $guides->random()->id,
                'sites' => [12, 24], // Lac Ahémé, Lac Nokoué
            ],
            [
                'nom' => 'Spiritualité et Vodoun',
                'description' => 'Plongez dans l\'univers mystique du Vodoun, religion ancestrale du Bénin, à travers ses sites sacrés.',
                'duree' => 2,
                'prix' => 150.00,
                'difficulte' => 'Moyenne',
                'guide_id' => $guides->random()->id,
                'sites' => [10, 16, 11, 20], // Temple des Pythons, Forêt Sacrée de Kpassè, Porte du Non-Retour, Plage de Ouidah
            ],
        ];

        DB::beginTransaction();
        
        try {
            foreach ($circuitsData as $data) {
                // Créer le circuit
                $circuit = Circuit::create([
                    'nom' => $data['nom'],
                    'description' => $data['description'],
                    'duree' => $data['duree'],
                    'prix' => $data['prix'],
                    'difficulte' => $data['difficulte'],
                    'guide_id' => $data['guide_id'],
                    'est_actif' => true,
                ]);

                // Associer les sites avec ordre
                foreach ($data['sites'] as $ordre => $siteId) {
                    // Calculer une durée de visite réaliste en fonction du type de site
                    $site = SiteTouristique::find($siteId);
                    $dureeVisite = $this->calculerDureeVisite($site->categorie_id);
                    
                    $circuit->sitesTouristiques()->attach($siteId, [
                        'ordre' => $ordre + 1,
                        'duree_visite' => $dureeVisite // Durée de visite basée sur la catégorie du site
                    ]);
                }

                // Ajouter des commentaires avec des notes réalistes
                $nbCommentaires = rand(3, 8); // Entre 3 et 8 commentaires par circuit
                $commentairesSelectionnes = collect($commentairesGeneriques)->random($nbCommentaires)->all();
                $usersSelectionnes = $users->random($nbCommentaires);

                foreach ($commentairesSelectionnes as $index => $commentaire) {
                    Commentaire::create([
                        'contenu' => $commentaire,
                        'note' => rand(3, 5), // Notes entre 3 et 5 étoiles pour des avis positifs
                        'user_id' => $usersSelectionnes[$index]->id,
                        'commentable_id' => $circuit->id,
                        'commentable_type' => 'App\Models\Circuit',
                        'is_hidden' => false,
                        'created_at' => now()->subDays(rand(1, 90)), // Date aléatoire dans les 90 derniers jours
                        'updated_at' => now()->subDays(rand(0, 1)), // Mise à jour récente ou pas
                    ]);
                }
            }
            
            DB::commit();
            $this->command->info('Les circuits ont été créés avec succès !');
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Erreur lors de la création des circuits : ' . $e->getMessage());
        }
    }
    
    /**
     * Calcule une durée de visite réaliste en fonction de la catégorie du site
     * 
     * @param int $categorieId
     * @return int Durée en minutes
     */
    private function calculerDureeVisite($categorieId)
    {
        // Durées de visite estimées par catégorie (en minutes)
        $durees = [
            1 => [60, 90], // Sites historiques
            2 => [120, 240], // Parcs naturels
            3 => [60, 120], // Musées
            4 => [90, 180], // Plages
            5 => [30, 60], // Sites religieux
        ];
        
        // Utiliser la catégorie 1 par défaut si la catégorie n'existe pas
        if (!isset($durees[$categorieId])) {
            $categorieId = 1;
        }
        
        // Retourner une durée aléatoire dans la plage définie pour cette catégorie
        return rand($durees[$categorieId][0], $durees[$categorieId][1]);
    }
}