<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- BASIC INFO -->
<title>Patient Engagement Application</title>
<!-- GOOGLE FONTS -->
<head>


    <?php
    echo $this->Html->css(array(
        'bootstrap.min.css',
        'bootstrap-theme.min.css',
        'bootstrap-tagsinput.css',
        'font-awesome.min.css',
        'doctor_static.css'
    ), array(
        "media" => 'all'
    ));
    ?>

    <?php
    echo $this->Html->script(array('jquery.js'));
    ?>


    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '235592626846699', {
            em: 'insert_email_variable,'
        });
        fbq('track', 'CompleteRegistration');

        $(document).ready(function () {
            fbq('track', 'CompleteRegistration');
        });

    </script>
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=235592626846699&ev=CompleteRegistration&noscript=1"
        /></noscript>




    <style>

        .cnt{
            border: 2px solid #dadada;
            border-radius: 3px;
            box-shadow: none;
            padding: 0.75rem !important;
            width: 96%;

        }
        .smt_btn {
            background: #fa0 none repeat scroll 0 0;
            border: 3px solid #fa0;
            border-radius: 10px;
            box-shadow: none;
            color: #fff;
            display: block;
            font-size: 0.9375rem;
            font-weight: 700;
            height: auto;
            letter-spacing: 2px;
            line-height: 1.5;
            margin: 1rem auto;
            padding: 0.75rem 1.5rem !important;
            text-shadow: none;
            text-transform: uppercase;
            transition: all 0.25s ease-in-out 0s;
            width: auto;
            float: right;
            margin-right:12px;
        }

        #successMessage{

            border: 3px solid #7EFE18;
        }
        #errorMessage{

            border: 3px solid #FE7918;
        }
        .message {
            background: #fff;
            border-radius: 12px;
            padding: 14px 3px !important;
        }
        .error-message {
            color: #FE7918;
            font-size: 12px;
            margin-top: -75px;
            padding: 0 5px !important;
            text-align: center;
            text-transform: uppercase;
        }
        .center_head_txt{
            text-align: justify;
            line-height: 1.8em;
        }
        .panel{margin-bottom: 0px !important;padding: 30px 8px 0px 8px !important;}
        #regForm1Copy h2{color:#043B49;}
        .footer_con_txt{color:#043B49; font-weight: bold;}
        body{color:#043B49;}
    </style>


    <link rel="shortcut icon" href="<?php  echo Router::url('/favicon.ico', true); ?>" type="image/x-icon">
    <link rel="icon" href="<?php  echo Router::url('/favicon.ico', true); ?>" type="image/x-icon">



</head>
<body>
<!-- Header -->
<header class="header" role="banner">
    <div class="wrapper">
        <h1 class="header__logo"><a href="<?php
            echo Router::url('/', true);
            ?>" title="Amazon Developer">
                <div class="mktoImg mktoGen" id="mkto_gen_headerLogo"><img class="header__logo-img" id="headerLogo"
                                                                           mktoname="Header Logo (250x100)"
                                                                           src="<?php
                                                                           echo Router::url("/images/mblogo.png", true);
                                                                           ?>">
                </div>
            </a></h1>
    </div>
    <!-- Banner -->
    <section class="header__banner">
        <img class="banner_img"  src="<?php  echo Router::url("/images/patients-banner.png", true);  ?>">
    </section>
</header>
<!-- Main Content -->
<main class="main" role="main">
    <!-- Reg Form 1 -->

    <section class="registration gray-bg panel reg1 ">
        <div class="wrapper--content">
            <div class="mktoText" id="regForm1Copy"><h2>Get Your Own Doctor-Patient Engagement Application</h2>
                <p class="center_head_txt"> Your Branded Doctor Patient engagement App provide you an opportunity to connect, re-engage and retain patients as well as subscribers with your own Doctor's app.According to latest Mobile marketing and Geo-Localytics, push notifications and app based m-commerce platform can boost business engagement by 50%.  <b> </br>Fill out your information below to get your own DOCTOR PATIENT APP as well as latest presentation on how this app will help you brand your growing Medical Services. </b></p></div>
            <div class="mktoForm clearfix" id="regForm1Form">
                <div id="lpeCDiv_13422" class="lpeCElement Demand_Gen_Global_Form"><span
                        class="lpContentsItem formSpan">


                            <?php echo $this->Session->flash('success'); ?>
                        <?php echo $this->Session->flash('error'); ?>
                        <?php echo $this->Form->create('AppEnquiry',array('type'=>'file','id'=>'login_form','class'=>'form-horizontal mktoForm mktoHasWidth mktoLayoutAbove')); ?>


                        <div class="form-group">
                            <div class="col-sm-6">
                                <?php echo $this->Form->input('name',array('type'=>'text','placeholder'=>'Name','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                            </div>
                            <div class="col-sm-6">
                            <?php echo $this->Form->input('email',array('type'=>'email','placeholder'=>'E-mail','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <?php echo $this->Form->input('phone',array('type'=>'text','placeholder'=>'Mobile','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                            </div>
                            <div class="col-sm-6">
                                 <?php $type_array = $this->Custom->getDepartmentList("Doctor"); ?>
                                 <?php echo $this->Form->input('department_from',array('required'=>'required','empty'=>'Select Department','type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type cnt')); ?>
                          </div>

                        </div>




                        <div class="form-group">
                            <div class="col-sm-6 pull-right mktoButtonWrap mktoPaperMashup">
                                <?php echo $this->Form->submit('Submit',array('class'=>'smt_btn','id'=>'signup')); ?>
                            </div>
                        </div>



                        <?php echo $this->Form->end(); ?>





</span></div>
            </div>
        </div>
    </section>

    <section class="panel event event1">
        <div class="wrapper--content clearfix">
            <section class="event__info">
                <div class="mktoText" id="event1Copy">
                    <p class="footer_con_txt">
                        <!--Your Medical App is geared towards optimizing your medical
                        services through mobile platform. Your App's Push strategies is a result of well researched
                        and simplified method to improve Medical services efficiency at your front desk. Also when
                        it comes to engaging your patients as subject matter expert your app will be capable of
                        following the same basic rules of any other marketing tactics. </br>
                        -->
                        Some salient
                        features of your Doctor Patient engagement all will give your following mobile
                        business capabilities:
                    </p>
                    <ol>
                        <li>Communication seamlessly with your patients<br></li>
                        <li>Online appointments and payment<br></li>
                        <li>Keep patient medical records online<br></li>
                        <li>Upload your reports & consult with doctor online<br></li>
                        <li>Instant medical report sharing with doctor & patients<br></li>
                        <li>Unified view of your patient's health status<br></li>
                    </ol>
                </div>
            </section>
            <aside class="event__aside">
                <iframe width="100%" height="360" src="https://www.youtube.com/embed/jpSsbjwrgoM" frameborder="0" allowfullscreen></iframe>
            </aside>
        </div>
    </section>



</main>
<div id="mktoStyleLoaded" style="display: none; border-top-color: rgb(18, 52, 86);"></div>
<!-- Footer -->
<footer class="footer" role="contentinfo">
    <section class="footer__top">
        <div class="wrapper--content">
            <ul class="social clearfix">
                <li class="social__item"><a href="https://www.facebook.com/mbroadcastapp"
                                            class="social__link facebook" target="_blank"><i class="fa fa-facebook"
                                                                                             aria-hidden="true"></i></a>
                </li>
                <li class="social__item"><a href="https://twitter.com/mBroadcastApp" class="social__link twitter"
                                            target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </li>
                <li class="social__item"><a href="https://plus.google.com/114022426322768502237/posts"
                                            class="social__link blogger" target="_blank"><i class="fa fa-rss"
                                                                                            aria-hidden="true"></i></a>
                </li>
            </ul>
        </div>
    </section>
    <section class="footer__btm">
        <div class="wrapper">
            <a href="<?php
            echo Router::url('/', true);
            ?>">
                <div class="mktoImg mktoGen" id="mkto_gen_footerLogo"><img class="header__logo-img" id="footerLogo"
                                                                           src="<?php
                                                                           echo Router::url('/images/mbfooter.png', true);
                                                                           ?>"
                                                                           mktoname="Footer Logo (250x100)"></div>
            </a>
            <p class="copyright">© 2016-<span class="year">2017</span>, mEngage.in or its affiliates. All Rights
                Reserved.</p>
        </div>
    </section>
</footer>
</body>
</html>


