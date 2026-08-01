<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ProjectManagement\Models\Project;
use Modules\Clients\Models\Client;
use Illuminate\Http\Request;

class PMProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with(['client', 'tasks', 'manager'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->assigned_to, function($query, $id) {
                $query->whereHas('tasks', function($q) use ($id) {
                    $q->where('assigned_to', $id);
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin::pm-projects.index', compact('projects'));
    }

    public function create()
    {
        $clients = Client::all();
        $currencies = \Modules\Accounting\Models\Currency::where('is_active', true)->get();
        $projectManagers = \App\Models\User::role('project_manager')->get();
        return view('admin::pm-projects.create', compact('clients', 'currencies', 'projectManagers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,on_hold,completed,cancelled',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
            'currency_id' => 'nullable|exists:currencies,id',
        ]);

        Project::create($validated);

        return redirect()->route('admin.pm-projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $pmProject)
    {
        $pmProject->load(['tasks.assignee', 'tasks.timeLogs', 'client']);
        return view('admin::pm-projects.show', ['project' => $pmProject]);
    }

    public function edit(Project $pmProject)
    {
        $clients = Client::all();
        $currencies = \Modules\Accounting\Models\Currency::where('is_active', true)->get();
        $projectManagers = \App\Models\User::role('project_manager')->get();
        return view('admin::pm-projects.edit', ['project' => $pmProject, 'clients' => $clients, 'currencies' => $currencies, 'projectManagers' => $projectManagers]);
    }

    public function update(Request $request, Project $pmProject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,on_hold,completed,cancelled',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'budget' => 'nullable|numeric',
            'currency_id' => 'nullable|exists:currencies,id',
        ]);

        $pmProject->update($validated);

        return redirect()->route('admin.pm-projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $pmProject)
    {
        $pmProject->delete();
        return redirect()->route('admin.pm-projects.index')->with('success', 'Project deleted successfully.');
    }

    public function milestones(Project $pmProject)
    {
        return response()->json($pmProject->milestones);
    }
}
