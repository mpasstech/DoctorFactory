<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Edit Child Milestone</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
				
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->
<?php echo $this->element('app_admin_leftsidebar'); ?>
                   
                    <!--left sidebar-->

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <?php echo $this->element('child_milestone_tab'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('PrescriptionTag',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>


							<div class="form-group">
                                <div class="col-sm-12">
                                    <label>Milestone For</label>
                                    <?php echo $this->Form->input('name',array('type'=>'select','label'=>false,'options'=>$this->AppAdmin->getChildMilestonePeriod(),'class'=>'form-control cnt')); ?>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">

                                    <?php

                                    $options = array('MALE' => 'Male', 'FEMALE' => 'Female');
                                    $attributes = array('value'=>'MALE','legend' => "Gender",'class'=>'radio-inline','div'=>'label');
                                    echo $this->Form->radio('gender', $options, $attributes);


                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Description</label>
                                    <?php echo $this->Form->input('description',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'editor1','class'=>'form-control')); ?>                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Status</label>
                                    <?php echo $this->Form->input('status',array('type'=>'select','label'=>false,'options'=>array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'),'class'=>'form-control cnt')); ?>
                                </div>
							


                            <div class="form-group">
                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Update',array('class'=>'Btn-typ5','id'=>'signup')); ?>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>


                        </div>



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



    });


        <?php if($category == 'HEALTH_TIP'){ ?>
        $("#subCategoryContainer").html(healthTipSelect);
        <?php }else{ ?>
        $("#subCategoryContainer").html(emergencySelect);
        <?php } ?>


       $("#CmsDocDashboardSubCategoryId").val(<?php echo $subCategoryID; ?>);
    });

    /*$(document).ready(function(){
        var containerVal = $("#CmsDocDashboardCategory").val();
        if(containerVal == 'HEALTH_TIP')
        {
            $("#subCategoryContainer").show();
        }
        $("#CmsDocDashboardCategory").change(function(){
            var containerValRun = $("#CmsDocDashboardCategory").val();
            if(containerValRun == 'HEALTH_TIP')
            {
                $("#subCategoryContainer").show();
                $("#CmsDocDashboardSubCategoryId").attr("required",true);
            }
            else
            {
                $("#CmsDocDashboardSubCategoryId").val("");
                $("#CmsDocDashboardSubCategoryId").attr("required",false);
                $("#subCategoryContainer").hide();
            }
        });
    }); */
	
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
</script>
<script>
    $(document).ready(function(){
        $(".channel_tap a").removeClass('active');
        $("#v_add_channel").addClass('active');
		
			CKEDITOR.replace( 'editor1');

	
    });
</script>



    <style>

        fieldset label{
            margin: 0px 6px 0px 4px;
        }

    </style>