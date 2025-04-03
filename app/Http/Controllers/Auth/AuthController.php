<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserDocument;
use App\Notifications\NewRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountActivation;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;
use Rinvex\Countries\CountryLoader;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        $roles = Role::whereIn('name', ['Touriste', 'Guide Touristique', 'Propriétaire Hébergement'])->get();

        // Charger les pays avec la librairie Rinvex\Countries
        return view('auth.register', compact('roles'));
        
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        // Validation de base pour tous les utilisateurs
        $validationRules = [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone_complet' => 'required|string|max:20',
            'adresse' => 'required|string',
            'role_id' => 'required|exists:roles,id',
        ];

        
        // Obtenir le rôle choisi avant validation pour déterminer les règles conditionnelles
        $role = Role::findOrFail($request->role_id);
        
        // Validation conditionnelle pour les documents si l'utilisateur est guide ou propriétaire
        if (in_array($role->name, ['Guide Touristique', 'Propriétaire Hébergement'])) {
            $validationRules['documents'] = 'required|array|min:1';
            $validationRules['documents.*'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:10240';
            $validationRules['document_types'] = 'required|array|min:1';
            $validationRules['document_types.*'] = 'required|string|in:Carte d\'identité,Passeport,Licence de guide,Preuve de propriété,Autre';
        }
        
        $this->validate($request, $validationRules);
        
        // Créer l'utilisateur
        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone_complet,
            'adresse' => $request->adresse,
            'role_id' => $request->role_id,
            'status' => 'pending',
            'activation_token' => Str::random(60),
        ]);

        // Si c'est un guide ou un propriétaire, gérer les documents
        if (in_array($role->name, ['Guide Touristique', 'Propriétaire Hébergement'])) {
            // Valider et stocker les documents
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $index => $document) {
                    $path = $document->store('documents', 'public');
                    
                    UserDocument::create([
                        'user_id' => $user->id,
                        'type' => $request->document_types[$index] ?? 'Autre',
                        'file_path' => $path,
                        'status' => 'pending'
                    ]);
                }
            }
            
            // Envoyer une notification aux administrateurs
            $this->notifyAdmins($user, $role);
            
            return redirect()->route('auth.register.success')
                ->with('message', 'Votre inscription a été soumise. Elle est en cours de vérification par notre équipe.');
        } else {
            // Pour les touristes, envoyer un mail d'activation
            Mail::to($user->email)->send(new AccountActivation($user));
            
            return redirect()->route('auth.register.success')
                ->with('message', 'Votre inscription a été effectuée avec succès. Veuillez vérifier votre email pour activer votre compte.');
        }
    }

    /**
     * Notifie tous les administrateurs d'une nouvelle inscription
     *
     * @param User $user
     * @param Role $role
     * @return void
     */
    private function notifyAdmins(User $user, Role $role)
    {
        // Récupérer tous les admins (ajustez selon votre logique d'attribution des rôles)
        $admins = User::whereHas('role', function($query) {
            $query->where('name', 'Administrateur');
        })->get();
        
        // Notifier chaque admin
        foreach ($admins as $admin) {
            $admin->notify(new NewRegistration($user, $role));
        }
    }

    public function activateAccount($token)
    {
        $user = User::where('activation_token', $token)->first();
        
        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Ce lien d\'activation n\'est pas valide.');
        }
        
        $user->status = 'active';
        $user->activation_token = null;
        $user->email_verified_at = now();
        $user->save();
        
        return redirect()->route('login')
            ->with('success', 'Votre compte a été activé avec succès. Vous pouvez maintenant vous connecter.');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        // Vérifier si l'utilisateur existe et est actif
        $user = User::where('email', $request->email)->first();
        
        if (!$user || $user->status !== 'active') {
            return back()->with('error', 'Ces identifiants sont incorrects ou votre compte n\'est pas actif.');
        }
        
        if (auth()->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(route('profile.show'));
        }
        
        return back()->with('error', 'Email ou mot de passe incorrect.');
    }

    public function registerSuccess()
    {
        return view('auth.register-success');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}