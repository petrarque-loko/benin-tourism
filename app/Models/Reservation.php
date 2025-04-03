<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'date_debut',
        'date_fin',
        'statut',
        'raison_annulation',
        'user_id',
        'guide_id',
        'chambre_id',
        'reservable_id',
        'reservable_type',
        'nombre_personnes',
        'statut_paiement',
        'reference_paiement',
    ];
    
    protected $dates = [
        'date_debut',
        'date_fin',
    ];

    public function getDateDebutAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getDateFinAttribute($value)
    {
        return Carbon::parse($value);
    }
    
    // Relation avec l'utilisateur (touriste)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Relation avec le guide
    public function guide()
    {
        return $this->belongsTo(User::class, 'guide_id');
    }
    
    
    // Relation polymorphique pour d'autres types de réservables
    public function reservable()
    {
        return $this->morphTo();
    }
    
    // Méthode pour calculer le prix total
    public function prixTotal()
    {
        if ($this->chambre) {
            $nombreJours = $this->date_debut->diffInDays($this->date_fin) + 1;
            return $this->chambre->prix * $nombreJours;
        }
        
        return 0;
    }
    
    // Méthode pour annuler une réservation
    public function annuler($raison)
    {
        $this->statut = 'annulee';
        $this->raison_annulation = $raison;
        return $this->save();
    }
    
    // Méthode pour confirmer le paiement
    public function confirmerPaiement($reference)
    {
        $this->statut = 'confirmee';
        $this->statut_paiement = 'paye';
        $this->reference_paiement = $reference;
        return $this->save();
    }
}

    
    
    
    