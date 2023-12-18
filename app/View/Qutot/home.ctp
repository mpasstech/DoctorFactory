<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]-->
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>MEngage</title>
    <meta name="description" content="MEngage for doctor">
    <meta name="keywords" content=" appointment, clinic, dentist, doctor, health, health care, hospital, hospitality, laboratory, medical, medicine, patient">
    <meta name="author" content="mengage.in">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/bootstrap.css" >
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/style.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/colors/color1.css" id="colors">
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/animate.css">
    <link href="<?php echo Router::url('/',true); ?>home/icon/apple-touch-icon-48-precomposed.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="<?php echo Router::url('/',true); ?>home/icon/apple-touch-icon-32-precomposed.png" rel="apple-touch-icon-precomposed">
    <link href="<?php echo Router::url('/',true); ?>home/icon/favicon.png" rel="shortcut icon">
    <script src="<?php echo Router::url('/',true); ?>home/javascript/html5shiv.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/respond.min.js"></script>
    <style>

        .error{color: red;}
        .menu li .active{ color: #2bb0ef !important;}
        .flat-testimonials-single .avatar::before { position: unset !important; }
        .flat-appointment{padding-top: 12%;}

        .flat-title-slider{
            background:  #cecece99;
            color: #383838;

        }
        .whats_app, .whats_app:hover {
            background: #4FCE5D;
            color: #fff;
            padding: 0px 0px;
            border-radius: 8px;
            font-size: 3rem;
            height: 40px;
            width: 40px;
            position: fixed;
            bottom: 20px;
            right: 10px;
            text-align: center;
            z-index: 999;
        }
        .flat-contact-form .flat-contact-form .submit-wrap{
            margin-top: 0px !important;
        }
        .whats_app i{

            color: #fff;
            font-size: 1.8rem;

        }
        .top_box_space{
            height: 30px;
        }
        #contactform .msg-success{
            margin-top: -50px;
            position: relative;
            float: left;
            width: 100%;
        }
        .entry-page{
            border-top: 5px solid #5c78ff;
        }


        .flat-content-slider{
            background: #ffffffd1;
            padding: 5px 5px 6px 23px !important;
        }

        .nav-wrap li a, .nav-wrap li a:hover, .nav-wrap li a:focus{

            background: #5c78ff;

            border-radius: 50px;
            border: none;
            color: #fff !important;
            text-transform: capitalize !important;
        }

        .success_div{
            background-color: #61ef2b;
            color: #3b642b;
            font-weight: 700;
            font-family: "Lato", sans-serif;
            padding: 5px 15px 5px 10px;
            position: relative;
            display: block;
            top: -25px;
        }


        .appointment-form .send-wrap, .flat-contact-form .submit-wrap, .flat-progress-item{
            margin-bottom: 20px;
        }


    </style>
</head>
<body class="page-template-front-page header-sticky home" style="overflow-x: hidden;">
<div class="loader">
    <span class="loader1 block-loader"></span>
    <span class="loader2 block-loader"></span>
    <span class="loader3 block-loader"></span>
