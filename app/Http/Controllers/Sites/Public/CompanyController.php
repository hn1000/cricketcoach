<?php

namespace App\Http\Controllers\Sites\Public;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Staff;
use App\Services\AvailabilityService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    protected AvailabilityService $availabilityService;

    public function __construct(AvailabilityService $availabilityService)
    {
        $this->availabilityService = $availabilityService;
    }

    /**
     * Display a listing of active companies
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $companies = Company::where('is_active', true)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(12);

        return Inertia::render('Sites/Public/Companies/Index', [
            'companies' => $companies,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Display the specified company with staff members
     */
    public function show(Company $company)
    {
        if (!$company->is_active) {
            abort(404);
        }

        $company->load(['staff' => function ($query) {
            $query->where('is_active', true)->orderBy('first_name');
        }]);

        return Inertia::render('Sites/Public/Companies/Show', [
            'company' => $company,
        ]);
    }

    /**
     * Get available staff for a company
     */
    public function staff(Company $company)
    {
        if (!$company->is_active) {
            abort(404);
        }

        $staff = $company->staff()
            ->where('is_active', true)
            ->select('id', 'company_id', 'first_name', 'last_name', 'position', 'department')
            ->orderBy('first_name')
            ->get();

        return response()->json([
            'staff' => $staff,
        ]);
    }

    /**
     * Get available time slots for a staff member
     */
    public function availability(Request $request, Company $company, Staff $staff)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if (!$company->is_active || !$staff->is_active || $staff->company_id !== $company->id) {
            abort(404);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Get available slots for the date range
        $slotsByDate = $this->availabilityService->getAvailableSlotsForRange(
            $staff,
            $startDate,
            $endDate
        );

        return response()->json([
            'slots' => $slotsByDate,
        ]);
    }

    /**
     * Validate if a specific time slot is still available
     */
    public function validateSlot(Request $request, Company $company, Staff $staff)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);

        if (!$company->is_active || !$staff->is_active || $staff->company_id !== $company->id) {
            return response()->json([
                'available' => false,
                'message' => 'Staff member not found or inactive',
            ], 404);
        }

        $date = $request->input('date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $isAvailable = $this->availabilityService->isSlotAvailable(
            $staff,
            $date,
            $startTime,
            $endTime
        );

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable
                ? 'Time slot is available'
                : 'Time slot is no longer available',
        ]);
    }
}
