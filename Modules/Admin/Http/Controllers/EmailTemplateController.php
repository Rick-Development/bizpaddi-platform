<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index(Request $request)
    {
        $templates = EmailTemplate::query()
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('category', 'like', "%{$search}%");
            })
            ->when($request->category, function($query, $category) {
                $query->where('category', $category);
            })
            ->latest()
            ->paginate(20);

        $categories = EmailTemplate::distinct()->pluck('category')->filter();

        return view('admin::email-templates.index', compact('templates', 'categories'));
    }

    public function create()
    {
        return view('admin::email-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $template = EmailTemplate::create($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template created successfully.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin::email-templates.edit', compact('emailTemplate'));
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'category' => 'nullable|string|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $emailTemplate->update($validated);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template updated successfully.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Email template deleted successfully.');
    }
}
