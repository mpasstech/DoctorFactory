<html>
<head id ="row_content" class="row_content" >
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">


    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    
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
        window.doctor_id = "<?php echo $thin_app_id; ?>";


    </script>
    <link rel="icon" type="image/x-icon" href="https://www.mpasscheckin.com/assets/images/icons/favicon.ico">
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
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','bootstrap-datepicker.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','aes.js','flash.js?123','doctor.js?'.date('hiss'),'html5gallery.js','jquery-confirm.min.js','dropzone.min.js','typeahead.bundle.js','bootstrap-tagsinput.min.js','wickedpicker.min.js','ckeditor/ckeditor.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','bootstrap-datetimepicker.min.css','doctor.css?'.date('his'),'font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css','wickedpicker.min.css' ),array("media"=>'all')); ?>


 


	
 	<?php if (@$_GET['wa']=='y') { echo $this->Html->script(array('firebase-app.js','firebase-messaging.js')); ?>
         <script src="<?php echo Router::url('/app.js?'.date('ymdhis'),true);?>"></script>        
    <?php } ?>

    
    <title><?php echo $doctor_data['name']; ?></title>
    <style>

        header{
            position: fixed;
            width: 100%;
            z-index: 99999999999999;
            display: grid;
        }

        #dashboard_box{
            margin-top: 16%;
            float: left;
            width: 100%;
            display: block;
        }


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


        .upload_btn_span{
            border: 1px solid;
            padding: 10px;
            border-radius: 4px;
            background: #1b8c1b;
            color: #fff;

        }

        .append_health_tip, .bottom_ul{
            margin: 0;
            padding: 0;
            width: 97%;
            float: left;
        }
        .append_health_tip li table td{
            padding: 5px !important;
        }
        .append_health_tip li{
            list-style: none;
            width: 100%;
            padding: 2px;
            float: left;

            margin: 10px 5px;
        }

        .bottom_ul li{
            list-style: none;
            width: 30%;
            float: left;
            padding: 0;
            text-align: center;
        }
        .append_health_tip li img{
            width: 100%;
            height: 100px;
        }


        .append_health_tip li label,
        .append_health_tip li ul{
            float: left;

        }

        .append_health_tip li .datespan{
            font-size: 0.7rem;
            text-align: center;
            float: right;
            color:green;
            font-weight:600;
        }


        #health_tip .blog-container{

            background:#f0efef;

        }
        .append_health_tip li .title{
            font-size: 1rem;
            font-weight: 600;

        }

        .append_health_tip .bootom_tr{
            text-align: center;
            color: #7781ac;
            font-size: 0.8rem !important;
            font-size: 500
        }
        .append_health_tip li .description{
            font-weight: 400;
            font-size: 0.8rem ;
        }

        .append_health_tip li .description p:first-child{
            margin-bottom: 0px !important;
        }

        .append_health_tip li .description *{
            font-size: 0.9rem !important;
            color: #7a7a7a !important;
            font-weight: 400 !important;
        }

        .other_doctor_img{
            width: 80px;
            height: 80px;
            border-radius: 60%;
            border: 2px solid #c4c4c4;
            padding: 3px;
            float: left;
        }
        #other_doctors label{
            float: left;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 0px 10px;
            width: 70%;
        }

        .list_container_div{

            border-bottom: 1px solid #d7d7d7;

            padding: 10px 5px;
        }
        #other_doctors span{
                display: block;width: 100%;
            font-size: 1rem;
        }

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


        .login_box{
            padding: 40px 30px;
        }
        .login_box h3, .login_box p{
            text-align: center;
        }
        .login_box label{
            font-weight: 600;
            padding: 5px 0px;
        }
        .login_box p{
            border-bottom: 3px solid #013ec442;
            padding: 8px;
            font-size: 0.8rem;
        }

        #login_btn{
            width: 100%;
        }

        .loader_image{
            float: right;
            position: relative;
            right: 10px;
            width: 20px;
            height: 20px;
            top: -30px;
        }
        .login_screen_img{
            display: block;
            margin: 0 auto;
            width: 120px;
            height: 120px;
            border: 1px solid #f1f1f1;
            border-radius: 58px;
            padding: 4px;
        }

    </style>
    <style>
        .loader_all {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 45% auto !important;
        }

        .overlay_all {
            position: fixed;
            background: #fff;
            width: 100%;
            height: 100%;
            text-align: center;
            z-index: 11111;
            opacity: 0.7;
            margin: 0;
            left: 0;
            top: 0;
        }

        /* Safari */
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<div id="loaderDiv" style="display:none;" class="overlay_all">
    <div class="loader_all"></div>
</div>



<body data-di="<?php echo $doctor_id; ?>" data-ti="<?php echo $thin_app_id; ?>"  moq-hospital="<?php echo ($this->AppAdmin->check_app_enable_permission($thin_app_id,"MOQ_HOSPITAL"))?'YES':'NO'; ?>" data-sf ="<?php echo $doctor_data['allow_only_mobile_number_booking']; ?>" id="body">
<header>
    <div class="row block detail_main_box" >
        <div class="row top_header_row">
           
            <div class="col-3 top_photo_box">
                <div class="go_to_dash" style="display: none;">
                    <button class=""><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                </div>

                <img src="<?php echo $doctor_data['logo']; ?>" />
            </div>
            <div class="col-9" style="padding:0;">
                <div class="doctor-detail-container" >
                    
                <span style="float: left;display:block;width:90%;">
                    <h1 class="doctor-name"><?php echo mb_strimwidth($doctor_data['name'], 0, 25, '...'); ?></h1>
                    <span id="top_sub_title">Login</span>
                </span>


                    <span id="setting_menu_span" style="float: left;display:block;width:10%;">
                       
                    </span>
                    

               
                   
                </div>

                


            </div>
           
            
        </div>
    </div>
</header>

<div id="dashboard_box" class="login_box">
    <div class="row">
        <div class="col-12">

            <h4 style="text-align: center;width: 100%; font-weight: 500;">Welcome To <span style="font-size: 1.8rem;font-weight: 600; display: block;color: #3cb40a;width: 100%;"><?php echo $doctor_data['web_app_name']; ?></span></h4>


            <img class="login_screen_img" src="<?php echo $doctor_data['logo']; ?>" >

            <p>Please enter mobile number to book token</p>
            <div class="form-group">
                <label>Enter 10 Digit Mobile Number</label>
                <input class="form-control" type="number" id="login_mobile">
                <img style="display: none;" class="loader_image" src="<?php echo Router::url('/img/loader.gif',true); ?>">
            </div>
            <div class="form-group login_otp_box" style="display: none;" >
                <label>Enter OTP</label>
                <input type="number" class="form-control" id="login_otp">
                <br>
                <button class="btn btn-success" id="login_btn" type="button">Login</button>
            </div>

        </div>


    </div>
</div>



<div class="modal fade" id="upload_file" tabindex="-2" style="z-index: 999999;" role="dialog" aria-labelledby="myModalLabel">

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
                                <input name="file" type="file" accept="image/*" capture placeholder="Click here to browse file" />
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

    .app_status_lbl{
        margin: 0;
        padding: 6px 6px;
        background: #4aaf27;
        color: #fff;
        border-radius: 0px 0px 0px 34px;
        font-size: 0.8rem;
        width: 100%;
        text-align: center;
    }
    .app_status_lbl i{
        display: block;
        margin: 0 auto;
    }
    .patient_name_lbl{
        text-align: left;
        display: block;
        width: 100%;
        font-size: 1.2rem;
        padding: 2px 0px;
        color: #3c3c3c;
    }
    .inner_sub_table  td,
    .inner_sub_table  th{
        padding: 3px 10px!important;
        font-size: 0.8rem !important;
        color: #3c3c3c;
    }


    .inner_sub_table  th{
        font-weight: 600!important;
        font-size: 0.9rem !important;
        text-align: left;
        text-transform: capitalize !important;
    }


    .cancel_card{
        border-top:3px solid #4aaf27;
    }

    .cancel_card{
        border-top: 3px solid red !important;
    }
    .cancel_card .app_status_lbl{
        background: red !important;
    }

    .success_class{
        color: #4aaf27 !important;
    }





</style>


<script>

    Dropzone.autoDiscover = false;

    $(function () {




        window.onunload = refreshParent;
        function refreshParent() {
            window.opener.location.reload();
        }


        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };


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
                            html: "<div class='download_box'><p>The booking confirmation message has been sent to you on whatsapp or mobile sms in case you don't have whatsapp account.</p><p>Please <a href='"+apk_url+"' target='_blank'><i class='fa fa-android'></i> Download</a> doctor app from playstore to track medical records, live tracking of your token, notifications and better experience. </p><div id='diloag_add_box'></div></div>",
                            showCancelButton: false,
                            confirmButtonText: 'Thankyou',
                            customClass:"success-box",
                            showConfirmButton: true
                        }).then(function (result) {
                            window.close();
                            var url = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
                            window.location.replace(url);
                            window.location = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
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
                        var url = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
                        window.location.replace(url);
                        window.location = baseUrl+'doctor/index/?t='+(appointment.doctor_id);
                    }
                });
            }
        }


    });
</script>
</body>
</html>