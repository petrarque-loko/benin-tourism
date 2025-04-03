<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphMany;



/**
 * @method MorphMany notifications()
 * @method MorphMany unreadNotifications()
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'role_id',
        'status',
        'activation_token',
        'email_verified_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'activation_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the documents for the user.
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    /**
     * Determine si l'utilisateur est un administrateur.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role->name === 'Administrateur';
    }

    /**
     * Determine si l'utilisateur est un guide touristique.
     *
     * @return bool
     */
    public function isGuide()
    {
        return $this->role->name === 'Guide Touristique';
    }

    /**
     * Determine si l'utilisateur est un propriétaire d'hébergement.
     *
     * @return bool
     */
    public function isProprietaire()
    {
        return $this->role->name === 'Propriétaire Hébergement';
    }

    /**
     * Determine si l'utilisateur est un touriste.
     *
     * @return bool
     */
    public function isTouriste()
    {
        return $this->role->name === 'Touriste';
    }

    /**
     * Determine si le compte de l'utilisateur est actif.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
   
    public function commentaires()
    {
        return $this->morphMany(Commentaire::class, 'commentable');
    }
    
    /**
     * Obtenir les commentaires faits par cet utilisateur.
     */
    public function commentairesFaits()
    {
        return $this->hasMany(Commentaire::class, 'user_id');
    }

    // Relation avec les hébergements dont l'utilisateur est propriétaire
    public function hebergements()
    {
        return $this->hasMany(Hebergement::class, 'proprietaire_id');
    }

    // Relation avec les réservations faites par l'utilisateur
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    // Relation avec les réservations pour lesquelles l'utilisateur est guide
    public function reservationsGuide()
    {
        return $this->hasMany(Reservation::class, 'guide_id');
    }

    
} 