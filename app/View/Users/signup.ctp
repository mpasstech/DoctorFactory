<?php echo $this->Session->flash("front_error"); ?>
<?php echo $this->Session->flash("front_success"); ?>

<?php echo $this->Form->create('User',array('controller'=>'users','action'=>'signup','inputDefaults'=>array('required'=>false))); ?>

    <?php echo $this->Form->input('email',array('type'=>'text','label'=>false,'placeholder'=>'Email Address')); ?>
    <?php echo $this->Form->input('username',array('type'=>'text','label'=>false,'placeholder'=>'Username')); ?>
    <?php echo $this->Form->input('password',array('type'=>'text','label'=>false,'placeholder'=>'Password')); ?>
    <?php echo $this->Form->input('confirm_password',array('type'=>'password','label'=>false,'placeholder'=>'Confirm Password')); ?>
    <?php echo $this->Form->input('mobile',array('type'=>'text','label'=>false,'placeholder'=>'Mobile')); ?>
    <?php //echo $this->Form->file('image',array('label'=>'Image')); ?>
    
<?php echo $this->Form->end('Signin'); ?>