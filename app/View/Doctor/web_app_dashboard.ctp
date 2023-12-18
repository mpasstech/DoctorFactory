<div class="row tab_btn_div">

    <?php $showButton = true; ?>

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

                </div>
            </div>
        </div>
    </div>

    <div class="icon-div-btn col-4">
        <button class="tab-info" data-title="About" data-id="info"><i class="fa fa-info-circle"></i>About</button>
    </div>

    <?php if($this->AppAdmin->check_app_enable_permission($doctor_data['thinapp_id'],"QUICK_APPOINTMENT") || $this->AppAdmin->check_app_enable_permission($doctor_data['thinapp_id'],"NEW_QUICK_APPOINTMENT")){ ?>
        <div class="icon-div-btn col-4">
            <button class="tab-appointment" data-title="Book Token" id="app_btn" data-id="appointment">
                <i class="fa fa-ticket" aria-hidden="true"></i>Book Token
            </button>
        </div>

        <div class="icon-div-btn col-4" id="chat_boat_div" style="display: none;">
            <button onclick="" data-id="chatboat" ><i class="fa fa-comment-o" aria-hidden="true"></i>Book Token <br> Via Chatbot</button>
        </div>

        <div class="icon-div-btn col-4" id="dash_my_appointment" >
            <button class=" tab-blog" data-id="my_appointment" data-title="My Appointment"><i class="fa fa-history" aria-hidden="true"></i>My Token</button>
        </div>


    <?php } ?>

    <div class="icon-div-btn col-4" style="display: none;">
        <button class=" tab-blog" data-id="blog" data-title="Blog"><i class="fa fa-rss" aria-hidden="true"></i>Blog</button>
    </div>


    <?php
    $doctor_list = $this->AppAdmin->get_all_doctor_list($doctor_data['thin_app_id'], $doctor_data['doctor_id']);
    if(!empty($doctor_list)){
        ?>
        <div class="icon-div-btn col-4" id="dash_other_doctors" >
            <button class=" tab-blog" data-id="other_doctors" data-title="Other Doctors"><i class="fa fa-Users" aria-hidden="true"></i>Other Doctors</button>
        </div>
    <?php } ?>


    <?php if($this->AppAdmin->check_user_enable_functionlity($doctor_data['thinapp_id'],"SHOW_DOCUMENT_TO_USER")){ ?>
        <div class="icon-div-btn col-4" id="dash_medical_record" >
            <button class=" tab-blog" data-id="medical_record" data-title="Medical Record"><i class="fa fa-file-image-o" aria-hidden="true"></i>Medical Records</button>
        </div>
    <?php } ?>

     <?php if($this->AppAdmin->check_user_enable_functionlity($doctor_data['thinapp_id'],"SHOW_USER_TO_HEALTH_TIP")){ ?>
    <div class="icon-div-btn col-4" id="dash_health_tip" >
        <button class=" tab-blog" data-id="health_tip" data-title="Health Tip"><i class="fa fa-file-image-o" aria-hidden="true"></i>Health Tip</button>
    </div>
    <?php } ?>



    <?php if(!$isDoctor){ if(!empty($doctor_data['ivr_number'])){ ?>
        <div class="icon-div-btn col-4">
            <a href="javascript:void(0)" data-href="tel:<?php echo $doctor_data['ivr_number']; ?>" data-id="virtual_receptionist" class="virtual_reception_diract_btn" ><i class="fa fa-phone" aria-hidden="true"></i>Virtual Receptionist</a>
        </div>
    <?php }else if(!empty($doctor_data['doctor_code'])){ ?>
        <div class="icon-div-btn col-4">
            <a data-number="‎+911141171845" data-code="<?php echo $doctor_data['doctor_code']; ?>" class="virtual_reception_btn" data-id="virtual_receptionist" ><i class="fa fa-phone" aria-hidden="true"></i>Virtual Receptionist</a>
        </div>
    <?php }} ?>


    <div class="icon-div-btn col-4" style="display:none;">
        <button onclick="" data-id="doctor_apps" ><i class="fa fa-globe" aria-hidden="true"></i>DOCTORapps</button>
    </div>


    <?php if(!$isDoctor){ ?>
        <div class="icon-div-btn col-4" id="dash_track_token" >
            <button class=" tab-blog"  data-id="track_token" data-title="Track Token"><i class="fa fa-history" aria-hidden="true"></i>Track Token</button>
        </div>
    <?php } ?>

  <?php if($isDoctor){ ?>
        <div class="icon-div-btn col-4" id="dash_patient_list" >
            <button class=" tab-list"  data-id="patient_list" data-title="Patient List"><i class="fa fa-history" aria-hidden="true"></i>Patient List</button>
        </div>

        <div class="icon-div-btn col-4" id="dash_doc_setting" style="display: none;" >
            <button class=" tab-list" data-di='<?php echo base64_encode($doctor_id); ?>' data-type='doctor'  data-id="doctor_setting" data-title="Setting"><i class="fa fa-gear" aria-hidden="true"></i>Doctor Setting </button>
        </div>


    <?php } ?>




    <?php if($this->AppAdmin->check_app_enable_permission($doctor_data['thinapp_id'],"VACCINATION")){ ?>
        <div class="icon-div-btn col-4" id="dash_children" >
            <button class=" tab-blog"  data-id="children" data-title="Children List"><i class="fa fa-history" aria-hidden="true"></i>Vaccination</button>
        </div>
    <?php } ?>






