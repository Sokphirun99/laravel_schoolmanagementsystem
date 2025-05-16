<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TestRoleUsersSeeder extends Seeder
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
        $teacherRoleId = DB::table('roles')->where('name', 'teacher')->value('id');
        $studentRoleId = DB::table('roles')->where('name', 'student')->value('id');
        $parentRoleId = DB::table('roles')->where('name', 'parent')->value('id');

        if (!$adminRoleId || !$teacherRoleId || !$studentRoleId || !$parentRoleId) {
            $this->command->error('Required roles not found. Please ensure roles (admin, teacher, student, parent) exist.');
            return;
        }

        $this->command->info("Found roles: admin(id:$adminRoleId), teacher(id:$teacherRoleId), student(id:$studentRoleId), parent(id:$parentRoleId)");

        // 1. Admin user
        $adminUserId = $this->createOrGetUser('admin@school.test', 'Test Admin', $adminRoleId);
        $this->assignRole($adminUserId, $adminRoleId);
        $this->command->info('Created admin user: admin@school.test / password');

        // 2. Teacher user
        $teacherUserId = $this->createOrGetUser('teacher@school.test', 'Test Teacher', $teacherRoleId);
        $this->assignRole($teacherUserId, $teacherRoleId);
        $this->createTeacherProfile($teacherUserId);
        $this->command->info('Created teacher user: teacher@school.test / password');

        // 3. Student user
        $studentUserId = $this->createOrGetUser('student@school.test', 'Test Student', $studentRoleId);
        $this->assignRole($studentUserId, $studentRoleId);
        $this->createStudentProfile($studentUserId);
        $this->command->info('Created student user: student@school.test / password');

        // 4. Parent user
        $parentUserId = $this->createOrGetUser('parent@school.test', 'Test Parent', $parentRoleId);
        $this->assignRole($parentUserId, $parentRoleId);
        $this->createParentProfile($parentUserId);
        $this->command->info('Created parent user: parent@school.test / password');

        // 5. Multi-role user (Teacher and Admin)
        $multiRoleUserId = $this->createOrGetUser('multi@school.test', 'Multi-Role User', $teacherRoleId);
        $this->assignRole($multiRoleUserId, $teacherRoleId);
        $this->assignRole($multiRoleUserId, $adminRoleId);
        $this->createTeacherProfile($multiRoleUserId, 'PhD in Education Administration', '321 Multi-Role St');
        $this->command->info('Created multi-role user: multi@school.test / password');

        $this->command->info('All test users created successfully!');
    }

    /**
     * Create or get a user by email
     */
    private function createOrGetUser($email, $name, $roleId)
    {
        $user = DB::table('users')->where('email', $email)->first();

        if (!$user) {
            return DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => $roleId,
                'settings' => json_encode(['locale' => 'en']),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return $user->id;
    }

    /**
     * Assign a role to a user
     */
    private function assignRole($userId, $roleId)
    {
        DB::table('user_roles')->updateOrInsert(
            ['user_id' => $userId, 'role_id' => $roleId],
            []
        );
    }

    /**
     * Create a teacher profile
     */
    private function createTeacherProfile($userId, $qualification = 'Masters in Education', $address = '123 Teacher St')
    {
        $teacher = DB::table('teachers')->where('user_id', $userId)->first();

        if (!$teacher) {
            $email = 'teacher' . rand(100, 999) . '@school.test';
            DB::table('teachers')->insert([
                'user_id' => $userId,
                'employee_id' => 'TCH' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'first_name' => 'Test',
                'last_name' => 'Teacher',
                'gender' => 'Male',
                'date_of_birth' => '1980-01-01',
                'joining_date' => now(),
                'qualification' => $qualification,
                'address' => $address,
                'phone' => '555-' . rand(1000, 9999),
                'email' => $email,
                'department' => 'Science',
                'designation' => 'Senior Teacher',
                'school_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Create a student profile
     */
    private function createStudentProfile($userId)
    {
        $student = DB::table('students')->where('user_id', $userId)->first();

        if (!$student) {
            DB::table('students')->insert([
                'user_id' => $userId,
                'admission_number' => 'STU' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'first_name' => 'Test',
                'last_name' => 'Student',
                'gender' => 'Male',
                'date_of_birth' => '2005-01-01',
                'blood_group' => 'O+',
                'address' => '456 Student Ave',
                'phone' => '555-' . rand(1000, 9999),
                'emergency_contact' => '555-' . rand(1000, 9999),
                'admission_date' => now(),
                'school_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Create a parent profile
     */
    private function createParentProfile($userId)
    {
        $parent = DB::table('parents')->where('user_id', $userId)->first();

        if (!$parent) {
            DB::table('parents')->insert([
                'user_id' => $userId,
                'father_name' => 'John Doe',
                'father_occupation' => 'Engineer',
                'father_phone' => '555-' . rand(1000, 9999),
                'father_email' => 'father@example.com',
                'mother_name' => 'Jane Doe',
                'mother_occupation' => 'Doctor',
                'mother_phone' => '555-' . rand(1000, 9999),
                'mother_email' => 'mother@example.com',
                'address' => '789 Parent Blvd',
                'email' => 'parent@school.test',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
