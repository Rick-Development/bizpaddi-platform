<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\About\Models\Timeline;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function index()
    {
        $timelines = Timeline::orderBy('year', 'desc')->orderBy('order')->get();
        return view('admin::timeline.index', compact('timelines'));
    }

    public function create()
    {
        return view('admin::timeline.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
        ]);

        Timeline::create($validated);

        return redirect()->route('admin.timeline.index')
                        ->with('success', 'Timeline event created successfully.');
    }

    public function edit(Timeline $timeline)
    {
        return view('admin::timeline.edit', compact('timeline'));
    }

    public function update(Request $request, Timeline $timeline)
    {
        $validated = $request->validate([
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 10),
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
        ]);

        $timeline->update($validated);

        return redirect()->route('admin.timeline.index')
                        ->with('success', 'Timeline event updated successfully.');
    }

    public function destroy(Timeline $timeline)
    {
        $timeline->delete();

        return redirect()->route('admin.timeline.index')
                        ->with('success', 'Timeline event deleted successfully.');
    }
}
