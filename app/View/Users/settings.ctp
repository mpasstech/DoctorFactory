<?php echo $this->Html->script(array('jquery.uploadPreview.min'));?>
<?php echo $this->Session->flash("front_error"); ?>
<?php echo $this->Session->flash("front_success"); ?>

<?php 
////$user = new User();
///id = $this->Auth->User('id');
//			$UserInfo = $user->find('first',array('conditions'=>array('User.id'=>$id),'fields'=>array('id','password')));
//echo "<pre>";print_r($userdata['User']['credit']);
?>
<style>
.error-message {margin-left:20px !important;}
.message {margin-left:145px !important;}
</style>
<script>
    
   $(document).ready(function(){
   $('#upload_user_img').click(function(){
           $('#image-upload').trigger('click');
            return false;
       });    
        
});
    
  $.uploadPreview({
    input_field: "#image-upload",   // Default: .image-upload
    preview_box: "#image-preview",  // Default: .image-preview
   
    no_label: true                 // Default: false
  });
   
</script>

<style>
.table-box{margin-top:0px; margin-left: 0; width: 100%;}
td{text-align:center;}
.HEader > td{width:30%;}
.HEader-1 > td{width:25%;}
.Edit-btn{margin-bottom:0px;}
.form-group{margin-bottom:10px;}
</style>

<div class="middle-section">
    <div class="container">
        
    <div class="ADDCOR-block1">
        <ul class="nav nav-tabs1">
            <li class="active"><a class="settingpad" data-toggle="tab" href="#home">Edit Profile</a></li>
            <li><a class="settingpad" data-toggle="tab" href="#menu1">Settings</a></li>
            <li><a class="settingpad" data-toggle="tab" href="#menu2">Credit Balance</a></li>
            <li><a class="settingpad" data-toggle="tab" href="#menu3" >Credit Transfer</a></li>
            <li><a class="settingpad" data-toggle="tab" href="#menu4" >Templates</a></li>
        </ul>

<div class="tab-content">
    
<div id="home" class="tab-pane fade in active">
    <div class="panel-body">
      <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action'=>'settings'),'id'=>'login_form','enctype'=>'multipart/form-data','inputDefaults'=>array('required'=>false))); ?>
      <?php echo $this->Form->error('email'); ?>
      <?php echo $this->Form->error('username'); ?> 
        
    <!--/CHANGE PROFILE PIC-->
    <div class="SLP_categoryBoxText" style="width:250px !important; float:left !important; margin: 10px 0px 0px -10px;">
    <!--/<h2 style="margin:0px;">Edit Profile</h2>-->
    <?php if(@$this->request->data['User']['image'] != ''){ ?>
        <div class="left-top-box" style="width:180px !important;">
            <img height="180" width="180" src="<?php echo $this->request->data['User']['image']; ?>">
            <div class="SLP_categoryBoxText" style="width:180px !important; float:left !important;">
                <div style="display: none;"><?php echo $this->Form->file('image',array('id'=>'image-upload','placeholder'=>'Image','label'=>false)); ?></div>
                <button type="button" class="SEnd-btn6 pull-right" id="upload_user_img">Change Picture</button>  
            </div>
        </div>
    <?php }else{ ?>
        <div class="left-top-box" style="width:180px !important;">
            <p></p>
            <div class="SLP_categoryBoxText" style="width:180px !important; float:left !important;">
               <div style="display: none;"><?php echo $this->Form->file('image',array('id'=>'image-upload','placeholder'=>'Image','label'=>false)); ?></div> 
               <button type="button" class="SEnd-btn6 pull-right" id="upload_user_img">Change Picture</button>  
            </div>
        </div>
    <?php } ?>
    </div>
    
    <!--/account-setting-form-->
