<?php

namespace Modules\Home\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch hero sliders
        $heroSliders = \Modules\Home\Models\HeroSlider::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        // Fetch service categories for featured section
        // Replaces the old FeaturedContent model
        $serviceCategories = \Modules\Accounting\Models\ServiceCategory::orderBy('sort_order')
            ->get();
        
        // Fetch services for services preview section
        $services = \Modules\Services\Models\Service::where('is_active', true)
            ->orderBy('order')
            ->limit(4)
            ->get();
        
        // Fetch recent projects for portfolio preview
        // Note: projects table doesn't have is_active column
        $recentProjects = \Modules\Portfolio\Models\Project::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        // Fetch latest blog posts
        $latestPosts = \Modules\Blog\Models\Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        
        // Fetch testimonials (handle if model doesn't exist)
        $testimonials = collect([]);
        try {
            if (class_exists('\Modules\Clients\Models\Testimonial')) {
                $testimonials = \Modules\Clients\Models\Testimonial::where('is_featured', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            }
        } catch (\Exception $e) {
            // Testimonial model doesn't exist or has issues, use empty collection
            $testimonials = collect([]);
        }
        
        // Fetch store products (4 most recently updated)
        $storeProducts = \Illuminate\Support\Facades\Cache::remember('home_store_products', 3600, function () {
            $apiKey = env('STORE_API_KEY');
            $apiUrl = 'https://store.rickdevelopment.com/api/items/all';

            try {
                $response = \Illuminate\Support\Facades\Http::get($apiUrl, [
                    'api_key' => $apiKey,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (($data['status'] ?? '') === 'success') {
                        return collect($data['items'] ?? [])
                            ->sortByDesc('published_at')
                            ->take(4)
                            ->values();
                    }
                }
                return collect([]);
            } catch (\Exception $e) {
                return collect([]);
            }
        });
        
        // Fetch home stats
        $homeStats = \Modules\Home\Models\HomeStat::orderBy('sort_order')->get();

        // Fetch home features
        $homeFeatures = \Modules\Home\Models\HomeFeature::orderBy('sort_order')->get();

        // Fetch home processes
        $homeProcesses = \Modules\Home\Models\HomeProcess::orderBy('step_number')->get();

        // Fetch home CTA
        $homeCta = \Modules\Home\Models\HomeCta::where('is_active', true)->first();
        
        return view('home::index', compact(
            'heroSliders',
            'serviceCategories',
            'services',
            'recentProjects',
            'latestPosts',
            'testimonials',
            'storeProducts',
            'homeStats',
            'homeFeatures',
            'homeProcesses',
            'homeCta'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('home::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('home::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('home::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
