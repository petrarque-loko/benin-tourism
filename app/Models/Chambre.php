<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chambre extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'numero',
        'nom',
        'description',
        'type_chambre',
        'capacite',
        'prix',
        'est_disponible',
        'est_visible',
        'hebergement_id',
    ];
    
    // Relation avec l'hébergement
    public function hebergement()
    {
        return $this->belongsTo(Hebergement::class);
    }
    
    // Relation avec les équipements
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'chambre_equipement');
    }
    
    // Relation avec les réservations
    public function reservations()
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }
    
    // Relation avec les médias (photos)
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
    
    // Méthode pour vérifier la disponibilité entre deux dates
    public function estDisponible($dateDebut, $dateFin)
    {
        if (!$this->est_disponible || !$this->est_visible) {
            return false;
        }
        
        // Vérifier s'il n'y a pas de réservation qui chevauche cette période
        $reservationsConflictuelles = $this->reservations()
            ->where(function ($query) use ($dateDebut, $dateFin) {
                $query->where(function ($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                });
            })
            ->where('statut', '!=', 'annulee')
            ->count();
        
        return $reservationsConflictuelles === 0;
    }
}
