
const staticDevCoffee = "mengage-doctorapps-v1";
const assets = [

];

self.addEventListener("install", installEvent => {


    installEvent.waitUntil(
        caches.open(staticDevCoffee).then(cache => {
            cache.addAll(assets);
        })
    );
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(cacheNames) {
            return Promise.all(
                cacheNames.filter(function(cacheName) {
                    // Return true if you want to remove this cache,
                    // but remember that caches are shared across
                    // the whole origin

                }).map(function(cacheName) {
                    return caches.delete(cacheName);
                })
            );
        })
    );
});

self.addEventListener("fetch", fetchEvent => {
    fetchEvent.respondWith(
        caches.match(fetchEvent.request).then(res => {
            return res || fetch(fetchEvent.request);
        })
    );
});



// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries
// are not available in the service worker.


importScripts('js/firebase-app.js');
importScripts('js/firebase-messaging.js');
firebase.initializeApp({
    'messagingSenderId': '670774162115'
});
const messaging = firebase.messaging();

var db=null;
messaging.setBackgroundMessageHandler(function (payload) {
    var action = JSON.parse(payload.data['actions']);
    const notificationTitle = payload.data['title'];
    const notificationOptions = {
        body: payload.data['body'],
        icon: payload.data['logo'],
        url: payload.data['url'],
        actions:[
            {action:action[0]['action'],title:action[0]['title'],icon:action[0]['icon']}
        ]
    };
    return self.registration.showNotification(notificationTitle, notificationOptions);
});




self.addEventListener("notificationclick", function (event) {
    var urlToRedirect = event.notification.actions[0].action;
    event.notification.close();
    event.waitUntil(self.clients.openWindow(urlToRedirect));
});


