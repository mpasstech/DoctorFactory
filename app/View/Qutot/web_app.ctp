
<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>


    <meta name="author" content="mengage">

    <?php

    $tmp[] = @$doctor_data['city_name'];
    $tmp[] = @$doctor_data['country_name'];
    $tmp[] = @$doctor_data['state_name'];
    $tmp[] = @$doctor_data['doctor_category'];
    $tmp[] = @$doctor_data['name'];
    $keys = implode(',',$tmp);

    echo '<meta name="keywords" content="'.$keys.'">';

    ?>

    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';
        var app_category = '<?php echo $category_name; ?>';
    </script>
    <link rel="manifest" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/manifest.json?'.date('his'),true);?>" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-72x72.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-96x96.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-128x128.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-144x144.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-152x152.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-192x192.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-384x384.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-512x512.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#0952EE" />
    <meta name="theme-color" content="#0952EE" />
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','qutot.js?'.date('his'),'html5gallery.js','jquery-confirm.min.js','dropzone.min.js','typeahead.bundle.js','bootstrap-tagsinput.min.js','firebase-app.js','firebase-messaging.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','qutot.css?'.date('his'),'font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css' ),array("media"=>'all')); ?>


    <script src="<?php echo Router::url('/app.js',true); ?>"></script>



    <title><?php echo $doctor_data['name']; ?></title>
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
</style>
</head>

<body data-ti="<?php echo $thin_app_id; ?>"  moq-hospital="<?php echo ($this->AppAdmin->check_app_enable_permission($thin_app_id,"MOQ_HOSPITAL"))?'YES':'NO'; ?>" data-sf ="<?php echo $doctor_data['allow_only_mobile_number_booking']; ?>" id="body">
<?php if($doctor_data['doctor_id']=='1619'){ ?>
    <button style="display: block; position: absolute;z-index: 999" id="pushButton">Push Btn </button>
<?php } ?>


