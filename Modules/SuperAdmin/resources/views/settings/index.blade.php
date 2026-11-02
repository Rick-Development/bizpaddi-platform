@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div>
        <h1 class="text-2xl font-extrabold text-white tracking-tight">Super Admin & Billway Settings</h1>
        <p class="text-sm text-gray-400">Configure your Billway payment credentials and SaaS billing parameters.</p>
    </div>

    <!-- Webhook URL Banner -->
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-5 shadow-xl border border-white/10 space-y-2">
        <span class="text-xs font-bold uppercase text-gray-400 block">Your Billway Webhook URL</span>
        <div class="flex items-center space-x-2">
            <input type="text" readonly value="{{ $settings['webhook_url'] }}" id="webhook_url"
                   class="flex-1 bg-white/5 px-3.5 py-2 text-xs font-mono rounded-xl border border-white/10 text-emerald-300 select-all focus:outline-none">
            <button onclick="navigator.clipboard.writeText(document.getElementById('webhook_url').value); alert('Webhook URL copied!')"
                    class="px-3 py-2 text-xs font-bold rounded-xl bg-[#0E5E4A] text-white hover:bg-[#0c4e3e] border border-emerald-400/30 transition">
                Copy
            </button>
        </div>
        <p class="text-[11px] text-gray-400">Add this URL into your Billway merchant dashboard to receive automated renewal webhooks.</p>
    </div>

    <!-- Settings Form -->
    <form action="{{ route('super-admin.settings.update') }}" method="POST" class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border border-white/10 space-y-5">
        @csrf

        <h2 class="text-base font-bold text-white tracking-tight">Billway API Credentials</h2>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Billway Base API URL *</label>
            <input type="url" name="billway_base_url" value="{{ old('billway_base_url', $settings['billway_base_url']) }}" required
                   class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-mono placeholder-gray-500">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Billway Public Key</label>
            <input type="text" name="billway_public_key" value="{{ old('billway_public_key', $settings['billway_public_key']) }}" placeholder="pk_live_..."
                   class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-mono placeholder-gray-500">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Billway Secret Key</label>
            <input type="password" name="billway_secret_key" value="{{ old('billway_secret_key', $settings['billway_secret_key']) }}" placeholder="sk_live_..."
                   class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-mono placeholder-gray-500">
        </div>

        <div>
            <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Webhook Secret Hash</label>
            <input type="password" name="billway_webhook_secret" value="{{ old('billway_webhook_secret', $settings['billway_webhook_secret']) }}" placeholder="whsec_..."
                   class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white focus:ring-2 focus:ring-[#0E5E4A] focus:outline-none text-sm font-mono placeholder-gray-500">
        </div>

        <div class="pt-4 border-t border-white/10 flex items-center justify-end">
            <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-lg shadow-emerald-950/40 border border-emerald-400/30 transition">
                Save API Credentials
            </button>
        </div>
    </form>
</div>
@endsection
