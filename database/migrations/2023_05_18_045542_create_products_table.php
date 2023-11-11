<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->float('buying_price');
            $table->float('price');
            $table->integer('quantity');
            $table->integer('stock');
            $table->longText('description');
            $table->foreignId('category_id')->constrained('categories');
            $table->integer('sub_category_id');
            $table->foreignId('store_id')->constrained('stores');
            $table->integer('creator_id');
            $table->date('expiry_date');
            $table->integer('variant')->nullable();
            $table->string('days_left')->nullable();
            $table->enum('status', ['active','deactive', 'deleted'])->default('active');
            $table->timestamps();

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
