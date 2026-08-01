<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeamMemberController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role (Spatie)
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('department', 'like', "%{$request->search}%");
            });
        }

        $teamMembers = $query->latest()->paginate(12);
        // Deprecated self::ROLES, fetch from DB
        $roles = \Spatie\Permission\Models\Role::all();

        return view('admin::team-members.index', compact('teamMembers', 'roles'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $departments = \Modules\Admin\Models\Department::where('is_active', true)->get();
        return view('admin::team-members.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'department' => 'required|string|exists:departments,name', // Storing department name for now as per User model, but validating existence
            'phone' => 'nullable|string|max:20',
            'airtime_phone' => 'nullable|string|max:20',
            'data_phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspended,inactive',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        
        // Assign Spatie Role
        $user->assignRole($request->role);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member added successfully.');
    }

    public function show(User $teamMember)
    {
        // Get all performance records from when they started till now
        $performanceHistory = \Modules\ProjectManagement\Models\Performance::where('user_id', $teamMember->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Get current month performance
        $now = \Carbon\Carbon::now();
        $currentPerformance = $performanceHistory->where('month', $now->month)
            ->where('year', $now->year)
            ->first();

        // Calculate overall statistics
        $totalMonths = $performanceHistory->count();
        $averageScore = $performanceHistory->avg('score') ?? 0;
        $totalTasksCompleted = $performanceHistory->sum('tasks_completed');
        $totalTasksLate = $performanceHistory->sum('tasks_late');
        $totalTasksEarly = $performanceHistory->sum('tasks_early');
        
        // Calculate on-time percentage
        $totalTasks = $totalTasksCompleted + $totalTasksLate + $totalTasksEarly;
        $onTimePercentage = $totalTasks > 0 ? (($totalTasksCompleted + $totalTasksEarly) / $totalTasks) * 100 : 0;

        // Get assigned projects and tasks
        $assignedProjects = \Modules\ProjectManagement\Models\Project::whereHas('tasks', function($query) use ($teamMember) {
            $query->where('assigned_to', $teamMember->id);
        })->get();

        $assignedTasks = $teamMember->tasks()->with('project')->latest()->limit(10)->get();

        return view('admin::team-members.show', compact(
            'teamMember', 
            'currentPerformance', 
            'performanceHistory',
            'totalMonths',
            'averageScore',
            'totalTasksCompleted',
            'onTimePercentage',
            'assignedProjects', 
            'assignedTasks'
        ));
    }

    public function edit(User $teamMember)
    {
        $roles = \Spatie\Permission\Models\Role::all();
        $departments = \Modules\Admin\Models\Department::where('is_active', true)->get();
        return view('admin::team-members.edit', compact('teamMember', 'roles', 'departments'));
    }

    public function update(Request $request, User $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teamMember->id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|exists:roles,name',
            'department' => 'required|string|exists:departments,name',
            'phone' => 'nullable|string|max:20',
            'airtime_phone' => 'nullable|string|max:20',
            'data_phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,suspended,inactive',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $teamMember->update($validated);
        
        // Sync Spatie Role
        $teamMember->syncRoles([$request->role]);

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member updated successfully.');
    }

    public function destroy(User $teamMember)
    {
        $teamMember->delete();

        return redirect()->route('admin.team-members.index')
            ->with('success', 'Team member deleted successfully.');
    }

    public function updateStatus(Request $request, User $teamMember)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,suspended,inactive',
        ]);

        $teamMember->update($validated);

        return back()->with('success', 'Status updated successfully.');
    }

    public function performance(Request $request)
    {
        // Get filter parameters
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $teamMemberId = $request->input('team_member');

        // Build query for performance records
        $query = \Modules\ProjectManagement\Models\Performance::with('user')
            ->where('month', $month)
            ->where('year', $year);

        // Filter by specific team member if selected
        if ($teamMemberId) {
            $query->where('user_id', $teamMemberId);
        }

        $performances = $query->orderBy('score', 'desc')->get();

        // Get all team members for filter dropdown
        $teamMembers = User::orderBy('name')->get();

        // Calculate summary statistics
        $totalMembers = $performances->count();
        $averageScore = $performances->avg('score') ?? 0;
        $topPerformer = $performances->first();
        $totalTasksCompleted = $performances->sum('tasks_completed');

        return view('admin::team-members.performance', compact(
            'performances',
            'teamMembers',
            'month',
            'year',
            'teamMemberId',
            'totalMembers',
            'averageScore',
            'topPerformer',
            'totalTasksCompleted'
        ));
    }
}
