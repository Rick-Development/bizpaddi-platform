<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Home\Models\HomeStat;

class HomeStatsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = HomeStat::orderBy('sort_order')->get();
        return view('admin::home.stats.index', compact('stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::home.stats.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'integer|default:0',
        ]);

        HomeStat::create($validated);

        return redirect()->route('admin.home.stats.index')->with('success', 'Stat created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $stat = HomeStat::findOrFail($id);
        return view('admin::home.stats.edit', compact('stat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'value' => 'required|string|max:255',
            'label' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'integer',
        ]);

        $stat = HomeStat::findOrFail($id);
        $stat->update($validated);

        return redirect()->route('admin.home.stats.index')->with('success', 'Stat updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $stat = HomeStat::findOrFail($id);
        $stat->delete();

        return redirect()->route('admin.home.stats.index')->with('success', 'Stat deleted successfully.');
    }
}
