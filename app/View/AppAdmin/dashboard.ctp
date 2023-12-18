<?php
$login = $this->Session->read('Auth.User');
//pr($login); die;
$total_sms = $this->AppAdmin->getTotalSms($login['User']['id']);
$category = $this->AppAdmin->getTotalSms($login['Thinapp']['category_name']);
$thin_app_id = $login['User']['thinapp_id'];

$is_payment = $this->AppAdmin->paymentStatus($login['Leads']['customer_id']);
$is_first_login = $this->AppAdmin->is_default_channel($login['User']['id']);

?>

<!--div class="Inner-banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Dashboard</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div-->

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <h3 class="screen_title">Dashboard</h3>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box dashboard_box">


                            <?php if($total_sms){ ?>
                            <div class="form-group">
                                <div class="row">
                               <!-- <div class="col-sm-6">
                                    <div class="sms_div">
                                         Remaining Promotional SMS <br><strong><?php /*echo $total_sms['AppSmsStatic']['total_promotional_sms']; */?></strong>
                                    </div>
                                </div>-->
                                <div class="col-sm-12">
                                    <div class="sms_div">
                                        <strong>Remaining Total SMS <?php echo $total_sms['AppSmsStatic']['total_transactional_sms'] ?></strong>
                                    </div>
                                </div>
                                   </div>
                            </div>
                            <?php } ?>

                                <div class="form-group">
                                    <div class="row">

                                        <?php if($login['USER_ROLE'] =='ADMIN'){ ?>
                                            <div class="col-sm-12 dashboard_icon_li">


                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"QUICK_APPOINTMENT") || $this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"NEW_QUICK_APPOINTMENT")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <!--a href="<?php echo Router::url('/app_admin/appointment',true); ?>"-->
                                                        
                                                        <?php 
                                                            $actionName = "add_appointment";
                                                            if(isset($_COOKIE['bookingSetting'])){
                                                                if($_COOKIE['bookingSetting']=="NEW_SCREEN"){
                                                                    $actionName = "lite_book_appointment";
                                                                }
                                                                
                                                            }
                                                        ?>
                                                        
                                                        <a href="<?php echo Router::url('/app_admin/'.$actionName,true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/opd_icon.png')?>">
                                                                </div>

                                                                OPD
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/opd',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/ipd_icon.png')?>">
                                                            </div>
                                                            IPD
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/list_hospital_emergency',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/emergency_icon.png')?>">
                                                            </div>
                                                            EMERGENCY
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/hospital_bill.png')?>">
                                                            </div>
                                                            Billing
                                                        </div>
                                                    </a>
                                                </div>

                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"POLL")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/poll',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/poll-oneline-icon.png')?>">
                                                                </div>
                                                                Poll
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/poll_management',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/my_poll_icon.png')?>">
                                                                </div>
                                                                Poll Factory
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"TICKET")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/ticket',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/ticket-icon.png')?>">
                                                                </div>
                                                                Complaints Box
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"QUEST_BUY_SELL")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/quest?qt=',true).base64_encode('quest'); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/quest.png')?>">
                                                                </div>
                                                                Quest
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/quest?qt=',true).base64_encode('rent'); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/rent.png')?>">
                                                                </div>
                                                                Rent
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/quest?qt=',true).base64_encode('borrow'); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/borrow.png')?>">
                                                                </div>
                                                                Borrow
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/quest?qt=',true).base64_encode('buy'); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/buy.png')?>">
                                                                </div>
                                                                Buy
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/sell',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/sell.png')?>">
                                                                </div>
                                                                Sell
                                                            </div>
                                                        </a>
                                                    </div>

                                                <?php } ?>
                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"QUICK_APPOINTMENT") || $this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"NEW_QUICK_APPOINTMENT")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/billing_setting',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/appointment_das.png')?>">
                                                                </div>

                                                                Billing Setting
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report_hospital',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/hospital_inventory.png')?>">
                                                                </div>

                                                                Hospital Inventory
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/pharma_inventory.png')?>">
                                                                </div>

                                                                Pharma Inventory
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report_lab',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/lab_inventory.png')?>">
                                                                </div>

                                                                Lab Inventory
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report_service',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/service_inventory.png')?>">
                                                                </div>

                                                                Service & Package
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>




                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"NEW_LETTER")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/load_blog_post',true); ?>">
                                                            <!--<a href="javascript:void(0);" class="post_message_btn" >-->
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/newsletter.png')?>">
                                                                </div>
                                                                Doctor's Blog
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"SUBSCRIBER_LIST")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/subscriber',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/subscribers.png')?>">
                                                                </div>
                                                                Subscriber List
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>


                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"SUBSCRIBER_LIST")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/patient',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/subscribers.png')?>">
                                                                </div>
                                                                Patient List
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>


                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"OFFER")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/offers',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/offer.png')?>">
                                                                </div>
                                                                Offers
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>


                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"SERVICE")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/service',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/service.jpg')?>">
                                                                </div>
                                                                Service
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>



                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"DOCUMENT_MANAGEMENT")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/drive',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/document.png')?>">
                                                                </div>
                                                                Medical Records
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"PAYMENT")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/payment_item_list',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/payment.png')?>">
                                                                </div>
                                                                Payment
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"MY_LOCATIONS")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/location',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/location.png')?>">
                                                                </div>
                                                                Location
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/get_user_statics',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                                                            </div>
                                                            Users Statics
                                                        </div>
                                                    </a>
                                                </div>



                                                <?php if($login['Thinapp']['category_name']=="DOCTOR" || $login['Thinapp']['category_name']=="HOSPITAL"){ ?>

                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"HEALTH_TIP")){ ?>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/cms_doc_dashboard',true); ?>">
                                                            <div class="content_div" style="font-size: 10px;">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/cms_pages.png')?>">
                                                                </div>
                                                                Emergency & Health Tip
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <?php } ?>

                                                    <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"VACCINATION")){ ?>

                                                        <div class="col-sm-2 ">
                                                            <a href="<?php echo Router::url('/app_admin/vaccination',true); ?>">
                                                                <div class="content_div">
                                                                    <div class="dash_img">
                                                                        <img src="<?php echo Router::url('/thinapp_images/vaccination.png')?>">
                                                                    </div>
                                                                    Vaccination
                                                                </div>
                                                            </a>
                                                        </div>
                                                    <?php } ?>

                                                    <?php /*if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"PROFILE")){ */?><!--

                                                        <div class="col-sm-2 ">
                                                            <a href="<?php /*echo Router::url('/app_admin/profile',true); */?>">
                                                                <div class="content_div">
                                                                    <div class="dash_img">
                                                                        <img src="<?php /*echo Router::url('/thinapp_images/profile.png')*/?>">
                                                                    </div>
                                                                    My Profile
                                                                </div>
                                                            </a>
                                                        </div>
                                                    --><?php /*} */?>




                                                <?php } ?>
                                                <?php if(!empty($login['AppointmentStaff']['id'])){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/doctor_profile/',true).base64_encode($login['AppointmentStaff']['id']); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/profile.png')?>">
                                                                </div>
                                                                My Profile
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>


                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"WEB_PRESCRIPTION")){ ?>
                                                    <?php if(!empty($login['AppointmentStaff']['id'])){ ?>
                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/prescription/index/',true).base64_encode($login['AppointmentStaff']['id']); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/profile.png')?>">
                                                            </div>
                                                            My Prescription
                                                        </div>
                                                    </a>

                                                </div>

                                                <?php }} ?>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/permission',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/user_permission.png')?>">
                                                            </div>
                                                            User Permission
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/social',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/social_media.png')?>">
                                                            </div>
                                                            Social Setting
                                                        </div>
                                                    </a>
                                                </div>

                                                <!--div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/staff',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/staff_setting.png')?>">
                                                            </div>
                                                            Members
                                                        </div>
                                                    </a>
                                                </div-->


                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/prescription_tag_list',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/prescription.png')?>">
                                                            </div>
                                                            Prescription Tag
                                                        </div>
                                                    </a>
                                                </div>

                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"CHILD_MILESTONE")){ ?>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/child_milestone_list',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/child_milestone.png')?>">
                                                            </div>
                                                            Child Milestone
                                                        </div>
                                                    </a>
                                                </div>
                                                <?php } ?>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/payment_history',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/payment_history.png')?>">
                                                            </div>
                                                            Payment History
                                                        </div>
                                                    </a>
                                                </div>


                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/hospital_department_list',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/order.png')?>">
                                                            </div>
                                                            Department
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/doctor',true); ?>">
                                                        <div class="content_div" style="font-size: 10px;">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/staff_setting.png')?>">
                                                            </div>
                                                            Doctor / Recptionist
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a class="send_sms_icon" href="javascript:void(0);">
                                                        <div class="content_div" >
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/newsletter.png')?>">
                                                            </div>
                                                            Broadcast SMS
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a class="send_sms_icon" href="<?php echo Router::url('/app_admin/list_supplier_order',true); ?>">
                                                        <div class="content_div" >
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/cms_pages.png')?>">
                                                            </div>
                                                            Supplier & Order
                                                        </div>
                                                    </a>
                                                </div>


                                                <div class="col-sm-2 ">
                                                    <a class="cms" href="<?php echo Router::url('/cms/cms',true); ?>">
                                                        <div class="content_div" >
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/cms_icon.png')?>">
                                                            </div>
                                                            CMS
                                                        </div>
                                                    </a>
                                                </div>
                                                
                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"TELEMEDICINE")){ ?>
                                                    <div class="col-sm-2 ">

                                                        <a href="<?php echo Router::url('/telemedicine/telemedicine_report',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/voice_confrence_icon.png')?>">
                                                                </div>

                                                                TELEMEDICINE
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

											<?php if($ivr_number = $this->AppAdmin->has_ivr_call_number(0,$login['User']['thinapp_id'])){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/ivr_call_list/'.base64_encode($ivr_number),true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/voice_confrence_icon.png')?>">
                                                                </div>
                                                                IVR CALLS
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>


                                            </div>

                                        <?php }

                                        else if($login['USER_ROLE'] =='RECEPTIONIST' || $login['USER_ROLE'] =='DOCTOR' || $login['USER_ROLE'] =='STAFF'){ ?>

                                            <div class="col-sm-12 dashboard_icon_li">


                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"QUICK_APPOINTMENT") || $this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"NEW_QUICK_APPOINTMENT")){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/add_appointment',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/opd_icon.png')?>">
                                                                </div>

                                                                OPD
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/opd',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/ipd_icon.png')?>">
                                                            </div>
                                                            IPD
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/list_hospital_emergency',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/emergency_icon.png')?>">
                                                            </div>
                                                            EMERGENCY
                                                        </div>
                                                    </a>
                                                </div>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/hospital_bill.png')?>">
                                                            </div>
                                                            Billing
                                                        </div>
                                                    </a>
                                                </div>

                                                <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"WEB_PRESCRIPTION")){ ?>
                                                    <?php if(!empty($login['AppointmentStaff']['id'])){ ?>
                                                        <div class="col-sm-2 ">
                                                            <a href="<?php echo Router::url('/prescription/index/',true).base64_encode($login['AppointmentStaff']['id']); ?>">
                                                                <div class="content_div">
                                                                    <div class="dash_img">
                                                                        <img src="<?php echo Router::url('/thinapp_images/profile.png')?>">
                                                                    </div>
                                                                    My Prescription
                                                                </div>
                                                            </a>

                                                        </div>

                                                    <?php }} ?>

                                                <?php if($login['USER_ROLE'] =='RECEPTIONIST'){ ?>


                                                    <?php if($this->AppAdmin->check_user_enable_functionlity($login['User']['thinapp_id'],'RECEPTIONIST_CAN_MANAGE_BILLING_SETTING') =='YES'){ ?>
                                                        <div class="col-sm-2 ">
                                                            <a href="<?php echo Router::url('/app_admin/billing_setting',true); ?>">
                                                                <div class="content_div">
                                                                    <div class="dash_img">
                                                                        <img src="<?php echo Router::url('/thinapp_images/appointment_das.png')?>">
                                                                    </div>
                                                                    Billing Setting
                                                                </div>
                                                            </a>
                                                        </div>
                                                    <?php } ?>


                                                    <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"DOCUMENT_MANAGEMENT") && $this->AppAdmin->check_user_enable_functionlity($login['User']['thinapp_id'],'RECEPTIONIST_CAN_MANAGE_PATIENT_MEDICAL_RECORDS') =='YES'){ ?>
                                                        <div class="col-sm-2 ">
                                                            <a href="<?php echo Router::url('/app_admin/drive',true); ?>">
                                                                <div class="content_div">
                                                                    <div class="dash_img">
                                                                        <img src="<?php echo Router::url('/thinapp_images/document.png')?>">
                                                                    </div>
                                                                    Medical Records
                                                                </div>
                                                            </a>
                                                        </div>
                                                    <?php } ?>



                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report_hospital',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/hospital_inventory.png')?>">
                                                                </div>

                                                                Hospital Inventory
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/pharma_inventory.png')?>">
                                                                </div>

                                                                Pharma Inventory
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report_lab',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/lab_inventory.png')?>">
                                                                </div>

                                                                Lab Inventory
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report_service',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/service_inventory.png')?>">
                                                                </div>

                                                                Service & Package
                                                            </div>
                                                        </a>
                                                    </div>

                                                    
                                                <?php } ?>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/doctor_profile/',true).base64_encode($login['AppointmentStaff']['id']); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/profile.png')?>">
                                                            </div>
                                                            My Profile
                                                        </div>
                                                    </a>
                                                </div>


                                                <?php if($login['USER_ROLE'] =='STAFF' && $this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"WEB_PRESCRIPTION")){ ?>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/prescription/index/',true).base64_encode($login['AppointmentStaff']['id']); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/profile.png')?>">
                                                                </div>
                                                                My Prescription
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>



                                                <?php if($login['USER_ROLE'] =='RECEPTIONIST'){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/patient',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/subscribers.png')?>">
                                                                </div>
                                                                Patient List
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                                <div class="col-sm-2 ">
                                                    <a class="send_sms_icon" href="javascript:void(0);">
                                                        <div class="content_div" >
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/newsletter.png')?>">
                                                            </div>
                                                            Broadcast SMS
                                                        </div>
                                                    </a>
                                                </div>
                                                
                                                 <?php if($ivr_number = $this->AppAdmin->has_ivr_call_number(0,$login['User']['thinapp_id'])){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/ivr_call_list/'.base64_encode($ivr_number),true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/voice_confrence_icon.png')?>">
                                                                </div>
                                                                IVR CALLS
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

	<div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/prescription_list/',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/document.png')?>">
                                                            </div>
                                                            Prescription List
                                                        </div>
                                                    </a>
                                                </div>

                                            </div>

                                        <?php }

                                        else if($login['USER_ROLE'] =='LAB' || $login['USER_ROLE'] =='PHARMACY'){ ?>

                                            <div class="col-sm-12 dashboard_icon_li">

                                                <?php if($login['LabPharmacyUser']['is_inhouse'] == 'YES'){ ?>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/lab_patient_inhouse',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/subscribers.png')?>">
                                                                </div>
                                                                Patient List
                                                            </div>
                                                        </a>
                                                    </div>

                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/tracker/display_lab_pharmacy_tracker_new/'.base64_encode($thin_app_id).'/'.base64_encode($login['LabPharmacyUser']['id']),true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                                                                </div>
                                                                Tracker (Template 1)
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/tracker/display_lab_pharmacy_tracker_second/'.base64_encode($thin_app_id).'/'.base64_encode($login['LabPharmacyUser']['id']),true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                                                                </div>
                                                                Tracker (Template 2)
                                                            </div>
                                                        </a>
                                                    </div>

                                                <?php } ?>



                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/hospital_bill.png')?>">
                                                            </div>
                                                            Billing
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/billing_setting',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/appointment_das.png')?>">
                                                            </div>

                                                            Billing Setting
                                                        </div>
                                                    </a>
                                                </div>


                                                <?php if($login['USER_ROLE'] =='LAB'){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report_lab',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/lab_inventory.png')?>">
                                                                </div>

                                                                Lab Inventory
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php }else if($login['USER_ROLE'] =='PHARMACY'){ ?>
                                                    <div class="col-sm-2 ">
                                                        <a href="<?php echo Router::url('/app_admin/get_inventory_report',true); ?>">
                                                            <div class="content_div">
                                                                <div class="dash_img">
                                                                    <img src="<?php echo Router::url('/thinapp_images/pharma_inventory.png')?>">
                                                                </div>

                                                                Pharma Inventory
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php } ?>

                                                <div class="col-sm-2 ">
                                                    <a href="<?php echo Router::url('/app_admin/get_inventory_report_service',true); ?>">
                                                        <div class="content_div">
                                                            <div class="dash_img">
                                                                <img src="<?php echo Router::url('/thinapp_images/service_inventory.png')?>">
                                                            </div>

                                                            Service & Package
                                                        </div>
                                                    </a>
                                                </div>



                                            </div>

                                        <?php }else{ ?>


                                        <?php }?>






                                    </div>
                                </div>
                                <div class="clear"></div>

                        </div>


                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>




