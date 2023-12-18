<?php echo $this->Html->script(array('jquery.validationEngine.js','jquery.validationEngine-en.js')); ?>
<?php echo $this->Html->css(array('validationEngine.jquery')); ?>
<div class="mid_content">
    <div class="login-content">
        <div class="warrper">
            <h1>Text anywhere, anytime and on any device!</h1>
            <p>Send and receive all your texts on your notebook, desktop computer or tablet ? just like on your smartphone.</p>
            <?php echo $this->Session->flash("front_error"); ?>
            <?php echo $this->Session->flash("front_success"); ?>
            <div class="login-form">
                <div class="vf-respomnse" id="verify_response"></div>    
                <?php echo $this->Form->create('Verify',array('url'=>array('controller'=>'users','action'=>'verify_identity',$mobile),'id'=>'verify_form','inputDefaults'=>array('required'=>false))); ?>
                    <?php echo $this->Form->input('code',array('placeholder'=>'Verification code','label'=>false,'class'=>'validate[required]')); ?>
                    <?php echo $this->Form->submit('Submit',array('type'=>'button','id'=>'verify_submit','class'=>'login-submit-btn')); ?>
                    <?php //echo $this->Form->submit('Sign up'); ?>
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#verify_form").validationEngine();
        $('#verify_submit').click(function(){
            $("#verify_form").ajaxForm({
                dataType: "json",
                success: function(resp){
                    if(resp.status == 1){
                        window.location.href = '<?php echo SITE_URL."users/login" ?>';
                    }else{
                        $('#verify_response').html(resp.message);
                    }
                }
            }).submit();
        });
    });
</script>