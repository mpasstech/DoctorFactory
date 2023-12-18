<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
    <label style="display: block;">&nbsp;</label>
    <a class="btn btn-info" href="<?php echo Router::url('/app_admin/add_appointment_tmp',true)?>" >Back</a>
    <a class="btn btn-success" href="<?php echo Router::url('/app_admin/get_all_appointment_list_by_filter/PENDING',true)?>" >Pending</a>
    <a class="btn btn-warning" href="<?php echo Router::url('/app_admin/get_all_appointment_list_by_filter/SUCCESS',true)?>" >Success</a>

</div>
<div class="slot_div">
        <div class="message_span_slot"></div>
        <?php if(!empty($appointment_list)){ ?>
            <table class="table slot_table" style="font-size: 12px;" >
                <tr>
                    <th >UHID</th>
                    <th >Token</th>
                    <th >Title</th>
                    <th >Name</th>
                    <th >Mobile</th>
                    <th >Age</th>
                    <th >Gender</th>

                    <th >Fees</th>
                    <th >Payment Status</th>
                    <th >Payment Via</th>
                    <th >Con. Doctor</th>
                    <th >Department</th>
                    <th >Country</th>
                    <th >State</th>
                    <th >City</th>
                    <th >Hospital UHID</th>
                    <th >H UHID</th>

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
                        <td class="patient_h_uhid">
                            <?php if(!empty($value['patient_id'])){ echo $value['hospital_uhid']; } ?>
                        </td>

                        <td style="padding:8px 2px;">
                            <?php if(!empty($value['patient_id']) && !empty($value['appointment_id'])){ ?>
                                <input style="width: 65%; padding:3px 0px; float:left;" class="hospital_uhid" value="<?php echo $value['hospital_uhid']; ?>">
                                <button style="float: left; padding:3px 0px; margin: 0px;"  data-status="<?php echo $value['payment_status']; ?>" type="button" class="save_uhid_btn btn btn-xs btn-success" data-ai="<?php echo base64_encode($value['appointment_id']); ?>" data-pi="<?php echo base64_encode($value['patient_id']); ?>">Save</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php  } ?>
            </table>
        <?php }else{ ?>
            <h3 style="text-align: center;">No appointment list found</h3>
        <?php } ?>
        <style>
            .header{
                display: none !important;
            }
            .slot_table th{
                padding: 0px 10px !important;
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
                    var ai = $(this).attr('data-ai');
                    var status = $(this).attr('data-status');
                    var url = baseurl + "app_admin/update_payment_uhid";
                    if(status=='SUCCESS'){
                         url = baseurl + "app_admin/update_patient_hospital_uhid";
                    }
                    var uhid = $(this).closest('td').find('.hospital_uhid').val();
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {ai:ai,pi: pi,uhid:uhid},
                        beforeSend: function () {
                            $btn.button('loading').html('<i class="fa fa-spinner fa-spin state_spin"></i>');
                        },
                        success: function (data) {
                            $btn.button('reset');
                            var response = JSON.parse(data);
                            if(response.status==1){
                                $($btn).closest("tr").find('patient_h_uhid').html(uhid);
                                $($btn).hide();
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

