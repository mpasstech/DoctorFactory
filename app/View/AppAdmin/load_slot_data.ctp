<?php
$booked_with_payment_success = "#78f704";
$booked_with_payment_pending = "#fbec3a";
$available_slots = "#FFFFFF";
$expired_time_slots = "#927d7d";
$refunded_appointment = "#04f3ff";
$blocked_slots = "#9765d8";
$emergency = "#ff0000";

foreach($colorData AS $color){
    if($color['type'] == 'booked_with_payment_success')
    {
        $booked_with_payment_success = $color['color_code'];
    }
    else if($color['type'] == 'booked_with_payment_pending'){
        $booked_with_payment_pending = $color['color_code'];
    }
    else if($color['type'] == 'available_slots'){
        $available_slots = $color['color_code'];
    }
    else if($color['type'] == 'expired_time_slots'){
        $expired_time_slots = $color['color_code'];
    }
    else if($color['type'] == 'refunded_appointment'){
        $refunded_appointment = $color['color_code'];
    }
    else if($color['type'] == 'blocked_slots'){
        $blocked_slots = $color['color_code'];
    }
    else if($color['type'] == 'emergency'){
        $emergency = $color['color_code'];
    }

}

?>
<style>
    .emergency_appointment{
        background:<?php echo $emergency; ?> !important;
    }
    .BOOKED.SUCCESS{
        background:<?php echo $booked_with_payment_success; ?> !important;
        border-color:<?php echo $booked_with_payment_success; ?> !important;
    }

    .BOOKED.PENDING{
        background:<?php echo $booked_with_payment_pending; ?> !important;
        border-color:<?php echo $booked_with_payment_pending; ?> !important;
    }

    .expire_class.AVAILABLE{
        background: <?php echo $expired_time_slots; ?> !important;
        border-color:<?php echo $expired_time_slots; ?> !important;
        color:#000;
    }

    .AVAILABLE{
        background: <?php echo $available_slots; ?> !important;
        border-color:#04a6f0;
        color:#04a6f0;

    }
    .BLOCKED{
        background: <?php echo $blocked_slots; ?> !important;
        border-color: <?php echo $blocked_slots; ?>;
        color:#000;

    }

    .REFUNDBOOKED, .REFUND {
        background: <?php echo $refunded_appointment; ?> !important;
        border-color: <?php echo $refunded_appointment; ?> !important;
        color:#000;
    }
    
      .total_fee_lbl{
        float: left;
        position: absolute;
        font-size: 9px;
        background: red;
        color: #fff;
        padding: 1px 5px;
        text-align: center;
        border-radius: 20px;
        line-height: 13px;
        bottom: -2px;
        left:68px;
        width: 35% !important;
    }
	
     .video_lbl{
 		left: -37px !important;
        top: 102px;
        padding: 3px 0px;
        background: red;
        color: #fff;
        font-size: 13px;
        float: left;
        text-align: center;
        width: 48% !important;
        line-height: 1.2;
        border-radius: 1px;
        position: absolute;
        transform: rotate(90deg);
    }
    
</style>
<div class="slot_div" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
<div class="message_span_slot"></div>
<?php
error_reporting( 0 );
$login = $this->Session->read('Auth.User');
$role  = $this->Session->read('Auth.User.USER_ROLE');
$staff_data = $this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);
$selected_doctor_data = $this->AppAdmin->get_doctor_by_id(base64_decode($doctor_id),$login['User']['thinapp_id']);
$smart_clinic = $this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'], "SMART_CLINIC");
$break_array =array();
if($smart_clinic){
    $break_array = $this->AppAdmin->walk_in_button_position($login['User']['thinapp_id'],base64_decode($doctor_id), base64_decode($address_id), base64_decode($service_id),$booking_date);
}