<div class="account-setting-form">
      <?php echo $this->Form->hidden('User.id'); ?>
      <?php echo $this->Form->hidden('User.old_image',array('value'=>@$this->request->data['User']['image'])); ?>
    
     <!--/NAME-->
    <div class="form-group">
        <label class="control-label col-sm-2" style="width:16.6667% ;" for="email">Name:</label>
        <div class="col-sm-10" style="width: 83.3333% !important;">
          <!--<input class="form-control" id="email" placeholder="" type="text">-->
          <?php echo $this->Form->input('username',array('type'=>'text','placeholder'=>'Name','label'=>false,'class'=>'form-control validate[required]')); ?>
        </div>
    </div>
     
     <!--/MOBILE NO-->
    <div class="form-group">
        <label class="control-label col-sm-2" style="width: 16.6667%" for="email">Mobile:</label>
        <div class="col-sm-10" style="width: 83.3333% !important;">
          <!--<input class="form-control" id="email" placeholder="" type="text">-->
          <?php echo $this->Form->input('mobile',array('type'=>'text','disabled'=>'disabled','placeholder'=>'Mobile','label'=>false,'class'=>'form-control validate[required,custom[phone]]')); ?> 
        </div>
    </div>
     
     <!--/EMAIL-->
    <div class="form-group">
        <label class="control-label col-sm-2" style="width:16.6667% " for="email">Email:</label>
        <div class="col-sm-10" style="width: 83.3333% !important;">
          <!--<input class="form-control" id="email" placeholder="" type="email">-->
            <?php echo $this->Form->input('email',array('placeholder'=>'Email Address','label'=>false,'class'=>'form-control')); ?>
        </div>
    </div>
     
     <!--/ABOUT ME:-->
    <div class="form-group">
        <label class="control-label col-sm-2" style="width: 16.6667% " for="email">About me:</label>
        <div class="col-sm-10" style="width: 83.3333% !important;">
            <!--<textarea class="form-control" cols="10" rows="3" id="comment" style="resize:none;"></textarea>-->
            <?php echo $this->Form->input('about',array('type'=>'textarea','placeholder'=>'About Me','label'=>false,'class'=>'form-control')); ?>
            
        </div>
    </div>
     
     <!--/BUTTON-->
    <div class="form-group" style="margin: 0 !important;">
        <div class="col-sm-offset-2 col-sm-10" style="margin-left: 16.6667% !important;">
            <!--<button type="button" class="Edit-btn" style="float:left !important;">Sumbit</button>
            <button type="button" class="Edit-btn" style="float:left !important; background:#9D9D9D !important;">Reset</button>-->
            <?php echo $this->Form->submit('Submit',array('class'=>'Edit-btn','style'=>'float:left;')); ?>
            <?php echo $this->Form->reset('Reset',array('class'=>'Edit-btn','style'=>'float:left; background:#9D9D9D;')); ?>
        </div>
    </div>
   <?php echo $this->Form->end(); ?>   
</div>
   
</div>
</div>
    
<div id="menu1" class="tab-pane fade">
    <div class="panel-body">
        <div class="password">
            <div id="res-text_1">
              <h2 style="font-size:16px !important; font-weight: bold !important;">Change Password</h2>
            </div>
         <?php echo $this->Form->create('User',array('url'=>array('controller'=>'users','action' => 'change_password'),'autocomplete'=>'off','style'=>'padding-left: 20px;'));?>   
     
     <!--/Old Password-->
        <div class="form-group">
            <label class="control-label" for="email" style="width:20% !important; float:left !important;">Old Password:</label>
            <div class="col-sm-10" style="width: 80% !important;">
              <?php echo $this->Form->input('User.currentpassword',array('class'=>'form-control','label'=>false,'type'=>'password', 'placeholder'=>'Old Password','required'=>false)); ?>
            </div>
        </div>
     
     <!--/New Password-->
        <div class="form-group">
            <label class="control-label" style="width:20% !important; float:left !important;" for="email">New Password:</label>
            <div class="col-sm-10" style="width:80% !important;">
              <?php echo $this->Form->input('User.password',array('class'=>'form-control','label'=>false,'type'=>'password', 'placeholder'=>'New Password','required'=>false)); ?>
            </div>
        </div>
     
     <!--/Confirm New Password-->
        <div class="form-group">
            <label class="control-label" for="email" style="width:20% !important; float:left !important;">Confirm New Password:</label>
            <div class="col-sm-10" style="width: 80% !important;">
              <?php echo $this->Form->input('User.confirm_passwd',array('class'=>'form-control','label'=>false,'type'=>'password', 'placeholder'=>'Confirm Password','required'=>false)); ?>
            </div>
        </div>

     <!--/BUTTON-->
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10" style="margin-left:20% !important;">
            <?php echo $this->Form->submit('Submit',array('class'=>'Edit-btn','style'=>'float:left;')); ?>
            <?php echo $this->Form->reset('Reset',array('class'=>'Edit-btn','style'=>'float:left; background:#9D9D9D;')); ?>
            </div>
        </div>
     <?php echo $this->Form->end();?>
        </div>
    </div>
