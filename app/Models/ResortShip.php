<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResortShip extends Model
{
    protected $table = 'resort_ships';

    protected $primaryKey = 'id';

    // public $timestamps = false; // since you use created_on / last_modified_on

    protected $fillable = [
         'id',
         'destination_id',
         'resort_ship_name',
         'created_by',
         'created_on',
         'last_modified_by',
         'last_modified_on',
         'is_deleted'
    ];
}
