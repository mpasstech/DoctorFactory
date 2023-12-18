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
        window.doctor_id = "<?php echo $thin_app_id; ?>";


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
    <?php echo $this->Html->script(array('jquery-3.5.1.min.js','popper.min.js','bootstrap4.min.js','loader.js','es6-promise.auto.min.js','sweetalert2.min.js','jquery.maskedinput-1.2.2-co.min.js','queue_management.js?'.date('his'),'html5gallery.js','jquery-confirm.min.js','dropzone.min.js','typeahead.bundle.js','bootstrap-tagsinput.min.js','firebase-app.js','firebase-messaging.js')); ?>
    <?php echo $this->Html->css(array( 'bootstrap4.min.css','doctor.css?'.date('his'),'font-awesome.min.css','sweetalert2.min.css','jquery-confirm.min.css','dropzone.min.css','jquery.typeahead.css','bootstrap-tagsinput.css' ),array("media"=>'all')); ?>
    <script src="<?php echo Router::url('/app.js?'.date('ymdhis'),true);?>"></script>
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

        .qutot_font{font-family:"AR CENA"!important}

        .go_to_dash button {
            font-size: 1.5rem !important;
            padding: 1.3rem 0 !important;
        }


        #dashboard_box{
            margin-bottom :10px;
        }

    </style>
</head>

<body data-di="<?php echo $doctor_id; ?>" data-ti="<?php echo base64_encode($thin_app_id); ?>"  moq-hospital="<?php echo ($this->AppAdmin->check_app_enable_permission($thin_app_id,"MOQ_HOSPITAL"))?'YES':'NO'; ?>" data-sf ="<?php echo $doctor_data['allow_only_mobile_number_booking']; ?>" id="body">
<header>
    <div class="row block detail_main_box" >
        <div class="row top_header_row">
            <div class="col-12" style="text-align: center;padding-right: 0;padding-left: 0;" >

                <div class="go_to_dash" style="float: left;position: absolute;left: 10; display: none;text-align:center;">
                    <button class=""><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
                </div>

                <h1 class="large_heading qutot_font" style="margin: 0;">QueueToT</h1>
                <span id="top_sub_title" style="font-size: 1.2rem;">Queue Token Tracker</span>
            </div>
        </div>
    </div>
</header>

<div id="dashboard_box" class="login_box">

</div>

<?php
$param = "t=".base64_encode($thin_app_id);
?>

<iframe id="trackerIframe"  data-src="<?php echo Router::url('/tracker/fortis?'.$param,true);?>"  ></iframe>



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



    });
</script>
</body>
</html>