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
        Schema::create('enquiry_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('Customer who sent the enquiry');

            // Message content
            $table->text('message');
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->string('preferred_time_slot')->nullable()->comment('morning, afternoon, evening, flexible');

            // Status tracking
            $table->enum('status', ['new', 'read', 'replied', 'archived'])->default('new');
            $table->timestamp('read_at')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();

            // Indexes for quick lookups
            $table->index(['company_id', 'status']);
            $table->index(['staff_id', 'status']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquiry_messages');
    }
};
