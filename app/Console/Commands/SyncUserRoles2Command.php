<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Parents;
use App\Models\User;

class SyncUserRoles2Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sync-roles2 {--force : Run without confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize user roles using direct DB queries to avoid model issues';

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

        // Get all roles from the database
        $roles = DB::table('roles')->get();
        $roleMap = [];

        foreach ($roles as $role) {
            $roleMap[$role->name] = $role->id;
            $this->info("Found role: {$role->name} (ID: {$role->id})");
        }

        if (empty($roleMap)) {
            $this->error("No roles found in the database. Cannot continue.");
            return Command::FAILURE;
        }

        // Step 1: Sync from role_id to user_roles table
        $this->syncFromRoleIdToUserRoles($roleMap);

        // Step 2: Sync from related models (Student, Teacher, Parent)
        $this->syncFromRelatedModels($roleMap);

        $this->info('User role synchronization completed successfully.');

        return Command::SUCCESS;
    }

    /**
     * Sync roles from role_id column to user_roles table
     */
    private function syncFromRoleIdToUserRoles($roleMap)
    {
        $this->info('Syncing from role_id column to user_roles table...');

        $users = DB::table('users')->whereNotNull('role_id')->get();
        $total = count($users);
        $synced = 0;

        $this->output->progressStart($total);

        foreach ($users as $user) {
            // Check if this relationship already exists
            $exists = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->where('role_id', $user->role_id)
                ->exists();

            if (!$exists) {
                // Create the relationship in user_roles
                DB::table('user_roles')->insert([
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
     * Sync roles from related models (Student, Teacher, Parent)
     */
    private function syncFromRelatedModels($roleMap)
    {
        $this->info('Syncing roles from related models (Student, Teacher, Parent)...');

        // Sync students
        if (isset($roleMap['student'])) {
            $this->syncStudentRoles($roleMap['student']);
        } else {
            $this->warn('Student role not found.');
        }

        // Sync teachers
        if (isset($roleMap['teacher'])) {
            $this->syncTeacherRoles($roleMap['teacher']);
        } else {
            $this->warn('Teacher role not found.');
        }

        // Sync parents
        if (isset($roleMap['parent'])) {
            $this->syncParentRoles($roleMap['parent']);
        } else {
            $this->warn('Parent role not found.');
        }
    }

    /**
     * Sync student roles
     */
    private function syncStudentRoles($roleId)
    {
        $students = Student::whereHas('user')->get();
        $total = $students->count();
        $synced = 0;

        $this->output->progressStart($total);

        foreach ($students as $student) {
            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $student->user_id, 'role_id' => $roleId],
                []
            );
            $synced++;

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("Synced {$synced} student roles.");
    }

    /**
     * Sync teacher roles
     */
    private function syncTeacherRoles($roleId)
    {
        $teachers = Teacher::whereHas('user')->get();
        $total = $teachers->count();
        $synced = 0;

        $this->output->progressStart($total);

        foreach ($teachers as $teacher) {
            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $teacher->user_id, 'role_id' => $roleId],
                []
            );
            $synced++;

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("Synced {$synced} teacher roles.");
    }

    /**
     * Sync parent roles
     */
    private function syncParentRoles($roleId)
    {
        $parents = Parents::whereHas('user')->get();
        $total = $parents->count();
        $synced = 0;

        $this->output->progressStart($total);

        foreach ($parents as $parent) {
            DB::table('user_roles')->updateOrInsert(
                ['user_id' => $parent->user_id, 'role_id' => $roleId],
                []
            );
            $synced++;

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info("Synced {$synced} parent roles.");
    }
}
