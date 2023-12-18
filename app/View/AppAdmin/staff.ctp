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
                            <h3 class="screen_title">Members List</h3>
                            <?php echo $this->element('message'); ?>
                            <?php echo $this->element('app_admin_inner_tab_staff'); ?>


                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_staff'),'admin'=>true)); ?>


                            <div class="form-group">
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => 'Poll type', 'label' => 'Search by Name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-4">

                                    <?php echo $this->Form->input('number', array('type' => 'number', 'placeholder' => 'Mobile number', 'label' => 'Search by number', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <?php echo $this->Form->submit('Search',array('class'=>'Btn-typ3','id'=>'search')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->label('&nbsp;'); ?>
                                    <div class="submit">
                                        <a href="<?php echo $this->Html->url(array('controller'=>'app_admin','action'=>'search_staff')) ?>"><button type="button" class="Btn-typ3" >Reset</button></a>
                                    </div>
                                </div>

                            </div>

                            <?php echo $this->Form->end(); ?>



                            <div class="form-group row">
                                <div class="col-sm-12">

                            <div class="table-responsive">
                            <?php if(!empty($staff)){ ?>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Designation</th>
                                        <th>Description</th>
                                        <th>Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($staff as $key => $list){
                                        if(empty($list['AppStaff']['image'])){
                                            $path =Router::url('/images/channel-icon.png',true);
                                        }else{
                                            $path =$list['AppStaff']['image'];
                                        }
                                        ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><img class="staff_image_list" src="<?php echo $path;?>"></td>
                                        <td><?php echo $list['AppStaff']['fullname']; ?></td>
                                        <td><?php echo ucfirst($list['AppStaff']['email']); ?></td>
                                        <td><?php echo $list['AppStaff']['mobile']; ?></td>
                                        <td><?php echo $list['AppStaff']['designation']; ?></td>
                                        <td>

                                            <div data-toggle="tooltip" title="<?php echo $list['AppStaff']['description']; ?>" >
                                                <?php echo  mb_strimwidth($list['AppStaff']['description'], 0, 50, '...'); ?>
                                            </div>

                                        </td>
                                        <td>
                                            <div class="action_icon status_td">

                                            <?php

                                            if($list['AppStaff']['status']=="Y"){
                                            echo $this->Html->link('','javascript:void(0)',
                                            array('id'=>'status_staff','data-id'=>base64_encode($list['AppStaff']['id']),'class' => 'fa fa-check', 'title' => 'This user is active.'));

                                            }else{
                                            echo $this->Html->link('','javascript:void(0)',
                                            array('id'=>'status_staff','data-id'=>base64_encode($list['AppStaff']['id']),'class' => 'fa fa-close', 'title' => 'This user is inactive.'));

                                            }
                                            ?>
                                            </div>

                                            <div class="action_icon">
                                            <?php

                                            echo $this->Html->link('', Router::url('/app_admin/edit_staff/',true).base64_encode($list['AppStaff']['id']),
                                                array('class' => 'fa fa-edit', 'title' => 'Edit staff'));
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
        $("#v_app_staff_list").addClass('active');
        $('[data-toggle="tooltip"]').tooltip();

        $(document).on('click','#status_staff',function (e) {

            var id = $(this).attr('data-id');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_staff_status',
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

<style>
  
    .tooltip-inner {
        max-width: 500px !important;
    }
     .tooltip-inner {background-color: #03a9f5; text-align: justify;}
     .tooltip-arrow { border-bottom-color:#03a9f5; }
</style>






