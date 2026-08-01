<?php

namespace Modules\ClientPortal\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ClientPortal\Models\SupportTicket;

class TicketController extends ApiController
{
    public function index(Request $request)
    {
        $clientId = $request->user()->id;
        
        $tickets = SupportTicket::where('client_id', $clientId)
            ->latest()
            ->paginate(10);

        return $this->sendResponse($tickets, 'Support tickets retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket = SupportTicket::create([
            'client_id' => $request->user()->id,
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'priority' => $validated['priority'],
            'status' => 'open',
            'ticket_id' => 'TKT-' . strtoupper(uniqid()),
        ]);

        return $this->sendResponse($ticket, 'Support ticket created successfully.', 201);
    }

    public function show($id)
    {
        $clientId = auth()->id();
        
        $ticket = SupportTicket::where('client_id', $clientId)->find($id);

        if (!$ticket) {
            return $this->sendError('Support ticket not found.');
        }

        return $this->sendResponse($ticket, 'Ticket details retrieved successfully.');
    }
}
