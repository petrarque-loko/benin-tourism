<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraditionCoutume extends Model
{
    protected $table = 'traditions_coutumes';
    protected $fillable = [
        'titre',
        'resume',
        'contenu',
        'categorie_id',
    ];

    // Relation avec la catégorie
    public function categorie()
    {
        return $this->belongsTo(CategorieTradition::class, 'categorie_id');
    }

    // Relation polymorphique avec les médias
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}