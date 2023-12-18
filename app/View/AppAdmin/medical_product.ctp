<?php
$login = $this->Session->read('Auth.User');
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
                            <div class="form-group row">
                                <div class="col-sm-12">

                                    <div class="table-responsive">
                                        <?php if(!empty($MedicalProduct)){ ?>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>

                                                <th>Name</th>

                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Action</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($MedicalProduct as $key => $list){?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><?php echo $list['MedicalProduct']['name']; ?></td>

                                                    <td>&#x20B9;<?php echo $list['MedicalProduct']['price']; ?></td>
                                                    <td>
                                                        <div class="action_icon">
                                                            <button type="button" id="changeStatus" class="btn btn-primary btn-xs" row-id="<?php echo $list['MedicalProduct']['id']; ?>" ><?php echo ucfirst($list['MedicalProduct']['status']); ?></button>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="action_icon">
                                                            <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'edit_medical_product',base64_encode($list['MedicalProduct']['id']))) ?>" >
                                                                <button type="button" id="editEvent" class="btn btn-primary btn-xs" >EDIT</button>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>
                                    </div>

                                    <?php }else{ ?>
                                        <div class="no_data">
                                            <h2>You have no medical product</h2>
                                        </div>
                                    <?php } ?>

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

<script>

    $(document).ready(function(){

        $("#medical_product").addClass('active');

        $(document).on('click','#changeStatus',function (e) {

            var id = $(this).attr('row-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_medical_product_status',
                data:{id:id},
                type:'POST',
                beforeSend:function () {
                    //var path = baseurl+"images/ajax-loading-small.png";
                    //$(thisButton).removeClass("fa fa-check, fa fa-close");
                    $btn.button('loading').html('<i class="fa fa-circle-o-notch fa-spin"></i>');
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {
                            $(thisButton).html(data.text);

                    }
                    else
                    {
                        alert(data.message);
                    }

                },
                error:function () {
                    $btn.button('reset');
                }
            });
        });
    });
</script>
<style>
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
</style>