<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Subsidiaries\Models\Subsidiary;
use Illuminate\Http\Request;

class SubsidiaryController extends Controller
{
    public function index()
    {
        $subsidiaries = Subsidiary::latest()->get();
        return view('admin::subsidiaries.index', compact('subsidiaries'));
    }

    public function create()
    {
        return view('admin::subsidiaries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'website' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $subsidiary = Subsidiary::create($validated);

        if ($request->hasFile('logo')) {
            $subsidiary->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        return redirect()->route('admin.subsidiaries.index')
                        ->with('success', 'Subsidiary added successfully.');
    }

    public function edit(Subsidiary $subsidiary)
    {
        return view('admin::subsidiaries.edit', compact('subsidiary'));
    }

    public function update(Request $request, Subsidiary $subsidiary)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'website' => 'nullable|url',
            'location' => 'nullable|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $subsidiary->update($validated);

        if ($request->hasFile('logo')) {
            $subsidiary->clearMediaCollection('logo');
            $subsidiary->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        return redirect()->route('admin.subsidiaries.index')
                        ->with('success', 'Subsidiary updated successfully.');
    }

    public function destroy(Subsidiary $subsidiary)
    {
        $subsidiary->delete();

        return redirect()->route('admin.subsidiaries.index')
                        ->with('success', 'Subsidiary deleted successfully.');
    }
}
