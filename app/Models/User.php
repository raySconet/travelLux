<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'nickname', 
        'email',
        'email_as_username',
        'backup_email',
        'username',
        'phone_number',
        'cell_phone_number',
        'birth_date',
        'hire_date',
        'facebook_profile',
        'instagram_account',
        'twitter_account',
        'time_zone',
        'ssn',
        'ein',
        'automated_emails_page_access',
        'email_verified_at',
        'role',
        'agent_title',
        'commission',
        'alternate_commission',
        'include_in_alias_reports',
        'alias',
        'first_address_line',
        'second_address_line',
        'city',
        'state',
        'postal_code',
        'mentor_ids',
        'reservation_email_enabled',
        'reservation_agent_owns_email',
        'reservation_cc_email_address',
        'reservation_bcc_email_address',
        'task_enable_daily_tasks_email',
        'task_seperate_email_per_task',
        'task_send_email_at',
        'general_notes',
        'profile_photo',
        'profile_submit_flag',
        'is_disabled', 
        'password',
        'created_at',
        'created_by',
        'updated_at',
        'last_modified_by', 
        'isDeleted',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin()
    {
        return $this->userPermission === 'super_admin';
    }


    public function customers()
    {
        return $this->hasMany(Customer::class, 'agent_id');
    }


}
