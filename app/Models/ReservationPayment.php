<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationPayment extends Model
{
    protected $table = 'reservation_payments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'amount',
        'payment_type',
        'payment_method',
        'payment_date',
        'check_number',
        'credit_card_number',
        'notes',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
