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
        Schema::table('teachers', function (Blueprint $table) {
            // Add department_id column
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('department')
                  ->constrained('departments')
                  ->onDelete('set null');

            // Eventually we might want to deprecate the string 'department' column
            // but for now we'll keep it for backward compatibility
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['department_id']);

            // Drop the column
            $table->dropColumn('department_id');
        });
    }
};
