import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MESSAGING_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID
};

export function initFirebaseMessaging() {
    if (!firebaseConfig.apiKey) {
        console.log('Firebase configuration is missing. Skipping FCM initialization.');
        return;
    }

    try {
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        // Request permission and get token
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');
                
                // Get registration token
                getToken(messaging, { vapidKey: 'BIeB48ysI5ntCXtSxJD5O9tld6wyhmra_KxS5do7KXJ0NckSFyTPS_leckD6C9ahKhM_NUeXQC1sdj0vnEdPfYY' }).then((currentToken) => {
                    if (currentToken) {
                        sendTokenToServer(currentToken);
                    } else {
                        console.log('No registration token available. Request permission to generate one.');
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                });
            } else {
                console.log('Unable to get permission to notify.');
            }
        });

        // Handle foreground messages
        onMessage(messaging, (payload) => {
            console.log('Message received in foreground. ', payload);
            
            // Dispatch a custom event to update the UI (like the Bell Icon)
            const event = new CustomEvent('fcm-message-received', { detail: payload });
            window.dispatchEvent(event);
            
            // Optional: Show a toast notification using a library or custom HTML
            if(window.Alpine && window.Alpine.store('notifications')) {
                // Alpine.js integration if exists
            }
        });
    } catch (e) {
        console.error('Firebase Initialization Error:', e);
    }
}

function sendTokenToServer(token) {
    fetch('/fcm-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ token: token })
    }).then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }).then(data => {
        console.log('FCM Token successfully registered on server.');
    }).catch(error => {
        console.error('Error sending FCM token to server:', error);
    });
}
