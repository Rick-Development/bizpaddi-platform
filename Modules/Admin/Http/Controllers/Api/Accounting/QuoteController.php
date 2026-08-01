<?php

namespace Modules\Admin\Http\Controllers\Api\Accounting;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\Api\ApiController;
use Modules\Accounting\Models\Quote;

class QuoteController extends ApiController
{
    public function index()
    {
        $quotes = Quote::with('client')->latest()->paginate(20);
        return $this->sendResponse($quotes, 'Quotes retrieved successfully.');
    }

    public function show($id)
    {
        $quote = Quote::with(['items', 'client'])->find($id);
        if (!$quote) return $this->sendError('Quote not found.', 404);
        return $this->sendResponse($quote, 'Quote details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = $request->user()->id;
        $quote = Quote::create($data);
        return $this->sendResponse($quote, 'Quote created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $quote = Quote::find($id);
        if (!$quote) return $this->sendError('Quote not found.', 404);
        $quote->update($request->all());
        return $this->sendResponse($quote, 'Quote updated successfully.');
    }

    public function destroy($id)
    {
        $quote = Quote::find($id);
        if (!$quote) return $this->sendError('Quote not found.', 404);
        $quote->delete();
        return $this->sendResponse([], 'Quote deleted successfully.');
    }
}
