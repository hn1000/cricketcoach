<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sites\Admin\AdminBookingSlotResource;
use App\Models\BookingSlot;
use App\Models\Company;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingSlotController extends Controller
{
    public function index(Company $company, Staff $staff)
    {
        // Verify staff belongs to company
        if ($staff->company_id !== $company->id) {
            abort(404);
        }

        $slots = $staff->bookingSlots()
            ->with('creator')
            ->latest('date')
            ->latest('start_time')
            ->paginate(50);

        return Inertia::render('Sites/Admin/Companies/Manage/Staff/BookingSlots/Index', [
            'company' => $company,
            'staff' => $staff,
            'slots' => AdminBookingSlotResource::collection($slots),
        ]);
    }

    public function create(Company $company, Staff $staff)
    {
        // Verify staff belongs to company
        if ($staff->company_id !== $company->id) {
            abort(404);
        }

        return Inertia::render('Sites/Admin/Companies/Manage/Staff/BookingSlots/Create', [
            'company' => $company,
            'staff' => $staff,
        ]);
    }

    public function store(Request $request, Company $company, Staff $staff)
    {
        // Verify staff belongs to company
        if ($staff->company_id !== $company->id) {
            abort(404);
        }

        $validated = $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration' => 'required|integer|min:15|max:480',
            'is_available' => 'boolean',
            'status' => 'required|in:available,blocked',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check for overlaps
        $slot = new BookingSlot($validated);
        if ($slot->overlapsWithinSameDay(
            $staff->id,
            $validated['date'],
            $validated['start_time'],
            $validated['end_time']
        )) {
            return back()->withErrors([
                'start_time' => 'This time slot overlaps with an existing booking slot.'
            ])->withInput();
        }

        $validated['staff_id'] = $staff->id;
        $validated['created_by'] = auth()->id();

        BookingSlot::create($validated);

        return redirect()->route('admin.companies.staff.booking-slots.index', [$company, $staff])
            ->with('success', 'Booking slot created successfully.');
    }

    public function edit(Company $company, Staff $staff, BookingSlot $bookingSlot)
    {
        // Verify staff belongs to company and slot belongs to staff
        if ($staff->company_id !== $company->id || $bookingSlot->staff_id !== $staff->id) {
            abort(404);
        }

        return Inertia::render('Sites/Admin/Companies/Manage/Staff/BookingSlots/Edit', [
            'company' => $company,
            'staff' => $staff,
            'slot' => new AdminBookingSlotResource($bookingSlot),
        ]);
    }

    public function update(Request $request, Company $company, Staff $staff, BookingSlot $bookingSlot)
    {
        // Verify staff belongs to company and slot belongs to staff
        if ($staff->company_id !== $company->id || $bookingSlot->staff_id !== $staff->id) {
            abort(404);
        }

        // Prevent editing if already booked
        if ($bookingSlot->status === 'booked') {
            return back()->withErrors([
                'error' => 'Cannot edit a booked slot. Cancel the booking first.'
            ]);
        }

        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration' => 'required|integer|min:15|max:480',
            'is_available' => 'boolean',
            'status' => 'required|in:available,blocked,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check for overlaps (excluding current slot)
        $slot = new BookingSlot($validated);
        if ($slot->overlapsWithinSameDay(
            $staff->id,
            $validated['date'],
            $validated['start_time'],
            $validated['end_time'],
            $bookingSlot->id
        )) {
            return back()->withErrors([
                'start_time' => 'This time slot overlaps with an existing booking slot.'
            ])->withInput();
        }

        $bookingSlot->update($validated);

        return redirect()->route('admin.companies.staff.booking-slots.index', [$company, $staff])
            ->with('success', 'Booking slot updated successfully.');
    }

    public function destroy(Company $company, Staff $staff, BookingSlot $bookingSlot)
    {
        // Verify staff belongs to company and slot belongs to staff
        if ($staff->company_id !== $company->id || $bookingSlot->staff_id !== $staff->id) {
            abort(404);
        }

        // Prevent deletion if booked
        if ($bookingSlot->status === 'booked') {
            return back()->withErrors([
                'error' => 'Cannot delete a booked slot. Cancel the booking first or use force delete.'
            ]);
        }

        $bookingSlot->delete();

        return redirect()->route('admin.companies.staff.booking-slots.index', [$company, $staff])
            ->with('success', 'Booking slot deleted successfully.');
    }
}
