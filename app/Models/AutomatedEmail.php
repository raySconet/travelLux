<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomatedEmail extends Model
{
    protected $table = 'automated_emails';

    protected $primaryKey = 'id';

    protected $fillable = [
        'email_type',
        'subject',
        'message',
        'before_after',
        'days',
        'bcc_agent',
        'product_list',
        'destination_list',
        'cruise_itinerary_list',
        'resort_list',
        'agent_id',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_disabled',
        'is_deleted'
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id'); 
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_list'); 
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_list'); 
    }

    public function resortShip()
    {
        return $this->belongsTo(ResortShip::class, 'resort_list'); 
    }

    public function cruiseItinerary()
    {
        return $this->belongsTo(CruiseItinerary::class, 'cruise_itinerary_list'); 
    }
}
