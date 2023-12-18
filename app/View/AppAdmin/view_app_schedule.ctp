<?php
$login = $this->Session->read('Auth.User.User');

$reqData = $this->request->query;
$path = Router::url('/thinapp_images/staff.jpg',true);
$date =date('Y-m-d');
$reqData = $this->request->query;
if(isset($reqData['d']) && !empty($reqData)){
    $date = $reqData['d'];
}
?>
<?php  echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js')); ?>

<style>
    .form_admit {
        margin-top: 25px;
    }
</style>

<!--div class="Inner-banner">
    <div class="container">
        <div class="row">

            <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12 inner-page-text"> <h2>Staff Schedule</h2> </div>
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 pull-right create-btn-box">
            </div>
        </div>
    </div>
</div-->

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <h3 class="screen_title">My Appointment</h3>

                    <?php echo $this->element('app_admin_book_appointment'); ?>

                    <div class="col-md-12 form_admit right_box_div">



                        <?php echo $this->element('message'); ?>
                        <?php //echo $this->element('appointment_header_tab'); ?>


                        <div class="form-group">
                          <?php echo $this->element('app_admin_inner_tab_appointment_filter'); ?>
                        </div>

                            <div class="form-group">

                                <?php if(!isset($reqData['lt']) || $reqData['lt']=='s'){ ?>
                                <div class="col-sm-12">
                                <?php if(!empty($appointment_staff_list)){ ?>
                                <?php foreach($appointment_staff_list as $key => $staff){ ?>
                                    <?php





                                        if(!empty($staff['AppointmentStaff']['profile_photo'])){
                                            $path =$staff['AppointmentStaff']['profile_photo'];
                                        }

                                        ?>
                                        <div class="item  col-sm-3 grid-group-item view_staff_grid">
                                            <div class="thumbnail">

                                                <div class="staff_img_view" style="background-image: url('<?php echo $path ;?>')" alt=""></div>
                                                <div class="caption">
                                                    <h4 style="height:30px" class="group inner list-group-item-heading staff_name_head">
                                                        <?php echo $staff['AppointmentStaff']['name']; ?></h4>
                                                    <p class="group inner list-group-item-text bio_div">
                                                       <lable><i class="fa fa-phone"></i> <?php echo $staff['AppointmentStaff']['mobile']; ?></lable>
                                                       <lable style="height: 30px;"><?php echo $staff['AppointmentStaff']['sub_title']; ?></lable>
                                                    </p>
                                                    <h3 class="app_heading">Appointment On <?php echo date('d-M-Y',strtotime($date));?> </h3>
                                                    <?php  $address_id = 0; ?>
                                                    <p class="group inner list-group-item-text">
                                                        <lablel>New : <strong> <?php echo $this->AppAdmin->staff_appointment_count($staff['AppointmentStaff']['id'], $login['thinapp_id'], $date, "NEW",$address_id); ?></strong></lablel>
                                                    </p>

                                                    <p class="group inner list-group-item-text">
                                                        <lablel>Confirmed : <strong> <?php echo $this->AppAdmin->staff_appointment_count($staff['AppointmentStaff']['id'], $login['thinapp_id'],$date,"CONFIRM",$address_id); ?></strong></lablel>
                                                    </p>
                                                    <p class="group inner list-group-item-text">
                                                        <lablel>Closed : <strong> <?php echo $this->AppAdmin->staff_appointment_count($staff['AppointmentStaff']['id'], $login['thinapp_id'],$date,"CLOSED",$address_id); ?></strong></lablel>
                                                    </p>
                                                    <p class="group inner list-group-item-text">
                                                        <lablel>Canceled : <strong> <?php echo $this->AppAdmin->staff_appointment_count($staff['AppointmentStaff']['id'], $login['thinapp_id'],$date,"CANCELED",$address_id); ?></strong></lablel>
                                                    </p>
                                                    <p class="group inner list-group-item-text">
                                                        <lablel>Rescheduled : <strong> <?php echo $this->AppAdmin->staff_appointment_count($staff['AppointmentStaff']['id'], $login['thinapp_id'],$date,"RESCHEDULE",$address_id); ?></strong></lablel>
                                                    </p>


                                                    <div class="row">
                                                        <!--div class="col-xs-6">

                                                            <a class="" href="<?php echo Router::url('/tracker/display?t=',true).base64_encode($staff['AppointmentStaff']['thinapp_id'])."&&d=".base64_encode($staff['AppointmentStaff']['id']); ?>"><i class="fa fa-eye"></i> View Tracker </a>
                                                        </div-->
                                                        <div class="col-xs-12">

                                                            <a class="btn btn-ss btn-info staff_view_btn" href="<?php echo Router::url('/app_admin/view_staff_app_schedule?st=',true).base64_encode($staff['AppointmentStaff']['id'])."&&d=".$date; ?>"><i class="fa fa-eye"></i> View </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                <?php } ?>
                                <?php echo $this->element('paginator'); ?>


                            <?php }else{ ?>
                                <div class="no_data">
                                    <h2>There is no staff.</h2>
                                </div>
                            <?php } ?>

                                </div>
                                <?php }else{ ?>
                                <div class="col-sm-12">

                                    <div class="table table-responsive">
                                        <?php if(!empty($appointment_list)){ ?>
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Photo</th>
                                                <th>Customer Name</th>
                                                <th>Customer Mobile</th>
                                                <th>Service Name</th>
                                                <th>Service Address</th>
                                                <th>Appointment Booked</th>
                                                <th>Appointment On</th>
                                                <th>Fees</th>
                                                <th>Status</th>
                                                <th>Payment Status</th>
                                                <th>Action</th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($appointment_list as $key => $list){
                                                if(empty($list['AppointmentCustomer']['profile_photo'])){
                                                    $list['AppointmentCustomer']['profile_photo'] =Router::url('/thinapp_images/staff.jpg',true);
                                                }
                                                ?>
                                                <tr>
                                                    <td><?php echo $key+1; ?></td>
                                                    <td><img class="channel_icon_list" src="<?php echo $list['AppointmentCustomer']['profile_photo'];?>"></td>
                                                    <td><?php echo $list['AppointmentCustomer']['first_name']." ".$list['AppointmentCustomer']['last_name'] ; ?></td>
                                                    <td><?php echo $list['AppointmentCustomer']['mobile']; ?></td>
                                                    <td><?php echo ucfirst($list['AppointmentService']['name']); ?></td>
                                                    <td><?php echo ucfirst($list['AppointmentAddress']['address']); ?></td>
                                                    <td><?php echo date('d-m-Y',strtotime($list['AppointmentCustomerStaffService']['created'])); ?></td>
                                                    <td><?php echo $app_date = date('d-m-Y',strtotime($list['AppointmentCustomerStaffService']['appointment_datetime']))." ".$list['AppointmentCustomerStaffService']['slot_time']   ; ?></td>
                                                    <td><?php echo $list['AppointmentCustomerStaffService']['amount']; ?></td>
                                                    <td class="status_td"><?php echo ucfirst(strtolower($list['AppointmentCustomerStaffService']['status'])); ?></td>
                                                    <td><?php echo ucfirst(strtolower($list['AppointmentCustomerStaffService']['payment_status'])); ?></td>
                                                    <td class="app_btn_td">

                                                        <?php

                                                        $status =$list['AppointmentCustomerStaffService']['status'];
                                                        $payment_status = $list['AppointmentCustomerStaffService']['payment_status'];
                                                        $date = date('Y-m-d', strtotime($list['AppointmentCustomerStaffService']['appointment_datetime']))." ".$list['AppointmentCustomerStaffService']['slot_time'];
                                                        $appointment_datetime  = (date('Y-m-d H:i', strtotime($date)));
                                                        $current_date =  date('Y-m-d H:i');

                                                        ?>
                                                        <?php if($status=="CONFIRM" || $status=="RESCHEDULE" || $status=="NEW" ) { ?>

                                                            <button type="button" data-st_id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['appointment_staff_id']); ?>" data-service ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['appointment_service_id']); ?>" data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-success btn_reschedule"><i class="fa fa-refresh"></i> Reschedule</button>
                                                            <?php if( strtotime($current_date) > strtotime($appointment_datetime) ){ ?>
                                                                <button type="button" data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-warning close_btn"><i class="fa fa-power-off"></i> Close</button>
                                                            <?php }

                                                            ?>

                                                            <?php if( strtotime($current_date) < strtotime($appointment_datetime) ){ ?>
                                                                <button type="button" data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-danger cancel_btn"><i class="fa fa-close"></i> Cancel</button>
                                                            <?php } ?>

                                                        <?php } ?>

                                                        <?php  if( $payment_status == "PENDING" ) { ?>
                                                            <button type="button" data-id ="<?php echo base64_encode($list['AppointmentCustomerStaffService']['id']); ?>" class="btn btn-xs btn-info pay_btn"><i class="fa fa-money"></i> Pay</button>
                                                        <?php } ?>




                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                        <?php echo $this->element('paginator'); ?>

                                    </div>
                                    <?php } else{ ?>
                                        <div class="no_data">
                                            <h2>There is no appointment. </h2>
                                        </div>
                                    <?php } ?>

                                </div>
                                <?php } ?>
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

            <?php echo $this->Form->create('Message',array('method'=>'post','class'=>'form-horizontal msg_frm','id'=>'sub_frm')); ?>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Post Message</h4>
            </div>
            <div class="modal-body">



                <div class="form-group">
                    <div class="col-sm-12">

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
                        <?php echo $this->Form->input('file',array('type'=>'file','label'=>false,'class'=>'form-control browse_file')) ?>
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


