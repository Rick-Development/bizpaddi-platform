<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ProjectManagement\Models\KPI;

class KPIController extends Controller
{
    public function index()
    {
        $kpis = KPI::latest()->paginate(15);
        return view('admin::kpis.index', compact('kpis'));
    }

    public function create()
    {
        return view('admin::kpis.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:pm_kpis,key',
            'target' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        KPI::create($validated);

        return redirect()->route('admin.kpis.index')
            ->with('success', 'KPI created successfully.');
    }

    public function edit(KPI $kpi)
    {
        return view('admin::kpis.edit', compact('kpi'));
    }

    public function update(Request $request, KPI $kpi)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:pm_kpis,key,' . $kpi->id,
            'target' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $kpi->update($validated);

        return redirect()->route('admin.kpis.index')
            ->with('success', 'KPI updated successfully.');
    }

    public function destroy(KPI $kpi)
    {
        $kpi->delete();

        return redirect()->route('admin.kpis.index')
            ->with('success', 'KPI deleted successfully.');
    }
}
