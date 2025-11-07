<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseThirdP extends Model
{
    protected $table = 'case_third_p';

    protected $fillable = [
        'caseId', 'third_party_claim', 'third_party_adjuster',
        'third_party_tel', 'third_party_fax',
        'third_party_email', 'third_party_name',

    ];

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'court_case_id', 'id');
    }
}
