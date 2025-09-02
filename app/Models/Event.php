<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'title',
        'categoryId',
        'user_id',
        'date_from',
        'date_to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to a category
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }
}
