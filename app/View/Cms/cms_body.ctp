<?php
$login = $this->Session->read('Auth.User');

?>

<div class="col-sm-6">
    <label>Icon Title</label>
    <input type="text" name="icon_title" class="form-control icon_title" value="<?php echo @$data['CmsPage']['title']?>" required>
</div>



<input type="hidden" name="cpi" value="<?php echo !empty($data['CmsPage']['id'])?base64_encode($data['CmsPage']['id']):0; ?>">
<input type="hidden" name="page_category_id" value="<?php echo $page_category_id; ?>">

<?php  if(in_array($page_category_id,array(1,7,6,9,15,8,2,18))){ ?>

    <div class="col-sm-6">
        <label>Page Load Type</label>
        <select name="request_load_type" class="form-control link_type">
            <option value="CONTENT" <?php echo @($data['CmsPage']['request_load_type']=='CONTENT')?'selected':'' ?> >Content</option>
            <option value="URL" <?php echo @($data['CmsPage']['request_load_type']=='URL')?'selected':'' ?>>URL</option>
        </select>
    </div>

    <div class="col-sm-12 link_div" style="display:<?php echo (@$data['CmsPage']['request_load_type']=='URL')?'block':'none'; ?>">
        <label>Enter link URL</label>
        <input type="text" name="url" class="form-control link_input" value="<?php echo @$data['CmsPage']['url']?>">
    </div>

    <div class="content_div" style="display:<?php echo (@$data['CmsPage']['request_load_type']!='URL')?'block':'none'; ?>">
        <?php

        $accept_files  = "image/x-png,image/gif,image/jpeg";
        $category_name_display = "block";
        $sub_title_display = "block";
        $upload_media_display = "block";
        if(in_array($page_category_id,array(1,6,15,8,18))){
            $accept_files  = "image/x-png,image/gif,image/jpeg";
        }else if(in_array($page_category_id,array(7,9)) ){
            $accept_files  = "image/x-png,image/gif,image/jpeg,video/mp4";
        }

        if(in_array($page_category_id,array(1,15,8,18))){
            $category_name_display ="none";
        }

        if(in_array($page_category_id,array(1,8,2,18))){
            $sub_title_display ="none";
        }

        if(in_array($page_category_id,array(2))){
            $upload_media_display ="none";
        }



        foreach ($data['CmsPageBody'] as $key => $value){ ?>

          <div class="separate_section">
        <div class="col-sm-6 text_area_box">
            <label>Description</label>
            <textarea type="text"   rows="5"  editor-height="80px" name="description[]" id="description_<?php echo $key;?>" class="form-control description" > <?php echo @$value['description']; ?></textarea>
        </div>
        <div class="col-sm-6" style="display:<?php echo $category_name_display; ?>;">
            <label>Category Name</label>
            <input type="text" name="category_name[]" class="form-control category_name" value="<?php echo @$value['category_name']; ?>">
        </div>
        <div class="col-sm-6">
            <label>Title</label>
            <input type="text" name="title[]" class="form-control title" value="<?php echo @$value['title']; ?>">
        </div>
        <div class="col-sm-6" style="display:<?php echo $sub_title_display; ?>;">
            <label>Sub Title</label>
            <input type="text" name="sub_title[]"  class="form-control sub_title" value="<?php echo @$value['sub_title']; ?>">
        </div>
        <div class="col-sm-6" style="display: <?php echo $upload_media_display; ?>">
            <label style="display: block;">Image</label>
            <input type="file" accept="<?php echo $accept_files; ?>" name="select_media[]" class="form-control">
            <a title="Remove attached file by click on this button " class="clear_media" href="javascript:void(0);" style="display: <?php echo !empty($data['CmsPageBody'][0]['media'])?'block':'none'; ?>" ><i class="fa fa-trash"></i> </a>
            <input type="hidden" name="media[]" class="form-control" value="<?php echo @$value['media']; ?>">
        </div>
        <?php if($key > 0 ){ ?>
            <a href='javascript:void(0);' class='remove_box'><i class='fa fa-trash'></i></a>
        <?php } ?>
    </div>
    <?php } ?>
    </div>
<?php }else if(in_array($page_category_id,array(10,12,13,17,14,16,5))){ ?>

    <input type="hidden" name="request_load_type" value="URL">

    <div class="col-sm-6 link_div">
        <label>Enter link URL</label>
        <input type="text" name="url" class="form-control link_input" value="<?php echo @$data['CmsPage']['url']?>" required="required">
    </div>
<?php }else if(in_array($page_category_id,array(4))){ ?>

    <div class="col-sm-6">
        <label>Page Load Type</label>
        <select name="request_load_type" class="form-control link_type">
            <option value="CONTENT" <?php echo @($data['CmsPage']['request_load_type']=='CONTENT')?'selected':'' ?> >Content</option>
            <option value="URL" <?php echo @($data['CmsPage']['request_load_type']=='URL')?'selected':'' ?>>URL</option>
        </select>
    </div>

    <div class="col-sm-12 link_div" style="display:<?php echo (@$data['CmsPage']['request_load_type']=='URL')?'block':'none'; ?>">
        <label>Enter link URL</label>
        <input type="text" name="url" class="form-control link_input" value="<?php echo @$data['CmsPage']['url']?>" >
    </div>

    <div class="content_div" style="display:<?php echo (@$data['CmsPage']['request_load_type']!='URL')?'block':'none'; ?>">




          <?php

        $accept_files  = "image/x-png,image/gif,image/jpeg";
        $category_name_display = "block";
        $sub_title_display = "block";

        foreach ($data['CmsPageBody'] as $key => $value){ ?>

            <div class="separate_section">



                  <div class="col-sm-6">
                    <label>Mobile</label>
                    <input type="text" name="title[]" class="form-control title" value="<?php echo @$value['title']; ?>">
                </div>
                <div class="col-sm-6" style="display:<?php echo $sub_title_display; ?>;">
                    <label>Email</label>
                    <input type="email" name="sub_title[]"  class="form-control sub_title" value="<?php echo @$value['sub_title']; ?>">
                </div>
                <div class="col-sm-12" style="display:<?php echo $category_name_display; ?>;">
                    <label>Website Url</label>
                    <input type="text" name="field2[]" class="form-control category_name" value="<?php echo @$value['field2']; ?>">
                </div>


                <div class="col-sm-12" style="display:<?php echo $category_name_display; ?>;">
                    <label>Enter Address</label>
                    <input type="text" name="description[]" class="form-control location-region-picker" id="<?php echo date('Ymdhis').rand(99,99999); ?>" value="<?php echo @$value['description']; ?>">
                </div>

                <input type="hidden" name="field1[]" class="form-control palace_id" value="<?php echo @$value['field1']; ?>">


            </div>
        <?php } ?>


        <?php echo $this->Html->script(array('jquery.location-region-picker.js')); ?>
        <script>
            $(function(){
                $(".location-region-picker").LocationRegionPicker({
                    'google_api_key' : '<?php echo $google_api_key; ?>',
                    'types': ["address","place"]
                });
                $(document).off('input','.location-region-picker');
                $(document).on('input','.location-region-picker',function(){
                    $('.palace_id').val("");
                })


            })
        </script>



    </div>

<?php } ?>





