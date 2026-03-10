<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CruiseItinerary extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'cruise_itineraries';
    // public $timestamps = false; // since you use created_on / last_modified_on

    protected $fillable = [
        'id',
        'resort_ship_id',
        'cruise_name',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function resortShip()
    {
        return $this->belongsTo(ResortShip::class, 'resort_ship_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'cruise_itinerary_id', 'id');
    }

    public function automatedEmails()
    {
        return $this->hasMany(AutomatedEmail::class, 'cruise_itinerary_list');
    }
}
