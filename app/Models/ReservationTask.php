<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationTask extends Model
{
    protected $table = 'reservation_tasks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'timeline_task_id',
        'task_name',
        'priority',
        'due_date',
        'date_type',
        'is_completed',
        'is_completed_by',
        'is_completed_on',
        'noted',
        'is_timeline_task',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class,'created_by');
    }
}
