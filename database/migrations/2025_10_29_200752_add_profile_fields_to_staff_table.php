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
        Schema::table('staff', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('is_active');
            $table->string('profile_photo')->nullable()->after('bio');
            $table->json('qualifications')->nullable()->after('profile_photo');
            $table->json('specialties')->nullable()->after('qualifications');
            $table->json('lesson_types')->nullable()->after('specialties');
            $table->json('facilities')->nullable()->after('lesson_types');
            $table->json('technology_available')->nullable()->after('facilities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'profile_photo',
                'qualifications',
                'specialties',
                'lesson_types',
                'facilities',
                'technology_available',
            ]);
        });
    }
};
