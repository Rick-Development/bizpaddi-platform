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

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY || 'qxuhs8bjqhqczmjsk0m3';
const reverbHost = import.meta.env.VITE_REVERB_HOST || window.location.hostname;
const isHttps = window.location.protocol === 'https:';

try {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: reverbKey,
        wsHost: reverbHost,
        wsPort: isHttps ? 443 : 8080,
        wssPort: isHttps ? 443 : 8080,
        forceTLS: isHttps,
        enabledTransports: ['ws', 'wss'],
    });
} catch(e) {
    console.warn('Echo initialization failed:', e);
}

