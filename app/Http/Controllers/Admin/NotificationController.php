<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display all notifications for admin users
     */
    public function index()
    {
        $notifications = Notification::whereHas('user', function ($query) {
            $query->whereHas('role', function ($q) {
                $q->whereIn('name', ['admin', 'staff']);
            });
        })
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.pages.notifications.index', compact('notifications'));
    }

    /**
     * Mark a specific notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back();
    }

    /**
     * Mark all admin notifications as read
     */
    public function markAllRead()
    {
        Notification::whereHas('user', function ($query) {
            $query->whereHas('role', function ($q) {
                $q->whereIn('name', ['admin', 'staff']);
            });
        })->where('is_read', false)->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc');
    }
}
