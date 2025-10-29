<?php

namespace App\Http\Controllers\Sites\Company;

use App\Http\Controllers\Controller;
use App\Models\EnquiryMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EnquiryMessageController extends Controller
{
    /**
     * Display a listing of enquiries for the user's companies
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $companyIds = $user->getCompanyIds();

        $query = EnquiryMessage::whereIn('company_id', $companyIds)
            ->with(['company', 'staff', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $enquiries = $query->latest()->paginate(20);

        return Inertia::render('Sites/Company/Enquiries/Index', [
            'enquiries' => $enquiries,
            'filters' => [
                'status' => $request->status,
            ],
        ]);
    }

    /**
     * Display the specified enquiry
     */
    public function show(Request $request, EnquiryMessage $enquiry)
    {
        $user = $request->user();

        // Verify user can view this enquiry
        if (!in_array($enquiry->company_id, $user->getCompanyIds())) {
            abort(403, 'You do not have permission to view this enquiry.');
        }

        // Mark as read if it's new
        if ($enquiry->status === 'new') {
            $enquiry->markAsRead();
        }

        $enquiry->load(['company', 'staff', 'user']);

        return Inertia::render('Sites/Company/Enquiries/Show', [
            'enquiry' => $enquiry,
        ]);
    }

    /**
     * Update the status of an enquiry
     */
    public function updateStatus(Request $request, EnquiryMessage $enquiry)
    {
        $user = $request->user();

        // Verify user can manage this enquiry
        if (!in_array($enquiry->company_id, $user->getCompanyIds())) {
            abort(403, 'You do not have permission to update this enquiry.');
        }

        $validated = $request->validate([
            'status' => 'required|in:new,read,replied,archived',
        ]);

        $enquiry->update([
            'status' => $validated['status'],
            'replied_at' => $validated['status'] === 'replied' ? now() : $enquiry->replied_at,
        ]);

        return back()->with('success', 'Status updated successfully');
    }
}
