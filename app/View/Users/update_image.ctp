<div class="mid_content">
    <div class="login-content">
        <div class="warrper">
            <h1>Update Profile Settings</h1>
            <p>Send and receive all your texts on your notebook, desktop computer or tablet ? just like on your smartphone.</p>
            <?php echo $this->Session->flash("front_error"); ?>
            <?php echo $this->Session->flash("front_success"); ?>
            <div class="login-form">
            <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action'=>'update_image'),'id'=>'login_form','enctype'=>'multipart/form-data','inputDefaults'=>array('required'=>false))); ?>
                <?php echo $this->Form->hidden('User.id'); ?>
                <?php echo $this->Form->input('image',array('placeholder'=>'Image','label'=>false)); ?>
                <?php echo $this->Form->submit('Submit',array('class'=>'login-submit-btn')); ?>
            <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<script>
    $(document).ready(function(){
        
    });
</script>