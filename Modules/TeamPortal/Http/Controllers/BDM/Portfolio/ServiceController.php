<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Portfolio;

use App\Http\Controllers\Controller;
use Modules\Services\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(20);
        return view('teamportal::bdm.portfolio.services.index', compact('services'));
    }

    public function create()
    {
        return view('teamportal::bdm.portfolio.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug',
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'icon' => 'nullable|image|max:1024',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service = Service::create($validated);

        if ($request->hasFile('icon')) {
            $service->addMediaFromRequest('icon')->toMediaCollection('icon');
        }

        return redirect()->route('team.bdm.services.index')
                        ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        return view('teamportal::bdm.portfolio.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:services,slug,' . $service->id,
            'description' => 'nullable|string',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'icon' => 'nullable|image|max:1024',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $service->update($validated);

        if ($request->hasFile('icon')) {
            $service->clearMediaCollection('icon');
            $service->addMediaFromRequest('icon')->toMediaCollection('icon');
        }

        return redirect()->route('team.bdm.services.index')
                        ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('team.bdm.services.index')
                        ->with('success', 'Service deleted successfully.');
    }
}
