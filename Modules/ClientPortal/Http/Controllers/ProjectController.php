<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $client = auth()->guard('client')->user();
        $projects = $client->projects()->with(['tasks'])->latest()->paginate(10);

        return view('clientportal::projects.index', compact('projects'));
    }

    public function show($id)
    {
        $client = auth()->guard('client')->user();
        $project = $client->projects()->with(['tasks', 'currency'])->findOrFail($id);

        return view('clientportal::projects.show', compact('project'));
    }
}
