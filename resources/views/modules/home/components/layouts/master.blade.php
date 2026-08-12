<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $ga4Id = \Modules\Settings\Models\Setting::get('google_analytics_id', env('GOOGLE_ANALYTICS_ID'));
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
    <!-- Sentry Error Logging -->
    <script
      src="https://js.sentry-cdn.com/YOUR_SENTRY_KEY.min.js"
      crossorigin="anonymous"
    ></script>
    @inject('seo', 'Modules\SEO\Services\SeoService')
    {!! $seo->render(isset($seoModel) ? $seoModel : null) !!}
    @php
        $favicon = \Modules\Settings\Models\Setting::get('favicon');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @else
        <link rel="icon" href="/logo.png" type="image/png">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Default to light mode, only switch to dark if explicitly set
        if (localStorage.theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        }
    </script>
    </style>
</head>
<body class="font-sans antialiased text-gray-900 bg-white dark:bg-gray-900 dark:text-gray-100 transition-colors duration-300" x-data="{ mobileMenuOpen: false }">
    <nav class="bg-brand-secondary dark:bg-gray-900 border-b border-brand-primary/20 sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="shrink-0 flex items-center">
                        <a href="/" class="flex items-center gap-2">
                            <img src="/logo.png" alt="Rick Development Logo" class="h-10 w-auto">
                        </a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex sm:items-center">
                        @if(isset($headerMenu))
                            @foreach($headerMenu as $item)
                                <a href="{{ $item->route_name ? route($item->route_name) : $item->url }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold leading-5 focus:outline-none transition duration-150 ease-in-out {{ (request()->is(ltrim($item->url, '/'))) || ($item->route_name && request()->routeIs($item->route_name)) ? 'border-brand-primary text-white hover:text-yellow-300' : 'border-transparent hover:border-brand-primary text-gray-200 hover:text-white' }}">
                                    {{ __($item->label) }}
                                </a>
                            @endforeach
                        @endif
                        <a href="{{ route('lead.create') }}" class="ml-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-full text-white bg-gradient-to-r from-brand-primary to-purple-600 hover:from-brand-primary hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-primary shadow-lg transform hover:-translate-y-0.5 transition-all">
                            {{ __('Start a Project') }}
                        </a>
                    </div>
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ langOpen: false }">
                        <button @click="langOpen = !langOpen" type="button" class="flex items-center gap-1.5 text-gray-200 hover:text-white hover:bg-white/10 focus:outline-none rounded-lg text-sm px-3 py-2 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>
                            <span class="hidden sm:inline text-xs font-medium">Language</span>
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="langOpen" @click.outside="langOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="fixed sm:absolute left-0 sm:left-auto sm:right-0 mt-2 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden" style="display:none; top: 64px; right: 0; width: calc(100vw - 0px); max-width: 440px; max-height: 80vh;">
                            <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Select Language</p>
                            </div>
                            <div class="overflow-y-auto" style="max-height: calc(80vh - 50px);">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-0 p-2">
                                    <button onclick="translateLanguage('en')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇬🇧 English</button>
                                    <button onclick="translateLanguage('fr')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇫🇷 Français</button>
                                    <button onclick="translateLanguage('es')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇪🇸 Español</button>
                                    <button onclick="translateLanguage('de')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇩🇪 Deutsch</button>
                                    <button onclick="translateLanguage('ar')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇸🇦 العربية</button>
                                    <button onclick="translateLanguage('zh-CN')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇨🇳 中文 (简体)</button>
                                    <button onclick="translateLanguage('zh-TW')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇹🇼 中文 (繁體)</button>
                                    <button onclick="translateLanguage('pt')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇧🇷 Português</button>
                                    <button onclick="translateLanguage('ru')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇷🇺 Русский</button>
                                    <button onclick="translateLanguage('ja')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇯🇵 日本語</button>
                                    <button onclick="translateLanguage('hi')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 हिन्दी</button>
                                    <button onclick="translateLanguage('ko')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇰🇷 한국어</button>
                                    <button onclick="translateLanguage('it')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇹 Italiano</button>
                                    <button onclick="translateLanguage('nl')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇳🇱 Nederlands</button>
                                    <button onclick="translateLanguage('tr')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇹🇷 Türkçe</button>
                                    <button onclick="translateLanguage('pl')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇵🇱 Polski</button>
                                    <button onclick="translateLanguage('sv')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇸🇪 Svenska</button>
                                    <button onclick="translateLanguage('da')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇩🇰 Dansk</button>
                                    <button onclick="translateLanguage('fi')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇫🇮 Suomi</button>
                                    <button onclick="translateLanguage('no')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇳🇴 Norsk</button>
                                    <button onclick="translateLanguage('el')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇬🇷 Ελληνικά</button>
                                    <button onclick="translateLanguage('cs')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇨🇿 Čeština</button>
                                    <button onclick="translateLanguage('ro')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇷🇴 Română</button>
                                    <button onclick="translateLanguage('hu')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇭🇺 Magyar</button>
                                    <button onclick="translateLanguage('uk')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇺🇦 Українська</button>
                                    <button onclick="translateLanguage('id')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇩 Indonesia</button>
                                    <button onclick="translateLanguage('ms')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇲🇾 Melayu</button>
                                    <button onclick="translateLanguage('th')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇹🇭 ภาษาไทย</button>
                                    <button onclick="translateLanguage('vi')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇻🇳 Tiếng Việt</button>
                                    <button onclick="translateLanguage('fa')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇷 فارسی</button>
                                    <button onclick="translateLanguage('he')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇱 עברית</button>
                                    <button onclick="translateLanguage('bn')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇧🇩 বাংলা</button>
                                    <button onclick="translateLanguage('ur')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇵🇰 اردو</button>
                                    <button onclick="translateLanguage('sw')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇰🇪 Kiswahili</button>
                                    <button onclick="translateLanguage('yo')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇳🇬 Yorùbá</button>
                                    <button onclick="translateLanguage('ig')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇳🇬 Igbo</button>
                                    <button onclick="translateLanguage('ha')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇳🇬 Hausa</button>
                                    <button onclick="translateLanguage('am')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇪🇹 አማርኛ</button>
                                    <button onclick="translateLanguage('so')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇸🇴 Soomaali</button>
                                    <button onclick="translateLanguage('tl')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇵🇭 Filipino</button>
                                    <button onclick="translateLanguage('af')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇿🇦 Afrikaans</button>
                                    <button onclick="translateLanguage('zu')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇿🇦 isiZulu</button>
                                    <button onclick="translateLanguage('ta')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 தமிழ்</button>
                                    <button onclick="translateLanguage('te')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 తెలుగు</button>
                                    <button onclick="translateLanguage('ml')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 മലയാളം</button>
                                    <button onclick="translateLanguage('kn')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 ಕನ್ನಡ</button>
                                    <button onclick="translateLanguage('mr')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 मराठी</button>
                                    <button onclick="translateLanguage('gu')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 ગુજરાતી</button>
                                    <button onclick="translateLanguage('pa')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇮🇳 ਪੰਜਾਬੀ</button>
                                    <button onclick="translateLanguage('bg')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇧🇬 Български</button>
                                    <button onclick="translateLanguage('hr')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇭🇷 Hrvatski</button>
                                    <button onclick="translateLanguage('sk')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇸🇰 Slovenčina</button>
                                    <button onclick="translateLanguage('lt')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇱🇹 Lietuvių</button>
                                    <button onclick="translateLanguage('lv')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇱🇻 Latviešu</button>
                                    <button onclick="translateLanguage('et')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇪🇪 Eesti</button>
                                    <button onclick="translateLanguage('sl')" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-brand-primary/10 rounded-lg transition-colors">🇸🇮 Slovenščina</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" type="button" class="text-gray-200 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-4 focus:ring-white/20 rounded-lg text-sm p-2.5 transition-colors duration-200">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    </button>

                    <!-- Mobile menu button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="sm:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-200 hover:text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white transition-colors">
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

        <!-- Mobile menu -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="sm:hidden bg-brand-secondary border-t border-brand-primary/20"
             style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1">
                @if(isset($headerMenu))
                    @foreach($headerMenu as $item)
                        <a href="{{ $item->route_name ? route($item->route_name) : $item->url }}" 
                           class="block px-3 py-2 rounded-md text-base font-bold {{ (request()->is(ltrim($item->url, '/'))) || ($item->route_name && request()->routeIs($item->route_name)) ? 'text-white bg-brand-primary' : 'text-gray-200 hover:text-white hover:bg-white/10' }}">
                            {{ __($item->label) }}
                        </a>
                    @endforeach
                @endif
            </div>

            <div class="px-2 pt-2 pb-3 border-t border-white/10">
                <a href="{{ route('lead.create') }}" class="block w-full text-center px-3 py-3 rounded-md text-base font-bold text-white bg-gradient-to-r from-brand-primary to-purple-600 hover:from-brand-primary hover:to-purple-700">
                    {{ __('Start a Project') }}
                </a>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    <footer class="bg-brand-secondary dark:bg-gray-900 border-t border-brand-primary/20 mt-12">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="/logo.png" alt="Rick Development Logo" class="h-10 w-auto">
                    </div>
                    <p class="mt-4 text-gray-300">
                        {{ __('Building the future with cutting-edge technology and innovative solutions for global enterprises.') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white tracking-wider uppercase">{{ __('Company') }}</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="/about" class="text-base text-gray-300 hover:text-white transition-colors">{{ __('About') }}</a></li>
                        <li><a href="/careers" class="text-base text-gray-300 hover:text-white transition-colors">{{ __('Careers') }}</a></li>
                        <li><a href="/blog" class="text-base text-gray-300 hover:text-white transition-colors">{{ __('Blog') }}</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white tracking-wider uppercase">{{ __('Legal') }}</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="/privacy-policy" class="text-base text-gray-300 hover:text-white transition-colors">{{ __('Privacy Policy') }}</a></li>
                        <li><a href="/terms-conditions" class="text-base text-gray-300 hover:text-white transition-colors">{{ __('Terms & Conditions') }}</a></li>
                        <li><a href="/legal/limitation-of-liability" class="text-base text-gray-300 hover:text-white transition-colors">{{ __('Limitation of Liability') }}</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-white/10 pt-8">
                <p class="text-base text-gray-300 text-center">
                    &copy; {{ date('Y') }} Rick Development LTD. {{ __('All rights reserved.') }}
                </p>
            </div>
        </div>
    </footer>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }

            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>
    <!-- Floating WhatsApp Button -->
    @php
        $whatsapp = \Modules\Settings\Models\Setting::get('support_whatsapp', '1234567890');
    @endphp
    @if($whatsapp)
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" rel="noopener noreferrer"
       style="position: fixed; bottom: 24px; left: 24px; width: 56px; height: 56px; background-color: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 9999; transition: transform 0.3s ease;"
       onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';">
        <svg style="width: 32px; height: 32px; color: white;" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
    @endif

    <div id="google_translate_element" style="display:none; position:absolute; visibility:hidden;"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function translateLanguage(lang) {
            const langText = {
                'en': 'English', 'es': 'Español', 'fr': 'Français', 'de': 'Deutsch',
                'zh-CN': '中文', 'ar': 'العربية', 'pt': 'Português', 'ru': 'Русский',
                'ja': '日本語', 'hi': 'हिन्दी'
            };
            if (langText[lang]) {
                localStorage.setItem('user_lang', lang);
            }
            var select = document.querySelector('select.goog-te-combo');
            if (select) {
                select.value = lang;
                select.dispatchEvent(new Event('change'));
            } else {
                let retries = 0;
                const interval = setInterval(() => {
                    select = document.querySelector('select.goog-te-combo');
                    if (select) {
                        select.value = lang;
                        select.dispatchEvent(new Event('change'));
                        clearInterval(interval);
                    }
                    if (++retries > 10) clearInterval(interval);
                }, 500);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const supportedLangs = ['en', 'es', 'fr', 'de', 'zh-CN', 'ar', 'pt', 'ru', 'ja', 'hi'];
                const storedLang = localStorage.getItem('user_lang');
                const hasGoogTrans = document.cookie.split(';').some((item) => item.trim().startsWith('googtrans='));

                if (!storedLang && !hasGoogTrans) {
                    let browserLang = navigator.language || navigator.userLanguage;
                    browserLang = browserLang.split('-')[0];
                    if (navigator.language === 'zh-CN' || navigator.language === 'zh-TW') {
                        browserLang = 'zh-CN';
                    }
                    if (supportedLangs.includes(browserLang) && browserLang !== 'en') {
                        translateLanguage(browserLang);
                    }
                }
            }, 1000);
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <x-home::chat-widget />
</body>
</html>
