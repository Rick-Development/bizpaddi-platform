<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Portfolio\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::withCount('projects')->orderBy('name')->get();
        return view('admin::industries.index', compact('industries'));
    }

    public function create()
    {
        return view('admin::industries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:industries,slug',
        ]);

        Industry::create($validated);

        return redirect()->route('admin.industries.index')
                        ->with('success', 'Industry created successfully.');
    }

    public function edit(Industry $industry)
    {
        return view('admin::industries.edit', compact('industry'));
    }

    public function update(Request $request, Industry $industry)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:industries,slug,' . $industry->id,
        ]);

        $industry->update($validated);

        return redirect()->route('admin.industries.index')
                        ->with('success', 'Industry updated successfully.');
    }

    public function destroy(Industry $industry)
    {
        if ($industry->projects()->count() > 0) {
            return redirect()->route('admin.industries.index')
                            ->with('error', 'Cannot delete industry with existing projects.');
        }

        $industry->delete();

        return redirect()->route('admin.industries.index')
                        ->with('success', 'Industry deleted successfully.');
    }
}
