<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationDiningNote extends Model
{
    protected $table = 'reservation_dining_notes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'dining_date',
        'dining_time',
        'meal',
        'notes',
        'is_canceled',
        'canceled_by',
        'canceled_on',
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
