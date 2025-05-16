<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;

class SyncUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds to sync user roles.
     * This ensures that all users with role_id also have an entry in the user_roles table.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Syncing user roles to the user_roles table...');

        // Get all users with a role_id
        $users = User::whereNotNull('role_id')->get();
        $totalUsers = $users->count();
        $syncCount = 0;

        foreach ($users as $user) {
            // Check if this relationship already exists
            $exists = UserRole::where('user_id', $user->id)
                ->where('role_id', $user->role_id)
                ->exists();

            if (!$exists) {
                // Create the relationship in user_roles
                UserRole::create([
                    'user_id' => $user->id,
                    'role_id' => $user->role_id
                ]);
                $syncCount++;
            }
        }

        $this->command->info("Synced roles for $syncCount out of $totalUsers users.");

        // Special handling for teachers and students
        $this->syncStudentTeacherRoles();
    }

    /**
     * Specifically sync student and teacher roles based on their respective models
     */
    private function syncStudentTeacherRoles()
    {
        $this->command->info('Syncing roles for students and teachers based on their model relationships...');

        // Get student role
        $studentRole = Role::where('name', 'student')->first();
        if ($studentRole) {
            // Get all students through their model relationship
            $studentUserIds = DB::table('students')
                ->join('users', 'students.user_id', '=', 'users.id')
                ->pluck('users.id')
                ->toArray();

            foreach ($studentUserIds as $userId) {
                UserRole::firstOrCreate([
                    'user_id' => $userId,
                    'role_id' => $studentRole->id
                ]);
            }
            $this->command->info('Synced ' . count($studentUserIds) . ' student roles.');
        }

        // Get teacher role
        $teacherRole = Role::where('name', 'teacher')->first();
        if ($teacherRole) {
            // Get all teachers through their model relationship
            $teacherUserIds = DB::table('teachers')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->pluck('users.id')
                ->toArray();

            foreach ($teacherUserIds as $userId) {
                UserRole::firstOrCreate([
                    'user_id' => $userId,
                    'role_id' => $teacherRole->id
                ]);
            }
            $this->command->info('Synced ' . count($teacherUserIds) . ' teacher roles.');
        }
    }
}
