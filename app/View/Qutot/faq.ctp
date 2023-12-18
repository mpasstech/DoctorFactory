<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]-->
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="QuToT">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />

    <title>QuToT::Features</title>
    <meta property="og:title" content="QueueToT" />
    <meta property="og:description" content="Search QueueToT installations to book token." />
    <meta property="og:url" content="<?php echo Router::url('/img/qutot_text.png',true); ?>" />
    <meta property="og:image" content="<?php echo Router::url('/img/qutot_text.png',true); ?>" />



    <meta name="description" content="QuToT, QuToT xyz, QuToT xyz jaipur, QuToT, Virtual Queue Automation">
    <meta name="keywords" content="QuToT">
    <meta name="author" content="qutot.in">




    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';

    </script>

    <?php echo $this->Html->css(array('jquery.typeahead.css','aos.css')); ?>
    <?php echo $this->Html->script(array('popper.min.js','jquery.js','bootstrap4.5.3.min.js','jquery.typeahead.js','firebase-app.js','firebase-messaging.js','aos.js')); ?>
    <script src="<?php echo Router::url('/app.js?'.date('ymdhis'),true);?>"></script>

    <?php echo $this->Html->css(array( 'bootstrap4.min.css','font-awesome.min.css','bootstrap.min.css','qutot.css?'.date('ymdhis') ),array("media"=>'all')); ?>


    <script async src="https://www.googletagmanager.com/gtag/js?id=G-NS1YSMFXZC"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-NS1YSMFXZC');
    </script>

    <style>



        .bottom_sub_heading{
            font-size: 1.5rem;
            margin: 6px 0px;
            font-weight: 600;
        }


        *{
            /*border: 1px solid red;*/
        }

        .main_container{
           /* overflow-x: hidden;*/
        }


        .sub_heading{
            font-size: 2rem;
            text-align: center;
            width: 100%;
        }

        .sub_main_heading{
            font-size: 2.5rem;
            width: 100%;
            text-align: center;

        }


        .typeahead__container{
            margin: 3% auto !important;
            width: 70%;

        }


        form .col-sm-12{
            margin-bottom: 8px !important;
        }
        body{
            border-top: 7px solid #547DBF;
            float: left;
            width: 100%;
            display: grid;
            background-color: #f2f2f240;

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

        header{
            color: #333;
            padding: 0%;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 9999999;
            border-bottom: 2px solid #f9efef;
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
            width: 75%;
            padding: 2% 2% 2% 7% !important;
            font-size: 2rem !important;
            color: #547dbf;
            border: 2px solid !important;
            outline: none;
            z-index: 99999;
            height: 60% !important;
            border-radius: 67px;
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
            font-size: 5.5rem;
            font-family:"AR CENA" !important;
            margin: 0px;
            padding: 0px;

        }
        .large_heading span{
            font-size: 6rem;
            font-weight: 600;
        }


        .info{
            text-align: center !important;
            display: block;
            width: 100%;
            border-bottom: 0px solid gainsboro;
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
            font-size: 1.5rem;
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

        }
        .typeahead__query{
            height: 100%;
        }

        .typeahead__field, .typeahead__result{
            width: 100%;
            float: left;
            display: block;
        }




        .card img{
            height: 366px;
        }

        .main_grid{
            height: 60px;
        }

        @media only screen and (min-width: 320px) and (max-width: 430px) {

            .login_text{
                display: none;
            }

            .login_btn{
                font-size: 2.5rem !important;
            }

            .first_img{
                width: 100% !important;
            }

            .typeahead__container {
                width: 100%;
            }


            .search_box {
                width: 100%;
                padding: 2% 2% 2% 11% !important;
                font-size: 1.5rem !important;
                border: 2px solid !important;
                height: 50px !important;
            }

            .background_box{
                display: block !important;
            }
            .typeahead__item li label{
                font-size: 1.5rem !important;
            }

            .fa-search{
                font-size: 2rem !important;
                margin: 4% 4% !important;
            }

            .typeahead__item .app_name{
                font-size: 1.3rem !important;
            }

            .bottom_sub_heading{
                font-size: 1.5rem !important;
                margin: 6px 0px;
                font-weight: 600;
            }

            .qutot_now_btn {
                font-size: 1.5rem !important;
                padding: 2% !important;
            }

            .btn-search{
                font-size: 1rem;
            }
            .large_heading{
                font-size: 3.5rem;
                margin:0px;
            }

            .sub_heading {
                font-size: 1.5rem;
            }
            .sub_main_heading {
                font-size: 2rem;
                margin: 5px 0px;
            }

            .main_container{
                width: 100% !important;
                margin: 0px !important;
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
                width: 100%;
            }
            .btn-search{
                font-size: 1.8rem;
            }


            .background_box{
                display: block !important;
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
                width: 100%;
            }
            .btn-search{
                font-size: 1.8rem;
            }

            .background_box{
                display: block !important;
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
                width: 100%;
            }

            .background_box{
                display: block !important;
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
            font-size: 2.5rem;
            height: 100%;
            z-index: 999999;
            left: 0;
            color: #547DBF;
        }



        .main_container{
            width: 70%;
            display: block;
            margin: 0 auto;
            padding: 4% 2%;
            position: relative;
            overflow-x: hidden;
            margin-top: 90px !important;
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
            min-height: 230px !important;
            opacity: 0.4;
            width: 100%;
            height: 10px;
            position: relative;
            background: transparent;
            z-index: -99;
            bottom: -15%;
            display: none;

        }
        .search_box typeahead__field{
            border: none !important;
        }

        .top_heading h1, .top_heading p{
            width: 100%;

        }

        .card img{
            width: 100%;
            border-radius: 0px !important;

        }
        .feat_heading{
            text-align: center;
            width: 100%;
            color: #547DBF;
        }
        .row.card{
            padding: 10px 10px;
            border: none;
        }

        .features_div .card{

            padding: 5px 5px;
            height: 540px;
            border: 1px solid rgb(230 230 230 / 13%);
        }
        .col-sm-12{
            margin-bottom: 35px;
            padding-right: 5px !important;
            padding-left: 5px !important;
        }
        .card p{
            font-size: 1.7rem;
        }
        .card-body{
            padding: 0.9rem 0.9rem;

        }
        .card-title{
            font-weight: 600;
        }
        .back_arrow{
            float: left;
            padding: 0px 1rem;
            position: absolute;
            left: 0;
            color: orange;
        }


        .item {
            width: 200px;
            height: 200px;
            margin: 50px auto;
            padding-top: 75px;
            background: #ccc;
            text-align: center;
            color: #FFF;
            font-size: 3em;
        }

        .features_div .card{
            border: 1px solid #e2e2e287;
        }

        .total_token{
            background-color: #547DBF;
            color: #fff;
            border-radius: 10px;
        }
        .total_token .card-body{
            color: #428a00;
            text-align: center;
            padding: 1%;

        }

        .card-text{
            text-align: justify;
        }

        form label{
            color: #547DBF;
            font-weight: 400;
            font-size: 1.5rem;
            margin: 5px 0px;
        }
        h3{
            color: #547DBF;
            font-weight: 400;
        }
        .features_div h3{
            font-weight: 400 !important;
        }



        .login_btn{
            position: absolute;
            right: 0;
            font-size: 1.5rem;
            color: #42b342;
            padding: 3px 7px;
            border-radius: 9%;
        }
        .btn-info{
            background-color: #547DBF;
            border-color: #547DBF;
        }

        .icon_cls i, .icon_cls h3{
            float: left;
            color: #fff;
            text-align: center;
            font-weight: 500;
            width: 100%;
        }
        .total_token .media-body{
            display: block;
            width: 100%;
            text-align: center;
            color: #fff;
        }

        html {
            scroll-behavior: smooth;
        }

        .req{
            color: red;
        }
        #successMessage{
            text-align: center;
            background: #e7ffe7;
            color: green;
            padding: 13px 5px;
            border: 1px solid #91ff91;
            border-radius: 9px;
            position: fixed;
            left: 0;
            bottom: 69px;
            z-index: 9999999999;
            width: 90%;
            margin: 20px;
        }

        #errorMessage{
            text-align: center;
            background: #ffcaca;
            color: #b92c2c;
            padding: 13px 5px;
            border: 1px solid #f42727;
            border-radius: 9px;
            position: fixed;
            left: 0;
            bottom: 69px;
            z-index: 9999999999;
            width: 90%;
            margin: 20px;
        }

        li{
            list-style: decimal-leading-zero;
        }
        .back_btn{
            background: none;
            border: none;
            float: left;
            color: #547DBF;
            position: absolute;
            left: 4px;
        }

    </style>


