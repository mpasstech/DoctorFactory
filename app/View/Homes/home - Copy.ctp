<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US"><!--<![endif]-->
<head>
    <!-- Basic Page Needs -->
    <meta charset="utf-8">
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <title>MEngage</title>
    <meta name="description" content="MediCare is a minimal, clean and modern One Page HTML Template for dental clinics and any other medical and healthcare related businesses, such as hospitals, research centers, or pharmacies.">
    <meta name="keywords" content=" appointment, clinic, dentist, doctor, health, health care, hospital, hospitality, laboratory, medical, medicine, patient">
    <meta name="author" content="themesflat.com">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Bootstrap  -->
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/bootstrap.css" >

    <!-- Theme Style -->
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/style.css">

    <!-- Responsive -->
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/responsive.css">

    <!-- Colors -->
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/colors/color1.css" id="colors">
	
	<!-- Animation Style -->
    <link rel="stylesheet" type="text/css" href="<?php echo Router::url('/',true); ?>home/stylesheets/animate.css">

    <!-- Favicon and touch icons  -->
    <link href="<?php echo Router::url('/',true); ?>home/icon/apple-touch-icon-48-precomposed.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="<?php echo Router::url('/',true); ?>home/icon/apple-touch-icon-32-precomposed.png" rel="apple-touch-icon-precomposed">
    <link href="<?php echo Router::url('/',true); ?>home/icon/favicon.png" rel="shortcut icon">
	<link rel="stylesheet" href="<?php echo Router::url('/',true); ?>home/javascript/intltel/css/intlTelInput.css">

    <!--[if lt IE 9]>
        <script src="javascript/html5shiv.js"></script>
        <script src="javascript/respond.min.js"></script>
    <![endif]-->
	<style>
		#images2{ display: none;}
		#images3{ display: none;}
		#images4{ display: none;}
		#images5{ display: none;}
		#images6{ display: none;}
		.msg-error{color:red;}
		#otpform{ display: none; }
		.error{color: red;}
		.menu li .active{ color: #2bb0ef !important;}
		.flat-testimonials-single .avatar::before { position: unset !important; }
		.flat-appointment{padding-top: 12%;}
	</style>
</head>                                 
<body class="page-template-front-page header-sticky home">    
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
                </div><!-- /.logo -->
                <div class="nav-wrap">
                    <div class="btn-menu open"></div><!-- //mobile menu button -->
                    <nav id="mainnav" class="mainnav">
                        <ul class="menu"> 
                            <li class="home"><a href="#home" class="active">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#features">Features</a></li>
							<!--li><a href="#culture">Culture</a></li-->
                            <!--li><a href="#team">Our Team</a></li-->
                            <li><a href="#account">Account</a></li>
                            <li><a href="javascript:void(0);" onclick="window.location ='<?php echo Router::url('/doctor/doctor_list',true); ?>'" >Our Doctors</a></li>
                            <!--li><a href="#blog">Blog</a></li-->
                            <li><a href="#contact">Contact</a></li>
							<li><a href="<?php echo Router::url('/org-login',true); ?>"  onclick="window.location = '<?php echo Router::url('/org-login',true); ?>'">Login</a></li>
                        </ul><!-- /.menu -->
                    </nav><!-- /.mainnav -->
                </div><!-- /.nav-wrap -->
				
            </div><!-- /.header-inner --> 
        </header><!-- /.header --> 

        <!-- Slider -->
        <div class="tp-banner-container"id="home">
            <div class="tp-banner" >
                <ul>
                    <li data-transition="slideup" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                        <img src="<?php echo Router::url('/',true); ?>home/images/slides/1.png" alt="slider-image" />

                        <div class="tp-caption sfl flat-title-slider" data-x="15" data-y="276" data-speed="1000" data-start="1000" data-easing="Power3.easeInOut">Lifelong Engagement<br>With Patient</div>

                        <div class="tp-caption sfl flat-content-slider" data-x="15" data-y="580" data-speed="1000" data-start="1500" data-easing="Power3.easeInOut">Doctor's Own App</div>

                        <div class="tp-caption flat-scroll-btn animated bounce" data-x="585" data-y="953"><i class="icon-mouse icons"></i></div>
                        
                    </li>

                    <li data-transition="random-static" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                        <img src="<?php echo Router::url('/',true); ?>home/images/slides/2.png" alt="slider-image" />

						<div class="tp-caption sfl flat-title-slider" data-x="15" data-y="276" data-speed="1000" data-start="1000" data-easing="Power3.easeInOut">Brand Yourself Through<br>Your Own App</div>

                        <div class="tp-caption sfl flat-content-slider" data-x="15" data-y="580" data-speed="1000" data-start="1500" data-easing="Power3.easeInOut">Doctor's Own App</div>

                        <div class="tp-caption flat-scroll-btn animated bounce" data-x="585" data-y="953"><i class="icon-mouse icons"></i></div>
						
                    </li>
					
					<li data-transition="slideup" data-slotamount="7" data-masterspeed="1000" data-saveperformance="on">
                        <img src="<?php echo Router::url('/',true); ?>home/images/slides/communication.png" alt="slider-image" />

						<div class="tp-caption sfl flat-title-slider" data-x="15" data-y="276" data-speed="1000" data-start="1000" data-easing="Power3.easeInOut">Ease Of Communication<br>With Doctors</div>

                        <div class="tp-caption sfl flat-content-slider" data-x="15" data-y="580" data-speed="1000" data-start="1500" data-easing="Power3.easeInOut">One To One Chat + Report Sharing</div>

                        <div class="tp-caption flat-scroll-btn animated bounce" data-x="585" data-y="953"><i class="icon-mouse icons"></i></div>
						
                    </li>
					
                </ul>
            </div>
        </div>
       
        <!-- Entry Page -->
        <div class="entry-page">

            <!-- About -->
            <section id="about" class="flat-row row-about">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="flat-divider d6px"></div>
                            <div class="singleimages-border">
                                <img src="<?php echo Router::url('/',true); ?>home/images/blog/about.png" alt="images">
                            </div>
                        </div><!-- /.col-md-5 -->
                        <div class="col-md-7">
                            <div class="about-wrap padding-left61px">
                                <div class="title-box">
                                    <h4 class="sub-title">HI THERE! WELCOME TO MEngage</h4>
                                    <h2 class="main-title">Who We Are</h2>
                                </div><!-- /.title-box -->
                                
								<p>We believe that the relationship between patients and doctors is at the core of medical ethics, serving as an anchor for many of the most important debates in field. It’s surprising that so many young people want to give back to humanity in such a grand and noble way.</p> 

								<p>We develop  mass consumer engagement application for doctors on both android and IOS platforms. It is a team of wide variety of technology individuals, Start-up Entrepreneur’s and Co-founders. The organisation believes in building never-ending relationships with customers by sharing professional and ethical values.</p>  

								<p>Our journey started by interacting and developing relationship with pediatric doctors. Presently we are completed with pediatric ones and are moving ahead.  Meet some of our doctors here.</p>
								
                                <div class="flat-divider d26px"></div>                        
                                <!--a class="flat-button" href="#team">Our Team 
                                <i class="material-icons">supervisor_account</i></a-->
                            </div><!-- /.about-wrap -->
                        </div><!-- /.col-md-7 -->
                    </div><!-- /.row -->
                </div><!-- /.container -->
				
				                    <div class="row margin-top93px">
                    
						 <div class="col-md-2 col-md-offset-1">
                            <div class="flat-counter">
                                <div class="icon-counter">
                                    <i class="zmdi zmdi-male-female"></i>
                                </div>
                                <div class="content-counter">
                                    <div class="numb-count" data-to="7000" data-speed="2000" data-waypoint-active="yes">7000</div>
                                    <div class="name-count">Happy Patients</div>
                                </div>
                            </div>
                        </div>

                         <div class="col-md-2">
                            <div class="flat-counter ">
                                <div class="icon-counter">
                                    <i class="material-icons">healing</i>
                                </div>
                                <div class="content-counter">
                                    <div class="numb-count" data-to="3" data-speed="2000" data-waypoint-active="yes">3</div>
                                    <div class="name-count">Cities</div>
                                </div>
                            </div>
                        </div>

                         <div class="col-md-2">
                            <div class="flat-counter ">
                                <div class="icon-counter">
                                    <i class="fa fa-thumbs-up"></i>
                                </div>
                                <div class="content-counter facebook-like">
                                    <div class="numb-count" data-to="13" data-speed="2000" data-waypoint-active="yes">13</div>
                                    <div class="name-count">Facebook Likes</div>
                                </div>
                            </div>   
                        </div>

                         <div class="col-md-2">
                            <div class="flat-counter ">
                                <div class="icon-counter">
                                    <i class="material-icons">favorite_border</i>
                                </div>
                                <div class="content-counter">
                                    <div class="numb-count" data-to="60" data-speed="2000" data-waypoint-active="yes">60</div>
                                    <div class="name-count">Our Doctor</div>
                                </div>
                            </div>
                        </div>
                    
						 <div class="col-md-2">
                            <div class="flat-counter ">
                                <div class="icon-counter">
                                    <i class="material-icons">done_all</i>
                                </div>
                                <div class="content-counter">
                                    <div class="numb-count" data-to="400" data-speed="2000" data-waypoint-active="yes">400</div>
                                    <div class="name-count">Doctors In The Pipeline</div>
                                </div>
                            </div>
                        </div>
                    
					</div><!-- /.row -->
				
            </section>

            <!-- Our Advantages -->
            <section id="features" class="flat-row row-advantage">
                <div class="container">
                    <div class="title-section">
                        <h1 class="title" data-text="F">Our <span>Features</span></h1>
                    </div><!-- /.title-section -->
                    <div class="row">
                        <div class="col-md-6">  
                            <div class="row">                  
                                <div class="flat-tabs advantage bg-white-after">
                                    <ul class="menu-tab ">
                                        <li class="active"><a href="#" class="get_image" get-image="images1">I</a></li>
                                        <li><a href="#" class="get_image" get-image="images2">II</a></li>
                                        <li><a href="#" class="get_image" get-image="images3">III</a></li>
                                        <li><a href="#" class="get_image" get-image="images4">IV</a></li>
                                        <li><a href="#" class="get_image" get-image="images5">V</a></li>
										<li><a href="#" class="get_image" get-image="images6">VI</a></li>									
                                    </ul><!-- /.menu-tab -->
                                    <div class="content-tab">
                                        <div class="content-inner">
                                            <div class="title-content">MEDICAL RECORDS</div>
                                            <p>You can store your medical records on cloud and share them too. You can maintain the privacy as it is ultra-fast and secure.</p>
                                            
                                        </div>
                                        <div class="content-inner">
                                            <div class="title-content">VACCINATION AND CHILD GROWTH</div>
                                            <p>There is auto-reminder for vaccination and simultaneously you can track child’s height, weight and head circumference.</p>
                                            
                                        </div>
                                        <div class="content-inner">
                                            <div class="title-content">MOBILE APPOINTMENT</div>
                                            <p>Quick and easy appointment with queue system management is also featured. You can also avail multiple doctors with multiple locations. A special reminder for an appointment is also available.</p>
                                            
                                        </div>  
                                        <div class="content-inner">
                                            <div class="title-content">EASE OF COMMUNICATION AND REMINDER</div>
                                            <p>To reduce the difficulty in communication you can also chat with your patients or you can add support staff to do the same. An option of MEMOS provides you to add prescription in medical records. Also, an easy reminder for vaccination is also there.</p>
                                            
                                        </div>
                                        <div class="content-inner">
                                            <div class="title-content">HEALTH TIPS AND DOCTOR’S BLOG</div>
                                            <p>Regular health tips can keep your patients engaged via knowledge sharing. Also, you can add your own blogs in form of text, image, video, audio.</p>
                                            
                                        </div>
										<div class="content-inner">
                                            <div class="title-content">PAYMENT AND CONSENT</div>
                                            <p>You can also pay online to doctor. You can take digital consent approval with patients and can create multiple consent templates.</p>
                                            
                                        </div>
                                    </div><!-- /.content-tab -->
                                </div><!-- /.flat-tabs -->
                            </div>
                        </div><!-- /.col-md-6 medician_reminder -->
                        <div class="col-md-6 flat-bg-white">
                            <div class="row">
                                <img src="<?php echo Router::url('/',true); ?>home/images/advantage/medical_record.png" alt="images1" id="images1">
								<img src="<?php echo Router::url('/',true); ?>home/images/advantage/vaccination.png" alt="images2" id="images2">
								<img src="<?php echo Router::url('/',true); ?>home/images/advantage/appointment.png" alt="images3" id="images3">
								<img src="<?php echo Router::url('/',true); ?>home/images/advantage/medician_reminder.png" alt="images4" id="images4">
								<img src="<?php echo Router::url('/',true); ?>home/images/advantage/health_tip.png" alt="images5" id="images5">
								<img src="<?php echo Router::url('/',true); ?>home/images/advantage/Payment.png" alt="images6" id="images6">
                            </div>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </section>

			
			<!--section id="culture" class="flat-row row-portfolio">
                <div class="container">
                    <div class="col-md-12">
                        <div class="title-section">
                            <h1 class="title" data-text="P">Our<span> Culture</span></h1>
                        </div>
                    </div>               
                </div>       
                
				<div class="flat-portfolio v1">  
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">                           
                                <ul class="portfolio-filter">
                                    <li class="active"><a data-filter="*" href="#">All</a></li>
                                    <li><a data-filter=".dental" href="#">Dental</a></li>
                                    <li><a data-filter=".webdesign" href="#">Web Design</a></li>
                                    <li><a data-filter=".photoshop" href="#">Photoshop</a></li>
                                    <li><a data-filter=".videos" href="#">Videos </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-wrap clearfix">
                        <div class="item dental webdesign photoshop videos">
                            <img src="images/portfolio/v1.1.jpg" alt="image" class="transition">
                            <div class="item-caption">
                                <h4 class="title-portfolio">
                                    <a href="#">Cosmetic Dentistry</a>
                                </h4>
                                <ul class="categories">
                                    <li><a href="#">Health Dental</a></li>                            
                                </ul>
                            </div>                       
                            <div class="overlay">                            
                            </div>
                            <a href="#">
                                <div class="link">
                                    <img src="images/icon/icon-row-portfolio.png" alt="images">
                                </div>
                            </a>
                        </div>
                        <div class="item photoshop videos dental">                                     
                            <img src="images/portfolio/v1.2.jpg" alt="image" class="transition">
                            <div class="item-caption">
                                <h4 class="title-portfolio">
                                    <a href="#">Cosmetic Dentistry</a>
                                </h4>
                                <ul class="categories">
                                    <li><a href="#">Health Dental</a></li>                            
                                </ul>
                            </div>                       
                            <div class="overlay">                            
                            </div>
                            <a href="#">
                                <div class="link">
                                    <img src="images/icon/icon-row-portfolio.png" alt="images">
                                </div>
                            </a>
                        </div>
                        <div class="item dental">                                     
                            <img src="images/portfolio/v1.3.jpg" alt="image" class="transition">
                            <div class="item-caption">
                                <h4 class="title-portfolio">
                                    <a href="#">Cosmetic Dentistry</a>
                                </h4>
                                <ul class="categories">
                                    <li><a href="#">Health Dental</a></li>                            
                                </ul>
                            </div>                       
                            <div class="overlay">                            
                            </div>
                            <a href="#">
                                <div class="link">
                                    <img src="images/icon/icon-row-portfolio.png" alt="images">
                                </div>
                            </a>
                        </div>
                        <div class="item dental videos">                                     
                            <img src="images/portfolio/v1.4.jpg" alt="image" class="transition">
                            <div class="item-caption">
                                <h4 class="title-portfolio">
                                    <a href="#">Cosmetic Dentistry</a>
                                </h4>
                                <ul class="categories">
                                    <li><a href="#">Health Dental</a></li>                            
                                </ul>
                            </div>                       
                            <div class="overlay">                            
                            </div>
                            <a href="#">
                                <div class="link">
                                    <img src="images/icon/icon-row-portfolio.png" alt="images">
                                </div>
                            </a>
                        </div>
                        <div class="item dental webdesign w50">                                     
                            <img src="images/portfolio/v1.5.jpg" alt="image" class="transition">
                            <div class="item-caption">
                                <h4 class="title-portfolio">
                                    <a href="#">Cosmetic Dentistry</a>
                                </h4>
                                <ul class="categories">
                                    <li><a href="#">Health Dental</a></li>                            
                                </ul>
                            </div>                       
                            <div class="overlay">                            
                            </div>
                            <a href="#">
                                <div class="link">
                                    <img src="images/icon/icon-row-portfolio.png" alt="images">
                                </div>
                            </a>
                        </div>
                        <div class="item dental webdesign photoshop">                                     
                            <img src="images/portfolio/v1.6.jpg" alt="image" class="transition">
                            <div class="item-caption">
                                <h4 class="title-portfolio">
                                    <a href="#">Cosmetic Dentistry</a>
                                </h4>
                                <ul class="categories">
                                    <li><a href="#">Health Dental</a></li>                            
                                </ul>
                            </div>                       
                            <div class="overlay">                            
                            </div>
                            <a href="#">
                                <div class="link">
                                    <img src="images/icon/icon-row-portfolio.png" alt="images">
                                </div>
                            </a>
                        </div>                
                    </div>
                </div>  
            </section-->

			
            <!-- Our team -->
            <!--section id="team" class="flat-row row-our-team">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="title-section">
                                <h1 class="title" data-text="T">Our<span> Team</span></h1>
                            </div>
                        </div>     
                    </div>  
                    <div class="row">
                        <div class="col-md-12">
                            <div class="flat-tabs member">
                                <ul class="menu-tab">
                                    <li class="active"><a href="#"><img src="images/advantage/appointment.png" alt="images"></a></li>
                                    <li><a href="#"><img src="images/advantage/appointment.png" alt="images"></a></li>
                                    <li><a href="#"><img src="images/advantage/appointment.png" alt="images"></a></li>
									<li><a href="#"><img src="images/advantage/appointment.png" alt="images"></a></li>
									<li><a href="#"><img src="images/advantage/appointment.png" alt="images"></a></li>
									<li><a href="#"><img src="images/advantage/appointment.png" alt="images"></a></li>
									<li><a href="#"><img src="images/advantage/appointment.png" alt="images"></a></li>																		
                                </ul>
                                <div class="content-tab">
                                    <div class="content-inner">
                                        <div class="flat-team style1">                            
                                            <div class="avatar">                               
                                                <img src="images/member/1.jpg" alt="image">
                                            </div>                        
                                            <div class="content">
                                                <h3 class="name">Dr: Mike Roger1</h3>
                                                <p class="position">Principal Dentist</p>
                                                
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh lorem, tristique a augue ac, vestibulum vestibulum nunc. In ultricies id risus tincidunt sed amet ultrices</p>

                                                <p>Nulla venenatis erat feugiat condimentum varius. In feugiat in nibh non scelerisque. Aenean non ante feugiat, iaculis aliquam leo.</p>
                                                <ul class="social">
                                                    <li class="facebook">
                                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                    <li class="twitter">
                                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                    <li class="dribbble">
                                                        <a href="#"><i class="fa fa-dribbble"></i></a>
                                                    </li>                                    
                                                    <li class="pinterest">
                                                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div
                                    </div>
                                    <div class="content-inner">
                                        <div class="flat-team style1">                            
                                            <div class="avatar">                               
                                                <img src="images/member/2.jpg" alt="image">
                                            </div>                        
                                            <div class="content">
                                                <h3 class="name">Dr: Mike Roger2</h3>
                                                <p class="position">Principal Dentist</p>
                                                
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh lorem, tristique a augue ac, vestibulum vestibulum nunc. In ultricies id risus tincidunt sed amet ultrices</p>

                                                <p>Nulla venenatis erat feugiat condimentum varius. In feugiat in nibh non scelerisque. Aenean non ante feugiat, iaculis aliquam leo.</p>
                                                <ul class="social">
                                                    <li class="facebook">
                                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                    <li class="twitter">
                                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                    <li class="dribbble">
                                                        <a href="#"><i class="fa fa-dribbble"></i></a>
                                                    </li>                                    
                                                    <li class="pinterest">
                                                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="content-inner">
                                        <div class="flat-team style1">                            
                                            <div class="avatar">                               
                                                <img src="images/member/3.jpg" alt="image">
                                            </div>                        
                                            <div class="content">
                                                <h3 class="name">Dr: Mike Roger3</h3>
                                                <p class="position">Principal Dentist</p>
                                                
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh lorem, tristique a augue ac, vestibulum vestibulum nunc. In ultricies id risus tincidunt sed amet ultrices</p>

                                                <p>Nulla venenatis erat feugiat condimentum varius. In feugiat in nibh non scelerisque. Aenean non ante feugiat, iaculis aliquam leo.</p>
                                                <ul class="social">
                                                    <li class="facebook">
                                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                    <li class="twitter">
                                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                    <li class="dribbble">
                                                        <a href="#"><i class="fa fa-dribbble"></i></a>
                                                    </li>                                    
                                                    <li class="pinterest">
                                                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>   
                                    <div class="content-inner">
                                        <div class="flat-team style1">                            
                                            <div class="avatar">                               
                                                <img src="images/member/3.jpg" alt="image">
                                            </div>                        
                                            <div class="content">
                                                <h3 class="name">Dr: Mike Roger4</h3>
                                                <p class="position">Principal Dentist</p>
                                                
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh lorem, tristique a augue ac, vestibulum vestibulum nunc. In ultricies id risus tincidunt sed amet ultrices</p>

                                                <p>Nulla venenatis erat feugiat condimentum varius. In feugiat in nibh non scelerisque. Aenean non ante feugiat, iaculis aliquam leo.</p>
                                                <ul class="social">
                                                    <li class="facebook">
                                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                    <li class="twitter">
                                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                    <li class="dribbble">
                                                        <a href="#"><i class="fa fa-dribbble"></i></a>
                                                    </li>                                    
                                                    <li class="pinterest">
                                                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>  
                                    <div class="content-inner">
                                        <div class="flat-team style1">                            
                                            <div class="avatar">                               
                                                <img src="images/member/3.jpg" alt="image">
                                            </div>                        
                                            <div class="content">
                                                <h3 class="name">Dr: Mike Roger5</h3>
                                                <p class="position">Principal Dentist</p>
                                                
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh lorem, tristique a augue ac, vestibulum vestibulum nunc. In ultricies id risus tincidunt sed amet ultrices</p>

                                                <p>Nulla venenatis erat feugiat condimentum varius. In feugiat in nibh non scelerisque. Aenean non ante feugiat, iaculis aliquam leo.</p>
                                                <ul class="social">
                                                    <li class="facebook">
                                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                    <li class="twitter">
                                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                    <li class="dribbble">
                                                        <a href="#"><i class="fa fa-dribbble"></i></a>
                                                    </li>                                    
                                                    <li class="pinterest">
                                                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>  
                                    <div class="content-inner">
                                        <div class="flat-team style1">                            
                                            <div class="avatar">                               
                                                <img src="images/member/3.jpg" alt="image">
                                            </div>                        
                                            <div class="content">
                                                <h3 class="name">Dr: Mike Roger6</h3>
                                                <p class="position">Principal Dentist</p>
                                                
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh lorem, tristique a augue ac, vestibulum vestibulum nunc. In ultricies id risus tincidunt sed amet ultrices</p>

                                                <p>Nulla venenatis erat feugiat condimentum varius. In feugiat in nibh non scelerisque. Aenean non ante feugiat, iaculis aliquam leo.</p>
                                                <ul class="social">
                                                    <li class="facebook">
                                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                    <li class="twitter">
                                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                    <li class="dribbble">
                                                        <a href="#"><i class="fa fa-dribbble"></i></a>
                                                    </li>                                    
                                                    <li class="pinterest">
                                                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>  
                                    <div class="content-inner">
                                        <div class="flat-team style1">                            
                                            <div class="avatar">                               
                                                <img src="images/member/3.jpg" alt="image">
                                            </div>                        
                                            <div class="content">
                                                <h3 class="name">Dr: Mike Roger7</h3>
                                                <p class="position">Principal Dentist</p>
                                                
                                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam nibh lorem, tristique a augue ac, vestibulum vestibulum nunc. In ultricies id risus tincidunt sed amet ultrices</p>

                                                <p>Nulla venenatis erat feugiat condimentum varius. In feugiat in nibh non scelerisque. Aenean non ante feugiat, iaculis aliquam leo.</p>
                                                <ul class="social">
                                                    <li class="facebook">
                                                        <a href="#"><i class="fa fa-facebook"></i></a>
                                                    </li>
                                                    <li class="twitter">
                                                        <a href="#"><i class="fa fa-twitter"></i></a>
                                                    </li>
                                                    <li class="dribbble">
                                                        <a href="#"><i class="fa fa-dribbble"></i></a>
                                                    </li>                                    
                                                    <li class="pinterest">
                                                        <a href="#"><i class="fa fa-pinterest-p"></i></a>
                                                    </li>
                                                </ul>
                                            </div> 
                                        </div>
                                    </div>  
                                                     
                                </div>
                            </div>

                            
                        </div>
                    </div>
                </div>
            </section-->

            <!-- Appointment -->
            <section id="account" class="flat-row row-appointment nopad">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="flat-divider d100px"></div>
                            <div class="title-section">
                                <h1 class="title" data-text="A">Login To <span>Account</span></h1>                            
                            </div><!-- /.title-section -->
                            <div class="flat-appointment">
                                <form id="login_form" onsubmit="return false;" class="appointment-form contact-form for-full-width" method="post" action="<?php echo Router::url('/',true); ?>app_admin/org_login_ajax">
                                    <div class="note"></div>                                
                                    <div class="input-wrap wrap-left mobile">
										<input style="z-index: 1;border-color: red;" type="text" value="" placeholder="Mobile*" name="data[User][mobile]" id="mobile" required>
                                    </div>
                                    <div class="input-wrap wrap-right org_type">
										<!--select name="data[User][org_type]" id="org_type" required>
											
										</select -->
										<select name="data[User][slug]" class="slug_drp" placeholder="" id="text"required>
											<option value="">Orgnization*</option>
										</select>
										
                                    </div>
                                    <div class="input-wrap wrap-left password">
                                        <input type="password" value="" placeholder="Password*" name="data[User][password]" id="UserPassword" required>
                                    </div>
                                    <div class="send-wrap">
									
                                        <button class="flat-button border" type="submit" id="login_btn" disabled>Login <i class="material-icons">mode_edit</i></button>
                                    </div>
                                </form><!-- /.appointment-form -->
                            
							</div>
                        </div><!-- /.col-md-6-->
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
                    </div><!-- /.row-->
                </div><!-- /.container-fluid -->
            </section>

            <!-- Latest Blogs -->
            <!--section id="blog" class="flat-row row-blog">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                           <div class="title-section">
                                <h1 class="title" data-text="B">Latest<span>Blogs</span></h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="flat-latest-blog">  
                                <article class="entry format-gallery">
                                    <div class="feature-post">
                                        <div class="type-post">
                                        </div>
                                        <div class="entry-image">
                                            <img src="images/blog/latest-blog-1.jpg" alt="image">
                                        </div>
                                    </div>
                                   
                                    <div class="main-post">
                                        <h2 class="entry-title"><a href="blog-single.html">Donec porttitor justo et odio</a></h2>
                                        <div class="entry-meta">                                    
                                            <span class="date">Feb 25th, 2016</span>
                                            <span class="author"><a href="#">Bill Gates</a></span>
                                            <span class="comment"><a href="#">12</a></span>
                                            <span class="vote"><a href="#">20</a></span>
                                        </div>
                                        <div class="entry-content">
                                            <p>Lorem ipsum dolor amet, consectetur adipiscing. Nullam maximus nibh consequat metus varius hendrerit [...]
                                            <span class="more-link">
                                                <a href="blog-single.html">Read More<i class="material-icons">chevron_right</i></a>
                                            </span>
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="flat-latest-blog">  
                                <article class="entry format-standard">
                                    <div class="feature-post">
                                        <div class="type-post">
                                        </div>
                                        <div class="entry-image">
                                            <img src="images/blog/latest-blog-2.jpg" alt="image">
                                        </div>
                                    </div>
                                    
                                    <div class="main-post">
                                        <h2 class="entry-title"><a href="blog-single.html">Aliquam porttitor massa sem</a></h2>
                                        <div class="entry-meta">                                    
                                            <span class="date">Mar 18th, 2016</span>
                                            <span class="author"><a href="#">Henry Dell</a></span>
                                            <span class="comment"><a href="#">20</a></span>
                                            <span class="vote"><a href="#">21</a></span>
                                        </div>
                                        <div class="entry-content">
                                            <p>Lorem ipsum dolor amet, consectetur adipiscing. Nullam maximus nibh consequat metus varius hendrerit [...]
                                            <span class="more-link">
                                                <a href="blog-single.html">Read More<i class="material-icons">chevron_right</i></a>
                                            </span>
                                            </p>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>                    
                    </div>
                </div>
            </section-->

            <!-- Contact -->
            <section id="contact" class="flat-row nopad">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-nopad">
                            <div id="map" style="width: 100%; height: 634px; "></div> 
                        </div><!-- /.col-md-6 -->
                        <div class="col-md-6">
                            <div class="flat-divider d100px"></div>
                            <div class="title-section">
                                <h1 class="title" data-text="G">Get In <span>Touch</span></h1>
                            </div><!-- /.title-section -->
                            <form class="flat-contact-form for-full-width" id="contactform" method="post" action="<?php echo Router::url('/',true); ?>contact_us_ajax">
                                <div class="field clearfix">      
                                    <div class="wrap-type-input">                    
                                        <div class="input-wrap name">
                                            <input type="text" value="" tabindex="1" placeholder="Enter your Name *" name="data[AppEnquiry][name]" id="name" required>
                                        </div>
                                        <div class="input-wrap email">
                                            <input type="email" value="" tabindex="2" placeholder="Enter your Email *" name="data[AppEnquiry][email]" id="email-contact" required>
                                        </div>
                                        <div class="input-wrap last phone">
                                            <input type="number" value="" placeholder="Enter Your phone number *" name="data[AppEnquiry][phone]" id="phone" required>
                                        </div>  
                                    </div>
                                    <div class="textarea-wrap">
                                        <textarea class="type-input" tabindex="3" placeholder="Enter your Message *" name="data[AppEnquiry][message]" id="message-contact" required></textarea>
                                    </div>
                                </div>
                                <div class="submit-wrap">
                                    <button class="flat-button">Send Message<i class="icon-envelope-open icons"></i></button>
                                </div>
                            </form><!-- /.comment-form -->     
                        </div>
                    </div>
                </div><!-- /.container -->
            </section>

        </div><!-- /.Entry Page --> 

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-widgets">
                <div class="container">
                    <div class="row"> 
                        <div class="col-md-4">  
                            <div class="widget widget_text">
                                <div class="textwidget">
                                    <img src="<?php echo Router::url('/',true); ?>home/images/logo.png" alt="images">
                                    <p>MEngage develop mass consumer engagement application for doctors on both android and IOS platforms. It is a team of wide variety of technology individuals, Start-up Entrepreneur’s and Co-founders. The organisation believes in building never-ending relationships with customers by sharing professional and ethical values.</p>
                                </div>
                            </div><!-- /.widget -->      
                        </div><!-- /.col-md-4 --> 

                         <div class="col-md-4">  
                            <div class="widget widget_text">
                                <h5 class="widget-title">Contact us</h5>
                                <div class="textwidget">                               
                                    <p>228, Okay Plus Spaces, Near Apex Circle,<br>
									Malviya Industrial Area Jaipur<br>
									Rajasthan, India, 302017<br>
									Email: engage@mengage.in<br>
                                    Phone: 0141-4910316, +91-7412993344</p>
                                </div>
                            </div><!-- /.widget -->      
                        </div><!-- /.col-md-4 -->

                         <div class="col-md-4">  
                            <div class="widget widget_text">                            
                                <h5 class="widget-title">Working Hours</h5>
                                <div class="textwidget">                                
                                    <p>
                                        Monday to Saturday: 10:00 - 19:00 <br>
                                        <!--Saturday: 10:00 - 15:00<br-->
                                        Sunday: Closed
                                    </p>
                                </div>
                            </div><!-- /.widget -->      
                        </div><!-- /.col-md-4 -->

                    </div><!-- /.row -->    
                </div><!-- /.container -->
            </div><!-- /.footer-widgets -->

            <!-- Go Top -->
            <a class="go-top style1">
                <i class="zmdi zmdi-long-arrow-up"></i>
            </a>
            
        </footer> 

        <!-- Bottom -->
        <div class="bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="copyright"> 
                        <p style="float: left;"><a href="http://mengage.co.in/privacy">PRIVACY POLICY</a></p>
                            <p>Copyright ©2016  
                            <a href="http://mengage.co.in" target = "_blank"> MEngage.</a> All Rights Reserved.</p>
                        </div>                   
                    </div><!-- /.col-md-12 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </div>  
    </div><!-- /.boxed -->

    <!-- Go Top -->
    <a class="go-top">
        <i class="zmdi zmdi-long-arrow-up"></i>
    </a>
        
    <!-- Javascript -->
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/bootstrap.min.js"></script>      
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.easing.js"></script>    
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/owl.carousel.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.isotope.min.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/imagesloaded.min.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery-countTo.js"></script> 
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery-waypoints.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery-ui-datepicker.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery-validate.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.mb.YTPlayer.js"></script> 
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyAOypxMO7tZ59hBc6WvHJVQ7TupUSh8D5g"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/gmap3.min.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.cookie.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.fitvids.js"></script>
    

    <!-- Revolution Slider -->
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.themepunch.tools.min.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/jquery.themepunch.revolution.min.js"></script>
    <script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/slider.js"></script>
	<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/intltel/js/intlTelInput.min.js"></script>
	<script type="text/javascript" src="<?php echo Router::url('/',true); ?>home/javascript/main.js"></script>
	
	<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5a03042dbb0c3f433d4c7ee6/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->


	<script>
		$(document).on("click",".get_image",function(){
			var imageToShow = $(this).attr("get-image");
			$("#images1").hide();
			$("#images2").hide();
			$("#images3").hide();
			$("#images4").hide();
			$("#images5").hide();
			$("#images6").hide();
			$("#"+imageToShow).show();
		});
	</script>
	
	<!--script type="text/javascript">

			$(function(){
			window.submit ='no';
			$("#phone").on("countrychange", function(e, countryData) {
				//console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
				//console.log($("#phone").intlTelInput("isValidNumber"));
				// do something with countryData
				$("#mobile").trigger("keyup");

			});

			$(document).on("keyup","#mobile", function() {
				//console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
				if($(this).intlTelInput("isValidNumber")){
					$(this).css('border-color',"#ccc");
				submit =true;
				}else{
				submit =false;
					$(this).css('border-color',"red");
				}
			});



			/*$(document).on("submit","#appointmentform", function() {
				console.log($("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
				if($("#mobile").intlTelInput("isValidNumber")){
					var mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
					$("#mobile").val(mob);
				}else{
					return false;
				}
			}); */

			$(document).on("keyup", "#org_unique_url", function () {
				var str = $(this).val();
				if(str.match(/^([a-z0-9]+-)*[a-z0-9]+$/i)){
					$(this).css('border-color',"#ccc");
					window.submit ='yes';
					console.log(window.submit);
				}else{
					$(this).css('border-color',"red");
					window.submit ='no';
				}
			});

			$.get("https://ipinfo.io", function(response) {
				//console.log(response.country);
				//console.log(response.city);
				$("#mobile").intlTelInput({
					allowExtensions: true,
					autoFormat: false,
					autoHideDialCode: false,
					autoPlaceholder:  false,
					initialCountry: response.country,
					ipinfoToken: "yolo",
					nationalMode: true,
					numberType: "MOBILE",
					//onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
					//preferredCountries: ['cn', 'jp'],
					preventInvalidNumbers: true,
					utilsScript: "<?php echo Router::url('/',true); ?>home/javascript/intltel/js/utils.js"
				});
			}, "jsonp");

			$(document).on("keyup",".url_box",function () {
				var val = $(this).val();
				if(val!=""){
					$(".url_leble").html('Ex :- http://mengage.co.in<?php echo Router::url('/',true); ?>'+$(this).val());
				}else{

					$(".url_leble").html("&nbsp;");
				}

			})
		});
	
            $('#appointmentform').validate({
                submitHandler: function (form) {
				
				
				//console.log($("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
				if($("#mobile").intlTelInput("isValidNumber")){
					var mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
					$("#mobile").val(mob);
				}else{
					return false;
				}
				
				
				if(window.submit == 'no')
				{
				return false;
				}
				
				if($("#password").val() != $("#confirm_password").val())
				{
					$(".conf-pass").text("Confirm password does not match!");
					return false;
				}
				else
				{
					//$(".conf-pass").text("");
					
				}
				
				
				
				
                    var $form = $(form),
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
								var dataGet = JSON.parse(msg);
								console.log(dataGet);
                            if ( dataGet.status == 1 ) {                                
                                result = 'Email Sent Successfully. Thank you, Your application is accepted - we will contact you shortly';
                                cls = 'msg-success';
								
								
								$("#appointmentform").css("display","none");
								$("#otpform").css("display","block");
								
								
								$("#m_otp").val(dataGet.m_c);
								
								
								
                            } else {
                                result = dataGet.error;
                                cls = 'msg-error';
                            }

                            $form.prepend(
                                $('<div />', {
                                    'class': 'flat-alert ' + cls,
                                    'text' : result
                                }).append(
                                    $('<a class="close" href="#"><i class="fa fa-close"></i></a>')
                                )
                            );

                            //$form.find(':input').not('.submit').val('');
                        },
                        complete: function (xhr, status, error_thrown) {
                            $form.find('.loading').remove();
                        }
                    }); 
					
					
					
					
					
					
                }
            });	
	
			$('#otpform').validate({
                submitHandler: function (form) {
					
					
					
					var $form = $(form),
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
								var dataGet = JSON.parse(msg);
                            if ( dataGet.status == 1 ) {                                
                                
								window.location = "<?php echo Router::url('/',true); ?>app_admin/app_info";
								
                            } else {
                                result = dataGet.msg;
                                cls = 'msg-error';
                            }

                            $form.prepend(
                                $('<div />', {
                                    'class': 'flat-alert ' + cls,
                                    'text' : result
                                }).append(
                                    $('<a class="close" href="#"><i class="fa fa-close"></i></a>')
                                )
                            );

                            //$form.find(':input').not('.submit').val('');
                        },
                        complete: function (xhr, status, error_thrown) {
                            $form.find('.loading').remove();
                        }
                    }); 
					
					
					
					
					
				}
				});
	
	</script-->

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

        $("#mobile").on("countrychange", function(e, countryData) {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            //console.log($("#phone").intlTelInput("isValidNumber"));
            // do something with countryData
            $("#mobile").trigger("keyup");

        });

        $(document).on("keyup blur","#mobile", function() {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));

                if($(this).intlTelInput("isValidNumber")){
                    $(this).css('border-color',"#ccc");
                    checkOrg();
                }else{
                    $(this).css('border-color',"red");
				}
            
        });

        /* $(document).on("submit","#login_form", function() {
            //console.log($("#phone").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
            if($("#mobile").intlTelInput("isValidNumber")){
                var mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
                $("#mobile").val(mob);
            }else{
                return false;
            }
        }); */
		
		
		
		$('#login_form').validate({
                submitHandler: function (form) {
				
				
				//console.log($("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164));
				if($("#mobile").intlTelInput("isValidNumber")){
					var mob = $("#mobile").intlTelInput("getNumber",intlTelInputUtils.numberFormat.E164);
					$("#mobile").val(mob);
				}else{
					return false;
				}
				
				
                    var $form = $(form),
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
                            var result = "";
                            var cls;
								var dataGet = JSON.parse(msg);
								console.log(dataGet);
                            if ( dataGet.status == 1 ) {                                
                                
								
								window.location = "<?php echo Router::url('/',true); ?>app_admin/"+dataGet.redir;
								
								
								
                            } else {
                                //result = dataGet.error;
								result = "Mobile or password does not match!";
                                cls = 'msg-error';
                            }

                            if(result != "")
                            {
                                $form.prepend(
                                    $('<div />', {
                                        'class': 'flat-alert ' + cls,
                                        'text' : result
                                    }).append(
                                        $('<a class="close" href="#"><i class="fa fa-close"></i></a>')
                                    )
                                );
                            }


                            //$form.find(':input').not('.submit').val('');
                        },
                        complete: function (xhr, status, error_thrown) {
                            $form.find('.loading').remove();
                        }
                    }); 
					
					
					
					
					
					
                }
            });
		
		
		
		

        $.get("https://ipinfo.io", function(response) {
            //console.log(response.country);
            //console.log(response.city);
            $("#mobile").intlTelInput({
                allowExtensions: true,
                autoFormat: false,
                autoHideDialCode: false,
                autoPlaceholder:  false,
                initialCountry: response.country,
                ipinfoToken: "yolo",
                nationalMode: true,
                numberType: "MOBILE",
                //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                //preferredCountries: ['cn', 'jp'],
                preventInvalidNumbers: true,
                utilsScript: "<?php echo Router::url('/',true); ?>home/javascript/intltel/js/utils.js"
            });
               var inter = setInterval(function () {
                   checkOrg();
                   clearInterval(inter);
               },2)

        }, "jsonp");

    });
	</script>
	
</body>
</html>