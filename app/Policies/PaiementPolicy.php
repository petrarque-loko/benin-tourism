<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaiementPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut initier un paiement pour une réservation
     */
    public function initier(User $user, Reservation $reservation)
    {
        // Seul le touriste qui a fait la réservation peut initier un paiement
        // et seulement si le paiement est en attente
        return $user->id === $reservation->user_id && 
               $user->isTouriste() &&
               $reservation->statut_paiement === 'en_attente';
    }

    /**
     * Détermine si l'utilisateur peut confirmer un paiement pour une réservation (via KkiaPay)
     */
    public function confirmer(User $user, Reservation $reservation)
    {
        // Seul le touriste qui a fait la réservation peut confirmer un paiement
        // et seulement si le paiement est en attente
        return $user->id === $reservation->user_id &&
               $user->isTouriste() &&
               $reservation->statut_paiement === 'en_attente';
    }

    /**
     * Détermine si l'utilisateur peut voir l'historique de ses paiements
     */
    public function historique(User $user)
    {
        // Tous les utilisateurs authentifiés peuvent voir leur historique de paiement
        return true;
    }
}

namespace App\Policies;

use App\Models\Commentaire;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentairePolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir tous les commentaires
     */
    public function viewAny(User $user)
    {
        // Tout utilisateur authentifié peut voir les commentaires
        return true;
    }

    /**
     * Détermine si l'utilisateur peut créer un commentaire
     */
    public function create(User $user)
    {
        // Seuls les touristes peuvent créer des commentaires
        return $user->isTouriste();
    }

    /**
     * Détermine si l'utilisateur peut masquer un commentaire
     */
    public function hide(User $user, Commentaire $commentaire)
    {
        // Les propriétaires peuvent masquer les commentaires sur leurs hébergements
        if ($user->isProprietaire()) {
            return $commentaire->hebergement->proprietaire_id === $user->id;
        }
        
        // Les admins peuvent masquer n'importe quel commentaire
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut supprimer un commentaire
     */
    public function delete(User $user, Commentaire $commentaire)
    {
        // Les utilisateurs peuvent supprimer leurs propres commentaires
        if ($user->isTouriste()) {
            return $commentaire->user_id === $user->id;
        }
        
        // Seuls les admins peuvent supprimer n'importe quel commentaire
        return $user->isAdmin();
    }
}