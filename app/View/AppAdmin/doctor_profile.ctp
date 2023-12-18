<?php
$login = $this->Session->read('Auth.User');
$country_list =$this->AppAdmin->countryDropdown();



?>



<div class="Home-section-2">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <?php echo $this->element('message'); ?>
                    <?php if($login['USER_ROLE'] =="ADMIN" || @$login['AppointmentStaff']['staff_type'] == "DOCTOR" ){  ?>
                    <button type="button" class="btn btn-info" style="margin-top:22px;" data-toggle="collapse" data-target="#demo">Click To Update Consent</button>
                    <div id="demo" class="collapse">
                        <!-- consent section start here -->

                            <h3 class="screen_title">Appointment Consent Template</h3>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                                <?php echo $this->Form->create('Consent',array('type'=>'file','method'=>'post','class'=>'form-horizontal','url'=>array('controller'=>'app_admin','action' => "save_appointment_consent/".base64_encode($id)))); ?>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="consent_title_heading" >Online Appointment Consent</h3>
                                    <div class="form-group">
                                        <div class="col-sm-8">
                                            <label>Consent Title <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>

                                            <?php echo $this->Form->input('online_id',array('value'=>@$consent_data['ONLINE_APPOINTMENT']['id'],'type'=>'hidden','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                            <?php echo $this->Form->input('online_title',array('value'=>@$consent_data['ONLINE_APPOINTMENT']['consent_title'], 'required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter  title.')",'oninput'=>"setCustomValidity('')" ,'type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Status</label>
                                            <?php $type_array = array('ACTIVE'=>'Active','INACTIVE'=>'Inactive','DELETED'=>'Deleted'); ?>
                                            <?php echo $this->Form->input('online_status',array('value'=>@$consent_data['ONLINE_APPOINTMENT']['status'],'type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>
                                        <div class="col-sm-12">
                                            <label>Consent</label>
                                            <?php echo $this->Form->input('online_text',array('value'=>@$consent_data['ONLINE_APPOINTMENT']['consent_message'],'type'=>'textarea','placeholder'=>'','label'=>false,'id'=>'offline_text','class'=>'form-control cnt')); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <h3 class="consent_title_heading">Offline Appointment Consent</h3>
                                    <div class="form-group">
                                        <div class="col-sm-8">
                                            <label>Consent Title <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                                            <?php echo $this->Form->input('offline_id',array('value'=>@$consent_data['OFFLINE_APPOINTMENT']['id'], 'required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter  title.')",'oninput'=>"setCustomValidity('')" ,'type'=>'hidden','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>

                                            <?php echo $this->Form->input('offline_title',array('value'=>@$consent_data['OFFLINE_APPOINTMENT']['consent_title'], 'type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Status</label>
                                            <?php $type_array = array('ACTIVE'=>'Active','INACTIVE'=>'Inactive','DELETED'=>'Deleted'); ?>
                                            <?php echo $this->Form->input('offline_status',array('value'=>@$consent_data['OFFLINE_APPOINTMENT']['status'],'type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control')); ?>
                                        </div>
                                        <div class="col-sm-12">
                                            <label>Consent</label>
                                            <?php echo $this->Form->input('offline_text',array('value'=>@$consent_data['OFFLINE_APPOINTMENT']['consent_message'],'type'=>'textarea','placeholder'=>'','label'=>false,'id'=>'online_text','class'=>'form-control cnt')); ?>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-9">
                                        <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                        <input type="reset" class="btn btn-warning" value="Reset">
                                        <input type="submit" class="btn btn-success" value="Save">
                                    </div>
                                </div>

                            </div>
                            <?php echo $this->Form->end(); ?>

                        <!-- consent section end here -->
                    </div>
                    <?php } ?>
                    <!-- profile section start here-->
                    <h3 class="screen_title">Edit Doctor/ Receptionist</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">
                        <?php $options = array('YES'=>'Yes','NO'=>'No');  echo $this->Form->create('AppointmentStaff',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>
                        <div class="col-lg-9 col-md-9 col-sm-9">
                            <div class="form-group">
                                <?php  if($login['USER_ROLE'] =="ADMIN"){  ?>
                                <div class="col-sm-4">
                                    <label>Staff Type</label>
                                    <?php $type_array = array('DOCTOR'=>'Doctor','RECEPTIONIST'=>'Receptionist'); ?>
                                    <?php echo $this->Form->input('staff_type',array("id"=>"staff_type",'type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control')); ?>
                                </div>
                                <?php } ?>

                                <div class="col-sm-4">
                                    <label>Name <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                                    <?php echo $this->Form->input('name',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter  name.')",'oninput'=>"setCustomValidity('')" ,'type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                </div>

                                <div class="col-sm-4">
                                    <label>Name Label </label>
                                    <?php echo $this->Form->input('name_label',array('type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                </div>


                                <div class="col-sm-4">
                                    <label>Email</label>
                                    <?php echo $this->Form->input('email',array('oninvalid'=>"this.setCustomValidity('Please enter email.')",'oninput'=>"setCustomValidity('')" ,'type'=>'email','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <label>Mobile <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                                    <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <label>Category <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                                    <?php $type_array = $this->AppAdmin->getDepartmentCategoryDropdown(); ?>
                                    <?php echo $this->Form->input('department_category_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select category.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Category','type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type cnt')); ?>
                                </div>
                                <div class="col-sm-4">
                                    <label>Department <i class="fa fa-asterisk mandatory" aria-hidden="true"></i></label>
                                    <?php $type_array = $this->AppAdmin->getDoctorCategoryDropdown($login['User']['thinapp_id']); ?>
                                    <?php echo $this->Form->input('appointment_category_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select department.')",'oninput'=>"setCustomValidity('')",'empty'=>array('0'=>'Select Department'),'type'=>'select','label'=>false,'options'=>$type_array,'class'=>'form-control message_type cnt')); ?>
                                </div>

                                <?php if($login['USER_ROLE'] =="ADMIN"){ ?>
                                    <div class="col-sm-4 border_div">
                                        <div class="radio_lbl_vac">
                                            <?php
                                            $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_refund_payment'],'legend' => "Can refund payment?",'class'=>'radio-inline','div'=>'label');
                                            echo $this->Form->radio('allow_refund_payment', $options, $attributes);
                                            ?>
                                        </div>
                                    </div>
                                <?php } ?>


                                <?php if($login['USER_ROLE'] =="ADMIN" && $login['Thinapp']['category_name'] =='HOSPITAL' ){ ?>
                                    <div class="reception_div">



                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php

                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['edit_appointment_payment'],'legend' => "Can receptionist edit payment?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('edit_appointment_payment', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php

                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_reschedule_appointment'],'legend' => "Can receptionist reschedule appointment?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_reschedule_appointment', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_block_appointment_slot'],'legend' => "Can receptionist block appointment time slot?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_block_appointment_slot', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php

                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_date_wise_report'],'legend' => "Can receptionist see date wise report?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_date_wise_report', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php

                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_product_wise_report'],'legend' => "Can receptionist see product wise report?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_product_wise_report', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_change_appointment_doctor'],'legend' => "Can receptionist assign appointment to other doctor?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_change_appointment_doctor', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_book_past_appointment'],'legend' => "Can receptionist book past appointment?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_book_past_appointment', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>



                                        <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"SMART_CLINIC")){ ?>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_web_token_booking'],'legend' => "Can receptionist book online tokens?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_web_token_booking', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <?php } ?>




                                    </div>
                                <?php } ?>

                                <div class="doctor_section" style="<?php echo (@$this->request->data['AppointmentStaff']['staff_type']=='RECEPTIONIST')?'display: none':''; ?>">

                                        <div class="col-sm-4">
                                            <label>Country</label>
                                            <?php echo $this->Form->input('country_id',array('oninvalid'=>"this.setCustomValidity('Please select country.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select Country'),'options'=>$country_list,'class'=>'form-control country')); ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <?php $state_list =@$this->AppAdmin->stateDropdown(@$this->request->data['AppointmentStaff']['country_id']);?>
                                            <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                                            <?php echo $this->Form->input('state_id',array('oninvalid'=>"this.setCustomValidity('Please select state.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select State'),'options'=>$state_list,'class'=>'form-control state')); ?>
                                        </div>


                                        <div class="col-sm-4">
                                            <?php $city_list =@$this->AppAdmin->cityDropdown(@$this->request->data['AppointmentStaff']['state_id']);?>
                                            <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                                            <?php echo $this->Form->input('city_id',array('oninvalid'=>"this.setCustomValidity('Please select city.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select City'),'options'=>$city_list,'class'=>'form-control city')); ?>
                                        </div>




                                        <div class="col-sm-4">
                                            <label>DOB</label>
                                            <?php echo $this->Form->input('dob',array('value'=>'','autocomplete'=>'off','type'=>'text','label'=>false,'class'=>'dob form-control datepicker')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <?php $year =array();for($counter=0;$counter<=150;$counter++)$year[]=$counter;
                                            $year_value = $month_value = 0;
                                            $experience = $this->request->data['AppointmentStaff']['experience'];
                                            if(!empty($experience)){
                                                $tmp = explode(".",$experience);
                                                $year_value = @$tmp[0];
                                                $month_value = !empty($tmp[1])?$tmp[1]:0;
                                            }

                                            ?>
                                            <label>Total Year Exp.</label>
                                            <?php echo $this->Form->input('year',array('value'=>$year_value,'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select year experience.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'options'=>$year,'class'=>'form-control year')); ?>
                                        </div>
                                        <div class="col-sm-4">
                                            <label>Total Month Exp.</label>
                                            <?php $month =array();for($counter=0;$counter<=12;$counter++)$month[]=$counter; ?>
                                            <?php echo $this->Form->input('month',array('value'=>$month_value, 'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select month experience.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'options'=>$month,'class'=>'form-control month')); ?>
                                        </div>

                                        <div class="col-sm-4">

                                            <label>Education/Designation</label>
                                            <?php echo $this->Form->input('sub_title',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                        </div>

                                        <div class="col-sm-4">

                                            <label>Commission(%)</label>
                                            <?php echo $this->Form->input('commission',array('type'=>'number','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                        </div>

                                        <div class="col-sm-4">

                                            <label>Website Url</label>
                                            <?php echo $this->Form->input('website_url',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>





                                        <div class="col-sm-4">
                                            <label>Registration Number</label>
                                            <?php echo $this->Form->input('registration_number',array('type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Twitter URL</label>
                                            <?php echo $this->Form->input('twitter_url',array('type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                        </div>





                                        <div class="col-sm-4">
                                            <label>LinkedIn URL</label>
                                            <?php echo $this->Form->input('linkedin_url',array('type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Instagram URL</label>
                                            <?php echo $this->Form->input('instagram_url',array('type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Facebook URL</label>
                                            <?php echo $this->Form->input('facebook_url',array('type'=>'text','label'=>false,'class'=>'form-control')); ?>
                                        </div>



                                        <div class="col-sm-4">
                                            <label>Report Patient Tracker Queue Difference</label>
                                            <?php echo $this->Form->input('lab_report_patient_queue_after_number',array('type'=>'number','max'=>100, 'min'=>0,'label'=>false,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Emergency Patient Tracker Queue Difference</label>
                                            <?php echo $this->Form->input('emergency_appointment_queue_after_number',array('type'=>'number','max'=>100, 'min'=>0,'label'=>false,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Late Patient Tracker Queue Difference</label>
                                            <?php echo $this->Form->input('late_check_in_queue_after_number',array('type'=>'number','max'=>100, 'min'=>0,'label'=>false,'class'=>'form-control')); ?>
                                        </div>

                                        <div class="col-sm-4">
                                            <label>Priority Patient Tracker Queue Difference</label>
                                            <?php echo $this->Form->input('other_check_in_queue_after_number',array('type'=>'number','max'=>100, 'min'=>0,'label'=>false,'class'=>'form-control')); ?>
                                        </div>




                                        <div class="col-sm-4 border_div">


                                            <div class="radio_lbl_vac">

                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_mobile'],'legend' => "Can user see mobile number?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_mobile', $options, $attributes);

                                                ?>

                                            </div>


                                        </div>
                                        <div class="col-sm-4 border_div">


                                            <div class="radio_lbl_vac">

                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_fees'],'legend' => "Can user see appointment fees?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_fees', $options, $attributes);

                                                ?>

                                            </div>


                                        </div>


                                        <div id="third_box" class="col-sm-4 border_div clearfix" >


                                            <div class="radio_lbl_vac">

                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_appointment_time'],'legend' => "Can user see appointment time?",'class'=>'radio-inline','div'=>'label','id'=>'show_appointment_time','data_type'=>'time');
                                                echo $this->Form->radio('show_appointment_time', $options, $attributes);

                                                ?>

                                            </div>


                                        </div>
                                        <div class="col-sm-4 border_div">


                                            <div class="radio_lbl_vac">

                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_appointment_token'],'legend' => "Can user see appointment token?",'class'=>'radio-inline','div'=>'label','id'=>'show_appointment_token','data_type'=>'token');
                                                echo $this->Form->radio('show_appointment_token', $options, $attributes);

                                                ?>

                                            </div>


                                        </div>


                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('BOTH'=>'Every Where','APP'=>'App','WEB'=>'Web');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['visible_for'],'legend' => "Doctor is visible on",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('visible_for', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>


                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_emergency_appointment'],'legend' => "Can user book emergency appointment?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_emergency_appointment', $options, $attributes);

                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_extend_time_on_tracker'],'legend' => "Show approx time on tracker?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_extend_time_on_tracker', $options, $attributes);

                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['is_offline_consulting'],'legend' => "Allow Clinic/Hospital consultation?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('is_offline_consulting', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['is_online_consulting'],'legend' => "Allow video consultation?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('is_online_consulting', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['is_audio_consulting'],'legend' => "Allow audio consultation?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('is_audio_consulting', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['is_chat_consulting'],'legend' => "Allow chat consultation?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('is_chat_consulting', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>


                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_whats_app_video_call'],'legend' => "Allow whats app video call?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_whats_app_video_call', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>



                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['user_can_video_call'],'legend' => "Allow patient to make app video call to doctor?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('user_can_video_call', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>


                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['user_can_whats_app_video_call'],'legend' => "Allow patient to make whatsapp video call to doctor?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('user_can_whats_app_video_call', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_booked_slot_to_patient'],'legend' => "Show booked appointment slots to app for patient?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_booked_slot_to_patient', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                    <div class="col-sm-4 border_div">
                                        <div class="radio_lbl_vac">
                                            <?php
                                            $attributes = array('value'=>@$this->request->data['AppointmentStaff']['print_priscription_when_pending_amount'],'legend' => "Print prescription of pending amount appointments?",'class'=>'radio-inline','div'=>'label');
                                            echo $this->Form->radio('print_priscription_when_pending_amount', $options, $attributes);
                                            ?>
                                        </div>
                                    </div>



                                        <?php if($login['USER_ROLE'] =="ADMIN"){ ?>

                                            <div class="col-sm-4 border_div">
                                                <div class="radio_lbl_vac">
                                                    <?php
                                                    $attributes = array('value'=>@$this->request->data['AppointmentStaff']['consultation_video_recording'],'legend' => "Save recording for online consultation?",'class'=>'radio-inline','div'=>'label');
                                                    echo $this->Form->radio('consultation_video_recording', $options, $attributes);
                                                    ?>
                                                </div>
                                            </div>



                                        <?php } ?>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_time_slot_to_patient'],'legend' => "Show doctor to patient to book appointment?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_time_slot_to_patient', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                    <?php if($this->AppAdmin->check_app_enable_permission($login['User']['thinapp_id'],"QUEUE_MANAGEMENT_APP")){ ?>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('BOOKED_TOKEN_WISE'=>'Token Wise','SEQUENCE_WISE'=>'Sequence Wise');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['increase_token_on_traker'],'legend' => "How to move queue management tracker?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('increase_token_on_traker', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'No');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_doctor_on_tracker'],'legend' => "Show this Doctor/Service on tracker?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_doctor_on_tracker', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border_div">
                                            <label>Show token list for Digital TUD</label>
                                            <?php
                                                $doc_list_options = array();
                                                foreach ($doctorList as $key =>$doc){
                                                    $doc_list_options[$doc['id']] = $doc['name'];
                                                }
                                                echo $this->Form->input('show_token_into_digital_tud',array('empty'=>array('0'=>'Select Doctor/Counter'),'type'=>'select','label'=>false,'options'=>$doc_list_options,'class'=>'form-control message_type cnt'));
                                            ?>
                                        </div>

                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('BOOKED'=>'Booked Token','NON_BOOKED'=>'NonBooked Token');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_token_list_on_digitl_tud'],'legend' => "Show token list on digital TUD",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_token_list_on_digitl_tud', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>

                                         <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'NO');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['show_into_pine_device'],'legend' => "Show doctor into Pine Device?",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('show_into_pine_device', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>


                                        <div class="col-sm-4 border_div">
                                            <div class="radio_lbl_vac">
                                                <?php
                                                $options = array('YES'=>'Yes','NO'=>'NO');
                                                $attributes = array('value'=>@$this->request->data['AppointmentStaff']['allow_multiple_login'],'legend' => "Allow multiple login in device",'class'=>'radio-inline','div'=>'label');
                                                echo $this->Form->radio('allow_multiple_login', $options, $attributes);
                                                ?>
                                            </div>
                                        </div>






                                    <?php } ?>

                                        <div class="col-sm-4 border_div">
                                            <label>Emergency Fee</label>
                                            <?php echo $this->Form->input('emergency_appointment_fee',array('type'=>'number','label'=>false,'class'=>'form-control')); ?>
                                        </div>
										
                                         <div class="col-sm-4 border_div">
                                            <label>SEO Page Id</label>
                                            <?php echo $this->Form->input('post_id',array('type'=>'number','label'=>false,'class'=>'form-control')); ?>
                                        </div>

										<div class="col-sm-12">

                                        <label>Blog Address Iframe</label>
                                        <?php echo $this->Form->input('blog_map',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        
                                        
                                        <div class="col-sm-12">
                                            <label>Google Review Link</label>
                                            <?php echo $this->Form->input('google_review_link',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>
                                        
                                        
                                        <div class="col-sm-12">

                                            <label>Address</label>
                                            <?php echo $this->Form->input('address',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                        </div>



                                        <div class="col-sm-12">
                                            <label>About Doctor/Receptionist</label>
                                            <?php echo $this->Form->input('description',array('row'=>'10','type'=>'textarea','label'=>false,'class'=>'form-control cnt')); ?>                                </div>

                                </div>



                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-9">
                                    <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                    <input type="reset" class="btn btn-warning" value="Reset">
                                    <input type="submit" class="btn btn-success" value="Save">
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3">

                            <div class="form-group">

                                <label>Upload Profile Image</label>
                                <?php
                                $path  = @$this->request->data['AppointmentStaff']['profile_photo'];
                                $css = "";
                                if(!empty($path)){
                                    $css = "background:url('$path')";
                                }
                                ?>
                                <a class="upload_media upload_img_icon" href="javascript:void(0)"><i class="fa fa-upload"> Upload</i></a>
                                <div class="channle_img_box channel_img edit_img_icon" style="background-repeat:no-repeat;background-size:cover !important;<?php echo $css; ?>">

                                </div>


                                <div class="col-sm-12">
                                    <?php echo $this->Form->input('file',array("accept"=>"image/x-png,image/gif,image/jpeg",'type'=>'file','label'=>false,'class'=>'form-control hidden_file_browse')) ?>
                                    <?php echo $this->Form->input('profile_photo',array('type'=>'hidden','label'=>false,'class'=>'image_box')) ?>
                                    <div class="file_error"></div>
                                    <div class="file_success"></div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    <!-- profile section end here -->
                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>

<style>
    .error-message{

        bottom: -15px !important;
        position: absolute !important;
    }
    legend{
        font-size: 13px !important;
        font-weight: 600;
        width: 100%;
        margin-bottom: 4px !important;
        line-height: inherit;
        border-bottom: 1px solid #e5e5e5;
        height: 42px !important;


    }

    .radio_lbl_vac{
        width: 100%;
        background: #efefef;
        padding: 2px 9px;
    }


    .border_div{
       height: 83px;
    }


    .clearfix{
       clear:both;
    }



    .consent_title_heading{
        text-transform: uppercase;
    }
</style>


<script>
    $(document).ready(function(){


        CKEDITOR.replace( "online_text",{
            toolbarGroups: [
                { name: 'links', groups: [ 'links' ] },
                { name: 'colors', groups: [ 'colors' ] },
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'styles', groups: ['styles']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
            ],
            removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
            height:300,
            autoParagraph :false,
            enterMode : CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P
        } );
        CKEDITOR.replace( "offline_text",{
            toolbarGroups: [
                { name: 'links', groups: [ 'links' ] },
                { name: 'colors', groups: [ 'colors' ] },
                {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                {name: 'styles', groups: ['styles']},
                {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
            ],
            removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
            height:300,
            autoParagraph :false,
            enterMode : CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P
        } );


        var upload = false;
        var upload_file =false;
        $(".channel_tap a").removeClass('active');
        $("#v_add_channel").addClass('active');
        var is_image = $(".image_box").val();
        if(is_image != ""){
            $('.channle_img_box').css('background-image', "url('"+is_image+"')");
        }


        /* $("#v_add_channel").attr("href","javascript:void(0);");
         $("#v_add_channel").html("<i class='fa fa-pencil'> </i> Edit Channel");
         */


        var dob = $(".dob").val();
        if(dob =='0000-00-00'){
            $(".dob").val('');
        }
        $('.datepicker').datepicker({autoclose:true,format:'dd-mm-yyyy'});

        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });


        $(document).on('submit','#sub_frm',function(e){
            if(upload_file==true){
                $.alert('Please upload profile image');
                return false;
            }

        });

        $(document).on('change','.hidden_file_browse',function(e){
            if($(this).val()){
                $(".upload_media").show();
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });


        $(document).on('change',"[name='data[AppointmentStaff][show_appointment_time]'], [name='data[AppointmentStaff][show_appointment_token]']",function(e){
            var time = $("[name='data[AppointmentStaff][show_appointment_time]']:checked").val();
            var token = $("[name='data[AppointmentStaff][show_appointment_token]']:checked").val();
            var data_type = $(this).attr('data_type');
            if(time =='NO' && token == 'NO'){
                if(data_type=='time'){
                    $("#show_appointment_timeYES").prop("checked", true);
                }else{
                    $("#show_appointment_tokenYES").prop("checked", true);
                }
                $.alert("Please select one of option YES from 'Can user see appointment time?' OR 'Can user see appointment time?'");
            }

        });

        $(document).on('change','#staff_type',function(e){

            if($(this).val()=="DOCTOR"){
                $('#third_box').addClass('clearfix');
                $(".doctor_section").slideDown(300);
                $(".reception_div").hide();

            }else{
                $('#third_box').removeClass('clearfix');
                $(".doctor_section").slideUp(300);
                $(".reception_div").show();

            }



        });


        $(".reception_div").hide();
        if($("#staff_type").val()=="RECEPTIONIST"){
            $(".reception_div").show();
        }

        function readURL(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('.channle_img_box').css('background-image', "url('"+e.target.result+"')");
                    upload_file= true;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).on('change','.country',function(e){
            var country_id =$(this).val();
            if(country_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_state_list",
                    data:{country_id:country_id},
                    beforeSend:function(){

                        $('.state_spin').show();
                        $('.city, .state').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.state_spin').hide();
                        $('.state').html(data);;
                        $('.city, .state').attr('disabled',false);
                        // $(".state").trigger('change');
                    },
                    error: function(data){
                    }
                });
            }

        });


        $(document).on('change','.state',function(e){
            var state_id =$(this).val();
            if(state_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_city_list",
                    data:{state_id:state_id},
                    beforeSend:function(){
                        $('.city_spin').show();
                        $('.city').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.city_spin').hide();
                        $('.city').attr('disabled',false).html(data);
                    },
                    error: function(data){
                    }
                });
            }

        });


        if($(".country").val()==""){
            // $(".country").trigger('change');
        }


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

    });
</script>






