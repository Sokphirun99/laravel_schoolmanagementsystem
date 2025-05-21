<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the department_id column doesn't exist before adding it
        if (!Schema::hasColumn('subjects', 'department_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                // Add department_id column
                $table->foreignId('department_id')
                    ->nullable()
                    ->after('class_id')
                    ->constrained('departments')
                    ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Only drop the column if it exists
        if (Schema::hasColumn('subjects', 'department_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                // Drop the foreign key constraint
                $table->dropForeign(['department_id']);

                // Drop the column
                $table->dropColumn('department_id');
            });
        }
    }
};
