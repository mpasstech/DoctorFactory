<?php echo $this->Html->script(array('jquery.uploadPreview.min'));?>
<!--/RIGHT-BOX-->
<div class="right-box">
    <div class="top-headibg-block">

        
        <div class="all-msg3">
            <p>Edit Page</p>
        </div> 
    </div>
<?php echo $this->Session->flash("front_error"); ?>
<?php echo $this->Session->flash("front_success"); ?>
    
<div class="reply-box">
    <div class="Create-channel-box">
        
    <?php echo $this->Form->create('CmsPage',array('url'=>array('controller' => 'CmsPages', 'action'=> 'editpage'),'inputDefaults'=>array('required'=>false),'enctype'=>'multipart/form-data'));?>
    <?php echo $this->Form->hidden('id');?>
        
       

    
<div class="form-box">
	 
    <div class="form-group">
      <label class="control-label col-sm-3" for="email"> Page Name : </label>
      <div class="col-sm-9" style="padding:10px;">
          <?php echo $this->Form->input('title',array('type'=>'text','maxlength'=> 30,'placeholder'=>'Channel Name','label'=>false,'class'=>'form-control'));?>
      </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-sm-3" for="email" style="padding-top:50px;">Description :</label>
      <div class="col-sm-9" style="padding:10px;">
        <?php echo $this->Form->input('description',array('label'=>false,'type'=>'textarea','maxlength'=> 50,'class'=>'form-control'));?>
      </div>
      <div class="channel-logo">

              <div id="image-preview" style="width: 160px; height: 160px;">
                  <img id="update_image" src="<?php echo $this->request->data['CmsPage']['image'];?>"/>
                  <div ><?php echo $this->Form->file('image',array('id'=>'image-upload', 'placeholder'=>'Image', 'onchange' => 'readURL(this)',  'label'=>false, 'value' => $this->request->data['CmsPage']['image'] )); ?></div>
                  <button type="button" class="sub" style="width:100%; margin-top:10px; margin-left:0px;" id="upload_user" onclick="$('#image-upload').trigger('click');">Update Picture</button>
              </div>

          </div>

    </div>
    
   

    <div class="form-group">
      <div class="col-sm-3"></div>
        <div class="col-sm-9 text" style="padding:0px;">
            <?php echo $this->Form->submit('Update Page',array('class'=>'Subscriber-btn')); ?>


        </div>
<div class="back-btn" style="float: right;
                                 width: 70px;
                                 text-align: center;
                                 position: absolute;
                                 height: 28px;
                                 border-radius: 4px;
                                 margin-left: 320px;
                                 margin-top: 80px;
                                 background: #03a9f4;">
                        <?php echo $this->Html->link('Back', array('controller'=>'users','action'=>'dashboard')); ?>
                    </div>

    </div>


</div>
  <?php echo $this->Form->end(); ?>      
</div> 

</div><!--REPLY-BOX-END-->
</div><!--RIGHT-BOX-END-->
<script>
	function upload_user()
	{
	 $('#image-upload').trigger('click');	
	 return false;
	}

$.uploadPreview({
    input_field: "#image-upload",   // Default: .image-upload
    preview_box: "#image-preview",  // Default: .image-preview
   
    no_label: true                 // Default: false
  });
</script>
