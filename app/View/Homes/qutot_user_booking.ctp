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
    <link rel="manifest" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/manifest.json?'.date('his'),true);?>" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-72x72.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-96x96.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-128x128.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-144x144.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-152x152.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-192x192.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-384x384.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-512x512.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#5D54FF" />
    <meta name="theme-color" content="#5D54FF" />


    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js','bootstrap-datepicker.min.js','moment.js','bootstrap-datetimepicker.min.js','firebase-app.js','firebase-messaging.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css','bootstrap-datepicker.min.css','bootstrap-datetimepicker.min.css' ),array("media"=>'all')); ?>
    <script src="<?php echo Router::url('/app.js',true); ?>"></script>

    <style>


        .more_app{
            float: left;
            display: block;
            border-top: 1px solid green;
            padding: 5px;
            text-align: center;
            margin-top: 8%;
            width: 100%;
        }
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
        Token Booking
    </h3>

</header>
<?php } ?>

<div class="container-fluid">
    <div class="main_box">
        <input type="hidden" id="t_app" value="<?php echo ($thin_app_id); ?>">
        <?php if(!empty($data)){ ?>
            <div id="token_booking_sec" class="row section_box">

                <h3 class="box_heading">Token Booking
                    <?php if(base64_decode($thin_app_id) == CK_BIRLA_APP_ID){ ?>
                        <span id="counter_report_btn" style="display:none;float: right;font-size: 1rem;"><i class="fa fa-line-chart"></i> Report</span>
                    <?php } ?>
                </h3>
                <div class="col-12">
                    <div class="doctor_selection">

                        <label class="inner_label">Tab on name to select counter</label>
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

                        <label style="min-height: auto !important;" class="message_lbl"></label>
                    </div>
                </div>
                <div class="col-12 buton_box" style="padding-left: 0;padding-right: 0px;">

                    <button type="button" class="btn btn-xs button"  style="display: none;" id="book_appointment_other">Book Token<span style="font-size:0.6rem;" >( Other Apps/ Pre Appointment )</span></button>
                    <button type="button" class="btn btn-xs button"  style="display: none;" id="book_appointment">Book Token<span>( Next Available )</span></button>
                    <?php if(count($data) > 1){ ?>
                        <button type="button" class="btn btn-xs button btn-danger" style="display: none;" id="select_doctor"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</button>
                    <?php } ?>
                </div>
            </div>

            <section class="more_app">
                <h6>Now you can manage you queue</h6>
                <a href="<?php echo Router::url('/homes/web_app_signup/',true); ?>" target="_blank" class="btn btn-info">Start Now</a>
            </section>

        <?php }else{ ?>
            <h2> Invalid Request </h2>
        <?php } ?>
    </div>

    <button style="display:none;" id="btnSave"><img  src="<?php echo Router::url('/add_home_screen/qutot/'.$app_id.'/',true);?>icons/icon-72x72.png" alt="image"> Save app to homescreen</button>

</div>


</body>

<script>
    $( document ).ready(function() {

        var display = "<?php echo $display; ?>";


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

                            var jc = $.confirm({
                                title: 'Chose Time/Token',
                                columnClass:'box_container',
                                content: time_string,
                                html:true,
                                buttons: {
                                    next:{
                                        text: 'Book Token',
                                        btnClass: 'btn-blue emr_slot_time_btn',
                                        action: function (e) {
                                            var slot  = $(".selected_time").attr('data-slot');
                                            var queue  = $(".selected_time").attr('data-queue');
                                            var status  = $(".selected_time").attr('data-status');
                                            if(!slot || !queue || !status ){
                                                $.alert('Please select token/time');
                                            }else{
                                                 bookAppointment($("#book_appointment_other"),queue,slot,status);
                                                 jc.close();
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
            if(total_doctor > 1){
                $("#select_doctor").show();
            }
            $(".booking_form, #book_appointment, #book_appointment_other").show();
            $("#patient_name").focus();
        }

        if(total_doctor == 1){
            $(".doctor_input:first-child").trigger('click');
        }

        $(document).on("click","#select_doctor",function(e){
            $(".booking_form, #book_appointment, #select_doctor, #book_appointment_other").hide();
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
                 $(".message_lbl").html("Please enter first name");
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
                      status:status
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






        $(document).on("click","#back_token_btn",function(e){
            $("#token_booking_sec").show();
            $("#token_list_sec").hide();
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


    });
</script>
</html>


