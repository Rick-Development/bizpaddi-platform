<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Settings\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin::settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Handle Logo Upload
        if ($request->hasFile('company_logo')) {
            $request->validate([
                'company_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Delete old logo if exists
            $oldLogo = Setting::get('company_logo');
            if ($oldLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('company_logo')->store('settings', 'public');
            Setting::set('company_logo', $path);
        }

        // Handle Watermark Upload
        if ($request->hasFile('watermark_logo')) {
            $request->validate([
                'watermark_logo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $oldWatermark = Setting::get('watermark_logo');
            if ($oldWatermark && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldWatermark)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldWatermark);
            }

            $path = $request->file('watermark_logo')->store('settings', 'public');
            Setting::set('watermark_logo', $path);
        }

        // Handle Signature Upload
        if ($request->hasFile('company_signature')) {
            $request->validate([
                'company_signature' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $oldSignature = Setting::get('company_signature');
            if ($oldSignature && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldSignature)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldSignature);
            }

            $path = $request->file('company_signature')->store('settings', 'public');
            Setting::set('company_signature', $path);
        }

        // Handle Favicon Upload
        if ($request->hasFile('favicon')) {
            $request->validate([
                'favicon' => 'image|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
            ]);

            $oldFavicon = Setting::get('favicon');
            if ($oldFavicon && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldFavicon)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldFavicon);
            }

            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', $path);
        }

        $data = $request->except(['_token', 'company_logo', 'watermark_logo', 'company_signature', 'favicon']);

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.index')
                        ->with('success', 'Settings updated successfully.');
    }
}
