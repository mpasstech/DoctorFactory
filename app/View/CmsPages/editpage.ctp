<?php echo $this->Html->css(array('popup', 'font-awesome.min')); ?>
<div class="chanels-popup">
    <?php echo $this->Session->flash("front_error"); ?>
    <?php echo $this->Session->flash("front_success"); ?>
    <div class="chanels"><strong>Edit Channel</strong> <span></span></div>
    <?php
        echo $this->Form->create('Channel',array('url'=>array('controller'=>'channels','action'=>'edit'),'inputDefaults'=>array('required'=>false),'enctype'=>'multipart/form-data'));
        echo $this->Form->hidden('id');
        echo $this->Form->input('channel_name',array('type'=>'text'));
        echo $this->Form->input('channel_desc');
        echo $this->Form->input('response_url',array('type'=>'text'));
        echo $this->Form->input('image',array('type'=>'file','label'=>'Channel Image'));
        echo $this->Form->label('allow_sms','Allow SMS');
        if($this->request->data['Channel']['allow_sms'] == 'Y'){
            echo $this->Form->checkbox('allow_sms',array('checked'=>'checked','style'=>'margin-top: 20px;'));
        }else{
            echo $this->Form->checkbox('allow_sms',array('style'=>'margin-top: 20px;'));
        }
        echo "<div class='clear'></div>";
	echo $this->Form->label('is_default','Make it Default');
        if($this->request->data['Channel']['is_default'] == 'Y'){
            echo $this->Form->checkbox('is_default',array('checked'=>'checked','disabled'=>'disabled','style'=>'margin-top: 20px;'));
            //echo "Default Channel";
        }else{
            echo $this->Form->checkbox('is_default',array('style'=>'margin-top: 20px;'));
        }
        echo "<div class='clear'></div>";
        echo $this->Form->label('is_public','Make it Public');
        if($this->request->data['Channel']['is_public'] == 'Y'){
            echo $this->Form->checkbox('is_public',array('checked'=>'checked','style'=>'margin-top: 20px;'));    
        }else{
            echo $this->Form->checkbox('is_public',array('style'=>'margin-top: 20px;'));
        }
        //echo "<div class='clear'></div>";
        echo "<div class='pb-channel-div'>Any anonymous user can subscribe to a publice channel.</div>";
        echo $this->Form->end('Add');
    ?>
</div>

<style>
.clear{ clear:both;}
</style>