</div>
    
<div id="menu2" class="tab-pane fade">
    <div class="panel-body">
        <div class="credit-limit">
            <div class="credit-limit-text" style="margin-top:0px !important;">
                <h2 style="font-size:16px !important; font-weight: bold !important; margin-top:0px !important;">Credit Report</h2>
            </div>
<!--        <div class="report-box">
            <p>Credit Limit <br>
                <span>5,00,000</span>
            </p>
        </div>-->
            
        <div class="report-box">
            <p>Credit Balance <br>
                 <span><?php echo $userdata['User']['credit'];?></span>
            </p>
        </div>

       <?php
        $test1=$userdata['User']['expiredate'];
        $test2= date('d F,Y',strtotime($test1));
         ?>
            
        <div class="report-box">
            <p>Expire Date <br>
                 <span><?php echo $test2;?></span>
            </p>
        </div>
		 
<!--        <div class="view-status-chart"><img src="../img/status1.jpg" alt="img"></div>-->
        <div class="clearfix"></div>
         
    <div class="credit-limit-Table">
        <h2 style="font-size:16px !important; font-weight: bold !important; margin-top:10px !important;display: inline-block !important;">View Report</h2>
         
        <div class="Drop-down-Category" style="float:right !important; margin-top:0px !important;">
                <?php
		  
		   $channel = new Channel();
		   $data = $channel->getDefaultChannelData($mychannel[0]['Channel']['id']);
		  // echo "<pre>";print_r($data);
		  ?>
            <select id="company_2" class="form-control" style="color: #B6B1B1 !important;font-size: 14px !important; box-shadow: 0px 0px 0px #FFF !important;" onchange="getChannelData(this.value)">
	        <?php if(!empty($mychannel)){
			        foreach($mychannel as $channel)
					{  
			?>
            <option value="<?php echo $channel['Channel']['id'];?>"> <?php echo $channel['Channel']['channel_name'];?> </option>
            <?php }}?>
            </select>
        </div>
        
        <p style="display:inline-block !important; float:right !important; margin: 0px !important;padding-top: 7px !important;padding-right: 10px !important;">Select a Channel</p>
    <div class="table-box">
        <table class="table table-striped">
    
        <tbody>
            <tr class="HEader">
              <td>Message Type</td>
              <td>No of Message</td>
              <td>Credits</td>
            </tr>
            
            <tr>
              <td>Push</td>
              <td id="app"><?php echo $data['app'];?></td>
              <td id="push_credit"><?php echo $data['push_credit'];?></td>
            </tr>
            
            <tr>
              <td>Sms</td>
              <td id="sms"><?php echo $data['sms'];?></td>
              <td id="sms_credit"><?php echo $data['sms_credit'];?></td>
            </tr>
            
	    <tr>
               <td>&nbsp;</td>
               <td><strong>Total Credit</strong></td>
               <td id="total_credit"><strong><?php echo $data['total_credit'];?></strong></td>
           </tr>
	  
    </tbody>
  </table>
</div>
             
    </div>
    </div>
