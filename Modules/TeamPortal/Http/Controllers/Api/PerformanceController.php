<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\Performance;
use Modules\ProjectManagement\Models\KPI;

class PerformanceController extends ApiController
{
    /**
     * GET /api/v1/team/performance
     * Get performance metrics and KPIs for the authenticated team member.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $performance = Performance::where('user_id', $user->id)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(10);

        // Fetch assigned KPIs
        $kpis = KPI::all();

        return $this->sendResponse([
            'performance_reviews' => $performance,
            'kpis' => $kpis,
        ], 'Performance metrics retrieved successfully.');
    }
}
