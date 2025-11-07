<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseAffidavit extends Model
{
    protected $table = 'case_affidavit';

    protected $fillable = [
        'caseId', 'case_client_id', 'providerName', 'dateOrdered',
        'dateReceivedBr', 'dateReceivedMr',
        'dateServed', 'noticeFilled',
        'mri_and_results', 'controverted',

    ];

    public function client()
    {
        return $this->belongsTo(CaseClient::class, 'case_client_id');
    }


}
