<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::withCount('services')->orderBy('sort_order')->get();
        return view('admin::accounting.service-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin::accounting.service-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('service-categories', 'public');
        }

        ServiceCategory::create($validated);

        return redirect()->route('admin.accounting.service-categories.index')
            ->with('success', 'Service category created successfully.');
    }

    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->load('services');
        return view('admin::accounting.service-categories.show', compact('serviceCategory'));
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin::accounting.service-categories.edit', compact('serviceCategory'));
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($serviceCategory->image && Storage::disk('public')->exists($serviceCategory->image)) {
                Storage::disk('public')->delete($serviceCategory->image);
            }
            $validated['image'] = $request->file('image')->store('service-categories', 'public');
        }

        $serviceCategory->update($validated);

        return redirect()->route('admin.accounting.service-categories.index')
            ->with('success', 'Service category updated successfully.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();
        return redirect()->route('admin.accounting.service-categories.index')
            ->with('success', 'Service category deleted successfully.');
    }
}
