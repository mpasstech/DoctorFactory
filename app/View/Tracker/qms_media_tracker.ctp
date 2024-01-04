<html>

<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <script src="<?php echo Router::url('/js/jquery.js') ?>"></script>
    <script src="<?php echo Router::url('/js/bootstrap.min.js') ?>"></script>
    <link rel="stylesheet" href="<?php echo Router::url('/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo Router::url('/css/font-awesome.min.css') ?>" />
    <link rel="icon" type="image/x-icon" href="https://www.mpasscheckin.com/assets/images/icons/favicon.ico">
    <?php echo $this->Html->css(array('tracker.css')); ?>
    <style>
        #zoomElement {

            display: none;
            align-items: center;
            justify-content: center;
            text-align: center;
            position: absolute;
            transition: transform 0.5s ease, opacity 5s ease;
            background-color: #ffffffad;
            transform-origin: center center;
            font-size: 50px;
            color: #3f84bf;
        }


        #font_setting {
            padding: 10px 0px;
            margin: 2px 0 25px 0px;
            float: left;
            width: 100%;
            background: #efefef;
        }

        #font_setting li {
            list-style: none;
            width: 20%;
            float: left;
            text-align: center;
        }

        #font_setting li label {
            display: block;
            width: 100%;

        }

        #font_setting li select {
            padding: 5px 10px;
            width: 60%;



        }

        .upcoming_patient_ul li {
            width: 90%;
            margin: 10px 30px;
            border: 1px solid;
            border-radius: 5px;
            padding: 0px 0px !important;
            height: auto !important;
            font-size: 4rem;
            display: block;
        }

        .upcoming_patient_ul li label {
            padding: 0;
            margin: 0;
            width: 100%;
            display: block;
            float: left;
        }

        .upcoming_patient_ul li span {
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


        .service_name_heading {
            background: #dddbdb !important;
        }

        .half_screen_window {
            float: left !important;
            /* width: 50% !important; */
            width: 100% !important;
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

        .zoom .token_number,
        .zoom .service_name {
            font-size: 12rem;
            color: #fff !important;
        }

        .zoom .doctor_profile {
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
        .marq_div {
            position: absolute;
            bottom: 5px;
            width: 100%;
            display: block;
            margin: 0;
            padding: 0;
            font-size: 3rem;
            font-weight: 600;
        }

        .heading_bar h1 {
            font-size: 3rem;
        }

        .carousel-caption {
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

        .carousel-caption p {
            color: #484848;
            font-size: 2.2rem;
            text-align: center;
            font-weight: 500;
            text-shadow: none;

        }

        .carousel-caption h3 {
            color: rgb(65, 105, 225);
            box-shadow: none;

        }

        .carousel-caption h6 {
            box-shadow: none !important;
            color: green !important;
            font-weight: 500;
        }



        .frame_div {
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

        .frame_div img {
            margin: 0 auto;
            display: block;
            float: left;
            width: 100%;
            height: 100%;
            position: relative;
        }


        .carousel-control.left,
        .carousel-control.right {
            background: none;
        }

        .token_li {
            margin: 1%;
            padding: 0.1%;
            width: 98%;
            float: left;
            display: block;
        }

        #settingButton {
            background: #1c1ce3;
            border: 1px solid #0a0aad !important;


            color: #fff;
            padding: 0px 8px;
            border-radius: 6px;
        }

        .play_voice {
            width: 30px;
            height: 30px;
            background: red;
            padding: 5px;
            float: left;
            margin: 2px 4px;
            border-radius: 6px;
        }

        .patient_token_span {
            background: green;
            font-size: 2.5rem;
            padding: 12px 0%;
            border-radius: 52%;
            width: 55px;
            height: 55px;
            display: block;
            text-align: center;
            margin: 10px auto;

            color: #fff;
        }

        .current_token_box {
            padding: 0.8rem 0.5rem;
            display: block;
            width: 100%;
            float: left;
            border-bottom: 2px solid #3f84bf;
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

        .active_box {
            display: block !important;
        }

        .mu-hero-overlay {
            height: 100%;

        }

        #go-button {
            position: absolute;
            position: absolute;
            position: absolute;
            top: 50px;
            float: right;
            z-index: 9999999;
            right: 0;
            height: 40px;
            width: 45px;
            cursor: pointer;

        }



        .carousel-indicators {
            display: none;
        }


        #go-button img {
            border: 1px solid;
            border-radius: 6px 0px 0px 6px;
        }

        #go-button span {
            font-size: 9px;
            color: #fff;
            width: 100%;
            display: block;
            text-align: center;
        }


        .append_html {
            display: block;
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }


        .append_html li {
            list-style: none;
            float: left;

            padding: 10px;


        }

        td {
            padding: 0;
        }

        /* .append_html li:first-child, .append_html li:nth-child(2){
            padding: 2% 10px ;
        } */

        .append_html ul {

            padding: 0;
            /* margin: 10px; */
            float: left;
            display: inline-flex;
        }

        .heading_bar {
            font-size: 6rem;
            text-align: center;
            width: 100%;
            background: #3f84bf;
            color: #fff;
        }

        .doctor_profile {
            border: 1px solid #fff;
            padding: 2px;
            border-radius: 50%;
            width: 7rem;
            height: 7rem;
        }

        .service_name {
            font-size: 3rem;
            padding: 0px 2rem;
            color: #fff;

            width: 100%;
            display: block;
        }

        .token_heading {
            width: 100%;
            text-align: center;
            color: #fff;
            font-size: 3.0rem;
        }

        .token_number {
            color: #000;
            text-align: center;
            font-size: 5.5rem;
            padding: 0px 5px;

        }

        @media only screen and (max-width: 500px) {
            .mu-logo {
                font-size: 18px;
            }

            #mu-event-counter,
            .mu-event-counter-area {
                width: 100%;
            }

            .mu-event-counter-block {
                width: 170px;
                height: 170px;
                margin: 0 auto;
            }
        }

        @media (min-width: 1000px) and (max-width: 1200px) and (orientation: landscape) {

            body {}


        }

        @media (min-width: 1000px) and (max-width: 1200px) and (min-height: 1500px) and (max-height: 2000px) {

            .mu-event-counter-area {
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

            .mu-event-counter-block {
                margin: 0 11%;
            }

            #mu-event-counter {
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
                margin: 0;
            }


        }

        @media (max-width: 480px) {


            #bottomLogoMpass {
                left: 0% !important;

                width: 40% !important;
                padding: 5px !important;
                border-radius: 5px 5px 0px 0px;
            }

            #tracker_box {
                height: auto !important;
            }


            .live_tracker_lbl {
                font-size: 1.5rem !important;
                padding: 0.5rem;
            }

            .play_voice {
                width: 30px;
                height: 30px;
            }

            #settingButton {
                display: none !important;
            }

            .token_li tr td:first-child {}

            #setting_doctor_list_box li {
                width: 48% !important;
                height: 71px !important;
                font-size: 1.3rem !important;
            }

            .mu-event-counter-block span {
                font-size: 65px !important;
            }

            .heading_bar h1 {
                font-size: 2.5rem;
            }


            .mu-event-counter-area,
            .mu-hero-featured-content {
                width: 100%;
            }

        }

        @media (max-width: 360px) {}

        @media (min-width: 320px) and (max-width: 480px) {
            .heading_bar {
                font-size: 3.5rem;
                padding: 15px;
            }

            .play_voice {
                width: 30px;
                height: 30px;
            }

            .live_tracker_lbl {
                font-size: 1.5rem !important;
                padding: 0.5rem;
            }

            #bottomLogoMpass {
                left: 0% !important;

                width: 40% !important;
                padding: 5px !important;
                border-radius: 5px 5px 0px 0px;
            }

            #settingButton {
                display: none !important;
            }

            .token_li tr td:first-child {}

            #setting_doctor_list_box li {
                width: 48% !important;
                height: 71px !important;
                font-size: 1.3rem !important;
            }


            .append_html li:first-child,
            .append_html li:nth-child(2) {
                padding: 4% 10px;
            }




            .doctor_profile {
                width: 5rem;
                height: 5rem;
            }

            .service_name {
                font-size: 3rem !important;
            }

            .token_heading {
                font-size: 2.2rem;
                padding: 0;
                margin: 0;
            }

            .token_number {
                font-size: 3rem !important;
            }

            .append_html li {
                height: 90px;
            }

            .append_html ul {
                margin: 5px;
            }

            #go-button {
                display: none;
            }
        }

        @media (min-width: 576px) and (max-width: 800px) {}






        body {
            background: #fff;
        }

        header {
            background: #fff;
        }

        .amazon_silk {
            width: 50% !important;
            float: left !important;
        }

        #setting_doctor_list_box {
            float: left;
            display: block;
            width: 100%;
        }

        #setting_doctor_list_box li {
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

        #setting_doctor_list_box li label {
            width: 100%;
            float: left;
            display: block;
            margin: 0;
            padding: 0.8rem 0.7rem;

        }


        #setting_doctor_list_box li.active {
            background: #10c10c;
            color: #fff;
        }


        #setting_doctor_list_box li input {
            float: right;
            width: 18px;
            height: 18px;
        }

        #setting_doctor_list_box .service_name_span {
            display: block;
            width: 100%;
            float: left;
        }

        #billingCounterDisplay li {
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

        #billingCounterDisplay span {
            font-size: 6rem;
            background: blue;
            color: #fff;
            padding: 0.6rem;
        }
    </style>
    <script type="text/javascript" src="<?php echo TOKEN_SOCKET_URL . '/socket/socket.io.js'; ?>"></script>

