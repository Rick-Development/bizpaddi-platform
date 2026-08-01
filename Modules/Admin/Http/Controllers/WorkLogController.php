<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\TeamPortal\Models\WorkLog;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class WorkLogController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkLog::with(['user'])->latest('start_time');

        // Filter by Date (default to today)
        if ($request->has('date')) {
            $date = Carbon::parse($request->date);
            $query->whereDate('start_time', $date);
        }

        // Filter by User
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $workLogs = $query->paginate(20)->withQueryString();
        $users = User::orderBy('name')->get();

        return view('admin::work-logs.index', compact('workLogs', 'users'));
    }

    public function show(WorkLog $workLog)
    {
        $workLog->load(['user']);
        return view('admin::work-logs.show', compact('workLog'));
    }
}
