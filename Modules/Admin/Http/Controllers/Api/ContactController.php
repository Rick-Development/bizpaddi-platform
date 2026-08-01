<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Communications\Models\Contact;

class ContactController extends ApiController
{
    public function index(Request $request)
    {
        $contacts = Contact::latest()->paginate(20);
        return $this->sendResponse($contacts, 'Contacts retrieved successfully.');
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        if (!$contact) return $this->sendError('Contact not found.', 404);
        return $this->sendResponse($contact, 'Contact details retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email'      => 'required|email|unique:contacts,email',
            'first_name' => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'company'    => 'nullable|string|max:255',
            'status'     => 'nullable|string|in:active,unsubscribed,bounced',
            'role'       => 'nullable|string|max:255',
            'metadata'   => 'nullable|array',
        ]);

        if (!isset($validated['status'])) {
            $validated['status'] = 'active';
        }
        
        $validated['subscribed_at'] = now();

        $contact = Contact::create($validated);
        return $this->sendResponse($contact, 'Contact created successfully.', 201);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        if (!$contact) return $this->sendError('Contact not found.', 404);

        $validated = $request->validate([
            'email'      => 'sometimes|email|unique:contacts,email,' . $id,
            'first_name' => 'sometimes|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'phone'      => 'nullable|string|max:20',
            'company'    => 'nullable|string|max:255',
            'status'     => 'nullable|string|in:active,unsubscribed,bounced',
            'role'       => 'nullable|string|max:255',
            'metadata'   => 'nullable|array',
        ]);

        $contact->update($validated);
        return $this->sendResponse($contact->fresh(), 'Contact updated successfully.');
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) return $this->sendError('Contact not found.', 404);
        $contact->delete();
        return $this->sendResponse([], 'Contact deleted successfully.');
    }
}
