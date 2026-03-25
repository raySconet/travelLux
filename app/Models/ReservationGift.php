<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationGift extends Model
{
    protected $table = 'reservation_gifts';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'customer_name',
        'gift_date',
        'gift_type',
        'amount',
        'notes',
        'paid_by',
        'last4_of_card',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class,'reservation_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
