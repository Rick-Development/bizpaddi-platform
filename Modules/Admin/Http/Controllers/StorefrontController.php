<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StoreProduct;
use App\Models\Tenant;
use App\Scopes\TenantScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    /**
     * Resolve active tenant business.
     */
    protected function getActiveTenant(): ?Tenant
    {
        $tenantId = TenantScope::getActiveTenantId();
        if ($tenantId) {
            return Tenant::find($tenantId);
        }

        if (auth('admin')->check()) {
            return auth('admin')->user()->tenant;
        }

        return null;
    }

    /**
     * Display product catalog for the tenant.
     */
    public function index(Request $request)
    {
        $tenant = $this->getActiveTenant();
        $query = StoreProduct::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'     => StoreProduct::count(),
            'active'    => StoreProduct::where('is_active', true)->count(),
            'out_stock' => StoreProduct::where('stock_quantity', '<=', 0)->count(),
        ];

        $categories = StoreProduct::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('admin::storefront.products.index', compact('products', 'stats', 'categories', 'tenant'));
    }

    /**
     * Show form to add a new product.
     */
    public function create()
    {
        $tenant = $this->getActiveTenant();
        return view('admin::storefront.products.create', compact('tenant'));
    }

    /**
     * Store new product in catalog.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
            'is_active'      => 'nullable|boolean',
        ], [
            'image.uploaded' => 'The image could not be uploaded. Please ensure the file size is under 10MB and in a supported image format.',
            'image.mimes'    => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp, avif.',
            'image.max'      => 'The image may not be greater than 10MB.',
        ]);

        $tenant = $this->getActiveTenant();
        if ($tenant) {
            $billingCheck = $tenant->chargeOrAuthorizeAction('product_create', null, "Add Product to Catalog: {$validated['name']}");
            if (!$billingCheck['allowed']) {
                return redirect()->route('admin.billing.index')->with('error', $billingCheck['message']);
            }
        }

        $data = [
            'name'           => $validated['name'],
            'slug'           => Str::slug($validated['name']) . '-' . rand(100, 999),
            'category'       => $validated['category'] ?? 'General',
            'price'          => $validated['price'],
            'sale_price'     => $validated['sale_price'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'description'    => $validated['description'] ?? null,
            'is_active'      => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('store/products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product = StoreProduct::create($data);

        return redirect()
            ->route('admin.storefront.products.index')
            ->with('success', "Product '{$product->name}' added to your digital storefront!");
    }

    /**
     * Show form to edit product.
     */
    public function edit(StoreProduct $product)
    {
        $tenant = $this->getActiveTenant();
        return view('admin::storefront.products.edit', compact('product', 'tenant'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, StoreProduct $product)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'price'          => 'required|numeric|min:0',
            'sale_price'     => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description'    => 'nullable|string',
            'image'          => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:10240',
            'is_active'      => 'nullable|boolean',
        ], [
            'image.uploaded' => 'The image could not be uploaded. Please ensure the file size is under 10MB and in a supported image format.',
            'image.mimes'    => 'The image must be a file of type: jpeg, png, jpg, gif, svg, webp, avif.',
            'image.max'      => 'The image may not be greater than 10MB.',
        ]);

        $tenant = $this->getActiveTenant();
        if ($tenant) {
            $billingCheck = $tenant->chargeOrAuthorizeAction('product_create', null, "Add Product to Catalog: {$validated['name']}");
            if (!$billingCheck['allowed']) {
                return redirect()->route('admin.billing.index')->with('error', $billingCheck['message']);
            }
        }

        $data = [
            'name'           => $validated['name'],
            'category'       => $validated['category'] ?? 'General',
            'price'          => $validated['price'],
            'sale_price'     => $validated['sale_price'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'description'    => $validated['description'] ?? null,
            'is_active'      => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('store/products', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $product->update($data);

        return redirect()
            ->route('admin.storefront.products.index')
            ->with('success', "Product '{$product->name}' updated successfully.");
    }

    /**
     * Delete product.
     */
    public function destroy(StoreProduct $product)
    {
        $name = $product->name;
        $product->delete();

        return redirect()
            ->route('admin.storefront.products.index')
            ->with('success', "Product '{$name}' removed from storefront.");
    }

    /**
     * Storefront branding & domain settings.
     */
    public function settings()
    {
        $tenant = $this->getActiveTenant();
        $hasCustomDomainPlan = $tenant ? $tenant->hasCustomDomainAccess() : false;

        return view('admin::storefront.settings', compact('tenant', 'hasCustomDomainPlan'));
    }

    /**
     * Save Storefront settings.
     */
    public function updateSettings(Request $request)
    {
        $tenant = $this->getActiveTenant();
        if (!$tenant) {
            return back()->withErrors(['error' => 'Tenant not found.']);
        }

        $validated = $request->validate([
            'store_name'        => 'required|string|max:120',
            'store_tagline'     => 'nullable|string|max:255',
            'store_description' => 'nullable|string|max:1000',
            'store_phone'       => 'nullable|string|max:30',
            'store_email'       => 'nullable|email|max:120',
            'currency'          => 'required|string|size:3',
            'custom_domain'     => 'nullable|string|max:120',
            'store_banner'      => 'nullable|image|max:5120',
        ]);

        $settings = $tenant->settings ?? [];
        $settings['store_name'] = $validated['store_name'];
        $settings['store_tagline'] = $validated['store_tagline'] ?? '';
        $settings['store_description'] = $validated['store_description'] ?? '';
        $settings['store_phone'] = $validated['store_phone'] ?? '';
        $settings['store_email'] = $validated['store_email'] ?? '';

        if ($request->hasFile('store_banner')) {
            $path = $request->file('store_banner')->store('store/banners', 'public');
            $settings['store_banner'] = '/storage/' . $path;
        }

        $tenant->currency = strtoupper($validated['currency']);
        $tenant->settings = $settings;

        // Domain update gated by plan
        if ($tenant->hasCustomDomainAccess() && $request->filled('custom_domain')) {
            $domain = strtolower(trim(preg_replace('#^https?://#', '', $request->custom_domain)));
            $tenant->custom_domain = rtrim($domain, '/');
        }

        $tenant->save();

        return redirect()
            ->route('admin.storefront.settings')
            ->with('success', 'Storefront settings updated successfully.');
    }
}
