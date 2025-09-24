import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

// PWA Installation Prompt
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent Chrome 67 and earlier from automatically showing the prompt
    e.preventDefault();
    // Stash the event so it can be triggered later
    deferredPrompt = e;
    // Show install button or notification
    showInstallPrompt();
});

function showInstallPrompt() {
    // You can show a custom install button here
    console.log('PWA install prompt available');
}

// Handle PWA install
window.addEventListener('appinstalled', (evt) => {
    console.log('PWA was installed');
});

// Register service worker for background sync
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.ready.then(registration => {
        // Register for background sync
        if ('sync' in window.ServiceWorkerRegistration.prototype) {
            registration.sync.register('sync-offline-data');
        }
    });
}

// Offline detection
window.addEventListener('online', () => {
    console.log('Connection restored');
    // Trigger background sync
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.ready.then(registration => {
            if ('sync' in window.ServiceWorkerRegistration.prototype) {
                registration.sync.register('sync-offline-data');
            }
        });
    }
});

window.addEventListener('offline', () => {
    console.log('Connection lost');
});

createInertiaApp({
    title: (title) => `${title} - OmniChain Driver`,
    resolve: (name) => resolvePageComponent(`./Pages/Driver/${name}.vue`, import.meta.glob('./Pages/Driver/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
    progress: {
        color: '#3b82f6',
        showSpinner: true,
    },
})
