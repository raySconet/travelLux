<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationPaidInFullAudit extends Model
{
    protected $table = 'reservation_paid_in_full_audit';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'field_name',
        'old_value',
        'new_value',
        'modified_by',
        'modified_on'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function modifiedByUser()
    {
        return $this->belongsTo(User::class, 'modified_by', 'id');
    }
}