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



                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'admin','action' => 'search_message'),'admin'=>true)); ?>


                            <div class="form-group">
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('message', array('type' => 'text', 'placeholder' => 'Message', 'label' => 'Search by Message', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php $type_array = array(
                                        'TEXT'=>'TEXT',
                                        'IMAGE'=>'IMAGE',
                                        'VIDEO'=>'VIDEO',
                                        'AUDIO'=>'AUDIO'
                                    );?>
                                    <?php echo $this->Form->input('message_type', array('type' => 'select','empty'=>'All','options'=>$type_array,'label' => 'Search by Type', 'class' => 'form-control')); ?>
                                </div>


                                <div class="col-sm-3">
                                    <?php $type_array = $this->SupportAdmin->get_public_channel();?>
                                    <?php echo $this->Form->input('channel', array('type' => 'select','empty'=>'All','options'=>$type_array,'label' => 'Search by Channel', 'class' => 'form-control')); ?>
                                </div>


                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-1">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'admin','action'=>'message')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>


                            <?php echo $this->element('message'); ?>
                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($message)){ ?>
                                <ul class="outer_ul_message">
                                <?php foreach ($message as $key => $list){ ?>
                                       <li class="outer_li_message">
                                        <ul class="inner_ul_message">

                                        <li class="message_list_td msg_media">

                                            <?php
                                            if(!empty($list['Message']['message_file_url']) && $list['Message']['message_type']=="IMAGE"){
                                                echo "<img src='".$list['Message']['message_file_url']."'>";
                                            }else if(!empty($list['Message']['message_file_url']) && $list['Message']['message_type']=="VIDEO"){
                                                echo "<video width='230' controls><source src='".$list['Message']['message_file_url']."'></video>";

                                            }else if(!empty($list['Message']['message_file_url']) && $list['Message']['message_type']=="AUDIO"){
                                                echo "<video width='230' controls><source src='".$list['Message']['message_file_url'].".mp'></video>";
                                            }else if(!empty($list['Message']['message_file_url']) && $list['Message']['message_type']=="TEXT"){
                                                echo "<label>".$list['Message']['message_file_url']."</label>";
                                            }

                                            ?>
                                        </li>

                                            <li class="message_list_td msg_opt">
                                            <div class="lable_msg_div">
                                                <lable>Message</lable><?php echo $list['Message']['message']; ?>
                                            </div>
                                            <div class="lable_msg_div">
                                         <lable>Message Type</lable><?php echo ucfirst($list['Message']['message_type']); ?>
                                            </div>  <div class="lable_msg_div">
                                         <lable>Post By</lable><?php echo $list['Message']['Owner']['mobile']; ?>

                                            </div>   <div class="lable_msg_div">
                                         <lable>Date</lable><?php echo $list['Message']['created']; ?>
                                            </div>          <div class="lable_msg_div">

                                            <lable>Status</lable>


                                                <?php

                                                if($list['Message']['status']=="Y"){
                                                    echo $this->Html->link('','javascript:void(0)',
                                                        array('id'=>'status_staff','data-id'=>base64_encode($list['Message']['id']),'class' => 'fa fa-check', 'title' => 'This message is active.'));

                                                }else{
                                                    echo $this->Html->link('','javascript:void(0)',
                                                        array('id'=>'status_staff','data-id'=>base64_encode($list['Message']['id']),'class' => 'fa fa-close', 'title' => 'This message is inactive.'));

                                                }
                                                ?>

                                            </div>

                                        </li>
                                        </ul>
                                       </li>
                                <?php } ?>

                                </ul>


                                <div style="clear: both;"><?php echo $this->element('paginator'); ?></div>

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





<script>
    $(document).ready(function(){


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');

        $(document).on('click','#status_staff',function (e) {

            var id = $(this).attr('data-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'admin/admin/change_message_status',
                data:{id:id},
                type:'POST',
                beforeSend:function () {
                    var path = baseurl+"images/ajax-loading-small.png";
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

