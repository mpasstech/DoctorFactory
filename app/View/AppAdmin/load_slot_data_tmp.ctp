<?php
$booked_with_payment_success = "#78f704";
$booked_with_payment_pending = "#fbec3a";
$available_slots = "#FFFFFF";
$expired_time_slots = "#927d7d";
$refunded_appointment = "#04f3ff";
$blocked_slots = "#9765d8";
$emergency = "#ff0000";
?>
<div class="slot_div" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">



<div class="message_span_slot"></div>


<?php
error_reporting( 0 );
$login = $this->Session->read('Auth.User');
$role  = $this->Session->read('Auth.User.USER_ROLE');
$staff_data = $this->AppAdmin->get_doctor_by_id($login['AppointmentStaff']['id'],$login['User']['thinapp_id']);

$appointment_ids = array_column($final_array,"appointment_id","appointment_id");
$appointment_data = $this->AppAdmin->get_tmp_appointment_data($appointment_ids);





?>

    <style>

        .message_div{
            display: block;
            text-align: center;
            width: auto;
        }
        .add_more_rows{
            background: #fff3f3;
        }
    </style>

    <table style="font-size: 10px;" class="slot_blok table table-secondary">

        <tr>
            <th>UHID</th>
            <th>Token Number</th>
            <th>Time</th>
            <th>Patient Name</th>
            <th>Patient Mobile</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Fees</th>
            <th>Payment Status</th>
            <th>Payment Via</th>
            <th>Con. Doctor</th>
            <th>Department</th>
            <th>State</th>
            <th>City</th>
            <th>Hospital UHID</th>
            <th>H UHID</th>

            <th>Action</th>
        </tr>
        <?php if(!empty($final_array)){

            $appointment_array = $new_slots_array =array();

            $queue_number= $time_array = array();
            foreach ($final_array as $key => $list){ ?>
                <tr class="<?php echo ($list['custom_token']=='YES')?'add_more_rows':''; ?>">
                    <td><?php  echo $list['uhid'];  ?></td>
                    <td>
                        <?php
                                echo $token =  $this->AppAdmin->create_queue_number($list);
                                if(is_numeric($token)){
                                    $queue_number[] =$token;
                                    $time_array[] = strtotime(!empty($list['slot_time'])?$list['slot_time']:$list['time']);
                                }

                        ?>
                    </td>
                    <td><?php  echo !empty($list['slot_time'])?$list['slot_time']:$list['time'];  ?></td>
                    <td><input type="text" class="patient_name" value="<?php echo @$list['name']; ?>" > </td>
                    <td><input maxlength="10" type="text" class="patient_mobile" value="<?php echo @$list['mobile']; ?>" > </td>
                    <td><?php  echo isset($appointment_data[$list['appointment_id']])?$appointment_data[$list['appointment_id']]['age']:'';  ?></td>
                    <td><?php  echo isset($appointment_data[$list['appointment_id']])?$appointment_data[$list['appointment_id']]['gender']:'';  ?></td>
                    <td><?php  echo $list['amount'];  ?></td>
                    <td class="payment_status_td"><?php echo $list['payment_status']; ?> </td>
                    <td><?php  echo isset($appointment_data[$list['appointment_id']])?$appointment_data[$list['appointment_id']]['booking_via']:'';  ?></td>
                    <td><?php  echo isset($appointment_data[$list['appointment_id']])?$appointment_data[$list['appointment_id']]['doctor_name']:'';  ?></td>
                    <td><?php  echo isset($appointment_data[$list['appointment_id']])?$appointment_data[$list['appointment_id']]['department']:'';  ?></td>
                    <td><?php  echo isset($appointment_data[$list['appointment_id']])?$appointment_data[$list['appointment_id']]['state']:'';  ?></td>
                    <td><?php  echo isset($appointment_data[$list['appointment_id']])?$appointment_data[$list['appointment_id']]['city']:'';  ?></td>
                    <td class="uhid_td"><?php echo $list['third_party_uhid']; ?></td>
                    <td><input maxlength="10" type="text" class="third_party_uhid" value="<?php echo $list['third_party_uhid']; ?>" > </td>
                    <td>
                    <?php if(empty($list['customer_type']) || $list['customer_type'] =='CUSTOMER'){ ?>
                        <?php if(empty($list['mobile'])){ ?>
                            <button data-token = "" data-queue="<?php  echo $list['queue_number']; ?>" data-slot="<?php  echo !empty($list['slot_time'])?$list['slot_time']:$list['time'];  ?>" class="btn btn-xs btn-success book_appointment">Book Appointment</button>
                        <?php }else if(!empty($list['mobile']) && $list['payment_status'] =="SUCCESS"){ ?>
                            <button data-ai="<?php  echo base64_encode($list['appointment_id']); ?>" data-pi="<?php  echo base64_encode($list['customer_id']); ?>" class="btn btn-xs btn-warning save_uhid_btn">Save</button>
                        <?php }else if(!empty($list['mobile']) && $list['payment_status'] =="PENDING"){ ?>
                            <button data-ai="<?php  echo base64_encode($list['appointment_id']); ?>" data-pi="<?php  echo base64_encode($list['customer_id']); ?>" class="btn btn-xs btn-warning update_payment_uhid">Payment</button>
                        <?php } ?>
                    <?php } ?>
                        <span class="message_div"></span>
                    </td>
                </tr>
            <?php } ?>

            <?php
                $end_timestamp = strtotime('11:59 PM');
                $max_token = max($queue_number);
                for($timestamp  =  max($time_array); $timestamp <=$end_timestamp; ){
                    $timestamp = strtotime("+ ".$duration, ($timestamp));
                    if($timestamp <= $end_timestamp){
                        $slot_time = date('h:i A',$timestamp);
                        $token = ++$max_token;

                        ?>


                        <tr class="add_more_rows">
                            <td></td>
                            <td> <?php echo $token ?></td>
                            <td><?php  echo $slot_time;  ?></td>
                            <td><input type="text" class="patient_name" value="" > </td>
                            <td><input maxlength="10" type="text" class="patient_mobile" value="" > </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="uhid_td"></td>
                            <td><input maxlength="10" type="text" class="third_party_uhid"> </td>
                            <td>
                                <button  data-token = "ADD_MORE_TOKEN" data-queue="<?php  echo $token; ?>" data-slot="<?php  echo $slot_time;  ?>" class="btn btn-xs btn-success book_appointment">Book Appointment</button>
                                <span class="message_div"></span>
                            </td>
                        </tr>



                        <?php


                    }
                }


            ?>




        <?php }else{ ?>
            <div class="not_available_doctor">
                <h3><font color="#fff">Appointment Tokens Are Not Available</font></h3>
            </div>
        <?php } ?>

    </table>



    <script>



        $(document).ready(function () {


            $(document).off('click','.book_appointment');
            $(document).on('click','.book_appointment',function(e){
            var $btn = $(this);
            var slot = $(this).attr('data-slot');
            var queue = $(this).attr('data-queue');
            var dt = $(this).attr('data-token');
            var row = $(this).closest('tr');
            var message_div = $(row).find('.message_div');
            var mobile = $(row).find('.patient_mobile').val();
            var name = $(row).find('.patient_name').val();
            var third_party_uhid = $(row).find('.third_party_uhid').val();
            var service_amount = $("#service_drp option:selected").attr('data-value');

            if(mobile !='' && name !=''){

                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/book_new_appointment_tmp",
                    data:{
                        uhid:third_party_uhid,
                        name:name,
                        mobile:mobile,
                        slot:slot,
                        doctor:$("#doctor_drp").val(),
                        address:$("#address_drp").val(),
                        service:$("#service_drp").val(),
                        amount:service_amount,
                        dt:dt,
                        queue:queue
                    },
                    beforeSend:function(){
                        $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');

                    },
                    success:function(data){
                        $btn.button('reset');
                        data = JSON.parse(data);
                        if(data.status==1){
                            $btn.remove();
                        }else{
                            $(message_div).html(data.message);
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        $(message_div).html("Sorry something went wrong on server.");

                    }
                });
            }else{
              if(mobile==''){
                  $(row).find('.patient_mobile').css('border-color','red');
              }else if(name==''){
                  $(row).find('.patient_name').css('border-color','red');
              }
            }
        });

            $(document).off('click','.save_uhid_btn');
            $(document).on('click','.save_uhid_btn',function(e){
                var $btn = $(this);
                var pi = $(this).attr('data-pi');
                var uhid = $(this).closest('tr').find('.third_party_uhid').val();
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
                            $($btn).hide();
                            $($btn).closest('tr').find('.uhid_td').html(uhid);
                        }
                    },
                    error: function (data) {
                        $btn.button('reset');
                    }
                });

            });


            $(document).off('click','.update_payment_uhid');
            $(document).on('click','.update_payment_uhid',function(e){
                var $btn = $(this);
                var pi = $(this).attr('data-pi');
                var ai = $(this).attr('data-ai');
                var uhid = $(this).closest('tr').find('.third_party_uhid').val();
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/update_payment_uhid",
                    data: {pi: pi,uhid:uhid,ai:ai},
                    beforeSend: function () {
                        $btn.button('loading').html('<i class="fa fa-spinner fa-spin state_spin"></i>');
                    },
                    success: function (data) {
                        $btn.button('reset');
                        var response = JSON.parse(data);
                        if(response.status==1){
                            $($btn).hide();
                            $($btn).closest('tr').find('.payment_status_td').html('SUCCESS');
                            $($btn).closest('tr').find('.uhid_td').html(uhid);
                        }
                    },
                    error: function (data) {
                        $btn.button('reset');
                    }
                });

            });





        });
    </script>
</div>


