# Role Management System for Laravel School Management

## Overview

This enhancement provides a flexible role management system for the Laravel School Management System. It enables users to have multiple roles, improves role checking methods, and implements role-based access control.

## Current Status

All core functionality has been implemented and tested:
- ✅ Users can have multiple roles
- ✅ Role-checking methods are working properly
- ✅ Legacy compatibility is maintained
- ✅ Role synchronization is working between old and new systems
- ✅ Test users with various role combinations have been created

You can access the demo at `/admin/role-demo` (after logging in as an admin).

## Features Implemented

1. **Many-to-Many Relationship**: Users can now have multiple roles, stored in the `user_roles` table.
2. **Backward Compatibility**: Maintains compatibility with the legacy `role_id` column.
3. **Role Management UI**: Admin interface for assigning and managing user roles.
4. **Role-Based Middleware**: Protects routes based on user roles.
5. **Role Management Traits**: Common functionality shared across models.
6. **Service Layer**: Handles user creation and updates with proper role assignment.
7. **Database Migration**: Syncs existing roles to the new system.
8. **CLI Command**: Command to synchronize roles between systems.
9. **Tests**: Feature tests for the role management system.

## Implementation Details

### Models

- **UserRole**: Represents the many-to-many relationship between users and roles.
- **User**: Enhanced with role checking and assignment methods.

### Traits

- **UserRolesTrait**: Contains shared methods for role management.

### Services

- **UserService**: Handles user creation and updates with role assignment.

### Middleware

- **CheckUserRole**: Protects routes based on user roles.

### Commands

- **SyncUserRoles2Command**: Synchronizes roles between the legacy system and the new one.

### UI Components

- Role Management Dashboard
- Multi-role selection interface
- Reports based on user roles

## Setup Instructions

1. Run database migrations:
   ```bash
   php artisan migrate
   ```

2. Run the role synchronization command:
   ```bash
   php artisan users:sync-roles2
   ```

3. Add the role management menu:
   ```bash
   php artisan db:seed --class=RoleManagementMenuSeeder
   ```
   
   Or visit this URL: `/setup/add-role-management-menu`

## Usage

### Role Middleware

Use the `check.role` middleware to protect routes based on user roles:

```php
Route::middleware(['check.role:admin'])->group(function () {
    // Admin-only routes
});

Route::middleware(['check.role:student,teacher'])->group(function () {
    // Routes for students and teachers
});
```

### Role Checking in Code

```php
$user = Auth::user();

// Check if user has a specific role
if ($user->hasRole($roleId)) {
    // ...
}

// Check if user has a specific role by name
if ($user->hasRoleName('admin')) {
    // ...
}

// Check if user has any of the specified roles
if ($user->hasAnyRole([$adminRoleId, $teacherRoleId])) {
    // ...
}
```

### Role Assignment

```php
$user = User::find($id);

// Assign a role
$user->assignRole($roleId);

// Remove a role
$user->removeRole($roleId);

// Keep legacy role_id in sync
$user->syncRoleId();
```

## Example Routes

Example routes have been created to demonstrate the use of the role middleware:

- `/dashboard` - Accessible to all authenticated users
- `/admin/system-settings` - Admin only
- `/teacher/my-classes` - Teachers only
- `/student/my-courses` - Students only
- `/parent/my-children` - Parents only
- `/library` - Students and teachers
- `/reports` - Admins, teachers, and parents

## Testing

Run the role management tests:

```bash
php artisan test --filter=UserRoleManagementTest
```

## Documentation

For more detailed documentation, see the [Role Management Documentation](docs/role-management.md).

## Next Steps

1. Implement additional role-based views and permissions
2. Enhance the UI for role management
3. Add role-based reporting
4. Implement team-based permissions (e.g., teachers assigned to specific classes)
