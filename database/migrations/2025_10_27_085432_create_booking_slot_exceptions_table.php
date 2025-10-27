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
        Schema::create('booking_slot_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->date('exception_date');
            $table->time('start_time')->nullable()->comment('If null, entire day is blocked');
            $table->time('end_time')->nullable()->comment('If null, entire day is blocked');
            $table->enum('type', ['unavailable', 'custom_hours'])->default('unavailable');
            $table->string('reason')->nullable()->comment('Vacation, holiday, meeting, etc.');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index for quick lookups when calculating availability
            $table->index(['staff_id', 'exception_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_slot_exceptions');
    }
};
