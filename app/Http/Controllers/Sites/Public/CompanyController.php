<?php

namespace App\Http\Controllers\Sites\Public;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Staff;
use App\Services\AvailabilityService;
use App\Services\GeolocationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    protected AvailabilityService $availabilityService;
    protected GeolocationService $geolocationService;

    public function __construct(AvailabilityService $availabilityService, GeolocationService $geolocationService)
    {
        $this->availabilityService = $availabilityService;
        $this->geolocationService = $geolocationService;
    }

    /**
     * Display a listing of active companies with advanced search and filtering
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $location = $request->input('location');
        $distance = $request->input('distance', 20); // Default 20 miles
        $specialties = $request->input('specialties', []);
        $lessonTypes = $request->input('lesson_types', []);
        $facilities = $request->input('facilities', []);
        $technology = $request->input('technology', []);

        // Geocode the location if provided
        $userCoordinates = null;
        if ($location) {
            $userCoordinates = $this->geolocationService->geocode($location);
        }

        $query = Company::query()
            ->where('is_active', true)
            ->with(['staff' => function ($staffQuery) use ($specialties, $lessonTypes, $facilities, $technology) {
                $staffQuery->where('is_active', true);

                // Filter staff by specialties
                if (!empty($specialties)) {
                    $staffQuery->where(function ($q) use ($specialties) {
                        foreach ($specialties as $specialty) {
                            $q->orWhereJsonContains('specialties', $specialty);
                        }
                    });
                }

                // Filter staff by lesson types
                if (!empty($lessonTypes)) {
                    $staffQuery->where(function ($q) use ($lessonTypes) {
                        foreach ($lessonTypes as $type) {
                            $q->orWhereJsonContains('lesson_types', $type);
                        }
                    });
                }

                // Filter staff by facilities
                if (!empty($facilities)) {
                    $staffQuery->where(function ($q) use ($facilities) {
                        foreach ($facilities as $facility) {
                            $q->orWhereJsonContains('facilities', $facility);
                        }
                    });
                }

                // Filter staff by technology
                if (!empty($technology)) {
                    $staffQuery->where(function ($q) use ($technology) {
                        foreach ($technology as $tech) {
                            $q->orWhereJsonContains('technology_available', $tech);
                        }
                    });
                }
            }]);

        // Text search
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Location-based filtering
        if ($userCoordinates) {
            $boundingBox = $this->geolocationService->getBoundingBox(
                $userCoordinates['latitude'],
                $userCoordinates['longitude'],
                $distance
            );

            $query->whereBetween('latitude', [$boundingBox['minLat'], $boundingBox['maxLat']])
                ->whereBetween('longitude', [$boundingBox['minLng'], $boundingBox['maxLng']]);
        }

        // Get companies
        $companies = $query->get();

        // Calculate distances and filter by exact radius if location provided
        if ($userCoordinates) {
            $companies = $companies->map(function ($company) use ($userCoordinates) {
                if ($company->latitude && $company->longitude) {
                    $company->distance = round($this->geolocationService->calculateDistance(
                        $userCoordinates['latitude'],
                        $userCoordinates['longitude'],
                        $company->latitude,
                        $company->longitude
                    ), 1);
                }
                return $company;
            })->filter(function ($company) use ($distance) {
                return !isset($company->distance) || $company->distance <= $distance;
            })->sortBy('distance')->values();
        } else {
            $companies = $companies->sortBy('name')->values();
        }

        // Filter companies that have matching staff (if filters applied)
        if (!empty($specialties) || !empty($lessonTypes) || !empty($facilities) || !empty($technology)) {
            $companies = $companies->filter(function ($company) {
                return $company->staff->count() > 0;
            })->values();
        }

        // Manual pagination
        $perPage = 12;
        $currentPage = $request->input('page', 1);
        $total = $companies->count();
        $companies = $companies->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginatedCompanies = new \Illuminate\Pagination\LengthAwarePaginator(
            $companies,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return Inertia::render('Sites/Public/Companies/Index', [
            'companies' => $paginatedCompanies,
            'filters' => [
                'search' => $search,
                'location' => $location,
                'distance' => $distance,
                'specialties' => $specialties,
                'lesson_types' => $lessonTypes,
                'facilities' => $facilities,
                'technology' => $technology,
            ],
            'userCoordinates' => $userCoordinates,
            'filterOptions' => [
                'specialties' => ['batting', 'bowling', 'wicket-keeping', 'all-rounder', 'junior', 'fielding'],
                'lesson_types' => ['in-person', 'virtual', 'group', 'individual', 'academy'],
                'facilities' => ['indoor-studio', 'outdoor-nets', 'grass-pitch', 'astro-pitch', 'video-analysis'],
                'technology' => ['video-analysis', 'bowling-machine', 'speed-gun', 'ball-tracking', 'biomechanics'],
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
