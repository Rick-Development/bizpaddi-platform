<?php

namespace Modules\AI\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Modules\AI\Models\AISetting;
use Illuminate\Http\Request;

class AISettingController extends Controller
{
    /**
     * Display AI settings.
     */
    public function index()
    {
        $settings = [
            'active_provider' => AISetting::getSetting('active_provider'),
            'system_prompt' => AISetting::getSetting('system_prompt'),
            'openai_key' => AISetting::getSetting('api_key', 'openai'),
            'gemini_key' => AISetting::getSetting('api_key', 'gemini'),
            'anthropic_key' => AISetting::getSetting('api_key', 'anthropic'),
        ];

        return view('ai::admin.settings.index', compact('settings'));
    }

    /**
     * Update AI settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'active_provider' => 'required|in:openai,gemini,anthropic',
            'system_prompt' => 'nullable|string',
            'openai_key' => 'nullable|string',
            'gemini_key' => 'nullable|string',
            'anthropic_key' => 'nullable|string',
        ]);

        // Save General Settings
        AISetting::setSetting('active_provider', $request->active_provider);
        AISetting::setSetting('system_prompt', $request->system_prompt);

        // Save Provider Keys
        if ($request->filled('openai_key')) {
            AISetting::setSetting('api_key', $request->openai_key, 'openai', 'string', true);
        }
        if ($request->filled('gemini_key')) {
            AISetting::setSetting('api_key', $request->gemini_key, 'gemini', 'string', true);
        }
        if ($request->filled('anthropic_key')) {
            AISetting::setSetting('api_key', $request->anthropic_key, 'anthropic', 'string', true);
        }

        return back()->with('success', 'AI Settings updated successfully.');
    }
}
