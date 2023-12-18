<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]-->
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <meta name="description" content="Doctorxy, Doctor xyz, Doctor xyz jaipur, DOCTORapps, Hybrid OPD with Telemedicine, Virtual Queue Automation, Doctor's Own App, Virtual Reception 24X7, Lifelong Engagement With Patient">
    <meta name="keywords" content="Doctorxy, Doctor xyz, Doctor xyz jaipur, Hybrid OPD with Telemedicine, Virtual Queue Automation, Doctor's Own App, Virtual Reception 24X7, Lifelong Engagement With Patient">
    <meta name="author" content="mengage.in">


    <link rel="manifest" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>manifest.json" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-72x72.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-96x96.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-128x128.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-144x144.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-152x152.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-192x192.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-384x384.png" />
    <link rel="apple-touch-icon" href="<?php echo Router::url('/add_home_screen/doctorapps/',true);?>icons/icon-512x512.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#547DBF" />
    <meta name="theme-color" content="#547DBF" />


    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
      <?php if($_SERVER['HTTP_HOST']=='localhost'){ ?>
            var baseUrl = '<?php echo Router::url('/',true); ?>';
        <?php }else{ ?>
            var baseUrl = 'https://www.mpasscheckin.com/doctor/';
        <?php } ?>

    </script>
    
    <?php echo $this->Html->css(array('jquery.typeahead.css')); ?>
    <?php echo $this->Html->script(array('popper.min.js','jquery.js','bootstrap4.5.3.min.js','jquery.typeahead.js')); ?>
