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
        if (!Schema::hasTable('product')) {
            Schema::create('product', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug');
                $table->string('description')->nullable();
                $table->double('price', 10, 2);
                $table->double('compare_price', 10, 2)->nullable();
                $table->foreignId('cate_id')->constrained('cat')->onDelete('cascade');
                $table->foreignId('brand_id')->nullable()->constrained('brand')->onDelete('cascade');
                $table->foreignId('sub_cate_id')->nullable()->constrained('sub_cate')->onDelete('cascade');
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
        Schema::dropIfExists('product');
    }
};
