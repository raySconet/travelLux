<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseFirstP extends Model
{
    protected $table = 'case_one_p';

    protected $fillable = [
        'caseId', 'name', 'claim',
        'adjuster', 'tel',
        'fax', 'email',

    ];
    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'court_case_id', 'id');
    }
}
