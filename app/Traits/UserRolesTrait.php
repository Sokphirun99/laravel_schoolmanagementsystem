<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\UserRole;

/**
 * Trait UserRolesTrait
 *
 * A trait for managing user roles in a standardized way across models
 */
trait UserRolesTrait
{
    /**
     * Define a many-to-many relationship with roles
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    /**
     * Boot the trait to ensure role_id changes are synced to the user_roles table
     */
    protected static function bootUserRolesTrait()
    {
        static::saved(function ($user) {
            if ($user->isDirty('role_id') && $user->role_id) {
                $user->syncRoleId();
            }
        });
    }

    /**
     * Authenticate a user and check their roles
     *
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @return \App\Models\User|null
     */
    public static function authenticateWithRoles($email, $password, $remember = false)
    {
        if (auth()->attempt(['email' => $email, 'password' => $password], $remember)) {
            $user = auth()->user();

            // Sync single role_id with user_roles table if needed
            if ($user->role_id) {
                $user->syncRoleId();
            }

            return $user;
        }

        return null;
    }

    /**
     * Get all roles assigned to the user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllRoles()
    {
        return UserRole::getRolesForUser($this->id);
    }

    /**
     * Get the primary role of the user (based on role_id)
     *
     * @return \App\Models\Role|null
     */
    public function getPrimaryRole()
    {
        if ($this->role_id) {
            return Role::find($this->role_id);
        }

        // If no role_id is set, try to get the first assigned role
        return $this->roles()->first();
    }

    /**
     * Check if the user has any of the specified roles
     *
     * @param array $roleIds
     * @return bool
     */
    public function hasAnyRole(array $roleIds)
    {
        // Check the primary role first (for performance)
        if (in_array($this->role_id, $roleIds)) {
            return true;
        }

        // Check the roles relationship
        return $this->roles()->whereIn('roles.id', $roleIds)->exists();
    }

    /**
     * Check if the user has any of the specified roles by name
     *
     * @param array $roleNames
     * @return bool
     */
    public function hasAnyRoleName(array $roleNames)
    {
        return $this->roles()->whereIn('roles.name', $roleNames)->exists();
    }

    /**
     * Check if the user has a specific role by name
     *
     * @param string $roleName
     * @return bool
     */
    public function hasRoleName(string $roleName)
    {
        return $this->roles()->where('roles.name', $roleName)->exists();
    }

    /**
     * Get student data if the user is a student
     *
     * @return \App\Models\Student|null
     */
    public function getStudentData()
    {
        if ($this->isStudent()) {
            return $this->student;
        }

        return null;
    }

    /**
     * Get teacher data if the user is a teacher
     *
     * @return \App\Models\Teacher|null
     */
    public function getTeacherData()
    {
        if ($this->isTeacher()) {
            return $this->teacher;
        }

        return null;
    }

    /**
     * Get parent data if the user is a parent
     *
     * @return \App\Models\Parents|null
     */
    public function getParentData()
    {
        if ($this->isParent()) {
            return $this->parent;
        }

        return null;
    }

    /**
     * Sync the user's primary role_id with the user_roles table
     *
     * @return void
     */
    public function syncRoleId()
    {
        if (!$this->role_id) {
            return;
        }

        UserRole::firstOrCreate([
            'user_id' => $this->id,
            'role_id' => $this->role_id
        ]);
    }

    /**
     * Check if the user has the student role
     *
     * @return bool
     */
    public function isStudent()
    {
        return $this->hasRoleName('student');
    }

    /**
     * Check if the user has the teacher role
     *
     * @return bool
     */
    public function isTeacher()
    {
        return $this->hasRoleName('teacher');
    }

    /**
     * Check if the user has the admin role
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRoleName('admin');
    }

    /**
     * Check if the user has the parent role
     *
     * @return bool
     */
    public function isParent()
    {
        return $this->hasRoleName('parent');
    }
}