</div>
</div>
    
 <?php 
 $reseller = $user[0]['users']['is_reseller'];
 
 ?>
    
<div id="menu3" class="tab-pane fade">
    <div class="panel-body">
      <?php if($reseller == 'Y'){ ?>    
    <div class="password-1">
            <div id="res-text_1">
              <h2 style="font-size:16px !important; font-weight: bold !important; margin-top:10px !important;display: inline-block !important;">Credit Transfer</h2>
            </div>
        
   
        
    <?php echo $this->Form->create('CreditHistory',array('url'=>array('controller'=>'users','action' => 'credithistory'),'autocomplete'=>'off'));?>   
     <!--/Mobile No-->
        <div class="form-group">
            <label class="control-label" for="email" style="width:20% !important; float:left !important;">Mobile No:</label>
            <div class="col-sm-10" style="width: 80% !important;">
              <?php echo $this->Form->input('mobile',array('class'=>'form-control','label'=>false,'type'=>'text', 'placeholder'=>'Mobile No','required'=>true)); ?>
            </div>
        </div>
     
     <!--/Credits-->
        <div class="form-group">
            <label class="control-label" style="width:20% !important; float:left !important;" for="email">Credit:</label>
            <div class="col-sm-10" style="width:80% !important;">
               <?php echo $this->Form->input('credit',array('class'=>'form-control','label'=>false,'type'=>'text', 'placeholder'=>'Credit','required'=>true)); ?>
            </div>
        </div>
     
     <!--/BUTTON-->
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10" style="margin-left:20% !important;">
            <?php echo $this->Form->submit('Transfer',array('class'=>'Edit-btn','style'=>'float:left;')); ?>
            <?php echo $this->Form->reset('Reset',array('class'=>'Edit-btn','style'=>'float:left; background:#9D9D9D;')); ?>
            </div>
        </div>
     <?php echo $this->Form->end();?>
        </div>    
      
        <div class="credit-limit-Table-1" >
        <h2 style="font-size:16px !important; font-weight: bold !important; margin-top:10px !important;display: inline-block !important;">Credit History</h2>
      
        
    <div class="table-box">
        <table class="table table-striped">
    
            <tbody>
            <tr class="HEader-1">
              <td>Name</td>
              <td>Mobile No</td>
              <td>Credits</td>
              <td>Date</td>
            </tr>
            
            <?php
            
            if (!empty($creditdata)){
               foreach($creditdata as $credit)
		    {
			
	    ?>
            
            <tr>
              <td><?php echo $credit['credit_histories']['name']; ?></td>
              <td><?php echo $credit['credit_histories']['mobile']; ?></td>
              <td><?php echo $credit['credit_histories']['credit']; ?></td>
              <td><?php echo $credit['credit_histories']['created']; ?></td>
            </tr>
         <?php }}?>   
    </tbody>
  </table>
</div>
             
    </div>
    <?php } else { ?>
  
           
        <div class="password">
            <div id="res-text">
             <div id="res-text_1">
              <h2 style="font-size:16px !important; font-weight: bold !important; margin-top:10px !important;display: inline-block !important; color: red !important;">Please Contact to MEngage Admin to Activate Reseller Account.</h2>
            </div>
            </div>
        
       </div>      
 
    <?php } ?>

</div>    
  
</div>
    
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#chkSender").click(function () {
            if ($(this).is(":checked")) {
                $("#dvSender").show();
            } else {
                $("#dvSender").hide();
            }
        });
    });
</script>
    
