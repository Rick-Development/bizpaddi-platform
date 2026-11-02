@extends('superadmin::layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">SaaS Subscription Plans</h1>
            <p class="text-sm text-gray-400">Configure packaging tiers, per-seat fees, and feature gates for Bizpaddi businesses.</p>
        </div>
        <a href="{{ route('super-admin.plans.create') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-lg shadow-emerald-950/40 border border-emerald-400/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Create New Plan</span>
        </a>
    </div>

    <!-- Plans Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($plans as $plan)
        <div class="bg-[#10231C]/90 backdrop-blur-md rounded-2xl p-6 shadow-xl border {{ $plan->is_active ? 'border-white/10 hover:border-emerald-500/30' : 'border-red-500/30 opacity-60' }} flex flex-col justify-between transition">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold px-2 py-0.5 rounded {{ $plan->billing_type === 'payg' ? 'bg-amber-400/20 text-amber-300 font-extrabold border border-amber-400/30' : ($plan->billing_type === 'per_seat' ? 'bg-[#F2A900]/20 text-[#F2A900] border border-[#F2A900]/30' : 'bg-[#0E5E4A]/30 text-emerald-300 border border-emerald-400/30') }}">
                        {{ $plan->billing_type === 'payg' ? '⚡ PAYG Micro-Fees' : ($plan->billing_type === 'per_seat' ? 'Per-Seat Model' : 'Flat Tiered') }}
                    </span>
                    <span class="text-xs text-gray-400 font-semibold">{{ $plan->subscriptions_count }} Subscribers</span>
                </div>

                <h3 class="text-xl font-extrabold text-white mt-3">{{ $plan->name }}</h3>
                <span class="text-xs font-mono text-gray-400 block">{{ $plan->code }}</span>
                <p class="text-xs text-gray-300 mt-2">{{ $plan->description }}</p>

                <!-- Pricing Display -->
                <div class="mt-4 p-4 rounded-xl bg-white/[0.03] border border-white/10 space-y-1">
                    <div class="flex items-baseline justify-between">
                        <span class="text-xs text-gray-400 font-semibold">Base Price</span>
                        <span class="text-lg font-extrabold text-white">₦{{ number_format($plan->price, 2) }}</span>
                    </div>
                    @if($plan->billing_type === 'per_seat')
                    <div class="flex items-baseline justify-between text-xs text-emerald-400 font-semibold">
                        <span>Extra Seat Cost</span>
                        <span>+ ₦{{ number_format($plan->price_per_user, 2) }}/user</span>
                    </div>
                    @endif
                    <div class="flex items-baseline justify-between text-xs text-gray-400">
                        <span>Included Seats</span>
                        <span class="font-bold text-white">{{ $plan->included_users }} seats</span>
                    </div>
                    <div class="flex items-baseline justify-between text-xs text-gray-400">
                        <span>Max Users</span>
                        <span class="font-bold text-white">{{ $plan->max_users ? $plan->max_users . ' seats' : 'Unlimited' }}</span>
                    </div>
                </div>

                <!-- Features Preview -->
                <div class="mt-4 space-y-1.5">
                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Key Features</span>
                    <div class="flex flex-wrap gap-1">
                        @if($plan->enable_ai)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-400/20 text-amber-300 border border-amber-400/30">AI Workforce ✨</span>
                        @endif
                        @if($plan->enable_custom_domain)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Custom Domain 🌐</span>
                        @endif
                        @foreach(array_slice($plan->features ?? [], 0, 4, true) as $key => $val)
                        @if($val)
                        <span class="text-[10px] font-medium px-2 py-0.5 rounded bg-white/10 text-gray-300 border border-white/10 capitalize">{{ str_replace('_', ' ', $key) }}</span>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-6 pt-4 border-t border-white/10 flex items-center justify-between">
                <a href="{{ route('super-admin.plans.edit', $plan) }}" class="text-xs font-bold text-emerald-400 hover:text-emerald-300 transition">
                    Edit Plan Settings &rarr;
                </a>
                @if($plan->is_active)
                <form action="{{ route('super-admin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Deactivate this plan?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-semibold text-rose-400 hover:text-rose-300">Deactivate</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
