<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'contenu',
        'note',
        'user_id',
        'commentable_id',
        'commentable_type',
        'is_hidden'
    ];

    protected $casts = [
        'note' => 'integer',
        'is_hidden' => 'boolean'
    ];
    
    /**
     * Get the parent commentable model.
     */
    public function commentable()
    {
        return $this->morphTo();
    }
    
    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Méthode pour masquer/afficher un commentaire
    public function toggleVisibilite()
    {
        $this->is_hidden = !$this->is_hidden;
        return $this->save();
    }
}