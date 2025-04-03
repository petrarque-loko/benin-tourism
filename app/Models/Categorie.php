<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;
    
    protected $fillable = ['nom', 'description'];
    
    // Si vous avez des sites touristiques liés à une catégorie
    public function sitesTouristiques()
    {
        return $this->hasMany(SiteTouristique::class);
    }
}