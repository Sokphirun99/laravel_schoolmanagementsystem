<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\PortalUser;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds to create test users for each role.
     *
     * @return void
     */
    public function run()
    {
        // Create test users for each role
        echo "Creating test users for different roles...\n";

        // Create basic users with different roles (no profiles)
        $this->createBasicUsers();

        echo "Test users created successfully!\n";
    }

    /**
     * Create an admin test user
     */
    protected function createAdminUser()
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Test Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'avatar' => 'users/default.png',
                'status' => true
            ]
        );

        $this->command->info('Admin user created: admin@test.com / password');
    }

    /**
     * Create a teacher test user
     */
    protected function createTeacherUser()
    {
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'name' => 'Test Teacher',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'avatar' => 'users/default.png',
                'status' => true
            ]
        );

        // Create related teacher profile if it doesn't exist
        if (!$teacher->teacher) {
            $teacherProfile = Teacher::create([
                'user_id' => $teacher->id,
                'first_name' => 'Test',
                'last_name' => 'Teacher',
                'gender' => 'Male',
                'date_of_birth' => now()->subYears(35),
                'address' => '123 School Street',
                'phone' => '555-1234',
                'qualification' => 'Master of Education',
                'subjects' => 'Mathematics,Science',
                'joining_date' => now()->subYears(5),
                'photo' => 'teachers/default.png',
            ]);
        }

        // Create portal user for teacher if it doesn't exist
        PortalUser::firstOrCreate(
            ['email' => 'teacher@test.com'],
            [
                'name' => 'Test Teacher',
                'password' => Hash::make('password'),
                'user_type' => 'teacher',
                'related_id' => $teacher->teacher->id ?? null,
                'status' => true
            ]
        );

        $this->command->info('Teacher user created: teacher@test.com / password');
    }

    /**
     * Create a student test user
     */
    protected function createStudentUser()
    {
        $student = User::firstOrCreate(
            ['email' => 'student@test.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
                'role' => 'student',
                'avatar' => 'users/default.png',
                'status' => true
            ]
        );

        // Create related student profile if it doesn't exist
        if (!$student->student) {
            $studentProfile = Student::create([
                'user_id' => $student->id,
                'admission_no' => 'ST' . date('Y') . rand(1000, 9999),
                'roll_number' => 'R' . rand(100, 999),
                'first_name' => 'Test',
                'last_name' => 'Student',
                'gender' => 'Male',
                'date_of_birth' => now()->subYears(15),
                'class_id' => 1, // Assuming you have at least one class
                'section_id' => 1, // Assuming you have at least one section
                'admission_date' => now()->subMonths(6),
                'blood_group' => 'O+',
                'address' => '456 Student Avenue',
                'phone' => '555-5678',
                'photo' => 'students/default.png',
            ]);
        }

        // Create portal user for student if it doesn't exist
        PortalUser::firstOrCreate(
            ['email' => 'student@test.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
                'user_type' => 'student',
                'related_id' => $student->student->id ?? null,
                'status' => true
            ]
        );

        $this->command->info('Student user created: student@test.com / password');
    }

    /**
     * Create a parent test user
     */
    protected function createParentUser()
    {
        $parent = User::firstOrCreate(
            ['email' => 'parent@test.com'],
            [
                'name' => 'Test Parent',
                'password' => Hash::make('password'),
                'role' => 'parent',
                'avatar' => 'users/default.png',
                'status' => true
            ]
        );

        // Create related parent profile if it doesn't exist
        if (!$parent->parent) {
            $parentProfile = ParentModel::create([
                'user_id' => $parent->id,
                'first_name' => 'Test',
                'last_name' => 'Parent',
                'gender' => 'Male',
                'occupation' => 'Engineer',
                'address' => '789 Parent Road',
                'phone' => '555-9012',
                'photo' => 'parents/default.png',
            ]);
        }

        // Create portal user for parent if it doesn't exist
        PortalUser::firstOrCreate(
            ['email' => 'parent@test.com'],
            [
                'name' => 'Test Parent',
                'password' => Hash::make('password'),
                'user_type' => 'parent',
                'related_id' => $parent->parent->id ?? null,
                'status' => true
            ]
        );

        $this->command->info('Parent user created: parent@test.com / password');
    }

    /**
     * Create a staff test user
     */
    protected function createStaffUser()
    {
        $staff = User::firstOrCreate(
            ['email' => 'staff@test.com'],
            [
                'name' => 'Test Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'avatar' => 'users/default.png',
                'status' => true
            ]
        );

        $this->command->info('Staff user created: staff@test.com / password');
    }

    /**
     * Create basic users with different roles
     */
    protected function createBasicUsers()
    {
        // Array of test users to create
        $users = [
            [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => true,
                'create_portal_user' => false
            ],
            [
                'name' => 'Test Teacher',
                'email' => 'teacher@test.com',
                'password' => Hash::make('password'),
                'role' => 'teacher',
                'status' => true,
                'create_portal_user' => false
            ],
            [
                'name' => 'Test Student',
                'email' => 'student@test.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => true,
                'create_portal_user' => true
            ],
            [
                'name' => 'Test Parent',
                'email' => 'parent@test.com',
                'password' => Hash::make('password'),
                'role' => 'parent',
                'status' => true,
                'create_portal_user' => true
            ],
            [
                'name' => 'Test Staff',
                'email' => 'staff@test.com',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'status' => true,
                'create_portal_user' => false
            ],
        ];

        // Create or update each user
        foreach ($users as $userData) {
            $email = $userData['email'];
            $createPortalUser = $userData['create_portal_user'] ?? false;
            
            // Remove non-fillable attribute
            unset($userData['create_portal_user']);
            
            // Create the main user
            $user = User::firstOrNew(['email' => $email]);
            $user->fill($userData);
            $user->save();
            
            echo "User created: {$email} / password\n";
            
            // Create related profile and portal user if needed
            if ($createPortalUser) {
                // Create student record if needed
                if (strtolower($userData['role']) === 'student') {
                    $student = Student::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'student_id' => 'ST' . date('Y') . rand(1000, 9999),
                            'first_name' => 'Test',
                            'last_name' => 'Student',
                            'email' => $email,
                            'gender' => 'male',
                            'date_of_birth' => Carbon::now()->subYears(15),
                            'class_id' => 1, // Default values, create these if needed
                            'section_id' => 1,
                            'admission_date' => Carbon::now()->subMonths(6),
                            'blood_group' => 'O+',
                            'address' => '456 Student Avenue',
                            'phone' => '555-5678',
                            'photo' => 'students/default.png',
                        ]
                    );
                    
                    // Create portal user for student
                    $portalUser = PortalUser::firstOrNew(['email' => $email]);
                    $portalUser->name = $userData['name'];
                    $portalUser->email = $email;
                    $portalUser->password = Hash::make('password'); // Create a new hash directly
                    $portalUser->user_type = 'student';
                    $portalUser->related_id = $student->id; // Use actual student ID
                    $portalUser->status = true;
                    $portalUser->save();
                }
                
                // Create parent record if needed
                if (strtolower($userData['role']) === 'parent') {
                    $parent = ParentModel::firstOrCreate(
                        ['user_id' => $user->id],
                        [
                            'first_name' => 'Test',
                            'last_name' => 'Parent',
                            'email' => $email,
                            'gender' => 'male',
                            'occupation' => 'Engineer',
                            'address' => '789 Parent Road',
                            'phone' => '555-9012',
                            'photo' => 'parents/default.png',
                        ]
                    );
                    
                    // Create portal user for parent
                    $portalUser = PortalUser::firstOrNew(['email' => $email]);
                    $portalUser->name = $userData['name'];
                    $portalUser->email = $email;
                    $portalUser->password = Hash::make('password'); // Create a new hash directly
                    $portalUser->user_type = 'parent';
                    $portalUser->related_id = $parent->id; // Use actual parent ID
                    $portalUser->status = true;
                    $portalUser->save();
                }
                
                echo "Portal user created: {$email} / password\n";
            }
        }
    }
}