?>

    <ul class="slot_blok">



        <?php if(  in_array($role,array('ADMIN','DOCTOR','RECEPTIONIST'))  && strtotime($booking_date) == strtotime(date('m/d/Y')) ){ ?>

        <?php if(!$this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"SMART_CLINIC")){ ?>
        
        <?php } ?>

		<li class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            <div data-type="WALK-IN" class="add_walk-in_token slot_main_div"  href="javascript:void(0)">
                <i class="fa fa-plus"></i>
                <span>Walk-In Appointment</span>
            </div>
        </li>


            <?php if($selected_doctor_data['allow_emergency_appointment']=='YES'){ ?>
                <li class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                    <div data-type="EMERGENCY" class="add_emergency_token slot_main_div"  href="javascript:void(0)">
                        <i class="fa fa-plus"></i>
                        <span>Emergency Appointment</span>
                    </div>
                </li>
            <?php } ?>

        <?php } ?>

            <?php if(!empty($final_array)){
                $queue_number= $time_array = array();
                    $break_counter = 0;
                foreach ($final_array as $key => $list){
                        $queue_number[] = $list['queue_number'];
                        $time_array[] = strtotime($list['time']);
                        $walk_in = @(@$list['has_token']=="NO")?' WALK-IN':'';
                         $consulting_type = isset($list['consulting_type'])?$list['consulting_type']:'';
                        
                        if (!empty($break_array) && strtotime($break_array[$break_counter]) < strtotime($list['time']) && $break_counter < count($break_array) ){
                         $last_time = $break_array[$break_counter];
                         $break_counter++;


                         ?>
						
                        	 <li class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                            <div data-type="WALK-IN"  class="add_walk-in_token slot_main_div break_walk_in"
                                 data-last-time="<?php echo base64_encode($this->AppAdmin->get_next_walk_in_time_after_break(base64_decode($doctor_id),base64_decode($address_id), date('Y-m-d',strtotime($booking_date)),$last_time,$list['time'])); ?>"
                                 href="javascript:void(0)">
                                <i class="fa fa-plus"></i>
                                <span>Walk-In Appointment</span>
                            </div>
                        </li>
                       

                   <?php }


                        if($list['status'] != 'BLOCKED'){
                        if(empty($list['profile_photo'])){
                            $list['profile_photo'] =Router::url('/thinapp_images/staff.jpg',true);
                        }
                        ?>
                        <li class="col-lg-3 col-md-3 col-sm-3 col-xs-3">

                            <?php $expire_class =  ( strtotime($booking_date) == strtotime(date('m/d/y')) && $list['timestamp'] < strtotime(date('H:i')))?'expire_class':'upcoming_slot'; ?>

                            <?php
                                if($list['flag'] == 'AVAILABLE'){
                                    $payStat = "";
                                }
                                else
                                {
                                    $payStat = $list['payment_status'];
                                }
                            ?>

                        <div  <?php echo @($list['emergency_appointment']=="YES")?'emergency_appointment':''; ?> data-time="<?php echo $list['time']; ?>" data-i = "<?php echo base64_encode($list['appointment_id']); ?>" data-slot = "<?php echo base64_encode($list['time']); ?>" queue_number = "<?php echo base64_encode($list['queue_number']); ?>" class="slot_main_div <?php echo @($list['emergency_appointment']=="YES")?'emergency_appointment':''; ?> <?php echo $expire_class; ?> <?php echo ($list['status'] == 'REFUND')?$list['status']:''; echo $list['flag'].$walk_in; ?> <?php echo $payStat; ?> " href="javascript:void(0);" >




                            <div class='button-div'>

                            <?php if( $list['booking_validity_attempt'] > 1 || $list['is_paid_booking_convenience_fee']!= 'NO'){  ?>
                                <?php if($list['flag']=='BOOKED' || $list['flag']=='RESCHEDULE'){ ?>

                                    <?php

                                    $status =$list['status'];
                                    $payment_status = $list['payment_status'];
                                    $date = date('Y-m-d', strtotime($list['appointment_datetime']))." ".$list['slot_time'];
                                    $appointment_datetime  = (date('Y-m-d H:i', strtotime($date)));
                                    $current_date =  date('Y-m-d H:i');

                                    $ipd = $list['ipd_procedure_amount'];
                                    $vaccination =$list['vaccination_amount'];
                                    $other =$list['other_amount'];
                                    $opdCharge = $list['amount'];
                                    $staff_login = $this->Session->read('Auth.User');
                                    ?>

                                    <?php  if( ($payment_status == "PENDING" || $payment_status == "FAILURE") && $status != 'CANCELED' ) { ?>
                                        <button type="button" data-pt ="<?php echo ($list['customer_type']); ?>" data-pi ="<?php echo base64_encode($list['customer_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" ipd="<?php echo $ipd; ?>" vaccination="<?php echo $vaccination; ?>"  other="<?php echo $other; ?>" opd="<?php echo $opdCharge; ?>" class="btn btn-xs btn-info appointment_pay_btn"><i class="fa fa-money"></i> Pay Now</button>
                                              <button type="button"  data-id ="<?php echo base64_encode($list['id']); ?>"  class="btn btn-xs btn-warning pay_que_btn_skip">Skip Payment Queue</button>
                                          
                                    <?php }else{ ?>

                                        <?php if( (in_array($user_role,array('ADMIN','STAFF','DOCTOR')) || ($user_role =="RECEPTIONIST" && @$staff_data['edit_appointment_payment'] =="YES")) && $status != 'REFUND' && $list['due_amount_settled'] =="NO") { ?>
                                            <button type="button" data-id ="<?php echo base64_encode($list['medical_product_order_id']); ?>"  class="btn btn-xs btn-warning pay_edit_btn"><i class="fa fa-money"></i> Edit Payment</button>
                                        <?php } ?>
                                        <?php if($status != 'REFUND'){ ?>
                                            <?php if(($user_role == 'ADMIN' || $staff_data['allow_refund_payment'] =="YES") && $list['total_billing'] > 0){ ?>


                                                <button type="button" data-id ="<?php echo base64_encode($list['medical_product_order_id']); ?>" class="btn btn-xs btn-warning delete_btn"><i class="fa fa-trash"></i> Refund</button>
                                            <?php } ?>

                                        <?php }else{ ?>
                                            <?php  if(($final_array[$key+1]['queue_number'] != $list['queue_number']) && ($list['sub_token']=='NO')){ ?>
                                                <button type="button" data-slot = "<?php echo base64_encode($list['time']); ?>" queue_number = "<?php echo base64_encode($list['queue_number']); ?>" class="btn btn-xs btn-warning AVAILABLE"><i class="fa fa-plus"></i> Book This Token</button>
                                            <?php } ?>
                                        <?php } ?>
                                        <button type="button"  class="btn btn-xs btn-info" onclick="var win = window.open(baseurl+'app_admin/print_invoice/<?php echo base64_encode($list['medical_product_order_id']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Receipt</button>

                                    <?php } ?>

				
                                   <?php if($selected_doctor_data['print_priscription_when_pending_amount']=='YES' || $list['payment_status']=='SUCCESS'){ ?>
                                                <button type="button"  class="btn btn-xs btn-success" onclick="var win = window.open(baseurl+'app_admin/print_prescription/<?php echo base64_encode($list['id']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa  fa-print"></i> Prescription</button>
                                            <?php } ?>

                                    <?php if($status=="CONFIRM" || $status=="RESCHEDULE" || $status=="NEW" ) { ?>


                                        <?php if ($login['USER_ROLE'] == 'ADMIN' OR $staff_data['allow_change_appointment_doctor'] == "YES" ) { ?>
                                            <button type="button"  data-di ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-info btn_assign_doctor"><i class="fa fa-tasks"></i> Assign Doctor</button>
                                        <?php } ?>



                                        <?php if(isset($list['uhid']) && !empty($list['uhid'])){ ?>
                                            <button type="button"  class="btn history_btn btn-xs btn-warning" src="<?php echo $this->Html->url(array('controller'=>'tracker','action'=>'get_patient_history',base64_encode($login['User']['thinapp_id']),base64_encode($list['uhid']),base64_encode($list['id']))) ?>" ><i class="fa fa-money"></i> Patient History</button>




                                            <button type="button"  class="btn btn-xs btn-info" onclick="var win = window.open(baseurl+'app_admin/add_hospital_receipt_search?u=<?php echo base64_encode($list['uhid']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Other Billing</button>
                                        <?php } ?>

                                        <?php if ($login['USER_ROLE'] == 'ADMIN' OR $staff_data['allow_reschedule_appointment'] == "YES" ) { ?>
                                            <button type="button" data-st_id ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-address ="<?php echo base64_encode($list['appointment_address_id']); ?>" data-service ="<?php echo base64_encode($list['appointment_service_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-success btn_reschedule"><i class="fa fa-refresh"></i> Reschedule</button>
                                        <?php } ?>

                                        <?php if( ( ($payment_status == "PENDING") || ( @$list['total_billing'] == 0 && $list['booking_validity_attempt'] > 1 )  ) ){  ?>

                                            <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-danger cancel_btn"><i class="fa fa-close"></i> Cancel</button>

                                        <?php } ?>
                                        <?php if($consulting_type=='VIDEO' && in_array($role,array('ADMIN','DOCTOR'))){ ?>
                                                    <button type="button" data-href="<?php echo Router::url("/homes/video/",true).base64_encode($list['id']."##DOCTOR"); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-danger start_video_btn"><i class="fa fa-video-camera"></i> Start Video Call</button>
                                                <?php } ?>
                                                
                                                 <?php if( $consulting_type=='AUDIO' && in_array($role,array('ADMIN','DOCTOR'))){ ?>
                                                    <button type="button" data-href="<?php echo "https://mngz.in:3005/?".base64_encode("ai=".$list['id']."&m=".$list['mobile']."&n=".$list['name']."&l=".$login['Thinapp']['logo']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-danger start_audio_btn"><i class="fa fa-phone"></i> Start Audio Call</button>
                                                <?php } ?>
                                                
                                                
                                        <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-warning close_btn"><i class="fa fa-power-off"></i> Close</button>
                                        <?php if($list['mobile'] != '+919999999999'){ ?>
                                        <button type="button" data-pt ="<?php echo ($list['customer_type']); ?>" data-ai ="<?php echo base64_encode($list['id']); ?>" data-pi ="<?php echo base64_encode($list['customer_id']); ?>"  data-m ="<?php echo $list['mobile']; ?>" class="btn btn-xs btn-info switch_patient_btn"><i class="fa fa-toggle-on"></i> Switch Patient</button>
                                        <?php } ?>


                                        <?php if($list['checked_in'] == 'NO'){ ?>
                                            <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-success checked_in_btn"><i class="fa fa-check"></i> Check-In</button>
                                        <?php } ?>

                                    <?php } ?>

                                    <?php $receptionist_allow = ($role=="RECEPTIONIST" && (( $staff_data['allow_book_past_appointment']=="YES" && strtotime($booking_date) <= strtotime(date('m/d/Y'))) || ($staff_data['allow_book_past_appointment']=="NO" && strtotime($booking_date) == strtotime(date('m/d/Y')))))?true:false; ?>
                                    <?php if($receptionist_allow || in_array($role,array('ADMIN','DOCTOR'))){ ?>

                                    <?php if( $list['has_token'] == 'YES' && $list['sub_token']=='NO' && $status != 'REFUND' ){ ?>
                                        <button data-type="SUB_TOKEN" type="button" data-slot = "<?php echo base64_encode($list['time']); ?>" queue_number = "<?php echo base64_encode($list['queue_number']); ?>" class="btn btn-xs btn-warning add_sub_token"><i class="fa fa-plus"></i> Add Sub Token</button>
                                    <?php } ?>


                                <?php } ?>

                                    <?php if($status=="CONFIRM" || $status=="RESCHEDULE" || $status=="NEW" ) { ?>
                                        <?php if($list['skip_tracker']=="NO") { ?>
                                            <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-info skip_btn">Skip Appointment</button>
                                        <?php }else{ ?>
                                            <button  class="btn btn-info btn-xs">Appointment Skipped</button>
                                        <?php } ?>
                                    <?php } ?>

                                <?php } ?>
                                <?php if($list['flag']=='BOOKED' || $list['flag']=='RESCHEDULE' || $list['flag']=='CLOSED'){ ?>

                                    <?php if(isset($list['uhid']) && !empty($list['uhid']) && $list['status']=='CLOSED'){ ?>
                                         <button type="button"  class="btn history_btn btn-xs btn-warning" src="<?php echo $this->Html->url(array('controller'=>'tracker','action'=>'get_patient_history',base64_encode($login['User']['thinapp_id']),base64_encode($list['uhid']),base64_encode($list['id']))) ?>" ><i class="fa fa-money"></i> Patient History</button>
                                       
                                        <button type="button"  class="btn btn-xs btn-info" onclick="var win = window.open(baseurl+'app_admin/add_hospital_receipt_search?u=<?php echo base64_encode($list['uhid']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Other Billing</button>
                                    <?php } ?>

                                    <button type="button" data-pt ="<?php echo ($list['customer_type']); ?>" data-pi ="<?php echo base64_encode($list['customer_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-info edit_patient_btn"><i class="fa fa-pencil"></i> Edit Patient</button>
                                <?php } ?>

                                <?php }else{ ?>
                                    
                                    <label style="text-align: center;">Please wait the token fee is being paid by the patient </label>
                                
                                <?php } ?>

                            </div>
                            <div class='user-info-taken' >

								 <?php if($list['appointment_booked_from']=='APP'){ ?>
                                    <div class="app_booking_lbl"><i class="fa fa-mobile"></i> App Booking</div>
                                <?php } ?>

                                <?php if($consulting_type=='VIDEO'){ ?>
                                            <div class="video_lbl"><i class="fa fa-video-camera"></i> Consulting</div>
                                        <?php }else if($consulting_type=='AUDIO'){ ?>
                                            <div class="video_lbl"><i class="fa fa-phone"></i> Consulting</div>
                                        <?php }else if($consulting_type=='CHAT'){ ?>
                                            <div class="video_lbl"><i class="fa fa-commenting"></i> Consulting</div>
                                        <?php } ?>

                               <?php if($list['reminder_message']=="TOKEN_PENDING"){ ?>
                                            <h3><?php echo $list['reminder_message']; ?></h3>
                                 <?php }else{ ?>
                                    <div class='token_class'>
                                        <?php  echo $this->AppAdmin->create_queue_number($list);  ?>
                                    </div>
                                <?php } ?>



                                <?php if($list['flag']=='BOOKED'){ ?>
                                    <div class='user-info-taken' >
                                        <label><i class='fa fa-user'></i>&nbsp; <?php echo mb_strimwidth( $list['name'], 0, 23, "..."); ?> </label>
                                        <label><i class='fa fa-phone'></i>&nbsp;  <?php echo $list['mobile']; ?></label>
                                    </div>
                                <?php }else{ ?>

                                    <?php $receptionist_allow = ($role=="RECEPTIONIST" && (( $staff_data['allow_book_past_appointment']=="YES" && strtotime($booking_date) <= strtotime(date('m/d/Y'))) || ($staff_data['allow_book_past_appointment']=="NO" && strtotime($booking_date) == strtotime(date('m/d/Y')))))?true:false; ?>
                                    <?php if($receptionist_allow || in_array($role,array('ADMIN','DOCTOR'))){ ?>
                                    <?php if( ($list['sub_token']=='NO') && ($list['flag']!='BLOCKED') ){ ?>
                                        <div class='user-img-class' >
                                            <button data-type="SUB_TOKEN" type="button" data-slot = "<?php echo base64_encode($list['time']); ?>" queue_number = "<?php echo base64_encode($list['queue_number']); ?>" class="btn btn-xs btn-warning add_sub_token"><i class="fa fa-plus"></i> Add Sub Token</button>
                                        </div>
                                    <?php } ?>
                                        <?php } ?>

                                    <div class='user-space-token' >
                                        <label><?php echo ($expire_class=='expire_class' && $list['flag']=='AVAILABLE')?'EXPIRED':$list['flag']; ?></label>
                                    </div>

                                <?php } ?>
                                
                                     <?php if(!empty($list['uhid'])){ ?>
                                    <label class="uhid_lbl">UHID: <?php echo $list['uhid']; ?></label>
                                <?php } ?>

                            </div>



                            <div class='user-info-taken time_slot_label' data-token = "<?php echo $list['queue_number']; ?>" data-time="<?php echo $list['time']; ?>" data-status = "<?php echo $list['flag']; ?>"  >
                                <?php
                                if(empty($list['emergency_appointment']) || $list['emergency_appointment']=='NO'){
                                    echo $list['time'];
                                }
                                if($list['emergency_appointment']=='YES'){
                                    echo '<b>Emergency</b>';
                                }else{
                                    if($list['status'] == 'CLOSED'){
                                        echo '<b>&nbsp;|&nbsp;'.$status.'</b>';
                                    }else{
                                        echo '<b>'.(($list['flag'] !== 'AVAILABLE')?"&nbsp;|&nbsp".$list['flag']:"").'</b>';
                                    }
                                }


                                ?>
                                
                                <?php if($list['is_paid_booking_convenience_fee']== 'YES' ){  ?>
                                            <label  class="total_fee_lbl"><?php  echo "Token Fee : ".$list['is_paid_booking_convenience_fee'];  ?></label>
                                        <?php  } ?>

                            </div>
                            <?php if($list['flag']=='BOOKED'){ ?>
                                <div class='user-payment-info' style="padding: 20px 0px;" >
                                    <div class="payment-type-container"><?php echo mb_strimwidth( $list['booking_payment_type'], 0, 15, "..."); ?></div>
                                    <?php if(@$list['total_billing'] > 0 || $list['booking_validity_attempt'] == 1 ){ ?>
                                        <div class="payment-amount-container"><i class="fa fa-inr" aria-hidden="true"></i> <?php
                                           if(!empty($list['total_billing'])){
                                               echo $list['total_billing'];
                                           }else{
                                               echo ((int)$list['amount']+(int)$list['ipd_procedure_amount']+(int)$list['vaccination_amount']+(int)$list['other_amount']);
                                           }
                                            ?></div>
                                    <?php }else{ ?>
                                        <div class="payment-amount-container"><i class="fa fa-inr" aria-hidden="true"></i> Free/-</div>
                                    <?php } ?>
                                    <div class="payment-status-container">
                                        <?php
                                        if($status != 'REFUND')
                                        {
                                            echo $list['payment_status'];
                                        }
                                        else
                                        {
                                            echo $status.'ED';
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php } ?>



                        </div>
                            <?php if($list['flag']=='BOOKED' || $list['flag']=='RESCHEDULE'){ ?>
                                <label style="display: none;" class="option_btn close_option_btn"><i class="fa fa-close"></i> </label>
                                <label style="display: none;" type="button" class="option_btn btn btn-default btn-xs option_bar_btn"><i class="fa fa-bars"></i> </label>
                            <?php } ?>

                        </li>

                    <?php } }
                            $max_token = (int)max($queue_number);
                            $max_token = !empty($max_token)?($max_token+1):1;
                            $max_time = !empty($time_array)?date('h:i A',max($time_array)):date('h:i A');
                            $new_time = date('h:i A',strtotime("+$duration", strtotime($max_time)));
                    ?>


            <?php }else{ ?>
                <div class="not_available_doctor">
                    <h3><font color="#fff">Appointment Tokens Are Not Available</font></h3>
                </div>
            <?php } ?>

            <?php
                if(empty($new_time)){
                    $new_time = @$this->AppAdmin->get_doctor_address_time(base64_decode($doctor_id),base64_decode($address_id))['time_from'];
                }
                $max_token = !empty($max_token)?$max_token:1;
            ?>


        <?php $permission1 = ($role=="RECEPTIONIST" && $staff_data['allow_book_past_appointment']=="YES" && strtotime($booking_date) <= strtotime(date('m/d/Y')) )  ?>

        <?php $permission2 = ($role=="RECEPTIONIST" && $staff_data['allow_book_past_appointment']=="NO" && strtotime($booking_date) >= strtotime(date('m/d/Y')) )  ?>

        <?php if( $permission1 || $permission2 || in_array($role,array('ADMIN','DOCTOR'))){ ?>

            <li class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <div data-type="ADD_MORE_TOKEN" class="slot_main_div add_more_token" data-slot = "<?php echo base64_encode($new_time); ?>"  data-custom="YES" queue = "<?php echo base64_encode($max_token); ?>" href="javascript:void(0)">
                    <i class="fa fa-plus"></i>
                    <span>Book More Appointment</span>
                </div>
            </li>

        <?php } ?>

    </ul>

    

    <style>








        .user-payment-info {
            display: inline-flex;
            width: 100%;
        }
        .payment-type-container {
            width: 32%;
            padding-top: 12px;
        }
        .payment-amount-container {
            width: 32%;
            border: 1px solid transparent;
            border-radius: 1em;
            margin-bottom: 12px;
            font-weight: bold;
            background-color: #04a6f0;
            color: white;
            font-size: 12px;
            height: 25px;
        }
        .payment-status-container{
            width: 36%;
            padding-top: 12px;
        }
        .token_class {
            float: left;
            text-align: center;
            width: 60px;
            font-weight: 600;
            font-size: 16px;
            color: #0009;
            border: 2px solid;
            border-radius: 0;
            height: 50px;
            padding: 12px 0px;
            background-color: #04a6f0;
            color: white;
        }
        .user-img-class {

            width: 50%;
            float: right;
            text-align: center;
            height: 62px;

        }
        .time_con{ float: left;
            font-size: 17px;
            color: #0a470bb3; }
        .time_con small{ font-size:10px; }
        .user-info-taken{
            display: block;

        }
        .user-space-token {
            width: 100%;
            display: inline-grid;
        }
        .user-info-taken label{
            width: 100%;
        }
        .user-space-token label{
            margin-top: 20%;
        }
        .slot_blok .slot_main_div {
            height: 220px;
            cursor: pointer;
            padding: 2px;
        }
        .slot_div .slot_blok .is-selected{
            border: 2px solid red !important;
        }
        .button-div {
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(87, 85, 85, 0.33);
        }

        .add_more_token,
        .add_walk-in_token, .add_emergency_token{
            padding: 30% 0% !important;
            text-align: center;
            background: #8a868696;
            color: #fff;
        }
        .add_emergency_token:hover,
        .add_walk-in_token:hover,
        .add_more_token:hover{
            background: #dcd8d8;
            color: #0894d4;

        }
        .add_emergency_token .fa,
        .add_walk-in_token .fa,
        .add_more_token .fa{
            font-size: 50px;
            display: block;
        }

        .add_emergency_token span,
        .add_walk-in_token span,
        .add_more_token span{
            font-size: 20px;
            display: block;
            width: 100%;
        }


        .emergency_box{
            background:red !important;
        }

       /* .WALK-IN{
            background: #f35e5ead none repeat scroll 0 0 !important;
            border: 1px solid #f14848 !important;

        }

        .PENDING{
            background:#f74040 !important;
        }*/




    </style>

    <script>



        $(document).ready(function () {


            var theme = "<?php echo $login['Thinapp']['web_theme']; ?>";
            if(theme=="THEME_2"){
                $(".top-info").hide();
            }

            setTimeout(function () {
                $(".not_available_doctor").fadeOut(800);
            },1000);


        var focus_obj;
       /* if (clickedIndex == -1) {
            if($(".upcoming_slot.AVAILABLE").length > 1){
                focus_obj = $(".upcoming_slot.AVAILABLE").first().addClass("is-selected");
            }else{
                focus_obj = $(".add_more_token").addClass("is-selected");
            }
        } else {
            focus_obj = $(".slot_blok li a:eq(" + clickedIndex + ")").addClass("is-selected");
        }
*/
        if($("#search_cus_modal").length == 0 && !$('.modal').is(':visible')){
            if($(".upcoming_slot.AVAILABLE").length > 1){
                focus_obj = $(".upcoming_slot.AVAILABLE").first().addClass("is-selected");
            }else{
                focus_obj = $(".add_more_token").addClass("is-selected");
            }
        }


        $(document).off("click",".slot_blok li .slot_main_div");
        $(document).on("click",".slot_blok li .slot_main_div",function(){

            clickedIndex = $(".slot_blok li slot_main_div").index(this);
            $( ".is-selected" ).removeClass( "is-selected" );
            $(".slot_blok li .slot_main_div:eq("+clickedIndex+")").addClass( "is-selected" ).focus();


        });


		  $(document).off("click",".start_video_btn, .start_audio_btn");
            $(document).on("click",".start_video_btn, .start_audio_btn",function(){
                var height = 600;
                var width = 350;
                if(!is_desktop()){
                    height = window.innerHeight;
                    width = window.innerWidth;
                }
                var url = $(this).attr('data-href');
                var newWin = window.open(url, "PoP_Up", "directories=no,titlebar=no,toolbar=no,location=0,status=no,menubar=0,scrollbars=0,resizable=0,width="+width+",height="+height);
                return false;
            });
            /* this method check web cemra is available or not */
            function detectWebcam(callback) {
                let md = navigator.mediaDevices;
                if (!md || !md.enumerateDevices) return callback(false);
                md.enumerateDevices().then(devices => {
                    callback(devices.some(device => 'videoinput' === device.kind));
                })
            }
            detectWebcam(function(hasWebcam) {
                if(hasWebcam){
                    $(".start_video_btn").show();
                }else{
                    $(".start_video_btn").hide();
                }
            })



        $("[rel=tooltip]").tooltip({html:true});



        function is_desktop(){
            if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                return false;
            }return true;
        }



        if(!is_desktop()){
            $(".BOOKED, .REFUNDBOOKED").closest("li").find('.option_bar_btn').show();
        }

        $(document).off('click','.option_bar_btn');
        $(document).on('click','.option_bar_btn',function () {
            $(this).closest("li").find(".BOOKED, .REFUNDBOOKED").trigger('mouseenter');
        });

        $(document).off('click','.close_option_btn');
        $(document).on('click','.close_option_btn',function () {
            $(this).closest("li").find(".BOOKED, .REFUNDBOOKED").trigger('mouseleave');
        });


        $(document).off('mouseenter','.BOOKED, .REFUNDBOOKED');
        $(document).on('mouseenter','.BOOKED, .REFUNDBOOKED',function () {
            if(!is_desktop()){
                $(this).closest("li").find(".option_bar_btn").hide();
                $(this).closest("li").find(".close_option_btn").show();
            }
            $(this).find(".button-div").css('display','block');
            $(this).find(".user-info-taken").css('display','none');
            $(this).find(".user-payment-info").css('display','none');
        });

        $(document).off('mouseleave','.BOOKED, .REFUNDBOOKED');
        $(document).on('mouseleave','.BOOKED, .REFUNDBOOKED',function () {
            if(!is_desktop()){
                $(this).closest("li").find(".close_option_btn").hide();
                $(this).closest("li").find(".option_bar_btn").show();
            }

            $(this).find(".button-div").css('display','none');
            $(this).find(".user-info-taken").css('display','block');
            $(this).find(".user-payment-info").css('display','inline-flex');
        });




        var schedule_in_process =false;
        $(document).off('click','.btn_reschedule');
        $(document).on('click','.btn_reschedule',function(e){

            if(schedule_in_process === false){
                var $btn = $(this);
                var id = $(this).attr('data-id');
                var service = $(this).attr('data-service');
                var address = $(this).attr('data-address');
                var st_id = $(this).attr('data-st_id');

                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/load_reschedule_modal",
                    data:{id:id,service:service,st_id:st_id,address:address},
                    beforeSend:function(){
                        schedule_in_process = true;
                        $btn.button('loading').val('Wait..')
                    },
                    success:function(data){
                        $("#reschedule").html(data).modal("show");
                        $btn.button('reset');
                        schedule_in_process = false;
                    },
                    error: function(data){
                        schedule_in_process =false;
                        $btn.button('reset');
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });
            }


        });



        $(document).off('click','.close_btn');
        $(document).on('click','.close_btn',function(e){
            if(confirm("Are you sure you want to close this appointment ?")) {
                var $btn = $(this);
                var obj = $(this);
                var id = $(this).attr('data-id');
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/close_appointment",
                    data:{id:id},
                    beforeSend:function(){
                        $btn.button('loading').html('Wait..')
                    },
                    success:function(data){
                        var response = JSON.parse(data);
                        if(response.status==1){
                            $("#appointment_date").trigger('changeDate');
                            $btn.button('reset');
                        }else{
                            alert(response.message);
                            $btn.button('reset');
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });
            }
        });

        $(document).off('click','.checked_in_btn');
        $(document).on('click','.checked_in_btn',function(e){
                if(confirm("Are you sure you want to check-in this appointment ?")) {
                    var $btn = $(this);
                    var obj = $(this);
                    var id = $(this).attr('data-id');
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/check_in_appointment",
                        data:{id:id},
                        beforeSend:function(){
                            $btn.button('loading').html('Wait..')
                        },
                        success:function(data){
                            var response = JSON.parse(data);
                            if(response.status==1){
                                $("#appointment_date").trigger('changeDate');
                                $btn.button('reset');
                            }else{
                                alert(response.message);
                                $btn.button('reset');
                            }
                        },
                        error: function(data){
                            $btn.button('reset');
                            alert("Sorry something went wrong on server.");
                        }
                    });
                }
            });

        $(document).off('submit','#cancelForm');
        $(document).on('submit','#cancelForm',function(e){
            e.preventDefault();
            var $btn = $(this).find("[type='submit']");
            var obj = $(this);
            var dataToSend = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/cancel_appointment",
                data: dataToSend,
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    var response = JSON.parse(data);
                    if (response.status == 1) {
                        $("#myModalCancelForm").modal("hide");
                        $("#appointment_date").trigger('changeDate');
                        $btn.button('reset');
                    } else {
                        alert(response.message);
                        $btn.button('reset');
                    }
                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });
        });

        $(document).off('click','.cancel_btn');
        $(document).on('click','.cancel_btn',function(e){
            $("#myModalCancelForm").modal("show");
            var id = $(this).attr('data-id');
            $("#cancelForm")[0].reset();
            $("#cancelIdHolder").val(id);
        });

        /*  $(document).off('click','.delete_btn');
         $(document).on('click','.delete_btn',function(e){
         $("#myModalRefundForm").modal("show");
         var id = $(this).attr('data-id');
         $("#refundForm")[0].reset();

         $("#refundIdHolder").val(id);
         });
         */



        $(document).off('submit','#refundForm');
        $(document).on('submit','#refundForm',function(e){
            e.preventDefault();
            var $btn = $(this).find("[type='submit']");
            var obj = $(this);
            var dataToSend = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/refund_order_amount",
                data: dataToSend,
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    var response = JSON.parse(data);
                    if (response.status == 1) {
                        $("#appointment_date").trigger('changeDate');
                        $("#myModalRefundForm").modal("hide");
                        $btn.button('reset');

                    } else {
                        alert(response.message);
                        $btn.button('reset');
                    }
                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });
        });

        $(document).off('click','.skip_btn');
        $(document).on('click','.skip_btn',function(e){
            var $btn = $(this);
            var id = $(this).attr('data-id');
            var row = $(this).closest('tr');
            var dialog = $.confirm({
                title: 'Skip Appointment',
                content: 'Are you sure you want to skip this appointment.',
                keys: ['enter', 'shift'],
                buttons:{
                    Yes: {
                        keys: ['enter'],
                        action:function(e){
                            var $btn2 = $(this);
                            $.ajax({
                                type:'POST',
                                url: baseurl+"app_admin/appointment_skip",
                                data:{id:id},
                                beforeSend:function(){
                                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                                    $btn2.button('loading');
                                },
                                success:function(data){
                                    $btn.button('reset');
                                    $btn2.button('reset');
                                    data = JSON.parse(data);
                                    if(data.status==1){
                                        dialog.close();
                                        $btn.remove();

                                    }else{
                                        $.alert(data.message);
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $btn2.button('reset');
                                    $.alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    Cancel: {
                        action:function () {
                            dialog.close();
                        }
                    }
                }


            });
        });

	
    	$(document).off('click','.pay_que_btn_skip');
        $(document).on('click','.pay_que_btn_skip',function(e){
                var $btn = $(this);
                var id = $(this).attr('data-id');
                var dialog = $.confirm({
                    title: 'Skip Payment Queue From Tracker',
                    content: 'Are you sure you want to skip this appointment from payment queue.',
                    keys: ['enter', 'shift'],
                    buttons:{
                        Yes: {
                            keys: ['enter'],
                            action:function(e){
                                var $btn2 = $(this);
                                $.ajax({
                                    type:'POST',
                                    url: baseurl+"app_admin/skip_payment_queue",
                                    data:{id:id},
                                    beforeSend:function(){
                                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                                        $btn2.button('loading');
                                    },
                                    success:function(data){
                                        $btn.button('reset');
                                        $btn2.button('reset');
                                        data = JSON.parse(data);
                                        if(data.status==1){
                                            dialog.close();
                                            $btn.remove();
                                        }else{
                                            $.alert(data.message);
                                        }
                                    },
                                    error: function(data){
                                        $btn.button('reset');
                                        $btn2.button('reset');
                                        $.alert("Sorry something went wrong on server.");
                                    }
                                });
                                return false;
                            }
                        },
                        Cancel: {
                            action:function () {
                                dialog.close();
                            }
                        }
                    }


                });
            });

        $(document).off('click','.delete_btn');
        $(document).on('click','.delete_btn',function(){
            var id = $(this).attr('data-id');
            var $btn = $(this);
            $.ajax({
                url: "<?php echo Router::url('/app_admin/refund_amount',true);?>",
                type:'POST',
                data:{id:id},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#myModalRefundForm');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });

        $(document).off('click','.appointment_pay_btn');
        $(document).on('click','.appointment_pay_btn',function(e){
            var $btn = $(this);
            var obj = $(this);
            var ai = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/get_add_appointment_payment",
                data: {ai: ai},
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    $btn.button('reset');
                    var html = $(data).filter('#myModalFormAdd');
                    $(html).modal('show');
                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        });

        $(document).off('click','.pay_edit_btn');
        $(document).on('click','.pay_edit_btn',function(e){


            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/getEditMedicalOrder",
                data: {id: id},
                beforeSend: function () {
                    $btn.button('loading').html('Wait..')
                },
                success: function (data) {
                    $btn.button('reset');
                    var html = $(data).filter('#editPaymentModal');
                    $(html).modal('show');
                },
                error: function (data) {
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        });


        $(document).off('click','.btn_assign_doctor');
        $(document).on('click','.btn_assign_doctor',function(e){


            var obj = $(this);
            var ai = $(this).attr('data-id');
            var last_di = $(this).attr('data-di');
            var temp = $('#doctor_drp').clone();
            var selected = $('#doctor_drp').val();

            var string = $(temp).attr('id','temp_doctor_list').prop('outerHTML');

            var dialog = $.confirm({
                title: 'Assign Doctor!',
                content: '' +
                '<div class="form-group">' +
                '<label>Select Doctor</label>' +
                 string +
                '</div>',
                buttons: {
                    formSubmit: {
                        text: 'Assign',
                        btnClass: 'btn-blue assign_btn',
                        action: function () {
                            var $btn = $(".assign_btn");
                            var doctor_id = btoa(this.$content.find('#temp_doctor_list').val());
                             if(doctor_id){
                                $.ajax({
                                    type: 'POST',
                                    url: baseurl + "app_admin/assign_appointment_doctor",
                                    data: {ai:ai,di:doctor_id,last_di:last_di},
                                    beforeSend: function () {
                                        $btn.button({loadingText: 'Changing...'}).button('loading');
                                    },
                                    success: function (data) {
                                        $btn.button('reset');
                                        data = JSON.parse(data);
                                        if(data.status==1){
                                            dialog.close();
                                            $("#appointment_date").trigger('changeDate');
                                        }else{
                                            $.alert(data.message);
                                        }

                                    },
                                    error: function (data) {
                                        $btn.button('reset');
                                        $.alert("Sorry something went wrong on server.");
                                    }
                                });
                            }else{
                                 $.alert("Please select doctor");
                             }
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {

                   $("#temp_doctor_list").val(selected);
                }
            });





        });
        
        	
             $(document).off('click','.switch_patient_btn');
            $(document).on('click','.switch_patient_btn',function(e){

                var $btn = $(this);
                var mobile = $(this).attr('data-m');
                var pt = $(this).attr('data-pt');
                var pi = $(this).attr('data-pi');
                var ai = $(this).attr('data-ai');
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/switch_patient_modal",
                    data:{m:mobile,pt:pt,pi:pi,ai:ai},
                    beforeSend:function(){
                        $btn.button('loading').val('Wait..')
                    },
                    success:function(data){
                        $btn.button('reset');
                        var html = $(data).filter('#switchModal');
                        $(html).modal('show');
                    },
                    error: function(data){
                        $btn.button('reset');
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });

            });

            /* function setSearch(){

                if(tokenSearch == "first_available")
                {
                    $('#first_available').prop("checked",true);
                }
                else if(tokenSearch == "bottom_to_top"){
                    $('#bottom_to_top').prop("checked",true);
                }
                else if(tokenSearch == "top_to_bottom"){
                    $('#top_to_bottom').prop("checked",true);
                }
                else if(tokenSearch != ""){
                    $("#token_search").val(tokenSearch);
                }
            }

            setSearch();
            $(document).off("input click","#first_available,#bottom_to_top,#top_to_bottom,#token_search");
            $(document).on("input click","#first_available,#bottom_to_top,#top_to_bottom,#token_search",function(){
                tokenSearch = $(this).val();
                setSearch();
                if(tokenSearch == "first_available")
                {
                    $('#first_available').prop("checked",true);
                    $(".upcoming_slot.AVAILABLE:first").addClass( "is-selected" ).focus();
                    $("#token_search").val('');
                }
                else if(tokenSearch == "bottom_to_top"){
                    $('#bottom_to_top').prop("checked",true);
                    $("#token_search").val('');
                }
                else if(tokenSearch == "top_to_bottom"){
                    $('#top_to_bottom').prop("checked",true);
                    $("#token_search").val('');
                }
                else if(tokenSearch != ""){
                    $("#token_search").val(tokenSearch);
                    $('#first_available').prop("checked",false);
                    $('#bottom_to_top').prop("checked",false);
                    $('#top_to_bottom').prop("checked",false);
                }
            }); */

        });
    </script>
</div>


