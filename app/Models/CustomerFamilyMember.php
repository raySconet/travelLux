<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFamilyMember extends Model
{
    protected $table = 'customer_family_members';

    protected $primaryKey = 'id' ;

    protected $fillable = [
        'customer_id',
        'fname',
        'mname',
        'lname',
        'nickname',
        'birth_date',
        'relation',
        'gender',
        'cellphone',
        'home_phone',
        'work_phone',
        'email',
        'traveler_number',
        'deceased',
        'passport_number',
        'passport_issue_date',
        'passport_expiration_date',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip_code',
        'country',
        'special_notes',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted' 
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

}
