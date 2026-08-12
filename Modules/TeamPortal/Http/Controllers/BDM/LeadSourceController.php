<?php

namespace Modules\TeamPortal\Http\Controllers\BDM;

use App\Http\Controllers\Controller;
use Modules\Contact\Models\LeadSource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeadSourceController extends Controller
{
    public function index()
    {
        $sources = LeadSource::orderBy('sort_order')->orderBy('name')->paginate(20);
        return view('teamportal::bdm.lead-sources.index', compact('sources'));
    }

    public function create()
    {
        return view('teamportal::bdm.lead-sources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $count = 1;
        while (LeadSource::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        LeadSource::create($validated);

        return redirect()->route('team.bdm.lead-sources.index')
            ->with('success', 'Lead Source created successfully.');
    }

    public function edit(LeadSource $leadSource)
    {
        return view('teamportal::bdm.lead-sources.edit', compact('leadSource'));
    }

    public function update(Request $request, LeadSource $leadSource)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure slug is unique
        $originalSlug = $validated['slug'];
        $count = 1;
        while (LeadSource::where('slug', $validated['slug'])->where('id', '!=', $leadSource->id)->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        $leadSource->update($validated);

        return redirect()->route('team.bdm.lead-sources.index')
            ->with('success', 'Lead Source updated successfully.');
    }

    public function destroy(LeadSource $leadSource)
    {
        $leadSource->delete();

        return redirect()->route('team.bdm.lead-sources.index')
            ->with('success', 'Lead Source deleted successfully.');
    }
}
