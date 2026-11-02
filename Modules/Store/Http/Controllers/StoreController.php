<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;

class StoreController extends Controller
{
    /**
     * Display a tenant's public digital storefront.
     */
    public function tenantStore(string $slug, \Illuminate\Http\Request $request)
    {
        $tenant = \App\Models\Tenant::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $query = $tenant->products()->where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = $tenant->products()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('store::tenant_store', compact('tenant', 'products', 'categories'));
    }
}
