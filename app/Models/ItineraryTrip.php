<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryTrip extends Model
{
    protected $table = 'itinerary_trips';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 
        'date', 
        'created_by',
        'is_deleted'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'itinerary_trip_id', 'id');
    }
}
