var firebaseConfig = {
    apiKey: "AIzaSyAjVkayDn99JcIgbFcSdbfl3B3jxHWCtSQ",
    authDomain: "mbroadcast-b50a6.firebaseapp.com",
    databaseURL: "https://mbroadcast-b50a6.firebaseio.com",
    projectId: "mbroadcast-b50a6",
    storageBucket: "mbroadcast-b50a6.appspot.com",
    messagingSenderId: "670774162115",
    appId: "1:670774162115:web:7cae6926392fce92"
};


firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();
messaging.usePublicVapidKey("BDGGM5BTpjHhS6iHQzo_Gk2IwKJz4FJvqZhLpsBVZsbH4VzLxpHbGMcVKXBbS3jStIV3UUOuKdHAdbW5ssK2Rog");
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


if ("serviceWorker" in navigator) {
    window.addEventListener("load", function() {


        navigator.serviceWorker.getRegistrations().then(function(registrations) {

            for(let registration of registrations) {
                //registration.unregister();
            }

            navigator.serviceWorker
                .register("https://site.test/php_factory/service-worker.js")
                .then(registration => {
                    console.log("service worker registered");
                    messaging.useServiceWorker(registration);
                    Notification.requestPermission().then( function(permission){
                        if (permission === 'granted') {
                            messaging.getToken().then( function(currentToken){
                                if (currentToken) {
                                    localStorage.setItem('token',currentToken);
                                } else {
                                    console.log('No Instance ID token available. Request permission to generate one.');
                                }
                            }).catch((err) => {
                                console.log('An error occurred while retrieving token. ', err);
                            });
                            messaging.onMessage(function(payload){
                                var action = JSON.parse(payload.data['actions']);
                                const notificationTitle = payload.data['title'];
                                const notificationOptions = {
                                    body: payload.data['body'],
                                    icon: payload.data['logo'],
                                    actions:[
                                        {action:action[0]['action'],title:action[0]['title'],icon:action[0]['icon']}
                                    ]
                                };
                                registration.showNotification(notificationTitle, notificationOptions);
                            });
                        } else {
                            console.log('Unable to get permission to notify.');
                        }
                    });


                }).catch(err => console.log("service worker not registered", err));

        })



    });
}



