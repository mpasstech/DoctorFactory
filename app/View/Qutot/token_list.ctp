<?php if(!empty($data)){ ?>
    <table id="customers" class="table table-bordered">
        <thead>
        <tr>
            <th>S.no</th>
            <th style="width: 35% !important;">Name <br> Mobile</th>
            <th>Token</th>
            <th>Status</th>
            <th style="width: 20% !important;">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $key => $list) { ?>
            <tr>
                <td><?php echo $key+1; ?></td>
                <td>
                    <?php if($list['patient_type']=='CUSTOMER'){ ?>
                        <a href="javascript:void(0);" data-ai="<?php echo base64_encode($list['appointment_id']); ?>" data-pi="<?php echo base64_encode($list['patient_id']); ?>" class="edit_pat_name pat_namd_td"><?php echo $list['patient_name']; ?></a>
                    <?php }else{ ?>
                        <span class="pat_namd_td"><?php echo $list['patient_name']; ?></span>
                    <?php } ?>
                    <br>
                    <br>
                     <?php echo substr($list['patient_mobile'], -10); ?>
                </td>
                <td style="text-align: center;"><?php

                        if($list['thinapp_id']==CK_BIRLA_APP_ID){
                            echo $token = $this->AppAdmin->get_ck_birla_token($list['appointment_staff_id'],$list['queue_number']);
                            if(($list['status']=='RESCHEDULE' || $list['status']=='NEW' || $list['status']=='CONFIRM') && !empty($list['counter'])){
                                echo " / ".$list['counter'];
                            }else if(in_array($list['status'],array('RESCHEDULE','NEW','CONFIRM')) &&  $list['patient_queue_type']=='DOCTOR_CHECKIN'){
                                echo " / At Doctor";
                            }
                        }else{
                            echo $token = $list['queue_number'];
                        }



                    ?></td>
                <td>

                    <?php if($list['consulting_type']=='VIDEO'){ ?>
                        <span class="status_icon">Video</span>
                    <?php } ?>

                    <?php if($list['consulting_type']=='AUDIO'){ ?>
                        <span class="status_icon" style="color: #ffc107;" >Audio</span>
                    <?php } ?>



                    <span class="status_td">
                        <?php
                        $status = $list['status'];
                        if($list['status']=='RESCHEDULE' || $list['status']=='NEW' || $list['status']=='CONFIRM'){
                            $status = ($list['skip_tracker']=='YES')?'Skipped':'Booked';
                        }
                        echo ucfirst(strtolower($status));
                        ?>
                    </span>
                </td>
                <td class="btn_td">
                    <?php if($list['status']=='RESCHEDULE' || $list['status']=='NEW' || $list['status']=='CONFIRM'){ ?>

                        <?php if($list['consulting_type']=='VIDEO'){
                            $param = base64_encode($list['appointment_id']."##DOCTOR");
                            $video_link = SITE_PATH."homes/video/$param";
                            ?>
                            <a  target="_blank" class="btn btn-success whats_btn" href="https://api.whatsapp.com/send?phone=<?php echo $list['patient_mobile']; ?>" ><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                            <button class="btn btn-success start_video_call" data-url="<?php echo $video_link; ?>" ><i class="fa fa-video-camera" aria-hidden="true"></i></button>
                        <?php } ?>

                        <?php if( $list['consulting_type']=='AUDIO'){ ?>
                            <a  target="_blank" class="btn btn-success whats_btn" href="https://api.whatsapp.com/send?phone=<?php echo $list['patient_mobile']; ?>" ><i class="fa fa-whatsapp" aria-hidden="true"></i></a>
                            <button type="button" data-url="<?php echo "https://mngz.in:3005/?".base64_encode("ai=".$list['appointment_id']."&m=".$list['patient_mobile']."&n=".$list['patient_name']."&l=".$list['logo']); ?>" class="btn btn-xs btn-warning start_audio_btn"><i class="fa fa-phone"></i></button>
                        <?php }
                        ?>


                        <div class="btn-group dropleft">
                            <button type="button" class="drop_action_btn btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                                More
                            </button>
                            <div class="dropdown-menu" >
                                <?php if($list['thinapp_id'] ==CK_BIRLA_APP_ID && $list['patient_queue_type']!='DOCTOR_CHECKIN'){ ?>
                                    <a class="dropdown-item send_to_doctor_btn" data-id="<?php echo base64_encode($list['appointment_id'])?>" href="javascript:void(0);">Send To Doctor</a>
                                <?php } ?>
                                <?php
                                    $display = 'none';
                                    $show_btn = 'none';
                                    if($list['thinapp_id'] ==CK_BIRLA_APP_ID){
                                        if($list['patient_queue_type']=='DOCTOR_CHECKIN'){
                                            $display='block';
                                        }
                                        $show_btn = 'block';
                                    }

                                ?>

                                <a style="display: <?php echo $display; ?>;" class="dropdown-item send_to_billing_btn" data-id="<?php echo base64_encode($list['appointment_id']); ?>" href="javascript:void(0);">Send To Billing Counter</a>
                                <a style="display: <?php echo $show_btn; ?>;" class="dropdown-item assign_counter_btn" data-token="<?php echo $token; ?>" data-id="<?php echo base64_encode($list['appointment_id']); ?>" href="javascript:void(0);">Assign Counter</a>
                                <a style="display:<?php echo ($list['skip_tracker']=='NO')?'block':'none'; ?>" class="dropdown-item skip_btn" data-id="<?php echo base64_encode($list['appointment_id'])?>" href="javascript:void(0);">Skip Token</a>

                                <a data-status="<?php echo $status; ?>" style="display:none;" class="dropdown-item unskip_btn" data-id="<?php echo base64_encode($list['appointment_id'])?>" href="javascript:void(0);">Un-Skip Token</a>

                                <?php if($list['consulting_type']!='OFFLINE'){
                                    $string = $list['folder_id'] ."##FOLDER##".$list['patient_mobile'];
                                    $folder_url = FOLDER_PATH .'record/'. Custom::encodeVariable($string);

                                ?>
                                    <a data-url="<?php echo $folder_url; ?>" class="dropdown-item upload_record" href="javascript:void(0);">Medical Record</a>
                                <?php } ?>






                                <a class="dropdown-item close_btn" data-id="<?php echo base64_encode($list['appointment_id'])?>" href="javascript:void(0);">Close Token</a>
                                <a class="dropdown-item cancel_btn" data-id="<?php echo base64_encode($list['appointment_id'])?>" href="javascript:void(0);">Cancel Token</a>
                            </div>
                        </div>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php }else{ ?>
    <h4>No Token List</h4>
<?php } ?>
