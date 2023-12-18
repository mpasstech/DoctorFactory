<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <script src="<?php echo Router::url('/js/jquery.js')?>"></script>
    <script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
    <link rel="stylesheet" href="<?php echo Router::url('/css/bootstrap.min.css')?>" />
    <link rel="stylesheet" href="<?php echo Router::url('/css/font-awesome.min.css')?>" />

    <?php echo $this->Html->css(array('tracker.css')); ?>
    <style>



        .upcoming_patient_ul li{
            width: 90%;
            margin: 10px 30px;
            border: 1px solid;
            border-radius: 5px;
            padding: 0px 0px !important;
            height: auto !important;
            font-size: 4rem;
            display: block;
        }
        .upcoming_patient_ul li label{
            padding: 0;
            margin: 0;
            width: 100%;
            display: block;
            float: left;
        }
        .upcoming_patient_ul li span{
            float: left !important;
            display: block;
            background: #e7e7e7;
            color: #000;
            padding: 0px;
            position: relative;
            width: 90px;
            text-align: center;
            margin-right: 13px;
        }


        .service_name_heading{
            background: #dddbdb !important;
        }

        .half_screen_window{
            float: left !important;
            width: 50% !important;
        }

        .zoom {


            width: 100%;
            background-color: #00D100;
            animation-name: stretch;
            animation-duration: 1.5s;
            animation-timing-function: ease-in-out;
            animation-delay: 0;
            animation-direction: alternate;
            animation-iteration-count: infinite;
            animation-fill-mode: none;
            animation-play-state: running;
            border-radius: 0 !important;

        }
        .zoom .token_number, .zoom .service_name{
            font-size: 12rem;
            color: #fff !important;
        }

        .zoom .doctor_profile{
            width: 100%;
            height: auto;


        }

        @keyframes stretch {
            0% {
                transform: scale(1);
                background-color: #00D100;
            }
            50% {
                background-color: #00A300;
            }
            100% {
                transform: scale(.7);
                background-color: #007500;
            }
        }


        /* html, body {margin: 0; height: 100%; overflow: hidden}*/
        .marq_div{
            position: absolute;
            bottom: 5px;
            width: 100%;
            display: block;
            margin: 0;
            padding: 0;
            font-size: 3rem;
            font-weight: 600;
        }

        .heading_bar h1{
            font-size: 4rem;
        }

        .carousel-caption{
            position: relative;
            text-align: center;
            float: left;
            width: 100%;
            display: block;
            left: 0;
            margin-top: 2%;
            min-height: 25rem;
            padding: 1rem;
        }

        .carousel-caption p{
            color: #484848;
            font-size: 2.2rem;
            text-align: center;
            font-weight: 500;
            text-shadow: none;

        }
        .carousel-caption h3{
            color: rgb(65, 105, 225);
            box-shadow: none;

        }

        .carousel-caption h6{
            box-shadow: none !important;
            color: green !important;
            font-weight: 500;
        }



        .frame_div{
            margin: 1rem auto;
            border: none;
            border-radius: 0px 4px 0px 0px;
            display: block;
            /* transform: rotateY(-17deg); */
            box-shadow: 6px 7px 19px #c1c1c1;
            background-repeat: no-repeat;
            background-size: auto;
            width: 350px !important;
            height: 350px !important;

        }

        .frame_div img{
            margin: 0 auto;
            display: block;
            float: left;
            width: 100%;
            height: 100%;
            position: relative;
        }


        .carousel-control.left,
        .carousel-control.right{
            background: none;
        }
        .token_li{
            margin: 1%;
            padding: 1%;
            width: 98%;
            float: left;
            display: block;
        }
        #settingButton{
            background: #1c1ce3;
            border: 1px solid #0a0aad !important;
            width: 5rem;
            height: 100%;
            color: #fff;
            padding: 5px 8px;
            border-radius: 6px;
        }
        .play_voice{
            width: 50px;
            height: 50px;
            background: red;
            padding: 5px;
            float: left;
            margin: 2px 4px;
            border-radius: 6px;
        }
        .patient_token_span{
            background: #fff;
            font-size: 2.5rem;
            padding: 7px 0%;
            border-radius: 17%;
            width: 40px;
            height: 40px;
            display: block;
            text-align: center;
            margin: 0 auto;
        }
        .current_token_box{
            padding: 0.5rem 0.3rem;
            background: #76f700;
            display: flex;
        }
        .overlay_box_div {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            display: block;
            background: #fff;
            z-index: 99999;
            opacity: 1;
            width: 100%;
            display: none;
        }
        .overlay_box_div .emergency {
            color: red;
            margin-top: 5%;
            font-size: 20rem;
            width: 100%;
            text-align: center;
            opacity: 1;
        }
        .overlay_box_div .patient_name {
            text-align: center;
            width: 100%;
            font-size: 8rem;
        }



        .note_lbl {

            position: absolute;
            bottom: 0;
            color: #fff;
            font-size: 33px;

            width: 100%;
        }
        .active_box{
            display: block !important;
        }
        .mu-hero-overlay {
            height: 100%;

        }
        #go-button{position: absolute;
            position: absolute;
            position: absolute;
            top: 6px;
            float: right;
            z-index: 9999999;
            right: 0;
            height: 40px;
            width: 45px;
            cursor: pointer;

        }



        .carousel-indicators{
            display: none;
        }


        #go-button img{
            border: 1px solid;
            border-radius: 6px 0px 0px 6px;
        }
        #go-button span{
            font-size: 9px;
            color: #fff;
            width: 100%;
            display: block;
            text-align: center;
        }

        .append_html li{
            list-style: none;
            float: left;
            height: 150px;
            padding: 10px;

        }
        .append_html li:first-child, .append_html li:nth-child(2){
            padding: 2% 10px ;
        }

        .append_html ul{
            width: 98%;
            padding: 0;
            margin: 10px;
            float: left;
        }
        .heading_bar{
            font-size: 6rem; text-align: center;width: 100%;background: #3f84bf;color: #fff;
        }
        .doctor_profile{
            border:1px solid #fff; padding: 2px; border-radius: 50%; width: 7rem; height: 7rem;
        }
        .service_name{
            font-size: 3rem;
            padding: 0px 2rem;
            color: #fff;

            width: 100%;
            display: block;
        }
        .token_heading{
            width: 100%;text-align: center;color:#fff; font-size: 3.0rem;
        }
        .token_number{
            color: #000;
            text-align: center;
            font-size: 5.5rem;

        }

        @media only screen and (max-width: 500px) {
            .mu-logo{
                font-size: 18px;
            }
            #mu-event-counter, .mu-event-counter-area{
                width: 100%;
            }
            .mu-event-counter-block{
                width: 170px;
                height: 170px;
                margin: 0 auto;
            }
        }
        @media (min-width: 1000px) and (max-width: 1200px) and (orientation: landscape) {

            body {

            }


        }
        @media (min-width: 1000px) and (max-width: 1200px) and (min-height: 1500px)  and (max-height: 2000px) {

            .mu-event-counter-area{
                margin-top: 30%;
            }
            .container {

                width: 100%;

            }
            #go-button {
                display: none;
            }
            .mu-event-counter-block {
                width: 80% !important;
                height: 650px;
                margin: 0 auto;
            }
            .mu-event-counter-block{
                margin: 0 11%;
            }
            #mu-event-counter{
                width: 100%;
            }
            .mu-hero-featured-content {

                display: inline;
                float: right;
                margin-top: -400px;
                text-align: center;
                width: 100%;
                padding-top: 30px;

            }
            .mu-event-counter-block span {

                display: block;
                font-size: 300px;
                font-weight: 700;
                padding-top: 300px;
                line-height: 40px;

            }
            .mu-hero-featured-content {
                margin:  0;
            }


        }
        @media (max-width: 480px){

            #setting_doctor_list_box li{
                width: 48% !important;
                height: 71px !important;
                font-size: 1.3rem !important;
            }
            .mu-event-counter-block span {
                font-size: 65px !important;
            }
            .heading_bar h1{
                font-size: 3rem;
            }


            .mu-event-counter-area, .mu-hero-featured-content {
                width: 100%;
            }

        }
        @media (max-width: 360px){


        }
        @media (min-width: 320px) and (max-width: 480px) {
            .heading_bar{
                font-size: 3.5rem;
                padding: 15px;
            }

            #setting_doctor_list_box li{
                width: 48% !important;
                height: 71px !important;
                font-size: 1.3rem !important;
            }


            .append_html li:first-child, .append_html li:nth-child(2){
                padding: 4% 10px ;
            }




            .doctor_profile{
                width: 5rem;
                height: 5rem;
            }

            .service_name{
                font-size: 3rem !important;
            }
            .token_heading{
                font-size: 2.2rem;
                padding: 0;
                margin: 0;
            }
            .token_number{
                font-size: 3rem !important;
            }
            .append_html li{
                height: 90px;
            }
            .append_html ul{
                margin: 5px;
            }
            #go-button{
                display: none;
            }
        }

        @media (min-width: 576px) and (max-width: 800px){

        }






        body{
            background: #fff;
        }
        header{
            background: #fff;
        }

        .amazon_silk{
            width: 50% !important;
            float: left !important;
        }

        #setting_doctor_list_box{
            float: left;
            display: block;
            width: 100%;
        }

        #setting_doctor_list_box li{
            float: left;
            width: 24%;
            padding: 0px;
            list-style: none;
            border: 1px solid #dbdbdb;
            margin: 0.3rem 0.2rem;
            font-size: 1.4rem;
            cursor: pointer;
            border-radius: 4px;
        }

        #setting_doctor_list_box li label{
            width: 100%;
            float: left;
            display: block;
            margin: 0;
            padding: 0.8rem 0.7rem;

        }


        #setting_doctor_list_box li.active{
            background: #10c10c;
            color: #fff;
        }


        #setting_doctor_list_box li input{
            float: right;
            width: 18px;
            height: 18px;
        }

        #setting_doctor_list_box .service_name_span{
            display: block;
            width: 100%;
            float: left;
        }

        #billingCounterDisplay li{
            list-style: none;
            float: left;
            padding: 3%;
            background: #ff00a5;
            color: #fff;
            border-radius: 68%;
            height: 100px;
            width: 100px;
            padding-top: 25px;
            font-size: 3rem;
            text-align: center;
            margin: 0.2rem;

        }

        #billingCounterDisplay span{
            font-size: 6rem;
            background: blue;
            color: #fff;
            padding: 0.6rem;
        }

    </style>
    <script type="text/javascript" src="<?php echo TOKEN_SOCKET_URL.'/socket/socket.io.js'; ?>"></script>