<div class="col-sm-12 button_box" style="text-align: right; margin-top: 15px;">

    <?php if(in_array($page_category_id,array(7,6,9,15,8,2))) { ?>
        <button type="button" class="btn-danger btn add_more_button"><i class="fa fa-plus"></i> Add More</button>
    <?php } ?>

    <button type="button" onclick="window.history.back();" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back</button>
    <button type="reset" class="btn-warning btn reset_button"><i class="fa fa-refresh"></i> Reset</button>

    <button type="submit" class="btn-success btn save_template_btn" ><i class="fa fa-save"></i> Save</button>
</div>

<style>
    .button_box button{
        min-width: 100px;
    }
    .clear_media, .clear_media:hover{
        float: right;
        margin-top: -32px;
        background: red;
        color: #fff;
        width: 30px;
        height: 30px;
        right: 17px;
        padding: 3px 8px;
        font-size: 22px;
        /* z-index: 9999999999999; */
        position: absolute;
    }
    .separate_section{
        border: 2px solid #d6d6d6;
        margin: 5px 15px;
        padding: 6px 0px;
        float: left;
        border-radius: 4px;
    }
    .remove_box, .remove_box:hover{
        position: absolute;
        float: right;
        border: 1px solid;
        font-size: 18px;
        padding: 5px 7px;
        border-radius: 27px;
        width: 30px;
        height: 30px;
        margin-top: -17px;
        right: 25px;
        background: #03A9F5;
        color: #fff;
    }
