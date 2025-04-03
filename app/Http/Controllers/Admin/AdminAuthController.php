<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Vérifier si l'utilisateur existe, si le mot de passe est correct et si c'est un admin
        if (!$user || !Hash::check($request->password, $user->password) || !$user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => ['Les identifiants fournis sont incorrects ou vous n\'avez pas les droits d\'administrateur.'],
            ]);
        }

        // Authentification de l'utilisateur
        Auth::login($user, $request->remember);

        // Créer un token Sanctum si nécessaire pour les API
        // $token = $user->createToken('admin-token')->plainTextToken;

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}