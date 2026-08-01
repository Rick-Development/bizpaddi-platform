@extends('admin::layouts.app')

@section('title', 'Configure Security Protocol')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-x-3">
            <div class="p-2 bg-indigo-100 rounded-lg">
                <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 18H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 12h7.5" />
                </svg>
            </div>
            Security Configuration
        </h1>
        <p class="mt-2 text-sm text-gray-600">Fine-tune rate limiting parameters for specific system resources.</p>
    </div>
    <a href="{{ route('admin.rate-limits.index') }}" class="inline-flex items-center gap-x-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-all duration-200">
        <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
        </svg>
        Dashboard
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-900">Protocol Thresholds</h3>
                <p class="text-xs text-gray-500 mt-1">Set the maximum allowed traffic frequency for this registry.</p>
            </div>
            <form action="{{ route('admin.rate-limits.update', $rateLimit) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="px-8 py-8 space-y-8">
                    <!-- Status Toggle -->
                    <div class="flex items-center justify-between p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100">
                        <span class="flex grow flex-col">
                            <span class="text-sm font-bold text-indigo-900" id="availability-label">Enforce Rate Limiting</span>
                            <span class="text-xs text-indigo-700/70" id="availability-description">Toggle the active shielding for this specific route.</span>
                        </span>
                        <div x-data="{ enabled: {{ $rateLimit->is_enabled ? 'true' : 'false' }} }">
                            <input type="hidden" name="is_enabled" :value="enabled ? 1 : 0">
                            <button type="button" 
                                @click="enabled = !enabled"
                                :class="enabled ? 'bg-indigo-600' : 'bg-gray-200'"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 shadow-inner" 
                                role="switch" aria-checked="false" aria-labelledby="availability-label" aria-describedby="availability-description">
                                <span aria-hidden="true" 
                                    :class="enabled ? 'translate-x-5' : 'translate-x-0'"
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow-xl ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 sm:grid-cols-2">
                        <div>
                            <label for="max_attempts" class="block text-sm font-bold text-gray-900">Max Attempts</label>
                            <p class="text-xs text-gray-500 mb-3">Total concurrent requests permitted.</p>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m9-9L21 12m0 0l-4.5 4.5M21 12H7.5" />
                                    </svg>
                                </div>
                                <input type="number" name="max_attempts" id="max_attempts" value="{{ old('max_attempts', $rateLimit->max_attempts) }}" min="1" class="block w-full rounded-xl border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-200">
                            </div>
                        </div>

                        <div>
                            <label for="decay_minutes" class="block text-sm font-bold text-gray-900">Decay Window</label>
                            <p class="text-xs text-gray-500 mb-3">Timeframe (minutes) before reset.</p>
                            <div class="relative rounded-xl shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <input type="number" name="decay_minutes" id="decay_minutes" value="{{ old('decay_minutes', $rateLimit->decay_minutes) }}" min="1" class="block w-full rounded-xl border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all duration-200">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-5 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-x-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-xl hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all duration-200">
                        Commit Protocol Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white shadow-sm border border-gray-100 rounded-2xl overflow-hidden sticky top-8">
            <div class="px-6 py-5 bg-gray-900">
                <h3 class="text-base font-bold text-white">Registry Metadata</h3>
                <p class="text-xs text-gray-400 mt-1 italic">Read-only system identifiers</p>
            </div>
            <div class="px-6 py-8 space-y-6">
                <div>
                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Global Category</dt>
                    <dd class="mt-2">
                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold ring-1 ring-inset {{ 
                            $rateLimit->category === 'Admin' ? 'bg-purple-50 text-purple-700 ring-purple-600/20' : 
                            ($rateLimit->category === 'Frontend' ? 'bg-green-50 text-green-700 ring-green-600/20' : 
                            ($rateLimit->category === 'API' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 
                            'bg-blue-50 text-blue-700 ring-blue-600/20'))
                        }}">
                            {{ $rateLimit->category }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">Internal Alias</dt>
                    <dd class="mt-2 text-sm font-semibold text-gray-900 break-all">{{ $rateLimit->route_name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-bold text-gray-400 uppercase tracking-widest">System Resource Path</dt>
                    <dd class="mt-2 text-xs font-mono text-indigo-600 bg-indigo-50 p-3 rounded-xl border border-indigo-100 break-all leading-relaxed">
                        {{ $rateLimit->route_path }}
                    </dd>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span>Registry Version</span>
                    <span class="font-mono">v1.0.4-L10</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
