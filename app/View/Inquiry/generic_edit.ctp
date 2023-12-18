<?php echo $this->Html->script('ckeditor/ckeditor');?>

<?php echo $this->Form->create('Advertisement',array('url'=>array('controller'=>'Advertisement','action' => 'edit'),'enctype'=>'multipart/form-data','admin'=>true)); ?>
<fieldset>
<?php echo $this->Form->label('Advertisement.title', 'Advertisement Title'); ?>
<div class="clear"></div>
<?php echo $this->Form->error('Advertisement.title'); ?>
<?php echo $this->Form->text('Advertisement.title', array('class'=>"inp-form") ); ?>
</fieldset>
<fieldset>
<div class="clear"></div>

<?php echo $this->Form->label('Advertisement.description', 'Description'); ?>
<div class="clear"></div>
<?php echo $this->Form->error('Advertisement.description'); ?>
<?php echo $this->Form->textarea('Advertisement.description', array('rows' => 10, 'cols' => 70, 'id'=>'content', 'class'=>'ckeditor') ); ?>
</fieldset>
<fieldset>
<div class="clear"></div>

<?php echo $this->Form->label('Advertisement.link', 'Link'); ?>
<div class="clear"></div>
<?php echo $this->Form->error('Advertisement.link'); ?>
<?php echo $this->Form->text('Advertisement.link', array('class'=>"inp-form") ); ?>
</fieldset>
<fieldset>


<?php echo $this->Form->label('Advertisement.image', 'Image'); ?>
<div class="clear"></div>
<?php echo $this->Form->input('Advertisement.image',array('type'=>'file','label'=>false)); ?>
</fieldset>
<div class="clear"></div>

<fieldset>
<div class="clear"></div>

<?php echo $this->Form->label('Advertisement.Set_on', 'Set On'); ?>
<div class="clear"></div>
<?php echo $this->Form->error('Advertisement.Set_on'); ?>
<?php $options = array('home'=>'home','detail' => 'detail'); ?>
<?php echo $this->Form->select('Advertisement.Set_on',$options ,array('escape' => false, 'empty'=>'Select Location')); ?>
</fieldset>
<fieldset>
<div class="clear"></div>

<?php echo $this->Form->label('Advertisement.status', 'Active'); ?>
<?php echo $this->Form->error('Advertisement.status'); ?>
<?php echo $this->Form->checkbox('Advertisement.status', array('checked'=>'checked')); ?>
</fieldset>
<div class="clear"></div>

<br />
<?php echo $this->Form->submit('save',array('class'=>'form-submit')); ?>

<?php echo $this->Form->end(); ?>

