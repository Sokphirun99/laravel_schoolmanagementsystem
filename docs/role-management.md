# Laravel School Management System - Role Management

This document describes the enhanced role management system implemented in the Laravel School Management System.

## Overview

The role management system allows users to have multiple roles, providing a more flexible approach to user permissions. This is an enhancement to the previous system which only allowed a single role per user.

The system maintains backward compatibility with existing code by keeping the `role_id` column in the users table, while adding a new `user_roles` pivot table for the many-to-many relationship.

## Features

- Many-to-many relationship between users and roles
- Backward compatibility with the legacy `role_id` column
- Helper methods for checking user roles
- Role-based middleware for route protection
- UI for managing user roles

## Models

### User

The User model uses the `UserRolesTrait` to implement role-related functionality:

```php
use App\Traits\UserRolesTrait;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, UserRolesTrait;
    
    // ...
}
```

### Role

The Role model defines the available roles and their relationship with users:

```php
class Role extends Model
{
    use SoftDeletes;
    
    // Role constants for easy reference
    public const ADMIN = 1;
    public const TEACHER = 2;
    public const STUDENT = 3;
    public const PARENT = 4;
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }
}
```

### UserRole

This model represents the many-to-many relationship between users and roles:

```php
class UserRole extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'role_id'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    
    // Helper methods for role management
    public static function hasRole(int $userId, int $roleId): bool { ... }
    public static function assignRole(int $userId, int $roleId): self { ... }
    public static function removeRole(int $userId, int $roleId): bool { ... }
    public static function getUsersByRole(int $roleId) { ... }
    public static function getRolesForUser(int $userId) { ... }
}
```

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    public $timestamps = false;
    
    protected $fillable = ['user_id', 'role_id'];
    
    protected $table = 'user_roles';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
```

## User Model Methods

The User model has been enhanced with the following methods:

### Role Checking

```php
// Check if user has a specific role by ID
$user->hasRole($roleId);

// Check if user has a specific role by name
$user->hasRoleName('admin');

// Check if user has any of the specified roles
$user->hasAnyRole([$adminRoleId, $teacherRoleId]);

// Get all roles assigned to the user
$roles = $user->getAllRoles();

// Get the primary role (first assigned role)
$primaryRole = $user->getPrimaryRole();
```

### Role Assignment

```php
// Assign a role to the user
$user->assignRole($roleId);

// Remove a role from the user
$user->removeRole($roleId);

// Sync the legacy role_id with the first role in user_roles
$user->syncRoleId();
```

## UserRolesTrait

This trait provides common role management functionality that can be shared across models:

```php
trait UserRolesTrait
{
    public static function authenticateWithRoles($email, $password, $remember = false)
    {
        // Handles authentication with role loading
    }
    
    public function getAllRoles()
    {
        // Returns all roles for the user
    }
    
    public function getPrimaryRole()
    {
        // Returns the primary role for the user
    }
    
    public function hasAnyRole(array $roleIds)
    {
        // Checks if the user has any of the specified roles
    }
    
    // Additional helper methods...
}
```

## Middleware

The `CheckUserRole` middleware allows for role-based route protection:

```php
// Routes accessible to administrators only
Route::middleware(['check.role:admin'])->group(function () {
    // Admin-only routes
});

// Routes accessible to multiple roles
Route::middleware(['check.role:student,teacher'])->group(function () {
    // Routes accessible to students and teachers
});
```

## Database Migration

A migration has been created to sync existing roles from the legacy `role_id` column to the new `user_roles` table:

```php
Schema::create('user_roles', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('role_id');
    
    $table->foreign('user_id')->references('id')->on('users')
        ->onDelete('cascade');
    $table->foreign('role_id')->references('id')->on('roles')
        ->onDelete('cascade');
        
    $table->primary(['user_id', 'role_id']);
});
```

## Command for Syncing Roles

A custom Artisan command has been created to sync existing roles:

```bash
php artisan users:sync-roles
```

This command:
1. Syncs roles from the `role_id` column to the `user_roles` table
2. Assigns appropriate roles based on related models (Student, Teacher, Parent)
3. Reports any conflicting role assignments

## UI Components

A role management interface is available at `/admin/manage-roles` which allows administrators to:
1. View all users and their current roles
2. Assign multiple roles to users
3. Remove roles from users

## Usage Examples

### Route Definition

```php
// Admin-only route
Route::middleware(['check.role:admin'])->prefix('admin')->group(function () {
    Route::get('/system-settings', function () {
        return view('admin.system_settings');
    })->name('admin.system-settings');
});

// Multi-role route
Route::middleware(['check.role:student,teacher'])->group(function () {
    Route::get('/library', function () {
        return view('shared.library');
    })->name('shared.library');
});
```

### Controller Authorization

```php
public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    
    if (!$user->hasRoleName('admin') && !$user->hasRoleName('teacher')) {
        abort(403, 'Unauthorized action.');
    }
    
    // Continue with update logic...
}
```

## Testing

The role management system includes comprehensive tests to ensure proper functioning:

```php
// Run the tests
php artisan test --filter=UserRoleManagementTest
```

These tests validate:
- Role assignment and removal
- Multiple role support
- Legacy role ID synchronization
- Middleware functionality
