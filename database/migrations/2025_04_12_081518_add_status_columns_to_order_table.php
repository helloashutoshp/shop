
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->enum('delivery_status', ['delivered', 'pending', 'shipped'])->default('pending')->after('grandTotal');
            $table->enum('payment_status', ['paid', 'unpaid'])->default('unpaid')->after('delivery_status');
        });
    }

    public function down(): void
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('delivery_status');
            $table->dropColumn('payment_status');
        });
    }
};
