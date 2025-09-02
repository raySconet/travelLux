<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categories';

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function courtCases()
    {
        return $this->hasMany(CourtCase::class);
    }
}
