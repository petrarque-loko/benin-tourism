<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class TouristeSeeder extends Seeder
{
    public function run()
    {
        // Créer 10 utilisateurs avec le rôle "touriste" (role_id = 12)
        User::factory()->count(10)->create(['role_id' => 12]);
    }
}