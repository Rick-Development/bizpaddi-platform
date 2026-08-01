<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\SEO\Models\SeoMeta;

class SeoController extends Controller
{
    public function index()
    {
        // Fetch global settings (where route_name is 'global')
        $seo = SeoMeta::firstOrCreate(
            ['route_name' => 'global'],
            [
                'title' => config('app.name'),
                'description' => 'Default description',
            ]
        );

        return view('admin::seo.index', compact('seo'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'keywords' => 'nullable|string',
            'og_image' => 'nullable|url',
        ]);

        $seo = SeoMeta::where('route_name', 'global')->firstOrFail();
        
        $seo->update($validated);

        return back()->with('success', 'Global SEO settings updated successfully.');
    }
}
