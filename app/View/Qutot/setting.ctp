<?php

    $country_list =$this->AppAdmin->countryDropdown();


?>


<style>


    .name_container{
        margin: 10px 0px;
    }
    .mandatory{
        color: red;
        font-size: 8px;
    }
    #accordion{
        margin: 10px;
    }
    #accordion .card-body{
        padding: 0px;
    }
    #profile_sec .card-header {
        background-color: #5D54FF;
        border-bottom: #fff;
        color: #fff;
    }
    #profile_sec .btn-link{
        color: #fff;
    }
    .hidden_file_browse_logo, .upload_media,
    .hidden_file_browse, .upload_media{
        display: none;
    }

    #break_form td, #break_form th,
    #hours_table td, #hours_table th{
        padding: 5px 2px;
        font-size: 0.9rem;
    }

    #break_form input, #break_form select,
    #hours_table input, #hours_table select{
       height: 35px;
       padding: 4px 3px;
        font-size: 0.9rem;
    }

    .delete_break{
        width: 27px;
        height: 27px;
        padding: 0;
        text-align: center;
    }

    .add_more_break{
        padding: 0px 5px;
        float: right;
    }

    .appointment_type_section{
        margin: 7px 0px;
        border: 1px solid #d8d8d8;
        padding: 9px;
        /* color: #fff; */
        min-height: 150px;
    }

    .custom-checkbox{
        margin: 8px 0px;
    }


    #collapseSix .add_more_btn{
        float: left;
        display: inherit;
        width: 48%;
        margin: 2px;
    }
    .error_lbl{
        font-size: 0.8rem;
        color: red;
    }
    .inner_box{
        float: left;
        width: 48%;
        margin: 0px 2px;
    }

    .time_box {
        position: relative;
        z-index: 1000;
        float: left;
        width: 100%;

    }
    #time_box .col-6{
        width: 48%;
        float: left;
        padding: 0;
        margin: 2px;
    }



</style>
<div class="col-12" style="position: absolute;">
    <button type="button" style="position: absolute; float: left;color: blue;background: none;border: none;" class="btn btn-xs btn-danger setting_back " ><i class="fa fa-long-arrow-left"></i> Back</button>
</div>

<h3 class="box_heading"> Settings</h3>


