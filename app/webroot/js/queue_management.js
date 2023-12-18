$(document).ready(function(){







    var db;
    var app_id = $("#body").attr('data-ti');
    var dbname = "patientDB_"+app_id;
    var table_name = "patient_"+app_id;



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

    var version = 1;
    var request = window.indexedDB.open(dbname, version);
    request.onerror = function(event,error) {
        console.log("error: "+request.error);
    };
    request.onsuccess = function(event) {
        db = request.result;
        showDashboard();

    };
    request.onupgradeneeded = function(event) {
        var db = event.target.result;
        var objectStore = db.createObjectStore(table_name, {keyPath: "patient_id"});
    }



    function read(key) {
        var transaction = db.transaction([table_name]);
        var objectStore = transaction.objectStore(table_name);
        var request = objectStore.get(key);
        request.onerror = function(event) {
            return false;
        };

        request.onsuccess = function(event) {
            return request.result;
        };
    }


    function updateList(newList,pastList) {


        if(pastList.length > 0){
            $.each(pastList,function (index,value) {
                remove(value.patient_id);
            });
        }


        $.each(newList,function (index,value) {
            addPatient(value);
        });

        showDashboardIcon();
    }


    function addPatient(object) {
        var request = db.transaction([table_name], "readwrite")
            .objectStore(table_name)
            .add(object);
    }

    function remove(key) {
        var request = db.transaction([table_name], "readwrite")
            .objectStore(table_name)
            .delete(key);
    }

    $(document).on('click','.tab_btn_div button',function(e){
        var ID = $(this).attr('data-id');

        if(ID=='android_app' || ID=='web_app' || ID=='about_hospital' ){
            return false;
        }

        if(ID=='chatboat'){

            var ti  = ($('body').attr('data-ti'));
            var dialog = $.confirm({
                title: 'Enter Mobile Number',
                content:"<label>Enter your 10 digit mobile number</label><input type='number' class='form-control' name='mobile' id='mobile_num'>",
                type: 'red',
                buttons: {
                    ok: {
                        text: "Open Chatboat    ",
                        btnClass: 'btn-primary confirm_btn',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                            var $btn2 = $(".confirm_btn");
                            var mobile = $("#mobile_num").val();
                            if(mobile.length==10){
                                $.ajax({
                                    url: baseUrl+"queue_management/get_chatbot_url",
                                    type:'POST',
                                    data:{m:btoa(mobile),ti:ti,di:$('body').attr('data-di')},
                                    beforeSend:function(){
                                        $($btn2).html('Loading...');
                                    },
                                    success: function(link){

                                       window.open(link,'_blank');
                                       dialog.close();
                                    },error:function () {
                                        dialog.close();
                                    }
                                });

                            }
                                return false;



                        }
                    },
                    cancel: function(){

                    }
                }
            });


            return false;
        }

        $(".tab-content, .add_container").hide();
        $("#"+ID).show();
        $(".tab_btn_div, .table_bottom, #trackerIframe").hide();
        $(".go_to_dash").show();
        if($(this).attr('data-title')!=''){
            $("#top_sub_title").text($(this).attr('data-title'));
        }





    });

    $(document).off('click',".go_to_dash");
    $(document).on('click',".go_to_dash",function(){
        $(".go_to_dash, .tab-content").hide();
        $(".tab_btn_div, .add_container, .table_bottom, #trackerIframe").show();
        $("#top_sub_title").text('Queue Token Tracker');
    });


    $(document).on('click','.date_box',function(){
        $('.date_box').removeClass("selected_date");
        $(this).addClass('selected_date');
        $(this).closest("ul").hide();
        $(".append_slots").html("");
        $("#slot_box").show();
        load_doctor_slot();

    });

    $(".carousel").on("touchstart", function(event){
        var xClick = event.originalEvent.touches[0].pageX;
        $(this).one("touchmove", function(event){
            var xMove = event.originalEvent.touches[0].pageX;
            if( Math.floor(xClick - xMove) > 5 ){
                $(this).carousel('next');
            }
            else if( Math.floor(xClick - xMove) < -5 ){
                $(this).carousel('prev');
            }
        });
        $(".carousel").on("touchend", function(){
            $(this).off("touchmove");
        });
    });








    function scrollToPosition(element, flag) {
        var ele = $('.wrap').scrollTop();
        var move  = 0;
        if(flag =='top'){
            move = ele +$(element).height() * 5;
        }else{
            move = ele - $(element).height() * 5
            if(move < 0){
                move = 0;
            }

        }
        console.log(move);
        $('.wrap').animate({
            scrollTop: move
        }, 250);


    }

    //Create an Array of posts
    var date_box = $('.date_box');
    var position = 0; //Start Position
    var next = $('#next');
    var prev = $('#prev');

    next.click(function(evt) {
        scrollToPosition(date_box[position += 1],'top');

    });

    prev.click(function(evt) {

        scrollToPosition(date_box[position -= 1],'prev');

    });

    var box_height = $('.appoinment-slot-holder').height();
    if(box_height > 400){
        $(".wrap").height($('.appoinment-slot-holder').height() - 150);
    }


    $(document).on('click','.appointment-slot',function(){
        if($(this).hasClass('available')){
            $(".append_slots").find('.selected-slot').removeClass("selected-slot");
            $(this).addClass('selected-slot');
            visibleBookButton();

        }
    });





    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    var urlParams = new URLSearchParams(window.location.search);
    var add_more_br = false;
    if (urlParams.get('android')) {
        add_more_br=true;
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




    function installAppStatus(){
        var trans = db.transaction(table_name, IDBTransaction.READ_ONLY);
        var objectStore = db.transaction(table_name).objectStore(table_name);
        objectStore.openCursor().onsuccess = function(event) {
            var cursor = event.target.result;
            if (cursor) {
                patient_data = cursor.value;
                var mobile = patient_data.mobile;
                var thin_app_id = patient_data.thinapp_id;
                installAjax(mobile,thin_app_id);
            }else{
                if($('.verify_tracker_number').attr('data-key') && $(body).attr('data-ti')){
                    var mobile = atob($('.verify_tracker_number').attr('data-key'));
                    var thin_app_id = atob($(body).attr('data-ti'));
                    installAjax(mobile,thin_app_id);
                }
            }
        };
    }

    function installAjax(mobile,thin_app_id){
        $.ajax({
            url: baseUrl+"doctor/web_app_installed",
            data:{m:btoa(mobile),t:btoa(thin_app_id)},
            type:'POST',
            beforeSend:function () {

            },
            success: function(result){

            },error:function () {

            }
        });
    }

    let deferredPrompt;
    var btnSave = document.getElementById('btnSaveDoctor');
    window.addEventListener('beforeinstallprompt', function(e) {
        console.log('beforeinstallprompt Event fired');
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        return false;
    });


    function hideInstallBtn(){
        $("#btnSaveDoctor").hide();
        $("#btnSaveDoctor").removeClass('highlight_install_btn');
        $("#fade_box, .navigation-arrow-up").remove();
    }
    $(document).on('click',"#btnSaveDoctor",function(){
        if(deferredPrompt !== undefined) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function(choiceResult) {
                console.log(choiceResult.outcome);
                if(choiceResult.outcome == 'dismissed') {
                    console.log('User cancelled home screen install');
                }
                else {
                    saveInstallLog();
                    installAppStatus();
                    hideInstallBtn();

                }
                // We no longer need the prompt.  Clear it up.
                deferredPrompt = null;
            });
        }else{
            var app_name ="<?php echo $doctor_data['app_name']; ?>";
            var ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf('safari') != -1) {
                if (ua.indexOf('chrome') > -1) {
                    alert("Following reasons are that app can not add on the homescreen\n\n1. This web browser does not support this feature. You can try again by updating your browser.\n2. You have already added '"+app_name+"' to homescreen.");
                    hideInstallBtn();
                } else {
                    var img = "<img style='float:left;width:100%;' src='https://s3-ap-south-1.amazonaws.com/mengage/202103041939141957658215.png'>";
                    var ios= "<h6>Following steps are needed to add app '"+app_name+"' on homescreen.</h6>\n"+img;
                    swal({
                        type:'',
                        title:'',
                        html: ios,
                        showCancelButton: false,
                        confirmButtonText: 'Got It',
                        customClass:"success-box",
                        showConfirmButton: true
                    }).then(function (result) {

                    });



                }
            }
        }
    });

    function onLoad(){

        $(".container").removeClass("dynamic_width");
        if (window.matchMedia('(display-mode: standalone)').matches) {
            $("#btnSaveDoctor, .top_back_box").hide();
        }else{

            if(!is_desktop()){
                if(deferredPrompt !== undefined) {
                    $("#btnSaveDoctor").addClass('highlight_install_btn');
                    $("#fade_box").show();
                    $("#btnSaveDoctor").show();
                }
                var ua = navigator.userAgent.toLowerCase();
                if (ua.indexOf('safari') != -1) {
                    if (ua.indexOf('chrome') > -1) {} else {
                        $("#btnSaveDoctor").show();
                    }
                }
            }else{
                $("#btnSaveDoctor").hide();
                $(".container").addClass("dynamic_width");
            }
        }
    }

    setTimeout(function () {
        onLoad();
    },1000);

    function is_desktop(){
        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
            return false;
        }return true;
    }

    function saveInstallLog(){
        var di =$("#body").attr('data-di');
        $.ajax({
            url: baseUrl+"doctor/web_app_install_log",
            type:'POST',
            data:{di:di},
            success: function(result){},
            error:function () {}
        });
    }


    function showDashboard(){
        var di = $("body").attr('data-di');
        var ti = $("body").attr('data-ti');
        $.ajax({
            url: baseUrl+"queue_management/web_app_dashboard",
            type:'POST',
            data:{di:di,ti:ti},
            beforeSend:function () {
                $("#overlay_loader").show();
            },
            success: function(result){
                $("#dashboard_box").removeClass('login_box');
                $("#dashboard_box").html(result);
                $("#overlay_loader").fadeOut(800);
            },error:function () {
                $("#overlay_loader").fadeOut(800);

            }
        });
    }



});

