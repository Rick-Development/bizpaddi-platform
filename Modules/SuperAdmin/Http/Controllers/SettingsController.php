<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'billway_base_url' => config('services.billway.base_url', 'https://api.billwayhq.com'),
            'billway_public_key' => config('services.billway.public_key', ''),
            'billway_secret_key' => config('services.billway.secret_key', ''),
            'billway_webhook_secret' => config('services.billway.webhook_secret', ''),
            'default_currency' => config('app.currency', 'NGN'),
            'default_trial_days' => 14,
            'webhook_url' => route('webhooks.billway'),
        ];

        return view('superadmin::settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'billway_base_url' => 'required|url',
            'billway_public_key' => 'nullable|string',
            'billway_secret_key' => 'nullable|string',
            'billway_webhook_secret' => 'nullable|string',
        ]);

        $envFile = base_path('.env');
        if (File::exists($envFile)) {
            $content = File::get($envFile);

            $replacements = [
                'BILLWAY_BASE_URL' => $request->billway_base_url,
                'BILLWAY_PUBLIC_KEY' => $request->billway_public_key ?? '',
                'BILLWAY_SECRET_KEY' => $request->billway_secret_key ?? '',
                'BILLWAY_WEBHOOK_SECRET' => $request->billway_webhook_secret ?? '',
            ];

            foreach ($replacements as $key => $value) {
                if (preg_match("/^{$key}=/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
                } else {
                    $content .= PHP_EOL . "{$key}={$value}";
                }
            }

            File::put($envFile, $content);
        }

        return back()->with('success', 'Billway API settings updated successfully.');
    }
}
