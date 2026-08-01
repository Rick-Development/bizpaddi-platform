<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Investor\Models\PressRelease;
use Illuminate\Support\Str;

class InvestorPressReleaseController extends Controller
{
    public function index()
    {
        $pressReleases = PressRelease::orderBy('published_at', 'desc')
            ->paginate(15);
            
        return view('admin::investor.press-releases.index', compact('pressReleases'));
    }

    public function create()
    {
        return view('admin::investor.press-releases.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:press_releases,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category' => 'required|in:earnings,corporate,product,other',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if (!isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        PressRelease::create($validated);

        return redirect()->route('admin.investor.press-releases.index')
            ->with('success', 'Press release created successfully.');
    }

    public function edit(PressRelease $pressRelease)
    {
        return view('admin::investor.press-releases.edit', compact('pressRelease'));
    }

    public function update(Request $request, PressRelease $pressRelease)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:press_releases,slug,' . $pressRelease->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'category' => 'required|in:earnings,corporate,product,other',
            'is_featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $pressRelease->update($validated);

        return redirect()->route('admin.investor.press-releases.index')
            ->with('success', 'Press release updated successfully.');
    }

    public function destroy(PressRelease $pressRelease)
    {
        $pressRelease->delete();
        
        return redirect()->route('admin.investor.press-releases.index')
            ->with('success', 'Press release deleted successfully.');
    }
}
