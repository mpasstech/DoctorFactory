
<article class="module width_full">
          <header><h3><?php echo $this->request->data['User']['first_name']." " .$this->request->data['User']['last_name']." "; ?>'s View</h3></header>
             <div id="content-table-inner">
			<div id="table-content" style="padding-left: 20px;">
				    <h3>Personal Details : </h3>    

				    <table class="fulluser">
				   
				    <?php echo $this->Form->create('UserDetail', array('url' => SITE_URL.'admin/usermanager/edit_user')); ?>
				    <?php 
				  
				     echo $this->Form->hidden('UserDetail.id',array('value'=>$this->request->data['UserDetail']['id']));
				     echo $this->Form->hidden('User.id',array('value'=>$this->request->data['User']['id'])); ?>
						<tr>
							    <td><strong>First Name: </strong></td>
							    <td><?php echo $this->Form->input('User.first_name',array('label'=>false,'class'=>'inputbox')); ?> </td>
							     <td><strong>Last Name:</strong> </td>
							    <td><?php echo $this->Form->input('User.last_name',array('label'=>false,'class'=>'inputbox')); ?> </td>
							    
						</tr>
						
						<tr>
							    <td><strong>UserName: </strong></td>
							    <td><?php echo $this->Form->input('User.username',array('label'=>false,'readonly','class'=>'inputbox')); ?> </td>
							      <td><strong>Nationality:</strong> </td>
							    <td><?php echo $this->Form->input('nationality',array('label'=>false,'class'=>'inputbox')); ?> </td>
						</tr>
					
						<tr>
							    <td><strong>Email :</strong> </td>
							    <td><?php echo $this->Form->input('User.email',array('label'=>false,'readonly','class'=>'inputbox')); ?> </td>
							   <td><strong>Language:</strong> </td>
							   <?php //pr($this->request->data['UserDetail']['language']);
							    ?>
							    <td>
								      <?php //echo $this->Lang->languageSelect('UserDetail.language',array('default' => 'aa', 'class' => 'selectbox','label'=>false)); ?>
								      <?php echo $this->Form->error('UserDetail.language');?>
								      <?php echo $this->Form->select('UserDetail.language',$this->Lang->languages,array('class' => 'selectbox','label'=>false,'multiple'=>'multiple','default'=>$this->request->data['UserDetail']['language']));?>
							    </td>
							    
						</tr>
						
						<tr>
							    <td><strong>Display Name:</strong> </td>
							    <td><?php echo $this->Form->input('UserDetail.display_name',array('label'=>false,'class'=>'inputbox')); ?> </td>
							    <td><strong>Date of Birth: </strong></td>
							    <td> <?php echo $this->Form->input('UserDetail.dob',array('label'=>false,'dateFormat'=>'DMY', 'minYear'=>date('Y')-50, 'maxYear'=>date('Y')-10+1, 'label'=>false));?> </td>
						</tr>
						
						<tr>
							    <td><strong>Gender: </strong></td>
							    <td>
							    <?php
								      $options=array('Male'=>'Male','Female'=>'Female');
								      $attributes=array('legend'=>false);
							    ?>
							    <?php echo $this->Form->radio('UserDetail.sex',$options,$attributes);?></td>
							    <td><strong>Address: </strong></td>
							    <td><?php echo $this->Form->input('address',array('label'=>false,'class'=>'inputbox')); ?> </td>
						</tr>
						
						<tr>
							    <td><strong>City:</strong> </td>
							    <td><?php echo $this->Form->input('city',array('label'=>false,'class'=>'inputbox')); ?> </td>
							     <td><strong>Post Code:</strong> </td>
							    <td><?php echo $this->Form->input('post_code',array('label'=>false,'class'=>'inputbox')); ?> </td>
						</tr>
						
						<tr>
							    <td><strong>Phone Number:</strong> </td>
							    <td><?php echo $this->Form->input('phone_number',array('label'=>false,'class'=>'inputbox')); ?> </td>
							    <td><strong>Country:</strong> </td>
							    <td>
								      <?php // echo $this->Lang->countrySelect('UserDetail.country', array( 'default' => 'us','class' => 'selectbox','label'=>false)); ?>
								      <?php echo $this->Form->error('UserDetail.countries');?>
								      <?php $country = array("1"=>'Australia',"2"=>'New Zealand');?>
								      <?php echo $this->Form->select('UserDetail.country',$country,array('class' => 'selectbox','label'=>false));?>
							    </td>
						</tr>
						
						<tr>
							        <td><strong>Skills :</strong> </td>
							   <td><?php echo $this->Form->textarea('UserDetail.skills',array('class'=>'text-area','type'=>'text','rows'=>'3','cols'=>'26','label'=>false,'class'=>'inputbox'));?> </td>
							    <td><strong>Experience: </strong></td>
							    <td> <?php echo $this->Form->textarea('UserDetail.experience',array('class'=>'text-area','type'=>'text','rows'=>'3','cols'=>'26','label'=>false));?> </td>
						</tr>
						
						
						<tr><td colspan="3">
						
					<?php echo $this->Form->submit('Submit',array('class'=>'alt_btn')); ?></td></tr>	
						
				    </table>
				   				
				
			</div>
			<div class="clear"></div>
		 </div>
            
</article>