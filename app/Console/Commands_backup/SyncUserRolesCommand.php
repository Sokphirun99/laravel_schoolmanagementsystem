<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Parents;
use Illuminate\Support\Facades\DB;

class SyncUserRolesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync-roles {--force : Run without confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize user roles between the legacy role_id column and the new user_roles table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (!$this->option('force') && !$this->confirm('This will sync all user roles. Continue?')) {
            $this->info('Operation cancelled.');
            return Command::SUCCESS;
        }

        $this->info('Starting user role synchronization...');

        // Step 1: Sync from role_id to user_roles table
        $this->syncFromRoleIdToUserRoles();

        // Step 2: Sync from related models (Student, Teacher, Parent)
        $this->syncFromRelatedModels();

        // Step 3: Report conflicting roles
        $this->reportConflictingRoles();

        $this->info('User role synchronization completed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Sync roles from role_id column to user_roles table
     */
    private function syncFromRoleIdToUserRoles()
    {
        $this->info('Syncing from role_id column to user_roles table...');

        $users = User::whereNotNull('role_id')->get();
        $total = $users->count();
        $synced = 0;

        $this->output->progressStart($total);

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
                $synced++;
            }

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("Synced {$synced} of {$total} users from role_id.");
    }

    /**
     * Sync roles from related models
     */
    private function syncFromRelatedModels()
    {
        $this->info('Syncing roles from related models (Student, Teacher, Parent)...');

        // Sync students        // Query directly by ID, avoiding model soft-delete constraints
        $studentRole = DB::table('roles')->where('name', 'student')->first();
        if ($studentRole) {
            $students = Student::whereHas('user')->get();
            $total = $students->count();
            $synced = 0;

            $this->output->progressStart($total);

            foreach ($students as $student) {
                UserRole::firstOrCreate([
                    'user_id' => $student->user_id,
                    'role_id' => $studentRole->id
                ]);
                $synced++;

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info("Synced {$synced} student roles.");
        } else {
            $this->warn('Student role not found.');
        }        // Sync teachers using direct DB query
        $teacherRole = DB::table('roles')->where('name', 'teacher')->first();
        if ($teacherRole) {
            $teachers = Teacher::whereHas('user')->get();
            $total = $teachers->count();
            $synced = 0;

            $this->output->progressStart($total);

            foreach ($teachers as $teacher) {
                UserRole::firstOrCreate([
                    'user_id' => $teacher->user_id,
                    'role_id' => $teacherRole->id
                ]);
                $synced++;

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info("Synced {$synced} teacher roles.");
        } else {
            $this->warn('Teacher role not found.');
        }        // Sync parents using direct DB query
        $parentRole = DB::table('roles')->where('name', 'parent')->first();
        if ($parentRole) {
            $parents = Parents::whereHas('user')->get();
            $total = $parents->count();
            $synced = 0;

            $this->output->progressStart($total);

            foreach ($parents as $parent) {
                UserRole::firstOrCreate([
                    'user_id' => $parent->user_id,
                    'role_id' => $parentRole->id
                ]);
                $synced++;

                $this->output->progressAdvance();
            }

            $this->output->progressFinish();
            $this->info("Synced {$synced} parent roles.");
        } else {
            $this->warn('Parent role not found.');
        }
    }

    /**
     * Report users with conflicting roles
     */
    private function reportConflictingRoles()
    {
        $this->info('Checking for conflicting roles...');

        $conflictingUsers = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->whereColumn('users.role_id', '!=', 'user_roles.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.role_id')
            ->distinct()
            ->get();

        if ($conflictingUsers->count() > 0) {
            $this->warn("Found {$conflictingUsers->count()} users with conflicting roles:");

            $headers = ['ID', 'Name', 'Email', 'Primary Role', 'Other Roles'];
            $rows = [];

            foreach ($conflictingUsers as $user) {
                $otherRoles = UserRole::where('user_id', $user->id)
                    ->where('role_id', '!=', $user->role_id)
                    ->with('role')
                    ->get()
                    ->map(function ($userRole) {
                        return $userRole->role->name . " (ID: {$userRole->role_id})";
                    })
                    ->implode(', ');

                $primaryRole = DB::table('roles')->where('id', $user->role_id)->first();
                $primaryRoleName = $primaryRole ? $primaryRole->name . " (ID: {$primaryRole->id})" : "Unknown (ID: {$user->role_id})";

                $rows[] = [$user->id, $user->name, $user->email, $primaryRoleName, $otherRoles];
            }

            $this->table($headers, $rows);

            if ($this->confirm('Would you like to resolve these conflicts by making the role_id match the first role in user_roles?')) {
                $this->resolveConflicts();
            }
        } else {
            $this->info('No conflicting roles found.');
        }
    }

    /**
     * Resolve conflicting roles
     */
    private function resolveConflicts()
    {
        $this->info('Resolving conflicts...');

        $users = User::whereHas('roles', function($query) {
            $query->whereColumn('roles.id', '!=', 'users.role_id');
        })->get();

        $updated = 0;

        foreach ($users as $user) {
            // Get the first role without trying to order by created_at since it doesn't exist
            $primaryUserRole = $user->roles()->first();

            if ($primaryUserRole) {
                $user->role_id = $primaryUserRole->id;
                $user->save();
                $updated++;
            }
        }

        $this->info("Updated primary role for {$updated} users.");
    }
}
