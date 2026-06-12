const CACHE_NAME = 'sport-app-v1';
const URLS_TO_CACHE = [
  '/',
  '/offline.html',
  '/manifest.json',
  '/images/icon-192.png',
  '/images/icon-512.png'
];

// Installation du service worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(URLS_TO_CACHE).catch(() => {
        // Les URLs peuvent ne pas être disponibles, c'est ok
        console.log('Certaines ressources n\'ont pas pu être cachées');
      });
    })
  );
  self.skipWaiting();
});

// Activation du service worker
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Gestion des requêtes (Network First avec fallback au cache)
self.addEventListener('fetch', (event) => {
  // Ne mettre en cache que les GET
  if (event.request.method !== 'GET') {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        // Ne mettre en cache que les réponses valides
        if (!response || response.status !== 200 || response.type === 'error') {
          return response;
        }

        // Cloner la réponse
        const responseToCache = response.clone();

        // Vérifier si c'est une ressource statique (assets, CSS, JS, images)
        const url = new URL(event.request.url);
        if (
          url.pathname.startsWith('/build/') ||
          url.pathname.startsWith('/images/') ||
          url.pathname.startsWith('/fonts/') ||
          event.request.destination === 'style' ||
          event.request.destination === 'script' ||
          event.request.destination === 'image'
        ) {
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseToCache);
          });
        }

        return response;
      })
      .catch(() => {
        // Si la requête échoue, essayer le cache
        return caches.match(event.request).then((response) => {
          return response || new Response('Offline - Page not available', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
              'Content-Type': 'text/plain'
            })
          });
        });
      })
  );
});
