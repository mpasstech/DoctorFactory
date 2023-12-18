<!--/RIGHT-BOX-->
<div class="right-box">
      <!--/HEADING-->
<div class="top-box">
    <div class="tp-line">
      <p>Send Reply Email</p>
    </div>
</div>
      
<div class="form-box">
    <table class="tablesorter" border="0" cellpadding="0">
    <tbody>
        <tr valign="top">
        <td>
           <?php echo $this->Html->script('ckeditor/ckeditor');?>
           <?php echo $this->Form->create('Inquiry',array('url' => array('controller'=>'Inquiry', 'action' => 'sendIndividual','admin'=>true))); ?>
           <?php echo $this->Form->input('Inquiry.inquiry_id',array('type'=>'hidden','default'=>$reciver_id));?>
            
        <table class="tablesorter" cellpadding="0">
            <tbody>
                
             <fieldset>
            
            <?php echo $this->Form->label('Inquiry.message', 'Reply Message'); ?>
            <div class="clear"></div>
            <?php echo $this->Form->error('Inquiry.message'); ?>
            <?php echo $this->Form->textarea('Inquiry.message', array('rows' => 10, 'cols' => 70, 'id'=>'content', 'class'=>'ckeditor') ); ?>
            </fieldset>
            
            	 <tr>
                    <td>&nbsp;</td>
                    <td><?php echo $this->Form->submit('Submit',array('class'=>'Add-btn2','div'=>false,'label'=>false,'type'=>'submit')); ?>
                        <?php echo $this->Form->reset('Reset',array('class'=>'Add-btn2')); ?>
                    </td>        
                </tr>   
                
		</tbody>			
	    </table>				
	   <?php echo $this->Form->end();?>				
        </td>
        </tr>
    </tbody>
    </table>
</div>
</div>    



