<?php

namespace Modules\Admin\Http\Controllers\Api\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Accounting\Models\Currency;

class CurrencyController extends ApiController
{
    public function index()
    {
        $currencies = Currency::all();
        return $this->sendResponse($currencies, 'Currencies retrieved successfully.');
    }

    public function show($id)
    {
        $currency = Currency::find($id);
        if (!$currency) return $this->sendError('Currency not found.', 404);
        return $this->sendResponse($currency, 'Currency details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $currency = Currency::create($request->all());
        return $this->sendResponse($currency, 'Currency created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);
        if (!$currency) return $this->sendError('Currency not found.', 404);
        $currency->update($request->all());
        return $this->sendResponse($currency, 'Currency updated successfully.');
    }

    public function destroy($id)
    {
        $currency = Currency::find($id);
        if (!$currency) return $this->sendError('Currency not found.', 404);
        $currency->delete();
        return $this->sendResponse([], 'Currency deleted successfully.');
    }
}
