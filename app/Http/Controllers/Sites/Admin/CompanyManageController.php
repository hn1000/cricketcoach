<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Inertia\Inertia;

class CompanyManageController extends Controller
{
    public function dashboard(Company $company)
    {
        // Get staff statistics
        $totalStaff = $company->staff()->count();
        $activeStaff = $company->staff()->where('is_active', true)->count();
        $inactiveStaff = $company->staff()->where('is_active', false)->count();

        $stats = [
            'total_staff' => $totalStaff,
            'active_staff' => $activeStaff,
            'inactive_staff' => $inactiveStaff,
        ];

        return Inertia::render('Sites/Admin/Companies/Manage/Dashboard', [
            'company' => $company,
            'stats' => $stats,
        ]);
    }
}
