<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSurvey extends Model
{
    protected $table = 'customer_surveys';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'sent_on',
        'submit_flag',
        'submitted_on',
        'submitted_survery_content'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
