<?php if($type=="ALL_APPOINTMENT"){ ?>
    <div class="slot_div">
        <div class="message_span_slot"></div>
        <?php if(!empty($appointment_list)){ ?>
            <table class="table slot_table" style="font-size: 12px;" >
                <tr>
                    <th width="3%">UHID</th>
                    <th width="3%">Token</th>
                    <th width="2%">Title</th>
                    <th width="12%">Name</th>
                    <th width="8%">Mobile</th>
                    <th width="5%">Age</th>
                    <th width="5%">Gender</th>

                    <th width="5%">Fees</th>
                    <th width="5%">Payment Status</th>
                    <th width="3%">Payment Via</th>
                    <th width="8%">Con. Doctor</th>
                    <th width="8%">Department</th>
                    <th width="5%">Country</th>
                    <th width="5%">State</th>
                    <th width="5%">City</th>
                    <th width="5%">Hospital UHID</th>
                    <th width="15%">H UHID</th>

                </tr>
                <?php  foreach ($appointment_list as $key => $value){ ?>
                    <tr>
                        <td><?php echo $value['uhid']; ?></td>
                        <td><?php echo $this->AppAdmin->create_queue_number($value); ?></td>
                        <td><?php echo $value['title']; ?></td>
                        <td><?php echo $value['patient_name']; ?></td>
                        <td><?php echo $value['patient_mobile']; ?></td>
                        <td><?php
                            echo $value['patient_age'];

                            ?>
                        </td>
                        <td><?php echo $value['gender']; ?></td>

                        <td><?php echo $value['amount']; ?></td>
                        <td><?php echo $value['payment_status']; ?></td>
                        <td><?php echo $value['payment_via']; ?></td>
                        <td><?php echo $value['doctor_name']; ?></td>
                        <td><?php echo $value['doctor_department']; ?></td>
                        <td><?php echo $value['country_name']; ?></td>
                        <td><?php echo $value['state_name']; ?></td>
                        <td><?php echo $value['city_name']; ?></td>
                        <td>
                            <?php if(!empty($value['patient_id'])){ echo $value['hospital_uhid']; } ?>
                        </td>

                        <td style="padding:8px 2px;">
                            <?php if(!empty($value['patient_id'])){ ?>
                                <input style="width: 65%; padding:3px 0px; float:left;" class="hospital_uhid" value="<?php echo $value['hospital_uhid']; ?>">
                                <button style="float: left; padding:6px 0px; margin: 0px;"  type="button" class="save_uhid_btn btn btn-xs btn-success" data-pi="<?php echo base64_encode($value['patient_id']); ?>"><i class="fa fa-save"></i> </button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php  } ?>
            </table>

        <?php } ?>
        <style>
            .slot_table th{
                padding: 0px 5px !important;
            }
            .save_uhid_btn{
                float: left;
                padding: 3px 1px;
                border-radius: 0px;
                margin: 0px 4px;
                width: 30px;
            }
        </style>
        <script>
            $(function(){
                $(document).off('click','.save_uhid_btn');
                $(document).on('click','.save_uhid_btn',function(e){
                    var $btn = $(this);
                    var pi = $(this).attr('data-pi');
                    var uhid = $(this).closest('td').find('.hospital_uhid').val();
                    $.ajax({
                        type: 'POST',
                        url: baseurl + "app_admin/update_patient_hospital_uhid",
                        data: {pi: pi,uhid:uhid},
                        beforeSend: function () {
                            $btn.button('loading').html('<i class="fa fa-spinner fa-spin state_spin"></i>');
                        },
                        success: function (data) {
                            $btn.button('reset');
                            var response = JSON.parse(data);
                            if(response.status==1){
                                $($btn).closest("tr").hide();
                            }
                        },
                        error: function (data) {
                            $btn.button('reset');
                        }
                    });

                });
            })
        </script>
    </div>

<?php }else if($type=="LIVE_TRACKER"){ ?>


<?php }else{ ?>
    <div class="slot_div">
        <div class="message_span_slot"></div>
        <?php
        error_reporting( 0 );
        $login = $this->Session->read('Auth.User');
        $staff_data = $this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);
        ?>
        <?php if(!empty($final_array)){
            $queue_number= $time_array = array();
            ?>
            <table class="table slot_table">
                <tr>
                    <th>#</th>
                    <th>Token</th>
                    <th>Time</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>More</th>
                </tr>
                <?php
                $int = 0;
                foreach ($final_array as $key => $list){

                    $bookStatus = $list['status'];

                    if( $type == "ALL_BOOKED" ) {
                        if ($list['flag'] != 'BOOKED' || $bookStatus == 'CANCELED') {
                            continue;
                        }
                    }
                    else if($type == "CANCEL")
                    {
                        if ($bookStatus != 'CANCELED') {
                            continue;
                        }
                    }
                    else if($type == "CLOSE")
                    {
                        if ($bookStatus != 'CLOSED') {
                            continue;
                        }
                    }
                    else if($type == "EMERGENCY")
                    {
                        if ($list['flag'] != 'BOOKED' || $bookStatus == 'CANCELED' || $list['emergency_appointment'] == 'NO') {
                            continue;
                        }
                    }
                    else if($type == "CHECKED_IN")
                    {
                        if ($list['flag'] != 'BOOKED' || $bookStatus == 'CANCELED' || $list['checked_in'] == 'NO') {
                            continue;
                        }
                    }




                    $int++;
                    $queue_number[] = $list['queue_number'];
                    $time_array[] = strtotime($list['time']);
                    $walk_in = @(@$list['has_token']=="NO")?' WALK-IN':'';
                    if($list['status'] != 'BLOCKED'){
                        if(empty($list['profile_photo'])){
                            $list['profile_photo'] =Router::url('/thinapp_images/staff.jpg',true);
                        }
                        ?>
                        <tr data-time="<?php echo $list['time']; ?>" data-i = "<?php echo base64_encode($list['appointment_id']); ?>" data-slot = "<?php echo base64_encode($list['time']); ?>" queue_number = "<?php echo base64_encode($list['queue_number']); ?>" >

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


                            <td>
                                <?php echo $int; ?>
                            </td>

                            <td>
                                <?php echo $this->AppAdmin->create_queue_number($list); ?>
                            </td>
                            <td>
                                <?php echo ($list['emergency_appointment']=="YES")?date('h:i A',strtotime($list['created'])):$list['time']; ?>
                            </td>
                            <?php if($list['flag']=='BOOKED'){ ?>
                                <td>
                                    <?php echo mb_strimwidth( $list['name'], 0, 23, "..."); ?>
                                </td>
                                <td>
                                    <?php echo $list['mobile']; ?>
                                </td>
                            <?php }else{ ?>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <td>
                                <?php
                                echo ($expire_class=='expire_class' && $list['flag']=='AVAILABLE')?'EXPIRED':$list['status'];
                                ?>
                            </td>

                            <?php if($list['flag']=='BOOKED'){ ?>
                                <td>
                                    <?php echo $list['booking_payment_type']; ?>
                                </td>
                                <td>
                                    <?php if(@$list['total_billing'] > 0 || $list['booking_validity_attempt'] == 1 ){ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i> <?php
                                        if(!empty($list['total_billing'])){
                                            echo $list['total_billing'];
                                        }else{
                                            echo ((int)$list['amount']+(int)$list['ipd_procedure_amount']+(int)$list['vaccination_amount']+(int)$list['other_amount']);
                                        }
                                        ?>
                                    <?php }else{ ?>
                                        <i class="fa fa-inr" aria-hidden="true"></i> Free/-
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                    $status =$list['status'];
                                    if($status != 'REFUND')
                                    {
                                        echo $list['payment_status'];
                                    }
                                    else
                                    {
                                        echo $status.'ED';
                                    }
                                    ?>
                                </td>
                            <?php }else{ ?>
                                <td></td>
                                <td></td>
                                <td></td>
                            <?php } ?>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Option
                                        <span class="caret"></span></button>
                                    <ul class="dropdown-menu pull-right option_btn_panel">
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
                                            <?php }else{ ?>

                                                <?php if( (in_array($user_role,array('ADMIN','STAFF','DOCTOR')) || ($user_role =="RECEPTIONIST" && @$staff_data['edit_appointment_payment'] =="YES")) && $status != 'REFUND' && $list['due_amount_settled'] =="NO") { ?>
                                                    <button type="button" data-id ="<?php echo base64_encode($list['medical_product_order_id']); ?>"  class="btn btn-xs btn-warning pay_edit_btn"><i class="fa fa-money"></i> Edit Payment</button>
                                                <?php } ?>
                                                <?php if($status != 'REFUND'){ ?>
                                                    <?php if((in_array($user_role,array('ADMIN','STAFF','DOCTOR')) || ($user_role =="RECEPTIONIST" && @$staff_data['allow_refund_payment'] =="YES")) && @$list['total_billing'] > 0){ ?>


                                                        <button type="button" data-id ="<?php echo base64_encode($list['medical_product_order_id']); ?>" class="btn btn-xs btn-warning delete_btn"><i class="fa fa-trash"></i> Refund</button>
                                                    <?php } ?>

                                                <?php }else{ ?>
                                                    <?php  if(($final_array[$key+1]['queue_number'] != $list['queue_number']) && ($list['sub_token']=='NO')){ ?>
                                                        <button type="button" data-slot = "<?php echo base64_encode($list['time']); ?>" queue_number = "<?php echo base64_encode($list['queue_number']); ?>" class="btn btn-xs btn-warning AVAILABLE"><i class="fa fa-plus"></i> Book This Token</button>
                                                    <?php } ?>
                                                <?php } ?>
                                                <button type="button"  class="btn btn-xs btn-info" onclick="var win = window.open(baseurl+'app_admin/print_invoice/<?php echo base64_encode($list['medical_product_order_id']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Receipt</button>
                                                <button type="button"  class="btn btn-xs btn-success" onclick="var win = window.open(baseurl+'app_admin/print_prescription/<?php echo base64_encode($list['id']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa  fa-pencil"></i> Print Prescription</button>
                                            <?php } ?>



                                            <?php if($status=="CONFIRM" || $status=="RESCHEDULE" || $status=="NEW" ) { ?>


                                                <?php if ($login['USER_ROLE'] == 'ADMIN' OR $staff_data['allow_change_appointment_doctor'] == "YES" ) { ?>
                                                    <button type="button"  data-di ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-info btn_assign_doctor"><i class="fa fa-tasks"></i> Assign Doctor</button>
                                                <?php } ?>



                                                <?php if(isset($list['uhid']) && !empty($list['uhid'])){ ?>
                                                    <button type="button"  class="btn btn-xs btn-warning" onclick="var win = window.open(baseurl+'app_admin/get_patient_history/<?php echo base64_encode($login['User']['thinapp_id'])."/".base64_encode($list['uhid']); ?>'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Patient History</button>
                                                    <button type="button"  class="btn btn-xs btn-info" onclick="var win = window.open(baseurl+'app_admin/add_hospital_receipt_search?u=<?php echo base64_encode($list['uhid']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Other Billing</button>
                                                <?php } ?>

                                                <?php if ($login['USER_ROLE'] == 'ADMIN' OR $staff_data['allow_reschedule_appointment'] == "YES" ) { ?>
                                                    <button type="button" data-st_id ="<?php echo base64_encode($list['appointment_staff_id']); ?>" data-address ="<?php echo base64_encode($list['appointment_address_id']); ?>" data-service ="<?php echo base64_encode($list['appointment_service_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-success btn_reschedule"><i class="fa fa-refresh"></i> Reschedule</button>
                                                <?php } ?>

                                                <?php if( ( ($payment_status == "PENDING") || ( @$list['total_billing'] == 0 && $list['booking_validity_attempt'] > 1 )  ) ){  ?>

                                                    <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-danger cancel_btn"><i class="fa fa-close"></i> Cancel</button>

                                                <?php } ?>
                                                <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-warning close_btn"><i class="fa fa-power-off"></i> Close</button>
                                            <?php } ?>

                                            <?php if($status=="CONFIRM" || $status=="RESCHEDULE" || $status=="NEW" ) { ?>
                                                <?php if($list['skip_tracker']=="NO") { ?>
                                                    <button type="button" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-info skip_btn"><i class="fa fa-close"></i> Skip Appointment</button>
                                                <?php }else{ ?>
                                                    <button  class="btn btn-info btn-xs">This appointment is skiped</button>
                                                <?php } ?>
                                            <?php } ?>



                                        <?php } ?>
                                        <?php if($list['flag']=='BOOKED' || $list['flag']=='RESCHEDULE' || $list['flag']=='CLOSED'){ ?>

                                            <?php if(isset($list['uhid']) && !empty($list['uhid']) && $list['status']=='CLOSED'){ ?>
                                                <button type="button"  class="btn btn-xs btn-warning" onclick="var win = window.open(baseurl+'app_admin/get_patient_history/<?php echo base64_encode($login['User']['thinapp_id'])."/".base64_encode($list['uhid']); ?>'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Patient History</button>
                                                <button type="button"  class="btn btn-xs btn-info" onclick="var win = window.open(baseurl+'app_admin/add_hospital_receipt_search?u=<?php echo base64_encode($list['uhid']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Other Billing</button>
                                            <?php } ?>

                                            <button type="button" data-pt ="<?php echo ($list['customer_type']); ?>" data-pi ="<?php echo base64_encode($list['customer_id']); ?>" data-id ="<?php echo base64_encode($list['id']); ?>" class="btn btn-xs btn-info edit_patient_btn"><i class="fa fa-pencil"></i> Edit Patient</button>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </td>


                        </tr>

                    <?php }
                    ?>

                    <?php
                }
                ?>
            </table>

        <?php }
        if($int == 0){ ?>
            <div class="not_available_doctor">
                <h3><font color="#fff">No data found as per selected category</font></h3>
            </div>
            <script>$(".slot_table").remove();</script>
        <?php } ?>
        <div class="total_earning_div">

            <ul >
                <li class="today_collection_lbl"><strong>Today's Collection </strong></li>
                <?php if(!empty($payment_list)){ foreach($payment_list AS $val){ ?>
                    <li class="total_amount_lbl payment_type_<?php echo $val['id']; ?>" data-amount="<?php echo $val['total']; ?>"><?php echo $val['payment_type']; ?>: <strong><i class="fa fa-inr"></i> <?php echo $val['total']; ?></strong></li>
                <?php }} ?>



            </ul>
        </div>

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
                font-size: 18px;
                color: #0009;
                border: 2px dashed;
                border-radius: 50px;
                height: 60px;
                padding: 18px 0px;
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
            .slot_blok a {
                height: 245px;
            }
            .slot_div .slot_blok .is-selected{
                border: 2px solid red !important;
            }
            .button-div {
                width: 100%;
                height: 100%;
                display: none;
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


                setTimeout(function () {
                    $(".not_available_doctor").fadeOut(800);
                },1000);


                var focus_obj;

                if($("#search_cus_modal").length == 0 && !$('.modal').is(':visible')){
                    if($(".upcoming_slot.AVAILABLE").length > 1){
                        focus_obj = $(".upcoming_slot.AVAILABLE").first().addClass("is-selected");
                    }else{
                        focus_obj = $(".add_more_token").addClass("is-selected");
                    }
                }

                $(document).off("click",".slot_blok li a");
                $(document).on("click",".slot_blok li a",function(){

                    clickedIndex = $(".slot_blok li a").index(this);
                    $( ".is-selected" ).removeClass( "is-selected" );
                    $(".slot_blok li a:eq("+clickedIndex+")").addClass( "is-selected" ).focus();


                });

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
                    $(this).find(".button-div").css('display','grid');
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

            });
        </script>
    </div>
<?php } ?>



