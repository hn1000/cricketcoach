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
        // MySQL/MariaDB syntax to modify enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_gateway ENUM('stripe', 'paypal', 'manual', 'mock') DEFAULT 'stripe'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'mock' from enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_gateway ENUM('stripe', 'paypal', 'manual') DEFAULT 'stripe'");
    }
};
