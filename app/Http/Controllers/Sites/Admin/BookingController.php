<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingCancelled;
use App\Mail\BookingConfirmation;
use App\Models\Booking;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings
     */
    public function index(Request $request)
    {
        $query = Booking::with(['company', 'staff', 'order'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->has('company_id') && $request->company_id !== '') {
            $query->where('company_id', $request->company_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('booking_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('booking_date', '<=', $request->date_to);
        }

        // Search by customer
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(20);

        $companies = Company::select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('Sites/Admin/Bookings/Index', [
            'bookings' => $bookings,
            'companies' => $companies,
            'filters' => [
                'status' => $request->status,
                'company_id' => $request->company_id,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'search' => $request->search,
            ],
        ]);
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        $booking->load([
            'company',
            'staff',
            'order.items',
            'order.payment',
        ]);

        return Inertia::render('Sites/Admin/Bookings/Show', [
            'booking' => $booking,
        ]);
    }

    /**
     * Update the booking status
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
            'notes' => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        // If notes provided, append to existing notes
        if ($request->has('notes') && $request->notes) {
            $existingNotes = $booking->notes ?? '';
            $timestamp = now()->format('Y-m-d H:i');
            $newNote = "[$timestamp] Status changed to {$request->status}: {$request->notes}";
            $booking->update([
                'notes' => $existingNotes ? $existingNotes . "\n\n" . $newNote : $newNote,
            ]);
        }

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    /**
     * Cancel a booking
     */
    public function cancel(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Booking is already cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->reason,
            'cancelled_at' => now(),
        ]);

        // Add note about cancellation
        $existingNotes = $booking->notes ?? '';
        $timestamp = now()->format('Y-m-d H:i');
        $reason = $request->reason ? ": {$request->reason}" : '';
        $newNote = "[$timestamp] Booking cancelled by admin{$reason}";
        $booking->update([
            'notes' => $existingNotes ? $existingNotes . "\n\n" . $newNote : $newNote,
        ]);

        // Send cancellation email to customer
        Mail::to($booking->customer_email)->queue(new BookingCancelled($booking));

        return redirect()->back()->with('success', 'Booking cancelled successfully.');
    }

    /**
     * Resend confirmation email to customer
     */
    public function resendConfirmation(Booking $booking)
    {
        // Load relationships needed for email
        $booking->load(['company', 'staff']);

        // Send confirmation email
        Mail::to($booking->customer_email)->queue(new BookingConfirmation($booking));

        // Add note that confirmation was resent
        $existingNotes = $booking->notes ?? '';
        $timestamp = now()->format('Y-m-d H:i');
        $newNote = "[$timestamp] Confirmation email resent by admin";
        $booking->update([
            'notes' => $existingNotes ? $existingNotes . "\n\n" . $newNote : $newNote,
        ]);

        return redirect()->back()->with('success', 'Confirmation email sent successfully.');
    }

    /**
     * Get booking statistics
     */
    public function statistics(Request $request)
    {
        $query = Booking::query();

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('booking_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('booking_date', '<=', $request->date_to);
        }

        $statistics = [
            'total' => (clone $query)->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'completed' => (clone $query)->where('status', 'completed')->count(),
            'no_show' => (clone $query)->where('status', 'no_show')->count(),
        ];

        return response()->json($statistics);
    }
}
