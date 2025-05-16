<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can be assigned a role
     *
     * @return void
     */
    public function test_user_can_be_assigned_a_role()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a role
        $role = Role::create([
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'A role for testing'
        ]);

        // Assign the role to the user
        $user->assignRole($role->id);

        // Check if the role was assigned
        $this->assertTrue($user->hasRole($role->id));
        $this->assertTrue($user->hasRoleName('test-role'));
    }

    /**
     * Test that a user can have multiple roles
     *
     * @return void
     */
    public function test_user_can_have_multiple_roles()
    {
        // Create a user
        $user = User::factory()->create();

        // Create roles
        $roleA = Role::create([
            'name' => 'role-a',
            'display_name' => 'Role A',
            'description' => 'First test role'
        ]);

        $roleB = Role::create([
            'name' => 'role-b',
            'display_name' => 'Role B',
            'description' => 'Second test role'
        ]);

        // Assign roles to user
        $user->assignRole($roleA->id);
        $user->assignRole($roleB->id);

        // Check if the user has both roles
        $this->assertTrue($user->hasRole($roleA->id));
        $this->assertTrue($user->hasRole($roleB->id));

        // Check if the user has at least one of the roles
        $this->assertTrue($user->hasAnyRole([$roleA->id, $roleB->id]));

        // Check if the user has all the roles
        $roles = $user->getAllRoles();
        $this->assertCount(2, $roles);
    }

    /**
     * Test that a role can be removed from a user
     *
     * @return void
     */
    public function test_role_can_be_removed_from_user()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a role
        $role = Role::create([
            'name' => 'removable-role',
            'display_name' => 'Removable Role',
            'description' => 'A role that can be removed'
        ]);

        // Assign the role to the user
        $user->assignRole($role->id);
        $this->assertTrue($user->hasRole($role->id));

        // Remove the role
        $user->removeRole($role->id);

        // Check if the role was removed
        $this->assertFalse($user->hasRole($role->id));
    }

    /**
     * Test that legacy role_id is synced with primary role
     *
     * @return void
     */
    public function test_legacy_role_id_is_synced()
    {
        // Create a user without a role_id
        $user = User::factory()->create([
            'role_id' => null
        ]);

        // Create a role
        $role = Role::create([
            'name' => 'primary-role',
            'display_name' => 'Primary Role',
            'description' => 'A primary role'
        ]);

        // Assign the role to the user
        $user->assignRole($role->id);

        // Sync the role_id
        $user->syncRoleId();

        // Check if the role_id is updated
        $this->assertEquals($role->id, $user->role_id);
    }

    /**
     * Test middleware can restrict access based on role
     *
     * @return void
     */
    public function test_middleware_restricts_access_based_on_role()
    {
        // Create roles
        $adminRole = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Administrator role'
        ]);

        $teacherRole = Role::create([
            'name' => 'teacher',
            'display_name' => 'Teacher',
            'description' => 'Teacher role'
        ]);

        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole($adminRole->id);

        // Create teacher user
        $teacher = User::factory()->create();
        $teacher->assignRole($teacherRole->id);

        // Test admin-only route
        $this->actingAs($admin)
            ->get('/admin/system-settings')
            ->assertStatus(200);

        $this->actingAs($teacher)
            ->get('/admin/system-settings')
            ->assertStatus(403); // Forbidden

        // Test teacher-only route
        $this->actingAs($teacher)
            ->get('/teacher/my-classes')
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get('/teacher/my-classes')
            ->assertStatus(403); // Forbidden
    }
}
