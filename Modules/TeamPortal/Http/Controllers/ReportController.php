<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\TeamPortal\Models\DailyReport; // Assuming model exists or need to create
use App\Models\User;

class ReportController extends Controller
{
    public function index()
    {
        $reports = auth()->user()->reports()->latest()->paginate(10);
        return view('teamportal::reports.index', compact('reports'));
    }

    public function create()
    {
        return view('teamportal::reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tasks_completed' => 'required|string',
            'tasks_planned' => 'required|string',
            'challenges' => 'nullable|string',
            'hours_worked' => 'required|numeric',
        ]);

        auth()->user()->reports()->create([
            'tasks_completed' => $request->tasks_completed,
            'tasks_planned' => $request->tasks_planned,
            'challenges' => $request->challenges,
            'content' => $request->tasks_completed . "\n\nPlanned:\n" . $request->tasks_planned, // Fallback content
            'hours_worked' => $request->hours_worked,
        ]);

        return redirect()->route('team.reports.index')->with('success', 'Report submitted successfully.');
    }

    public function show($id)
    {
        $report = auth()->user()->reports()->findOrFail($id);
        return view('teamportal::reports.show', compact('report'));
    }
}
