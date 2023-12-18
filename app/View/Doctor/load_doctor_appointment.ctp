
<style>
    .card_main_container .card,
    .card_main_container .card-body{
        padding: 0;
    }



</style>



<div class="col-12 card-deck card_main_container">
    <?php if(!empty($appointmentList)){ foreach($appointmentList as $key => $val) {

        $cancel_card_cls = "";
        if(strtolower($val['status'])=='canceled'){
            $cancel_card_cls = "cancel_card";
        }

        ?>
    <div class="card <?php echo $cancel_card_cls; ?>">
        <div class="card-body">
            <table class="main_table" style="width: 100%;">
                <tr>
                    <td style="padding: 0px 10px;">
                        <label>
                            <i class="fa fa-calendar"></i> <?php echo date('d M y',strtotime($val['appointment_datetime'])); ?>&nbsp;
                            <i class="fa fa-clock-o"></i> <?php echo date('h:i A',strtotime($val['appointment_datetime'])); ?>
                        </label>
                    </td>
                    <td>

                        <label class="app_status_lbl">
                            <i class="fa fa-<?php echo ($val['status']=="CANCELED")?'times':'check'?>"></i> <?php echo ucfirst(strtolower($val['status'])); ?>
                        </label>


                    </td>
                </tr>

                <tr style="border-bottom: 1px solid #b5b5b5;">
                    <td colspan="2" style="padding: 5px 10px;">
                        <label class="patient_name_lbl"><i class="fa fa-user>"></i> <strong><?php echo $val['appointment_patient_name']; ?></strong></label>
                        <span style="width: 100%;display: block;font-size: 0.8rem;" ><i class="fa fa-phone"></i> <?php echo $val['customer_mobile']; ?>


                            <?php if(!empty($val['total_star'])){ ?>
                                <span class="star_lbl" style="float: right;">
                                 <?php for($stars =1; $stars <=5; $stars++){ ?>
                                     <span data-number="<?php echo $stars; ?>" class="star_span fa fa-star <?php echo ($stars <= $val['total_star'])?'checked':''; ?>"></span>
                                 <?php } ?>
                            </span>
                            <?php }else{ ?>
                                <button type="button" data-di="<?php echo base64_encode($doctor_id); ?>" data-u="<?php echo base64_encode($val['appointment_patient_name']); ?>" data-m="<?php echo base64_encode($val['customer_mobile']); ?>" class="send_review_link"><i class="fa fa-star" aria-hidden="true"></i> Send Review Link</button>
                            <?php } ?>


                        </span>
                        <span style="width: 100%;display: block;font-size: 0.8rem;" ><i class="fa fa-stethoscope"></i> <?php echo $val['doctor_name']; ?></span>
                    </td>
                </tr>


                <tr>
                    <td style="width: 75%;">
                        <table style="width: 100%;" class="inner_sub_table">
                            <?php

                            $con_lbl_class = ($val['payment_status']=='SUCCESS')?"success_class":"";
                            $booking_lbl_class = ($val['conv_status']=='PAID')?"success_class":"";

                            ?>

                            <tr>
                                <td><i class="fa fa-ticket"></i> Consultation Type</td>
                                <th>
                                    <?php if($val['consulting_type']=='VIDEO'){ ?>
                                        <label class="video_lbl"><i class="fa fa-video-camera"></i> Video</label>
                                    <?php }else if($val['consulting_type']=='AUDIO'){ ?>
                                        <label class="video_lbl"><i class="fa fa-phone"></i> Phone</label>
                                    <?php }else if($val['consulting_type']=='CHAT'){ ?>
                                        <label class="video_lbl"><i class="fa fa-commenting"></i> Chat</label>
                                    <?php }else{ ?>
                                        <label class="video_lbl"><i class="fa fa-building"></i> Walk-In</label>
                                    <?php } ?>



                                </th>
                            </tr>

                            <?php $rowSpan =1; if(!empty($val['conv_amount'])) { $rowSpan++; ?>
                                <tr>
                                    <td><i class="fa fa-inr"></i> Token Fee <b><?php echo $val['conv_amount']; ?></b> Rs/-</td>
                                    <th class="<?php echo $booking_lbl_class; ?>"><?php echo ucfirst(strtolower($val['conv_status'])); ?></th>
                                </tr>
                            <?php } ?>

                            <?php if($val['show_fees']=='YES'){  $rowSpan++; ?>
                                <tr>
                                    <td><i class="fa fa-inr"></i> Doctor Con. Fee <b><?php echo $val['service_amount']; ?></b> Rs/-</td>
                                    <th class="<?php echo $con_lbl_class; ?>"><?php $p_status = ($val['payment_status']=='SUCCESS')?'Paid':$val['payment_status']; echo ucfirst(strtolower($p_status)); ?></th>
                                </tr>
                            <?php } ?>



                        </table>
                    </td>
                    <td style="border-left: 1px dashed #b5b5b5;">
                        <span style="text-align: center;width: 100%;display: block;">Token</span>
                        <h4 style="text-align: center;width: 100%;">
                            <?php echo ($val["reminder_message"]=="TOKEN_PENDING")?'...':$val['queue_number']; ?>
                        </h4>
                    </td>
                </tr>

                <?php if(!empty($val['reason_of_appointment'])){  ?>
                    <tr>

                        <td colspan="2" style="padding: 0px 10px;"> <b>Reason :-</b> <?php echo $val['reason_of_appointment']; ?></td>
                    </tr>
                <?php } ?>


                <tr class="button_box_tr" >
                    <td  colspan="2">

                        <table style="width: 100%;border-top:1px solid #e3e3e3;margin-top: 15px;">
                            <tr>
                                <td>


                                <?php if($val['folder_id']){  ?>
                                    <li><button type="button" class="app_action_btn patient_medical_record" data-name="<?php echo $val['appointment_patient_name'];?>"  data-fi="<?php echo base64_encode($val['folder_id']);?>" >Records</button></li>
                                <?php } ?>

                                <?php if(in_array($val['status'],array('NEW','CONFIRM','RESCHEDULE'))){  ?>
                                     <li><button type="button" class="app_action_btn token_list_cancel_btn" data-id="<?php echo base64_encode($val['appointment_id']);?>" >Cancel</button></li>
                                <?php } ?>
                                <?php if(in_array($val['status'],array('NEW','CONFIRM','RESCHEDULE'))){  ?>
                                     <li><button  type="button" class="app_action_btn token_list_close_btn" data-id="<?php echo base64_encode($val['appointment_id']);?>" >Close</button></li>
                                <?php } ?>

                                <?php if($val['consulting_type']=='VIDEO'){
                                    if($video_calling_on_web == 'YES') {
                                        $param = base64_encode($val['appointment_id'] . "##DOCTOR");
                                        $video_link = SITE_PATH . "homes/video/$param"; ?>
                                <li>    <a href="<?php echo $video_link; ?>" target="_blank" class="app_action_btn telemedicine"><i class="fa fa-video-camera" aria-hidden="true"></i> Call </a></li>
                                    <?php }?>
                                <?php } ?>

                                <?php if($val['consulting_type']=='AUDIO'){ ?>
                                        <li><a href="tel:<?php echo $val['customer_mobile']; ?>" target="_blank" class="app_action_btn audio_btn"><i class="fa fa-phone" aria-hidden="true"></i> Call</a></li>
                                <?php } ?>

                                <?php if($val['consulting_type']=='VIDEO' || $val['consulting_type']=='AUDIO'){ ?>
                                        <li><a href="https://api.whatsapp.com/send?phone=<?php echo $val['customer_mobile']; ?>" target="_blank" class="app_action_btn video_btn"><i class="fa fa-whatsapp" aria-hidden="true"></i> Call</a></li>
                                <?php } ?>







                                </td>

                            </tr>
                        </table>








                    </td>
                </tr>


            </table>
        </div>
    </div>
    <?php }}else{
        if($offset==0){
            $message = "You have not booked token yet.";
        }else{ ?>
            <?php $message="No more appointment";
        }
    ?>
    <h2 class="doc_not_available"><?php echo $message; ?></h2>
    <script>$(".load_more").hide();</script>
    <?php } ?>
</div>