<div class="accordion" id="accordion">

    <div class="card">
        <div class="card-header" id="headingZero">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseZero" aria-expanded="false" aria-controls="collapseZero">
                    <i class="fa fa-picture-o" aria-hidden="true"></i> Edit App Logo
                </button>
            </h2>
        </div>
        <div id="collapseZero" class="collapse" aria-labelledby="headingZero" data-parent="#accordionExample">
            <div class="card-body">
                <h3 class="box_heading">Edit App Logo</h3>

                <form  enctype="multipart/form-data"  id="logo_form">
                    <div class="col-12">

                        <div class="doctor_bar">
                            <label>Tap Logo to Edit</label>
                            <?php
                            $image_path = "https://www.mengage.in/doctor/images/channel-icon.png";
                            if(!empty($doctor_data['logo'])){
                                $image_path = $doctor_data['logo'];
                            }

                            $css = "";
                            if(!empty($image_path)){
                                $css = "background:url('$image_path')";
                            }

                            ?>

                            <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                            <input type="hidden" name="st" value="logo">

                            <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse_logo')) ?>
                            <div  class="channle_img_box channel_img edit_img_icon_logo" style="margin: 0 auto; width: 100px; height: 100px; border-radius: 50px;background-repeat:no-repeat;background-size:cover !important;<?php echo $css; ?>">
                            </div>

                            <h4 style="margin: 10px; padding: 0px;text-align: center;" class="doctor_name_lbl" ></h4>
                        </div>


                    </div>
                    <div class="col-12 buton_box" style="padding-left: 0;padding-right: 0px;">
                        <button type="submit" class="btn btn-xs button btn-success"   id="logo_save_btn"><i class="fa fa-th-list" aria-hidden="true"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingOne">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                    <i class="fa fa-pencil"></i> Edit Profile
                </button>
            </h2>
        </div>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                <h3 class="box_heading">Edit Profile</h3>
                <form  enctype="multipart/form-data"  id="profile_form">
                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                    <input type="hidden" name="si" value="<?php echo base64_encode($doctor_data['service_id']); ?>">
                    <input type="hidden" name="ai" value="<?php echo base64_encode($doctor_data['address_id']); ?>">
                    <input type="hidden" name="aai" value="<?php echo base64_encode($doctor_data['assoc_address_id']); ?>">

                <div class="col-12">
                        <div class="doctor_bar">

                            <?php
                                    $image_path = "https://www.mengage.in/doctor/images/channel-icon.png";
                                    if(!empty($doctor_data['profile_photo'])){
                                        $image_path = $doctor_data['profile_photo'];
                                    }

                                    $css = "";
                                    if(!empty($image_path)){
                                        $css = "background:url('$image_path')";
                                    }

                            ?>

                            <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                            <input type="hidden" name="st" value="profile">

                            <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse')) ?>
                            <div  class="channle_img_box channel_img edit_img_icon" style="margin: 0 auto; width: 100px; height: 100px; border-radius: 50px;background-repeat:no-repeat;background-size:cover !important;<?php echo $css; ?>">

                            </div>

                            <h4 style="margin: 10px; padding: 0px;text-align: center;" class="doctor_name_lbl" ></h4>
                        </div>
                        <?php
                        $display = 'none';
                        $DTI_display = 'none';
                        $main_label = 'Counter Name';
                        $button_text = "Counter";
                        if(in_array($doctor_data['category_name'],array('HOSPITAL','DOCTOR_OPDS','DOCTOR'))){
                            $DTI_display = 'block';
                            $main_label = 'Doctor Name';
                            $button_text = "Doctor";
                        }
                        if($doctor_data['allow_only_mobile_number_booking']=='NO'){
                            $display = 'block';
                        }
                        ?>
                        <div class="name_container">
                            <label><?php echo $main_label; ?> <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                            <?php echo $this->Form->input('name',array('value'=>$doctor_data['name'],'oninvalid'=>"this.setCustomValidity('Please enter name.')",'oninput'=>"setCustomValidity('')" ,'required'=>'required','type'=>'text','label'=>false,'class'=>'form-control')); ?>
                        </div>

                        <div class="name_container">
                            <label>Email</label>
                            <?php echo $this->Form->input('email',array('value'=>$doctor_data['email'],'type'=>'text','label'=>false,'class'=>'form-control')); ?>
                        </div>

                        <div class="name_container">
                            <label>Mobile</label>
                            <?php echo $this->Form->input('mobile',array('value'=>substr($doctor_data['mobile'],-10),'type'=>'mobile','label'=>false,'class'=>'form-control')); ?>
                        </div>

                    <div class="name_container">
                        <label>Address</label>
                        <?php echo $this->Form->input('address',array('value'=>$doctor_data['app_address'],'type'=>'text','label'=>false,'class'=>'form-control')); ?>
                    </div>

                    <h5 style="margin: 7px 0px;">Service Detail</h5>
                    <div class="name_container" >
                        <label>Service Name</label>
                        <?php echo $this->Form->input('service_name',array('value'=>$doctor_data['service_name'],'type'=>'text','label'=>false,'class'=>'form-control')); ?>
                    </div>

                    <div class="name_container" >
                        <label style="display:block;">Slot duration</label>

                        <select class="form-control" id="service_duration" name="service_duration">
                            <?php
                            $duration_array = array('1 MINUTE','2 MINUTES','3 MINUTES','4 MINUTES','5 MINUTES','6 MINUTES','7 MINUTES','8 MINUTES','9 MINUTES','10 MINUTES','12 MINUTES','15 MINUTES','18 MINUTES','20 MINUTES','25 MINUTES','30 MINUTES','45 MINUTES','1 HOUR','2 HOURS','3 HOURS','4 HOURS','5 HOURS','1 WEEK','2 WEEKS','2 WEEKS','1 MONTH','100 YEARS');
                            foreach($duration_array as $key => $duration){ ?>
                                <option <?php echo ($duration==$doctor_data['service_slot_duration'])?'selected':''; ?>  value="<?php echo $duration; ?>"><?php echo $duration; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="name_container" style="float:left; width: 100%;display: block;" id="time_box">
                        <label style="display: block;">Service Timing</label>
                        <div class="inner_box">
                            <label style="width: 100%;display: block;">From Time</label>
                            <input type="text" value="<?php echo date("H:i",strtotime($doctor_data['from_time'])); ?>" autocomplete="off" data-type="not_empty" name="from_time" class="form-control time_box validate_box">
                        </div>
                        <div class="inner_box">
                            <label style="width: 100%;display: block;">To Time</label>
                            <input type="text" value="<?php echo date("H:i",strtotime($doctor_data['to_time'])); ?>"  autocomplete="off" data-type="not_empty" name="to_time" class="form-control time_box validate_box">
                        </div>
                    </div>
                    <div  style="display: <?php echo $DTI_display; ?>; float: left;width: 100%;">
                        <h5 style="margin: 7px 0px;">Service Charges</h5>

                        <div class="name_container">
                            <label>Fee</label>
                            <?php echo $this->Form->input('service_amount',array('name'=>'service_amount','value'=>$doctor_data['service_amount'],'type'=>'number','label'=>false,'class'=>'form-control')); ?>
                        </div>
                        <div class="name_container">
                            <label>Video Consultation Fee</label>
                            <?php echo $this->Form->input('video_consulting_amount',array('name'=>'video_consulting_amount','value'=>$doctor_data['video_consulting_amount'],'type'=>'number','label'=>false,'class'=>'form-control')); ?>
                        </div>
                        <div class="name_container">
                            <label>Audio Consultation Fee</label>
                            <?php echo $this->Form->input('audio_consulting_amount',array('name'=>'audio_consulting_amount','value'=>$doctor_data['audio_consulting_amount'],'type'=>'number','label'=>false,'class'=>'form-control')); ?>
                        </div>
                        <div class="name_container">
                            <label>Chat Consultation Fee</label>
                            <?php echo $this->Form->input('chat_consulting_amount',array('name'=>'chat_consulting_amount','value'=>$doctor_data['chat_consulting_amount'],'type'=>'number','label'=>false,'class'=>'form-control')); ?>
                        </div>

                        </div>

                </div>
                <div class="col-12 buton_box" style="float: left;" >
                    <button type="submit" style="float: left;" class="btn btn-xs button btn-success"   id="profile_save_btn"><i class="fa fa-th-save" aria-hidden="true"></i> Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <i class="fa fa-gears"></i> Token Hours Setting
                </button>
            </h2>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body">
                <form id="hour_form">
                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                    <input type="hidden" name="st" value="hours">
                    <h3 class="box_heading">Edit Hours Setting</h3>
                    <div class="table-responsive">
                        <form method="post">
                            <table id="hours_table" class="table">
                                <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Day</th>
                                    <th width="24%">Time From</th>
                                    <th width="24%">Time To</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($hours_list as $key => $list){ ?>


                                    <tr>
                                        <?php
                                        $dowMap = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday','Sunday');
                                        $day = $dowMap[$list['AppointmentStaffHour']['appointment_day_time_id']-1];
                                        $id = $list['AppointmentStaffHour']['id'];

                                        $status =  $list['AppointmentStaffHour']['status'];
                                        ?>

                                        <td><?php echo $key+1; ?></td>
                                        <td><?php echo $day; ?>  </td>
                                        <td><input name="from_time[<?php echo $id; ?>]" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo date('H:i',strtotime($list['AppointmentStaffHour']['time_from'])); ?>" ></td>
                                        <td><input name="to_time[<?php echo $id; ?>]" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo date('H:i',strtotime($list['AppointmentStaffHour']['time_to'])); ?>" ></td>
                                        <td>
                                            <select class="form-control" name="status[<?php echo $id; ?>]" >
                                                <option value="OPEN" <?php echo ($status=='OPEN')?'selected':''; ?> >OPEN</option>
                                                <option value="CLOSED" <?php echo ($status=='CLOSED')?'selected':''; ?>>CLOSED</option>
                                            </select>
                                        </td>
                                    </tr>




                                <?php } ?>

                                <tr>
                                    <td colspan="5" style="text-align: right;">


                                        <button type="submit" class="btn  btn-success">Save</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <i class="fa fa-clock-o"></i> Token Break Setting
                </button>
            </h2>
        </div>
        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
            <div class="card-body">

                <form id="break_form">
                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                    <input type="hidden" name="st" value="breaks">
                    <h3 class="box_heading">Edit Break Setting</h3>
                    <div class="table-responsive">
                        <form method="post" id="break_form">
                            <table id="example" style="width: 100%;" class="table">
                                <tbody>
                                <?php foreach ($break_array as $key => $list){


                                    $dowMap = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
                                    $day = $dowMap[$list['appointment_day_time_id'] - 1];
                                    $day_num =  $list['appointment_day_time_id'];

                                    ?>
                                    <tr style="background: #6d6d6d;color: #fff;">
                                        <th style="width: 90%;"><?php echo $day; ?></th>
                                        <th><button type="button" data-day="<?php echo $list['appointment_day_time_id']; ?>" class="btn btn-info add_more_break"><i class="fa fa-plus"></i> Add Break</button></th>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <table style="width: 100%; border: none;" id="inner_table" class="table">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th style="width: 45%;">Time From</th>
                                                    <th style="width: 45%;">Time To</th>
                                                    <th></th>

                                                </tr>
                                                </thead>
                                                <tbody id="tbody<?php echo $day_num; ?>" class="append_child_table">
                                                <?php if($list['data_list']){ ?>
                                                    <?php foreach ($list['data_list'] as $key_break => $break){ ?>
                                                        <tr>
                                                            <td><?php echo $key_break+1; ?></td>
                                                            <td><input name="<?php echo $day_num."[from_time][]"; ?>" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo date('H:i',strtotime($break['time_from'])); ?>" ></td>
                                                            <td><input name="<?php echo $day_num."[to_time][]"; ?>" onkeydown="return false" class="form-control time_box" type="text" value="<?php echo date('H:i',strtotime($break['time_to'])); ?>" ></td>
                                                            <td><button type="button" class="btn btn-danger delete_break"><i class="fa fa-trash"></i></button></td>
                                                        </tr>
                                                    <?php } ?>

                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </td>

                                    </tr>



                                <?php } ?>

                                <tr>
                                    <td colspan="2" style="text-align: right;">

                                        <button type="submit" class="btn  btn-success">Save</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </form>


            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headineSix">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add More Counter
                </button>
            </h2>
        </div>
        <div id="collapseSix" class="collapse" aria-labelledby="headineSix" data-parent="#accordionExample">
            <div class="card-body">
                <div class="col-sm-12" style="text-align: center;">
                    <br>
                    <button type="button" data-number="" data-type="<?php echo $button_text; ?>" class="btn btn-warning add_more_btn"><i class="fa fa-plus"></i> More <?php echo $button_text; ?> </button>
                    <button type="button" data-number="" data-type="assistant" class="btn btn-success add_more_btn"><i class="fa fa-plus"></i> More Assistant </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headineEight">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                    <i class="fa fa-plus" aria-hidden="true"></i> Block Dates
                </button>
            </h2>
        </div>
        <div id="collapseEight" class="collapse" aria-labelledby="headineEight" data-parent="#accordionExample">
            <div class="card-body">
                <form id="break_form">
                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                    <input type="hidden" name="si" value="<?php echo base64_encode($doctor_data['service_id']); ?>">
                    <input type="hidden" name="ai" value="<?php echo base64_encode($doctor_data['address_id']); ?>">
                    <input type="hidden" name="st" value="block_date">
                    <h3 class="box_heading">Block Dates</h3>
                    <div class="col-12" id="time_box">
                        <div class="col-6">
                            <label style="width: 100%;display: block;">From Date</label>
                            <input type="text"  required value=""  autocomplete="off" data-type="not_empty" name="from_date" id ="from_date" class="form-control  validate_box">
                        </div>
                        <div class="col-6">
                            <label style="width: 100%;display: block;">To Date</label>
                            <input type="text" required  value="" autocomplete="off" data-type="not_empty" name="to_date" id="to_date" class="form-control  validate_box">
                        </div>
                    </div>
                    <div class="col-12 buton_box" style="padding-left: 0;padding-right: 0px; float: right;">
                        <br>


                        <button type="submit" class="btn  btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headineNine">
            <h2 class="mb-0">
                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                    <i class="fa fa-key" aria-hidden="true"></i> Update Password
                </button>
            </h2>
        </div>

        <div id="collapseNine" class="collapse" aria-labelledby="headineNine" data-parent="#accordionExample">
            <div class="card-body">
                <form id="password_form">
                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                    <input type="hidden" name="st" value="password">
                    <h3 class="box_heading">Update Password</h3>
                    <div class="col-12">
                        <label style="width: 100%;display: block;">New Password</label>
                            <input type="text"  required  autocomplete="off" data-type="not_empty" name="new_password" class="new_password form-control validate_box">
                    </div>
                    <div class="col-12">
                        <label style="width: 100%;display: block;">Confirm Password</label>
                        <input type="text"  required  autocomplete="off" data-type="not_empty" name="confirm_password" class="confirm_password form-control validate_box">
                    </div>
                    <div class="col-12">
                        <label class="mesage_box" style="width: 100%;display: block;color: red;text-align: center;margin-top: 6px;"></label>

                    </div>
                    <div class="col-12 buton_box" style="padding-left: 0;padding-right: 0px; float: right;">
                        <br>


                        <button type="submit" class="btn  btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>



<script type="text/javascript">
    $( document ).ready(function() {

        $('#from_date, #to_date').datepicker({
            setDate: new Date(),
            autoclose: true,
            format: 'dd-mm-yyyy',
            minDate:new Date()
        });

        $('.time_box').each(function (index,value) {
            $(this).wickedpicker({now:$(this).val(),twentyFour:true});
        })



        var img_loader = "<?php echo Router::url('/img/ajaxloader.gif',true); ?>";
        var baseurl = "<?php echo Router::url('/',true); ?>";
        $('#accordion').collapse({
            toggle: true
        });




        $(document).off('click','.setting_back');
        $(document).on('click','.setting_back',function(e){
            $("#logo_image").trigger('click');
        });


        $(document).off('click','.add_more_btn');
        $(document).on('click','.add_more_btn',function(e){



            /* this code add more counter and assitenat please use this code letter */
            var total_user = $(".doctor_input").length;
            var type = $(this).attr('data-type');



            var t = "<?php echo base64_encode($doctor_data['thinapp_id']); ?>";
            var allow_count = "<?php echo ($doctor_data['allowed_doctor_count']); ?>";
            if(total_user < allow_count ){
                var doctor_drp = '';
                var st = (type=='assistant')?'RECEPTIONIST':'DOCTOR';
                if(type=='assistant'){
                    doctor_drp = "<label>Associate with</label><select name='ass_di' class='form-control associate_with'>";
                    $(".doctor_selection .doctor_input").each(function () {
                        var name = $(this).closest('label').attr('data-name');
                        var value = $(this).val();
                        doctor_drp += "<option value="+value+">"+name+"</option>";
                    });
                    doctor_drp += "</select>";
                    content = '<form id="detail_form">';
                    content += '<input type="hidden" name="t" value="'+t+'">';
                    content += '<input type="hidden" name="staff_type" value="'+st+'">';
                    content += '<div class="col-12">'+doctor_drp+'<label>Assistant Name</label><input name="sn" type="text" autocomplete="off" data-type="not_empty" class="form-control counter_name validate_box"></div><div class="col-12"><label>Mobile Number</label><input type="number" name="sm"  autocomplete="off" maxlength="10"  class="form-control counter_number validate_box" data-type="mobile"></div>';
                    content += "</form>";
                }else{
                    content = '<form id="detail_form">';
                    content += '<input type="hidden" name="t" value="'+t+'">';
                    content += '<input type="hidden" name="staff_type" value="'+st+'">';
                    content += '<div class="col-12">'+doctor_drp+'<label>Counter/Executive Name</label><input name="sn" type="text" autocomplete="off" data-type="not_empty" class="form-control counter_name validate_box"></div><div class="col-12"><label>Mobile Number</label><input type="number" name="sm"  autocomplete="off" maxlength="10"  class="form-control counter_number validate_box" data-type="mobile"></div>';
                    content += $(".copy_service_box").html();
                    content += $(".copy_fees_div").html();
                    content += "</div></form>";
                }


                update_dialog = $.confirm({
                    title: "Add More Counter/Executive",
                    content:content,
                    type: 'red',
                    buttons: {
                        ok: {
                            text: "Save",
                            btnClass: 'btn-primary confirm_btn',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                var $btn = $(".confirm_btn");
                                var last = $(".confirm_btn").text();
                                var $form = this.$content.find("input, select");
                                var data_boj = {};

                                $.each($form, function (index,value) {
                                        data_boj[$(this).attr('name')] = $(this).val();
                                })


                                if(formValidation(this.$content.find(".counter_name")) && formValidation(this.$content.find(".counter_number"))){
                                    var di = this.$content.find(".associate_with").val();
                                    $.ajax({
                                        type:'POST',
                                        url: "<?php echo Router::url('/qutot/add_more_staff',true); ?>",
                                        data:{'data':data_boj},
                                        beforeSend:function(){
                                            $btn.text('Saving...');
                                            $btn.attr('disabled',true);
                                        },
                                        success:function(response){
                                            $btn.text(last);
                                            $btn.attr('disabled',false);
                                            response = JSON.parse(response);
                                            if (response.status == 1) {
                                                window.location.reload();
                                                update_dialog.close();

                                            }else{
                                                $.alert(response.message);
                                            }
                                        },
                                        error: function(data){
                                            $btn.text(last);
                                            $btn.attr('disabled',false);
                                        }
                                    });
                                    return false;
                                }else{
                                    return false;
                                }
                            }
                        },
                        cancel: function(){

                        },

                    },
                    onContentReady:function(){

                        this.$content.find('.time_box').each(function (index,value) {
                            $(this).wickedpicker({now:$(this).val(),twentyFour:true});
                        });


                        $(document).off('input','.validate_box');
                        $(document).on('input','.validate_box',function(e){
                            formValidation(this);


                        });
                    }
                });
            }else{

                var subscription_dialog = $.confirm({
                    title: "Subscription Upgrade Request",
                    content:"Your current subscription plan allow only "+allow_count+" "+type+"/Assistant. Please contact us on whatsapp for upgrade plan.",
                    type: 'red',
                    buttons: {
                        ok: {
                            text: '<i class="fa fa-whatsapp" aria-hidden="true"></i> Send Message',
                            btnClass: 'btn-success confirm_btn',
                            keys: ['enter'],
                            name:"ok",
                            action: function(e){
                                subscription_dialog.close();
                                window.open("https://api.whatsapp.com/send?phone=+918955004048", '_blank');
                            }
                        },
                        cancel: function(){
                            subscription_dialog.close();
                        },

                    }
                });


            }
        });




        var counter_mobile = [];
        $(".doctor_selection .doctor_input").each(function () {
            var m = $(this).val();
            counter_mobile.push(m.substring(m.length - 10));
        });

        function formValidation(obj){

            //return  true;
            var value_str =$(obj).val();
            var object =$(obj).closest('.col-12');
            $(object).find('.error_lbl').remove();
            var type =$(obj).attr('data-type');
            var message = '';
            if(type=='mobile'){
                if(value_str.length != 10 || !value_str.match('[1-9]{1}[0-9]{9}'))  {
                    message = 'Enter 10 digit valid mobile number';
                }else if(counter_mobile.includes(value_str)){
                    message = 'This mobile number already register';
                }
            }else if(type=='app_name'){
                var letters = /^[A-Za-z\s]+$/;
                if(value_str==''){
                    message = 'Please enter Clinic/Hospital name';
                }else if(!value_str.match(letters)){
                    message = 'Enter only alphabets';
                }
            }else if(type=='not_empty'){
                if(value_str=='')  {
                    message = 'This field cannot be empty';
                }
            }else if(type=='email'){
                if(value_str != ''){
                    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
                    if(!pattern.test(value_str)){
                        message='Please enter valid email address';
                    }
                }
            }
            if(message!=''){
                $(object).append("<label class='error_lbl'>"+message+"</label>");
                return false;
            }
            return true;
        }


        $(document).off('click','.edit_img_icon');
        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });

        $(document).off('input','.confirm_password, .new_password');
        $(document).on('input','.confirm_password, .new_password',function(e){

            var message ="Both password does not match";
            $(this).closest('form').find('.mesage_box').html(message);
            $(this).closest('form').find('[type="submit"]').attr('disabled','disabled');
            if($(".new_password").val() !='' && ($(".confirm_password").val() == $(".new_password").val())){
                $(this).closest('form').find('[type="submit"]').removeAttr('disabled');
                $(this).closest('form').find('.mesage_box').html('');
            }


        });





        $(document).off('click','.delete_break');
        $(document).on('click','.delete_break',function(e){
            $(this).closest('tr').remove();
        });


        $(document).off('click','.edit_img_icon_logo');
        $(document).on('click','.edit_img_icon_logo',function(e){
            $(".hidden_file_browse_logo").trigger("click");
        });

        $(document).off('submit','#profile_form, #hour_form, #break_form, #logo_form, #service_form, #password_form');
        $(document).on('submit','#profile_form, #hour_form, #break_form, #logo_form, #service_form, #password_form',function(e){
            e.preventDefault();

            var form_id = e.target.id;
            var allow_save = false;
            if(form_id=='logo_form' && confirm("Please confirm you uploaded right file for logo? If 'OK' then new logo will be visible for enter application")){
                allow_save = true;
            }else if(form_id=='hour_form' && confirm("Are you sure you want to update this setting? If 'OK' then your booked token will effect by changing this setting")){
                allow_save = true;
            }else if(form_id=='break_form' && confirm("Are you sure you want to update this setting? If 'OK' then your booked token will effect by changing this setting")){
                allow_save = true;
            }else if(form_id=='service_form' && confirm("Are you sure you want to update this setting? If 'OK' then consultation charges will effect by this setting")){
                allow_save = true;
            }else{
                allow_save = true;
            }

            if(allow_save===true){
                var formData = new FormData($(this)[0]);
                var $btn = $(this).find(':submit');
                var text = $($btn).text();
                $.ajax({
                    type:'POST',
                    url: baseurl+"qutot/save_setting",
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    beforeSend:function(){
                        $($btn).prop('disabled',true).text('Saving..');
                    },
                    success:function(response){
                        $($btn).prop('disabled',false).text(text);
                        var response = JSON.parse(response);
                        $.alert(response.message);
                    },
                    error: function(data){
                        $($btn).prop('disabled',false).text(text);
                        $.alert('Sorry, server is busy please try again letter');
                    }
                });
            }


        });

        $(document).off('change','.hidden_file_browse');
        $(document).on('change','.hidden_file_browse',function(e){
            if($(this).val()){
                $(".upload_media").show();
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });


        $(document).off('change','.hidden_file_browse_logo');
        $(document).on('change','.hidden_file_browse_logo',function(e){
            if($(this).val()){
                $(".upload_media").show();
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });


        function readURL(input) {
            var channel = $(input).closest('form').find('.channle_img_box');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(channel).css('background-image', "url('"+e.target.result+"')");
                    upload_file= true;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).off('click','.upload_media');
        $(document).on('click','.upload_media',function(e){
            var formData = new FormData($("#sub_frm")[0]);
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/upload_doctor_image",
                data:formData,
                cache:false,
                contentType: false,
                processData: false,
                beforeSend:function(){
                    $(".file_error, .file_success").html("");
                    $('.channle_img_box').html("<img src='"+img_loader+"'>");
                },
                success:function(data){
                    data = JSON.parse(data);

                    //$btn.button('reset');
                    $('.channle_img_box').html("");
                    if(data.status==1){
                        $(".image_box").val(data.url);
                        //$(".channel_img").attr("src",data.url);
                        upload_file =false;
                        $(".file_success").html(data.message);
                    }else{
                        $(".file_error").html(data.message);
                    }
                },
                error: function(data){
                    //$btn.button('reset');
                    $('.channle_img_box').css('background-image', "url('"+oldImg+"')");
                    $(".file_error").html("Sorry something went wrong on server.");
                }
            });

        })



        $('.time_box').keypress(function(event) {
            event.preventDefault();
            return false;
        });



        $(document).off('click','.add_more_break');
        $(document).on('click','.add_more_break',function(e){
            var day_num = $(this).attr('data-day');
            var total_row= $("#tbody"+day_num).find('tr').length+1;
            var from_name = day_num+"[from_time][]";
            var to_name = day_num+"[to_time][]";

            var string = '<tr><td>'+total_row+'</td><td><input name="'+from_name+'" value="08:00 AM" onkeydown="return false" class="form-control time_box" type="text"></td><td><input value="08:00 PM" name="'+to_name+'" onkeydown="return false" class="form-control time_box" type="text"></td><td><button type="button" class="btn btn-danger delete_break"><i class="fa fa-trash"></i></button></td></tr>';
            $("#tbody"+day_num).append(string);
            $('#break_form .time_box').wickedpicker({twentyFour:true});

        });


        $(document).off('change','.country');
        $(document).on('change','.country',function(e){
            var country_id =$(this).val();
            if(country_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"homes/get_state_list",
                    data:{country_id:country_id},
                    beforeSend:function(){
                        $('.state_spin').show();
                        $('.city, .state').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.state_spin').hide();
                        $('.state').html(data);;
                        if($('.state').attr('default')!=''){
                            $('.state').val($('.state').attr('default'));;
                            $('.state').attr('default','');
                            $(".state").trigger('change');

                        }

                        $('.city, .state').attr('disabled',false);
                        // $(".state").trigger('change');
                    },
                    error: function(data){
                    }
                });
            }

        });

        $(document).off('change','.state');
        $(document).on('change','.state',function(e){
            var state_id =$(this).val();
            if(state_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"homes/get_city_list",
                    data:{state_id:state_id},
                    beforeSend:function(){
                        $('.city_spin').show();
                        $('.city').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.city_spin').hide();
                        $('.city').attr('disabled',false).html(data);
                        if($('.city').attr('default')!=''){
                            $('.city').val($('.city').attr('default'));;
                            $('.city').attr('default','');
                        }
                    },
                    error: function(data){
                    }
                });
            }

        });

        if($(".country").val()!=""){
             $(".country").trigger('change');
        }

    });
</script>