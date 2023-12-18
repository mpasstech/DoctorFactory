<?php
$login = $this->Session->read('Auth.User');
$category = $this->request->data['CmsDocDashboard']['category'];
$subCategoryID = $this->request->data['CmsDocDashboard']['sub_category_id'];
?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-12">
                                    <!--   <h3>Dashboard</h3>-->
                                    <ul class="col-lg-12 col-md-12 dashboard_icon_li">


                                        <li>
                                            <a href="<?php echo Router::url('/app_admin/view_app_schedule',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/appointment.png')?>">
                                                    </div>
                                                    My Appointment
                                                </div>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?php echo Router::url('/app_admin/add_appointment',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/add_appointment.png')?>">
                                                    </div>
                                                    Book Appointment
                                                </div>
                                            </a>
                                        </li>
                                        <?php if($doctor_id){ ?>
                                            <li>

                                                <a href="<?php echo Router::url('/tracker/display?t=',true).base64_encode($login['User']['thinapp_id'])."&&d=".base64_encode($doctor_id); ?>" >
                                                    <div class="content_div">
                                                        <div class="dash_img">
                                                            <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                                                        </div>
                                                        View Tracker
                                                    </div>
                                                </a>


                                            </li>
                                        <?php } ?>
                                        <li>
                                            <a href="<?php echo Router::url('/app_admin/medical_product',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/product.png')?>">
                                                    </div>
                                                    Services
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="<?php echo Router::url('/app_admin/medical_product_orders',true); ?>">
                                                <div class="content_div">
                                                    <div class="dash_img">
                                                        <img src="<?php echo Router::url('/thinapp_images/order.png')?>">
                                                    </div>
                                                    Receipt
                                                </div>
                                            </a>
                                        </li>
                                    </ul>

                                </div>
                            </div>
                            <div class="clear"></div>



                        </div>
                        <?php echo $this->element('app_admin_inner_tab_medical_product'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('MedicalProduct',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_form')); ?>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Name</label>
                                    <?php echo $this->Form->input('name',array('type'=>'text','autofocus','placeholder'=>'Name','label'=>false,'class'=>'form-control cnt','maxlength'=>'60','required'=>true)); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Price</label>
                                    <?php echo $this->Form->input('price',array('type'=>'number','placeholder'=>'Price','label'=>false,'class'=>'form-control cnt','max'=>'9999999','required'=>true)); ?>

                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>


                        </div>



                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".channel_tap a").removeClass('active');
        $("#v_add_channel").addClass('active');
        $("#add_medical_product").addClass('active');
    });
</script>
<style>
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
</style>