</style>
<script>
    $(document).ready(function(){
        $(".channel_tap a").removeClass('active');
        $("#v_add_cms").addClass('active');

        var iframe_url = "<?php echo $iframe_url; ?>";
        if(iframe_url!=''){
            $(".prev_iframe").attr('src',iframe_url).show();
        }else{
            $(".prev_iframe").hide();
        }


        function updateAutocomplete(){
            var availableTags = [];
            $("input[name='category_name[]']").each(function(){
                var value =$(this).val();
                if(value!=''){
                   if (availableTags.indexOf(value) === -1){
                        availableTags.push(value);
                    }

                }
            });

            if(availableTags.length > 0){
                $( "input[name='category_name[]']" ).autocomplete({
                    source: availableTags,
                    delay: 100,
                    classes: {
                        "ui-autocomplete": "highlight"
                    },
                    select: function( event, ui ) {
                        event.preventDefault();
                        var name = ui.item.value.split('-');
                        $("#refferer_by_name").val(name[0]);
                        if(name[1]){
                            $("#refferer_by_mobile").val(name[1]);
                        }else{
                            $("#refferer_by_mobile").val('');
                        }

                    }
                });
            }
        }


        updateAutocomplete();


        $(document).off("blur","input[name='category_name[]']");
        $(document).on("blur","input[name='category_name[]']",function(){
            updateAutocomplete();
        });




        $(document).off("click",".add_more_button");
        $(document).on("click",".add_more_button",function(){


            $(".content_div").append($(".separate_section:first").clone());
            $(".separate_section:last input").val('');
            $(".separate_section:last text").val('');
            var height = $(".separate_section:last").find('.description').attr('editor-height');
            $(".separate_section:last .text_area_box").html('');
            $(".separate_section:last .clear_media").hide();
            $(".separate_section:last").append("<a href='javascript:void(0);' class='remove_box'><i class='fa fa-trash'></i></a>");
            var length = $('.separate_section').length;
            var text_area_id = "description_"+length;
            var text_area = '<label>Description</label><textarea type="text"   rows="5"  editor-height="'+height+'" name="description[]" id="'+text_area_id+'" class="form-control description" ></textarea>';
            $(".separate_section:last .text_area_box").html(text_area);
            addCkEditor(text_area_id,height);
            updateAutocomplete();


        });

        $(document).off("click",".remove_box");
        $(document).on("click",".remove_box",function(){

           $(this).closest(".separate_section").remove();

        });



        $(document).off("change",".link_type");
        $(document).on("change",".link_type",function(){


            if($(this).val()=="URL"){
                $(".content_div").val('').hide();
                $(".link_div").show();
                $(".add_more_button").hide();
                $(".link_input").attr('required','required');

            }else{
                $(".content_div").show();
                $(".link_div").val('').hide();
                $(".add_more_button").show();
                $(".link_input").removeAttr('required');
            }
        });

        if($(".description").length >0){
            $('.description').each(function(){
                var height = $(this).attr('editor-height');
                addCkEditor($(this).attr('id'),height);
            });

        }


        function addCkEditor(id,height){
            CKEDITOR.replace( id,{
                toolbarGroups: [
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                    {name: 'styles', groups: ['styles']},
                    {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
                ],
                removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
                height:height,
                autoParagraph :false,
                enterMode : CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_P
            } );

        }

        $(document).off('submit','.save_template_form');
        $(document).on('submit','.save_template_form',function(e){
           e.preventDefault();

           var reset_btn = $(".reset_button");
           var button = $(".save_template_btn");
            $(button).button({loadingText: "<i class='fa fa-spinner fa-pulse fa-fw'></i> Saving..."});
            $(button).button('loading');
            var formData = new FormData($(this)[0]);
            $.ajax({
                type:'POST',
                url: baseurl+"cms/save_template",
                data:formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend:function(){

                },
                success:function(response){

                    $(button).button('reset');
                    var response = JSON.parse(response);
                    if(response.status==1){
                        $.alert(response.message);
                        $(".icon_dropdown").trigger('change');
                        $(".prev_iframe").attr('src',response.url).show();
                    }else{
                        $.alert(response.message);
                    }

                },
                error: function(data){

                    $(button).button('reset');
                    $.alert("Sorry something went wrong on server.");
                }
            });


        });



        $(document).off('change','input[name="select_media[]"]');
        $(document).on('change','input[name="select_media[]"]',function(e){
            if($(this).val()){
                readURL(this);
            }
        });


        $(document).off('click','.clear_media');
        $(document).on('click','.clear_media',function(e){
            $(this).parent().find('input[name="media[]"]').val('');
            $(this).parent().find('input[name="select_media[]"]').val('');
            $(this).hide();
        });

        function invalidFileAction(input,message){
            $(input).val('');
            $(input).parent().find('.clear_media').trigger('click');
            $.alert(message);

        }
        function readURL(input) {
            var file_obj = input;
            if (input.files && input.files[0]) {
                var ext_obj = input.files[0].type.split("/");
                var page_category_id  = $("input[name='page_category_id']").val();
                var type = ext_obj[0].toUpperCase();
                if(type != 'IMAGE' && ( page_category_id==1 || page_category_id ==7 || page_category_id ==15) ){
                    $(input).parent().find('.clear_media').show();
                    invalidFileAction(input,"Please upload image file only");
                }else if( (type != 'IMAGE' && type != 'VIDEO') && ( page_category_id==6 && page_category_id ==9 ) ){
                    invalidFileAction(input,"Please upload image or video file only");
                }else{
                    $(input).parent().find('.clear_media').show();
                }
            }
        }





    });



</script>
