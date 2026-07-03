<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'module_name',
        'record_id',
        'message',
        'date',
        'created_by',
        'is_read'
    ];
}
