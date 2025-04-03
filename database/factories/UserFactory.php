<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt(Str::random(10)), // Mot de passe aléatoire sécurisé
            'telephone' => $this->faker->phoneNumber,
            'adresse' => $this->faker->address,
            'role_id' => 12, // Valeur par défaut, écrasée par le seeder si spécifiée
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }
}