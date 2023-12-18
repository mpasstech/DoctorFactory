    <?php
    $login = $this->Session->read('Auth');
    $category = $this->request->data['CmsDocDashboard']['category'];
    $subCategoryID = $this->request->data['CmsDocDashboard']['sub_category_id'];
    ?>

    <div class="Inner-banner">
        <div class="container">
            <div class="row">
                <!--  SLIDER IMAGE -->
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>DOC CMS</h2> </div>
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

                        <?php echo $this->element('support_admin_leftsidebar'); ?>
                        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                            <?php echo $this->element('supp_admin_inner_tab_cms_doc'); ?>


                            <div class="Social-login-box payment_bx">

                                <?php echo $this->element('message'); ?>

                                <?php echo $this->Form->create('CmsDocDashboard',array('type'=>'file','method'=>'post','class'=>'form-horizontal')); ?>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Title</label>
                                        <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Page Title','label'=>false,'id'=>'mobile','class'=>'form-control cnt','maxlength'=>'256')); ?>

                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label>Select Category</label>
                                        <?php echo $this->Form->input('category',array('type'=>'select','label'=>false,'options'=>array('EMERGENCY'=>'Emergency','HEALTH_TIP'=>'Health Tip',"MENGAGE_CHANNEL"=>"MEngage Channel"),'class'=>'form-control cnt')); ?>
                                    </div>
                                </div>

                                <div class="form-group" >
                                    <div class="col-sm-12">
                                        <label>Select Sub Category</label>
                                        <div  id="subCategoryContainer"></div>
                                        <?php /* echo $this->Form->input('sub_category_id',array('type'=>'select','label'=>false,'options'=>$subCategories,'class'=>'form-control cnt','required'=>true));*/ ?>
                                    </div>
                                </div>
                                <div class="form-group" >
                                    <div class="col-sm-12">
                                        <div class="radio_lbl_vac">

                                            <?php
                                            $options = array('ALL' => 'Everyone', 'ADMIN' => 'Only app owner', 'USER' => 'Only app patients');
                                            $attributes = array('value'=>'ALL','legend' => "Who can see this post?",'class'=>'radio-inline','div'=>'label');
                                            echo $this->Form->radio('visible_for', $options, $attributes);

                                            ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group" >
                                    <div class="col-sm-12">
                                        <div class="radio_lbl_vac">

                                            <?php
                                            $options = array('YES' => 'Yes', 'NO' => 'No');
                                            $attributes = array('value'=>'NO','legend' => "Would you like to send silent notification?",'class'=>'radio-inline noti_class','div'=>'label');
                                            echo $this->Form->radio('notification', $options, $attributes);

                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <!--
                                                            <div class="form-group">
                                                                <div class="col-sm-12">
                                                                    <label>Page Slug</label>
                                                                    <?php /*echo $this->Form->input('slug',array('type'=>'text','placeholder'=>'Page Slug','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); */?>

                                                                </div>
                                                            </div>
                                -->

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
                    <!-- box 1 -->


                </div>
                <!--box 2 -->

            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $("#CmsDocDashboardAdminAddCmsDocDashboardForm").submit(function(event){
                event.preventDefault();
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

            var channelSelect = selectInput;
            channelSelect += '<?php foreach($mengageChannel as $key => $value){ echo '<option value="'.$key.'">'.$value.'</option>'; } ?>';
            channelSelect +='</select>';

            $("#CmsDocDashboardCategory").change(()=>{
                var containerValRun = $("#CmsDocDashboardCategory").val();
            if(containerValRun == 'EMERGENCY')
            {
                $("#subCategoryContainer").html(emergencySelect);
            }
            else if(containerValRun == 'HEALTH_TIP'){
                $("#subCategoryContainer").html(healthTipSelect);
            }else
            {
                $("#subCategoryContainer").html(channelSelect);
            }
        });


            <?php if($category == 'HEALTH_TIP'){ ?>
            $("#subCategoryContainer").html(healthTipSelect);
            <?php }else if($category == 'EMERGENCY'){ ?>
            $("#subCategoryContainer").html(emergencySelect);
            <?php }else{ ?>
            $("#subCategoryContainer").html(channelSelect);
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

                    return val.substr(0, maxChar);
                });
            }
        }




    </script>
    <script>
        $(document).ready(function(){
            $(".channel_tap a").removeClass('active');
            $("#v_add_channel").addClass('active');
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




