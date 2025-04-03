<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = auth()->user();
        
        if (!auth()->check() || !$user || !$user->isAdmin()) {
            // Si l'utilisateur n'est pas connecté ou n'est pas un admin
            return redirect()->route('admin.login')->with('error', 'Accès non autorisé.');
        }
    
        return $next($request);
    }
}