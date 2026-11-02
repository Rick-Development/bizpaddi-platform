<div class="relative" x-data="{ 
    open: false, 
    notifications: [], 
    unreadCount: 0,
    
    init() {
        this.fetchNotifications();
        
        // Listen for new FCM push notifications coming from the service worker via our fcm.js
        window.addEventListener('fcm-message-received', (e) => {
            this.fetchNotifications();
            // Show toast if desired
            if(typeof showGlobalToast === 'function') {
                showGlobalToast(e.detail.notification.title, e.detail.notification.body, e.detail.data.url, 'bell');
            }
        });
    },

    fetchNotifications() {
        fetch('/notifications')
            .then(res => res.json())
            .then(data => {
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            });
    },

    markAsRead(id, url) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).then(() => {
            if(url && url !== '#') {
                window.location.href = url;
            } else {
                this.fetchNotifications();
            }
        });
    },

    markAllAsRead() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        }).then(() => {
            this.fetchNotifications();
            this.open = false;
        });
    }
}">
    <!-- Bell Button -->
    <button type="button" @click="open = !open" @click.away="open = false" class="relative p-2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors rounded-full hover:bg-gray-100">
        <span class="sr-only">View notifications</span>
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        
        <!-- Unread Badge -->
        <span x-show="unreadCount > 0" class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-emerald-600 rounded-full" x-text="unreadCount" x-cloak></span>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-xl shadow-xl ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
         x-cloak>
        
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            <button x-show="unreadCount > 0" @click="markAllAsRead" class="text-xs font-medium text-emerald-400 hover:text-[#d93635]">Mark all as read</button>
        </div>

        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-6 text-center text-sm text-gray-500">
                    No new notifications.
                </div>
            </template>
            
            <template x-for="notif in notifications" :key="notif.id">
                <a href="#" @click.prevent="markAsRead(notif.id, notif.data.url)" 
                   class="block px-4 py-3 border-b border-gray-50 hover:bg-gray-50 transition-colors"
                   :class="{'bg-blue-50/30': notif.read_at === null}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="h-8 w-8 rounded-lg bg-emerald-600/10 text-emerald-400 flex items-center justify-center">
                                <!-- Default Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900" x-text="notif.data.title"></p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="notif.data.message || notif.data.body"></p>
                            <p class="text-[10px] text-gray-400 mt-1" x-text="notif.time_ago"></p>
                        </div>
                        <div class="flex-shrink-0" x-show="notif.read_at === null">
                            <span class="inline-block h-2 w-2 bg-emerald-600 rounded-full"></span>
                        </div>
                    </div>
                </a>
            </template>
        </div>
        
        <div class="border-t border-gray-100 bg-gray-50 px-4 py-2 text-center">
            <span class="text-xs text-gray-500">Notifications are synced across devices</span>
        </div>
    </div>
</div>
