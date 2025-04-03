<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Création du rôle administrateur
        $adminRole = Role::create([
            'name' => 'Administrateur'
        ]);

        // Création du rôle guide touristique
        Role::create([
            'name' => 'Guide Touristique'
        ]);

        Role::create([
            'name' => 'Propiétaire d\'hébergement' 
        ]);

        // Création du rôle touriste
        Role::create([
            'name' => 'Touriste'
        ]);

        // Création d'un utilisateur admin par défaut
        User::create([
            'nom' => 'LOKO',
            'prenom' => 'Pétrarque',
            'email' => 'lokopetrarque2003@gmail.com',
            'password' => Hash::make('Silvere@2003#'),
            'role_id' => $adminRole->id,
            'status' => 'active',
        ]);
    }
}