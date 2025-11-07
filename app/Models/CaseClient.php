<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseClient extends Model
{
    protected $table = 'case_client';

    protected $fillable = [
        'caseId', 'client_name', 'client_tel',
        'client_email', 'client_dob',
        'client_ssn', 'client_address',

    ];

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class, 'caseId', 'id');
    }

    public function affidavits()
    {
        return $this->hasMany(CaseAffidavit::class, 'case_client_id', 'id');
    }
    public function treatingChart()
    {
        return $this->hasOne(CaseTreatingChart::class, 'case_client_id', 'id');
    }

    public function negotiatingCharts()
    {
        return $this->hasMany(CaseNegotiatingChart::class, 'case_client_id', 'id');
    }
}
