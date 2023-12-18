<?php
$login = $this->Session->read('Auth.User');

if($app_data['tracker_template_id'] == 1){ ?>
<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <?php echo $this->Html->css(array('tracker.css')); ?>
    <style>
       /* html, body {margin: 0; height: 100%; overflow: hidden}*/

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
       .blink_me {
        color: red;
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
            top: 29px;
            float: right;
            z-index: 9999999;
            right: 0;
            height: 40px;
            width: 45px;
            cursor: pointer;

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
            background: red !important;
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
           .mu-event-counter-block span {
               font-size: 65px !important;
           }

           .mu-event-counter-area, .mu-hero-featured-content {
               width: 100%;
           }
       }
       @media (max-width: 360px){

           .mu-hero-featured-area {
               padding: 64px 0;
           }
       }
       @media (min-width: 320px) and (max-width: 480px) {

           .mu-logo{
               font-size: 18px;
           }
           #mu-event-counter, .mu-event-counter-area{
               width: 100%;
               margin: 8px auto;
           }
           .mu-event-counter-block{
               width: 170px;
               height: 170px;
               margin-left: 21%;

               padding-top: 50px;
               line-height: 20px;
           }
           }
       }



    </style>
</head>
<body>


    <header id="mu-hero" class="" role="banner">

        <div class="overlay_box_div" >
            <lable class="break_doctor"></lable>
            <h2 class="emergency">EMERGENCY</h2>
            <h3 class="emer_patient_name"></h3>
        </div>

        <div id="go-button">
            <img src="<?php echo Router::url('/images/tracker/large_screen.png',true);?>" ></img>
            <span>Maximize</span>
        </div>
        <div class="mu-hero-overlay" >
            <div class="container">
                <div class="mu-hero-area append_html">

                    <h1 style="text-align: center;"> Please wait... </h1>

                </div>
            </div>

        </div>
    </header>




</body>
<script src="<?php echo Router::url('/js/jquery.js')?>"></script>
<script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
<script>
    $(document).ready(function(){



        function blink_text() {
            $('.blink_me').fadeOut(500);
            $('.blink_me').fadeIn(500);
        }
        setInterval(blink_text,1000);

        var refresh_list_seconds = "<?php echo $refresh_list_seconds; ?>";
        var change_doctor_seconds = "<?php echo $change_doctor_seconds; ?>";
        var image_path = "<?php echo Router::url('/images/tracker/',true);?>";
        var doctor_id =0;
        var doctor_index =0;
        var doctor_id_string = "<?php echo $doctor_ids; ?>";
        var doctor_id_array = doctor_id_string.split(",");
        var total_doctor = doctor_id_array.length;
        if(total_doctor > 0){
            doctor_id = doctor_id_array[doctor_index];
        }

        load_list();
        var tot_doctor = $('.main_div').length;
        var change_div = 30000;
        var load_list_time = total_doctor * change_div;



        setInterval(function () {
            if(process_running===false){
                doctor_index=0;
                load_list();

            }
        },refresh_list_seconds);





        var process_running = false;


        Object.size = function(obj) {
            var size = 0, key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };


        function check_emergency_appointment(current_obj){
            var doctor_id = $(current_obj).attr('id')+"";
            var doctor_name = $(current_obj).find('.mu-logo').text();
            var object_data = JSON.parse($('body').data('key'));
            if(object_data.hasOwnProperty(doctor_id)){

                var data = object_data[doctor_id];
                if(data.patient_name){

                    $(".break_doctor").html(doctor_name);
                    if(data.flag=="CUSTOM"){
                        $(".emergency").hide();
                        $(".overlay_box_div .emer_patient_name").html(data.patient_name);
                    }else{
                        $(".emergency").show();

                        $(".overlay_box_div .emer_patient_name").html("Patient - "+data.patient_name);
                    }
                    $(".overlay_box_div").show();
                }else{
                    $(".overlay_box_div .emer_patient_name").html("");
                    $(".overlay_box_div").hide();
                }
            }else{
                $(".overlay_box_div .emer_patient_name").html("");
                $(".overlay_box_div").hide();
            }
        }

        var interIneer;
        var index = 0;
        function load_list(){

            var showloader =false;
            var t = "<?php echo @($this->request->query['t']); ?>";
            var n = "<?php echo @($app_data['name']); ?>";
            var logo = "<?php echo @($app_data['logo']); ?>";
            var template_id = "<?php echo @($app_data['tracker_template_id']); ?>";
            var st = "<?php echo @($app_data['show_tracker_time']); ?>";
            var spn = "<?php echo @($app_data['show_patient_name_on_tracker']); ?>";
            var baseurl ="<?php echo Router::url('/',true);?>";

            $.ajax({
                type:'POST',
                url: baseurl+"tracker/load_tracker",
                data:{
                    st:st,logo:logo,template_id:template_id,t:t,app_name:n,doctor_id_string:doctor_id_string,spn:spn
                },
                beforeSend:function(){
                    clearInterval(interIneer);
                    index =0;
                    if(showloader===true){
                    }
                    process_running = true;

                },
                success:function(data){

                    var x = new Date()
                    var x1=x.toUTCString();
                    var hour = (x.getHours() <10)?"0"+x.getHours():x.getHours();
                    var minute = (x.getMinutes() <10)?"0"+x.getMinutes():x.getMinutes();
                    var sec = (x.getSeconds() <10)?"0"+x.getSeconds():x.getSeconds();

                    $(".loader_div").hide();

                    $(".append_html").html(data);
                    check_emergency_appointment($('.main_div:first'));
                    doctor_id_array =[];
                    $('.main_div').each(function( index, value ) {
                        doctor_id_array.push($(this).attr('id'));
                    });
                    $('.main_div:first').addClass('active_box');
                    index = 1;
                    interIneer = setInterval(function () {
                        var current_obj = $('.active_box');
                        check_emergency_appointment(current_obj);
                        if($('.active_box').length == 0){
                            check_emergency_appointment($('.main_div:first'));
                            $('.main_div:first').addClass('active_box');

                        }else{
                            current_obj = $('.active_box');
                            $('.main_div').removeClass('active_box');
                            if($(current_obj).next('.main_div').length == 1 ){
                                check_emergency_appointment($(current_obj).next('.main_div'));
                                $(current_obj).next('.main_div').addClass('active_box');
                            }else{
                                check_emergency_appointment($('.main_div:first'));
                                $('.main_div:first').addClass('active_box');
                            }
                        }

                    },change_doctor_seconds);

                    process_running = false;

                },
                error: function(data){
                    $(".loader_div").hide();
                    if(showloader===true){
                        // $.alert("Sorry something went wrong on server.");
                    }
                    process_running =false;

                }
            });



        }


        function GoInFullscreen(element) {
            if(element.requestFullscreen)
                element.requestFullscreen();
            else if(element.mozRequestFullScreen)
                element.mozRequestFullScreen();
            else if(element.webkitRequestFullscreen)
                element.webkitRequestFullscreen();
            else if(element.msRequestFullscreen)
                element.msRequestFullscreen();
        }
        function GoOutFullscreen() {
            if(document.exitFullscreen)
                document.exitFullscreen();
            else if(document.mozCancelFullScreen)
                document.mozCancelFullScreen();
            else if(document.webkitExitFullscreen)
                document.webkitExitFullscreen();
            else if(document.msExitFullscreen)
                document.msExitFullscreen();
        }
        function IsFullScreenCurrently() {
            var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;

            // If no element is in full-screen
            if(full_screen_element === null)
                return false;
            else
                return true;
        }
        $("#go-button").on('click', function() {

            if(IsFullScreenCurrently())
                GoOutFullscreen();
            else
                GoInFullscreen($("#mu-hero").get(0));
        });
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            if(IsFullScreenCurrently()) {
                $("#go-button").attr('src',image_path+"small_screen.png");
                $("#go-button span").html('Minimize');
            }
            else {
                $("#go-button").attr('src',image_path+"large_screen.png");
                $("#go-button span").html('Maximize');
            }
        });



    });
