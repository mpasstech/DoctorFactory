<html lang="en">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- BASIC INFO -->
<title>Patient Engagement Application</title>
<!-- GOOGLE FONTS -->
<head>

    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
            n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '235592626846699', {
                em: 'insert_email_variable,'
        });
        fbq('track', 'PageView');

    </script>


<script>x
    var otpVal = '';
    var timeVal =0;
    var baseurl = '<?php echo Router::url('/',true); ?>';
</script>

    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=235592626846699&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Facebook Pixel Code -->

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
            /*float: right;
            margin-right:12px;*/
        }
        .mktoButtonWrap{
            text-align: center;
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
        .subscriber_div {
            background: #0884a3 none repeat scroll 0 0;
            border-radius: 13px;
            color: #fff;
            font-size: 30px;
            font-weight: 500;
            margin: 23px 0;
            padding: 16px 0;
            text-align: center;
            text-decoration: underline;
        }


        .footer_sub {
            float: left;
            text-align: center;
            width: 88%;
            font-size: 30px;
        }
        body{color:#043B49;}
        .otp {
            text-align: center;
        }
        #otp_holder{
            display: none;
        }
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
                                                                           echo Router::url("/images/logo.png", true);
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
                <h4 class="subscriber_div">DOCTOR'S OWN APP WITH NEXTGEN PATIENT ENGAGEMENT FEATURES</h4>
                <p class="center_head_txt">
                    Your Branded Doctor Patient engagement App provide you an opportunity to connect, re-engage and retain patients as well as subscribers with your own Doctor's app.According to latest Mobile marketing and Geo-Localytics, push notifications and app based m-commerce platform can boost business engagement by 50%.
                </p>
                    </br>
                <H3  style="text-align: center; width: 100%;"><b>Fill out your information below to get your own DOCTOR PATIENT APP.</b></H3>

                </div>
            <div class="mktoForm clearfix" id="regForm1Form">
                <div id="lpeCDiv_13422" class="lpeCElement Demand_Gen_Global_Form"><span
                            class="lpContentsItem formSpan">


                            <?php

                            echo $result = $this->Session->flash('success');
                            if(!empty($result)){
                                echo $this->Session->flash('success'); ?>

                                <script type="text/javascript">

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
                            <?php }
                            ?>
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
                                <?php echo $this->Form->input('phone',array('type'=>'text', 'pattern'=>"[56789][0-9]{9}",'placeholder'=>'Mobile','label'=>false,'id'=>'mobile','class'=>'form-control phone cnt')); ?>
                            </div>
                            <div class="col-sm-6">
                                 <?php $type_array = $this->Custom->getDepartmentList("Doctor"); ?>
                                 <?php echo $this->Form->input('department_from',array('required'=>'required','empty'=>'Select Department','type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type cnt')); ?>
                          </div>

                        </div>

                        <div class="form-group" id="otp_holder">
                            <div class="col-sm-6 col-sm-offset-3">
                                <?php echo $this->Form->input('OTP',array('type'=>'text','placeholder'=>'OTP','label'=>false,'id'=>'mobile','class'=>'form-control otp cnt')); ?>
                            </div>
                        </div>

                        <div class="form-group">

                            <div class="col-sm-6 pull-left mktoButtonWrap mktoPaperMashup">
                                <?php echo $this->Form->submit('Generate OTP',array('class'=>'smt_btn','id'=>'generateOTPBtn')); ?>
                            </div>

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
                        <li>Maintain and upload patient medical records online for life long<br></li>
                        <li>Automated vaccination reminder & child growth <br></li>
                        <li>Communication seamlessly with your patients using chat & memos<br></li>
                        <li>Mobile based quick appointments and payment<br></li>
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
            <div class="footer_sub">Lifelong Patient Engagement <span>+</span> Brand Yourself <span>=</span> Doctor's own app</div>
            <ul class="social clearfix">
                <li class="social__item"><a href="https://www.facebook.com/MEngageApp"
                                            class="social__link facebook" target="_blank"><i class="fa fa-facebook"
                                                                                             aria-hidden="true"></i></a>
                </li>
                <li class="social__item"><a href="https://twitter.com/engage_patients" class="social__link twitter"
                                            target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                </li>
                <li class="social__item"><a href="https://www.instagram.com/mengagepatients/"
                                            class="social__link" style="background: #fb3958;" target="_blank"><i class="fa fa-instagram"
                                                                                                                 aria-hidden="true"></i></a>
                </li>
                <li class="social__item"><a href="https://www.linkedin.com/in/mengage-engaging-patients-2aa119154"
                                            class="social__link " style="background: #0077B5;" target="_blank"><i class="fa fa-linkedin"
                                                                                                                  aria-hidden="true"></i></a>
                </li>
            </ul>
        </div>
    </section>
    <section class="footer__btm">
        <div class="wrapper">
            <p class="copyright" style="width:100%;">© 2016-<span class="year">2017</span>, MEngage.in or its affiliates. All Rights
                Reserved.</p>
            <a href="<?php
            echo Router::url('/', true);
            ?>">
                <div class="mktoImg mktoGen" id="mkto_gen_footerLogo"><img class="header__logo-img" id="footerLogo"
                                                                           src="<?php
                                                                           echo Router::url('/images/mbfooter.png', true);
                                                                           ?>"
                                                                           mktoname="Footer Logo (250x100)"></div>
            </a>

        </div>
    </section>

    <script>
        $(document).on('input','.phone',function(){
            var str = $(this).val();
            $("#otp_holder").hide();
            var sd=parseInt(str);
            if(sd) {
                sd=sd.toString();
                $(this).val(sd);
            }
            else
            {
                $(this).val('');
            }
            var strLen = sd.length;
            if( strLen >= 10 )
            {
                $(this).val(sd.substr(0, 10));
            }
            else
            {
                $(".otp").val('');
            }

        });

        function getOTP(){
            $(".otp").val('');
            $("#otp_holder").show();
            timeVal = 60;
            $("#generateOTPBtn").attr('disabled',true);
            var timeInterval = setInterval(function(){

                if(timeVal > 0)
                {
                    timeVal = (timeVal - 1);
                    $("#generateOTPBtn").val(timeVal);

                }
                else
                {
                    $("#generateOTPBtn").removeAttr('disabled');
                    $("#generateOTPBtn").val('Generate OTP');
                    clearInterval(timeInterval);
                }
            }, 1000);

            var phoneVal = $(".phone").val();
            $.ajax({
                type: 'POST',
                url: baseurl + "homes/generate_request_otp",
                data: {phoneVal: phoneVal},
                success: function (data) {

                    otpVal =  parseInt(data);

                },
                error: function (data) {
                    alert("Sorry something went wrong on server.");
                }
            });

        }

        var buttonpressed;

        $(document).on('click',"[type='submit']",function() {
            buttonpressed = $(this).attr('id')
        });
        $(document).on('submit','#login_form',function(e) {
            e.preventDefault();
            if(buttonpressed == 'generateOTPBtn')
            {
                getOTP();
            }
            else
            {
                var enteredOTP = $(".otp").val();
                if(enteredOTP == otpVal)
                {
                    $('#login_form').unbind("submit");
                    $('#login_form')[0].submit();
                }
                else
                {
                    alert("OTP does not match! ");
                }
            }
        });



    </script>

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-90203409-1', 'auto');
        ga('send', 'pageview');

    </script>
</footer>
<style>



    .social__item {

        display: inline-block;
        width: 31px;
        height: 36px;
        margin: 0 0;
        float: left;
        padding: 2px;

    }
    .footer_sub span{
        color: blue;
    }
</style>
</body>
</html>