</head>
<body>

<header>
    <div class="row card">

        <h1 class="large_heading qutot_font">
            <button class="back_btn" onclick="goBack()"><i class="fa fa-long-arrow-left"></i></button>
            QueueToT</h1>
        <p class="card-text" style="text-align: center;width: 100%;">Frequently Asked Questions </p>
    </div>
</header>
<div class="main_container">
    <div class="row card">
        <div class="card-body">
             <div class="col-12">
                <h3>What is Mission QuToT?</h3>
                <p class="card-text">Mission QuToT is about deleting queues from everywhere. Post 2021, queues experience will not be frustrating any more. The crowd will become virtual. </p>
             </div>

            <div class="col-12">
                <h3>How an ordinary person can participate in The Mission QuToT?</h3>
                <p class="card-text">Whenever anyone sees lines, let the people know they don’t deserve standing in queues. Request the authorities to use QuToT.  </p>
            </div>


            <div class="col-12">
                <h3>How QuToT creates value for both clients and their users? </h3>
                <p class="card-text">For Users: Saves lot of time of users / end customers and relieves them from the frustrations of queues. </p>
                <p class="card-text">For Organisations: Saves staff salaries in managing queues. It is estimated that one counter has potential of saving one man month salary per month because of virtual queue automation. In case, there are more counters, the saving potential multiplies many times. </p>
            </div>

             <div class="col-12">
                <h3>Why QuToT is free? </h3>
                <p class="card-text">Single QuToT installation is free for all kinds of counters/ places / Doctors OPDs etc..
                    In addition, all bookings of walk-in tokens are also free for end users.
                </p>
                <p class="card-text">QuToT is used to organise walk-in queue in most efficient manner with free walk-in self token generation by users themselves. The queues so generated can be tracked live on line. It is chargeable to those, who want to track queue live online and book token from the comfort of home, resulting into time saved, enhanced productivity and frustrations of queues. </p>
            </div>

            <div class="col-12">
                <h3>Are user Walk-in tokens always free?  </h3>

                <p class="card-text">Walk in tokens are generally free, unless upgraded modules are being used by the clients. For upgrade options the cost of same is born by the clients and it is free in general for users barring few exceptions. </p>
            </div>
            <div class="col-12">
                <h3>In how many ways walk-in tokens can be booked by users for free?  </h3>
                <ul>
                    <li>
                        <p class="card-text">After scanning QR Code at onsite installations, user can book token from QuToT walk in screen.</p>
                    </li>
                    <li>
                        <p class="card-text">Users scan also book token for free in case of e-Token dispenser devices installed at counter.</p>
                    </li>
                    <li>
                        <p class="card-text">Walk in token through Qutot Schedule web app is presently free. Users can search client / counter on QuToT portal <a href="www.qutot.com">Qutot.com</a>  and type name of entity/place to search entity web app.  </p>
                    </li>
                </ul>

            </div>

            <div class="col-12">
                <h3>How much is the price for upgrade option for clients installations? </h3>

                <p class="card-text">In cases, when it is upgraded with single step e-token self help dispensing device or, and with Live token display on TV  screen, it becomes chargeable at a nominal price of 3 cent or INR 2 per token chargeable to client ( not users). This will be in addition to one time Set up fees and Customisation cost at actual, if any as applicable chargeable to the client/counter. </p>
            </div>

            <div class="col-12">
                <h3>What is the cost of booking token on line? </h3>

                <p class="card-text">30 Cent or  INR 20 per Token only, and only if users book online. As mentioned earlier walk in tokens are free for users in general. </p>
            </div>


            <div class="col-12">
                <h3>In how many ways can a user book token?  </h3>
                <p class="card-text">User can self generate and book tokens without any one else help in multiple ways-</p>
                <ul>
                    <li>
                        <p class="card-text">After scanning QR Code, users can book token from QuToT walk-in screen.</p>
                    </li>
                    <li>
                        <p class="card-text">User can type and search with entity name on QuToT portal - qutot.com, and get QuToT Schedule web app to book token with both options of walk-in and schedule tokens online. </p>
                    </li>
                    <li>
                        <p class="card-text">User can use QuToT Schedule web app  of counter on what's app and book token both walk in and schedule on line.</p>
                    </li>
                    <li>
                        <p class="card-text">All above methods are self generation of tokens. User can also request the counter executive to book his or her token.</p>
                    </li> <li>
                        <p class="card-text">24/7 Virtual Receptionist on phone number  to book token under paid module.</p>
                    </li>
                </ul>

            </div>



            <div class="col-12">
                <h3>How a user can track token?</h3>
                <ul>
                    <li>
                        <p class="card-text">User gets a link with token number on his mobile as sms. User can track his token live using the link. </p>
                    </li>
                    <li>
                        <p class="card-text">In addition user can track token using QuToT schedule - a web app , besides TV display, if installed at the counter after opting for upgrade options. </p>
                    </li>

                </ul>

            </div>


            <div class="col-12">
                <h3>Can a single organisation create multiple counters for free? </h3>

                <p class="card-text">Multiple counters in single organisation is paid module. </p>
            </div>

            <div class="col-12">
                <h3>How much time is taken in installing or configuring a QuToT? </h3>
                <p class="card-text">In matter of few minutes a counter can be up and running. Take a print of QR Code and place it at visible position so that users start generating tokens themselves.</p>
            </div>

            <div class="col-12">
                <h3>Is Qutot Schedule App is available on Google App Store for users?</h3>
                <p class="card-text">No ; One can find QuToT Schedule web app on Qutot.com.  This method  of app saving on your mobile does  not consume any memory of mobile device.   One time single app link saved will help users find other multiple entities and counters, for which users can book token, whenever needed in future. Users, whenever sees crowds or queues can share the QuTotT Now link with the authorities and ask them to install the QuToT for the benefit of the common public.  For doing so, users may get the credit ,  which can be used by users in booking online tokens.</p>
            </div>

        </div>
        </div>
    </div>
</div>


</body>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</html>