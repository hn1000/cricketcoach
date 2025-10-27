<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('price', 10, 2)->after('duration');
            $table->string('confirmation_token')->nullable()->after('notes');

            $table->index('confirmation_token');
        });

        // Update status enum to include pending_payment
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending_payment', 'confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'confirmed'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn(['order_id', 'price', 'confirmation_token']);
        });

        // Revert status enum
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('confirmed', 'completed', 'cancelled', 'no_show') DEFAULT 'confirmed'");
    }
};
