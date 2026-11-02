@extends('admin::layouts.app')

@section('title', 'Mail / SMTP Settings')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold text-white">Mail / SMTP Settings</h1>
        <p class="mt-2 text-sm text-gray-400">Configure the outgoing email server used for all system notifications, weekly reports, and lead alerts. Changes are written directly to your <code class="bg-white/[0.06] px-1 rounded text-xs font-mono">.env</code> file.</p>
    </div>
    <a href="{{ route('admin.settings.index') }}" class="text-sm text-gray-400 hover:text-gray-300 flex items-center gap-1">
        ← Back to Settings
    </a>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-lg bg-green-50 border border-green-200 p-4">
        <svg class="h-5 w-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
    </div>
@endif

@if(session('test_success'))
    <div class="mb-6 flex items-start gap-3 rounded-lg bg-blue-50 border border-blue-200 p-4">
        <svg class="h-5 w-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/></svg>
        <p class="text-sm font-medium text-blue-800">{{ session('test_success') }}</p>
    </div>
@endif

@if(session('test_error'))
    <div class="mb-6 flex items-start gap-3 rounded-lg bg-red-50 border border-red-200 p-4">
        <svg class="h-5 w-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5a1 1 0 112 0v-2a1 1 0 11-2 0v2zm0-6a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd"/></svg>
        <div>
            <p class="text-sm font-medium text-red-800">SMTP Connection Failed</p>
            <p class="text-sm text-red-700 mt-1">{{ session('test_error') }}</p>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
        <p class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</p>
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li class="text-sm text-red-700">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- ===========================
         LEFT: SMTP Config Form
         =========================== --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Current Status Banner --}}
        <div class="rounded-xl border border-white/10 bg-[#10231C]/60 border border-white/10 overflow-hidden">
            <div class="px-6 py-4 bg-white/[0.03] border-b border-white/10 flex items-center justify-between">
                <h2 class="text-base font-semibold text-white">Current Status</h2>
                @php
                    $currentMailer = $settings['mail_mailer'] ?? config('mail.default', 'log');
                    $isConfigured  = $currentMailer !== 'log' && $currentMailer !== 'array' && !empty($settings['mail_host']);
                @endphp
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-0.5 text-xs font-medium
                    {{ $isConfigured ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $isConfigured ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                    {{ $isConfigured ? 'SMTP Active' : 'Not Configured (using log driver)' }}
                </span>
            </div>
            <div class="px-6 py-4 grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Mailer</p>
                    <p class="mt-1 font-semibold text-white">{{ strtoupper($settings['mail_mailer'] ?? 'log') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Host</p>
                    <p class="mt-1 font-semibold text-white truncate">{{ $settings['mail_host'] ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">Port</p>
                    <p class="mt-1 font-semibold text-white">{{ $settings['mail_port'] ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-medium uppercase tracking-wider">From</p>
                    <p class="mt-1 font-semibold text-white truncate">{{ $settings['mail_from_address'] ?: '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Main Config Form --}}
        <form action="{{ route('admin.settings.mail.update') }}" method="POST" class="rounded-xl border border-white/10 bg-[#10231C]/60 border border-white/10 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b border-white/10 bg-white/[0.03]">
                <h2 class="text-base font-semibold text-white">SMTP Configuration</h2>
                <p class="mt-1 text-xs text-gray-400">These values will be saved to the database and written to your <code class="font-mono">.env</code> file.</p>
            </div>

            <div class="px-6 py-6 space-y-6">

                {{-- Mailer Driver --}}
                <div>
                    <label for="mail_mailer" class="block text-sm font-medium text-white">Mail Driver</label>
                    <select name="mail_mailer" id="mail_mailer"
                        class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                        <option value="smtp"    {{ ($settings['mail_mailer'] ?? '') === 'smtp'     ? 'selected' : '' }}>SMTP (Recommended)</option>
                        <option value="ses"     {{ ($settings['mail_mailer'] ?? '') === 'ses'      ? 'selected' : '' }}>Amazon SES</option>
                        <option value="postmark"{{ ($settings['mail_mailer'] ?? '') === 'postmark' ? 'selected' : '' }}>Postmark</option>
                        <option value="resend"  {{ ($settings['mail_mailer'] ?? '') === 'resend'   ? 'selected' : '' }}>Resend</option>
                        <option value="sendmail"{{ ($settings['mail_mailer'] ?? '') === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                        <option value="log"     {{ ($settings['mail_mailer'] ?? '') === 'log'      ? 'selected' : '' }}>Log (dev/debug only)</option>
                    </select>
                    <p class="mt-1.5 text-xs text-gray-400">Use <strong>SMTP</strong> for all production environments.</p>
                </div>

                {{-- Host + Port + Encryption row --}}
                <div class="grid grid-cols-1 sm:grid-cols-6 gap-4">
                    <div class="sm:col-span-3">
                        <label for="mail_host" class="block text-sm font-medium text-white">SMTP Host</label>
                        <input type="text" name="mail_host" id="mail_host"
                               value="{{ old('mail_host', $settings['mail_host'] ?? '') }}"
                               placeholder="e.g. smtp.gmail.com"
                               class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                    </div>

                    <div class="sm:col-span-1">
                        <label for="mail_port" class="block text-sm font-medium text-white">Port</label>
                        <input type="number" name="mail_port" id="mail_port"
                               value="{{ old('mail_port', $settings['mail_port'] ?? 587) }}"
                               placeholder="587"
                               class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                    </div>

                    <div class="sm:col-span-2">
                        <label for="mail_encryption" class="block text-sm font-medium text-white">Encryption</label>
                        <select name="mail_encryption" id="mail_encryption"
                            class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                            <option value="tls"      {{ ($settings['mail_encryption'] ?? 'tls') === 'tls'      ? 'selected' : '' }}>TLS (port 587)</option>
                            <option value="ssl"      {{ ($settings['mail_encryption'] ?? '') === 'ssl'         ? 'selected' : '' }}>SSL (port 465)</option>
                            <option value="starttls" {{ ($settings['mail_encryption'] ?? '') === 'starttls'    ? 'selected' : '' }}>STARTTLS</option>
                            <option value=""         {{ ($settings['mail_encryption'] ?? '') === ''            ? 'selected' : '' }}>None</option>
                        </select>
                    </div>
                </div>

                {{-- Username + Password --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="mail_username" class="block text-sm font-medium text-white">SMTP Username</label>
                        <input type="text" name="mail_username" id="mail_username"
                               value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                               placeholder="your@email.com"
                               autocomplete="off"
                               class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                    </div>
                    <div>
                        <label for="mail_password" class="block text-sm font-medium text-white">
                            SMTP Password / App Password
                        </label>
                        <div class="relative mt-2">
                            <input type="password" name="mail_password" id="mail_password"
                                   value=""
                                   placeholder="{{ !empty($settings['mail_password']) ? '••••••••••••' : 'Enter password' }}"
                                   autocomplete="new-password"
                                   class="block w-full rounded-md border-0 py-1.5 pr-10 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-400">
                                <svg id="eye-icon" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @if(!empty($settings['mail_password']))
                            <p class="mt-1 text-xs text-amber-600">A password is currently saved. Leave blank to keep it unchanged.</p>
                        @endif
                    </div>
                </div>

                {{-- From Address + From Name --}}
                <div class="rounded-lg bg-white/[0.03] border border-white/10 p-4 space-y-4">
                    <h3 class="text-sm font-semibold text-white">Sender Identity</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="mail_from_address" class="block text-sm font-medium text-white">From Address</label>
                            <input type="email" name="mail_from_address" id="mail_from_address"
                                   value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                                   placeholder="noreply@bizpaddi.com"
                                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                            <p class="mt-1 text-xs text-gray-400">Must match or be authorized by your SMTP provider.</p>
                        </div>
                        <div>
                            <label for="mail_from_name" class="block text-sm font-medium text-white">From Name</label>
                            <input type="text" name="mail_from_name" id="mail_from_name"
                                   value="{{ old('mail_from_name', $settings['mail_from_name'] ?? config('app.name')) }}"
                                   placeholder="Bizpaddi"
                                   class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-brand-primary sm:text-sm bg-[#0a1612] text-white border-white/10">
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-white/[0.03] border-t border-white/10 flex items-center justify-end gap-3">
                <a href="{{ route('admin.settings.index') }}"
                   class="text-sm font-medium text-gray-400 hover:text-white px-4 py-2 rounded-md border border-white/10 bg-[#10231C]/60">
                    Cancel
                </a>
                <button type="submit"
                        class="rounded-md bg-brand-primary px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 transition-colors">
                    Save & Apply Settings
                </button>
            </div>
        </form>
    </div>

    {{-- ===========================
         RIGHT: Test Email + Tips
         =========================== --}}
    <div class="space-y-6">

        {{-- Test Email Card --}}
        <div class="rounded-xl border border-white/10 bg-[#10231C]/60 border border-white/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-white/[0.03]">
                <h2 class="text-base font-semibold text-white">Send Test Email</h2>
                <p class="mt-1 text-xs text-gray-400">Use the saved configuration to verify delivery.</p>
            </div>
            <form action="{{ route('admin.settings.mail.test') }}" method="POST" class="px-6 py-4 space-y-4">
                @csrf
                <div>
                    <label for="test_email" class="block text-sm font-medium text-white">Recipient Email</label>
                    <input type="email" name="test_email" id="test_email"
                           value="{{ old('test_email', auth()->user()->email ?? '') }}"
                           placeholder="you@example.com"
                           class="mt-2 block w-full rounded-md border-0 py-1.5 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-brand-primary sm:text-sm">
                </div>
                <button type="submit"
                        class="w-full rounded-md bg-gray-800 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-700 transition-colors flex items-center justify-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Test Email
                </button>
            </form>
        </div>

        {{-- Common SMTP Presets --}}
        <div class="rounded-xl border border-white/10 bg-[#10231C]/60 border border-white/10 overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-white/[0.03]">
                <h2 class="text-base font-semibold text-white">Common SMTP Presets</h2>
                <p class="mt-1 text-xs text-gray-400">Click to auto-fill the form.</p>
            </div>
            <div class="px-6 py-4 space-y-2">
                <button type="button" onclick="applyPreset('smtp.gmail.com', 587, 'tls')"
                    class="w-full text-left px-3 py-2 rounded-lg border border-white/10 hover:bg-white/[0.03] transition-colors">
                    <p class="text-sm font-semibold text-white">Gmail</p>
                    <p class="text-xs text-gray-400">smtp.gmail.com · Port 587 · TLS</p>
                </button>
                <button type="button" onclick="applyPreset('smtp.office365.com', 587, 'starttls')"
                    class="w-full text-left px-3 py-2 rounded-lg border border-white/10 hover:bg-white/[0.03] transition-colors">
                    <p class="text-sm font-semibold text-white">Microsoft 365 / Outlook</p>
                    <p class="text-xs text-gray-400">smtp.office365.com · Port 587 · STARTTLS</p>
                </button>
                <button type="button" onclick="applyPreset('smtp.zoho.com', 587, 'tls')"
                    class="w-full text-left px-3 py-2 rounded-lg border border-white/10 hover:bg-white/[0.03] transition-colors">
                    <p class="text-sm font-semibold text-white">Zoho Mail</p>
                    <p class="text-xs text-gray-400">smtp.zoho.com · Port 587 · TLS</p>
                </button>
                <button type="button" onclick="applyPreset('smtp.mailgun.org', 587, 'tls')"
                    class="w-full text-left px-3 py-2 rounded-lg border border-white/10 hover:bg-white/[0.03] transition-colors">
                    <p class="text-sm font-semibold text-white">Mailgun</p>
                    <p class="text-xs text-gray-400">smtp.mailgun.org · Port 587 · TLS</p>
                </button>
                <button type="button" onclick="applyPreset('smtp.sendgrid.net', 587, 'tls')"
                    class="w-full text-left px-3 py-2 rounded-lg border border-white/10 hover:bg-white/[0.03] transition-colors">
                    <p class="text-sm font-semibold text-white">SendGrid</p>
                    <p class="text-xs text-gray-400">smtp.sendgrid.net · Port 587 · TLS</p>
                </button>
            </div>
        </div>

        {{-- Info Tips --}}
        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 space-y-2">
            <p class="font-semibold">💡 Tips</p>
            <ul class="list-disc pl-4 space-y-1 text-xs">
                <li>For Gmail, use an <strong>App Password</strong> (not your Google account password). Enable 2FA on your Google account first.</li>
                <li>If using a cPanel host, the SMTP host is usually <code class="font-mono">mail.yourdomain.com</code>.</li>
                <li>After saving, always send a test email before going live.</li>
                <li>The password is stored encrypted in the database and written to <code class="font-mono">.env</code> for process-level access.</li>
            </ul>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function togglePassword() {
    const input = document.getElementById('mail_password');
    const icon  = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`;
    } else {
        input.type = 'password';
        icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
}

function applyPreset(host, port, encryption) {
    document.getElementById('mail_host').value       = host;
    document.getElementById('mail_port').value       = port;
    document.getElementById('mail_encryption').value = encryption;
    document.getElementById('mail_mailer').value     = 'smtp';

    // Scroll the host field into view
    document.getElementById('mail_host').scrollIntoView({ behavior: 'smooth', block: 'center' });
    document.getElementById('mail_host').classList.add('ring-brand-primary', 'ring-2');
    setTimeout(() => document.getElementById('mail_host').classList.remove('ring-brand-primary', 'ring-2'), 2000);
}
</script>
@endpush
