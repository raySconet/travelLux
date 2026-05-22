<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryDay extends Model
{
    protected $table = 'itinerary_days';

    protected $primaryKey = 'id';

    protected $fillable = [
        'dayNumber',
        'itinerary_trip_id',
        'dayTitle',
        'dayDate',
        'isDeleted'
    ];

    public function itinerary()
    {
        return $this->belongsTo(ItineraryTrip::class, 'itinerary_trip_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(ItineraryEvent::class, 'itineraryEventDayId', 'id');
    }
}