</div>
<div class="main_container container-fluid contain" data-c = "<?php echo $channel_id; ?>" data-ti = "<?php echo base64_encode($doctor_data['thin_app_id']); ?>" data-di = "<?php echo $doctor_data['doctor_id']; ?>" data-online="<?php echo $doctor_data['is_online_consulting']; ?>" data-offline="<?php echo $doctor_data['is_offline_consulting']; ?>" data-chat="<?php echo $doctor_data['is_chat_consulting']; ?>" data-audio="<?php echo $doctor_data['is_audio_consulting']; ?>" >


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
                            <i class="fa fa-heartbeat"></i>
                            <?php echo '<span>'.$doctor_data['doctor_category'].'</span>'; ?>
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
                    <?php if($doctor_data['thinapp_id']!=904){ ?>
                        <label class="label_title">
                            <i class="fa fa-graduation-cap"></i> <?php echo $doctor_data['education']; ?>
                                    <?php if(!empty($doctor_data['doctor_category'])){ echo !empty($doctor_data['education'])?"<br>":''; ?>
                                        <?php echo '<span>'.$doctor_data['doctor_category'].'</span>'; ?>
                                    <?php } ?>

                        </label>
                    <?php }else{ ?>
                        <label class="label_title">
                            <span style="display:block;width:100%;">Musculoskeletal Medicine and Pain,</span>
                            <span style="display:block;width:100%;">Musculoskeletal Interventions,</span>
                            <span style="display:block;width:100%;">Neuro - Rehabilitation,</span>
                            <span style="display:block;width:100%;">Medical and Surgical Rehabilitation</span>
                        </label>
                    <?php } ?>
                    
                <?php } ?>
            </div>

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
                    <i class="fa fa-envelope"></i> <label class="info-div-icon">&nbsp;<a href="mailto:<?php echo $doctor_data['email']; ?>"><?php echo $doctor_data['email']; ?></a></label>
                </div>
            <?php } ?>


            <?php if(!empty($doctor_data['website_url'])){ ?>
                <div class="info-detail"><i class="fa fa-globe" aria-hidden="true"></i> <label class="info-div-icon">&nbsp;<a href="<?php echo $doctor_data['website_url']; ?>" ><?php echo $doctor_data['website_url']; ?></a></label></div>
            <?php } ?>



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


    <?php if($this->AppAdmin->check_app_enable_permission($doctor_data['thinapp_id'],"QUICK_APPOINTMENT") || $this->AppAdmin->check_app_enable_permission($doctor_data['thinapp_id'],"NEW_QUICK_APPOINTMENT")){ ?>

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
                                                            <p class="address_label" data-show="<?php echo $address['from_time'] .' To '.$address['to_time']; ?>"><i class="fa fa-clock-o time_icon" aria-hidden="true"></i> <?php echo date('h:i A',strtotime($address['from_time'])) .' - '.date('h:i A',strtotime($address['to_time'])); ; ?></p>


                                    </span>
                        <span style="width: 5%;">

                                        <a href="javascript:void(0);"><i class="fa fa-angle-right right_arrow_nav"></i></a>

                                    </span>



                    </li>
                <?php }  ?>
            </ul>

            <ul id="date_slider" style="display: none;"  class="">
                <h5 class="inner_label_header">
                    <a id="back_to_location" href="javascript:void(0);" data-hide="#date_slider" data-target="#addressSlider" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i></a>
                    Token Booking Date
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
                <div class="col-12 appoinment-slot-holder" style="min-height: 350px;">
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
                <ul class="naviagation_ul">
                    <li class="booked_label"><label>0</label> <span>Booked<span> </li>
                    <li class="available_label"><label>0</label><span> Available</span> </li>
                          
                        </ul>

                <?php if($doctor_data['allow_emergency_appointment']=='YES'){ ?>
                    <div class="col-12 emergency_row">
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

            <?php if($isDoctor){ ?>
                <div class="row token_option_box">
                    <?php if(!empty($appointment_data['address_list'])){ ?>
                        <select style="display:<?php echo (count($appointment_data['address_list']))>1?'block':'none'; ?>" class="form-control" id="doctor_address_drp">
                            <?php foreach ($appointment_data['address_list'] as $key => $address){ ?>
                                <option value="<?php echo $address['address_id']; ?>"><?php echo $address['address']; ?></option>
                            <?php } ?>
                        </select>
                        <input style="width: 100%;float:left;" type="text" placeholder="Search by token/mobile number" class="form-control" id="search_appoitnment_box" >
                        <label class="date_lable_select" >
                            <input  type="text" id="search_date" readonly value="<?php echo date('d-m-Y'); ?>" >
                            <i class="fa fa-calendar" style="float: left;font-size: 1rem;margin: 0.1rem;"></i>
                        </label>

                    <?php } ?>
                </div>
                <div class="row">

                    <div class="col-12" id="append_labels">

                    </div>
                </div>
            <?php } ?>



            <div class="row blog-container append_appointments">


            </div>

            <div class="load_more" id="load_more_appointment_btn" data-offset="0" style="display: none;">
                <button>
                    <img style="display:none; margin: 0 1px;" class="loader_img" src="<?php echo Router::url('/img/loader.gif',true); ?>" />
                    <span>Load More</span>
                </button>
            </div>



        </div>


    <?php } ?>

    <div class="tab-content" id="blog"  style="display: none;"  >
        <div class="col-12 blog-search">
            <div class="form-group">
                <form class="search_btn_form icon-addon addon-md">
                    <input type="text" placeholder="Search Blog..." class="form-control search-input" id="search-blog-text">
                    <button type="submit" id="search_blog_btn"  rel="tooltip" title="Search Blog..."><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>
        <div class="row blog-container append_blogs">

        </div>
        <div class="load_more" id="load_more_blog_btn"><button>Load More</button></div>
    </div>



    <div class="tab-content" id="doctor_setting"  style="display: none;"  >
        
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


    <div class="tab-content" id="health_tip"  style="display: none;"  >
        <div class="row blog-container">
            <ul  class="append_health_tip">

            </ul>

        </div>
        <div class="load_more" id="load_more_tip" style="display: none !important;"><button>Load More</button></div>
    </div>

    <div class="tab-content" id="children"  style="display: none;"  >
        <div class="raw blog-container">
            <input id="search_child" type="text" autocomplete="off" placeholder="Search..." class="form-control">
        </div>
        <div class="raw" style="height: 580px; overflow-y: auto;">
            <ul  class="append_childrens" style="padding: 0;margin: 0;">

            </ul>

            <img style="display: none;" class="load_more_child_loader" src="<?php echo Router::url('/img/loader.gif',true); ?>">
            <div class="load_more" id="load_more_child" data-offset="0" style="display: none;"><button>Load More</button></div>

        </div>
        <?php if($isDoctor){ ?>
            <button class="btn btn-success load_add_children_btn"><i class="fa fa-plus"></i></button>
        <?php } ?>


    </div>

    <?php  if(!empty($doctor_list)){  ?>
        <div class="tab-content" id="other_doctors" style="display: none;">
            <div class="row">
                <?php foreach ($doctor_list as $key =>$val){ ?>

                    <div class="col-12 list_container_div" >
                        <img src="<?php echo !empty($val['profile_photo'])?$val['profile_photo']:$doctor_data['logo']; ?>" class="other_doctor_img" />
                        <label>
                            <?php echo $val['name'];?>
                            <span class="span_department"><?php echo $val['department_name'];?></span>
                            <a target="_blank" href="<?php echo 'https://www.mpasscheckin.com/doctor/doctor/index/'.$val['id'].'/?t='.base64_encode($val['id']).'&wa=y&back=no'; ?>"> Book Token</a>
                        </label>

                    </div>

                <?php } ?>
            </div>
        </div>
    <?php } ?>


    <div class="tab-content" id="patient_list"  style="display: none;"  >
        <div class="raw blog-container token_option_box">
            <input id="search_patient_list_box" type="text" autocomplete="off" placeholder="Search..." class="form-control">
        </div>
        <div class="raw" style="height: 580px; overflow-y: auto;">
            <div  class="append_patients" style="padding: 0;margin: 0;">

            </div>

            <div class="load_more" id="load_more_patient" data-offset="0" style="display: none;">
                <button>
                    <img style="display:none; margin: 0 1px;" class="loader_img" src="<?php echo Router::url('/img/loader.gif',true); ?>" />
                    <span>Load More</span>
                </button>
            </div>

        </div>


    </div>


