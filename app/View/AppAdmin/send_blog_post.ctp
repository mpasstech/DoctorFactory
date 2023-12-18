<div class="modal fade"  style="width:1200px;margin:2% auto; overflow: auto;" id="send_blog_modal" >
    <?php
    $login = $this->Session->read('Auth.User');
    $thin_app_id = $login['User']['thinapp_id'];
    ?>


    <style>


        .image_box .box img{
            width: 100px;
            height: 100px;
            border-radius: 10px;
        }
        .image_box{
            float: left;
            display: inline-block;
            width: 20%;
        }
        .browse_file{
            display: none !important;
        }
        .box{
            text-align: center;
        }
        .image_container{
            float: left;
            width: 100%;
            text-align: center;
        }
        .remove_image{

        }
        #msg_box{
            min-height: 240px;
        }
        .video_box{
            width: 100%;
            display: block;
            float: left;
        }
        .append_video, .append_audio{
            width: 100%;
            float: left;
            height: auto;
            margin-top:10px;
        }

        .append_video video{
            height: 240px !important;
        }

        .video_container, .image_container, .audio_container{
            min-height: 280px !important;
            max-height: 280px !important;
            display: block;
            float: left;
            width: 100%;
        }
        .append_video video, .append_audio audio{
            width: 100%;
        }
        .upload_video_btn, .upload_audio_btn, .remove_video, .remove_audio{
            float: left;
            width: 30%;
        }


    </style>

    <div class="modal-content">
        <?php echo $this->Form->create('Message',array('method'=>'post','class'=>'form-horizontal msg_frm','id'=>'post_blog_form', 'enctype'=>"multipart/form-data")); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-align: center;font-weight: bold;"><?php echo $title; ?></h4>
        </div>
        <div class="modal-body body_cnt" data-channel="<?php echo base64_encode($this->AppAdmin->default_channel_data($thin_app_id)['id']); ?>">

            <?php echo $this->Form->input('chn_id',array('type'=>'hidden','required'=>'required','value'=>base64_encode($this->AppAdmin->default_channel_data($thin_app_id)['id']))); ?>
            <?php echo $this->Form->input('data_id',array('id'>'data_id','type'=>'hidden','required'=>'required','value'=>$message_id)); ?>


            <div class="row">
                <div class="col-sm-6">
                    <div class="col-sm-12">
                        <label>Message Title</label>
                        <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Title','label'=>false,'value'=>@$message_data['title'],'id'=>'title','class'=>'form-control title','required'=>'required')); ?>
                    </div>
                    <div class="col-sm-12">
                        <label>Message Type</label>
                        <?php $changed = !isset($message_data)?'changed':''; $type_array = array(
                            'TEXT'=>'TEXT',
                            'IMAGE'=>'IMAGE',
                            'VIDEO'=>'VIDEO',
                            'AUDIO'=>'AUDIO'
                        );?>
                        <?php echo $this->Form->input('message_type',array('type'=>'select','label'=>false,'value'=>@$message_data['message_type'],'options'=>$type_array,'class'=>'form-control '.$changed)); ?>
                    </div>

                    <div class="col-sm-12 image_container">
                        <?php

                            $file_urls = @$message_data['message_file_url'];
                            $path = $default_image = "";
                            if(!empty($file_urls)){
                                $file_urls = explode(",",$file_urls);
                            }
                            for($loop=0;$loop<5; $loop++){
                            $path = '';
                            $default_image= Router::url('/images/upload_image.png',true);
                            if(!empty($message_data)) {
                                if ($message_data['multiple_image'] =="YES") {
                                    if(isset($file_urls[$loop])){
                                        $path = $file_urls[$loop];
                                        $default_image = explode("##",$file_urls[$loop]);
                                        $default_image =$default_image[2];
                                    }
                                }else{
                                    if(isset($file_urls[$loop])){

                                        $default_image = $message_data['message_file_url'];
                                        if(!empty($message_data) && $message_data['message_type'] == "IMAGE"){
                                            if(empty($message_data['original_filename'])){
                                                $name = explode("/", $message_data['message_file_url']);
                                                $name = end($name);
                                            }else{
                                                $name = $message_data['original_filename'];
                                            }
                                            $path = "IMAGE##".$name."##".$message_data['message_file_url'];
                                        }


                                    }

                                }
                            }

                            ?>
                            <div class="image_box">

                                <div class="box">

                                    <input  type="file" name="upload_<?php echo $loop;?>" accept="image/*" class="browse_file">

                                    <input type="hidden" class="last_file" value="<?php echo $path; ?>" name="last_files[]" />
                                    <img  src="<?php echo $default_image; ?>" class="fa fa-upload sample_box" />
                                    <a style="display: <?php echo !empty($path)?'block':'none'; ?>" default="<?php echo Router::url('/images/upload_image.png',true); ?>" class="remove_image" href="javascript:void(0);" >Remove Image</a>

                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="col-sm-12 video_container">

                        <div class="video_box">
                            <input type="file" name="upload_v" accept="video/*" style="display: none;" class="video_browse_file">
                            <?php
                                $data_url = "";
                                if(!empty($message_data) && $message_data['message_type'] == "VIDEO"){
                                    if(empty($message_data['original_filename'])){
                                        $name = explode("/", $message_data['message_file_url']);
                                        $name = end($name);
                                    }else{
                                        $name = $message_data['original_filename'];
                                    }
                                    $data_url = "VIDEO##".$name."##".$message_data['message_file_url'];
                                }
                            ?>

                            <input type="hidden" class="last_file" value="<?php echo $data_url; ?>" name="last_files[]" />
                            <button type="button" class="btn btn-success upload_video_btn"><i class="fa fa-upload"></i> Upload Video</button>
                            <button type="button" style="display: <?php echo !empty($message_data['message_type'])?'block':'none'; ?>"  class="btn btn-danger remove_video"  ><i class="fa fa-trash"></i> Remove Video</button>
                            <div class="append_video">
                                <?php if(!empty($message_data['message_file_url']) && $message_data['message_type'] == "VIDEO"){
                                    $ext = explode('.',$message_data['message_file_url']);;
                                    $ext = end($ext);

                                    ?>
                                      <video class="video" controls="controls"><source src="<?php echo $message_data['message_file_url']; ?>" type="video/<?php echo $ext; ?>">Your browser does not support the video tag.</video>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 audio_container">
                        <div class="audio_box">
                            <?php
                            $data_url = "";
                            if(!empty($message_data) && $message_data['message_type'] == "AUDIO"){
                                if(empty($message_data['original_filename'])){
                                    $name = explode("/", $message_data['message_file_url']);
                                    $name = end($name);
                                }else{
                                    $name = $message_data['original_filename'];
                                }
                                $data_url = "AUDIO##".$name."##".$message_data['message_file_url'];
                            }
                            ?>
                            <input type="file" name="upload_a" accept="audio/*" style="display: none;" class="audio_browse_file">
                            <input type="hidden" class="last_file" value="<?php echo $data_url; ?>" name="last_files[]" />
                            <button type="button" class="btn btn-success upload_audio_btn"><i class="fa fa-upload"></i> Upload Audio</button>
                            <button type="button" style="display: <?php echo !empty($message_data['message_type'])?'block':'none'; ?>" class="btn btn-danger remove_audio"  ><i class="fa fa-trash"></i> Remove Audio</button>
                            <div class="append_audio">
                                <?php if(!empty($message_data['message_file_url']) && $message_data['message_type'] == "AUDIO"){
                                    $ext = explode('.',$message_data['message_file_url']);;
                                    $ext = end($ext);
                                    ?>
                                    <audio class="audio" controls="controls"><source src="<?php echo $message_data['message_file_url']; ?>" type="audio/<?php echo $ext; ?>">Your browser does not support the audio tag.</audio>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    <div class="col-sm-12">
                        <label>Write your message </label>
                        <?php echo $this->Form->input('message',array('type'=>'textarea','placeholder'=>'Write your message here','value'=>@$message_data['message'],'label'=>false,'id'=>'msg_box', 'row'=>'10', 'class'=>'form-control msg_box','required'=>false)); ?>
                    </div>
                    <div class="col-sm-12" style="text-align: right;">
                        <button type="button" class="btn-warning btn modal_dismis" data-dismiss="modal">Cancel</button>
                        <?php if(empty($message_data)){ ?>
                        <button type="reset" class="btn-success btn" >Reset</button>
                        <?php } ?>
                        <button type="submit" class="btn-info btn send" >Post</button>
                    </div>
                </div>

            </div>
        </div>

       <?php echo $this->Form->end(); ?>



        <style>
            .upload_media{
                margin: 0px -20px !important;
                padding: 13px 12px;
            }
            .media_container img{position: relative;width: 100%; height: 230px;}
            .media_container video{position: relative;width: 100%;height: 230px;}

            .two_div {
                border: 1px solid #dcdbdb;
                height: 240px;
                padding: 2px 2px;
            }
        </style>
        <script>
            $(document).ready(function(){

                function clear(){
                    $(".video_container, .image_container, .audio_container").hide();
                    if($('#MessageMessageType').hasClass('changed')){
                        $(".remove_image, .remove_video, .remove_audio").trigger('click');
                    }
                }

                $(document).off('reset','#post_blog_form');
                $(document).on('reset','#post_blog_form',function(e){
                    clear();
                });


                $(document).off('change','#MessageMessageType');
                $(document).on('change','#MessageMessageType',function(e){
                    clear();
                    $('#MessageMessageType').addClass('changed');
                    if($(this).val() == "IMAGE"){
                        $(".image_container").show();
                    }else if($(this).val() == "VIDEO"){
                        $(".video_container").show();
                    }else if($(this).val() == "AUDIO"){
                        $(".audio_container").show();
                    }


                });

                setTimeout(function () {
                    $('#MessageMessageType').trigger('change');
                },5)




                $(document).off('click','.sample_box');
                $(document).on('click','.sample_box',function(){
                    $(this).closest('.box').find('.browse_file').trigger('click');
                });

                $(document).off('click','.remove_image');
                $(document).on('click','.remove_image',function(){
                    $(this).closest('.box').find('.sample_box').attr('src',$(this).attr('default'));
                    $(this).closest('.box').find('.browse_file').val(null);
                    $(this).closest('.box').find('.last_file').val('');
                    $(this).hide();
                });


                $(document).off('click','.upload_video_btn');
                $(document).on('click','.upload_video_btn',function(){
                    $(this).closest('.video_box').find('.video_browse_file').trigger('click');
                });
                $(document).off('click','.remove_video');
                $(document).on('click','.remove_video',function(){
                    $(".video_browse_file").val(null);
                    $(this).closest('.video_box').find('.last_file').val('');
                    $(".append_video").html('');
                    $(this).hide();
                });


                $(document).off('click','.upload_audio_btn');
                $(document).on('click','.upload_audio_btn',function(){
                    $(this).closest('.audio_box').find('.audio_browse_file').trigger('click');
                });
                $(document).off('click','.remove_audio');
                $(document).on('click','.remove_audio',function(){
                    $(".audio_browse_file").val(null);
                    $(this).closest('.audio_box').find('.last_file').val('');
                    $(".append_audio").html('');
                    $(this).hide();
                });


                var upload_file =false;
                $(document).off('change','.browse_file, .video_browse_file, .audio_browse_file ');
                $(document).on('change','.browse_file, .video_browse_file, .audio_browse_file',function(e){
                    if($(this).val()){
                        readURL(this);
                    }else{
                        upload_file = false;
                    }
                });


                function readURL(input) {
                    var file_obj = input;
                    if (input.files && input.files[0]) {
                        var ext_obj = input.files[0].type.split("/");
                        var type = ext_obj[0];
                        var ext = ext_obj[1];
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var url = e.target.result;

                            if(type =="image"){
                                $(file_obj).closest('.box').find('.sample_box').attr('src',url);
                                $(file_obj).closest('.box').find('.last_file').val('');
                                $(file_obj).closest('.box').find('.remove_image').show();

                            }else if(type =="video"){





                                var vid = '<video class="video" controls="controls"><source src="'+url+'" type="video/'+ext+'">Your browser does not support the video tag.</video>';
                                $('.append_video').html(vid);
                                $('.video_box').find('.last_file').val('');
                                $('.remove_video').show();


                            }else if(type =="audio"){
                                var vid = '<audio class="audio" controls="controls"><source src="'+url+'" type="audio/'+ext+'">Your browser does not support the audio tag.</audio>';
                                $('.append_audio').html(vid);
                                $('.audio_box').find('.last_file').val('');
                                $('.remove_audio').show();

                            }else{
                                $(file_obj).val(null);
                            }




                        }
                        reader.readAsDataURL(input.files[0]);

                    }
                }


                $(document).off('submit','#post_blog_form');
                $(document).on('submit','#post_blog_form',function(e){
                    e.preventDefault();

                    var post_message = true;
                    var message="";
                    var type = $('#MessageMessageType').val();
                    if(type =='VIDEO' && ( !$(".video_browse_file").val() && !$(".video_box .last_file").val() )  ){
                        post_message = false;
                        message = "Please select video file";
                    }else if(type =='AUDIO' && ( !$(".audio_browse_file").val() && !$(".audio_box .last_file").val())){
                        post_message = false;
                        message = "Please select audio file";
                    }else if(type=="IMAGE"){
                        var total_files = 0;
                        $(".browse_file").each(function (index,value) {
                            if($(this).val() || $(this).closest('.box').find(".last_file").val() ){ total_files++; }
                        });
                        if(total_files == 0){
                            post_message = false;
                            message = "Please select at least one image file";
                        }

                    }else if(type=="TEXT" && !$("#msg_box").val()){
                        post_message = false;
                        message = "Please type your message";
                    }else if(type=="TEXT" && $("#msg_box").val()){

                        var description_len = $("#msg_box").val().length;
                        if($("#msg_box").val()==""){
                            message = "Please type your message";
                            post_message = false;
                        }else if(description_len > 4000 ){
                            message = "Please upload minimum 4000 characters. You have enter only "+description_len+" characters. ";
                            post_message = false;
                        }

                    }


                    if(post_message ===true){

                        var btn = $(this).find('[type=submit]');
                        var id =  $(".body_cnt").attr("data-channel");

                        $.ajax({
                            url:baseurl+"/app_admin/add_message_ajax",
                            processData: false,
                            contentType: false,
                            type:'POST',
                            data:new FormData(this),
                            beforeSend:function(){
                                var text = "Posting...";
                                if($("#data_id").val()){
                                    text = "Updating...";
                                }
                                btn.button('loading').text(text);
                            },
                            success:function(res){
                                var response = JSON.parse(res);
                                if(response.status==1){
                                    $("#send_blog_modal").modal('hide');
                                    window.location.reload();
                                }else{
                                    $.alert(response.message);
                                }
                                btn.button('reset');
                            },
                            error:function () {
                                btn.button('reset');
                                $.alert("Sorry something went wrong on server.").css('color','red');
                            }
                        })
                    }else{
                        $.alert(message);
                    }

                });


                $(document).on('click','.upload_media',function(e){


                    var formData = new FormData($("#sub_frm")[0]);
                    var filename = $('.browse_file').val().split('\\').pop();
                    var $btn = $(this).find('i');
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/upload_media",
                        data:formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        beforeSend:function(){
                            $(".file_error, .file_success").html("");
                            $btn.button('loading').html('').attr('class',"fa fa-circle-o-notch fa-spin");

                        },
                        success:function(data){
                            data = JSON.parse(data);
                            $btn.button('loading').html('').attr('class',"fa fa-upload");
                            $btn.button('reset');
                            if(data.status==1){
                                $(".image_box").val(data.url);
                                $(".original_file").val(filename);
                                $(".channel_img").attr("src",data.url);
                                $(".file_success").html(data.message);
                                upload_file= false;
                            }else{
                                $(".file_error").html(data.message);
                            }
                        },
                        error: function(data){
                            $btn.button('loading').html('').attr('class',"fa fa-upload");
                            $(".file_error").html("Sorry something went wrong on server.");
                        }
                    });




                })


            });
        </script>
    </div>


</div>

