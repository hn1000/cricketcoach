<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sites\Admin\StaffResource;
use App\Models\Company;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StaffController extends Controller
{
    public function index(Company $company)
    {
        $staff = $company->staff()->latest()->paginate(10);

        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Index', [
            'company' => $company,
            'staff' => StaffResource::collection($staff),
        ]);
    }

    public function create(Company $company)
    {
        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Create', [
            'company' => $company,
        ]);
    }

    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $company->staff()->create($validated);

        return redirect()->route('admin.companies.staff.index', $company)
            ->with('success', 'Staff member created successfully.');
    }

    public function edit(Company $company, Staff $staff)
    {
        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Edit', [
            'company' => $company,
            'staff' => new StaffResource($staff),
        ]);
    }

    public function update(Request $request, Company $company, Staff $staff)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'hire_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $staff->update($validated);

        return redirect()->route('admin.companies.staff.index', $company)
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy(Company $company, Staff $staff)
    {
        $staff->delete();

        return redirect()->route('admin.companies.staff.index', $company)
            ->with('success', 'Staff member deleted successfully.');
    }
}
