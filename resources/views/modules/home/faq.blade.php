<x-home::layouts.master>
    <!-- FAQ Hero Section (Dark Mode) -->
    <section class="relative overflow-hidden bg-gradient-to-b from-[#08110e] via-[#0b1512] to-[#08110e] text-white pt-20 pb-16 lg:pt-28 lg:pb-20" x-data="{
        search: '',
        activeCategory: 'all',
        activeAccordion: null
    }">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-[#0E5E4A]/20 rounded-full blur-[140px] pointer-events-none"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-bold text-emerald-300">
                Help & Answers
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight">
                Frequently Asked<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-[#F2A900] to-teal-200">
                    Questions.
                </span>
            </h1>
            <p class="text-base sm:text-lg text-gray-300 max-w-2xl mx-auto font-normal leading-relaxed">
                Everything you need to know about setting up your workspace, managing invoices, and leading your team.
            </p>

            <!-- Search Bar -->
            <div class="pt-2 max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" x-model="search" placeholder="Search questions (e.g. invoicing, custom domain, payroll)..." 
                           class="w-full px-5 py-4 pl-12 rounded-2xl bg-white/[0.04] border border-white/15 text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#0E5E4A] focus:border-emerald-400/50 backdrop-blur-md shadow-xl transition">
                    <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- FAQ Categories & Accordions -->
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 space-y-8">
            <div class="space-y-4">
                
                <!-- FAQ 1 -->
                <div class="bg-white/[0.03] rounded-2xl p-6 border border-white/10 shadow-sm transition hover:border-white/20">
                    <button @click="activeAccordion = (activeAccordion === 1 ? null : 1)" class="w-full flex items-center justify-between text-left font-bold text-base text-white">
                        <span>How does automated invoice payment reconciliation work?</span>
                        <span class="text-sm text-amber-300 font-mono" x-text="activeAccordion === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 1" x-collapse class="mt-4 text-xs sm:text-sm text-gray-300 leading-relaxed border-t border-white/10 pt-3">
                        Whenever you issue an invoice in Bizpaddi, your customer can pay directly. When payment is completed, funds hit your business account and the invoice is automatically marked as "Paid" in real time. You receive an instant email and in-app notification without ever needing to cross-reference bank receipts manually.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="bg-white/[0.03] rounded-2xl p-6 border border-white/10 shadow-sm transition hover:border-white/20">
                    <button @click="activeAccordion = (activeAccordion === 2 ? null : 2)" class="w-full flex items-center justify-between text-left font-bold text-base text-white">
                        <span>Can I connect our company's existing custom domain?</span>
                        <span class="text-sm text-amber-300 font-mono" x-text="activeAccordion === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 2" x-collapse class="mt-4 text-xs sm:text-sm text-gray-300 leading-relaxed border-t border-white/10 pt-3">
                        Yes! In addition to your free <span class="font-mono text-amber-300">yourname.bizpaddi.com</span> subdomain, you can connect your existing top-level domain (e.g. <span class="font-mono text-amber-300">yourcompany.com</span>). Simply point an A-record to our server IP. Automatic SSL certificates are issued immediately with zero server maintenance.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="bg-white/[0.03] rounded-2xl p-6 border border-white/10 shadow-sm transition hover:border-white/20">
                    <button @click="activeAccordion = (activeAccordion === 3 ? null : 3)" class="w-full flex items-center justify-between text-left font-bold text-base text-white">
                        <span>Can my remote staff members log in and track their hours?</span>
                        <span class="text-sm text-amber-300 font-mono" x-text="activeAccordion === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 3" x-collapse class="mt-4 text-xs sm:text-sm text-gray-300 leading-relaxed border-t border-white/10 pt-3">
                        Yes. Each team member receives their own credentials to the dedicated Team Portal. They can clock in, submit daily standup summaries, view their assigned Kanban task boards, run work session timers, and access their monthly salary payslips.
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="bg-white/[0.03] rounded-2xl p-6 border border-white/10 shadow-sm transition hover:border-white/20">
                    <button @click="activeAccordion = (activeAccordion === 4 ? null : 4)" class="w-full flex items-center justify-between text-left font-bold text-base text-white">
                        <span>Can I upgrade, downgrade, or add more staff seats anytime?</span>
                        <span class="text-sm text-amber-300 font-mono" x-text="activeAccordion === 4 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 4" x-collapse class="mt-4 text-xs sm:text-sm text-gray-300 leading-relaxed border-t border-white/10 pt-3">
                        Yes, absolutely. You can upgrade your plan or adjust user seats anytime directly from your workspace billing dashboard. Changes and prorated billing are applied instantly.
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="bg-white/[0.03] rounded-2xl p-6 border border-white/10 shadow-sm transition hover:border-white/20">
                    <button @click="activeAccordion = (activeAccordion === 5 ? null : 5)" class="w-full flex items-center justify-between text-left font-bold text-base text-white">
                        <span>Can I invite my external clients to view project milestones?</span>
                        <span class="text-sm text-amber-300 font-mono" x-text="activeAccordion === 5 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 5" x-collapse class="mt-4 text-xs sm:text-sm text-gray-300 leading-relaxed border-t border-white/10 pt-3">
                        Yes! Bizpaddi includes a dedicated Client Portal. Your clients can log in using their email to inspect their active project deliverables, sign digital contracts, review past receipts, and submit new service orders without booking discovery calls.
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="bg-white/[0.03] rounded-2xl p-6 border border-white/10 shadow-sm transition hover:border-white/20">
                    <button @click="activeAccordion = (activeAccordion === 6 ? null : 6)" class="w-full flex items-center justify-between text-left font-bold text-base text-white">
                        <span>How secure is Bizpaddi for financial and company data?</span>
                        <span class="text-sm text-amber-300 font-mono" x-text="activeAccordion === 6 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 6" x-collapse class="mt-4 text-xs sm:text-sm text-gray-300 leading-relaxed border-t border-white/10 pt-3">
                        Every company workspace runs on isolated multi-tenant database partitions with 256-bit SSL encryption. All payment transactions and business records are strictly monitored with real-time audit logging and comply with modern data privacy standards.
                    </div>
                </div>

            </div>

            <!-- Concierge Support Card -->
            <div class="p-8 rounded-3xl bg-gradient-to-br from-[#0E5E4A]/30 to-white/[0.02] border border-emerald-500/20 text-center space-y-4">
                <h3 class="text-xl font-bold text-white">Still have questions?</h3>
                <p class="text-xs sm:text-sm text-gray-300 max-w-md mx-auto">
                    Our African onboarding team is available to assist you with workspace setup, DNS migration, or custom enterprise requirements.
                </p>
                <div class="pt-2 flex items-center justify-center space-x-4">
                    <a href="https://wa.me/2349030117230" target="_blank" class="px-6 py-3 rounded-xl bg-[#25D366] hover:bg-emerald-600 text-white font-bold text-xs shadow-md transition flex items-center space-x-2">
                        <span>Chat on WhatsApp</span>
                    </a>
                    <a href="{{ route('admin.register') }}" target="_blank" rel="noopener" class="px-6 py-3 rounded-xl bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white font-bold text-xs border border-emerald-400/30 transition">
                        Get Started Now
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-home::layouts.master>
