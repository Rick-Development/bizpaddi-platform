<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Settings\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class MailSettingsController extends Controller
{
    /**
     * The mail setting keys managed by this controller.
     */
    protected array $mailKeys = [
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'mail_encryption',
        'mail_from_address',
        'mail_from_name',
    ];

    /**
     * Show the mail settings form.
     */
    public function index()
    {
        $settings = [];
        foreach ($this->mailKeys as $key) {
            $settings[$key] = Setting::get($key);
        }

        // Populate from current config as fallbacks if not yet saved to DB
        $settings['mail_mailer']       = $settings['mail_mailer']       ?? config('mail.default', 'smtp');
        $settings['mail_host']         = $settings['mail_host']         ?? config('mail.mailers.smtp.host', '');
        $settings['mail_port']         = $settings['mail_port']         ?? config('mail.mailers.smtp.port', 587);
        $settings['mail_username']     = $settings['mail_username']     ?? config('mail.mailers.smtp.username', '');
        $settings['mail_password']     = $settings['mail_password']     ?? '';
        $settings['mail_encryption']   = $settings['mail_encryption']   ?? config('mail.mailers.smtp.scheme', 'tls');
        $settings['mail_from_address'] = $settings['mail_from_address'] ?? config('mail.from.address', '');
        $settings['mail_from_name']    = $settings['mail_from_name']    ?? config('mail.from.name', config('app.name'));

        return view('admin::settings.mail', compact('settings'));
    }

    /**
     * Save mail settings to the DB and update the .env file.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'mail_mailer'       => 'required|string|in:smtp,sendmail,ses,postmark,resend,log,array',
            'mail_host'         => 'nullable|string|max:255',
            'mail_port'         => 'nullable|integer|min:1|max:65535',
            'mail_username'     => 'nullable|string|max:255',
            'mail_password'     => 'nullable|string|max:255',
            'mail_encryption'   => 'nullable|string|in:tls,ssl,starttls,',
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name'    => 'required|string|max:255',
        ]);

        // If password field was left blank, keep the existing saved password
        if (empty($validated['mail_password'])) {
            $validated['mail_password'] = Setting::get('mail_password', '');
        }

        // 1. Save each value to the settings table
        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        // 2. Write values to .env file
        $encryption = $validated['mail_encryption'] ?? 'tls';
        $scheme = ($encryption === 'ssl') ? 'smtps' : (($encryption === 'tls' || $encryption === 'starttls') ? 'smtp' : '');

        $this->writeToEnv([
            'MAIL_MAILER'       => $validated['mail_mailer'],
            'MAIL_HOST'         => $validated['mail_host'] ?? '',
            'MAIL_PORT'         => $validated['mail_port'] ?? 587,
            'MAIL_USERNAME'     => $validated['mail_username'] ?? '',
            'MAIL_PASSWORD'     => $validated['mail_password'] ?? '',
            'MAIL_SCHEME'       => $scheme,
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_FROM_NAME'    => '"' . $validated['mail_from_name'] . '"',
        ]);

        // 3. Apply config at runtime for the current request cycle
        $this->applyMailConfig($validated);

        return redirect()->route('admin.settings.mail.index')
                         ->with('success', 'Mail settings saved successfully. The .env file has been updated.');
    }

    /**
     * Send a test email to verify the SMTP configuration.
     */
    public function sendTest(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        // Apply latest saved config before attempting to send
        $settings = [];
        foreach ($this->mailKeys as $key) {
            $settings[$key] = Setting::get($key);
        }
        $this->applyMailConfig($settings);

        try {
            Mail::raw(
                "This is a test email from " . config('app.name') . ".\n\n" .
                "If you received this, your SMTP configuration is working correctly.\n\n" .
                "Sent at: " . now()->toDateTimeString(),
                function ($message) use ($request) {
                    $message->to($request->test_email)
                            ->subject('Test Email — ' . config('app.name'));
                }
            );

            return back()->with('test_success', 'Test email sent to ' . $request->test_email . '. Please check your inbox.');
        } catch (\Exception $e) {
            Log::error('Mail test failed: ' . $e->getMessage());
            return back()->with('test_error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    /**
     * Apply mail configuration to the running Laravel config at runtime.
     */
    protected function applyMailConfig(array $settings): void
    {
        $mailer   = $settings['mail_mailer']     ?? 'smtp';
        $host     = $settings['mail_host']       ?? '';
        $port     = (int) ($settings['mail_port'] ?? 587);
        $username = $settings['mail_username']   ?? '';
        $password = $settings['mail_password']   ?? '';
        $encryption = $settings['mail_encryption'] ?? 'tls';
        $from     = $settings['mail_from_address'] ?? '';
        $name     = $settings['mail_from_name']    ?? config('app.name');

        // Map tls/starttls -> smtp, ssl -> smtps for Symfony Mailer compatibility
        if ($encryption === 'tls' || $encryption === 'starttls') {
            $scheme = 'smtp';
        } elseif ($encryption === 'ssl') {
            $scheme = 'smtps';
        } else {
            $scheme = null;
        }

        Config::set('mail.default', $mailer);
        Config::set('mail.mailers.smtp.host', $host);
        Config::set('mail.mailers.smtp.port', $port);
        Config::set('mail.mailers.smtp.username', $username);
        Config::set('mail.mailers.smtp.password', $password);
        Config::set('mail.mailers.smtp.scheme', $scheme);
        Config::set('mail.from.address', $from);
        Config::set('mail.from.name', $name);

        Mail::purge('smtp');
        if ($mailer !== 'smtp') {
            Mail::purge($mailer);
        }
    }

    /**
     * Write key=value pairs to the .env file, replacing existing keys.
     */
    protected function writeToEnv(array $data): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            // Values containing spaces or special characters must be quoted
            $needsQuotes = preg_match('/\s/', (string) $value) && !str_starts_with((string) $value, '"');
            $formattedValue = $needsQuotes ? '"' . $value . '"' : $value;

            // Replace the existing key line if found, otherwise append it
            if (preg_match("/^{$key}=.*/m", $envContent)) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$formattedValue}",
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}={$formattedValue}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
