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
            Schema::table('prod', function (Blueprint $table) {
                $table->text('description')->change();
                $table->text('short_description')->nullable()->after('description');
                $table->text('shipping_return')->nullable()->after('short_description');
                $table->text('related_product')->nullable()->after('shipping_return');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prod', function (Blueprint $table) {
            $table->string('description')->change();
            $table->dropColumn('short_description');
            $table->dropColumn('shipping_return');
            $table->dropColumn('related_product');
        });
    }
};
