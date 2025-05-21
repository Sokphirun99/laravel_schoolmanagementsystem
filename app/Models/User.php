<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserRole;
use App\Traits\UserRolesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use TCG\Voyager\Contracts\User as VoyagerUserContract;

class User extends \TCG\Voyager\Models\User implements VoyagerUserContract
{
    use HasApiTokens, HasFactory, Notifiable, UserRolesTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the student record associated with the user.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Get the teacher record associated with the user.
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Get the parent record associated with the user.
     */
    public function parent()
    {
        return $this->hasOne(Parents::class);
    }

    // The isStudent(), isTeacher(), isParent(), hasRole(), and hasRoleName() methods
    // have been moved to the UserRolesTrait

    /**
     * Assign a role to the user
     *
     * @param int $roleId
     * @return \App\Models\UserRole
     */
    public function assignRole($roleId)
    {
        // Use firstOrCreate to avoid duplicate entries and prevent ordering issues
        return UserRole::firstOrCreate([
            'user_id' => $this->id,
            'role_id' => $roleId
        ]);
    }

    /**
     * Remove a role from the user
     *
     * @param int $roleId
     * @return bool
     */
    public function removeRole($roleId)
    {
        return UserRole::where('user_id', $this->id)
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * Get field_id attribute - added to resolve Voyager error
     */
    public function getFieldIdAttribute()
    {
        return null;
    }

    // The roles() relationship has been moved to the UserRolesTrait
}
