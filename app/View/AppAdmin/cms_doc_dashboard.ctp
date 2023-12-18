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

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">



                        <div class="Social-login-box payment_bx">
                            <h3 class="screen_title">Health & Emergency</h3>
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->element('app_admin_inner_tab_cms_doc'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($channel)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>

                                        <th>Title</th>

										<th>Category</th>
                                        <th>Status</th>
                                        <th>Action</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($channel as $key => $list){?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $list['CmsDocDashboard']['title']; ?></td>
                                        
										<td><?php echo $list['CmsDocDashboard']['category']; ?></td>
                                        <td><?php echo ucfirst($list['CmsDocDashboard']['status']); ?></td>
                                        <td>
                                            <div class="action_icon">
                                                <?php
                                                echo $this->Html->link('',Router::url('/app_admin/edit_cms_doc_dashboard/',true).base64_encode($list['CmsDocDashboard']['id']),
                                                    array('class' => 'fa fa-edit', 'title' => 'Edit cms'));
                                                ?>
                                            </div>

                                            <div class="action_icon">
                                                <?php
                                                 echo $this->Html->link('',Router::url('/app_admin/view_cms_doc_dashboard/',true).base64_encode($list['CmsDocDashboard']['id']),
                                                    array('class' => 'fa fa-eye', 'title' => 'Html View'));
                                                ?>
                                            </div>

                                            <div class="action_icon">
                                                <?php
                                                echo $this->Html->link('','javascript:void(0);',
                                                    array('data'=>Router::url('/app_admin/delete_cms_doc_dashboard/',true).base64_encode($list['CmsDocDashboard']['id']),'class' => 'fa fa-trash delete_cms', 'title' => 'Delete this Tip.'));
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
                                    <h2>You have no cms pages</h2>
                                </div>
                            <?php } ?>

                                </div>
                            </div>
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
        $("#v_app_cms_list").addClass('active');

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
                })
            }

        })

    $(document).on('click','.post_message',function(e){

        $(".show_msg_text").html('');;
        $(".msg_box").val('');;
        $("#myModal").modal('show').attr("data-channel",$(this).attr('data-channel'));

    })


        $(document).on('click','.delete_cms',function(e){
            var del_obj = $(this);
            var jc = $.confirm({
                title: 'Delete Health & Emergency',
                content: 'Are you sure you want to delete this tip?',
                type: 'red',
                buttons: {
                    ok: {
                        text: "Yes",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        name:"ok",
                        action: function(e){
                            var url =  del_obj.attr("data");
                            window.location.href = url;
                            return false;
                        }
                    },
                    cancel: function(){

                    }
                }
            });
        });


    });
</script>