<script src="<?php echo Router::url('/',true);?>app.js"></script>

    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','bootstrap.min.css' ),array("media"=>'all')); ?>
    <title>DOCTORapps</title>

    <style>

        .desclaimer p{
            font-size: 9px;
        }
        .doctor_lbl a{
            color: #547DBF;
            padding: 0.7rem;
            border: 1px solid;
            border-radius: 30px;
            margin: 0.3rem 0;
            text-decoration: none;
            cursor: pointer;
        }

        .dynamic_width{
            width: 50%;
        }

        ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
            color: #8a8a8a;
            opacity: 1; /* Firefox */
        }

        :-ms-input-placeholder { /* Internet Explorer 10-11 */
            color: #8a8a8a;
        }

        ::-ms-input-placeholder { /* Microsoft Edge */
            color: #8a8a8a;
        }

        #header{
            margin-bottom: 7%;
            width: 95%;
            margin: 1% auto;
        }
        .main_grid{
            border: 2px solid #547DBF;
            float: left;
            margin: 0 auto;
            width: 100%;
            box-shadow: 0px 5px 9px #5896ee8f;
        }
        .svg-wrapper{
            width: 100%;
            display: block;
            text-align: center;
            margin: 20% auto;
        }
        .search_box_container{
            display: block;
            float: left;
            width: 100%;
        }
        .search_btn_container{
            display: block;
            text-align: center;
            height: 100%;
            border: none;
        }


        .icon_span{
            color: green;
            border: 1px solid;
            padding: 2px;
            border-radius: 3px;
            margin: 2px 2px;
            min-width: 19px !important;
            float: left;
            min-height: 15px;
            text-align: center;
        }


        .right_button{
            float: right;
            width: auto;
        }
        .container{
            margin: 1% auto;
        }
        .search_box{
            width: 100%;
            padding: 1.8rem 0px;
            font-size: 2.2rem;
            color: #4e4848;
            border: none !important;
            outline: none;
            z-index: 99999;
            height: 100% !important;
        }
        .fa-search{
            font-size: 3.5rem;
        }
        .main_grid div[class*="col-"]{
            margin: 0;
            padding: 0;
        }
        .search_link{
            text-align: center;
            margin: 0 auto;
            width: 100%;
            color: #449944;

        }
        .icon_box{
            float: right;
        }
        .btn-search, .btn-search:focus, .btn-search:active{
            width: 100%;
            height: 100%;
            background: #6c6cff;
            color: #fff;
            border: none;
            outline: none;
            font-size: 2.2rem;
        }
        .large_heading{
            text-align: center;
            width: 100%;
            display: block;
            color: #547DBF;
            margin-top: 3%;
            font-weight: 500;
            font-size: 4rem;
        }
        .large_heading span{
            font-size: 6rem;
            font-weight: 600;
        }


        .info{
            text-align: center !important;
            display: block;
            width: 100%;
            border-bottom: 1px solid gainsboro;
            padding: 6px 0px;
            line-height: 2rem;
        }


        .whats_app, .whats_app:hover{
            font-size: 4.5rem;
            color: #fff;
            background: #4FCE5D;
            height: 60px;
            width: 60px;
            border-radius: 10px;
            display: block;
            margin: 1% auto;
            padding: 0px 10px;


        }
        .doctor_lbl{
            width: 100%;
            text-align: center;
            font-size: 1.6rem;
            color: #959595;
        }
        .whats_app_row{
            margin: 0;
            margin-top: 4%;
            float: left;
            display: block;
            width: 100%;
        }
        .search_box_container .dropdown-menu{
            width: 100%;
            border-radius: 0px 0px 5px 5px;
        }
        .search_box_container .dropdown-item{
            font-size: 1.8rem;
            border-bottom: 1px solid #eee;
            padding: 1%;
        }

        .search_box_container .dropdown-item:focus{
            outline: none;
        }


        .typeahead__item .city_name{

            width: 100%;
            display: block;
            float: left;
        }
        .typeahead__item .app_name{
            font-size: 1.3rem;
            margin-bottom: 5px;

            display: block;
        }
        .typeahead__item ul{
            float: left;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .typeahead__item{
            float: left;
            width: 100%;
            display: block;
        }

        .typeahead__item a{
            float: left;
            width: 100%;
            display: block;
        }

        .typeahead__item a strong{
            color: #d44646;
        }

        .typeahead__item .category_name{
            display: block;
            font-size: 1.3rem !important;
        }


        .typeahead__item ul{
            width: 100%;
            float: left;
            display: block;
            margin: 0;
            padding: 0;
        }

        .typeahead__item li label{
            font-size: 1.5rem;
        }

        .typeahead__item li:first-child{
            width: 60%;
            float: left;
            list-style: none;
        }
        .typeahead__item li:last-child{
            width: 40%;
            float: left;
            list-style: none;
            text-align: right;
        }

        .doctor_link_sec{
            width: 100%;
            display: block;
            float: left;
            margin-bottom: 15%;
        }
        .last_img{
            width: 35%;
            /* display: block; */
            bottom: 9%;
            position: absolute;
            left: 5%;
            opacity: 0.8;
            z-index: -99999;
            transform: rotate(351deg);
        }

        #btnSave{
            position: fixed;
            left: 0;
            bottom: 0;
            color: #4FCE5D;
            background: #fff;
            border-radius: 0px;
            outline: none;
            padding: 5px 2px;
            width: 98%;
            margin: 2% 1%;
            border: none;
            border-top: 2px solid;

        }

                .row{
                    margin-right: 0px !important;
                    margin-left: 0px !important;
                }
                .typeahead__field{
                    height: 100%;
                    font-size: 2rem;
                }
                .typeahead__query{
                    height: 100%;
                }

                .typeahead__field, .typeahead__result{
                    width: 100%;
                    float: left;
                    display: block;
                }



                @media only screen and (min-width: 320px) and (max-width: 430px) {
                    .search_box {
                        padding: 1rem 0.5px;
                        font-size: 1.2rem !important;
                    }
                    .fa-search{
                        font-size: 1.5rem;
                    }
                    .btn-search{
                        font-size: 1rem;
                    }
                    .large_heading{
                        font-size: 2rem;
                        margin-top: 10%;
                    }

                    .large_heading span{
                        font-size: 3rem;
                    }


                    .whats_app, .whats_app:hover{
                        font-size: 3.5rem;
                        height: 50px;
                        width: 50px;
                    }
                    .whats_app_row{
                        margin-top: 4%;
                    }
                    .doctor_lbl{
                        font-size: 1.7rem;
                    }
                    .doctor_lbl a{
                        display: block;;
                    }
                    .info{
                        font-size: 1.6rem !important;
                    }
                    .last_img{
                        display: none;
                    }
                    .search_box_container .dropdown-item .category_name{
                        font-size: 1rem !important;
                    }
                    .search_box_container .dropdown-item .city_name, .search_box_container .dropdown-item .app_name{
                        font-size: 1rem;
                    }
                    .search_box_container .dropdown-item li:first-child, .search_box_container .dropdown-item li:last-child{
                        font-size: 1.2rem;
                    }
                }


                @media (min-width: 481px) and (max-width: 767px) and (orientation: portrait){
                    .search_box{
                        font-size: 1.8rem !important;
                    }
                    .btn-search{
                        font-size: 1.8rem;
                    }

                    .last_img {
                        width: 35%;
                        bottom: 15%;
                        left: 9%;
                    }

                }

                @media (min-width: 481px) and (max-width: 767px) and (orientation: landscape){
                    .search_box{
                        font-size: 1.8rem !important;
                    }
                    .btn-search{
                        font-size: 1.8rem;
                    }

                    .last_img {
                        width: 37%;
                        bottom: -20%;
                        left: 1%;
                    }
                    .whats_app_row{
                        margin-top: 10%;
                    }
                    #header{
                        margin-bottom: 20%;
                    }

                }

                @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
                    .search_box{
                        font-size: 1.8rem !important;
                    }
                    #header{
                        margin-bottom: 20%;
                    }
                    .last_img {
                        width: 40%;
                        /* display: block; */
                bottom: 17%;
                position: absolute;
                left: 4%;
                opacity: 0.8;
                z-index: -99999;
                transform: rotate(351deg);
            }
            .whats_app_row{
                margin-top: 17%;
            }

        }
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {

            .last_img{
                display: none;
            }

            #header{
                margin-bottom: 20%;
            }

        }




    </style>



