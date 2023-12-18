<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8" />
    <title>Token Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <link rel="stylesheet" href="<?php echo Router::url('/css/moq_css.css?'.date('his'),true)?>" />
    <link rel="manifest" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/manifest.json?'.date('his'),true);?>" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-72x72.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-96x96.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-128x128.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-144x144.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-152x152.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-192x192.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-384x384.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-512x512.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#5D54FF" />
    <meta name="theme-color" content="#5D54FF" />
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js','moment.js','bootstrap-datepicker.min.js','firebase-app.js','firebase-messaging.js','wickedpicker.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css','bootstrap-datepicker.min.css','wickedpicker.min.css' ),array("media"=>'all')); ?>
    <script src="<?php echo Router::url('/app.js',true); ?>"></script>
    <style>

        .info_msg{
            color: #0f5a1f;
            font-size: 0.7rem;
            text-align: center;
            width: 100%;
            display: block;
            padding: 2px 0px;
            font-weight: 600;
        }

        .status_icon{
            border: 1px solid;
            color: green;
            padding: 3px 5px;
            display: block;
            border-radius: 20px;
            font-size: 0.6rem;
            font-weight: 600;
            margin: 0 auto;
            width: 80%;
            background: transparent;
        }
        #btnSave{
            position: fixed;
            left: 0;
            bottom: 0;
            color: #5D54FF;
            background: #fff;
            border-radius: 0px;
            outline: none;
            padding: 5px 2px;
            width: 98%;
            margin: 2% 1%;
            border: none;
            border-top: 2px solid;

        }

        #btnSave img{
            width: 25px;
            height: 25px;
            display: inline-flex;
            margin-top: -3px;
            position: absolute;
            left: 23px;

        }

        .jconfirm-content{
            float: left;
            width: 100%;

        }
        .jconfirm-content ul{
            margin: 0;
            padding: 0;
        }
        .jconfirm-content ul li{
            float: left;
            width: 19%;
            list-style: none;
            font-size: 0.7rem;
            text-align: center;
            border: 1px solid #cfcfcf;
            margin: 0.2rem 0.1rem;
        }
        .BOOKED{
            background: red;
            color: #fff;
        }
        .BLOCKED{
            background: #794848;
            color: #fff;
        }

        .selected_time{
            background: #1426ffd9;
            color: #fff;
        }
        .main_box{
            float: left;
            width: 100%;
            display: block;
            margin: 0 !important;
            padding: 0;

        }

        #list_append{
            text-align: center;
        }

        .box_heading{
            font-size: 1.5rem;
            width: 100%;
            text-align: center;
            border-bottom: 1px solid rgb(210 210 210);
            padding-bottom: 7px;
            margin: 8px 0.5rem;
        }
        .button span{
            display: block;
            width: 100%;
            text-align: center;
        }
        .counter_container{
            width: 100%;
            padding: 0.5rem;
        }
        .counter_container .jconfirm-content{
            float: left;
            display: block;
            width: 100%;
        }
        .counter_container label{
            float: left;
            display: block;
            width: 100%;
            padding: 0.3rem;
            border: 1px solid #d2cdcd;
            border-radius: 14px;
        }
        .counter_container label input{
            float: left;
            width: 15px;
            height: 15px;
            border: 0px solid #000;
            margin: 0.3rem 0.6rem;
        }

        #profile_sec{
            min-height: 350px;


        }

        .whats_btn, .start_video_call, .start_audio_call{
            float: left;
        }

        .wickedpicker{
            z-index: 999999999 !important;
            width: 47%;
            height: 145px;
        }
        .wickedpicker__close{
            font-size: 1.5rem;
        }

        .wickedpicker__controls__control, .wickedpicker__controls__control--separator {
            font-size: 1.5rem;
            width: 40px;
        }

        .message_bar{
            border: 1px solid;

            text-align: center;
            width: 100%;

        }
        .message_bar table{
            width: 100%;
        }

        .set_current_token{
            text-align: center;
            display: block;
            background: none;
            color: #fff;
            padding: 3px;
            position: absolute;
            bottom: 10px;
            height: 25px;
            width: 25px;
            background: #189114;
            border-radius: 17px;
            float: right;
            right: 10px;

        }


        .play_current_token{
            text-align: center;
            display: block;
            background: none;
            color: #fff;
            padding: 1px;
            position: absolute;
            bottom: 9px;
            height: 25px;
            width: 25px;
            background: #189114;
            border-radius: 14px;
            float: left;
            left: 10px;
        }
        .play_current_token  i{
            color: #fff !important;
        }



        .remark_class label{
            margin: 0.2rem 0.3rem;
            border: 1px solid #3333ea;
            padding: 0.6rem;
            font-size: 0.9rem;
            margin-bottom: 0;

        }

        .jconfirm-content ul {
            height: 460px;
            overflow-y: auto;
            border: 1px solid #ececec;


        }

</style>

</head>


<?php

$background = Router::url('/opd_form/css/backgound.png',true);
$background = "background: url('$background');";
if(empty($data)){
    $background ='overflow:hidden;background:none;';
}

$single_field = 'YES';

?>

<body style="<?php echo $background; ?>;">

<?php if(!empty($data)){ ?>
    <header>
        <h3 style="text-align: center;">
            <img id="logo_image" src="<?php echo $data[0]['logo']; ?>" alt="Logo Image" />
            Daily Token
            <i id="load_setting"  class="fa fa-gears" style="float:right;"></i>
        </h3>

    </header>
<?php } ?>


