<?php
           
            ?>
            <style>



.card-body .address_checkbox{
    width: 20px;
    height: 20px;
    margin: 5px auto;
    display: block;

}

                .card-body .buton_box{
                    margin: 5px 0px;
                    float: left;
                    display: block;
                    width: 100%;
                    padding: 0;
                    text-align: center;
                }
                .card-header button{
                    font-size: 1rem;
                    text-decoration: none !important;
                    color: #000;
                    margin: 0;
                    padding: 0;
                }


                .card-header{
                    padding: 0.5rem;
                    margin-bottom: 0;
                    background: #dddddd;
                    border-bottom: 0px solid rgba(0,0,0,.125);
                    border-radius: 0 !important;
                    margin: 2px I !important;

                }



                .accordion .card{
                    margin:2px;
                }

                .buton_box button{
                    float:none !important;
                }
                .mandatory{
                    color: red;
                    font-size: 8px;
                }
                #accordion{
                    margin: 0px;
                }
                #accordion .card-body{
                    padding: 0px 5px;
                }
                #settingModal .card-header {
                    background-color: #5D54FF;
                    border-bottom: #fff;
                    color: #fff;
                    padding: 0.3rem;
                }
                #settingModal .btn-link{
                    color: #fff;
                }
                .hidden_file_browse_logo, .upload_media,
                .hidden_file_browse, .upload_media{
                    display: none;
                }

                #service_form td, #service_form th,
                #address_form td, #address_form th,
                #break_form td, #break_form th,
                #hours_table td, #hours_table th{
                    padding: 5px 2px;
                    font-size: 0.9rem;
                }

                #service_form input, #service_form select,
                #address_form input, #address_form select,
                #break_form input, #break_form select,
                #hours_table input, #hours_table select{
                    height: 27px;
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
                    padding-bottom: 5px;
                    margin: 2px 0 0 0;
                    list-style: none;
                    font-size: 14px;
                    text-align: center;
                    background-color: #fff;
                    border: 1px solid #ccc;
                    border: 1px solid rgba(0, 0, 0, 0.15);
                    border-radius: 4px;
                    
                    background-clip: padding-box;
                }
            </style>

            

            <div class="accordion" id="accordion">
                <?php if($type=='app'){ ?>
                    <div class="card">
                        <div class="card-header" id="headingZero">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseZero" aria-expanded="false" aria-controls="collapseZero">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> Edit App Logo
                                </button>
                            </h2>
                        </div>
                        <div id="collapseZero" class="collapse" aria-labelledby="headingZero" data-parent="#accordion">
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
                                    <div class="col-12 buton_box">
                                        <button type="submit" class="btn btn-xs button btn-success"   id="logo_save_btn"><i class="fa fa-th-list" aria-hidden="true"></i> Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingSeven">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                    <i class="fa fa-picture-o" aria-hidden="true"></i> App Setting
                                </button>
                            </h2>
                        </div>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
                            <div class="card-body">
                                <h3 class="box_heading">App Setting</h3>

                                <form  id="app_setting_form">

                                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                                    <input type="hidden" name="st" value="app_setting">

                                    <div class="col-12">

                                        <div class="name_container">
                                            <label>Website URL</label>
                                            <?php echo $this->Form->input('website_url',array('value'=>$doctor_data['website_url'],'type'=>'text','label'=>false,'class'=>'form-control')); ?>

                                        </div>
                                        <div  class="name_container">
                                            <label>Tracker Message</label>
                                            <?php echo $this->Form->input('tune_tracker_media',array('value'=>$doctor_data['tune_tracker_media'],'type'=>'textarea','label'=>false,'class'=>'form-control')); ?>
                                        </div>

                                    </div>
                                    <div class="col-12 buton_box" >
                                        <button type="submit" class="btn btn-xs button btn-success"   id="app_setting_save"><i class="fa fa-th-list" aria-hidden="true"></i> Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php if($type=='doctor'){ ?>
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    <i class="fa fa-pencil"></i> Edit Profile
                                </button>
                            </h2>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                               
                                <form  enctype="multipart/form-data"  id="profile_form">
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
                                        $main_label = 'Executive Name';
                                        if(!in_array($doctor_data['category_name'],array('DOCTOR_OPDS','HOSPITALS','DOCTOR','HOSPITAL'))){
                                            $DTI_display = 'block';
                                            $main_label = 'Doctor Name';
                                        }


                                        if($doctor_data['allow_only_mobile_number_booking']=='NO'){
                                            $display = 'block';
                                        }
                                        ?>



                                        <div class="name_container">
                                            <label><?php echo $main_label; ?> <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                                            <?php echo $this->Form->input('name',array('value'=>$doctor_data['name'],'oninvalid'=>"this.setCustomValidity('Please enter name.')",'oninput'=>"setCustomValidity('')" ,'required'=>'required','type'=>'text','label'=>false,'class'=>'form-control')); ?>

                                        </div>
                                        <div style="display: <?php echo $DTI_display; ?>" class="name_container">
                                            <label>Education</label>
                                            <?php echo $this->Form->input('sub_title',array('value'=>$doctor_data['education'],'type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                        </div>
                                        <div style="display: <?php echo $DTI_display; ?>" class="name_container">
                                            <label>Category <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                                            <?php $type_array = $this->AppAdmin->getDepartmentCategoryDropdown(); ?>
                                            <?php echo $this->Form->input('department_category_id',array('value'=>$doctor_data['department_category_id'],'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select category.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Category','type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type cnt')); ?>
                                        </div>
                                        <div class="name_container">
                                            <label>Email</label>
                                            <?php echo $this->Form->input('email',array('value'=>$doctor_data['email'],'type'=>'text','label'=>false,'class'=>'form-control')); ?>

                                        </div>
                                       

                                        <div class="name_container">
                                            <label>Education</label>
                                            <?php echo $this->Form->input('sub_title',array('value'=>$doctor_data['sub_title'],'type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                        </div>
                                        
                                        <div class="name_container">
                                            <label>Emergency Fee Amount</label>
                                            <?php echo $this->Form->input('emergency_appointment_fee',array('value'=>$doctor_data['emergency_appointment_fee'],'type'=>'number','label'=>false,'class'=>'form-control')); ?>
                                        </div>
                                        
                                        <div class="name_container">
                                            <label>Show mobile to User</label>
                                            <?php $type_array = array('YES'=>'Yes','NO'=>'No'); ?>
                                            <?php echo $this->Form->input('show_mobile',array("id"=>"show_mobile",'type'=>'select','value'=>$doctor_data['show_mobile'], 'label'=>false,'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="name_container">
                                            <label>Show fee to User</label>
                                            <?php $type_array = array('YES'=>'Yes','NO'=>'No'); ?>
                                            <?php echo $this->Form->input('show_fees',array("id"=>"show_fees",'type'=>'select','value'=>$doctor_data['show_fees'], 'label'=>false,'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="name_container">
                                            <label>Allow payment mode </label>
                                            <?php $type_array = array('BOTH'=>'Both','CASH'=>'Cash','ONLINE'=>'Online'); ?>
                                            <?php echo $this->Form->input('payment_mode',array("id"=>"payment_mode",'type'=>'select','value'=>$doctor_data['payment_mode'], 'label'=>false,'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>
                                        

                                        <div class="name_container">
                                            <label>Adavance booking days</label>
                                            <?php echo $this->Form->input('allow_upcoming_days_booking',array('value'=>$doctor_data['allow_upcoming_days_booking'],'type'=>'number','label'=>false,'class'=>'form-control')); ?>
                                        </div>


                                        <div class="name_container">
                                            <label>Description (<span style="display: none;" id="char_cnt">0</span> / 250 Character Only)</label>
                                            <?php echo $this->Form->input('description',array('id'=>"description",'value'=>$doctor_data['description'],'type'=>'textarea','label'=>false,'class'=>'form-control')); ?>
                                        </div>



                                        <div  style="display: block; float: left;width: 100%;">
                                            <h5 style="margin: 7px 0px;">Allow Token Booking For</h5>
                                            <div class="appointment_type_section">

                                                <div class="name_container">
                                                    <label>Clinic/Hospital Visit Consultation</label>
                                                    <select class="form-control" name="data[is_offline_consulting]">
                                                        <option value="YES" <?php echo ($doctor_data['is_offline_consulting']=='YES')?'selected':''; ?>>Yes</option>
                                                        <option value="No" <?php echo ($doctor_data['is_offline_consulting']=='NO')?'selected':''; ?>>No</option>
                                                    </select>
                                                    <label >Video Consultation</label>
                                                    <select class="form-control" name="data[is_online_consulting]">
                                                        <option value="YES" <?php echo ($doctor_data['is_online_consulting']=='YES')?'selected':''; ?>>Yes</option>
                                                        <option value="No" <?php echo ($doctor_data['is_online_consulting']=='NO')?'selected':''; ?>>No</option>
                                                    </select>


                                                    <!--label >Chat Consultation</label>
                                                    <select class="form-control" name="data[is_chat_consulting]">
                                                        <option value="YES" <?php echo ($doctor_data['is_chat_consulting']=='YES')?'selected':''; ?>>Yes</option>
                                                        <option value="No" <?php echo ($doctor_data['is_chat_consulting']=='NO')?'selected':''; ?>>No</option>
                                                    </select-->

                                                    <label >Audio Consultation</label>
                                                    <select class="form-control" name="data[is_audio_consulting]">
                                                        <option value="YES" <?php echo ($doctor_data['is_audio_consulting']=='YES')?'selected':''; ?>>Yes</option>
                                                        <option value="No" <?php echo ($doctor_data['is_audio_consulting']=='NO')?'selected':''; ?>>No</option>
                                                    </select>

                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                    <div class="col-12 buton_box" >
                                        <button type="reset"  class="btn btn-xs button btn-warning"><i class="fa fa-refresh" aria-hidden="true"></i> Reset</button>
                                        <button type="submit"  class="btn btn-xs button btn-success"   id="profile_save_btn"><i class="fa fa-th-save" aria-hidden="true"></i> Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingFive">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    <i class="fa fa-pencil"></i> Appointment Address
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                            <div class="card-body">
                               
                                <div class="table-responsive">
                                    <form method="post" id="address_form">

                                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                                    <input type="hidden" name="st" value="appointment_address">
                                    


                                        <table id="example" class="table">
                                            
                                            <tbody>
                                            <?php foreach ($address_list as $key => $list){ 
                                                
                                                $background = ($key%2==0)?"#ededed":"#ffffff";

                                                ?>

                                                 <tr style="background:<?php echo $background; ?>">
                                                        <td colspan="2" style="font-weight:600; border-top:1px solid #dee2e6;">
                                                            <?php echo $list['address']; ?>
                                                            <input class="address_checkbox" style="float: right; margin-right:10px;" type="checkbox" name="box[<?php echo $list['address_id']; ?>]" <?php echo !empty($list['selected'])?'checked':''; ?> >
                                                        </td>

                                                </tr>
                                                <tr style="background:<?php echo $background; ?>">
                                                    
                                                    
                                                    <td style="border-top:none; ">
                                                        <label>Time From</label>
                                                        <input name="from_time[<?php echo $list['address_id']; ?>]" class="form-control time_box hasWickedpicker" type="text" value="<?php echo !empty($list['from_time'])?$list['from_time']:'00:00'; ?>" >
                                                    </td>
                                                    <td style="border-top:none;">
                                                        <label>Time To</label>
                                                        <input name="to_time[<?php echo $list['address_id']; ?>]"  class="form-control time_box hasWickedpicker" type="text" value="<?php echo !empty($list['to_time'])?$list['to_time']:'00:00'; ?>" >
                                                    </td>
                                                   
                                                </tr>




                                            <?php } ?>

                                            
                                            </tbody>
                                        </table>
                                        <div class="col-12 buton_box">
                                            <button type="reset" class="btn  btn-warning">Reset</button>
                                            <button type="submit" class="btn  btn-success">Save</button>
                                        </div>
                                    </form>
                                    
                                </div>

                            </div>
                               
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingSix">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    <i class="fa fa-gears"></i> Appointment Services
                                </button>
                            </h2>
                        </div>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                            <div class="card-body">
                                <form id="hour_form">
                                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                                    <input type="hidden" name="st" value="hours">
                                 
                                    <div class="table-responsive">
                                    <form method="post" id="service_form">

                                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                                    <input type="hidden" name="st" value="appointment_service">
                                    

                                        <table id="example" class="table">
                                            
                                            <tbody>
                                            <?php foreach ($service_list as $key => $list){
                                                
                                                 $background = ($key%2==0)?"#ededed":"#ffffff";
                                                
                                                ?>

                                                <tr style="background:<?php echo $background; ?>">
                                                        <td colspan="3" style="font-weight:600; border-top:1px solid #dee2e6;">
                                                            <?php echo $list['name']; ?> 
                                                            <input style="float: right; margin-right: 10px;" class="address_checkbox" type="radio" value="<?php echo $list['service_id']; ?>" name="selected_service" <?php echo !empty($list['selected'])?'checked':''; ?> >
                                                        </td>
                                                        
                                                </tr>
                                                <tr style="background:<?php echo $background; ?>">
                                                    <td style="border-top:none; ">
                                                        <label>Clinic Fee</label>
                                                        <input name="service[<?php echo $list['service_id']; ?>][service_amount]" class="form-control" type="number" value="<?php echo $list['service_amount']; ?>" >
                                                    </td>
                                                    <td style="border-top:none;">
                                                        <label>Video Fee</label>
                                                        <input name="service[<?php echo $list['service_id']; ?>][video_consulting_amount]"  class="form-control" type="number" value="<?php echo $list['video_consulting_amount']; ?>" >
                                                    </td>
                                                    <td style="border-top:none;">
                                                        <label>Audio Fee</label>
                                                        <input name="service[<?php echo $list['service_id']; ?>][audio_consulting_amount]"  class="form-control" type="number" value="<?php echo $list['audio_consulting_amount']; ?>" >
                                                    </td>
                                                </tr>




                                            <?php } ?>

                                            
                                            </tbody>
                                        </table>
                                        <div class="col-12 buton_box">
                                            <button type="reset" class="btn  btn-warning">Reset</button>
                                            <button type="submit" class="btn  btn-success">Save</button>
                                        </div>
                                    </form>
                                   
                                </div>            
                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <i class="fa fa-gears"></i> Appointment Hours Setting
                                </button>
                            </h2>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <form id="hour_form">
                                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                                    <input type="hidden" name="st" value="hours">
                                  
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

                                               
                                                </tbody>
                                            </table>

                                            <div class="col-12 buton_box">
                                                <button type="reset" class="btn  btn-warning">Reset</button>
                                                <button type="submit" class="btn  btn-success">Save</button>
                                            </div>

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
                                    <i class="fa fa-clock-o"></i> Appointment Break Setting
                                </button>
                            </h2>
                        </div>
                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                            <div class="card-body">

                                <form id="break_form">
                                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                                    <input type="hidden" name="st" value="breaks">
                                    
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

                                               
                                                </tbody>
                                            </table>
                                            <div class="col-12 buton_box">
                                            <button type="reset" class="btn  btn-warning">Reset</button>
                                            <button type="submit" class="btn  btn-success">Save</button>
                                            </div>
                                        </form>
                                    </div>

                                   
                                </form>


                            </div>
                        </div>
                    </div>


                    <div class="card" style="border: none;">
                        <div class="card-header" id="headingFour">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <i class="fa fa-clock-o"></i> Block Date
                                </button>
                            </h2>
                        </div>
                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                            <div class="card-body">

                                <form id="block_form">
                                    <input type="hidden" name="di" value="<?php echo base64_encode($doctor_data['doctor_id']); ?>">
                                    <input type="hidden" name="st" value="block_date">
                                    

                                    <label>Please select date</label>
                                    <input type="text" name="blockDate" id="blockDate" class="form-control" >


                                    <?php if(count($block_array) > 0){ ?>
                                            <br>
                                    <label>Upcoming Block Dates</label>
                                    <div class="table-responsive">
                                            <table id="example12" style="width: 100%;" class="table-bordered">
                                                <tbody>
                                                <?php $counter=1; foreach ($block_array as $key => $list){  ?>
                                                    <tr>
                                                        <td style="text-align: center;" ><?php echo $counter++; ?></td>
                                                        <td style="text-align: center;"><b><?php echo date('d/m/Y',strtotime($list['blocked_date'])); ?></b></td>
                                                        <td style="text-align: center;"><a href="javascript:void(0)" data-id="<?php echo base64_encode($list['id']); ?>" class="unblock_date"><i class="fa fa-trash" style="color: red;" ></i></a> </td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                    </div>
                                    <?php } ?>

                                    <div class="col-12 buton_box">
                                    <button type="reset" class="btn  btn-warning">Reset</button>
                                    <button type="submit" class="btn  btn-success">Save</button>
                                    </div>

                                </form>


                            </div>
                        </div>
                    </div>





                <?php } ?>





            </div>
            <script type="text/javascript">
                $( document ).ready(function() {
                  
                  
                  
                  
                    $('.time_box').each(function (index,value) {
                        var value = $(this).val();
                        var option  = {twentyFour:true};
                        if(value!=""){
                            option = {now:value,twentyFour:true}
                        }
                        var obj = $(this).wickedpicker(option);
                    });

                    $(document).off('focus',".time_box");
                    $(document).on('focus',".time_box",function(){
                        $('.time_box').wickedpicker('hide');
                        $(this).wickedpicker('show');
                    });


 
                    if($("#description").length > 0){
                        CKEDITOR.replace( "description",{
                            toolbarGroups: [
                                { name: 'links', groups: [ 'links' ] },
                                { name: 'colors', groups: [ 'colors' ] },
                                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                                {name: 'styles', groups: ['styles']},
                                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
                            ],
                            removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
                            autoParagraph :false,
                            enterMode : CKEDITOR.ENTER_BR,
                            shiftEnterMode: CKEDITOR.ENTER_P
                        } );
                    }


                    if($("#tune_tracker_media").length > 0){
                        CKEDITOR.replace( "tune_tracker_media",{
                            toolbarGroups: [
                                { name: 'links', groups: [ 'links' ] },
                                { name: 'colors', groups: [ 'colors' ] },
                                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                                {name: 'styles', groups: ['styles']},
                                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
                            ],
                            removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
                            autoParagraph :false,
                            enterMode : CKEDITOR.ENTER_BR,
                            shiftEnterMode: CKEDITOR.ENTER_P
                        } );
                    }



                    var img_loader = "<?php echo Router::url('/img/ajaxloader.gif',true); ?>";
                    var baseurl = "<?php echo Router::url('/',true); ?>";
                    $('#accordion').collapse({
                        toggle: true
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
                    $(document).off('click','.delete_break');
                    $(document).on('click','.delete_break',function(e){
                        $(this).closest('tr').remove();
                    });


                    $(document).off('click','.edit_img_icon_logo');
                    $(document).on('click','.edit_img_icon_logo',function(e){
                        $(".hidden_file_browse_logo").trigger("click");
                    });

                    $(document).off('submit','#service_form, #address_form, #profile_form, #hour_form, #break_form, #logo_form, #service_form, #app_setting_form, #block_form');
                    $(document).on('submit','#service_form, #address_form, #profile_form, #hour_form, #break_form, #logo_form, #service_form, #app_setting_form, #block_form',function(e){
                        e.preventDefault();

                        var currentForm = $(this);
                        var tab_id = $(currentForm).closest(".card").find(".card-header").attr('id');

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
                        }else if(form_id=='app_setting_form' && confirm("Are you sure you want to update this setting?")){
                            allow_save = true;
                        }else if(form_id=='block_form'){
                            if($("#blockDate").val()==""){
                                alert("Please select date for block")
                            }else if(confirm("Are you sure? If appointment booked on this date will be canceled.")){
                                allow_save = true;
                            }
                        }else{
                            allow_save = true;
                        }

                        if(allow_save===true){
                            var formData = new FormData($(this)[0]);
                            var $btn = $(this).find(':submit');
                            var text = $($btn).text();
                            $.ajax({
                                type:'POST',
                                url: baseurl+"homes/mq_save_setting",
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
                                    if(response.status==1){
                                        flashMessage('success',response.message);
                                    }else{
                                        flashMessage('error',response.message);
                                    }
                                    
                                    $("#dash_doc_setting button").trigger('click');
                                    setTimeout(function(){
                                        $("#"+tab_id).find("button").trigger("click");
                                    },1000);    

                                },
                                error: function(data){
                                    $($btn).prop('disabled',false).text(text);
                                    flashMessage('error',"'Sorry, server is busy please try again letter'");
                                }
                            });
                        }


                    });

                    $(document).off('click','.unblock_date');
                    $(document).on('click','.unblock_date',function(e){
                        var $btn = $(this);
                        var text = $($btn);
                        var bi = $(this).attr('data-id');
                        $.ajax({
                            type:'POST',
                            url: baseurl+"homes/unblockDate",
                            data:{bi:bi},
                            beforeSend:function(){
                                $($btn).prop('disabled',true).text('...');
                            },
                            success:function(response){
                                $($btn).prop('disabled',false).text(text);
                                var response = JSON.parse(response);
                                $.alert(response.message);
                                $($btn).closest("tr").remove();
                            },
                            error: function(data){
                                $($btn).prop('disabled',false).text(text);
                                $.alert('Sorry, server is busy please try again letter');
                            }
                        });
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


                    $(document).off('input','#description');
                    $(document).on('input','#description',function(e){
                        $("#char_cnt").html($(this).val().length);
                    });

                    $("#description").trigger("input");


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