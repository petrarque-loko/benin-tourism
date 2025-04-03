<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\User; // <== Ajoute cette ligne

class UpdateUserLastLogin
{
    public function handle(Login $event)
    {
        $user = $event->user;

        if ($user instanceof User) { // Vérifier que $user est bien un modèle User
            $user->last_login_at = now();
            $user->save();
        }
    }
}