</div>
<!-- Boxed -->
<div class="boxed">
    <!-- Header -->
    <header id="header" class="header style1 clearfix">
        <div class="header-inner">
            <div id="logo" class="logo">
                <a href="/doctor" rel="home">
                    <img src="<?php echo Router::url('/',true); ?>home/images/logo.png" alt="image">

                </a>
            </div>
            <div class="vl"></div>


            <!-- /.logo -->
            <div class="nav-wrap">
                <div class="btn-menu open"></div><!-- //mobile menu button -->
                <nav id="mainnav" class="mainnav">
                    <ul class="menu">

                        <li><a href="<?php echo Router::url('/',true); ?>"  onclick="window.location = '<?php echo Router::url('/',true); ?>'"><i class="fa fa-home"></i> Doctor Apps</a></li>
                        <li><a href="<?php echo Router::url('/org-login',true); ?>"  onclick="window.location = '<?php echo Router::url('/org-login',true); ?>'"><i class="fa fa-sign-in"></i> Login</a></li>
                    </ul><!-- /.menu -->
                </nav><!-- /.mainnav -->
            </div><!-- /.nav-wrap -->

        </div><!-- /.header-inner -->
    </header><!-- /.header -->

    <!-- Slider -->
    <div class="tp-banner-container"id="home">
        <div class="tp-banner" >
            <ul>



                <li data-transition="random-static" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                    <img src="<?php echo Router::url('/',true); ?>home/images/slides/branding_app.png" alt="slider-image" />
                    <div class="tp-caption sfl flat-title-slider" data-x="5" data-y="80" data-speed="1000" data-start="800" data-easing="Power3.easeInOut">Brand Yourself Through<br>Your Own App</div>
                    <div class="tp-caption sfl flat-content-slider" data-x="15" data-y="400" data-speed="1000" data-start="800" data-easing="Power3.easeInOut">Doctor's Own App</div>
                </li>


                <li data-transition="random-static" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                    <img src="<?php echo Router::url('/',true); ?>home/images/slides/hybrid_opd.png" alt="slider-image" />

                    <div class="tp-caption sfl flat-title-slider" data-x="5" data-y="80" data-speed="1000" data-start="800" data-easing="Power3.easeInOut">Hybrid OPD with Telemedicine</div>


                </li>



                <li data-transition="random-static" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                    <img src="<?php echo Router::url('/',true); ?>home/images/slides/queue.png" alt="slider-image" />
                    <div class="tp-caption sfl flat-title-slider" data-x="5" data-y="80" data-speed="1000" data-start="800" data-easing="Power3.easeInOut">Virtual Queue <br> Automation</div>
                    <div class="tp-caption sfl flat-content-slider" data-x="15" data-y="400" data-speed="1000" data-start="800" data-easing="Power3.easeInOut">Patient Hate Wating</div>

                </li>

                <li data-transition="random-static" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                    <img src="<?php echo Router::url('/',true); ?>home/images/slides/virtual_receptionist.png" alt="slider-image" />
                    <div class="tp-caption sfl flat-title-slider" data-x="5" data-y="400" data-speed="1000" data-start="800" data-easing="Power3.easeInOut">Virtual Reception 24X7</div>

                </li>

                <li data-transition="random-static" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                    <img src="<?php echo Router::url('/',true); ?>home/images/slides/life_long.png" alt="slider-image" />
                    <div class="tp-caption sfl flat-title-slider" data-x="5" data-y="50" data-speed="1000" data-start="1000" data-easing="Power3.easeInOut">Lifelong Engagement<br>With Patient</div>
                    <div class="tp-caption sfl flat-content-slider" data-x="15" data-y="400" data-speed="1000" data-start="800" data-easing="Power3.easeInOut">Doctor's Own App</div>

                </li>





            </ul>
        </div>
    </div>

    <!-- Entry Page -->
    <div class="entry-page">




        <!-- Appointment -->
        <section  class="flat-row row-appointment nopad">
            <div class="container-fluid">
                <div class="row">

                    <div class="col-md-6 wrap-testimonials">
                        <div class="flat-testimonials" data-item="1" data-nav="true" data-dots="false" data-auto="true">
                            <div class="flat-testimonials-single">
                                <div class="col-md-12 doc_img" style="bottom: 70px !important;"><img src="<?php echo Router::url('/',true); ?>home/images/doc_images/saify.png"></div>
                                <div class="whisper">Great App.Smooth navigation.No hiccups.I Specially loved the Medical records and vaccination feature.Now i dont need to keep hard copy of patients data and i dont need to remember their vaccination dates.Thanks MEngage.</div>
                                <div class="avatar">
                                    <div class="name">Dr. Saify Arsiwala</div>
                                    <div class="position">Cardiologist</div>
                                </div>
                            </div>

                            <div class="flat-testimonials-single">
                                <div class="col-md-12 doc_img" style="bottom: 70px !important;"><img src="<?php echo Router::url('/',true); ?>home/images/doc_images/ashish.png" style="bottom: 100px !important;"></div>
                                <div class="whisper">I installed the app and that is it.The experience is too good.Now can share my knowledge with my patients via health tip and doctor's blog and my patients also liking my posts.These modules are too good.</div>
                                <div class="avatar">
                                    <div class="name">Dr. Ashish Agarwal</div>
                                    <div class="position">Pediatrician</div>
                                </div>
                            </div>
                            <div class="flat-testimonials-single">
                                <div class="col-md-12 doc_img" style="bottom: 70px !important;"><img src="<?php echo Router::url('/',true); ?>home/images/doc_images/raturi.png" style="bottom: 100px !important;"></div>
                                <div class="whisper">Very nice application.This application provides option to store the documents and prescription and patients can do chat with me any time regarding their presecription and health.</div>
                                <div class="avatar">
                                    <div class="name">Dr. Bharat Mohan Raturi</div>
                                    <div class="position">Pediatrician</div>
                                </div>
                            </div>
                            <div class="flat-testimonials-single">
                                <div class="col-md-12 doc_img" style="bottom: 70px !important;"><img src="<?php echo Router::url('/',true); ?>home/images/doc_images/ajesh_mathuria.png" style="bottom: 100px !important;"></div>
                                <div class="whisper">Thanks MEngage team for making this too good application.This app made my life so easy.Now patients do not fight for appointment and they dont need to be in queue for their appointment.They can book appointment from their home and come for counsultation on their booked time.</div>
                                <div class="avatar">
                                    <div class="name">Dr. Rajesh Mathuria</div>
                                    <div class="position">Pediatrician</div>
                                </div>
                            </div>




                        </div><!-- /.flat-testimonials-->
                    </div><!-- /.col-md-6-->
                    <div id="contact" class="col-md-6">
                        <div class="flat-divider top_box_space"></div>
                        <div class="title-section">
                            <h1 class="title" data-text="G">Get In <span>Touch</span></h1>
                        </div><!-- /.title-section -->
                        <form class="flat-contact-form for-full-width" id="contactFormNew" method="post" action="<?php echo Router::url('/',true); ?>contact_us_ajax">
                            <div class="field clearfix">
                                <div class="wrap-type-input">
                                    <div class="input-wrap name">
                                        <input type="text" value="" tabindex="1" oninvalid="this.setCustomValidity('Please enter your name')"  oninput="this.setCustomValidity('')" placeholder="Enter your name *" name="data[AppEnquiry][name]" id="name" required>
                                    </div>
                                    <div class="input-wrap last phone">
                                        <input type="number" value="" tabindex="2" oninvalid="this.setCustomValidity('Please enter your 10 digit mobile number')"  oninput="this.setCustomValidity('')" placeholder="Enter your phone number *" name="data[AppEnquiry][phone]" id="phone" required>
                                    </div>
                                    <div class="input-wrap email">
                                        <input type="email" value="" tabindex="3" placeholder="Enter your email" name="data[AppEnquiry][email]" id="email-contact">
                                    </div>

                                </div>
                                <div class="textarea-wrap">
                                    <textarea class="type-input" tabindex="4" placeholder="Your message here...." name="data[AppEnquiry][message]" id="message-contact"></textarea>
                                </div>
                            </div>
                            <div class="submit-wrap">
                                <button class="flat-button">Send Message<i class="icon-envelope-open icons"></i></button>
                            </div>

                            <?php if(!empty($un)){ ?>
                                <p style="text-align: center;" id="subscribe_pera">
                                    If you wish to stop receiving messages from us, please unsubscribe
                                    <button data-m="<?php echo $un; ?>" type="button" id="unsubscribe_btn" style="text-transform: capitalize;" class="btn btn-danger"><i class="fa fa-ban" aria-hidden="true"></i> Unsubscribe</button>
                                </p>
                            <?php } ?>

                        </form><!-- /.comment-form -->
                    </div>

                </div><!-- /.row-->
            </div><!-- /.container-fluid -->
        </section>



    </div><!-- /.Entry Page -->



    <!-- Bottom -->
    <div class="bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="copyright">
                        <p style="float: left;">


                            <a href="<?php echo Router::url('/privacy',true); ?>" >Privacy Policy</a> |
                            <a href="<?php echo Router::url('/term',true); ?>" >Terms of Use</a> |
                            <a href="<?php echo Router::url('/refund',true); ?>" >Refund Policy</a>


                        </p>
                        <p style="float: left;text-align: center;width: 100%;">Copyright ©2016
                            <a href="http://mengage.co.in" target = "_blank"> MEngage.</a> All Rights Reserved.</p>
                    </div>
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div>
</div><!-- /.boxed -->



