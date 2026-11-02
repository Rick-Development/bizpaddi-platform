@extends('admin::layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8" x-data="{ showTopupModal: false, topupAmount: 2500 }">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white">Subscription & Operating Wallet</h1>
            <p class="text-sm text-gray-400 mt-0.5">Manage your business operating wallet balance, micro-fee ledger, and platform subscriptions.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $tenant->billing_model === 'payg' ? 'bg-amber-500/20 text-amber-300 border border-amber-400/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                {{ $tenant->billing_model === 'payg' ? '⚡ Pay-As-You-Transact Mode' : '🏢 Subscription Mode' }}
            </span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $tenant->status === 'active' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-400/30' }}">
                {{ ucfirst($tenant->status) }} Account
            </span>
        </div>
    </div>

    <!-- Operating Wallet & Billing Status Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Card 1: Operating Wallet Balance -->
        <div class="bg-gradient-to-br from-[#10231C] to-[#08110e] rounded-3xl p-6 sm:p-7 text-white shadow-xl border border-white/10 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Operating Wallet Balance</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                </div>
                <div class="mt-4 flex items-baseline space-x-2">
                    <span class="text-3xl sm:text-4xl font-black text-white">
                        ₦{{ number_format($tenant->wallet_balance ?? 0, 2) }}
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                    Micro-fees for invoice generation and team actions are deducted from this balance. Zero monthly commitment.
                </p>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 flex items-center space-x-2">
                <button type="button" 
                        @click="showTopupModal = true"
                        class="w-full py-2.5 px-4 rounded-xl text-xs font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-lg transition flex items-center justify-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Top Up Wallet</span>
                </button>
            </div>
        </div>

        <!-- Card 2: Active Plan / Mode Status -->
        <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-3xl p-6 sm:p-7 shadow-xl border border-white/10 flex flex-col justify-between text-white">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block">Current Billing Paradigm</span>
                <span class="text-2xl font-extrabold text-white mt-1 block">
                    @if($tenant->billing_model === 'payg')
                        Pay-As-You-Transact
                    @else
                        {{ $subscription->plan->name ?? 'Standard Subscription' }}
                    @endif
                </span>
                <span class="text-xs text-gray-400 block mt-1">
                    @if($tenant->billing_model === 'payg')
                        Zero monthly commitment • Tiny micro-fees only
                    @elseif($subscription && $subscription->plan)
                        ₦{{ number_format($subscription->amount_paid, 2) }} / {{ $subscription->plan->billing_interval }}
                    @else
                        Unlimited monthly access
                    @endif
                </span>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10">
                <form action="{{ route('admin.billing.switch-model') }}" method="POST">
                    @csrf
                    @if($tenant->billing_model === 'payg')
                        <input type="hidden" name="billing_model" value="subscription">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-white/10 hover:bg-white/20 text-white transition border border-white/10">
                            Switch to Monthly Subscription &rarr;
                        </button>
                    @else
                        <input type="hidden" name="billing_model" value="payg">
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 border border-amber-400/30 transition">
                            Switch to Pay-As-You-Transact Wallet &rarr;
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Card 3: Team Seats & Usage -->
        <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-3xl p-6 sm:p-7 shadow-xl border border-white/10 flex flex-col justify-between text-white">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block">Active Team Seats</span>
                <div class="mt-1 flex items-baseline space-x-2">
                    <span class="text-3xl font-extrabold text-white">{{ $activeSeats }}</span>
                    <span class="text-xs text-gray-400">
                        @if($tenant->billing_model === 'payg')
                            / Unlimited (₦20/action)
                        @else
                            / {{ $subscription->seats_purchased ?? 5 }} seats included
                        @endif
                    </span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-1.5 mt-3 overflow-hidden">
                    @php
                        $maxSeats = max(1, $subscription->seats_purchased ?? 5);
                        $percentage = min(100, round(($activeSeats / $maxSeats) * 100));
                    @endphp
                    <div class="bg-emerald-400 h-1.5 rounded-full" style="width: {{ $percentage }}%"></div>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-white/10 text-xs text-gray-400 flex items-center justify-between">
                <span>Public Storefront:</span>
                <span class="font-bold text-emerald-400">Active & Included FREE</span>
            </div>
        </div>

    </div>

    <!-- Pay-As-You-Transact Micro-Fee Ledger & Rate Sheet -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Micro-Rate Schedule -->
        <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-3xl p-6 shadow-xl border border-white/10 space-y-4 text-white">
            <h2 class="text-base font-extrabold text-white">Per-Service Micro-Rates</h2>
            <p class="text-xs text-gray-400">When using Pay-As-You-Transact mode, fees are deducted only when actions execute.</p>
            
            <div class="space-y-3 pt-2">
                <div class="p-3 rounded-xl bg-[#0a1612] border border-white/5 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">📄</span>
                        <span class="font-semibold text-gray-200">Smart Invoice Generated</span>
                    </div>
                    <span class="font-bold text-emerald-400">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_invoice_fee', '50') }}</span>
                </div>

                <div class="p-3 rounded-xl bg-[#0a1612] border border-white/5 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">📝</span>
                        <span class="font-semibold text-gray-200">Client Quotation / Proposal</span>
                    </div>
                    <span class="font-bold text-emerald-400">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_quote_fee', '30') }}</span>
                </div>

                <div class="p-3 rounded-xl bg-[#0a1612] border border-white/5 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">👥</span>
                        <span class="font-semibold text-gray-200">Team Member Daily Clock-in</span>
                    </div>
                    <span class="font-bold text-emerald-400">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_team_fee', '20') }}</span>
                </div>

                <div class="p-3 rounded-xl bg-[#0a1612] border border-white/5 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">💳</span>
                        <span class="font-semibold text-gray-200">Payment Collection (Inflow)</span>
                    </div>
                    <span class="font-bold text-emerald-400">{{ \Modules\Settings\Models\Setting::get('pricing_payg_inflow_fee', '1%') }}</span>
                </div>

                <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-base">🌐</span>
                        <span class="font-semibold text-emerald-200">Storefront & CRM Hub</span>
                    </div>
                    <span class="font-bold text-emerald-400">₦0 FREE</span>
                </div>
            </div>
        </div>

        <!-- Right: Recent Wallet Ledger Transactions -->
        <div class="lg:col-span-2 bg-[#10231C]/60 backdrop-blur-xl rounded-3xl p-6 shadow-xl border border-white/10 space-y-4 text-white">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div>
                    <h2 class="text-base font-extrabold text-white">Operating Wallet Activity Ledger</h2>
                    <p class="text-xs text-gray-400">Real-time log of wallet top-ups and micro-fee deductions.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-white/5 text-gray-400 uppercase font-bold">
                        <tr>
                            <th class="py-2.5 px-4">Action / Description</th>
                            <th class="py-2.5 px-4">Type</th>
                            <th class="py-2.5 px-4">Amount</th>
                            <th class="py-2.5 px-4">Balance After</th>
                            <th class="py-2.5 px-4">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($walletTransactions as $tx)
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="py-3 px-4">
                                <span class="font-semibold text-white block">{{ $tx->description }}</span>
                                @if($tx->reference)
                                <span class="font-mono text-[10px] text-gray-400 block">{{ $tx->reference }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $tx->type === 'credit' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30' }}">
                                    {{ $tx->type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold {{ $tx->type === 'credit' ? 'text-emerald-400' : 'text-gray-300' }}">
                                {{ $tx->type === 'credit' ? '+' : '-' }}₦{{ number_format($tx->amount, 2) }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-gray-300">
                                ₦{{ number_format($tx->balance_after, 2) }}
                            </td>
                            <td class="py-3 px-4 text-gray-400 text-[11px]">
                                {{ $tx->created_at->format('M d, Y h:ia') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400">
                                No wallet transactions recorded yet. Top up your wallet to start transacting.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Available Monthly & Annual Plans -->
    <div class="space-y-4">
        <div>
            <h2 class="text-lg font-bold text-white">Unlimited Monthly & Annual Plans</h2>
            <p class="text-xs text-gray-400">For scaling companies handling high daily transaction volumes seeking zero per-transaction fees.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($availablePlans as $plan)
            <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-3xl p-6 shadow-xl border {{ ($subscription && $subscription->plan_id === $plan->id && $tenant->billing_model === 'subscription') ? 'border-emerald-500 ring-2 ring-emerald-500/30' : 'border-white/10' }} flex flex-col justify-between text-white">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold px-2 py-0.5 rounded {{ $plan->billing_type === 'per_seat' ? 'bg-[#F2A900]/20 text-amber-300 border border-amber-400/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                            {{ $plan->billing_type === 'per_seat' ? 'Per-Seat Model' : 'Flat Tiered' }}
                        </span>
                        @if($subscription && $subscription->plan_id === $plan->id && $tenant->billing_model === 'subscription')
                        <span class="text-[11px] font-extrabold text-emerald-400">Active Plan</span>
                        @endif
                    </div>

                    <h3 class="text-xl font-extrabold text-white mt-3">{{ $plan->name }}</h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $plan->description }}</p>

                    <div class="mt-4 pt-3 border-t border-white/10">
                        <span class="text-2xl font-extrabold text-white">₦{{ number_format($plan->price, 0) }}</span>
                        <span class="text-xs text-gray-400">/ {{ $plan->billing_interval }}</span>
                        @if($plan->billing_type === 'per_seat' && $plan->price_per_user > 0)
                        <span class="block text-xs font-semibold text-emerald-400 mt-0.5">+ ₦{{ number_format($plan->price_per_user, 0) }}/extra seat</span>
                        @endif
                    </div>

                    <ul class="mt-4 space-y-2 text-xs text-gray-300">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Includes <strong>{{ $plan->included_users }} seats</strong></span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span><strong>Unlimited</strong> Invoices (₦0 per invoice)</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Public Storefront & Digital Website CMS</span>
                        </li>
                        @if($plan->enable_custom_domain)
                        <li class="flex items-center space-x-2 font-semibold text-emerald-300">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Custom Domain Integration Included</span>
                        </li>
                        @endif
                        @if($plan->enable_ai)
                        <li class="flex items-center space-x-2 font-semibold text-amber-300">
                            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            <span>Autonomous AI Workforce Suite ✨</span>
                        </li>
                        @endif
                    </ul>
                </div>

                <div class="mt-6 pt-4 border-t border-white/10">
                    <form action="{{ route('admin.billing.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" class="w-full py-2.5 rounded-xl text-xs font-bold {{ ($subscription && $subscription->plan_id === $plan->id && $tenant->billing_model === 'subscription') ? 'bg-emerald-600 text-white hover:bg-emerald-500' : 'bg-white/10 text-white hover:bg-white/20 border border-white/10' }} shadow-sm transition">
                            {{ ($subscription && $subscription->plan_id === $plan->id && $tenant->billing_model === 'subscription') ? 'Renew via Billway' : 'Activate Plan' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Billway Payment History -->
    <div class="bg-[#10231C]/60 backdrop-blur-xl rounded-3xl shadow-xl border border-white/10 overflow-hidden text-white">
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between">
            <h2 class="text-base font-bold text-white">Billway Transaction & Top-up Receipts</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-white/5 text-xs font-bold text-gray-400 uppercase">
                    <tr>
                        <th class="py-3 px-6">Reference</th>
                        <th class="py-3 px-6">Amount</th>
                        <th class="py-3 px-6">Gateway</th>
                        <th class="py-3 px-6">Status</th>
                        <th class="py-3 px-6">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($recentPayments as $payment)
                    <tr class="hover:bg-white/[0.02] transition">
                        <td class="py-3.5 px-6 font-mono text-xs font-semibold text-gray-300">{{ $payment->reference }}</td>
                        <td class="py-3.5 px-6 font-bold text-white">₦{{ number_format($payment->amount, 2) }}</td>
                        <td class="py-3.5 px-6 text-xs text-gray-400 uppercase">{{ $payment->gateway }}</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold {{ $payment->status === 'successful' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-400/30' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-xs text-gray-400">{{ $payment->created_at->format('M d, Y h:ia') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-xs text-gray-400">No payment receipts recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Up Wallet Modal (Alpine.js) -->
    <div x-show="showTopupModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-md flex items-center justify-center p-4" 
         style="display: none;">
        
        <div @click.away="showTopupModal = false" 
             class="bg-[#0c1a15] rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-white/10 text-left text-white">
            <div class="flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center space-x-2">
                    <span class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-400/30 flex items-center justify-center font-bold text-sm">⚡</span>
                    <h3 class="text-lg font-extrabold text-white">Top Up Operating Wallet</h3>
                </div>
                <button type="button" @click="showTopupModal = false" class="text-gray-400 hover:text-white text-xl font-bold">&times;</button>
            </div>

            <p class="text-xs text-gray-400 leading-relaxed">
                Fund your operational wallet via Billway bank transfer or card. Funds are credited instantly and never expire.
            </p>

            <form action="{{ route('admin.billing.topup') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Quick Amount Badges -->
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase text-gray-300">Select Amount</label>
                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" @click="topupAmount = 1000" 
                                :class="topupAmount === 1000 ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10'"
                                class="py-2 rounded-xl text-xs font-bold border transition">₦1,000</button>
                        <button type="button" @click="topupAmount = 2500" 
                                :class="topupAmount === 2500 ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10'"
                                class="py-2 rounded-xl text-xs font-bold border transition">₦2,500</button>
                        <button type="button" @click="topupAmount = 5000" 
                                :class="topupAmount === 5000 ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10'"
                                class="py-2 rounded-xl text-xs font-bold border transition">₦5,000</button>
                        <button type="button" @click="topupAmount = 10000" 
                                :class="topupAmount === 10000 ? 'bg-emerald-600 text-white border-emerald-500' : 'bg-white/5 text-gray-300 border-white/10 hover:bg-white/10'"
                                class="py-2 rounded-xl text-xs font-bold border transition">₦10,000</button>
                    </div>
                </div>

                <!-- Custom Amount Input -->
                <div class="space-y-1.5">
                    <label for="amount" class="block text-xs font-bold uppercase text-gray-300">Amount (NGN)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400 font-bold">₦</span>
                        <input type="number" id="amount" name="amount" x-model="topupAmount" min="500" required 
                               class="w-full pl-8 pr-4 py-3 rounded-xl bg-[#08110e] border border-white/10 text-sm font-bold text-white focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    </div>
                    <span class="text-[11px] text-gray-400">Minimum top-up: ₦500</span>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full py-3.5 rounded-xl text-sm font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-lg shadow-amber-500/20 transition">
                        Proceed to Billway Checkout &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
