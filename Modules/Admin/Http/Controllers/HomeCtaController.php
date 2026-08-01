<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Home\Models\HomeCta;

class HomeCtaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ctas = HomeCta::all();
        return view('admin::home.ctas.index', compact('ctas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::home.ctas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button1_text' => 'nullable|string|max:255',
            'button1_link' => 'nullable|string|max:255',
            'button2_text' => 'nullable|string|max:255',
            'button2_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        HomeCta::create($validated);

        return redirect()->route('admin.home.ctas.index')->with('success', 'CTA created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cta = HomeCta::findOrFail($id);
        return view('admin::home.ctas.edit', compact('cta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button1_text' => 'nullable|string|max:255',
            'button1_link' => 'nullable|string|max:255',
            'button2_text' => 'nullable|string|max:255',
            'button2_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $cta = HomeCta::findOrFail($id);
        $cta->update($validated);

        return redirect()->route('admin.home.ctas.index')->with('success', 'CTA updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cta = HomeCta::findOrFail($id);
        $cta->delete();

        return redirect()->route('admin.home.ctas.index')->with('success', 'CTA deleted successfully.');
    }
}
