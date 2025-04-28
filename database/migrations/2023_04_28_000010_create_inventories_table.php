<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('category');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->date('purchase_date');
            $table->string('supplier_name')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->string('invoice_number')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['available', 'damaged', 'lost', 'disposed'])->default('available');
            $table->text('remarks')->nullable();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
