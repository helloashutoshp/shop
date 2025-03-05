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
        if (!Schema::hasTable('usershipping_address')) {
            Schema::create('usershipping_address', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
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
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usershipping_address');
    }
};
