<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReservationPolicy
{
    use HandlesAuthorization;

    /**
     * Détermine si l'utilisateur peut voir la liste des réservations
     */
    public function viewAny(User $user)
    {
        // Tous les utilisateurs authentifiés peuvent voir leurs propres réservations
        return true;
    }

    /**
     * Détermine si l'utilisateur peut voir les détails d'une réservation spécifique
     */
    public function view(User $user, Reservation $reservation)
    {
        // Les touristes ne peuvent voir que leurs propres réservations
        if ($user->isTouriste()) {
            return $reservation->user_id === $user->id;
        }
        
        // Les propriétaires peuvent voir les réservations liées à leurs hébergements
        if ($user->isProprietaire()) {
            return $reservation->chambre->hebergement->proprietaire_id === $user->id;
        }
        
        // Les guides peuvent voir les réservations pour lesquelles ils sont assignés
        if ($user->isGuide()) {
            return $reservation->guide_id === $user->id;
        }
        
        // Les admins peuvent voir toutes les réservations
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut créer des réservations
     */
    public function create(User $user)
    {
        // Tous les touristes peuvent créer des réservations
        return $user->isTouriste() || $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut mettre à jour une réservation
     */
    public function update(User $user, Reservation $reservation)
    {
        // Les touristes ne peuvent mettre à jour que leurs propres réservations
        // et seulement si elles sont en attente
        if ($user->isTouriste()) {
            return $reservation->user_id === $user->id && $reservation->statut === 'en_attente';
        }
        
        // Les propriétaires ne peuvent pas modifier les réservations
        if ($user->isProprietaire()) {
            return false;
        }
        
        // Les guides peuvent mettre à jour les réservations pour lesquelles ils sont assignés
        if ($user->isGuide()) {
            return $reservation->guide_id === $user->id;
        }
        
        // Les admins peuvent mettre à jour toutes les réservations
        return $user->isAdmin();
    }

    /**
     * Détermine si l'utilisateur peut annuler une réservation
     */
    public function cancel(User $user, Reservation $reservation)
    {
        // Les touristes ne peuvent annuler que leurs propres réservations
        // et seulement si elles ne sont pas déjà annulées ou terminées
        if ($user->isTouriste()) {
            return $reservation->user_id === $user->id && 
                   !in_array($reservation->statut, ['annulee', 'terminee']);
        }
        
        // Les propriétaires ne peuvent pas annuler les réservations
        if ($user->isProprietaire()) {
            return false;
        }
        
        // Les admins peuvent annuler toutes les réservations non terminées
        return $user->isAdmin() && $reservation->statut !== 'terminee';
    }

    /**
     * Détermine si l'utilisateur peut confirmer une réservation
     */
    public function confirm(User $user, Reservation $reservation)
    {
        // Seul le touriste qui a fait la réservation peut la confirmer
        return $user->isTouriste() && $reservation->user_id === $user->id && 
               $reservation->statut === 'en_attente';
    }

    /**
     * Détermine si l'utilisateur peut accéder aux statistiques des réservations
     */
    public function viewStatistics(User $user)
    {
        // Seuls les admins peuvent voir les statistiques globales
        return $user->isAdmin();
    }
}