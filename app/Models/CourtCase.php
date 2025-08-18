<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtCase extends Model
{
    //
    public function todo()
    {
        return $this->hasMany(Todo::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_case');
    }
}
