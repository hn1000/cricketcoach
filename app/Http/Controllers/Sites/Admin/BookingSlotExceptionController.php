<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sites\Admin\BookingSlotExceptionResource;
use App\Models\BookingSlotException;
use App\Models\Company;
use App\Models\Staff;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingSlotExceptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Company $company, Staff $staff)
    {
        $exceptions = BookingSlotException::where('staff_id', $staff->id)
            ->orderBy('exception_date', 'desc')
            ->get();

        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Exceptions/Index', [
            'company' => $company,
            'staff' => $staff,
            'exceptions' => BookingSlotExceptionResource::collection($exceptions),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Company $company, Staff $staff)
    {
        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Exceptions/Create', [
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
            'exception_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'type' => ['required', 'in:unavailable,custom_hours'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // If type is unavailable and no times specified, it's a full day block
        if ($validated['type'] === 'unavailable' && !$validated['start_time'] && !$validated['end_time']) {
            // Full day exception - no additional validation needed
        } elseif ($validated['start_time'] && $validated['end_time']) {
            // Partial day exception - times are required
        } else {
            return back()->withErrors([
                'start_time' => 'Both start and end times are required for partial day exceptions.',
            ]);
        }

        $staff->bookingSlotExceptions()->create($validated);

        return redirect()
            ->route('admin.companies.staff.exceptions.index', [$company->id, $staff->id])
            ->with('success', 'Exception created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company, Staff $staff, BookingSlotException $exception)
    {
        return Inertia::render('Sites/Admin/Companies/Manage/Staff/Exceptions/Edit', [
            'company' => $company,
            'staff' => $staff,
            'exception' => new BookingSlotExceptionResource($exception),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company, Staff $staff, BookingSlotException $exception)
    {
        $validated = $request->validate([
            'exception_date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
            'type' => ['required', 'in:unavailable,custom_hours'],
            'reason' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        // Validate times based on type
        if ($validated['type'] === 'unavailable' && !$validated['start_time'] && !$validated['end_time']) {
            // Full day exception
        } elseif ($validated['start_time'] && $validated['end_time']) {
            // Partial day exception
        } else {
            return back()->withErrors([
                'start_time' => 'Both start and end times are required for partial day exceptions.',
            ]);
        }

        $exception->update($validated);

        return redirect()
            ->route('admin.companies.staff.exceptions.index', [$company->id, $staff->id])
            ->with('success', 'Exception updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company, Staff $staff, BookingSlotException $exception)
    {
        $exception->delete();

        return redirect()
            ->route('admin.companies.staff.exceptions.index', [$company->id, $staff->id])
            ->with('success', 'Exception deleted successfully.');
    }
}
