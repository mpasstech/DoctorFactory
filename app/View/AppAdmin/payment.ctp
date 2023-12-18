<?php

$login = $this->Session->read('Auth.User');

$is_payment = $this->AppAdmin->getLeadPaymentStatus($login['User']['org_unique_url']);
$current_plan = $this->AppAdmin->getCurrentMembership($login['User']['id']);
$lead = $this->AppAdmin->getLeadData($login['User']['org_unique_url']);
$membership = $this->AppAdmin->getMembership();


$path = Router::url('/img/app_logo.png',true);


?>
<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Payment</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">

            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 payment_frm">
                        <?php echo $this->element('app_admin_inner_tab_header'); ?>
                       
                            <div class="Social-login-box payment_bx">

                                <?php echo $this->element('message'); ?>


                                <div  style="display: <?php echo !empty($current_plan)?'block':'none';?>" >

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <div class="membeship_lable">

                                                    Yor current membership is  <strong><?php echo $current_plan['Membership']['name'];?></strong> will expire on <strong><?php echo date('Y-m-d H:i', strtotime($current_plan['Payments']['membership_expire']));?></strong>
                                                    &nbsp;&nbsp;&nbsp; <a href="javascript:void(0);" class="upgrade_now ">Upgrade Now</a>

                                                </div>
                                        </div>
                                    </div>



                                </div>
                                <div class="member_ship_div" style="display: <?php echo ($is_payment)?'none':'block';?>" >
                                    <?php if(count($membership) > 0){ ?>

                                        <?php foreach ($membership as $member ){ ?>
                                           <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 meber_box">
                                            <div class="member_rad_btn">
                                                <label id="basic"> <?php echo $member['Membership']['name']; ?> membership of Rs. <?php echo $member['Membership']['actual_amount']; ?> ( <a href="<?php echo Router::url('/price',true); ?>" title="view all feature list">View all feature list</a>) </label>

                                            </div>

                                               <div class="btn_div">

                                                   <?php echo $this->Form->create('User',array('method'=>'post','id'=>'pay_frm')); ?>
                                                   <script
                                                       src="https://checkout.razorpay.com/v1/checkout.js"
                                                       data-key="<?php echo RAXORPAY_TEST_KEY; ?>"
                                                       data-amount="<?php echo $member['Membership']['actual_amount']*100; ?>"
                                                       data-name="<?php echo $login['User']['username']; ?>"
                                                       data-description="<?php echo $member['Membership']['name']; ?>"
                                                       data-image="<?php echo $path; ?>"
                                                       data-netbanking="true"

                                                       data-prefill.name="<?php echo $login['User']['username']; ?>"
                                                       data-prefill.email="<?php echo $login['User']['email']; ?>"
                                                       data-prefill.contact="<?php echo $login['User']['mobile']; ?>"
                                                       data-notes.shopping_order_id="21">
                                                   </script>

                                                   <?php echo $this->Form->input('di',array('type'=>'hidden','value'=>base64_encode($member['Membership']['id']))); ?>
                                                   <?php echo $this->Form->input('dn',array('type'=>'hidden','value'=>base64_encode($member['Membership']['name']))); ?>
                                                   <?php echo $this->Form->input('da',array('type'=>'hidden','value'=>base64_encode($member['Membership']['actual_amount']))); ?>

                                                   <?php echo $this->Form->end(); ?>
                                               </div>

                                   </div>

                                        <?php } ?>

                                    <?php } ?>
                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h3>Payment History</h3>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Membership Name</th>
                                                <th>Price/Rs.</th>
                                                <th>Purchase Date</th>
                                                <th>Expire Date</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            if(!empty($payment_history)){
                                                foreach ($payment_history as $key => $member){ ?>
                                                    <tr>
                                                        <td><?php echo $key+1; ?></td>
                                                        <td><?php echo $member['Membership']['name']; ?></td>
                                                        <td><?php echo $member['Membership']['actual_amount']; ?></td>
                                                        <td><?php echo $member['Payments']['membership_start']; ?></td>
                                                        <td><?php echo $member['Payments']['membership_expire']; ?></td>
                                                        <td><?php echo $member['Payments']['membership_status']; ?></td>

                                                    </tr>
                                                <?php }
                                            }
                                            else
                                            { ?>
                                                <tr>
                                                    <td colspan="6" align="center"><b>You have't done any payment!</b></td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
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
    $(document).ready(function () {



        $(document).on('click','.upgrade_now',function(){
            $(".member_ship_div").slideToggle('slow');
        })
        $(".app_inner_tab a").removeClass('active');
        $("#v_payment").addClass('active');

        $("[type=submit]").addClass('Btn-typ3 pay_btn');

    })

</script>
