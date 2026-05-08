<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationAttachment extends Model
{
    protected $table = 'reservation_attachments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'file_name',
        'file_extension',
        'file_size',
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
}
