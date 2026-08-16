<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\FirebaseNotificationService;
use Modules\Clients\Models\Client;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(FirebaseNotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $clients = Client::where('is_active', true)->orderBy('name')->get();
        $teamMembers = \App\Models\User::orderBy('name')->get();
        return view('admin::notifications.index', compact('clients', 'teamMembers'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'exists:clients,id'
        ]);

        $result = $this->notificationService->sendToClients(
            $request->client_ids,
            $request->title,
            $request->message,
            ['type' => 'admin_broadcast']
        );

        if ($result['success']) {
            return redirect()->route('admin.notifications.index')
                ->with('success', 'Notification sent successfully to ' . count($request->client_ids) . ' client(s).');
        }

        return redirect()->route('admin.notifications.index')
            ->with('error', 'Failed to send notification: ' . ($result['message'] ?? 'Unknown error'));
    }

    public function sendToTeam(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id'
        ]);

        $users = \App\Models\User::whereIn('id', $request->user_ids)->get();
        $notification = new \App\Notifications\AdminBroadcastNotification($request->title, $request->message);

        foreach ($users as $user) {
            $user->notify($notification);
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', 'Notification broadcasted successfully to ' . $users->count() . ' team member(s).');
    }
}
