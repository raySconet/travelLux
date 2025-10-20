<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoSection extends Model
{
    protected $table = 'todo_sections';

    protected $fillable = [
        'title',
        'description',
        'categoryId',
        'completeDate',
        'sectionOrder',
        'caseId',
    ];

    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($section) {
            if (is_null($section->sectionOrder)) {
                $maxOrder = self::where('caseId', $section->caseId)->max('sectionOrder');
                $section->sectionOrder = $maxOrder ? $maxOrder + 1 : 1;
            }
        });
    }

    // Belongs to a category
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categoryId', 'id');
    }
}
