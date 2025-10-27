<?php

namespace Database\Seeders;

use App\Models\BookingSlotException;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSlotExceptionSeeder extends Seeder
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

        $firstStaff = $staff->first();

        if ($firstStaff) {
            // Example 1: Full day off (Christmas)
            BookingSlotException::create([
                'staff_id' => $firstStaff->id,
                'exception_date' => Carbon::parse('2025-12-25'),
                'start_time' => null,
                'end_time' => null,
                'type' => 'unavailable',
                'reason' => 'Christmas Holiday',
                'notes' => 'Office closed for Christmas',
            ]);

            // Example 2: Partial day block (lunch meeting)
            BookingSlotException::create([
                'staff_id' => $firstStaff->id,
                'exception_date' => Carbon::today()->addDays(3),
                'start_time' => '12:00',
                'end_time' => '14:00',
                'type' => 'unavailable',
                'reason' => 'Team Meeting',
                'notes' => 'Monthly all-hands meeting',
            ]);

            // Example 3: Vacation week
            for ($i = 1; $i <= 5; $i++) {
                BookingSlotException::create([
                    'staff_id' => $firstStaff->id,
                    'exception_date' => Carbon::today()->addWeeks(2)->addDays($i),
                    'start_time' => null,
                    'end_time' => null,
                    'type' => 'unavailable',
                    'reason' => 'Annual Leave',
                    'notes' => 'Summer vacation',
                ]);
            }
        }

        // Add exceptions for other staff members
        $secondStaff = $staff->skip(1)->first();
        if ($secondStaff) {
            // Example: Early finish one day
            BookingSlotException::create([
                'staff_id' => $secondStaff->id,
                'exception_date' => Carbon::today()->addDays(7),
                'start_time' => '15:00',
                'end_time' => '17:00',
                'type' => 'unavailable',
                'reason' => 'Doctor Appointment',
                'notes' => 'Leaving early',
            ]);
        }

        $this->command->info('Booking slot exceptions seeded successfully.');
    }
}
