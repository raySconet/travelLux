<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationLink extends Model
{
    protected $table = 'reservation_links';
    
    protected $primaryKey = 'id' ;

    protected $fillable = [
        'reservation_id',
        'linked_reservation_id',
        'is_linked',
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
