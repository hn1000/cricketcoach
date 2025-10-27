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
        Schema::create('booking_slot_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration')->comment('Duration in minutes (e.g., 30, 45, 60)');
            $table->boolean('is_active')->default(true);
            $table->date('effective_from')->nullable()->comment('When this template becomes active');
            $table->date('effective_until')->nullable()->comment('When this template expires');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Ensure no overlapping templates for the same staff on the same day
            $table->index(['staff_id', 'day_of_week', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_slot_templates');
    }
};
