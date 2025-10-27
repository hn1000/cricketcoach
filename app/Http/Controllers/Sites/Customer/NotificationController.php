<?php

namespace App\Http\Controllers\Sites\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = $user->notifications();

        // Filter by read/unread
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->filter === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->paginate(20);

        return Inertia::render('Sites/Customer/Notifications/Index', [
            'notifications' => $notifications,
            'filter' => $request->get('filter', 'all'),
            'unreadCount' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        return response()->json([
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $notification = $user->notifications()->findOrFail($id);

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted.',
        ]);
    }
}
