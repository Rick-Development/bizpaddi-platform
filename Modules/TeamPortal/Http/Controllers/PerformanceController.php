<?php

namespace Modules\TeamPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Performance;
use Modules\ProjectManagement\Models\KPI;
use Modules\ProjectManagement\Models\Task;
use Carbon\Carbon;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $now = Carbon::now();
        $month = $request->get('month', $now->month);
        $year = $request->get('year', $now->year);

        $performance = Performance::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        // If no performance record yet, it might mean no tasks completed this month
        // We'll show a default of 100% and current stats
        if (!$performance) {
            $performance = new Performance([
                'user_id' => $user->id,
                'month' => $month,
                'year' => $year,
                'score' => 100.00,
                'tasks_completed' => 0,
                'tasks_late' => 0,
                'tasks_early' => 0,
            ]);
        }

        $kpis = KPI::all();
        
        // Fetch recent tasks that impacted this score
        $recentTasks = Task::where('assigned_to', $user->id)
            ->whereNotNull('completed_at')
            ->whereYear('completed_at', $year)
            ->whereMonth('completed_at', $month)
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('teamportal::performance.index', compact('performance', 'kpis', 'recentTasks', 'month', 'year'));
    }
}
