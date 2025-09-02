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

    public function clients()
    {
        return $this->hasMany(CaseClient::class, 'court_case_id', 'id');
    }

    public function thirdP()
    {
        return $this->hasMany(CaseThirdP::class, 'court_case_id', 'id');
    }
    public function firstP()
    {
        return $this->hasMany(CaseFirstP::class, 'court_case_id', 'id');
    }
    public function defenseCouncel()
    {
        return $this->hasMany(CaseDefenseCounsel::class, 'court_case_id', 'id');
    }
}
