<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseThirdP extends Model
{
    protected $table = 'case_third_p';

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'court_case_id', 'id');
    }
}
