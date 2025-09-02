<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseDefenseCounsel extends Model
{
    protected $table = 'case_defense_counsel';

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'court_case_id', 'id');
    }
}
