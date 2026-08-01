<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Admin\Models\SalaryDisbursement;

class EarningsController extends ApiController
{
    /**
     * GET /api/v1/team/earnings
     * Get a list of salary disbursements and earnings for the user.
     */
    public function index(Request $request)
    {
        $earnings = SalaryDisbursement::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return $this->sendResponse($earnings, 'Earnings retrieved successfully.');
    }

    /**
     * GET /api/v1/team/earnings/{id}
     * Get details of a specific earning/salary disbursement.
     */
    public function show(Request $request, $id)
    {
        $earning = SalaryDisbursement::where('user_id', $request->user()->id)->find($id);

        if (!$earning) {
            return $this->sendError('Earning record not found.', 404);
        }

        return $this->sendResponse($earning, 'Earning details retrieved successfully.');
    }
}
