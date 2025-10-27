<?php

namespace App\Http\Controllers\Sites\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Get upcoming bookings (next 3)
        $upcomingBookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'pending_payment'])
            ->where('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->with(['company', 'staff'])
            ->limit(3)
            ->get();

        // Get recent orders (last 5)
        $recentOrders = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->with(['company', 'items'])
            ->limit(5)
            ->get();

        // Calculate stats
        $stats = [
            'total_bookings' => Booking::where('user_id', $user->id)->count(),
            'active_bookings' => Booking::where('user_id', $user->id)
                ->whereIn('status', ['confirmed', 'pending_payment'])
                ->where('booking_date', '>=', now()->toDateString())
                ->count(),
            'completed_bookings' => Booking::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Order::where('user_id', $user->id)
                ->where('status', 'paid')
                ->sum('total'),
            'unread_notifications' => $user->unreadNotifications()->count(),
        ];

        return Inertia::render('Sites/Customer/Dashboard', [
            'upcomingBookings' => $upcomingBookings,
            'recentOrders' => $recentOrders,
            'stats' => $stats,
        ]);
    }
}