</script>



</html>
<?php }else if($app_data['tracker_template_id'] == 2){ ?>
    <html>
    <head>
        <title>Appointment Tracker</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <script src="<?php echo Router::url('/js/jquery.js')?>"></script>

        <script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
              <?php echo $this->Html->css(array('bootstrap.min.css','font-awesome.min.css'),array("media"=>'all','fullBase' => true)); ?>
        <style>

.mu-hero{
                width: 100%;
                float: left;
                display: block;
            }
            .overlay_box_div {
                position: fixed;
                top: 0;
                left: 0;
                height: 100%;
                display: block;
                background: #fff;
                z-index: 99999;
                opacity: 0.7;
                width: 100%;
                display: none;
            }
            .overlay_box_div .emergency {
                color: red;
                margin-top: 17%;
                font-size: 13rem;
                width: 100%;
                text-align: center;
                opacity: 1;
            }
            .overlay_box_div .patient_name {
                text-align: center;
                width: 100%;
                font-size: 8rem;
            }
            .main_box{
                display: block;
                float: left;
                width: 100%;
            }
            .active_box{
                display: block !important;
            }
            .container {
                background: #E8EBF0;
                margin: 0 auto;
                width: 100%;
            }

            body{
                background: #E8EBF0;
                overflow: hidden;
            }
            #go-button{position: absolute;
                position: absolute;
                position: absolute;
                top: 29px;
                float: right;
                z-index: 9999999;
                right: 0;
                height: 40px;
                width: 45px;
                cursor: pointer;

            }
            #go-button img{
                border: 1px solid;
                border-radius: 6px 0px 0px 6px;
                width: 50px;
                height: 50px;
            }
            #go-button span{
                font-size: 9px;
                color: #fff;
                width: 100%;
                display: block;
                text-align: center;
            }

            .append_tracker_list{

            }
            .second .corner_shape{
                width: 14%;
                height: 91px;
                margin-top: -19px;
                position: absolute;
                float: left;
                top: 100;
                left: -10px;

            }
            .second .corner_shape img{
                width: 100%;
            }
            .container{
                background: #E8EBF0;
                margin: 0 auto;
            }
            .doctor_section{
                background: #fff;
                margin: 7px 20px;
                border-radius: 100px 0px 0px 100px;
            }
            .second .doctor_name{
                color: #fff;
                font-size: 35px;
                text-align: center;

            }
            .second .second_parent_div, .third_parent_div{
                padding: 1px 0px;
            }
            .second .token_span{
                border-radius: 74px;
                border: 7px solid #C4CDDB;
                padding-top: 25px;
                width: 115px;
                height: 115px;
                position: absolute;
                text-align: center;
                background-image: linear-gradient(#D9D9D9, #fff);
                font-size: 35px;
            }



            .blink{
                color:red !important;
                padding-top: 40px !important;
                font-size: 18px !important;
            }

            
            .second .overlay{
                z-index: 3;
                position: absolute;
                content: '';
                display: block;
                margin: -56px auto;
                right: 0;
                width: 13%;
                background-image: linear-gradient(to left, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.0));
                transform: rotate(15deg);
                float: right;
                height: 72px;

            }
            .second .doctor_img_div img {
                width: 210px;
                height: 210px;
                border: 8px solid;
            }
            .second .doctor_img_div{
                margin: 0px;
                padding: 3px;
                border: 3px solid;
                border-radius: 140px;
                z-index: 1;
                width: 17%;
            }

            .second .shape_div img{
                width: 100%;
            }
            .second .room_parent{
                max-height: 100px;
            }
            .second .shape_div{
                float: left;
                height: 30px;
                left: -16px;
                position: relative;
                width: 19%;
            }

            #pacman {
                width: 80px;
                height: 80px;

                box-shadow: 15px 15px 0 0 red;
            }


            .second .room_inner img{
                width: 230px;
                transform: rotate(-47deg);
                margin-left: -30px;
                margin-top: -72px;
                height: 280px;

            }
            .second .room_inner{

                -moz-transform: scale(0.6) rotate(49deg) translate(-50px, 2px) skew(-23deg, -26deg);
                -webkit-transform: scale(0.6) rotate(49deg) translate(-50px, 2px) skew(-23deg, -26deg);
                -o-transform: scale(0.6) rotate(49deg) translate(-50px, 2px) skew(-23deg, -26deg);
                -ms-transform: scale(0.6) rotate(49deg) translate(-50px, 2px) skew(-23deg, -26deg);
                transform: scale(0.6) rotate(49deg) translate(-50px, 2px) skew(-23deg, -26deg);
                width: 230px;
                height: 230px;
                border-right: 8px solid;
                border-bottom: 8px solid;

                border-radius: 4px;

            }
            .second .inner_text{
                font-weight: 600;
                font-size: 30px;
                text-align: center;

            }
            .second_row_box{
                margin-left: -54px;
                width: 87%;
            }
            .second_row{

            }




            /* responsive css */




            @media (min-width: 768px) and (max-width: 1024px) and (orientation: portrait){

                .second .corner_shape {
                    width: 18%;

                    height: 74px;

                    margin-top: -43px;

                    position: absolute;

                    float: left;

                    top: 100;

                    left: -5px;

                }

                .second .inner_text {

                    font-size: 20px;

                }
                .second .room_inner {

                    height: 165px;

                    width: 165px;

                }

                .second .room_inner img {

                    width: 189px;

                    transform: rotate(-47deg);

                    margin-left: -24px;

                    margin-top: -29px;

                    height: 187px;

                }
                .second .doctor_img_div img {
                    width: 160px;
                    height: 160px;
                }
                .second .second_parent_div, .third_parent_div {

                    padding: 3px 0px;

                }
                .second .second_row {

                    padding-top: 14px;

                }
                .second .token_span {

                    padding-top: 13px;

                    width: 80px;

                    height: 80px;

                    font-size: 30px;

                    border: 6px solid #C4CDDB;

                    text-align: center;


                }
                .second .doctor_name {
                    font-size: 19px;

                }
                .second_row_box {
                    margin-left: -37px;

                    width: 81%;

                }
                .second .doctor_img_div {

                    width: 24%;
                }

                .second .shape_div {
                    float: left;

                    height: 13px;

                    left: -15px;

                    position: relative;

                    width: 21%;

                }
                .second .room_parent {

                    max-height: 65px;

                }
            }
            @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {

                .second .doctor_img_div img {

                    width: 190px;

                    height: 190px;
                }
                .second .doctor_img_div {
                    padding: 6px;
                    width: 21%;
                }
                .second .corner_shape {
                    width: 14%;

                    height: 83px;

                    margin-top: -23px;

                    position: absolute;

                    float: left;

                    top: 100;

                    left: -2px;

                }
                .second .inner_text {
                    font-weight: 600;
                    font-size: 30px;
                    text-align: center;

                }
                .second .second_parent_div, .third_parent_div {
                    padding: 3px 0px;
                }
                .second .doctor_name{
                    font-size: 26px;
                }
                .second_row_box {
                    margin-left: -39px;

                    width: 82%;

                }
                .second .token_span {
                    padding-top: 17px;

                    width: 100px;

                    height: 100px;
                }
                .second .room_inner{
                    width: 225px;

                    height: 225px;
                }
                .second .room_inner img {

                    width: 238px;
                    transform: rotate(-47deg);
                    margin-left: -45px;
                    margin-top: -80px;
                    height: 296px;

                }
                .second_row {

                    padding-top: 11px;

                }
                .second .shape_div {
                    width: 19%;
                    height: 44px;
                    margin-top: -100px;
                    position: absolute;
                    float: left;
                    top: 100;
                    left: 0px;

                }

            }

            .patient_name_heading label, .appointment_time_heading label{
                display: block;
                color: #fff;
                text-align: center;
                FONT-SIZE: 20PX;
                border-radius: 42px;
                padding: 3px 0px;
                font-weight: 400;
            }

            .patient_name_heading span, .appointment_time_heading span{
               font-size: 23px;
                text-align: center;
                width: 100%;
                text-align: center;
                display: block;
                font-weight: 600;
            }



            @media (min-width: 1080px) and (min-height: 1920px) and (orientation: portrait){

                .blink{

                    padding-top: 100px !important;
                    font-size: 46px !important;
                }

                .patient_lbl_div{
                    border: 1px solid;
                    width: 700px;
                    margin-left: -200px;
                    padding: 0px;
                    top: 135px;
                    border-radius: 0px 0px 171px 0px;
                }
                .patient_lbl_div label{
                    color: #fff;
                    padding: 18px 5px;
                    margin: 0px 0px;
                    width: 209px;
                    height: 80px;
                    display: inline-block;
                    float: left;
                    border-radius: 0px;
                    font-size: 30px;
                    font-weight: 600;
                }
                .patient_name_heading span{

                    font-size: 40px;


                    width: 70%;
                    float: left;
                    text-align: left;
                    padding: 15px 6px;

                }
                .appointment_time_heading{
                    text-align: center;
                }
                .appointment_time_heading label{
                    border-top: 1px solid #fff;
                    float: left;
                    display: inline-block;
                    float: left;
                }

                .patient_name_heading, .appointment_time_heading{
                    text-align: center;
                    font-weight: 600;
                }
             .appointment_time_heading span{
                 color: #6A4F94;
                 width: 47%;
                 display: block;
                 padding: 14px 7px;
                 font-size: 48px;
                 text-align: left;



                 float: left;
                }
                .patient_lbl_div h2{
                    padding: 0px;
                    margin: 0px;
                    font-weight: 600;
                    font-size: 30px;
                    float: left;
                    width: 100%;
                }
                .append_tracker_list{
                    overflow: hidden;
                }
                .name_div{
                    width: 100%;
                    font-size: 54px !important;
                }
                .department_div {
                    width: 70%;
                    right: 0;
                    float: right;
                    border: none;
                    font-size: 45px !important;
                    border-top: 2px solid #fff;
                }
                .doctor_section {
                    background: #fff;
                    margin: 20px 3px;
                    border-radius: 0px;
                }
                .second .doctor_img_div img {
                    width: 250px;
                    height: 250px;
                    margin: 3px;

                }
                .second .doctor_img_div {
                    padding: 0px;
                    width: 265px;
                    height: 262px;
                    text-align: center;

                }
                .second .corner_shape {
                    display: none;
                    width: 14%;

                    height: 83px;

                    margin-top: -23px;

                    position: absolute;

                    float: left;

                    top: 100;

                    left: -2px;

                }
                .second .inner_text {
                    font-weight: 600;
                    font-size: 45px;
                    text-align: center;
                    width: 100%;
                    display: block;
                }
                .second .second_parent_div, .third_parent_div {
                    padding: 3px 0px;
                }
                .second .doctor_name {
                    font-size: 35px;
                }
                .second_row_box {
                    margin-left: -56px;
                    width: 80%;
                }
                .second .token_span {
                    padding-top: 62px;
                    width: 300px;
                    height: 300px;
                    position: relative;
                    display: inline-block;
                    border-radius: 75%;
                    border: 18px solid #c4cddb;
                    font-size: 100px;
                    margin: 2px 0px;

                }
                .second .room_inner {
                    width: 400px;
                    height: 400px;
                }
                .second .room_inner img {
                    width: 400px;
                    transform: rotate(-47deg);
                    margin-left: -61px;
                    margin-top: -118px;
                    height: 495px;
                }
                .second_row {

                    padding-top: 11px;

                }
                .second .shape_div img{}
                .second .shape_div {
                    width: 28%;
                    height: 44px;
                    margin-top: -100px;
                    position: absolute;
                    float: left;
                    top: 100;
                    left: -9px;
                }
            }

            @media (min-width: 1920px) and (min-height: 1080px) and (orientation: landscape) {

                .append_tracker_list{
                    overflow: hidden;
                }

                .patient_lbl_div label{
                    font-size: 27px;
                }
                .patient_name_heading span, .appointment_time_heading span{
                    font-size: 33px;;
                }

                .blink{

                    padding-top: 47px !important;
                    font-size: 23px !important;
                }
                .doctor_section {
                    border-radius: 200px 0px 0px 200px;
                    margin: 20px 20px 0px 20px;
                }
                .second .doctor_img_div img {
                    width: 305px;
                    height: 305px;
                }
                .second .doctor_img_div {
                    padding: 10px;
                    width: 18%;
                    border-radius: 70%;
                }
                .second .corner_shape {
                    width: 14%;
                    height: 123px;
                    margin-top: 18px;
                    position: absolute;
                    float: left;
                    top: 100;
                    left: -14px;
                }
                .second .inner_text {
                    font-weight: 600;
                    font-size: 50px;
                    text-align: center;
                }
                .second .second_parent_div, .third_parent_div {
                    padding: 3px 0px;
                }
                .second .doctor_name {
                    font-size: 55px;
                }
                .second_row_box {
                    margin-left: -76px;
                    width: 86%;
                }
                .second .token_span {
                    padding-top: 33px;
                    width: 155px;
                    height: 155px;
                    font-size: 50px;
                    border-radius: 75%;
                    border: 13px solid #C4CDDB;
                }
                .second .room_inner {
                    width: 330px;
                    height: 330px;
                }
                .second .room_inner img {
                    width: 390px;
                    transform: rotate(-47deg);
                    margin-left: -71px;
                    margin-top: -94px;
                    height: 415px;
                }
                .second_row {

                    padding-top: 11px;

                }
                .second .shape_div {
                    width: 22%;
                    height: 44px;
                    margin-top: -100px;
                    position: absolute;
                    float: left;
                    top: 100;
                    left: -12px;
                }

            }


        </style>

    </head>
    <body>
    <div id = "mu-hero">
    <div id="go-button">
        <img src="<?php echo Router::url('/images/tracker/large_screen.png',true);?>" ></img>
        <span>Maximize</span>
    </div>


    <div class="container append_tracker_list second">

        <h1 style="text-align: center;"> Please Wait... </h1>
    </div>

        <div class="overlay_box_div">
            <h2 class="emergency">EMERGENCY</h2>
            <h3 class="patient_name"></h3>
        </div>

    </div>

    <script>
        $(document).ready(function(){
            var image_path = "<?php echo Router::url('/images/tracker/',true);?>";
            var refresh_list_seconds = "<?php echo $refresh_list_seconds; ?>";
            var change_doctor_seconds = "<?php echo $change_doctor_seconds; ?>";
            var interIneer;
            function load_list(){

                var t = "<?php echo @($this->request->query['t']); ?>";
                var n = "<?php echo @($app_data['name']); ?>";
                var logo = "<?php echo @($app_data['logo']); ?>";
                var template_id = "<?php echo @($app_data['tracker_template_id']); ?>";
                var st = "<?php echo @($app_data['show_tracker_time']); ?>";
                var spn = "<?php echo @($app_data['show_patient_name_on_tracker']); ?>";

                var baseurl ="<?php echo Router::url('/',true);?>";
                var doctor_id_string = "<?php echo $doctor_ids; ?>";
                var doctor_id_array = doctor_id_string.split(",");
                var blink_interval;
                $.ajax({
                    type:'POST',
                    url: baseurl+"tracker/load_tracker",
                    data:{
                        st:st,logo:logo,template_id:template_id,t:t,app_name:n,doctor_id_string:doctor_id_string,spn:spn
                    },
                    beforeSend:function(){
                        clearInterval(blink_interval);
                        clearInterval(interIneer);
                    },
                    success:function(data){
                        blink_interval = setInterval(blink_text, 1000);
                        $(".append_tracker_list").html(data);
                        $('.main_div:first').addClass('active_box');
                        interIneer = setInterval(function () {
                            var current_obj = $('.active_box');
                            if($('.active_box').length == 0){
                                $('.main_div:first').addClass('active_box');
                            }else{
                                current_obj = $('.active_box');
                                $('.main_div').removeClass('active_box');
                                if($(current_obj).next('.main_div').length == 1 ){
                                    $(current_obj).next('.main_div').addClass('active_box');
                                }else{
                                    $('.main_div:first').addClass('active_box');
                                }
                            }
                            check_emergency_appointment($(current_obj).attr('id'));
                        },change_doctor_seconds);



                    },
                    error: function(data){

                    }
                });

            }
            load_list();
            function check_emergency_appointment(doctor_id){

            }

            setInterval(function(){load_list();},refresh_list_seconds);

            function blink_text() {
                $('.blink span').fadeOut(500);
                $('.blink span').fadeIn(500);
            }



           function resize(){
                $('.container').height($(document).height());
           }

            function GoInFullscreen(element) {
                if(element.requestFullscreen)
                    element.requestFullscreen();
                else if(element.mozRequestFullScreen)
                    element.mozRequestFullScreen();
                else if(element.webkitRequestFullscreen)
                    element.webkitRequestFullscreen();
                else if(element.msRequestFullscreen)
                    element.msRequestFullscreen();

            }
            function GoOutFullscreen() {
                if(document.exitFullscreen)
                    document.exitFullscreen();
                else if(document.mozCancelFullScreen)
                    document.mozCancelFullScreen();
                else if(document.webkitExitFullscreen)
                    document.webkitExitFullscreen();
                else if(document.msExitFullscreen)
                    document.msExitFullscreen();

            }
            function IsFullScreenCurrently() {
                var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;

                // If no element is in full-screen
                if(full_screen_element === null)
                    return false;
                else
                    return true;
            }
            $("#go-button").on('click', function() {

                if(IsFullScreenCurrently())
                    GoOutFullscreen();
                else
                    GoInFullscreen($("#mu-hero").get(0));
            });
            $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
                if(IsFullScreenCurrently()) {
                    $("#go-button").attr('src',image_path+"small_screen.png");
                    $("#go-button span").html('Minimize');

                }
                else {
                    $("#go-button").attr('src',image_path+"large_screen.png");
                    $("#go-button span").html('Maximize');

                }
            });

            $(window).on('resize', function(){
                resize();
            });

            resize();

        });
    </script>



    </body>
    </html>
