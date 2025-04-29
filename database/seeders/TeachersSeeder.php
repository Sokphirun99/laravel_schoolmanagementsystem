<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\User;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Faker\Factory as Faker;

class TeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Clear existing teacher records if requested
        if ($this->command->confirm('Do you want to clear existing teacher records?', false)) {
            $this->command->info('Clearing existing teacher records...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('teachers')->truncate();
            DB::table('users')->where('role_id', 2)->delete(); // Assuming role_id 2 is for teachers
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $this->command->info('Existing teacher records cleared.');
        }

        // Get or create teacher role
        $teacherRole = Role::firstOrCreate(
            ['name' => 'teacher'],
            [
                'display_name' => 'Teacher',
                'description' => 'Teacher role'
            ]
        );

        $teacherRoleId = $teacherRole->id;
        $this->command->info("Using teacher role ID: {$teacherRoleId}");

        // Get school ID
        $schoolId = null;
        if (Schema::hasTable('schools')) {
            $school = DB::table('schools')->first();
            if ($school) {
                $schoolId = $school->id;
                $this->command->info("Using school ID: {$schoolId}");
            } else {
                $this->command->warn('No school found. Teachers will have null school_id.');
            }
        }

        // First, let's check which columns actually exist in the teachers table
        $teacherColumns = Schema::getColumnListing('teachers');
        $this->command->info('Available teacher columns: ' . implode(', ', $teacherColumns));

        // Create teacher accounts
        $defaultPassword = Hash::make('password123');

        // Find the next available teacher email number
        $maxEmail = DB::table('users')
            ->where('email', 'LIKE', 'teacher%@school.test')
            ->max('email');

        $nextEmailNumber = 1;
        if ($maxEmail) {
            preg_match('/teacher(\d+)@school\.test/', $maxEmail, $matches);
            if (isset($matches[1])) {
                $nextEmailNumber = (int)$matches[1] + 1;
            }
        }

        $this->command->info("Starting email sequence from: teacher{$nextEmailNumber}@school.test");

        DB::beginTransaction();

        try {
            $this->command->info('Creating 25 teacher accounts...');
            $this->command->getOutput()->progressStart(25);

            for ($i = 0; $i < 25; $i++) {
                $emailNum = $nextEmailNumber + $i;
                $email = "teacher{$emailNum}@school.test";

                // Create user account
                $gender = $faker->randomElement(['Male', 'Female']);
                $firstName = $gender === 'Male' ? $faker->firstNameMale : $faker->firstNameFemale;
                $lastName = $faker->lastName;
                $fullName = "{$firstName} {$lastName}";

                $user = new User();
                $user->name = $fullName;
                $user->email = $email;
                $user->password = $defaultPassword;
                $user->role_id = $teacherRoleId;
                $user->avatar = 'users/default.png';
                $user->save();

                // Create teacher record - only set fields that exist in the table
                $teacher = new Teacher();
                $teacher->employee_id = 'TCH' . str_pad($emailNum, 4, '0', STR_PAD_LEFT);
                $teacher->first_name = $firstName;
                $teacher->last_name = $lastName;

                // Only set these fields if they exist in the table
                if (in_array('gender', $teacherColumns)) {
                    $teacher->gender = $gender;
                }

                if (in_array('date_of_birth', $teacherColumns)) {
                    $teacher->date_of_birth = $faker->dateTimeBetween('-55 years', '-25 years')->format('Y-m-d');
                }

                // Note: Database has 'qualification' (singular), not 'qualifications' (plural)
                if (in_array('qualification', $teacherColumns)) {
                    $teacher->qualification = $faker->randomElement(['B.Ed', 'M.Ed', 'Ph.D', 'M.A', 'B.A']);
                }

                if (in_array('address', $teacherColumns)) {
                    $teacher->address = $faker->address;
                }

                if (in_array('phone', $teacherColumns)) {
                    $teacher->phone = $faker->phoneNumber;
                }

                if (in_array('email', $teacherColumns)) {
                    $teacher->email = $email;
                }

                if (in_array('joining_date', $teacherColumns)) {
                    $teacher->joining_date = $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d');
                }

                $teacher->user_id = $user->id;

                // Add values for other required columns
                if (in_array('department', $teacherColumns)) {
                    $teacher->department = $faker->randomElement([
                        'Science', 'Arts', 'Commerce', 'Mathematics', 'Languages',
                        'Physical Education', 'Computer Science'
                    ]);
                }

                if (in_array('designation', $teacherColumns)) {
                    $teacher->designation = $faker->randomElement([
                        'Senior Teacher', 'Junior Teacher', 'HOD', 'Assistant Teacher',
                        'Lab Assistant', 'Vice Principal'
                    ]);
                }

                if (in_array('salary', $teacherColumns)) {
                    $teacher->salary = $faker->numberBetween(30000, 80000);
                }

                if (in_array('status', $teacherColumns)) {
                    $teacher->status = $faker->randomElement(['active', 'inactive']);
                }

                if (in_array('school_id', $teacherColumns)) {
                    $teacher->school_id = $schoolId;
                }

                if (in_array('photo', $teacherColumns)) {
                    $teacher->photo = null; // We'll leave this null for now
                }

                $teacher->save();

                $this->command->getOutput()->progressAdvance();
            }

            DB::commit();
            $this->command->getOutput()->progressFinish();
            $this->command->info('25 teacher accounts created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error creating teacher accounts: ' . $e->getMessage());
        }
    }
}
