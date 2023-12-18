<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

                    <?php // echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo $this->element('app_admin_dental_supplires'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('DentalSupplierSetting',array('type'=>'file','id'=>'form_post','method'=>'post','class'=>'form-horizontal')); ?>


                            <div class="form-group">
                                <div class="col-sm-6 ">

                                    <div class="col-sm-12 block">
                                        <label>Shade</label>
                                        <div class="shade_holder">

                                            <?php if(!empty($teethShade)){ ?>

                                                <?php foreach($teethShade  AS $shadeData){ ?>
                                                    <div class="col-sm-12 set_field">
                                                        <input type="text" name="TeethShade[]" placeholder="Teeth Shade" value="<?php echo $shadeData['TeethShade']['shade']?>" class='form-control cnt' required>
                                                        <button type="button" id="deleteMoreShade" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                    </div>
                                                <?php } ?>

                                            <?php }else{ ?>
                                                <div class="col-sm-12 set_field">
                                                    <input type="text" name="TeethShade[]" placeholder="Teeth Shade" class='form-control cnt' required>
                                                    <button type="button" id="deleteMoreShade" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                </div>
                                            <?php } ?>


                                        </div>
                                        <button type="button" id="addMoreShade" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>
                                    </div>

                                </div>
                                <div class="col-sm-6 ">


                                    <div class="col-sm-12 block">
                                        <label>Attachment Type</label>
                                        <div class="attachment_holder">

                                            <?php if(!empty($teethAttachment)){ ?>

                                                <?php foreach($teethAttachment  AS $teethAttachmentData){ ?>
                                                    <div class="col-sm-12 set_field">
                                                        <input type="text" name="TeethAttachment[]" placeholder="Attachment Type" value="<?php echo $teethAttachmentData['TeethAttachment']['name']; ?>" class='form-control cnt' required>
                                                        <button type="button" id="deleteMoreAttachment" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                    </div>
                                                <?php } ?>

                                            <?php }else{ ?>
                                                <div class="col-sm-12 set_field">
                                                    <input type="text" name="TeethAttachment[]" placeholder="Attachment Type" class='form-control cnt' required>
                                                    <button type="button" id="deleteMoreAttachment" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                </div>
                                            <?php } ?>




                                        </div>
                                        <button type="button" id="addMoreAttachment" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>

                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <label>Category & Subcategory Text</label>

                                    <div class="category_text">



                                        <?php $teethTextCategoryIndex = 0; if(!empty($teethTextCategory)){ ?>

                                        <?php foreach($teethTextCategory AS $key => $teethTextCategoryData ){ ?>
                                                <div class="col-sm-12 set_category_text  block">

                                                    <input type="text" value="<?php echo $teethTextCategoryData['TeethTextCategory']['name']; ?>" name="teethTextCategory[<?php echo $teethTextCategoryIndex; ?>]" placeholder="Category Text" class='form-control cnt' required>


                                                    <div class="col-sm-9 col-sm-offset-3">


                                                        <div class="set_sub_category_text">

                                                            <?php foreach($teethTextSubCategory AS $teethTextSubCategoryData){ ?>
                                                                <?php if($teethTextSubCategoryData['TeethTextSubCategory']['teeth_text_category_id'] == $teethTextCategoryData['TeethTextCategory']['id']){ ?>
                                                                    <div class="col-sm-12 set_category_text">
                                                                        <input type="text" value="<?php echo $teethTextSubCategoryData['TeethTextSubCategory']['name']; ?>" name="teethTextSubcategory[<?php echo $teethTextCategoryIndex; ?>][]" placeholder="Subcategory Text" class='form-control cnt' required>
                                                                        <button type="button" id="deleteTeethTextSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                                    </div>
                                                                <?php } ?>
                                                            <?php } ?>

                                                        </div>


                                                        <button type="button" id="addMoreTeethTextSubcategory" data-id="<?php echo $teethTextCategoryIndex; ?>" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>

                                                    </div>


                                                    <button type="button" id="deleteTeethTextCategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>

                                                </div>
                                            <?php $teethTextCategoryIndex++; } ?>
                                        <?php }
                                        else{ ?>
                                            <div class="col-sm-12 set_category_text  block">

                                                <input type="text" name="teethTextCategory[<?php echo $teethTextCategoryIndex; ?>]" placeholder="Category Text" class='form-control cnt' required>


                                                <div class="col-sm-9 col-sm-offset-3">


                                                    <div class="set_sub_category_text">

                                                        <div class="col-sm-12 set_category_text">
                                                            <input type="text" name="teethTextSubcategory[<?php echo $teethTextCategoryIndex; ?>][]" placeholder="Subcategory Text" class='form-control cnt' required>
                                                            <button type="button" id="deleteTeethTextSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                        </div>

                                                    </div>


                                                    <button type="button" id="addMoreTeethTextSubcategory" data-id="<?php echo $teethTextCategoryIndex; ?>" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>

                                                </div>


                                                <button type="button" id="deleteTeethTextCategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>

                                            </div>
                                        <?php $teethTextCategoryIndex++; } ?>



                                    </div>
                                    <button type="button" id="addMoreTeethTextCategory" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>

                                </div>


                                <div class="col-sm-6">
                                    <label>Category & Subcategory with Image</label>

                                    <div class="category_image">



                                        <?php $teethImageCategoryIndex = 0; if(!empty($teethImageCategory)){ ?>

                                            <?php foreach($teethImageCategory AS $key => $teethImageCategoryData ){ ?>
                                                <div class="col-sm-12 set_category_image  block">

                                                    <input type="text" name="teethImageCategory[<?php echo $teethImageCategoryIndex; ?>]" value="<?php echo $teethImageCategoryData['TeethImageCategory']['name'];?>" placeholder="Category Image" class='form-control cnt' required>


                                                    <div class="col-sm-9 col-sm-offset-3">


                                                        <div class="set_sub_category_image">

                                                            <?php foreach($teethImageSubCategory AS $teethImageSubCategoryData){ ?>
                                                                <?php if($teethImageSubCategoryData['TeethImageSubCategory']['teeth_image_category_id'] == $teethImageCategoryData['TeethImageCategory']['id']){ ?>
                                                                    <div class="col-sm-12 set_category_image image_container">
                                                                        <input type="text" name="teethImageSubcategory[<?php echo $teethImageCategoryIndex; ?>][]" value="<?php echo $teethImageSubCategoryData['TeethImageSubCategory']['name']; ?>" placeholder="Subcategory Image" class='image_text form-control cnt' required>
                                                                        <input type="file" name="teethImageSubcategoryFile[<?php echo $teethImageCategoryIndex; ?>][]" class='image_image form-control cnt' required>
                                                                        <button type="button" id="deleteTeethImageSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                                    </div>
                                                                <?php } ?>

                                                            <?php } ?>

                                                        </div>


                                                        <button type="button" id="addMoreTeethImageSubcategory" data-id="<?php echo $teethImageCategoryIndex; ?>" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>

                                                    </div>


                                                    <button type="button" id="deleteTeethImageCategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>

                                                </div>
                                            <?php $teethImageCategoryIndex++; } ?>

                                        <?php  } else { ?>

                                            <div class="col-sm-12 set_category_image  block">

                                                <input type="text" name="teethImageCategory[<?php echo $teethImageCategoryIndex; ?>]" placeholder="Category Image" class='form-control cnt' required>


                                                <div class="col-sm-9 col-sm-offset-3">


                                                    <div class="set_sub_category_image">

                                                        <div class="col-sm-12 set_category_image image_container">
                                                            <input type="text" name="teethImageSubcategory[<?php echo $teethImageCategoryIndex; ?>][]" placeholder="Subcategory Image" class='image_text form-control cnt' required>
                                                            <input type="file" name="teethImageSubcategoryFile[<?php echo $teethImageCategoryIndex; ?>][]" class='image_image form-control cnt' required>
                                                            <button type="button" id="deleteTeethImageSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>
                                                        </div>

                                                    </div>


                                                    <button type="button" id="addMoreTeethImageSubcategory" data-id="<?php echo $teethImageCategoryIndex; ?>" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>

                                                </div>


                                                <button type="button" id="deleteTeethImageCategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>

                                            </div>

                                        <?php  $teethImageCategoryIndex++; } ?>


                                    </div>
                                    <button type="button" id="addMoreTeethImageCategory" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>

                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-5">
                                    <?php echo $this->Form->submit('Update Setting',array('class'=>'Btn-typ5','id'=>'btn_update')); ?>
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

