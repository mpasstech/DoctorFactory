
<?php echo $this->Session->flash('done'); ?>
<?php echo $this->Session->flash('error');?>
<!--/RIGHT-BOX-->
    <div class="right-box">
      <!--/HEADING-->
      <div class="top-box">
        <div class="tp-line">
          <p>Site Setting</p>
        </div>
      </div>
      <div class="form-box">
        <table class="tablesorter" border="0" cellpadding="0">
          <tbody>
            <tr valign="top">
              <td>
	            	<?php echo $this->Form->create('Setting',array('url' => array('controller'=>'Settings', 'action' => 'admin_index'))); ?>
                         <table class="tablesorter" cellpadding="0">
                         <tbody>
			<?php	
				    if(isset($setting) && !empty($setting)){
						foreach($setting as $key=>$setting_data){
							    if($setting_data['Setting']['type'] != 'api') {
			?>
                        <tr>
                            <td><?php echo $this->Form->label($setting_data['Setting']['name']); ?></td>
			
                        <td><?php echo $this->Form->text('Setting.'.$setting_data['Setting']['id'], array('class'=>"form-control", 'value'=>$setting_data['Setting']['value']) ); ?></td>
                         </tr>
			<?php
							    }
							    else
							    { ?>
							 <tr>
                                                             <td><?php echo $this->Form->label($setting_data['Setting']['name']); ?></td>
                                                             <td>
							    <?php $default_api = array('ROUTE' => 'ROUTE', 'GUPSHUP' => 'GUPSHUP', 'PLIVO' => 'PLIVO') ?>
							    <?php echo $this->Form->select('Setting.'.$setting_data['Setting']['id'], $default_api,array('empty'=>'Select API','class'=>'form-control', 'value'=>$setting_data['Setting']['value'])); ?>
                                                             </td>     
                                                         </tr>
						      <?php }
						}
				    } 
			?>
                     <tr>
                         <td>&nbsp;</td>
			   
                         <td><?php echo $this->Form->submit('Update',array('class'=>'Add-btn2')); ?></td>
                     </tr>     
                         </tbody>
                        </table>
			<?php echo $this->Form->end(); ?>
	      </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>