<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\CommunicationScript;
use Modules\Communications\Models\CommunicationChannel;
use Illuminate\Http\Request;

class CommunicationScriptController extends Controller
{
    public function index(Request $request)
    {
        $query = CommunicationScript::with('channel');

        if ($request->has('channel_id')) {
            $query->where('channel_id', $request->channel_id);
        }

        $scripts = $query->latest()->paginate(20);
        $channels = CommunicationChannel::where('is_active', true)->get();

        return view('admin::communication-scripts.index', compact('scripts', 'channels'));
    }

    public function create()
    {
        $channels = CommunicationChannel::where('is_active', true)->get();
        return view('admin::communication-scripts.create', compact('channels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'channel_id' => 'required|exists:communication_channels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $script = CommunicationScript::create($validated);

        return redirect()->route('admin.communication-scripts.edit', $script)
            ->with('success', 'Script created. Now add steps.');
    }

    public function edit(CommunicationScript $communication_script)
    {
        $communication_script->load(['steps.exceptions', 'channel']);
        return view('admin::communication-scripts.edit', ['script' => $communication_script]);
    }
    
    public function show(CommunicationScript $communication_script)
    {
        $communication_script->load(['steps.exceptions', 'channel']);
        return view('admin::communication-scripts.view', ['script' => $communication_script]);
    }

    public function update(Request $request, CommunicationScript $communication_script)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $communication_script->update($validated);

        return redirect()->back()->with('success', 'Script updated successfully.');
    }

    public function destroy(CommunicationScript $communication_script)
    {
        $communication_script->delete();
        return redirect()->route('admin.communication-scripts.index')
            ->with('success', 'Script deleted successfully.');
    }
}
