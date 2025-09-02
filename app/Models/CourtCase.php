<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourtCase extends Model
{
    protected $table = 'court_cases';

    public function todo()
    {
        return $this->hasMany(Todo::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_case');
    }

    // Belongs to a category
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    // A case belongs to a case stage
    public function caseStage()
    {
        return $this->belongsTo(CaseStage::class);
    }
}
