<?php

namespace App\Http\Controllers\Sites\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of the user's bookings
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Booking::where('user_id', $user->id)
            ->with(['company', 'staff', 'order']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('booking_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('booking_date', '<=', $request->date_to);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('company', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('staff', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortField = $request->get('sort', 'booking_date');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $bookings = $query->paginate(10)->withQueryString();

        return Inertia::render('Sites/Customer/Bookings/Index', [
            'bookings' => $bookings,
            'filters' => $request->only(['status', 'date_from', 'date_to', 'search', 'sort', 'direction']),
        ]);
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $user = auth()->user();

        // Ensure user owns this booking
        if ($booking->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this booking.');
        }

        $booking->load(['company', 'staff', 'order.payment']);

        return Inertia::render('Sites/Customer/Bookings/Show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        $user = auth()->user();

        // Ensure user owns this booking
        if ($booking->user_id !== $user->id) {
            return response()->json([
                'message' => 'Unauthorized access to this booking.',
            ], 403);
        }

        // Check if booking is already cancelled
        if ($booking->status === 'cancelled') {
            return response()->json([
                'message' => 'This booking has already been cancelled.',
            ], 422);
        }

        // Check cancellation policy (must cancel at least 24hrs before)
        $bookingDateTime = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time);
        $hoursUntilBooking = now()->diffInHours($bookingDateTime, false);

        if ($hoursUntilBooking < 24) {
            return response()->json([
                'message' => 'Bookings must be cancelled at least 24 hours in advance.',
            ], 422);
        }

        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        // Cancel the booking
        $booking->cancel($request->input('cancellation_reason'));

        // TODO: Trigger refund if payment was made
        // TODO: Send cancellation email

        return response()->json([
            'message' => 'Booking cancelled successfully.',
        ]);
    }
}
