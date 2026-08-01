<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Quote;

class QuoteController extends ApiController
{
    public function index(Request $request)
    {
        // Assuming client_id matches the authenticated user ID
        $clientId = $request->user()->id;
        
        $quotes = Quote::where('client_id', $clientId)
            ->with(['currency', 'items'])
            ->latest()
            ->paginate(10);

        return $this->sendResponse($quotes, 'Quotes retrieved successfully.');
    }

    public function show($id)
    {
        $clientId = auth()->id();
        
        $quote = Quote::where('client_id', $clientId)
            ->with(['currency', 'items'])
            ->find($id);

        if (!$quote) {
            return $this->sendError('Quote not found.');
        }

        return $this->sendResponse($quote, 'Quote details retrieved successfully.');
    }
}
