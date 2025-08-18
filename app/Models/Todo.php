<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'title',
        'description',
        'completeDate',
        'toDoStatus',
        'case_id',
    ];

    public function courtCase()
    {
        return $this->belongsTo(CourtCase::class); // careful: if your model is named `Cases`, change it
    }
}
