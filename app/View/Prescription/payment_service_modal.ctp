<div class="modal fade" style="z-index: 99999;" id="paymentServiceListModal" role="dialog" >
    <div class="modal-dialog modal-md" style="width: 30%; height: 500px;" >
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Service List</h4>
            </div>
            <div class="modal-body ">
                <?php if(!empty($payment_service_list)) { ?>
                    <div class="row" >
                        <input class="form-control" id="myInput" type="text" placeholder="Search..">
                        <br>
                        <ul id="myTable" class="service_modal_div">

                            <?php if(!empty($appointment_data)){
                                $tmp['service_id'] = '0';
                                $tmp['service_name'] = 'OPD';
                                $tmp['price'] = $appointment_data['amount'];
                                array_unshift($payment_service_list, $tmp);
                            } ?>

                            <?php foreach($payment_service_list as $key => $value){ ?>
                                <li>
                                    <label class="service_name" data-name="<?php echo $value['service_name']; ?>"><input class="select_box" type="checkbox" value="<?php echo base64_encode($value['service_id']); ?>"> <?php echo $value['service_name']; ?></label>
                                    <label class="service_amt" data-amount="<?php echo $value['price']; ?>"> <a href="javascript:void(0);"><?php echo $value['price']; ?></a> </label>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="ser_list_action_btn">
                            <a href="javascript:void(0)" class="cancel_payment_service_btn">Cancel</a>
                            <a href="javascript:void(0)" class="service_selection_done_btn">Done</a>
                        </div>
                    </div>
                <?php }else{ ?>
                    <h3>No service list found</h3>
                <?php } ?>
            </div>
        </div>
    </div>

</div>