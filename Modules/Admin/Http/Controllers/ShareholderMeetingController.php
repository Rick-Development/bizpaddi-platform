<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Investor\Models\ShareholderMeeting;

class ShareholderMeetingController extends Controller
{
    public function index()
    {
        $meetings = ShareholderMeeting::orderBy('meeting_date', 'desc')
            ->paginate(15);
            
        return view('admin::investor.shareholder-meetings.index', compact('meetings'));
    }

    public function create()
    {
        return view('admin::investor.shareholder-meetings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_type' => 'required|in:agm,egm,special',
            'meeting_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'agenda' => 'nullable|string',
            'is_upcoming' => 'boolean',
            'agenda_file' => 'nullable|file|mimes:pdf|max:5120',
            'minutes_file' => 'nullable|file|mimes:pdf|max:5120',
            'presentation_file' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
        ]);

        $meeting = ShareholderMeeting::create($validated);

        if ($request->hasFile('agenda_file')) {
            $meeting->addMediaFromRequest('agenda_file')->toMediaCollection('agenda_file');
        }

        if ($request->hasFile('minutes_file')) {
            $meeting->addMediaFromRequest('minutes_file')->toMediaCollection('minutes_file');
        }

        if ($request->hasFile('presentation_file')) {
            $meeting->addMediaFromRequest('presentation_file')->toMediaCollection('presentation_file');
        }

        return redirect()->route('admin.investor.shareholder-meetings.index')
            ->with('success', 'Shareholder meeting created successfully.');
    }

    public function edit(ShareholderMeeting $shareholderMeeting)
    {
        return view('admin::investor.shareholder-meetings.edit', compact('shareholderMeeting'));
    }

    public function update(Request $request, ShareholderMeeting $shareholderMeeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'meeting_type' => 'required|in:agm,egm,special',
            'meeting_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'agenda' => 'nullable|string',
            'is_upcoming' => 'boolean',
            'agenda_file' => 'nullable|file|mimes:pdf|max:5120',
            'minutes_file' => 'nullable|file|mimes:pdf|max:5120',
            'presentation_file' => 'nullable|file|mimes:pdf,ppt,pptx|max:10240',
        ]);

        $shareholderMeeting->update($validated);

        if ($request->hasFile('agenda_file')) {
            $shareholderMeeting->clearMediaCollection('agenda_file');
            $shareholderMeeting->addMediaFromRequest('agenda_file')->toMediaCollection('agenda_file');
        }

        if ($request->hasFile('minutes_file')) {
            $shareholderMeeting->clearMediaCollection('minutes_file');
            $shareholderMeeting->addMediaFromRequest('minutes_file')->toMediaCollection('minutes_file');
        }

        if ($request->hasFile('presentation_file')) {
            $shareholderMeeting->clearMediaCollection('presentation_file');
            $shareholderMeeting->addMediaFromRequest('presentation_file')->toMediaCollection('presentation_file');
        }

        return redirect()->route('admin.investor.shareholder-meetings.index')
            ->with('success', 'Shareholder meeting updated successfully.');
    }

    public function destroy(ShareholderMeeting $shareholderMeeting)
    {
        $shareholderMeeting->delete();
        
        return redirect()->route('admin.investor.shareholder-meetings.index')
            ->with('success', 'Shareholder meeting deleted successfully.');
    }
}
