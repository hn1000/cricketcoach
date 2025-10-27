<?php

namespace Database\Seeders;

use App\Models\BookingSlotTemplate;
use App\Models\Staff;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSlotTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = Staff::all();

        if ($staff->isEmpty()) {
            $this->command->warn('No staff found. Please run StaffSeeder first.');
            return;
        }

        foreach ($staff as $member) {
            // Create weekday templates (Monday-Friday)
            // Most staff work 9-5 with 45-minute slots
            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

            foreach ($weekdays as $day) {
                // Standard 9-5 schedule with 45-minute slots
                BookingSlotTemplate::create([
                    'staff_id' => $member->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'slot_duration' => 45,
                    'is_active' => true,
                    'notes' => 'Standard weekday availability',
                ]);
            }

            // Some staff also work Saturdays (half day)
            if ($member->id % 2 === 0) {
                BookingSlotTemplate::create([
                    'staff_id' => $member->id,
                    'day_of_week' => 'saturday',
                    'start_time' => '09:00',
                    'end_time' => '13:00',
                    'slot_duration' => 45,
                    'is_active' => true,
                    'notes' => 'Saturday half-day availability',
                ]);
            }
        }

        // Example: Create a staff member with different slot durations for different times
        $firstStaff = $staff->first();
        if ($firstStaff) {
            // Override Monday with 1-hour slots in the morning, 30-min slots in the afternoon
            BookingSlotTemplate::where('staff_id', $firstStaff->id)
                ->where('day_of_week', 'monday')
                ->delete();

            // 1-hour slots in the morning (9 AM - 12 PM)
            BookingSlotTemplate::create([
                'staff_id' => $firstStaff->id,
                'day_of_week' => 'monday',
                'start_time' => '09:00',
                'end_time' => '12:00',
                'slot_duration' => 60,
                'is_active' => true,
                'notes' => 'Monday morning - 1 hour sessions',
            ]);

            // 30-minute slots in the afternoon (2 PM - 5 PM)
            BookingSlotTemplate::create([
                'staff_id' => $firstStaff->id,
                'day_of_week' => 'monday',
                'start_time' => '14:00',
                'end_time' => '17:00',
                'slot_duration' => 30,
                'is_active' => true,
                'notes' => 'Monday afternoon - 30 minute sessions',
            ]);
        }

        $this->command->info('Booking slot templates seeded successfully.');
    }
}
