<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSent extends Model
{
    protected $table = 'forms_sent';

    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id',
        'reservation_id',
        'form_id',
        'sent_by',
        'sent_on',
        'submit_flag',
        'submitted_on',
        'submitted_form_content'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function form()
    {
        return $this->belongsTo(CustomersForm::class, 'form_id');
    }
}
