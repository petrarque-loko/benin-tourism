<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieTradition extends Model
{
    protected $table = 'categories_traditions';
    protected $fillable = ['nom'];

    public function traditions()
    {
        return $this->hasMany(TraditionCoutume::class, 'categorie_id');
    }
}