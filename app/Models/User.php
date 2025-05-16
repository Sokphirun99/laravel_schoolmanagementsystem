<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\UserRolesTrait;

class User extends \TCG\Voyager\Models\User
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

    /**
     * Check if user is a student
     * Uses both the legacy role_id and the new user_roles relationship
     */
    public function isStudent()
    {
        // Check both the legacy role_id and the roles relationship
        return $this->role_id === Role::STUDENT ||
               $this->hasRole(Role::STUDENT) ||
               $this->student()->exists();
    }

    /**
     * Check if user is a teacher
     * Uses both the legacy role_id and the new user_roles relationship
     */
    public function isTeacher()
    {
        // Check both the legacy role_id and the roles relationship
        return $this->role_id === Role::TEACHER ||
               $this->hasRole(Role::TEACHER) ||
               $this->teacher()->exists();
    }

    /**
     * Check if user is a parent
     * Uses both the legacy role_id and the new user_roles relationship
     */
    public function isParent()
    {
        // Check both the legacy role_id and the roles relationship
        return $this->role_id === Role::PARENT ||
               $this->hasRole(Role::PARENT) ||
               $this->parent()->exists();
    }

    /**
     * Check if the user has a specific role by ID
     *
     * @param int $roleId
     * @return bool
     */
    public function hasRole($roleId)
    {
        return $this->roles()->where('roles.id', $roleId)->exists();
    }

    /**
     * Check if the user has a specific role by name
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRoleName($roleName)
    {
        return $this->roles()->where('roles.name', $roleName)->exists();
    }

    /**
     * Assign a role to the user
     *
     * @param int $roleId
     * @return \App\Models\UserRole
     */
    public function assignRole($roleId)
    {
        // First check if the user already has this role
        if (!$this->hasRole($roleId)) {
            return UserRole::create([
                'user_id' => $this->id,
                'role_id' => $roleId
            ]);
        }

        return $this->roles()->where('roles.id', $roleId)->first()->pivot;
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
     * Sync the role_id with user_roles table to ensure consistency
     *
     * @return void
     */
    public function syncRoleId()
    {
        if ($this->role_id) {
            $this->assignRole($this->role_id);
        }
    }

    /**
     * Get field_id attribute - added to resolve Voyager error
     */
    public function getFieldIdAttribute()
    {
        return null;
    }

    /**
     * The roles that belong to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }
}
