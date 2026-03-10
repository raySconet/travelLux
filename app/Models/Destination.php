<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{

protected $table = 'destinations';

    protected $primaryKey = 'id';

    // public $timestamps = false; // since you use created_on / last_modified_on

    protected $fillable = [
         'id',
         'product_id',
         'destination_name',
         'display_order',
         'created_by',
         'created_on',
         'last_modified_by',
         'last_modified_on',
         'created_at',
         'updated_at',
         'is_deleted'
    ];
    // Each destination belongs to a product
     public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function resortShips()
    {
        return $this->hasMany(ResortShip::class, 'destination_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'destination_id', 'id');
    }

    public function timelineTasks()
    {
        return $this->hasMany(TimelineTask::class, 'destination_id', 'id');
    }

    public function automatedEmails()
    {
        return $this->hasMany(AutomatedEmail::class, 'destination_list');
    }
}