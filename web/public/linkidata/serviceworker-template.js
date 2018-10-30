const filesToCache = [
    '/',
    '/styles/material-icons.css',
    '/fonts/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2',
    '/styles/main.css',
    '/styles/demo-grid.css',
    '/scripts/vendor/web-animations-2.3.1.min.js',
    '/scripts/vendor/hammer-2.0.8.min.js',
    '/scripts/link-list.js',
    '/scripts/vendor/muuri-0.7.0.js',
    '/scripts/vendor/web-animations.min.js.map',
    '/scripts/vendor/hammer.min.js.map',
    'scripts/MaterialIcons-Regular.ijmap',
    'images/linki-logo.png',
    '/index.html'
];

const staticCacheName = version + 'linki-static';

addEventListener('install', installEvent => {
    installEvent.waitUntil(
        caches.open(staticCacheName)
        .then ( staticCache => {
            return staticCache.addAll(filesToCache);
        })
    );
});

addEventListener('fetch', fetchEvent => {
    const request = fetchEvent.request;
    fetchEvent.respondWith(
        // is it cached?
        caches.match(request)
        .then ( responseFromCache => {
           if (responseFromCache) {
            // console.log('Found ', fetchEvent.request.url, ' in cache');
            return responseFromCache;
           }
           // not cached fetch it from the network
           // console.log('Not found ', fetchEvent.request.url, ' in cache');
           return fetch(request)
           .catch ( error => {
                return caches.match('/index.html')
           });
        })
    );
});

addEventListener('activate', activateEvent => {
    activateEvent.waitUntil(
        caches.keys()
        .then (cacheNames => {
            return Promise.all(
                cacheNames.map( cacheName => {
                    if ( cacheName != staticCacheName ) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
        .then ( () => {
            return clients.claim();
        })
    );
});