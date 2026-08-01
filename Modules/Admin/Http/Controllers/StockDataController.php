<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Investor\Models\StockData;

class StockDataController extends Controller
{
    public function index()
    {
        $stockData = StockData::orderBy('date', 'desc')
            ->paginate(30);
            
        return view('admin::investor.stock-data.index', compact('stockData'));
    }

    public function create()
    {
        return view('admin::investor.stock-data.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:stock_data,date',
            'opening_price' => 'nullable|numeric|min:0',
            'closing_price' => 'required|numeric|min:0',
            'high_price' => 'nullable|numeric|min:0',
            'low_price' => 'nullable|numeric|min:0',
            'volume' => 'nullable|integer|min:0',
            'market_cap' => 'nullable|numeric|min:0',
        ]);

        StockData::create($validated);

        return redirect()->route('admin.investor.stock-data.index')
            ->with('success', 'Stock data created successfully.');
    }

    public function edit(StockData $stockDatum)
    {
        return view('admin::investor.stock-data.edit', compact('stockDatum'));
    }

    public function update(Request $request, StockData $stockDatum)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:stock_data,date,' . $stockDatum->id,
            'opening_price' => 'nullable|numeric|min:0',
            'closing_price' => 'required|numeric|min:0',
            'high_price' => 'nullable|numeric|min:0',
            'low_price' => 'nullable|numeric|min:0',
            'volume' => 'nullable|integer|min:0',
            'market_cap' => 'nullable|numeric|min:0',
        ]);

        $stockDatum->update($validated);

        return redirect()->route('admin.investor.stock-data.index')
            ->with('success', 'Stock data updated successfully.');
    }

    public function destroy(StockData $stockDatum)
    {
        $stockDatum->delete();
        
        return redirect()->route('admin.investor.stock-data.index')
            ->with('success', 'Stock data deleted successfully.');
    }
}
