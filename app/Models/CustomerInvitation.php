<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerInvitation extends Model
{
    protected $table = 'customer_invitations';

    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id',
        'status',
        'created_by',
        'created_on',
        'submit_flag',
        'submitted_on',
        'expired_flag',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