<style>
    .set_field {
        display: inline-flex;
    }
    .btn-remove {
        position: absolute;
        float: right;
        right: 15px;
        top: 0px;
    }
    .set_category_text {
        margin-top: 10px;
    }
    #addMoreShade {
        margin-left: 15px;
    }
    #addMoreAttachment {
        margin-left: 15px;
    }
    #addMoreTeethTextSubcategory {
        margin-left: 15px;
    }
    #addMoreTeethTextCategory {
        margin-left: 15px;
    }
    .set_category_image {
        margin-top: 10px;
    }
    #addMoreTeethImageSubcategory {
        margin-left: 15px;
    }
    #addMoreTeethImageCategory {
        margin-left: 15px;
    }
    .image_image {

        width: 40%;
        margin-left:5px;

    }
    .image_text {

        width: 60%;

    }
    .image_container {

        display: flex;

    }
    .block {

        background-color: #b8e0c9;
        padding: 30px;
        border: 1px solid transparent;
        border-radius: 5px;

    }
</style>

<script>
    $(document).ready(function () {
        $("#addMoreShade").click(function(){
            var htmlAdd = '<div class="col-sm-12 set_field">'+
                '<input type="text" name="TeethShade[]" placeholder="Teeth Shade" class="form-control cnt" required>'+
                    '<button type="button" id="deleteMoreShade" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>';
            $(".shade_holder").append(htmlAdd);
        });
        $(document).on('click',"#deleteMoreShade",function(){
            $(this).parent(".set_field").remove();
        });
        $("#addMoreAttachment").click(function(){
            var htmlAdd = '<div class="col-sm-12 set_field">'+
                '<input type="text" name="TeethAttachment[]" placeholder="Attachment Type" class="form-control cnt" required>'+
                '<button type="button" id="deleteMoreAttachment" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>';
            $(".attachment_holder").append(htmlAdd);
        });
        $(document).on('click',"#deleteMoreAttachment",function(){
            $(this).parent(".set_field").remove();
        });

        var indexTextCategory = <?php echo $teethTextCategoryIndex; ?>;
        $("#addMoreTeethTextCategory").click(function(){
            var htmlAdd = '<div class="col-sm-12 set_category_text  block">'+
                '<input type="text" name="teethTextCategory['+indexTextCategory+']" placeholder="Category Text" class="form-control cnt" required>'+
                '<div class="col-sm-9 col-sm-offset-3">'+
                '<div class="set_sub_category_text">'+
                '<div class="col-sm-12 set_category_text">'+
                '<input type="text" name="teethTextSubcategory['+indexTextCategory+'][]" placeholder="Subcategory Text" class="form-control cnt" required>'+
                '<button type="button" id="deleteTeethTextSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>'+
                '</div>'+
                '<button type="button" id="addMoreTeethTextSubcategory" data-id="'+indexTextCategory+'" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>'+
                '</div>'+
                '<button type="button" id="deleteTeethTextCategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>';

            $(".category_text").append(htmlAdd);
            indexTextCategory++;
        });
        $(document).on("click","#addMoreTeethTextSubcategory",function(){
            var index = $(this).attr("data-id");
            var htmlToAppend = '<div class="col-sm-12 set_category_text">'+
                '<input type="text" name="teethTextSubcategory['+index+'][]" placeholder="Subcategory Text" class="form-control cnt" required>'+
                '<button type="button" id="deleteTeethTextSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>';
            $(this).siblings(".set_sub_category_text").append(htmlToAppend);

        });
        $(document).on("click","#deleteTeethTextCategory",function(){
            $(this).parent(".set_category_text").remove();
        });
        $(document).on("click","#deleteTeethTextSubcategory",function(){
            $(this).parent(".set_category_text").remove();
        });

        var indexImageCategory = <?php echo $teethImageCategoryIndex;?>;
        $("#addMoreTeethImageCategory").click(function(){
            var htmlAdd = '<div class="col-sm-12 set_category_image  block">'+
                '<input type="text" name="teethImageCategory['+indexImageCategory+']" placeholder="Category Image" class="form-control cnt" required>'+
                '<div class="col-sm-9 col-sm-offset-3">'+
                '<div class="set_sub_category_image">'+
                '<div class="col-sm-12 set_category_image image_container">'+
                '<input type="text" name="teethImageSubcategory['+indexImageCategory+'][]" placeholder="Subcategory Image" class="image_text form-control cnt" required>'+
                '<input type="file" name="teethImageSubcategoryFile['+indexImageCategory+'][]" class="image_image form-control cnt" required>'+
                '<button type="button" id="deleteTeethImageSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>'+
                '</div>'+
                '<button type="button" id="addMoreTeethImageSubcategory" data-id="'+indexImageCategory+'" class="btn btn-primary btn-xs"  ><i class="fa fa-plus fa-2x"></i></button>'+
                '</div>'+
                '<button type="button" id="deleteTeethImageCategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>';

            $(".category_image").append(htmlAdd);
            indexImageCategory++;
        });
        $(document).on("click","#addMoreTeethImageSubcategory",function(){
            var index = $(this).attr("data-id");
            var htmlToAppend = '<div class="col-sm-12 set_category_image image_container">'+
                '<input type="text" name="teethImageSubcategory['+index+'][]" placeholder="Subcategory Image" class="image_text form-control cnt" required>'+
                '<input type="file" name="teethImageSubcategoryFile['+index+'][]" class="image_image form-control cnt" required>'+
                '<button type="button" id="deleteTeethImageSubcategory" class="btn-remove btn btn-primary btn-xs"  ><i class="fa fa-remove fa-2x"></i></button>'+
                '</div>';
            $(this).siblings(".set_sub_category_image").append(htmlToAppend);

        });
        $(document).on("click","#deleteTeethImageCategory",function(){
            $(this).parent(".set_category_image").remove();
        });
        $(document).on("click","#deleteTeethImageSubcategory",function(){
            $(this).parent(".set_category_image").remove();
        });


        $(document).on("submit","#form_post",function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $btn = $("#btn_update");
            $.ajax({
                url: baseurl+'/app_admin/update_dental_supplier_setting',
                type: 'POST',
                data: formData,
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function (data) {
                    var result = JSON.parse(data);
                    if(result.status = 1)
                    {
                        window.location = baseurl+'/app_admin/list_dental_supplier'
                    }
                    else
                    {
                        alert(result.message);
                    }
                    $btn.button('reset');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


    });

</script>