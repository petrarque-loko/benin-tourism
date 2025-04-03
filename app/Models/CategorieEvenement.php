<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieEvenement extends Model
{
    protected $table = 'categories_evenements';
    protected $fillable = ['nom'];
}