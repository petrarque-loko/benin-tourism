<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circuit extends Model
{
    use HasFactory;
    
    protected $table = 'circuits';
    
    protected $fillable = [
        'nom',
        'description',
        'duree',
        'prix',
        'difficulte',
        'guide_id',
        'est_actif'
    ];
    
    protected $casts = [
        'prix' => 'float',
        'duree' => 'integer',
        'est_actif' => 'boolean',
    ];
    
    /**
     * Récupère le guide associé au circuit
     */
    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
    
    /**
     * Les sites touristiques inclus dans ce circuit
     */
    public function sitesTouristiques()
    {
        return $this->belongsToMany(
            SiteTouristique::class, 
            'circuit_site_touristique', 
            'circuit_id', 
            'sites_touristique_id' // Corrigé ici (vérifie si c'est bien `site_touristique_id` ou `sites_touristique_id`)
        )->withPivot('ordre', 'duree_visite')->orderBy('ordre');
    }

    
    /**
     * Récupère les réservations pour ce circuit
     */
    public function reservations()
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }
    
    // Relation avec les commentaires
    public function commentaires()
    {
        return $this->morphMany(Commentaire::class, 'commentable');
    }
    
    
    /**
     * Vérifie si le circuit est disponible dans la période spécifiée
     */
    public function estDisponible($dateDebut, $dateFin, $guideId = null)
    {
        $query = Reservation::where('reservable_id', $this->id)
                           ->where('reservable_type', 'App\\Models\\Circuit')
                           ->where('statut', 'approuvé')
                           ->where('date_debut', '<=', $dateFin)
                           ->where('date_fin', '>=', $dateDebut);
        
        if ($guideId) {
            $query->where('guide_id', $guideId);
        }
        
        return !$query->exists();
    }
    
    /**
     * Calcule la note moyenne du circuit
     */
    public function noteMoyenne()
    {
        return $this->commentaires()->avg('note') ?: 0;
    }
}  