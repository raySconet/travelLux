<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCase extends Model
{
    protected $table = 'user_case';

    protected $fillable = [
        'user_id',
        'case_id'
    ];
}
