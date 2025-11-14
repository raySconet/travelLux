<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $table = 'todos';

    protected $fillable = [
        'title',
        'description',
        'completeDate',
        'toDoStatus',
        'sectionId',
        'noteBox',
        'completedBy',
    ];

    // Each todo belongs to one section
    public function section()
    {
        return $this->belongsTo(TodoSection::class, 'sectionId', 'id');
    }
}
