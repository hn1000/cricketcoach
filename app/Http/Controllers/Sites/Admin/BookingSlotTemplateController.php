<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sites\Admin\BookingSlotTemplateResource;
use App\Models\BookingSlotTemplate;
use App\Models\Company;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingSlotTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company, Staff $staff)
    {
        $templates = BookingSlotTemplate::where('staff_id', $staff->id)
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Availability/Index', [
            'company' => $company,
            'staff' => $staff,
            'templates' => BookingSlotTemplateResource::collection($templates),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company, Staff $staff)
    {
        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Availability/Create', [
            'company' => $company,
            'staff' => $staff,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Company $company, Staff $staff)
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'min:15', 'max:480'],
            'is_active' => ['boolean'],
            'effective_from' => ['nullable', 'date'],
            'effective_until' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Check for overlapping templates
        $template = new BookingSlotTemplate($validated);
        if ($template->overlapsWithTemplate(
            $staff->id,
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time']
        )) {
            return back()->withErrors([
                'start_time' => 'This time slot overlaps with an existing availability template.',
            ]);
        }

        $staff->bookingSlotTemplates()->create($validated);

        return redirect()
            ->route('admin.companies.staff.availability.index', [$company->id, $staff->id])
            ->with('success', 'Availability template created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company, Staff $staff, BookingSlotTemplate $template)
    {
        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Availability/Edit', [
            'company' => $company,
            'staff' => $staff,
            'template' => new BookingSlotTemplateResource($template),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Staff $staff, BookingSlotTemplate $template)
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'slot_duration' => ['required', 'integer', 'min:15', 'max:480'],
            'is_active' => ['boolean'],
            'effective_from' => ['nullable', 'date'],
            'effective_until' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Check for overlapping templates (excluding current one)
        $testTemplate = new BookingSlotTemplate($validated);
        if ($testTemplate->overlapsWithTemplate(
            $staff->id,
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time'],
            $template->id
        )) {
            return back()->withErrors([
                'start_time' => 'This time slot overlaps with an existing availability template.',
            ]);
        }

        $template->update($validated);

        return redirect()
            ->route('admin.companies.staff.availability.index', [$company->id, $staff->id])
            ->with('success', 'Availability template updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Staff $staff, BookingSlotTemplate $template)
    {
        $template->delete();

        return redirect()
            ->route('admin.companies.staff.availability.index', [$company->id, $staff->id])
            ->with('success', 'Availability template deleted successfully.');
    }
}
