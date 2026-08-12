<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ClientPortal\Models\SupportTicket;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with(['client', 'messages'])
            ->latest()
            ->paginate(20);
            
        return view('teamportal::bdm.support.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['client', 'messages.sender'])->findOrFail($id);
        return view('teamportal::bdm.support.tickets.show', compact('ticket'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
            'priority' => 'nullable|in:low,medium,high,critical',
        ]);

        $ticket = SupportTicket::findOrFail($id);
        $ticket->update($request->only(['status', 'priority']));

        return back()->with('success', 'Ticket status updated.');
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::findOrFail($id);

        $ticket->messages()->create([
            'admin_id' => auth()->id(), // BDM user responding
            'message' => $request->message,
            'is_admin' => true,
        ]);

        if ($ticket->status === 'closed') {
             $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Reply sent successfully.');
    }
}
