<?php

namespace App\Http\Controllers\Sites\Public;

use App\Http\Controllers\Controller;
use App\Mail\EnquiryReceived;
use App\Models\Company;
use App\Models\EnquiryMessage;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class EnquiryMessageController extends Controller
{
    /**
     * Show the enquiry form for a specific coach
     */
    public function create(Company $company, Staff $staff)
    {
        // Check if company uses online booking instead
        if ($company->booking_system_on) {
            return redirect()->route('booking.create', [$company, $staff])
                ->with('info', 'This company uses online booking. Please select a time slot.');
        }

        // Verify staff belongs to company
        if ($staff->company_id !== $company->id) {
            abort(404);
        }

        // Verify both are active
        if (!$company->is_active || !$staff->is_active) {
            abort(404);
        }

        return Inertia::render('Sites/Public/Enquiry/Create', [
            'company' => $company,
            'staff' => $staff,
        ]);
    }

    /**
     * Store a new enquiry message
     */
    public function store(Request $request, Company $company, Staff $staff)
    {
        // Must be authenticated
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('intended', route('enquiry.create', [$company, $staff]))
                ->with('info', 'Please login or register to send an enquiry.');
        }

        // Check if company uses online booking instead
        if ($company->booking_system_on) {
            return redirect()->route('booking.create', [$company, $staff]);
        }

        // Verify staff belongs to company
        if ($staff->company_id !== $company->id) {
            abort(404);
        }

        // Validate
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'preferred_time' => 'nullable|date_format:H:i',
            'preferred_time_slot' => 'nullable|in:morning,afternoon,evening,flexible',
        ]);

        // Create enquiry message
        $enquiry = EnquiryMessage::create([
            'company_id' => $company->id,
            'staff_id' => $staff->id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'preferred_date' => $validated['preferred_date'] ?? null,
            'preferred_time' => $validated['preferred_time'] ?? null,
            'preferred_time_slot' => $validated['preferred_time_slot'] ?? null,
        ]);

        // Send email notification to coach
        try {
            Mail::to($staff->email)->send(new EnquiryReceived($enquiry));
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send enquiry email: ' . $e->getMessage());
        }

        return redirect()->route('enquiry.success', $enquiry->id)
            ->with('success', 'Your enquiry has been sent!');
    }

    /**
     * Show the success/thank you page
     */
    public function success(EnquiryMessage $enquiry)
    {
        // Ensure user owns this enquiry
        if ($enquiry->user_id !== auth()->id()) {
            abort(403);
        }

        $enquiry->load(['company', 'staff', 'user']);

        return Inertia::render('Sites/Public/Enquiry/Success', [
            'enquiry' => $enquiry,
        ]);
    }
}
