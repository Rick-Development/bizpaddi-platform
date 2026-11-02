<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Service;
use Modules\Accounting\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    protected function tenantId(): ?int
    {
        $admin = auth('admin')->user();
        return $admin && !empty($admin->tenant_id) ? (int) $admin->tenant_id : null;
    }

    protected function categories()
    {
        $tenantId = $this->tenantId();
        return ServiceCategory::query()
            ->where(function ($q) use ($tenantId) {
                $q->whereNull('tenant_id');
                if ($tenantId) {
                    $q->orWhere('tenant_id', $tenantId);
                }
            })
            ->orderBy('name')->get();
    }

    protected function ownedService(Service $service): Service
    {
        $tenantId = $this->tenantId();
        abort_if($tenantId && (int) $service->tenant_id !== $tenantId, 404);
        return $service;
    }

    public function index()
    {
        $query = Service::with(['category', 'currency'])->latest();
        if ($tenantId = $this->tenantId()) {
            $query->where('services.tenant_id', $tenantId);
        }
        $services = $query->paginate(20);
        return view('admin::accounting.services.index', compact('services'));
    }

    public function create()
    {
        $categories = $this->categories();
        $currencies = \Modules\Accounting\Models\Currency::where('is_active', true)->orderBy('code')->get();
        return view('admin::accounting.services.create', compact('categories', 'currencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:hour,day,project,item,month',
            'is_active' => 'boolean',
        ]);

        // Checkbox handling
        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        $validated['tenant_id'] = $this->tenantId();

        Service::create($validated);

        return redirect()->route('admin.accounting.services.index')
                        ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $this->ownedService($service);
        $categories = $this->categories();
        $currencies = \Modules\Accounting\Models\Currency::where('is_active', true)->orderBy('code')->get();
        return view('admin::accounting.services.edit', compact('service', 'categories', 'currencies'));
    }

    public function update(Request $request, Service $service)
    {
        $this->ownedService($service);
        $validated = $request->validate([
            'category_id' => 'required|exists:service_categories,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'unit' => 'required|string|in:hour,day,project,item,month',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $service->update($validated);

        return redirect()->route('admin.accounting.services.index')
                        ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $this->ownedService($service);
        $service->delete();

        return redirect()->route('admin.accounting.services.index')
                        ->with('success', 'Service deleted successfully.');
    }
}
