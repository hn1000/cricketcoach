<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\EnquiryMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EnquiryMessageController extends Controller
{
    /**
     * Display a listing of enquiries for companies the user manages
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // For now, show all enquiries if admin
        // TODO: Add proper company ownership/permissions
        $query = EnquiryMessage::with(['company', 'staff', 'user'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->has('company_id') && $request->company_id !== '') {
            $query->where('company_id', $request->company_id);
        }

        $enquiries = $query->paginate(20);

        return Inertia::render('Sites/Admin/Enquiries/Index', [
            'enquiries' => $enquiries,
            'companies' => Company::where('is_active', true)->get(['id', 'name']),
            'filters' => [
                'status' => $request->status,
                'company_id' => $request->company_id,
            ],
        ]);
    }

    /**
     * Display a specific enquiry
     */
    public function show(EnquiryMessage $enquiry)
    {
        // TODO: Add authorization check

        // Mark as read if it's new
        if ($enquiry->status === 'new') {
            $enquiry->markAsRead();
        }

        $enquiry->load(['company', 'staff', 'user']);

        return Inertia::render('Sites/Admin/Enquiries/Show', [
            'enquiry' => $enquiry,
        ]);
    }

    /**
     * Update the status of an enquiry
     */
    public function updateStatus(Request $request, EnquiryMessage $enquiry)
    {
        // TODO: Add authorization check

        $request->validate([
            'status' => 'required|in:new,read,replied,archived',
        ]);

        $enquiry->update([
            'status' => $request->status,
            'replied_at' => $request->status === 'replied' ? now() : $enquiry->replied_at,
        ]);

        return back()->with('success', 'Status updated successfully');
    }
}
