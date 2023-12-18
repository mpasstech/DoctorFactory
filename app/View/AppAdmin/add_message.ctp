<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>My Channel</h2> </div>
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

                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 custom_form_box">
                        <?php echo $this->element('app_admin_inner_tab_channel'); ?>

                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('Message',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>My Post here</label>
                                    <?php echo $this->Form->input('message',array('type'=>'textarea','placeholder'=>'Write your message here','label'=>false,'id'=>'message_editor','class'=>'form-control cnt')); ?>
                                </div>
                            </div>
                            <div class="form-group">


                                <div class="col-sm-4">
                                    <label>Channel for post message</label>
                                    <?php $type_array = $this->AppAdmin->getChannelList($login['User']['id'],$login['User']['thinapp_id']); ?>
                                    <?php echo $this->Form->input('channel_id',array('type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <label>Messag Type</label>
                                    <?php $type_array = array(
                                        'TEXT'=>'TEXT',
                                        'IMAGE'=>'IMAGE',
                                        'VIDEO'=>'VIDEO',
                                        'AUDIO'=>'AUDIO'
                                    );?>
                                    <?php echo $this->Form->input('message_type',array('type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type')); ?>
                                </div>


                                <div class="col-sm-4">
                                    <label>Upload Media</label>
                                    <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control f_inp')) ?>
                                    <?php echo $this->Form->input('message_file_url',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                                    <?php echo $this->Form->input('original_filename',array('type'=>'hidden','label'=>false,'class'=>'original_file')) ?>
                                    <div class="file_error"></div>
                                    <div class="file_success"></div>

                                </div>

                                <div class="col-sm-2">
                                    <label>&nbsp;</label>
                                    <?php echo $this->Form->submit('Upload',array('type'=>'button','class'=>'upload_media btn btn-success')); ?>

                                </div>


                            </div>





                            <div class="form-group">


                                <div class="col-sm-3 pull-right">
                                    <?php echo $this->Form->submit('Post',array('class'=>'Btn-typ5','id'=>'signup')); ?>
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

        var upload = true;

        $(".channel_tap a").removeClass('active');
        $("#v_add_message").addClass('active');

        CKEDITOR.replace( 'message_editor', {
            toolbar: [
                [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],
                { name: 'basicstyles', items: [ 'Bold', 'Italic' ] },
                { name: 'styles', items: [ 'Styles', 'Format' ] },
                { name: 'paragraph', items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] }
            ]
        });

        $(document).on('click','.upload_media',function(e){

         if(upload == true){
             var formData = new FormData($("#sub_frm")[0]);
             var filename = $('.f_inp').val().split('\\').pop();
                var $btn = $(this);
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/upload_media",
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $(".file_error, .file_success").html("");
                        $btn.button('loading').val('Wait..')
                    },
                    success:function(data){
                        data = JSON.parse(data);
                        $btn.button('reset');

                        if(data.status==1){
                            $(".image_box").val(data.url);
                            $(".original_file").val(filename);
                            $(".channel_img").attr("src",data.url);
                            $(".file_success").html(data.message);
                        }else{
                            $(".file_error").html(data.message);
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });
            }



        })
    });
</script>






