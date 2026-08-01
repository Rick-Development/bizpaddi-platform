<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\About\Models\Leader;
use Illuminate\Http\Request;

class LeaderController extends Controller
{
    public function index()
    {
        $leaders = Leader::orderBy('order')->get();
        return view('admin::leaders.index', compact('leaders'));
    }

    public function create()
    {
        return view('admin::leaders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'photo' => 'nullable|image|max:2048',
            'order' => 'required|integer|min:0',
        ]);

        $leader = Leader::create($validated);

        if ($request->hasFile('photo')) {
            $leader->addMediaFromRequest('photo')
                   ->toMediaCollection('photo');
        }

        return redirect()->route('admin.leaders.index')
                        ->with('success', 'Leader created successfully.');
    }

    public function edit(Leader $leader)
    {
        return view('admin::leaders.edit', compact('leader'));
    }

    public function update(Request $request, Leader $leader)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'linkedin' => 'nullable|url|max:255',
            'photo' => 'nullable|image|max:2048',
            'order' => 'required|integer|min:0',
        ]);

        $leader->update($validated);

        if ($request->hasFile('photo')) {
            $leader->clearMediaCollection('photo');
            $leader->addMediaFromRequest('photo')
                   ->toMediaCollection('photo');
        }

        return redirect()->route('admin.leaders.index')
                        ->with('success', 'Leader updated successfully.');
    }

    public function destroy(Leader $leader)
    {
        $leader->delete();

        return redirect()->route('admin.leaders.index')
                        ->with('success', 'Leader deleted successfully.');
    }
}
