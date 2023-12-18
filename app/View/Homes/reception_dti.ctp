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
    <link rel="icon" type="image/png" href="https://www.mpasscheckin.com/doctor/favicon.png">
    <meta name="apple-mobile-web-app-status-bar" content="#5D54FF" />
    <meta name="theme-color" content="#5D54FF" />

    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js','moment.js','bootstrap-datepicker.min.js','firebase-app.js','firebase-messaging.js','wickedpicker.min.js','ckeditor/ckeditor.js','moment.min.js','daterangepicker.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css','bootstrap-datepicker.min.css','wickedpicker.min.css','daterangepicker.css' ),array("media"=>'all')); ?>


    <style>


        .announce_tracker_btn button{
            border: 1px solid;
            background: none;
            border-radius: 5px;
            cursor: pointer;
            color: green;
        }

        .full_width_btn{
            width: 100% !important;
            margin: 10px 0 !important;
        }


        .singleButonCss{
            float: none  !important;
            margin: 0 auto !important;
        }


        #bottom_option td i{
            display: block;
        }
        #bottom_option .activeTab{
            background: rgb(14 2 233) !important;
        }

        #bottom_option td{
            padding: 5px;
            text-align: center;
        }
        .close_confirm_box_div .jconfirm-box{
            width: 80%;
        }
        .confirm_cancel_btn{
            position: absolute;
            top: 9px;
            right: 10px;
            background: none !important;
            color: #000 !important;
        }
        .counterNameLbl{
            display: block;
            font-size: 0.8rem;
            padding: 0;
            margin: 0 auto;
            color: #000;
            background: yellow;
            width: auto;
            border-radius: 10%;
            height: 25px;
            font-weight: 600;
            display: none;
        }
        .indication_label{
            padding-top:15px;
        }
        .active_token{
            background: red;
            color: #fff;
        }
        .indication_label li{
            float: left;
            list-style: none;
            display: block;
            font-size: 0.6rem;
            width: 33%;
            margin: 0;
        }
        .indication_label li div{
            width: 20px !important;
            height: 20px;
            border-radius: 50%;
            border: 1px solid;
            display: block;
            text-align: center;
            margin: 0 auto;

        }
        .indication_label li label{
            float: left;
            margin: 0;
            padding: 0rem 0.2rem;
            text-align: center;
            display: block;
            width: 100%;

        }

        .button_slider::-webkit-scrollbar {
            /* display: none;*/
        }

        .button_slider {
            /*-ms-overflow-style: none;  !* IE and Edge *!
            scrollbar-width: none;  !* Firefox *!*/
        }


        .scrollable-container {
            width: 100%;
            overflow-x: auto;
            white-space: nowrap;
        }


        .button_slider{
            display: flex;
            padding: 0;
            overflow-x: auto;
            margin: 0;
            cursor: grab;

        }
        .button_slider li:active{
            cursor: grabbing;
        }
        .button_slider li{
            float: left;
            list-style: none;
            margin: 1.5rem 0.3rem;
        }

        .button_slider li span.active{
            font-weight: 600;
            color: #fff;
            background: green !important;
        }

        .button_slider li span.billing{
            font-weight: 600;
            color: #000;
            background: yellow;
        }




        .button_slider li span{
            border: 1px solid;
            padding: 13% 3% !important;
            border-radius: 50%;
            height: 60px;
            width: 60px !important;
            font-size: 1.5rem !important;

        }


        #loaderIcon {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 46% auto;
        }
        #loaderBox{
            background: white;
            position: absolute;
            z-index: 9999;
            width: 100%;
            height: 100%;
            opacity: .8;

        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }


        #dropdownMenuLink{
            background: none;
            border: none;
            padding: 0;
            font-size: 1.5rem;
        }
        .message_box{
            padding: 2rem 0.2rem;
        }
        .message_box p{
            font-size: 1rem;
        }

        .iot_div table td span{
            display: block;
            width: 100%;
            text-align: center;
            padding: 1rem 0;
            font-size: 0.8rem;
        }
        .display_div{
            font-size: 3rem;
            background: black;
            margin: 0;
            padding: 0rem;
            color: red;
            width: 100%;
            text-align: center;
            font-weight: 600;
            height: 4rem;

        }


        .iot_div button {
            padding: 15px 25px;
            font-size: 24px;
            text-align: center;
            cursor: pointer;
            outline: none;
            color: #fff;
            background-color: #1975C6;
            border: none;
            border-radius: 5px;
            box-shadow: 0 9px #999;
            height: 7rem;
            width: 100%;
        }

        .iot_div button:hover {background-color: #1975C6}

        .iot_div button:active{
            background-color: #1975C6;
            box-shadow: 0 5px #666;
            transform: translateY(4px);
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
            margin-bottom: 2rem;
            padding: 0px 0px 2rem 0px;
            border-bottom: 1px solid #bebebe;
            float: left;
            width: 100%;
        }
        .jconfirm-content ul li{
            float: left;
            width: 21%;
            list-style: none;
            font-size: 1rem;
            text-align: center;
            border: 1px solid #cfcfcf;
            margin: 0.2rem 0.4rem;
            padding: 0.6rem;
            font-weight: 600;
            cursor: pointer;
            height: 60px;
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
.close_confirm_box_div{
    width: 60% !important;
}


        .counter_select_btn.active{
            background: green;
            color: #fff;
        }

        .dialog_btn{
display: none !important;

        }

        .half_button{
            height: 3.2rem !important;
            padding: 0 !important;
            font-size: 1.2rem !important;
        }


        .counter_name{
            text-align: left;
            font-weight: 500;
            font-size: 1.5rem;
        }
.counter_token_number{
    text-align: center !important;
    font-weight: 600;
    font-size: 2rem;
}
.doctor_name_lbl{
    text-align: center;
    background: rgb(93 84 255);
    width: auto;

    padding: 0.5rem;

    color: #fff;
    margin: 0 auto;
    display: block;
}
.counter_tr{
    border-bottom: 1px solid grey;
}

.box_container_iot{
    float: left;
    width: 100%;
    display: block;
    border: 1px solid #c8c8c8;
    padding: 0;
    margin: 0.5rem 0.1rem;
}

.announce_tracker_btn{
    text-align: right;
}


.loading_text img{
    width: 40px;
    height: 40px;
}

.token_num_lbl{
    background: yellow;
    color: #000;
    padding: 5px 3px;
    float: left;
    border-radius: 0PX 0PX 15px 0px;
    height: 31px;
    width: 41px;
    margin-left: -10px;
    margin-top: -10px;
    display: block;
}

.counter_name_lbl{

padding: 0;
    display: block;
    float: left;
    text-align: center;
    width: 100%;
}

    </style>

</head>


<?php

$background = Router::url('/opd_form/css/backgound.png',true);
$background = "background: url('$background');";
if(empty($data)){
    $background ='background:none;';
}

$single_field = 'YES';

?>

<body style="<?php echo $background; ?>;">



<div id="loaderBox" style="display: none;">
    <div id="loaderIcon"></div>
</div>


<?php if(!empty($app_data)){ ?>
    <header>
        <table style="width: 100%" class="header_table">
            <tr>
                <td style="width: 10%">
                    <img id="logo_image" src="<?php echo $app_data['logo']; ?>" alt="Logo Image" />
                </td>
                <td>
                    <h3 style="text-align: center;width: 100%;">
                        <?php echo $app_data['name']; ?>
                    </h3>
                </td>
                <td style="width: 5%">
                    <div id="nav_menu"  class="dropleft show ">
                        <a class="btn btn-secondary" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                            <?php
                            $logoutUrl = Router::url('/homes/dti_logout/',true).base64_encode($thin_app_id).'/'.base64_encode($login_id) ;
                            ?>
                            <a class="dropdown-item" href="<?php echo $logoutUrl; ?>">
                                <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                            </a>


                        </div>
                    </div>
                </td>
            </tr>

        </table>

    </header>
<?php } ?>
<input type="hidden" id="t_app" value="<?php echo base64_encode($thin_app_id); ?>">

<div class="container-fluid" style="padding-left: 5px; padding-right: 5px;">
    <div class="row" style="margin-left: 0px;margin-right: 0px;">
    <?php foreach ($final_array as $doctor_id =>$val){ $col = (12/count($final_array));  ?>
     <div class="col-lg-<?php echo $col; ?> col-md-<?php echo $col; ?> col-sm-12 iot_box" data-wise="<?php echo $val['show_token_wise']; ?>" id="<?php echo $val['associated_doctor_id']; ?>">
         <div class="box_container_iot">
         <h3 class="doctor_name_lbl"> <?php echo $val['associated_doctor_name']; ?>
             <button type="button" style="float: right;"  class="btn btn-success tokenListBtn"><i class="fa fa-list"></i> </button>
         </h3>
         <div style="padding: 0;"  class="row section_box counter_list_sec">
             <div class="col-12" style="height: 270px; overflow-y: auto;" >
                 <table style="width: 100%;">
                        <tr>
                            <th style="width:33%;text-align: left;">Room Number</th>
                            <th style="width:33%;text-align: center;">Current Token</th>
                            <th style="width:33%;text-align: right;">Action</th>
                        </tr>
                     <?php foreach($val['counter'] as $key => $counterData) { ?>
                         <tr class="counter_tr" data-id ="<?php echo $counterData['counter_id']; ?>">
                             <td class="counter_name" >
                                 <?php echo $counterData['counter_name']; ?>
                             </td>
                             <td  class="counter_token_number" id="<?php echo "counter_number_".$counterData['counter_id']; ?>" >

                             </td>
                             <td class="announce_tracker_btn">
                                 <button title="Update current token" style="display: none;" class="single_counter_action"><i class="fa fa-power-off"></i> </button>

                                  <button title="Play current token"  style="display: none;" class="single_counter_action_play iot_button" data-type="play" data-status="PLAY"><i class="fa fa-bell"></i> </button>


                             </td>

                         </tr>
                     <?php } ?>

                 </table>
             </div>
         </div>
        <div  id="token_booking_sec" style="padding: 0 !important;" class="row section_box">
            <div class="col-12" >
                <div class="doctor_selection">
                    <div class="iot_div" style="width: 100%;float: left;display: block; text-align: center;">
                        <h6 class="loading_text"  style="position: absolute;top:0;left :10px;"> </h6>

                        <h5 class="assign_token_heading" style="float: left;display: block; margin-top:0px;text-align: center;width: 100%;"></h5>
                        <table style="width: 100%; float: left;display: block;">
                                <tr>
                                    <td>

                                        <ul class="button_slider" style="display: none;"  >
                                            <?php for($counter=1;$counter<=400;$counter++){ ?>
                                                <li style="display: none;" id="token_<?php echo $counter; ?>" >
                                                    <span class="token_<?php echo $counter; ?>"  ><?php echo $counter; ?></span>
                                                    <label class="counterNameLbl"></label>
                                                </li>
                                            <?php } ?>
                                        </ul>


                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
         </div>
    </div>
    <?php } ?>
    </div>
</div>
</body>
    <script type="text/javascript" src="<?php echo TOKEN_SOCKET_URL.'/socket/socket.io.js'; ?>"></script>

<script>
    $( document ).ready(function() {
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

        var login_url = "<?php echo Router::url('/dti/',true).$app_data['slug']; ?>";


        var login_id ="<?php echo $login_id; ?>";
        var thin_app_id ="<?php echo ($thin_app_id); ?>";

        var loaderImage = "<?php echo Router::url('/img/iot_display_loader.gif',true)?>";

        var socetUrl =  "<?php echo TOKEN_SOCKET_URL; ?>";
        socket = io.connect( socetUrl );
        socket.on( 'updateToken', function( data ) {

            if(data.thin_app_id==thin_app_id){

                if(data.doctor_id == login_id && data.reload==true && getCookie('_dti')!=data.dti_token ){
                    window.location.href =login_url;
                }
                if($("#counter_number_"+data.doctor_id).length==1){
                    $("#counter_number_"+data.doctor_id).html(data.token);
                }
                var token_visible = $("#"+data.associate_counter_id).attr('data-wise');
                if($("#"+data.associate_counter_id).length==1){
                    var updateObject = $("#"+data.associate_counter_id);
                    $(updateObject).find(".button_slider li span").removeClass("billing");
                    $(updateObject).find(".button_slider li span").removeClass("active_token");
                    $(updateObject).find(".button_slider li .counterNameLbl").html("");
                    $(updateObject).find(".button_slider li .counterNameLbl").hide();

                    var bookedTokenArray = data.bookedTokenString.split(",");
                    if(token_visible=="NON_BOOKED"){
                        $(updateObject).find(".button_slider li").show();
                    }else{
                        $(updateObject).find(".button_slider li").hide();
                    }

                    if(bookedTokenArray.length > 0){

                        $(updateObject).find(".button_slider").show();
                        if(token_visible=="NON_BOOKED"){
                            $(updateObject).find(".button_slider li").show();
                        }else{
                            $(updateObject).find(".button_slider li").hide();
                        }

                        $.each(bookedTokenArray,function(index,value){
                            $(updateObject).find(".button_slider").find("#token_"+value).show();
                        });

                        $(updateObject).find(".single_counter_action, .single_counter_action_play").hide();

                        if(data.activeTokens!=""){
                            try{
                                var activeTokenArray = JSON.parse(data.activeTokens);
                                $.each(activeTokenArray,function(index,value){
                                    if(value.tNumber){
                                        $(updateObject).find(".button_slider").find(".token_"+value.tNumber.trim()).addClass("active_token");
                                        var updateToken = value.tNumber.trim();
                                        $("#counter_number_"+value.dId).closest(".counter_tr").find(".single_counter_action, .single_counter_action_play").show();
                                        console.log(updateToken);
                                        $("#counter_number_"+value.dId).html(updateToken);
                                        var obj = $(updateObject).find(".button_slider").find("#token_"+value.tNumber.trim()).find(".counterNameLbl");
                                        $(obj).attr('data-id',value.dId);
                                        $(obj).html(value.dName);
                                        $(obj).css('display','block');
                                        $(obj).closest('li').show();
                                    }

                                });
                            }catch (e) {

                            }
                        }

                    }
                    assign_label(updateObject);
                }
            }
        });








        $(document).on("click",".close_confirm_box_div .counter_select_btn",function(e){
                $(".close_confirm_box_div .counter_select_btn").removeClass("active");
                $(".close_confirm_box_div .jconfirm-buttons button").removeClass("dialog_btn");
                var token  = $(this).attr('data-token');
                var ctoken  = $(this).attr('data-ctoken');
                if(token > 0) {
                    var skip_text = "Skip ("+token+") & Assign ("+ctoken+")";
                    var close_text = "Close ("+token+") & Assign ("+ctoken+")";

                    $(".close_token_btn").text(close_text);
                    $(".skip_token_btn").text(skip_text);

                    $(".close_token_btn, .skip_token_btn").show();
                    $(".assign_token_btn ").hide();



                }else{
                    $(".close_token_btn, .skip_token_btn").hide();
                    $(".assign_token_btn").show();
                }


                $(this).addClass("active");
        });






        function showLoadingIcon(flag){
            if(flag==true){
                $("#loaderBox").show();
            }else{
                $("#loaderBox").hide();
            }

        }



        $(document).on("click",".play_tracker_voice",function(e){

            var $btn =  $(this);
            var text =  $(this).text();
            var token =  $(this).attr('data-token');
            var doctor_name =  $( "#select_doctor_drp option:selected" ).text();
            var doctor_id =  $( "#select_doctor_drp" ).val();
            var display_token = $(".display_div").text();


            var data = {
                doctor_name:doctor_name,
                token:token,
                doctor_id:doctor_id,
                t:$("#t_app").val()
            };
            if(display_token!="OPEN"){
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
            }


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


        $(document).on("click",".tokenListBtn",function(e){
            var di = btoa($(this).closest('.iot_box').attr('id'));
            var ti = "<?php echo base64_encode($thin_app_id); ?>";
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/iot_token_list_modal',true); ?>",
                data:{di:di,ti:ti},
                beforeSend:function(){
                    showLoadingIcon(true);
                },
                success:function(response){
                    $(response).filter("#tokenListModal").modal('show');
                    showLoadingIcon(false);
                },
                error: function(data){
                    $.alert('Something, went wrong on server');
                    showLoadingIcon(false);
                }
            });
        });




        var setTokenBeforeClose = "<?php echo $setTokenBeforeClose; ?>";
        $(document).on("click",".iot_button",function(e){

            var token_number = $(this).closest(".counter_tr").find(".counter_token_number").text();
            var doctor_id = $(this).closest(".counter_tr").attr('data-id');

            var buttonType = $(this).attr('data-type');
            var status = $(this).attr('data-status');
            var service = "IoT_current_token";
            if(buttonType=='previous'){
                service = "IoT_previous_token";
                iot_action(service,status);
            }else if(buttonType=='next'){
                service = "IoT_next_token";
                if(setTokenBeforeClose=="YES"){
                    var dialog = $.confirm({
                        title: 'Set Next Token',
                        content: "<label>Enter Next Token</label><input autocomplete='off' type='number' class='form-control' name='current_token_input' id='current_token_input'>",
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
                                        iot_action(service,status,token);
                                        dialog.close();
                                    }
                                    return false;
                                }
                            },
                            cancel: function () {
                                //close
                            },
                        }

                    });
                }else{
                    iot_action(service,status,0);
                }
            }else if(buttonType=='play'){
                service = "IoT_play_current_token";
                if($(".display_div").text()!='OPEN'){
                    iot_action(service,status,token_number,doctor_id);
                }

            }



        });


        function getDoctorIds(box_id){
            var tmpArray = [];
            $("#"+box_id).find(".counter_tr").each(function (){
                tmpArray.push($(this).attr('data-id'));
            })
            return tmpArray.join(",");
        }


        function assign_label(object){

            if( $(object).find('.button_slider li:visible').length > 0){
                $(object).find(".assign_token_heading").html("Assign Token");
            }else{
                $(object).find(".assign_token_heading").html("No token booked yet");
            }


        }

        function iot_action(service,status,token_number,doctor_id){
            if(service!=""){

                var masterbox = doctor_id;

                var assoc_doc_id = $("#counter_number_"+doctor_id).closest(".iot_box").attr('id');
                var doctor_ids = getDoctorIds(doctor_id);
                if(assoc_doc_id){
                     doctor_ids = getDoctorIds(assoc_doc_id);
                     masterbox=assoc_doc_id;
                }
                var object = $("#"+masterbox);


                var data = JSON.stringify({'doctor_ids':doctor_ids,'token':token_number,'thin_app_id':thin_app_id,'doctor_id':doctor_id,'status':status,"user_id":login_id});
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/services/',true); ?>"+service,
                    data:data,
                    beforeSend:function(){
                        $(object).find(".loading_text").html("<img src='"+loaderImage+"' />");
                    },
                    success:function(response){
                       $(object).find(".loading_text").html("");
                        response = JSON.parse(response);
                        $(object).find(".display_div").html(response.current_token);
                        if(token_number==0){
                            $(object).find(".token_"+response.current_token).addClass("active");
                            if(response.activeTokens!=""){

                                try{
                                    var activeTokenArray = JSON.parse(response.activeTokens);
                                    $.each(activeTokenArray,function(index,value){
                                        $(object).find(".button_slider").find(".token_"+value.tNumber).addClass("active_token");
                                        var obj = $(object).find(".button_slider").find(".token_"+value.tNumber).find(".counterNameLbl");
                                        obj.html(value.dName);
                                        obj.css('display','block');
                                    });


                                }catch (e) {

                                }
                            }
                        }
                        setTimeout(function(){
                            var token_visible = $(object).attr('data-wise');
                            if(token_visible=="NON_BOOKED"){
                                $(object).find(".button_slider").find("li").css('display','block');
                                assign_label(object);
                            }
                        },50);
                    },
                    error: function(data){
                        $(object).find(".loading_text").html("");
                    }
                });
            }
        }

        setTimeout(function(){
            $(".iot_box").each(function (){
                iot_action("IoT_current_token","",0,$(this).attr('id'));
            })
        },50);




        function getNextTokenObject(status){

            var recordFound = false;
            var yourActiveToken = $(".button_slider .active").text();
            $(".button_slider li:visible").each(function(index,value){
                var obj = $(this).find("span")
                var token = $(obj).text().trim();

                if(!$(obj).hasClass("active_token") && parseInt(yourActiveToken) < parseInt(token) ){
                    updateToken(null,token,status,$(this));
                    recordFound = true;
                    return false;
                }
            });

            if(!recordFound){
                /* if token sequence is finish than start from first*/
                $(".button_slider li:visible").each(function(index,value){
                    var obj = $(this).find("span")
                    var token = $(obj).text().trim();
                    updateToken(null,token,status,$(this));
                    return false;
                })
            }
        }


        $(document).on("click",".auto_close_button, .auto_skip_button",function(e){
            var status = "no";
            if($(this).attr('data-type')=='CLOSE'){
                status = "yes";
            }
            getNextTokenObject(status);

        });


        function singleTokenAction(currentObj,token,counter_id){
            var boxObject = $(currentObj).closest(".iot_box");
            var ass_doc_id = $(boxObject).attr('id');
            var jc = $.confirm({
                title: "Confirm",
                columnClass:'box_container close_confirm_box_div',
                content: "Please select an option for Token No <b style='font-size: 1.5rem;color:red;' >"+ token+"</b>",
                html:true,
                buttons: {
                    next:{
                        text: 'Skip this token',
                        btnClass: 'btn-info',
                        action: function (e) {
                            updateToken(jc,"","no",currentObj,counter_id,ass_doc_id);
                            return false;
                        }
                    },
                    confirm:{
                        text: 'Close this Token',
                        btnClass: 'btn-success',
                        action: function (e) {
                            updateToken(jc,"","yes",currentObj,counter_id,ass_doc_id);
                            return false;
                        }
                    },
                    cancel: {
                        btnClass: 'btn-red confirm_cancel_btn',
                        text: 'X',
                        action: function () {
                            jc.close();
                        }
                    }
                }
            });
        }

        $(document).on("click",".single_counter_action",function(e){
            var token = $(this).closest(".counter_tr").find(".counter_token_number").text();
            var counter_id = $(this).closest(".counter_tr").attr('data-id');
            if(token!=""){
                token = parseInt(token);
            }
               singleTokenAction($(this),token,counter_id);
        })


        $(document).on("click",".button_slider li",function(e){
            var currentObj = $(this);
            var boxObject = $(this).closest(".iot_box");
            var boxName = $(boxObject).find(".doctor_name_lbl").text();
            var ass_doc_id = $(boxObject).attr('id');
            var token = $(currentObj).find('span').text().trim();
            var currentToken = $(boxObject).find(".button_slider .active").text();
            if($(currentObj).find('span').hasClass("active_token")){
                var counter_id = $(currentObj).find('.counterNameLbl').attr('data-id');
                singleTokenAction(currentObj,token,counter_id);
            }else{
                var counterString = "";
                $(boxObject).find(".counter_tr").each(function (index,val){
                    var counter_id =$(this).attr('data-id');
                    var counter_name =$(this).find(".counter_name").text().trim();
                    var active_room_token_num =$(this).find(".counter_token_number").text().trim();
                    if(active_room_token_num==""){
                        active_room_token_num = 0;
                    }

                    var token_lbl = (active_room_token_num != 0)?"<span class='token_num_lbl'>"+active_room_token_num+"</span>":"";
                    counterString += "<li class='counter_select_btn' data-ctoken='"+token+"'  data-token='"+active_room_token_num+"' data-id='"+counter_id+"'>"+token_lbl+"<label class='counter_name_lbl'>"+counter_name+"</label></li>";
                });

                counterString = "<h5 style='font-weight: 400 !important;'>Assign room for token number <b style='color: green;font-weight: 600;'>"+token+"</b> </h5><ul>"+counterString+"</ul>";
                counterString += "<div style='display: none;' class='prev_token_message'></div>";


                var jc = $.confirm({
                    title: boxName,
                    columnClass:'box_container close_confirm_box_div',
                    content: counterString,
                    html:true,
                    buttons: {
                        next:{
                            text: 'Skip & Assign Token',
                            btnClass: 'btn-info dialog_btn skip_token_btn',
                            action: function (e) {

                                if($(".counter_select_btn.active").length==1){
                                    var doctor_id = $(".counter_select_btn.active").attr('data-id');
                                    token = $(".counter_select_btn.active").attr('data-ctoken');
                                    updateToken(jc,token,"no",currentObj,doctor_id,ass_doc_id);
                                }else{
                                    $.alert("Please select room / counter");
                                }

                                return false;
                            }
                        },
                        confirm:{
                            text: 'Close & Assign Token',
                            btnClass: 'btn-success dialog_btn close_token_btn',
                            action: function (e) {
                                if($(".counter_select_btn.active").length==1){
                                    var doctor_id = $(".counter_select_btn.active").attr('data-id');
                                    token = $(".counter_select_btn.active").attr('data-ctoken');
                                    updateToken(jc,token,"yes",currentObj,doctor_id,ass_doc_id);
                                }else{
                                    $.alert("Please select room / counter");
                                }
                                return false;
                            }
                        },
                        ok:{
                            text: 'Assign Token',
                            btnClass: 'btn-info dialog_btn assign_token_btn',
                            action: function (e) {
                                if($(".counter_select_btn.active").length==1){
                                    var doctor_id = $(".counter_select_btn.active").attr('data-id');
                                    token = $(".counter_select_btn.active").attr('data-ctoken');
                                    updateToken(jc,token,"no",currentObj,doctor_id,ass_doc_id);
                                }else{
                                    $.alert("Please select room / counter");
                                }

                                return false;
                            }
                        },
                        cancel: {
                            btnClass: 'btn-red confirm_cancel_btn',
                            text: 'X',
                            action: function () {
                                jc.close();
                            }
                        }
                    }
                });

            }

        });





        function getCounterTypeSelectedDoctor(){
            var counterType = "";
            $("#select_tud_doctor_drp option").each(function(index,object){
                if($(object).attr('value') == $( "#select_tud_doctor_drp" ).val()){
                    counterType =  $(object).attr('counter-type');
                }
            });
            return counterType;
        }

        function updateToken(jc,token,flag,currentObj,doctor_id,ass_doc_id){
            var doctor_ids = getDoctorIds(ass_doc_id);
            var di = btoa(doctor_id);
            var ti = $("#t_app").val();
            var object = $("#"+ass_doc_id);
            $.ajax({
                type: 'POST',
                url: "<?php echo Router::url('/homes/setCurrentToken',true); ?>",
                data: {doctor_ids:doctor_ids,user_id:login_id,di:di,ti:ti,token:token,close_active_token:flag},
                beforeSend: function () {

                    $(object).find(".loading_text").html("<img src='"+loaderImage+"' />");

                },
                success: function (response) {

                    $(object).find(".loading_text").html("");

                    response = JSON.parse(response);
                    $("#counter_number_"+doctor_id).html(response.current_token);
                    /* chenge selected box color */
                    //$(".button_slider li span").removeClass('active');
                    /* chenge selected box color */
                    if(jc !==null){
                        jc.close();
                    }

                },
                error: function (data) {
                    if(jc !==null){
                        jc.close();
                    }
                }
            });

        }



        function getSelectedDoctorId(){
            if($("#select_tud_doctor_drp").length ==1){
                return  $("#select_tud_doctor_drp").val();
            }
            return staff_member_id;
        }


        $(document).on("click",".copyText",function(){

            var text = $(this).attr('data-copy');
            navigator.clipboard.writeText(text);
            $("#show_copy_text").html(text+" copy successfully");
            $("#show_copy_text").show();
            setTimeout(function(){
                $("#show_copy_text").fadeOut(500);
            },400);

        });


        $(document).on('hidden.bs.modal',"#tokenListModal", function () {
            $(this).remove();
        });


        $(document).on("click",".call_patient",function () {
            var closestTd = $(this).closest('td');
            var mobile= $(this).attr('data-m');
            var doctor_id= $(this).attr('data-d');
            var thin_app_id= $(this).attr('data-t');
            var ai= $(this).attr('data-ai');


            $btn = $(this);
            $last_text = $(this).text();
            $.ajax({
                url: "<?php echo Router::url('/homes/callToPatient', true); ?>",
                data: {appointment_id:ai, thin_app_id:thin_app_id,doctor_id:doctor_id,mobile: mobile},
                type: 'POST',
                beforeSend:function(){
                    $btn.button('loading').html('Sending...');
                },
                success: function (result) {
                    $btn.button('reset').html($last_text);
                    $(closestTd).html("Calling...");
                    alert('Call Send Successfully');
                },
                error:function () {
                    $btn.button('reset').html($last_text);
                }
            });
        });




        if($(".auto_skip_button").length==0){
            $(".iot_button").addClass("singleButonCss");
        }
        $(".iot_button").show();


        function setSliderWidth(){
            $(".iot_div table").each(function (){
                $(this).find(".button_slider").width($(this).width());
            })
        }
        window.addEventListener('resize', function() {

            setSliderWidth();

            if (window.innerWidth < 600) {
                $(".iot_div button").addClass("full_width_btn");
            }


        });
        setSliderWidth();
    });




</script>
</html>


