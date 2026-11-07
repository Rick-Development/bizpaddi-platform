@extends('superadmin::layouts.app')

@section('title', 'Global SEO & Social Card Manager')

@section('content')
<div class="max-w-4xl mx-auto space-y-8 pb-16">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center space-x-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400">Search Engine & Social Media Visibility</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Global SEO Manager</h1>
            <p class="mt-1 text-xs sm:text-sm text-gray-400">
                Configure platform meta tags, browser titles, search indexing, and WhatsApp / Twitter link cards.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}" target="_blank" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-white/5 text-gray-200 border border-white/10 hover:bg-white/10 hover:text-white transition">
                <span>View Live Site</span>
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h11a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <button type="submit" form="seo-form" 
                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] hover:opacity-95 text-white shadow-lg shadow-emerald-950/40 border border-emerald-400/30 transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save SEO Settings</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm font-semibold flex items-center space-x-3">
        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    {{-- Live Social Media Card Preview --}}
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-emerald-500/20 space-y-4">
        <div class="flex items-center justify-between border-b border-white/10 pb-3">
            <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-white">Live WhatsApp & Social Media Preview</span>
            </div>
            <span class="text-[10px] font-bold text-gray-400">1200 × 630 Card</span>
        </div>

        <div class="max-w-md bg-[#08110e] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <div class="aspect-[1200/630] w-full bg-black/40 overflow-hidden relative">
                <img src="{{ !empty($seo->og_image) ? (str_starts_with($seo->og_image, 'http') ? $seo->og_image : asset($seo->og_image)) : asset('images/bizpaddi/og-preview.png') }}" 
                     alt="Social Preview" 
                     class="w-full h-full object-cover">
            </div>
            <div class="p-4 space-y-1 bg-[#10231C]">
                <span class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider">bizpaddi.com</span>
                <h4 class="text-xs font-bold text-white truncate">{{ $seo->title ?? 'Bizpaddi | All-in-One Business Operating System for African Commerce' }}</h4>
                <p class="text-[11px] text-gray-400 line-clamp-2 leading-relaxed">{{ $seo->description ?? 'Run your entire business on autopilot. Smart invoicing with automated payment reconciliation, Kanban project pipelines, staff payroll, and digital storefronts.' }}</p>
            </div>
        </div>
    </div>

    {{-- SEO Form --}}
    <div class="bg-[#10231C]/90 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-white/10">
        <form id="seo-form" action="{{ \Route::has('super-admin.seo.update') ? route('super-admin.seo.update') : route('admin.seo.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Global Title -->
            <div>
                <label for="title" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">
                    Global Title Suffix / Default Title
                </label>
                <input type="text" name="title" id="title" value="{{ old('title', $seo->title) }}" required
                       class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-medium"
                       placeholder="Bizpaddi | All-in-One Business Operating System for African Commerce">
                <p class="mt-1.5 text-[11px] text-gray-400">Used as the browser tab title and the main headline when shared on social media and Google search.</p>
            </div>

            <!-- Global Description -->
            <div>
                <label for="description" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">
                    Default Meta Description
                </label>
                <textarea name="description" id="description" rows="3" required
                          class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm leading-relaxed"
                          placeholder="Run your entire business on autopilot...">{{ old('description', $seo->description) }}</textarea>
                <p class="mt-1.5 text-[11px] text-gray-400">Displayed in Google search results and WhatsApp / Twitter link cards (recommended: 140–160 characters).</p>
            </div>

            <!-- Global Keywords -->
            <div>
                <label for="keywords" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">
                    Default Meta Keywords
                </label>
                <input type="text" name="keywords" id="keywords" value="{{ old('keywords', $seo->keywords) }}"
                       class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm"
                       placeholder="business management platform, invoicing software, virtual bank accounts, payroll software">
                <p class="mt-1.5 text-[11px] text-gray-400">Comma-separated keywords for search engine categorization.</p>
            </div>

            <!-- Default OG Image -->
            <div>
                <label for="og_image" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">
                    Default Social Share Image (OG Image)
                </label>
                <input type="text" name="og_image" id="og_image" value="{{ old('og_image', $seo->og_image) }}"
                       class="w-full px-4 py-3 rounded-xl border border-white/10 bg-white/5 text-white focus:outline-none focus:ring-2 focus:ring-emerald-500 text-sm font-mono"
                       placeholder="/images/bizpaddi/og-preview.png">
                <p class="mt-1.5 text-[11px] text-gray-400">
                    Path to 1200×630px social image. Default is <span class="font-mono text-emerald-400">/images/bizpaddi/og-preview.png</span>.
                </p>
            </div>

            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                <span class="text-xs text-gray-400">Changes reflect immediately across WhatsApp, Facebook, LinkedIn and Google indexers.</span>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-lg transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection