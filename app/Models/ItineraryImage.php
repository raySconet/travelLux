<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryImage extends Model
{
    protected $table = 'itinerary_image';

    protected $primaryKey = 'id';

    protected $fillable = [
        'itinerary_id',
        'name',
        'extension',
        'is_deleted'
    ];

    public function itinerary()
    {
        return $this->belongsTo(ItineraryTrip::class, 'itinerary_id', 'id');
    }
}
