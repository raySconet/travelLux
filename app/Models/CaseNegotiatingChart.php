<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseNegotiatingChart extends Model
{
    protected $table = 'case_negotiating_chart';

    protected $fillable = [
        'case_client_id', 'medsTotal', 'medsPviTotal',
        'negotiationLastOffer', 'negotiationLastOfferDate',
        'negotiationLastDemand', 'negotiationLastDemandDate', 'physicalPainMentalAnguishText',
        'negotiationNameBottom', 'negotiationDateBottom', 'negotiationAmountBottom'
    ];

    public function client()
    {
        return $this->belongsTo(CaseClient::class, 'case_client_id', 'id');
    }
}
