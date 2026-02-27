<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgencyProfile extends Model
{
    protected $table = 'agency_profile';

    protected $primaryKey = 'id';

    // public $timestamps = false;

    protected $fillable = [
        'company_name',
        'contact_person_name',
        'agency_tag_line',
        'new_customer_invite_email_subject',
        'cell_phone_number',
        'home_phone_number',
        'is_deleted'
    ];
}
