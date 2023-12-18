var db;
var app_id = window.doctor_id;
var dbname = "patientDB_"+app_id;
var table_name = "patient_"+app_id;
var notification_table ="notification";

//prefixes of implementation that we want to test
window.indexedDB = window.indexedDB || window.mozIndexedDB ||
    window.webkitIndexedDB || window.msIndexedDB;

//prefixes of window.IDB objects
window.IDBTransaction = window.IDBTransaction ||
    window.webkitIDBTransaction || window.msIDBTransaction;
window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange ||
    window.msIDBKeyRange
if (!window.indexedDB) {
    window.alert("Your browser doesn't support a stable version of IndexedDB.")
}
var version = (!getCookie('dbversion'))?1:getCookie('dbversion');
var request = window.indexedDB.open(dbname, version);
request.onerror = function(event,error) {
    console.log("error: "+request.error);
};
request.onsuccess = function(event) {
    db = request.result;
    var connection = request.result;
    connection.onversionchange = function(e) {
        connection.close();
    };
    if(version==1){
        var dbrequest = window.indexedDB.open(dbname, 2);
        dbrequest.onupgradeneeded = function (e){
            db2 = dbrequest.result;
            if (e.oldVersion < 2) {
                var objectStore2 = db2.createObjectStore(notification_table,{
                    keyPath: "id",
                    unique: true,
                    autoIncrement:true
                });
            }
        }
        dbrequest.onsuccess = function(event) {
            setCookie('dbversion', 2, 3000);
            db = dbrequest.result;
        }
    }
};
request.onupgradeneeded = function(event) {
    var db = event.target.result;
    var objectStore = db.createObjectStore(table_name, {keyPath: "patient_id"});
}
function addNotification(object) {
    var request = db.transaction([notification_table], "readwrite")
        .objectStore(notification_table)
        .add(object);
}

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
