<?php

namespace App\Http\Controllers\Sites\Company;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EnquiryMessage;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the company dashboard
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $companyIds = $user->getCompanyIds();

        // Get the first company (or selected company if multi-company support added later)
        $company = $user->companies()->with('users')->first();

        if (!$company) {
            abort(403, 'You are not associated with any company.');
        }

        // Stats for the dashboard
        $totalCoaches = Staff::whereIn('company_id', $companyIds)->count();

        $upcomingBookings = Booking::whereIn('company_id', $companyIds)
            ->where('booking_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending_payment', 'confirmed'])
            ->count();

        $newEnquiries = EnquiryMessage::whereIn('company_id', $companyIds)
            ->where('status', 'new')
            ->count();

        $totalBookings = Booking::whereIn('company_id', $companyIds)->count();

        // Recent bookings
        $recentBookings = Booking::whereIn('company_id', $companyIds)
            ->with(['staff', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        // Recent enquiries
        $recentEnquiries = EnquiryMessage::whereIn('company_id', $companyIds)
            ->with(['staff', 'user'])
            ->latest()
            ->limit(5)
            ->get();

        return Inertia::render('Sites/Company/Dashboard', [
            'company' => $company,
            'stats' => [
                'totalCoaches' => $totalCoaches,
                'upcomingBookings' => $upcomingBookings,
                'newEnquiries' => $newEnquiries,
                'totalBookings' => $totalBookings,
            ],
            'recentBookings' => $recentBookings,
            'recentEnquiries' => $recentEnquiries,
        ]);
    }
}
