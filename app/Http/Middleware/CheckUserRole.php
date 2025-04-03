<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect('login');
        }

        // Vérifier si l'utilisateur a le rôle spécifié
        if (Auth::user()->role_id != $role) {
            // Rediriger ou renvoyer une erreur 403 (Forbidden)
            abort(403, 'Vous n\'avez pas les autorisations nécessaires.');
        }

        return $next($request);
    }
}