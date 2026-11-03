<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PlatformFeatureRate;
use Illuminate\Http\Request;

class PlatformFeatureRateController extends Controller
{
    /**
     * Display all dynamic platform feature rates.
     */
    public function index()
    {
        // Ensure defaults exist if empty
        if (PlatformFeatureRate::count() === 0) {
            PlatformFeatureRate::seedDefaults();
        }

        $rates = PlatformFeatureRate::orderBy('category')->orderBy('name')->get();
        $categories = $rates->groupBy('category');

        $stats = [
            'total' => $rates->count(),
            'active' => $rates->where('is_active', true)->count(),
            'max_fee' => (float) $rates->max('fee'),
            'min_fee' => (float) $rates->where('fee', '>', 0)->min('fee') ?: 0.00,
        ];

        return view('superadmin::feature-rates.index', compact('rates', 'categories', 'stats'));
    }

    /**
     * Batch update feature rates.
     */
    public function updateAll(Request $request)
    {
        $data = $request->validate([
            'rates' => 'required|array',
            'rates.*.fee' => 'required|numeric|min:0',
            'rates.*.is_active' => 'nullable|boolean',
            'rates.*.description' => 'nullable|string|max:500',
        ]);

        foreach ($data['rates'] as $id => $payload) {
            $rate = PlatformFeatureRate::find($id);
            if ($rate) {
                $rate->update([
                    'fee' => $payload['fee'],
                    'is_active' => !empty($payload['is_active']),
                    'description' => $payload['description'] ?? $rate->description,
                ]);
            }
        }

        return redirect()->route('super-admin.feature-rates.index')
            ->with('success', 'Platform feature monetization rates updated successfully.');
    }

    /**
     * Add a new dynamic feature rate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'action_key' => 'required|string|unique:platform_feature_rates,action_key',
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'fee' => 'required|numeric|min:0',
            'unit_label' => 'required|string|max:50',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['currency'] = 'NGN';

        PlatformFeatureRate::create($validated);

        return redirect()->route('super-admin.feature-rates.index')
            ->with('success', "Feature rate '{$validated['name']}' created successfully.");
    }
}
