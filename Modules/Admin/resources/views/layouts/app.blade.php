<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - {{ config('app.name', 'Bizpaddi') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/bizpaddi/icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/bizpaddi/icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        
        .sidebar-gradient {
            background: linear-gradient(180deg, #10231C 0%, #08110e 100%);
        }
        
        .stat-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stat-card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-[#08110e] text-white antialiased overflow-x-hidden w-full max-w-[100vw]" x-data="{ sidebarOpen: false }">
    <div class="min-h-full bg-[#08110e] w-full max-w-[100vw] overflow-x-hidden">
        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="relative z-50 lg:hidden" 
             role="dialog" 
             aria-modal="true"
             style="display: none;">
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>
            <div class="fixed inset-0 flex">
                <div x-show="sidebarOpen"
                     x-transition:enter="transition-ease-in-out duration-300 transform"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in-out duration-300 transform"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative mr-16 flex w-full max-w-xs flex-1">
                    <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                        <button type="button" @click="sidebarOpen = false" class="-m-2.5 p-2.5">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @include('admin::layouts.partials.sidebar')
                </div>
            </div>
        </div>

        <!-- Desktop sidebar -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col border-r border-white/10">
            @include('admin::layouts.partials.sidebar')
        </div>

        <!-- Main content -->
        <div class="lg:pl-72 flex flex-col min-h-screen bg-[#08110e] w-full max-w-full overflow-x-hidden min-w-0">
            <!-- Top bar -->
            <div class="sticky top-0 z-40 border-b border-white/10 bg-[#10231C]/90 backdrop-blur-md shadow-sm">
                <div class="mx-auto max-w-7xl 2xl:max-w-[1600px] flex h-16 shrink-0 items-center gap-x-4 px-4 sm:gap-x-6 sm:px-6 lg:px-8">
                    <button type="button" @click="sidebarOpen = true" class="-m-2.5 p-2.5 text-gray-300 hover:text-white lg:hidden relative z-30 cursor-pointer focus:outline-none">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div class="h-6 w-px bg-white/10 lg:hidden"></div>

                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                        <div class="flex flex-1 items-center">
                            <h2 class="text-lg font-bold text-white tracking-tight">@yield('title', 'Dashboard')</h2>
                        </div>
                        <div class="flex items-center gap-x-4 lg:gap-x-6">
                            <x-bell-notification />
                            
                            <!-- User menu -->
                            <div class="flex items-center gap-x-3">
                                <a href="{{ route('admin.account.profile.edit') }}" class="flex items-center gap-x-2.5 px-2.5 py-1.5 rounded-xl hover:bg-white/10 transition-colors group" title="Edit Admin Profile & Details">
                                    <div class="w-8 h-8 rounded-full bg-[#0E5E4A] text-white flex items-center justify-center font-bold text-xs border border-emerald-400/30">
                                        {{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}
                                    </div>
                                    <div class="hidden sm:block text-left">
                                        <p class="text-sm font-semibold text-white group-hover:text-emerald-300 transition-colors leading-tight">{{ auth('admin')->user()->name }}</p>
                                        <p class="text-[11px] text-gray-400 font-medium leading-tight">{{ str_replace('_', ' ', ucfirst(auth('admin')->user()->role)) }} &bull; <span class="text-emerald-400 underline">Edit Details</span></p>
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-x-1.5 rounded-xl px-2.5 py-1.5 text-xs font-semibold text-gray-300 hover:text-red-400 hover:bg-white/10 transition-colors">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                        </svg>
                                        <span class="hidden sm:inline">Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page content -->
            <main class="py-8 flex-1 w-full max-w-full overflow-x-hidden min-w-0">
                <div class="px-4 sm:px-6 lg:px-8 max-w-7xl 2xl:max-w-[1600px] mx-auto w-full min-w-0">
                    @if(session('success'))
                    <div class="mb-6 rounded-2xl bg-emerald-500/10 p-4 border border-emerald-500/30 flex items-center gap-3">
                        <div class="flex-shrink-0 text-emerald-400">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-emerald-300">{{ session('success') }}</p>
                        </div>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="mb-6 rounded-2xl bg-rose-500/10 p-4 border border-rose-500/30 flex items-center gap-3">
                        <div class="flex-shrink-0 text-rose-400">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-rose-300">{{ session('error') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="mb-6 rounded-2xl bg-rose-500/10 p-4 border border-rose-500/30">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 text-rose-400 mt-0.5">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-rose-200">Please review the errors below:</h3>
                                <ul class="mt-1 list-disc list-inside text-xs text-rose-300 space-y-0.5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </main>
    <!-- Global Toast Notifications Container -->
    <div id="global-toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none"></div>

    <!-- Desktop Notification Modal Prompt -->
    <div id="desktop-notif-modal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4 bg-black/70 backdrop-blur-md transition-opacity duration-300 opacity-0">
        <div class="bg-[#10231C] rounded-3xl shadow-2xl max-w-md w-full p-6 border border-white/10 transform scale-95 transition-all duration-300 text-center relative overflow-hidden">
            
            <div class="h-16 w-16 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/10">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>

            <h3 class="text-base font-bold text-white">Enable Admin Desktop Notifications?</h3>
            <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                Get instant desktop alerts for new admin messages and global task comments even when your browser is in the background.
            </p>

            <div class="mt-6 flex items-center justify-center gap-3">
                <button type="button" onclick="closeNotifModal()" 
                        class="px-4 py-2.5 rounded-xl border border-white/10 text-gray-400 hover:text-white hover:bg-white/5 text-xs font-semibold transition-all">
                    Cancel / Not Now
                </button>
                <button type="button" onclick="grantNotifPermissionFromModal()" 
                        class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-md hover:shadow-lg active:scale-95 transition-all">
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
            const bgGradient = iconType === 'chat' ? 'from-emerald-800 to-teal-900' : 'from-emerald-600 to-teal-700';

            const toastHtml = `
                <div id="${toastId}" class="pointer-events-auto bg-[#10231C] rounded-2xl shadow-xl border border-white/10 p-4 flex items-start gap-3 transform translate-x-full transition-all duration-300 opacity-0 cursor-pointer hover:border-emerald-500/50"
                     onclick="window.location.href='${url}'">
                    <div class="h-9 w-9 rounded-xl bg-gradient-to-br ${bgGradient} text-white flex items-center justify-center flex-shrink-0 font-bold text-xs shadow-sm">
                        ${iconType === 'chat' ? '💬' : '📝'}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-xs font-bold text-white truncate">${title}</h4>
                        <p class="text-xs text-gray-400 truncate mt-0.5">${body}</p>
                        <span class="text-[10px] text-emerald-400 font-semibold mt-1 inline-block">Click to view →</span>
                    </div>
                    <button type="button" onclick="event.stopPropagation(); document.getElementById('${toastId}').remove();" class="text-gray-400 hover:text-white p-1">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('afterbegin', toastHtml);

            const toastEl = document.getElementById(toastId);
            setTimeout(() => {
                if (toastEl) {
                    toastEl.classList.remove('opacity-0', 'translate-x-full');
                }
            }, 50);

            setTimeout(() => {
                if (toastEl) {
                    toastEl.classList.add('opacity-0', 'translate-x-full');
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
    <x-notification-prompt />
    @stack('scripts')
</body>
</html>