<div id="menu4" class="tab-pane fade">
    <div class="panel-body">
        
    <div class="password-2">
            <div id="res-text_1">
              <h2 style="font-size:16px !important; font-weight: bold !important; margin-top:10px !important;display: inline-block !important;">Templates Assign</h2>
            </div>
      
        <div class="form-group">
            <label class="control-label" for="email" style="width:22% !important; float:left !important;">Sender Id:</label>
            <div class="col-sm-10" style="width: 70% !important;">
                <select id="company2" class="form-control" style="font-size:14px; color:#9f9f9f; padding-left:15px; border: 1px solid #F2F1F1;">
                  <option>1</option>
                  <option>2</option>
                  <option>3</option>
                </select>
            </div>  
            
           
                <label for="chkSender">
                   <input type="checkbox" id="chkSender" />
                </label>

                <div id="dvSender" class="col-sm-10" style="width: 78% !important; float: right; display: none;">
                    <input type="text" class="form-control" id="txtSenderNumber"  />
                </div>
            
        </div>
        
         <div class="form-group">
            <label class="control-label" for="email" style="width:22% !important; float:left !important;">Category:</label>
            <div class="col-sm-10" style="width: 70% !important;">
              <select id="company2" class="form-control" style="font-size:14px; color:#9f9f9f; padding-left:15px; border: 1px solid #F2F1F1;">
              <option>Health</option>
              <option>Real Estate</option>
              <option>Education</option>
            </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label" for="email" style="width:22% !important; float:left !important;">Template:</label>
            <div class="col-sm-10" style="width: 70% !important;">
              <?php echo $this->Form->input('template',array('class'=>'form-control','label'=>false,'type'=>'textarea', 'placeholder'=>'','required'=>false),array('style'=>'resize:none;')); ?>
            </div>
        </div>

     <!--/BUTTON-->
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10" style="margin-left:20% !important;">
            <?php echo $this->Form->submit('Submit',array('class'=>'Edit-btn','style'=>'float:left;')); ?>
            
            </div>
        </div>
     <?php echo $this->Form->end();?>
        </div>    
      
    <div class="credit-limit-Table-2" >
        <h2 style="font-size:16px !important; font-weight: bold !important; margin-top:10px !important;display: inline-block !important;">Templates History</h2>
      
        
    <div class="table-box">
        <table class="table table-striped">
    
            <tbody>
            <tr class="HEader-1">
              <td style="width: 13%;">Sender Id</td>
              <td style="width: 18%;">Category</td>
              <td style="width: 39%;">Templates</td>
              <td style="width: 10%;">Approved</td>
              <td style="width: 20%;">Created</td>
            </tr>
           
            <tr>
              <td>1023</td>
              <td>Health</td>
              <td>Thank you this is a simple message.</td>
              <td>Y</td>
              <td>2016-01-19 11:54:52</td>
            </tr>
            
            <tr>
              <td>1023</td>
              <td>Real Estate</td>
              <td>Thank you this is a simple message.</td>
              <td>Y</td>
              <td>2016-01-19 11:54:52</td>
            </tr>
            
            <tr>
              <td>1023</td>
              <td>Education</td>
              <td>Thank you this is a simple message.</td>
              <td>Y</td>
              <td>2016-01-19 11:54:52</td>
            </tr>
            
            <tr>
              <td>1023</td>
              <td>Health</td>
              <td>Thank you this is a simple message.</td>
              <td>Y</td>
              <td>2016-01-19 11:54:52</td>
            </tr>
          
    </tbody>
  </table>
</div>
             
    </div>

</div> 
</div>    
  
</div>    

</div>
</div>
</div>

<script>
    function getChannelData(channelId)
	{
	    $.ajax({
                        'url':  "<?php echo SITE_URL; ?>messages/getchannelmessagedata",
                        'data':  {"channelId" : channelId},
                        'type' : 'post',
                        'dataType' : 'json',
                        'success': function(resp){
						
                            if(resp.status == 1){
							 $('#sms').html(resp.sms);
							 $('#app').html(resp.app);
							 $('#sms_credit').html(resp.sms_credit);
							  $('#push_credit').html(resp.push_credit);
							  $('#total_credit strong').html(resp.total_credit);
							}else{
                                $('#resp_message_error').html(resp.message);
                                $('#resp_message_sucess').hide();
                                $('#resp_message_error').show();
                            }
                        }
                    });
	}
        
     
</script>

<?php echo $this->Html->script(array('bootstrap.min','jquery','jquery.form'));?>