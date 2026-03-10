<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersFormRequired extends Model
{
    protected $table = 'customers_form_required' ;

    protected $primaryKey = 'id';

    protected $fillable = [
        'form_id',
        'all_customers_required',
        'all_reservations_required',
        'product_id',
        'destination_id',
        'is_deleted'
    ];

    public function form()
    {
        return $this->belongsTo(CustomersForm::class, 'form_id')->where();
    }
}
