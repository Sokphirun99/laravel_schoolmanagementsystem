<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add 'teacher' to enum and make related_id nullable (using raw SQL to avoid DBAL requirement)
        DB::statement("ALTER TABLE `portal_users` MODIFY `user_type` ENUM('parent','student','teacher') NOT NULL");
        DB::statement("ALTER TABLE `portal_users` MODIFY `related_id` BIGINT UNSIGNED NULL");
    }

    public function down(): void
    {
        // Revert enum and related_id nullability
        DB::statement("ALTER TABLE `portal_users` MODIFY `user_type` ENUM('parent','student') NOT NULL");
        DB::statement("ALTER TABLE `portal_users` MODIFY `related_id` BIGINT UNSIGNED NOT NULL");
    }
};
