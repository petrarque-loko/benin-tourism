<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDocument;
use App\Notifications\NewRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationApproved;
use App\Mail\RegistrationRejected;

class ManageUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    
    public function index()
    {
        $users = User::with('role')->paginate(15);
        return view('admin.users.index', compact('users'));
    }
    
    public function show($id)
    {
        $user = User::with(['role', 'documents'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Basculer entre actif et suspendu

        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();
        
        $action = $user->status === 'active' ? 'activé' : 'suspendu';
        
        return back()->with('success', "Le compte de l'utilisateur a été $action avec succès.");
    }
    
    public function pendingRegistrations()
    {
        $pendingUsers = User::whereHas('role', function($query) {
            $query->whereIn('name', ['Guide Touristique', 'Propriétaire Hébergement']);
        })->where('status', 'pending')->paginate(15);
        
        return view('admin.users.pending', compact('pendingUsers'));
    }
    
    public function reviewRegistration($id)
    {
        $user = User::with(['role', 'documents'])->findOrFail($id);
        
        // Marquer la notification comme lue
        auth()->user()->notifications()
            ->where('data->type', 'registration')
            ->where('data->notifiable_id', $user->id)
            ->get()
            ->each(function($notification) {
                $notification->markAsRead();
            });
            
        return view('admin.users.review', compact('user'));
    }
    
    public function approveRegistration($id)
    {
        $user = User::findOrFail($id);
        
        // Approuver tous les documents
        foreach ($user->documents as $document) {
            $document->status = 'approved';
            $document->save();
        }
        
        // Activer le compte
        $user->status = 'active';
        $user->save();
        
        // Envoyer un email de confirmation
        Mail::to($user->email)->send(new RegistrationApproved($user));
        
        return redirect()->route('admin.users.pending')
            ->with('success', "L'inscription de {$user->prenom} {$user->nom} a été approuvée avec succès.");
    }
    
    public function rejectRegistration(Request $request, $id)
    {
        $this->validate($request, [
            'rejection_reason' => 'required|string',
        ]);
        
        $user = User::findOrFail($id);
        
        // Rejeter tous les documents
        foreach ($user->documents as $document) {
            $document->status = 'rejected';
            $document->rejection_reason = $request->rejection_reason;
            $document->save();
        }

        // Suspendre le compte
        $user->status = 'rejected';
        $user->save();
        
        // Envoyer un email de rejet
        Mail::to($user->email)->send(new RegistrationRejected($user, $request->rejection_reason));
        
        // Supprimer l'utilisateur (optionnel)
        // $user->delete();
        
        return redirect()->route('admin.users.pending')
            ->with('success', "L'inscription de {$user->prenom} {$user->nom} a été rejetée.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
    
        // Supprimer les documents associés
        foreach ($user->documents as $document) {
            $document->delete();
        }
    
        // Supprimer l'utilisateur
        $user->delete();
    
        return redirect()->route('admin.users.index')
            ->with('success', "Le compte de {$user->prenom} {$user->nom} a été supprimé avec succès.");
    }
    
    
}