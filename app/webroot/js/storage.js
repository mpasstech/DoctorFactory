
//prefixes of implementation that we want to test
window.indexedDB = window.indexedDB || window.mozIndexedDB ||
    window.webkitIndexedDB || window.msIndexedDB;
//prefixes of window.IDB objects
window.IDBTransaction = window.IDBTransaction ||
    window.webkitIDBTransaction || window.msIDBTransaction;
window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange ||
    window.msIDBKeyRange

function isSupport() {
    return window.indexedDB;
}



function read(key) {
    if(isSupport()){
        var transaction = db.transaction(["patient"]);
        var objectStore = transaction.objectStore("patient");
        var request = objectStore.get(key);
        request.onerror = function(event) {
            return false;
        };
        request.onsuccess = function(event) {
            return request.result;
        };
    }else{
        return false;
    }


}

function readAll() {
    if(isSupport()){
        var objectStore = db.transaction("patient").objectStore("patient");
        var list =[];
        objectStore.openCursor().onsuccess = function(event) {
        var cursor = event.target.result;
        if (cursor) {
            list.push(cursor);
            cursor.continue();
        }
        return list;
    };
    }else{
        return false;
    }
}

function add(object) {
    if(isSupport()){
    var request = db.transaction(["patient"], "readwrite")
        .objectStore("patient")
        .add(object);
        request.onsuccess = function(event) {
            return true;
        };
        request.onerror = function(event) {
           return false;
        }
    }else{
        return false;
    }
}

function remove(key) {
    if(isSupport()){
        var request = db.transaction(["patient"], "readwrite").objectStore("patient").delete(key);
        request.onsuccess = function(event) { return true; };
        request.onerror = function(event) {
            return false;
        }
    }else{
        return false;
    }
}