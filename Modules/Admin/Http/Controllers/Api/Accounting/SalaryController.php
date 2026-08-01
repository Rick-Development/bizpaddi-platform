<?php

namespace Modules\Admin\Http\Controllers\Api\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Admin\Models\SalaryDisbursement;

class SalaryController extends ApiController
{
    public function index()
    {
        $salaries = SalaryDisbursement::with('user')->latest()->paginate(20);
        return $this->sendResponse($salaries, 'Salary records retrieved successfully.');
    }

    public function show($id)
    {
        $salary = SalaryDisbursement::with('user')->find($id);
        if (!$salary) return $this->sendError('Salary record not found.', 404);
        return $this->sendResponse($salary, 'Salary details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $salary = SalaryDisbursement::create($request->all());
        return $this->sendResponse($salary, 'Salary record created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $salary = SalaryDisbursement::find($id);
        if (!$salary) return $this->sendError('Salary record not found.', 404);
        $salary->update($request->all());
        return $this->sendResponse($salary, 'Salary record updated successfully.');
    }

    public function destroy($id)
    {
        $salary = SalaryDisbursement::find($id);
        if (!$salary) return $this->sendError('Salary record not found.', 404);
        $salary->delete();
        return $this->sendResponse([], 'Salary record deleted successfully.');
    }
}
