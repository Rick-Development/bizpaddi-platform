<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Clients\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin::testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin::testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:1024',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        $testimonial = Testimonial::create($validated);

        if ($request->hasFile('avatar')) {
            $testimonial->addMediaFromRequest('avatar')
                       ->toMediaCollection('avatar');
        }

        return redirect()->route('admin.testimonials.index')
                        ->with('success', 'Testimonial created successfully.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin::testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'author_name' => 'required|string|max:255',
            'author_position' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'avatar' => 'nullable|image|max:1024',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        $testimonial->update($validated);

        if ($request->hasFile('avatar')) {
            $testimonial->clearMediaCollection('avatar');
            $testimonial->addMediaFromRequest('avatar')
                       ->toMediaCollection('avatar');
        }

        return redirect()->route('admin.testimonials.index')
                        ->with('success', 'Testimonial updated successfully.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')
                        ->with('success', 'Testimonial deleted successfully.');
    }
}
