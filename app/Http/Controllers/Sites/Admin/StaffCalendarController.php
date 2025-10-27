<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Staff;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffCalendarController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Display the staff calendar view
     */
    public function index(Company $company, Staff $staff)
    {
        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Calendar/Index', [
            'company' => $company,
            'staff' => $staff,
        ]);
    }

    /**
     * Get calendar events (availability, exceptions, bookings) for the staff member
     */
    public function events(Request $request, Company $company, Staff $staff)
    {
        $start = $request->input('start', Carbon::now()->startOfWeek()->toDateString());
        $end = $request->input('end', Carbon::now()->endOfWeek()->toDateString());

        $events = [];

        // Get available slots for the date range
        $slotsByDate = $this->availabilityService->getAvailableSlotsForRange($staff, $start, $end);

        // Add available slots as events
        foreach ($slotsByDate as $date => $slots) {
            foreach ($slots as $slot) {
                $startDateTime = "{$date} {$slot['start_time']}";
                $endDateTime = "{$date} {$slot['end_time']}";

                $events[] = [
                    'id' => "available-{$date}-{$slot['start_time']}",
                    'type' => 'available',
                    'title' => 'Available',
                    'start' => $startDateTime,
                    'end' => $endDateTime,
                    'allDay' => false,
                    'backgroundColor' => '#dcfce7',
                    'borderColor' => '#86efac',
                    'textColor' => '#166534',
                    'metadata' => [
                        'duration' => $slot['duration'],
                        'time' => "{$slot['start_time']} - {$slot['end_time']}",
                    ],
                ];
            }
        }

        // Get exceptions for the date range
        $exceptions = $staff->bookingSlotExceptions()
            ->betweenDates($start, $end)
            ->get();

        foreach ($exceptions as $exception) {
            $isFullDay = $exception->isFullDayBlock();

            $events[] = [
                'id' => "exception-{$exception->id}",
                'type' => 'exception',
                'title' => $exception->reason ?: 'Unavailable',
                'start' => $isFullDay
                    ? $exception->exception_date->toDateString()
                    : "{$exception->exception_date->toDateString()} {$exception->start_time}",
                'end' => $isFullDay
                    ? $exception->exception_date->toDateString()
                    : "{$exception->exception_date->toDateString()} {$exception->end_time}",
                'allDay' => $isFullDay,
                'backgroundColor' => '#fee2e2',
                'borderColor' => '#ef4444',
                'textColor' => '#991b1b',
                'metadata' => [
                    'exceptionId' => $exception->id,
                    'reason' => $exception->reason,
                    'notes' => $exception->notes,
                    'time' => $isFullDay ? 'All day' : "{$exception->start_time} - {$exception->end_time}",
                ],
            ];
        }

        // Get bookings for the date range
        $bookings = $staff->bookings()
            ->betweenDates($start, $end)
            ->confirmed()
            ->get();

        foreach ($bookings as $booking) {
            $events[] = [
                'id' => "booking-{$booking->id}",
                'type' => 'booking',
                'title' => "Booked: {$booking->customer_name}",
                'start' => "{$booking->booking_date->toDateString()} {$booking->start_time}",
                'end' => "{$booking->booking_date->toDateString()} {$booking->end_time}",
                'allDay' => false,
                'backgroundColor' => '#dbeafe',
                'borderColor' => '#3b82f6',
                'textColor' => '#1e40af',
                'metadata' => [
                    'bookingId' => $booking->id,
                    'customerName' => $booking->customer_name,
                    'customerEmail' => $booking->customer_email,
                    'customerPhone' => $booking->customer_phone,
                    'status' => $booking->status,
                    'duration' => $booking->duration,
                    'notes' => $booking->notes,
                    'staffName' => $staff->full_name,
                    'staffId' => $staff->id,
                    'time' => "{$booking->start_time} - {$booking->end_time}",
                ],
            ];
        }

        return response()->json([
            'events' => $events,
        ]);
    }
}
