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
                    <?php echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>

                        <?php echo $this->element('app_admin_inner_tab_channel_factory_filter'); ?>

                        <?php echo $this->element('app_admin_inner_tab_channel'); ?>


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
                                        <th>Subscribes</th>
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
                                        <td><?php echo $this->Custom->total_subscribe($list['Channel']['id']); ?></td>
                                        <td>
                                            <div class="action_icon">
                                                <?php
                                                $sub = $this->AppAdmin->isSubcribeChannel($login['User']['id'],$login['User']['thinapp_id'],$list['Channel']['id']);
                                                if(!empty($sub))
                                                {
                                                    if($sub['status'] == 'SUBSCRIBED'){
                                                        echo '<button channel-id="'.base64_encode($list['Channel']['id']).'" data-new="0" title ="Click to unscubscribe" type="button" id="changeStatus" subscriber-id="'.base64_encode($sub['subscriberID']).'" class="btn btn-success btn-xs">Subscribed</button>';
                                                    }else{
                                                        echo '<button channel-id="'.base64_encode($list['Channel']['id']).'" data-new="0" title ="Click to scubscribe" type="button" id="changeStatus" subscriber-id="'.base64_encode($sub['subscriberID']).'" class="btn btn-warning btn-xs">Not Subscribed</button>';
                                                    }
                                                }else{
                                                        echo '<button channel-id="'.base64_encode($list['Channel']['id']).'" data-new="1" title ="Click to scubscribe" type="button" id="changeStatus" subscriber-id="'.base64_encode($sub['subscriberID']).'" class="btn btn-warning btn-xs">Not Subscribed</button>';
                                                }
                                                ?>
                                            </div>



                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>

                        </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>You have no channels</h2>
                                </div>
                            <?php } ?>

                                </div>

                            </div>
                            <div class="clear"></div>



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

            <?php echo $this->Form->create('Message',array('method'=>'post','class'=>'form-horizontal msg_frm')); ?>

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

                        <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'options'=>$type_array,'class'=>'form-control cnt')); ?>
                    </div>
                    <div class="col-sm-3">
                        <label>&nbsp;</label>
                        <?php echo $this->Form->submit('Upload',array('type'=>'button','class'=>'upload_btn btn btn-success')); ?>
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
        $("#v_add_factory").addClass('active');

            $(document).on('submit','.msg_frm',function(e){
                e.preventDefault();


                if($(".msg_box").val()==""){
                    $(".msg_box").css('border-color','red');
                }else{

                    var btn = $(this).find('[type=submit]');

                    var id =  $("#myModal").attr("data-channel");
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
                                    $("#myModal").modal("hide");
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
                    });
                }

            });

            $(document).on('click','.post_message',function(e){

                $(".show_msg_text").html('');;
                $(".msg_box").val('');;
                $("#myModal").modal('show').attr("data-channel",$(this).attr('data-channel'));

            });

            $(document).on('click','#changeStatus',function (e) {
                e.preventDefault();
                if(confirm('Change subscribe status?') == false)
                {
                    return false;
                }
                var subscriberID = $(this).attr('subscriber-id');
                var is_new = $(this).attr('data-new');
                var channel_id = $(this).attr('channel-id');
                var $btn = $(this);
                var thisButton = $(this);
                $.ajax({
                    url: baseurl+'app_admin/chang_subscribe_id',
                    data:{subscriberID:subscriberID,is_new:is_new,channel_id:channel_id},
                    type:'POST',
                    beforeSend:function(){
                        $btn.button('loading').text('Wait...');
                    },
                    success: function(result){

                        $btn.button('reset');
                        var data = JSON.parse(result);
                        if(data.status == 1)
                        {

                            $(thisButton).removeClass('btn-success');
                            $(thisButton).attr('data-new',data.is_new);
                            $(thisButton).attr('subscriber-id',data.sub_id);
                            $(thisButton).removeClass('btn-warning');
                            $(thisButton).addClass(data.class);
                            $(thisButton).text(data.text);
                        }
                        else
                        {
                            $btn.button('reset');
                            alert(data.message);
                        }
                    }
                });
            });

    });
</script>






