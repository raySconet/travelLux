<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'atty_initials',
        'stage_of_process',
        'client_name',
        'user_id',
        'categoryId',
        'date_from',
        'date_to',
        'isDeleted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Belongs to a category
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categoryId');
    }
}
