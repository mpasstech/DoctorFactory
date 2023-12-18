
<html>
<head id ="row_content" class="row_content" >
    <title>Token Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="apple-mobile-web-app-status-bar" content="#5D54FF" />
    <meta name="theme-color" content="#5D54FF" />
    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';
    </script>
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','es6-promise.auto.min.js','jquery-confirm.min.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','doctor.css?'.date('his'),'font-awesome.min.css','jquery-confirm.min.css' ),array("media"=>'all')); ?>

    <style>
        .banner_lbl{
            text-align: center;
            font-size: 0.6rem;
            text-align: center;
            width: 100%;
            font-weight: 600;
            text-decoration: underline;
            text-transform: uppercase;
            color: green;
        }

        .navigation-arrow-left {
            animation: slide1 1s ease-in-out infinite;
            color: #1bb518;
            font-size: 1.5rem;
            padding: 1rem 0;
            float: left;

        }
        @keyframes slide1 {
            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(5px, 0px);
            }
        }

        .upload_btn_span{
            border: 1px solid;
            padding: 10px;
            border-radius: 4px;
            background: #1b8c1b;
            color: #fff;
        }


        header div[class^="col-"], .icon-div-btn{
            float: left !important;
        }

        .icon-div-btn{
            min-height: 120px !important;
        }

    </style>
</head>

<body id="body" style="float: left;display: block;">
<header >
    <div class="row block detail_main_box" >
        <div class="row top_header_row">
            <div class="col-1 top_back_box" style="">


            </div>
            <div class="col-3 top_photo_box">
                <img src="<?php echo $data[0]['profile_photo']; ?>" />
            </div>
            <div class="col-9" style="padding-right: 0px;padding-left: 0px;display: contents;">
                <div class="doctor-detail-container" >
                    <h1 class="doctor-name"><?php echo mb_strimwidth($data[0]['doctor_name'], 0, 25, '...'); ?></h1>
                    <span id="top_sub_title">Dashboard</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row tab_btn_div">

        <?php $showButton = true; ?>

        <div class="col-12 banner_div_sec">
            <div class="row doctor_banner_img_sec">
                <div class="col-3">
                    <?php if(!empty($doctor_data['profile_photo'])){ ?>
                        <img src="<?php echo $doctor_data['profile_photo']; ?>"  />
                    <?php }else{ ?>
                        <img src="<?php echo Router::url("/images/channel-icon.png",true); ?>" />
                    <?php } ?>
                </div>
                <div class="col-9" style="padding-right: 0px;">
                    <div class="service_sec">

                        <div class="service_sec">
                            <?php if($category_name=='DOCTOR_OPD' || $category_name=='DOCTOR'){ ?>
                                <h5>Consultation Fee</h5>
                                <?php if($doctor_data['is_offline_consulting']=='YES'){ ?>
                                    <label class="dash_consulting_lbl"><i class="fa fa-building-o" aria-hidden="true"></i> Hospital/Clinic Visit <span><?php echo $doctor_data['service_amount']; ?> Rs/-</span></label>
                                <?php } ?>
                                <?php if($doctor_data['is_online_consulting']=='YES'){ ?>
                                    <label class="dash_consulting_lbl"><i class="fa fa-video-camera" aria-hidden="true"></i> Video <span><?php echo $doctor_data['video_consulting_amount']; ?> Rs/-</span></label>
                                <?php } ?>
                                <?php if($doctor_data['is_audio_consulting']=='YES'){ ?>
                                    <label class="dash_consulting_lbl"><i class="fa fa-file-audio-o" aria-hidden="true"></i> Voice <span><?php echo $doctor_data['audio_consulting_amount']; ?> Rs/-</span></label>
                                <?php } ?>
                                <?php if($doctor_data['is_chat_consulting']=='YES'){ ?>
                                    <label class="dash_consulting_lbl"><i class="fa fa-comments-o" aria-hidden="true"></i> Chat <span><?php echo $doctor_data['chat_consulting_amount']; ?> Rs/-</span></label>
                                <?php } ?>
                            <?php }else{ ?>
                                <h5>Nature of Service</h5>
                                <label class="dash_consulting_lbl"><i class="fa fa-handshake-o" aria-hidden="true"></i> <?php echo $data_array['service']['name']; ?></label>
                                <br>
                                <label class=""><i class="fa fa-clock-o" aria-hidden="true"></i> <b><?php echo $data_array['service']['time_from']; ?></b> To  <b><?php echo $data_array['service']['time_to']; ?></b> </label>
                            <?php } ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="icon-div-btn col-4">
            <button class="tab-info" data-title="About" data-id="info"><i class="fa fa-info-circle"></i>About</button>
        </div>

        <div class="icon-div-btn col-4">
            <button class="tab-appointment" data-title="Book Token" id="app_btn" data-id="appointment">
                <i class="fa fa-ticket" aria-hidden="true"></i>Book Token
            </button>
        </div>

        <div class="icon-div-btn col-4">
            <button class="tab-appointment" data-title="Book Token" id="app_btn" data-id="appointment">
                <i class="fa fa-ticket" aria-hidden="true"></i>Walk In token
            </button>
        </div>


        <div class="icon-div-btn col-4" id="dash_my_appointment" style="">
            <button class=" tab-blog" data-id="my_appointment" data-title="My Appointment"><i class="fa fa-history" aria-hidden="true"></i>My Token</button>
        </div>






        <div class="icon-div-btn col-4" id="dash_track_token" style="" >
            <button class=" tab-blog"  data-id="track_token" data-title="Track Token"><i class="fa fa-history" aria-hidden="true"></i>Track Token</button>
        </div>





    </div>
</header>
<div class="main_container container-fluid contain" >
    <table class="table_bottom">
        <tr>
            <td class="tracker_row">
                <div class="row row_box">
                    <style>
                        .tracker_row th, .tracker_row td{
                            padding: 3px 1px !important;
                        }
                        .tracker_row{
                            background: #4aaf27;
                        }
                        .tracker_row .refresh_tracker_btn{
                            padding: 1px 7px;
                            font-size: 0.7rem;
                            border: 1px solid green;
                            outline: none;
                        }
                    </style>
                    <table id='tracker_tab_data' class="row_box" style="width: 100%;text-align: center;padding: 2px;">

                        <tr style="background: linear-gradient(#4aaf27, #83b970cf); color: #fff;">
                            <th>Current Token</th>
                            <th style="border-right: 2px solid #5aab5a;">Time</th>
                            <th>Your Token</th>
                            <th>Time</th>
                        </tr>
                        <tr style="background: #f5fff2;">
                            <td style="color: green;">15</td>
                            <td style="border-right: 2px solid #5aab5a;">10:30 AM</td>

                            <td style="color: green;">
                                <b>14</b>
                            </td>
                            <td>
                                10:15 AM
                            </td>
                        </tr>
                        <tr style="background: #f5fff2;">
                            <td colspan="3" style="text-align: center; padding: 5px 1px !important; font-size: 0.9rem; ">Your waiting time is : <b><?php echo  0; ?></b></td>
                            <td colspan="1" style="text-align: center; padding: 5px 1px !important; "><button class="xs btn btn-default refresh_tracker_btn">Reload</td>
                        </tr>

                    </table>
                </div>
            </td>
        </tr>

    </table>
</div>



</body>
</html>






