<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\FeaturePrice;
use Illuminate\Http\Request;

class FeaturePricingController extends Controller
{
    public function index()
    {
        $features = FeaturePrice::latest()->paginate(20);
        return view('admin::accounting.feature-pricing.index', compact('features'));
    }

    public function create()
    {
        return view('admin::accounting.feature-pricing.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string',
            'feature_code' => 'required|string|unique:feature_prices,feature_code',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'billing_type' => 'required|in:one_time,monthly,yearly',
            'is_active' => 'boolean',
            'parameters' => 'nullable|json',
        ]);

        FeaturePrice::create($validated);

        return redirect()->route('admin.accounting.feature-pricing.index')
            ->with('success', 'Feature pricing created successfully.');
    }

    public function show(FeaturePrice $featurePricing)
    {
        return view('admin::accounting.feature-pricing.show', compact('featurePricing'));
    }

    public function edit(FeaturePrice $featurePricing)
    {
        return view('admin::accounting.feature-pricing.edit', compact('featurePricing'));
    }

    public function update(Request $request, FeaturePrice $featurePricing)
    {
        $validated = $request->validate([
            'feature_name' => 'required|string',
            'feature_code' => 'required|string|unique:feature_prices,feature_code,' . $featurePricing->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'billing_type' => 'required|in:one_time,monthly,yearly',
            'is_active' => 'boolean',
            'parameters' => 'nullable|json',
        ]);

        $featurePricing->update($validated);

        return redirect()->route('admin.accounting.feature-pricing.index')
            ->with('success', 'Feature pricing updated successfully.');
    }

    public function destroy(FeaturePrice $featurePricing)
    {
        $featurePricing->delete();
        return redirect()->route('admin.accounting.feature-pricing.index')
            ->with('success', 'Feature pricing deleted successfully.');
    }
}
