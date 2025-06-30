<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_assigned_a_role()
    {
        // Create a user with the admin role
        $admin = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Check if the user has the admin role
        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isTeacher());
        $this->assertEquals('Administrator', $admin->role_label);
        $this->assertTrue($admin->hasRole('admin'));
        $this->assertTrue($admin->hasRole(['admin', 'teacher']));
        $this->assertFalse($admin->hasRole('teacher'));
    }

    /** @test */
    public function user_can_check_different_roles()
    {
        // Create a user with the teacher role
        $teacher = User::create([
            'name' => 'Test Teacher',
            'email' => 'teacher@test.com',
            'password' => bcrypt('password'),
            'role' => 'teacher'
        ]);

        // Check various role methods
        $this->assertTrue($teacher->isTeacher());
        $this->assertFalse($teacher->isAdmin());
        $this->assertFalse($teacher->isStudent());
        $this->assertFalse($teacher->isParent());
        $this->assertEquals('Teacher', $teacher->role_label);
        $this->assertTrue($teacher->hasRole('teacher'));
    }

    /** @test */
    public function can_query_users_by_role()
    {
        // Create users with different roles
        User::create([
            'name' => 'Admin User',
            'email' => 'admin1@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Teacher User',
            'email' => 'teacher1@test.com',
            'password' => bcrypt('password'),
            'role' => 'teacher'
        ]);
        
        User::create([
            'name' => 'Student User',
            'email' => 'student1@test.com',
            'password' => bcrypt('password'),
            'role' => 'student'
        ]);

        // Test the role-based scopes
        $this->assertEquals(1, User::admins()->count());
        $this->assertEquals(1, User::teachers()->count());
        $this->assertEquals(1, User::students()->count());
        
        // Test byRole scope
        $this->assertEquals(1, User::byRole('admin')->count());
    }
}
