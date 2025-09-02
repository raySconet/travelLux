<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseStage extends Model
{
    protected $table = 'case_stages';

    // A stage can have many cases
    public function courtCases()
    {
        return $this->hasMany(CourtCase::class);
    }

}
