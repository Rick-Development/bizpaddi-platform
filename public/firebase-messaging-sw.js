importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDaTw9u2l83Cb8RuPQ99p1sNZUxYo0w5Bk",
    authDomain: "bizpaddi-app.firebaseapp.com",
    projectId: "bizpaddi-app",
    storageBucket: "bizpaddi-app.firebasestorage.app",
    messagingSenderId: "243007112605",
    appId: "1:243007112605:web:5ab31079e4dd9625fe1b74",
    measurementId: "G-C8L2HPF310"
};

try {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage((payload) => {
        console.log('[firebase-messaging-sw.js] Received background message ', payload);
        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: '/icon.png', // Add a default icon to public/icon.png later
            data: payload.data
        };

        self.registration.showNotification(notificationTitle, notificationOptions);
    });
} catch (e) {
    console.error('Firebase Service Worker Initialization Error:', e);
}

// Handle notification click
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    // Open the url provided in data payload, if any
    const urlToOpen = new URL(event.notification.data?.url || '/', self.location.origin).href;
    
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then((windowClients) => {
            let matchingClient = null;
            for (let i = 0; i < windowClients.length; i++) {
                const windowClient = windowClients[i];
                if (windowClient.url === urlToOpen) {
                    matchingClient = windowClient;
                    break;
                }
            }
            if (matchingClient) {
                return matchingClient.focus();
            } else {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});
