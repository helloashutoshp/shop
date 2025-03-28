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
        if (!Schema::hasTable('sub_cate')) {
            Schema::table('sub_cate', function (Blueprint $table) {
                $table->enum('showHome', ['YES', 'NO'])->default('YES')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_cate', function (Blueprint $table) {
            $table->integer('showHome')->default(1)->change();
        });
    }
};
