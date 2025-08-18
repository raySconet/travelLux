<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'color',
        'user_id',
        'date_from',
        'date_to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
