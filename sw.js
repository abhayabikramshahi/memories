const CACHE_NAME = 'album-cache-v1';
const ASSETS = [
  './',
  './manifest.json',
  './sw.js',
  './icons/icon-192.png',
  './icons/icon-512.png',
  './icons/icon-512-maskable.png',
  'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css',
  'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js'
];

self.addEventListener('install', e => {
  e.waitUntil(caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS)));
  self.skipWaiting();
});

self.addEventListener('activate', e => e.waitUntil(self.clients.claim()));

self.addEventListener('fetch', e => {
  e.respondWith(caches.match(e.request).then(res => res || fetch(e.request)));
});
