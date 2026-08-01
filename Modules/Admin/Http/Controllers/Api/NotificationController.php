<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends ApiController
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications()->paginate(20);
        return $this->sendResponse($notifications, 'Notifications retrieved successfully.');
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
        return $this->sendResponse([], 'Notification marked as read.');
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return $this->sendResponse([], 'All notifications marked as read.');
    }
}
