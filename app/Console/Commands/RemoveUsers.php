<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Parents;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;

class RemoveUsers extends Command
{
    protected $signature = 'users:remove {role? : The role ID to remove (leave empty to see options)}';
    protected $description = 'Remove users by role with proper constraint handling';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $role = $this->argument('role');

        if (!$role) {
            // Show the available roles if no role was specified
            $roles = DB::table('roles')->get();
            $this->info('Available roles:');
            foreach ($roles as $r) {
                $this->line("- {$r->id}: {$r->display_name}");
            }

            $role = $this->ask('Which role ID would you like to remove? (Leave blank to cancel)');
            if (!$role) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        // Confirm before proceeding
        $roleName = DB::table('roles')->where('id', $role)->value('display_name');
        if (!$roleName) {
            $this->error("Role ID {$role} not found!");
            return 1;
        }

        if (!$this->confirm("Are you sure you want to remove all users with role '{$roleName}' (ID: {$role})? This can't be undone!", false)) {
            $this->info('Operation cancelled.');
            return 0;
        }

        // Begin a transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Get user IDs to be deleted
            $userIds = User::where('role_id', $role)->pluck('id')->toArray();
            $count = count($userIds);

            if ($count === 0) {
                $this->info("No users found with role '{$roleName}'. Nothing to delete.");
                return 0;
            }

            $this->info("Found {$count} users to remove. Handling related records first...");

            // Update students table
            $studentsUpdated = Student::whereIn('user_id', $userIds)
                ->update(['user_id' => null]);
            $this->info("- Updated {$studentsUpdated} student records");

            // Update teachers table
            $teachersUpdated = Teacher::whereIn('user_id', $userIds)
                ->update(['user_id' => null]);
            $this->info("- Updated {$teachersUpdated} teacher records");

            // Update parents table
            $parentsUpdated = Parents::whereIn('user_id', $userIds)
                ->update(['user_id' => null]);
            $this->info("- Updated {$parentsUpdated} parent records");

            // Delete from user_roles table
            $rolesDeleted = UserRole::whereIn('user_id', $userIds)->delete();
            $this->info("- Removed {$rolesDeleted} user role associations");

            // Delete the users
            $usersDeleted = User::where('role_id', $role)->delete();
            $this->info("Successfully removed {$usersDeleted} users with role '{$roleName}'");

            DB::commit();
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error removing users: {$e->getMessage()}");
            return 1;
        }
    }
}