</head>
<body>
<header id="header" class="header">
    <div class="right_button">
        <!-- <a href="<?php /*echo Router::url('/org-login',true); */?>"  onclick="window.location = '<?php /*echo Router::url('/org-login',true); */?>'"><i class="fa fa-sign-in"></i> Login</a>-->
        <a href="javascript:void(0)"  onclick="window.location = 'https://www.mpasscheckin.com/virtual-queue-management-system#login'"><i class="fa fa-sign-in"></i> Login</a>
    </div>
    <div id="logo" class="logo">
        <a href="/doctor" rel="home">
            <img src="https://www.mpasscheckin.com/assets/images/logo/logo.webp" alt="image">
        </a>
    </div>
</header><!-- /.header -->

<div class="container dynamic_width"  >

    <div class="row">

        <h1 class="large_heading"><span style="font-weight:500 !important;">DOCTOR</span>apps</h1>
        <label style="margin-bottom: 0.9rem !important; text-align: center;width: 100%;color: #30A95A;">Search and Connect with Doctors, Clinics and Hospitals in the simplistic manner .</label>

        <h5 style="width: 100%;display: block;text-align: center;">Doctor apps is a web app store for Doctors/Clinics/Hospitals</h5>
    </div>

    <div class="row main_grid justify-content-center">
        <div class="col-1">
            <div class="svg-wrapper">
                <i class="fa fa-search" aria-hidden="true"></i>
            </div>
        </div>
        <div class="col-11 typeahead__container">
            <div class="search_box_container typeahead__field">
                <div class="typeahead__query">
                    <input id="search" class="search_box js-typeahead" autocomplete="off" autofocus type="text" placeholder="Search Doctor category, Doctor/Hospital name, city" />
                </div>
            </div>
        </div>
        <div style="display: none;" class="col-2">
            <div class="search_btn_container">
                <button class="btn-search" id="search_btn" type="button">SEARCH</button>
            </div>
        </div>
    </div>


    <div class="row whats_app_row" >
        <p style="font-size:12px; text-align: center;width: 100%;display: block;">While searching, Look for symbol <span style="font-size: 12px; font-weight: 600;"><i class='fa fa-video-camera'></i></span> for doctors, who are doing Tele- Consultation</p>
        <p style="font-size:12px; text-align: center;width: 100%;display: block;"><b>Please type <span style="color: red; font-weight: 600;">Corona</span> to search Doctors for Corona Consultation</b></p>

        <div class="col-12" style="display:none;">
            <a href="https://api.whatsapp.com/send?phone=918955004049" class="whats_app">
                <i class="fa fa-whatsapp"></i>
            </a>
            <h3 class="info">Say 'Hi' on WhatsApp to help us to reply you back</h3>
        </div>
    </div>


    <div class="row doctor_link_sec">
        <div class="col-12">
            <br>
            <br>
            <label class="doctor_lbl">if you are doctor then click on <a href="<?php echo Router::url('/doctor_home',true); ?>">DOCTORapps</a> </label>
        </div>

        <div class="col-12 desclaimer">
            <br>
            <br>
            <p>अस्वीकरं : MEngage एक टेक्नोलॉजी प्लेटफॉर्म है जो कि मरीज़ एवं डॉक्टर को जोड़ता है मरीज़ के इलाज लिए। MEngage स्वयं किसी भी प्रकार का प्रमार्श् नही करता एवं किसी भी प्रकार से उतरदायी नही है प्रमार्श् के कारण।</p>
            <p>Disclaimer : Mengage is a technology plate - form that connects Patients and Doctors for seeking consultation from Doctors. mEngage , itself does not give any consulting to patients and hence is not responsible in any ways, whatsoever in relation to Patients consulting by Doctors.</p>
        </div>

        <div style="text-align: center; display: none;" class="col-12">
            <?php
            if(!empty($lat) && !empty($lng)){ ?>
                <a class="search_link" href="<?php echo Router::url('/',true); ?>" ><i class="fa fa-map-pin"></i> Search any where location doctors</a>
            <?php  }else{ ?>
                <a class="search_link" href="javascript:void(0);" id="near_my_location" ><i class="fa fa-map-pin"></i> Search my nearest location doctors</a>
            <?php   } ?>
        </div>

        <button style="display:none;" id="btnSave"><img style="width: 20px; height: 20px;" src="<?php echo Router::url('/add_home_screen/doctorapps/icons/icon-72x72.png',true); ?>" alt="image"> Save to homescreen for ease of navigation </button>
    </div>

