<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('title')</title>
    @php
        $favicon = \Modules\Settings\Models\Setting::get('favicon');
    @endphp
    @if($favicon)
        <link rel="icon" href="{{ asset('storage/' . $favicon) }}" type="image/x-icon">
    @else
        <link rel="icon" href="/logo.png" type="image/png">
    @endif
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Configure axios globally: CSRF token + JSON request header
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token && window.axios) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
                axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            }
        });
    </script>

    @vite(['resources/js/app.js'])
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#FB4C4B',
                            secondary: '#191C47',
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="h-full" x-data="{ sidebarOpen: false }">
    <div class="min-h-full">
        <!-- Mobile Sidebar -->
        @if(auth()->check() && auth()->user()->status === 'active')
        <div x-show="sidebarOpen" class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80"></div>

            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div x-show="sidebarOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile Sidebar Content -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-secondary px-6 pb-2 ring-1 ring-white/10">
                         @include('teamportal::layouts.partials.sidebar')
                    </div>
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-brand-secondary px-6 pb-4">
               @include('teamportal::layouts.partials.sidebar')
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="{{ auth()->check() && auth()->user()->status === 'active' ? 'lg:pl-72' : '' }}">
            <!-- Mobile Header -->
            @if(auth()->check() && auth()->user()->status === 'active')
            <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 lg:hidden">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <div class="flex flex-1 items-center justify-center lg:justify-end">
                         <span class="text-lg font-bold text-gray-900">Team Portal</span>
                    </div>
                </div>
            </div>
            @endif

            <main class="py-10">
                <div class="px-4 sm:px-6 lg:px-8">
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-4 rounded-md bg-green-50 p-4 border-l-4 border-green-400">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    <!-- Global Toast Notifications Container -->
    <div id="global-toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

    <!-- Desktop Notification Modal Prompt -->
    <div id="desktop-notif-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-300 opacity-0">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-gray-100 transform scale-95 transition-all duration-300 text-center relative overflow-hidden">
            
            <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-[#191C47] to-[#FB4C4B] text-white flex items-center justify-center mx-auto mb-4 shadow-lg shadow-red-500/20">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>

            <h3 class="text-base font-bold text-gray-900">Enable Desktop Notifications?</h3>
            <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                Get instant desktop alerts for new team chat messages and task comments even when your browser tab is in the background.
            </p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="closeNotifModal()" 
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 text-xs font-semibold transition-all">
                    Cancel / Not Now
                </button>
                <button type="button" onclick="grantNotifPermissionFromModal()" 
                        class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#FB4C4B] to-[#e03b3a] text-white text-xs font-bold shadow-md hover:shadow-lg active:scale-95 transition-all">
                    Enable Notifications 🔔
                </button>
            </div>
        </div>
    </div>

    <script>
        // Web Audio Synthesized Notification Chime
        function playNotificationChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                osc.frequency.exponentialRampToValueAtTime(880, ctx.currentTime + 0.12);
                gain.gain.setValueAtTime(0.12, ctx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.35);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start();
                osc.stop(ctx.currentTime + 0.35);
            } catch(e) {}
        }

        // Open/Close Notification Modal
        function openNotifModal() {
            const modal = document.getElementById('desktop-notif-modal');
            if (!modal) return;
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                const card = modal.querySelector('.transform');
                if (card) {
                    card.classList.remove('scale-95');
                    card.classList.add('scale-100');
                }
            }, 50);
        }

        function closeNotifModal() {
            const modal = document.getElementById('desktop-notif-modal');
            if (!modal) return;
            modal.classList.add('opacity-0');
            const card = modal.querySelector('.transform');
            if (card) {
                card.classList.remove('scale-100');
                card.classList.add('scale-95');
            }
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }


        function grantNotifPermissionFromModal() {
            if (typeof Notification === 'undefined' || !('Notification' in window)) {
                closeNotifModal();
                showGlobalToast('In-App Alerts Active 🔔', 'Your mobile browser uses in-app toasts & badges for real-time notifications.', '#', 'chat');
                return;
            }

            try {
                Notification.requestPermission().then(permission => {
                    closeNotifModal();
                    if (permission === 'granted') {
                        showGlobalToast('Notifications Enabled 🔔', 'You will now receive alerts for new chats & comments.', '#', 'chat');
                    } else {
                        showGlobalToast('In-App Alerts Active 🔔', 'In-app toasts & sidebar badges will keep you updated live.', '#', 'chat');
                    }
                }).catch(() => {
                    closeNotifModal();
                    showGlobalToast('In-App Alerts Active 🔔', 'Real-time in-app toasts & badges are active on your device.', '#', 'chat');
                });
            } catch (e) {
                closeNotifModal();
                showGlobalToast('In-App Alerts Active 🔔', 'Real-time in-app toasts & badges are active on your device.', '#', 'chat');
            }
        }


        // Show Floating Interactive Toast & Native Desktop Notification
        function showGlobalToast(title, body, url = '#', iconType = 'chat') {
            playNotificationChime();

            // Native OS Desktop Notification (works when browser is in background)
            if (typeof Notification !== 'undefined' && Notification.permission === 'granted') {
                try {
                    const desktopNotif = new Notification(title, {
                        body: body,
                        icon: '/logo.png',
                    });
                    desktopNotif.onclick = function() {
                        window.focus();
                        if (url && url !== '#') {
                            window.location.href = url;
                        }
                    };
                } catch(e) {}
            }

            // In-App Floating Toast
            const container = document.getElementById('global-toast-container');
            if (!container) return;

            const toastId = 'toast-' + Date.now();
            const bgGradient = iconType === 'chat' ? 'from-[#191C47] to-[#2B2F6C]' : 'from-[#FB4C4B] to-[#D93635]';

            const toastHtml = `
                <div id="${toastId}" class="pointer-events-auto bg-white rounded-2xl shadow-xl border border-gray-200/80 p-4 flex items-start gap-3 transform translate-x-full transition-all duration-300 opacity-0 cursor-pointer hover:border-[#FB4C4B]"
                     onclick="window.location.href='${url}'">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br ${bgGradient} text-white flex items-center justify-center flex-shrink-0 font-bold text-xs shadow-sm">
                        ${iconType === 'chat' ? '💬' : '📝'}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-gray-900 truncate">${title}</h4>
                        <p class="text-xs text-gray-600 truncate mt-0.5">${body}</p>
                        <span class="text-[10px] text-[#FB4C4B] font-semibold mt-1 inline-block">Click to view →</span>
                    </div>
                    <button type="button" onclick="event.stopPropagation(); document.getElementById('${toastId}').remove();" class="text-gray-400 hover:text-gray-600 p-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('afterbegin', toastHtml);

            const toastEl = document.getElementById(toastId);
            setTimeout(() => {
                if (toastEl) {
                    toastEl.classList.remove('translate-x-full', 'opacity-0');
                }
            }, 50);

            setTimeout(() => {
                if (toastEl) {
                    toastEl.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toastEl.remove(), 300);
                }
            }, 6000);
        }

        // On Page Load: Check if permission is default, if so show modal prompt
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Notification !== 'undefined' && Notification.permission === 'default') {
                setTimeout(openNotifModal, 1000);
            }
        });
    </script>



    @stack('scripts')
</body>
</html>

