<?php
$login = $this->Session->read('Auth.User');

?>

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
                    <h3 class="screen_title">Appointment</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box dashboard_box">



                                <div class="form-group">
                                    <div class="row">
                                    <div class="col-sm-12 billing_element">
                                        <div class="add_receipt col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/view_app_schedule',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/appointment.png')?>">
                                                    </div>
                                                    My Appointment
                                                </div>
                                            </a>
                                        </div>

                                        <div class="add_receipt col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/add_appointment',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/add_appointment.png')?>">
                                                    </div>
                                                    Book Appointment
                                                </div>
                                            </a>
                                        </div>


                                        <?php if($doctor_id){ ?>
                                            <div class="add_receipt col-lg-2 col-md-2">

                                                <a href="<?php echo Router::url('/tracker/display?t=',true).base64_encode($login['User']['thinapp_id'])."&&d=".base64_encode($doctor_id); ?>" >
                                                    <div class="content_div">
                                                        <div class="dash_img">
                                                            <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                                                        </div>
                                                        View Tracker
                                                    </div>
                                                </a>


                                            </div>
                                        <?php } ?>
                                        <div class="add_receipt col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/medical_product_orders',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/order.png')?>">
                                                    </div>
                                                    Receipt
                                                </div>
                                            </a>
                                        </div>
                                        <div class="add_receipt col-lg-2 col-md-2">
                                            <a href="<?php echo Router::url('/app_admin/setting_print_prescription',true); ?>" target="_blank">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/prescription.png')?>">
                                                    </div>
                                                    Prescription Setting
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
<style>
    .dashboard_icon_li li {

        text-align: center;
        width: 17%;

    }
    .dashboard_icon_li .content_div{
        min-height:146px;
    }
    /*.dashboard_icon_li .content_div {
        text-align: center;
        font-family: Georgia;
        text-transform: uppercase;
        border: 2px solid #9ff28d;
        border-radius: 1em;
        padding: 5px;
        color: black;
        font-weight: bold;
    } */
</style>