</div>

</body>



<script type="text/javascript">
    $(function () {




        function openWindow(last_selected){
            window.location.href=baseUrl+"doctor/index/"+last_selected+"/?t="+btoa(last_selected)+'&wa=y&back=no';
        }

        typeof $.typeahead === 'function' && $.typeahead({
            input: "#search",
            minLength: 1,
            maxItem: 15,

            highlight:'any',
            hint: true,
            dynamic: true,
            display: ["doctor_name", "category_name","app_name","city_name"],
            template: "<span data-series='{{doctor_name|raw}}'>" +
                "<ul><li><label>{{doctor_name|raw}}</label><span class='category_name'>{{category_name|raw}} </span></li><li> <span class='app_name'><img src='{{logo}}' style='height:1.5rem;width:1.5rem;'> {{app_name|raw}}</span><span class='city_name'>{{city_name|raw}}</span><span class='icon_box'>{{is_online_consulting}}  {{is_audio_consulting}}  {{is_chat_consulting}}</span> </li></ul>"+
                "</span>",
            maxItemPerGroup: 15,
            backdrop:false,
            emptyTemplate: 'No result for "{{query}}"',
            source: {data:<?php echo $data_array; ?>},
            callback: {
                onClickAfter: function (node, a, item, event) {
                    openWindow(item.id);

                }
            },
            debug: true
        });









        $("#search").val('');
        $("#search").focus();


        var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        };




        function success(pos) {
            var crd = pos.coords;
            window.location.href=baseUrl+"/homes/index/"+btoa(crd.latitude)+"/"+btoa(crd.longitude);
            // console.log('Your current position is:');
            // console.log(`Latitude : ${crd.latitude}`);
            // console.log(`Longitude: ${crd.longitude}`);
            // console.log(`More or less ${crd.accuracy} meters.`);

        }
        function error(err) {
            if(err.code==1){
                alert("Please allow location permission for search nearest location");
            }
            console.warn(`ERROR(${err.code}): ${err.message}`);
        }


        var lat ="<?php echo $lat; ?>";
        var lng ="<?php echo $lng; ?>";

        $("#near_my_location").click(function(){

            if(lat=='' && lng==''){
                navigator.geolocation.getCurrentPosition(success, error, options);
            }else{
                window.location.href=baseUrl+"/homes/index/"+lat+"/"+lng;
            }

        })




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
                var ua = navigator.userAgent.toLowerCase();
                if (ua.indexOf('safari') != -1) {
                    if (ua.indexOf('chrome') > -1) {
                        alert("Following reasons are that app can not add on the homescreen\n\n1. This web browser does not support this feature. You can try again by updating your browser.\n2. You have already added 'Doctor Apps' to homescreen.");
                    } else {
                        var ios= "Following steps are add to app on the homescreen.\n1. Tap the Share button (at the browser options)\n2. From the options tap the Add to Homescreen option, you can notice an icon of the website or screenshot of website added to your devices homescreen instantly.\n3. Tap the icon from homescreen, then the Progressive Web App of your website will be loaded.";
                        alert(ios);
                    }
                }

             }
        });



        function onLoad(){
            $(".container").removeClass("dynamic_width");
            $("#btnSave").hide();
            if (window.matchMedia('(display-mode: standalone)').matches) {
                $("#btnSave").hide();
            }else{
                if(!is_desktop()){
                    $("#btnSave").hide();
                }else{
                    $(".container").addClass("dynamic_width");
                }
            }
        }

        window.addEventListener('load', (event) => {
            onLoad();
        });


        function is_desktop(){
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                return false;
            }return true;
        }




    });
</script>

</html>