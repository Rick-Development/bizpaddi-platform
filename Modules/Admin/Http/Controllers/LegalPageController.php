<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Legal\Models\LegalPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LegalPageController extends Controller
{
    public function index()
    {
        $pages = LegalPage::latest()->get();
        return view('admin::legal-pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin::legal-pages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'effective_date' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['version'] = 1;

        LegalPage::create($validated);

        return redirect()->route('admin.legal-pages.index')
                        ->with('success', 'Legal page created successfully.');
    }

    public function edit(LegalPage $legalPage)
    {
        return view('admin::legal-pages.edit', compact('legalPage'));
    }

    public function update(Request $request, LegalPage $legalPage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'effective_date' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Increment version if content changed
        if ($legalPage->content !== $validated['content']) {
            $validated['version'] = $legalPage->version + 1;
        }

        $legalPage->update($validated);

        return redirect()->route('admin.legal-pages.index')
                        ->with('success', 'Legal page updated successfully.');
    }

    public function destroy(LegalPage $legalPage)
    {
        $legalPage->delete();

        return redirect()->route('admin.legal-pages.index')
                        ->with('success', 'Legal page deleted successfully.');
    }
}
