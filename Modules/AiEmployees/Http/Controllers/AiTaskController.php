<?php

namespace Modules\AiEmployees\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\AiEmployees\Models\AiEmployee;
use Modules\AiEmployees\Models\AiTask;
use Modules\AiEmployees\Jobs\ProcessAiTaskJob;

class AiTaskController extends Controller
{
    /**
     * Display a listing of assigned tasks.
     */
    public function index(Request $request)
    {
        $query = AiTask::with('employee');

        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $tasks = $query->latest()->paginate(10);
        $employees = AiEmployee::where('is_active', true)->get();

        return view('aiemployees::admin.tasks.index', compact('tasks', 'employees'));
    }

    /**
     * Show the form for assigning a new task.
     */
    public function create()
    {
        $employees = AiEmployee::where('is_active', true)
            ->whereNull('parent_id')
            ->where('is_sub_agent', false)
            ->get();
            
        return view('aiemployees::admin.tasks.create', compact('employees'));
    }

    /**
     * Store a newly assigned task in storage and dispatch it.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:ai_employees,id',
            'task' => 'required|string',
        ]);

        $employee = AiEmployee::findOrFail($validated['employee_id']);
        if (!$employee->is_active) {
            return redirect()->back()->withErrors(['employee_id' => 'This AI Employee is currently deactivated.']);
        }

        $aiTask = AiTask::create([
            'employee_id' => $validated['employee_id'],
            'task' => $validated['task'],
            'status' => 'pending'
        ]);

        ProcessAiTaskJob::dispatch($aiTask);

        return redirect()->route('admin.aiemployees.tasks.index')->with('success', 'Task successfully assigned and queued.');
    }

    /**
     * Show details of a task.
     */
    public function show($id)
    {
        $task = AiTask::with('employee')->findOrFail($id);
        
        $leads = [];
        if (class_exists('\Modules\Contact\Models\Lead') && $task->employee_id) {
            $leads = \Modules\Contact\Models\Lead::where('ai_employee_id', $task->employee_id)
                ->latest()
                ->limit(10)
                ->get();
        }
        
        return view('aiemployees::admin.tasks.show', compact('task', 'leads'));
    }

    /**
     * Show the form for editing the task description.
     */
    public function edit($id)
    {
        $task = AiTask::findOrFail($id);
        $employees = AiEmployee::where('is_active', true)
            ->whereNull('parent_id')
            ->where('is_sub_agent', false)
            ->get();
        return view('aiemployees::admin.tasks.edit', compact('task', 'employees'));
    }

    /**
     * Update the task description.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:ai_employees,id',
            'task' => 'required|string',
            'status' => 'required|in:pending,processing,completed,failed',
            'output' => 'nullable|string',
        ]);

        $task = AiTask::findOrFail($id);

        if ($task->status === 'processing' && $validated['status'] !== 'pending') {
            return redirect()->back()->with('error', 'Cannot update task while it is being processed by the AI.');
        }

        $oldStatus = $task->status;

        $outputVal = $validated['output'];
        if ($outputVal) {
            $jsonDecoded = json_decode($outputVal, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $outputVal = json_encode(['result' => $outputVal]);
            }
        }

        $task->update([
            'employee_id' => $validated['employee_id'],
            'task' => $validated['task'],
            'status' => $validated['status'],
            'output' => $outputVal,
        ]);

        if ($validated['status'] === 'pending' && $oldStatus !== 'pending') {
            ProcessAiTaskJob::dispatch($task);
        }

        return redirect()->route('admin.aiemployees.tasks.index')->with('success', 'Task details updated successfully.');
    }

    /**
     * Remove the task from storage.
     */
    public function destroy($id)
    {
        $task = AiTask::findOrFail($id);
        if ($task->status === 'processing') {
            return response()->json(['success' => false, 'message' => 'Cannot delete task while processing.'], 400);
        }

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task successfully removed.',
            'redirect' => route('admin.aiemployees.tasks.index')
        ]);
    }

    /**
     * Dispatch the task job again.
     */
    public function retry(Request $request, $id)
    {
        $task = AiTask::findOrFail($id);
        $task->update(['status' => 'pending', 'output' => null]);
        ProcessAiTaskJob::dispatch($task);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Task has been re-queued.'
            ]);
        }

        return redirect()->route('admin.aiemployees.tasks.index')->with('success', 'Task has been re-queued.');
    }
}
