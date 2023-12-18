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
    <?php if($iot_screen=='iot'){ ?>
        <link rel="manifest" href="<?php echo Router::url('/add_home_screen/tud/'.$staff_member_id.'/manifest.json?'.date('his'),true);?>" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-72x72.png" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-96x96.png" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-128x128.png" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-144x144.png" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-152x152.png" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-192x192.png" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-384x384.png" />
        <link rel="apple-touch-icon" href="https://mpasscheckin.com/doctor/add_home_screen/doctorapps/tud_icons/icon-512x512.png" />

    <?php }else{ ?>
        <link rel="manifest" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/manifest.json?'.date('his'),true);?>" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-72x72.png" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-96x96.png" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-128x128.png" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-144x144.png" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-152x152.png" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-192x192.png" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-384x384.png" />
        <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/moq/'.$staff_member_id.'/',true);?>icons/icon-512x512.png" />
    <?php } ?>



    <meta name="apple-mobile-web-app-status-bar" content="#5D54FF" />
    <meta name="theme-color" content="#5D54FF" />

    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','jquery-confirm.min.js','moment.js','moment.js','bootstrap-datepicker.min.js','firebase-app.js','firebase-messaging.js','wickedpicker.min.js','ckeditor/ckeditor.js','moment.min.js','daterangepicker.js','flash.js?5555')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css','bootstrap-datepicker.min.css','wickedpicker.min.css','daterangepicker.css' ),array("media"=>'all')); ?>


    <script src="<?php echo Router::url('/app.js?20230717',true); ?>"></script>
    <style>



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


        .button_slider{
            display: flex;
            padding: 0;
            overflow-x: scroll;
            margin: 0;
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

        .half_button{
            height: 3.2rem !important;
            padding: 0 !important;
            font-size: 1.2rem !important;
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



<div id="loaderBox" style="display: none;">
    <div id="loaderIcon"></div>
</div>


<?php if(!empty($app_data)){ ?>
    <header>
        <table style="width: 100%" class="header_table">
            <tr>
                <td style="width: 10%">
                    <?php $image_id =""; if($iot_screen=='no'){ $image_id ="logo_image";  }  ?>
                    <img id="<?php echo $image_id; ?>" src="<?php echo $app_data['logo']; ?>" alt="Logo Image" />
                </td>
                <td>
                    <h3 style="text-align: center;width: 100%;">
                        <?php if($iot_screen=='no'){  ?>
                            Daily Token
                        <?php }else{  ?>
                            <?php echo $app_data['name']; ?>
                        <?php }  ?>
                    </h3>
                </td>
                <td style="width: 5%">
                    <div id="nav_menu" style="display: none;" class="dropleft show ">
                        <a class="btn btn-secondary" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <?php if($iot_screen=='no'){ $type = ($loginUserRole=='ADMIN')?'app':'doctor'; ?>
                                <a data-id="<?php echo base64_encode($staff_member_id); ?>" data-type="<?php echo $type; ?>" class="dropdown-item load_setting" href="javascript:void(0)">
                                    <i class="fa fa-cogs" aria-hidden="true"></i> Setting
                                </a>

                                <a class="dropdown-item load_setting" data-type="doctor"  data-id="<?php echo base64_encode($staff_member_id); ?>" href="javascript:void(0)">
                                    <i class="fa fa-cogs"></i> Appointment Setting
                                </a>


                                <a class="dropdown-item" id="report_btn" href="javascript:void(0)">
                                    <i class="fa fa-line-chart"></i> Report
                                </a>

                                <?php if($loginUserRole=='ADMIN'){ ?>
                                    <a class="dropdown-item"  href="<?php echo Router::url('/homes/mq_form/',true).$thin_app_id.'/'.base64_encode($staff_member_id)."/iot";?>" >
                                        <i class="fa fa-gamepad"></i> Digital TUD
                                    </a>
                                <?php } ?>


                            <?php }else{  ?>
                                <?php if($loginUserRole=='ADMIN'){ ?>
                                    <a class="dropdown-item"  href="<?php echo Router::url('/homes/mq_form/',true).$thin_app_id.'/'.base64_encode($staff_member_id);?>" >
                                        <i class="fa fa-dashboard"></i> DTI
                                    </a>
                                <?php } ?>
                            <?php }  ?>
                            
                            <a data-id="<?php echo base64_encode($staff_member_id); ?>" data-type="doctor" class="dropdown-item load_setting" href="javascript:void(0)">
                                    <i class="fa fa-cogs" aria-hidden="true"></i> Setting
                                </a>

                            <?php
                            $logoutUrl = Router::url('/homes/dti_logout/',true).$thin_app_id.'/'.base64_encode($login_id) ;
                            ?>
                            <a class="dropdown-item" href="<?php echo $logoutUrl; ?>">
                                <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                            </a>


                        </div>
                    </div>
                </td>
            </tr>

        </table>
        <?php if($iot_screen!='iot'){  ?>
            <table id="bottom_option" style="width:100%; border-top: 2px solid;">
                <tr>
                    <?php if($loginUserRole=='ADMIN'){ ?>
                        <td id="dashboard_tab"><i class="fa fa-dashboard"></i>Dashboard</td>
                    <?php } ?>

                    <td id="book_token_tab" class="activeTab"><i class="fa fa-ticket"></i>Book Token</td>
                    <td id="token_list_tab"><i class="fa fa-list-ol"></i>Token List</td>
                </tr>
            </table>
        <?php }  ?>

    </header>
<?php } ?>

<div class="container-fluid" style="padding-left: 5px; padding-right: 5px;">
    <?php if($settingConfigure){ ?>
    <?php if($iot_screen=='iot'){  ?>
        <div class="iot_box">
            <input type="hidden" id="t_app" value="<?php echo ($thin_app_id); ?>">
            <div class="row section_box">
                <div class="col-12">
                    <div class="row doctor_selection">
                        <div class="col-<?php echo ($is_custom_app=="YES")?'10':'12'; ?>">
                            <?php if($loginUserRole=="RECEPTIONIST"){ ?>
                                <select style="width: 100% !important;" id="select_tud_doctor_drp">
                                    <?php foreach ($data as $key => $list) { ?>
                                        <option token_visible="<?php echo $list['token_visible']; ?>" associate_counter="<?php echo $list['associate_counter_id']; ?>"   counter-type="<?php echo $list['counter_booking_type']; ?>" value="<?php echo $list['doctor_id']; ?>"><?php echo $list['doctor_name']; ?></option>
                                    <?php } ?>
                                </select>
                            <?php }else{ ?>
                                <h3 class="doctor_name_id"  data-id="<?php echo $doctorData['id']; ?>" style="text-align: center;width: 100%;"> <?php echo $doctorData['name']; ?></h3>
                                <select style="display:none;" id="select_tud_doctor_drp">
                                    <option token_visible="<?php echo $doctorData['show_token_list_on_digitl_tud']; ?>" associate_counter="<?php echo $doctorData['show_token_into_digital_tud']; ?>" counter-type="<?php echo $doctorData['counter_booking_type']; ?>" value="<?php echo $doctorData['id']; ?>"><?php echo $doctorData['name']; ?></option>
                                </select>

                            <?php } ?>
                        </div>

                        <?php if($is_custom_app=="YES"){ ?>
                            <div class="col-2">
                                <button type="button" id="tokenListBtn" class="btn btn-success"><i class="fa fa-list"></i> </button>
                            </div>
                        <?php } ?>








                    </div>
                </div>

            </div>

            <div class="row section_box" style="padding: 0;margin: 0;">
                <h3 class="display_div">

                </h3>

            </div>
            <div id="token_booking_sec" class="row section_box">
                <div class="col-12">
                    <div class="doctor_selection">
                        <div class="iot_div" style="width: 100%;float: left;display: block; text-align: center;">

                            <table style="width: 100%; float: left;display: block;">
                                <?php if($is_custom_app=="YES"){ ?>




                                    <tr class="announce_tracker_btn">
                                        <td style="width: 1%;">

                                            <?php if($thin_app_id==base64_encode(907)){ ?>
                                                <button  style="float:left;height: 3rem;width: 32%;margin-bottom: 1rem; padding: 0;" class="auto_skip_button" data-type="SKIP" ><i class="fa fa-forward"></i> Skip Token</button>
                                            <?php } ?>

                                            <button  style="background-color:green;float:left;margin:0 10px; height: 3rem;width: 33%;margin-bottom: 1rem; padding: 0;" class="iot_button" data-type="play" data-status="PLAY"><i class="fa fa-play"></i> Play Token</button>

                                            <?php if($thin_app_id==base64_encode(907)){ ?>
                                                <button  style="float:left;height: 3rem;width: 32%;margin-bottom: 1rem; padding: 0;" class="auto_close_button" data-type="CLOSE" ><i class="fa fa-power-off"></i> Close Token</button>
                                            <?php } ?>


                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5 style="margin-top:15px;text-align: center;width: 100%;">Set Current Token</h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td >
                                            <ul class="button_slider" style="display: none;">
                                                <?php for($counter=1;$counter<=400;$counter++){ ?>
                                                    <li style="display: none;" id="token_<?php echo $counter; ?>" >
                                                        <span class="token_<?php echo $counter; ?>"  ><?php echo $counter; ?></span>
                                                        <label class="counterNameLbl"></label>
                                                    </li>
                                                <?php } ?>
                                            </ul>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="indication_label">
                                            <li><div style="background: red;"></div><label>On Other Counter</label></li>

                                            <?php if($thin_app_id==base64_encode(EHCC_APP_ID)){ ?>
                                                <li><div style="background: yellow;"></div><label>Billing Under Process</label></li>
                                            <?php } ?>
                                            <li><div style="background: green;"></div><label>Your Current Token</label></li>
                                        </td>
                                    </tr>
                                <?php }else{ ?>
                                    <tr>
                                        <td  style="width: 20%;">
                                            <button class="iot_button" data-type="previous"  data-status="PREVIOUS"><i class="fa fa-arrow-left"></i></button>
                                            <span>Previous</span>
                                        </td>
                                        <td>
                                            <button class="iot_button" data-type="play" data-status="PLAY"><i class="fa fa-play"></i></button>
                                            <span>Play</span>
                                        </td>
                                        <td  style="width: 30%;">

                                            <button class="iot_button half_button" style="margin-bottom: 9px;" data-type="next" data-status="SKIPPED">Skip</button>
                                            <br>
                                            <button class="iot_button half_button"  data-type="next" data-status="CLOSED" >Close</button>
                                            <span>&nbsp;</span>


                                        </td>
                                    </tr>
                                <?php } ?>


                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <?php if($thin_app_id==base64_encode(EHCC_APP_ID)){ ?>

                <div id="billing_token_sec" class="row section_box">
                    <div class="col-12">
                        <h5 style="text-align: center;width: 100%;">Tokens Under Billing Counter</h5>
                        <p id="tokenStringContainer"></p>
                    </div>

                </div>

            <?php } ?>
            
             <?php if($counter_type=="BILLING" && count($send_to_counter) > 0){ ?>
                <select id="send_to_counter" style="display: none;">
                    <?php foreach($send_to_counter as $key => $val){ ?>
                        <option value="<?php echo $val['id'];?>"><?php echo $val['name']; ?></option>
                    <?php } ?>
                </select>
            <?php } ?>


        </div>
    <?php }else{  ?>
    <div class="main_box">
        <input type="hidden" id="t_app" value="<?php echo ($thin_app_id); ?>">
        <?php if(!empty($data)){ ?>



        <div id="dashboard_chart" class="row section_box" style="display: none;">
            <h6 style="text-align: center;width: 100%;display: block;">Showing chart for </h6>


            <div id="chart_dashbaord_date" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 275px; margin: 0 auto;">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span> <i class="fa fa-caret-down"></i>
            </div>



            <div id="append_chart" class="row" style="margin-left: 0;margin-right: 0;"></div>

        </div>

        <div id="token_booking_sec" class="row section_box" style="display: none;">

            <div class="col-12">
                <div class="doctor_selection">

                    <label class="inner_label">Tab on name to select doctor</label>
                    <table style="width: 100%;padding: 0;">
                        <?php foreach ($data as $key => $doctor){ ?>
                            <tr data-name="<?php echo $doctor['doctor_name']; ?>" class="container_element <?php echo ($key==0)?'selected_label':''; ?>">
                                <td style="width: 85%;">
                                    <label>
                                        <img class="checkmark" src="<?php echo !empty($doctor['profile_photo'])?$doctor['profile_photo']:$doctor['logo']; ?>">
                                        <span><?php echo $doctor['doctor_name']; ?></span>
                                        <input data-dur="<?php echo $doctor['service_slot_duration']; ?>" data-ai="<?php echo base64_encode($doctor['address_id']); ?>" data-id="<?php echo $doctor['doctor_id']; ?>" type="radio" class="doctor_input" name="doctor_name" value="<?php echo $doctor['mobile']; ?>" <?php echo ($key==0)?'checked="checked"':''; ?>  >

                                    </label>



                                </td>
                                <td class="doctorSettingIconTd" style="width: 15%; text-align: center;" valign="middle">
                                    <i class="fa fa-cogs load_setting" data-type="doctor"  data-id="<?php echo base64_encode($doctor['doctor_id']); ?>"></i>
                                </td>
                            </tr>

                        <?php } ?>

                    </table>
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
                    <button type="button" class="btn btn-xs button btn-success" style="display: none;" id="select_doctor"><i class="fa fa-list-ul" aria-hidden="true"></i> Doctor List</button>
                <?php } ?>
                <button type="button" class="btn btn-xs button btn-success"  style="display: none;"  id="token_list_btn"><i class="fa fa-th-list" aria-hidden="true"></i> Token List</button>

            </div>
        </div>
        <div id="token_list_sec" style="display: none;margin-left: 0;margin-right: 0;" class="row section_box">

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


                <button style="" class="button btn btn-xs" id="update_next_token"><i class="fa fa-desktop" aria-hidden="true"></i> Next Token</button>
                <button style="" class="button btn btn-xs" id="close_token_btn"><i class="fa fa-power-off" aria-hidden="true"></i> Close Token</button>

            </div>
            <div class="col-12" id="list_append">

            </div>
        </div>


    </div>
<?php }else{ ?>
    <h2> Invalid Request </h2>
<?php } ?>
</div>
<?php }  ?>
<?php }else{ ?>
    <div class="message_box">
        <h4>Token Setting Not Configured</h4>
        <p>The token setting for selected Doctor or Counter is not configured.Please contact to admin to configure the setting. </p>
        <a style="margin: 0 auto; display: block;color: #fff;text-decoration: none;" href="<?php echo Router::url('/dti/',true).$app_data['slug'];?>" class="button" type="button">Back to login</a>
    </div>
<?php } ?>
<button style="display:none;" id="btnSave"><img  src="<?php echo $app_data['logo']; ?>" alt="image"> Add to homescreen</button>
</div>
<div class="modal fade" id="settingModal"></div>




</body>


    <script type="text/javascript" src="<?php echo TOKEN_SOCKET_URL.'/socket/socket.io.js'; ?>"></script>



<script>
    <?php if($settingConfigure){ ?>
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

        var staff_member_id ="<?php echo $staff_member_id; ?>";
        var login_doctor ="<?php echo $login_doctor; ?>";
        var patient_booking_form ="<?php echo $patient_booking_form; ?>";
        var thin_app_id ="<?php echo base64_decode($thin_app_id); ?>";
        var ehcc_app_id ="<?php echo EHCC_APP_ID; ?>";
        var is_custom_app ="<?php echo $is_custom_app; ?>";

        var staff_id = "<?php echo $staff_id; ?>";
        var display = "<?php echo @$display; ?>";
        var iot_screen = "<?php echo $iot_screen; ?>";
        var loaderImage = "<?php echo Router::url('/img/iot_display_loader.gif',true)?>";
        if(iot_screen=='iot'){
            var socetUrl =  "<?php echo TOKEN_SOCKET_URL; ?>";
            socket = io.connect( socetUrl );
            socket.on( 'updateToken', function( data ) {

                if(data.thin_app_id==thin_app_id){

					
                    if(data.doctor_id == login_doctor && data.reload==true && getCookie('_dti')!=data.dti_token ){
                        window.location.href =login_url;
                    }
                    if(getSelectedDoctorId()==data.doctor_id){
                        $(".display_div").html(data.token);
                    }
                    var token_visible = $('option:selected', $("#select_tud_doctor_drp")).attr('token_visible');
                    var associate_counter = $('option:selected', $("#select_tud_doctor_drp")).attr('associate_counter');
                    if(is_custom_app=="YES" && data.associate_counter_id==associate_counter){

                        console.log('Token Update');
                        $("#tokenStringContainer").html(data.billingTokenString);
                        $(".button_slider li span").removeClass("billing");
                        $(".button_slider li span").removeClass("active_token");
                        $(".button_slider li .counterNameLbl").html("");
                        $(".button_slider li .counterNameLbl").hide();

                        var tokenArray = data.billingTokenString.split(",");
                        if(tokenArray.length > 0){
                            $.each(tokenArray,function(index,value){
                                value = value.trim();
                                $(".button_slider").find(".token_"+value).addClass("billing");
                            });
                        }

                        var bookedTokenArray = data.bookedTokenString.split(",");
                        if(token_visible=="NON_BOOKED"){
                            $(".button_slider li").show();
                        }else{
                            $(".button_slider li").hide();
                        }


                        if(bookedTokenArray.length > 0){


                            if(token_visible=="NON_BOOKED"){
                                $(".button_slider li").show();
                            }else{
                                $(".button_slider li").hide();
                            }

                            $.each(bookedTokenArray,function(index,value){
                                $(".button_slider").find("#token_"+value).show();
                            });

                            if(data.activeTokens!=""){
                                try{
                                    var activeTokenArray = JSON.parse(data.activeTokens);

                                    $.each(activeTokenArray,function(index,value){
                                        if(value.tNumber){
                                            $(".button_slider").find(".token_"+value.tNumber.trim()).addClass("active_token");
                                            var obj = $(".button_slider").find("#token_"+value.tNumber.trim()).find(".counterNameLbl");
                                            $(obj).html(value.dName);
                                            $(obj).css('display','block');
                                            $(obj).closest('li').show();
                                        }

                                    });
                                }catch (e) {

                                }
                            }

                        }else{
                            var html ="<lable style='width:100%;text-align:center;'>No token booked yet</label>";
                            $(".button_slider").html(html);
                        }
                    }
                }
            });
        }else{
            var socetUrl =  "<?php echo TOKEN_SOCKET_URL; ?>";
            socket = io.connect( socetUrl );
            socket.on( 'updateToken', function( data ) {
                if(data.thin_app_id==thin_app_id){
                  
                  
                  	if(data.doctor_id == login_doctor && data.reload==true && getCookie('_dti')!=data.dti_token ){
                    	if(data.thin_app_id != 892){
                       		 window.location.href =login_url;
                        }
                        
                    }
                  
                    if($("#select_doctor_drp").length == 1){
                    
                        if(data.doctor_id ==  $("#select_doctor_drp").val()){
                            $(".current_token_number_td").html(data.token);
                            if(data.patient_name && data.patient_name!=""){
                                $(".current_patient_name_td").html(data.patient_name);
                            }else{
                          		  $(".current_patient_name_td").html("-");
                            }
                        }
                    }
                }
            });
        }

        function showLoadingIcon(flag){
            if(flag==true){
                $("#loaderBox").show();
            }else{
                $("#loaderBox").hide();
            }

        }

        $(document).on("click","#tokenListBtn",function(e){
            var di = btoa($("#select_tud_doctor_drp").val());
            var ti = "<?php echo $thin_app_id; ?>";
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




        $(document).on("click",".load_setting",function(e){
            var di = $(this).attr('data-id');
            var type = $(this).attr('data-type');
            $.ajax({
                type:'POST',
                url: "<?php echo Router::url('/homes/mq_setting',true); ?>",
                data:{di:di,type:type},
                beforeSend:function(){
                    showLoadingIcon(true);
                },
                success:function(response){
                    $("#settingModal").html(response);
                    $("#settingModal").modal('show');
                    setTimeout(function(){
                        $('#blockDate').datepicker({
                            setDate: new Date(),
                            autoclose: true,
                            format: 'dd/mm/yyyy'
                        });
                    },1000);

                    showLoadingIcon(false);
                },
                error: function(data){
                    $.alert('Something, went wrong on server');
                    showLoadingIcon(false);
                }
            });
        });

        $(document).on("click","#logo_image",function(e){
            if(!isBookingForm()){
                showHideScreen('TOKEN_BOOKING');


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
            $(".doctor_input:first").trigger('click');
        }

        $(document).on("click","#select_doctor, #logo_image",function(e){

            if(!isBookingForm()) {
                $(".booking_form, #book_appointment, #select_doctor, #book_appointment_other, #token_list_sec").hide();
                if (total_doctor == 1) {
                    $(".doctor_input:checked").trigger('click');
                } else {
                    $(".doctor_selection").show();

                }

            }
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
                            if(!isBookingForm()){
                                if(confirm(response.message)){
                                    $("#select_doctor_drp").val($('.doctor_input:checked').attr('data-id'));
                                    $("#token_list_btn").trigger("click");
                                }
                            }else{
                                $(".message_lbl").html(response.message);
                                setTimeout(function(){ $(".message_lbl").html(''); },3000);
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
            var di = "";
            if($("#select_doctor_drp").length > 0){
                var di = btoa($("#select_doctor_drp").val());
            }else{
                di = btoa($(".doctor_name_id").attr('data-id'));
            }


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




        $(document).on("click","#bottom_option td",function(e){
            if($(this).attr('id')=="dashboard_tab"){
                showHideScreen('DASHBOARD');
            }else if($(this).attr('id')=="book_token_tab"){
                showHideScreen('TOKEN_BOOKING');
            }else if($(this).attr('id')=="token_list_tab"){
                showHideScreen('TOKEN_LIST')
            }

        });

        function showHideScreen(type){
            if(type=='TOKEN_LIST' || type=='TOKEN_BOOKING' || type=='DASHBOARD'){
                $("#token_booking_sec, #token_list_sec, #dashboard_chart, #profile_sec, #token_list_sec").hide();
                $("#bottom_option td").removeClass('activeTab');
            }
            if(type=='TOKEN_LIST'){
                $("#token_list_sec").show();
                $("#token_list_tab").addClass('activeTab');
            }else if(type=='TOKEN_BOOKING'){
                $("#token_booking_sec").show();
                $("#book_token_tab").addClass('activeTab');
            }else if(type=='DASHBOARD'){
                $("#dashboard_chart").show();
                $("#dashboard_tab").addClass('activeTab');
            }
        }

        $(document).on("click","#token_list_btn",function(e){
            $("#select_doctor_drp").val($('.doctor_input:checked').attr('data-id'));
            showHideScreen("TOKEN_LIST");
            $("#select_doctor_drp").trigger("change");
        });

        $(document).on("click","#back_token_btn",function(e){
            var di = $("#select_doctor_drp").val();
            $(".doctor_input[data-id="+di+"]").attr("checked",true);
            $(".doctor_input[data-id="+di+"]").trigger("click");
            showHideScreen("TOKEN_BOOKING");

        });

        $(document).on("click","#refresh_token_btn",function(e){
            $("#token_list_btn").trigger("click");
        });






        function freezScreenButton(show_btn){
            if(show_btn==true){
                $("#book_appointment_other, #token_list_btn, #select_doctor, .header_table .dropleft").show();
                if (typeof(Storage) !== "undefined") {

                    if(!isBookingForm()){
                        localStorage.removeItem("freez");
                    }

                }
            }else{
                if (typeof(Storage) !== "undefined") {
                    var di = $('.doctor_input:checked').attr('data-id');
                    if(!isBookingForm()){
                        localStorage.setItem("freez", di);
                    }




                }
                $("#book_appointment_other, #token_list_btn, #select_doctor, .header_table .dropleft").hide();
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
            var htm_string = "<h6>From Date</h6><input autocomplete='off' onkeydown='return false' type='text'  id='from_date'><h6>To Date</h6><input  autocomplete='off' onkeydown='return false' type='text' id='to_date'><h6>Amount</h6><input autocomplete='off' type='text' id='amount'><h6>Report Type </h6><select id='report_type'><option value='appointment_list'>Appointment List</opton><option value='log_list'>Token Log Report</opton></select><br><iframe id='load_report_frame' style='margin-top:10px;display:none;width:100%;height: 350px;'></iframe>";


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
                            var report_type = $("#report_type").val();
                            if(from_date==''){
                                $("#from_date").focus();
                            }else if(to_date==''){
                                $("#to_date").focus();
                            }else{

                                $($btn).prop('disabled',true).text('Generationg..');
                                var ti ="<?php echo ($thin_app_id); ?>";
                                var fullUrl = "";
                                console.log(report_type);
                                if(report_type=='log_list'){
                                    var baseUrl = "<?php echo Router::url("/homes/create_counter_report/",true); ?>";
                                    fullUrl = baseUrl+ti+"/"+from_date+"/"+to_date;
                                }else{
                                    var baseUrl = "<?php echo Router::url("/homes/dti_report/",true); ?>";
                                    fullUrl = baseUrl+ti+"/"+from_date+"/"+to_date+"/"+amount;
                                }

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
                    setTimeout(function (){
                        $('#from_date, #to_date').datepicker({
                            "setDate": new Date(),
                            "autoclose": true,
                            "format": 'dd-mm-yyyy'
                        });
                    },100);
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


        function isBookingForm(){
            return (patient_booking_form=='y');
        }

        $("#token_booking_sec, #nav_menu").show();
        if(isBookingForm()){
            $("input[data-id="+staff_member_id+"]").trigger('click');
            freezScreenButton(false);
        }else{
            setInterval(function () {
                loadTokeList(false);
            },6000);
        }






        function checkCurrentDate(){
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            return  mm + '/' + dd + '/' + yyyy;
        }
        var currentDate = checkCurrentDate();
        setInterval(function () {

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
        var setTokenBeforeClose = "<?php echo $setTokenBeforeClose; ?>";
        $(document).on("click",".iot_button",function(e){
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
                    iot_action(service,status);
                }

            }



        });


        function iot_action(service,status,token_number){
            var user_id = "<?php echo $staff_member_id; ?>";
            if(service!=""){


                var doctor_ids = "<?php echo $doctor_ids; ?>";
                if(is_custom_app=="YES"){
                    doctor_ids = "<?php echo $ehcc_counter_ids; ?>";
                }

                var data = JSON.stringify({'doctor_ids':doctor_ids,'token':token_number,'thin_app_id':thin_app_id,'doctor_id':getSelectedDoctorId(),'status':status,"user_id":user_id});
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/services/',true); ?>"+service,
                    data:data,
                    beforeSend:function(){
                        $(".display_div").html("<img src='"+loaderImage+"' />");
                    },
                    success:function(response){
                        response = JSON.parse(response);
                        $(".display_div").html(response.current_token);
                        if(token_number==0){
                            $(".token_"+response.current_token).addClass("active");
                            if(response.activeTokens!=""){
                                try{
                                    var activeTokenArray = JSON.parse(response.activeTokens);
                                    $.each(activeTokenArray,function(index,value){
                                        $(".button_slider").find(".token_"+value.tNumber).addClass("active_token");
                                        var obj = $(".button_slider").find(".token_"+value.tNumber).find(".counterNameLbl");
                                        obj.html(value.dName);
                                        obj.css('display','block');
                                    });
                                }catch (e) {

                                }
                            }
                        }
                        setTimeout(function(){
                            var token_visible = $('option:selected', $("#select_tud_doctor_drp")).attr('token_visible');
                            if(token_visible=="NON_BOOKED"){
                                $(".button_slider").find("li").css('display','block');
                            }
                        },50);


                    },
                    error: function(data){

                    }
                });
            }
        }

        setTimeout(function(){
            iot_action("IoT_current_token","",0);
        },200);




        $(document).on("change","#select_tud_doctor_drp",function(e){
            manageAnnouncment();
            iot_action("IoT_current_token","");

        });






        function manageAnnouncment(){
            if(getCounterTypeSelectedDoctor() == "BILLING"){
                $(".announce_tracker_btn").hide();
            }else{
                $(".announce_tracker_btn").show();
            }
        }

        manageAnnouncment();





        function setButtonSliderWidth(){
            $(".button_slider").width($("#token_booking_sec .doctor_selection").width());
            $(".button_slider").show();
        }




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


       $(document).on("click",".button_slider li",function(e){
            var currentObj = $(this);
            var token = $(currentObj).find('span').text().trim();
            var currentToken = $(".button_slider .active:visible").text();
            if($("#send_to_counter").length==1){
                if(!$(currentObj).find('span').hasClass("active") && $(currentObj).find('span').hasClass("active_token")){
                    $.alert("The token is already being processed by another counter.");
                }else{
                    var clone = $("#send_to_counter").clone();
                    if(currentToken!=""){
                        var jc = $.confirm({
                            title: 'Assign Token',
                            columnClass:'box_container close_confirm_box_div',
                            content: "<h6>Assing token no <b style='color:red;font-size: 1.5rem;'>"+currentToken+"</b>to</h6><div id='append_select'></div>",
                            html:true,
                            buttons: {
                                next:{
                                    text: 'Skip Token',
                                    btnClass: 'btn-info',
                                    action: function (e) {
                                       assignToken(jc,token,"no",currentObj,0,"");
                                        return false;
                                    }
                                },
                                confirm:{
                                    text: 'Assign',
                                    btnClass: 'btn-info',
                                    action: function (e) {
                                        var assign_doc_id = $("#append_select #send_to_counter").val();
                                        var assign_remark = $("#append_select #assign_remark").val();
                                        assignToken(jc,token,"no",currentObj,assign_doc_id,assign_remark);
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
                            },
                            onContentReady: function () {
                                $("#append_select").html(clone);
                                var input_lbl ="<label style='display:block;width:100%'>Remark </label><input type='text' id='assign_remark' maxlength='30' />";
                                $("#append_select").append(input_lbl);
                                $("#append_select #send_to_counter").show();
                            },
                        });
                    }else{
                        updateToken(null,token,"",currentObj);
                    }
                }

            }else{
                if(!$(currentObj).find('span').hasClass("active") && $(currentObj).find('span').hasClass("active_token")){
                    $.alert("The token is already being processed by another counter.");
                }else{
                    if(getCounterTypeSelectedDoctor() != "BILLING"){
                        if(currentToken!=""){
                            var jc = $.confirm({
                                title: 'Confirm',
                                columnClass:'box_container close_confirm_box_div',
                                content: "<h6>What to do with active token <b style='color:red;font-size: 1.5rem;'>"+currentToken+"</b>?</h6>",
                                html:true,
                                buttons: {
                                    next:{
                                        text: 'Skip Token',
                                        btnClass: 'btn-info',
                                        action: function (e) {
                                            updateToken(jc,token,"no",currentObj);
                                            return false;
                                        }
                                    },
                                    confirm:{
                                        text: 'Close Token',
                                        btnClass: 'btn-success',
                                        action: function (e) {
                                            updateToken(jc,token,"yes",currentObj);
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
                        }else{
                            updateToken(null,token,"",currentObj);
                        }
                    }else{
                        updateToken(null,token,"",currentObj);
                    }
                }
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

        function updateToken(jc,token,flag,currentObj){
            var di = btoa($( "#select_tud_doctor_drp" ).val());
            var ti = $("#t_app").val();
            var user_id = "<?php echo $staff_member_id; ?>";
            var doctor_ids = "<?php echo $ehcc_counter_ids; ?>";
            $.ajax({
                type: 'POST',
                url: "<?php echo Router::url('/homes/setCurrentToken',true); ?>",
                data: {doctor_ids:doctor_ids,user_id:user_id,di:di,ti:ti,token:token,close_active_token:flag},
                beforeSend: function () {
                    $(".display_div").html("<img src='"+loaderImage+"' />");
                },
                success: function (response) {
                    response = JSON.parse(response);
                    $(".display_div").html(response.current_token);

                    /* chenge selected box color */
                    $(".button_slider li span").removeClass('active');
                    $(currentObj).find('span').addClass('active');
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
        
        
        function assignToken(jc,token,flag,currentObj,assignTokenTo,assign_remark){
            var di = btoa($( "#select_tud_doctor_drp" ).val());
            var ti = $("#t_app").val();
            var user_id = "<?php echo $staff_member_id; ?>";
            var doctor_ids = "<?php echo $ehcc_counter_ids; ?>";
            $.ajax({
                type: 'POST',
                url: "<?php echo Router::url('/homes/setCurrentToken',true); ?>",
                data: {assign_remark:assign_remark,assignTokenTo:assignTokenTo,doctor_ids:doctor_ids,user_id:user_id,di:di,ti:ti,token:token,close_active_token:flag},
                beforeSend: function () {
                    $(".display_div").html("<img src='"+loaderImage+"' />");
                },
                success: function (response) {
                    response = JSON.parse(response);
                 
                    var currentToken = $(".button_slider .active:visible").text();
                    $(".button_slider li span").removeClass('active');
                     if(token != currentToken){
                        $(currentObj).find('span').addClass('active');
                    }
                   
                   

                    $(".display_div").html(response.current_token);

                    /* chenge selected box color */

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

        $(window).resize(function(){
            setButtonSliderWidth()
        });
        setButtonSliderWidth();

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

        if($("#dashboard_tab").length == 1 ){
            function loadChartReport(report_type,start_date,end_date){
                var duration = $("#chart_dashbaord_drp").val();
                var ti = $("#t_app").val();
                $.ajax({
                    type:'POST',
                    url: "<?php echo Router::url('/homes/dashboard_chart',true); ?>",
                    data:{d:duration,ti:ti,rt:report_type,sd:start_date,ed:end_date},
                    beforeSend:function(){
                        showLoadingIcon(true);
                    },
                    success:function(response){
                        $("#append_chart").html(response);
                        showLoadingIcon(false);
                    },
                    error: function(data){
                        $.alert('Something, went wrong on server');
                        showLoadingIcon(false);
                    }
                });
            }
            var start = moment().subtract(6, 'days');
            var end = moment();
            function cb(start, end) {
                $('#chart_dashbaord_date span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
                loadChartReport("ALL",start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
            }

            $('#chart_dashbaord_date').daterangepicker({
                startDate: start,
                endDate: end,
                format:"DD-MM-YYYY",
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            var firstClicked = false;
            $(document).on("click","#dashboard_tab",function(){
                if(firstClicked==false){
                    firstClicked=true;
                    cb(start, end);
                }

            });



        }


        if($(".auto_skip_button").length==0){
            $(".iot_button").addClass("singleButonCss");
        }
        $(".iot_button").show();
        window.addEventListener('resize', function() {
            if (window.innerWidth < 600) {
                $(".iot_div button").addClass("full_width_btn");
            }


        });
        
        $(document).on("click",".send_review_link",function(){
        var btn = $(this);
        var di = $(this).attr('data-di');
            var un = $(this).attr('data-u');
            var m = $(this).attr('data-m');

           
            $.ajax({
                url: "<?php echo Router::url("/doctor/send_review_link/",true); ?>",
                data: {di:di,un:un,m:m},
                type:'POST',
                beforeSend:function () {
                    $(btn).button('loading').html('Sending...');
                },
                success: function(response){
                    response = JSON.parse(response);
                    $(btn).button('reset');
                    if(response.status==1){
                        $(btn).hide();
                        flashMessage('success',response.message);
                    }else{
                        flashMessage('error',response.message);
                    }
                },error:function () {
                    $(btn).button('reset');
                    flashMessage('error',"Sorry unable to submit review.");

                }
            });

    })
        

    });
    <?php } ?>
</script>
</html>


