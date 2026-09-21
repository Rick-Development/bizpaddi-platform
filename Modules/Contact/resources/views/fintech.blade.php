<x-home::layouts.master>
    <style>
        :root {
            --bg-deep: #191C47;
            --bg-darker: #101232;
            --accent-red: #FC4C4B;
            --accent-red-hover: #E03B3A;
            --text-muted: rgba(255, 255, 255, 0.65);
            --border-light: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--bg-deep) !important;
        }

        main {
            background-color: var(--bg-deep) !important;
        }

        footer {
            margin-top: 0 !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
            background-color: var(--bg-darker) !important;
        }

        .fintech-wrapper {
            background-color: var(--bg-deep);
            color: #ffffff;
            font-family: inherit;
        }

        .fintech-grid-bg {
            background-image: radial-gradient(circle at 50% 50%, rgba(25, 28, 71, 0) 0%, #191C47 100%),
                              url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 40L40 40M40 0L40 40' fill='none' stroke='rgba(255,255,255,0.02)' stroke-width='1'/%3E%3C/svg%3E");
        }

        .fintech-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.04) 0%, rgba(255, 255, 255, 0.01) 100%);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.09);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .fintech-card:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.07) 0%, rgba(255, 255, 255, 0.02) 100%);
            border-color: rgba(252, 76, 75, 0.35);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), 0 0 25px 0 rgba(252, 76, 75, 0.12);
        }

        .fintech-badge {
            background: rgba(252, 76, 75, 0.12);
            border: 1px solid rgba(252, 76, 75, 0.3);
            color: #FC4C4B;
        }

        /* SVG animations */
        @keyframes pulse-flow {
            0% {
                stroke-dashoffset: 100;
            }
            100% {
                stroke-dashoffset: 0;
            }
        }

        .animate-flow-path {
            stroke-dasharray: 8 12;
            animation: pulse-flow 5s linear infinite;
        }

        .animate-flow-fast {
            stroke-dasharray: 6 8;
            animation: pulse-flow 2.5s linear infinite;
        }

        @keyframes pulse-glow {
            0%, 100% { opacity: 0.3; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.08); }
        }

        .glow-pulse {
            animation: pulse-glow 4s ease-in-out infinite;
        }
    </style>

    <div class="fintech-wrapper overflow-x-hidden fintech-grid-bg" x-data="fintechLandingPage()">

        <!-- HERO SECTION -->
        <section class="relative min-h-[85vh] flex items-center py-12 lg:py-24 overflow-hidden">
            <!-- Glowing ambient effects -->
            <div class="absolute top-1/4 left-10 w-[500px] h-[500px] bg-[#FC4C4B]/10 rounded-full filter blur-[150px] pointer-events-none glow-pulse"></div>
            <div class="absolute bottom-1/4 right-10 w-[500px] h-[500px] bg-purple-600/10 rounded-full filter blur-[160px] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                    
                    <!-- Left Hero Content -->
                    <div class="lg:col-span-7 text-left space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[11px] font-bold tracking-wider text-[#FC4C4B] bg-[#FC4C4B]/10 border border-[#FC4C4B]/20 uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B] animate-pulse"></span>
                            Fintech Infrastructure
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                            Your Fintech Vision.<br>
                            Engineered for <span class="text-[#FC4C4B]">Scale.</span>
                        </h1>

                        <p class="text-sm sm:text-base lg:text-lg text-white/80 font-light leading-relaxed max-w-2xl">
                            From domestic & cross-border payments and digital wallets to banking APIs, crypto/stablecoin rails, and high-frequency transaction engines — <strong class="text-white font-semibold">Rick Development</strong> engineers the technology powering ambitious financial businesses.
                        </p>

                        <!-- Key Highlights (Clean list structure, no bulky border boxes) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 max-w-xl">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-400/10 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm text-white/70">99.99% Core Engine Uptime SLA</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-400/10 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm text-white/70">Bank-Grade 256-bit AES & KYC</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-400/10 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm text-white/70">Multi-Currency FX Engine</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-400/10 flex items-center justify-center">
                                    <svg class="w-3 h-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-sm text-white/70">100% Client IP Ownership</span>
                            </div>
                        </div>

                        <!-- CTA Actions (Clean wrap on mobile, no full-width stretch) -->
                        <div class="flex flex-wrap gap-4 pt-4">
                            <a href="#lead-form" @click="scrollToForm('Other', 'hero_consultation')" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-sm font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-xl shadow-red-500/25 transition-all text-center">
                                Request a Consultation
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="#solutions" class="inline-flex items-center justify-center px-8 py-3.5 rounded-full text-sm font-bold text-white/90 border border-white/20 hover:border-white/45 hover:text-white bg-white/5 hover:bg-white/10 transition-all text-center">
                                Explore Solutions
                            </a>
                        </div>
                    </div>

                    <!-- Right Visual (Dynamic Fintech Architecture Engine) -->
                    <div class="lg:col-span-5 relative flex justify-center">
                        <div class="w-full max-w-[460px] rounded-3xl border border-white/10 bg-[#12153b]/90 backdrop-blur-2xl p-6 sm:p-7 shadow-2xl relative overflow-hidden">
                            
                            <div class="flex justify-between items-center pb-3.5 border-b border-white/10 mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-green-400 animate-pulse"></span>
                                    <span class="text-xs font-bold text-white tracking-wide">CORE TRANSACTION ENGINE</span>
                                </div>
                                <span class="text-[10px] font-mono text-[#FC4C4B] bg-[#FC4C4B]/10 px-2 py-0.5 rounded border border-[#FC4C4B]/20">LATENCY &lt; 40ms</span>
                            </div>

                            <svg class="w-full h-80 relative z-10" viewBox="0 0 400 360" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <defs>
                                    <linearGradient id="glowRed" x1="0%" y1="0%" x2="100%" y2="100%">
                                        <stop offset="0%" stop-color="#FC4C4B" />
                                        <stop offset="100%" stop-color="#801C1A" />
                                    </linearGradient>
                                </defs>

                                <!-- Connecting animated pathways -->
                                <path d="M 200,45 L 200,95" stroke="url(#glowRed)" stroke-width="2" class="animate-flow-fast" />
                                <path d="M 200,140 L 200,185" stroke="rgba(255,255,255,0.25)" stroke-width="2" />
                                <path d="M 200,210 L 85,260" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
                                <path d="M 200,210 L 200,260" stroke="url(#glowRed)" stroke-width="2" class="animate-flow-path" />
                                <path d="M 200,210 L 315,260" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
                                <path d="M 85,285 L 200,330" stroke="rgba(255,255,255,0.15)" stroke-width="2" />
                                <path d="M 200,285 L 200,330" stroke="url(#glowRed)" stroke-width="2" class="animate-flow-path" />
                                <path d="M 315,285 L 200,330" stroke="rgba(255,255,255,0.15)" stroke-width="2" />

                                <!-- Node: Clients & Customers -->
                                <g transform="translate(130, 15)">
                                    <rect width="140" height="32" rx="8" fill="#1E2259" stroke="rgba(255,255,255,0.25)" stroke-width="1"/>
                                    <text x="70" y="20" fill="#ffffff" font-size="10" font-weight="bold" text-anchor="middle" letter-spacing="1">END USERS & CLIENTS</text>
                                </g>

                                <!-- Node: Frontend App / API Consumers -->
                                <g transform="translate(120, 95)">
                                    <rect width="160" height="45" rx="10" fill="#1E2259" stroke="#FC4C4B" stroke-width="1.5"/>
                                    <text x="80" y="27" fill="#ffffff" font-size="11" font-weight="bold" text-anchor="middle">WEB & MOBILE INTERFACES</text>
                                </g>

                                <!-- Node: API Gateway & Auth -->
                                <g transform="translate(130, 185)">
                                    <rect width="140" height="32" rx="8" fill="#151840" stroke="rgba(255,255,255,0.25)" stroke-width="1"/>
                                    <text x="70" y="20" fill="rgba(255,255,255,0.95)" font-size="10" font-weight="bold" text-anchor="middle" letter-spacing="0.5">API GATEWAY & AUTH</text>
                                </g>

                                <!-- Branch: Payment Processing -->
                                <g transform="translate(20, 260)">
                                    <rect width="130" height="30" rx="6" fill="#121435" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
                                    <text x="65" y="19" fill="rgba(255,255,255,0.8)" font-size="9" font-weight="bold" text-anchor="middle">PAYMENT RAILS</text>
                                </g>

                                <!-- Branch: Ledger & Wallet -->
                                <g transform="translate(160, 260)">
                                    <rect width="80" height="30" rx="6" fill="#1E2259" stroke="#FC4C4B" stroke-width="1" />
                                    <text x="40" y="19" fill="#ffffff" font-size="9" font-weight="bold" text-anchor="middle">WALLET & FX</text>
                                </g>

                                <!-- Branch: Crypto Rails -->
                                <g transform="translate(255, 260)">
                                    <rect width="125" height="30" rx="6" fill="#121435" stroke="rgba(255,255,255,0.15)" stroke-width="1"/>
                                    <text x="62" y="19" fill="rgba(255,255,255,0.8)" font-size="9" font-weight="bold" text-anchor="middle">CRYPTO / STABLECOIN</text>
                                </g>

                                <!-- Node: Core Settlement Engine -->
                                <g transform="translate(110, 330)">
                                    <rect width="180" height="32" rx="8" fill="#1E2259" stroke="rgba(255,255,255,0.25)" stroke-width="1" />
                                    <text x="90" y="20" fill="#ffffff" font-size="9" font-weight="bold" text-anchor="middle" letter-spacing="0.5">AUTOMATED SETTLEMENT & BANKS</text>
                                </g>
                            </svg>

                            <div class="mt-3 pt-3 border-t border-white/10 flex justify-between items-center text-xs text-white/60">
                                <span>Idempotency Key Verification</span>
                                <span class="text-green-400 font-mono font-bold">ACTIVE (0ms duplicate)</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- PROVEN IMPACT STATS STRIP -->
        <section class="border-y border-white/10 bg-[#101232]/80 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-y md:divide-y-0 md:divide-x divide-white/10">
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl sm:text-4xl font-extrabold text-[#FC4C4B] mb-1">99.99%</div>
                        <div class="text-xs sm:text-sm font-medium text-white/70">Engine Uptime & Reliability SLA</div>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl sm:text-4xl font-extrabold text-white mb-1">&lt; 45ms</div>
                        <div class="text-xs sm:text-sm font-medium text-white/70">Average Transaction Routing Latency</div>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl sm:text-4xl font-extrabold text-[#FC4C4B] mb-1">15+</div>
                        <div class="text-xs sm:text-sm font-medium text-white/70">African & Global Commercial Bank Gateways</div>
                    </div>
                    <div class="pt-4 md:pt-0">
                        <div class="text-3xl sm:text-4xl font-extrabold text-white mb-1">100%</div>
                        <div class="text-xs sm:text-sm font-medium text-white/70">Custom Architecture (You Own 100% IP)</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5 CORE AD SOLUTIONS SECTION -->
        <section id="solutions" class="py-24">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="text-center max-w-3xl mx-auto mb-20">
                    <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase">Comprehensive Engineering Scope</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-3 mb-6">
                        One Engineering Partner.<br>Five High-Impact Fintech Capabilities.
                    </h2>
                    <p class="text-base sm:text-lg text-white/75 font-light leading-relaxed">
                        Whether you are building cross-border payment corridors, stablecoin infrastructure, customer-facing mobile fintech apps, or enterprise core ledgers — we engineer the technology to take you to production.
                    </p>
                </div>

                <!-- Solution 01 — Cross-Border Payments -->
                <div id="solution-payments" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-24 py-10 border-b border-white/10">
                    <div class="lg:col-span-6 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-bold bg-[#FC4C4B]/10 text-[#FC4C4B] border border-[#FC4C4B]/20">
                            01 / CROSS-BORDER PAYMENTS & REMITTANCE
                        </div>
                        <h3 class="text-3xl font-extrabold text-white leading-tight">Move Money Seamlessly Across Markets.</h3>
                        <p class="text-white/80 font-light leading-relaxed">
                            Build payment platforms designed for domestic and international transactions, multi-currency virtual accounts, automated FX conversion, and instant settlement across African and global corridors.
                        </p>
                        
                        <div class="grid grid-cols-2 gap-3 text-sm text-white/90 font-medium">
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Multi-currency wallets</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Automated FX Engine</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Virtual NUBAN & IBANs</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Real-time Payout Rails</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> High-throughput APIs</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Automated Reconciliation</div>
                        </div>

                        <div class="pt-3">
                            <a href="#lead-form" @click="scrollToForm('Payment Platform', 'solutions_payment')" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full text-sm font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-lg shadow-red-500/25 transition-all">
                                Build Your Payment Platform &rarr;
                            </a>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-6 flex justify-center">
                        <div class="w-full max-w-[440px] rounded-2xl fintech-card p-6 flex flex-col justify-between shadow-2xl">
                            <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                <span class="text-xs font-bold text-white/70">Multi-Currency Corridor Flow</span>
                                <span class="text-[10px] font-bold text-green-400 bg-green-500/10 px-2.5 py-1 rounded border border-green-500/20">LIVE CORRIDORS</span>
                            </div>

                            <svg class="w-full h-44 my-4" viewBox="0 0 350 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M 40,100 Q 110,30 180,100" stroke="#FC4C4B" stroke-width="2" class="animate-flow-path" />
                                <path d="M 180,100 Q 250,30 320,100" stroke="rgba(255,255,255,0.25)" stroke-width="2" />
                                <path d="M 40,100 L 320,100" stroke="rgba(255,255,255,0.1)" stroke-dasharray="3 3" />
                                
                                <g transform="translate(15, 80)">
                                    <circle cx="25" cy="20" r="16" fill="#1C2053" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                                    <text x="25" y="24" fill="#ffffff" font-size="10" font-weight="bold" text-anchor="middle">NGN ₦</text>
                                </g>
                                <g transform="translate(155, 80)">
                                    <circle cx="25" cy="20" r="16" fill="#1C2053" stroke="#FC4C4B" stroke-width="2"/>
                                    <text x="25" y="24" fill="#ffffff" font-size="10" font-weight="bold" text-anchor="middle">KES KSh</text>
                                </g>
                                <g transform="translate(295, 80)">
                                    <circle cx="25" cy="20" r="16" fill="#1C2053" stroke="rgba(255,255,255,0.3)" stroke-width="1.5"/>
                                    <text x="25" y="24" fill="#ffffff" font-size="10" font-weight="bold" text-anchor="middle">GBP £</text>
                                </g>

                                <text x="110" y="45" fill="#FC4C4B" font-size="10" font-weight="bold" text-anchor="middle">FX Rate Lock (15s)</text>
                                <text x="250" y="45" fill="rgba(255,255,255,0.6)" font-size="10" font-weight="bold" text-anchor="middle">SEPA / SWIFT Rail</text>
                            </svg>

                            <div class="bg-white/5 border border-white/10 rounded-xl p-3.5 flex justify-between items-center text-xs">
                                <div>
                                    <span class="block text-white/50 text-[10px] uppercase">Corridor Routing</span>
                                    <span class="text-white font-semibold">Nigeria → Kenya → UK → USA</span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-green-400 font-bold text-[11px]">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span> Instant Settlement
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solution 02 — Crypto / Stablecoin Infrastructure -->
                <div id="solution-crypto" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-24 py-10 border-b border-white/10 lg:flex-row-reverse">
                    <div class="lg:col-span-6 lg:order-2 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-bold bg-[#FC4C4B]/10 text-[#FC4C4B] border border-[#FC4C4B]/20">
                            02 / DIGITAL ASSETS & STABLECOIN RAILS
                        </div>
                        <h3 class="text-3xl font-extrabold text-white leading-tight">Institutional Digital Asset Infrastructure.</h3>
                        <p class="text-white/80 font-light leading-relaxed">
                            Engineer secure, institutional-grade platforms for non-custodial and MPC wallets, crypto exchanges, stablecoin cross-border settlements (USDT/USDC), and seamless fiat on/off ramps.
                        </p>
                        
                        <div class="bg-[#FC4C4B]/10 border border-[#FC4C4B]/25 rounded-xl p-3.5 text-xs text-white/90">
                            <strong class="text-[#FC4C4B] block mb-0.5">Software Engineering Partner</strong>
                            We build proprietary software engineering & backend infrastructure — not speculative token advice.
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm text-white/90 font-medium">
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> MPC & Custodial Wallets</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Stablecoin Settlement Rails</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Buy, Sell & Swap Matchers</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Direct Fiat On/Off Gateways</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Blockchain Nodes & Indexers</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> AML & Travel Rule Compliance</div>
                        </div>

                        <div class="pt-3">
                            <a href="#lead-form" @click="scrollToForm('Crypto / Stablecoin Product', 'solutions_crypto')" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full text-sm font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-lg shadow-red-500/25 transition-all">
                                Build Your Crypto Product &rarr;
                            </a>
                        </div>
                    </div>
                    
                    <div class="lg:col-span-6 lg:order-1 flex justify-center">
                        <div class="w-full max-w-[440px] rounded-2xl fintech-card p-6 flex flex-col justify-between shadow-2xl">
                            <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                <span class="text-xs font-bold text-white/70">Fiat ↔ Stablecoin Pipeline</span>
                                <span class="text-[10px] font-bold text-yellow-400 bg-yellow-500/10 px-2.5 py-1 rounded border border-yellow-500/20">MPC SECURED</span>
                            </div>

                            <div class="space-y-3.5 my-auto py-3">
                                <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl p-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-[#FC4C4B]/20 flex items-center justify-center font-bold text-[#FC4C4B] text-sm">🔒</div>
                                        <div>
                                            <span class="text-xs font-semibold text-white block">Multi-Sig MPC Wallet</span>
                                            <span class="text-[10px] text-white/50">Gas-optimized EVM/Tron Vault</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-white">0.75 ETH</span>
                                </div>
                                
                                <div class="text-center text-[#FC4C4B] text-xs font-bold flex items-center justify-center gap-2">
                                    <span>↓</span> <span>INSTANT SMART CONTRACT CONVERSION</span> <span>↓</span>
                                </div>

                                <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl p-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-green-500/20 flex items-center justify-center font-bold text-green-400 text-sm">₮</div>
                                        <div>
                                            <span class="text-xs font-semibold text-white block">Stablecoin Liquidity Rail</span>
                                            <span class="text-[10px] text-white/50">USDT / USDC Settlement Pool</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-white">$2,500.00</span>
                                </div>

                                <div class="text-center text-[#FC4C4B] text-xs font-bold flex items-center justify-center gap-2">
                                    <span>↓</span> <span>AUTOMATED BANK DISPATCH</span> <span>↓</span>
                                </div>

                                <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-xl p-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-blue-500/20 flex items-center justify-center font-bold text-blue-400 text-sm">🏦</div>
                                        <div>
                                            <span class="text-xs font-semibold text-white block">Commercial Banking Gateway</span>
                                            <span class="text-[10px] text-white/50">NIP / SEPA / ACH Direct Credit</span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-green-400">FIAT DISPATCHED</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solution 03 — Fintech Applications & Neobanks -->
                <div id="solution-fintech" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-24 py-10 border-b border-white/10">
                    <div class="lg:col-span-6 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-bold bg-[#FC4C4B]/10 text-[#FC4C4B] border border-[#FC4C4B]/20">
                            03 / FINTECH APPLICATIONS & NEOBANKS
                        </div>
                        <h3 class="text-3xl font-extrabold text-white leading-tight">Turn Financial Ideas into Engaging Products.</h3>
                        <p class="text-white/80 font-light leading-relaxed">
                            Transform your concept into a sleek, secure, customer-facing mobile and web platform with intuitive financial UX, instant onboarding, biometric security, and comprehensive management dashboards.
                        </p>

                        <div class="grid grid-cols-2 gap-3 text-sm text-white/90 font-medium">
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> iOS & Android Mobile Apps</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Virtual & Physical Card Issuing</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Bill Payments, Airtime & VAS</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Tiered KYC & AML Verification</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Push & In-App Financial Alerts</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Back-office Admin & Risk Suite</div>
                        </div>

                        <div class="pt-3">
                            <a href="#lead-form" @click="scrollToForm('Fintech Application', 'solutions_fintech')" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full text-sm font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-lg shadow-red-500/25 transition-all">
                                Start Building Your App &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-6 flex justify-center">
                        <div class="w-72 h-[450px] rounded-[36px] border-4 border-white/20 bg-[#1E2259] p-4 shadow-2xl relative flex flex-col justify-between">
                            <div>
                                <div class="w-24 h-4 bg-black/50 rounded-full mx-auto mb-4"></div>
                                
                                <div class="flex justify-between items-center mb-4 px-1">
                                    <div>
                                        <span class="block text-[9px] text-white/60 uppercase font-semibold">Total Balance</span>
                                        <span class="block text-base font-extrabold text-white">$64,820.00</span>
                                    </div>
                                    <div class="w-7 h-7 rounded-full bg-[#FC4C4B]/20 flex items-center justify-center text-[10px] text-[#FC4C4B] font-bold">RD</div>
                                </div>

                                <div class="bg-gradient-to-r from-red-600 to-[#FC4C4B] rounded-2xl p-4 shadow-lg mb-4 text-white">
                                    <div class="flex justify-between items-start mb-6">
                                        <span class="text-[9px] font-bold tracking-wider">PREMIUM VIRTUAL CARD</span>
                                        <span class="text-[9px] font-light">Rick Dev Core</span>
                                    </div>
                                    <span class="block text-sm font-semibold tracking-widest mb-2 font-mono">5412 •••• •••• 9845</span>
                                    <div class="flex justify-between text-[8px] opacity-90">
                                        <span>CORPORATE CARD</span>
                                        <span>EXP 12/29</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-4 gap-1.5 mb-4 text-center text-[8px] font-bold text-white">
                                    <div class="bg-white/5 rounded-lg p-2">SEND</div>
                                    <div class="bg-white/5 rounded-lg p-2">RECEIVE</div>
                                    <div class="bg-white/5 rounded-lg p-2">CARDS</div>
                                    <div class="bg-white/5 rounded-lg p-2">BILLS</div>
                                </div>
                            </div>

                            <div class="space-y-2 text-[9px] text-white/80 bg-white/5 p-2.5 rounded-xl">
                                <div class="flex justify-between items-center">
                                    <span>Biometric Auth (FaceID / Fingerprint)</span>
                                    <span class="text-green-400 font-bold">Active</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span>Tier 3 KYC Identity Engine</span>
                                    <span class="text-green-400 font-bold">Verified</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solution 04 — Financial Infrastructure & APIs -->
                <div id="solution-infrastructure" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center mb-24 py-10 border-b border-white/10 lg:flex-row-reverse">
                    <div class="lg:col-span-6 lg:order-2 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-bold bg-[#FC4C4B]/10 text-[#FC4C4B] border border-[#FC4C4B]/20">
                            04 / ENTERPRISE CORE INFRASTRUCTURE
                        </div>
                        <h3 class="text-3xl font-extrabold text-white leading-tight">Infrastructure Built Around Your Business Model.</h3>
                        <p class="text-white/80 font-light leading-relaxed">
                            Build the robust financial engines, unified API layers, and transaction processors required to operate, automate, and scale modern financial operations without downtime.
                        </p>

                        <div class="grid grid-cols-2 gap-3 text-sm text-white/90 font-medium">
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Double-Entry Ledger Architecture</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> High-Throughput API Gateway</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Multi-Provider Fallback Routing</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Automated Daily Reconciliation</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Event-Driven Webhook Dispatcher</div>
                            <div class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-[#FC4C4B]"></span> Real-Time Analytics & Audit Logs</div>
                        </div>

                        <div class="pt-3">
                            <a href="#lead-form" @click="scrollToForm('Financial Infrastructure', 'solutions_infrastructure')" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full text-sm font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-lg shadow-red-500/25 transition-all">
                                Build Your Infrastructure &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-6 lg:order-1 flex justify-center">
                        <div class="w-full max-w-[440px] rounded-2xl fintech-card p-6 flex flex-col justify-between shadow-2xl">
                            <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                <span class="text-xs font-bold text-white/70">Fault-Tolerant Engine Overview</span>
                                <span class="text-[10px] font-bold text-[#FC4C4B] bg-[#FC4C4B]/10 px-2.5 py-1 rounded border border-[#FC4C4B]/20">AUTO-FAILOVER</span>
                            </div>

                            <div class="grid grid-cols-3 gap-3 my-auto py-3 text-center text-xs">
                                <div class="bg-white/5 border border-white/10 rounded-xl p-3">
                                    <span class="block font-bold text-white text-xs">CLIENT APPS</span>
                                    <span class="text-[10px] text-white/50">Web & Mobile</span>
                                </div>
                                <div class="col-span-2 bg-[#FC4C4B]/10 border border-[#FC4C4B]/30 rounded-xl p-3 flex items-center justify-center font-bold text-white text-xs tracking-wide">
                                    UNIFIED API GATEWAY
                                </div>

                                <div class="col-span-3 bg-white/5 border border-white/10 rounded-xl p-4 text-left">
                                    <div class="flex justify-between items-center mb-1.5">
                                        <span class="font-bold text-white text-xs">DOUBLE-ENTRY LEDGER SYSTEM</span>
                                        <span class="text-[10px] text-green-400 font-bold bg-green-500/10 px-2 py-0.5 rounded">ZERO DRIFT</span>
                                    </div>
                                    <p class="text-[11px] text-white/70 leading-relaxed">
                                        Guaranteed ACID compliance, real-time balance reconciliation, and idempotent transaction state locks.
                                    </p>
                                </div>

                                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5">
                                    <span class="block font-bold text-white">BANK RAILS</span>
                                </div>
                                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5">
                                    <span class="block font-bold text-white">VAS RAILS</span>
                                </div>
                                <div class="bg-white/5 border border-white/10 rounded-xl p-2.5">
                                    <span class="block font-bold text-white">SETTLEMENT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solution 05 — African Fintech Growth & Venture Building -->
                <div id="solution-africa" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center py-10">
                    <div class="lg:col-span-6 space-y-6">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md text-xs font-bold bg-[#FC4C4B]/10 text-[#FC4C4B] border border-[#FC4C4B]/20">
                            05 / AFRICAN FINTECH VENTURE BUILDING
                        </div>
                        <h3 class="text-3xl font-extrabold text-white leading-tight">Build the Next African Fintech Unicorn.</h3>
                        <p class="text-white/80 font-light leading-relaxed">
                            Africa's financial technology ecosystem is expanding rapidly. We partner with ambitious founders, microfinance banks, and neobanks to turn bold product ideas into scalable, fundable, production-ready technology.
                        </p>

                        <div class="p-4 bg-[#FC4C4B]/10 border border-[#FC4C4B]/25 rounded-2xl">
                            <span class="text-base font-extrabold text-[#FC4C4B] block mb-1">You bring the vision. We build the technology.</span>
                            <p class="text-xs text-white/80 leading-relaxed">
                                Seamless technology connectivity across Lagos, Accra, Nairobi, Johannesburg, Cairo, London and New York.
                            </p>
                        </div>

                        <div class="pt-3">
                            <a href="#lead-form" @click="scrollToForm('Banking / MFB Platform', 'solutions_africa')" class="inline-flex items-center gap-2 px-7 py-3.5 rounded-full text-sm font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-lg shadow-red-500/25 transition-all">
                                Start Building With Us &rarr;
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-6 flex justify-center">
                        <div class="w-full max-w-[440px] rounded-2xl fintech-card p-6 flex flex-col justify-between shadow-2xl">
                            <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                <span class="text-xs font-bold text-white/70">Pan-African & Global Node Grid</span>
                                <span class="text-[10px] font-bold text-[#FC4C4B] bg-[#FC4C4B]/10 px-2.5 py-1 rounded border border-[#FC4C4B]/20">INTERCONNECTED</span>
                            </div>

                            <svg class="w-full h-48 my-3" viewBox="0 0 350 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M 40,150 L 120,110 L 220,140 L 300,60" stroke="#FC4C4B" stroke-width="2" class="animate-flow-path" />
                                <path d="M 120,110 L 220,60" stroke="rgba(255,255,255,0.3)" stroke-width="1.5" />
                                <path d="M 220,140 L 300,150" stroke="rgba(255,255,255,0.3)" stroke-width="1.5" />

                                <circle cx="40" cy="150" r="6" fill="#FC4C4B" />
                                <text x="40" y="172" fill="#ffffff" font-size="10" font-weight="bold" text-anchor="middle">Lagos</text>
                                
                                <circle cx="120" cy="110" r="5" fill="rgba(255,255,255,0.8)" />
                                <text x="120" y="128" fill="rgba(255,255,255,0.9)" font-size="9" text-anchor="middle">Accra</text>

                                <circle cx="220" cy="140" r="6" fill="#FC4C4B" />
                                <text x="220" y="162" fill="#ffffff" font-size="10" font-weight="bold" text-anchor="middle">Nairobi</text>

                                <circle cx="220" cy="60" r="5" fill="rgba(255,255,255,0.8)" />
                                <text x="220" y="48" fill="rgba(255,255,255,0.9)" font-size="9" text-anchor="middle">Cairo</text>

                                <circle cx="300" cy="60" r="6" fill="#FC4C4B" />
                                <text x="300" y="46" fill="#ffffff" font-size="10" font-weight="bold" text-anchor="middle">London</text>

                                <circle cx="300" cy="150" r="5" fill="rgba(255,255,255,0.8)" />
                                <text x="300" y="170" fill="rgba(255,255,255,0.9)" font-size="9" text-anchor="middle">Johannesburg</text>
                            </svg>

                            <div class="bg-white/5 border border-white/10 rounded-xl p-3 flex items-center justify-between text-xs">
                                <span class="text-white/70">Pan-African Cross-Routing Latency</span>
                                <span class="font-mono font-bold text-[#FC4C4B]">&lt; 38ms</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <!-- INTERACTIVE API EXPLORER (Proves technical depth to CTOs) -->
        <section id="api-preview" class="py-24 border-t border-white/10 bg-[#101232]/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase">Built for Technical Founders & CTOs</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-3">Clean APIs. Zero Technical Debt.</h2>
                    <p class="text-base text-white/70 mt-3 font-light">
                        We design modular, developer-friendly endpoints with strict schema validation, comprehensive webhooks, and predictable JSON responses.
                    </p>
                </div>

                <div class="max-w-4xl mx-auto rounded-3xl border border-white/15 bg-[#0b0d24] shadow-2xl overflow-hidden" x-data="{ apiTab: 'wallet' }">
                    <!-- Tabs Bar -->
                    <div class="flex border-b border-white/10 bg-white/[0.02] px-6 py-3 items-center justify-between overflow-x-auto">
                        <div class="flex gap-2 sm:gap-4 text-xs font-mono">
                            <button @click="apiTab = 'wallet'" :class="apiTab === 'wallet' ? 'bg-[#FC4C4B] text-white' : 'text-white/60 hover:text-white bg-white/5'" class="px-3 py-1.5 rounded-lg font-bold transition-all">
                                POST /v1/wallets/create
                            </button>
                            <button @click="apiTab = 'payout'" :class="apiTab === 'payout' ? 'bg-[#FC4C4B] text-white' : 'text-white/60 hover:text-white bg-white/5'" class="px-3 py-1.5 rounded-lg font-bold transition-all">
                                POST /v1/payouts/dispatch
                            </button>
                            <button @click="apiTab = 'crypto'" :class="apiTab === 'crypto' ? 'bg-[#FC4C4B] text-white' : 'text-white/60 hover:text-white bg-white/5'" class="px-3 py-1.5 rounded-lg font-bold transition-all">
                                POST /v1/crypto/settle
                            </button>
                        </div>
                        <span class="text-[10px] text-green-400 font-mono hidden sm:inline">200 OK (38ms)</span>
                    </div>

                    <!-- Code Display -->
                    <div class="p-6 font-mono text-xs text-white/90 overflow-x-auto">
                        <div x-show="apiTab === 'wallet'">
                            <span class="text-white/40">// 1. Create Multi-Currency Virtual Wallet with Tier-3 KYC</span><br>
                            <span class="text-[#FC4C4B]">POST</span> <span class="text-blue-300">https://api.yourfintech.com/v1/wallets/create</span><br><br>
                            <span class="text-purple-300">{</span><br>
                            &nbsp;&nbsp;<span class="text-blue-300">"customer_id"</span>: <span class="text-green-300">"cus_8941f82d"</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"currencies"</span>: [<span class="text-green-300">"NGN"</span>, <span class="text-green-300">"USD"</span>, <span class="text-green-300">"KES"</span>, <span class="text-green-300">"GBP"</span>],<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"auto_generate_virtual_accounts"</span>: <span class="text-yellow-300">true</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"settlement_rail"</span>: <span class="text-green-300">"instant_nip_rtgs"</span><br>
                            <span class="text-purple-300">}</span><br><br>
                            <span class="text-white/40">// Response:</span><br>
                            <span class="text-purple-300">{</span> <span class="text-blue-300">"status"</span>: <span class="text-green-300">"success"</span>, <span class="text-blue-300">"wallet_id"</span>: <span class="text-green-300">"wal_01HZ89X"</span>, <span class="text-blue-300">"allocated_accounts"</span>: [<span class="text-yellow-300">4</span>], <span class="text-blue-300">"state"</span>: <span class="text-green-300">"ACTIVE"</span> <span class="text-purple-300">}</span>
                        </div>

                        <div x-show="apiTab === 'payout'" style="display: none;">
                            <span class="text-white/40">// 2. Cross-Border Multi-Corridor Instant Payout</span><br>
                            <span class="text-[#FC4C4B]">POST</span> <span class="text-blue-300">https://api.yourfintech.com/v1/payouts/dispatch</span><br><br>
                            <span class="text-purple-300">{</span><br>
                            &nbsp;&nbsp;<span class="text-blue-300">"source_currency"</span>: <span class="text-green-300">"USD"</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"destination_currency"</span>: <span class="text-green-300">"KES"</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"amount"</span>: <span class="text-yellow-300">50000.00</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"beneficiary_account"</span>: <span class="text-green-300">"MPESA_254712345678"</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"idempotency_key"</span>: <span class="text-green-300">"idem_984f82c0"</span><br>
                            <span class="text-purple-300">}</span>
                        </div>

                        <div x-show="apiTab === 'crypto'" style="display: none;">
                            <span class="text-white/40">// 3. Stablecoin (USDT) Fiat On/Off Ramp Settlement</span><br>
                            <span class="text-[#FC4C4B]">POST</span> <span class="text-blue-300">https://api.yourfintech.com/v1/crypto/settle</span><br><br>
                            <span class="text-purple-300">{</span><br>
                            &nbsp;&nbsp;<span class="text-blue-300">"asset"</span>: <span class="text-green-300">"USDT_TRON"</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"amount"</span>: <span class="text-yellow-300">12500.00</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"action"</span>: <span class="text-green-300">"LIQUIDATE_TO_FIAT"</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"settlement_bank_code"</span>: <span class="text-green-300">"058"</span>,<br>
                            &nbsp;&nbsp;<span class="text-blue-300">"aml_score_verified"</span>: <span class="text-yellow-300">true</span><br>
                            <span class="text-purple-300">}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- WHY RICK DEV VS GENERIC AGENCIES / IN-HOUSE (Comparison Matrix) -->
        <section id="comparison" class="py-24 border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase">Decision Criteria</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-3">Why Ambitious Fintechs Choose Rick Development</h2>
                    <p class="text-base text-white/70 mt-3 font-light">
                        Building financial products is fundamentally different from building general websites or apps.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-white/80 border-collapse">
                        <thead>
                            <tr class="border-b border-white/15 text-xs uppercase tracking-wider text-white/50">
                                <th class="py-4 px-6">Capability & Standard</th>
                                <th class="py-4 px-6 text-white/40">Generic Web Agency</th>
                                <th class="py-4 px-6 text-white/40">Hiring In-House From Scratch</th>
                                <th class="py-4 px-6 text-[#FC4C4B] font-bold bg-[#FC4C4B]/10 rounded-t-xl">Rick Development</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr>
                                <td class="py-4 px-6 font-semibold text-white">Financial Domain & Banking APIs</td>
                                <td class="py-4 px-6 text-red-400">❌ Generic, no domain expertise</td>
                                <td class="py-4 px-6 text-yellow-300">⚠️ 4-6 months trial & error</td>
                                <td class="py-4 px-6 text-green-400 font-bold bg-[#FC4C4B]/5">✅ Battle-tested bank connectors</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 font-semibold text-white">Double-Entry Ledger Architecture</td>
                                <td class="py-4 px-6 text-red-400">❌ Basic database (risk of balance drift)</td>
                                <td class="py-4 px-6 text-yellow-300">⚠️ Complex to build from zero</td>
                                <td class="py-4 px-6 text-green-400 font-bold bg-[#FC4C4B]/5">✅ Strict ACID reconciliation core</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 font-semibold text-white">Speed to Production (Go-to-Market)</td>
                                <td class="py-4 px-6 text-white/60">4 - 8 Months</td>
                                <td class="py-4 px-6 text-red-400">6 - 12 Months (Hiring delay)</td>
                                <td class="py-4 px-6 text-green-400 font-bold bg-[#FC4C4B]/5">✅ 6 - 6 Weeks MVP Launch</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 font-semibold text-white">Intellectual Property (IP Ownership)</td>
                                <td class="py-4 px-6 text-yellow-300">⚠️ Proprietary lock-in often occurs</td>
                                <td class="py-4 px-6 text-white/60">100% Owned</td>
                                <td class="py-4 px-6 text-green-400 font-bold bg-[#FC4C4B]/5">✅ 100% Client IP & Code Ownership</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-6 font-semibold text-white">Bank-Grade Security Standards</td>
                                <td class="py-4 px-6 text-red-400">❌ Basic web security only</td>
                                <td class="py-4 px-6 text-yellow-300">⚠️ Requires specialized SecOps hires</td>
                                <td class="py-4 px-6 text-green-400 font-bold bg-[#FC4C4B]/5">✅ AES-256, OAuth2, RBAC & Audit Trails</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- SECURITY, COMPLIANCE & SLA -->
        <section id="security" class="py-20 border-t border-white/10 bg-[#101232]/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase">Institutional Rigor</span>
                    <h2 class="text-3xl font-extrabold text-white mt-2">Engineered with Bank-Grade Security Standards</h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="fintech-card p-6 rounded-2xl text-center space-y-2">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-green-500/10 flex items-center justify-center text-green-400 text-lg font-bold">🔐</div>
                        <h4 class="text-sm font-bold text-white">End-to-End Encryption</h4>
                        <p class="text-xs text-white/60 leading-relaxed">AES-256 data at rest, TLS 1.3 in transit, and secret management.</p>
                    </div>

                    <div class="fintech-card p-6 rounded-2xl text-center space-y-2">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 text-lg font-bold">🛡️</div>
                        <h4 class="text-sm font-bold text-white">Role-Based RBAC</h4>
                        <p class="text-xs text-white/60 leading-relaxed">Granular permissions, MFA enforcement, and tamper-proof audit trails.</p>
                    </div>

                    <div class="fintech-card p-6 rounded-2xl text-center space-y-2">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 text-lg font-bold">⚡</div>
                        <h4 class="text-sm font-bold text-white">Idempotent Requests</h4>
                        <p class="text-xs text-white/60 leading-relaxed">Zero double-spend, zero duplicate charges, real-time transaction locks.</p>
                    </div>

                    <div class="fintech-card p-6 rounded-2xl text-center space-y-2">
                        <div class="w-10 h-10 mx-auto rounded-xl bg-[#FC4C4B]/10 flex items-center justify-center text-[#FC4C4B] text-lg font-bold">📜</div>
                        <h4 class="text-sm font-bold text-white">Compliance Ready</h4>
                        <p class="text-xs text-white/60 leading-relaxed">Designed around PCI-DSS, GDPR, NDPR, and regulatory reporting specs.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- PROCESS (From Idea to Production) -->
        <section id="process" class="py-24 border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase">Our Execution Roadmap</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white mt-3">From Fintech Idea to Live Production</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="fintech-card rounded-2xl p-6 space-y-3 relative overflow-hidden">
                        <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase block">STAGE 01</span>
                        <h3 class="text-lg font-bold text-white">Discover & Scope</h3>
                        <p class="text-xs text-white/70 font-light leading-relaxed">
                            We analyze business logic, payment routing, compliance mandates, target corridors, and partner API specifications.
                        </p>
                    </div>

                    <div class="fintech-card rounded-2xl p-6 space-y-3 relative overflow-hidden">
                        <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase block">STAGE 02</span>
                        <h3 class="text-lg font-bold text-white">Architect & Secure</h3>
                        <p class="text-xs text-white/70 font-light leading-relaxed">
                            We draft database schemas, double-entry ledger models, API schemas, security layers, and provider failover paths.
                        </p>
                    </div>

                    <div class="fintech-card rounded-2xl p-6 space-y-3 relative overflow-hidden">
                        <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase block">STAGE 03</span>
                        <h3 class="text-lg font-bold text-white">Engineer & Test</h3>
                        <p class="text-xs text-white/70 font-light leading-relaxed">
                            Our team develops mobile apps, web portals, core ledgers, admin monitors, and executes end-to-end sandbox testing.
                        </p>
                    </div>

                    <div class="fintech-card rounded-2xl p-6 space-y-3 relative overflow-hidden">
                        <span class="text-xs font-extrabold tracking-widest text-[#FC4C4B] uppercase block">STAGE 04</span>
                        <h3 class="text-lg font-bold text-white">Deploy & Scale</h3>
                        <p class="text-xs text-white/70 font-light leading-relaxed">
                            Production deployment, automated telemetry monitoring, SLA maintenance, and continuous feature expansion.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- LEAD QUALIFICATION FORM SECTION -->
        <section id="lead-form" class="py-24 border-t border-white/10 bg-gradient-to-b from-[#191C47] to-[#0c0e29] relative">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="fintech-card rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden border border-white/15">
                    
                    <!-- Header -->
                    <div class="text-center mb-8" x-show="!formSubmitted">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#FC4C4B]/20 text-[#FC4C4B] border border-[#FC4C4B]/30 uppercase tracking-widest">
                            Direct Engineering Consultation
                        </span>
                        <h2 class="text-3xl font-extrabold text-white mt-3">Let's Build What's Next.</h2>
                        <p class="text-sm text-white/75 font-light mt-2 max-w-lg mx-auto leading-relaxed">
                            Tell us about your project, current stage, and goals. A senior fintech architect from our team will review your requirements and provide a technical roadmap.
                        </p>
                    </div>

                    <!-- Loading State Overlay -->
                    <div x-show="formLoading" class="absolute inset-0 bg-[#191C47]/95 z-20 flex flex-col items-center justify-center rounded-3xl" style="display: none;">
                        <svg class="animate-spin h-10 w-10 text-[#FC4C4B] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-bold text-white">Submitting your inquiry to our fintech sales team...</span>
                    </div>

                    <!-- Success Confirmation -->
                    <div x-show="formSubmitted" class="text-center py-10" style="display: none;">
                        <div class="w-16 h-16 bg-green-500/20 border border-green-500/30 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-extrabold text-white mb-2">Consultation Request Received!</h3>
                        <p class="text-sm text-white/80 font-light leading-relaxed max-w-md mx-auto mb-6">
                            Thank you, <span class="font-semibold text-white" x-text="formData.name"></span>. Our lead fintech sales team will get back to you within 12 hours.
                        </p>
                        <a href="/" class="inline-flex items-center justify-center px-7 py-3 rounded-full text-xs font-bold text-white bg-white/10 hover:bg-white/20 transition-colors">
                            Return to Homepage
                        </a>
                    </div>

                    <!-- Form Content -->
                    <form x-show="!formSubmitted" @submit.prevent="submitForm()" class="space-y-5">
                        <div x-show="formError" class="bg-red-500/10 border border-red-500/30 rounded-xl p-3 text-xs text-red-400" style="display: none;" x-text="formError"></div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-1.5">Full Name <span class="text-[#FC4C4B]">*</span></label>
                                <input type="text" x-model="formData.name" required class="w-full px-4 py-3 bg-white/5 border border-white/15 rounded-xl focus:border-[#FC4C4B] focus:ring-1 focus:ring-[#FC4C4B] text-white text-sm outline-none transition-all placeholder-white/30" placeholder="e.g. David Adeleke">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-1.5">Work Email <span class="text-[#FC4C4B]">*</span></label>
                                <input type="email" x-model="formData.email" required class="w-full px-4 py-3 bg-white/5 border border-white/15 rounded-xl focus:border-[#FC4C4B] focus:ring-1 focus:ring-[#FC4C4B] text-white text-sm outline-none transition-all placeholder-white/30" placeholder="david@fintech.io">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-1.5">Company / Startup</label>
                                <input type="text" x-model="formData.company" class="w-full px-4 py-3 bg-white/5 border border-white/15 rounded-xl focus:border-[#FC4C4B] focus:ring-1 focus:ring-[#FC4C4B] text-white text-sm outline-none transition-all placeholder-white/30" placeholder="e.g. Velocity Pay">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-1.5">Job Title</label>
                                <input type="text" x-model="formData.job_title" class="w-full px-4 py-3 bg-white/5 border border-white/15 rounded-xl focus:border-[#FC4C4B] focus:ring-1 focus:ring-[#FC4C4B] text-white text-sm outline-none transition-all placeholder-white/30" placeholder="e.g. Founder / CTO / VP Product">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-1.5">What are you building? <span class="text-[#FC4C4B]">*</span></label>
                                <select x-model="formData.solution_interest" required class="w-full px-4 py-3 bg-[#191C47] border border-white/15 rounded-xl focus:border-[#FC4C4B] focus:ring-1 focus:ring-[#FC4C4B] text-white text-sm outline-none transition-all">
                                    <option value="">Select Primary Solution</option>
                                    <option value="Payment Platform">Cross-Border Payment Platform</option>
                                    <option value="Fintech Application">Consumer / Neobank Mobile App</option>
                                    <option value="Financial Infrastructure">Core Banking / Enterprise Infrastructure</option>
                                    <option value="Crypto / Stablecoin Product">Crypto / Stablecoin Rails (USDT/USDC)</option>
                                    <option value="Digital Wallet">Digital Wallet & Card Issuing</option>
                                    <option value="Banking / MFB Platform">Microfinance / Core Banking Platform</option>
                                    <option value="Other">Other Custom Financial Product</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-1.5">Project Stage <span class="text-[#FC4C4B]">*</span></label>
                                <select x-model="formData.project_stage" required class="w-full px-4 py-3 bg-[#191C47] border border-white/15 rounded-xl focus:border-[#FC4C4B] focus:ring-1 focus:ring-[#FC4C4B] text-white text-sm outline-none transition-all">
                                    <option value="">Select Project Stage</option>
                                    <option value="Idea / Concept">Idea / Concept Stage</option>
                                    <option value="MVP">Building Initial MVP</option>
                                    <option value="Existing Product">Existing Product (Need Upgrade)</option>
                                    <option value="Scaling Existing Platform">Scaling Existing High-Volume Platform</option>
                                    <option value="Enterprise Transformation">Enterprise Banking Modernization</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-white/80 uppercase tracking-wider mb-1.5">Briefly describe your project requirements & timelines</label>
                            <textarea x-model="formData.description" rows="3" class="w-full px-4 py-3 bg-white/5 border border-white/15 rounded-xl focus:border-[#FC4C4B] focus:ring-1 focus:ring-[#FC4C4B] text-white text-sm outline-none transition-all placeholder-white/30" placeholder="e.g. We are building a multi-currency remittance app for the UK-Nigeria corridor with virtual USD accounts. Looking to launch MVP in 8 weeks..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl text-base font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-xl shadow-red-500/30 transform hover:-translate-y-0.5 transition-all">
                            Request a Consultation & Technical Proposal &rarr;
                        </button>

                        <!-- Trust Seal -->
                        <div class="pt-2 flex flex-wrap items-center justify-center gap-6 text-[11px] text-white/50">
                            <span class="flex items-center gap-1.5">
                                🔒 100% Confidential NDA
                            </span>
                            <span class="flex items-center gap-1.5">
                                ⏱️ 12h Response Time
                            </span>
                            <span class="flex items-center gap-1.5">
                                👨‍💻 Direct CTO Consultation
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- HIGH-INTENT FINAL CTA -->
        <section class="py-20 border-t border-white/10 text-center relative overflow-hidden bg-[#101232]/90">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <h2 class="text-3xl sm:text-5xl font-extrabold text-white mb-4">Your Vision. Our Engineering.</h2>
                <p class="text-base sm:text-lg text-white/75 font-light mb-8 max-w-xl mx-auto leading-relaxed">
                    Ready to build a financial platform or infrastructure capable of competing in the next generation of digital finance?
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="#lead-form" @click="scrollToForm('Other', 'final_start_cta')" class="inline-flex items-center justify-center px-9 py-4 rounded-full text-base font-bold text-white bg-[#FC4C4B] hover:bg-[#E03B3A] shadow-xl shadow-red-500/30 transition-all">
                        START BUILDING TODAY
                    </a>
                    <a href="#lead-form" @click="scrollToForm('Other', 'final_talk_cta')" class="inline-flex items-center justify-center px-9 py-4 rounded-full text-base font-bold text-white/80 border border-white/20 hover:border-white/40 hover:text-white bg-white/5 hover:bg-white/10 transition-all">
                        Talk to Rick Development
                    </a>
                </div>
            </div>
        </section>

    </div>

    <!-- ALPINE JS LOGIC -->
    <script>
        function fintechLandingPage() {
            const urlParams = new URLSearchParams(window.location.search);
            
            return {
                formLoading: false,
                formSubmitted: false,
                formError: null,

                formData: {
                    name: '',
                    email: '',
                    company: '',
                    job_title: '',
                    solution_interest: '',
                    project_stage: '',
                    description: '',
                    clicked_cta: 'organic',

                    utm_source: urlParams.get('utm_source') || '',
                    utm_medium: urlParams.get('utm_medium') || '',
                    utm_campaign: urlParams.get('utm_campaign') || '',
                    utm_content: urlParams.get('utm_content') || '',
                    utm_term: urlParams.get('utm_term') || ''
                },

                scrollToForm(interest, ctaName) {
                    this.formData.clicked_cta = ctaName;

                    if (interest && interest !== 'Other') {
                        this.formData.solution_interest = interest;
                    }

                    const formElement = document.getElementById('lead-form');
                    if (formElement) {
                        formElement.scrollIntoView({ behavior: 'smooth' });
                    }
                },

                async submitForm() {
                    this.formLoading = true;
                    this.formError = null;

                    try {
                        const response = await fetch('{{ route('fintech.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.formData)
                        });

                        const result = await response.json();

                        if (response.ok && result.status === 'success') {
                            this.formSubmitted = true;
                            if (typeof fbq === 'function') {
                                fbq('track', 'Lead', { content_name: 'Fintech Project Request' });
                            }
                        } else {
                            this.formError = result.message || 'An error occurred during submission. Please check your inputs and try again.';
                        }
                    } catch (error) {
                        this.formError = 'A network error occurred. Please try again or contact us directly.';
                    } finally {
                        this.formLoading = false;
                    }
                }
            };
        }
    </script>
</x-home::layouts.master>
