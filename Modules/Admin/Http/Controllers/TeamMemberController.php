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
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:5120',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

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
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|max:5120',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($teamMember->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($teamMember->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($teamMember->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
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
        $period = $request->input('period', 'monthly');
        $teamMemberId = $request->input('team_member', '');
        
        $startDate = \Carbon\Carbon::now()->startOfMonth();
        $endDate = \Carbon\Carbon::now()->endOfMonth();
        $periodLabel = '';
        
        if ($period === 'custom' && ($request->filled('start_date') || $request->filled('end_date'))) {
            $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : \Carbon\Carbon::minValue();
            $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : \Carbon\Carbon::now()->endOfDay();
            $periodLabel = $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        } elseif ($period === 'daily') {
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::today();
            $startDate = $refDate->copy()->startOfDay();
            $endDate = $refDate->copy()->endOfDay();
            $periodLabel = $refDate->format('F d, Y');
        } elseif ($period === 'weekly') {
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now();
            $startDate = $refDate->copy()->startOfWeek();
            $endDate = $refDate->copy()->endOfWeek();
            $periodLabel = $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        } elseif ($period === 'yearly') {
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now();
            $startDate = $refDate->copy()->startOfYear();
            $endDate = $refDate->copy()->endOfYear();
            $periodLabel = $startDate->format('Y');
        } else {
            // Default Monthly
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now();
            $startDate = $refDate->copy()->startOfMonth();
            $endDate = $refDate->copy()->endOfMonth();
            $periodLabel = $startDate->format('F Y');
        }

        // Target Users
        $targetUsers = collect();
        if ($teamMemberId === 'group_active') {
            $targetUsers = User::where('status', 'active')->orderBy('name')->get();
        } elseif ($teamMemberId === 'group_suspended') {
            $targetUsers = User::where('status', 'suspended')->orderBy('name')->get();
        } elseif ($teamMemberId === 'group_inactive') {
            $targetUsers = User::where('status', 'inactive')->orderBy('name')->get();
        } elseif (!empty($teamMemberId) && is_numeric($teamMemberId)) {
            $targetUsers = User::where('id', $teamMemberId)->get();
        } else {
            $targetUsers = User::where('status', 'active')->orderBy('name')->get(); // Default to all active
        }

        $performances = collect();
        $totalTasksCompleted = 0;

        foreach ($targetUsers as $user) {
            $metrics = \Modules\ProjectManagement\Services\PerformanceEngine::calculate($user->id, $startDate, $endDate);
            $totalTasksCompleted += $metrics['tasks']['completed'];
            
            // Create a pseudo-model to match the view's expectations
            $performances->push((object)[
                'user' => $user,
                'score' => $metrics['score'],
                'tasks_completed' => $metrics['tasks']['completed'],
                'tasks_late' => $metrics['tasks']['late'],
                'tasks_early' => $metrics['tasks']['early'],
                'bonus_points' => $metrics['total_bonus'],
                'penalty_points' => $metrics['total_penalty'],
                'metrics' => $metrics
            ]);
        }

        $performances = $performances->sortByDesc('score')->values();

        // All users for dropdown
        $teamMembers = User::orderBy('name')->get();
        $totalMembers = $performances->count();
        $averageScore = $totalMembers > 0 ? $performances->avg('score') : 0;
        $topPerformer = $performances->first();

        return view('admin::team-members.performance', compact(
            'performances',
            'teamMembers',
            'period',
            'periodLabel',
            'teamMemberId',
            'totalMembers',
            'averageScore',
            'topPerformer',
            'totalTasksCompleted',
            'startDate',
            'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $teamMemberId = $request->input('team_member', '');
        
        $startDate = \Carbon\Carbon::now()->startOfMonth();
        $endDate = \Carbon\Carbon::now()->endOfMonth();
        $periodLabel = '';
        
        if ($period === 'custom' && ($request->filled('start_date') || $request->filled('end_date'))) {
            $startDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date)->startOfDay() : \Carbon\Carbon::minValue();
            $endDate = $request->filled('end_date') ? \Carbon\Carbon::parse($request->end_date)->endOfDay() : \Carbon\Carbon::now()->endOfDay();
            $periodLabel = $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        } elseif ($period === 'daily') {
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::today();
            $startDate = $refDate->copy()->startOfDay();
            $endDate = $refDate->copy()->endOfDay();
            $periodLabel = $refDate->format('F d, Y');
        } elseif ($period === 'weekly') {
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now();
            $startDate = $refDate->copy()->startOfWeek();
            $endDate = $refDate->copy()->endOfWeek();
            $periodLabel = $startDate->format('M d, Y') . ' - ' . $endDate->format('M d, Y');
        } elseif ($period === 'yearly') {
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now();
            $startDate = $refDate->copy()->startOfYear();
            $endDate = $refDate->copy()->endOfYear();
            $periodLabel = $startDate->format('Y');
        } else {
            // Default Monthly
            $refDate = $request->filled('start_date') ? \Carbon\Carbon::parse($request->start_date) : \Carbon\Carbon::now();
            $startDate = $refDate->copy()->startOfMonth();
            $endDate = $refDate->copy()->endOfMonth();
            $periodLabel = $startDate->format('F Y');
        }

        // Target Users
        $targetUsers = collect();
        if ($teamMemberId === 'group_active') {
            $targetUsers = User::where('status', 'active')->orderBy('name')->get();
        } elseif ($teamMemberId === 'group_suspended') {
            $targetUsers = User::where('status', 'suspended')->orderBy('name')->get();
        } elseif ($teamMemberId === 'group_inactive') {
            $targetUsers = User::where('status', 'inactive')->orderBy('name')->get();
        } elseif (!empty($teamMemberId) && is_numeric($teamMemberId)) {
            $targetUsers = User::where('id', $teamMemberId)->get();
        } else {
            $targetUsers = User::where('status', 'active')->orderBy('name')->get(); // Default to all active
        }

        $performances = collect();
        $totalTasksCompleted = 0;

        foreach ($targetUsers as $user) {
            $metrics = \Modules\ProjectManagement\Services\PerformanceEngine::calculate($user->id, $startDate, $endDate);
            $totalTasksCompleted += $metrics['tasks']['completed'];
            
            $performances->push((object)[
                'user' => $user,
                'score' => $metrics['score'],
                'tasks_completed' => $metrics['tasks']['completed'],
                'tasks_late' => $metrics['tasks']['late'],
                'tasks_early' => $metrics['tasks']['early'],
                'bonus_points' => $metrics['total_bonus'],
                'penalty_points' => $metrics['total_penalty'],
                'metrics' => $metrics
            ]);
        }

        $performances = $performances->sortByDesc('score')->values();
        $totalMembers = $performances->count();
        $averageScore = $totalMembers > 0 ? $performances->avg('score') : 0;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin::team-members.export-pdf', compact(
            'performances',
            'period',
            'periodLabel',
            'teamMemberId',
            'totalMembers',
            'averageScore',
            'totalTasksCompleted',
            'startDate',
            'endDate'
        ));

        // Use orientation landscape for more space if needed, though portrait is fine.
        $pdf->setPaper('A4', 'portrait');

        if ($request->has('preview')) {
            return $pdf->stream('Team_Performance_Report.pdf');
        }

        return $pdf->download('Team_Performance_Report.pdf');
    }
}
