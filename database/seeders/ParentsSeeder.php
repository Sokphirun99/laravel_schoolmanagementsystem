<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Parents;
use App\Models\Student;
use Faker\Factory as Faker;

class ParentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing records if confirmed
        if ($this->command->confirm('Do you want to clear existing parent and their user records?', false))
        {
            $this->command->info('Clearing existing parent records...');

            // First, set parent_id to null for all students
            DB::table('students')->update(['parent_id' => null]);

            // Now it's safe to truncate the parents table
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Parents::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            User::where('role_id', 4)->delete(); // Delete parent users only
            $this->command->info('Existing parent records cleared successfully.');
        }

        // Create parent role if it doesn't exist
        $parentRole = \TCG\Voyager\Models\Role::firstOrCreate(
            ['name' => 'parent'],
            ['display_name' => 'Parent']
        );

        // Use updateOrCreate instead of firstOrCreate
        \TCG\Voyager\Models\DataType::updateOrCreate(
            ['name' => 'parents'],
            [
                'slug' => 'parents',
                'display_name_singular' => 'Parent',
                'display_name_plural' => 'Parents',
                'icon' => 'voyager-people',
                'model_name' => 'App\\Models\\Parents',
                'generate_permissions' => 1,
                'server_side' => 0
            ]
        );

        $faker = Faker::create();
        $defaultPassword = Hash::make('password');

        // Get highest existing email number to avoid duplicates
        $maxEmail = DB::table('users')
            ->where('email', 'like', 'parent%@school.test')
            ->max(DB::raw('CAST(SUBSTRING(email, 7, LOCATE("@", email) - 7) AS UNSIGNED)'));

        $nextEmailNumber = ($maxEmail) ? $maxEmail + 1 : 1;
        $this->command->info("Starting email sequence from: parent{$nextEmailNumber}@school.test");

        // Get existing student to assign parents
        $studentCollection = Student::all();
        if ($studentCollection->isEmpty()) {
            $this->command->error('No student found! Please run StudentsSeeder first.');
            return;
        }

        $this->command->info('Creating 150 parent accounts...');

        $createdCount = 0;
        $studentIndex = 0;

        // Create 150 parent accounts
        for ($i = 0; $i < 150; $i++) {
            try {
                // Create user account with unique email
                $emailNum = $nextEmailNumber + $i;
                $userEmail = "parent{$emailNum}@school.test";

                // Check if email already exists
                if (User::where('email', $userEmail)->exists()) {
                    $this->command->warn("Email {$userEmail} already exists. Skipping...");
                    continue;
                }

                // Create user account
                $user = new User();
                $user->name = $faker->firstName . ' ' . $faker->lastName;
                $user->email = $userEmail;
                $user->password = $defaultPassword;
                $user->role_id = $parentRole->id;
                $user->avatar = 'users/default.png'; // Add default avatar
                $user->save();

                // Create parent record
                $parent = new Parents();
                $parent->user_id = $user->id;
                $parent->father_name = $faker->firstName('male') . ' ' . $faker->lastName;
                $parent->mother_name = $faker->firstName('female') . ' ' . $faker->lastName;
                $parent->father_occupation = $faker->jobTitle;
                $parent->mother_occupation = $faker->jobTitle;
                $parent->father_phone = $faker->phoneNumber;
                $parent->mother_phone = $faker->phoneNumber;
                $parent->father_email = "father_" . $emailNum . "@example.com";
                $parent->mother_email = "mother_" . $emailNum . "@example.com";
                $parent->address = $faker->address;
                $parent->save();

                // Assign up to 3 student to this parent
                $studentCount = $faker->numberBetween(1, 3);
                for ($j = 0; $j < $studentCount && $studentIndex < $studentCollection->count(); $j++) {
                    $student = $studentCollection[$studentIndex];
                    $student->parent_id = $parent->id;
                    $student->save();
                    $studentIndex++;

                    // If we've assigned all student, reset to the beginning
                    if ($studentIndex >= $studentCollection->count()) {
                        $studentIndex = 0;
                    }
                }

                $createdCount++;

                if ($i === 0 || ($i+1) % 50 === 0) {
                    $this->command->info("Created {$createdCount} parent accounts so far...");
                }
            } catch (\Exception $e) {
                $this->command->error("Error creating parent #{$i}: " . $e->getMessage());
            }
        }

        $this->command->info("Successfully created {$createdCount} parent accounts!");
    }
}


