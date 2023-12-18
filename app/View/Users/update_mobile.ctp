<?php echo $this->Html->script(array('jquery.min.js','jquery.form','intlTelInput')); ?>
<?php echo $this->Html->css(array('popup', 'font-awesome.min','intlTelInput')); ?>
<div class="chanels-popup">
    <div class="chanels"><strong>Update Mobile</strong> <span></span></div>
    <?php if($userdata['User']['is_verified'] == "Y"){ ?>
        <p>If you update your mobile number then you have to verify your account.</p>
        <p id="resp_message_error" style="color: red; text-align: center;"></p>
        <p id="resp_message_sucess" style="color: #3BADDA; text-align: center;"></p>
        <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action' => 'update_mobile'),'id'=>'update_mobile_form','autocomplete'=>'off'));?>
        <?php //echo $this->Form->input('User.current_mobile',array('type'=>'text', 'placeholder'=>'Current Mobile','required'=>false)); ?>
        <?php echo $this->Form->input('User.new_mobile',array('type'=>'text', 'placeholder'=>'Enter New Mobile','required'=>false,'label'=>false)); ?>
        <?php echo $this->Form->submit('Save',array('class'=>'alt_btn update-mob-button','id'=>'up_mobile_number')); ?>
        <?php echo $this->Form->end();?>
        <div id="v_mob_div" class="input text" style="display: none;">
            <label for="UserNewMobile">Verification code</label>
            <input type="text" name="v_code" placeholder="Verification code" id="v_code">
            <input type="button" value="Verify" id="v_btn">
        </div>
    <?php }else{ ?>
        <p style="text-align: center;  ">Your current mobile number is not verified yet. Please verify current mobile number then update with new one <br><br>
        If you not received verification code Then Click <a href="javascript:void(0)" id="resend_v_code">Here</a> to Resend code</p>
        <p id="resp_message_error" style="color: red; text-align: center;"></p>
        <p id="resp_message_sucess" style="color: #3BADDA; text-align: center;"></p>
        <div id="v_mob_div" class="input text" style="">
            <label for="UserNewMobile">Verification code</label>
            <input type="text" name="v_code" placeholder="Verification code" id="v_code">
            <input type="button" value="Verify" id="v_btn">
        </div>
    <?php }?>
</div>
<script>
    $(document).ready(function(){
        $("#update_mobile_form").ajaxForm({
            dataType: "json",
            success: function(resp){
                console.log(resp);
                if(resp.status == 1){
                    $('#resp_message_sucess').html(resp.message);
                    $('#resp_message_error').hide();
                    $('#resp_message_sucess').show();
                    $('#update_mobile_form').hide();
                    $('#v_mob_div').show();
                }else{
                    $('#resp_message_error').html(resp.message);
                    $('#resp_message_sucess').hide();
                    $('#resp_message_error').show();
                }
            }
        });
        
        $('#v_btn').click(function(){
            
            var code = $('#v_code').val();
            if(code != ''){
                $.ajax({
                    'url':  "<?php echo SITE_URL; ?>users/verify_mobile",
                    'data':  {"v_code" : code},
                    'type' : 'post',
                    'dataType' : 'json',
                    'success': function(resp){
                        if(resp.status == 1){
                            $('#resp_message_sucess').html(resp.message);
                            $('#resp_message_error').hide();
                            $('#resp_message_sucess').show();
                            setTimeout(function(){
                                //$.fancybox.close();
                                parent.location.reload();
                            },800);
                        }else{
                            $('#resp_message_error').html(resp.message);
                            $('#resp_message_sucess').hide();
                            $('#resp_message_error').show();
                        }
                    }
                });
            }else{
                $('#resp_message_error').html('Enter Verification code first');
                $('#resp_message_error').show();
            }
        });
        
        
        $('#resend_v_code').live('click',function(){
            
            $.ajax({
                'url':  "<?php echo SITE_URL."users/resend_verification_code"; ?>",
                'type' : 'get',
                'dataType' : 'json',
                'success': function(resp){
                    if(resp.status == 1){
                        $('#resp_message_sucess').html(resp.message);
                        $('#resp_message_error').hide();
                        $('#resp_message_sucess').show();
                    }else{
                        $('#resp_message_error').html(resp.message);
                        $('#resp_message_sucess').hide();
                        $('#resp_message_error').show();
                    }
                }
            });
            
        });
        
        $("#UserNewMobile").intlTelInput({
            autoFormat: true,
            autoHideDialCode: false,
            defaultCountry: "in",
            //nationalMode: true,
            //numberType: "MOBILE",
            //onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            preferredCountries: ['in','us'],
            responsiveDropdown: true,
            //utilsScript: "<?php echo SITE_URL."js/utils.js"; ?>"
            //utilsScript: "js/utils.js"
        });
    });
</script>