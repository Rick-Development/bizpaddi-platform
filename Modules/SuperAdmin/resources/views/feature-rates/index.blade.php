@extends('superadmin::layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-20" x-data="{ showNewModal: false }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-white/10">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Feature Micro-Rates & Monetization</h1>
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-500/15 text-amber-300 border border-amber-500/30">
                    ⚡ Dynamic PAYG Engine
                </span>
            </div>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">
                Configure real-time deduction rates charged to businesses on Pay-As-You-Transact mode when performing operational actions.
            </p>
        </div>

        <div class="flex items-center space-x-3">
            <button type="button" @click="showNewModal = true" 
                    class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold bg-white/5 text-gray-200 border border-white/10 hover:bg-white/10 hover:text-white shadow-sm transition">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Add New Feature</span>
            </button>
            <button type="submit" form="feature-rates-form" 
                    class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] hover:opacity-95 text-white shadow-lg shadow-emerald-950/40 border border-emerald-400/30 transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Save All Rates</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm font-semibold flex items-center space-x-3">
        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-xl">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Monetized Actions</span>
            <span class="text-2xl sm:text-3xl font-black text-white mt-1 block">{{ $stats['total'] }}</span>
            <span class="text-xs text-gray-400 mt-0.5 block">Configured in DB</span>
        </div>
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-xl">
            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 block">Active Micro-Gates</span>
            <span class="text-2xl sm:text-3xl font-black text-emerald-400 mt-1 block">{{ $stats['active'] }}</span>
            <span class="text-xs text-emerald-500/80 mt-0.5 block">Enforcing deductions</span>
        </div>
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-xl">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block">Highest Action Fee</span>
            <span class="text-2xl sm:text-3xl font-black text-[#F2A900] mt-1 block">₦{{ number_format($stats['max_fee'], 0) }}</span>
            <span class="text-xs text-amber-400/80 mt-0.5 block">Peak operational fee</span>
        </div>
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-5 border border-white/10 shadow-xl">
            <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block">Lowest Action Fee</span>
            <span class="text-2xl sm:text-3xl font-black text-white mt-1 block">₦{{ number_format($stats['min_fee'], 0) }}</span>
            <span class="text-xs text-gray-400 mt-0.5 block">Entry micro-fee</span>
        </div>
    </div>

    <!-- Main Rates Form -->
    <form id="feature-rates-form" action="{{ route('super-admin.feature-rates.update-all') }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        @foreach($categories as $categoryName => $catRates)
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-3xl p-6 sm:p-8 shadow-xl border border-white/10 space-y-6">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <div class="flex items-center space-x-3">
                    <span class="w-8 h-8 rounded-xl bg-emerald-500/15 text-emerald-400 flex items-center justify-center font-black text-xs border border-emerald-500/30">
                        {{ substr($categoryName, 0, 1) }}
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-white tracking-tight">{{ $categoryName }}</h2>
                        <p class="text-xs text-gray-400">{{ count($catRates) }} monetized action endpoints in this department</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($catRates as $rate)
                <div class="p-4 sm:p-5 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/30 transition flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="space-y-1 max-w-xl">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-bold text-white">{{ $rate->name }}</span>
                            <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-white/10 text-emerald-300 border border-white/10">
                                {{ $rate->action_key }}
                            </span>
                        </div>
                        <input type="text" name="rates[{{ $rate->id }}][description]" value="{{ old("rates.{$rate->id}.description", $rate->description) }}"
                               placeholder="Action description..."
                               class="w-full text-xs text-gray-400 bg-transparent border-0 p-0 focus:outline-none focus:text-white transition">
                    </div>

                    <div class="flex items-center space-x-4 self-end md:self-center">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-mono font-bold text-gray-400">₦</span>
                            <input type="number" step="1" min="0" 
                                   name="rates[{{ $rate->id }}][fee]" 
                                   value="{{ old("rates.{$rate->id}.fee", $rate->fee) }}" 
                                   required
                                   class="w-24 sm:w-28 px-3 py-2 rounded-xl text-sm font-black text-white bg-white/5 border border-white/10 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <span class="text-[11px] text-gray-400 font-medium whitespace-nowrap">{{ $rate->unit_label }}</span>
                        </div>

                        <!-- Active Toggle -->
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="rates[{{ $rate->id }}][is_active]" value="1" 
                                   {{ $rate->is_active ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Floating Save Bar -->
        <div class="sticky bottom-6 z-40 bg-[#10231C] text-white p-4 rounded-2xl shadow-2xl border border-white/10 flex items-center justify-between">
            <div class="flex items-center space-x-2 text-xs sm:text-sm text-gray-300">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Any rate updated here is immediately queried and debited during live tenant operations.</span>
            </div>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-lg transition">
                Publish Dynamic Rates
            </button>
        </div>
    </form>

    <!-- Modal: Add New Dynamic Rate -->
    <div x-show="showNewModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
        <div @click.away="showNewModal = false" class="bg-[#10231C] border border-white/10 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl text-white space-y-5">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="text-lg font-bold text-white">Create New Feature Micro-Rate</h3>
                <button type="button" @click="showNewModal = false" class="text-gray-400 hover:text-white">&times;</button>
            </div>

            <form action="{{ route('super-admin.feature-rates.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Action Key (Identifier) *</label>
                    <input type="text" name="action_key" required placeholder="e.g. inventory_export" 
                           class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white text-sm font-mono focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Feature Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Bulk Inventory Export" 
                           class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Category *</label>
                        <input type="text" name="category" required placeholder="e.g. Storefront" 
                               class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Fee (₦) *</label>
                        <input type="number" name="fee" required min="0" step="1" value="50" 
                               class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white text-sm font-bold focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Unit Label *</label>
                    <input type="text" name="unit_label" required placeholder="e.g. per export" value="per action"
                           class="w-full px-4 py-2.5 rounded-xl border border-white/10 bg-white/5 text-white text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-300 mb-1">Description</label>
                    <textarea name="description" rows="2" placeholder="Brief summary of what this fee covers..."
                              class="w-full px-4 py-2 rounded-xl border border-white/10 bg-white/5 text-white text-xs focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>

                <div class="pt-3 border-t border-white/10 flex items-center justify-end space-x-3">
                    <button type="button" @click="showNewModal = false" class="px-4 py-2 text-xs font-semibold text-gray-400 hover:text-white">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-xs font-bold bg-gradient-to-r from-[#0E5E4A] to-[#10B981] text-white">
                        Create Feature Rate
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
