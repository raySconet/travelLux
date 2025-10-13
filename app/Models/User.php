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

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'userPermission',
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

    public static function getActiveUsers()
    {
        return self::where('isDeleted', 0)->orderBy('name')->get();
    }

    public function event()
    {
        return $this->hasMany(Event::class);
    }
    // If you omit the pivot table name,
    // Laravel will assume the table is the alphabetical order of the two model names (snake_case, singular), like court_case_user.
    public function courtCases()
    {
        return $this->belongsToMany(CourtCase::class, 'user_case', 'user_id', 'case_id')->withTimestamps();
    }

    public function isSuperAdmin()
    {
        return $this->userPermission === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->userPermission === 'admin';
    }

    public function isRegularUser()
    {
        return $this->userPermission === 'user';
    }

    public function canEditEvent(Event $event)
    {
        return $this->isSuperAdmin() || ($this->isAdmin() && $event->user_id === $this->id);
    }

    public function canDeleteEvent(Event $event)
    {
        return $this->canEditEvent($event); // same logic for now
    }

    // public function canViewEvent(Event $event)
    // {
    //     return $this->isSuperAdmin() || $this->isAdmin() || ($event->user_id === $this->id);
    // }

    public function canViewCase(CourtCase $case)
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isAssignedToCase($case);
    }

    public function canEditCase(CourtCase $case)
    {
        return $this->isSuperAdmin() || ($this->isAdmin() && $this->isAssignedToCase($case));
    }

    public function canDeleteCase(CourtCase $case)
    {
        return $this->canEditCase($case);
    }

    protected function isAssignedToCase(CourtCase $case)
    {
        return $case->relationLoaded('users') ? $case->users->contains('id', $this->id) : $case->users()->where('user_id', $this->id)->exists();
    }

    public function canCreateCase()
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function canViewEventsAndCasesForUser($targetUserId)
    {
        if ($this->isSuperAdmin() || $this->isAdmin()) {
            return true;
        }

        return $this->id === $targetUserId;
    }

    public function canAddCategory()
    {
        return $this->isSuperAdmin();
    }

    public function canEditCategory()
    {
        return $this->isSuperAdmin();
    }

    public function canDeleteCategory()
    {
        return $this->isSuperAdmin();
    }
}
