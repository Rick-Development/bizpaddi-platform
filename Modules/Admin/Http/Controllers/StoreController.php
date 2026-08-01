<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreController extends Controller
{
    public function index()
    {
        $apiKey = env('STORE_API_KEY');
        $apiUrl = 'https://store.rickdevelopment.com/api/items/all';

        try {
            $response = Http::get($apiUrl, [
                'api_key' => $apiKey,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (($data['status'] ?? '') === 'success') {
                    $items = collect($data['items'] ?? [])->take(12); // Limit to 12 items
                    return view('admin::store.index', compact('items'));
                }
            }
            
            // Handle API error or empty response
            $items = collect([]);
            return view('admin::store.index', compact('items'))->withErrors(['api' => 'Failed to fetch products from the store.']);

        } catch (\Exception $e) {
            $items = collect([]);
            return view('admin::store.index', compact('items'))->withErrors(['api' => 'An error occurred while connecting to the store.']);
        }
    }
}
