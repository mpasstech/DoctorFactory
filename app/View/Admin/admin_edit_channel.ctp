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
                    <?php echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <?php echo $this->element('admin_inner_tab_channel'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->Session->flash('success'); ?>
                            <?php echo $this->Session->flash('error'); ?>
                            <?php
                                $path = Router::url('/images/channel-icon.png');
                                if(!empty($post['Channel']['image'])){
                                    $path = $post['Channel']['image'];
                                }
                            ?>

                            <div class="channel_img_div"><img src="<?php echo $path; ?>"> </div>

                            <?php echo $this->Form->create('Channel',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Channel Name</label>
                                    <?php echo $this->Form->input('channel_name',array('type'=>'text','placeholder'=>'Channel name','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label>Channel Description</label>
                                    <?php echo $this->Form->input('channel_desc',array('type'=>'textarea','placeholder'=>'Channel description','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>                                </div>
                            </div>



                            <div class="form-group">

                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('status',array('type'=>'select','label'=>false,'options'=>array('Y'=>'Active','N'=>'Inactive'),'class'=>'form-control cnt',($post['Channel']['is_searchable']=='Y')?"checked='checked'":'')); ?>
                                </div>


                                <div class="col-sm-6">

                                    <?php echo $this->Form->input('is_searchable',array('value'=>'Y','type'=>'hidden','label'=>false)); ?>
                                    <?php echo $this->Form->input('is_publish_mbroadcast',array('value'=>'Y','type'=>'hidden','label'=>false)) ?>

                                    <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control')) ?>
                                    <?php echo $this->Form->input('image',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                                    <div class="file_error"></div>
                                    <div class="file_success"></div>

                                </div>

                                <div class="col-sm-2">

                                    <?php echo $this->Form->submit('Upload',array('type'=>'button','class'=>'upload_media btn btn-success')); ?>

                                </div>

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



<div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <?php echo $this->Form->create('form',array( 'class'=>'form','id'=>'form')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Post Message</h4>
            </div>
            <div class="modal-body">

                    <div class="form-group">
                        <?php echo $this->Form->input('reply', array('type'=>'textarea','label'=>false,'required'=>false,'placeholder'=>'Reply here','class'=>'form-control input-lg ped','id'=>'reply','style'=>(array('box-shadow'=>'0px 0px 0px','border-radius'=>'0px','border'=>'0px none')))); ?>
                        <div class="show_err"></div>
                    </div>

                <div class="form-group">
                    <?php echo $this->Form->input('channel_list', array('type'=>'select','label'=>false,'required'=>false,'options'=>$this->Custom->getChannelList($login['User']['thinapp_id']),'class'=>'form-control input-lg ped','id'=>'reply','style'=>(array('box-shadow'=>'0px 0px 0px','border-radius'=>'0px','border'=>'0px none')))); ?>
                    <div class="show_err"></div>
                </div>

            </div>
            <div class="modal-footer">
                <?php echo $this->Form->submit('Post Message',array('class'=>'Btn-typ3','id'=>'signup')); ?>
            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

</div>





<script>
    $(document).ready(function(){


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');

    $(document).on('click','.post_message',function(e){
        $("#reply").val('');
        $(".show_err").html('');;
        $("#myModal").modal('show').attr("mob",$(this).attr('data-num'));

    })


        $(document).on('click','.upload_media',function(e){


            var formData = new FormData($("#sub_frm")[0]);
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"admin/admin/upload",
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




        })

    });
</script>






