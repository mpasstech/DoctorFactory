<?php
$login = $this->Session->read('Auth.User');
$category = @$this->request->data['CmsDocDashboard']['category'];
$subCategoryID = @$this->request->data['CmsDocDashboard']['sub_category_id'];
?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Add Health & Emergency</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <?php echo $this->element('app_admin_inner_tab_cms_doc'); ?>
                            <?php echo $this->Form->create('CmsDocDashboard',array('type'=>'file','method'=>'post','class'=>'form-horizontal sub_form')); ?>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Title</label>
                                    <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Title','label'=>false,'id'=>'mobile','class'=>'form-control cnt','maxlength'=>'256')); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Select Category</label>
                                    <?php echo $this->Form->input('category',array('type'=>'select','label'=>false,'options'=>array('EMERGENCY'=>'Emergency','HEALTH_TIP'=>'Health Tip'),'class'=>'form-control cnt')); ?>
                                </div>
                            </div>

                            <div class="form-group" >
                                <div class="col-sm-12">
                                    <label>Select Sub Category</label>
                                    <div  id="subCategoryContainer"></div>
                                    <?php /* echo $this->Form->input('sub_category_id',array('type'=>'select','label'=>false,'options'=>$subCategories,'class'=>'form-control cnt','required'=>true));*/ ?>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Page Description</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'editor1','class'=>'form-control')); ?>                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Media Type</label>
                                    <?php echo $this->Form->input('media_type',array('type'=>'select','label'=>false,'options'=>array('IMAGE'=>'Image','VIDEO'=>'Video'),'class'=>'form-control cnt')); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Media</label>
                                    <?php echo $this->Form->input('file',array('type'=>'file','class'=>'form-control','required'=>'required','label'=>false)); ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Add',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script>
$(document).ready(function(){


	$("#CmsDocDashboardAddCmsDocDashboardForm").submit(function(event){


		event.preventDefault();

        var description_len = CKEDITOR.instances.editor1.getData().length;
        if(description_len < 80 ){
            $.alert("Please upload minimum 80 characters. You have enter only "+description_len+" characters. ");
            return false;
        }

        var mediaTypeVal = $("#CmsDocDashboardMediaType").val();
		var fileSize = $("#CmsDocDashboardFile").prop('files')[0]['size']; 
		var currentType = $("#CmsDocDashboardFile").prop('files')[0]['type'];


        if(mediaTypeVal == 'IMAGE')
		{
			
			if( (currentType.indexOf("image") < 0) || ((fileSize / 1048576) > 21) )
			{
				$.alert("Please select an image file of size less then 20 MB!");
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
				$.alert("Please select an video file of size less then 20 MB!");
			}
			else
			{
				$( this ).unbind( "submit");
				$( this ).submit();
			}
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
        });


        <?php if($category == 'HEALTH_TIP'){ ?>
            $("#subCategoryContainer").html(healthTipSelect);
        <?php }else{ ?>
            $("#subCategoryContainer").html(emergencySelect);
        <?php } ?>

        $("#CmsDocDashboardSubCategoryId").val(<?php echo $subCategoryID; ?>);

        $(".channel_tap a").removeClass('active');
        $("#v_add_cms").addClass('active');



        $('#CmsDocDashboardTitle').on('keyup', function() {
            limitText(this, 256)
        });

        function limitText(field, maxChar){
            var ref = $(field),
                val = ref.val();
            if ( val.length >= maxChar ){
                ref.val(function() {
                    console.log(val.substr(0, maxChar))
                    return val.substr(0, maxChar);
                });
            }
        }

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
    });



</script>




