<x-home::layouts.master>
    <!-- Pricing Hero Section (Dark Mode Design System) -->
    <section style="background-color: #08110e !important;" class="relative overflow-hidden text-white pt-20 pb-20 lg:pt-28 lg:pb-24" 
             x-data="{ 
                 mode: 'payg', 
                 annual: false,
                 calcInvoices: 15,
                 calcQuotes: 8,
                 calcTeam: 2,
                 calcWorkDays: 22,
                 invoiceFee: {{ (int) \Modules\Settings\Models\Setting::get('pricing_payg_invoice_fee', 50) }},
                 quoteFee: {{ (int) \Modules\Settings\Models\Setting::get('pricing_payg_quote_fee', 30) }},
                 teamFee: {{ (int) \Modules\Settings\Models\Setting::get('pricing_payg_team_fee', 20) }},
                 get totalSpend() {
                     return (this.calcInvoices * this.invoiceFee) + (this.calcQuotes * this.quoteFee) + (this.calcTeam * this.teamFee * this.calcWorkDays);
                 },
                 get traditionalSaaS() {
                     return 15000;
                 },
                 get monthlySavings() {
                     return Math.max(0, this.traditionalSaaS - this.totalSpend);
                 }
             }">
        <!-- Subtle Glow Effects -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[750px] h-[750px] bg-[#0E5E4A]/20 rounded-full blur-[140px] pointer-events-none"></div>
        <div class="absolute top-1/2 right-10 w-[400px] h-[400px] bg-[#F2A900]/10 rounded-full blur-[120px] pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-amber-400/10 border border-amber-400/20 text-xs font-bold text-amber-300">
                {{ \Modules\Settings\Models\Setting::get('home_pricing_badge', 'Flexible Pricing for African Business') }}
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                {{ \Modules\Settings\Models\Setting::get('home_pricing_title', 'Fair, Transparent Pricing Built for Africa') }}<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-[#F2A900] to-teal-200">
                    Pay Only When You Do Business.
                </span>
            </h1>
            <p class="text-base sm:text-lg text-gray-300 max-w-3xl mx-auto font-normal leading-relaxed">
                {{ \Modules\Settings\Models\Setting::get('home_pricing_subtitle', 'Choose how you pay: top up an operating wallet and pay small micro-fees per transaction, or pick an unlimited flat monthly plan for scaling teams.') }}
            </p>

            <!-- Dual Pricing Paradigm Switcher -->
            <div class="pt-6 flex flex-col sm:flex-row items-center justify-center gap-3">
                <div class="inline-flex p-1.5 rounded-2xl bg-white/[0.06] border border-white/10 backdrop-blur-md shadow-2xl">
                    <button type="button" 
                            @click="mode = 'payg'"
                            :class="mode === 'payg' ? 'bg-[#0E5E4A] text-white shadow-lg border border-emerald-400/40' : 'text-gray-400 hover:text-white'"
                            class="flex items-center space-x-2 px-5 py-3 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-200">
                        <span class="w-2 h-2 rounded-full bg-[#F2A900] animate-pulse"></span>
                        <span>⚡ Pay-As-You-Transact (Micro-Fees)</span>
                        <span class="px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[10px] font-black uppercase hidden sm:inline">The African Model</span>
                    </button>
                    <button type="button" 
                            @click="mode = 'subscription'"
                            :class="mode === 'subscription' ? 'bg-[#0E5E4A] text-white shadow-lg border border-emerald-400/40' : 'text-gray-400 hover:text-white'"
                            class="flex items-center space-x-2 px-5 py-3 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-200">
                        <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span>🏢 Unlimited Flat Monthly Plans</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODE 1: PAY-AS-YOU-TRANSACT (MICRO-FEES)   -->
        <!-- ========================================== -->
        <div x-show="mode === 'payg'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 space-y-12">
            
            <!-- PAYG Explainer Hero Card -->
            <div class="bg-gradient-to-r from-[#10231C] via-[#0b1b16] to-[#10231C] rounded-3xl p-8 sm:p-10 border border-emerald-500/30 shadow-2xl text-left space-y-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 pb-6 border-b border-white/10">
                    <div class="space-y-2">
                        <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-[#F2A900]/20 text-[#F2A900] text-xs font-bold uppercase tracking-wider">
                            <span>⚡</span>
                            <span>{{ \Modules\Settings\Models\Setting::get('pricing_payg_badge', '⚡ The African Model • Zero Monthly Lock-in') }}</span>
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white">
                            {{ \Modules\Settings\Models\Setting::get('pricing_payg_title', 'Pay-As-You-Transact (Operating Wallet)') }}
                        </h2>
                        <p class="text-sm text-gray-300 max-w-3xl leading-relaxed">
                            {{ \Modules\Settings\Models\Setting::get('pricing_payg_subtitle', 'No monthly commitments. Top up your operating wallet from ₦500 and only pay tiny micro-fees when you actually issue an invoice or perform an operation. Zero usage = ₦0.') }}
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ route('register') }}?plan=pay_as_you_go" target="_blank" rel="noopener" class="inline-flex items-center space-x-2 px-8 py-4 rounded-2xl text-sm font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-xl transition transform hover:-translate-y-0.5">
                            <span>Activate Operating Wallet</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 3 Steps How It Works -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-[#0E5E4A] text-emerald-300 font-extrabold flex items-center justify-center text-sm shadow-md border border-emerald-400/30">
                            1
                        </div>
                        <h3 class="text-base font-bold text-white">Top Up from ₦500</h3>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            Transfer any amount via instant Billway bank transfer or card into your dedicated Bizpaddi operating wallet.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-[#0E5E4A] text-emerald-300 font-extrabold flex items-center justify-center text-sm shadow-md border border-emerald-400/30">
                            2
                        </div>
                        <h3 class="text-base font-bold text-white">Execute Operations</h3>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            Generate smart invoices, issue client quotations, track projects, and record team work sessions freely.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 space-y-2">
                        <div class="w-10 h-10 rounded-xl bg-[#F2A900] text-[#08110e] font-black flex items-center justify-center text-sm shadow-md">
                            3
                        </div>
                        <h3 class="text-base font-bold text-white">Micro-Fee Deducted</h3>
                        <p class="text-xs text-gray-300 leading-relaxed">
                            Only a tiny fee (e.g. ₦50) is deducted per action. Unused balance never expires. Zero monthly usage = ₦0 spent.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Transparent Micro-Fee Rates Grid -->
            <div>
                <div class="text-center space-y-2 mb-8">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#F2A900]">Simple Micro-Rates</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white">Transparent Per-Service Fee Schedule</h2>
                    <p class="text-xs sm:text-sm text-gray-400">Everything is clearly priced. No hidden deductions or surprise charges.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Rate Card 1: Invoice -->
                    <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/40 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A]/40 flex items-center justify-center text-2xl border border-emerald-400/20">
                                📄
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Smart Invoicing</span>
                                <h3 class="text-lg font-extrabold text-white">Invoice Generation & Tracking</h3>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed">
                                Automated payment tracking, dedicated virtual account on invoice, PDF receipt, and payment reminders.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-3xl font-black text-white">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_invoice_fee', '50') }}</span>
                            <span class="text-xs text-gray-400">/ invoice generated</span>
                        </div>
                    </div>

                    <!-- Rate Card 2: Quote -->
                    <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/40 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A]/40 flex items-center justify-center text-2xl border border-emerald-400/20">
                                📝
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Quotations & Bids</span>
                                <h3 class="text-lg font-extrabold text-white">Client Quotes & Proposals</h3>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed">
                                Professional branded proposals, 1-click client digital acceptance, and automatic conversion into an invoice.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-3xl font-black text-white">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_quote_fee', '30') }}</span>
                            <span class="text-xs text-gray-400">/ quote created</span>
                        </div>
                    </div>

                    <!-- Rate Card 3: Team Operations -->
                    <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/40 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A]/40 flex items-center justify-center text-2xl border border-emerald-400/20">
                                👥
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Team Operations</span>
                                <h3 class="text-lg font-extrabold text-white">Staff Clock-in & Standup</h3>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed">
                                Staff attendance verification, daily work log submission, task checklist execution, and output scorecards.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-3xl font-black text-white">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_team_fee', '20') }}</span>
                            <span class="text-xs text-gray-400">/ active user/day</span>
                        </div>
                    </div>

                    <!-- Rate Card 4: Inflow / Collection -->
                    <div class="p-6 rounded-3xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/40 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A]/40 flex items-center justify-center text-2xl border border-emerald-400/20">
                                💳
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Payment Collection</span>
                                <h3 class="text-lg font-extrabold text-white">Customer Payment Inflow</h3>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed">
                                Instant automated bank transfer reconciliation, virtual bank account settlement directly into your bank.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-2xl font-black text-amber-300">{{ \Modules\Settings\Models\Setting::get('pricing_payg_inflow_fee', '1% (Capped at ₦1,000)') }}</span>
                            <span class="text-xs text-gray-400">per collection</span>
                        </div>
                    </div>

                    <!-- Rate Card 5: Free Digital Storefront -->
                    <div class="p-6 rounded-3xl bg-[#0E5E4A]/20 border border-emerald-500/40 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A] flex items-center justify-center text-2xl">
                                🌐
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">Always Included</span>
                                <h3 class="text-lg font-extrabold text-white">Public Digital Storefront</h3>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed">
                                Dedicated business website at `slug.bizpaddi.com`. Showcase services, accept client bookings, and display catalog.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-2xl font-black text-emerald-400">₦0 FREE</span>
                            <span class="text-xs text-gray-400">Included with wallet</span>
                        </div>
                    </div>

                    <!-- Rate Card 6: Free CRM & Financial Ledger -->
                    <div class="p-6 rounded-3xl bg-[#0E5E4A]/20 border border-emerald-500/40 transition flex flex-col justify-between space-y-4">
                        <div class="space-y-3">
                            <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A] flex items-center justify-center text-2xl">
                                📊
                            </div>
                            <div>
                                <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-400">Always Included</span>
                                <h3 class="text-lg font-extrabold text-white">CRM & Cash Flow Ledger</h3>
                            </div>
                            <p class="text-xs text-gray-300 leading-relaxed">
                                Unlimited client directory, interaction history, revenue analytics, cash flow tracking, and income logs.
                            </p>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-2xl font-black text-emerald-400">₦0 FREE</span>
                            <span class="text-xs text-gray-400">Included with wallet</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interactive Live Savings Calculator -->
            <div class="bg-gradient-to-b from-[#10231C] to-[#08110e] rounded-3xl p-8 sm:p-12 border border-white/15 shadow-2xl">
                <div class="max-w-4xl mx-auto space-y-8">
                    <div class="text-center space-y-2">
                        <span class="text-xs font-black uppercase tracking-widest text-[#F2A900]">Live Savings Calculator</span>
                        <h2 class="text-2xl sm:text-4xl font-extrabold text-white">See How Much You Save vs Traditional SaaS</h2>
                        <p class="text-xs sm:text-sm text-gray-300">
                            Adjust the sliders below to match your real business volume this month.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
                        <!-- Slider 1 -->
                        <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10 space-y-3">
                            <div class="flex items-center justify-between text-xs font-bold text-gray-300">
                                <span>Monthly Invoices</span>
                                <span class="text-amber-300 font-extrabold text-base" x-text="calcInvoices"></span>
                            </div>
                            <input type="range" min="1" max="100" step="1" x-model="calcInvoices" 
                                   class="w-full accent-[#F2A900] cursor-pointer">
                            <div class="flex justify-between text-[10px] text-gray-500">
                                <span>1 invoice</span>
                                <span>100 invoices</span>
                            </div>
                        </div>

                        <!-- Slider 2 -->
                        <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10 space-y-3">
                            <div class="flex items-center justify-between text-xs font-bold text-gray-300">
                                <span>Monthly Quotes</span>
                                <span class="text-amber-300 font-extrabold text-base" x-text="calcQuotes"></span>
                            </div>
                            <input type="range" min="0" max="50" step="1" x-model="calcQuotes" 
                                   class="w-full accent-[#F2A900] cursor-pointer">
                            <div class="flex justify-between text-[10px] text-gray-500">
                                <span>0 quotes</span>
                                <span>50 quotes</span>
                            </div>
                        </div>

                        <!-- Slider 3 -->
                        <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/10 space-y-3">
                            <div class="flex items-center justify-between text-xs font-bold text-gray-300">
                                <span>Active Team Members</span>
                                <span class="text-amber-300 font-extrabold text-base" x-text="calcTeam"></span>
                            </div>
                            <input type="range" min="1" max="15" step="1" x-model="calcTeam" 
                                   class="w-full accent-[#F2A900] cursor-pointer">
                            <div class="flex justify-between text-[10px] text-gray-500">
                                <span>1 member</span>
                                <span>15 members</span>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Comparison Result Box -->
                    <div class="p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-[#0E5E4A]/50 via-[#0a382c]/40 to-[#0E5E4A]/50 border-2 border-emerald-400/50 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="space-y-1 text-center md:text-left">
                            <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-300 block">Your Estimated Monthly Spend</span>
                            <div class="flex items-baseline justify-center md:justify-start space-x-2">
                                <span class="text-4xl sm:text-5xl font-black text-white" x-text="'₦' + totalSpend.toLocaleString()"></span>
                                <span class="text-xs text-gray-400">/ month</span>
                            </div>
                            <p class="text-xs text-gray-300">
                                You only pay this when operations actually occur. Zero usage = ₦0.
                            </p>
                        </div>

                        <div class="text-center md:text-right space-y-2">
                            <div class="inline-flex items-center space-x-2 px-4 py-1.5 rounded-full bg-[#F2A900] text-[#08110e] text-xs font-black uppercase shadow-lg">
                                <span>Save</span>
                                <span x-text="'₦' + monthlySavings.toLocaleString() + ' / month'"></span>
                            </div>
                            <p class="text-xs text-gray-300">
                                Compared to fixed ₦15,000/mo traditional SaaS plans
                            </p>
                        </div>
                    </div>

                    <div class="text-center pt-2">
                        <a href="{{ route('register') }}?plan=pay_as_you_go" target="_blank" rel="noopener" class="inline-flex items-center space-x-2 px-9 py-4 rounded-2xl text-base font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-2xl transition transform hover:-translate-y-1">
                            <span>Get Started with Pay-As-You-Transact Wallet</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                        <p class="text-[11px] text-gray-400 mt-2">Zero setup fee • Top up anytime from ₦500 • Instant activation</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- ============================================== -->
        <!-- MODE 2: UNLIMITED MONTHLY & ANNUAL PLANS       -->
        <!-- ============================================== -->
        <div x-show="mode === 'subscription'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 space-y-12">
            
            <!-- Monthly / Annual Toggle -->
            <div class="flex items-center justify-center space-x-4">
                <span :class="!annual ? 'text-white font-bold' : 'text-gray-400'" class="text-sm transition">Monthly Billing</span>
                <button type="button" @click="annual = !annual" class="relative inline-flex h-7 w-14 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out bg-[#0E5E4A] focus:outline-none">
                    <span :class="annual ? 'translate-x-7 bg-[#F2A900]' : 'translate-x-0 bg-white'" class="pointer-events-none inline-block h-6 w-6 transform rounded-full shadow ring-0 transition duration-200 ease-in-out"></span>
                </button>
                <span :class="annual ? 'text-white font-bold' : 'text-gray-400'" class="text-sm transition flex items-center space-x-1.5">
                    <span>Annual Billing</span>
                    <span class="px-2 py-0.5 rounded-full bg-[#F2A900] text-[#08110e] text-[10px] font-black uppercase">Save 20%</span>
                </span>
            </div>

            <!-- Subscription Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch text-left">
                @foreach($subscriptionPlans as $plan)
                <div class="rounded-3xl p-8 flex flex-col justify-between transition-all duration-200 {{ $plan->enable_ai ? 'bg-[#0E5E4A]/30 text-white shadow-2xl relative border-2 border-emerald-400/60' : 'bg-white/[0.03] text-white border border-white/10 hover:border-white/20' }}">
                    @if($plan->enable_ai)
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-[#F2A900] text-[#08110e] text-xs font-black uppercase shadow-md">
                        Most Comprehensive
                    </div>
                    @endif

                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                {{ $plan->billing_type === 'per_seat' ? 'Per-Seat Model' : 'Flat Tiered' }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-extrabold mt-2 text-white">{{ $plan->name }}</h3>
                        <p class="text-xs mt-2 min-h-[36px] text-gray-300">{{ $plan->description }}</p>

                        <div class="mt-6 pt-4 border-t border-white/10">
                            <div class="flex items-baseline space-x-1">
                                <span class="text-4xl font-black text-white" x-text="annual ? '₦' + Math.round({{ $plan->price }} * 0.8).toLocaleString() : '₦{{ number_format($plan->price, 0) }}'"></span>
                                <span class="text-xs text-gray-400">/ {{ $plan->billing_interval }}</span>
                            </div>
                            @if($plan->billing_type === 'per_seat' && $plan->price_per_user > 0)
                            <span class="block text-xs font-bold text-amber-300 mt-1.5">+ ₦{{ number_format($plan->price_per_user, 0) }} per extra user seat</span>
                            @endif
                        </div>

                        <ul class="mt-8 space-y-3.5 text-xs text-gray-300">
                            <li class="flex items-center space-x-2.5">
                                <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Includes <strong>{{ $plan->included_users }} active team seats</strong></span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span><strong>Unlimited</strong> Invoices (₦0 per invoice)</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span><strong>Unlimited</strong> Quotes & Estimates</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Drag-and-Drop Kanban Task Pipelines</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Staff Remote Attendance & Clock-in</span>
                            </li>
                            <li class="flex items-center space-x-2.5">
                                <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Dedicated Storefront (`yourname.bizpaddi.com`)</span>
                            </li>
                            @if($plan->enable_custom_domain)
                            <li class="flex items-center space-x-2.5 font-bold text-amber-300">
                                <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Custom Domain Integration Included</span>
                            </li>
                            @endif
                            @if($plan->enable_ai)
                            <li class="flex items-center space-x-2.5 font-bold text-amber-200">
                                <svg class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Autonomous AI Employees & Copilots ✨</span>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <div class="mt-10 pt-4">
                        <a href="{{ route('register') }}?plan={{ $plan->code }}" target="_blank" rel="noopener" class="block w-full py-4 rounded-2xl text-center text-sm font-extrabold {{ $plan->enable_ai ? 'bg-[#F2A900] text-[#08110e] hover:bg-amber-300' : 'bg-[#0E5E4A] text-white hover:bg-[#0c4e3e]' }} shadow-xl transition transform hover:-translate-y-0.5 border border-emerald-400/30">
                            Select {{ $plan->name }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Side-by-Side Model Comparison Table (Dark Mode) -->
    <section class="py-20 bg-[#0b1512] text-white border-t border-white/10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#F2A900]">At a Glance</span>
                <h2 class="text-3xl font-extrabold text-white">Compare Billing Models</h2>
                <p class="text-xs sm:text-sm text-gray-400">Choose the model that fits your transaction frequency and team size.</p>
            </div>

            <div class="overflow-x-auto rounded-3xl border border-white/10 bg-white/[0.02]">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead>
                        <tr class="border-b border-white/10 bg-white/[0.04] text-gray-300">
                            <th class="p-5 font-bold">Feature / Capability</th>
                            <th class="p-5 font-bold text-center text-amber-300">⚡ Pay-As-You-Transact</th>
                            <th class="p-5 font-bold text-center">Starter Monthly</th>
                            <th class="p-5 font-bold text-center text-emerald-400">Enterprise AI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-gray-300">
                        <tr>
                            <td class="p-5 font-semibold text-white">Monthly Commitment</td>
                            <td class="p-5 text-center font-black text-amber-300">₦0 (Zero Lock-in)</td>
                            <td class="p-5 text-center">₦15,000 / month</td>
                            <td class="p-5 text-center font-bold text-emerald-400">₦85,000 / month</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-white">Invoice Creation Cost</td>
                            <td class="p-5 text-center text-amber-300 font-bold">₦50 / invoice</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">Unlimited (₦0)</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">Unlimited (₦0)</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-white">Quotation & Proposal Cost</td>
                            <td class="p-5 text-center text-amber-300 font-bold">₦30 / quote</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">Unlimited (₦0)</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">Unlimited (₦0)</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-white">Team Member Clock-in</td>
                            <td class="p-5 text-center text-amber-300 font-bold">₦20 / user action</td>
                            <td class="p-5 text-center">5 Included Seats</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">100 Included Seats</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-white">Automated Payment Tracking & Receipts</td>
                            <td class="p-5 text-center text-emerald-400">✓ Included</td>
                            <td class="p-5 text-center text-emerald-400">✓ Included</td>
                            <td class="p-5 text-center text-emerald-400">✓ Included</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-white">Public Digital Storefront (`slug.bizpaddi.com`)</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">✓ Included FREE</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">✓ Included FREE</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">✓ Included FREE</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-white">Custom Domain Integration</td>
                            <td class="p-5 text-center text-gray-500">—</td>
                            <td class="p-5 text-center text-gray-500">—</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">✓ Included</td>
                        </tr>
                        <tr>
                            <td class="p-5 font-semibold text-white">Autonomous AI Workforce</td>
                            <td class="p-5 text-center text-gray-500">—</td>
                            <td class="p-5 text-center text-gray-500">—</td>
                            <td class="p-5 text-center text-emerald-400 font-bold">✓ Full AI Suite</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Pricing FAQs (African Context) -->
    <section class="py-20 bg-[#08110e] text-white border-t border-white/10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#F2A900]">Got Questions?</span>
                <h2 class="text-3xl font-extrabold text-white">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4 text-xs sm:text-sm">
                <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 space-y-2">
                    <h3 class="font-extrabold text-white text-base">How does the Pay-As-You-Transact operating wallet work?</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Just like a fintech app (Opay, PalmPay, Moniepoint), you fund your business operating wallet with any amount from ₦500 via instant bank transfer. When you generate an invoice, ₦50 is deducted. When you issue a quote, ₦30 is deducted. If you do not use the software in a particular week or month, you spend ₦0. Your wallet balance never expires!
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 space-y-2">
                    <h3 class="font-extrabold text-white text-base">Can I switch between Pay-As-You-Transact and Flat Monthly anytime?</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Yes, absolutely. If your transaction volume grows and you begin issuing dozens of invoices weekly, you can switch to an unlimited flat monthly plan from your billing dashboard with 1 click to eliminate per-transaction fees.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 space-y-2">
                    <h3 class="font-extrabold text-white text-base">What happens if my wallet balance runs out?</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Your workspace remains active and your clients can still view and pay pending invoices. When you need to generate a new invoice or perform an operation, the system will prompt you with your dedicated Billway virtual account number to top up instantly.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 space-y-2">
                    <h3 class="font-extrabold text-white text-base">Are there any hidden setup fees or surprise recurring debits?</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Never. There are zero account opening fees, zero maintenance charges, and no automated bank card debits unless you explicitly renew a monthly subscription.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Bottom Conversion CTA -->
    <section class="py-20 bg-gradient-to-r from-[#0E5E4A] via-[#093d30] to-[#08110e] text-white text-center border-t border-white/10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
                Launch Your Business Workspace Today
            </h2>
            <p class="text-base sm:text-lg text-gray-200 max-w-2xl mx-auto">
                No credit card required. Instant account activation in 60 seconds.
            </p>
            <div class="pt-4">
                <a href="{{ route('register') }}" target="_blank" rel="noopener" class="inline-flex items-center space-x-2 px-9 py-4 rounded-2xl text-base font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-2xl transition transform hover:-translate-y-1">
                    <span>Get Started Now</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </section>
</x-home::layouts.master>
