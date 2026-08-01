<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Clients\Models\Client;

class ClientController extends ApiController
{
    /**
     * GET /api/v1/team/clients
     * List clients that the team member is allowed to see (based on assigned projects).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('You do not have permission to view clients.', 403);
        }

        // Get clients associated with projects where this user is assigned tasks or is the manager
        $clients = Client::whereHas('projects', function ($query) use ($user) {
            $query->where('manager_id', $user->id)
                  ->orWhereHas('tasks', function ($q) use ($user) {
                      $q->where('assigned_to', $user->id);
                  });
        })
        ->paginate(15);

        return $this->sendResponse($clients, 'Clients retrieved successfully.');
    }

    /**
     * GET /api/v1/team/clients/{id}
     * Get details of a specific client.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('You do not have permission to view clients.', 403);
        }

        $client = Client::whereHas('projects', function ($query) use ($user) {
            $query->where('manager_id', $user->id)
                  ->orWhereHas('tasks', function ($q) use ($user) {
                      $q->where('assigned_to', $user->id);
                  });
        })->find($id);

        if (!$client) {
            return $this->sendError('Client not found or you do not have access.', 404);
        }

        return $this->sendResponse($client, 'Client details retrieved successfully.');
    }
}
