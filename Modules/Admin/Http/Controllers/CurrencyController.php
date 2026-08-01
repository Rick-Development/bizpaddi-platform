<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Accounting\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::latest()->get();
        return view('admin::accounting.currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('admin::accounting.currencies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|uppercase|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
        ]);

        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        if ($validated['is_default']) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        Currency::create($validated);

        return redirect()->route('admin.accounting.currencies.index')
            ->with('success', 'Currency created successfully.');
    }

    public function edit(Currency $currency)
    {
        return view('admin::accounting.currencies.edit', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|uppercase|unique:currencies,code,' . $currency->id,
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0',
        ]);

        $validated['is_default'] = $request->has('is_default');
        $validated['is_active'] = $request->has('is_active');

        if ($validated['is_default'] && !$currency->is_default) {
            Currency::where('is_default', true)->update(['is_default' => false]);
        }

        $currency->update($validated);

        return redirect()->route('admin.accounting.currencies.index')
            ->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        if ($currency->is_default) {
            return back()->with('error', 'Cannot delete the default currency.');
        }

        $currency->delete();
        return redirect()->route('admin.accounting.currencies.index')
            ->with('success', 'Currency deleted successfully.');
    }
}
