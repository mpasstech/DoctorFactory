<?php echo $this->Html->script(array('jquery.uploadPreview.min'));?>

<!--/RIGHT-BOX-->
<div class="right-box">
    <div class="top-headibg-block">

        
        <div class="all-msg3">
            <p>Create New Page</p>
        </div> 
    </div>
<?php echo $this->Session->flash("front_error"); ?>
<?php echo $this->Session->flash("front_success"); ?>
    
<div class="reply-box">
    <div class="Create-channel-box">
        
    <?php  echo $this->Form->create('CmsPage',array('url'=>array('controller'=>'CmsPages','action'=>'add'),'inputDefaults'=>array('required'=>true),'enctype'=>'multipart/form-data'));?>    
	

   
<div class="form-box">
	 
    <div class="form-group">
      <label class="control-label col-sm-3" for="email">Page Name :</label>
      <div class="col-sm-9" style="padding:10px;">
          <?php echo $this->Form->input('title',array('type'=>'text','maxlength'=> 30,'placeholder'=>'Page Name','label'=>false,'class'=>'form-control'));?>
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-3" for="email" style="padding-top:50px;">Description :</label>
      <div class="col-sm-9" style="padding:10px;">
        <?php echo $this->Form->input('description',array('label'=>false,'type'=>'textarea'));?>
      </div>
    </div>
    
  <!--  <div class="form-group">
      <label class="control-label col-sm-3" for="email">Visibility :</label>
      <div class="col-sm-9" style="padding:10px;">
          <?php $sizes = array('0' => 'Private', '1' => 'Public');
            echo $this->Form->input('is_public',array('options' => $sizes, 'default' => '','class'=>'form-control','label'=>false,'style'=>'font-size:14px; color:#9f9f9f; padding-left:15px; border: 1px solid #F2F1F1;'));
          ?>
      </div>
    </div> -->
     <div class="channel-logo">
           <div id="image-preview" style="width: 160px; height: 160px;">
                <div style="display: block;"><?php echo $this->Form->file('image',array('id'=>'image-upload','class'=>'upload','placeholder'=>'Image','label'=>false)); ?></div>
                <button type="button" class="sub" style="width:100%; margin-top:170px; margin-left:0px;" id="upload_user_img">Add Image</button>
            </div>
        </div>
    <div class="form-group">
      <div class="col-sm-3"></div>
        <div class="col-sm-9 text" style="padding:0px;">
            <?php echo $this->Form->submit('Submit',array('class'=>'Subscriber-btn')); ?>


          <div class="back-btn" style="float: right;
                                                                        width: 70px;
                                                                        text-align: center;
                                                                        position: absolute;
                                                                        height: 28px;
                                                                        border-radius: 4px;
                                                                        margin-left: 120px;
                                                                        margin-top: -28px;
                                                                        background: #03a9f4;">
                      <?php echo $this->Html->link('Back', array('controller'=>'users','action'=>'dashboard')); ?>
                  </div>


    </div>
        </div>

            
</div>
  <?php echo $this->Form->end(); ?>      
</div>

</div><!--REPLY-BOX-END-->
</div><!--RIGHT-BOX-END-->

<script>
    
    $('#upload_user_img').click(function(){
		
           $('#image-upload').trigger('click');
            return false;
       });
    
  $.uploadPreview({
    input_field: "#image-upload",   // Default: .image-upload
    preview_box: "#image-preview",  // Default: .image-preview
   
    no_label: true                 // Default: false
  });
   
</script>
