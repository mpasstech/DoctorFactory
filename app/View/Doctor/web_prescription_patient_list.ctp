
<?php
    if(!empty($post_data)){
    foreach ($post_data as $key => $value){

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
    <li class="patient_list_li" data-row="<?php echo base64_encode(json_encode($value)); ?>">
        <a href="javascript:void(0);">
            <div class="media">
                <img class="media-object " src="<?php echo $path; ?>" alt="">
                <div class="media-body">
                    <span class="token">
                        <?php echo ($value['has_token'] =='YES' )?$value['queue_number']:'WI-'.$value['queue_number']; ?>
                    </span>
                    <span class="name">
                        <?php echo $value['patient_name']; ?><br>
                        <span><i class="fa fa-mobile"></i> <?php echo $value['patient_mobile']; ?></span>

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

            <button type="button" data-toggle="confirmation"  data-ai="<?php echo base64_encode($value['appointment_id']); ?>" class="btn btn-xs btn-info pay_btn"><i class="fa fa-rupee"></i> Pay</button>

            <?php } if($value['status'] !="CLOSED" && $value['status'] !="CANCELED"){ ?>

             <button type="button" data-toggle="confirmation"  data-ai="<?php echo base64_encode($value['appointment_id']); ?>" class="btn btn-xs btn-info cancel_btn"><i class="fa fa-times"></i> Cancel</button>

             <?php }  ?>

        </span>


    </li>
<?php }}else{ ?>

            <h6 class="list_not_found"> No patient found</h6>
<?php }  ?>


