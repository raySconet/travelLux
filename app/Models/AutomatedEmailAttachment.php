<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutomatedEmailAttachment extends Model
{
    protected $table = 'automated_email_attachments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'automated_email_id',
        'file_name',
        'file_extension',
        'file_size',
        'created_by',
        'created_on',
        'last_modified_by',
        'last_modified_on',
        'is_deleted'
    ];

    public function automatedEmail()
    {
        return $this->belongsTo(AutomatedEmail::class, 'automated_email_id');
    }
}
