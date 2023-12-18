<?php echo $this->Html->css(array('popup', 'font-awesome.min')); ?>
<div class="chanels-popup">
    <?php echo $this->Session->flash("front_error"); ?>
    <?php echo $this->Session->flash("front_success"); ?>
    <div class="chanels"><strong>Change Password</strong> <span></span></div>
    <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action' => 'change_password'),'autocomplete'=>'off'));?>
    <?php echo $this->Form->input('User.currentpassword',array('type'=>'password', 'placeholder'=>'Current Password','label'=>'Current Password','required'=>false)); ?>
    <?php echo $this->Form->input('User.password',array('type'=>'password', 'placeholder'=>'New Password','required'=>false)); ?>
    <?php echo $this->Form->input('User.confirm_passwd',array('type'=>'password', 'placeholder'=>'Confirm Password','required'=>false)); ?>
    <?php echo $this->Form->submit('Save',array('class'=>'alt_btn')); ?>
    <?php echo $this->Form->end();?>
</div>