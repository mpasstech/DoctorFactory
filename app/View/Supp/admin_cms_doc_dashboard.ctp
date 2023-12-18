<?php
$login = $this->Session->read('Auth');
?>

<div class="Inner-banner">
    <div class="container">
        <div class="row">
            <!--  SLIDER IMAGE -->
            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>DOC CMS</h2> </div>
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
                   <?php echo $this->element('support_admin_leftsidebar'); ?>
                    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                        <?php echo $this->element('supp_admin_inner_tab_cms_doc'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>
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
                                        <th>Send Notification</th>
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
                                        <td><?php
                                              if(!empty($list['CmsDocDashboard']['notification_param'])){
                                                  echo "<button class='btn btn-info btn-sm send_alert' data-id=".$list['CmsDocDashboard']['id']." class=''>Send Alert</button>";
                                              }else{
                                                  echo "Alert Sent";
                                              }

                                            ?></td>
                                        <td>
                                            <div class="action_icon">
                                                <?php
                                                echo $this->Html->link('',Router::url('/admin/supp/edit_cms_doc_dashboard/',true).base64_encode($list['CmsDocDashboard']['id']),
                                                    array('class' => 'fa fa-edit', 'title' => 'Edit cms'));
                                                ?>
                                            </div>

                                            <div class="action_icon">
                                                <?php
                                                 echo $this->Html->link('',Router::url('/admin/supp/view_cms_doc_dashboard/',true).base64_encode($list['CmsDocDashboard']['id']),
                                                    array('class' => 'fa fa-eye', 'title' => 'Html View'));
                                                ?>
                                            </div>

                                            <div class="action_icon">
                                                <?php
                                                echo $this->Html->link('','javascript:void(0);',
                                                    array('data-cat'=>$list['CmsDocDashboard']['category'], 'data'=>Router::url('/admin/supp/delete_cms_doc_dashboard/',true).base64_encode($list['CmsDocDashboard']['id']),'class' => 'fa fa-trash delete_cms', 'title' => 'Delete this Tip.'));
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
                    url:baseurl+"/admin/supp/add_message_ajax",
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
            var cat = $(this).attr('data-cat');
            var title = "Delete Health Tip";
            if(cat=="MENGAGE_CHANNEL"){
                title = "Delete MEngage Channel Tip";
            }else if(cat == "EMERGENCY"){
                title = "Delete Emergency Tip";
            }
            var jc = $.confirm({
                title: title,
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
        $(document).on('click','.send_alert',function(e){

            var del_obj =btn = $(this);
            var parent_td =  $(this).closest('td');
            var title = "Send Notification";
            var id =  $(del_obj).attr("data-id");
            $.ajax({
                url:baseurl+"/admin/supp/send_health_tip_notification",
                type:'POST',
                data:{id:id},
                beforeSend:function(){
                    btn.button('loading').html('Sending...');
                },
                success:function(res){
                    var response = JSON.parse(res);
                    btn.button('reset');
                    if(response.status==1){
                        parent_td.html('Alert Sent');
                        btn.hide();
                    }
                    $.alert(response.message);

                },
                error: function(xhr, status, error) {
                    btn.button('reset');
                    var err = eval("(" + xhr.responseText + ")");
                    $.alert(err.Message);
                }
            })

        });


    });
</script>






