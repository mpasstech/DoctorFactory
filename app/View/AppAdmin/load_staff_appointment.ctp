<?php $login1 = $this->Session->read('Auth.User');

$staff_data = @$this->AppAdmin->get_doctor_by_id($login1['AppointmentStaff']['id'],$login1['User']['thinapp_id']);
?>
<?php  echo $this->Html->css(array('font-awesome.min.css')); ?>
<div class="clear"></div>
<div class="form-group">

    <style>
        .appointment_count_p strong{ color: #0a6d9ae0; font-size: 14px;margin-right: 5px; }
        .appointment_count_p {
            text-align: right;
            float: left;
            width: 75%;
        }
        .appointment_count_p label {
            font-size: 13px;

        }

        .heading_div_box{
            border-top: 2px solid #067a82b8;
            float: left;
            width: 100%;
            margin-bottom: 20px;
            background: #c4f0f361;
            padding: 2px 5px;
        }
        .doctor_name_label{
            float: left;
            width: 25%;
            text-align: left;
            font-size: 15px;
        }
        .opd_div{
            float: left;
            width: 100%;
            text-align: center;
            background: #74e1dc75;
            height: 25px;
            padding: 0px 0px;
        }
        .opd_div label{
            padding: 0px 15px;
        }
    </style>

    <div class="heading_div_box">
        <p class="doctor_name_label"><label>Doctor Name: &nbsp;</label><?php echo $staffName; ?></p>
        <p class="group inner list-group-item-text appointment_count_p">


            <?php
            $new = $confirmed = $closed= $canceled = $rescheduled= $total = $total_appointment = 0;
            $address_id = @$this->request->query['ad'];

            ?>



            <label>New : <strong class="new"> </strong></label>
            <label>Confirmed : <strong class="confirmed"> </strong></label>
            <label>Closed : <strong class="closed"> </strong></label>
            <label>Canceled : <strong class="canceled"> </strong></label>
            <label>Rescheduled : <strong class="rescheduled"> </strong></label>
            <label>Total : <strong class="total"></strong></label>
        </p>
        <p>
              <span class="opd_div">
            <?php if(!empty($payment_type)){ foreach($payment_type  as $key => $data){ ?>
                <label><?php echo $data['payment_type']; ?> : <strong><i class="fa fa-inr" aria-hidden="true"></i> <?php echo (int)$data['total']; ?></strong></label>
            <?php }} ?>
           </span>

        </p>

    </div>

</div>



<div class="form-group">
    <div class="col-sm-12">

        <div class="table table-responsive">
            <?php if(!empty($appointment_list)){ ?>
            <table id="dataTable" class="table table-striped" style="font-size: 13px;">
                <thead>
                <tr>
                    <th width="1%">#</th>
                    <th width="10%">Patient Name</th>
                    <th width="8%">Patient Mobile</th>
                    <th width="15%">Patient Address</th>
                    <th width="15%">Doctor Address</th>
                    <th width="5%">Referred By</th>
                    <th width="2%">Token</th>
                    <th width="15%">Date</th>
                    <th width="5%">Total Amount</th>
                    <th width="5%">Status</th>
                    <th width="5%">Payment Status</th>
                    <th width="5%">Payment Mode</th>
                    <th width="5%">More</th>
                    <th  style="text-align: right;"><input type="checkbox" name="select_all" class="select_all" >
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($appointment_list as $key => $list){


                    ?>
                    <tr>

                        <?php

                            if(!empty($list['AppointmentCustomer']['first_name'])){
                                $name = trim($list['AppointmentCustomer']['title'].' '.$list['AppointmentCustomer']['first_name']);
                                $mobile = $list['AppointmentCustomer']['mobile'];
                            }else{
                                $name = trim($list['Children']['title'].' '.$list['Children']['child_name']);
                                $mobile = $list['Children']['mobile'];

                            }

                        ?>
                        <td><?php echo $key+1; ?></td>

                        <td><?php echo $name ; ?></td>
                        <td><?php echo $mobile; ?></td>
                        <td><?php echo !empty($list['AppointmentCustomer']['address'])?ucfirst($list['AppointmentCustomer']['address']):$list['Children']['address']; ?></td>
                        <td><?php echo ucfirst($list['AppointmentAddress']['address']); ?></td>
                        <td><?php echo $list['AppointmentCustomerStaffService']['referred_by']; echo !empty($list['AppointmentCustomerStaffService']['referred_by_mobile'])?"(".$list['AppointmentCustomerStaffService']['referred_by_mobile'].")":""; ?></td>
                        <td><?php echo $this->AppAdmin->create_queue_number($list['AppointmentCustomerStaffService']);
                        ?></td>
                        <td><?php echo $app_date = date('d-m-Y',strtotime($list['AppointmentCustomerStaffService']['appointment_datetime']))." ".$list['AppointmentCustomerStaffService']['slot_time']   ; ?></td>
                        <td class="amt">

                            <?php


                            if($list['AppointmentCustomerStaffService']['status'] == 'REFUND')
                            {
                                $opdCharge = $opv = $list['AppointmentCustomerStaffService']['amount'] - $list['AppointmentCustomerStaffService']['refund_amount'];
                            }
                            else
                            {
                                $opdCharge = $opv = $list['AppointmentCustomerStaffService']['amount'];
                            }




                                $ipd = $list['AppointmentCustomerStaffService']['ipd_procedure_amount'];
                                $vaccination = $list['AppointmentCustomerStaffService']['vaccination_amount'];
                                $other = $list['AppointmentCustomerStaffService']['other_amount'];
                                $total = $opv+$ipd+$vaccination+$other;

                                $msg = "<div class='amount_div_tooltip'><p><label>OPD :</label><i class='fa fa-inr' aria-hidden='true'></i>$opv /- </p><p><label>IPD :</label><i class='fa fa-inr' aria-hidden='true'></i>$ipd /-</p><p><label>VACCINATION :</label><i class='fa fa-inr' aria-hidden='true'></i>$vaccination /-</p><p><label>OTHER :</label><i class='fa fa-inr' aria-hidden='true'></i>$other /-</p></div>";

                            ?>



                            <a href="javascript:void(0);" rel='tooltip' class="show_amt_tooltip" data-original-title="<?php echo $msg; ?>" >
                            <?php

                                $total_calculate_amount =  !empty($list['MedicalProductOrder']['total_amount'])?$list['MedicalProductOrder']['total_amount']:0;
                                echo sprintf('%0.2f', $total_calculate_amount);


                            ?>
                            </a>


                        </td>
                        <td class="status_td"><?php


                            $status = strtolower($list['AppointmentCustomerStaffService']['status']);
                            if($status =="new"){
                                $new++;
                            }else if($status=='confirm'){
                                $confirmed++;
                            }else if($status=='canceled'){
                                $canceled++;
                            }else if($status=='reschedule'){
                                $rescheduled++;
                            }else if($status=='closed'){
                                $closed++;
                            }
                            $total_appointment++;
                            echo ucfirst($status);
                            if($status == 'refund')
                            {
                                echo "<br>(".sprintf('%0.2f', $list['AppointmentCustomerStaffService']['refund_amount']).")";
                            }



                            ?></td>
                        <td class="payment_status"><?php echo ucfirst(strtolower($list['AppointmentCustomerStaffService']['payment_status'])); ?></td>


                        <?php
                        $text = $text2 ='';

                        if($list['AppointmentCustomerStaffService']['booking_payment_type'] == 'ONLINE')
                        {
                            $text = "ONLINE";
                        }
                        else
                        {
                            $text = isset($list['HospitalPaymentType']['name'])?$list['HospitalPaymentType']['name']:"CASH";
                        }


                        $text2 = isset($list['MedicalProductOrder']['payment_description'])?$list['MedicalProductOrder']['payment_description']:"";


                        ?>


                        <td class="payment_type_name" title="<?php echo $text2; ?>"><?php echo $text; ?></td>

                        <td class="app_btn_td">

                            <div class="dropdown">
                                <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown">Option
                                    <span class="caret"></span></button>
                                     <ul class="dropdown-menu pull-right option_btn_panel">
                                    <?php
                                    if($list['AppointmentCustomerStaffService']['delete_status'] != 'FOLLOW_UP'){
                                        $status =$list['AppointmentCustomerStaffService']['status'];
                                        $payment_status = $list['AppointmentCustomerStaffService']['payment_status'];
                                        $date = date('Y-m-d', strtotime($list['AppointmentCustomerStaffService']['appointment_datetime']))." ".$list['AppointmentCustomerStaffService']['slot_time'];
                                        $appointment_datetime  = (date('Y-m-d H:i', strtotime($date)));
                                        $current_date =  date('Y-m-d H:i');
                                        ?>
                                        <?php  if( $payment_status == "PENDING" && $status != 'CANCELED') { ?>
                                            <button type="button" ipd="<?php echo $ipd; ?>" vaccination="<?php echo $vaccination; ?>" other="<?php echo $other; ?>" opd="<?php echo $opdCharge; ?>"  data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-info pay_btn"><i class="fa fa-money"></i> Payment</button>

                                        <?php }else{ ?>

                                            <?php  if( $payment_status == "SUCCESS" && $status != 'CANCELED'){ ?>
                                                <button type="button" class="btn btn-xs btn-info" onclick="var win = window.open(baseurl+'app_admin/print_invoice/<?php echo base64_encode($list['AppointmentCustomerStaffService']['medical_product_order_id']); ?>', '_blank'); if (win) { win.focus(); } else { alert('Please allow popups for this website'); }" ><i class="fa fa-money"></i> Receipt</button>

                                                <?php if($list[0]['due_amount_settled'] =="NO" && (($login1['USER_ROLE'] == 'RECEPTIONIST' && @$staff_data['edit_appointment_payment'] =="YES" ) || $login1['USER_ROLE'] == 'ADMIN' || $login1['USER_ROLE'] =='DOCTOR') ){ ?>
                                                    <button type="button" class="btn btn-primary btn-xs editBtn" data-id="<?php echo base64_encode($list['AppointmentCustomerStaffService']['medical_product_order_id']); ?>"><i class="fa fa-inr" aria-hidden="true"></i> Edit</button>
                                                <?php } ?>


                                            <?php } ?>



                                        <?php } ?>

                                        <?php if($status=="CONFIRM" || $status=="RESCHEDULE" || $status=="NEW" ) { ?>


                                            <?php if(($login1['USER_ROLE'] == 'RECEPTIONIST' && @$staff_data['allow_reschedule_appointment'] =="YES" ) || $login1['USER_ROLE'] == 'ADMIN' || $login1['USER_ROLE'] =='DOCTOR'){ ?>
                                                <button type="button" data-st_id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['appointment_staff_id']); ?>" data-address ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['appointment_address_id']); ?>" data-service ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['appointment_service_id']); ?>" data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-success btn_reschedule"><i class="fa fa-refresh"></i> Reschedule</button>
                                            <?php } ?>



                                            <?php if( $payment_status == "PENDING" ){ ?>

                                                <button type="button" data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-danger cancel_btn"><i class="fa fa-close"></i> Cancel</button>

                                            <?php } ?>

                                            <button type="button" data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-warning close_btn"><i class="fa fa-power-off"></i> Close</button>

                                        <?php } ?>



                                    <?php }
                                    else
                                    { ?>FOLLOW UP<?php } ?>
                                </ul>
                            </div>




                        </td>

                        <td align="right">
                            <?php if($list['AppointmentCustomerStaffService']['payment_status'] == 'SUCCESS'){ ?>
                                <input type="checkbox" name="select_id" value="<?php echo $list['AppointmentCustomerStaffService']['id']; ?>" class="select_id" id="select_id" >
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php } else{ ?>
                <div class="no_data">
                    <h2>There is no appointment. </h2>
                </div>
            <?php } ?>


            <?php if(count($appointment_list) > 0){ ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="form-group">
                    <?php if($login1['USER_ROLE'] !='RECEPTIONIST' && $login1['USER_ROLE'] !='DOCTOR' && $login1['USER_ROLE'] !='STAFF'){ ?>
                        <div class="col-md-4 col-md-offset-10">

                            <button  style="margin-top: 15px;" class="btn btn-info" id="deleteSelected"><i class="fa fa-trash"></i> Delete Appointment</button>

                        </div>
                    <?php } ?>

                    <!--div class="col-md-4">
                        <?php echo $this->Form->submit('Follow Up',array('class'=>'Btn-typ5','id'=>'followUp')); ?>
                    </div-->
                </div>
            </div>
            <?php } ?>
        </div>

    </div>

</div>
<div class="clear"></div>

<div class="clear"></div>
<style>





    .bookmark{width: 8%;border: 1px solid silver; padding: 5px; float:right;border-radius: 10px; display: inline-flex;}
    .green_mark{
        background-color: green !important;
        border-color: green !important;
    }

    .amount_div_tooltip label{ color: yellow; margin: 2px 5px; text-align: left}
    .amount_div_tooltip p{ color: deepskyblue; margin: 8px 5px; border-bottom: 1px solid greenyellow;}

    table.dataTable tbody th, table.dataTable tbody td{
        padding: 3px 3px !important;
    }
    table.dataTable thead th, table.dataTable thead td{
        padding: 4px !important;
    }
</style>
<script>
    $(document).ready(function () {

        function setCookie(cname, cvalue, exdays) {
            var d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            var expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        var table = $('#dataTable').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 10, 25, 50, 100, 150, 200, -1 ],
                [ '10 rows', '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            'aoColumnDefs': [{
                'bSortable': false,
                'aTargets': [-1] /* 1st one, start by the right */
            }],
            "language": {
                "emptyTable": "No Data Found Related To Search"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10]
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7,8,9,10]
                    }

                }
            ]
        });
        var user_id  = "<?php echo $login1['User']['id']; ?>";

        var cache_name = 'doctor_appointment_table_order'+user_id;
        if(getCookie(cache_name)){
            var data = getCookie(cache_name).split(',');
            table.order( [data[0],data[1]] ).draw();
        }
        $('#dataTable').on( 'order.dt', function () {
            var order = table.order();
            setCookie(cache_name,order);
        } );






        $('.new').html("<?php echo $new; ?>");
        $('.canceled').html("<?php echo $canceled; ?>");
        $('.closed').html("<?php echo $closed; ?>");
        $('.rescheduled').html("<?php echo $rescheduled; ?>");
        $('.confirmed').html("<?php echo $confirmed; ?>");
        $('.total').html("<?php echo $total_appointment; ?>");



        $(".show_amt_tooltip").tooltip({html:true});
    });




    $(document).off('click','.select_all');
    $(document).on('click','.select_all',function(e){
        if($(this).prop('checked') == true)
        {
            $(".select_id").prop('checked',true);
        }
        else
        {
            $(".select_id").prop('checked',false);
        }
    });


    $(document).off('click','.editBtn');
    $(document).on('click','.editBtn',function(e){


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



</script>

