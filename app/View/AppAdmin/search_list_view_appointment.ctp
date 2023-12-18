<style>
    .green_row td{
        background: #94C405 !important;
        color: #fff;
    }
    .footer_bar{
        float: left;
        margin: 0;
        padding: 0;
        width: 100%;
    }
    .footer_bar li{
        list-style: none;
        width: 20%;
        float: left;
        margin: 0px 5px;
        padding: 0px;

    }

    .footer_bar .label_box{
        width: 60%;
        float: left;
        background: #577bff;
        color: #fff;
        padding: 5px;
        font-size: 1.5rem;
        text-align: center;
    }

    .footer_bar .label_box label{
        width: 100%;
        font-size: 1.3rem;
        text-align: center;
    }

    .footer_bar .amount_box{
        width: 40%;
        float: left;
        background: #27ab0f;
        color: #fff;
        padding: 5px;
        font-size: 2.5rem;
        text-align: center;
        height: 100%;
    }


    .footer_bar .label_box label{
        width: 100%;
    }
</style>

<div class="form-group row">
    <div class="col-sm-12">
        <div class="table table-responsive">
            <table id="data_table" class="table table-striped table-bordered" style="width:100%">
                <thead>
                <tr>
                    <th>#</th>
                    <th>UHID</th>
                    <th>Patient Name</th>
                    <th>Patient Mobile</th>
                    <th>Doctor Name</th>
                    <th>Date & Time</th>
                    <th>Token</th>
                    <th>Payment Status</th>
                    <th>Appointment Status</th>
                    <th>Booked Via</th>
                    <th>Booking Type</th>
                    <th>Convince Fee</th>
                    <th>Consulting Type</th>
                    <th>Amount</th>
                </tr>
                </thead>
                <tbody>

                <?php
                    $total_token =  array();

                    if(!empty($list)){ $total = 0;foreach($list AS $key => $data){ ?>
                    <tr class="<?php echo ($data['payment_status']=='SUCCESS' && in_array($data['status'],array('CONFIRM','RESCHEDULE')))?'green_row':''; ?>">
                        <td><b><?php echo $key+1; ?>.</b></td>
                        <td><?php echo $data['uhid']; ?></td>
                        <td><?php echo $data['appointment_patient_name']; ?></td>
                        <td><?php echo $data['patient_mobile']; ?></td>
                        <td><?php echo $data['doctor_name']; ?></td>
                        <td>
                            <?php echo date('d-m-Y',strtotime($data['appointment_datetime'])); ?><br>
                            <?php echo date('h:i A',strtotime($data['appointment_datetime'])); ?>
                        </td>

                        <td><?php echo $this->AppAdmin->create_queue_number($data); ?></td>
                        <td><?php echo ucfirst(strtolower($data['payment_status'])); ?></td>
                        <td><?php echo ucfirst(strtolower($data['status'])); ?></td>
                        <td><?php echo $data['appointment_booked_from']; ?></td>
                        <td><?php echo ucfirst(strtolower($data['booking_payment_type'])); ?></td>
                        <td><?php
                           if(!empty($data['booking_convenience_fee'])){
                                    $data_key = $data['consulting_type']."@".($data['booking_convenience_fee'])."@".$data['doctor_online_consulting_fee'];
                                    $total_token[$data_key] = @$total_token[$data_key]+1;
                            }
                            echo $data['booking_convenience_fee'];
                        ?></td>
                        <td><?php echo ($data['consulting_type']=='OFFLINE')?'Hospital Visit':ucfirst(strtolower($data['consulting_type'])); ?></td>
                        <td><?php echo $data['amount']; $total +=$data['amount'];  ?></td>
                    </tr>
                <?php }} ?>


                </tbody>
                <tfoot>
                <tr>
                    <td colspan="12" align="right"></td>
                    <td ><b>Total</b></td>
                    <td><b><?php echo !empty($total)?$total:'0'; ?></b></td>
                </tr>
                </tfoot>
            </table>

            <?php if(!empty($total_token)){ ?>
                <h3 style="text-align: center;">Booking Convenience Report Table</h3>
                <table style="width: 100%;" class="table-bordered">
                    <tr>
                        <th>S.No</th>
                        <th>Consulting Type</th>

                        <th>Token Fee</th>
                        <th>Consulting Fee</th>
                        <th>Total Token</th>
                        <th>Total Token Amount</th>
                        <th>Total Consulting Fee</th>
                    </tr>
                    <?php  $counter=1;
                    $tot_token_amt= $tot_con_fee= $token_count =0;

                    ksort($total_token);
                    foreach ($total_token as $key =>$total_token){
                        $type = explode("@",$key);
                        $consult_type = $type[0];
                        $consult_type =($consult_type=='OFFLINE')?'Hospital Visit':ucfirst(strtolower($consult_type));
                        $token_amount = $type[1];
                        $consulting_fee = @$type[2];
                        ?>
                        <tr>
                            <td><label><?php echo $counter++; ?></label></td>
                            <td><label><?php echo $consult_type; ?></label></td>

                            <td><label><?php echo $token_amount; ?></label></td>
                            <td><label><?php echo $consulting_fee; ?></label></td>
                            <td><label><?php echo $total_token; $token_count += $total_token; ?></label></td>
                            <td><label><?php echo $token_amount * $total_token; $tot_token_amt +=($token_amount * $total_token); ?></label></td>
                            <td><label><?php echo $consulting_fee * $total_token; $tot_con_fee += ($consulting_fee * $total_token); ?></label></td>

                        </tr>

                    <?php } ?>
                    <tr>
                        <th colspan="4" style="text-align: right;">Total</th>
                        <th><?php echo $token_count; ?></th>
                        <th><?php echo $tot_token_amt. " Rs/-"; ?></th>
                        <th><?php echo $tot_con_fee. " Rs/-"; ?></th>

                    </tr>
                </table>
            <?php } ?>
            <br>
            
             <?php if(!empty($sms_data)){ $total=0; ?>
                <h3 style="text-align: center;">SMS SENT REPORT</h3>
                <table style="width: 100%;" class="table-bordered">
                    <tr>
                        <th>S.No</th>
                        <th>OTP SMS</th>
                        <th>Booking SMS</th>
                        <th>Cancel SMS</th>
                        <th>Vaccination SMS</th>
                        <th>Total SMS</th>

                    </tr>
                    <tr>
                        <td><label>1</label></td>
                        <td><label><?php echo $sms_data['total_otp_message']; $total += $sms_data['total_otp_message']; ?></label></td>
                        <td><label><?php echo $sms_data['total_booking_sms']; $total += $sms_data['total_booking_sms']; ?></label></td>
                        <td><label><?php echo $sms_data['total_cancel_sms']; $total += $sms_data['total_cancel_sms']; ?></label></td>
                        <td><label><?php echo $sms_data['total_vac_message']; $total += $sms_data['total_vac_message']; ?></label></td>
                        <td><label><?php echo $total; ?></label></td>



                    </tr>

                </table>
            <?php } ?>
            
            
        </div>
    </div>
</div>
<div class="clear"></div>
<script>
    $(function () {
        var columns = [];
        $("#data_table thead:first tr th").each(function (index,value) {
            if(!$(this).hasClass("action_btn")){
                columns.push(index);
            }
        });
        $('#data_table').DataTable({
            dom: 'Blfrtip',
            lengthMenu: [
                [ 25, 50, 100, 150, 200, -1 ],
                [ '25 rows', '50 rows', '100 rows', '150 rows', '200 rows', 'Show all' ]
            ],
            "aaSorting": [],
            "language": {
                "emptyTable": "No Data Found"
            },
            buttons: [
                {
                    extend: 'copy',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'csv',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'excel',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'pdf',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                },
                {
                    extend: 'print',
                    header: true,
                    footer: true,
                    messageTop: '<?php echo $reportTitle; ?>',
                    title: '',
                    exportOptions: {
                        columns: columns
                    }

                }
            ]
        });
    })
</script>

