<?php
    if(!empty($post_data['data']['list'])){
    foreach ($post_data['data']['list'] as $key => $value){
        if($action_for=="APPOINTMENT"){
            if(empty($value['profile_photo'])){
                $path =Router::url('/images/channel-icon.png',true);
            }else{
                $path =$value['profile_photo'];

            }

            $icon_array['NEW']['status'] ="Booked";
            $icon_array['NEW']['icon'] ="fa fa-check";

            $icon_array['CONFIRM']['status'] ="Booked";
            $icon_array['CONFIRM']['icon'] ="fa fa-check";

            $icon_array['RESCHEDULE']['status'] ="Booked";
            $icon_array['RESCHEDULE']['icon'] ="fa fa-check";


            $icon_array['CANCELED']['status'] ="Canceled";
            $icon_array['CANCELED']['icon'] ="fa fa-ban";

            $icon_array['CLOSED']['status'] ="Closed";
            $icon_array['CLOSED']['icon'] ="fa fa-power-off";

            ?>
            <li class="patient_list_li" >
                <a data-ai="<?php echo base64_encode($value['appointment_id']); ?>" data-pt="<?php echo $value['user_type']; ?>" data-pi="<?php echo base64_encode($value['patient_id']); ?>" href="javascript:void(0);">
                    <div class="media">
                        <div class="media-object" style="text-align: center;font-size: 20px; color: #fff; background: <?php echo "#".$this->AppAdmin->randomColorPicker(); ?>;"><?php echo strtoupper($value['patient_name'][0]); ?></div>

                        <div class="media-body">
                    <span class="token">
                        <?php echo ($value['has_token'] =='YES' )?$value['queue_number']:'WI-'.$value['queue_number']; ?>
                    </span>
                            <span class="name">
                        <?php echo $value['patient_name']; ?><br>
                        <span style="font-size: 11px; !important;"><i class="fa fa-phone"></i> <?php echo $value['mobile']; ?></span>

                    </span>
                            <span class="message">

                        <span class="payment_status"><i class="fa fa-rupee"></i> <?php echo ucfirst(strtolower($value['payment_status'])); ?></span>
                        <span class="time center_span appointment_time"><i class="fa fa-clock-o"></i> <?php echo date('h:i A',strtotime($value['appointment_datetime'])); ?></span>
                                <?php  if(!empty($icon_array)){ ?>
                                    <span data-icon = "<?php echo base64_encode(json_encode($icon_array)); ?>" class="appointment_status"><i class="<?php echo $icon_array[$value['status']]['icon']; ?>"></i> <?php echo $icon_array[$value['status']]['status']; ?></span>
                                <?php } ?>
                    </span>
                        </div>
                    </div>

                </a>
                <span class="action_div" >
                    <?php if(in_array($value['status'],array("NEW","CONFIRM","RESCHEDULE"))){ ?>
                        <button type="button" data-toggle="confirmation"  data-ai="<?php echo base64_encode($value['appointment_id']); ?>" class="btn btn-xs btn-info close_btn"><i class="fa fa-power-off"></i> Close</button>
                    <?php } if($value['payment_status'] =="PENDING" && in_array($value['status'],array("NEW","CONFIRM","RESCHEDULE"))){ ?>
                        <button type="button" data-toggle="confirmation"  data-pt="<?php echo base64_encode($value['user_type']); ?>" data-pi="<?php echo base64_encode($value['patient_id']); ?>" data-ai="<?php echo base64_encode($value['appointment_id']); ?>" class="btn btn-xs btn-info pay_btn"><i class="fa fa-rupee"></i> Pay</button>
                    <?php } if($value['status'] !="CLOSED" && $value['status'] !="CANCELED"){ ?>
                        <button type="button" data-toggle="confirmation"  data-ai="<?php echo base64_encode($value['appointment_id']); ?>" class="btn btn-xs btn-info cancel_btn"><i class="fa fa-times"></i> Cancel</button>
                    <?php }  ?>
                </span>
            </li>

        <?php }else if($action_for=="MEDICAL_RECORD"){ ?>

        <?php }else if($action_for=="CHILDREN" || $action_for =="CUSTOMER"){
            $path ="";
            if(!empty($value['profile_photo'])){
                $path =Router::url('/images/channel-icon.png',true);
            }

            ?>
            <li class="patient_list_li" >
                <a data-ai='0' data-pt="<?php echo $value['user_type']; ?>" data-pi="<?php echo base64_encode($value['patient_id']); ?>" href="javascript:void(0);">
                    <div class="media">
                        <div class="media-object" style="text-align: center;font-size: 20px; color: #fff; background: <?php echo "#".$this->AppAdmin->randomColorPicker(); ?>;"><?php echo strtoupper($value['patient_name'][0]); ?></div>


                        <div class="media-body">

                        <span class="name">
                        <?php echo $value['patient_name']; ?><br>
                        <span><i class="fa fa-phone"></i> <?php echo $value['mobile']; ?></span>

                        </span>

                        </div>
                    </div>

                </a>

            </li>
        <?php } ?>

<?php }}else{ ?>


            <h6 class="list_not_found"> <?php echo $post_data['message']; ?></h6>

<?php }  ?>


