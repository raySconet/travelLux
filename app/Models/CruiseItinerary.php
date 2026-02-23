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
}
