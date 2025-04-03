<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Récupérer les rôles
        $touristeRole = Role::where('name', 'Touriste')->first();
        $guideRole = Role::where('name', 'Guide Touristique')->first();
        $proprietaireRole = Role::where('name', 'Propriétaire Hébergement')->first();
        
        // Statistiques des utilisateurs
        $totalUsers = User::count();
        $pendingCount = User::where('status', 'pending')->count();
        $touristesCount = User::where('role_id', $touristeRole->id)->where('status', 'active')->count();
        $guidesCount = User::where('role_id', $guideRole->id)->where('status', 'active')->count();
        $proprietairesCount = User::where('role_id', $proprietaireRole->id)->where('status', 'active')->count();
        
        // Utilisateurs récents en attente
        $recentPendingUsers = User::with('role')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Compteur de notifications non lues
        $unreadNotificationsCount = auth()->user()->unreadNotifications()->count();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'pendingCount',
            'touristesCount',
            'guidesCount',
            'proprietairesCount',
            'recentPendingUsers',
            'unreadNotificationsCount'
        ));
    }
}