<?php
$login = $this->Session->read('Auth.User.User');

$reqData = $this->request->query;
$path = Router::url('/thinapp_images/staff.jpg',true);
$date =date('Y-m-d');
$reqData = $this->request->query;
if(isset($reqData['d']) && !empty($reqData)){
    $date = $reqData['d'];
}

?>
<div class="form-group">
    <div class="row">
        <div class="col-md-12  col-md-offset-3 billing_element">


                <div class="my_app add_receipt col-lg-2 col-md-2 col-md-offset-2  col-lg-offset-2">
                    <a href="<?php echo Router::url('/app_admin/view_app_schedule',true); ?>">
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/appointment.png')?>">
                            </div>
                            My Appointment
                        </div>
                    </a>
                </div>

                <div class="book_app add_receipt col-lg-2 col-md-2">
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
                    <div class="tracker_div add_receipt col-lg-2 col-md-2">

                        <a href="<?php echo Router::url('/tracker/display?t=',true).base64_encode($login['thinapp_id'])."&&d=".base64_encode($doctor_id); ?>" >
                            <div class="content_div">
                                <div class="dash_img">
                                    <img src="<?php echo Router::url('/thinapp_images/users.png')?>">
                                </div>
                                View Tracker
                            </div>
                        </a>


                    </div>
                <?php } ?>
                <div class="receipt_div add_receipt col-lg-2 col-md-2">
                    <a href="<?php echo Router::url('/app_admin/medical_product_orders',true); ?>">
                        <div class="content_div">
                            <div class="dash_img">
                                <img src="<?php echo Router::url('/thinapp_images/order.png')?>">
                            </div>
                            Receipt
                        </div>
                    </a>
                </div>

        </div>
    </div>
    <div class="clear"></div>



</div>
<style>

    .Inner-banner{
        background: none;
    }


</style>
<script>
    $(function () {
        $(".billing_element div").removeClass('active-tab-header');
        var action = "<?php echo $this->params['action']; ?>";

        if(action == 'view_app_schedule' || action=='view_staff_app_schedule'){
            $('.my_app').addClass('active-tab-header');
        }else if(action == 'add_appointment'){
            $('.book_app').addClass('active-tab-header');
        }else if(action == 'medical_product_orders'){
            $('.receipt_div').addClass('active-tab-header');
        }
    })
</script>
