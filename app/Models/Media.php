<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    
    protected $table = 'medias';
    
    protected $fillable = [
        'type',
        'url',
        'mediable_id',
        'mediable_type'
    ];
    /**
     * Get the parent mediable model.
     */
    public function mediable()
    {
        return $this->morphTo();
    }
}


