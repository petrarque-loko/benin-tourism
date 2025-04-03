<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show()
    {
        return view('profile.show');
    }
    
    public function edit()
    {
        return view('profile.edit');
    }
    
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $this->validate($request, [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
        ]);
        
        $user->update([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
        ]);
        
        return redirect()->route('profile.show')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }
    
    public function showChangePasswordForm()
    {
        return view('profile.change-password');
    }
    
    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Le mot de passe actuel est incorrect.');
        }
        
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        
        return redirect()->route('profile.show')
            ->with('success', 'Votre mot de passe a été changé avec succès.');
    }
}