<?php
$login = $this->Session->read('Auth.User');
$category = $this->request->data['CmsDocDashboard']['category'];
$subCategoryID = $this->request->data['CmsDocDashboard']['sub_category_id'];
?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
				
                <div class="middle-block">
                    <h3 class="screen_title">Edit Health & Emergency</h3>
                    <div class="Social-login-box payment_bx">
                        <?php echo $this->element('message'); ?>
                        <?php echo $this->element('app_admin_inner_tab_cms_doc'); ?>
                        <?php echo $this->Form->create('CmsDocDashboard',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Page Title</label>
                                <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Page Title','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Category</label>
                                <?php echo $this->Form->input('category',array('type'=>'select','label'=>false,'options'=>array('EMERGENCY'=>'Emergency','HEALTH_TIP'=>'Health Tip'),'class'=>'form-control cnt','maxlength'=>'256')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Sub Category</label>
                                <div id="subCategoryContainer"></div>
                                <?php /* echo $this->Form->input('sub_category_id',array('type'=>'select','label'=>false,'options'=>$subCategories,'empty'=>'Please Select','class'=>'form-control cnt','required'=>true));*/ ?>
                            </div>
                        </div>

                        <!--   <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Page Slug</label>
                                    <?php /*echo $this->Form->input('slug',array('type'=>'text','placeholder'=>'Page Slug','label'=>false,'class'=>'form-control cnt')); */?>

                                </div>
                            </div>
-->





                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Page Description</label>
                                <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'editor1','class'=>'form-control')); ?>                                </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Status</label>
                                <?php echo $this->Form->input('status',array('type'=>'select','label'=>false,'options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'class'=>'form-control cnt')); ?>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Media Type</label>
                                    <?php echo $this->Form->input('media_type',array('type'=>'select','label'=>false,'options'=>array('IMAGE'=>'Image','VIDEO'=>'Video'),'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <label>Image</label>
                                <?php echo $this->Form->input('file',array('type'=>'file','class'=>'form-control','label'=>false)); ?>
                            </div>
                        </div>

                        <?php if($this->request->data['CmsDocDashboard']['media_type'] == 'IMAGE'){ ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <img style="width:200px;" src="<?php echo $this->request->data['CmsDocDashboard']['image']; ?>">
                                </div>
                            </div>
                        <?php }
                        else
                        { ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <video width="320" height="240" controls>
                                        <source src="<?php echo $this->request->data['CmsDocDashboard']['image']; ?>" type="video/mp4">
                                        <source src="<?php echo $this->request->data['CmsDocDashboard']['image']; ?>" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            </div>
                        <?php } ?>







                        <div class="form-group">
                            <div class="col-sm-3 pull-right">
                                <?php echo $this->Form->submit('Update',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                            </div>
                        </div>

                        <?php echo $this->Form->end(); ?>


                    </div>

                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>




<script>
$(document).ready(function(){
	$("#CmsDocDashboardEditCmsDocDashboardForm").submit(function(event){
		event.preventDefault();



        var description_len = CKEDITOR.instances.editor1.getData().length;
        if(description_len < 80 ){
            $.alert("Please upload minimum 80 characters. You have enter only "+description_len+" characters. ");
            return false;
        }


        
		if($("#CmsDocDashboardFile").prop('files').length > 0)
		{
				var mediaTypeVal = $("#CmsDocDashboardMediaType").val();
				var fileSize = $("#CmsDocDashboardFile").prop('files')[0]['size']; 
				var currentType = $("#CmsDocDashboardFile").prop('files')[0]['type'];
				
				if(mediaTypeVal == 'IMAGE')
				{
					
					if( (currentType.indexOf("image") < 0) || ((fileSize / 1048576) > 21) )
					{
						alert("Please select an image file of size less then 20 MB!");
					}
					else
					{
						$( this ).unbind( "submit");
						$( this ).submit();
					}
				}
				else
				{
					if( (currentType.indexOf("video") < 0) || ((fileSize / 1048576) > 21) )
					{
						alert("Please select an video file of size less then 20 MB!");
					}
					else
					{
						$( this ).unbind( "submit");
						$( this ).submit();
					}
				}
		}
		else
		{
			$( this ).unbind( "submit");
			$( this ).submit();
		}
	});
});
</script>
<script>

    $(document).ready(function(){

        var selectInput = '<select name="data[CmsDocDashboard][sub_category_id]" class="form-control cnt" required="required" id="CmsDocDashboardSubCategoryId">';

        var healthTipSelect = selectInput;
        healthTipSelect += '<?php foreach($subCategories as $key => $value){ echo '<option value="'.$key.'">'.$value.'</option>'; } ?>';
        healthTipSelect +='</select>';

        var emergencySelect = selectInput;
        emergencySelect += '<?php foreach($subCategoriesEmergency as $key => $value){ echo '<option value="'.$key.'">'.$value.'</option>'; } ?>';
        emergencySelect +='</select>';


        $(document).on("change","#CmsDocDashboardCategory",function(){
            var containerValRun = $(this).val();
            if(containerValRun == 'EMERGENCY')
            {
                $("#subCategoryContainer").html(emergencySelect);
            }
            else
            {
                $("#subCategoryContainer").html(healthTipSelect);
            }
        })






        <?php if($category == 'HEALTH_TIP'){ ?>
        $("#subCategoryContainer").html(healthTipSelect);
        <?php }else{ ?>
        $("#subCategoryContainer").html(emergencySelect);
        <?php } ?>


       $("#CmsDocDashboardSubCategoryId").val(<?php echo $subCategoryID; ?>);


    $(".channel_tap a").removeClass('active');
    $("#v_app_cms_list").addClass('active');

    CKEDITOR.replace( 'editor1',{wordcount: {
            showParagraphs: false,
            showWordCount: true,
            showCharCount: true,
            countSpacesAsChars: false,
            countHTML: false,
            maxWordCount: -1,
            maxCharCount: 3600}
        }
    );

    $('#CmsDocDashboardTitle').on('keyup', function() {
        limitText(this, 256)
    });

    function limitText(field, maxChar){
        var ref = $(field),
            val = ref.val();
        if ( val.length >= maxChar ){
            ref.val(function() {

                return val.substr(0, maxChar);
            });
        }
    }

    });


	

</script>




