# Role Management System for Laravel School Management

## Overview

The School Management System uses Voyager's standard role management system, which provides a simple and effective way to assign roles to users and control access to various parts of the application.

## Current Status

The role system is fully implemented using Voyager's built-in functionality:
- ✅ Users have a single primary role (admin, teacher, student, parent, or staff)
- ✅ Role-checking methods are working properly
- ✅ Role-based access control is implemented
- ✅ Test users with various roles have been created

You can access the demo at `/admin/role-demo` (after logging in as an admin).

## Features Implemented

1. **Role Assignment**: Each user is assigned one primary role
2. **Role Management UI**: Admin interface for assigning roles to users via Voyager
3. **Role-Based Middleware**: Protects routes based on user roles
4. **Role-Based Permission**: Controls access to features based on user roles
5. **Role Methods**: Helper methods to check user roles (isAdmin, isTeacher, etc.)
6. **User Scopes**: Query scopes to filter users by role (admins(), teachers(), etc.)
7. **Role Labels**: Human-readable role labels for display purposes
8. **Dashboard Redirects**: Role-specific dashboards and redirects

## Implementation Details

### Models

- **User**: Enhanced with role checking and assignment methods.

### Traits

- **UserRolesTrait**: Contains shared methods for role management.

### Middleware

- **CheckUserRole**: Protects routes based on user roles.

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

// Assign a role directly
$user->role = 'admin'; // or 'teacher', 'student', 'parent', 'staff'
$user->save();

// Or using Voyager's admin panel:
// Navigate to Users -> Edit User -> Select Role from dropdown
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
