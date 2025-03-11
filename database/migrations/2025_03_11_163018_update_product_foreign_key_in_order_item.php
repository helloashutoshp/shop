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
        if (Schema::hasTable('order_item')) {
            Schema::table('order_item', function (Blueprint $table) {
                // Drop the old foreign key
                $table->dropForeign(['product_id']);

                // Add the new foreign key constraint
                $table->foreign('product_id')->references('id')->on('prod')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_item')) {
            Schema::table('order_item', function (Blueprint $table) {
                // Rollback to the original foreign key
                $table->dropForeign(['product_id']);
                $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            });
        }
    }
};
