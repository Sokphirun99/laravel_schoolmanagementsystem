<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\UserRole;

return new class extends Migration
{
    /**
     * Run the migrations to sync existing user roles from role_id to the user_roles table.
     * This ensures backward compatibility while transitioning to the many-to-many role system.
     *
     * @return void
     */
    public function up()
    {
        // Insert existing user-role relationships into the user_roles table
        $users = DB::table('users')->whereNotNull('role_id')->select('id', 'role_id')->get();

        foreach ($users as $user) {
            // Check if the relationship doesn't already exist
            $exists = DB::table('user_roles')
                ->where('user_id', $user->id)
                ->where('role_id', $user->role_id)
                ->exists();

            if (!$exists) {
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This is a data migration, so we don't need to revert anything specific
    }
};
