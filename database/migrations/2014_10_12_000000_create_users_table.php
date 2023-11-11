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

        Schema::create('users', function (Blueprint $table) {
            $table->id('id')->foreign('products.user_id');
            $table->unsignedBigInteger('parent_id')->default(0);
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('status');
            $table->string('type');
            $table->string('store_id');
            $table->rememberToken();
            $table->timestamps();


        });
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
