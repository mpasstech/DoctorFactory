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

    <meta name="description" content="QuToT, QuToT xyz, QuToT xyz jaipur, QuToT, Virtual Queue Automation">
    <meta name="keywords" content="QuToT">
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
        var baseUrl = '<?php echo Router::url('/',true); ?>';

    </script>

    <?php echo $this->Html->css(array('jquery.typeahead.css')); ?>
    <?php echo $this->Html->script(array('popper.min.js','jquery.js','bootstrap4.5.3.min.js','jquery.typeahead.js','firebase-app.js','firebase-messaging.js')); ?>
    <script src="<?php echo Router::url('/app.js?'.date('ymdhis'),true);?>"></script>

    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','bootstrap.min.css' ),array("media"=>'all')); ?>
    <title>QuToT</title>

    <style>

        *{
            /*border: 1px solid red;*/
        }



        .sub_heading{
            font-size: 3rem;
            text-align: center;
            width: 100%;
        }

        .sub_main_heading{
            font-size: 4rem;
            width: 100%;
        }

        body{
            background: linear-gradient(#fff, #f4f9ff);
            background-repeat: no-repeat;
            min-height: 720px;
        }
        #logo{
            text-align: center;
        }
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
            border: 2px solid #547DBF;
        }
        .main_grid{
            border: none;
            float: left;
            margin: 0 auto;
            width: 100%;
            box-shadow: 0px 5px 9px #5896ee8f;
        }
        .svg-wrapper{
            width: 100%;
            display: block;
            text-align: center;
            margin: 50% auto;
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
            padding: 2% 2% 2% 7% !important;
            font-size: 2.5rem !important;
            color: #547dbf;
            border: 5px solid !important;
            outline: none;
            z-index: 99999;
            height: 100% !important;
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

            font-weight: 500;
            font-size: 8.5rem;
            font-family: emoji;


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
            font-size: 2.3rem;
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
            font-size: 2.5rem;
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

        }
        .typeahead__query{
            height: 100%;
        }

        .typeahead__field, .typeahead__result{
            width: 100%;
            float: left;
            display: block;
        }


        .second_pera p{
            font-size: 1.5rem;
        }


        .main_grid{
            height: 60px;
        }

        @media only screen and (min-width: 320px) and (max-width: 430px) {
            .search_box {
                width: 100%;
                padding: 2% 2% 2% 11% !important;
                font-size: 1.8rem !important;
                border: 2px solid !important;

            }

            .typeahead__item li label{
                font-size: 1.5rem !important;
            }

            .fa-search{
                font-size: 2rem !important;
            }

            .typeahead__item .app_name{
                font-size: 1.3rem !important;
            }

            .bottom_sub_heading{
                font-size: 1.5rem;
            }

            .qutot_now_btn {
                font-size: 2rem !important;
                padding: 2% !important;
            }

            .btn-search{
                font-size: 1rem;
            }
            .large_heading{
                font-size: 5.5rem;
                margin:6%;
            }

            .sub_heading {
                font-size: 2rem;
            }
            .sub_main_heading {
                font-size: 2.8rem;
            }
            .second_pera p{
                font-size: 1rem;
            }
            .main_container{
                width: 100% !important;
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



        .fa-search{
            margin: 2% 2%;
            position: absolute;
            font-size: 3rem;
            height: 100%;

            z-index: 999999;
        }

        body{
            border-top: 3px solid blue;
        }

        .main_container{

            width: 70%;
            display: table;
            margin: 0px auto;
            /* text-align: center; */
            padding: 2% 2%;
            position: relative;
        }
        .qutot_now_btn{
            font-size: 2rem;
            padding: 1%;
        }

        .typeahead__cancel-button{
            display: none;
        }

        .background_box{
            background-size: cover !important;
            background-repeat: no-repeat !important;
            min-height: 360px !important;
            opacity: 0.5;
            width: 100%;
            height: 100%;
            position: absolute;
            background: transparent;
            z-index: -99;

        }
    </style>



</head>
<body>


<div class="main_container">

    <div class="row">
        <h1 class="large_heading">QueueToT</h1>
        <p class="sub_heading">First ever of it's kind Virtual Queue Token Tracker</p>
    </div>

    <div class="row second_pera">
        <h4 class="sub_main_heading" style="color: #547DBF;">Why QueueToT?</h4>
        <p>The average person will spend  substantial time standing in line over their lifetime. Besides waiting in queues is always unproductive and frustrating, be it doctors opds,  ( mention all as given in queuetot video description on YouTube ) </p>
        <p>QueueToT ( Queue Token Tracker) helps save more than 90 percent of the waiting time by making queues virtual and tracking queues live. It helps users match their arrival time with the time of their token number, besides organising queues in a disciplined manner.</p>


    </div>
    <div class="row ">
       <div class="col-12 typeahead__container" style="margin: 2% 0%;">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input id="search" class="search_box typeahead__field js-typeahead" autocomplete="off" autofocus type="text" placeholder="Place,Entity,Institution,Doctor,Hospital" />
        </div>
    </div>

    <div class="row ">
        <div class="col-12" style="text-align: center;">
            <h3 class="bottom_sub_heading">Start using QueueToT free in a matter of minutes by selecting following link</h3>
            <a href="<?php echo Router::url('/qutot/web_app_signup',true); ?>" type="button" class="btn btn-success qutot_now_btn">QueueToT Now</a>
            <a href="<?php echo Router::url('/qutot/login',true); ?>" type="button" class="btn btn-info qutot_now_btn">Login</a>

        </div>
        <img class="background_box" src="<?php echo Router::url('/img/queue_background.png',true); ?>">
    </div>



    <div class="row doctor_link_sec">
        <div class="col-12">


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
            window.location.href=baseUrl+"qutot/web_app/"+last_selected+"/?t="+btoa(last_selected)+'&wa=y&back=no';
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
                "<ul><li><label>{{doctor_name|raw}}</label></li><li> <span class='app_name'><img src='{{logo}}' style='height:1.5rem;width:1.5rem;'> {{app_name|raw}}</span></li></ul>"+
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