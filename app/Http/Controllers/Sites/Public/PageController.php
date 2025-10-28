<?php

namespace App\Http\Controllers\Sites\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * Show the About Us page
     */
    public function about()
    {
        return Inertia::render('Sites/Public/Pages/About');
    }

    /**
     * Show the How It Works page
     */
    public function howItWorks()
    {
        return Inertia::render('Sites/Public/Pages/HowItWorks');
    }

    /**
     * Show the FAQ page
     */
    public function faq()
    {
        return Inertia::render('Sites/Public/Pages/FAQ');
    }

    /**
     * Show the Contact Us page
     */
    public function contact()
    {
        return Inertia::render('Sites/Public/Pages/Contact');
    }

    /**
     * Handle contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        // TODO: Send email to admin
        // For now, just flash a success message

        return redirect()->back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Show the Privacy Policy page
     */
    public function privacy()
    {
        return Inertia::render('Sites/Public/Pages/Privacy');
    }

    /**
     * Show the Terms of Service page
     */
    public function terms()
    {
        return Inertia::render('Sites/Public/Pages/Terms');
    }
}