<div class="modal fade" id="reschedule" role="dialog"></div>

<style>
    .thumbnail{
        background-color: #eee;
    }
    .staff_view_btn
    { 
        width: 100%;
        background: #03a9f5; 
    }
    .dashboard_icon_li li {

        text-align: center;
        width: 23%;

    }
    thead tr{
        background-color: skyblue;
        color: green;
    }
    .container {
        width: 95%;
    }
</style>
<script>
    $(document).ready(function(){


        $(".channel_tap a").removeClass('active');
        $("#v_app_channel_list").addClass('active');


        var upload_file =false;
        $(document).on('change','.browse_file',function(e){
            if($(this).val()){
                readURL(this);
            }else{
                upload_file = false;
            }
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    upload_file= true;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $(document).on('submit','.msg_frm',function(e){
            e.preventDefault();

            if(upload_file==true){
                alert('Please upload file');
                return false;
            }

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
        $(document).on('click','.upload_media',function(e){


                var formData = new FormData($("#sub_frm")[0]);
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
                            $(".channel_img").attr("src",data.url);
                            $(".file_success").html(data.message);
                            upload_file= false;
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


        /*serach box script start*/

        var concept = $('#search_param').val();
        if(concept!=""){
            $('#search_concept').text(concept);
        }
        $('.search-panel .dropdown-menu').find('a').click(function(e) {
            e.preventDefault();
            var param = $(this).attr("href").replace("#","");
            var concept = $(this).text();
            $('.search-panel span#search_concept').text(concept);
            $('.input-group #search_param').val(param);
        });

        /*serach box script end*/

        $(".datepicker").datepicker(
            'setDate', new Date("<?php echo $date; ?>")
        ).on('changeDate', function(ev){
            $(this).datepicker('hide');
        });





        var schedule_in_process =false;
        $(document).on('click','.btn_reschedule',function(e){

            if(schedule_in_process === false){
                var $btn = $(this);
                var id = $(this).attr('data-id');
                var service = $(this).attr('data-service');
                var st_id = $(this).attr('data-st_id');

                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/load_reschedule_modal",
                    data:{id:id,service:service,st_id:st_id},
                    beforeSend:function(){
                        schedule_in_process = true;
                        $btn.button('loading').val('Wait..')
                    },
                    success:function(data){
                        $("#reschedule").html(data).modal("show");
                        $btn.button('reset');
                        schedule_in_process = false;
                    },
                    error: function(data){
                        schedule_in_process =false;
                        $btn.button('reset');
                        $(".file_error").html("Sorry something went wrong on server.");
                    }
                });
            }


        })


        $(document).on('click','.pay_btn',function(e){
            if(confirm("Are you sure you want to payment for this appointment ?")){
                var $btn = $(this);
                var id = $(this).attr('data-id');
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/appointment_payment",
                    data:{id:id},
                    beforeSend:function(){
                        $btn.button('loading').html('Wait..')
                    },
                    success:function(data){
                        var response = JSON.parse(data);
                        if(response.status==1){
                            window.location.reload();
                        }else{
                            alert(response.message);
                            //$(".message_span_slot").html(response.message);
                            $btn.button('reset');
                        }
                    },
                    error: function(data){
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });
            }
        })

        $(document).on('click','.close_btn',function(e){

            var $btn = $(this);
            var obj = $(this);
            var id = $(this).attr('data-id');
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/close_appointment",
                data:{id:id},
                beforeSend:function(){
                    $btn.button('loading').html('Wait..')
                },
                success:function(data){
                    var response = JSON.parse(data);
                    if(response.status==1){
                        $(obj).closest("tr").find(".status_td").html("Closed");
                        $(obj).closest("tr").find(".app_btn_td").html("");
                    }else{
                        alert(response.message);
                        $btn.button('reset');
                    }
                },
                error: function(data){
                    $btn.button('reset');
                    alert("Sorry something went wrong on server.");
                }
            });

        })


        $(document).on('click','.cancel_btn',function(e){

            if(confirm("Are you sure you want to cancel this appointment ?")) {
                var $btn = $(this);
                var obj = $(this);
                var id = $(this).attr('data-id');
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/cancel_appointment",
                    data: {id: id},
                    beforeSend: function () {
                        $btn.button('loading').html('Wait..')
                    },
                    success: function (data) {
                        var response = JSON.parse(data);
                        if (response.status == 1) {
                            $(obj).closest("tr").find(".status_td").html("Cancel");
                            $(obj).closest("tr").find(".app_btn_td").html("");
                        } else {
                            alert(response.message);
                            $btn.button('reset');
                        }
                    },
                    error: function (data) {
                        $btn.button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });
            }

        })



















    });
</script>








