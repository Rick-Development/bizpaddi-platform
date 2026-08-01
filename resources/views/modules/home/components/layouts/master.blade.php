<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @inject('seo', 'Modules\SEO\Services\SeoService')
    {!! $seo->render(isset($seoModel) ? $seoModel : null) !!}
    <link rel="icon" href="/logo.png" type="image/png">
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
    <style>
        /* Hide Google Translate Bar */
        .goog-te-banner-frame.skiptranslate, .goog-te-gadget-icon {
            display: none !important;
        }
        body {
            top: 0px !important;
        }
        .goog-te-menu-value img {
            display: none !important;
        }
        .goog-te-menu-frame {
            box-shadow: none !important;
        }
        #google_translate_element {
            display: none !important;
        }
        .skiptranslate {
            display: none !important;
        }
        body > .skiptranslate {
            display: none !important;
        }
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
                    <!-- Language Switcher -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-gray-200 hover:text-white focus:outline-none transition-colors duration-200">
                            <span class="uppercase font-semibold">{{ app()->getLocale() }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50" 
                             style="display: none;">
                            <div class="py-1 max-h-96 overflow-y-auto">
                                <a href="javascript:void(0)" onclick="translateLanguage('en')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇬🇧 English
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('es')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇪🇸 Español
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('fr')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇫🇷 Français
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('de')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇩🇪 Deutsch
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('zh-CN')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇨🇳 中文
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('ar')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇸🇦 العربية
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('pt')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇵🇹 Português
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('ru')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇷🇺 Русский
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('ja')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇯🇵 日本語
                                </a>
                                <a href="javascript:void(0)" onclick="translateLanguage('hi')" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    🇮🇳 हिन्दी
                                </a>
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
                <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">{{ __('Language') }}</p>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    <a href="javascript:void(0)" onclick="translateLanguage('en')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇬🇧 English</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('es')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇪🇸 Español</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('fr')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇫🇷 Français</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('de')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇩🇪 Deutsch</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('zh-CN')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇨🇳 中文</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('ar')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇸🇦 العربية</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('pt')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇵🇹 Português</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('ru')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇷🇺 Русский</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('ja')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇯🇵 日本語</a>
                    <a href="javascript:void(0)" onclick="translateLanguage('hi')" class="px-3 py-2 rounded-md text-sm font-medium text-gray-200 hover:bg-white/10">🇮🇳 हिन्दी</a>
                </div>
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
                        @foreach(\Modules\Legal\Models\LegalPage::orderBy('title')->get() as $legalPage)
                            <li>
                                <a href="{{ in_array($legalPage->slug, ['privacy-policy', 'terms-conditions']) ? url('/' . $legalPage->slug) : route('legal.page', $legalPage->slug) }}" class="text-base text-gray-300 hover:text-white transition-colors">
                                    {{ __($legalPage->title) }}
                                </a>
                            </li>
                        @endforeach
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
    <!-- Chat Widget Inline -->
    <script>
        function chatWidget() {
            return {
                isOpen: false,
                messages: [],
                userInput: '',
                isTyping: false,
                sessionId: localStorage.getItem('chat_session_id'),
    
                initChat() {
                    console.log('Chat Widget Initialized');
                    if (this.sessionId) {
                        this.fetchHistory();
                    }
                },
    
                toggleChat() {
                    this.isOpen = !this.isOpen;
                    if (this.isOpen && !this.sessionId && this.messages.length === 0) {
                        this.startSession();
                    }
                    this.$nextTick(() => this.scrollToBottom());
                },
    
                async startSession() {
                    try {
                        const response = await fetch('{{ route("ai.chat.start") }}', { 
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();
                        if (data.status === 'success') {
                            this.sessionId = data.session_id;
                            localStorage.setItem('chat_session_id', this.sessionId);
                            // Add welcome message if new session
                            if (this.messages.length === 0) {
                                this.messages.push({
                                    role: 'assistant',
                                    message: 'Hello! How can I help you today?'
                                });
                            }
                        }
                    } catch (error) {
                        console.error('Failed to start chat session:', error);
                    }
                },
    
                async fetchHistory() {
                    try {
                        const response = await fetch('{{ route("ai.chat.start") }}', { 
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ session_id: this.sessionId })
                        });
                        const data = await response.json();
                        if (data.status === 'success' && data.history) {
                            this.messages = data.history;
                            this.$nextTick(() => this.scrollToBottom());
                        } else if (!data.session_id) {
                            localStorage.removeItem('chat_session_id');
                            this.sessionId = null;
                            this.messages = [];
                        }
                    } catch (error) {
                        console.error('Failed to fetch history:', error);
                    }
                },
    
                async sendMessage() {
                    if (!this.userInput.trim()) return;
    
                    // Ensure we have a session before sending
                    if (!this.sessionId) {
                        await this.startSession();
                        if (!this.sessionId) {
                            console.error('Failed to create session');
                            this.messages.push({ role: 'assistant', message: 'Error: Could not start chat session.' });
                            return;
                        }
                    }

                    const userMsg = this.userInput;
                    this.messages.push({ role: 'user', message: userMsg });
                    this.userInput = '';
                    this.isTyping = true;
                    this.$nextTick(() => this.scrollToBottom());
    
                    try {
                        const response = await fetch('{{ route("ai.chat.send") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                session_id: this.sessionId,
                                message: userMsg
                            })
                        });
                        const data = await response.json();
                        
                        this.isTyping = false;
                        
                        if (data.status === 'success') {
                            this.messages.push({ role: 'assistant', message: data.message });
                        } else {
                            this.messages.push({ role: 'assistant', message: 'Something went wrong. Please try again.' });
                        }
                        this.$nextTick(() => this.scrollToBottom());
    
                    } catch (error) {
                        console.error('Message send failed:', error);
                        this.isTyping = false;
                        this.messages.push({ role: 'assistant', message: 'Error connecting to server.' });
                    }
                },
    
                scrollToBottom() {
                    const container = this.$refs.messagesContainer;
                    container.scrollTop = container.scrollHeight;
                },

                renderMessage(content) {
                    if (typeof window.marked !== 'undefined') {
                        return window.marked.parse(content);
                    }
                    return content.replace(/\n/g, '<br>');
                }
            }
        }
    </script>
    
    <!-- Chat Toggle Button - Simplified -->
    <button 
        onclick="var widget = document.getElementById('chatWidget'); widget.style.display = widget.style.display === 'none' ? 'flex' : 'none';"
        style="position: fixed; bottom: 24px; right: 24px; width: 56px; height: 56px; background-color: #FB4C4B; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); z-index: 9999; border: none; cursor: pointer;">
        <svg style="width: 28px; height: 28px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
        </svg>
    </button>

    <!-- Chat Widget Window -->
    <div id="chatWidget" x-data="chatWidget()" x-init="initChat()" 
         style="display: none; position: fixed; bottom: 90px; right: 24px; width: 384px; max-width: calc(100vw - 48px); height: 500px; max-height: 80vh; background: white; border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); z-index: 9999; flex-direction: column; overflow: hidden;">
        
        <!-- Header -->
        <div style="background-color: #FB4C4B; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div>
                    <h3 style="color: white; font-weight: bold; font-size: 14px; margin: 0;">Support Chat</h3>
                    <p style="color: rgba(255,255,255,0.8); font-size: 12px; margin: 0;">AI Assistant</p>
                </div>
            </div>
            <button onclick="document.getElementById('chatWidget').style.display='none';" style="background: none; border: none; color: rgba(255,255,255,0.8); cursor: pointer; padding: 4px;">
                <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Messages Area -->
        <div x-ref="messagesContainer" style="flex: 1; overflow-y: auto; padding: 16px; background: #f9fafb; display: flex; flex-direction: column; gap: 16px;">
            <style>
                @keyframes typing {
                    0%, 80%, 100% { transform: scale(0); }
                    40% { transform: scale(1); }
                }
                .chat-msg-content ul { list-style-type: disc; margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
                .chat-msg-content ol { list-style-type: decimal; margin-left: 1.5rem; margin-top: 0.5rem; margin-bottom: 0.5rem; }
                .chat-msg-content li { margin-bottom: 0.25rem; }
                .chat-msg-content p { margin-bottom: 0.5rem; }
                .chat-msg-content p:last-child { margin-bottom: 0; }
                .chat-msg-content strong { font-weight: 600; }
                .chat-msg-content a { text-decoration: underline; }
                .chat-msg-content h1, .chat-msg-content h2, .chat-msg-content h3 { font-weight: 700; margin-top: 0.5rem; margin-bottom: 0.25rem; }
            </style>
            <template x-for="(msg, index) in messages" :key="index">
                <div :style="msg.role === 'user' ? 'display: flex; flex-direction: column; align-items: flex-end;' : 'display: flex; flex-direction: column; align-items: flex-start;'">
                    <div :style="(msg.role === 'user' ? 'background-color: #FB4C4B; color: white;' : 'background-color: white; color: #1f2937; border: 1px solid #e5e7eb;') + ' max-width: 85%; border-radius: 16px; padding: 10px 16px; font-size: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);'">
                        <div x-html="renderMessage(msg.message)" class="chat-msg-content" style="line-height: 1.6;"></div>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" style="display: none;" :style="isTyping ? 'display: flex; flex-direction: column; align-items: flex-start;' : 'display: none'">
                <div style="background-color: white; border: 1px solid #e5e7eb; border-radius: 16px; padding: 12px 16px; border-bottom-left-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <div style="display: flex; gap: 4px; align-items: center; height: 12px;">
                        <div style="width: 6px; height: 6px; background-color: #9ca3af; border-radius: 50%; animation: typing 1.4s infinite ease-in-out both;"></div>
                        <div style="width: 6px; height: 6px; background-color: #9ca3af; border-radius: 50%; animation: typing 1.4s infinite ease-in-out both; animation-delay: 0.2s;"></div>
                        <div style="width: 6px; height: 6px; background-color: #9ca3af; border-radius: 50%; animation: typing 1.4s infinite ease-in-out both; animation-delay: 0.4s;"></div>
                    </div>
                </div>
                <span style="font-size: 11px; color: #6b7280; margin-top: 4px; margin-left: 4px;">AI is thinking...</span>
            </div>
        </div>

        <!-- Input Area -->
        <div style="padding: 16px; background: white; border-top: 1px solid #e5e7eb;">
            <form @submit.prevent="sendMessage" style="display: flex; align-items: center; gap: 8px;">
                <input type="text" x-model="userInput" :disabled="isTyping" placeholder="Type your message..." 
                       style="flex: 1; border: 1px solid #d1d5db; border-radius: 24px; padding: 8px 16px; font-size: 14px; outline: none;">
                <button type="submit" :disabled="!userInput.trim() || isTyping"
                        style="background-color: #FB4C4B; color: white; width: 36px; height: 36px; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 20px; height: 20px; transform: rotate(90deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    <div id="google_translate_element"></div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,es,fr,de,zh-CN,ar,pt,ru,ja,hi',
                autoDisplay: false
            }, 'google_translate_element');
        }

        function translateLanguage(lang) {
            // Update UI immediately for better UX
            const langText = {
                'en': 'English',
                'es': 'Español',
                'fr': 'Français',
                'de': 'Deutsch',
                'zh-CN': '中文',
                'ar': 'العربية',
                'pt': 'Português',
                'ru': 'Русский',
                'ja': '日本語',
                'hi': 'हिन्दी'
            };
            
            if (langText[lang]) {
                const langSpan = document.querySelector('nav .uppercase.font-semibold');
                if (langSpan) langSpan.textContent = lang;
                localStorage.setItem('user_lang', lang);
            }

            var select = document.querySelector('select.goog-te-combo');
            if (select) {
                select.value = lang;
                select.dispatchEvent(new Event('change'));
            } else {
                // Widget might not be loaded yet, retry a few times
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

        // Auto-detect browser language on first visit
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const supportedLangs = ['en', 'es', 'fr', 'de', 'zh-CN', 'ar', 'pt', 'ru', 'ja', 'hi'];
                const storedLang = localStorage.getItem('user_lang');
                const hasGoogTrans = document.cookie.split(';').some((item) => item.trim().startsWith('googtrans='));

                if (!storedLang && !hasGoogTrans) {
                    let browserLang = navigator.language || navigator.userLanguage;
                    browserLang = browserLang.split('-')[0]; // Get 'en' from 'en-US'
                    
                    // Special case for Chinese
                    if (navigator.language === 'zh-CN' || navigator.language === 'zh-TW') {
                        browserLang = 'zh-CN';
                    }

                    if (supportedLangs.includes(browserLang) && browserLang !== 'en') {
                        console.log('Auto-detected language:', browserLang);
                        translateLanguage(browserLang);
                    }
                }
            }, 1000); // Wait for widget to potentialy load
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    <x-home::chat-widget />
</body>
</html>
