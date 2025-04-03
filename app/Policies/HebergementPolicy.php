<?php

namespace App\Policies;

use App\Models\Hebergement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HebergementPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des hébergements
     */
    public function viewAny(User $user)
    {
        // Les propriétaires peuvent voir la liste de leurs hébergements
        // Les admins peuvent voir tous les hébergements
        return $user->isProprietaire() || $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut voir les détails d'un hébergement spécifique
     * (infos générales, localisation, avis, commentaires, chambres)
     */
    public function view(User $user, Hebergement $hebergement)
    {
        // Les propriétaires ne peuvent voir que leurs propres hébergements
        if ($user->isProprietaire()) {
            return $hebergement->proprietaire_id === $user->id;
        }
        
        // Les admins peuvent voir tous les hébergements
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut voir le catalogue public d'hébergements
     * Cette méthode est appelée sans authentification nécessaire
     */
    public function viewCatalogue(?User $user)
    {
        // Tous les utilisateurs (même non connectés) peuvent voir le catalogue
        return true;
    }
    
    /**
     * Détermine si l'utilisateur peut créer des hébergements
     */
    public function create(User $user)
    {
        // Seuls les propriétaires peuvent créer des hébergements
        return $user->isProprietaire();
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour un hébergement
     */
    public function update(User $user, Hebergement $hebergement)
    {
        // Les propriétaires ne peuvent mettre à jour que leurs propres hébergements
        if ($user->isProprietaire()) {
            return $hebergement->proprietaire_id === $user->id;
        }
        
        // Les admins peuvent mettre à jour tous les hébergements
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer un hébergement
     */
    public function delete(User $user, Hebergement $hebergement)
    {
        // Les propriétaires ne peuvent supprimer que leurs propres hébergements
        if ($user->isProprietaire()) {
            return $hebergement->proprietaire_id === $user->id;
        }
        
        // Les admins peuvent supprimer tous les hébergements
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut gérer les médias d'un hébergement
     */
    public function manageMedia(User $user, Hebergement $hebergement)
    {
        // Même logique que pour la mise à jour
        return $this->update($user, $hebergement);
    }
}