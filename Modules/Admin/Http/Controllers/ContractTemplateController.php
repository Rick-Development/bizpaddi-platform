<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contracts\Models\ContractTemplate;
use Illuminate\Http\Request;

class ContractTemplateController extends Controller
{
    public function index()
    {
        $templates = ContractTemplate::with('creator')->latest()->paginate(20);
        return view('admin::contracts.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin::contracts.templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contract_type' => 'required|in:sla,nda,employment,service_agreement,partnership,vendor,other',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'default_terms' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth('admin')->id();

        ContractTemplate::create($validated);

        return redirect()->route('admin.contracts.templates.index')
            ->with('success', 'Template created successfully.');
    }

    public function edit(ContractTemplate $template)
    {
        return view('admin::contracts.templates.edit', compact('template'));
    }

    public function update(Request $request, ContractTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contract_type' => 'required|in:sla,nda,employment,service_agreement,partnership,vendor,other',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'default_terms' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $template->update($validated);

        return redirect()->route('admin.contracts.templates.index')
            ->with('success', 'Template updated successfully.');
    }

    public function destroy(ContractTemplate $template)
    {
        $template->delete();

        return redirect()->route('admin.contracts.templates.index')
            ->with('success', 'Template deleted successfully.');
    }
}
