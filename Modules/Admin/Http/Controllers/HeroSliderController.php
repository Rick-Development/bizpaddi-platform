<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Home\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::orderBy('order')->get();
        return view('admin::hero-sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin::hero-sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'bg_image' => 'nullable|image|max:2048',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('bg_image')) {
            $validated['bg_image'] = $request->file('bg_image')->store('hero-sliders', 'public');
        }

        HeroSlider::create($validated);

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Hero slider created successfully.');
    }

    public function edit(HeroSlider $heroSlider)
    {
        return view('admin::hero-sliders.edit', compact('heroSlider'));
    }

    public function update(Request $request, HeroSlider $heroSlider)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'bg_image' => 'nullable|image|max:2048',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('bg_image')) {
            // Delete old image
            if ($heroSlider->bg_image && Storage::disk('public')->exists($heroSlider->bg_image)) {
                Storage::disk('public')->delete($heroSlider->bg_image);
            }
            $validated['bg_image'] = $request->file('bg_image')->store('hero-sliders', 'public');
        }

        $heroSlider->update($validated);

        return redirect()->route('admin.hero-sliders.index')
                        ->with('success', 'Hero slider updated successfully.');
    }

    public function destroy(HeroSlider $heroSlider)
    {
        if ($heroSlider->bg_image && Storage::disk('public')->exists($heroSlider->bg_image)) {
            Storage::disk('public')->delete($heroSlider->bg_image);
        }

        $heroSlider->delete();

        return redirect()->route('admin.hero-sliders.index')
            ->with('success', 'Hero slider deleted successfully.');
    }
}
