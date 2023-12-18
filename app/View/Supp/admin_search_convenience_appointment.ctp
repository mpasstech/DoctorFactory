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
                    <th>Acion</th>

                </tr>
                </thead>
                <tbody>

                <?php
                    $total_token =  array();

                    if(!empty($list)){ $total = 0;foreach($list AS $key => $data){ ?>
                    <tr class="<?php echo ($data['payment_status']=='SUCCESS' && in_array($data['status'],array('CONFIRM','RESCHEDULE')))?'green_row':''; ?>">
                        <td><b><?php echo $key+1; ?>.</b></td>

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
                        <td>
                            <?php

                                    $medical_product_order_id = base64_encode($data['medical_product_order_id']);
                                    $reference_id = base64_encode($data['reference_id']);
                                    $booking_convenience_order_detail_id = base64_encode($data['booking_convenience_order_detail_id']);
                                    $appointment_id = base64_encode($data['appointment_id']);
                                    $thin_app_id = base64_encode($data['thinapp_id']);

                                    $payment_mode = ($data['payment_mode']);
                                    $total_amount = ($data['total_amount']);

                                    $booking_convenience_fee = ($data['booking_convenience_fee']);
                                    $doctor_online_consulting_fee = ($data['doctor_online_consulting_fee']);

                             if($data['fee_status']=='ACTIVE'){
                            ?>

                            <button data-ti="<?php echo $thin_app_id;?>" data-ai="<?php echo $appointment_id; ?>" data-tm="<?php echo $total;?>" data-pm="<?php echo $payment_mode;?>" data-docf="<?php echo $doctor_online_consulting_fee; ?>" data-bcf="<?php echo $booking_convenience_fee;?>" data-bcodi="<?php echo $booking_convenience_order_detail_id;?>" data-ri="<?php echo $reference_id;?>" data-mpoi="<?php echo $medical_product_order_id;?>" class="refund_btn btn btn-warning"  type="button">Refund</button> </td>
                            <?php } ?>

                    </tr>
                <?php }} ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="11" align="right"></td>
                    <td ><b>Total</b></td>
                    <td><b><?php echo !empty($total)?$total:'0'; ?></b></td>
                    <td></td>
                </tr>
                </tfoot>
            </table>


        </div>
    </div>
    <?php $reportTitle = "Report"; ?>
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

        $(document).off('click','.refund_btn');
        $(document).on('click','.refund_btn',function(e){
            var del_obj = $(this);
            var btn = $(this);


            var pm =  $(this).attr("data-pm");
            var docf =  $(this).attr("data-docf");
            var bcf =  $(this).attr("data-bcf");
            var bcodi =  $(this).attr("data-bcodi");
            var ri =  $(this).attr("data-ri");
            var mpoi =  $(this).attr("data-mpoi");
            var ai =  $(this).attr("data-ai");
            var ti =  $(this).attr("data-ti");
            var total = parseFloat(bcf)+parseFloat(docf);


            var jc = $.confirm({
                title: 'Refund Cashfree Amount',
                content: '' +
                    '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<p>Total Paid Amount : <b>'+(total)+'Rs/-</b> <br> Doctor Consulting Fee: <b>'+docf+'Rs/-</b> <br>Booking Con Fee : <b>'+bcf+'Rs/-</b> </p>' +
                    '<label>Enter Amount</label>' +
                    '<input type="text" value="'+bcf+'"  placeholder="Please enter refund amount" class="amount form-control" required />' +
                    '<label>Enter Message</label>' +
                    '<input type="text" value="Refund by doctor"  placeholder="Enter message" class="message form-control" maxlength="30"  />' +
                    '</div>' +
                    '</form>',
                buttons: {
                    formSubmit: {
                        text: 'Refund',
                        btnClass: 'btn-blue',
                        action: function (e) {

                            var amount = this.$content.find('.amount').val();
                            var message = this.$content.find('.message').val();
                            if(!amount){
                                $.alert('Please enter refund amount');
                                return false;
                            }else{
                                $.ajax({
                                    url:baseurl+"/admin/supp/refund_online_amount",
                                    type:'POST',
                                    data:{
                                        tm :  amount,
                                        pm :  pm,
                                        docf :  docf,
                                        bcf :  bcf,
                                        bcodi :  bcodi,
                                        ri :  ri,
                                        mpoi :  mpoi,
                                        ai:ai,
                                        ti:ti,
                                        msg:message
                                    },
                                    beforeSend:function(){
                                        jc.buttons.formSubmit.setText("Wait..");
                                    },
                                    success:function(res){
                                        var response = JSON.parse(res);
                                        jc.buttons.formSubmit.setText("Refund");
                                        if(response.status=='OK'){
                                            $.alert(response.message);
                                            jc.close();
                                            btn.remove();
                                            return false;

                                        }else{
                                            $.alert(response.message);
                                        }
                                    },
                                    error:function () {

                                        $.alert(response.message);
                                    }
                                });
                            }
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {
                    // bind to events
                    var jc = this;
                    this.$content.find('form').on('submit', function (e) {
                        // if the user submits the form by pressing enter in the field.
                        e.preventDefault();
                        jc.$$formSubmit.trigger('click'); // reference the button and click it
                    });
                }
            });
        });

    })
</script>

