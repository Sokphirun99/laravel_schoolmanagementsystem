<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class PortalUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'status',
        'related_id',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Get the student associated with the portal user.
     */
    public function student()
    {
        return $this->hasOne(Student::class, 'id', 'related_id')
            ->when($this->user_type === 'student', function ($query) {
                return $query;
            });
    }

    /**
     * Get the parent associated with the portal user.
     */
    public function parent()
    {
        return $this->hasOne(ParentModel::class, 'id', 'related_id')
            ->when($this->user_type === 'parent', function ($query) {
                return $query;
            });
    }

    /**
     * Determine if the user is a parent.
     */
    public function isParent()
    {
        return $this->user_type === 'parent';
    }

    /**
     * Determine if the user is a student.
     */
    public function isStudent()
    {
        return $this->user_type === 'student';
    }
}
