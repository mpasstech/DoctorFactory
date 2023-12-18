    <!--/RIGHT-BOX-->
<div class="right-box">
      <!--/HEADING-->
<div class="top-box">
  <div class="tp-line">
    <p>Edit News</p>
  </div>
</div>
      
<div class="form-box">
    <table class="tablesorter" border="0" cellpadding="0">
      <tbody>
            <tr valign="top">
              <td>
                    <?php echo $this->Html->script('ckeditor/ckeditor');?>
                    <?php echo $this->Form->create('EmailTemplate',array('url' => array('controller'=>'Emailtemplates', 'action' => 'admin_edit'))); ?>
                    <?php echo $this->Form->hidden('id'); ?>
                  
                <table class="tablesorter" cellpadding="0">
                  <tbody>
                      
                    <tr>
                        <td><?php echo $this->Form->label('EmailTemplate.senderEmail', 'From Email'); ?></td>
                        <td>
                            <?php echo $this->Form->error('EmailTemplate.senderEmail'); ?>
                            <?php echo $this->Form->input('EmailTemplate.senderEmail',array('class'=>'form-control','label'=>false,'type'=>'text', 'placeholder'=>'','required'=>false)); ?>
                        </td>
                    </tr>
                      
                    <tr>
                        <td><?php echo $this->Form->label('EmailTemplate.subject', 'Subject'); ?></td>
                        <td>
                            <?php echo $this->Form->error('EmailTemplate.subject'); ?>
                            <?php echo $this->Form->input('EmailTemplate.subject',array('class'=>'form-control','label'=>false,'type'=>'text', 'placeholder'=>'','required'=>false)); ?>
                        </td>
                    </tr>
                    
                    <tr>
			<td><?php echo $this->Form->label('EmailTemplate.format', 'Email Format'); ?></td>
                        <td>
                            <?php echo $this->Form->error('EmailTemplate.subject'); ?>
                            <?php echo $this->Form->textarea('EmailTemplate.subject',array('rows' => 10, 'cols' => 70, 'id'=>'msg', 'class'=>'ckeditor')); ?>
                        </td>	    
		    </tr>
                    
                      <tr>
		         <td>&nbsp;</td>
			 <td>	    
			    <?php echo $this->Form->submit('Submit',array('class'=>'Add-btn2')); ?>	    
			</td>	 
																    
                        </tr>
                  </table>
                  <?php echo $this->Form->end(); ?>
        </td>
        </tr>
     </tbody>
   </table>
</div>
</div>

