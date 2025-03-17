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
        if (!Schema::hasTable('discount')) {
            Schema::create('discount', function (Blueprint $table) {
                $table->id();
                $table->string('code');
                $table->string('name')->nullable();
                $table->string('description')->nullable();
                $table->integer('max_uses')->nullable();
                $table->integer('max_uses_user')->nullable();
                $table->enum('type', ['percent', 'fixed'])->default('fixed');
                $table->double('dicount_amount', '10', '2');
                $table->double('minimum_amount', '10', '2')->nullable();
                $table->integer('status')->default(1);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount');
    }
};
