/* Service worker Smart Mosque Platform.
   Strategi: network-first untuk halaman (agar jadwal selalu mutakhir),
   cache-first untuk aset statis, dan halaman offline sebagai cadangan. */

const VERSION = 'alikhlash-v1';
const OFFLINE_URL = '/offline.html';
const PRECACHE = [OFFLINE_URL, '/manifest.webmanifest', '/icon.svg'];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(VERSION).then(cache => cache.addAll(PRECACHE)).then(() => self.skipWaiting())
    );
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(keys.filter(k => k !== VERSION).map(k => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', event => {
    const { request } = event;

    if (request.method !== 'GET' || new URL(request.url).origin !== self.location.origin) return;

    /* Navigasi halaman: coba jaringan dulu, jatuh ke cache lalu halaman offline. */
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then(response => {
                    const copy = response.clone();
                    caches.open(VERSION).then(cache => cache.put(request, copy));
                    return response;
                })
                .catch(() => caches.match(request).then(hit => hit || caches.match(OFFLINE_URL)))
        );
        return;
    }

    /* Aset statis: cache dulu, perbarui di latar belakang. */
    if (/\.(?:css|js|png|jpg|jpeg|svg|webp|woff2?)$/.test(new URL(request.url).pathname)) {
        event.respondWith(
            caches.match(request).then(hit => {
                const network = fetch(request).then(response => {
                    const copy = response.clone();
                    caches.open(VERSION).then(cache => cache.put(request, copy));
                    return response;
                }).catch(() => hit);

                return hit || network;
            })
        );
    }
});
