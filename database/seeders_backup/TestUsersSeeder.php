<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Creating test users with different roles...');

        // Get role IDs
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');
        $userRoleId = DB::table('roles')->where('name', 'user')->value('id');
        $teacherRoleId = DB::table('roles')->where('name', 'teacher')->value('id');
        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');
        $parentRoleId = DB::table('roles')->where('name', 'parent')->value('id');

        // Create test users

        // 1. Admin user
        $adminUser = DB::table('users')->where('email', 'admin@school.test')->first();
        if (!$adminUser) {
            $adminUserId = DB::table('users')->insertGetId([
                'name' => 'Test Admin',
                'email' => 'admin@school.test',
                'password' => Hash::make('password'),
                'role_id' => $adminRoleId,
                'settings' => json_encode(['locale' => 'en']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $adminUserId = $adminUser->id;
        }

        // Ensure admin role
        DB::table('user_roles')->updateOrInsert(
            ['user_id' => $adminUserId, 'role_id' => $adminRoleId],
            []
        );

        $this->command->info('Created admin user: admin@school.test / password');

        // 2. Teacher user
        $teacherUser = User::firstOrCreate(
            ['email' => 'teacher@school.test'],
            [
                'name' => 'Test Teacher',
                'password' => Hash::make('password'),
                'role_id' => $teacherRoleId,
                'settings' => ['locale' => 'en'],
            ]
        );

        // Ensure teacher role
        UserRole::firstOrCreate([
            'user_id' => $teacherUser->id,
            'role_id' => $teacherRoleId,
        ]);

        // Create teacher profile if it doesn't exist
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $teacherUser->id],
            [
                'address' => '123 Teacher St',
                'phone' => '555-1234',
                'qualification' => 'Masters in Education',
            ]
        );

        $this->command->info('Created teacher user: teacher@school.test / password');

        // 3. Student user
        $studentUser = User::firstOrCreate(
            ['email' => 'student@school.test'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
                'role_id' => $studentRoleId,
                'settings' => ['locale' => 'en'],
            ]
        );

        // Ensure student role
        UserRole::firstOrCreate([
            'user_id' => $studentUser->id,
            'role_id' => $studentRoleId,
        ]);

        // Create student profile if it doesn't exist
        $student = Student::firstOrCreate(
            ['user_id' => $studentUser->id],
            [
                'roll_number' => 'STU001',
                'admission_date' => now(),
                'address' => '456 Student Ave',
                'phone' => '555-5678',
            ]
        );

        $this->command->info('Created student user: student@school.test / password');

        // 4. Parent user
        $parentUser = User::firstOrCreate(
            ['email' => 'parent@school.test'],
            [
                'name' => 'Test Parent',
                'password' => Hash::make('password'),
                'role_id' => $parentRoleId,
                'settings' => ['locale' => 'en'],
            ]
        );

        // Ensure parent role
        UserRole::firstOrCreate([
            'user_id' => $parentUser->id,
            'role_id' => $parentRoleId,
        ]);

        // Create parent profile if it doesn't exist
        $parent = Parents::firstOrCreate(
            ['user_id' => $parentUser->id],
            [
                'name' => 'Test Parent',
                'email' => 'parent@school.test',
                'phone' => '555-9012',
                'address' => '789 Parent Blvd',
            ]
        );

        $this->command->info('Created parent user: parent@school.test / password');

        // 5. Multi-role user (Teacher and Admin)
        $multiRoleUser = User::firstOrCreate(
            ['email' => 'multi@school.test'],
            [
                'name' => 'Multi-Role User',
                'password' => Hash::make('password'),
                'role_id' => $teacherRoleId, // Primary role is teacher
                'settings' => ['locale' => 'en'],
            ]
        );

        // Ensure teacher role
        UserRole::firstOrCreate([
            'user_id' => $multiRoleUser->id,
            'role_id' => $teacherRoleId,
        ]);

        // Add admin role
        UserRole::firstOrCreate([
            'user_id' => $multiRoleUser->id,
            'role_id' => $adminRoleId,
        ]);

        // Create teacher profile if it doesn't exist
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $multiRoleUser->id],
            [
                'address' => '321 Multi-Role St',
                'phone' => '555-3456',
                'qualification' => 'PhD in Education Administration',
            ]
        );

        $this->command->info('Created multi-role user: multi@school.test / password');

        $this->command->info('All test users created successfully!');
    }
}
