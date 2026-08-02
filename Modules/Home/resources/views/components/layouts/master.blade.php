<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        @if(isset($headerMenu))
                            @foreach($headerMenu as $item)
                                <a href="{{ $item->route_name ? route($item->route_name) : $item->url }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold leading-5 focus:outline-none transition duration-150 ease-in-out {{ (request()->is(ltrim($item->url, '/'))) || ($item->route_name && request()->routeIs($item->route_name)) ? 'border-brand-primary text-white hover:text-yellow-300' : 'border-transparent hover:border-brand-primary text-gray-200 hover:text-white' }}">
                                    {{ __($item->label) }}
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <!-- Language Switcher Removed -->

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
                <!-- Mobile Language Removed -->
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
    <a href="https://wa.me/1234567890" target="_blank" rel="noopener noreferrer"
       style="position: fixed; bottom: 24px; left: 24px; width: 56px; height: 56px; background-color: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 9999; transition: transform 0.3s ease;"
       onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';">
        <svg style="width: 32px; height: 32px; color: white;" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
        </svg>
    </a>
</body>
</html>
