<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvenementCulturel extends Model
{
    protected $table = 'evenements_culturels';
    protected $fillable = [
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'lieu',
        'latitude',
        'longitude',
        'categorie_id',
    ];

    // Relation avec la catégorie
    public function categorie()
    {
        return $this->belongsTo(CategorieEvenement::class, 'categorie_id');
    }

    // Relation polymorphique avec les médias
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    // Relation polymorphique avec les réservations
    public function reservations()
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }
}