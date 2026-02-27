<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelineTask extends Model
{
    protected $table = 'timeline_tasks';

    protected $primaryKey = 'id';

    protected $fillable = [
        'product_id',
        'destination_id',
        'task_name',
        'priority',
        'due_days',
        'before_after',
        'date_type',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }
}
