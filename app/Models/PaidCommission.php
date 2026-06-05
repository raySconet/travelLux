<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaidCommission extends Model
{
    protected $table = 'paid_commissions';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'check_number',
        'check_date',
        'reservation_id',
        'amount',
        'is_agent',
        'agent_id'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
}
