<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = $this->fetchProducts();
        return view('store::index', compact('products'));
    }

    /**
     * Fetch products from the external API with caching.
     */
    private function fetchProducts($limit = 4)
    {
        return Cache::remember('store_products_' . $limit, 3600, function () use ($limit) {
            $apiKey = env('STORE_API_KEY');
            $apiUrl = 'https://store.rickdevelopment.com/api/items/all';

            try {
                $response = Http::get($apiUrl, [
                    'api_key' => $apiKey,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (($data['status'] ?? '') === 'success') {
                        // Sort by published_at descending and take the limit
                        return collect($data['items'] ?? [])
                            ->sortByDesc('published_at')
                            ->take($limit)
                            ->values();
                    }
                }
                
                return collect([]);
            } catch (\Exception $e) {
                return collect([]);
            }
        });
    }
}
