<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\Campaign;
use Modules\Communications\Models\ContactList;
use Modules\Communications\Models\EmailTemplate;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $campaigns = Campaign::with(['contactList', 'template'])
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%");
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(20);

        return view('admin::campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $contactLists = ContactList::where('is_active', true)->get();
        $templates = EmailTemplate::where('is_active', true)->get();
        
        return view('admin::campaigns.create', compact('contactLists', 'templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'template_id' => 'nullable|exists:email_templates,id',
            'contact_list_id' => 'nullable|exists:contact_lists,id',
            'content' => 'required|string',
            'from_name' => 'required|string|max:100',
            'from_email' => 'required|email',
            'reply_to' => 'nullable|email',
            'scheduled_at' => 'nullable|date|after:now',
            'status' => 'required|in:draft,scheduled',
        ]);

        $campaign = Campaign::create($validated);

        return redirect()->route('admin.campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully.');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['contactList', 'template', 'logs']);
        
        return view('admin::campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $contactLists = ContactList::where('is_active', true)->get();
        $templates = EmailTemplate::where('is_active', true)->get();
        
        return view('admin::campaigns.edit', compact('campaign', 'contactLists', 'templates'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'template_id' => 'nullable|exists:email_templates,id',
            'contact_list_id' => 'nullable|exists:contact_lists,id',
            'content' => 'required|string',
            'from_name' => 'required|string|max:100',
            'from_email' => 'required|email',
            'reply_to' => 'nullable|email',
            'scheduled_at' => 'nullable|date|after:now',
            'status' => 'required|in:draft,scheduled,paused',
        ]);

        $campaign->update($validated);

        return redirect()->route('admin.campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        if (in_array($campaign->status, ['sending', 'sent'])) {
            return back()->with('error', 'Cannot delete a campaign that has been sent or is sending.');
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }

    public function send(Campaign $campaign)
    {
        if ($campaign->status !== 'draft' && $campaign->status !== 'scheduled') {
            return back()->with('error', 'Campaign cannot be sent in its current status.');
        }

        // TODO: Implement actual email sending logic
        $campaign->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);

        return back()->with('success', 'Campaign sent successfully!');
    }
}
