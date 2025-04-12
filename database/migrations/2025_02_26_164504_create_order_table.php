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
        if (!Schema::hasTable('c')) {
            Schema::create('order', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->double('shipping', 10, 2);
                $table->double('subtotal', 10, 2);
                $table->string('coupon_code')->nullable();
                $table->double('discount', 10, 2)->nullable();
                $table->double('grandTotal', 10, 2)->nullable();

                $table->string('firstName');
                $table->string('lastName');
                $table->string('email');
                $table->string('mobile');
                $table->foreignId('country_id')->constrained('country')->onDelete('cascade');
                $table->text('address');
                $table->string('appartment')->nullable();
                $table->string('city');
                $table->string('state');
                $table->integer('zip');
                $table->text('note');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};
