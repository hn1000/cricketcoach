<?php

namespace App\Http\Controllers\Sites\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Staff;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BookingController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Show the booking creation form
     */
    public function create(Request $request, Company $company, Staff $staff)
    {
        if (!$company->is_active || !$staff->is_active || $staff->company_id !== $company->id) {
            abort(404);
        }

        // Get pre-selected date and time from query params if available
        $selectedDate = $request->input('date');
        $selectedStartTime = $request->input('start_time');
        $selectedEndTime = $request->input('end_time');

        return Inertia::render('Sites/Public/Booking/Create', [
            'company' => $company,
            'staff' => $staff,
            'preselected' => [
                'date' => $selectedDate,
                'start_time' => $selectedStartTime,
                'end_time' => $selectedEndTime,
            ],
        ]);
    }

    /**
     * Store a new booking and create order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'staff_id' => 'required|exists:staff,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $company = Company::findOrFail($validated['company_id']);
            $staff = Staff::findOrFail($validated['staff_id']);

            // Verify company and staff are active and associated
            if (!$company->is_active || !$staff->is_active || $staff->company_id !== $company->id) {
                return response()->json([
                    'message' => 'Invalid company or staff member',
                ], 422);
            }

            // Verify slot is still available
            $isAvailable = $this->availabilityService->isSlotAvailable(
                $staff,
                $validated['booking_date'],
                $validated['start_time'],
                $validated['end_time']
            );

            if (!$isAvailable) {
                return response()->json([
                    'message' => 'This time slot is no longer available. Please select another time.',
                ], 422);
            }

            // Calculate duration in minutes
            // Append :00 if only H:i format provided
            $startTimeStr = strlen($validated['start_time']) === 5 ? $validated['start_time'] . ':00' : $validated['start_time'];
            $endTimeStr = strlen($validated['end_time']) === 5 ? $validated['end_time'] . ':00' : $validated['end_time'];

            $startTime = Carbon::createFromFormat('H:i:s', $startTimeStr);
            $endTime = Carbon::createFromFormat('H:i:s', $endTimeStr);
            $duration = $startTime->diffInMinutes($endTime);

            // TODO: Get price from staff/service configuration
            // For now, using a default price of $50
            $price = 50.00;

            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(), // null if guest
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'company_id' => $company->id,
                'order_type' => 'booking',
                'status' => 'pending',
                'subtotal' => $price,
                'tax' => 0, // TODO: Calculate tax based on location
                'total' => $price,
                'currency' => 'USD',
            ]);

            // Create the booking with pending_payment status
            $booking = Booking::create([
                'order_id' => $order->id,
                'staff_id' => $staff->id,
                'user_id' => auth()->id(),
                'company_id' => $company->id,
                'booking_date' => $validated['booking_date'],
                'start_time' => $startTimeStr,
                'end_time' => $endTimeStr,
                'duration' => $duration,
                'price' => $price,
                'status' => 'pending_payment',
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'notes' => $validated['notes'],
                'confirmation_token' => Str::random(32),
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'orderable_type' => Booking::class,
                'orderable_id' => $booking->id,
                'quantity' => 1,
                'unit_price' => $price,
                'total_price' => $price,
                'metadata' => [
                    'staff_name' => $staff->full_name,
                    'booking_date' => $validated['booking_date'],
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'duration' => $duration,
                ],
            ]);

            DB::commit();

            return response()->json([
                'order_id' => $order->id,
                'booking_id' => $booking->id,
                'message' => 'Booking created successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show booking confirmation page (after payment)
     */
    public function confirmation(Request $request, string $confirmationToken)
    {
        $booking = Booking::where('confirmation_token', $confirmationToken)
            ->with(['staff', 'company', 'order'])
            ->firstOrFail();

        return Inertia::render('Sites/Public/Booking/Confirmation', [
            'booking' => $booking,
        ]);
    }

    /**
     * Show booking cancellation page (for guests)
     */
    public function cancelForm(string $confirmationToken)
    {
        $booking = Booking::where('confirmation_token', $confirmationToken)
            ->with(['staff', 'company'])
            ->firstOrFail();

        if ($booking->status === 'cancelled') {
            return Inertia::render('Sites/Public/Booking/AlreadyCancelled', [
                'booking' => $booking,
            ]);
        }

        return Inertia::render('Sites/Public/Booking/Cancel', [
            'booking' => $booking,
        ]);
    }

    /**
     * Cancel a booking (for guests)
     */
    public function cancel(Request $request, string $confirmationToken)
    {
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $booking = Booking::where('confirmation_token', $confirmationToken)
            ->firstOrFail();

        if ($booking->status === 'cancelled') {
            return response()->json([
                'message' => 'This booking has already been cancelled',
            ], 422);
        }

        // Check cancellation policy (e.g., must cancel at least 24hrs before)
        $bookingDateTime = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time);
        $hoursUntilBooking = now()->diffInHours($bookingDateTime, false);

        if ($hoursUntilBooking < 24) {
            return response()->json([
                'message' => 'Bookings must be cancelled at least 24 hours in advance',
            ], 422);
        }

        $booking->cancel($request->input('cancellation_reason'));

        // TODO: Trigger refund if payment was made
        // TODO: Send cancellation email

        return response()->json([
            'message' => 'Booking cancelled successfully',
        ]);
    }
}
