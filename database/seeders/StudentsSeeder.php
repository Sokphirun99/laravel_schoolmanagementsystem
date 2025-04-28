<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\User;
use App\Models\Section;
use App\Models\Parents;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class StudentsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Clear existing student and user records
        if ($this->command->confirm('Do you want to clear existing student and user records? This will delete ALL existing students and their users!', false)) {
            $this->command->info('Clearing existing student and user records...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Truncate students table
            DB::table('students')->truncate();

            // Delete student users - but also delete ANY user with student email format
            DB::table('users')->where('role_id', 3)->delete();
            DB::table('users')->where('email', 'LIKE', 'student%@school.test')->delete();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->info('Existing student and user records cleared successfully.');
        }

        // First, create a school if none exists
        $schoolId = null;
        if (Schema::hasTable('schools')) {
            $schoolExists = DB::table('schools')->exists();
            if (!$schoolExists) {
                $schoolId = DB::table('schools')->insertGetId([
                    'name' => 'Default School',
                    'address' => $faker->address,
                    'phone' => $faker->phoneNumber,
                    'email' => 'school@example.com',
                    'website' => 'https://school.example.com',
                    'code' => 'SCH001',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->command->info("Created default school with ID: {$schoolId}");
            } else {
                $schoolId = DB::table('schools')->first()->id;
                $this->command->info("Using existing school with ID: {$schoolId}");
            }
        } else {
            $this->command->warn('Schools table not found. Students will have null school_id.');
        }

        // Create student role if it doesn't exist
        $studentRole = Role::firstOrCreate(
            ['name' => 'student'],
            [
                'display_name' => 'Student',
                'description' => 'Student role'
            ]
        );

        // EXPLICITLY display the role ID to verify it's correct
        $studentRoleId = $studentRole->id;
        $this->command->info("Using student role ID: {$studentRoleId}");

        // Get available sections or create dummy IDs
        $sections = [];
        if (Schema::hasTable('sections')) {
            $sections = Section::pluck('id')->toArray();
        }
        if (empty($sections)) {
            $this->command->warn('No sections found. Students will have null section.');
        }

        // Get available parents or create dummy IDs
        $parents = [];
        if (Schema::hasTable('parents')) {
            $parents = Parents::pluck('id')->toArray();
        }
        if (empty($parents)) {
            $this->command->warn('No parents found. Students will have null parent.');
        }

        // Create default password and use student role ID
        $defaultPassword = Hash::make('password123');

        // Find the highest existing admission number to avoid duplicates
        $maxAdmission = DB::table('students')
            ->where('admission_number', 'LIKE', 'STU%')
            ->max('admission_number');

        $nextAdmissionNumber = 1001; // Default starting number
        if ($maxAdmission) {
            preg_match('/STU(\d+)/', $maxAdmission, $matches);
            if (isset($matches[1])) {
                $nextAdmissionNumber = (int)$matches[1] + 1;
            }
        }
        $this->command->info("Starting admission numbers from: STU" . $nextAdmissionNumber);

        // Use database transaction for data integrity
        DB::beginTransaction();

        try {
            $this->command->info('Creating 200 student accounts...');
            $this->command->getOutput()->progressStart(200);

            // Find the highest existing email number to avoid duplicates
            $maxEmail = DB::table('users')
                ->where('email', 'LIKE', 'student%@school.test')
                ->max('email');

            $nextEmailNumber = 1; // Default starting number

            if ($maxEmail) {
                preg_match('/student(\d+)@school\.test/', $maxEmail, $matches);
                if (isset($matches[1])) {
                    $nextEmailNumber = (int)$matches[1] + 1;
                }
            }
            $this->command->info("Starting email sequence from: student{$nextEmailNumber}@school.test");

            // When creating users, explicitly output the first one's details
            $firstUserCreated = false;

            for ($i = 0; $i < 200; $i++) {
                // Create user account with truly unique email
                $emailNum = $nextEmailNumber + $i;
                $userEmail = "student{$emailNum}@school.test";

                // First check if this email already exists
                $existingUser = DB::table('users')->where('email', $userEmail)->first();
                if ($existingUser) {
                    $this->command->warn("Email {$userEmail} already exists - skipping");
                    continue;
                }

                $user = new User();
                $user->name = $faker->firstName . ' ' . $faker->lastName;
                $user->email = $userEmail;
                $user->password = $defaultPassword;
                $user->role_id = $studentRoleId; // Make sure we're using the correct role ID
                $user->avatar = 'users/default.png';
                $user->save();

                if (!$firstUserCreated) {
                    $this->command->info("First user created with ID: {$user->id}, Email: {$user->email}, Role ID: {$user->role_id}");
                    $firstUserCreated = true;
                }

                // Create student record with unique admission number
                $gender = $faker->randomElement(['Male', 'Female']);
                $firstName = $gender === 'Male' ? $faker->firstNameMale : $faker->firstNameFemale;

                $student = new Student();
                $student->admission_number = 'STU' . str_pad($nextAdmissionNumber + $i, 5, '0', STR_PAD_LEFT);
                $student->first_name = $firstName;
                $student->last_name = $faker->lastName;
                $student->gender = $gender;
                $student->date_of_birth = $faker->dateTimeBetween('-18 years', '-6 years')->format('Y-m-d');
                $student->blood_group = $faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);
                $student->address = $faker->address;
                $student->phone = $faker->phoneNumber;
                $student->emergency_contact = $faker->phoneNumber;
                $student->admission_date = $faker->dateTimeBetween('-4 years', 'now')->format('Y-m-d');
                $student->user_id = $user->id;
                $student->section_id = !empty($sections) ? $faker->randomElement($sections) : null;
                $student->parent_id = !empty($parents) ? $faker->randomElement($parents) : null;
                $student->status = $faker->randomElement(['active', 'inactive']);
                $student->medical_condition = $faker->optional(0.3)->sentence();
                $student->photo = null;
                $student->school_id = $schoolId;
                $student->save();

                $this->command->getOutput()->progressAdvance();

                // Commit in smaller batches for 200 records
                if ($i % 50 === 49) {
                    DB::commit();
                    DB::beginTransaction();
                    $this->command->info("Created " . ($i + 1) . " students so far...");
                }
            }

            DB::commit();
            $this->command->getOutput()->progressFinish();
            $this->command->info('200 student accounts created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error creating student accounts: ' . $e->getMessage());
        }
    }
}

