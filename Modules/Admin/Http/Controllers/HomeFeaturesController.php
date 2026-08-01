<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Home\Models\HomeFeature;

class HomeFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = HomeFeature::orderBy('sort_order')->get();
        return view('admin::home.features.index', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::home.features.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'integer|default:0',
        ]);

        HomeFeature::create($validated);

        return redirect()->route('admin.home.features.index')->with('success', 'Feature created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $feature = HomeFeature::findOrFail($id);
        return view('admin::home.features.edit', compact('feature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'integer',
        ]);

        $feature = HomeFeature::findOrFail($id);
        $feature->update($validated);

        return redirect()->route('admin.home.features.index')->with('success', 'Feature updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $feature = HomeFeature::findOrFail($id);
        $feature->delete();

        return redirect()->route('admin.home.features.index')->with('success', 'Feature deleted successfully.');
    }
}
