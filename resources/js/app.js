import './bootstrap';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

import { marked } from 'marked';

Alpine.plugin(intersect);

window.Alpine = Alpine;
window.marked = marked;

import { initFirebaseMessaging } from './fcm';

Alpine.start();

// Initialize Firebase Messaging for push notifications
document.addEventListener('DOMContentLoaded', () => {
    initFirebaseMessaging();
});