<a style="display: none;" href="https://api.whatsapp.com/send?phone=918955004049" class="whats_app">
    <i class="zmdi zmdi-whatsapp"></i>
</a>



<!-- Javascript -->
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.easing.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/owl.carousel.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.isotope.min.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/imagesloaded.min.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery-waypoints.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery-ui-datepicker.js"></script>
<!-- Revolution Slider -->
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.themepunch.tools.min.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.themepunch.revolution.min.js"></script>
<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/main.js"></script>







<script>
    $(document).ready(function (){

        var org_value = "";

        function checkOrg(){
            var mob = $("#mobile").val();

            if(mob!="" && typeof intlTelInputUtils !== 'undefined'){
                mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
                $.ajax({
                    url:"<?php echo Router::url('/',true); ?>app_admin/get_org",
                    type:'POST',
                    data:{mob:mob},
                    /*beforeSend:function(){
                     $(".loading_mob").show();
                     }, */
                    success:function(res){
                        if(res!=0){
                            /* $('.register_label').fadeOut(); */
                            $('#login_btn').prop('disabled',false);
                            $(".slug_drp").html(res);
                            if(org_value!=""){
                                $(".slug_drp").val(org_value);
                            }
                        }else{
                            /*$('.register_label').fadeIn(); */
                            $('#login_btn').prop('disabled',true);
                            $(".slug_drp").html("<option>Select Organization</option>");

                        }

                        /*$(".loading_mob").hide(); */
                    },
                    error:function () {
                        /* $(".loading_mob").hide(); */
                    }
                })
            }

        }


        $("#unsubscribe_btn").on("click", function() {
            var mob = $(this).attr('data-m');
            $btn = $(this);
            $.ajax({
                url:"<?php echo Router::url('/homes/unsubscribe',true); ?>",
                type:'POST',
                data:{mob:mob},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> Wait..');
                },
                success:function(res){
                    $btn.button('reset');
                    if(res=='SUCCESS'){
                        $("#subscribe_pera").text("You are unsubscribed successfully").css('color','green');
                    }
                },
                error:function () {

                }
            })
        });





        if (window.location.href.indexOf("#contanct") > -1) {
            $(".menu li a[href='#contact']").trigger('click');
        }
        $('.tp-banner').show().revolution({
            dottedOverlay:"none",
            delay:6000,
            startwidth:1200,
            startheight:650,
            hideThumbs:200,

            thumbWidth:100,
            thumbHeight:50,
            thumbAmount:5,

            navigationType:"none",
            navigationArrows:"solo",
            navigationStyle:"square",

            touchenabled:"on",
            onHoverStop:"on",

            swipe_velocity: 0.7,
            swipe_min_touches: 1,
            swipe_max_touches: 1,
            drag_block_vertical: false,

            parallax:"mouse",
            parallaxBgFreeze:"on",
            parallaxLevels:[7,4,3,2,5,4,3,2,1,0],

            keyboardNavigation:"off",

            navigationHAlign:"center",
            navigationVAlign:"bottom",
            navigationHOffset:0,
            navigationVOffset:20,

            soloArrowLeftHalign:"left",
            soloArrowLeftValign:"center",
            soloArrowLeftHOffset:20,
            soloArrowLeftVOffset:0,

            soloArrowRightHalign:"right",
            soloArrowRightValign:"center",
            soloArrowRightHOffset:20,
            soloArrowRightVOffset:0,

            shadow:0,
            fullWidth:"on",
            fullScreen:"off",

            spinner:"spinner4",

            stopLoop:"off",
            stopAfterLoops:-1,
            stopAtSlide:-1,

            shuffle:"on",

            autoHeight:"off",
            forceFullWidth:"off",

            hideThumbsOnMobile:"off",
            hideNavDelayOnMobile:1500,
            hideBulletsOnMobile:"off",
            hideArrowsOnMobile:"off",
            hideThumbsUnderResolution:0,

            hideSliderAtLimit:0,
            hideCaptionAtLimit:0,
            hideAllCaptionAtLilmit:0,
            startWithSlide:0,
            fullScreenOffsetContainer: ""
        });




        $(document).on("submit","#contactFormNew", function (e) {
            e.preventDefault();
            var $form = $("#contactFormNew"),
                str = $form.serialize(),
                loading = $('<div />', { 'class': 'loading' });
            $.ajax({
                type: "POST",
                url:  $form.attr('action'),
                data: str,
                beforeSend: function () {
                    $form.find('.send-wrap').append(loading);
                },
                success: function( msg ) {
                    var result, cls;
                    if ( msg == 'Success' ) {
                        result = 'Thankyou for connect with us we will contact you shortly';
                        cls = 'msg-success';
                    } else {
                        result = 'Error sending email.';
                        cls = 'msg-error';
                    }

                    $form.prepend(
                        $('<div />', {
                            'class': 'success_div ' + cls,
                            'text' : result
                        }).append(
                            $('<a class="close" href="#"></a>')
                        )
                    );

                    setTimeout(function () {
                        $(".success_div").fadeOut('slow');
                    },4000);

                    $form.find(':input').not('.submit').val('');
                },
                complete: function (xhr, status, error_thrown) {
                    $form.find('.loading').remove();
                }
            });

        });



    });
</script>

</body>

</html>