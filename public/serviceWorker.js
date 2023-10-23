const staticAssets = [
    'offline.html',
    'manifest.json',
    '/css/app.css',
    '/js/app.js',
    '/js/vendor.js',
    '/js/manifest.js',
];
//Will cache any url from this domain while fetch
const dynamicAssetDomain = [
    'tfhub.dev',
    'storage.googleapis.com'
];

const CACHE_VERSION = 1;
const CURRENT_CACHES = {
    static: 'static-assets-v' + CACHE_VERSION,
    dynamic: 'dynamic-assets-v' + CACHE_VERSION
};

self.addEventListener('install', async event => {
    const cache = await caches.open(CURRENT_CACHES.static);

    await cache.addAll(staticAssets);
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    // Delete all caches that aren't named in CURRENT_CACHES.
    var expectedCacheNamesSet = new Set(Object.values(CURRENT_CACHES));
    if (self.registration.navigationPreload) {
        // Enable navigation preloads!
        self.registration.navigationPreload.enable();
    }
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.map(function (cacheName) {
                    if (!expectedCacheNamesSet.has(cacheName)) {
                        // If this cache name isn't present in the set of "expected" cache names, then delete it.
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});


self.addEventListener('fetch', event => {
    const req = event.request;
    event.respondWith(cacheFirst(req, event));
});

async function cacheFirst(req, event) {
    try {
        const cachedResponse = await caches.match(req);
        if (cachedResponse) {
            return cachedResponse
        }

        const response = await event.preloadResponse;
        if (response) {
            return response
        }

        return networkFirst(req);
    } catch (e) {
        if (req.mode === 'navigate') {
            return caches.match('/offline.html');
        }
    }

}

async function networkFirst(req) {

    const urlObj = new URL(req.url);

    const res = await fetch(req).catch(() => {
        if (req.mode === 'navigate') {
            return caches.match('/offline.html');
        }
    });
    if (dynamicAssetDomain.indexOf(urlObj.hostname) !== -1) {
        const cache = await caches.open(CURRENT_CACHES.dynamic);

        cache.put(req, res.clone());
    }

    return res;
}

self.addEventListener('push', event => {
    var data = event.data.json();
    var promise = self.registration.showNotification(data.title, data);
    event.waitUntil(promise);
});

self.addEventListener('notificationclick', function (event) {
    const clickedNotification = event.notification;
    let url = '';
    if (typeof clickedNotification.data !== 'undefined' && typeof clickedNotification.data.url !== 'undefined') {
        url = clickedNotification.data.url;
    }
    clickedNotification.close();
    event.waitUntil(
        clients.matchAll({type: 'window'}).then(windowClients => {
            // Check if there is already a window/tab open with the target URL
            for (var i = 0; i < windowClients.length; i++) {
                var client = windowClients[i];
                // If so, just focus it.
                if (client.url === url && 'focus' in client) {
                    return client.focus().reload();
                }
            }
            // If not, then open the target URL in a new window/tab.
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
