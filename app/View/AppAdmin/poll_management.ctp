<?php
$login = $this->Session->read('Auth.User');

?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Poll Management</h2> </div>
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
                 <!--   --><?php /*echo $this->element('app_admin_leftsidebar'); */?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box">

                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_poll'),'admin'=>true)); ?>


                            <div class="form-group">
                                <div class="col-sm-3">

                                    <?php echo $this->Form->input('poll_type', array('type' => 'text', 'placeholder' => 'Poll type', 'label' => 'Search by Type', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-3">

                                    <?php echo $this->Form->input('question', array('type' => 'text', 'placeholder' => 'Question here', 'label' => 'Search by Question', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">

                                    <?php echo $this->Form->input('publish_type', array('type' => 'select','empty'=>'All','options'=>array('PUBLIC'=>'Public','PRIVATE'=>'Private'),'label' => 'Publish Type', 'class' => 'form-control')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'search_poll')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>


                            <?php echo $this->element('message'); ?>

                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($question)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Poll Type</th>
                                        <th>Question</th>

                                        <th>Poll Publish</th>
                                        <th>Total Participates</th>
                                        <th>Total Response</th>
                                        <th>Duration</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($question as $key => $list){ ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>

                                        <td><?php echo $list['ActionType']['name']; ?></td>
                                        <td><?php echo $list['ActionQuestion']['question_text']; ?></td>
                                        <td><?php echo ucfirst($list['ActionQuestion']['poll_publish']); ?></td>
                                        <td><?php echo $list['ActionQuestion']['participates_count']; ?></td>
                                        <td><?php echo $list['ActionQuestion']['response_count']; ?></td>
                                        <td><?php echo $list['ActionQuestion']['poll_duration']; ?></td>

                                        <td>
                                      <!--      <div class="action_icon">
                                                <?php
/*                                                echo $this->Html->link('', 'javascript:void(0);',
                                                    array('data-channel' =>base64_encode($list['ActionQuestion']['id']),'class' => 'fa fa-envelope post_message', 'title' => 'Post new message'));
                                                */?>
                                            </div>
-->
                                            <div class="action_icon">
                                            <?php

                                            if($list['ActionQuestion']['status']=="Y") {
                                                echo $this->Html->link('', "javascript:void(0);",
                                                    array('data-id' => base64_encode($list['ActionQuestion']['id']), 'class' => 'fa fa-check ch_status', 'title' => 'Click to active this poll'));
                                            }else{
                                                echo $this->Html->link('', "javascript:void(0);",
                                                    array('data-id' => base64_encode($list['ActionQuestion']['id']), 'class' => 'fa fa-close ch_status', 'title' => 'Click to active this poll'));
                                            }


                                            ?>
                                            </div>
                                                <div class="action_icon">
                                            <?php
                                            echo $this->Html->link('', Router::url('/app_admin/view_poll/',true).base64_encode($list['ActionQuestion']['id']),
                                                array('class' => 'fa fa-eye', 'title' => 'View Channel'));


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
                                    <h2>You have no poll</h2>
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
        $("#v_app_channel_list").addClass('active');

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


        $(document).on('click','.ch_status',function (e) {

            var id = $(this).attr('data-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_poll_status',
                data:{id:id},
                type:'POST',
                beforeSend:function () {
                    $(thisButton).removeClass("fa fa-check, fa fa-close");
                    $btn.button('loading').html('').attr('class',"fa fa-circle-o-notch fa-spin");
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1)
                    {   if(data.text=="Y"){
                        $(thisButton).attr('class',"fa fa-check");
                    }else{
                        $(thisButton).attr('class',"fa fa-close");
                    }
                    }
                    else
                    {
                        alert(data.message);
                    }

                },
                error:function () {
                    $btn.button('reset');
                }
            });
        });





    });
</script>






