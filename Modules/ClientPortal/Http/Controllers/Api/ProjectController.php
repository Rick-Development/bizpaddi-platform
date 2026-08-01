<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;

class ProjectController extends ApiController
{
    public function index(Request $request)
    {
        $clientId = $request->user()->id;
        
        $projects = Project::where('client_id', $clientId)
            ->with(['manager:id,name,email', 'currency'])
            ->latest()
            ->paginate(10);

        return $this->sendResponse($projects, 'Projects retrieved successfully.');
    }

    public function show($id)
    {
        $clientId = auth()->id();
        
        $project = Project::where('client_id', $clientId)
            ->with(['manager', 'currency', 'tasks' => function($q) {
                $q->latest()->take(5);
            }])
            ->find($id);

        if (!$project) {
            return $this->sendError('Project not found.');
        }

        return $this->sendResponse($project, 'Project details retrieved successfully.');
    }
}
