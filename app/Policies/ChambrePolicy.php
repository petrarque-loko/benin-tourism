<?php
namespace App\Policies;

use App\Models\Chambre;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChambrePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des chambres
     */
    public function viewAny(User $user)
    {
        // Les propriétaires et les admins peuvent voir la liste des chambres
        return $user->isProprietaire() || $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut voir le catalogue public des chambres
     * Cette méthode est appelée sans authentification nécessaire
     */
    public function viewCatalogue(?User $user)
    {
        // Tous les utilisateurs (même non connectés) peuvent voir le catalogue
        return true;
    }

    /**
     * Détermine si l'utilisateur peut voir les détails d'une chambre spécifique
     * (infos générales, galerie d'images, liste des réservations)
     */
    public function view(User $user, Chambre $chambre)
    {
        // Les propriétaires ne peuvent voir que les chambres de leurs hébergements
        if ($user->isProprietaire()) {
            return $chambre->hebergement->proprietaire_id === $user->id;
        }
        
        // Les admins peuvent voir toutes les chambres
        return $user->isAdmin();
    }

    /**
     * Détermine si n'importe quel utilisateur peut voir les détails publics d'une chambre
     * (infos sur l'hébergement, localisation, équipements, avis)
     */
    public function viewDetails(?User $user, Chambre $chambre)
    {
        // Tous les utilisateurs (même non connectés) peuvent voir les détails publics
        return true;
    }

    /**
     * Détermine si l'utilisateur peut créer des chambres
     */
    public function create(User $user, $hebergementId)
    {
        // Vérifier si l'hébergement appartient au propriétaire
        if ($user->isProprietaire()) {
            $hebergement = \App\Models\Hebergement::find($hebergementId);
            return $hebergement && $hebergement->proprietaire_id === $user->id;
        }
        
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une chambre
     */
    public function update(User $user, Chambre $chambre)
    {
        // Les propriétaires ne peuvent mettre à jour que les chambres de leurs hébergements
        if ($user->isProprietaire()) {
            return $chambre->hebergement->proprietaire_id === $user->id;
        }
        
        // Les admins peuvent mettre à jour toutes les chambres
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer une chambre
     */
    public function delete(User $user, Chambre $chambre)
    {
        // Les propriétaires ne peuvent supprimer que les chambres de leurs hébergements
        if ($user->isProprietaire()) {
            return $chambre->hebergement->proprietaire_id === $user->id;
        }
        
        // Les admins peuvent supprimer toutes les chambres
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut gérer les médias d'une chambre
     */
    public function manageMedia(User $user, Chambre $chambre)
    {
        // Même logique que pour la mise à jour
        return $this->update($user, $chambre);
    }

    /**
     * Détermine si l'utilisateur peut modifier la visibilité d'une chambre
     */
    public function toggleVisibility(User $user, Chambre $chambre)
    {
        // Même logique que pour la mise à jour
        return $this->update($user, $chambre);
    }

    /**
     * Détermine si l'utilisateur peut modifier la disponibilité d'une chambre
     */
    public function toggleAvailability(User $user, Chambre $chambre)
    {
        // Même logique que pour la mise à jour
        return $this->update($user, $chambre);
    }
}