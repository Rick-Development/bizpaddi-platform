<x-home::layouts.master>
    <!-- 1. Hero Section (Unified Dark Mode) -->
    <!-- 1. Hero Section (Unified Dark Mode & Centered Balance) -->
    <section style="background-color: #08110e !important;" class="relative overflow-hidden text-white min-h-[75vh] flex items-center justify-center py-20 lg:py-28">
        <!-- Ambient Background Glows (Centered) -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[#0E5E4A]/20 rounded-full blur-[150px] pointer-events-none"></div>
        <div class="absolute top-1/2 right-1/4 -translate-y-1/2 w-[400px] h-[400px] bg-[#F2A900]/10 rounded-full blur-[130px] pointer-events-none"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="text-center max-w-4xl mx-auto space-y-8 sm:space-y-10">
        
                <!-- Main Headline -->
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.12]">
                    {{ \Modules\Settings\Models\Setting::get('home_hero_headline_1', 'Run Your Entire Business.') }}<br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-[#F2A900] to-teal-200">
                        {{ \Modules\Settings\Models\Setting::get('home_hero_headline_gradient', 'Invoicing, Projects & Team') }}
                    </span>
                    {{ \Modules\Settings\Models\Setting::get('home_hero_headline_2', 'with One Software.') }}
                </h1>

                <!-- Subheadline -->
                <p class="text-base sm:text-lg lg:text-xl text-gray-300 leading-relaxed max-w-2xl mx-auto font-normal">
                    {{ \Modules\Settings\Models\Setting::get('home_hero_subheadline', 'Bizpaddi gives you smart invoicing with automated payment tracking, Kanban project pipelines, staff payroll, omnichannel CRM, and a client storefront—built for African commerce.') }}
                </p> 

                <!-- CTA Buttons -->
                <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-5">
                    <a href="{{ route('register') }}" target="_blank" rel="noopener" class="w-full sm:w-auto px-9 py-4 rounded-2xl text-base font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-xl hover:shadow-emerald-900/50 transform hover:-translate-y-0.5 transition duration-200 flex items-center justify-center space-x-2 border border-emerald-400/40">
                        <span>{{ \Modules\Settings\Models\Setting::get('home_hero_primary_cta_text', 'Get Started Now') }}</span>
                        <svg class="w-5 h-5 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                    <a href="{{ route('pricing') }}" style="background-color: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2);" class="w-full sm:w-auto px-8 py-4 rounded-2xl text-base font-bold text-white hover:bg-white/[0.14] transition duration-200 flex items-center justify-center space-x-2">
                        <span>{{ \Modules\Settings\Models\Setting::get('home_hero_secondary_cta_text', 'View Pricing') }}</span>
                        <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <!-- Trust Micro-Badges -->
                <div class="flex flex-wrap items-center justify-center gap-y-3 gap-x-8 text-xs sm:text-sm text-gray-400 pt-4">
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ \Modules\Settings\Models\Setting::get('home_trust_badge_1', 'No credit card required') }}</span>
                    </span>
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ \Modules\Settings\Models\Setting::get('home_trust_badge_2', 'Live workspace in 60 seconds') }}</span>
                    </span>
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ \Modules\Settings\Models\Setting::get('home_trust_badge_3', 'Dedicated storefront included') }}</span>
                    </span>
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ \Modules\Settings\Models\Setting::get('home_trust_badge_4', '256-Bit SSL Encrypted') }}</span>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Trust Metrics Bar (Dark Mode) -->
    <section style="background-color: #060c0a !important;" class="border-y border-white/10 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <span class="text-3xl sm:text-4xl font-extrabold text-[#F2A900] block">{{ \Modules\Settings\Models\Setting::get('home_metric_1_val', '₦500M+') }}</span>
                    <span class="text-xs sm:text-sm text-gray-400 mt-1 block font-medium">{{ \Modules\Settings\Models\Setting::get('home_metric_1_label', 'Invoices & Collections Settled') }}</span>
                </div>
                <div>
                    <span class="text-3xl sm:text-4xl font-extrabold text-white block">{{ \Modules\Settings\Models\Setting::get('home_metric_2_val', 'Instant') }}</span>
                    <span class="text-xs sm:text-sm text-gray-400 mt-1 block font-medium">{{ \Modules\Settings\Models\Setting::get('home_metric_2_label', 'Payment Auto-Reconciliation') }}</span>
                </div>
                <div>
                    <span class="text-3xl sm:text-4xl font-extrabold text-emerald-400 block">{{ \Modules\Settings\Models\Setting::get('home_metric_3_val', '99.98%') }}</span>
                    <span class="text-xs sm:text-sm text-gray-400 mt-1 block font-medium">{{ \Modules\Settings\Models\Setting::get('home_metric_3_label', 'Enterprise Platform Uptime') }}</span>
                </div>
                <div>
                    <span class="text-3xl sm:text-4xl font-extrabold text-white block">{{ \Modules\Settings\Models\Setting::get('home_metric_4_val', '10-in-1') }}</span>
                    <span class="text-xs sm:text-sm text-gray-400 mt-1 block font-medium">{{ \Modules\Settings\Models\Setting::get('home_metric_4_label', 'All Your Core Tools in One Place') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Before vs After Bizpaddi (Dark Mode) -->
    <section style="background-color: #08110e !important;" class="py-24 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#F2A900]">{{ \Modules\Settings\Models\Setting::get('home_advantage_badge', 'The Clear Advantage') }}</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">{{ \Modules\Settings\Models\Setting::get('home_advantage_title', 'Why Growing Businesses Switch to Bizpaddi') }}</h2>
                <p class="text-sm sm:text-base text-gray-300">{{ \Modules\Settings\Models\Setting::get('home_advantage_subtitle', 'See the real operational difference between managing fragmented tools and running on an all-in-one business management platform.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch max-w-5xl mx-auto">
                <!-- The Old Way (Chaos) -->
                <div class="bg-red-950/20 border border-red-500/20 rounded-3xl p-8 space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-red-500/20 text-red-400 flex items-center justify-center font-bold">
                            ✕
                        </div>
                        <h3 class="text-xl font-bold text-red-200">The Fragmented Way (Chaos)</h3>
                    </div>

                    <ul class="space-y-4 text-xs sm:text-sm text-gray-300">
                        <li class="flex items-start space-x-3">
                            <span class="text-red-400 font-bold">•</span>
                            <span><strong>Chasing Bank Receipts:</strong> Customers send screenshots on WhatsApp; you spend hours cross-referencing bank statements.</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="text-red-400 font-bold">•</span>
                            <span><strong>Scattered Subscriptions:</strong> Paying multiple separate bills for invoicing, task boards, payroll, and website hosting that quickly add up every month.</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="text-red-400 font-bold">•</span>
                            <span><strong>Disorganized Project Deliverables:</strong> Client tasks scattered between email, WhatsApp threads, and spreadsheets with missed deadlines.</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="text-red-400 font-bold">•</span>
                            <span><strong>Manual Payroll Headaches:</strong> Clunky salary calculations done manually with zero payslips and messy bank transfers.</span>
                        </li>
                    </ul>
                </div>

                <!-- The Bizpaddi Way (High-Velocity Growth) -->
                <div class="bg-[#0E5E4A]/30 border-2 border-emerald-400/40 rounded-3xl p-8 space-y-6 relative overflow-hidden shadow-2xl">
                    <div class="absolute top-0 right-0 px-4 py-1.5 bg-[#F2A900] text-[#08110e] text-xs font-black uppercase rounded-bl-2xl">
                        Recommended
                    </div>

                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold">
                            ✓
                        </div>
                        <h3 class="text-xl font-bold text-white">The Bizpaddi Way (Unified Platform)</h3>
                    </div>

                    <ul class="space-y-4 text-xs sm:text-sm text-gray-200">
                        <li class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">•</span>
                            <span><strong>Instant Auto-Reconciliation:</strong> Invoices automatically track incoming payments. When your customer pays, the portal immediately marks it Paid and generates a receipt.</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">•</span>
                            <span><strong>Predictable Local Pricing:</strong> Replace multiple separate subscriptions with one unified workspace starting at ₦15,000/month.</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">•</span>
                            <span><strong>Visual Kanban Task Control:</strong> Live sprint boards, work session timers, and QA approvals keep every project transparent and on-schedule.</span>
                        </li>
                        <li class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold">•</span>
                            <span><strong>Automated Payroll & Airtime:</strong> Automated salary calculation, instant digital payslips, and airtime/data stipends for your staff.</span>
                        </li>
                    </ul>

                    <div class="pt-2">
                        <a href="{{ route('register') }}" target="_blank" rel="noopener" class="block w-full py-3.5 rounded-xl bg-[#F2A900] hover:bg-amber-300 text-[#08110e] font-extrabold text-sm text-center shadow-lg transition">
                            Digitize Your Business With Bizpaddi &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Bento Features Grid (Dark Mode All Through) -->
    <section style="background-color: #0b1512 !important;" class="py-24 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400">{{ \Modules\Settings\Models\Setting::get('home_features_badge', 'Built for High-Growth Companies') }}</span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">{{ \Modules\Settings\Models\Setting::get('home_features_title', 'Everything Your Business Needs to Scale Digitally') }}</h2>
                <p class="text-sm sm:text-base text-gray-300">{{ \Modules\Settings\Models\Setting::get('home_features_subtitle', 'Built to handle online tech agencies, retail distributors, consultants, and offline companies expanding into digital operations.') }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bento 1: Smart Invoicing -->
                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-3xl p-8 shadow-sm border border-white/10 hover:border-white/20 transition space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A]/30 text-emerald-400 flex items-center justify-center font-bold text-2xl border border-emerald-500/20">
                        💳
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ \Modules\Settings\Models\Setting::get('home_feature_1_title', 'Smart Invoicing & Instant Collections') }}</h3>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        {{ \Modules\Settings\Models\Setting::get('home_feature_1_desc', 'Generate professional estimates and recurring invoices in seconds. Every invoice comes with automated payment tracking and real-time reconciliation.') }}
                    </p>
                    <ul class="text-xs text-gray-400 space-y-1.5 pt-2">
                        <li>• Multi-currency support (NGN, USD, GBP)</li>
                        <li>• 1-click quote-to-invoice conversion</li>
                        <li>• Automated email payment reminders & receipts</li>
                    </ul>
                </div>

                <!-- Bento 2: Projects & Kanban -->
                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-3xl p-8 shadow-sm border border-white/10 hover:border-white/20 transition space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-[#F2A900] flex items-center justify-center font-bold text-2xl border border-amber-500/20">
                        📊
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ \Modules\Settings\Models\Setting::get('home_feature_2_title', 'Project Tracking & Kanban Workflows') }}</h3>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        {{ \Modules\Settings\Models\Setting::get('home_feature_2_desc', 'Keep client deliverables on schedule. Assign tasks with swimlanes, manage milestones, log developer/staff work sessions, and track bugs through completion.') }}
                    </p>
                    <ul class="text-xs text-gray-400 space-y-1.5 pt-2">
                        <li>• Drag-and-drop Kanban task boards</li>
                        <li>• Live work session timer & audit export</li>
                        <li>• QA approvals and client milestone view</li>
                    </ul>
                </div>

                <!-- Bento 3: HR, Payroll & Attendance -->
                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-3xl p-8 shadow-sm border border-white/10 hover:border-white/20 transition space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold text-2xl border border-emerald-500/20">
                        👥
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ \Modules\Settings\Models\Setting::get('home_feature_3_title', 'Staff Attendance, Payroll & Utilities') }}</h3>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        {{ \Modules\Settings\Models\Setting::get('home_feature_3_desc', 'Empower your team with a dedicated Team Portal. Track daily clock-ins, performance scorecards, and disburse monthly staff salaries with utility phone top-ups.') }}
                    </p>
                    <ul class="text-xs text-gray-400 space-y-1.5 pt-2">
                        <li>• Daily attendance and standup reports</li>
                        <li>• Automated salary calculation & digital slips</li>
                        <li>• Airtime & data allowance top-ups</li>
                    </ul>
                </div>

                <!-- Bento 4: Storefront & Subdomains -->
                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-3xl p-8 shadow-sm border border-white/10 hover:border-white/20 transition space-y-4 md:col-span-2">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-2xl border border-blue-500/20">
                        🌐
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ \Modules\Settings\Models\Setting::get('home_feature_4_title', 'Your Dedicated Public Storefront & Custom Domain') }}</h3>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        {{ \Modules\Settings\Models\Setting::get('home_feature_4_desc', 'Every Bizpaddi account comes with a public-facing website at yourcompany.bizpaddi.com. Showcase your services, publish articles, accept quote requests, and connect your own custom domain with instant SSL.') }}
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2 text-xs text-gray-300">
                        <div class="p-3 bg-black/40 rounded-xl border border-white/10">
                            <span class="font-bold text-white block">CMS & Blog Engine</span>
                            <span class="text-gray-400">Publish articles with SEO optimization</span>
                        </div>
                        <div class="p-3 bg-black/40 rounded-xl border border-white/10">
                            <span class="font-bold text-white block">Client Self-Service</span>
                            <span class="text-gray-400">Clients view contracts and invoices</span>
                        </div>
                        <div class="p-3 bg-black/40 rounded-xl border border-white/10">
                            <span class="font-bold text-white block">Custom Domain Setup</span>
                            <span class="text-gray-400">Map your own domain with instant SSL</span>
                        </div>
                    </div>
                </div>

                <!-- Bento 5: AI Workforce -->
                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-3xl p-8 shadow-sm border border-amber-400/30 hover:border-amber-400/50 transition space-y-4 relative overflow-hidden">
                    <div class="absolute top-0 right-0 px-3 py-1 bg-[#F2A900] text-[#08110e] text-[10px] font-extrabold uppercase rounded-bl-xl">
                        AI Powered
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-2xl border border-amber-500/20">
                        🤖
                    </div>
                    <h3 class="text-xl font-bold text-white">{{ \Modules\Settings\Models\Setting::get('home_feature_5_title', 'Autonomous AI Workforce') }}</h3>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        {{ \Modules\Settings\Models\Setting::get('home_feature_5_desc', 'Scale without hiring immediately. Deploy dedicated AI agents to assist with marketing content, candidate scoring, customer inquiries, and daily proposals.') }}
                    </p>
                    <ul class="text-xs text-gray-400 space-y-1.5 pt-2">
                        <li>• Human-in-the-loop pending approval</li>
                        <li>• Custom knowledge base training</li>
                        <li>• Protect your token quotas with ease</li>
                    </ul>
                </div>
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('features') }}" class="inline-flex items-center space-x-2 text-sm font-bold text-[#F2A900] hover:text-amber-300 transition">
                    <span>Explore all platform features in detail</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 5. Interactive ROI & Savings Calculator (Dark Mode) -->
    <section style="background-color: #08110e !important;" class="py-20 text-white" x-data="{
        seats: 5,
        calculateUsd() { return Math.round(this.seats * 35); },
        calculateNairaUsd() { return this.calculateUsd() * 1550; },
        calculateBizpaddi() { return 25000 + (Math.max(0, this.seats - 5) * 3000); },
        calculateSavings() { return this.calculateNairaUsd() - this.calculateBizpaddi(); }
    }">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-[#10231C] to-[#0b1512] border border-white/15 rounded-3xl p-8 sm:p-12 backdrop-blur-md shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-7 space-y-4">
                        <span class="text-xs font-extrabold uppercase tracking-widest text-[#F2A900]">ROI & Savings Calculator</span>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-white">How Much Do You Save With Bizpaddi?</h3>
                        <p class="text-xs sm:text-sm text-gray-300 leading-relaxed">
                            Compare what you currently pay across other platforms versus Bizpaddi's unified workspace.
                        </p>

                        <!-- Interactive Slider -->
                        <div class="pt-4 space-y-3">
                            <div class="flex items-center justify-between text-sm font-bold">
                                <span class="text-gray-300">Total Team Members:</span>
                                <span class="text-amber-300 text-lg font-extrabold" x-text="seats + ' Seats'"></span>
                            </div>
                            <input type="range" min="1" max="30" step="1" x-model="seats" class="w-full h-2 bg-white/20 rounded-lg appearance-none cursor-pointer accent-[#F2A900]">
                            <div class="flex justify-between text-[10px] text-gray-400">
                                <span>1 Seat (Solo)</span>
                                <span>15 Seats</span>
                                <span>30 Seats (Scaling)</span>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 bg-black/40 border border-white/10 rounded-2xl p-6 text-center space-y-4">
                        <span class="text-xs uppercase font-extrabold tracking-wider text-emerald-400">Estimated Monthly Savings</span>
                        <div class="text-3xl sm:text-4xl font-black text-amber-300">
                            ₦<span x-text="calculateSavings().toLocaleString()"></span>
                        </div>
                        <p class="text-[11px] text-gray-400 leading-relaxed">
                            Separate individual tool subscriptions average ~₦<span x-text="calculateNairaUsd().toLocaleString()"></span>/mo. On Bizpaddi, your entire business runs for just ₦<span x-text="calculateBizpaddi().toLocaleString()"></span>/mo.
                        </p>
                        <a href="{{ route('register') }}" target="_blank" rel="noopener" class="block w-full py-3 rounded-xl bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white text-xs font-extrabold shadow-md transition border border-emerald-400/30">
                            Claim These Savings (Start Free) &rarr;
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. Transparent Pricing Overview (Dark Mode All Through) -->
    <section style="background-color: #0b1512 !important;" class="py-24 text-white relative overflow-hidden" x-data="{ mode: 'payg', annual: false }">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[#0E5E4A]/15 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center max-w-3xl mx-auto space-y-4">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-amber-400/10 border border-amber-400/20 text-xs font-bold text-amber-300">
                    {{ \Modules\Settings\Models\Setting::get('home_pricing_badge', 'Flexible Pricing for African Business') }}
                </span>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white">
                    {{ \Modules\Settings\Models\Setting::get('home_pricing_title', 'Fair, Transparent Pricing Built for Africa') }}
                </h2>
                <p class="text-sm sm:text-base text-gray-300">
                    {{ \Modules\Settings\Models\Setting::get('home_pricing_subtitle', 'Choose how you pay: top up an operating wallet and pay small micro-fees per transaction, or pick an unlimited flat monthly plan for scaling teams.') }}
                </p>

                <!-- Dual Paradigm Switcher -->
                <div class="pt-2 flex items-center justify-center">
                    <div class="inline-flex p-1.5 rounded-2xl bg-white/[0.06] border border-white/10 backdrop-blur-md shadow-xl">
                        <button type="button" 
                                @click="mode = 'payg'"
                                :class="mode === 'payg' ? 'bg-[#0E5E4A] text-white shadow-lg border border-emerald-400/40' : 'text-gray-400 hover:text-white'"
                                class="flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-200">
                            <span class="w-2 h-2 rounded-full bg-[#F2A900] animate-pulse"></span>
                            <span>⚡ Pay-As-You-Transact</span>
                            <span class="px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[10px] font-black uppercase hidden sm:inline">Zero Lock-in</span>
                        </button>
                        <button type="button" 
                                @click="mode = 'subscription'"
                                :class="mode === 'subscription' ? 'bg-[#0E5E4A] text-white shadow-lg border border-emerald-400/40' : 'text-gray-400 hover:text-white'"
                                class="flex items-center space-x-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-extrabold transition-all duration-200">
                            <svg class="w-4 h-4 text-[#F2A900]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>🏢 Unlimited Monthly Plans</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- MODE 1: PAY-AS-YOU-TRANSACT (MICRO-FEES) -->
            <div x-show="mode === 'payg'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                <!-- Highlight Card -->
                <div class="bg-gradient-to-r from-[#10231C] via-[#0d211a] to-[#10231C] rounded-3xl p-6 sm:p-8 border border-emerald-500/30 shadow-2xl flex flex-col lg:flex-row items-center justify-between gap-6 text-left">
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-amber-300 uppercase tracking-wider">⚡ The African Fintech-Style Model</span>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-white">Top Up Wallet from ₦500. Zero Usage = ₦0 Spent.</h3>
                        <p class="text-xs sm:text-sm text-gray-300 max-w-2xl">
                            No monthly lock-in. Simply fund your operating wallet and pay tiny micro-fees only when you execute actions. Your wallet balance never expires.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('register') }}?plan=pay_as_you_go" target="_blank" rel="noopener" class="inline-flex items-center space-x-2 px-7 py-3.5 rounded-xl text-sm font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-xl transition transform hover:-translate-y-0.5">
                            <span>Get Started with Wallet</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>

                <!-- 4 Micro-fee preview cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/40 transition space-y-3 text-left">
                        <div class="w-10 h-10 rounded-xl bg-[#0E5E4A]/40 flex items-center justify-center text-xl">📄</div>
                        <h4 class="text-base font-extrabold text-white">Smart Invoice</h4>
                        <p class="text-xs text-gray-300">Dedicated virtual account, auto-reconciliation & PDF receipt.</p>
                        <div class="pt-2 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-2xl font-black text-white">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_invoice_fee', '50') }}</span>
                            <span class="text-[11px] text-gray-400">/ invoice</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/40 transition space-y-3 text-left">
                        <div class="w-10 h-10 rounded-xl bg-[#0E5E4A]/40 flex items-center justify-center text-xl">📝</div>
                        <h4 class="text-base font-extrabold text-white">Client Quote</h4>
                        <p class="text-xs text-gray-300">Proposal bid with 1-click digital client acceptance.</p>
                        <div class="pt-2 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-2xl font-black text-white">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_quote_fee', '30') }}</span>
                            <span class="text-[11px] text-gray-400">/ quote</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-white/[0.03] border border-white/10 hover:border-emerald-500/40 transition space-y-3 text-left">
                        <div class="w-10 h-10 rounded-xl bg-[#0E5E4A]/40 flex items-center justify-center text-xl">👥</div>
                        <h4 class="text-base font-extrabold text-white">Team Clock-in</h4>
                        <p class="text-xs text-gray-300">Staff attendance, daily work logs & scorecards.</p>
                        <div class="pt-2 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-2xl font-black text-white">₦{{ \Modules\Settings\Models\Setting::get('pricing_payg_team_fee', '20') }}</span>
                            <span class="text-[11px] text-gray-400">/ user action</span>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-[#0E5E4A]/25 border border-emerald-500/40 transition space-y-3 text-left">
                        <div class="w-10 h-10 rounded-xl bg-[#0E5E4A] flex items-center justify-center text-xl">🌐</div>
                        <h4 class="text-base font-extrabold text-white">Digital Storefront</h4>
                        <p class="text-xs text-gray-300">Your branded website at `slug.bizpaddi.com`.</p>
                        <div class="pt-2 border-t border-white/10 flex items-baseline justify-between">
                            <span class="text-2xl font-black text-emerald-400">₦0 FREE</span>
                            <span class="text-[11px] text-gray-400">Always included</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODE 2: UNLIMITED MONTHLY PLANS -->
            <div x-show="mode === 'subscription'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8">
                <!-- Pricing Grid (Loaded Dynamically from Backend) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch text-left">
                    @foreach($subscriptionPlans ?? $plans->where('billing_type', '!=', 'payg') as $plan)
                    <div class="rounded-3xl p-8 flex flex-col justify-between transition-all duration-200 {{ $plan->enable_ai ? 'bg-[#0E5E4A]/40 text-white shadow-2xl relative border-2 border-emerald-400' : 'bg-white/[0.03] text-white border border-white/10 hover:border-white/20' }}">
                        @if($plan->enable_ai)
                        <div class="absolute -top-3 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-[#F2A900] text-[#08110e] text-xs font-extrabold uppercase shadow-sm">
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
                                <span class="text-4xl font-black text-white">₦{{ number_format($plan->price, 0) }}</span>
                                <span class="text-xs text-gray-400">/ {{ $plan->billing_interval }}</span>
                                @if($plan->billing_type === 'per_seat' && $plan->price_per_user > 0)
                                <span class="block text-xs font-bold text-amber-300 mt-1">+ ₦{{ number_format($plan->price_per_user, 0) }} per extra user seat</span>
                                @endif
                            </div>

                            <ul class="mt-6 space-y-3 text-xs text-gray-300">
                                <li class="flex items-center space-x-2.5">
                                    <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Includes <strong>{{ $plan->included_users }} team seats</strong></span>
                                </li>
                                <li class="flex items-center space-x-2.5">
                                    <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span><strong>Unlimited</strong> Invoices & Collections</span>
                                </li>
                                <li class="flex items-center space-x-2.5">
                                    <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Project Management & Kanban Workflows</span>
                                </li>
                                <li class="flex items-center space-x-2.5">
                                    <svg class="w-4 h-4 text-[#F2A900]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>Public Digital Storefront (`slug.bizpaddi.com`)</span>
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

                        <div class="mt-8 pt-4">
                            <a href="{{ route('register') }}?plan={{ $plan->code }}" target="_blank" rel="noopener" class="block w-full py-3.5 rounded-2xl text-center text-sm font-extrabold {{ $plan->enable_ai ? 'bg-[#F2A900] text-[#08110e] hover:bg-amber-300' : 'bg-[#0E5E4A] text-white hover:bg-[#0c4e3e]' }} shadow-md transition transform hover:-translate-y-0.5 border border-emerald-400/30">
                                Get Started Now
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center pt-4">
                <a href="{{ route('pricing') }}" class="inline-flex items-center space-x-2 text-sm font-bold text-[#F2A900] hover:text-amber-300 transition">
                    <span>View full African pricing calculator & side-by-side comparison</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 7. Founder Social Proof & Testimonials (Dark Mode) -->
    <section style="background-color: #08110e !important;" class="py-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-2">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#F2A900]">Loved by Founders</span>
                <h2 class="text-3xl font-extrabold text-white">Built for High-Performing African Teams</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div style="background-color: rgba(255,255,255,0.03);" class="border border-white/10 rounded-3xl p-6 space-y-4">
                    <div class="text-[#F2A900] text-sm">★★★★★</div>
                    <p class="text-xs sm:text-sm text-gray-300 leading-relaxed italic">
                        "Automated invoice tracking changed the game for our agency. Clients pay and the invoice marks paid within seconds without me checking bank apps or asking for screenshot receipts."
                    </p>
                    <div class="flex items-center space-x-3 pt-2">
                        <div class="w-9 h-9 rounded-full bg-[#0E5E4A] flex items-center justify-center font-bold text-white text-xs">
                            TA
                        </div>
                        <div>
                            <span class="font-bold text-white text-xs block">Tunde Adeleke</span>
                            <span class="text-[10px] text-gray-400">MD, Apex Creatives Lagos</span>
                        </div>
                    </div>
                </div>

                <div style="background-color: rgba(255,255,255,0.03);" class="border border-white/10 rounded-3xl p-6 space-y-4">
                    <div class="text-[#F2A900] text-sm">★★★★★</div>
                    <p class="text-xs sm:text-sm text-gray-300 leading-relaxed italic">
                        "Consolidating our operations into Bizpaddi transformed our business. Our entire remote team clocks in, tracks Kanban tasks, and gets their salary slips and airtime allowances right inside one portal."
                    </p>
                    <div class="flex items-center space-x-3 pt-2">
                        <div class="w-9 h-9 rounded-full bg-[#F2A900] text-[#08110e] flex items-center justify-center font-bold text-xs">
                            FO
                        </div>
                        <div>
                            <span class="font-bold text-white text-xs block">Funke Oladipo</span>
                            <span class="text-[10px] text-gray-400">COO, SwiftLogistics Hub</span>
                        </div>
                    </div>
                </div>

                <div style="background-color: rgba(255,255,255,0.03);" class="border border-white/10 rounded-3xl p-6 space-y-4">
                    <div class="text-[#F2A900] text-sm">★★★★★</div>
                    <p class="text-xs sm:text-sm text-gray-300 leading-relaxed italic">
                        "Setting up our company website at primeconsult.bizpaddi.com and mapping our custom domain took 5 minutes. Clients now request service quotes directly through our portal."
                    </p>
                    <div class="flex items-center space-x-3 pt-2">
                        <div class="w-9 h-9 rounded-full bg-emerald-600 flex items-center justify-center font-bold text-white text-xs">
                            EM
                        </div>
                        <div>
                            <span class="font-bold text-white text-xs block">Dr. Emeka Madu</span>
                            <span class="text-[10px] text-gray-400">Managing Partner, Prime Advisory</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. FAQ Accordion Section (Dark Mode All Through) -->
    <section style="background-color: #0b1512 !important;" class="py-24 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#F2A900]">Common Questions</span>
                <h2 class="text-3xl font-extrabold text-white">Frequently Asked Questions</h2>
            </div>

            <div class="space-y-4" x-data="{ active: null }">
                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-2xl p-5 border border-white/10 shadow-sm">
                    <button @click="active = (active === 1 ? null : 1)" class="w-full flex items-center justify-between text-left font-bold text-sm text-white">
                        <span>How does automated payment reconciliation work for our invoices?</span>
                        <span class="text-xs text-gray-400" x-text="active === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="active === 1" x-collapse class="mt-3 text-xs text-gray-300 leading-relaxed">
                        Whenever you issue an invoice to a customer, Bizpaddi includes direct payment details. When your client makes a transfer, your business receives the funds and the invoice is automatically marked "Paid" within seconds without manual verification.
                    </div>
                </div>

                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-2xl p-5 border border-white/10 shadow-sm">
                    <button @click="active = (active === 2 ? null : 2)" class="w-full flex items-center justify-between text-left font-bold text-sm text-white">
                        <span>Can I connect our company's own custom domain?</span>
                        <span class="text-xs text-gray-400" x-text="active === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="active === 2" x-collapse class="mt-3 text-xs text-gray-300 leading-relaxed">
                        Yes! In addition to your free <span class="font-mono text-amber-300 font-bold">yourname.bizpaddi.com</span> subdomain, you can connect any custom domain (e.g. <span class="font-mono">yourcompany.com</span>). Simply point an A-Record to our server IP. Automatic SSL certificates are issued.
                    </div>
                </div>

                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-2xl p-5 border border-white/10 shadow-sm">
                    <button @click="active = (active === 3 ? null : 3)" class="w-full flex items-center justify-between text-left font-bold text-sm text-white">
                        <span>Can my staff and team members log in remotely?</span>
                        <span class="text-xs text-gray-400" x-text="active === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="active === 3" x-collapse class="mt-3 text-xs text-gray-300 leading-relaxed">
                        Yes. Each employee gets access to the dedicated Team Portal to clock in, view assigned tasks on Kanban boards, track work time, submit daily reports, and access their monthly payslips.
                    </div>
                </div>

                <div style="background-color: rgba(255,255,255,0.03);" class="rounded-2xl p-5 border border-white/10 shadow-sm">
                    <button @click="active = (active === 4 ? null : 4)" class="w-full flex items-center justify-between text-left font-bold text-sm text-white">
                        <span>Can I upgrade, downgrade, or add more staff seats anytime?</span>
                        <span class="text-xs text-gray-400" x-text="active === 4 ? '−' : '+'"></span>
                    </button>
                    <div x-show="active === 4" x-collapse class="mt-3 text-xs text-gray-300 leading-relaxed">
                        Yes, absolutely. You can upgrade your plan or adjust user seats anytime directly from your workspace billing dashboard. Changes and prorated billing are applied instantly.
                    </div>
                </div>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('faq') }}" class="inline-flex items-center space-x-2 text-sm font-bold text-[#F2A900] hover:text-amber-300 transition">
                    <span>View all frequently asked questions</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 9. Bottom Conversion CTA (Dark Forest Theme) -->
    <section style="background-color: #050b09 !important;" class="py-20 text-white text-center border-t border-white/10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
                {{ \Modules\Settings\Models\Setting::get('home_bottom_cta_headline', 'Ready to Stop Losing Time to Manual Admin?') }}
            </h2>
            <p class="text-base sm:text-lg text-gray-200 max-w-2xl mx-auto">
                {{ \Modules\Settings\Models\Setting::get('home_bottom_cta_subheadline', 'Join ambitious businesses running on Bizpaddi. Invoicing with automated payment tracking, project management, staff payroll, and your brand\'s digital storefront.') }}
            </p>
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-9 py-4 rounded-2xl text-base font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-2xl transition transform hover:-translate-y-1">
                    <span>{{ \Modules\Settings\Models\Setting::get('home_bottom_cta_btn', 'Launch Your Workspace') }}</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
            <p class="text-xs text-gray-300 pt-2">
                {{ \Modules\Settings\Models\Setting::get('home_bottom_cta_guarantee', '14-Day Full-Access Free Trial • Cancel Anytime • Dedicated African Concierge Support') }}
            </p>
        </div>
    </section>
</x-home::layouts.master>
