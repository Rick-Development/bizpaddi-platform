@extends('admin::layouts.app')

@section('title', 'Storefront Settings • Digital Storefront')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between border-b border-white/10 pb-5">
        <div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.storefront.products.index') }}" class="text-xs text-gray-400 hover:text-white transition">&larr; Back to Products</a>
            </div>
            <h1 class="text-2xl font-black text-white tracking-tight mt-1">Storefront Settings & Domain</h1>
            <p class="text-xs text-gray-400">Configure your public storefront branding, contact information, and domain mapping.</p>
        </div>
        <div>
            @if($tenant)
            <a href="{{ $tenant->getStorefrontUrl() }}" target="_blank" class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition shadow-sm">
                <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Preview Storefront</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Domain Plan Notice Card -->
    @if(!$hasCustomDomainPlan)
    <div class="p-6 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-200 space-y-3">
        <div class="flex items-start space-x-3">
            <span class="text-2xl">🔒</span>
            <div class="flex-1">
                <h3 class="text-sm font-black text-amber-300 uppercase tracking-wider">Subdomains & Custom Domains (Paid Plan Feature)</h3>
                <p class="text-xs text-gray-300 mt-1 leading-relaxed">
                    Dedicated custom subdomains and domain mapping are reserved for paid subscription tiers. 
                    Your live public storefront is fully operational and accessible via the secure path:
                    <span class="font-mono text-emerald-400 font-bold block mt-1">{{ url('/store/' . ($tenant->slug ?? 'slug')) }}</span>
                </p>
                <div class="mt-3">
                    <a href="{{ route('admin.billing.index') }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-amber-400 text-black font-extrabold text-xs hover:bg-amber-300 transition shadow-sm">
                        <span>Upgrade Plan to Unlock Subdomain & Custom Domains</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <span class="text-xl">✨</span>
            <div>
                <p class="text-xs font-bold text-white">Custom Domain Enabled on Your Plan</p>
                <p class="text-[11px] text-gray-400">You can connect your own domain or dedicated business subdomain.</p>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-400 text-black">Active Feature</span>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.storefront.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Branding & Profile -->
        <div class="p-6 rounded-2xl bg-[#10231C]/40 border border-white/10 space-y-5">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3">Storefront Branding</h2>

            <div>
                <label for="store_name" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Storefront Display Name *</label>
                <input type="text" name="store_name" id="store_name" required value="{{ old('store_name', $tenant->settings['store_name'] ?? $tenant->name) }}" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                @error('store_name')<p class="text-[11px] text-red-400 mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="store_tagline" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Tagline / Motto</label>
                    <input type="text" name="store_tagline" id="store_tagline" value="{{ old('store_tagline', $tenant->settings['store_tagline'] ?? '') }}" placeholder="e.g. Premium Tech Services & Solutions" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                </div>

                <div>
                    <label for="currency" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Catalog Currency</label>
                    <select name="currency" id="currency" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white focus:outline-none focus:border-emerald-500">
                        <option value="NGN" {{ (old('currency', $tenant->currency) === 'NGN') ? 'selected' : '' }}>NGN (₦)</option>
                        <option value="USD" {{ (old('currency', $tenant->currency) === 'USD') ? 'selected' : '' }}>USD ($)</option>
                        <option value="GBP" {{ (old('currency', $tenant->currency) === 'GBP') ? 'selected' : '' }}>GBP (£)</option>
                        <option value="EUR" {{ (old('currency', $tenant->currency) === 'EUR') ? 'selected' : '' }}>EUR (€)</option>
                        <option value="GHS" {{ (old('currency', $tenant->currency) === 'GHS') ? 'selected' : '' }}>GHS (₵)</option>
                        <option value="KES" {{ (old('currency', $tenant->currency) === 'KES') ? 'selected' : '' }}>KES (KSh)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="store_description" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">About This Storefront</label>
                <textarea name="store_description" id="store_description" rows="3" placeholder="Brief summary of your business, catalog, delivery policies, and warranties..." class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 leading-relaxed">{{ old('store_description', $tenant->settings['store_description'] ?? '') }}</textarea>
            </div>
        </div>

        <!-- Contact & Order Routing -->
        <div class="p-6 rounded-2xl bg-[#10231C]/40 border border-white/10 space-y-5">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3">Inquiry & Order Contact</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="store_phone" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">WhatsApp / Phone for Orders</label>
                    <input type="text" name="store_phone" id="store_phone" value="{{ old('store_phone', $tenant->settings['store_phone'] ?? $tenant->phone) }}" placeholder="+234 801 234 5678" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                    <p class="text-[11px] text-gray-400 mt-1">Clients can click to order directly into your WhatsApp.</p>
                </div>

                <div>
                    <label for="store_email" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Orders Email</label>
                    <input type="email" name="store_email" id="store_email" value="{{ old('store_email', $tenant->settings['store_email'] ?? $tenant->email) }}" placeholder="sales@yourbrand.com" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                </div>
            </div>
        </div>

        <!-- Domain Configuration -->
        <div class="p-6 rounded-2xl bg-[#10231C]/40 border border-white/10 space-y-5">
            <h2 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3">Domain Configuration</h2>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Default Storefront URL</label>
                <div class="flex items-center space-x-2">
                    <input type="text" readonly value="{{ url('/store/' . ($tenant->slug ?? 'your-slug')) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-emerald-400 font-mono font-bold cursor-text">
                </div>
                <p class="text-[11px] text-gray-400 mt-1">This standard URL is always active and ready to share with customers.</p>
            </div>

            @if($hasCustomDomainPlan)
            <div>
                <label for="custom_domain" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Custom Domain Mapping</label>
                <input type="text" name="custom_domain" id="custom_domain" value="{{ old('custom_domain', $tenant->custom_domain) }}" placeholder="store.yourcompany.com" class="w-full bg-[#08110e] border border-white/10 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500">
                <p class="text-[11px] text-gray-400 mt-1">Point a CNAME record from your domain provider to Bizpaddi host.</p>
            </div>
            @endif
        </div>

        <div class="flex items-center justify-end space-x-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0E5E4A] hover:bg-emerald-600 text-white text-xs font-bold transition shadow-md">
                Save Storefront Settings
            </button>
        </div>
    </form>
</div>
@endsection
