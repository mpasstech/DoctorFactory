<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Channel List</h2> </div>
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



                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'admin','action' => 'search_channel'),'admin'=>true)); ?>


                            <div class="form-group">
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Channel name', 'label' => 'Search by Name', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'channel')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>


                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($channel)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Icon</th>
                                        <th>Channel Name</th>

                                        <th>Channle Type</th>
                                        <th>Description</th>
                                        <!--th>Subscribes</th-->
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($channel as $key => $list){
                                        if(empty($list['Channel']['channel_image'])){
                                            $list['Channel']['channel_image'] =Router::url('/images/channel-icon.png',true);
                                        }
                                        ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                           <td><img class="channel_icon_list" src="<?php echo $list['Channel']['channel_image'];?>"></td>
                                        <td><?php echo $list['Channel']['channel_name']; ?></td>
                                        <td><?php echo ucfirst($list['Channel']['channel_status']); ?></td>
                                        <td><?php echo $list['Channel']['channel_desc']; ?></td>
                                        <!--td><?php echo $this->Custom->total_subscribe($list['Channel']['id']); ?></td-->
                                        <td>
                                            <div class="action_icon" style="display:flex;">
                                                <?php
                                                echo $this->Html->link('', 'javascript:void(0);',
                                                    array('data-channel' =>base64_encode($list['Channel']['id']),'class' => 'fa fa-envelope fa-2x post_message', 'title' => 'Post new message'));
                                                ?>
                                            &nbsp;
                                            <?php

                                            echo $this->Html->link('', Router::url('/admin/admin/edit_channel/',true).base64_encode($list['Channel']['id']),
                                                array('class' => 'fa fa-edit fa-2x', 'title' => 'View Channel'));

                                            ?>
                                                &nbsp;
                                            <?php
                                            echo $this->Html->link('', Router::url('/admin/admin/view_channel/',true).base64_encode($list['Channel']['id']),
                                                array('class' => 'fa fa-eye fa-2x', 'title' => 'View Channel'));


                                            ?>
                                                </div>

                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>
                            </div>

                            </div>
                        </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>You have no channels</h2>
                                </div>
                            <?php } ?>
                            <div class="clear"></div>
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
            <?php echo $this->Session->flash('success'); ?>
            <?php echo $this->Session->flash('error'); ?>

            <?php echo $this->Form->create('Message',array('method'=>'post','class'=>'form-horizontal msg_frm','id'=>'sub_frm')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Post Message</h4>
            </div>
            <div class="modal-body">



                <div class="form-group">
                    <div class="col-sm-12">
                        <label>My Post here</label>
                        <?php echo $this->Form->input('message',array('type'=>'textarea','placeholder'=>'Write your message here','label'=>false,'id'=>'mobile','class'=>'form-control cnt msg_box')); ?>
                    </div>
                </div>



                <div class="form-group">

                    <div class="col-sm-3">
                        <label>Messag Type</label>
                        <?php $type_array = array(
                            'TEXT'=>'TEXT',
                            'IMAGE'=>'IMAGE',
                            'VIDEO'=>'VIDEO',
                            'AUDIO'=>'AUDIO'


                        );?>
                        <?php echo $this->Form->input('message_type',array('type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control cnt')); ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Upload Media</label>
                        <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control')) ?>
                        <?php echo $this->Form->input('message_file_url',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                        <div class="file_error"></div>
                        <div class="file_success"></div>

                    </div>

                    <div class="col-sm-2">
                        <label>&nbsp;</label>
                        <?php echo $this->Form->submit('Upload',array('type'=>'button','class'=>'upload_media btn btn-success')); ?>

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

</div>





<script>
    $(document).ready(function(){


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');

        $(document).on('submit','.msg_frm',function(e){
            e.preventDefault();


            if($(".msg_box").val()==""){
                $(".msg_box").css('border-color','red');
            }else{
                var form = $(this);
                var btn = $(this).find('[type=submit]');

                var id =  $("#myModal").attr("data-channel");
                $.ajax({
                    url:baseurl+"admin/admin/add_message_ajax",
                    type:'POST',
                    data:{
                        chn_id:id,
                        Message:$(".msg_frm").serialize()
                    },
                    beforeSend:function(){
                        btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                    },
                    success:function(res){
                        var response = JSON.parse(res);
                        if(response.status==1){
                            $(".show_msg_text").html(response.message).css('color','green');
                            var inter = setInterval(function(){

                                $("#myModal").modal("hide");
                                $(form).trigger('reset');
                                $('.file_error,.file_success').text('');
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

        $(document).on('click','.post_message',function(e){

            $(".show_msg_text").html('');;
            $(".msg_box").val('');;
            $("#myModal").modal('show').attr("data-channel",$(this).attr('data-channel'));

        })



        $(document).on('click','.upload_media',function(e){


            var formData = new FormData($("#sub_frm")[0]);
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"admin/admin/upload_event_media",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $(".file_error, .file_success").html("");
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
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




        });

    });
</script>

