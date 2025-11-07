<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseDefenseCounsel extends Model
{
    protected $table = 'case_defense_counsel';

    protected $fillable = [
        'caseId', 'defense_tel', 'defense_email',
        'defense_fax', 'defense_name',
        'defense_cell', 'defense_address', 'defense_attorney'

    ];

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'court_case_id', 'id');
    }
}
