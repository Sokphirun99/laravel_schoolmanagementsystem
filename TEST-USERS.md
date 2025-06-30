# Testing User Roles in the School Management System

This document provides instructions for testing the role system in the Laravel School Management System.

## Test Users Available

You can create test users for each role using the TestUsersSeeder. These users can be used to test
role-based functionality, permissions, and access control in the system.

### Available Test Users

| Role      | Email               | Password | Description                                |
|-----------|---------------------|---------|--------------------------------------------|
| Admin     | admin@test.com      | password| Full access to all system features         |
| Teacher   | teacher@test.com    | password| Access to classes, grades, and assignments |
| Student   | student@test.com    | password| Access to own grades and assignments       |
| Parent    | parent@test.com     | password| Access to children's information           |
| Staff     | staff@test.com      | password| Limited administrative access              |

## Creating Test Users

To create these test users, run:

```bash
# Using Docker
docker compose exec app php artisan db:seed --class=TestUsersSeeder

# Or using local PHP
php artisan db:seed --class=TestUsersSeeder
```

## Running Role Tests

The system includes unit tests to verify the role system functionality:

```bash
# Using Docker
docker compose exec app php artisan test --filter=UserRoleTest

# Or using local PHP
php artisan test --filter=UserRoleTest
```

## Manual Testing

You can manually test the role system by:

1. Logging in with different test user accounts
2. Verifying that appropriate menus and features are shown/hidden based on roles
3. Testing the role middleware by visiting protected routes:
   - `/role-demo/admin-only` (admin only)
   - `/role-demo/teacher-only` (teacher only)
   - `/role-demo/student-only` (student only)
   - `/role-demo/parent-only` (parent only)
   - `/role-demo/teacher-admin` (teachers or admins)
   - `/role-demo/student-teacher` (students or teachers)

## Resetting Test Data

If you need to remove all test users:

```php
// Using Tinker
App\Models\User::whereIn('email', [
    'admin@test.com',
    'teacher@test.com', 
    'student@test.com', 
    'parent@test.com', 
    'staff@test.com'
])->delete();

App\Models\PortalUser::whereIn('email', [
    'teacher@test.com', 
    'student@test.com', 
    'parent@test.com'
])->delete();
```

## Checking Role Functions

You can use the following methods to check user roles in your code:

```php
$user = Auth::user();

// Basic role checks
$user->isAdmin();    // true if admin
$user->isTeacher();  // true if teacher
$user->isStudent();  // true if student
$user->isParent();   // true if parent
$user->isStaff();    // true if staff

// Check against a specific role
$user->hasRole('admin');  // true if admin
$user->hasRole(['admin', 'teacher']);  // true if admin OR teacher

// Get human-readable role name
$roleName = $user->role_label;  // Returns "Administrator", "Teacher", etc.
```
