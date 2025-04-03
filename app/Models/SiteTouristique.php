<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteTouristique extends Model
{
    use HasFactory;
    
    protected $table = 'sites_touristiques';
    
    protected $fillable = [
        'nom',
        'description',
        'localisation',
        'latitude',
        'longitude',
        'categorie_id'
    ];
    
    /**
     * Get the category that owns the tourist site.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
    
    /**
     * Get the media for the tourist site.
     */
    public function medias()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
    
    /**
     * Get the comments for the tourist site.
     */
    public function commentaires()
    {
        return $this->morphMany(Commentaire::class, 'commentable');
    }
    
    /**
     * Get the reservations for the tourist site.
     */
    public function reservations()
    {
        return $this->morphMany(Reservation::class, 'reservable');
    }


    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * Get average rating from comments.
     */
    public function getNoteMoyenneAttribute()
    {
        return $this->commentaires()->whereNotNull('note')->avg('note') ?? 0;
    }

   
}