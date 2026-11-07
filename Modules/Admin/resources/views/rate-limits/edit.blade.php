@extends('superadmin::layouts.app')

@section('title', 'Configure Rate Limiting Protocol')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="p-2.5 bg-emerald-500/10 border border-emerald-500/20 rounded-xl text-emerald-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 12h7.5" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">Rate Limit Configuration</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-400">Fine-tune request limits and decay windows for this endpoint.</p>
            </div>
        </div>
        <a href="{{ route('super-admin.rate-limits.index') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-xs sm:text-sm font-bold text-gray-200 hover:bg-white/10 hover:text-white transition-all duration-200">
            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Registry
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Configuration Form -->
        <div class="lg:col-span-2">
            <div class="bg-[#10231C]/90 backdrop-blur-md shadow-xl border border-white/10 rounded-2xl overflow-hidden">
                <div class="px-6 py-5 border-b border-white/10 bg-white/[0.02]">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Protocol Thresholds</h3>
                    <p class="text-xs text-gray-400 mt-1">Set the maximum allowed request velocity for this route before throttling kicks in.</p>
                </div>

                <form action="{{ route('super-admin.rate-limits.update', $rateLimit) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-6 sm:p-8 space-y-6">
                        <!-- Status Toggle -->
                        <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/10">
                            <span class="flex grow flex-col pr-4">
                                <span class="text-sm font-bold text-white">Active Shielding Enforcement</span>
                                <span class="text-xs text-gray-400 mt-0.5">When disabled, this route bypasses the dynamic rate limiter middleware entirely.</span>
                            </span>
                            <div x-data="{ enabled: {{ $rateLimit->is_enabled ? 'true' : 'false' }} }">
                                <input type="hidden" name="is_enabled" :value="enabled ? 1 : 0">
                                <button type="button" 
                                    @click="enabled = !enabled"
                                    :class="enabled ? 'bg-gradient-to-r from-[#0E5E4A] to-[#10B981]' : 'bg-white/10'"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-inner" 
                                    role="switch">
                                    <span aria-hidden="true" 
                                        :class="enabled ? 'translate-x-5 bg-white' : 'translate-x-0 bg-gray-400'"
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full shadow-md ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </div>
                        </div>

                        <!-- Limit Inputs -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="max_attempts" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Max Attempts (Ceiling)</label>
                                <p class="text-xs text-gray-400 mb-2.5">Total requests permitted within the decay window.</p>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m9-9L21 12m0 0l-4.5 4.5M21 12H7.5" />
                                        </svg>
                                    </div>
                                    <input type="number" name="max_attempts" id="max_attempts" 
                                           value="{{ old('max_attempts', $rateLimit->max_attempts) }}" min="1" 
                                           class="block w-full rounded-xl border border-white/10 py-2.5 pl-10 pr-3 text-sm text-white bg-white/5 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                                </div>
                            </div>

                            <div>
                                <label for="decay_minutes" class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1.5">Decay Window (Minutes)</label>
                                <p class="text-xs text-gray-400 mb-2.5">Timeframe before request counters reset to zero.</p>
                                <div class="relative">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input type="number" name="decay_minutes" id="decay_minutes" 
                                           value="{{ old('decay_minutes', $rateLimit->decay_minutes) }}" min="1" 
                                           class="block w-full rounded-xl border border-white/10 py-2.5 pl-10 pr-3 text-sm text-white bg-white/5 placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/[0.02] px-6 py-4 border-t border-white/10 flex items-center justify-end gap-3">
                        <a href="{{ route('super-admin.rate-limits.index') }}" class="px-4 py-2 text-xs font-bold text-gray-400 hover:text-white transition">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center gap-x-2 rounded-xl bg-gradient-to-r from-[#0E5E4A] to-[#10B981] px-5 py-2.5 text-xs sm:text-sm font-bold text-white shadow-lg shadow-emerald-950/40 hover:opacity-95 border border-emerald-400/30 transition-all duration-200">
                            Save Security Protocol
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Metadata Sidepanel -->
        <div class="lg:col-span-1">
            <div class="bg-[#10231C]/90 backdrop-blur-md shadow-xl border border-white/10 rounded-2xl overflow-hidden sticky top-8">
                <div class="px-6 py-5 border-b border-white/10 bg-white/[0.02]">
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider">Registry Metadata</h3>
                    <p class="text-xs text-gray-400 mt-1">Read-only system identifiers & route signature.</p>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Global Category</dt>
                        <dd class="mt-2">
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold {{ 
                                $rateLimit->category === 'Admin' ? 'bg-purple-500/20 text-purple-300 border border-purple-500/30' : 
                                ($rateLimit->category === 'Frontend' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 
                                ($rateLimit->category === 'API' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 
                                'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30'))
                            }}">
                                {{ $rateLimit->category }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Route Alias</dt>
                        <dd class="mt-2 text-sm font-mono font-bold text-white break-all bg-white/5 p-2.5 rounded-xl border border-white/10">
                            {{ $rateLimit->route_name }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">System Resource Path</dt>
                        <dd class="mt-2 text-xs font-mono text-emerald-300 bg-white/5 p-3 rounded-xl border border-white/10 break-all leading-relaxed">
                            {{ $rateLimit->route_path }}
                        </dd>
                    </div>
                </div>
                <div class="px-6 py-3.5 bg-white/[0.02] border-t border-white/10 flex items-center justify-between text-xs text-gray-400">
                    <span>Engine</span>
                    <span class="font-mono text-emerald-400">Bizpaddi Firewall</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
