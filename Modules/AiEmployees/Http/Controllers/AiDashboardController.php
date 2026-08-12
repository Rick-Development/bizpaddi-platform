<?php

namespace Modules\AiEmployees\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\AiEmployees\Models\AiEmployee;
use Modules\AiEmployees\Models\AiTask;
use Modules\AiEmployees\Models\AiPendingAction;
use Modules\AiEmployees\Jobs\ProcessAiTaskJob;
use Modules\AiEmployees\Services\AiOrchestratorService;
use Modules\Mcp\Services\McpService;

class AiDashboardController extends Controller
{
    public function index()
    {
        $tasksProcessed = AiTask::count();
        $completedTasks = AiTask::where('status', 'completed')->count();
        $failedTasks = AiTask::where('status', 'failed')->count();
        $totalFinished = $completedTasks + $failedTasks;
        
        $successRate = $totalFinished > 0 
            ? round(($completedTasks / $totalFinished) * 100, 1) 
            : 100.0;

        $stats = [
            'total_agents' => AiEmployee::count(),
            'active_agents' => AiEmployee::where('is_active', true)->count(),
            'tasks_processed' => $tasksProcessed,
            'success_rate' => $successRate,
        ];
        
        $agents = AiEmployee::where('is_active', true)
            ->whereNull('parent_id')
            ->where('is_sub_agent', false)
            ->get();
        $pendingActions = AiPendingAction::with('employee')->where('status', 'pending')->latest()->get();
        
        // Update stats
        $stats['pending_reviews'] = $pendingActions->count();
        
        return view('aiemployees::admin.dashboard', compact('stats', 'agents', 'pendingActions'));
    }

    public function processTask(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:ai_employees,id',
            'task' => 'required|string',
        ]);

        $employee = AiEmployee::findOrFail($request->employee_id);

        if (!$employee->is_active) {
            return response()->json(['text' => 'This AI Employee is currently deactivated.'], 400);
        }
        
        $aiTask = AiTask::create([
            'employee_id' => $employee->id,
            'task' => $request->task,
            'status' => 'pending'
        ]);

        ProcessAiTaskJob::dispatch($aiTask);

        return response()->json([
            'task_id' => $aiTask->id,
            'status' => 'pending',
            'message' => 'Task queued for processing'
        ]);
    }

    public function pollTask($id)
    {
        $task = AiTask::findOrFail($id);
        
        if ($task->status === 'completed' || $task->status === 'failed') {
            return response()->json([
                'status' => $task->status,
                'result' => json_decode($task->output, true)
            ]);
        }

        return response()->json([
            'status' => $task->status,
        ]);
    }

    public function approveAction(Request $request, $id, McpService $mcpService)
    {
        $action = AiPendingAction::findOrFail($id);
        
        if ($action->status !== 'pending') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Action is no longer pending.'], 422);
            }
            return back()->with('error', 'Action is no longer pending.');
        }

        try {
            $result = $mcpService->executeTool($action->tool_name, $action->tool_arguments);
            
            $action->update([
                'status' => 'executed',
                'result' => is_array($result) ? json_encode($result) : $result
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Action approved and executed successfully.']);
            }
            return back()->with('success', 'Action approved and executed successfully.');
        } catch (\Exception $e) {
            $action->update([
                'status' => 'failed',
                'result' => $e->getMessage()
            ]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Action failed during execution: ' . $e->getMessage()], 500);
            }
            return back()->with('error', 'Action failed during execution: ' . $e->getMessage());
        }
    }

    public function rejectAction(Request $request, $id)
    {
        $action = AiPendingAction::findOrFail($id);
        
        if ($action->status !== 'pending') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Action is no longer pending.'], 422);
            }
            return back()->with('error', 'Action is no longer pending.');
        }

        $action->update(['status' => 'rejected']);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Action has been rejected.']);
        }
        return back()->with('success', 'Action has been rejected.');
    }

    public function runRoutines(\Illuminate\Http\Request $request)
    {
        try {
            $params = [];
            $roleName = 'all';
            if ($request->filled('role')) {
                $params['--role'] = $request->role;
                $roleName = str_replace('_', ' ', \Illuminate\Support\Str::title($request->role));
            }
            
            \Illuminate\Support\Facades\Artisan::call('ai:run-autonomous-routines', $params);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Autonomous routines triggered successfully for active {$roleName} AI employees."
                ]);
            }

            return back()->with('success', "Autonomous routines triggered successfully for active {$roleName} AI employees.");
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error running routines: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error running routines: ' . $e->getMessage());
        }
    }

    public function pendingActions(Request $request)
    {
        $pendingActions = AiPendingAction::with('employee')
            ->where('status', 'pending')
            ->latest()
            ->paginate(20);
            
        return view('aiemployees::admin.pending_actions.index', compact('pendingActions'));
    }

    public function bulkApprove(Request $request, McpService $mcpService)
    {
        // Support both JSON body and form-encoded input
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            $json = json_decode($request->getContent(), true);
            $ids = $json['ids'] ?? [];
        }
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No actions selected.'], 422);
        }

        $actions = AiPendingAction::whereIn('id', $ids)->where('status', 'pending')->get();
        if ($actions->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No pending actions found to approve.'], 422);
        }

        $successCount = 0;
        $failCount = 0;
        $errors = [];

        foreach ($actions as $action) {
            try {
                $result = $mcpService->executeTool($action->tool_name, $action->tool_arguments);
                $action->update([
                    'status' => 'executed',
                    'result' => is_array($result) ? json_encode($result) : $result
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $action->update([
                    'status' => 'failed',
                    'result' => $e->getMessage()
                ]);
                $failCount++;
                $errors[] = "Action #{$action->id} failed: " . $e->getMessage();
            }
        }

        $msg = "Successfully approved and executed {$successCount} actions.";
        if ($failCount > 0) {
            $msg .= " Failed to execute {$failCount} actions.";
        }

        return response()->json([
            'success' => $successCount > 0,
            'message' => $msg,
            'errors' => $errors
        ]);
    }

    public function bulkReject(Request $request)
    {
        // Support both JSON body and form-encoded input
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            $json = json_decode($request->getContent(), true);
            $ids = $json['ids'] ?? [];
        }
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['success' => false, 'message' => 'No actions selected.'], 422);
        }

        $count = AiPendingAction::whereIn('id', $ids)
            ->where('status', 'pending')
            ->update(['status' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => "Successfully rejected {$count} actions."
        ]);
    }
}
