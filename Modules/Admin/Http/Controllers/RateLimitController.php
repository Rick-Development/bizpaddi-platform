<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Models\RateLimit;

class RateLimitController extends Controller
{
    public function index()
    {
        $rateLimits = RateLimit::latest()->get();
        
        $stats = [
            'total' => $rateLimits->count(),
            'active' => $rateLimits->where('is_enabled', true)->count(),
            'disabled' => $rateLimits->where('is_enabled', false)->count(),
            'categories' => $rateLimits->groupBy('category')->map->count(),
        ];

        return view('admin::rate-limits.index', compact('rateLimits', 'stats'));
    }

    public function sync()
    {
        $routes = Route::getRoutes();
        $count = 0;

        foreach ($routes as $route) {
            $name = $route->getName();
            if (!$name) continue;

            $path = $route->uri();
            $category = $this->categorizeRoute($name, $path);

            $exists = RateLimit::where('route_name', $name)->exists();

            if (!$exists) {
                RateLimit::create([
                    'category' => $category,
                    'route_name' => $name,
                    'route_path' => $path,
                    'max_attempts' => 60,
                    'decay_minutes' => 1,
                    'is_enabled' => false,
                ]);
                $count++;
            }
        }

        return back()->with('success', "Successfully synced $count new routes.");
    }

    private function categorizeRoute($name, $path)
    {
        if (str_starts_with($name, 'admin.')) return 'Admin';
        if (str_starts_with($name, 'client.')) return 'Client Portal';
        if (str_starts_with($name, 'team.')) return 'Team Portal';
        if (str_starts_with($path, 'api/')) return 'API';
        
        return 'Frontend';
    }

    public function edit(RateLimit $rateLimit)
    {
        return view('admin::rate-limits.edit', compact('rateLimit'));
    }

    public function update(Request $request, RateLimit $rateLimit)
    {
        $validated = $request->validate([
            'max_attempts' => 'required|integer|min:1',
            'decay_minutes' => 'required|integer|min:1',
            'is_enabled' => 'nullable|boolean',
        ]);

        $validated['is_enabled'] = $request->has('is_enabled');

        $rateLimit->update($validated);

        $targetRoute = \Illuminate\Support\Facades\Route::has('super-admin.rate-limits.index') ? 'super-admin.rate-limits.index' : 'admin.rate-limits.index';
        return redirect()->route($targetRoute)
            ->with('success', 'Rate limit updated successfully.');
    }

    public function destroy(RateLimit $rateLimit)
    {
        $rateLimit->delete();
        return back()->with('success', 'Rate limit removed.');
    }
}
