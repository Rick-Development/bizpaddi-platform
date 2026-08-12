<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TrustBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TrustBadgeController extends Controller
{
    public function index()
    {
        $badges = TrustBadge::orderBy('sort_order')->get();
        return view('admin::trust_badges.index', compact('badges'));
    }

    public function create()
    {
        return view('admin::trust_badges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_path' => 'required|image|max:5120',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['image_path'] = $request->file('image_path')->store('trust_badges', 'public');

        TrustBadge::create($validated);
        return redirect()->route('admin.trust-badges.index')->with('success', 'Badge created.');
    }

    public function edit(TrustBadge $trustBadge)
    {
        return view('admin::trust_badges.edit', compact('trustBadge'));
    }

    public function update(Request $request, TrustBadge $trustBadge)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image_path' => 'nullable|image|max:5120',
            'is_active' => 'boolean',
            'sort_order' => 'integer'
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        if ($request->hasFile('image_path')) {
            if ($trustBadge->image_path) {
                Storage::disk('public')->delete($trustBadge->image_path);
            }
            $validated['image_path'] = $request->file('image_path')->store('trust_badges', 'public');
        }

        $trustBadge->update($validated);
        return redirect()->route('admin.trust-badges.index')->with('success', 'Badge updated.');
    }

    public function destroy(TrustBadge $trustBadge)
    {
        if ($trustBadge->image_path) {
            Storage::disk('public')->delete($trustBadge->image_path);
        }
        $trustBadge->delete();
        return redirect()->route('admin.trust-badges.index')->with('success', 'Badge deleted.');
    }
}