</head>
<body>


zcZXc

</body>

<script>
    $(document).ready(function(){

        var thin_app_id =  "<?php echo $thin_app_id; ?>";
        var doctorId =  "<?php echo $patientData['doctor_id']; ?>";
        var appointment_id =  "<?php echo $patientData['appointment_id']; ?>";

        var socetUrl =  "<?php echo TOKEN_SOCKET_URL; ?>";
        socket = io.connect( socetUrl );
        socket.on( 'updateToken', function( data ) {

            if(data.thin_app_id==thin_app_id){

                if(data.doctor_id != doctorId && data.appointment_id == appointment_id){
                    window.location.reload();
                }

                if(data.reload==true){

                }else if(data.play==true){
                    var key = "TM_"+data.doctor_id+"_"+data.token;
                    $("#"+data.doctor_id).find(".token_number").html(token);
                    $("#"+data.doctor_id).find(".patient_name").html(pat_name);
                    localStorage.setItem(key,data.fileName);
                }else if(data.play==false){
                    $("#"+data.doctor_id).find(".token_number").html(data.token);
                    $("#"+data.doctor_id).find(".patient_name").html(pat_name);
                }
                if(token=="Closed"){
                    $("#"+data.doctor_id).find(".token_number").html("-");
                }


            }
        });
        setInterval(function(){
            for (var i = 0; i < localStorage.length; i++){
                if (localStorage.key(i).substring(0,3) == 'TM_') {
                    var key = localStorage.key(i);
                    var keyArray = key.split("_");
                    var tmpArray =[];
                    tmpArray.push(new Audio(baseurl+"queue_tracker_voices/"+localStorage.getItem(key)));
                    play_sound_queue(tmpArray,key,keyArray[1]);
                }
            }
        },3000);

        var audioRunning = false;
        var baseurl = "<?php echo Router::url('/',true); ?>";
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for(let i = 0; i <ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }


        $(document).on("click",".play_voice",function(evt){
            const ks = new Audio(baseurl+'queue_tracker_voices/no_sound.mp3');
            let userinteraction = 0;
            if(userinteraction) return;
            userinteraction++;
            ks.play()
            $(this).hide();
        })

        blink_interval = setInterval(blink_text, 1000);
        function blink_text() {
            $('.blink').fadeOut(500);
            $('.blink').fadeIn(500);
        }



        var process_running = false;

        Object.size = function(obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };





        function play(audio,doctor_id,storageKey,  callback) {

            try {
                if(audioRunning == false){
                    localStorage.removeItem(storageKey);
                    audioRunning = true;
                    var fileName = audio.src.split('/').pop();
                    $("#"+doctor_id).closest('.ul_class').find(".token_li").addClass("zoom");
                    setTimeout(function(){
                        $("#"+doctor_id).closest('.ul_class').find(".token_li").removeClass("zoom");
                    },6000);

                    audio.play().catch(function() {
                        localStorage.removeItem(storageKey);
                        audioRunning = false;
                        //$('.ul_class').removeClass("blink");
                    });
                    if(callback){
                        //$("[data-voice='"+fileName+"']").closest('.ul_class').removeClass("blink");
                        audio.addEventListener('ended', callback);
                    }
                }
            }catch(err) {
                localStorage.removeItem(storageKey);
                audioRunning = false;
                $('.ul_class').removeClass("blink");
            }


        }

        function play_sound_queue(sounds,storageKey,doctor_id) {
            $('.ul_class').removeClass("blink");
            var index = 0;
            function recursive_play() {
                var fileName = sounds[index].src.split('/').pop();
                if (index + 1 === sounds.length) {
                    play(sounds[index],doctor_id,storageKey,  function () {
                        audioRunning = false;
                        $("#"+doctor_id).closest('.ul_class').removeClass("blink");

                    });
                } else {
                    play(sounds[index],doctor_id, storageKey,  function() {
                        $("#"+doctor_id).closest('.ul_class').removeClass("blink");

                        index++;
                        setTimeout(function () {
                            audioRunning = false;
                            recursive_play();
                        },1000);
                    });
                }
            }
            recursive_play();
        }

        function isMobileDevice() {
            const mobileDevicePatterns = /Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i;
            return !mobileDevicePatterns.test(navigator.userAgent);
        }

    });
</script>
