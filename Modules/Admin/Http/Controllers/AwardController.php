<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\About\Models\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index()
    {
        $awards = Award::orderBy('year', 'desc')->get();
        return view('admin::awards.index', compact('awards'));
    }

    public function create()
    {
        return view('admin::awards.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
        ]);

        Award::create($validated);

        return redirect()->route('admin.awards.index')
                        ->with('success', 'Award created successfully.');
    }

    public function edit(Award $award)
    {
        return view('admin::awards.edit', compact('award'));
    }

    public function update(Request $request, Award $award)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'nullable|string',
        ]);

        $award->update($validated);

        return redirect()->route('admin.awards.index')
                        ->with('success', 'Award updated successfully.');
    }

    public function destroy(Award $award)
    {
        $award->delete();

        return redirect()->route('admin.awards.index')
                        ->with('success', 'Award deleted successfully.');
    }
}
