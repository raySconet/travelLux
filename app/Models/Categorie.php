<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categories';

    protected $fillable = ['categoryName', 'color'];

    public static function getActiveCategories()
    {
        return self::where('isDeleted', 0)->orderBy('categoryName')->get();
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function courtCases()
    {
        return $this->hasMany(CourtCase::class);
    }
}