<div class="container-fluid">
    <div class="login_box">
        <h1 style="font-size: 2rem;">Welcome To MoQ Device</h1>
        <h4 style="font-size: 1.2rem;" >Please login before access account</h4>
        <button class="button" id="login_btn">Login</button>
        <div class="login_message"></div>
    </div>
    <div class="main_box" style="display: none;">
        <input type="hidden" id="t_app" value="<?php echo ($thin_app_id); ?>">
        <?php if(!empty($data)){ ?>
            <div id="token_booking_sec" class="row section_box">

                <h3 class="box_heading">Token Booking
                    <?php if(base64_decode($thin_app_id) == CK_BIRLA_APP_ID){ ?>
                        <span id="counter_report_btn" style="float: right;font-size: 1rem;"><i class="fa fa-line-chart"></i> Report</span>
                    <?php }else{ ?>
                        <span id="report_btn" style="float: right;font-size: 1rem;"><i class="fa fa-line-chart"></i> Report</span>
                    <?php } ?>
                </h3>
                <div class="col-12">
                    <div class="doctor_selection">

                        <label class="inner_label">Tab on name to select doctor</label>
                        <?php foreach ($data as $key => $doctor){ ?>
                            <label data-name="<?php echo $doctor['doctor_name']; ?>" class="container_element <?php echo ($key==0)?'selected_label':''; ?>"><?php echo $doctor['doctor_name']; ?>
                                <input data-dur="<?php echo $doctor['service_slot_duration']; ?>" data-ai="<?php echo base64_encode($doctor['address_id']); ?>" data-id="<?php echo $doctor['doctor_id']; ?>" type="radio" class="doctor_input" name="doctor_name" value="<?php echo $doctor['mobile']; ?>" <?php echo ($key==0)?'checked="checked"':''; ?>  >
                                <span class="checkmark">
                        <img class="doctor_list_profile" src="<?php echo !empty($doctor['profile_photo'])?$doctor['profile_photo']:$doctor['logo']; ?>">
                    </span>
                            </label>
                        <?php } ?>
                    </div>
                    <div style="display: none;" class="booking_form">
                        <div class="doctor_bar">
                            <img class="selected_img_box"  src="<?php echo $data[0]['logo']; ?>">
                            <h4 style="margin: 10px; padding: 0px;text-align: center;" class="doctor_name_lbl" ></h4>
                        </div>
                        <p style="font-size: 0.9rem;"><?php echo ($category_name=='TEMPLE')?'User':'Patient'; ?> Information</p>

                        <?php
                        $display = 'none';
                        if($data[0]['allow_only_mobile_number_booking']=='NO'){
                            $display = 'block';
                        }
                        ?>

                        <div style="display: <?php echo $display; ?>" class="name_container">
                            <label>First Name</label>
                            <input type="text" maxlength="35" id="patient_name" />
                        </div>


                        <label>Mobile <span style="color: red;">*</span></label>
                        <input type="number" pattern="\d{3}[\-]\d{3}[\-]\d{4}" required id="patient_mobile" />
                        <span class="info_msg">Use '9999999999' in case patient has not mobile number </span>

                        <label class="message_lbl"></label>
                    </div>
                </div>
                <div class="col-12 buton_box" style="padding-left: 0;padding-right: 0px;">

                    <button type="button" class="btn btn-xs button"  style="display: none;" id="book_appointment_other">Book Token<span style="font-size:0.6rem;" >( Other Apps/ Pre Appointment )</span></button>
                    <button type="button" class="btn btn-xs button"  style="display: none;" id="book_appointment">Book Token<span>( Next Available )</span></button>
                    <?php if(count($data) > 1){ ?>
                        <button type="button" class="btn btn-xs button btn-danger" style="display: none;" id="select_doctor"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</button>
                    <?php } ?>
                    <button type="button" class="btn btn-xs button btn-success"   id="token_list_btn"><i class="fa fa-th-list" aria-hidden="true"></i> Token List</button>

                </div>
            </div>
            <div id="token_list_sec" style="display: none;margin-left: 0;margin-right: 0;" class="row section_box">

                <h1 class="top_heading">Token List</h1>



                <div class="col-12">


                    <select style="margin-bottom: 10px; width: 100% !important;display: <?php echo (count($data) > 1)?'initial':'none'; ?>;" id="select_doctor_drp">
                        <?php foreach ($data as $key => $list) { ?>
                            <option value="<?php echo $list['doctor_id']; ?>"><?php echo $list['doctor_name']; ?></option>
                        <?php } ?>
                    </select>
                    <?php if($this->AppAdmin->check_app_enable_permission(base64_decode($thin_app_id),"QUEUE_MANAGEMENT_APP")){ ?>
                    <div class="queue_display_container">
                        <a  href="javascript:void(0);" class="set_current_token" ><i class="fa fa-pencil"></i></a>
                        <a  href="javascript:void(0);"  class="play_current_token" ><i class="fa fa-play-circle-o"></i></a>

                        <label class="message_bar" ></label>
                    </div>

                    <?php } ?>
                </div>
                <div class="col-12 buton_box">
                    <button style="border: none;" class="button btn btn-xs" id="refresh_token_btn"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Token</button>
                    <button style="border: none;" class="button  btn btn-xs" id="back_token_btn"><i class="fa fa-ticket" aria-hidden="true"></i> Book Token</button>

                    <?php if(base64_decode($thin_app_id) ==CK_BIRLA_APP_ID){ ?>
                        <button style="" class="button btn btn-xs" id="counter_setting_btn"><i class="fa fa-gear" aria-hidden="true"></i> Counter Setting</button>
                    <?php } ?>
                    <?php if(base64_decode($thin_app_id) !=892 ){ ?>
                        <button style="" class="button btn btn-xs" id="update_next_token"><i class="fa fa-desktop" aria-hidden="true"></i> Next Token</button>
                        <button style="" class="button btn btn-xs" id="close_token_btn"><i class="fa fa-power-off" aria-hidden="true"></i> Close Token</button>
                    <?php } ?>

                </div>
                <div class="col-12" id="list_append">

                </div>
            </div>

            <div id="profile_sec" style="display:none;"  class="row section_box">

            </div>

        <?php }else{ ?>
            <h2> Invalid Request </h2>
        <?php } ?>
    </div>

    <button style="display:none;" id="btnSave"><img  src="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-72x72.png" alt="image"> Save to homescreen for ease of navigation </button>

</div>


</body>

