<?php
$login = $this->Session->read('Auth.User');
$old_appointment = $this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'], "QUICK_APPOINTMENT");


?>


<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-left: 2px; padding-right: 2px;">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Doctor / Receptionist List</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>


                            <?php echo $this->Form->create('Search',array('type'=>'get','url'=>array('controller'=>'app_admin','action' => 'search_doctor'),'admin'=>true)); ?>


                            <div class="form-group">
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('name', array('type' => 'text', 'placeholder' => '', 'label' => 'Search by Name', 'class' => 'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('mobile', array('type' => 'number', 'placeholder' => '', 'label' => 'Search by number', 'class' => 'form-control')); ?>
                                </div>

                                <?php if($login['Thinapp']['category_name'] =='HOSPITAL'){  ?>
                                <div class="col-sm-2">
                                    <?php echo $this->Form->input('staff_type', array('label' => 'Search by Role','type' => 'select','empty'=>'All','options'=>array('DOCTOR'=>'Doctor','RECEPTIONIST'=>'Receptionist'),'class' => 'form-control')); ?>
                                </div>
                                <?php } ?>

                                <div class="col-sm-2">

                                    <?php $type_array = array('ACTIVE'=>'Active','INACTIVE'=>'Inactive'); ?>
                                    <?php echo $this->Form->input('status',array('type'=>'select','label'=>'Status','empty'=>'All','options'=>$type_array,'class'=>'form-control')); ?>
                                </div>


                                <div class="col-md-4">
                                    <div class="input number">
                                    <label> &nbsp;</label><br>
                                    <?php echo $this->Form->submit('Search',array('div'=>false,'class'=>'btn btn-info','id'=>'search')); ?>
                                    <a class="btn btn-warning btnReset" href="<?php echo Router::url('/app_admin/doctor/',true); ?>">Reset</a>
                                    <a class="btn btn-success btnReset" href="<?php echo Router::url('/app_admin/add_doctor',true); ?>">Add Doctor/Receptionist</a>
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
                                        <?php if($login['Thinapp']['category_name'] =='HOSPITAL'){  ?>
                                        <th>Role</th>
                                        <?php } ?>
                                        <th>Mobile</th>
                                        <th>Education</th>
                                        <th>Department</th>
                                        <th>Category</th>
                                        <th>Change Password</th>
                                        <th>Status</th>
                                        <th style="width: 11%;">Action</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($staff as $key => $list){
                                        if(empty($list['AppointmentStaff']['profile_photo'])){
                                            $path =Router::url('/images/channel-icon.png',true);
                                        }else{
                                            $path =$list['AppointmentStaff']['profile_photo'];
                                        }
                                        ?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
                                        <td><img class="staff_image_list" src="<?php echo $path;?>"></td>
                                        <td><?php echo $list['AppointmentStaff']['name']; ?></td>
                                        <?php if($login['Thinapp']['category_name'] =='HOSPITAL'){  ?>
                                        <td><?php echo ucfirst(strtolower($list['AppointmentStaff']['staff_type'])); ?></td>
                                        <?php } ?>
                                        <td><?php echo $list['AppointmentStaff']['mobile']; ?></td>
                                        <td><?php echo $list['AppointmentStaff']['sub_title']; ?></td>
                                        <td><?php echo $list['AppointmentCategory']['name']; ?></td>
                                        <td><?php echo $list['DepartmentCategory']['category_name']; ?></td>
                                        <td>
                                            <input type="text" class="password" data-id="<?php echo base64_encode($list['AppointmentStaff']['id']); ?>">
                                            <button style="display: none;" type="button" class="btn btn-success btn-xs pass_btn"><i class="fa fa-check"></i> </button>

                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-<?php echo ($list['AppointmentStaff']['status']=='ACTIVE')?'success':'danger'; ?> btn-xs status_btn"  data-status = "<?php echo $list['AppointmentStaff']['status']; ?>" data-id="<?php echo base64_encode($list['AppointmentStaff']['id']); ?>"><?php echo $list['AppointmentStaff']['status']; ?></button>

                                        </td>
                                        <td>

                                            <a type="button" class="btn btn-info btn-xs" href="<?php echo Router::url('/app_admin/doctor_profile/').base64_encode($list['AppointmentStaff']['id'])."/".base64_encode('YES'); ?>"><i class="fa fa-edit"></i> Edit </a>
                                            <?php if($list['AppointmentStaff']['staff_type'] =='DOCTOR' && $list['AppointmentStaff']['status']=='ACTIVE' && $old_appointment){ ?>
                                                <a type="button" class="btn btn-success btn-xs" href="<?php echo Router::url('/app_admin/doctor_setting/').base64_encode($list['AppointmentStaff']['id']); ?>"><i class="fa fa-gear"></i> Setting</a>
                                            <?php } ?>

                                        </td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                                <?php echo $this->element('paginator'); ?>

                        </div>
                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>No doctor register yet</h2>
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


<style>
    .pass_btn, .password{
        height: 25px;
        float: left;
        border-radius: 0px;
    }
    .pass_btn{
        margin-top: -25px;
        position: relative;
        left: 91%;
        float: left;
        clear: both;
    }
    .password{
        width: 100%;
        padding-right: 28px;
        padding-left: 4px;
        border: 1px solid #c3c3c3;
    }
</style>


<script>
    $(document).ready(function(){


        $(".channel_tap a").removeClass('active');
        $("#v_app_staff_list").addClass('active');
        $('[data-toggle="tooltip"]').tooltip();

        $(document).on('click','.status_btn',function (e) {

            var id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
            var thisButton = $(this);
            var $btn = $(this);
            $.ajax({
                url: baseurl+'app_admin/change_doctor_status',
                data:{i:id,s:status},
                type:'POST',
                beforeSend:function () {
                    $btn.button('loading').html('Wait...');
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1){
                        $(thisButton).attr('data-status',data.label);


                        if(data.label=='ACTIVE'){
                            $(thisButton).removeClass('btn-danger').addClass('btn-success');
                        }else{
                            $(thisButton).removeClass('btn-success').addClass('btn-danger');
                        }
                        $(thisButton).html(data.label);

                    }else{
                        alert(data.message);
                    }

                },
                error:function () {
                    $btn.button('reset');
                }
            });
        });


        $(document).on('click','.pass_btn',function (e) {

            var id = $(this).closest('td').find('.password').attr('data-id');
            var pass = $(this).closest('td').find('.password').val();
            var append = $(this).closest('td').find('.password');
            var thisButton = $(this);
            var $btn = $(this).find('i');
            $.ajax({
                url: baseurl+'app_admin/change_doctor_password',
                data:{i:id,p:pass},
                type:'POST',
                beforeSend:function () {
                    var path = baseurl+"images/ajax-loading-small.png";
                    $btn.button('loading').html('').attr('class',"fa fa-circle-o-notch fa-spin");
                },
                success: function(result){
                    $btn.button('reset');
                    var data = JSON.parse(result);
                    if(data.status == 1){
                        $(thisButton).hide();
                        $(append).css('border-color','green');
                    }else{
                        alert(data.message);
                        $(thisButton).removeClass('btn-success').addClass('btn-danger');
                    }

                },
                error:function () {
                    $btn.button('reset');
                }
            });
        });



        $(document).on('keyup','.password',function (e) {

            var id = $(this).attr('data-id');
            var pass = $(this).val();
            if(pass != ''){

                $(this).closest('td').find('.pass_btn').show();
            }else{
                $(this).closest('td').find('.pass_btn').hide();

            }

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






