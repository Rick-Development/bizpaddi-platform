<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" style="background-color: #08110e !important; color: #ffffff !important;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $ga4Id = \Modules\Settings\Models\Setting::get('google_analytics_id', env('GOOGLE_ANALYTICS_ID'));
        $metaPixelId = \Modules\Settings\Models\Setting::get('meta_pixel_id', env('META_PIXEL_ID', '2163484361059410'));
    @endphp
    @if($ga4Id)
    <!-- Google Analytics 4 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $ga4Id }}');
    </script>
    @endif
    @if($metaPixelId)
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '{{ $metaPixelId }}');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id={{ $metaPixelId }}&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    @endif
    @inject('seo', 'Modules\SEO\Services\SeoService')
    {!! $seo->render(isset($seoModel) ? $seoModel : null) !!}
    
    <!-- Transparent Favicon -->
    <link rel="icon" href="{{ asset('images/bizpaddi/icon.png') }}" type="image/png">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        html, body, main {
            background-color: #08110e !important;
            color: #ffffff !important;
        }
        * { font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body style="background-color: #08110e !important; color: #ffffff !important;" class="font-sans antialiased min-h-screen selection:bg-[#F2A900] selection:text-[#08110e]" x-data="{ mobileMenuOpen: false }">
    <!-- Navbar -->
    <nav style="background-color: #0b1512 !important; border-bottom: 1px solid rgba(255,255,255,0.1) !important;" class="sticky top-0 z-50 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <!-- Left end: Authentic Brand Logo (No Color Inversion) -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" width="148" height="40" fetchpriority="high" class="h-9 sm:h-10 w-auto object-contain">
                    </a>
                </div>

                <!-- Right side (Desktop & Tablet): Nav Links + Sign In + Trial CTA -->
                <div class="hidden md:flex items-center space-x-7">
                    <div class="flex items-center space-x-6 text-sm font-semibold text-gray-300">
                        <a href="{{ route('features') }}" class="hover:text-white transition {{ request()->routeIs('features') ? 'text-[#F2A900] font-bold' : '' }}">
                            Features
                        </a>
                        <a href="{{ route('pricing') }}" class="hover:text-white transition {{ request()->routeIs('pricing') ? 'text-[#F2A900] font-bold' : '' }}">
                            Pricing
                        </a>
                        <a href="{{ route('faq') }}" class="hover:text-white transition {{ request()->routeIs('faq') ? 'text-[#F2A900] font-bold' : '' }}">
                            FAQ
                        </a>
                    </div>

                    <div class="flex items-center space-x-4 pl-4 border-l border-white/10">
                        <a href="{{ route('admin.login') }}" target="_blank" rel="noopener" class="text-sm font-bold text-gray-200 hover:text-white transition">
                            Sign In
                        </a>
                        <a href="{{ route('admin.register') }}" target="_blank" rel="noopener" class="px-5 py-2.5 rounded-xl text-sm font-extrabold bg-[#0E5E4A] hover:bg-[#0c4e3e] text-white border border-emerald-400/30 shadow-lg hover:shadow-emerald-900/40 transition transform hover:-translate-y-0.5">
                            Get Started Now
                        </a>
                    </div>
                </div>

                <!-- Mobile Menu Button (Below 768px) -->
                <div class="md:hidden flex items-center space-x-2.5">
                    <a href="{{ route('admin.register') }}" target="_blank" rel="noopener" class="px-3.5 py-1.5 rounded-lg text-xs font-extrabold bg-[#0E5E4A] text-white border border-emerald-400/30">
                        Get Started
                    </a>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="p-2 rounded-lg text-gray-200 hover:text-white hover:bg-white/10 focus:outline-none">
                        <svg class="h-6 w-6" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile dropdown drawer -->
        <div x-show="mobileMenuOpen" x-cloak class="md:hidden bg-[#08110e] border-t border-white/10 px-5 pt-3 pb-6 space-y-3 text-sm font-semibold">
            <a href="{{ route('features') }}" class="block py-2 text-gray-300 hover:text-white">Features</a>
            <a href="{{ route('pricing') }}" class="block py-2 text-gray-300 hover:text-white">Pricing</a>
            <a href="{{ route('faq') }}" class="block py-2 text-gray-300 hover:text-white">FAQ</a>
            <a href="{{ route('admin.login') }}" target="_blank" rel="noopener" class="block py-2 text-gray-300 hover:text-white">Sign In</a>
            <a href="{{ route('admin.register') }}" target="_blank" rel="noopener" class="block text-center py-3 rounded-xl bg-[#0E5E4A] text-white font-bold border border-emerald-400/30">Get Started Now</a>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <!-- Footer in Dark Mode -->
    <footer style="background-color: #050b09 !important; border-top: 1px solid rgba(255,255,255,0.1) !important;" class="text-white mt-20">
        <div class="max-w-7xl mx-auto py-14 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-10">
                <div class="col-span-1 md:col-span-2 space-y-4">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/bizpaddi/logo.png') }}" alt="Bizpaddi" class="h-9 w-auto object-contain">
                    </a>
                    <p class="text-sm text-gray-300 max-w-sm leading-relaxed">
                        {{ \Modules\Settings\Models\Setting::get('home_footer_bio', 'The all-in-one business management and invoicing platform. Instant payment confirmation, automated invoice reconciliation, project tracking, staff payroll, and digital storefronts.') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-xs font-extrabold text-amber-300 tracking-wider uppercase">Platform Modules</h3>
                    <ul class="mt-4 space-y-2.5 text-xs text-gray-300">
                        <li><a href="{{ route('features') }}" class="hover:text-white transition">Smart Invoicing & Payments</a></li>
                        <li><a href="{{ route('features') }}" class="hover:text-white transition">Kanban & Task Management</a></li>
                        <li><a href="{{ route('features') }}" class="hover:text-white transition">Team Attendance & Payroll</a></li>
                        <li><a href="{{ route('features') }}" class="hover:text-white transition">Public Digital Storefront</a></li>
                        <li><a href="{{ route('features') }}" class="hover:text-white transition">Autonomous AI Workforce</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-extrabold text-amber-300 tracking-wider uppercase">Portals & Product</h3>
                    <ul class="mt-4 space-y-2.5 text-xs text-gray-300">
                        <li><a href="{{ route('admin.register') }}" target="_blank" rel="noopener" class="hover:text-white transition">Get Started</a></li>
                        <li><a href="{{ route('pricing') }}" class="hover:text-white transition">Pricing Plans</a></li>
                        <li><a href="{{ route('admin.login') }}" target="_blank" rel="noopener" class="hover:text-white transition">Business Workspace</a></li>
                        <li><a href="{{ route('team.login') }}" target="_blank" rel="noopener" class="hover:text-white transition">Staff & Team Portal</a></li>
                        <li><a href="{{ route('client.login') }}" target="_blank" rel="noopener" class="hover:text-white transition">Client Portal</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xs font-extrabold text-amber-300 tracking-wider uppercase">Security & Legal</h3>
                    <ul class="mt-4 space-y-2.5 text-xs text-gray-300">
                        <li><a href="/privacy-policy" class="hover:text-white transition">Privacy Policy</a></li>
                        <li><a href="/terms-conditions" class="hover:text-white transition">Terms of Service</a></li>
                        <li><a href="/legal/limitation-of-liability" class="hover:text-white transition">Limitation of Liability</a></li>
                        <li><span class="text-gray-400">NDPR & 256-Bit SSL Encrypted</span></li>
                    </ul>
                </div>
            </div>
            <div class="mt-12 border-t border-white/10 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-400 gap-4">
                <p>
                    &copy; {{ date('Y') }} {{ \Modules\Settings\Models\Setting::get('home_footer_copyright', 'Bizpaddi Technologies Ltd. All rights reserved.') }}
                </p>
            </div>
        </div>
    </footer>

</body>
</html>
