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

    public function attachments()
    {
        return $this->hasMany(ItineraryAttachment::class, 'trip_id', 'id')->where('isDeleted', 0);
    }

    public function itineraryDays()
    {
        return $this->hasMany(ItineraryDay::class, 'itinerary_trip_id', 'id');
    }

    public function itineraryImages()
    {
        return $this->hasMany(ItineraryImage::class, 'itinerary_id', 'id');
    }

    public function itineraryEvents()
    {
        return $this->hasMany(
            ItineraryEvent::class,
            'itineraryEventDayId',
            'id'
        );
    }
}
