<?php

namespace Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $settings = [
            'support_email' => \Modules\Settings\Models\Setting::get('support_email'),
            'support_phone' => \Modules\Settings\Models\Setting::get('support_phone'),
            'support_whatsapp' => \Modules\Settings\Models\Setting::get('support_whatsapp'),
            'social_facebook' => \Modules\Settings\Models\Setting::get('social_facebook'),
            'social_instagram' => \Modules\Settings\Models\Setting::get('social_instagram'),
            'social_twitter' => \Modules\Settings\Models\Setting::get('social_twitter'),
            'social_linkedin' => \Modules\Settings\Models\Setting::get('social_linkedin'),
            'fcm_server_key' => \Modules\Settings\Models\Setting::get('fcm_server_key'),
        ];

        return view('settings::index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'support_email' => 'nullable|email',
            'support_phone' => 'nullable|string',
            'support_whatsapp' => 'nullable|string',
            'social_facebook' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'fcm_server_key' => 'nullable|string',
        ]);

        foreach ($data as $key => $value) {
            \Modules\Settings\Models\Setting::set($key, $value);
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