<?php }else if($app_data['tracker_template_id'] == 3){ ?>
    <html>
    <head>
        <title>Appointment Tracker</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <?php echo $this->Html->css(array('third_tracker.css'),array("media"=>'all','fullBase' => true)); ?>
        <style>

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
                margin-top: 3%;
                font-size: 14rem;
                width: 100%;
                text-align: center;
                opacity: 1;
            }
            .overlay_box_div .emer_patient_name {
                text-align: center;
                width: 100%;
                font-size: 6rem;
                margin-top: 12%;
            }

            @media (min-width: 1080px) and (min-height: 1920px) and (orientation: portrait){

                .token .token_number {
                    margin-top: -250px;
                    text-align: center;
                    font-size: 110px;
                    font-weight: 500;

                }
                .doc_img_div {
                    width: 11%;
                    text-align: center;
                    margin: 15px 10px 15px 0px;
                }
                .room_number {
                    float: right;
                    position: absolute;
                    right: 0px;
                    width: 50%;
                    font-weight: 600;
                    display: inline-block;
                    /* height: 100%; */
                    /* padding: 46px 30px; */
                    font-size: 2.5rem;
                    text-align: center;
                    top: 40px;
                }

            }
            .active_box{
                display: block !important;
            }

        </style>
    </head>
    <body >


    <!-- Highlights -->
    <div id="element" class=" main_section" style="width: 100%;">
        <div class="overlay_box_div" >
            <lable class="break_doctor"></lable>
            <h2 class="emergency">EMERGENCY</h2>
            <h3 class="emer_patient_name"></h3>
        </div>
          <img src="<?php echo Router::url('/images/tracker/patient_icon.png',true);?>" id="select_doctor" class="select_doctor" style="WIDTH: 3%;POSITION: ABSOLUTE;FLOAT: RIGHT;RIGHT: 0;z-index:99999;"></img>
      
        <img src="<?php echo Router::url('/images/tracker/large_screen.png',true);?>" id="go-button"></img>
        <div class="shedow_div"></div>
        <div class="append_html">

            <h1 style="text-align: center;"> Please wait... </h1>

        </div>



    </div>

    </div>


    <!-- Scripts -->
    <script src="<?php echo Router::url('/js/jquery.js')?>"></script>
    <script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
    
    
    <?php echo $this->Html->script(array('jquery-confirm.min.js')); ?>
    <?php echo $this->Html->css(array( 'jquery-confirm.min.css'),array("media"=>'all')); ?>


    <script>
        $(document).ready(function(){

            var refresh_list_seconds = "<?php echo $refresh_list_seconds; ?>";
            var change_doctor_seconds = "<?php echo $change_doctor_seconds; ?>";
            var image_path = "<?php echo Router::url('/images/tracker/',true);?>";
            var doctor_id =0;
            var doctor_index =0;
            var doctor_id_string = "<?php echo $doctor_ids; ?>";
            var option_string = "<?php echo $option_string; ?>";

            var doctor_id_array = doctor_id_string.split(",");
            var total_doctor = doctor_id_array.length;
            if(total_doctor > 0){
                doctor_id = doctor_id_array[doctor_index];
            }

            load_list();
            setInterval(function () {
                if(process_running===false){
                    doctor_index=0;
                    showloader = false;
                    load_list();

                }
            },refresh_list_seconds);
            var process_running = false;
            Object.size = function(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            };
            var interIneer;
            var index = 0;
            var showloader = false;
            function load_list(){

                var t = "<?php echo @($this->request->query['t']); ?>";
                var n = "<?php echo @($app_data['name']); ?>";
                var logo = "<?php echo @($app_data['logo']); ?>";
                var template_id = "<?php echo @($app_data['tracker_template_id']); ?>";
                var st = "<?php echo @($app_data['show_tracker_time']); ?>";
                var spn = "<?php echo @($app_data['show_patient_name_on_tracker']); ?>";
                var id_string = doctor_id_string;
                if(getCookie('tracker_selected_doctor') && getCookie('tracker_selected_doctor')!='ALL'){
                    id_string = getCookie('tracker_selected_doctor');
                }

                var baseurl ="<?php echo Router::url('/',true);?>";
                $.ajax({
                    type:'POST',
                    url: baseurl+"tracker/load_tracker",
                    data:{
                        st:st,logo:logo,template_id:template_id,t:t,d:doctor_id,app_name:n,doctor_id_string:id_string,spn:spn
                    },
                    beforeSend:function(){
                        clearInterval(interIneer);
                        index =0;
                        if(showloader===true){
                        }
                        process_running = true;

                    },
                    success:function(data){

                        var x = new Date()
                        var x1=x.toUTCString();
                        var hour = (x.getHours() <10)?"0"+x.getHours():x.getHours();
                        var minute = (x.getMinutes() <10)?"0"+x.getMinutes():x.getMinutes();
                        var sec = (x.getSeconds() <10)?"0"+x.getSeconds():x.getSeconds();

                        $(".loader_div").hide();

                        $(".append_html").html(data);
                        check_emergency_appointment($('.main_div:first'));
                        doctor_id_array =[];
                        $('.main_div').each(function( index, value ) {
                            doctor_id_array.push($(this).attr('id'));
                        });

                        $('.main_div:first').addClass('active_box');

                        interIneer = setInterval(function () {
                            var current_obj = $('.active_box');
                            check_emergency_appointment(current_obj);
                            if($('.active_box').length == 0){
                                check_emergency_appointment($('.main_div:first'));
                                $('.main_div:first').addClass('active_box');

                            }else{
                                current_obj = $('.active_box');
                                $('.main_div').removeClass('active_box');
                                if($(current_obj).next('.main_div').length == 1 ){
                                    check_emergency_appointment($(current_obj).next('.main_div'));
                                    $(current_obj).next('.main_div').addClass('active_box');
                                }else{
                                    check_emergency_appointment($('.main_div:first'));
                                    $('.main_div:first').addClass('active_box');
                                }
                            }

                        },change_doctor_seconds);

                        process_running = false;


                    },
                    error: function(data){
                        $(".loader_div").hide();
                        if(showloader===true){
                            // $.alert("Sorry something went wrong on server.");
                        }
                        process_running =false;

                    }
                });

            }

            function check_emergency_appointment(current_obj){
                var doctor_id = $(current_obj).attr('id')+"";
                var doctor_name = $(current_obj).find('.doc_name_div h1').text();
                var object_data = JSON.parse($('body').data('key'));
                if(object_data.hasOwnProperty(doctor_id)){

                    var data = object_data[doctor_id];
                    if(data.patient_name){

                        $(".break_doctor").html(doctor_name);
                        if(data.flag=="CUSTOM"){
                            $(".emergency").hide();
                            $(".overlay_box_div .emer_patient_name").html(data.patient_name);
                        }else{
                            $(".emergency").show();

                            $(".overlay_box_div .emer_patient_name").html("Patient - "+data.patient_name);
                        }
                        $(".overlay_box_div").show();
                    }else{
                        $(".overlay_box_div .emer_patient_name").html("");
                        $(".overlay_box_div").hide();
                    }
                }else{
                    $(".overlay_box_div .emer_patient_name").html("");
                    $(".overlay_box_div").hide();
                }
            }

            /* Get into full screen */
            function GoInFullscreen(element) {
            	$("#select_doctor").hide();
                if(element.requestFullscreen)
                    element.requestFullscreen();
                else if(element.mozRequestFullScreen)
                    element.mozRequestFullScreen();
                else if(element.webkitRequestFullscreen)
                    element.webkitRequestFullscreen();
                else if(element.msRequestFullscreen)
                    element.msRequestFullscreen();
            }

            /* Get out of full screen */
            function GoOutFullscreen() {
            $("#select_doctor").show();
                if(document.exitFullscreen)
                    document.exitFullscreen();
                else if(document.mozCancelFullScreen)
                    document.mozCancelFullScreen();
                else if(document.webkitExitFullscreen)
                    document.webkitExitFullscreen();
                else if(document.msExitFullscreen)
                    document.msExitFullscreen();
            }

            /* Is currently in full screen or not */
            function IsFullScreenCurrently() {
                var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;

                // If no element is in full-screen
                if(full_screen_element === null)
                    return false;
                else
                    return true;
            }

            $("#go-button").on('click', function() {
                if(IsFullScreenCurrently())
                    GoOutFullscreen();
                else
                    GoInFullscreen($("#element").get(0));
            });

            $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
                if(IsFullScreenCurrently()) {
                    $("#go-button").attr('src',image_path+"small_screen.png");
                }
                else {
                    $("#go-button").attr('src',image_path+"large_screen.png");
                }
            });

            blink_interval = setInterval(blink_text, 1000);
            function blink_text() {
                $('.blink').fadeOut(500);
                $('.blink').fadeIn(500);
            }
            
           $(document).on('click','.select_doctor',function(e){


                var obj = $(this);
                var selected = getCookie('tracker_selected_doctor');

                var string = "<select id='temp_doctor_list'>"+option_string+"</select>";

                var dialog = $.confirm({
                    title: 'Select Doctor',
                    content: '' +
                        '<div class="form-group">' +
                        '<label>Select Doctor For Tracker</label>' +
                        string +
                        '</div>',
                    buttons: {
                        formSubmit: {
                            text: 'Assign',
                            btnClass: 'btn-blue assign_btn',
                            action: function () {
                                var $btn = $(".assign_btn");
                                dialog.close();
                                var doctor_id = (this.$content.find('#temp_doctor_list').val());
                                setCookie('tracker_selected_doctor', doctor_id, 300);
                                return false;
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
                    onContentReady: function () {
                        if(selected!=''){
                            $("#temp_doctor_list").val(selected);
                        }

                    }
                });





            });

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

            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

        });
    </script>

    </body>
    </html>
<?php }else if($app_data['tracker_template_id'] == 4){ ?>
    <html>
    <head>
        <title>Appointment Tracker</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <?php echo $this->Html->css(array('fourth_tracker.css?'.date('Ymd')),array("media"=>'all','fullBase' => true)); ?>
        <style>
            .active_box{
                display: block !important;
            }


            @media (min-width: 1080px) and (min-height: 1920px) and (orientation: portrait){
                .powered_by{
                    width: 25%;
                }
                .current_patient_span{
                    width: 70% !important;;
                }
                .current_patient_time_span{
                    width: 30% !important;
                }

                .current_patient_span span, .current_patient_time_span span{
                   font-size :4.5rem !important;
                }

                .doctor_patient_list .patient_box, .doctor_patient_list .time_box{
                    font-size: 3.1rem !important;
                }
                .check_in_type_lbl{
                    width: 36% !important;
                }
            }
        </style>
    </head>
    <body >

    <!-- Highlights -->
    <div id="element" class=" main_section" style="width: 100%;">

        <div class="overlay_box_div" >
            <lable class="break_doctor"></lable>
            <h2 class="emergency">EMERGENCY</h2>
            <h3 class="emer_patient_name"></h3>
        </div>


        <img src="<?php echo Router::url('/images/tracker/large_screen.png',true);?>" id="go-button"></img>

		<?php $address_list =$this->AppAdmin->get_app_address($thin_app_id); ?>
        <select id="top_address_drp" style="position: absolute;right:65px; z-index: 99999;padding: 2px 2px; width: 10%; ">
            <?php if(count($address_list) > 1){ ?>
                <option value="">All Address</option>
            <?php } ?>
            <?php if(!empty($address_list)){ foreach ($address_list as $address_id => $address){ ?>
                <option value="<?php echo $address_id;?>"><?php echo $address;?></option>
            <?php }} ?>
        </select>
        
        <select id="screen_size_drp" style="position: absolute;right: 0; padding: 2px 2px; z-index: 99999;">
            <?php for($counter=60;$counter <= 100;){ ?>
                <option value="<?php echo $counter.'%'; ?>"><?php echo $counter."%"; ?></option>
                <?php $counter = $counter+1; } ?>
        </select>

        <div class="shedow_div"></div>
        <div class="append_html">
            <h1 style="text-align: center;"> Please wait... </h1>

        </div>


    </div>

    </div>


    <!-- Scripts -->
    <script src="<?php echo Router::url('/js/jquery.js')?>"></script>
    <script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>
    <script>

        $(document).ready(function(){

            var dataAudioPreload = {};
			
             $(document).on("change","#top_address_drp",function(){
                setCookie("top_address_drp", $(this).val(), 800);
                load_list();
            });
			
            var top_address_drp = getCookie("top_address_drp");
            if(top_address_drp!=''){
                $("#top_address_drp").val(top_address_drp);
            }

           function getVoiceString(tokenNumber, patientName, roomNumber){
               var text = "<?php echo $app_data['tracker_voice']; ?>";
               if(text==''){
                   text = "token number [TOKEN], [NAME], room number [ROOM]";
               }
               if(roomNumber == ''){
                    text = text.replace(', room number [ROOM]','');
               }
                text = text.replace('[TOKEN]',tokenNumber);
                text = text.replace('[NAME]',patientName);
                text = text.replace('[ROOM]',roomNumber);

                return encodeURIComponent(text);
            }

            function preloadAudio(contentArray){
                contentArray.forEach(function(item, index){
                    var textToAnounce = getVoiceString(item.token, item.name, item.room_number);
                    if(!total_play_string_array.hasOwnProperty(textToAnounce)){
                        var aud = new Audio('http://ivrapi.indiantts.co.in/tts?type=indiantts&text='+textToAnounce+'&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts');
                        aud.preload = 'auto';
                    }
                });
            }

            var total_play_string_array = {};
            function play_voice_tune(token, name, room_number){
                var textToAnounce = getVoiceString(token, name,room_number);
                if(!total_play_string_array.hasOwnProperty(textToAnounce)){
                   /* var src = "http://ivrapi.indiantts.co.in/tts?type=indiantts&text="+textToAnounce+"&api_key=2d108780-0b86-11e6-b056-07d516fb06e1&user_id=80&action=play&audio.tts";
                    total_play_string_array[textToAnounce] =true;
                    $("#play_frame").remove();
                    var iframe = document.createElement('iframe');
                    iframe.id = "play_frame";
                    iframe.src = src;
                    iframe.allow = "autoplay";
                    iframe.style.display = 'none';
                    iframe.height= '10';
                    iframe.width= '10';
                    document.body.appendChild(iframe);*/
                }
            }

            function play_current_token(current_obj){
                if($(".main_div").length==1){
                    var patient_name = $(current_obj).find(".current_patient_span span").text().trim();
                    var token = $(current_obj).find(".current_patient_time_span span").text().trim();
                    var room = $(current_obj).find(".room_number").text().trim().split(":");
                    room = (room.length==2)?room[1].trim():'';
                    play_voice_tune(token, patient_name, room);
                }
            }

            function play_tune(){
                audioElement = document.createElement('audio');
                audioElement.setAttribute('src', audioUrl);
                if(typeof videoAttr.muted !== "undefined")
                {
                    videoAttr.muted = true;
                }
                audioElement.play({
                    onplay: function() {

                        if(isPlaying == true)
                        {
                            audioElement.pause();
                            audioElement.currentTime = 0;
                        }
                    },
                    onerror: function(errorCode, description) {
                        console.log(errorCode,description);
                        if(typeof videoAttr.muted !== "undefined")
                        {
                            videoAttr.muted = false;
                        }
                    }
                });
            }

            $(".footertext").hide();
            var refresh_list_seconds = "<?php echo $refresh_list_seconds; ?>";
            var change_doctor_seconds = "<?php echo $change_doctor_seconds; ?>";
            var image_path = "<?php echo Router::url('/images/tracker/',true);?>";
            var doctor_id =0;
            var doctor_index =0;
            var doctor_id_string = "<?php echo $doctor_ids; ?>";
            var doctor_id_array = doctor_id_string.split(",");
            var total_doctor = doctor_id_array.length;
            if(total_doctor > 0){
                doctor_id = doctor_id_array[doctor_index];
            }

            function check_emergency_appointment(current_obj){
                var doctor_id = $(current_obj).attr('id')+"";
                var doctor_name = $(current_obj).find('.doctor_name').text();
                var object_data = JSON.parse($('body').data('key'));
                
                if($(".main_div").length==0 && object_data){
                   $.each(object_data,function (index,value) {
                       doctor_id = index;
                   });
                }
                
                
                if(object_data.hasOwnProperty(doctor_id)){

                    var data = object_data[doctor_id];
                    doctor_name = (data.doctor_name)?data.doctor_name:doctor_name;
                    if(data.patient_name){

                        $(".break_doctor").html(doctor_name);
                        if(data.flag=="CUSTOM"){
                            $(".emergency").hide();
                            $(".overlay_box_div .emer_patient_name").html(data.patient_name);
                        }else{
                            $(".emergency").show();

                            $(".overlay_box_div .emer_patient_name").html("Patient - "+data.patient_name);
                        }
                        $(".overlay_box_div").show();
                    }else{
                        $(".overlay_box_div .emer_patient_name").html("");
                        $(".overlay_box_div").hide();
                    }
                }else{
                    $(".overlay_box_div .emer_patient_name").html("");
                    $(".overlay_box_div").hide();
                }

            }

            load_list();
            var timer_count =0;
            var no_appointment_doctor = {};

            function setIndex(){
                doctor_index++;
                $("#"+doctor_id).hide();
                doctor_id = doctor_id_array[doctor_index];
                $("#"+doctor_id).show();
            }

            var tot_doctor = $('.main_div').length;
            var change_div = refresh_list_seconds;
            var load_list_time = total_doctor * change_div;

            setInterval(function () {
                if(process_running===false){
                    doctor_index=0;
                    showloader = false;
                    load_list();
                }
            },refresh_list_seconds);

            var process_running = false;
            Object.size = function(obj) {
                var size = 0, key;
                for (key in obj) {
                    if (obj.hasOwnProperty(key)) size++;
                }
                return size;
            };

            var interIneer;
            var index = 0;
            var showloader = false;
            var change_div = false;


            function load_list(){

                var t = "<?php echo @($this->request->query['t']); ?>";
                var n = "<?php echo @($app_data['name']); ?>";
                var logo = "<?php echo @($app_data['logo']); ?>";
                var template_id = "<?php echo @($app_data['tracker_template_id']); ?>";
                var st = "<?php echo @($app_data['show_tracker_time']); ?>";
                var spn = "<?php echo @($app_data['show_patient_name_on_tracker']); ?>";
                var ai = $("#top_address_drp").val();

                var baseurl ="<?php echo Router::url('/',true);?>";
                $.ajax({
                    type:'POST',
                    url: baseurl+"tracker/load_tracker",
                    data:{
                        ai:ai,st:st,logo:logo,template_id:template_id,t:t,d:doctor_id,app_name:n,doctor_id_string:doctor_id_string,spn:spn
                    },
                    beforeSend:function(){
                        clearInterval(interIneer);
                        index =0;
                        if(showloader===true){
                        }
                        process_running = true;

                    },
                    success:function(data){




                        var x = new Date()
                        var x1=x.toUTCString();
                        var hour = (x.getHours() <10)?"0"+x.getHours():x.getHours();
                        var minute = (x.getMinutes() <10)?"0"+x.getMinutes():x.getMinutes();
                        var sec = (x.getSeconds() <10)?"0"+x.getSeconds():x.getSeconds();





                        $(".loader_div").hide();
                        $(".append_html").html(data);
                        var current_obj = $('.main_div:first').addClass('active_box');
                        check_emergency_appointment(current_obj);


                        doctor_id_array =[];
                        $('.main_div').each(function( index, value ) {
                            doctor_id_array.push($(this).attr('id'));
                            var list_doc_id =$(this).attr('id');
                            var tmp = [];
                            $("#"+list_doc_id).find('.doctor_patient_list li').each(function( index, value ) {
                                if(index > 0){
                                    var patient_name = $(this).find(".patient_box").text().trim();
                                    var token = $(this).find(".patient_box").text().trim();
                                    var room = $("#"+list_doc_id).find(".room_number").text().trim().split(":");
                                    room = (room.length==2)?room[1].trim():'';
                                    tmp.push({token:token,name:patient_name,room_number:room});
                                }
                            });


                        });


                        index = 1;
                        interIneer = setInterval(function () {

                            var current_obj = $('.active_box');
                            check_emergency_appointment(current_obj);
                            if($('.active_box').length == 0){
                                check_emergency_appointment($('.main_div:first'));
                                $('.main_div:first').addClass('active_box');

                            }else{
                                current_obj = $('.active_box');
                                $('.main_div').removeClass('active_box');
                                if($(current_obj).next('.main_div').length == 1 ){
                                    check_emergency_appointment($(current_obj).next('.main_div'));
                                    $(current_obj).next('.main_div').addClass('active_box');
                                }else{
                                    check_emergency_appointment($('.main_div:first'));
                                    $('.main_div:first').addClass('active_box');
                                }
                            }


                        },change_doctor_seconds);
                        process_running = false;


                    },
                    error: function(data){
                        $(".loader_div").hide();
                        if(showloader===true){
                            // $.alert("Sorry something went wrong on server.");
                        }
                        process_running =false;

                    }
                });

            }



            /* Get into full screen */
            function GoInFullscreen(element) {
                if(element.requestFullscreen)
                    element.requestFullscreen();
                else if(element.mozRequestFullScreen)
                    element.mozRequestFullScreen();
                else if(element.webkitRequestFullscreen)
                    element.webkitRequestFullscreen();
                else if(element.msRequestFullscreen)
                    element.msRequestFullscreen();
                    
                    $("#top_address_drp").hide();
            }

            /* Get out of full screen */
            function GoOutFullscreen() {
                if(document.exitFullscreen)
                    document.exitFullscreen();
                else if(document.mozCancelFullScreen)
                    document.mozCancelFullScreen();
                else if(document.webkitExitFullscreen)
                    document.webkitExitFullscreen();
                else if(document.msExitFullscreen)
                    document.msExitFullscreen();
                     $("#top_address_drp").show();
            }

            /* Is currently in full screen or not */
            function IsFullScreenCurrently() {
                var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;

                // If no element is in full-screen
                if(full_screen_element === null)
                    return false;
                else
                    return true;
            }

            $("#go-button").on('click', function() {
                if(IsFullScreenCurrently())
                    GoOutFullscreen();
                else
                    GoInFullscreen($("#element").get(0));
            });

            $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
                if(IsFullScreenCurrently()) {
                    $("#go-button").attr('src',image_path+"small_screen.png");
                }
                else {
                    $("#go-button").attr('src',image_path+"large_screen.png");
                }
            });


            blink_interval = setInterval(blink_text, 1000);
            function blink_text() {
                $('.blink').fadeOut(500);
                $('.blink').fadeIn(500);
            }

            /* screen resolution change option */
            function setCookie(cname, cvalue, exdays) {
                var d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                var expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";";
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
            $(document).on("change","#screen_size_drp",function(){
                document.body.style.zoom = $(this).val();
                setCookie("tracker_screen", $(this).val(), 800);
            });

            var screen_size = getCookie("tracker_screen");
            $("#screen_size_drp").val("100%");
            document.body.style.zoom = "100%";
            if(screen_size!=''){
                $("#screen_size_drp").val(screen_size);
                document.body.style.zoom = screen_size;
            }


        });
    </script>

    </body>
    </html>
