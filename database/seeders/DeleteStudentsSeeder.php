<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeleteStudentsSeeder extends Seeder
{
    public function run()
    {
        if ($this->command->confirm('Do you want to delete ALL existing students and their user accounts?', false)) {
            $this->command->info('Deleting student records...');

            // Disable foreign key checks to avoid constraint issues
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Get count of records before deletion
            $studentCount = DB::table('students')->count();
            $userCount = DB::table('users')->where('role_id', 3)->count();
            $otherUserCount = DB::table('users')->where('email', 'LIKE', 'student%@school.test')->count();

            // Truncate students table (remove all records)
            DB::table('students')->truncate();

            // Remove student users (role_id = 3)
            DB::table('users')->where('role_id', 3)->delete();

            // Also delete ANY user with student email format
            DB::table('users')->where('email', 'LIKE', 'student%@school.test')->delete();

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            $this->command->info("Deleted {$studentCount} student records.");
            $this->command->info("Deleted {$userCount} student user accounts with role_id=3.");
            $this->command->info("Deleted {$otherUserCount} additional user accounts with student email format.");
            $this->command->info('All student data has been removed successfully.');
        } else {
            $this->command->info('Operation cancelled. No records were deleted.');
        }
    }
}
