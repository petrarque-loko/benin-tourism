<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nom',
        'description',
        'icone',
    ];
    
    // Relation avec les chambres
    public function chambres()
    {
        return $this->belongsToMany(Chambre::class, 'chambre_equipement');
    }
}
