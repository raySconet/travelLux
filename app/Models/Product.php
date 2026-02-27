<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    protected $table = 'products';

    protected $primaryKey = 'id';

    // public $timestamps = false; // since you use created_on / last_modified_on

    protected $fillable = [
        'product_name',
        'display_order',
        'product_type',
        'product_sub_type',
        'first_address_line',
        'second_address_line',
        'phone_number',
        'vendor_bdm',
        'bdm_phone_number',
        'bdm_email',
        'notes',
        'city',
        'state',
        'country',
        'postal_code',
        'currency',
        'tax',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function destinations()
    {
        return $this->hasMany(Destination::class, 'product_id', 'id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'product_id', 'id');
    }

    public function timelineTasks()
    {
        return $this->hasMany(TimelineTask::class, 'product_id', 'id');
    }
}
