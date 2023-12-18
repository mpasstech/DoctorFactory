<?php
$counter =0;
if(!empty($appointment_slot['slot_list'])){
    foreach($appointment_slot['slot_list'] as $key => $val) {

        $label = '';
        if($val['status'] == 'AVAILABLE'){
            $label = 'available';
        }else if($val['status'] == 'BOOKED'){
            $label = 'booked';
        }else if($val['status'] == 'BLOCKED'){
            $label = 'not-available';
        }

        if($val['custom_slot']=='NO'){ $counter++;
        ?>


        <div data-type="<?php echo base64_encode($label); ?>" class="appointment-slot <?php echo $label; ?>">
            <span class="appointment-token"><?php echo $val['queue_number']; ?></span>
            <span class="appointment-time"><?php echo $val['slot']; ?></span>
        </div>
    <?php }}}
    if($counter==0){
        $statuData = $this->AppAdmin->getDayOpenStatus($doctor_id,$booking_date,$address_id);
        $status = @$statuData['status'];
        $is_date_blocked = @$statuData['is_date_blocked'];
    ?>
    <h2 class="doc_not_available slot_error_message"></h2>
    <script>
        $(function () {
            var status = "<?php echo $status; ?>";
            var is_date_blocked = "<?php echo $is_date_blocked; ?>";
            var category = "<?php echo $category_name; ?>";
            var label = (category=='HOSPITAL' || category=='HOSPITALS' || category=='DOCTOR_OPD' || category=='DOCTOR' )?'Doctor':'Counter';

            var address_lbl =$(".selected_address .address_label").attr("data-show");
            var info ='<i style="font-size:2.5rem;" class="fa fa-info-circle" aria-hidden="true"></i>';
            if(status=='OPEN'){
                var message = "<br>Please book tokens between <br><b>"+address_lbl+"</b>";
                if(is_date_blocked=='YES'){

                    if(label=='Doctor'){
                        message = "<br>"+label+" is not available<br>";
                    }else{
                        message = "<br>Counter is closed<br>";
                    }
                }
                $(".slot_error_message").html(info+message);
            }else{
                $(".slot_error_message").html(info+"<br>"+label+"'s token slots will open shortly.");
            }
        })

    </script>
<?php  } ?>
