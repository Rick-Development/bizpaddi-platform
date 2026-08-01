<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Home\Models\FeaturedContent;
use Illuminate\Http\Request;

class FeaturedContentController extends Controller
{
    public function index()
    {
        $contents = FeaturedContent::orderBy('order')->get();
        return view('admin::featured-content.index', compact('contents'));
    }

    public function create()
    {
        return view('admin::featured-content.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'type' => 'required|in:service,project,news',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        FeaturedContent::create($validated);

        return redirect()->route('admin.featured-content.index')
                        ->with('success', 'Featured content created successfully.');
    }

    public function edit(FeaturedContent $featuredContent)
    {
        return view('admin::featured-content.edit', compact('featuredContent'));
    }

    public function update(Request $request, FeaturedContent $featuredContent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'type' => 'required|in:service,project,news',
            'link' => 'nullable|string|max:255',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $featuredContent->update($validated);

        return redirect()->route('admin.featured-content.index')
                        ->with('success', 'Featured content updated successfully.');
    }

    public function destroy(FeaturedContent $featuredContent)
    {
        $featuredContent->delete();

        return redirect()->route('admin.featured-content.index')
                        ->with('success', 'Featured content deleted successfully.');
    }
}
