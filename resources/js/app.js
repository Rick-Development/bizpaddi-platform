import './bootstrap';

import Alpine from 'alpinejs';
import intersect from '@alpinejs/intersect';

import { marked } from 'marked';

Alpine.plugin(intersect);

window.Alpine = Alpine;
window.marked = marked;

import { initFirebaseMessaging } from './fcm';

Alpine.start();

// Universal Password Visibility Toggle
export function initPasswordToggles() {
    document.querySelectorAll('input[type="password"]').forEach((input) => {
        if (input.dataset.passwordToggleInit) return;
        const parent = input.parentElement;
        if (!parent || parent.querySelector('.password-toggle-btn')) return;

        input.dataset.passwordToggleInit = 'true';

        if (window.getComputedStyle(parent).position === 'static') {
            parent.style.position = 'relative';
        }

        input.style.paddingRight = '2.75rem';

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'password-toggle-btn';
        btn.setAttribute('aria-label', 'Toggle password visibility');
        btn.setAttribute('tabindex', '-1');
        btn.style.position = 'absolute';
        btn.style.right = '0.75rem';
        btn.style.top = '50%';
        btn.style.transform = 'translateY(-50%)';
        btn.style.background = 'transparent';
        btn.style.border = 'none';
        btn.style.cursor = 'pointer';
        btn.style.display = 'inline-flex';
        btn.style.alignItems = 'center';
        btn.style.justifyContent = 'center';
        btn.style.color = '#9ca3af';
        btn.style.padding = '0.25rem';
        btn.style.zIndex = '10';

        const eyeOpenSvg = `<svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
        const eyeSlashSvg = `<svg style="width:18px;height:18px;color:#F2A900;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;

        btn.innerHTML = eyeOpenSvg;

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = eyeSlashSvg;
                btn.style.color = '#F2A900';
            } else {
                input.type = 'password';
                btn.innerHTML = eyeOpenSvg;
                btn.style.color = '#9ca3af';
            }
        });

        parent.appendChild(btn);
    });
}
window.initPasswordToggles = initPasswordToggles;

// Initialize Firebase Messaging for push notifications & password toggles
document.addEventListener('DOMContentLoaded', () => {
    initFirebaseMessaging();
    initPasswordToggles();
});

// Also observe for dynamic modals / forms inserted into DOM
if (typeof MutationObserver !== 'undefined') {
    const observer = new MutationObserver(() => {
        initPasswordToggles();
    });
    document.addEventListener('DOMContentLoaded', () => {
        observer.observe(document.body, { childList: true, subtree: true });
    });
}
