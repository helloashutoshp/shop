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
        if (!Schema::hasTable('order_item')) {
            Schema::create('order_item', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('order')->onDelete('cascade');
                $table->foreignId('product_id')->constrained('product')->onDelete('cascade');
                $table->string('name');
                $table->integer('qty');
                $table->double('price', 10, 2);
                $table->double('total', 10, 2);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};
