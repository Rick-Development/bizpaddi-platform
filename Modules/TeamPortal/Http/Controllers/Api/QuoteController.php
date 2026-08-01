<?php

namespace Modules\TeamPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Accounting\Models\Quote;

class QuoteController extends ApiController
{
    /**
     * GET /api/v1/team/quotes
     * List quotes related to the user's projects or clients.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('You do not have permission to view quotes.', 403);
        }

        $quotes = Quote::where('created_by', $user->id)
            ->latest()
            ->paginate(15);

        return $this->sendResponse($quotes, 'Quotes retrieved successfully.');
    }

    /**
     * GET /api/v1/team/quotes/{id}
     * Get details of a specific quote.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->isProjectManager()) {
            return $this->sendError('You do not have permission to view quotes.', 403);
        }

        $quote = Quote::with(['items', 'client'])->where('created_by', $user->id)->find($id);

        if (!$quote) {
            return $this->sendError('Quote not found.', 404);
        }

        return $this->sendResponse($quote, 'Quote details retrieved successfully.');
    }
}
