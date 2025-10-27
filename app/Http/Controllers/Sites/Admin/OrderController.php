<?php

namespace App\Http\Controllers\Sites\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['company', 'items.orderable', 'payment'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by company
        if ($request->has('company_id') && $request->company_id !== '') {
            $query->where('company_id', $request->company_id);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Search by customer or order number
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        $companies = Company::select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('Sites/Admin/Orders/Index', [
            'orders' => $orders,
            'companies' => $companies,
            'filters' => [
                'status' => $request->status,
                'company_id' => $request->company_id,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'search' => $request->search,
            ],
        ]);
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load([
            'company',
            'items.orderable',
            'payment',
        ]);

        return Inertia::render('Sites/Admin/Orders/Show', [
            'order' => $order,
        ]);
    }

    /**
     * Update the order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,refunded',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    /**
     * Process a refund for an order
     */
    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0.01|max:' . $order->total,
            'reason' => 'nullable|string|max:500',
        ]);

        if ($order->status !== 'paid') {
            return redirect()->back()->with('error', 'Only paid orders can be refunded.');
        }

        if (!$order->payment || !$order->payment->payment_intent_id) {
            return redirect()->back()->with('error', 'No payment information found for this order.');
        }

        try {
            $amount = $request->amount ?? $order->total;
            $reason = $request->reason ?? 'Requested by customer';

            $refund = $this->paymentService->refundOrder($order, $amount, $reason);

            $order->update([
                'status' => 'refunded',
            ]);

            // Update associated bookings to cancelled
            foreach ($order->items as $item) {
                if ($item->orderable_type === 'App\\Models\\Booking') {
                    $item->orderable->update([
                        'status' => 'cancelled',
                        'cancellation_reason' => 'Order refunded: ' . $reason,
                        'cancelled_at' => now(),
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Refund processed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to process refund: ' . $e->getMessage());
        }
    }

    /**
     * Get order statistics
     */
    public function statistics(Request $request)
    {
        $query = Order::query();

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from !== '') {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to !== '') {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $statistics = [
            'total' => (clone $query)->count(),
            'total_revenue' => (clone $query)->where('status', 'paid')->sum('total'),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'paid' => (clone $query)->where('status', 'paid')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'refunded' => (clone $query)->where('status', 'refunded')->count(),
        ];

        return response()->json($statistics);
    }
}
