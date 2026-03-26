<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationCommissionFee extends Model
{
    protected $table = 'reservation_commission_fees';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'fee_type',
        'amount',
        'notes',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function reservations()
    {
        return $this->belongsTo(Reservation::class,'reservation_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
