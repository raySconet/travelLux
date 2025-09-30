<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCase extends Model
{
    protected $table = 'user_case';

    protected $fillable = [
        'user_id',
        'case_id',
        'isDeleted'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function case()
    {
        return $this->belongsTo(CourtCase::class, 'case_id');
    }
}
