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
        if (!Schema::hasTable('prod_img')) {
            Schema::create('prod_img', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('prod')->onDelete('set null');
                $table->string('image');
                $table->integer('sort_order')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_img');
    }
};
