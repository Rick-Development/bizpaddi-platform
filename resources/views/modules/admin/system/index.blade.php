@extends('superadmin::layouts.app')

@section('title', 'System Operations & Maintenance')

@section('content')
<div class="space-y-8">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <div class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-400">DevOps & Server Utilities</span>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight flex items-center gap-3">
                <span>System Maintenance & Operations</span>
            </h1>
            <p class="text-xs text-gray-400 mt-1 max-w-2xl">
                Execute core platform maintenance, cache flushing, background queue orchestration, and database schema migrations.
            </p>
        </div>

        {{-- System Specs Badge --}}
        <div class="flex items-center gap-2 flex-wrap">
            <span class="px-3 py-1.5 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 text-xs font-mono font-medium">
                PHP <strong class="text-white">{{ PHP_VERSION }}</strong>
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 text-xs font-mono font-medium">
                Laravel <strong class="text-white">{{ app()->version() }}</strong>
            </span>
            <span class="px-3 py-1.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-bold uppercase tracking-wider">
                {{ config('app.env') }}
            </span>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="rounded-2xl bg-emerald-950/40 border border-emerald-500/30 p-4 text-emerald-300 backdrop-blur-md shadow-xl flex items-start gap-3">
            <svg class="h-5 w-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="flex-1 text-xs">
                <strong class="font-bold text-white block mb-0.5">Execution Successful</strong>
                <pre class="mt-1 font-mono text-[11px] whitespace-pre-wrap text-emerald-200 bg-black/30 p-2.5 rounded-xl border border-white/5">{{ session('success') }}</pre>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl bg-red-950/40 border border-red-500/30 p-4 text-red-300 backdrop-blur-md shadow-xl flex items-start gap-3">
            <svg class="h-5 w-5 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div class="flex-1 text-xs">
                <strong class="font-bold text-white block mb-0.5">Execution Failed</strong>
                <p class="mt-1 text-red-200">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- SECTION 1: Caches & Performance Optimization --}}
    <div class="space-y-4">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3">
            <div class="h-2 w-2 rounded-full bg-emerald-400"></div>
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">Caches & Performance Optimization</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Full Optimize -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-emerald-500/30 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Optimize Platform</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Compile and cache configuration, routes, and services for maximum speed.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="optimize">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] hover:brightness-110 text-white shadow-md active:scale-95 transition-all">
                        Run Optimize
                    </button>
                </form>
            </div>

            <!-- Optimize Clear -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Clear Optimization</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Safely clear compiled cache files across config, routes, and services.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="optimize-clear">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Optimize Clear
                    </button>
                </form>
            </div>

            <!-- Route Clear -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Clear Route Cache</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Flush cached route mapping when routes are updated or modified.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="route-clear">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Clear Routes
                    </button>
                </form>
            </div>

            <!-- View Clear -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Clear Blade Views</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Purge pre-rendered Blade templates so new HTML changes reflect immediately.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="view-clear">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Clear Views
                    </button>
                </form>
            </div>

            <!-- Config Clear -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Clear Configuration</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Remove cached .env configurations so updated credentials take effect.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="config-clear">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Clear Config
                    </button>
                </form>
            </div>

            <!-- Application Cache -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Flush App Cache</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Wipe in-memory and database key-value cache records.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="cache-clear">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Flush Cache
                    </button>
                </form>
            </div>

            <!-- Storage Link -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Link Storage Symlink</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Recreate public/storage symlink to serve uploaded product images and logos.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="storage-link">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Link Storage
                    </button>
                </form>
            </div>

            <!-- Composer Dump -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Composer Autoload</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Regenerate PHP autoload classmap for new controllers, models, and traits.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="composer-dump">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Dump Autoload
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- SECTION 2: Database Schema & Migrations --}}
    <div class="space-y-4 pt-4">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3">
            <div class="h-2 w-2 rounded-full bg-amber-400"></div>
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">Database Schema & Migrations</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Run Core Migrations -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-amber-400/30 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-amber-300 transition-colors">Execute Core Migrations</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Safely apply pending database column and table changes (`migrate --force`).</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="migrate">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-amber-500/20 border border-amber-500/30 text-amber-300 hover:bg-amber-500/30 active:scale-95 transition-all">
                        Run Migrations
                    </button>
                </form>
            </div>

            <!-- Module Migrations -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-amber-400/30 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-amber-300 transition-colors">Module Migrations</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Run all modular table migrations across Admin, Accounting, and AI modules.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="module-migrate">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Migrate Modules
                    </button>
                </form>
            </div>

            <!-- Permission Cache Reset -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Reset Permissions</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Purge and rebuild cached roles and RBAC permission tables.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="permission-cache-reset">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Reset Permissions
                    </button>
                </form>
            </div>

            <!-- Media Cleanup -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Clean Media Library</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">Purge orphaned uploaded files not referenced in any model.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="medialibrary-clean">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                        Clean Media
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- SECTION 3: Queue Management & Background Workers --}}
    <div class="space-y-4 pt-4">
        <div class="flex items-center gap-2 border-b border-white/10 pb-3">
            <div class="h-2 w-2 rounded-full bg-blue-400"></div>
            <h2 class="text-sm font-bold text-white uppercase tracking-wider">Queue Management & Background Workers</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Queue Restart -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-blue-400/30 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-blue-400 transition-colors">Restart Queue Workers</h3>
                    <p class="text-xs text-gray-400 mt-1">Instruct active queue worker daemons to gracefully reload after code deployment.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="queue-restart">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-blue-500/20 border border-blue-500/30 text-blue-300 hover:bg-blue-500/30 active:scale-95 transition-all">
                        Restart Workers
                    </button>
                </form>
            </div>

            <!-- Retry All Failed -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-emerald-500/30 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Retry All Failed Jobs</h3>
                    <p class="text-xs text-gray-400 mt-1">Re-queue all failed jobs (emails, webhooks, disbursements) back into pipeline.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST">
                    @csrf
                    <input type="hidden" name="command" value="queue-retry-all">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] hover:brightness-110 text-white shadow-md active:scale-95 transition-all">
                        Retry All Failed
                    </button>
                </form>
            </div>

            <!-- Flush Failed Jobs -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-red-500/30 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-red-400 transition-colors">Flush Failed Jobs</h3>
                    <p class="text-xs text-gray-400 mt-1">Permanently purge all failed queue job records from the database table.</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST" onsubmit="return confirm('Permanently clear all failed jobs?')">
                    @csrf
                    <input type="hidden" name="command" value="queue-flush">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-red-500/10 border border-red-500/20 text-red-300 hover:bg-red-500/20 active:scale-95 transition-all">
                        Flush Failed Jobs
                    </button>
                </form>
            </div>

            <!-- Status Control (Pause / Resume) -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Worker Status Control</h3>
                    <p class="text-xs text-gray-400 mt-1">Pause queue processing during scheduled maintenance, then resume.</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('super-admin.system.run') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="command" value="queue-pause">
                        <button type="submit" class="w-full py-2 px-3 rounded-xl text-xs font-semibold bg-amber-500/15 border border-amber-500/30 text-amber-300 hover:bg-amber-500/25 active:scale-95 transition-all">
                            Pause
                        </button>
                    </form>
                    <form action="{{ route('super-admin.system.run') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="command" value="queue-resume">
                        <button type="submit" class="w-full py-2 px-3 rounded-xl text-xs font-semibold bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 hover:bg-emerald-500/25 active:scale-95 transition-all">
                            Resume
                        </button>
                    </form>
                </div>
            </div>

            <!-- Prune Batches & Failed -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-white/20 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-white/[0.04] border border-white/10 text-gray-300 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-emerald-400 transition-colors">Prune Stale Records</h3>
                    <p class="text-xs text-gray-400 mt-1">Delete old completed batches and prune expired job entries.</p>
                </div>
                <div class="flex gap-2">
                    <form action="{{ route('super-admin.system.run') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="command" value="queue-prune-batches">
                        <button type="submit" class="w-full py-2 px-3 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                            Batches
                        </button>
                    </form>
                    <form action="{{ route('super-admin.system.run') }}" method="POST" class="flex-1">
                        @csrf
                        <input type="hidden" name="command" value="queue-prune-failed">
                        <button type="submit" class="w-full py-2 px-3 rounded-xl text-xs font-semibold bg-white/[0.04] border border-white/10 text-gray-300 hover:text-white hover:bg-white/[0.08] active:scale-95 transition-all">
                            Failed
                        </button>
                    </form>
                </div>
            </div>

            <!-- Clear Entire Queue (Dangerous) -->
            <div class="bg-[#0b1612] rounded-2xl border border-white/10 p-5 shadow-xl flex flex-col justify-between hover:border-red-500/30 transition-all group">
                <div class="mb-4">
                    <div class="h-9 w-9 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-sm font-bold text-white group-hover:text-red-400 transition-colors">Clear Pending Queue</h3>
                    <p class="text-xs text-gray-400 mt-1">Discard all pending waiting jobs in default queue (destructive).</p>
                </div>
                <form action="{{ route('super-admin.system.run') }}" method="POST" onsubmit="return confirm('⚠️ DANGER: This will delete ALL pending background jobs. Are you sure?')">
                    @csrf
                    <input type="hidden" name="command" value="queue-clear">
                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-semibold bg-red-500/10 border border-red-500/20 text-red-300 hover:bg-red-500/20 active:scale-95 transition-all">
                        Clear All Jobs
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
