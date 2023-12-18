<!--/RIGHT-BOX-->
<div class="right-box">
      <!--/HEADING-->
<div class="top-box">
    <div class="tp-line">
      <p><i class="fa fa-plus-circle"></i> Add New Thin App</p>
    </div>
</div>
      
<div class="form-box">
    <table class="tablesorter" border="0" cellpadding="0">
    <tbody>
        <tr valign="top">
        <td>
            <?php echo $this->Form->create('Thinapp',array('url'=>array('controller'=>'thinapps','action' => 'add'),'enctype'=>'multipart/form-data', 'novalidate','admin'=>true)); ?>
            
        <table class="tablesorter" cellpadding="0">
            <tbody>
                
                <tr>
                  <td><?php echo $this->Form->label('Thinapp.app_id', 'App ID'); ?></td>
                  <td> 
                      <?php //echo $this->Form->error('Thinapp.app_id'); ?>
                      <?php echo $this->Form->input('Thinapp.app_id',array('type'=>'text','placeholder'=>'App ID','label'=>false,'class'=>'form-control','id'=>'text'));?>
                  </td>
                </tr>
                      
                <tr>
                  <td><?php echo $this->Form->label('Thinapp.name', 'App Name'); ?></td>
                  <td> 
                      <?php // echo $this->Form->error('Thinapp.name'); ?>
                      <?php echo $this->Form->input('Thinapp.name',array('type'=>'text','placeholder'=>'App Name','label'=>false,'class'=>'form-control','id'=>''));?>
                  </td>
                </tr>
                
                <tr>
                  <td><?php echo $this->Form->label('Thinapp.user_id', 'Select User' ); ?></td>
		  <td>
		      <?php //echo $this->Form->error('Thinapp.user_id'); ?>
                      <?php echo $this->Form->input('Thinapp.user_id',array('type'=>'text','placeholder'=>'User ID','label'=>false,'class'=>'form-control','id'=>''));?>
    
                  </td>
                </tr>
                
                <tr>
                  <td><?php echo $this->Form->label('Thinapp.contactus_text', 'Contact Us Text' ); ?></td>
		  <td>
		      <?php //echo $this->Form->error('Thinapp.user_id'); ?>
                      <?php echo $this->Form->input('Thinapp.contactus_text',array('type'=>'textarea','placeholder'=>'Contact Us text','label'=>false,'class'=>'form-control','id'=>''));?>
    
                  </td>
                </tr>
                
                
                <tr>
                  <td><?php echo $this->Form->label('Thinapp.email', 'Enter Email' ); ?></td>
		  <td>
		      <?php //echo $this->Form->error('Thinapp.user_id'); ?>
                      <?php echo $this->Form->input('Thinapp.email',array('type'=>'text','placeholder'=>'Enter Email ','label'=>false,'class'=>'form-control','id'=>''));?>
    
                  </td>
                </tr>
                
                <tr>
                  <td><?php echo $this->Form->label('Thinapp.phone', 'Enter Phone' ); ?></td>
		  <td>
		      <?php //echo $this->Form->error('Thinapp.user_id'); ?>
                      <?php echo $this->Form->input('Thinapp.phone',array('type'=>'text','placeholder'=>'Phone No.','label'=>false,'class'=>'form-control','id'=>'pwd'));?>
    
                  </td>
                </tr>
                
                
                 <tr>
                  <td><?php echo $this->Form->label('Thinapp.address', 'Enter Address' ); ?></td>
		     <td> <?php echo $this->Form->input('Thinapp.address',array('type'=>'text','placeholder'=>'Address', 'label'=>false,'class'=>'form-control','id'=>'pwd'));?>
    
                  </td>
                </tr>
				 
				 <tr>
                  <td><?php echo $this->Form->label('Thinapp.start_date', 'Start Date' ); ?></td>
		     <td> <?php echo $this->Form->input('Thinapp.start_date',array('type'=>'text','placeholder'=>'Start Date', 'label'=>false,'class'=>'form-control default_datetimepicker','id'=>'pwd'));?>
    
                  </td>
                </tr>
				 
				 <tr>
                  <td><?php echo $this->Form->label('Thinapp.end_date', 'End Date' ); ?></td>
		     <td> <?php echo $this->Form->input('Thinapp.end_date',array('type'=>'text','placeholder'=>'End Date', 'label'=>false,'class'=>'form-control default_datetimepicker','id'=>'pwd'));?>
    
                  </td>
                </tr>
                        
                     
                <tr>
                    <td>&nbsp;</td>
                    <td><?php echo $this->Form->submit('Submit',array('class'=>'Add-btn2','div'=>false,'label'=>false,'type'=>'submit')); ?>
                        <?php //echo $this->Form->reset('Reset',array('class'=>'alt_btn')); ?>
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

<script>
    $(document).ready(function(){
	       
        $('#genearte_new_app_key').click(function(){
	    
            $.ajax({
                'url':  "<?php echo SITE_URL; ?>usermanager/genearte_new_app_key",
                'data':  {},
                'type' : 'get',
                'dataType' : 'html',
                'success': function(resp){
                    //console.log(resp);
                    if(resp != ''){
                        $('#show_app_key').val(resp);//change the button label to be 'Hide'
                    }else{
                        alert('Something went wrong! Please try again later .');
                    }
                }
            });
        });
    });
	
	 $('.default_datetimepicker').datetimepicker({
	//formatTime:'H:i',
	formatDate:'d-m-Y',
	//defaultDate:'8.12.1986', // it's my birthday
	defaultDate:'+03.01.1970', // it's my birthday
	timepicker:false,	
	//defaultTime:'10:00',
	//timepickerScrollbar:false
});
</script>