</head>

<body>
    <div id="zoomElement">Your Content Here</div>

    <header id="mu-hero" class="" role="banner">

        <div class="overlay_box_div">
            <lable class="break_doctor"></lable>
            <h2 class="emergency">EMERGENCY</h2>
            <h3 class="emer_patient_name"></h3>
        </div>


        <div id="go-button">
            <img src="<?php echo Router::url('/images/tracker/large_screen.png', true); ?>" />

            <span id="box_size"></span>
        </div>
        <div class="container" style="width: 100% !important; padding-left: 0;padding-right: 0; float: left;display: block;">


            <?php $col = 12;
            $has_slider = "";
            if (!empty($slide_list) || !empty($mediaData)) {
                $has_slider = "has_slider";
                $col = 12;
            }

            $cookies_name = "tracker_display_" . $thin_app_id;
            $hospital_font_size = "3rem";
            if (isset($_COOKIE[$cookies_name])) {
                $setting = json_decode($_COOKIE[$cookies_name], true);
                $hospital_font_size = isset($setting["hospital_font_size"]) ? $setting["hospital_font_size"] : $hospital_font_size;
            }

            ?>

            <div id="tracker_box" style="<?php if ($thin_app_id == 902) {
                                                echo "width:100% !important;";
                                            } ?> padding-right: 0;padding-left: 0;" class="col-xs-12 col-sm-12 col-md-<?php echo $col; ?> col-lg-<?php echo $col; ?> <?php echo $has_slider; ?>">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tracker_box_header" style="padding-right: 0;padding-left: 0; display: <?php echo ($ShowHeader == 'no') ? 'none' : ''; ?>">
                    <table class="heading_bar" style="width: 100%;">
                        <tr>

                            <td>
                                <h1 style="font-size:<?php echo $hospital_font_size; ?>; padding:0.6rem 0.2rem;width:100%;display:block;float:left;text-align:center;margin:0;">
                                    <img class="play_voice" style="position:absolute;left:0; float: left;" src="<?php echo Router::url('/img/sound-off.png', true); ?>" />
                                    <?php echo $thinapp_data['name']; ?>
                                    <button id="settingButton" style="float: right;">
                                        <i class="fa fa-gear" style="font-size: 2.5rem;"></i>
                                        <sapn style="display: block;font-size: 0.7rem;text-align: center" id="show_count">- / -</sapn>
                                    </button>

                                </h1>
                            </td>

                        </tr>
                    </table>
                </div>

                <?php if (!empty($patientToken)) {
                    if ($patientToken['sub_token'] == "NO") { ?>
                        <div class="col-md-12 current_token_box">
                            <table style="width: 100%;font-size: 2rem;">
                                <tr>
                                    <td colspan="3">
                                        <h3 style="width: 100%;text-align: center;">Your Token</h3>
                                    </td>

                                </tr>
                                <tr>
                                    <?php

                                    $label = "Doctor";
                                    if (!empty($patientToken['name_label'])) {
                                        $label = $patientToken['name_label'];
                                    } else if ($patientToken['department_category_id'] == 32) {
                                        $label = "Service";
                                    }


                                    $token = $patientToken['queue_number'];
                                    if ($patientToken['sub_token'] == "YES") {
                                        $token = !empty($patientToken['remark']) ? $patientToken['remark'] : "Emergency";
                                    }
                                    ?>
                                    <th><?php echo $label; ?></th>
                                    <td><?php echo $patientToken['doctor_name']; ?></td>
                                    <td rowspan="3"><span class="patient_token_span"><?php echo $token; ?></span></td>
                                </tr>
                                <tr>
                                    <th>Patient</th>
                                    <td>
                                        <?php echo $patientToken['patient_name']; ?>
                                    </td>
                                </tr>

                                <?php if ($ShowHeader == 'no') { ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center;">
                                            <button class="btn btn-xm btn-danger play_voice" style="width: 42%;padding: 0;">Allow Tracker Voice</button>
                                        </td>
                                    </tr>

                                <?php } ?>

                            </table>
                        </div>
                <?php }
                } ?>
                <div class="append_html">
                    <h1 style="text-align: center;"> Please wait... </h1>
                </div>

                <?php if (false) { ?>
                    <div class="marq_div">
                        <marquee scrollamount="8" style="padding: 0; display: block;width: 100%;"><?php echo $thinapp_data['tune_tracker_media']; ?></marquee>
                    </div>
                <?php } ?>
            </div>

            <?php if (!empty($mediaData)) { ?>
                <div class="row media-main-container media-attach">
                    <div id="slider_box" class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style=" padding-right: 0; padding-left: 0;">
                        <?php foreach ($mediaData as $keyMedia => $media) { ?>
                            <div style="margin:0 !important; background:#000;" class="row media-container media<?php echo $keyMedia; ?>" data-url="<?php echo $media['MediaMessage']['media_url']; ?>" id="media<?php echo $keyMedia; ?>" duration="<?php echo ($media['MediaMessage']['duration'] * 60) * 1000; ?>" style="display:none;" data-type="<?php echo $media['MediaMessage']['media_type']; ?>">
                                <?php if ($media['MediaMessage']['media_type'] == 'IMAGE') { ?>
                                    <img style="width: 100%; height:auto;" src="<?php echo $media['MediaMessage']['media_url']; ?>" class="media_image">
                                <?php } else if ($media['MediaMessage']['media_type'] == 'VIDEO') { ?>
                                    <video controls width="100%" height="100%" class="media_video" muted="muted" id="media1<?php echo $keyMedia; ?>" preload="metadata" controls="true">
                                        <source src="<?php echo $media['MediaMessage']['media_url']; ?>" type="video/mp4">
                                        <source src="<?php echo $media['MediaMessage']['media_url']; ?>" type="video/ogg">
                                        <source src="<?php echo $media['MediaMessage']['media_url']; ?>" type="video/webm">
                                        Your browser does not support the video tag.
                                    </video>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            <?php } else if (!empty($slide_list)) { ?>
                <div id="slider_box" class="col-xs-12 col-sm-12 col-md-6 col-lg-6" style="padding-right: 0; padding-left: 0;">

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-right: 0;padding-left: 0; display: <?php echo ($ShowHeader == 'no') ? 'none' : ''; ?>">
                        <table class="heading_bar" style="width: 100%;">
                            <tr>
                                <td>
                                    <h1><?php echo "Our Experts"; //$thinapp_data['name']; 
                                        ?></h1>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div style="padding-right: 0;padding-left: 0" id="slideList" class="carousel slide carousel slide col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <?php foreach ($slide_list as $key => $data) {
                                if (!empty($data['description'])) { ?>
                                    <li data-target="#slideList" data-slide-to="0" class="<?php echo ($key == 0) ? 'active' : ''; ?>"></li>
                            <?php }
                            } ?>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">

                            <?php foreach ($slide_list as $key => $data) {
                                if (!empty($data['description'])) {
                                    $path = "https://s3-ap-south-1.amazonaws.com/mengage/20220714132647102161613.png";
                                    if (!empty($data['profile_photo'])) {
                                        $path = $data['profile_photo'];
                                    }

                            ?>
                                    <div class="item <?php echo ($key == 0) ? 'active' : ''; ?>">
                                        <div class="frame_div">
                                            <img src="<?php echo $path; ?>" alt="<?php echo $data['doctor_name']; ?>" style="width:100%;">
                                        </div>
                                        <div style="border-left:1px solid #2574ed75;" class="carousel-caption">
                                            <h3><?php echo $data['doctor_name']; ?></h3>
                                            <?php if (!empty($data['description'])) { ?>
                                                <p><?php echo $data['description']; ?></p>
                                            <?php } ?>

                                        </div>
                                    </div>
                            <?php }
                            } ?>


                        </div>


                        <a style="display: none;" class="left carousel-control" href="#slideList" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a style="display: none;" class="right carousel-control" href="#slideList" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
            <?php } ?>








            <?php if ($thin_app_id == 899) { ?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                    <marquee id="billingCounterDisplay" style="padding: 0; display: <?php echo !empty($billingTokenString) ? 'block' : 'none'; ?>;width: 100%;">Billing Under Process :- <span><?php echo $billingTokenString; ?></span></marquee>
                </div>
            <?php } ?>




        </div>
        <div class="modal fade" id="settingModal" role="dialog">
            <div class="modal-dialog modal-lg" style="width: 90%;">
                <div class="modal-content" style="width: 100%;display: block;float: left;">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Tracker Setting</h4>
                    </div>
                    <div class="modal-body">

                        <h3>Font & Media Setting <button style="float: right;" id="reset_setting_btn" class="btn btn-info">Reset font & media setting</button></h3>

                        <ul id="font_setting">

                            <li>
                                <label>Room/Counter name</label>
                                <select id="counter_name_font_size">
                                    <?php for ($counter = 1; $counter <= 8; $counter = $counter + .1) { ?>
                                        <option value="<?php echo $counter . "rem"; ?>"> <?php echo $counter . "X"; ?></option>
                                    <?php } ?>
                                </select>
                            </li>
                            <li>
                                <label>Token number</label>
                                <select id="token_font_size">
                                    <?php for ($counter = 1; $counter <= 8; $counter = $counter + .1) { ?>
                                        <option value="<?php echo $counter . "rem"; ?>"> <?php echo $counter . "X"; ?></option>
                                    <?php } ?>
                                </select>
                            </li>



                            <li>
                                <label>Category Name</label>
                                <select id="category_font_size">
                                    <?php for ($counter = 1; $counter <= 8; $counter = $counter + .1) { ?>
                                        <option value="<?php echo $counter . "rem"; ?>"> <?php echo $counter . "X"; ?></option>
                                    <?php } ?>
                                </select>
                            </li>

                            <li>
                                <label>Hosptial Name</label>
                                <select id="hospital_font_size">
                                    <?php for ($counter = 1; $counter <= 8; $counter = $counter + .1) { ?>
                                        <option value="<?php echo $counter . "rem"; ?>"> <?php echo $counter . "X"; ?></option>
                                    <?php } ?>
                                </select>
                            </li>

                            <li>
                                <label>Youtube audio</label>
                                <select id="youtube_audio">
                                    <option value="on">On</option>
                                    <option value="off">Off</option>
                                </select>
                            </li>

                        </ul>


                        <h3 style="margin-top:30px;">Select Counter/Doctor/Room </h3>
                        <ul id="setting_doctor_list_box">

                        </ul>

                    </div>
                    <div class="modal-footer" style="float: left;width: 100%;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="modalSaveBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>
        <img id="bottomLogoMpass" style="border-radius : 40px 0px 0px 0px; bottom: 0px;right: 0px; background: rgb(255 255 255 / 10%); z-index: 999999;padding: 10px;" src="https://s3-ap-south-1.amazonaws.com/mengage/20230327170026266608522.png">


    </header>

</body>
<script src="https://www.youtube.com/player_api"></script>
<script>
    $(document).ready(function() {

        $('.carousel').carousel({
            interval: 8000,
            pause: "false"
        });

        var thin_app_id = "<?php echo $thin_app_id; ?>";
        var ehcc_app_id = "<?php echo $ehcc_app_id; ?>";
        var is_custom_app = "<?php echo $is_custom_app; ?>";

        var settingCookes = "doctor_setting_" + thin_app_id;
        var fontsettingCookes = "tracker_display_" + thin_app_id;


        var setting = getCookie(settingCookes);

        if (setting.length > 0) {
            setting = JSON.parse(setting);
        } else {
            var settingArray = [];
            $(".doc_checkbox").each(function() {
                var id = $(this).attr('data-id');
                settingArray.push({
                    "id": id,
                    "checked": true
                });
            });
            setCookie(settingCookes, JSON.stringify(settingArray), 30000);
        }



        var socetUrl = "<?php echo TOKEN_SOCKET_URL; ?>";
        socket = io.connect(socetUrl);
        socket.on('updateToken', function(data) {

            if (data.thin_app_id == thin_app_id) {
                if (data.billingTokenString != "") {
                    $("#billingCounterDisplay span").html(data.billingTokenString);
                    $("#billingCounterDisplay").show();
                } else {
                    $("#billingCounterDisplay").hide();
                }

                var doctor_id = data.doctor_id;
                if (setting != "") {
                    obj = setting.find(o => o.id === doctor_id);
                }

                var pat_name = "";
                try {
                    pat_name = (data.patient_name && data.patient_name.search("Patient") < 0) ? data.patient_name : "";
                } catch (error) {
                    console.error("An error occurred:", error.message);
                }




                if (setting == "" || (obj && obj.checked == true)) {
                    if (data.reload == true) {
                        load_list();
                    } else if (data.play == true) {
                        var key = "TM_" + data.doctor_id + "_" + data.token;
                        $("#" + data.doctor_id).find(".token_number").html(data.token);
                        $("#" + data.doctor_id).find(".patient_name").html(pat_name);
                        loadUpcomingList();
                        localStorage.setItem(key, data.fileName);
                    } else if (data.play == false) {
                        $("#" + data.doctor_id).find(".token_number").html(data.token);
                        $("#" + data.doctor_id).find(".patient_name").html(pat_name);

                        loadUpcomingList();
                    }
                }
                if (data.token == "Closed") {
                    $("#" + data.doctor_id).find(".token_number").addClass("closedToken");
                } else {
                    $("#" + data.doctor_id).find(".token_number").removeClass("closedToken");
                }


            }
        });
        setInterval(function() {
            for (var i = 0; i < localStorage.length; i++) {
                if (localStorage.key(i).substring(0, 3) == 'TM_') {
                    var key = localStorage.key(i);
                    var keyArray = key.split("_");
                    var tmpArray = [];
                    tmpArray.push(new Audio(baseurl + "queue_tracker_voices/" + localStorage.getItem(key)));
                    play_sound_queue(tmpArray, key, keyArray[1]);
                }
            }
        }, 3000);

        var audioRunning = false;
        var baseurl = "<?php echo Router::url('/', true); ?>";


        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            let name = cname + "=";
            let decodedCookie = decodeURIComponent(document.cookie);
            let ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
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


        $(document).on("click", ".play_voice", function(evt) {
            const ks = new Audio(baseurl + 'queue_tracker_voices/no_sound.mp3');
            let userinteraction = 0;
            if (userinteraction) return;
            userinteraction++;
            ks.play()
            $(this).hide();
        })

        $(document).on("click", "#settingButton", function(evt) {
            $(".doc_checkbox").prop("checked", true);
            var html = "";
            $(".load_tracker_div").each(function() {
                var doctor_id = $(this).find('.token_li').attr('id');
                var doctor_name = $(this).find('.service_name').text();
                var service_name = $(this).closest('.service_data_container').find('.service_name_heading').text();

                // alert("doctor_id----"+doctor_id+"-----doctor_name----"+doctor_name+"----service_name-----"+service_name)

                service_name = "<span class='service_name_span'>" + service_name + "</span>";

                var input = '<input type="checkbox" class="doc_checkbox ' + doctor_id + '"  data-id="' + doctor_id + '" id="checkbox_' + doctor_id + '"/>';
                html += "<li><label>" + doctor_name + input + service_name + "</label></li>";
            });

            $("#setting_doctor_list_box").html(html);
            var listFound = false;
            if (getCookie(settingCookes)) {
                var setting = JSON.parse(getCookie(settingCookes));
                if (setting.length > 0) {
                    $.each(setting, function(index, value) {
                        if (value.checked == true) {
                            $("#checkbox_" + value.id).closest("li").addClass("active");
                        }
                        $("#checkbox_" + value.id).prop("checked", value.checked);
                        listFound = true;
                    });
                }
            }

            if (listFound == false) {
                $("#setting_doctor_list_box [type='checkbox']").closest("li").addClass("active");
                $("#setting_doctor_list_box [type='checkbox']").prop("checked", true);
            }



            if (!getCookie(fontsettingCookes)) {
                saveFontSetting("3rem", "3rem", "1.8rem", "off", "3rem");
            }
            if (getCookie(fontsettingCookes)) {
                var setting = JSON.parse(getCookie(fontsettingCookes));
                $("#counter_name_font_size").val(setting['counter_name_font_size']);
                $("#token_font_size").val(setting['token_font_size']);
                $("#category_font_size").val(setting['category_font_size']);
                $("#hospital_font_size").val(setting['hospital_font_size']);
                $("#youtube_audio").val(setting['youtube_audio']);
            }


            $("#settingModal").modal("show");
        });



        function saveFontSetting(counter_name_font_size, token_font_size, category_font_size, youtube_audio, hospital_font_size) {
            var tmp = {
                counter_name_font_size: counter_name_font_size,
                token_font_size: token_font_size,
                category_font_size: category_font_size,
                youtube_audio: youtube_audio,
                hospital_font_size: hospital_font_size,
            }
            setCookie(fontsettingCookes, JSON.stringify(tmp), 30000);

        }

        var settingArray = [];



        $(document).on("change", "#setting_doctor_list_box input[type='checkbox']", function(evt) {
            if ($(this).is(":checked")) {
                $(this).closest('li').addClass('active');
            } else {
                $(this).closest('li').removeClass('active');
            }
        });


        $(document).on("click", "#reset_setting_btn", function(evt) {
            if (confirm("Are you sure you want to reset")) {
                saveFontSetting("3rem", "3rem", "1.8rem", "off");
                window.location.reload();
            }
        });



        $(document).on("click", "#modalSaveBtn", function(evt) {
            $(".doc_checkbox").each(function() {
                var id = $(this).attr('data-id');
                var checked = $(this).is(':checked');
                settingArray.push({
                    "id": id,
                    "checked": checked
                });
            });
            setCookie(settingCookes, JSON.stringify(settingArray), 30000);

            var counter_name_font_size = $("#counter_name_font_size").val();
            var token_font_size = $("#token_font_size").val();
            var category_font_size = $("#category_font_size").val();
            var hospital_font_size = $("#hospital_font_size").val();
            var youtube_audio = $("#youtube_audio").val();
            saveFontSetting(counter_name_font_size, token_font_size, category_font_size, youtube_audio, hospital_font_size);


            window.location.reload();
            $("#settingModal").modal("hide");
        });

        function checkCurrentDate() {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            return mm + '/' + dd + '/' + yyyy;
        }
        var currentDate = checkCurrentDate();
        setInterval(function() {
            if (currentDate != checkCurrentDate()) {
                load_list();
            }
        }, 600000);

        blink_interval = setInterval(blink_text, 1000);

        function blink_text() {
            $('.blink').fadeOut(500);
            $('.blink').fadeIn(500);
        }



        var refresh_list_seconds = "8000";
        load_list();
        var change_div = 3000;
        var interval = setInterval(function() {
            if (process_running === false) {
                //  load_list();
            }
        }, refresh_list_seconds);
        var process_running = false;

        Object.size = function(obj) {
            var size = 0,
                key;
            for (key in obj) {
                if (obj.hasOwnProperty(key)) size++;
            }
            return size;
        };


        function check_emergency_appointment(current_obj) {
            var doctor_id = $(current_obj).attr('id') + "";
            var doctor_name = $(current_obj).find('.mu-logo').text();
            var object_data = JSON.parse($('body').data('key'));
            if (object_data.hasOwnProperty(doctor_id)) {

                var data = object_data[doctor_id];
                if (data.patient_name) {

                    $(".break_doctor").html(doctor_name);
                    if (data.flag == "CUSTOM") {
                        $(".emergency").hide();
                        $(".overlay_box_div .emer_patient_name").html(data.patient_name);
                    } else {
                        $(".emergency").show();

                        $(".overlay_box_div .emer_patient_name").html("Patient - " + data.patient_name);
                    }
                    $(".overlay_box_div").show();
                } else {
                    $(".overlay_box_div .emer_patient_name").html("");
                    $(".overlay_box_div").hide();
                }
            } else {
                $(".overlay_box_div .emer_patient_name").html("");
                $(".overlay_box_div").hide();
            }
        }

        var interIneer;
        var screenLoad = false;
        var index = 0;

        function load_list() {



            if (true) {

                var actionName = (is_custom_app == "YES") ? 'load_qms_media_tracker' : 'load_fortis_tracker';

                var t = "<?php echo @($this->request->query['t']); ?>";
                var pt = "<?php echo isset($this->request->query['pm']) ? 'yes' : 'no'; ?>";
                var doctor_id_string = "<?php echo $doctor_ids; ?>";
                var logo = "<?php echo $thinapp_data['logo']; ?>";
                $.ajax({
                    type: 'POST',
                    url: baseurl + "tracker/" + actionName,
                    data: {
                        t: t,
                        doctor_id_string: doctor_id_string,
                        logo: logo,
                        pt: pt
                    },
                    beforeSend: function() {
                        index = 0;
                        process_running = true;
                    },
                    success: function(data) {
                        currentDate = checkCurrentDate();

                        $(".append_html").html(data);
                        if ($(".carousel-inner .item").length == 0 && $(".token_li").length > 4) {


                        }
                        var audioArray = [];
                        var audioFileCounter = 0;
                        var total = 0;
                        var active = 0;
                        $(".token_li").each(function(index, value) {
                            total++;
                            $('.ul_class').removeClass("blink");
                            var doctor_id = $(this).attr('id');
                            var obj;
                            if (setting != "") {
                                obj = setting.find(o => o.id === doctor_id);
                            }
                            if (setting == "" || (obj && obj.checked == true)) {
                                $(this).closest(".ul_class").show();
                                active++;
                            } else {
                                $(this).closest(".ul_class").hide();
                            }
                        });
                        $("#show_count").html(active + " / " + total);
                        screenLoad = true;
                        process_running = false;
                        $(".service_data_container").each(function() {
                            var parentObject = $(this);
                            if ($(this).find('.load_tracker_div:visible').length == 0) {
                                $(parentObject).hide();
                            }
                        });
                        //alert($(window).width());
                        if ($(window).width() > 800) {
                            if ($(".has_slider").length == 0) {
                                if ($(".token_li").length >= 4) {
                                    $(".ul_class").addClass("half_screen_window");
                                }
                            } else {
                                if ($(".has_slider").length == 1) {
                                    $("#tracker_box, #slider_box").addClass("half_screen_window");
                                }
                            }
                        }


                        loadUpcomingList();

                    },
                    error: function(data) {
                        process_running = false;
                    }
                });
            }
        }


        function isAudioSettingMute() {
            var setting = getCookie(fontsettingCookes);
            if (setting.length > 0) {
                setting = JSON.parse(setting);
                if (setting['youtube_audio'] != undefined && setting['youtube_audio'] == 'off') {
                    // player.mute(1);
                    return true;
                }
            }
            return false;

        }

        function play(audio, doctor_id, storageKey, callback) {
            $("#zoomElement").css("display", "none");
            try {
                if (audioRunning == false) {
                    localStorage.removeItem(storageKey);
                    audioRunning = true;
                    var fileName = audio.src.split('/').pop();
                    // $("#" + doctor_id).closest('.service_name').html("test")
                    var element = document.getElementById(doctor_id);
                    var service_nameElement = element.querySelector('.service_name');
                    var token_number = element.querySelector('.token_number');

                    var service_nameText = service_nameElement.textContent || service_nameElement.innerText;
                    var token_numberText = token_number.textContent || token_number.innerText;



                    var html = "<div style='background:#fff;width:70%;margin:10% auto;display:block;text-align:center;font-size:10rem;' >" + service_nameText + "<span class='blink' style='font-weight:600;color:green;font-size: 30rem;width: 50%;display:block;text-align:center;margin: 0 auto;'>" + token_numberText + " </span><label style='display:block;width:100%;text-align:center;font-size:6rem;font-weight:600;'>Token Number</label></div>"
                    $("#zoomElement").html(html);

                    updateZoom();
                    setTimeout(function() {
                        $("#" + doctor_id).closest('.ul_class').find(".token_li").removeClass("zoom");
                        $("#zoomElement").css("opacity", "0");
                        $("#zoomElement").css("z-index", "0");

                    }, 8000);

                    if (!isAudioSettingMute()) {
                        player.mute(1);
                    }


                    audio.play().catch(function() {
                        localStorage.removeItem(storageKey);
                        audioRunning = false;
                    });
                    if (callback) {
                        audio.addEventListener('ended', callback);
                    }
                }
            } catch (err) {
                localStorage.removeItem(storageKey);
                audioRunning = false;
                $('.ul_class').removeClass("blink");
            }


        }

        function play_sound_queue(sounds, storageKey, doctor_id) {
            $('.ul_class').removeClass("blink");
            var index = 0;

            function recursive_play() {
                var fileName = sounds[index].src.split('/').pop();
                if (index + 1 === sounds.length) {
                    play(sounds[index], doctor_id, storageKey, function() {
                        audioRunning = false;
                        $("#" + doctor_id).closest('.ul_class').removeClass("blink");
                        if (!isAudioSettingMute() && player && player.isMuted() == true) {
                            player.unMute();



                        }

                    });
                } else {
                    play(sounds[index], doctor_id, storageKey, function() {
                        $("#" + doctor_id).closest('.ul_class').removeClass("blink");
                        if (!isAudioSettingMute() && player && player.isMuted() == true) {
                            player.unMute();
                        }
                        index++;
                        setTimeout(function() {
                            audioRunning = false;
                            recursive_play();
                        }, 1000);
                    });
                }
            }
            recursive_play();
        }




        function GoInFullscreen(element) {
            if (element.requestFullscreen)
                element.requestFullscreen();
            else if (element.mozRequestFullScreen)
                element.mozRequestFullScreen();
            else if (element.webkitRequestFullscreen)
                element.webkitRequestFullscreen();
            else if (element.msRequestFullscreen)
                element.msRequestFullscreen();
        }

        function GoOutFullscreen() {
            if (document.exitFullscreen)
                document.exitFullscreen();
            else if (document.mozCancelFullScreen)
                document.mozCancelFullScreen();
            else if (document.webkitExitFullscreen)
                document.webkitExitFullscreen();
            else if (document.msExitFullscreen)
                document.msExitFullscreen();
        }

        function IsFullScreenCurrently() {
            var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;

            // If no element is in full-screen
            if (full_screen_element === null)
                return false;
            else
                return true;
        }
        $("#go-button").on('click', function() {

            if (IsFullScreenCurrently())
                GoOutFullscreen();
            else
                GoInFullscreen($("#mu-hero").get(0));
        });


        var image_path = "<?php echo Router::url('/images/tracker/', true); ?>"
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange MSFullscreenChange', function() {
            if (IsFullScreenCurrently()) {
                $("#go-button").attr('src', image_path + "small_screen.png");
                //$("#go-button span").html('Minimize');
            } else {
                $("#go-button").attr('src', image_path + "large_screen.png");
                //$("#go-button span").html('Maximize');
            }
        });



        function showMarque() {
            if ($(window).width() < 500) {
                $(".marq_div").hide();
            } else {
                $(".marq_div").show();
            }
        }


        function setLayout() {
            var match = /(?:; ([^;)]+) Build\/.*)?\bSilk\/([0-9._-]+)\b(.*\bMobile Safari\b)?/.exec(navigator.userAgent);

            var hasSlider = "<?php echo !empty($has_slider) ? 'yes' : 'no'; ?>";
            if (match && thin_app_id != ehcc_app_id && hasSlider == 'yes') {
                $("#tracker_box, #slider_box").addClass("amazon_silk");
            } else {
                $("#tracker_box, #slider_box").removeClass("amazon_silk");
            }
            showMarque();
            var screenHeight = window.innerHeight || document.documentElement.clientHeight;
            var box_width = $("#slider_box").width();
            var label = box_width + " X " + screenHeight;

            $("#box_size").html(label);
            //$(".media-container").height(screenHeight);

            setVideoHeight();


        }

        function loadUpcomingList() {
            if (thin_app_id == '607' && $(".token_li:visible").length == 1) {
                var doctor_id = $(".token_li:visible").attr('id');
                $.ajax({
                    type: 'GET',
                    url: baseurl + "tracker/get_upcoming_tokens_list/" + doctor_id,
                    success: function(data) {
                        $("#" + doctor_id).closest(".load_tracker_div").find(".upcoming_patient_ul").html(data);
                    }
                });
            }
        }




        $(window).resize(function() {
            setLayout();

        });

        setLayout();




        function isMobileDevice() {
            const mobileDevicePatterns = /Android|webOS|iPhone|iPad|iPod|BlackBerry|Windows Phone/i;
            return !mobileDevicePatterns.test(navigator.userAgent);
        }

        if (!isMobileDevice()) {
            $("#bottomLogoMpass").css("position", "relative");
            $("#bottomLogoMpass").css("margin", "0 auto");
            $("#bottomLogoMpass").css("display", "block");
        } else {
            $("#bottomLogoMpass").css("position", "fixed");
        }



        var mediaCount = <?php echo count($mediaData); ?>;
        var currKey = 0;

        function showImage() {
            $(".media" + currKey).show();
            currKey++;
            setTimeout(function() {
                showNext();
            }, 25000);
        }

        function showVideo() {
            console.log("media1" + currKey);
            $(".media" + currKey).show();
            videoAttr = document.getElementById("media1" + currKey);

            currKey++;

            var setting = getCookie(fontsettingCookes);
            if (setting.length > 0) {
                setting = JSON.parse(setting);
                if (setting['youtube_audio'] != undefined && setting['youtube_audio'] == 'off') {
                    videoAttr.muted = true;
                } else {
                    videoAttr.muted = false;
                }
            }

            console.log(videoAttr);
            var promise = videoAttr.play();
            if (promise !== undefined) {
                promise.then(function() {

                    videoAttr.onplay = function() {
                            //console.log('Yay, playing');
                        },
                        videoAttr.onerror = function(errorCode, description) {
                            console.log(errorCode, description);
                            showNext();
                        }
                    videoAttr.onended = function() {
                        //console.log("The video has ended");
                        showNext();
                    };




                }).catch(error => {
                    console.log(error);
                    showNext();
                });
            }




            /*setTimeout(function(){
            showNext();
            },5000);*/

        }

        var setedIntervalYoutube = "";
        var player = null;

        function showYoutube(duration) {


            var muted = 0;
            var setting = getCookie(fontsettingCookes);
            if (setting.length > 0) {
                setting = JSON.parse(setting);
                if (setting['youtube_audio'] != undefined && setting['youtube_audio'] == 'off') {
                    muted = 1;
                }
            }


            $(".media" + currKey).show();
            if ((typeof YT !== "undefined") && YT && YT.Player) {
                clearInterval(setedIntervalYoutube);
                var vedioKey = $(".media" + currKey).attr("data-url");
                player = new YT.Player("media" + currKey, {
                    width: '100%',
                    height: '100%',
                    videoId: vedioKey,
                    playerVars: {
                        'autoplay': 1, // Autoplay the video
                        'loop': 1, // Loop the video
                        'controls': 0, // Hide video controls
                        'showinfo': 0, // Hide video title and uploader info
                        'rel': 0, // Disable related videos at the end
                        'mute': muted // Mute the video
                    },
                    events: {
                        onReady: function(event) {
                            event.target.playVideo();
                        },
                        onStateChange: function(event) {
                            if (event.data == YT.PlayerState.ENDED) {
                                event.target.playVideo();
                            } else {
                                if (event.data === 0) {
                                    currKey++;
                                    player.destroy();
                                    showNext();
                                }
                            }

                        }
                    }
                });
                if (duration > 0) {
                    setTimeout(function() {
                        currKey++;
                        player.destroy();
                        showNext();
                    }, duration);
                }

            }
        }


        function showNext() {
            if (mediaCount <= currKey) {
                currKey = 0;
            }
            var mediaType = $(".media" + currKey).attr("data-type");
            $(".media-container").hide();
            if (mediaType == 'VIDEO') {
                showVideo();
            } else if (mediaType == 'IMAGE') {
                showImage();
            } else {
                var duration = $(".media" + currKey).attr("duration");

                setedIntervalYoutube = setInterval(function() {
                    showYoutube(duration)
                }, 2000);
            }
        }

        showNext();



        function setVideoHeight() {


            setTimeout(function() {

                var windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
                var myDiv = document.getElementsByClassName('marq_div');
                var marq_div = myDiv.length;
                var header = $(".tracker_box_header").height();

                var append_html = $(".append_html").height();
                var topSection = header + append_html;
                if (marq_div > 0) {

                    topSection += marq_div;

                }
                $("#tracker_box").css("height", topSection);
                $(".media-attach").css("height", windowHeight - topSection);
            }, 6000);




        }
        var zoomElement = document.getElementById('zoomElement');
        var zoomedIn = false;

        function updateZoom() {

            var windowHeight = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            var windowWidth = window.innerWidth;

            //  windowWidth = Math.round(windowWidth * 0.7);
            //  windowHeight = Math.round(windowHeight * 0.7);
            $(zoomElement).css("z-index", "9999999");
            $(zoomElement).css("display", "flex");
            $(zoomElement).css("opacity", "1");
            $(zoomElement).css("height", windowHeight);
            $(zoomElement).css("width", windowWidth);
            var zoomLevel = zoomedIn ? 2 : 1;
            //zoomElement.style.transform = 'scale(' + zoomLevel + ')';
            zoomedIn = !zoomedIn;
        }

        function deleteMp3FilesFromLocalStorage() {

            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                var fileName = localStorage.getItem(key)
                if (fileName.indexOf('.mp3') !== -1 || fileName.indexOf('.wav') !== -1) {
                    localStorage.removeItem(key);
                }
            }
        }
        deleteMp3FilesFromLocalStorage();
        
    });
    
                
   
</script>