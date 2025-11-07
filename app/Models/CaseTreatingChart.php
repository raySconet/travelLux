<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseTreatingChart extends Model
{
    protected $table = 'case_treating_chart';
    protected $fillable = [
        'case_client_id', 'ems', 'hospital',
        'chiropractor', 'pcpOrMd',
        'mriAndResults', 'orthoOrSurgery', 'painManagement'
    ];

    public function client()
    {
        return $this->belongsTo(CaseClient::class, 'case_client_id', 'id');
    }


}
