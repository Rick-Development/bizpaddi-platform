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
            'og_image' => 'nullable|string',
        ]);

        $seo = SeoMeta::firstOrCreate(
            ['route_name' => 'global'],
            [
                'title' => 'Bizpaddi | All-in-One Business Operating System for African Commerce',
                'description' => 'Run your entire business on autopilot. Smart invoicing with automated payment reconciliation, Kanban project pipelines, staff payroll, omnichannel CRM, and dedicated digital storefronts.',
            ]
        );
        
        $seo->update($validated);

        if (class_exists(\Modules\Settings\Models\Setting::class)) {
            \Modules\Settings\Models\Setting::set('seo_meta_title', $validated['title']);
            \Modules\Settings\Models\Setting::set('seo_meta_description', $validated['description']);
            \Modules\Settings\Models\Setting::set('seo_meta_keywords', $validated['keywords'] ?? '');
            \Modules\Settings\Models\Setting::set('seo_og_image', $validated['og_image'] ?? '');
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('view:clear');
        } catch (\Throwable $e) {}

        return back()->with('success', 'Global SEO settings updated successfully.');
    }
}