<script>
    $( document ).ready(function() {

        var staff_id = "<?php echo $staff_id; ?>";
        var display = "<?php echo $display; ?>";



        function loadSetting(di){

            var di = $(".doctor_input:checked").attr('data-id');

            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/mq_setting',true); ?>",
                data:{di:btoa(di)},
                beforeSend:function(){
                    $("#token_booking_sec, #token_list_sec").hide();
                    $("#profile_sec").show();
                    $("#profile_sec").html('<h5 style="text-align:center;">Please wait..</h5>');
                },
                success:function(response){
                    $("#profile_sec").html(response);
                },
                error: function(data){
                    alert('Something, went wrong on server');
                }
            });
        }


        $(document).on("click","#load_setting",function(e){
            loadSetting();
        });

        $(document).on("click","#logo_image",function(e){
            $("#token_booking_sec").show();
            $("#profile_sec, #token_list_sec").hide();
        });



        var local_name = "moq_login"+staff_id;
        function setLocalData(string){
            localStorage.setItem(local_name, string);
        }
        function getLocalData(){
            if(localStorage.getItem(local_name)){
                return localStorage.getItem(local_name);
            }
            return false;
        }

        function CheckLogin(password,convert){
            var $btn =  $("#login_btn");
            var data = {
                si:staff_id,
                p:password,
                convert:convert
            }
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/checkLogin',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Login...');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text('Login');
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        setLocalData(JSON.stringify(response.data));
                        if(response.data.mobile=="<?php echo $app_data['phone']; ?>"){
                            $("#counter_report_btn").show();
                        }


                        $('.main_box').show();
                        $('.login_box').hide();
                    }else{
                        alert('This is not valid password');
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text('Login');
                    alert('Something, went wrong on server');
                }
            });
        }

        var user_data = getLocalData();
        if(user_data){
            data = JSON.parse(user_data);
            CheckLogin(data.password,'no');

        }else{
            $(".login_box").show();
            $(".main_box").hide();
        }


        $(document).on("click","#login_btn",function(e){
            password = prompt("Please enter password for login");
            if(password){
                CheckLogin(password,'yes');
            }
        });



        $(document).on("click",".token_list",function(e){


            $(".remark_class").hide();
            if($(this).attr('data-status')!='BLOCKED'){
                $(".token_list").removeClass('selected_time');
                $(this).addClass('selected_time');

                if($(this).attr('data-status')=='BOOKED'){
                    $(".remark_class").show();
                }



            }

        });


        $(document).on("click","#book_appointment_other",function(e){

            if(!$('#patient_mobile').val().match('[0-9]{10}') || $('#patient_mobile').val().length!=10){
                $(".message_lbl").css('color','red');
                $(".message_lbl").html("Please enter 10 digit mobile number");
                $('#patient_mobile').focus();
                e.preventDefault();
                return false;
            }else if($('#patient_mobile').val()=='9999999999' && $('#patient_name').val()==''){
                $(".message_lbl").css('color','red');
                $(".message_lbl").html("Please enter patient name");
                $('#patient_name').focus();
                e.preventDefault();
                return false;
            }else{
                $btn = $("#book_appointment_other");
                var last_text = $($btn).find('span').html();

                var data = {
                    di:btoa($('.doctor_input:checked').attr('data-id')),
                    ai:$('.doctor_input:checked').attr('data-ai'),
                    dur:$('.doctor_input:checked').attr('data-dur'),
                    ti:"<?php echo $thin_app_id; ?>",
                }
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/homes/load_doctor_time_slot',true); ?>",
                    data:data,
                    beforeSend:function(){
                        $($btn).prop('disabled',true);
                        $($btn).find("span").text('Loading Slot...');
                    },
                    success:function(response){

                        $($btn).prop('disabled',false);
                        $($btn).find("span").text(last_text);


                        var response = JSON.parse(response);
                        if(response.status == 1){

                            var time_string ="<ul>";
                            $.each(response.data.slot_list,function(index, value){
                                time_string += "<li  data-status='"+value.status+"' data-queue='"+value.queue_number+"' data-slot='"+value.slot+"' class='token_list "+value.status+"'>"+value.queue_number+"<br>"+value.slot+"</li>";
                            });
                            time_string +="</ul><p style='width:100%;float:left;font-size:0.8rem;color:blue;margin-top:10px;'>You can also book sub token by choosing booked token slot</p><p class='remark_class' style='display:none;width:100%;float:left;'><span style='display:block;font-weight:600;'>Select Token Type </span><label><input type='radio' checked='checked' value='Emergency' class='app_type_radio' name='remark'>Emegency</label><label><input type='radio' class='app_type_radio' value='Pre-Appointment' name='remark'>Pre-Appointment</label></p>";
                            var jc = $.confirm({
                                title: 'Chose Time/Token',
                                columnClass:'box_container',
                                content: time_string,
                                html:true,
                                buttons: {
                                    next:{
                                        text: 'Book Appointment',
                                        btnClass: 'btn-blue emr_slot_time_btn',
                                        action: function (e) {

                                            var slot  = $(".selected_time").attr('data-slot');
                                            var queue  = $(".selected_time").attr('data-queue');
                                            var status  = $(".selected_time").attr('data-status');

                                            if(status!='BLOCKED'){
                                                if(!slot || !queue || !status ){
                                                    $.alert('Please select token/time');
                                                }else{
                                                    bookAppointment($("#book_appointment_other"),queue,slot,status);
                                                    jc.close();
                                                }
                                            }



                                            return false;
                                        }
                                    },
                                    cancel: {
                                        btnClass: 'emr_not_slot_time_btn',
                                        text: 'Cancel',
                                        action: function () {
                                            $($btn).prop('disabled',false);
                                            $($btn).find("span").text(last_text);
                                        }
                                    }
                                },
                                onContentReady: function () {

                                }
                            });



                        }else{
                            alert(response.message);

                        }
                    },
                    error: function(data){
                        $($option).html(last_text);

                        alert('Something, went wrong on server');
                    }
                });
            }



        });



        var single_field = "<?php echo $single_field; ?>";
        if(single_field=='NO'){
            var base_color = "rgb(230,230,230)";
            var active_color = "rgb(237, 40, 70)";
            var child = 1;
            var length = $("section").length - 1;
            $("#prev").addClass("disabled");
            $("#submit").addClass("disabled");

            $("section").not("section:nth-of-type(1)").hide();
            $("section").not("section:nth-of-type(1)").css('transform','translateX(100px)');

            var svgWidth = length * 200 + 24;
            $("#svg_wrap").html(
                '<svg version="1.1" id="svg_form_time" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 ' +
                svgWidth +
                ' 24" xml:space="preserve"></svg>'
            );

            function makeSVG(tag, attrs) {
                var el = document.createElementNS("http://www.w3.org/2000/svg", tag);
                for (var k in attrs) el.setAttribute(k, attrs[k]);
                return el;
            }

            for (i = 0; i < length; i++) {
                var positionX = 12 + i * 200;
                var rect = makeSVG("rect", { x: positionX, y: 9, width: 200, height: 6 });
                document.getElementById("svg_form_time").appendChild(rect);
                // <g><rect x="12" y="9" width="200" height="6"></rect></g>'
                var circle = makeSVG("circle", {
                    cx: positionX,
                    cy: 12,
                    r: 12,
                    width: positionX,
                    height: 6
                });
                document.getElementById("svg_form_time").appendChild(circle);
            }

            var circle = makeSVG("circle", {
                cx: positionX + 200,
                cy: 12,
                r: 12,
                width: positionX,
                height: 6
            });
            document.getElementById("svg_form_time").appendChild(circle);

            $('#svg_form_time rect').css('fill',base_color);
            $('#svg_form_time circle').css('fill',base_color);
            $("circle:nth-of-type(1)").css("fill", active_color);


            $(".button").click(function () {
                $("#svg_form_time rect").css("fill", active_color);
                $("#svg_form_time circle").css("fill", active_color);
                var id = $(this).attr("id");
                if (id == "next") {
                    var obj = $("#patient_name");
                    if($(obj).val()!=''){
                        $(obj).removeClass('req_input');
                        $("#prev").removeClass("disabled");
                        if (child >= length) {
                            $(this).addClass("disabled");
                            $('#submit').removeClass("disabled");
                        }
                        if (child <= length) {
                            child++;
                        }
                    }else{
                        $(obj).addClass('req_input');
                    }
                } else if (id == "prev") {
                    $("#next").removeClass("disabled");
                    $('#submit').addClass("disabled");
                    if (child <= 2) {
                        $(this).addClass("disabled");
                    }
                    if (child > 1) {
                        child--;
                    }
                }
                var circle_child = child + 1;
                $("#svg_form_time rect:nth-of-type(n + " + child + ")").css(
                    "fill",
                    base_color
                );
                $("#svg_form_time circle:nth-of-type(n + " + circle_child + ")").css(
                    "fill",
                    base_color
                );
                var currentSection = $("section:nth-of-type(" + child + ")");
                currentSection.fadeIn();
                currentSection.css('transform','translateX(0)');
                currentSection.prevAll('section').css('transform','translateX(-100px)');
                currentSection.nextAll('section').css('transform','translateX(100px)');
                $('section').not(currentSection).hide();
            });

            var inputElements = document.querySelectorAll("input[data-format]");
            inputElements.forEach(input => {
                let m = new IMask(input, {
                    mask: input.getAttribute("data-format")
                });
            });
            function diff_years(dt2, dt1)
            {

                var diff =(dt2.getTime() - dt1.getTime()) / 1000;
                diff /= (60 * 60 * 24);
                return Math.abs(Math.round(diff/365.25));

            }
            $(document).on("input","#dob",function(e){

                $("#age").val('');
                var parts =$("#dob").val().split('-');
                if(parts.length==3 && parts[2].length==4){
                    var dt1 = new Date(parts[2], parts[1] - 1, parts[1]);
                    var dt2 = new Date();
                    $("#age").val(diff_years(dt2, dt1));
                }

            });
        }

        var total_doctor = "<?php echo count($data); ?>";

        $(document).on("change, click",".doctor_input",function(e){

            showBookScreen(this);
        });


        function showBookScreen(obj){
            $(".container_element").removeClass("selected_label");
            $(obj).closest('.container_element').addClass("selected_label");
            $(".doctor_bar .doctor_name_lbl").html($(".selected_label").attr('data-name'));
            var src = $(".selected_label img").attr('src');
            $(".doctor_bar img").attr('src',src);

            $(".doctor_selection").hide();
            if($(".doctor_input").length > 1){
                $("#select_doctor").show();
            }
            $(".booking_form, #book_appointment, #book_appointment_other").show();
            $("#patient_name").focus();
        }

        if(total_doctor == 1){
            $(".doctor_input:first-child").trigger('click');
        }

        $(document).on("click","#select_doctor",function(e){
            $(".booking_form, #book_appointment, #select_doctor, #book_appointment_other, #token_list_sec").hide();
            $(".doctor_selection").show();

        });


        $(document).on("input","#patient_mobile",function(e){
            $(".message_lbl").html('');
            if($(this).val()=='9999999999'){
                $(".name_container").show();
            }else{
                $(".name_container").css('display',display);
            }


        });


        function bookAppointment(obj,token,time,status){

            var last_html = $(obj).find('span').html();

            if(!$('#patient_mobile').val().match('[0-9]{10}') || $('#patient_mobile').val().length!=10){
                $(".message_lbl").css('color','red');
                $(".message_lbl").html("Please enter 10 digit mobile number");
                $('#patient_mobile').focus();
            }else if($('#patient_mobile').val()=='9999999999' && $('#patient_name').val()==''){
                $(".message_lbl").css('color','red');
                $(".message_lbl").html("Please enter patient name");
                $('#patient_name').focus();
            }else{
                var $btn =  $(obj);
                var data = {
                    patient_name:$('#patient_name').val(),
                    patient_mobile:$('#patient_mobile').val(),
                    doctor_mobile:btoa($('.doctor_input:checked').val()),
                    thin_app_id:$("#t_app").val(),
                    token:token,
                    slot:time,
                    status:status,
                    remark:(status=="BOOKED")? $(".app_type_radio:checked").val():""
                };
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/services/mq_form_booking',true); ?>",
                    data:data,
                    beforeSend:function(){
                        $($btn).prop('disabled',true);
                        $($btn).find("span").text('Booking..');
                    },
                    success:function(response){
                        $($btn).prop('disabled',false);
                        $($btn).find("span").text(last_html);
                        var response = JSON.parse(response);
                        if(response.status == 1){
                            $("#patient_name, #patient_mobile").val('');
                            $(".message_lbl").css('color','green');
                            $("#patient_mobile").trigger("input");
                            if(confirm(response.message)){
                                $("#select_doctor_drp").val($('.doctor_input:checked').attr('data-id'));
                                $("#token_list_btn").trigger("click");
                            }
                        }else{
                            $(".message_lbl").css('color','red');
                            $(".message_lbl").html(response.message);
                        }
                        $(".booking_form").append("");

                        setTimeout(function () {
                            $(".message_lbl").html('');
                        },5000);
                    },
                    error: function(data){
                        $($btn).prop('disabled',false);
                        $($btn).find("span").text(last_html);
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });
            }
        }

        $(document).on("click","#book_appointment",function(e){
            bookAppointment(this,'','','');
        });


        var $list_request;
        function loadTokeList(loading){
            var open_menu =$(".drop_action_btn[aria-expanded='true']").length;
            if($("#token_list_sec").is(":visible") && open_menu ==0){
                var data = {
                    doctor_id:btoa($('#select_doctor_drp').val()),
                    thin_app_id:$("#t_app").val()
                };
                $list_request = $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/homes/mq_token_list',true); ?>",
                    data:data,
                    beforeSend:function(){
                        if ($list_request != null){
                            $list_request.abort();
                        }
                        if(loading==true){
                            $("#list_append").html("<h4>Loading...</h4>");
                        }
                    },
                    success:function(response){
                        $("#list_append").html(response);
                        $(".play_current_token").show();
                    },
                    error: function(data){
                        //$("#list_append").html("");
                        //alert("Sorry something went wrong on server.");
                    }
                });
            }
        }

        $(document).on("change","#select_doctor_drp",function(e){
            loadTokeList(true);
        });

        $("#select_doctor_drp").trigger("change");





        $(document).on('click','.set_current_token',function(e){
            var obj = $(this);
            var di = btoa($("#select_doctor_drp").val());
            var ti = $("#t_app").val();

            var dialog = $.confirm({
                title: 'Update Current Token',
                content: "<label>Enter Current Token</label><input autocomplete='off' type='number' class='form-control' name='current_token_input' id='current_token_input'>",
                buttons: {
                    formSubmit: {
                        text: 'Update',
                        btnClass: 'btn-blue assign_btn',
                        action: function () {
                            var $btn = $(".assign_btn");
                            var text =  $($btn).html();
                            var token = this.$content.find('#current_token_input').val();
                            if(token==''){
                                $.alert("Please enter token number");
                            }else{
                                $.ajax({
                                    type: 'POST',
                                    url: "<?php echo Router::url('/homes/setCurrentToken',true); ?>",
                                    data: {di:di,ti:ti,token:token},
                                    beforeSend: function () {
                                        $($btn).prop('disabled',true).text('Wait..');
                                    },
                                    success: function (data) {
                                        $($btn).prop('disabled',false).html(text);
                                        $("#select_doctor_drp").trigger('change');
                                        dialog.close();

                                    },
                                    error: function (data) {
                                        $($btn).prop('disabled',false).html(text);
                                        $.alert("Sorry something went wrong on server.");
                                    }
                                });
                            }
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {




                }
            });





        });



        $(document).on("click","#update_next_token",function(e){


            jc = $.confirm({
                title: 'Next Token',
                columnClass:'counter_container',
                content: "Are you sure you want to skip this token?",
                html:true,
                buttons: {
                    next:{
                        text: 'Yes',
                        btnClass: 'btn-success download_btn',
                        action: function (e) {
                            var data = {
                                ti:$("#t_app").val(),
                                di:$("#select_doctor_drp").val()
                            };
                            var $btn =  $(".download_btn");
                            var text =  $($btn).html();
                            $.ajax({
                                type:'POST',
                                url: "<?php echo Router::url('/homes/updateNextToken',true); ?>",
                                data:data,
                                beforeSend:function(){
                                    $($btn).prop('disabled',true).text('Wait..');
                                },
                                success:function(response){
                                    jc.close();
                                    $($btn).prop('disabled',false).html(text);
                                    var response = JSON.parse(response);
                                    $(".message_bar").html(response.string);

                                },
                                error: function(data){
                                    jc.close();
                                    $($btn).prop('disabled',false).html(text);
                                    alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    cancel: {
                        btnClass: 'btn btn-danger',
                        text: 'No',
                        action: function () {
                            jc.close();
                        }
                    }
                },
                onContentReady: function () {

                }
            });




        });

        $(document).on("click","#close_token_btn",function(e){

            var $btn =  $(this);
            var text =  $(this).html();
            var data = {
                id:$(this).attr('data-id')
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/close_appointment',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).html(text);
                    var response = JSON.parse(response);
                    if(response.status == 1){

                        $(".message_bar").html(response.string);
                    }else{
                        alert(response.message);
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).html(text);
                    alert("Sorry something went wrong on server.");
                }
            });

        });

        $(document).on("click",".skip_btn",function(e){
            var $btn =  $(this);
            var text =  $(this).text();

            var data = {
                id:$(this).attr('data-id')
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/skip_appointment',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        var last_status = $($btn).closest('tr').find('.status_td').text('Skipped');

                        $($btn).hide();
                        loadTokeList(false);
                    }else{
                        alert(response.message);
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry something went wrong on server.");
                }
            });



        });


        $(document).on("click",".unskip_btn",function(e){
            var $btn =  $(this);
            var text =  $(this).text();

            var data = {
                id:$(this).attr('data-id')
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/un_skip_appointment',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        var last_status = $($btn).attr('data-status');
                        $($btn).closest('.btn_td').find('.skip_btn').show();
                        $($btn).closest('tr').find('.status_td').html(last_status);
                        $($btn).hide();

                    }else{
                        alert(response.message);
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry something went wrong on server.");
                }
            });



        });

        $(document).on("click",".close_btn",function(e){

            if(confirm("Are you sure you want to close this appointment?")){
                var $btn =  $(this);
                var text =  $(this).text();
                var pat_data =   JSON.parse(getLocalData());
                mobile = btoa(pat_data.mobile);
                var data = {
                    id:$(this).attr('data-id'),
                    m:mobile
                };
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/homes/close_appointment',true); ?>",
                    data:data,
                    beforeSend:function(){
                        $($btn).prop('disabled',true).text('Wait..');
                    },
                    success:function(response){
                        $($btn).prop('disabled',false).text(text);
                        var response = JSON.parse(response);
                        if(response.status == 1){
                            $($btn).closest('tr').find('.status_td').html('Closed');
                            $($btn).closest('tr').find('.btn_td').html('');

                        }else{
                            alert(response.message);
                        }
                    },
                    error: function(data){
                        $($btn).prop('disabled',false).text(text);
                        alert("Sorry something went wrong on server.");
                    }
                });
            }




        });

        $(document).on("click",".send_to_billing_btn",function(e){
            var $btn =  $(this);
            var text =  $(this).text();
            var data = {
                id:$(this).attr('data-id')
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/send_to_billing_counter',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        $($btn).hide();
                        loadTokeList(false);
                    }else{
                        alert(response.message);
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry something went wrong on server.");
                }
            });

        });


        $(document).on("click",".send_to_doctor_btn",function(e){

            var $btn =  $(this);
            var text =  $(this).text();
            var data = {
                id:$(this).attr('data-id')
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/send_to_doctor',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        $($btn).closest('tr').find('.send_to_billing_btn').show();
                        $($btn).remove();
                        loadTokeList(false);
                    }else{
                        alert(response.message);
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry something went wrong on server.");
                }
            });




        });

        $(document).on("click",".cancel_btn",function(e){
            var message = prompt("Enter cancel reason if any");
            if(message || message ==''){
                var $btn =  $(this);
                var text =  $(this).text();
                var data = {
                    id:$(this).attr('data-id'),
                    message:message,
                };
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/homes/cancel_appointment',true); ?>",
                    data:data,
                    beforeSend:function(){
                        $($btn).prop('disabled',true).text('Wait..');
                    },
                    success:function(response){
                        $($btn).prop('disabled',false).text(text);
                        var response = JSON.parse(response);
                        if(response.status == 1){
                            $($btn).closest('tr').find('.status_td').html('Canceled');
                            $($btn).closest('tr').find('.btn_td').html('');
                            loadTokeList(false);
                        }else{
                            alert(response.message);
                        }
                    },
                    error: function(data){
                        $($btn).prop('disabled',false).text(text);
                        alert("Sorry something went wrong on server.");
                    }
                });
            }




        });

        $(document).on("click",".status_btn",function(e){
            var $btn =  $(this);
            var text =  $(this).text();
            var status =  $(this).attr('data-status');
            var t = "<?php echo $thin_app_id; ?>";
            var c =  $(this).attr('data-c');
            var id =  $(this).attr('data-id');
            var data = {
                s:status,id:id,c:c,t:t
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/update_counter_status',true); ?>",
                data:data,
                beforeSend:function(){

                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false);
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        $("#counterModal").modal('hide');
                    }else{
                        $($btn).prop('disabled',false).text(text);
                        alert(response.message);
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry unable to load counters.");
                }
            });



        });

        $(document).on("click",".edit_pat_name",function(e){
            var name = $(this).html();
            var id = $(this).attr('data-pi');
            var ai = $(this).attr('data-ai');
            var name_lbl = $(this).closest('tr').find('.pat_namd_td');
            var last_text = $(name_lbl).html();
            var new_pat_name = prompt("Edit patient name",name);
            if(new_pat_name){

                var data = {ai:ai,pi:id, pn:new_pat_name };
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/homes/edit_patient',true); ?>",
                    data:data,
                    beforeSend:function(){
                        $(name_lbl).text('Wait..');
                    },
                    success:function(response){
                        $(name_lbl).text(last_text);
                        var response = JSON.parse(response);
                        if(response.status == 1){
                            $(name_lbl).html(new_pat_name);
                            loadTokeList(false);
                        }else{
                            alert(response.message);
                        }
                    },
                    error: function(data){
                        $(name_lbl).text(last_text);
                        alert("Sorry something went wrong on server.");
                    }
                });
            }

        });

        $(document).on("click","#token_list_btn",function(e){
            $("#select_doctor_drp").val($('.doctor_input:checked').attr('data-id'));
            $("#token_booking_sec").hide();
            $("#token_list_sec").show();
            $("#select_doctor_drp").trigger("change");
        });

        $(document).on("click","#back_token_btn",function(e){
            var di = $("#select_doctor_drp").val();
            $(".doctor_input[data-id="+di+"]").attr("checked",true);
            $(".doctor_input[data-id="+di+"]").trigger("click");
            $("#token_booking_sec").show();
            $("#token_list_sec").hide();

        });

        $(document).on("click","#refresh_token_btn",function(e){
            $("#token_list_btn").trigger("click");
        });






        function freezScreenButton(show_btn){
            if(show_btn==true){
                $("#book_appointment_other, #token_list_btn, #select_doctor").show();
                if (typeof(Storage) !== "undefined") {
                    localStorage.removeItem("freez");
                }
            }else{
                if (typeof(Storage) !== "undefined") {
                    var di = $('.doctor_input:checked').attr('data-id');
                    localStorage.setItem("freez", di);
                }
                $("#book_appointment_other, #token_list_btn, #select_doctor").hide();
            }
        }

        if (typeof(Storage) !== "undefined") {
            if(localStorage.getItem("freez")){
                var di = localStorage.getItem("freez");
                var obj = $(".doctor_input[data-id="+di+"]");
                showBookScreen(obj);
                freezScreenButton(false);
            }
        }

        $(document).on("dblclick",".selected_img_box",function(e){
            if($("#book_appointment_other").is(":visible")){
                freezScreenButton(false);
            }else{
                password = prompt("Please enter password");
                var valid = "<?php echo date('dm'); ?>";
                if(password){
                    if(password==valid){
                        freezScreenButton(true);
                    }else{
                        alert('Invalid password');
                    }
                }
            }
        });


        /* add to home screen code */

        var deferredPrompt;
        var btnSave = document.getElementById('btnSave');
        window.addEventListener('beforeinstallprompt', function(e) {
            console.log('beforeinstallprompt Event fired');
            e.preventDefault();
            // Stash the event so it can be triggered later.
            deferredPrompt = e;
            return false;
        });

        btnSave.addEventListener('click', function() {
            if(deferredPrompt !== undefined) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then(function(choiceResult) {
                    console.log(choiceResult.outcome);
                    if(choiceResult.outcome == 'dismissed') {
                        console.log('User cancelled home screen install');

                    }
                    else {
                        console.log('User added to home screen');
                        $("#btnSave").hide();
                    }
                    // We no longer need the prompt.  Clear it up.
                    deferredPrompt = null;
                });
            }else{
                var app_name ="<?php echo $app_data['name']; ?>";
                var ua = navigator.userAgent.toLowerCase();
                if (ua.indexOf('safari') != -1) {
                    if (ua.indexOf('chrome') > -1) {
                        alert("Following reasons are that app can not add on the homescreen\n\n1. This web browser does not support this feature. You can try again by updating your browser.\n2. You have already added '"+app_name+"' to homescreen.");
                    } else {
                        var ios= "Following steps are add '"+app_name+"' to on homescreen.\n1. Tap the Share button (at the browser options)\n2. From the options tap the Add to Homescreen option, you can notice an icon of the website or screenshot of website added to your devices homescreen instantly.\n3. Tap the icon from homescreen, then the Progressive Web App of your website will be loaded.";
                        alert(ios);
                    }
                }


            }
        });
        function onLoad(){
            $(".container_element").removeClass("dynamic_width");
            if (window.matchMedia('(display-mode: standalone)').matches) {
                $("#btnSave").hide();

            }else{
                if(!is_desktop()){
                    $("#btnSave").show();
                }else{
                    $("#btnSave").hide();
                    $(".container_element").addClass("dynamic_width");
                }
            }
        }

        function is_desktop(){
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                return false;
            }return true;
        }
        window.addEventListener('load', (event) => {
            onLoad();
        });


        $(document).on("click","#counter_setting_btn",function(e){
            var $btn =  $(this);
            var text =  $(this).text();
            var data = {
                ti:"<?php echo ($thin_app_id); ?>"
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/mq_counter_list',true); ?>",
                data:data,
                beforeSend:function(){
                    $("#counterModal").remove();
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var html = $(response).filter("#counterModal");
                    $(html).modal('show');
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry unable to load counters.");
                }
            });



        });




        $(document).on("click","#counter_report_btn",function(e){
            var $btn =  $(this);
            var htm_string = "<h6>From Date</h6><input autocomplete='off' onkeydown='return false' type='text' id='from_date'><h6>To Date</h6><input autocomplete='off' onkeydown='return false' type='text' id='to_date'><br><iframe id='load_report_frame' style='margin-top:10px;display:none;width:100%;height: 350px;'></iframe>";
            jc = $.confirm({
                title: 'Download Counter Report',
                columnClass:'counter_container',
                content: htm_string,
                html:true,
                buttons: {
                    next:{
                        text: 'Download',
                        btnClass: 'btn-blue download_btn',
                        action: function (e) {
                            var $btn =$(".download_btn");
                            var text =$(".download_btn").text();;


                            var from_date = $("#from_date").val();
                            var to_date = $("#to_date").val();
                            if(from_date==''){
                                $("#from_date").focus();
                            }else if(to_date==''){
                                $("#to_date").focus();
                            }else{

                                $($btn).prop('disabled',true).text('Generationg..');
                                var ti ="<?php echo ($thin_app_id); ?>";
                                var baseUrl = "<?php echo Router::url("/homes/create_counter_report/",true); ?>";
                                var fullUrl = baseUrl+ti+"/"+from_date+"/"+to_date;

                                $("#load_report_frame").attr('src',fullUrl);
                                $("#load_report_frame").show();

                                setTimeout(function(){
                                    $($btn).prop('disabled',false).text(text);
                                },2000)



                            }
                            return false;
                        }
                    },
                    cancel: {
                        btnClass: 'btn btn-success',
                        text: 'Cancel',
                        action: function () {
                            jc.close();
                        }
                    }
                },
                onContentReady: function () {
                    $('#from_date, #to_date').datepicker({
                        setDate: new Date(),
                        autoclose: true,
                        format: 'dd-mm-yyyy'
                    });
                }
            });





        });


        $(document).on("click","#report_btn",function(e){
            var $btn =  $(this);
            var htm_string = "<h6>From Date</h6><input autocomplete='off' onkeydown='return false' type='text' id='from_date'><h6>To Date</h6><input autocomplete='off' onkeydown='return false' type='text' id='to_date'><h6>Amount</h6><input autocomplete='off' type='text' id='amount'><br><iframe id='load_report_frame' style='margin-top:10px;display:none;width:100%;height: 350px;'></iframe>";
            jc = $.confirm({
                title: 'Download Report',
                columnClass:'counter_container',
                content: htm_string,
                html:true,
                buttons: {
                    next:{
                        text: 'Download',
                        btnClass: 'btn-blue download_btn',
                        action: function (e) {
                            var $btn =$(".download_btn");
                            var text =$(".download_btn").text();;


                            var from_date = $("#from_date").val();
                            var to_date = $("#to_date").val();
                            var amount = $("#amount").val();
                            if(from_date==''){
                                $("#from_date").focus();
                            }else if(to_date==''){
                                $("#to_date").focus();
                            }else{

                                $($btn).prop('disabled',true).text('Generationg..');
                                var ti ="<?php echo ($thin_app_id); ?>";
                                var baseUrl = "<?php echo Router::url("/homes/dti_report/",true); ?>";
                                var fullUrl = baseUrl+ti+"/"+from_date+"/"+to_date+"/"+amount;

                                $("#load_report_frame").attr('src',fullUrl);
                                $("#load_report_frame").show();

                                setTimeout(function(){
                                    $($btn).prop('disabled',false).text(text);
                                },2000)



                            }
                            return false;
                        }
                    },
                    cancel: {
                        btnClass: 'btn btn-success',
                        text: 'Cancel',
                        action: function () {
                            jc.close();
                        }
                    }
                },
                onContentReady: function () {
                    $('#from_date, #to_date').datepicker({
                        setDate: new Date(),
                        autoclose: true,
                        format: 'dd-mm-yyyy'
                    });
                }
            });





        });


        var counter_dialog;
        $(document).on("click",".assign_counter_btn",function(e){
            var $btn =  $(this);
            var text =  $(this).text();
            var ai =  $(this).attr('data-id');
            var current_token =  $(this).attr('data-token');
            var data = {
                ti:"<?php echo ($thin_app_id); ?>",
                json_str:'YES'
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/mq_counter_list',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        var htm_string = "<p style='font-size:1rem;font-weight:500;'>Assign Counter For Token Number <b>"+current_token+"</b> </p>";
                        $.each(response.list,function(index, value){
                            if(value.status=='OPEN'){
                                var doc_name = (value.doctor_name)?" ( "+value.doctor_name+" )":'';
                                htm_string +="<label><input class='counter_input' data-ai='"+ai+"' data-ct='"+value.booking_type+"' name='counter_input' type='radio' value='"+value.counter+"'><span><b>"+value.counter+"</b>"+doc_name+"</span></label>";
                            }
                        });
                        htm_string += "<br><h6 style='color:#0514c5;text-align:center;'>Tab on counter name for assign counter</h6>";
                        counter_dialog = $.confirm({
                            title: 'Assign Counter',
                            columnClass:'counter_container',
                            content: htm_string,
                            html:true,
                            buttons: {
                                cancel: {
                                    btnClass: 'emr_not_slot_time_btn',
                                    text: 'Cancel',
                                    action: function () {
                                        counter_dialog.close();
                                    }
                                }
                            },
                            onContentReady: function () {






                            }
                        });
                    }else{
                        alert('This is not valid password');
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry unable to load counters.");
                }
            });

        });

        $(document).on("change",".counter_input",function(e){
            var $btn =  $(this).closest('label');
            var last_html = $(this).html();
            var ai =  $(this).attr('data-ai');
            var ct =  $(this).attr('data-ct');
            var counter =  $(this).val();

            var data = {
                ai:ai,c:counter,ct:ct
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/assign_counter',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Please wait...');
                    $($btn).css('text-align','center');
                },
                success:function(response){
                    $($btn).prop('disabled',false).html(last_html);
                    var response = JSON.parse(response);
                    if(response.status == 1){
                        counter_dialog.close();
                        loadTokeList(false);
                    }else{
                        alert(response.message);
                    }
                },
                error: function(data){
                    $($btn).prop('disabled',false).html(last_html);
                    alert("Sorry unable to assign counter.");
                }
            });
        });

        $(document).on("click",".upload_record, .start_audio_btn, .start_video_call",function(e){
            var url = $(this).attr('data-url');
            var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var h = window.innerHeight || document.documentElement.clientHeight  || document.body.clientHeight;
            var myWindow = window.open(url, "", "width="+w+",height="+h);
        });


        setInterval(function () {
            loadTokeList(false);
        },6000);


        function checkCurrentDate(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            return  mm + '/' + dd + '/' + yyyy;
        }
        var currentDate = checkCurrentDate();
        setInterval(function () {
           // console.log(currentDate);
           // console.log(checkCurrentDate());
            if(currentDate!=checkCurrentDate()){
                window.location.reload();
            }
        },30000);



        $(document).on("click",".play_tracker_voice",function(e){

            var $btn =  $(this);
            var text =  $(this).text();
            var token =  $(this).attr('data-token');
            var doctor_name =  $( "#select_doctor_drp option:selected" ).text();
            var doctor_id =  $( "#select_doctor_drp" ).val();

            var data = {
                doctor_name:doctor_name,
                token:token,
                doctor_id:doctor_id,
                t:$("#t_app").val()
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/play_tracker_voice',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).text('Wait..');
                },
                success:function(response){
                    $($btn).prop('disabled',false).text(text);
                    var response = JSON.parse(response);
                    alert(response.message);
                },
                error: function(data){
                    $($btn).prop('disabled',false).text(text);
                    alert("Sorry something went wrong on server.");
                }
            });




        });

        $(document).on("click",".play_current_token",function(e){

            var currentClass = "fa fa-play-circle-o";
            var loaderClass = "fa fa-spinner fa-spin";
            var $btn =  $(this).find("i");
            var token =  $(".current_token_number_td").text();
            var doctor_name =  $( "#select_doctor_drp option:selected" ).text();
            var doctor_id =  $( "#select_doctor_drp" ).val();
            var data = {
                doctor_name:doctor_name,
                token:token,
                doctor_id:doctor_id,
                t:$("#t_app").val()
            };
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/play_tracker_voice',true); ?>",
                data:data,
                beforeSend:function(){
                    $($btn).prop('disabled',true).removeClass(currentClass);
                    $($btn).addClass(loaderClass);
                },
                success:function(response){
                    $($btn).prop('disabled',true).removeClass(loaderClass);
                    $($btn).addClass(currentClass);

                    var response = JSON.parse(response);

                },
                error: function(data){
                    $($btn).prop('disabled',true).removeClass(loaderClass);
                    $($btn).addClass(currentClass);
                    alert("Sorry something went wrong on server.");
                }
            });




        });


    });
</script>
</html>


