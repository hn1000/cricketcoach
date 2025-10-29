<?php

namespace App\Http\Controllers\Sites\Company;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings for the user's companies
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $companyIds = $user->getCompanyIds();

        $query = Booking::whereIn('company_id', $companyIds)
            ->with(['company', 'staff', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('booking_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('booking_date', '<=', $request->date_to);
        }

        $bookings = $query->latest('booking_date')->paginate(20);

        return Inertia::render('Sites/Company/Bookings/Index', [
            'bookings' => $bookings,
            'filters' => [
                'status' => $request->status,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ],
        ]);
    }

    /**
     * Display the specified booking
     */
    public function show(Request $request, Booking $booking)
    {
        $user = $request->user();

        // Verify user can view this booking
        if (!in_array($booking->company_id, $user->getCompanyIds())) {
            abort(403, 'You do not have permission to view this booking.');
        }

        $booking->load(['company', 'staff', 'user', 'order']);

        return Inertia::render('Sites/Company/Bookings/Show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update the status of the booking
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $user = $request->user();

        // Verify user can manage this booking
        if (!in_array($booking->company_id, $user->getCompanyIds())) {
            abort(403, 'You do not have permission to update this booking.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending_payment,confirmed,completed,cancelled',
        ]);

        $booking->update($validated);

        return back()->with('success', 'Booking status updated successfully');
    }
}
