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


                    <!--left sidebar-->

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
                            <h3 class="screen_title">Doctor's Blog</h3>

                            <?php
                            $login = $this->Session->read('Auth.User');
                            $thin_app_id = $login['User']['thinapp_id'];
                            ?>

                            <div class="body_cnt"   data-channel="<?php echo base64_encode($this->AppAdmin->default_channel_data($thin_app_id)['id']); ?>">
                                <div class="">
                                    <?php echo $this->Session->flash('success'); ?>
                                    <?php echo $this->Session->flash('error'); ?>

                                    <?php echo $this->Form->create('Message',array('method'=>'post','class'=>'form-horizontal msg_frm','id'=>'sub_frm')); ?>

                                    <div class="modal-header">
                                        <h4 class="modal-title">Doctor's Blog</h4>
                                    </div>
                                    <div class="modal-body">



                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <?php echo $this->Form->input('title',array('type'=>'text','placeholder'=>'Title','label'=>false,'id'=>'title','class'=>'form-control title','required'=>'required')); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <?php echo $this->Form->input('message',array('type'=>'textarea','placeholder'=>'Write your message here','label'=>false,'id'=>'msg_box','class'=>'form-control msg_box')); ?>
                                            </div>
                                        </div>

                                        <div class="form-group">

                                            <div class="col-sm-6 two_div">


                                                <div class="col-sm-12">
                                                    <label>Message Type</label>
                                                    <?php $type_array = array(
                                                        'TEXT'=>'TEXT',
                                                        'IMAGE'=>'IMAGE',
                                                        'VIDEO'=>'VIDEO',
                                                        'AUDIO'=>'AUDIO'


                                                    );?>
                                                    <?php echo $this->Form->input('message_type',array('type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control cnt')); ?>
                                                </div>
                                                <div class="col-sm-10">
                                                    <label>Upload Media</label>
                                                    <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control browse_file')) ?>
                                                    <?php echo $this->Form->input('message_file_url',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                                                    <?php echo $this->Form->input('original_filename',array('type'=>'hidden','label'=>false,'class'=>'original_file')) ?>
                                                    <div class="file_error"></div>
                                                    <div class="file_success"></div>

                                                </div>
                                                <div class="col-sm-2">
                                                    <label>&nbsp;</label>
                                                    <div class="submit">
                                                        <button type="button" class="upload_media btn btn-success" href="javascript:void(0);"><i class="fa fa-upload"></i></button>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="col-sm-6 two_div">

                                                <div class="media_container">

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <div class="show_msg_text"></div>
                                        <?php echo $this->Form->submit('Post Message',array('class'=>'Btn-typ3','type'=>'submit')); ?>
                                    </div>
                                    <?php echo $this->Form->end(); ?>
                                </div>
                            </div>

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


                                    // CKEDITOR.replace( 'msg_box');

                                    var upload_file =false;
                                    $(document).on('change','.browse_file',function(e){
                                        if($(this).val()){
                                            readURL(this);
                                        }else{
                                            upload_file = false;
                                        }
                                    });

                                    function readURL(input) {
                                        console.log(input.files);
                                        if (input.files && input.files[0]) {

                                            var ext_obj = input.files[0].type.split("/");
                                            var type = ext_obj[0];
                                            var ext = ext_obj[1];
                                            var reader = new FileReader();
                                            reader.onload = function (e) {
                                                var url = e.target.result;
                                                if(type =="image"){
                                                    $('.media_container').html("<img src='"+url+"' >");
                                                }else if(type =="video"){
                                                    var vid = '<video class="video" controls="controls"><source src="'+url+'" type="video/'+ext+'">Your browser does not support the video tag.</video>';
                                                    $('.media_container').html(vid);
                                                }

                                                upload_file= true;
                                            }
                                            reader.readAsDataURL(input.files[0]);
                                        }
                                    }


                                    $(document).on('submit','.msg_frm',function(e){
                                        e.preventDefault();

                                        if(upload_file==true){
                                            $.alert('Please chose and upload file based on message type ');
                                            return false;
                                        }

                                        var description_len = $(".msg_box").val().length;
                                        if($(".msg_box").val()==""){
                                            $(".msg_box").css('border-color','red');
                                        }else if(description_len > 4000 ){
                                            $.alert("Please upload minimum 4000 characters. You have enter only "+description_len+" characters. ");
                                            return false;
                                        }else{
                                            var btn = $(this).find('[type=submit]');
                                            var id =  $(".body_cnt").attr("data-channel");
                                            $.ajax({
                                                url:baseurl+"/app_admin/add_message_ajax",
                                                type:'POST',
                                                data:{
                                                    chn_id:id,
                                                    Message:$(".msg_frm").serialize()
                                                },
                                                beforeSend:function(){
                                                    btn.button('loading').val('Message sending...');
                                                },
                                                success:function(res){
                                                    var response = JSON.parse(res);
                                                    if(response.status==1){
                                                        $(".show_msg_text").html(response.message).css('color','green');
                                                        var inter = setInterval(function(){
                                                            window.location.reload();
                                                            $(".show_msg_text").hide();


                                                            clearInterval(inter);
                                                        },1500);
                                                    }else{
                                                        $(".show_msg_text").html(response.message).css('color','red');
                                                    }
                                                    btn.button('reset');
                                                },
                                                error:function () {
                                                    btn.button('reset');
                                                    $(".show_msg_text").html("Sorry something went wrong on server.").css('color','red');
                                                }
                                            })
                                        }

                                    })


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






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>





