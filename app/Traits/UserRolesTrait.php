<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Support\Facades\Cache;

trait UserRolesTrait
{
    /**
     * Get all available roles for the system
     *
     * @return array
     */
    public static function availableRoles()
    {
        return Cache::remember('available_roles', 3600, function () {
            return Role::pluck('display_name', 'name')->toArray();
        }) ?: [
            'admin' => 'Administrator',
            'teacher' => 'Teacher',
            'student' => 'Student',
            'parent' => 'Parent',
            'staff' => 'Staff'
        ];
    }

    /**
     * Get the user's role label
     *
     * @return string
     */
    public function getRoleLabelAttribute()
    {
        return static::availableRoles()[$this->role] ?? ucfirst($this->role);
    }
    
    /**
     * Check if the user has a specific role
     *
     * @param string|array $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        $roles = is_array($roles) ? $roles : [$roles];
        return in_array($this->role, $roles);
    }
    
    /**
     * Check if user has any of the specified roles
     *
     * @param array $roles
     * @return bool
     */
    public function hasAnyRole(array $roles)
    {
        return $this->hasRole($roles);
    }
    
    /**
     * Check if user has all of the specified roles (for multi-role systems)
     *
     * @param array $roles
     * @return bool
     */
    public function hasAllRoles(array $roles)
    {
        // For single role system, user can only have all roles if there's only one role to check
        return count($roles) === 1 && $this->hasRole($roles[0]);
    }
    
    /**
     * Check if user has permission to access
     * 
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        // Admin has all permissions
        if ($this->isAdmin()) {
            return true;
        }
        
        // Define role-based permissions
        $rolePermissions = [
            'staff' => [
                'manage_users', 'view_reports', 'manage_students', 'manage_teachers', 'manage_parents'
            ],
            'teacher' => [
                'view_students', 'manage_classes', 'view_grades', 'update_grades', 'view_attendance', 'mark_attendance'
            ],
            'parent' => [
                'view_child_grades', 'view_child_attendance', 'view_child_teachers', 'communicate_teachers'
            ],
            'student' => [
                'view_own_grades', 'view_own_attendance', 'view_schedule', 'view_assignments'
            ]
        ];
        
        $userPermissions = $rolePermissions[$this->role] ?? [];
        return in_array($permission, $userPermissions);
    }
    
    /**
     * Get the dashboard route for this user based on role
     *
     * @return string
     */
    public function getDashboardRoute()
    {
        $routes = [
            'admin' => 'admin.dashboard',
            'staff' => 'staff.dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            'parent' => 'parent.dashboard',
        ];
        
        return $routes[$this->role] ?? 'home';
    }
    
    /**
     * Get the appropriate home URL for the user based on role
     *
     * @return string
     */
    public function getHomeUrl()
    {
        $homeUrls = [
            'admin' => '/admin',
            'staff' => '/staff',
            'teacher' => '/teacher',
            'student' => '/student',
            'parent' => '/parent',
        ];
        
        return $homeUrls[$this->role] ?? '/';
    }
    
    /**
     * Get a human-readable role name for display
     *
     * @return string
     */
    public function roleText()
    {
        $roles = $this->availableRoles();
        return $roles[$this->role] ?? ucfirst($this->role ?? 'user');
    }
    
    /**
     * Check if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Check if user is a teacher
     *
     * @return bool
     */
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }
    
    /**
     * Check if user is a student
     *
     * @return bool
     */
    public function isStudent()
    {
        return $this->role === 'student';
    }
    
    /**
     * Check if user is a parent
     *
     * @return bool
     */
    public function isParent()
    {
        return $this->role === 'parent';
    }
    
    /**
     * Check if user is staff
     *
     * @return bool
     */
    public function isStaff()
    {
        return $this->role === 'staff';
    }
}