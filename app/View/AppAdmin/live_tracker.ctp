<?php

error_reporting( 0 );
$login = $this->Session->read('Auth.User');
$role  = $this->Session->read('Auth.User.USER_ROLE');
$staff_data = $this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);
$booked_with_payment_success = "#78f704";
$booked_with_payment_pending = "#fbec3a";
$available_slots = "#FFFFFF";
$expired_time_slots = "#927d7d";
$refunded_appointment = "#04f3ff";
$blocked_slots = "#9765d8";
$emergency = "#ff0000";
$colorData = $this->AppAdmin->get_slot_color($login['User']['thinapp_id']);
foreach($colorData AS $color){
    if($color['AppointmentSlotColor']['type'] == 'booked_with_payment_success')
    {
        $booked_with_payment_success = $color['AppointmentSlotColor']['color_code'];
    }
    else if($color['AppointmentSlotColor']['type'] == 'booked_with_payment_pending'){
        $booked_with_payment_pending = $color['AppointmentSlotColor']['color_code'];
    }
    else if($color['AppointmentSlotColor']['type'] == 'available_slots'){
        $available_slots = $color['AppointmentSlotColor']['color_code'];
    }
    else if($color['AppointmentSlotColor']['type'] == 'expired_time_slots'){
        $expired_time_slots = $color['AppointmentSlotColor']['color_code'];
    }
    else if($color['AppointmentSlotColor']['type'] == 'refunded_appointment'){
        $refunded_appointment = $color['AppointmentSlotColor']['color_code'];
    }
    else if($color['AppointmentSlotColor']['type'] == 'blocked_slots'){
        $blocked_slots = $color['AppointmentSlotColor']['color_code'];
    }
    else if($color['AppointmentSlotColor']['type'] == 'emergency'){
        $emergency = $color['AppointmentSlotColor']['color_code'];
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
        font-size: 17px;
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
        position: relative;

        float: left;


    }

    .add_more_token,
    .add_walk-in_token{
        padding: 30% 0% !important;
        text-align: center;
        background: #8a868696;
        color: #fff;
    }
    .add_walk-in_token:hover,
    .add_more_token:hover{
        background: #dcd8d8;
        color: #0894d4;

    }
    .add_walk-in_token .fa,
    .add_more_token .fa{
        font-size: 50px;
        display: block;
    }

    .add_walk-in_token span,
    .add_more_token span{
        font-size: 20px;
        display: block;
        width: 100%;
    }

    .slot_div_button button{
        padding: 17px 5px;
        text-align: center;
        font-size: 16px !important;
        width: 100% !important;

    }

    .counter_ul{
        padding: 0px;
        margin: 0px;
        list-style: none;
    }
    .select_count{
        float: left;
        list-style: none;
        padding: 7px 4px;
        /* background: red; */
        color: #1b42ff;
        text-align: center;
        width: 40px;
        height: 40px;
        border-radius: 31px;
        background: transparent;
        border: 1px solid;
        margin: 2px;
        cursor: pointer;
    }
    .select_count:hover{
        background: #1b42ff;
        color: #fff;

    }

    .jconfirm-content-pane{
        height: 100% !important;
    }

    .jconfirm-box{
        margin:  0 auto !important;
        width: 600px !important;
    }

    .auto_assing{
        position: absolute !important;
        right: 0 !important;
        top: -1px !important;
        background: red;
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 21px;
        margin: 10px;
        font-size: 15px;
        border: 1px solid red;
    }
    .auto_assing:hover{
        background: #fff;
        color: red;
        border: 1px solid red;
    }

    .LAB_TEST{
        background: yellow;
        border-color:yellow;
        color: #fff;
    }
    .EMERGENCY_CHECKIN{
        background: red;
        border-color:red;
        color: #fff;
    }
    .CURRENT{
        background: green;
        border-color: green;
        color:#fff;
    }

    .label_show{
        display: block;
        width: 100%;
        list-style: none;
        float: left;
        padding: 0px;
        margin: 0 0 10px 0;
    }
    .label_show li{
        float: left;

    }
    .label_show li span{
        width: 20px;
        height: 20px;
        display: block;
        float: left;
        border-radius: 80%;
        margin: 2px 4px;
    }


    .top_slice{
        background: yellow;
        font-size: 12px;
        position: relative;
        float: right;
        width: 65%;
        margin: 4px 2%;
        text-transform: capitalize;
        font-weight: 600;
        padding: 2px 0px;
    }
    .EMERGENCY_CHECKIN, .EMERGENCY_CHECKIN:hover{
        background: red;
    }
    .LATE_CHECKIN, .LATE_CHECKIN:hover{
        background: #ffd211;
        color:#000;
        border-color: #ffd211;
    }
    .REPORT_CHECKIN, .REPORT_CHECKIN:hover{
        background: #00a1ff;
        color:#fff;
        border-color: #00a1ff;

    }
    .CURRENT, .CURRENT:hover{
        background: #80ff00;
        color:#000;
        border-color: #80ff00;
    }
    .slot_blok li .button-div button{
        height: 28px !important;
    }


    .fixed_button > li{
        list-style: none;
        float: right;
        width: auto;
        padding: 0px;
        margin: 5px;

    }

    .fixed_button{
        display: block;
        width: 100%;
        position: absolute;
        right:12px;
        top: -40px;
        float: right;
    }

    .fixed_button .main_btns{
        padding: 2px 10px;
        border-radius: 22px;
        outline: none !important;
    }
    .tracker_msg_ul{
        width:350px;

    }
    .tracker_msg_ul li{
        padding: 5px 7px;
        font-weight: 600;
    }
    .tracker_msg_ul .radio_btn{
        width: 18px;
        height: 18px;
        margin-right: 6px;

    }

    .tracker_msg_ul li label{
        display: block;
        width: 100%;
    }
    .tracker_msg_ul li span{
        position: absolute;
        margin-top: 2px;
    }
    .tracker_msg_ul li button{
        padding: 1px 3px;
    }

    .window_container .popover{
        width: 100%;
    }
    .window_container .popover-content{
        padding: 5px;
    }

    .window_container .popover-content li{
        padding: 5px 0px;
    }

    .hidden_btn{
        font-size: 11px;
        margin: 0px 3px;
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
    }

    .window_datepicker .popover{
        height: 56px;
        width: 210px;
    }

    .top_pop_btn{
        top: -67px;
        position: absolute;
        float: left;
        left: 0px;
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
<div class="fixed_button" >

    <li>
        <button type="button" id="emergency_block" class="main_btns btn btn-info"><i class="fa fa-stop-circle" aria-hidden="true"></i> Stop Booking</button>
    </li>
    <li class="window_datepicker">
        <button type="button" id="leave_block" class="main_btns btn btn-info"><i class="fa fa-power-off" aria-hidden="true"></i> Doctor On Leave</button>
    </li>

    <li style="display: block;" class="window_container">
        <button type="button" data-toggle="confirmation" id="load_template_tool_btn"  class="main_btns btn btn-info"><i class="fa fa-stop-circle"></i> Tracker Break</button>
    </li>
    <li>
        <button style="display: none;" type="button" id="refresh_page"  class="main_btns btn btn-success"><i class="fa fa-refresh" aria-hidden="true"></i> Refresh Screen</button>
    </li>

</div>
<div class="slot_div" id="load_slot_type_box">

    <ul class="slot_blok" >
        <?php $total_appointment = 0;  if(!empty($appointment_list)){
            $queue_number= $time_array = array();
            foreach ($appointment_list as $key => $list){
                $queue_number[] = $list['queue_number'];
                $time_array[] = strtotime($list['time']);
                $walk_in = @(@$list['has_token']=="NO")?' WALK-IN':'';
                $list['flag'] ="BOOKED";
                $consulting_type = isset($list['consulting_type'])?$list['consulting_type']:'';
                if($this->AppAdmin->visible_live_tracker_data($list,$login['USER_ROLE'])){

                    $total_appointment++;
                    if(empty($list['profile_photo'])){
                        $list['profile_photo'] =Router::url('/thinapp_images/staff.jpg',true);
                    }
                    ?>
                    <li data-assign="<?php echo $list['smart_clinic_tracker_queue']; ?>" class="col-lg-3 col-md-3 col-sm-3 col-xs-3 <?php echo ($current_token_value==$list['queue_number'])?'LIVE_TRACKER_CURRENT_TOKEN_LI':''; ?>">


                        <?php $expire_class =  ( strtotime($booking_date) == strtotime(date('m/d/y')) && $list['timestamp'] < strtotime(date('H:i')))?'expire_class':'upcoming_slot'; ?>

                        <?php
                        if($list['flag'] == 'AVAILABLE'){
                            $payStat = "";
                        }
                        else
                        {
                            $payStat = $list['payment_status'];
                        }

                        $status = $list['status'];
                        ?>

                        <div style="<?php echo ($list['skip_tracker']=='YES')?'background:#C0C0C0 !important;border-color:#C0C0C0 !important;':''; ?>"  <?php echo @($list['emergency_appointment']=="YES")?'emergency_appointment':''; ?> data-time="<?php echo $list['time']; ?>" data-i = "<?php echo base64_encode($list['appointment_id']); ?>" data-slot = "<?php echo base64_encode($list['time']); ?>" queue_number = "<?php echo base64_encode($list['queue_number']); ?>" class="slot_main_div <?php echo @($list['emergency_appointment']=="YES")?'emergency_appointment':''; ?> <?php echo $expire_class; ?> <?php echo ($list['status'] == 'REFUND')?$list['status']:''; echo "BOOKED".$walk_in; ?> <?php echo $payStat; ?> " href="javascript:void(0);" >



                            <?php if($list['patient_queue_type'] !='NONE' || $list['skip_tracker']=='YES' ) {
                                $label="";
                                if($list['skip_tracker']=="YES"){
                                    $label = 'LATE';
                                }else if($list['patient_queue_type'] =='LAB_TEST'){
                                    $label = "REPORT PATIENT";
                                }else if($list['patient_queue_type'] =='OTHER_CHECKIN'){
                                    $label = "PRIORITY CHECKIN";
                                }else if($list['patient_queue_type'] !='NONE'){
                                    $label = str_replace("_"," ",$list['patient_queue_type']);
                                }

                                ?>


                                <div class="top_slice"><?php echo $label; echo ($list['patient_queue_type']=='LAB_TEST')?"<span style='font-size: 10px; display: block; line-height: 15px;'>Sending Time &nbsp;".date('h:i A',strtotime($list['send_to_lab_datetime']))."</span>":''; ?> </div>


                            <?php }  ?>



                            <?php
                            $label_token = $list['queue_number'];
                            if($list['emergency_appointment']=="YES"){
                                $label_token = explode(".",$label_token);
                                $label_token = "E".$label_token[1];
                            }

                            ?>




                            <div class='button-div slot_div_button'>
                                <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-warning close_btn"><i class="fa fa-power-off"></i> Close</button>
                                <?php $button = $this->AppAdmin->create_appointment_button_array($login['User']['thinapp_id'],$role,$list); ?>


                                <?php if($button['SKIP']=="YES") { ?>
                                    <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-info skip_btn">Late Patient</button>
                                <?php } ?>

                                <?php if($button['LAB_TEST']=="YES") { ?>
                                    <button type="button" data-token = "<?php echo $list['queue_number']; ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-success send_to_lab"><i class="fa fa-flask"></i> Send For Report</button>
                                <?php } ?>

                                <?php if($button['REPORT_CHECKIN']=="YES") { ?>
                                    <button type="button" data-label-token = "<?php echo $label_token; ?>" data-queue-after = "<?php echo $list['show_after_queue']; ?>" data-token = "<?php echo $list['queue_number']; ?>" data-di ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" data-type="REPORT_CHECKIN" class="btn btn-info report_checked_in"><i class="fa fa-check"></i> Report Check In</button>
                                <?php } ?>

                                <?php if($button['EMERGENCY_CHECKIN']=="YES") { ?>
                                    <button type="button" data-label-token = "<?php echo $label_token; ?>" data-queue-after = "<?php echo $list['show_after_queue']; ?>" data-token = "<?php echo $list['queue_number']; ?>" data-di ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" data-type="EMERGENCY_CHECKIN" class="btn btn-danger emergency_checked_in"><i class="fa fa-check"></i> Emergency Check In</button>
                                <?php } ?>

                                <?php if($button['LATE_CHECKIN']=="YES") { ?>
                                    <button type="button" data-label-token = "<?php echo $label_token; ?>" data-queue-after = "<?php echo $list['show_after_queue']; ?>" data-token = "<?php echo $list['queue_number']; ?>" data-di ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" data-type="LATE_CHECKIN" class="btn btn-warning late_checked_in"><i class="fa fa-check"></i> Late Check In</button>
                                <?php } ?>



                                <button type="button" data-st_id ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-address ="<?php echo base64_encode($list['appointment_address_id']); ?>" data-service ="<?php echo base64_encode($list['appointment_service_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-success btn_reschedule"><i class="fa fa-refresh"></i> Reschedule</button>



                                <?php if($button['EARLY_CHECKIN']=="YES") { ?>
                                    <button type="button" data-label-token = "<?php echo $label_token; ?>" data-queue-after = "<?php echo $list['show_after_queue']; ?>" data-token = "<?php echo $list['queue_number']; ?>" data-di ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" data-type="EARLY_CHECKIN" class="btn btn-success early_checked_in"><i class="fa fa-check"></i> Send Next</button>
                                <?php } ?>

                                <?php if($button['OTHER_CHECKIN']=="YES") { ?>
                                    <button type="button" data-label-token = "<?php echo $label_token; ?>" data-queue-after = "<?php echo $list['show_after_queue']; ?>" data-token = "<?php echo $list['queue_number']; ?>" data-di ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" data-type="OTHER_CHECKIN" class="btn btn-default other_checked_in"><i class="fa fa-check"></i> Priority Check In</button>
                                <?php } ?>



                            </div>
                            <div class='user-info-taken' >
                            	<?php if($consulting_type=='VIDEO'){ ?>
                                    <div class="video_lbl"><i class="fa fa-video-camera"></i> Consulting</div>
                                <?php } ?>
                                <div class='token_class'>
                                    <?php  echo $this->AppAdmin->create_queue_number($list);  ?>
                                </div>
                                <div class='user-info-taken' >
                                    <label><i class='fa fa-user'></i>&nbsp; <?php echo mb_strimwidth( $list['name'], 0, 23, "..."); ?> </label>
                                    <label><i class='fa fa-phone'></i>&nbsp;  <?php echo $list['mobile']; ?></label>
                                </div>

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
                                    echo '<b>&nbsp;|&nbsp;Emergency</b>';
                                }else{
                                    echo '<b>&nbsp;|&nbsp;BOOKED</b>';
                                }


                                ?>



                            </div>
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

                                <?php if($list['is_paid_booking_convenience_fee']== 'YES' ){  ?>
                                    <label  class="total_fee_lbl"><?php  echo "Token Fee : ".$list['is_paid_booking_convenience_fee'];  ?></label>
                                <?php  } ?>
                            </div>


                        </div>
                        <label style="display: none;" class="option_btn close_option_btn"><i class="fa fa-close"></i> </label>
                        <label style="display: none;" type="button" class="option_btn btn btn-default btn-xs option_bar_btn"><i class="fa fa-bars"></i> </label>


                    </li>

                <?php }
            }
            ?>
        <?php } ?>


    </ul>

    <script>



        $(document).ready(function () {



            function getUrlVars()
            {
                var vars = [], hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for(var i = 0; i < hashes.length; i++)
                {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars;
            }

            var tab = getUrlVars()["d"];
            if(tab=='tab'){
                $("#refresh_page").show();
            }else{
                $("#refresh_page").hide();
            }

            function load_sms_tmp(){

                $.ajax({
                    type:'POST',
                    url: baseurl+"tracker/pause_tracker_sms",
                    data:{di:btoa($("#doctor_drp").val())},
                    beforeSend:function(){
                        setTimeout(function () {
                            $(".window_container .hidden_btn").hide();
                            $(".window_container .confirmation-content").html('<i style="margin: 0 45%;" class="fa fa-circle-o-notch fa-spin"></i>').show();
                        },1);

                    },
                    success:function(data){
                        $(".window_container .confirmation-content").html(data).show();
                        $(".window_container .hidden_btn").show();
                    },
                    error: function(data){
                        $(".window_container .popover-content").html('');
                    }
                });
            }

            function load_block_dates(){

                var di =btoa($("#doctor_drp").val());
                var ai =btoa($("#address_drp").val());
                var si =btoa($("#service_drp").val());
                $.ajax({
                    type:'POST',
                    url: baseurl+"tracker/block_date_window",
                    data:{di:di,ai:ai,si:si},
                    beforeSend:function(){
                        setTimeout(function () {
                            $(".window_datepicker .hidden_btn").hide();
                            $(".window_datepicker .confirmation-content").html('<i style="margin: 0 45%;" class="fa fa-circle-o-notch fa-spin"></i>').show();
                        },1);

                    },
                    success:function(data){
                        $(".window_datepicker .confirmation-content").html(data).show();
                        $(".window_datepicker .hidden_btn").show();
                    },
                    error: function(data){
                        $(".window_datepicker .popover-content").html('');
                    }
                });
            }


            $(document).off("click",".live_sms_button");
            $(document).on("click",".live_sms_button",function(e){
                load_sms_tmp();
            });



            function refreshList(){
                $("#live-tracker").trigger('click');
            }


            var total_appointment = "<?php echo $total_appointment; ?>";
            if(total_appointment==0){
                $("#load_slot_type_box").remove();
            }
            setTimeout(function () {
                $(".not_available_doctor").fadeOut(800);
            },1000);





            $(document).off("click",".slot_blok li .slot_main_div");
            $(document).on("click",".slot_blok li .slot_main_div",function(e){



                clickedIndex = $(".slot_blok li slot_main_div").index(this);
                $( ".is-selected" ).removeClass( "is-selected" );
                $(".slot_blok li .slot_main_div:eq("+clickedIndex+")").addClass( "is-selected" ).focus();


            });




            function is_desktop(){
                if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                    return false;
                }return true;
            }

            function allowClickButton(obj){
                if(!is_desktop() && $(obj).closest('li').find(".close_option_btn").is(":visible")){
                    return true;
                }else if(is_desktop()){
                    return true;
                }else{
                    return false;
                }
            }


            if(!is_desktop()){
                $(".BOOKED, .REFUNDBOOKED").closest("li").find('.option_bar_btn').show();
            }

            $(document).off('click','.option_bar_btn');
            $(document).on('click','.option_bar_btn',function () {
                if(!is_desktop()){
                    $(this).hide();
                    $(this).closest("li").find(".close_option_btn").show();
                }
                showButtonSlide($(this).closest("li"),true);

            });

            $(document).off('click','.close_option_btn');
            $(document).on('click','.close_option_btn',function () {
                if(!is_desktop()){
                    $(this).closest("li").find(".option_bar_btn").show();
                    $(this).hide();
                }
                showButtonSlide($(this).closest("li"),false);
            });

            function showButtonSlide(obj,show){
                if(show==true){
                    $(obj).find(".button-div").css('display','block');
                    $(obj).find(".top_slice").css('display','none');

                    $(obj).find(".user-info-taken").css('display','none');
                    $(obj).find(".user-payment-info").css('display','none');
                }else{

                    $(obj).find(".button-div").css('display','none');
                    $(obj).find(".top_slice").css('display','block');
                    $(obj).find(".user-info-taken").css('display','block');
                    $(obj).find(".user-payment-info").css('display','inline-flex');
                }

            }
            $(document).off('mouseenter','.BOOKED, .REFUNDBOOKED');
            $(document).on('mouseenter','.BOOKED, .REFUNDBOOKED',function () {
                if(is_desktop()) {
                    showButtonSlide(this, true);
                }
            });

            $(document).off('mouseleave','.BOOKED, .REFUNDBOOKED');
            $(document).on('mouseleave','.BOOKED, .REFUNDBOOKED',function () {
                if(is_desktop()) {
                    showButtonSlide(this, false);
                }
            });

            var schedule_in_process =false;

            $(document).off('click','.close_btn');
            $(document).on('click','.close_btn',function(e){
                if(allowClickButton(this)) {
                    if (confirm("Are you sure you want to close this appointment ?")) {
                        var $btn = $(this);
                        var obj = $(this);
                        var id = $(this).attr('data-id');
                        $.ajax({
                            type: 'POST',
                            url: baseurl + "app_admin/close_appointment",
                            data: {id: id},
                            beforeSend: function () {
                                $btn.button('loading').html('Wait..')
                            },
                            success: function (data) {
                                var response = JSON.parse(data);
                                if (response.status == 1) {
                                    refreshList();
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
                    }
                }
            });

            $(document).off('click','.skip_btn');
            $(document).on('click','.skip_btn',function(e){
                if(allowClickButton(this)) {
                    var $btn = $(this);
                    var id = $(this).attr('data-id');
                    var row = $(this).closest('tr');
                    var dialog = $.confirm({
                        title: 'Late Appointment',
                        content: 'Are you sure you want to mark this appointment as late?',
                        keys: ['enter', 'shift'],
                        buttons: {
                            Yes: {
                                keys: ['enter'],
                                action: function (e) {
                                    var $btn2 = $(this);
                                    $.ajax({
                                        type: 'POST',
                                        url: baseurl + "app_admin/appointment_skip",
                                        data: {id: id},
                                        beforeSend: function () {
                                            $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                                            $btn2.button('loading');
                                        },
                                        success: function (data) {
                                            $btn.button('reset');
                                            $btn2.button('reset');
                                            data = JSON.parse(data);
                                            if (data.status == 1) {
                                                dialog.close();
                                                $($btn).removeClass("skip_btn");
                                                $($btn).text('Appointment Skipped');
                                                refreshList();
                                            } else {
                                                $.alert(data.message);
                                            }
                                        },
                                        error: function (data) {
                                            $btn.button('reset');
                                            $btn2.button('reset');
                                            $.alert("Sorry something went wrong on server.");
                                        }
                                    });
                                    return false;
                                }
                            },
                            Cancel: {
                                action: function () {
                                    dialog.close();
                                }
                            }
                        }


                    });
                }
            });

            $(document).off('click','.send_to_lab');
            $(document).on('click','.send_to_lab',function(e){
                if(allowClickButton(this)) {
                    var $btn = $(this);
                    var id = $(this).attr('data-id');
                    var token = $(this).attr('data-token');
                    var doctor_id = $("#doctor_drp").val();
                    $.ajax({
                        type: 'POST',
                        url: baseurl + "app_admin/send_to_lab",
                        data: {id: id, di: doctor_id, t: token},
                        beforeSend: function () {
                            $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                        },
                        success: function (data) {
                            $btn.button('reset');
                            data = JSON.parse(data);
                            if (data.status == 1) {
                                refreshList();
                            } else {
                                $.alert(data.message);
                            }
                        },
                        error: function (data) {
                            $btn.button('reset');
                            $btn2.button('reset');
                            $.alert("Sorry something went wrong on server.");
                        }
                    });
                }
            });


            $(document).off('click','.report_checked_in, .emergency_checked_in, .late_checked_in, .other_checked_in, .early_checked_in');
            $(document).on('click','.report_checked_in, .emergency_checked_in, .late_checked_in, .other_checked_in, .early_checked_in',function(){
                if(allowClickButton(this)) {
                    var $btn = $(this);
                    var doctor_id = $(this).attr('data-di');
                    var appointment_id = $(this).attr('data-id');
                    var type = $(this).attr('data-type');
                    var clicked_token = $(this).attr('data-token');
                    var smart_clinic_tracker_queue = $(this).closest("li").attr('data-assign');
                    var patient_type = "";
                    if (type == 'REPORT_CHECKIN') {
                        patient_type = "Report";
                    } else if (type == 'LATE_CHECKIN') {
                        patient_type = "Late";
                    } else if (type == 'EMERGENCY_CHECKIN') {
                        patient_type = "Emergency";
                    } else if (type == 'OTHER_CHECKIN') {
                        patient_type = "Other";
                    } else if (type == 'EARLY_CHECKIN') {
                        patient_type = "Early";
                    }
                    var label_token = $(this).attr('data-label-token');
                    if (smart_clinic_tracker_queue == "AUTO_ASSIGN") {
                        $.ajax({
                            type: 'POST',
                            url: baseurl + "app_admin/check_in_patient",
                            data: {ct: type, di: doctor_id, ai: appointment_id, aat: 1, qn: clicked_token},
                            beforeSend: function () {
                                $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                            },
                            success: function (data) {
                                $btn.button('reset');
                                data = JSON.parse(data);
                                if (data.status == 1) {
                                    $("#live-tracker").trigger("click");
                                    dialog.close();
                                } else {
                                    $.alert(data.message);
                                }
                            },
                            error: function (data) {
                                $btn.button('reset');
                                $.alert("Sorry something went wrong on server.");
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: baseurl + "app_admin/get_doctor_upcoming_token_list",
                            data: {di: doctor_id},
                            beforeSend: function () {
                                $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                            },
                            success: function (data) {
                                $btn.button('reset');
                                data = JSON.parse(data);
                                if (data.status == 1) {
                                    var string = "";
                                    var label_string = "<ul class='label_show'>" +
                                        "<li>Current Token <span style='background:#80ff00;'></span> </li>" +
                                        "<li>Report Patient<span style='background:#00a1ff;'></span> </li>" +
                                        "<li>Emergency Patient<span style='background:red;'></span> </li>" +
                                        "<li>Late Patient<span style='background:#ffd211;'></span> </li>" +
                                        "</ul>";
                                    $.each(data.list, function (index, value) {
                                        var token = value.token;
                                        if (value.emergency_appointment == 'YES') {
                                            var tmp_array = value.token.split('.');
                                            token = "E" + tmp_array[1];
                                        }
                                        string += "<li ><button  rel=tooltip'  data-original-title=" + value.patient_name + " data-queue-after='" + value.show_after_queue + "' queue_number='" + value.token + "' class='patient_name_tooltip select_count " + value.status + "'>" + token + "</button></li>";
                                    });
                                    if (string == "") {
                                        string = "<li ><button   data-queue-after='' queue_number='0' class='patient_name_tooltip select_count CURRENT'><i class='fa fa-plus'></i></button></li>";
                                    }
                                    var content = label_string + '<ul class="counter_ul">' + string + '</ul>';
                                    var dialog = $.confirm({
                                        title: "CheckIn " + patient_type + " Pateint : <b>" + label_token + "<b>",
                                        content: content,
                                        buttons: {
                                            cancel: function () {
                                            }
                                        },
                                        closeIcon: false,
                                        onContentReady: function () {

                                            $(".patient_name_tooltip").tooltip({html: true});

                                            $(document).off("click", ".select_count");
                                            $(document).on("click", ".select_count", function () {
                                                var $btn = $(this);
                                                var queue_number = $(this).attr('queue_number');
                                                var queue_after = $(this).attr('data-queue-after');
                                                var ctoken = $(".CURRENT").attr('queue_number');
                                                var auto_assign_token = 0;
                                                $.ajax({
                                                    type: 'POST',
                                                    url: baseurl + "app_admin/check_in_patient",
                                                    data: {
                                                        ctoken: ctoken,
                                                        qa: queue_after,
                                                        ct: type,
                                                        di: doctor_id,
                                                        ai: appointment_id,
                                                        aat: auto_assign_token,
                                                        qn: queue_number
                                                    },
                                                    beforeSend: function () {
                                                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                                                    },
                                                    success: function (data) {
                                                        $btn.button('reset');
                                                        data = JSON.parse(data);
                                                        if (data.status == 1) {
                                                            $("#live-tracker").trigger("click");
                                                            dialog.close();
                                                        } else {
                                                            $.alert(data.message);
                                                        }
                                                    },
                                                    error: function (data) {
                                                        $btn.button('reset');
                                                        $.alert("Sorry something went wrong on server.");
                                                    }
                                                });
                                            });
                                        }
                                    });

                                } else {
                                    $.alert(data.message);
                                }
                            },
                            error: function (data) {
                                $btn.button('reset');
                                $.alert("Sorry something went wrong on server.");
                            }
                        });
                    }
                }

            });


            $(document).off('click','.btn_reschedule');
            $(document).on('click','.btn_reschedule',function(e){
                if(allowClickButton(this)) {
                    if (schedule_in_process === false) {
                        var $btn = $(this);
                        var id = $(this).attr('data-id');
                        var service = $(this).attr('data-service');
                        var address = $(this).attr('data-address');
                        var st_id = $(this).attr('data-st_id');

                        $.ajax({
                            type: 'POST',
                            url: baseurl + "app_admin/load_reschedule_modal",
                            data: {id: id, service: service, st_id: st_id, address: address},
                            beforeSend: function () {
                                schedule_in_process = true;
                                $btn.button('loading').val('Wait..')
                            },
                            success: function (data) {
                                $("#reschedule").html(data).modal("show");
                                $btn.button('reset');
                                schedule_in_process = false;
                            },
                            error: function (data) {
                                schedule_in_process = false;
                                $btn.button('reset');
                                $(".file_error").html("Sorry something went wrong on server.");
                            }
                        });
                    }
                }


            });



            $(document).off('click','#emergency_block');
            $(document).on('click','#emergency_block',function(e){
                var doctor_id = $('#doctor_drp').val();
                var service_id = $('#service_drp').val();
                var address_id = $('#address_drp').val();
                var date =  $("#appointment_date").val();
                var action_from =  ($(this).attr('id')=="leave_block")?"LEAVE":"EMERGENCY";
                var title = (action_from=='LEAVE')?'Doctor On Leave':'Stop Booking';

                var send_data = {ai:(address_id),di:(doctor_id),si:(service_id),d:date,af:action_from};
                var $btn = $(this);
                var dialog = $.confirm({
                    title: title,
                    content: '<span style="font-size:15px;">This action will cancel all upcoming appointment. Are you sure you want to save this setting?</span>',
                    keys: ['enter', 'shift'],
                    buttons:{
                        Yes: {
                            keys: ['enter'],
                            btnClass:"btn btn-success btn_confirm_block",
                            action:function(e){
                                var $btn2 = $(".btn_confirm_block");
                                $.ajax({
                                    type:'POST',
                                    url: baseurl + "tracker/block_slot",
                                    data:send_data,
                                    beforeSend:function(){
                                        //$btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                                        $btn2.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Saving..');
                                    },
                                    success:function(data){
                                        dialog.close();
                                        //  $btn.button('reset');
                                        $btn2.button('reset');
                                        var response = JSON.parse(data);
                                        if(response.status==1){
                                            $("#appointment_date").trigger('changeDate');
                                        }else{
                                            $.alert(response.message);
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


            //$('#load_template_tool_btn, #leave_block').confirmation('destroy');
            $('#load_template_tool_btn').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                title:load_sms_tmp,
                placement:'bottom',
                html:true,
                singleton:true,
                btnCancelClass:'hidden_btn delete_all_sms btn btn-danger btn-xs',
                btnOkClass:'hidden_btn btn btn-success btn-xs',
                container:'.window_container',
                btnCancelLabel:'Remove Tracker Break',
                onConfirm:function () {
                    var $btn = $(this);
                    var data_obj = [];
                    $(".custom_message_input").each(function (index,value) {
                        var tmp = {};
                        var sms =$(this).val();
                        if(sms !=''){
                            var checked = $(this).closest("li").find("input[type='radio']").is(":checked");
                            data_obj.push({'msg':sms,'checked':checked});
                        }

                    });
                    $.ajax({
                        type:'POST',
                        url: baseurl+"tracker/save_pause_sms",
                        data:{sms_data:JSON.stringify(data_obj),di:btoa($("#doctor_drp").val())},
                        beforeSend:function(){
                            $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Saving..');
                        },
                        success:function(data){
                            $btn.button('reset');

                            // $(".live_sms_button").trigger('click');
                            //$.alert("Save Successfully");
                        },
                        error: function(data){
                        }
                    });
                },
                btnOkLabel:'Save Tracker Break',
                onCancel:function () {
                    var $btn = $(this);
                    var all_sms = 'NO';
                    if($(this).hasClass('delete_all_sms')){
                        all_sms = 'YES';
                    }
                    $.ajax({
                        type:'POST',
                        url: baseurl+"tracker/delete_pause_sms",
                        data:{di:btoa($("#doctor_drp").val()),all_sms:all_sms},
                        beforeSend:function(){
                            $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Saving..');
                        },
                        success:function(data){
                            $btn.button('reset');
                            // $(".live_sms_button").trigger('click');
                            // $.alert("Delete Successfully");

                        },
                        error: function(data){

                        }
                    });
                }
            });

            $('#leave_block').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                title:load_block_dates,
                placement:'bottom',
                html:true,
                singleton:true,
                btnCancelClass:'top_pop_btn hidden_btn delete_all_sms btn btn-danger btn-xs',
                btnOkClass:'top_pop_btn hidden_btn btn btn-success btn-xs',
                container:'.window_datepicker',
                btnCancelLabel:'Cancel',
                onConfirm:function () {
                    var $btn = $(this);
                    var d = $("#blocked_dates").val();
                    var doctor_id = $('#doctor_drp').val();
                    var service_id = $('#service_drp').val();
                    var address_id = $('#address_drp').val();
                    var date =  $("#appointment_date").val();
                    $.ajax({
                        type:'POST',
                        url: baseurl+"tracker/doctor_on_leave",
                        data:{ai:btoa(address_id),di:btoa(doctor_id),si:btoa(service_id),d:d},
                        beforeSend:function(){
                            $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Saving..');
                        },
                        success:function(data){
                            $btn.button('reset');
                        },
                        error: function(data){
                        }
                    });



                },
                btnOkLabel:'Save Setting',
                onCancel:function () {
                    $('#blocked_datepicker').datepicker('hide');
                }
            });

            $(document).off('click','#refresh_page');
            $(document).on('click','#refresh_page',function(e){
                $(this).button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Refresh Screen');
                window.location.reload();
            });

        });
    </script>
</div>

<?php if(empty($total_appointment)){ ?>
    <div class="not_available_doctor">
        <h3><font color="#fff">Appointment Tokens Are Not Available</font></h3>
    </div>
<?php } ?>