<?php }else if($app_data['tracker_template_id'] == 20){ ?>

    <script>
        window.location.href = "<?php echo Router::url('/tracker/display_tracker_opd_new/'.base64_encode($thin_app_id),true);?>";
    </script>
<?php }else if($app_data['tracker_template_id'] == 21){ ?>

    <script>
        window.location.href = "<?php echo Router::url('/tracker/display_tracker_opd_media/'.base64_encode($thin_app_id),true);?>";
    </script>
<?php }else if($app_data['tracker_template_id'] == 22){ ?>

    <script>
        window.location.href = "<?php echo Router::url('/tracker/display_tracker_opd_multiple/'.base64_encode($thin_app_id),true);?>";
    </script>
<?php }else if($app_data['tracker_template_id'] == 23){ ?>
    <script>
        window.location.href = "<?php echo Router::url('/tracker/display_tracker_opd_media_two_column/'.base64_encode($thin_app_id),true);?>";
    </script>
<?php } ?>

<?php if(!empty($ivr_number)){ ?>


    <script>
        var ivr_number = '<?php echo $ivr_number; ?>';
        var url = '<?php echo Router::url("/images/token_1.jpg",true); ?>';
         var img = "<img  class='msg_image' src='"+url+"' style='z-index:999999;display: none; width: 100%;height: 85%;position: fixed; top: 0;left: 0;' >";
        var div = "<div class='msg_image' style='z-index:999999;display:none; width: 100%; font-size: 4rem; font-weight: 500; padding-top:10px; background:#034a03;color:#fff;text-align: center;height: 15%; position: fixed;bottom: 0;left: 0;'>CALL <span style='color:#ffeb00;font-weight: 600;'>"+ivr_number+"</span> TO BOOK APPOINTMENT. </div>";
       
        $("#mu-hero, #element").append(img+div);

        setInterval(function () {
            $(".msg_image").show();
            setTimeout(function () {
                $(".msg_image").fadeOut(8000);
            },10000);

        },90000);


    </script>

<?php } ?>


<div class="footertext">Powered by <img class="logo-mengage" src="https://mengage.in/doctor/images/logo.png"></div>
<style>
    .footertext img {
        height: 50px;
    }
    .footertext {
        width: 100%;
        position: fixed;
        bottom: 0;
        left: 0;
        text-align: right;
        font-size: 20px;
        font-weight: bold;
    }
    .break_doctor{
        text-align: center;
        /* min-width: 10%; */
        display: block;
        font-size: 2.5rem;
        background: blue;
        color: #fff;
        margin: 0 auto;
        padding: 5px 13px;
        border-radius: 0px 0px 30px 30px;
        width: 40%;
        font-weight: 600;
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
        margin: 0px;
        margin-top: 5%;
        font-size: 12rem;
        width: 100%;
        text-align: center;
        opacity: 1;
    }
    .overlay_box_div .emer_patient_name {
        text-align: center;
        width: 100%;
        font-size: 7rem;
        margin-top: 2%;
    }

    .overlay_box_div .emer_patient_name {
        text-align: center;
        width: 100%;
        font-size: 7rem;
        margin-top: 2%;
    }

</style>
