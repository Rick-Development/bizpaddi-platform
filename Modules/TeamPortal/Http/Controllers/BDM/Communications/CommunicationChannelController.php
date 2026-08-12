<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Communications;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\CommunicationChannel;
use Illuminate\Http\Request;

class CommunicationChannelController extends Controller
{
    public function index()
    {
        $channels = CommunicationChannel::latest()->get();
        return view('teamportal::bdm.communications.communication-channels.index', compact('channels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        CommunicationChannel::create($validated);

        return redirect()->back()->with('success', 'Channel created successfully.');
    }

    public function update(Request $request, CommunicationChannel $channel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $channel->update($validated);

        return redirect()->back()->with('success', 'Channel updated successfully.');
    }

    public function destroy(CommunicationChannel $channel)
    {
        $channel->delete();
        return redirect()->back()->with('success', 'Channel deleted successfully.');
    }
}