<header>
    <div class="row block detail_main_box" >
        <div class="row top_header_row">
            <div class="col-1 top_back_box" style="display: none;">
                <?php
                if($wa=='y'){
                    $back_url =  Router::url('/',true);
                }else{
                    if($department_search=='NO'){
                        $back_url =  Router::url('/doctor/department/'.base64_encode($doctor_data['thin_app_id']),true);
                    }else{
                        $back_url =  Router::url('/doctor/search_doctor/'.base64_encode($doctor_data['appointment_category_id']).'/'.base64_encode($doctor_data['thin_app_id']),true);
                    }
                }
                ?>
                <button type="button" id="back_button"  class="back_button" data-href="<?php echo $back_url; ?>" class=""><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
            </div>
            <div class="col-3 top_photo_box">
                <img src="<?php echo $doctor_data['logo']; ?>" />
            </div>
            <div class="col-9" style="padding-right: 0px;padding-left: 0px;display: contents;">
                <div class="doctor-detail-container" >
                    <?php if($thin_app_id=="821"){ ?>
                        <h1 class="doctor-name">Ck Birla Hospital</h1>
                    <?php }else{ ?>
                        <h1 class="doctor-name"><?php echo mb_strimwidth($doctor_data['name'], 0, 25, '...'); ?></h1>
                    <?php } ?>
                    <span id="top_sub_title">Dashboard</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row tab_btn_div">

        <?php $showButton = ($thin_app_id=='821')?false:true; ?>

        <?php if($thin_app_id=="821"){ ?>
            <div class="col-12" style="margin: 0; padding: 0;">
                <img src="https://s3-ap-south-1.amazonaws.com/mengage/banner/821_banner5.png" style="width: 100%; height: 200px;">
            </div>
        <?php }else{ ?>
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
                                <label class="dash_consulting_lbl"><i class="fa fa-handshake-o" aria-hidden="true"></i> <?php echo $doctor_data['service_name']; ?></label>
                                <br>
                                <label class=""><i class="fa fa-clock-o" aria-hidden="true"></i> <b><?php echo $doctor_data['from_time']; ?></b> To  <b><?php echo $doctor_data['to_time']; ?></b> </label>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>


        <?php if($showButton){ ?>
            <div class="icon-div-btn col-4">
                <button class="tab-info" data-title="About" data-id="info"><i class="fa fa-info-circle"></i>About</button>
            </div>
        <?php } ?>


        <div class="icon-div-btn col-4">
            <button class="tab-appointment" data-title="Book Token" id="app_btn" data-id="appointment">
                <i class="fa fa-ticket" aria-hidden="true"></i>Schedule Token
            </button>
        </div>

        <?php if($showButton){ ?>
            <div class="icon-div-btn col-4" id="dash_my_appointment" style="display: none;">
                <button class=" tab-blog" data-id="my_appointment" data-title="My Appointment"><i class="fa fa-history" aria-hidden="true"></i>My Token</button>
            </div>
        <?php } ?>





        <?php if($showButton && $category_name!='TEMPLE' && !$this->AppAdmin->check_app_enable_permission($thin_app_id,"MOQ_HOSPITAL")){ ?>
            <div class="icon-div-btn col-4" id="dash_medical_record" style="display: none;">
                <button class=" tab-blog" data-id="medical_record" data-title="Medical Record"><i class="fa fa-file-image-o" aria-hidden="true"></i>Medical Records</button>
            </div>
        <?php } ?>



        <div class="icon-div-btn col-4" style="display: none;">
            <button onclick="" data-id="doctor_apps" ><i class="fa fa-globe" aria-hidden="true"></i>QuToT</button>
        </div>

        <div class="icon-div-btn col-4" >
            <button onclick="" data-id="walkin_token" ><i class="fa fa-ticket" aria-hidden="true"></i>Walk-In Token</button>
        </div>


        <div class="icon-div-btn col-4" id="dash_track_token" style="display: none;" >
            <button class=" tab-blog"  data-id="track_token" data-title="Track Token"><i class="fa fa-history" aria-hidden="true"></i>Track Token</button>
        </div>

        <div class="icon-div-btn col-4">
            <button onclick="" data-id="doctor_apps" ><i class="fa fa-globe" aria-hidden="true"></i>QuToTapps</button>
        </div>



        <?php if($category_name!='TEMPLE' && !empty($doctor_data['package_name']) ){ ?>
            <div style="display: none;" class="icon-div-btn col-4">
                <?php
                $package_name = $doctor_data['package_name'];
                $url ="https://play.google.com/store/apps/details?id=$package_name&hl=en";
                ?>
                <button  onclick="window.open('<?php echo $url; ?>','_blank');" data-id="android_app" ><i class="fa fa-android" aria-hidden="true"></i>Android App</button>
            </div>
        <?php } ?>



    </div>
</header>

<div class="main_container container-fluid contain" data-c = "<?php echo $channel_id; ?>" data-ti = "<?php echo base64_encode($doctor_data['thin_app_id']); ?>" data-di = "<?php echo $doctor_id; ?>" data-online="<?php echo $doctor_data['is_online_consulting']; ?>" data-offline="<?php echo $doctor_data['is_offline_consulting']; ?>" data-chat="<?php echo $doctor_data['is_chat_consulting']; ?>" data-audio="<?php echo $doctor_data['is_audio_consulting']; ?>" >
    <div class="col-12"><button class="go_to_dash"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Go To Dashboard</button></div>
    <div class="row tab-content" id="info" style="display: none;"   >
        <div class="col-md-12  info-cont tab-information-container">
            <div class="row doctor_img_sec">
                <div class="col-4">
                    <?php if(!empty($doctor_data['profile_photo'])){ ?>
                        <img src="<?php echo $doctor_data['profile_photo']; ?>"  />
                    <?php }else{ ?>
                        <img src="<?php echo Router::url("/images/channel-icon.png",true); ?>" />
                    <?php } ?>
                </div>
                <div class="col-8">
                    <?php if(!empty($doctor_data['doctor_category'])){ ?>
                        <label class="label_title_top">
                            <?php if($category_name=='TEMPLE'){ ?>
                                <i class="fa fa-flag" aria-hidden="true"></i>
                            <?php }else{ ?>
                                <i class="fa fa-heartbeat"></i>
                                <?php echo '<span>'.$doctor_data['doctor_category'].'</span>'; ?>
                            <?php } ?>
                            <br>
                        </label>
                        <?php if(!empty($doctor_data['education'])){ ?>
                            <label class="edu_top_label">
                                <?php echo $doctor_data['education']; ?>
                            </label>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>

            <div class="tab-doc-name">
                <h1 class="info-doctor-name"><?php echo $doctor_data['name']; ?></h1>
                <?php if(!empty($doctor_data['education'])){ ?>
                    <label class="label_title"
                    <i class="fa fa-graduation-cap"></i> <?php echo $doctor_data['education']; ?>
                    <?php if(!empty($doctor_data['doctor_category'])){ echo !empty($doctor_data['education'])?"<br>":''; ?>
                        <?php if($category_name!='TEMPLE'){ ?>
                            <?php echo '<span>'.$doctor_data['doctor_category'].'</span>'; ?>
                        <?php } ?>
                    <?php } ?>
                    </label>
                <?php } ?>
            </div>

            <?php if($category_name!='TEMPLE'){ ?>

                <?php if(!empty($doctor_data['experience'])){ ?>
                    <div class="info-detail">
                        <i class="fa fa-briefcase"></i> <?php
                        $exp =   $doctor_data['experience'];
                        $exp = explode('.',$exp);
                        $year = $month = '';
                        if(!empty($exp)){
                            if($exp[0] > 0){
                                $year = ($exp[0] > 1)?$exp[0]." Years":$exp[0]." Year";
                            }
                            if(@$exp[1] > 0){
                                $month = ($exp[1] > 1)?$exp[1]." Months":$exp[1]." Month";
                            }
                        }
                        echo $year ." ".$month;
                        ?>
                    </div>
                <?php } ?>
                <?php if(!empty($doctor_data['registration_number'])){ ?>
                    <div class="info-detail">
                        <i class="fa fa-registered" aria-hidden="true"></i> <?php echo $doctor_data['registration_number']; ?>
                    </div>
                <?php } ?>
                <?php if(!empty($doctor_data['email'])){ ?>
                    <div class="info-detail">
                        <i class="fa fa-envelope"></i> <label class="info-div-icon">&nbsp;<a href="mailto:thevishwajeet@gmail.com"><?php echo $doctor_data['email']; ?></a></label>
                    </div>
                <?php } ?>


                <?php if(!empty($doctor_data['website_url'])){ ?>
                    <div class="info-detail"><i class="fa fa-globe" aria-hidden="true"></i> <label class="info-div-icon">&nbsp;<a href="<?php echo $doctor_data['website_url']; ?>" ><?php echo $doctor_data['website_url']; ?></a></label></div>
                <?php } ?>

            <?php }  ?>



            <?php  $address = @$appointment_data['address_list'][0]['address'];   ?>


            <?php if(!empty($address)){ ?>
                <div class="info-detail">
                    <i class="fa fa-map-marker"></i>
                    <label class="info-div-icon">&nbsp;<?php echo $address ?></label>
                </div>
            <?php } ?>
            <?php if(!empty($doctor_data['description'])){ ?>
                <div class="doctor-about about_section">
                    <?php echo $doctor_data['description']; ?>
                </div>
            <?php } ?>



        </div>
    </div>
    <div class="tab-content" id="appointment" style="display: none;" >
        <div style="display: none;" class="row availiblity-detail">
            <?php $time_string = $this->AppAdmin->doctorCurrentAvailableTime($doctor_data['doctor_id']); ?>
            <a class="chk_aval_link" href="javascript:void(0);" data-html="true" title="Doctor Availability List" data-poload="<?php echo Router::url('/doctor/check_availability/',true).base64_encode($doctor_data['doctor_id']); ?>"><i class="fa fa-clock-o" aria-hidden="true"></i> View Availability</a>
            <?php if(!empty($appointment_data['appointment_slot'])){ ?>
                <span class="avl_lbl"><i class="fa fa-check" aria-hidden="true"></i> Available</span>
            <?php }else{ ?>
                <span class="not_avl_lbl"><i class="fa fa-times" aria-hidden="true"></i>  Not Available</span>
            <?php } ?>

        </div>



        <?php if(!empty($appointment_data['address_list'])){ ?>
            <ul id="addressSlider" class="col-12" >

                <h5 class="inner_label_header">Location</h5>
                <span class="alert_span">Please tab on arrow <i class="fa fa-angle-right right_arrow_nav"></i> for select location </span>
                <?php foreach($appointment_data['address_list'] as $key => $address) { ?>

                    <li class="address_box <?php echo ($key == 0)?'selected_address':''; ?>" data-id="<?php echo $address['address_id']; ?>">
                        <span style="width: 0%;"> <i class="fa fa-map-marker address_icon"></i></span>
                        <span style="width: 95%;">
                                    <label class="address_text"><?php echo $address['address']; ?></label>
                                     <p class="address_label" data-show="<?php echo $address['from_time'] .' To '.$address['to_time']; ?>"><i class="fa fa-clock-o time_icon" aria-hidden="true"></i> <?php echo $address['from_time'] .' - '.$address['to_time']; ?></p>
                                </span>
                        <span style="width: 5%;">

                                    <a href="javscript:void(0);"><i class="fa fa-angle-right right_arrow_nav"></i></a>

                                </span>



                    </li>
                <?php }  ?>
            </ul>

            <ul id="date_slider" style="display: none;"  class="">
                <h5 class="inner_label_header">
                    <a id="back_to_location" href="javascript:void(0);" data-hide="#date_slider" data-target="#addressSlider" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                    <?php if($category_name=='TEMPLE'){ ?>
                        Token Booking Date
                    <?php }else{ ?>
                        Appointment Date
                    <?php } ?>
                </h5>
                <span class="alert_span">Please tab on date for select date </span>
                <?php  $counter = 0; if(!empty($appointment_data['day_list'])){ foreach($appointment_data['day_list'] as $key => $val) {
                    $date = explode("##",$val['date']); ?>
                    <li class="inner_box">
                        <div class="card">
                            <div data-show-date = "<?php echo $date[0].'-'.($date[2]).'-'.$date[3]; ?> " data-date = "<?php echo $date[3].'-'.($date[2]).'-'.$date[0]; ?> " class="date_box <?php echo (date('d')==$date[0] && date('M')==$date[2])?'selected_date':''; ?>">
                                <span class="day"><?php echo $date[1]; ?></span>
                                <span class="date"><?php echo $date[0]; ?></span>
                                <span class="month"><?php echo $date[2];?></span>
                            </div>
                        </div>
                    </li>
                <?php } } ?>
            </ul>

            <div id="slot_box" style="display: none;" class="slot_box_div row">
                <h5 class="inner_label_header">
                    <a data-hide="#slot_box" data-target="#date_slider" href="javascript:void(0);"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                    Time/Token
                </h5>
                <span class="alert_span">Please tab time slot for select Token/Time </span>
                <div class="col-12 appoinment-slot-holder" style="height: <?php echo ($doctor_data['allow_emergency_appointment']=='YES')?'370px':'450px';  ?>;">
                     <span style="display: none;" class="doctor_loader">
                        <img  src="<?php echo Router::url('/images/doctor_web_loader.gif',true); ?>" >
                     </span>
                    <div class="append_slots">
                        <?php
                        if(!empty($appointment_data['appointment_slot'])){
                            foreach($appointment_data['appointment_slot'] as $key => $val) {
                                $label = '';
                                if($val['status'] == 'AVAILABLE'){
                                    $label = 'available';
                                }else if($val['status'] == 'BOOKED'){
                                    $label = 'booked';
                                }else if($val['status'] == 'BLOCKED'){
                                    $label = 'not-available';
                                }
                                if($val['custom_slot']=='NO'){
                                    ?>
                                    <div class="appointment-slot <?php echo $label; ?>">
                                        <span class="appointment-token"><?php echo $val['queue_number']; ?></span>
                                        <span class="appointment-time"><?php echo $val['slot']; ?></span>
                                    </div>
                                <?php }}}else{ ?>
                            <h2 class="doc_not_available">Doctor will open appointment slots shortly.</h2>
                        <?php  } ?>
                    </div>
                </div>

                <?php if($doctor_data['allow_emergency_appointment']=='YES'){ ?>
                    <div style="display: none;" class="col-12 emergency_row">
                        <div class="row">
                            <label class="emergency_msg">
                                Above slots are booked online as a first come first serve basis. In case you wish to seek an appointment on a specific time, please select the emergency appointment below. The charges for emergency appointment will be extra as applicable.
                            </label>
                            <button data-fee ="<?php echo $doctor_data['emergency_appointment_fee'];  ?>" style="display:block;" id="emergency_btn">Emergency Appointment</button>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php }else{ ?>
            <h2 class="doc_not_available">Doctor will open appointment slots shortly.</h2>
        <?php } ?>



    </div>
    <div class="tab-content" id="my_appointment"  style="display: none;"  >
        <div class="row blog-container append_appointments">
        </div>
        <div class="load_more" id="load_more_appointment_btn"><button>Load More</button></div>
    </div>
    <div class="tab-content" id="medical_record"  style="display: none;"  >
        <div class="col-12 folder_drp_container">
            <select class="form-control" id="folder_drp">

            </select>
        </div>

        <div class="row blog-container append_medical_records">

        </div>
        <div class="load_more" id="load_more_record" style="display: none !important;"><button>Load More</button></div>
        <button type="button" class="btn btn-success float_btn add_new_file"><i class="fa fa-plus"></i> </button>
        <button type="button" class="btn btn-info refresh_btn"><i class="fa fa-refresh"></i> </button>
    </div>
    <div class="tab-content" id="walkin_token"  style="display: none;" data-src="<?php echo Router::url('/qutot/qutot_user_booking/',true).base64_encode($thin_app_id)."/?il=yes"; ?>">
        <div class="col-12 folder_drp_container">

        </div>
    </div>




    <table class="table_bottom">

        <?php if(!empty($this->request->query['pm'])){ $pm = $this->request->query['pm']; ?>
            <tr class="otp_row" style="display: none;width: 100%;">
                <td style="display: block;" class="otp_td" >
                    <table style="width: 100%;" >
                        <tr style="background: #31a209; border-bottom: 2px solid #fff;">
                            <td style="color:#fff;width:75%;text-align: center; padding: 5px 5px; text-transform: uppercase; " class="otp_text_td">
                                For view more info please
                            </td>
                            <td style="width:25%; text-align: center; padding: 5px 5px;" class="otp_button_td">
                                <button style="width:100%;font-size: 0.8rem; padding: 2px 6px;" data-key="<?php echo $pm; ?>" class="btn btn-xs btn-danger verify_tracker_number">Generate OTP</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php } ?>

        <tr>
            <td class="tracker_row">
            </td>
        </tr>
        <tr>
            <td>
                <?php

                    $ban_list  = $this->AppAdmin->getAddBanner();
                    $img_path =$ban_list[0]['path'];
                    $ban_list=array();
                    $ban_list[] ='Select install Webapp or Weblink on Dashboard or Home Page';
                    $ban_list[]= 'No download hassle';
                    $ban_list[]= 'No memory consumption -Still remain connected on single click';
                    $ban_list[]= 'Pre-book token in future any time in fewer clicks';
                    $ban_list[]= 'Track your token live on single click';

                ?>
                <?php if($show_add_banner && !empty($ban_list)){ ?>
                    <div class="row add_container">
                        <div id="home_advertisement" class="carousel slide" data-ride="carousel" >

                            <ol class="carousel-indicators">
                                <?php foreach ($ban_list as $key => $banner) {   ?>
                                    <li data-target="#carouselExampleIndicators" data-slide-to="<?php echo $key; ?>" class="<?php echo ($key==0)?'active':''; ?>""></li>

                                <?php } ?>
                            </ol>


                            <div class="carousel-inner">
                                <?php foreach ($ban_list as $key => $banner) {   ?>
                                    <div class="carousel-item <?php echo ($key==0)?'active':''; ?>">
                                        <table>
                                            <tr>
                                                <td style="width: 20%; padding: 0.3rem;">
                                                    <img src="<?php echo $img_path; ?>" class="d-block" alt="...">
                                                </td>
                                                <td style="width: 80%;padding: 0.2rem;">
                                                    <h6><?php echo $banner; ?></h6>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </td>
        </tr>
    </table>


</div>
<div class="modal fade" id="upload_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

    <div class="modal-dialog modal-sm" style="margin-top: 70px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Upload Prescription</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="padding: 0px;">
                <div class="form-group">
                    <div class="col-sm-12">
                        <form id="myId" class="dropzone" style="padding: 0px 0px 50px 0px !important; border: 0 !important;">
                            <input type="hidden" name="m" id="m" >
                            <input type="hidden" name="t" id="t">
                            <input type="hidden" name="d" id="d">

                            <div class="fallback col-12">
                                <input name="file" type="file" multiple placeholder="Click here to browse file" />
                            </div>
                            <div class="dz-message" data-dz-message><span class="upload_btn_span"><i class="fa fa-file-text-o" aria-hidden="true"></i> Upload Prescription</span></div>

                            <div class="col-sm-12" style="position: absolute;bottom: 0; width: 90%;float: left;">
                                <label style="display: block;">&nbsp;</label>
                                <button type="button" style="width: 100%; display: none;" class="btn btn-default" id="upload_pre_btn"><i class="fa fa-upload"></i> Upload</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>

</div>
<div id="fade_box" style="display: none;">

    <div class="row top_header_row">
        <div class="col-3 top_photo_box">
            <img src="<?php echo $doctor_data['logo']; ?>" />
        </div>
        <div class="col-9" style="padding-right: 0px;padding-left: 0px;display: contents;">
        <div class="doctor-detail-container" >
            <?php if($thin_app_id=="821"){ ?>
                <h1 class="doctor-name">Ck Birla Hospital</h1>
            <?php }else{ ?>
                <h1 class="doctor-name"><?php echo $doctor_data['name']; ?></h1>
            <?php } ?>
            <span style="font-size: 0.9rem; float: left;">Install Web App</span>
        </div>
    </div>
    </div

    <div class="col-12" style="float: left;">
        <p>
            नोट: टोकन को ट्रैक करने के लिए, आप ऊपर दिए गए लिंक को सेव कर सकते हैं जिसे बाद में कभी भी डिलीट किया जा सकता है, यदि आवश्यक न हो। इस लिंक को सहेजने से आपको भविष्य में घर से टोकन प्रीबुक करने में मदद मिलेगी।
            <br>
            सेविंग लिंक बेहद सरल है और शायद ही कोई समय लेता है और शायद ही आपके मोबाइल में किसी भी मेमोरी का उपभोग करता है। वही समय आपको डॉक्टरों और अस्पतालों तक पहुंचने में आसान मदद करता है।
        </p>
        <p>
            Note : To track token, you can save above link which could be deleted anytime later on, if not needed. Saving this link will help you in future to prebook token from home.
            <br>
            Saving link is extremely simple and hardly takes any time and hardly consumes any memory in your mobile. Same time helps you easy access to reach doctors and hospitals.
        </p>



        <button class="col-12" style="display:none; border: 1px solid #ececec;" id="btnSaveDoctor" data-id="web_app">
            <img style="float: left;border: 1px solid #d2d2d2;border-radius: 50%;" src="<?php echo Router::url('/add_home_screen/'.$doctor_data['doctor_id'].'/',true);?>icons/icon-72x72.png" alt="image">
            <span>
                Install Web App
                <br>
                ( Android/iOs )
            </span>
            <i class="fa fa-long-arrow-left navigation-arrow-left" aria-hidden="true"></i>
        </button>


    </div>
</div>
<div id="overlay_loader" class="overlay">
    <div class="overlay__inner">
        <div class="overlay__content">
            <span class="spinner"></span>
           <h2 style="width: 100%;float: left;text-align: center;color: #fff;">Welcome</h2>
        </div>
    </div>
</div>
<style>
    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background-image: linear-gradient(#013ec4, #fff);
        z-index: 999;
    }

    .overlay__inner {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: absolute;
    }

    .overlay__content {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .spinner {
        width: 75px;
        height: 75px;
        display: inline-block;
        border-width: 2px;
        border-color: rgba(255, 255, 255, 0.05);
        border-top-color: #fff;
        animation: spin 1s infinite linear;
        border-radius: 100%;
        border-style: solid;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<script>

    Dropzone.autoDiscover = false;

    $(function () {

        var baseurl ="<?php echo Router::url('/',true);?>";
            setTimeout(function(){
            $("#overlay_loader").fadeOut(800);
        },1000);

        var slider_interval = 4500;
        $('.carousel').carousel({
            interval: slider_interval
        });

        $('.inner_label_header a').click(function() {
            $($(this).attr("data-hide")).hide();
            $($(this).attr("data-target")).show();
        });

        $('.carousel-item').click(function() {
            //window.open("https://api.whatsapp.com/send?phone=918955004049",'_blank');
        });



        window.onunload = refreshParent;
        function refreshParent() {
            window.opener.location.reload();
        }

        $('#addressSlider li').click(function() {
            $(this).closest("ul").find("li").removeClass("selected_address");
            $(this).addClass("selected_address");
            $(this).closest("ul").hide();
            $("#date_slider").show();
        });



        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };

        $("#row_content").data('key',"<?php echo base64_encode(json_encode(($doctor_data))); ?>");
        $("#body").data('key',<?php echo json_encode(($success)); ?>);
        if($("#body").data('key')){
            var appointment = $("#body").data('key');
            if(appointment.id > 0){
                swal({
                    type:appointment.dialog_type,
                    title: appointment.title,
                    html: "<ul class='success_ul'>"+appointment.message+"</ul>",
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    customClass:"success-box",
                    showConfirmButton: true
                }).then(function (result) {
                    var apk_url = "<?php echo $doctor_data['apk_url']; ?>";

                    if (apk_url.search('http://') == -1){
                        apk_url = 'https://'+apk_url;
                    }
                    var ua = navigator.userAgent.toLowerCase();
                    var isAndroid = ua.indexOf("android") > -1;
                    var isAndroid = true;
                    if(isAndroid && app_category!='TEMPLE' && apk_url !='' && appointment.dialog_type=='success'){
                        swal({
                            type:'info',
                            title: 'Token Confirmed',
                            html: "<div class='download_box'><p>The booking confirmation message has been sent to your mobile number.</p><div id='diloag_add_box'></div></div>",
                            showCancelButton: false,
                            confirmButtonText: 'Thankyou',
                            customClass:"success-box",
                            showConfirmButton: true
                        }).then(function (result) {
                            window.close();
                            var url = baseUrl+'qutot/web_app/?t='+(appointment.doctor_id);
                            window.location.replace(url);
                            window.location = baseUrl+'qutot/web_app/?t='+(appointment.doctor_id);
                        });
                        if($(".add_container").length > 0){
                            setTimeout(function () {
                                var string = $(".add_container").html();
                                string = string.replace("home_advertisement", "dialog_advertisement");
                                $("#diloag_add_box").html($(string));
                                $('#dialog_advertisement').carousel({
                                    interval: slider_interval
                                });
                            },100);
                        }
                    }else{
                        var url = baseUrl+'doctor/web_app/?t='+(appointment.doctor_id);
                        window.location.replace(url);
                        window.location = baseUrl+'doctor/web_app/?t='+(appointment.doctor_id);
                    }
                });
            }
        }



        if(is_desktop()){
            $("body").addClass("desktop_layout");
        }


        $('.read_more_about').hover(function() {
            $('.available-tool').hide();
            var e = $(this);
            e.off('hover');
            e.popover({
                content: e.data('poload'),
                html: true,
                placement: 'right',
            }).popover('show');

        });


        $('.chk_aval_link').click(function() {
            //$('.about-tool').hide();
            var e = $(this);
            e.off('hover');
            $.get(e.data('poload'), function(d) {
                e.popover({
                    template: d,
                    html: true,
                    placement: 'bottom'
                }).popover('show');
            });


        });

        $('.back_button').click(function() {
            window.location.href = $(this).attr('data-href');
        });


        $(document).click(function() {
            //$('.about-tool').hide();
            $(".tooltip_container").remove();

        });

        $(document).click(function() {
            //$('.about-tool').hide();
            $(".tooltip_container").remove();

        });










        function isSafari(){
            var ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf('safari') != -1) {
                if (ua.indexOf('chrome') > -1) {
                    return false;
                } else {
                    return true;
                }
            }
        }

        if(isSafari()){
            $("[data-id='android_app']").closest(".icon-div-btn").hide();
        }








        function is_desktop(){
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                return false;
            }return true;
        }








    });
</script>
</body>
</html>