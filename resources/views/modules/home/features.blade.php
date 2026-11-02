<x-home::layouts.master>
    <!-- Features Hero Section -->
    <section style="background-color: #08110e !important;" class="relative overflow-hidden text-white pt-20 pb-16 lg:pt-28 lg:pb-24">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[#0E5E4A]/20 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-bold text-emerald-300">
                Platform Architecture
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                One Unified Business Platform.<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-[#F2A900] to-teal-200">
                    Built for High-Growth Operations.
                </span>
            </h1>
            <p class="text-base sm:text-lg text-gray-300 max-w-3xl mx-auto font-normal leading-relaxed">
                Everything you need to invoice clients, collect payments automatically, coordinate remote teams, disburse payroll, and publish your branded storefront.
            </p>
            <div class="pt-4 flex items-center justify-center space-x-4">
                <a href="{{ route('admin.register') }}" target="_blank" rel="noopener" class="px-8 py-3.5 rounded-2xl text-sm font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white shadow-xl hover:shadow-emerald-900/40 transition transform hover:-translate-y-0.5 border border-emerald-400/30">
                    Get Started Now
                </a>
                <a href="{{ route('pricing') }}" class="px-7 py-3.5 rounded-2xl text-sm font-bold bg-white/[0.05] hover:bg-white/[0.08] text-white border border-white/10 transition">
                    View Plans & Pricing
                </a>
            </div>
        </div>
    </section>

    <!-- Deep-Dive Features Grid (Dark Mode All Through) -->
    <section style="background-color: #0b1512 !important;" class="py-20 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-24">
            
            <!-- Module 1: Smart Invoicing & Automated Collections -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-[#0E5E4A]/30 text-emerald-400 flex items-center justify-center font-bold text-2xl border border-emerald-500/20">
                        💳
                    </div>
                    <h2 class="text-3xl font-extrabold text-white">Smart Invoicing & Automated Collections</h2>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Never chase customer bank transfer receipts again. Bizpaddi provides professional invoicing with automatic payment tracking and instant receipt generation.
                    </p>
                    <div class="space-y-3 text-sm text-gray-300">
                        <div class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span><strong>60-Second Auto-Settlement:</strong> Payments clear and mark invoices "Paid" instantly upon transfer.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span><strong>Multi-Currency Engine:</strong> Invoice locally in Naira (NGN) or internationally in USD, GBP, and EUR.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span><strong>Quote-to-Invoice Conversion:</strong> Generate professional estimates and convert them into payable invoices with 1 click.</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 bg-white/[0.03] border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between border-b border-white/10 pb-3">
                        <span class="text-xs font-bold text-gray-400">INVOICE #INV-4921</span>
                        <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-0.5 rounded-full border border-emerald-500/20">Paid & Auto-Reconciled</span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-xs text-gray-400">Total Billed</span>
                        <span class="text-3xl font-black text-white block">₦280,000</span>
                    </div>
                    <div class="p-4 bg-black/40 rounded-2xl border border-white/10 space-y-2 text-xs">
                        <span class="text-[10px] uppercase font-bold text-gray-400 block">Bank Transfer Settlement</span>
                        <span class="font-mono text-base font-extrabold text-amber-300 tracking-wider block">Auto-Reconciled</span>
                        <span class="text-gray-400 text-[11px] block">Settled via Instant Mobile Banking Transfer • 09:42 AM</span>
                    </div>
                </div>
            </div>

            <!-- Module 2: Kanban & Projects -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 lg:order-2 space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-[#F2A900] flex items-center justify-center font-bold text-2xl border border-amber-500/20">
                        📊
                    </div>
                    <h2 class="text-3xl font-extrabold text-white">Visual Kanban & Project Management</h2>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Keep client deliverables on track. Organize complex workflows with drag-and-drop task boards, track billable developer hours, and approve milestones with full audit trails.
                    </p>
                    <div class="space-y-3 text-sm text-gray-300">
                        <div class="flex items-start space-x-3">
                            <span class="text-[#F2A900] font-bold mt-0.5">✓</span>
                            <span><strong>Real-Time Task Pipelines:</strong> Customize swimlanes from Backlog to QA Testing and Ready for Client.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-[#F2A900] font-bold mt-0.5">✓</span>
                            <span><strong>Live Work Session Timers:</strong> Staff track active project hours directly tied to client billing.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-[#F2A900] font-bold mt-0.5">✓</span>
                            <span><strong>Milestone Approvals:</strong> Secure client sign-offs before triggering phase invoices.</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 lg:order-1 bg-white/[0.03] border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-300 border-b border-white/10 pb-3">
                        <span>Active Sprint: Mobile Banking Flow</span>
                        <span class="text-amber-300">Sprint Velocity: 92%</span>
                    </div>
                    <div class="space-y-3 text-xs">
                        <div class="p-3 bg-white/[0.04] rounded-xl border border-white/10 space-y-1">
                            <span class="text-[10px] font-bold text-emerald-400 uppercase">Completed</span>
                            <p class="font-bold text-white">Direct Invoice Auto-Settlement Engine</p>
                            <span class="text-[10px] text-gray-400">Assigned to: Lead Systems Architect</span>
                        </div>
                        <div class="p-3 bg-emerald-950/40 rounded-xl border border-emerald-500/20 space-y-1">
                            <span class="text-[10px] font-bold text-amber-300 uppercase">In Progress (02h 14m)</span>
                            <p class="font-bold text-white">Client Milestone Review & PDF Signoff</p>
                            <span class="text-[10px] text-gray-400">Active Timer Running</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module 3: Team Attendance & Payroll -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold text-2xl border border-emerald-500/20">
                        👥
                    </div>
                    <h2 class="text-3xl font-extrabold text-white">Remote Attendance, Payroll & Utilities</h2>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Manage your distributed workforce with zero friction. Staff clock in through their dedicated portal, submit daily standups, and receive monthly salary slips with automated airtime and data stipends.
                    </p>
                    <div class="space-y-3 text-sm text-gray-300">
                        <div class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span><strong>Digital Attendance & Geofencing:</strong> Verify daily staff hours and standup submissions.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span><strong>1-Click Payroll Run:</strong> Calculate tax, bonuses, deductions, and export encrypted bank payment batches.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-emerald-400 font-bold mt-0.5">✓</span>
                            <span><strong>Airtime & Data Allowance:</strong> Automate monthly utility allowances directly to employee SIM cards.</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 bg-white/[0.03] border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-300 border-b border-white/10 pb-3">
                        <span>Staff Disbursal Overview</span>
                        <span class="text-emerald-400">● 100% On Time</span>
                    </div>
                    <div class="p-4 bg-black/40 rounded-2xl border border-white/10 space-y-2">
                        <span class="text-[10px] text-gray-400 uppercase font-bold block">October Payroll Run</span>
                        <span class="text-2xl font-black text-white block">₦4,250,000</span>
                        <span class="text-xs text-gray-300 block">18 Staff Members • 18 Automated Salary Slips</span>
                    </div>
                </div>
            </div>

            <!-- Module 4: Digital Storefront -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 lg:order-2 space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-2xl border border-blue-500/20">
                        🌐
                    </div>
                    <h2 class="text-3xl font-extrabold text-white">Your Dedicated Storefront & Custom Domain</h2>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Every Bizpaddi account comes with a public-facing website at <span class="font-mono text-amber-300 font-bold">yourcompany.bizpaddi.com</span>. Showcase your services, publish articles, accept quote requests, and connect your own custom domain with instant SSL.
                    </p>
                    <div class="space-y-3 text-sm text-gray-300">
                        <div class="flex items-start space-x-3">
                            <span class="text-blue-400 font-bold mt-0.5">✓</span>
                            <span><strong>Custom Domain Mapping:</strong> Connect <span class="font-mono">yourbrand.com</span> with 1-click DNS configuration.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-blue-400 font-bold mt-0.5">✓</span>
                            <span><strong>Built-In Blog & SEO:</strong> Publish articles with pre-optimized schema and social preview meta tags.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-blue-400 font-bold mt-0.5">✓</span>
                            <span><strong>Interactive Quote Requests:</strong> Incoming customer quote requests turn into actionable CRM leads.</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 lg:order-1 bg-white/[0.03] border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-300 border-b border-white/10 pb-3">
                        <span class="text-amber-300 font-mono">apexagency.com</span>
                        <span class="text-emerald-400 font-bold">SSL Active 🔒</span>
                    </div>
                    <div class="p-4 bg-black/40 rounded-2xl border border-white/10 space-y-2 text-xs">
                        <span class="text-white font-bold block">Live Digital Agency Portal</span>
                        <p class="text-gray-300 text-xs">Published 14 Services, 8 Case Studies, and automated service order checkout.</p>
                    </div>
                </div>
            </div>

            <!-- Module 5: AI Workforce -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                <div class="lg:col-span-6 space-y-6">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-300 flex items-center justify-center font-bold text-2xl border border-amber-500/20">
                        🤖
                    </div>
                    <h2 class="text-3xl font-extrabold text-white">Autonomous AI Workforce</h2>
                    <p class="text-sm text-gray-300 leading-relaxed">
                        Scale your operations without immediate hiring costs. Deploy dedicated AI agents that evaluate client scopes, draft tailored proposals, screen candidates, and answer client queries 24/7.
                    </p>
                    <div class="space-y-3 text-sm text-gray-300">
                        <div class="flex items-start space-x-3">
                            <span class="text-amber-300 font-bold mt-0.5">✓</span>
                            <span><strong>Proposal Drafting:</strong> Analyzes inquiry parameters and drafts structured quotations.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-amber-300 font-bold mt-0.5">✓</span>
                            <span><strong>Human-in-the-Loop:</strong> You retain complete approval authority before anything is sent.</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <span class="text-amber-300 font-bold mt-0.5">✓</span>
                            <span><strong>Custom Knowledge Base:</strong> Upload your company documents and manuals to ground AI responses.</span>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-6 bg-white/[0.03] border border-white/10 rounded-3xl p-6 sm:p-8 space-y-4 shadow-2xl">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-300 border-b border-white/10 pb-3">
                        <span class="text-amber-300">AI Assistant Activity Log</span>
                        <span class="text-emerald-400 font-bold">● Operational</span>
                    </div>
                    <div class="p-4 bg-black/40 rounded-2xl border border-white/10 space-y-2 text-xs">
                        <span class="text-white font-bold block">Drafted Scope #0122 for ₦650,000</span>
                        <p class="text-gray-300 text-xs">"Client requested mobile app revamp. Extracted requirements, generated deliverables schedule, and placed in Pending Approvals."</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Bottom Conversion CTA -->
    <section style="background-color: #050b09 !important;" class="py-20 text-white text-center border-t border-white/10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <h2 class="text-3xl sm:text-5xl font-extrabold tracking-tight">
                Ready to Experience Bizpaddi?
            </h2>
            <p class="text-base sm:text-lg text-gray-200 max-w-2xl mx-auto">
                Launch your business workspace in 60 seconds. Full 14-day free access to all features.
            </p>
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('admin.register') }}" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-9 py-4 rounded-2xl text-base font-extrabold bg-[#F2A900] hover:bg-amber-300 text-[#08110e] shadow-2xl transition transform hover:-translate-y-1">
                    <span>Launch Your Workspace</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </section>
</x-home::layouts.master>
