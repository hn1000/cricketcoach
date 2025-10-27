<?php

namespace App\Http\Controllers\Sites\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Order::where('user_id', $user->id)
            ->with(['company', 'items.orderable', 'payment']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by order number
        if ($request->has('search') && $request->search) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $orders = $query->paginate(10)->withQueryString();

        return Inertia::render('Sites/Customer/Orders/Index', [
            'orders' => $orders,
            'filters' => $request->only(['status', 'date_from', 'date_to', 'search', 'sort', 'direction']),
        ]);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $user = auth()->user();

        // Ensure user owns this order
        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        $order->load(['company', 'items.orderable', 'payment']);

        return Inertia::render('Sites/Customer/Orders/Show', [
            'order' => $order,
        ]);
    }

    /**
     * Download invoice for an order
     */
    public function downloadInvoice(Order $order)
    {
        $user = auth()->user();

        // Ensure user owns this order
        if ($order->user_id !== $user->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        // TODO: Implement PDF generation
        // For now, return a simple text response
        return response()->json([
            'message' => 'Invoice download feature coming soon.',
            'order_number' => $order->order_number,
        ]);
    }
}
