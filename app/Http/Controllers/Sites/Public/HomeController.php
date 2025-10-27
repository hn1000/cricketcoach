<?php

namespace App\Http\Controllers\Sites\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sites\Public\PublicCompanyResource;
use App\Models\Company;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $companies = Company::where('is_active', true)
            ->latest()
            ->get();

        return Inertia::render('Sites/Public/Home/Master', [
            'companies' => PublicCompanyResource::collection($companies),
        ]);
    }
}
