<?php

namespace Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projectTypes = \Modules\Contact\Models\ProjectType::where('is_active', true)->orderBy('sort_order')->get();
        $budgets = \Modules\Contact\Models\BudgetRange::where('is_active', true)->orderBy('sort_order')->get();
        $timelines = \Modules\Contact\Models\ProjectTimeline::where('is_active', true)->orderBy('sort_order')->get();

        return view('contact::index', compact('projectTypes', 'budgets', 'timelines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contact::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'project_type' => 'nullable|string',
            'budget' => 'nullable|string',
            'timeline' => 'nullable|string',
        ]);

        $notes = [];
        if (!empty($validated['project_type'])) $notes[] = 'Project Type: ' . $validated['project_type'];
        if (!empty($validated['budget'])) $notes[] = 'Budget: ' . $validated['budget'];
        if (!empty($validated['timeline'])) $notes[] = 'Timeline: ' . $validated['timeline'];
        
        $submissionData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ];

        if (!empty($notes)) {
            $submissionData['notes'] = implode("\n", $notes);
        }

        \Modules\Contact\Models\ContactSubmission::create($submissionData);

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('contact::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('contact::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
