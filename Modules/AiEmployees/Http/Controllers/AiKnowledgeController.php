<?php

namespace Modules\AiEmployees\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\AiEmployees\Models\AiKnowledgeDocument;

class AiKnowledgeController extends Controller
{
    /**
     * Display a listing of knowledge documents.
     */
    public function index(Request $request)
    {
        $query = AiKnowledgeDocument::query();
        
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('category', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('aiemployees::admin.knowledge.index', compact('documents'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        return view('aiemployees::admin.knowledge.create');
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        AiKnowledgeDocument::create($validated);

        return redirect()->route('admin.aiemployees.knowledge.index')
            ->with('success', 'Training document added successfully. The AI employees will now search and utilize this knowledge during execution.');
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit($id)
    {
        $document = AiKnowledgeDocument::findOrFail($id);
        return view('aiemployees::admin.knowledge.edit', compact('document'));
    }

    /**
     * Update the specified document in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $document = AiKnowledgeDocument::findOrFail($id);
        $document->update($validated);

        return redirect()->route('admin.aiemployees.knowledge.index')
            ->with('success', 'Training document updated successfully.');
    }

    /**
     * Remove the specified document from storage.
     */
    public function destroy($id)
    {
        $document = AiKnowledgeDocument::findOrFail($id);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Training document deleted successfully.'
        ]);
    }
}
