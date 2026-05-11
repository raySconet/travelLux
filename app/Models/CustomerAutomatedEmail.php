<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAutomatedEmail extends Model
{
    public $table = 'customer_automated_emails';

    public $primaryKey = 'id';

    public $fillable = [
        'customer_id',
        'reservation_id',
        'automated_email_id',
        'date',
        'last_resent_date'
    ];

    public function automatedEmail()
    {
        return $this->belongsTo(AutomatedEmail::class, 'automated_email_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
