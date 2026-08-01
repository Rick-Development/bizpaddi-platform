<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Service;
use Modules\Accounting\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['category', 'currency'])->latest()->paginate(20);
        return view('admin::accounting.services.index', compact('services'));
    }

    public function create()
    {
        $categories = ServiceCategory::orderBy('name')->get();
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

        Service::create($validated);

        return redirect()->route('admin.accounting.services.index')
                        ->with('success', 'Service created successfully.');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::orderBy('name')->get();
        $currencies = \Modules\Accounting\Models\Currency::where('is_active', true)->orderBy('code')->get();
        return view('admin::accounting.services.edit', compact('service', 'categories', 'currencies'));
    }

    public function update(Request $request, Service $service)
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

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);

        $service->update($validated);

        return redirect()->route('admin.accounting.services.index')
                        ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.accounting.services.index')
                        ->with('success', 'Service deleted successfully.');
    }
}
