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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('isbn')->nullable()->unique();
            $table->string('author');
            $table->string('publisher')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('book_categories')->nullOnDelete();
            $table->text('description')->nullable();
            $table->integer('publication_year')->nullable();
            $table->string('edition')->nullable();
            $table->integer('total_quantity')->default(0);
            $table->integer('available_quantity')->default(0);
            $table->string('shelf_location')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->string('language')->nullable();
            $table->integer('pages')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
};