</div>


<?php 
    echo '<script>$("#setting_menu_span").html("'.$setting_menu.'");</script>';
?>






<div class="bottom_box">
    <table class="table_bottom">
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

<style>


	.getwayBox .swal2-cancel{
        position: absolute;
        top: -15px;
        right: -5px;
        background: red !important;
        color: #fff !important;
        border: none !important;
        border-radius: 0px 0px 0px 9px;
        font-size: 1rem !important;
        outline: none !important;
        width: 35px;
        height: 35px;
        padding: 0 !important;
        font-weight: 600;
   }

	 .getwayBox{
        height:560px !important;
        min-height:560px !important;
        position: absolute !important;
        left: 0 !important; 
        padding: 0 !important;

    }
    
    .date_lable_select{
        width: 35%;
        display: block;
        float: left;
        margin: 0 auto;
        text-align: center;
        background: #fff;
        color: blue;
        display: flex;
        border-radius: 3px;
    }

    #search_date{
        width: 70%;
        font-size: 1rem;
        border: none;
        height: 21px;
        font-weight: 600;
        outline: none !important;
        text-align: center;
        display: list-item;
        margin: 0 5%;
    }


    .datepicker td{
        padding: 5px !important;
    }

    .load_add_children_btn{
        position: fixed;
        right: 3%;
        bottom: 3%;
        border-radius: 63%;
        height: 50px;
        width: 50px;
    }

    .app_label_box{
        padding: 0.4rem 0px;
       border-bottom: 1px solid #cbcbcb;
    }

    .app_label_box li.active{
        background: blue;
        color: #fff;
        border-radius: 4px;
    }

    .app_label_box li{
        list-style: none;
        width: auto;
        text-align: center;
        padding: 0.3rem 0.4rem;
        font-size: 0.9rem;
        display: inline;
    }

    .app_label_box::-webkit-scrollbar {
        display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .app_label_box {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }



    #doctor_address_drp{
        border-radius: 10px;
    }
    .token_option_box{
        padding: 0.3rem;
        background: #dbdbdb;
        margin: 2%;
        border: 2px solid #c1c1c1;
    }
    #search_appoitnment_box{
        border-radius: 36px;
        margin: 5px 0px;
        padding: 8px 18px;
    }
    .child_li{
        list-style: none;
        padding: 10px 0px 0px 0px;
        margin: 10px 0px;
        width: 100%;
        float: left;
        border-bottom: 1px solid #dedede;
    }
    .child_li .child_name{
        padding: 0.6rem 0.5rem;
        background: #1c56d9d9;
        color: #fff;
    }
    .child_li table{
        width: 100%;
    }
    .child_li .image_td img{
        width: 70px;
        border: 1px solid #d3c8c8;
        border-radius: 50%;
        padding: 2px;
        height: 70px;
    }
    .child_li .image_td{
        text-align: center;
    }
    .child_li td{
        font-size: 0.9rem;
    }
    .child_li .patient_id{
        color: #fff;
        font-weight: 600;
        background: #61ac4a;
        padding: 1px 12px;
        border-radius: 4px;
    }
    .child_li .btn_tr td{
        text-align: center;
    }
    .child_li .btn_tr button{
        background: none;
        border: none;
        outline: none;
        padding: 5px 10px;
        color: #0069ea;
        font-weight: 600;
    }
    #search_child{
        width: 93%;
        margin: 10px auto;
    }
    .load_more_child_loader{
        margin: 0% auto;
        display: block;
        width: 10%;
    }
    .btn_tr{


    }
    .child_list_btn_p{
        padding: 0.2rem 0;
        width: 100%;
        float: left;
        background: #f7f7f7;
        margin: 0.4rem 0 0 0;
    }
    .button_box_tr button,
    .button_box_tr a{
        margin: 10px auto;
        text-align: center;
        display: block;
        background: #5555ef;
        border: 2px solid;
        color: #fff;
        padding: 5px 9px;
        border-radius: 5px;
        width: 100%;
    }

    .button_box_tr table td li{
        list-style: none;
        float: left;
        width: auto;

    }

    .star_lbl .checked {
        color: orange;
    }
    .send_review_link{
        float: right;
        background: #5555ef;
        padding: 1px 6px;
        border-radius: 4px;
        color: #ffff;
        border: 2px solid #6363e1;
    }


    .naviagation_ul{
        margin: 2px 0px;
        padding-top: 9px;
        width: 100%;
        display: block;
        float: left;
        border-top: 1px solid grey;
       
    }
    .naviagation_ul li{
        list-style: none;
        float: left;
        width: 50%;
        text-align: center;
    }

    .naviagation_ul li label{
        padding: 0px;
        width: 35px;
        height: 25px;
        font-weight: 600;
    }



    .naviagation_ul li.available_label label{
        border:1px solid #cccccc;
       
    }

    .naviagation_ul li.booked_label label{
        background: red;
        border:1px solid red;
        color: #fff;
       
    }


