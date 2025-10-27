<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingSlotException;
use App\Models\BookingSlotTemplate;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AvailabilityService
{
    /**
     * Get available booking slots for a staff member on a specific date
     *
     * @param Staff $staff
     * @param string $date Date in Y-m-d format
     * @return Collection Collection of available time slots
     */
    public function getAvailableSlots(Staff $staff, string $date): Collection
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = strtolower($carbonDate->format('l'));

        // Step 1: Get the template for this day of week
        $templates = $this->getActiveTemplatesForDay($staff->id, $dayOfWeek, $date);

        if ($templates->isEmpty()) {
            return collect();
        }

        // Step 2: Check for exceptions (full day unavailable)
        if ($this->hasFullDayException($staff->id, $date)) {
            return collect();
        }

        // Step 3: Get exceptions for this date
        $exceptions = $this->getExceptionsForDate($staff->id, $date);

        // Step 4: Get existing bookings for this date
        $bookings = $this->getBookingsForDate($staff->id, $date);

        // Step 5: Generate slots from templates and filter out unavailable ones
        $availableSlots = collect();

        foreach ($templates as $template) {
            $slots = $this->generateSlotsFromTemplate($template, $carbonDate);

            foreach ($slots as $slot) {
                // Check if slot is blocked by exception
                if ($this->isSlotBlockedByException($slot, $exceptions)) {
                    continue;
                }

                // Check if slot is already booked
                if ($this->isSlotBooked($slot, $bookings)) {
                    continue;
                }

                $availableSlots->push($slot);
            }
        }

        return $availableSlots->sortBy('start_time')->values();
    }

    /**
     * Get active templates for a specific day of week and date
     */
    protected function getActiveTemplatesForDay(int $staffId, string $dayOfWeek, string $date): Collection
    {
        return BookingSlotTemplate::where('staff_id', $staffId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->effectiveOn($date)
            ->get();
    }

    /**
     * Check if there's a full-day exception for this date
     */
    protected function hasFullDayException(int $staffId, string $date): bool
    {
        return BookingSlotException::where('staff_id', $staffId)
            ->forDate($date)
            ->where('type', 'unavailable')
            ->whereNull('start_time')
            ->whereNull('end_time')
            ->exists();
    }

    /**
     * Get exceptions for a specific date
     */
    protected function getExceptionsForDate(int $staffId, string $date): Collection
    {
        return BookingSlotException::where('staff_id', $staffId)
            ->forDate($date)
            ->get();
    }

    /**
     * Get confirmed bookings for a specific date
     */
    protected function getBookingsForDate(int $staffId, string $date): Collection
    {
        return Booking::where('staff_id', $staffId)
            ->forDate($date)
            ->confirmed()
            ->get();
    }

    /**
     * Generate time slots from a template
     */
    protected function generateSlotsFromTemplate(BookingSlotTemplate $template, Carbon $date): Collection
    {
        $slots = collect();
        $slotDuration = $template->slot_duration;

        $start = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $template->start_time);
        $end = Carbon::createFromFormat('Y-m-d H:i', $date->format('Y-m-d') . ' ' . $template->end_time);

        $current = $start->copy();

        while ($current->copy()->addMinutes($slotDuration)->lte($end)) {
            $slotEnd = $current->copy()->addMinutes($slotDuration);

            $slots->push([
                'start_time' => $current->format('H:i'),
                'end_time' => $slotEnd->format('H:i'),
                'duration' => $slotDuration,
                'template_id' => $template->id,
            ]);

            $current->addMinutes($slotDuration);
        }

        return $slots;
    }

    /**
     * Check if a slot is blocked by any exception
     */
    protected function isSlotBlockedByException(array $slot, Collection $exceptions): bool
    {
        foreach ($exceptions as $exception) {
            if ($exception->blocksTimeRange($slot['start_time'], $slot['end_time'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a slot is already booked
     */
    protected function isSlotBooked(array $slot, Collection $bookings): bool
    {
        foreach ($bookings as $booking) {
            // Check for time overlap
            if ($slot['start_time'] < $booking->end_time && $slot['end_time'] > $booking->start_time) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get available slots for a date range
     *
     * @param Staff $staff
     * @param string $startDate
     * @param string $endDate
     * @return Collection Collection grouped by date
     */
    public function getAvailableSlotsForRange(Staff $staff, string $startDate, string $endDate): Collection
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $slotsByDate = collect();

        $current = $start->copy();

        while ($current->lte($end)) {
            $dateStr = $current->format('Y-m-d');
            $slots = $this->getAvailableSlots($staff, $dateStr);

            if ($slots->isNotEmpty()) {
                $slotsByDate->put($dateStr, $slots);
            }

            $current->addDay();
        }

        return $slotsByDate;
    }

    /**
     * Check if a specific time slot is available
     *
     * @param Staff $staff
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    public function isSlotAvailable(Staff $staff, string $date, string $startTime, string $endTime): bool
    {
        $carbonDate = Carbon::parse($date);
        $dayOfWeek = strtolower($carbonDate->format('l'));

        // Check if there's a template that covers this time
        $templates = $this->getActiveTemplatesForDay($staff->id, $dayOfWeek, $date);

        $coveredByTemplate = false;
        foreach ($templates as $template) {
            if ($startTime >= $template->start_time && $endTime <= $template->end_time) {
                $coveredByTemplate = true;
                break;
            }
        }

        if (!$coveredByTemplate) {
            return false;
        }

        // Check for full day exception
        if ($this->hasFullDayException($staff->id, $date)) {
            return false;
        }

        // Check if blocked by exception
        $exceptions = $this->getExceptionsForDate($staff->id, $date);
        foreach ($exceptions as $exception) {
            if ($exception->blocksTimeRange($startTime, $endTime)) {
                return false;
            }
        }

        // Check if already booked
        $bookings = $this->getBookingsForDate($staff->id, $date);
        foreach ($bookings as $booking) {
            if ($startTime < $booking->end_time && $endTime > $booking->start_time) {
                return false;
            }
        }

        return true;
    }
}
