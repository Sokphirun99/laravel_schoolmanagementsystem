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
        Schema::table('parents', function (Blueprint $table) {
            // Add missing columns
            $table->string('father_email')->nullable()->after('father_phone');
            $table->string('mother_email')->nullable()->after('mother_phone');
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn(['father_email', 'mother_email']);
        });

        Schema::table('parents', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
        });
    }
};
