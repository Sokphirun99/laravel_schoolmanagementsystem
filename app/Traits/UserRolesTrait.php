<?php

namespace App\Traits;

trait UserRolesTrait
{
    /**
     * Get all available roles for the system
     *
     * @return array
     */
    public static function availableRoles()
    {
        return [
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
     * Check if user has permission to access
     * 
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        // For simplicity, we'll assume admins have all permissions
        if ($this->isAdmin()) {
            return true;
        }
        
        // Implement more complex permission logic here as needed
        // This could check against a permissions table or config
        
        return false;
    }
    
    /**
     * Get the dashboard route for this user based on role
     *
     * @return string
     */
    public function getDashboardRoute()
    {
        switch ($this->role) {
            case 'admin':
                return 'admin.dashboard';
            case 'teacher':
                return 'teacher.dashboard';
            case 'student':
                return 'student.dashboard';
            case 'parent':
                return 'parent.dashboard';
            default:
                return 'home';
        }
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