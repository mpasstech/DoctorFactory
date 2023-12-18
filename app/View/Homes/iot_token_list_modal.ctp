<div class="modal fade" id="tokenListModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Token List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if(!empty($final_array)){ ?>
                            <table id="customers" class="table table-bordered">
                                <thead>
                                <tr>

                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Token</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($final_array as $key => $list) { ?>
                                    <tr>

                                        <td><a href="javascript:void(0)" class="copyText" data-copy="<?php echo $list['patient_name']; ?>"><?php echo $list['patient_name']; ?></a> </td>
                                        <td><a href="javascript:void(0)" class="copyText" data-copy="<?php echo substr($list['patient_mobile'], -10); ?>"><?php echo $mobile = substr($list['patient_mobile'], -10); ?></a></td>
                                        <td><?php echo $list['queue_number']; ?></td>
                                        <td><?php echo $list['reason_of_appointment']; ?></td>
                                        <td><?php echo $list['status']; ?></td>
                                        <td>
                                        <?php if(($thin_app_id==134 || $thin_app_id==902 || $thin_app_id==905)){ ?>
                                          
                                                <?php if($list['status']!="Closed" &&  !empty($mobile) && $list['counter_booking_type']!='BILLING'){ ?>
                                                    <?php if($list['call_status']=="COMPLETED"){ ?>
                                                        <label>Call already Sent</label>
                                                    <?php }else if($list['call_status']=="CALLING"){ ?>
                                                        <label>Calling...</label>
                                                    <?php }else{ ?>
                                                        <button data-ai="<?php echo $list['appointment_id']; ?>"  data-d="<?php echo $doctor_id; ?>" data-t="<?php echo $thin_app_id; ?>" style="padding: 2px 10px;font-size: 0.7rem;" type="button" data-m="<?php echo $list['patient_mobile']; ?>" class="btn btn-xs btn-success call_patient">Send Call</button>
                                                    <?php } ?>
                                                <?php } ?>
                                        


                                          
                                        <?php } ?>


                                        <?php if(!empty($list['google_review_link'])){ ?>
                                                     <button type="button" data-di="<?php echo base64_encode($doctor_id); ?>" data-u="<?php echo base64_encode($list['patient_name']); ?>" data-m="<?php echo base64_encode($list['patient_mobile']); ?>" class="send_review_link"><i class="fa fa-star" aria-hidden="true"></i> Send Review Link</button>
                                        <?php } ?>


                                        </td>

                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php }else{ ?>
                            <h4>No Token List</h4>
                        <?php } ?>
                    </div>
                    <div class="col-md-12">
                        <label id="show_copy_text" style="text-align: center;color: green;display: block;float:left;width: 100%;"  ></label>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>

</div>



