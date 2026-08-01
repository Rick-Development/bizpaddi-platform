<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\TeamPortal\Models\WorkLog;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TeamMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $userId = $request->input('user_id');

        // Get all team members
        $usersQuery = User::orderBy('name');
        if ($userId) {
            $usersQuery->where('id', $userId);
        }
        $users = $usersQuery->get();
        $allUsers = User::orderBy('name')->get(); // For dropdown

        // Date range for the selected month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Don't count future days for absence calculation if current month
        $daysToCountLimit = $endDate->isFuture() ? Carbon::now() : $endDate;

        // Calculate total potential working days (excluding weekends) up to today/end of month
        $potentialWorkDays = 0;
        $tempDate = $startDate->copy();
        while ($tempDate->lte($daysToCountLimit)) {
            if (!$tempDate->isWeekend()) {
                $potentialWorkDays++;
            }
            $tempDate->addDay();
        }

        // Fetch logs for the period
        $logs = WorkLog::whereBetween('start_time', [$startDate, $endDate])->get();

        $monitoringData = $users->map(function ($user) use ($logs, $potentialWorkDays) {
            $userLogs = $logs->where('user_id', $user->id);
            
            // Days Present: Count distinct dates
            $daysPresent = $userLogs->pluck('start_time')
                ->map(fn($date) => $date->format('Y-m-d'))
                ->unique()
                ->count();

            // Total Duration
            $totalDurationMinutes = $userLogs->sum('duration_minutes');
            
            // Days Absent
            $daysAbsent = max(0, $potentialWorkDays - $daysPresent);

            return [
                'user' => $user,
                'days_present' => $daysPresent,
                'total_duration_minutes' => $totalDurationMinutes,
                'days_absent' => $daysAbsent,
                'avg_daily_hours' => $daysPresent > 0 ? round(($totalDurationMinutes / 60) / $daysPresent, 1) : 0,
            ];
        });

        // Summary Stats (for cards)
        $totalHours = round($monitoringData->sum('total_duration_minutes') / 60, 1);
        $avgAttendanceRate = $potentialWorkDays > 0 ? round(($monitoringData->sum('days_present') / ($users->count() * $potentialWorkDays)) * 100, 1) : 0;

        return view('admin::monitor.index', compact(
            'monitoringData', 
            'allUsers', 
            'year', 
            'month', 
            'totalHours', 
            'avgAttendanceRate',
            'potentialWorkDays'
        ));
    }
}
