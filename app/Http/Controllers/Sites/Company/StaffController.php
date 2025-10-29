<?php

namespace App\Http\Controllers\Sites\Company;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    /**
     * Display a listing of staff for the user's companies
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $companyIds = $user->getCompanyIds();

        $staff = Staff::whereIn('company_id', $companyIds)
            ->with('company')
            ->latest()
            ->paginate(20);

        return Inertia::render('Sites/Company/Staff/Index', [
            'staff' => $staff,
        ]);
    }

    /**
     * Show the form for creating a new staff member
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $companies = $user->companies;

        return Inertia::render('Sites/Company/Staff/Create', [
            'companies' => $companies,
        ]);
    }

    /**
     * Store a newly created staff member
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'qualifications' => 'nullable|array',
            'specialties' => 'nullable|array',
            'lesson_types' => 'nullable|array',
            'facilities' => 'nullable|array',
            'technology_available' => 'nullable|array',
        ]);

        // Verify user can manage this company
        if (!$user->canManageCompany($validated['company_id'])) {
            abort(403, 'You do not have permission to add staff to this company.');
        }

        Staff::create($validated);

        return redirect()->route('company.staff.index')
            ->with('success', 'Coach added successfully');
    }

    /**
     * Show the form for editing the specified staff member
     */
    public function edit(Request $request, Staff $staff)
    {
        $user = $request->user();

        // Verify user can manage this staff's company
        if (!$user->canManageCompany($staff->company_id)) {
            abort(403, 'You do not have permission to edit this coach.');
        }

        $companies = $user->companies;

        return Inertia::render('Sites/Company/Staff/Edit', [
            'staff' => $staff,
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified staff member
     */
    public function update(Request $request, Staff $staff)
    {
        $user = $request->user();

        // Verify user can manage this staff's company
        if (!$user->canManageCompany($staff->company_id)) {
            abort(403, 'You do not have permission to update this coach.');
        }

        $validated = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'qualifications' => 'nullable|array',
            'specialties' => 'nullable|array',
            'lesson_types' => 'nullable|array',
            'facilities' => 'nullable|array',
            'technology_available' => 'nullable|array',
        ]);

        // Verify user can manage the new company (if changed)
        if (!$user->canManageCompany($validated['company_id'])) {
            abort(403, 'You do not have permission to move staff to this company.');
        }

        $staff->update($validated);

        return redirect()->route('company.staff.index')
            ->with('success', 'Coach updated successfully');
    }

    /**
     * Remove the specified staff member
     */
    public function destroy(Request $request, Staff $staff)
    {
        $user = $request->user();

        // Verify user can manage this staff's company
        if (!$user->canManageCompany($staff->company_id)) {
            abort(403, 'You do not have permission to delete this coach.');
        }

        $staff->delete();

        return redirect()->route('company.staff.index')
            ->with('success', 'Coach deleted successfully');
    }
}
