<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Get user from multiple guards (admin, web, client)
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user() ?? Auth::guard('client')->user();

        if (!$user) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = $user->notifications()->take(50)->get()->map(function($notif) {
            return [
                'id' => $notif->id,
                'data' => $notif->data,
                'read_at' => $notif->read_at,
                'time_ago' => $notif->created_at->diffForHumans()
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count()
        ]);
    }

    public function markAsRead($id)
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user() ?? Auth::guard('client')->user();
        if ($user) {
            $notification = $user->notifications()->where('id', $id)->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::guard('admin')->user() ?? Auth::guard('web')->user() ?? Auth::guard('client')->user();
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
        return response()->json(['success' => true]);
    }
}
