<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItineraryAttachment extends Model
{
    protected $table = 'itinerary_attachments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'trip_id',
        'name',
        'extension',
        'isDeleted'
    ];

    public function trip()
    {
        return $this->belongsTo(ItineraryTrip::class, 'trip_id', 'id');
    }
}
