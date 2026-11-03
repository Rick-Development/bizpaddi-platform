<?php

namespace Modules\SuperAdmin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Modules\Settings\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'billway_base_url' => Setting::get('billway_base_url', config('services.billway.base_url', env('BILLWAY_BASE_URL', 'https://billwayservices.com/api/v1'))),
            'billway_public_key' => Setting::get('billway_public_key', config('services.billway.public_key', env('BILLWAY_PUBLIC_KEY', ''))),
            'billway_secret_key' => Setting::get('billway_secret_key', config('services.billway.secret_key', env('BILLWAY_SECRET_KEY', ''))),
            'billway_webhook_secret' => Setting::get('billway_webhook_secret', config('services.billway.webhook_secret', env('BILLWAY_WEBHOOK_SECRET', ''))),
            'billway_default_provider' => Setting::get('billway_default_provider', config('services.billway.default_provider', env('BILLWAY_DEFAULT_PROVIDER', 'p4'))),
            'default_currency' => Setting::get('default_currency', config('app.currency', 'NGN')),
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
            'billway_default_provider' => 'nullable|string|in:p4,p1',
        ]);

        $baseUrl = rtrim($request->billway_base_url, '/');
        $publicKey = $request->billway_public_key ?? '';
        $secretKey = $request->billway_secret_key ?? '';
        $webhookSecret = $request->billway_webhook_secret ?? '';
        $defaultProvider = $request->billway_default_provider ?? 'p4';

        // 1. Persist directly in Database Settings table for instant runtime availability
        Setting::set('billway_base_url', $baseUrl);
        Setting::set('billway_public_key', $publicKey);
        Setting::set('billway_secret_key', $secretKey);
        Setting::set('billway_webhook_secret', $webhookSecret);
        Setting::set('billway_default_provider', $defaultProvider);

        // 2. Also sync to .env file as secondary environment fallback
        $envFile = base_path('.env');
        if (File::exists($envFile)) {
            $content = File::get($envFile);

            $replacements = [
                'BILLWAY_BASE_URL' => $baseUrl,
                'BILLWAY_PUBLIC_KEY' => $publicKey,
                'BILLWAY_SECRET_KEY' => $secretKey,
                'BILLWAY_WEBHOOK_SECRET' => $webhookSecret,
                'BILLWAY_DEFAULT_PROVIDER' => $defaultProvider,
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

        // 3. Clear cached config if cached in production
        try {
            Artisan::call('config:clear');
        } catch (\Throwable $e) {
            // Non-blocking in restricted environments
        }

        return back()->with('success', 'Billway API settings updated successfully.');
    }
}
