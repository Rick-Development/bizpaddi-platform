<?php

namespace Modules\ClientPortal\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClientPortal\Models\SupportTicket;
use Modules\ClientPortal\Models\SupportTicketMessage;

class TicketController extends Controller
{
    public function index()
    {
        $client = auth()->guard('client')->user();
        $tickets = $client->supportTickets()->latest()->paginate(10);

        return view('clientportal::tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('clientportal::tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string',
        ]);

        $client = auth()->guard('client')->user();

        $ticket = $client->supportTickets()->create([
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
        ]);

        $ticket->messages()->create([
            'sender_id' => $client->id,
            'sender_type' => get_class($client),
            'message' => $request->message,
        ]);

        return redirect()->route('client.tickets.show', $ticket)
            ->with('success', 'Ticket created successfully.');
    }

    public function show($id)
    {
        $client = auth()->guard('client')->user();
        $ticket = $client->supportTickets()->with('messages.sender')->findOrFail($id);

        return view('clientportal::tickets.show', compact('ticket'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $client = auth()->guard('client')->user();
        $ticket = $client->supportTickets()->findOrFail($id);

        $ticket->messages()->create([
            'sender_id' => $client->id,
            'sender_type' => get_class($client),
            'message' => $request->message,
        ]);

        // Re-open ticket if it was closed (optional logic)
        if ($ticket->status === 'closed' || $ticket->status === 'resolved') {
            $ticket->update(['status' => 'open']);
        }

        return redirect()->route('client.tickets.show', $ticket)
            ->with('success', 'Reply sent successfully.');
    }
}
