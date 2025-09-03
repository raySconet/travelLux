<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoSection extends Model
{
    protected $table = 'todos';

    protected $fillable = [
        'title',
        'description',
        'completeDate',
        'toDoStatus',
        'case_id',
    ];

      public function todos()
    {
        return $this->hasMany(Todo::class);
    }
}
