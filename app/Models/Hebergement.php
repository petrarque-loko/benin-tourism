<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hebergement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'description',
        'adresse',
        'ville',
        'latitude', 
        'longitude',
        'prix_min',
        'prix_max',
        'disponibilite',
        'proprietaire_id',
        'type_hebergement_id',
    ];
    
    // Relation avec le propriétaire (User)
    public function proprietaire()
    {
        return $this->belongsTo(User::class, 'proprietaire_id');
    }
    
    // Relation avec le type d'hébergement
    public function typeHebergement()
    {
        return $this->belongsTo(TypeHebergement::class, 'type_hebergement_id');
    }
    
    // Relation avec les chambres
    public function chambres()
    {
        return $this->hasMany(Chambre::class);
    }
    
    // Relation avec les commentaires
    public function commentaires()
    {
        return $this->morphMany(Commentaire::class, 'commentable');
    }
    
    // Relation avec les médias (photos)
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
    
    // Méthode pour obtenir la note moyenne
    public function noteMoyenne()
    {
        return $this->commentaires()->avg('note') ?: 0;
    }
    
    // Méthode pour obtenir les chambres disponibles entre deux dates
    public function chambresDisponibles($dateDebut, $dateFin, $capacite = null)
    {
        $chambres = $this->chambres()
            ->where('est_di sponible', true)
            ->where('est_visible', true);
        
        if ($capacite) {
            $chambres->where('capacite', '>=', $capacite);
        }
        
        return $chambres->get()->filter(function ($chambre) use ($dateDebut, $dateFin) {
            return $chambre->estDisponible($dateDebut, $dateFin);
        });
    }
}