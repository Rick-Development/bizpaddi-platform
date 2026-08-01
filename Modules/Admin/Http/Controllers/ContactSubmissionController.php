<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contact\Models\ContactSubmission;
use Illuminate\Http\Request;

class ContactSubmissionController extends Controller
{
    public function index()
    {
        $submissions = ContactSubmission::latest()->paginate(25);
        return view('admin::contact-submissions.index', compact('submissions'));
    }

    public function show(ContactSubmission $contactSubmission)
    {
        if (!$contactSubmission->is_read) {
            $contactSubmission->update(['is_read' => true]);
        }
        return view('admin::contact-submissions.show', compact('contactSubmission'));
    }

    public function edit(ContactSubmission $contactSubmission)
    {
        return view('admin::contact-submissions.edit', compact('contactSubmission'));
    }

    public function update(Request $request, ContactSubmission $contactSubmission)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:new,in_progress,closed',
            'notes' => 'nullable|string',
        ]);

        $contactSubmission->update($validated);

        return redirect()->route('admin.contact-submissions.index')
                        ->with('success', 'Inquiry updated successfully.');
    }

    public function destroy(ContactSubmission $contactSubmission)
    {
        $contactSubmission->delete();

        return redirect()->route('admin.contact-submissions.index')
                        ->with('success', 'Inquiry deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contact_submissions,id',
        ]);

        ContactSubmission::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.contact-submissions.index')
                        ->with('success', 'Selected inquiries deleted successfully.');
    }
}
