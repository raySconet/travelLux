<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserForgotPassword extends Model
{
    protected $table = 'user_forgot_password';

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'user_id',
        'requested_on',
        'status'
    ];    

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
