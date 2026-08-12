<?php

namespace Modules\TeamPortal\Http\Controllers\BDM\Communications;

use App\Http\Controllers\Controller;
use Modules\Communications\Models\SmsTemplate;
use Illuminate\Http\Request;

class SmsTemplateController extends Controller
{
    public function index()
    {
        $templates = SmsTemplate::latest()->paginate(20);
        return view('teamportal::bdm.communications.sms-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('teamportal::bdm.communications.sms-templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        SmsTemplate::create($validated);

        return redirect()->route('team.bdm.sms-templates.index')
            ->with('success', 'SMS template created successfully.');
    }

    public function edit(SmsTemplate $smsTemplate)
    {
        return view('teamportal::bdm.communications.sms-templates.edit', compact('smsTemplate'));
    }

    public function update(Request $request, SmsTemplate $smsTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $smsTemplate->update($validated);

        return redirect()->route('team.bdm.sms-templates.index')
            ->with('success', 'SMS template updated successfully.');
    }

    public function destroy(SmsTemplate $smsTemplate)
    {
        $smsTemplate->delete();

        return redirect()->route('team.bdm.sms-templates.index')
            ->with('success', 'SMS template deleted successfully.');
    }
}
