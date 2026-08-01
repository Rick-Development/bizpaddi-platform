<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\ClientPortal\Models\SupportTicket as Ticket;

class TicketController extends ApiController
{
    public function index(Request $request)
    {
        $tickets = Ticket::with('client')->latest()->paginate(20);
        return $this->sendResponse($tickets, 'Tickets retrieved successfully.');
    }

    public function show($id)
    {
        $ticket = Ticket::with(['client', 'messages'])->find($id);
        if (!$ticket) return $this->sendError('Ticket not found.', 404);
        return $this->sendResponse($ticket, 'Ticket details retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) return $this->sendError('Ticket not found.', 404);

        $validated = $request->validate([
            'status' => 'sometimes|string',
            'priority' => 'sometimes|string',
            'client_id' => 'sometimes|exists:clients,id',
            'subject' => 'sometimes|string|max:255',
        ]);

        $ticket->update($validated);
        return $this->sendResponse($ticket->fresh(), 'Ticket updated successfully.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) return $this->sendError('Ticket not found.', 404);
        $ticket->delete();
        return $this->sendResponse([], 'Ticket deleted successfully.');
    }
}
