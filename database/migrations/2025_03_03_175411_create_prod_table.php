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
        if (!Schema::hasTable('prod')) {
            Schema::create('prod', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug');
                $table->string('description')->nullable();
                $table->double('price', 10, 2);
                $table->double('compare_price', 10, 2)->nullable();
                
                // Make foreign keys nullable and set to NULL when the parent record is deleted
                $table->foreignId('cate_id')->nullable()->constrained('cat')->onDelete('set null');
                $table->foreignId('brand_id')->nullable()->constrained('brand')->onDelete('set null');
                $table->foreignId('sub_cate_id')->nullable()->constrained('sub_cate')->onDelete('set null');
                
                $table->enum('isfeature', ['Yes', 'No'])->default('No');
                $table->string('sku');
                $table->string('barcode')->nullable();
                $table->enum('trackqty', ['Yes', 'No'])->default('Yes');
                $table->integer('qty')->nullable();
                $table->integer('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod');
    }
};
