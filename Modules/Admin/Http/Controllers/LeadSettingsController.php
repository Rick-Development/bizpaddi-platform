<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Contact\Models\ProjectType;
use Modules\Contact\Models\BudgetRange;
use Modules\Contact\Models\ProjectTimeline;

class LeadSettingsController extends Controller
{
    public function index()
    {
        $projectTypes = ProjectType::orderBy('sort_order')->get();
        $budgets = BudgetRange::orderBy('sort_order')->get();
        $timelines = ProjectTimeline::orderBy('sort_order')->get();

        return view('admin::leads.settings', compact('projectTypes', 'budgets', 'timelines'));
    }

    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        ProjectType::create([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Project Type added successfully.');
    }

    public function updateType(Request $request, ProjectType $type)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $type->update([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Project Type updated successfully.');
    }

    public function destroyType(ProjectType $type)
    {
        $type->delete();
        return back()->with('success', 'Project Type deleted successfully.');
    }

    public function storeBudget(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        BudgetRange::create([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Budget Range added successfully.');
    }

    public function updateBudget(Request $request, BudgetRange $budget)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $budget->update([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Budget Range updated successfully.');
    }

    public function destroyBudget(BudgetRange $budget)
    {
        $budget->delete();
        return back()->with('success', 'Budget Range deleted successfully.');
    }

    public function storeTimeline(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        ProjectTimeline::create([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Timeline added successfully.');
    }

    public function updateTimeline(Request $request, ProjectTimeline $timeline)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean'
        ]);

        $timeline->update([
            'name' => $validated['name'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active')
        ]);

        return back()->with('success', 'Timeline updated successfully.');
    }

    public function destroyTimeline(ProjectTimeline $timeline)
    {
        $timeline->delete();
        return back()->with('success', 'Timeline deleted successfully.');
    }
}
