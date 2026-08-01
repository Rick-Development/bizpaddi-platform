<?php

namespace Modules\Admin\Http\Controllers\Api\Contracts;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Contracts\Models\Contract;

class ContractController extends ApiController
{
    public function index(Request $request)
    {
        $contracts = Contract::with('client')->latest()->paginate(20);
        return $this->sendResponse($contracts, 'Contracts retrieved successfully.');
    }

    public function show($id)
    {
        $contract = Contract::with('client')->find($id);
        if (!$contract) return $this->sendError('Contract not found.', 404);
        return $this->sendResponse($contract, 'Contract details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $contract = Contract::create($request->all());
        return $this->sendResponse($contract, 'Contract created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $contract = Contract::find($id);
        if (!$contract) return $this->sendError('Contract not found.', 404);
        $contract->update($request->all());
        return $this->sendResponse($contract, 'Contract updated successfully.');
    }

    public function destroy($id)
    {
        $contract = Contract::find($id);
        if (!$contract) return $this->sendError('Contract not found.', 404);
        $contract->delete();
        return $this->sendResponse([], 'Contract deleted successfully.');
    }
}
