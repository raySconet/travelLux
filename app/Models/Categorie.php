<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'categories';

    protected $fillable = ['categoryName', 'color', 'isDeleted'];

    public static function getActiveCategories()
    {
        return self::where('isDeleted', 0)->orderBy('categoryName')->get();
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'categoryId');
    }

    public function courtCases()
    {
        return $this->hasMany(CourtCase::class, 'categoryId');
    }

    public function hasCasesOrEvents()
    {
        return $this->courtCases()->exists() || $this->events()->exists();
    }

    public function todoSections()
    {
        return $this->hasMany(TodoSection::class, 'categoryId', 'id');
    }
}
