<?php
$login = $this->Session->read('Auth.User');
?>
<div class="modal-dialog modal-sm">
    <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">App Functionality</h4>
        </div>
        <div class="modal-body">

            <div class="table-responsive">
                <table class="table table-responsive ">

                    <thead>
                    <tr >
                        <th>#</th>
                        <th>Functionality Name</th>
                        <th>Staus</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php
                    $isSmartClinicOn = false;
                    $thinappID = $thinappData['Thinapp']['id'];
                    if(isset($features) && !empty($features))
                    {
                        $counter=1;
                        foreach($features as $key => $val)
                        {

                            ?>
                            <tr>
                                <td><?php echo $key+1; ?></td>
                                <td><?php echo $val['label']; ?></td>

                                <?php
                                $class="";
                                if($val['label'] == 'Smart Clinic' && $val['status']=="ACTIVE")
                                {
                                    $isSmartClinicOn = true;

                                }
                                if($val['label'] == 'Smart Clinic'){
                                    $class = "smartClinic";
                                }

                                ?>


                                <td class="<?php echo $class; ?> td_links parent_div <?php echo ($val['app_function_type_id'] ==22 || $val['app_function_type_id'] ==40)?'com_'.$val['app_function_type_id']:''; ?>">
                                    <div style="display:flex; ">
                                        <?php
                                        if($val['status']=="ACTIVE"){ ?>
                                        <button  data-fun-id="<?php echo base64_encode($val['enabled_fun_id']);  ?>" data-app-id="<?php echo base64_encode($val['thin_app_id']);?>" fun-type-id="<?php echo base64_encode($val['app_function_type_id']);  ?>" type="button" class="action_btn btn btn-success btn-xs"  ><?php echo "ACTIVE"; ?></button>
                                        <?php }else{ ?>
                                        <button data-fun-id="<?php echo base64_encode($val['enabled_fun_id']);  ?>" data-app-id="<?php echo base64_encode($val['thin_app_id']);?>" fun-type-id="<?php echo base64_encode($val['app_function_type_id']);  ?>" type="button" class="action_btn btn btn-warning btn-xs"><?php echo "INACTIVE"; ?></button>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                        <?php

                        }
                    } ?>

                    </tbody>
                </table>
            </div>






        </div>
    </div>
</div>
<style>
    .small_text{
        font-size: 10px;
    }
</style>

<script>
    $(document).off('blur','#booking_convenience_fee, #booking_doctor_share_percentage, #booking_payment_getway_fee_percentage, #booking_convenience_fee_restrict_ivr, #booking_convenience_fee_terms_condition');
    $(document).on('blur','#booking_convenience_fee, #booking_doctor_share_percentage, #booking_payment_getway_fee_percentage, #booking_convenience_fee_restrict_ivr, #booking_convenience_fee_terms_condition',function(){
        var field = $(this).attr('id');
        var value = $(this).val();
        var thinappID = '<?php echo $thinappID; ?>';

        if(field == 'booking_convenience_fee_terms_condition')
        {
            value = value.replace(/\n\r?/g, '\n');
        }



        var thisButton = $(this);
        $.ajax({
            url: baseurl+'admin/supp/update_booking_convenience_fee',
            data:{field:field,value:value,thinappID:thinappID},
            type:'POST',
            beforeSend:function(){
                $(thisButton).attr('disabled','disabled');
            },
            success: function(result){
                $(thisButton).removeAttr('disabled');

            }
        });


    });
</script>

