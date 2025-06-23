<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // The role column for our role system (separate from Voyager's role_id which uses a different approach)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default(User::ROLE_ADMIN)->after('email');
            }
            
            // Add status field (active/inactive) defaulting to active (true)
            if (!Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(true)->after(Schema::hasColumn('users', 'role') ? 'role' : 'email');
            }
            
            // Add phone field
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('status');
            }
            
            // Add login tracking fields
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable()->after('last_login_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['role', 'status', 'phone', 'last_login_at', 'last_login_ip'];
            $columnsToRemove = [];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $columnsToRemove[] = $column;
                }
            }
            
            if (!empty($columnsToRemove)) {
                $table->dropColumn($columnsToRemove);
            }
        });
    }
};