<div class="modal fade" id="myModal" role="dialog" >
</div>



<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('form',array( 'class'=>'form','id'=>'form')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Upload Media</h4>
            </div>
            <div class="modal-body">



            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Upload Media',array('class'=>'Btn-typ3','type'=>'button')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>



<script>



    $(document).ready(function(){


        $(document).on('click','.send_sms_icon',function(){
            $.ajax({
                url: "<?php echo Router::url('/app_admin/send_sms',true);?>",
                type:'POST',
                success: function(result){
                    var html = $(result).filter('#send_sms_modal');
                    $(html).modal('show');
                },error:function () {

                }
            });
        });

        $(document).on('click','.post_message_btn',function(e){
            $.ajax({
                url: "<?php echo SITE_PATH;?>app_admin/load_blog_post",
                type:'POST',
                contentType: "application/json; charset=utf-8",
                success: function(result){
                    $("#myModal").modal('show').html(result);
                },error:function () {

                }
            });

        });
    });
</script>
<style>
    .dashboard_icon_li .content_div {
        text-align: center;
        font-family: Georgia;
        text-transform: uppercase;
        border: 1px solid #3dde1a;
        font-weight: bold;
        color: #000000;
        border-radius: 9px;
        width: 100%;
        height: 155px;
    }
    .dash_img > img {
        height: 92px;
        width: 92px;
    }

    .dashboard_icon_li .dash_img {
        display: block;
        text-align: center;
        position: relative;
        padding: 13px 0px 0px 0px;
    }
    .screen_title {
        margin-bottom: -4px !important;
	    font-weight: bold;
		font-size: 20px
    }
</style>