</style>

<script>
    Dropzone.autoDiscover = false;
    $(function () {




        



        function showLoadingIcon(flag){
            $("#loaderDiv").hide();
            if(flag==true){
                $("#loaderDiv").show();
            }
        }



        

        $(document).off('click',".load_setting");
        $(document).on('click',".load_setting",function() {
            $("#dash_doc_setting button").trigger("click");

        });


        var slider_interval = 4500;
        $('.carousel').carousel({
            interval: slider_interval
        });


        $("#search_date").datepicker({
            autoclose:true,
            format: 'dd-mm-yyyy',
            setDate: new Date()
        }).on('changeDate', function(e) {
                //e.format()
               //$(".app_label_box li.active").trigger("click");
        });


        $(document).off('click',".inner_label_header a");
        $(document).on('click',".inner_label_header a",function() {
            $($(this).attr("data-hide")).hide();
            $($(this).attr("data-target")).show();
        });






        $(document).off('click',"#addressSlider li");
        $(document).on('click',"#addressSlider li",function() {
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


        $("#body").data('key',<?php echo @json_encode(($success)); ?>);
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



        $(document).off('hover',".read_more_about");
        $(document).on('hover',".read_more_about",function() {
            $('.available-tool').hide();
            var e = $(this);
            e.off('hover');
            e.popover({
                content: e.data('poload'),
                html: true,
                placement: 'right',
            }).popover('show');

        });


        $(document).off('click',".back_button");
        $(document).on('click',".back_button",function() {
            window.location.href = $(this).attr('data-href');
        });

        if(is_desktop()){
            $("body").addClass("desktop_layout");
        }

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

		var url = new URL(window.location.href);
        if (url.searchParams.has('sal')) {
                $("button[data-id='my_appointment']").trigger("click");
        }


       


    });
</script>



</body>
</html>