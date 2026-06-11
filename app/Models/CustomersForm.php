<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomersForm extends Model
{
    protected $table = 'customers_form';

    protected $primaryKey = 'id';

    protected $fillable = [
        'form_name',
        'form_subject',
        'form_items_html_content',
        'preview_form_html_content',
        'is_active',
        'is_deleted'
    ];

    public function customersFormRequired()
    {
        return $this->hasMany(CustomersFormRequired::class, 'form_id')->where('is_deleted', 0);
    }

    public function formSent()
    {
        return $this->hasMany(FormSent::class, 'form_id', 'id');
    }
}
