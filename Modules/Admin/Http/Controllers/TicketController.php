<?php

namespace Modules\Admin\Http\Controllers;

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
            
        return view('admin::support.tickets.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = SupportTicket::with(['client', 'messages.sender'])->findOrFail($id);
        return view('admin::support.tickets.show', compact('ticket'));
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
            'admin_id' => auth('admin')->id(),
            'message' => $request->message,
            'is_admin' => true,
        ]);

        // If ticket was closed, re-open it? Optional. 
        // For now just keep status or maybe set to 'in_progress'
        if ($ticket->status === 'closed') {
             $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Reply sent successfully.');
    }
    
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted.');
    }
}
