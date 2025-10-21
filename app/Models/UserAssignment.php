<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAssignment extends Model
{
    protected $table = 'user_assignments';

    protected $fillable = [
        'user_id',
        'assigned_id',
        'isDeleted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_id');
    }
}
