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
        return view('admin::notifications.index', compact('clients'));
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
}
