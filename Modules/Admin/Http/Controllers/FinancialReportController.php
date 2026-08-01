<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Investor\Models\FinancialReport;

class FinancialReportController extends Controller
{
    public function index()
    {
        $reports = FinancialReport::orderBy('fiscal_year', 'desc')
            ->orderBy('fiscal_quarter', 'desc')
            ->paginate(15);
            
        return view('admin::investor.financial-reports.index', compact('reports'));
    }

    public function create()
    {
        return view('admin::investor.financial-reports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:quarterly,annual,special',
            'fiscal_year' => 'required|integer|min:2000|max:2100',
            'fiscal_quarter' => 'nullable|integer|min:1|max:4',
            'report_date' => 'required|date',
            'summary' => 'nullable|string',
            'is_published' => 'boolean',
            'report_file' => 'nullable|file|mimes:pdf,xlsx,xls|max:10240',
        ]);

        if ($request->boolean('is_published') && !$request->has('published_at')) {
            $validated['published_at'] = now();
        }

        $report = FinancialReport::create($validated);

        if ($request->hasFile('report_file')) {
            $report->addMediaFromRequest('report_file')->toMediaCollection('report_file');
        }

        return redirect()->route('admin.investor.financial-reports.index')
            ->with('success', 'Financial report created successfully.');
    }

    public function edit(FinancialReport $financialReport)
    {
        return view('admin::investor.financial-reports.edit', compact('financialReport'));
    }

    public function update(Request $request, FinancialReport $financialReport)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'report_type' => 'required|in:quarterly,annual,special',
            'fiscal_year' => 'required|integer|min:2000|max:2100',
            'fiscal_quarter' => 'nullable|integer|min:1|max:4',
            'report_date' => 'required|date',
            'summary' => 'nullable|string',
            'is_published' => 'boolean',
            'report_file' => 'nullable|file|mimes:pdf,xlsx,xls|max:10240',
        ]);

        if ($request->boolean('is_published') && !$financialReport->published_at) {
            $validated['published_at'] = now();
        } elseif (!$request->boolean('is_published')) {
            $validated['published_at'] = null;
        }

        $financialReport->update($validated);

        if ($request->hasFile('report_file')) {
            $financialReport->clearMediaCollection('report_file');
            $financialReport->addMediaFromRequest('report_file')->toMediaCollection('report_file');
        }

        return redirect()->route('admin.investor.financial-reports.index')
            ->with('success', 'Financial report updated successfully.');
    }

    public function destroy(FinancialReport $financialReport)
    {
        $financialReport->delete();
        
        return redirect()->route('admin.investor.financial-reports.index')
            ->with('success', 'Financial report deleted successfully.');
    }
}
