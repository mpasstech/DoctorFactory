<?php echo $this->Html->script(array('jquery.min.js','jquery.form')); ?>
<div class="contact_form">
<h4>We would love to hear from you </h4>
<?php
echo $this->Form->create('Inquiry',array('url'=>array('controller'=>'Inquiry','action'=>'index'),'inputDefaults'=>array('label'=>false,'required'=>false),'id'=>'update_mobile_form','enctype'=>'multipart/form-data'));
echo $this->Form->input('name',array('placeholder' => 'Name'));
echo $this->Form->input('email',array('placeholder' => 'Email Address'));
echo $this->Form->input('phone',array('type' => 'text','placeholder' => 'Phone No'));
echo $this->Form->input('message',array('placeholder' => 'Your message'));
echo $this->Form->submit('Add',array('class'=>'alt_btn','type'=>'button','id'=>'up_mobile_number'));
echo $this->Form->end('Add');
?>
</div>
<script>
    $(document).ready(function(){
        $('#up_mobile_number').click(function(){
            $("#update_mobile_form").ajaxForm({
                dataType: "json",
                success: function(resp){
                    console.log(resp);
                    //alert(resp);
                    if(resp){
                        $('#resp_message_sucess').html(resp.message);
                        $('#resp_message_error').hide();
                        $('#resp_message_sucess').show();
                        $('#update_mobile_form').hide();
                    }else{
                        $('#resp_message_error').html(resp.message);
                        $('#resp_message_sucess').hide();
                        $('#resp_message_error').show();
                    }
                }
            }).submit();
        });
    });
</script>