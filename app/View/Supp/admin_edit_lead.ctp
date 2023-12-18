<div class="right-box">
    <div class="top-box">
        <div class="tp-line">
            <p>Edit Lead</p>
        </div>
    </div>

    <div class="form-box">
        <table class="tablesorter" border="0" cellpadding="0">
            <tbody>
            <tr valign="top">
                <td>
                    <?php echo $this->Form->create('Leads',array('url'=>array('controller'=>'supp','action' => 'update_lead'),'enctype'=>'multipart/form-data','admin'=>true)); ?>

                    <table class="tablesorter" cellpadding="0">
                        <tbody>
                        <?php
                        if(isset($Leads) && !empty($Leads))
                        {
                            foreach ($Leads as $user)
                            {
                                if ($user['Leads']['user_id'] == 2)
                                    continue;
                                ?>
                                <tr>
                                    <td><?php echo $this->Form->label('Organization Name'); ?></td>
                                    <td>
                                        <?php

                                        //$org_names = $customer_lead['Leads']['org_name'];
                                        //echo $customer_lead;

                                        ?>

                                        <?php echo $this->Form->input('org_name', array('type' => 'text', 'placeholder' => '', 'value' => $user['Leads']['org_name'], 'label' => false, 'class' => 'form-control', 'id' => 'text', 'required' => 'required')); ?>
                                    </td>

                                </tr>
                                <tr>
                                    <td><?php echo $this->Form->label('Mobile'); ?></td>
                                    <td>
                                        <?php

                                        //$org_names = $customer_lead['Leads']['org_name'];
                                        //echo $customer_lead;

                                        ?>

                                        <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'','value'=>$user['Leads']['mobile'],'label'=>false,'class'=>'form-control','id'=>'text','required'=>'required'));?>
                                    </td>

                                </tr>
                                <tr>
                                    <td><?php echo $this->Form->label('Email'); ?></td>
                                    <td>
                                        <?php

                                        //$org_names = $customer_lead['Leads']['org_name'];
                                        //echo $customer_lead;

                                        ?>

                                        <?php echo $this->Form->input('cust_email',array('type'=>'text','placeholder'=>'','value'=>$user['Leads']['cust_email'],'label'=>false,'class'=>'form-control','id'=>'text','required'=>'required'));?>
                                    </td>

                                <tr>
                                    <td><?php echo $this->Form->label('Status'); ?></td>
                                    <td>
                                        <?php

                                        //$org_names = $customer_lead['Leads']['org_name'];
                                        //echo $customer_lead;

                                        ?>

                                        <?php

                                        $value = $user['Leads']['status'];

                                        echo $this->Form->input('status',array('options'=>array('NEW'=>'NEW','INPROCESS'=>'INPROCESS','REJECTED'=>'REJECTED','DONE'=>'DONE'),'label'=>false,'class'=>'form-control input-lg validate[required]','value'=>$value)); ?>
                                    </td>

                                </tr>
                                <tr>
                                    <td><?php echo $this->Form->label('Oraganization Type'); ?></td>
                                    <td>
                                        <?php

                                        //$org_names = $customer_lead['Leads']['org_name'];
                                        //echo $customer_lead;

                                        ?>

                                        <?php

                                        $selected_value = $user['Leads']['org_type'];



                                        echo $this->Form->input('org_type',array('options'=>$organization_type,'label'=>false,'class'=>'form-control input-lg validate[required]','value'=>$selected_value)); ?>
                                    </td>

                                </tr>

                                <tr>
                                    <td>&nbsp;</td>
                                    <td><?php echo $this->Form->submit('Update',array('class'=>'Add-btn2','div'=>false,'onclick'=>'myFunction()','label'=>false,'type'=>'submit','id'=>'edit_button')); ?>
                                        <?php //echo $this->Form->reset('Reset',array('class'=>'alt_btn')); ?>
                                    </td>
                                </tr>
                                </tr><?php }
                        }?>




                        </tbody>
                    </table>
    </div>
</div>

<script>
    $(document).ready(function(){

        $('#genearte_new_app_key').click(function(){

            $.ajax({
                'url':  "<?php echo SITE_URL; ?>usermanager/genearte_new_app_key",
                'data':  {},
                'type' : 'get',
                'dataType' : 'html',
                'success': function(resp){
                    //console.log(resp);
                    if(resp != ''){
                        $('#show_app_key').val(resp);//change the button label to be 'Hide'
                    }else{
                        alert('Something went wrong! Please try again later .');
                    }
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function(){
        $('#edit_button').click(function(){
            var mob=$('#mobile').val();
            if(mob.length <10){
                $('.error-message').show();
                //alert('Please enter valid mobile no.');
                return false;
            }
            //return false;
        });
    });
</script>

<script>
    $(document).ready(function(){
        $('#edit_button').click(function(){
            var pwd=$('#pwd').val();
            if(pwd.length <8){
                $('.error-message').show();
                //alert('Please enter valid password.');
                return false;
            }
            //return false;
        });
    });
</script>

