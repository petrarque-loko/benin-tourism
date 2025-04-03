<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeHebergement extends Model
{
    use HasFactory;
    
    protected $table = 'types_hebergement';
    
    protected $fillable = [
        'nom',
        'description',
    ];
    
    // Relation avec les hébergements
    public function hebergements()
    {
        return $this->hasMany(Hebergement::class, 'type_hebergement_id');
    }
}
