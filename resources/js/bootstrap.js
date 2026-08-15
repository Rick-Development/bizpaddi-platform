import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Auto-inject CSRF token from meta tag into every axios request
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
}

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

try {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY || '53b545eb95d673cd9f92',
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
        forceTLS: true
    });

    if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
        window.Echo.connector.pusher.connection.bind('state_change', (states) => {
            console.log('%c[Pusher WebSockets Status]: ' + states.current, 'color: #10B981; font-weight: bold;');
        });

        window.Echo.connector.pusher.connection.bind('error', (err) => {
            console.warn('[Pusher WebSockets Connection Error]:', err);
        });
    }
} catch(e) {
    console.warn('Echo initialization failed:', e);
}



