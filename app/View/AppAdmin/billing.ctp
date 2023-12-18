<?php
$login = $this->Session->read('Auth.User');

?>




<style>
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
    .middle-block {
        margin-top: 30px;
    }
</style>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <?php echo $this->element('billing_inner_header'); ?>
        </div>
    </div>
</div>



<!--div class="Inner-banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Appointment</h2> </div>
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
                    <!-- Heading -->
                    <!--left sidebar-->

                <!--    --><?php /*echo $this->element('app_admin_leftsidebar'); */?>
                    <!--left sidebar-->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box dashboard_box">
                            <h3 class="screen_title">Billing</h3>


                                <div class="form-group">
                                    <div class="row">
                                    <div class="col-sm-12 billing_dashboard">
                                        <div class="col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/add_hospital_receipt_search',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/add_receipt.png'); ?>">
                                                    </div>
                                                    Add Receipt
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/get_hospital_receipt_reports',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/Report.png'); ?>">
                                                    </div>
                                                    Reports
                                                </div>
                                            </a>
                                        </div>


                                        <div class="col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/hospital_patient_list',true); ?>" >
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/subscribers.png')?>">
                                                    </div>
                                                    Patient List
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/hospital_tax_rate',true); ?>" >
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/hospital_tax.png')?>">
                                                    </div>
                                                    Tax Rate
                                                </div>
                                            </a>
                                        </div>



                                        <div class="col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/hospital_service_category',true); ?>" >
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/hospital_category.png')?>">
                                                    </div>
                                                    Service Category
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/hospital_service',true); ?>" >
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/hospital_service.png')?>">
                                                    </div>
                                                    Product & Service
                                                </div>
                                            </a>
                                        </div>

                                        <div class="col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/hospital_payment_type',true); ?>" >
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/hospital_payment.png'); ?>">
                                                    </div>
                                                    Payment Type
                                                </div>
                                            </a>
                                        </div>






                                    </div>
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






