<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerIntakeForm extends Model
{
    protected $table = 'customer_intake_forms';

    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id',
        'status',
        'created_by',
        'created_on',
        'resent_on',
        'submit_flag',
        'submitted_on',
        'submitted_form_content',
        'counter'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
