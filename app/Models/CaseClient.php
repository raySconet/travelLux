<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseClient extends Model
{
    protected $table = 'case_client';

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'court_case_id', 'id');
    }
}
