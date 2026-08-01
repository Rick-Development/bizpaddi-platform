<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Home\Models\NavigationItem;

class NavigationItemController extends Controller
{
    public function index()
    {
        $navigationItems = NavigationItem::sorted()->get();
        return view('admin::navigation.index', compact('navigationItems'));
    }

    public function create()
    {
        return view('admin::navigation.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'location' => 'required|in:header,footer,both',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        NavigationItem::create($validated);

        return redirect()->route('admin.navigation.index')->with('success', 'Navigation item created successfully.');
    }

    public function edit(NavigationItem $navigation)
    {
        $navigationItem = $navigation;
        return view('admin::navigation.edit', compact('navigationItem'));
    }

    public function update(Request $request, NavigationItem $navigation)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'route_name' => 'nullable|string|max:255',
            'location' => 'required|in:header,footer,both',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $navigation->update($validated);

        return redirect()->route('admin.navigation.index')->with('success', 'Navigation item updated successfully.');
    }

    public function destroy(NavigationItem $navigation)
    {
        $navigation->delete();
        return redirect()->route('admin.navigation.index')->with('success', 'Navigation item deleted successfully.');
    }
}
