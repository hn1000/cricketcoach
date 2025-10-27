<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyCalendarController extends Controller
{
    /**
     * Display the company calendar view
     */
    public function index(Company $company)
    {
        // Get all staff members for filtering
        $staff = $company->staff()->where('is_active', true)->get(['id', 'first_name', 'last_name']);

        return Inertia::render('Sites/Admin/Companies/Calendar/Index', [
            'company' => $company,
            'staffMembers' => $staff,
        ]);
    }

    /**
     * Get all bookings for the company with optional staff filtering
     */
    public function bookings(Request $request, Company $company)
    {
        $start = $request->input('start', Carbon::now()->startOfWeek()->toDateString());
        $end = $request->input('end', Carbon::now()->endOfWeek()->toDateString());
        $staffIds = $request->input('staff_ids', []);

        $events = [];

        // Get bookings query
        $bookingsQuery = $company->bookings()
            ->with('staff')
            ->betweenDates($start, $end)
            ->confirmed();

        // Filter by staff if provided
        if (!empty($staffIds)) {
            $bookingsQuery->whereIn('staff_id', $staffIds);
        }

        $bookings = $bookingsQuery->get();

        // Generate color palette for staff members
        $staffColors = $this->generateStaffColors($company->staff()->where('is_active', true)->get());

        foreach ($bookings as $booking) {
            $staffId = $booking->staff_id;
            $staffColor = $staffColors[$staffId] ?? ['bg' => '#3b82f6', 'border' => '#2563eb', 'text' => '#1e40af'];

            $events[] = [
                'id' => "booking-{$booking->id}",
                'type' => 'booking',
                'title' => "{$booking->staff->full_name}: {$booking->customer_name}",
                'start' => "{$booking->booking_date->toDateString()} {$booking->start_time}",
                'end' => "{$booking->booking_date->toDateString()} {$booking->end_time}",
                'allDay' => false,
                'backgroundColor' => $staffColor['bg'],
                'borderColor' => $staffColor['border'],
                'textColor' => $staffColor['text'],
                'metadata' => [
                    'bookingId' => $booking->id,
                    'customerName' => $booking->customer_name,
                    'customerEmail' => $booking->customer_email,
                    'customerPhone' => $booking->customer_phone,
                    'status' => $booking->status,
                    'duration' => $booking->duration,
                    'notes' => $booking->notes,
                    'staffName' => $booking->staff->full_name,
                    'staffId' => $booking->staff_id,
                    'time' => "{$booking->start_time} - {$booking->end_time}",
                ],
            ];
        }

        return response()->json([
            'events' => $events,
            'staffColors' => $staffColors,
        ]);
    }

    /**
     * Generate consistent colors for staff members
     */
    protected function generateStaffColors($staffMembers)
    {
        $colorPalette = [
            ['bg' => '#dbeafe', 'border' => '#3b82f6', 'text' => '#1e40af'], // Blue
            ['bg' => '#dcfce7', 'border' => '#22c55e', 'text' => '#166534'], // Green
            ['bg' => '#fef3c7', 'border' => '#f59e0b', 'text' => '#92400e'], // Amber
            ['bg' => '#e0e7ff', 'border' => '#6366f1', 'text' => '#312e81'], // Indigo
            ['bg' => '#fce7f3', 'border' => '#ec4899', 'text' => '#831843'], // Pink
            ['bg' => '#ddd6fe', 'border' => '#8b5cf6', 'text' => '#4c1d95'], // Purple
            ['bg' => '#ccfbf1', 'border' => '#14b8a6', 'text' => '#134e4a'], // Teal
            ['bg' => '#fed7aa', 'border' => '#f97316', 'text' => '#7c2d12'], // Orange
        ];

        $colors = [];
        foreach ($staffMembers as $index => $staff) {
            $colors[$staff->id] = $colorPalette[$index % count($colorPalette)];
        }

        return $colors;
    }
}
