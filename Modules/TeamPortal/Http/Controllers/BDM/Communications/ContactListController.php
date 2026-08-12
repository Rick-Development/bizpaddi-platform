<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Communications;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\ContactList;
use Modules\Communications\Models\Contact;
use Illuminate\Http\Request;

class ContactListController extends Controller
{
    public function index(Request $request)
    {
        $lists = ContactList::withCount('contacts')
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(25);

        return view('teamportal::bdm.communications.contact-lists.index', compact('lists'));
    }

    public function create()
    {
        return view('teamportal::bdm.communications.contact-lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $list = ContactList::create($validated);

        return redirect()->route('team.bdm.contact-lists.show', $list)
            ->with('success', 'Contact list created successfully.');
    }

    public function show(ContactList $contactList)
    {
        $contactList->load('contacts');
        $contacts = $contactList->contacts()->paginate(25);
        
        return view('teamportal::bdm.communications.contact-lists.show', compact('contactList', 'contacts'));
    }

    public function edit(ContactList $contactList)
    {
        return view('teamportal::bdm.communications.contact-lists.edit', compact('contactList'));
    }

    public function update(Request $request, ContactList $contactList)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $contactList->update($validated);

        return redirect()->route('team.bdm.contact-lists.show', $contactList)
            ->with('success', 'Contact list updated successfully.');
    }

    public function destroy(ContactList $contactList)
    {
        $contactList->delete();

        return redirect()->route('team.bdm.contact-lists.index')
            ->with('success', 'Contact list deleted successfully.');
    }

    public function addContacts(Request $request, ContactList $contactList)
    {
        $validated = $request->validate([
            'contact_ids' => 'required|array',
            'contact_ids.*' => 'exists:contacts,id',
        ]);

        $contactList->contacts()->syncWithoutDetaching($validated['contact_ids']);
        $contactList->updateContactCount();

        return back()->with('success', 'Contacts added successfully.');
    }

    public function removeContact(ContactList $contactList, Contact $contact)
    {
        $contactList->contacts()->detach($contact->id);
        $contactList->updateContactCount();

        return back()->with('success', 'Contact removed from list.');
    }
}
