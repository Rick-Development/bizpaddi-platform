<?php

namespace Modules\Admin\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;

class SettingController extends ApiController
{
    public function index(Request $request)
    {
        $settings = Setting::all()->pluck('value', 'key');
        return $this->sendResponse($settings, 'Settings retrieved successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return $this->sendResponse([], 'Settings updated successfully.');
    }
}
