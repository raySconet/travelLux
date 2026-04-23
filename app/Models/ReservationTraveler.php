<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationTraveler extends Model
{
    protected $table = 'reservation_travelers';

    protected $fillable = [
        'reservation_id',
        'customer_family_member_id',
        'is_included',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function familyMember()
    {
        return $this->belongsTo(CustomerFamilyMember::class, 'customer_family_member_id');
    }
}
