<?php



$login = $this->Session->read('Auth.User');
$prescriptionFields = $this->AppAdmin->getHospitalPrescriptionFields($login['User']['thinapp_id']);
$reason_of_appointment_list = $this->AppAdmin->get_reason_of_appointment_list($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown(true);
$state_list=$city_list=array();
$create_form_dynamic = false;
if(!empty($prescriptionFields)){

    $tmp =array_reverse($prescriptionFields);
    $tmp =array_pop($tmp);
    $create_form_dynamic = isset($tmp['custom_field'])?true:false;
    if($create_form_dynamic){
        $prescriptionFields = $this->AppAdmin->array_order_by($prescriptionFields,'form_field_order');
    }
}
$blood_group =array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-');
$name_title =array(''=>'Select','B/O'=>'B/O','Baba'=>'Baba','Dr.'=>'Dr.','DR.(MRS)'=>'DR.(MRS)','DR.(MS)'=>'DR.(MS)','MR.'=>'MR.','MS.'=>'MS.','Mr'=>'Mr','Mrs.'=>'Mrs.','Miss.'=>'Miss.','Master'=>'Master','Baby'=>'Baby');

$gender =array('MALE'=>'Male','FEMALE'=>'Female');
$marital_status =array('MARRIED'=>'MARRIED','UNMARRIED'=>'UNMARRIED');



$relation =array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O');
echo $this->Html->script(array('magicsuggest-min.js'));
echo $this->Html->css(array('magicsuggest-min.css'));
$show_dialog ='NO';
?>


<div class="modal fade" id="edit_customer_dialog" role="dialog" keyboard="true">

    <?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
    <?php echo $this->Html->css(array('magicsuggest-min.css')); ?>

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Patient</h4>
        </div>
        <div class="modal-body">
            <label class="error_msg_div"></label>
            <?php if($user_type == 'CHILDREN'){ ?>
                <div class="row">

                    <?php echo $this->Form->create('Children',array('data-f'=>$folder_id,'data-p'=>$patient_id,'type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'patient_form')); ?>

                    <?php
                          echo $this->Form->input('child_growth_id',array('type'=>'hidden','value'=>$child_growth_id,'label'=>false));
                          echo $this->Form->input('reset_vaccination',array('type'=>'hidden','id'=>'reset_vaccination','label'=>false));

                          $show_dialog = ($this->request['data']['Children']['has_vaccination'] == 'YES' || $this->request['data']['Children']['dob_editable']=="NO")?'YES':'NO';

                    $dob = @$this->AppAdmin->dateFormat($this->request['data']['Children']['dob']);

                    ?>
                    <?php echo $this->Form->input('is_dob_editable',array('last_dob'=>$dob,'id'=>'is_dob_editable','type'=>'hidden','value'=>($this->request['data']['Children']['dob_editable']=="YES")?'YES':'NO','label'=>false)); ?>




                    <?php if($create_form_dynamic===false){ ?>
                        <?php
                        $dob = @$this->AppAdmin->dateFormat($this->request['data']['Children']['dob']);
                        $parents_mobile =@str_replace("+91","",$this->request['data']['Children']['parents_mobile']);
                        ?>


                        <div class="col-sm-1">
                            <label>Title</label>
                            <?php echo $this->Form->input('title',array('type'=>'select','options'=>$name_title,'label'=>false,'class'=>'form-control')); ?>
                        </div>
                        <div class="col-sm-3">
                            <label>Child Name<span class="red">*</span></label>
                            <?php echo $this->Form->input('child_name',array('type'=>'text',"maxlength"=>"25",'id'=>'focusInput','placeholder'=>'Child Name','label'=>false,'class'=>'form-control patient_name','required'=>true)); ?>
                        </div>


                        <div class="col-sm-2">
                            <?php
                            $mobile = !empty($this->request->data['Children']['mobile'])?substr($this->request->data['Children']['mobile'],-10):'';
                            ?>

                            <label>Mobile<span class="red">*</span></label>
                            <?php echo $this->Form->input('mobile',array('value'=>$mobile,'type'=>'text','id'=>'mobile_number',"maxlength"=>"13",'placeholder'=>'Mobile Number','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                            <label>Date Of Birth<span class="red">*</span></label>
                            <?php echo $this->Form->input('dob',array('type'=>'text', 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'value'=>$dob,'last_dob'=>$dob, 'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                        </div>
                        <div class="col-sm-2">
                            <label>Relation</label>
                            <div class="parent_holder">
                                <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>$relation,'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                                <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                            </div>


                        </div>
                        <div class="col-sm-2">
                            <label>Relation Mobile</label>
                            <?php echo $this->Form->input('parents_mobile',array('type'=>'text','value'=>$parents_mobile,'placeholder'=>'Parents Mobile',"pattern"=>"[56789][0-9]{9}",'title'=>"Please enter valid 10 digit mobile number",'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                        <div class="col-sm-2 clear">
                            <label>Gender <span class="red">*</span></label>
                            <?php echo $this->Form->input('gender',array('type'=>'select','empty'=>'Please Select','options'=>$gender,'label'=>false,'class'=>'form-control cnt','required'=>true)); ?>

                        </div>

                        <div class="col-sm-2">
                            <label>Email</label>
                            <?php echo $this->Form->input('email',array('type'=>'email',"maxlength"=>"50",'placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                        <div class="col-sm-2">
                            <label>Blood Group</label>
                            <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>$blood_group,'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                        <div class="col-sm-2 ">
                            <label>Address</label>
                            <?php echo $this->Form->input('patient_address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                        </div>


                        <div class="col-sm-2">
                            <label>Referred By (Name)</label>
                            <?php echo $this->Form->input('referred_by',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Referred By (Name)",'id'=>'referredBy','label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                        <div class="col-sm-2">
                            <label>Referred By (Mobile)</label>
                            <?php echo $this->Form->input('referred_by_mobile',array('type'=>'text','id'=>'referredByMobile',"maxlength"=>"15",'placeholder'=>"Referred By (Mobile)",'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                        <?php if(isset($prescriptionFields['weight']) && ($prescriptionFields['weight']['status'] == 'ACTIVE')) { ?>
                            <div class="col-sm-2">
                                <label>Weight (KG)</label>
                                <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In KG)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                        <?php }?>
                        <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                            <div class="col-sm-2">
                                <label>Height (M)</label>
                                <?php echo $this->Form->input('height',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In CM)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                        <?php }?>
                        <?php if(isset($prescriptionFields['head circumference']) && ($prescriptionFields['head circumference']['status'] == 'ACTIVE')) { ?>
                            <div class="col-sm-2">
                                <label>Head Circumference</label>
                                <?php echo $this->Form->input('head_circumference',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In CM)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                        <?php }?>
                        <div class="col-sm-4 clear">
                            <label>Reason Of Appointment</label>
                            <?php echo $this->Form->input('reason_of_appointment',array('type'=>'text',"maxlength"=>"250","list"=>"reason_of_appointment",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                        <div class="col-sm-4">
                            <label>Remark</label>
                            <?php echo $this->Form->input('notes',array('type'=>'text',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                    <?php }else{ foreach($prescriptionFields as $key => $value){



                        $required = @($value['required']=="YES")?true:false;
                        $visible_class= 'display_none';
                            if(($value['form_status']=='ACTIVE')){
                                $visible_class= 'display_block';
                                $app_id = base64_decode($appointment_id);
                                if(empty($app_id) && in_array($value['column'],array('referred_by','referred_by_mobile','reason_of_appointment','notes'))){
                                    $visible_class= 'display_none';
                                }

                            }

                            if($value['input']=='YES'){



                        ?>


                        <?php if(in_array($value['column'],array('dob','age','conceive_date','expected_date','marital_status'))){
                            $dob  = @$this->AppAdmin->dateFormat($this->request['data']['Children']['dob']);
                            if($value['column']=='dob'){ $required =true;
                                ?>
                                <div class="col-sm-2 <?php echo $visible_class; ?>">
                                    <label><?php echo $value['label']; ?></label>
                                    <?php echo $this->Form->input($value['column'],array('required'=>$required,'autoComplete'=>'off','value'=>@$dob,'type'=>'text','placeholder'=> $value['label'],'label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                </div>

                            <?php }}else if($value['column']=='gender' || $value['column']=='blood_group'){

                            $option =array();
                            if($value['column']=='gender'){
                                $option =$gender;
                                $required =true;
                            }else if($value['column']=='blood_group'){
                                $option =$blood_group;
                            }
                            ?>
                            <div class="col-sm-2  <?php echo $visible_class; ?>">
                                <label><?php echo $value['label']; ?></label>
                                <?php echo $this->Form->input($value['column'],array('required'=>$required,'autoComplete'=>'off','type'=>'select','empty'=>'Please Select','options'=>$option,'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                            </div>
                        <?php  }else {


                            $column = $value['column'];

                            if($value['column']=='patient_name'){
                                $column = 'child_name';
                                $required =true;
                            }else if($value['column']=='patient_height'){
                                $column = 'height';
                            }else if($value['column']=='address'){
                                $column = 'patient_address';
                            }else if($value['column']=='mobile'){
                                $required =true;
                            }else if($value['column']=='city_name'){
                                $visible_class .= " city_name_box";
                            }




                            ?>

                            <?php if($column =="parents_name"){ ?>
                                <div class="col-sm-2 <?php echo $visible_class; ?>">
                                    <label><?php echo $value['label']; ?></label>
                                    <div class="parent_holder">
                                        <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>$relation,'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                                        <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','type'=>'text',"maxlength"=>"50",'placeholder'=>$value['label'],'label'=>false,'class'=>'form-control cnt parent_name_input','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                    </div>
                                </div>
                            <?php }else if($column =="patient_category"){ ?>

                                <div class="col-sm-2 <?php echo $visible_class; ?>">
                                    <label><?php  echo $value['label']; ?></label>
                                    <?php echo $this->Form->input($value['column'],array('required'=>$required,'autoComplete'=>'off','type'=>'select','empty'=>'Please Select','options'=>$this->AppAdmin->get_patient_category_list_drp($login['User']['thinapp_id']),'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                </div>
                            <?php }else{ ?>

                                <?php if($column=='child_name'){ ?>
                                    <div class="col-sm-4 <?php echo $visible_class; ?>">
                                        <div class="title">
                                            <label>Title</label>
                                            <?php echo $this->Form->input('title',array('type'=>'select','options'=>$name_title,'label'=>false,'class'=>'form-control')); ?>
                                        </div>
                                        <div class="patient_name_div">
                                            <label><?php echo $value['label']; ?></label>
                                            <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','value'=>@$this->request->data['Children'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                        </div>
                                    </div>
                                <?php }else{ ?>
                                    <div class="col-sm-2 <?php echo $visible_class; ?>">
                                        <label><?php echo $value['label']; ?></label>
                                        <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','value'=>@$this->request->data['Children'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                    </div>
                                <?php } ?>


                                <?php if($value['column']=='city_id'){ ?>
                                    <div class="col-sm-2 city_name_box <?php echo $visible_class; ?>">
                                        <label>City Name</label>
                                        <?php echo $this->Form->input('city_name',array('autoComplete'=>'off','value'=>@$this->request->data['Children']['city_name'],'type'=>'text',"maxlength"=>"50",'placeholder'=> 'City Name','label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter City Name.')",'oninput'=>"setCustomValidity('')")); ?>
                                    </div>
                                <?php } ?>
                            <?php } ?>



                        <?php }}}} ?>

                    <div class="col-sm-12" style="text-align: right;">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                        <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-success edit_patient_update_btn"><i class="fa fa-save"></i> Save</button>
                    </div>

                    <?php echo $this->Form->end(); ?>

                </div>
            <?php } else{ ?>

            <div class="container">
                <?php echo $this->Form->create('AppointmentCustomer',array('data-f'=>$folder_id,'data-p'=>$patient_id,'type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'patient_form')); ?>

                <div class="row">

                    <?php echo $this->Form->input('patient_type',array('type'=>'hidden','value'=>'CUSTOMER','label'=>false,'required'=>true)); ?>

                    <?php if($create_form_dynamic===false){ ?>
                        <?php

                        $dob = @$this->AppAdmin->dateFormat($this->request['data']['AppointmentCustomer']['dob']);
                        $age = @$this->AppAdmin->create_age_array($this->request['data']['AppointmentCustomer']['age']);
                        $year = $age['Year'];
                        $month = $age['Month'];
                        $day = $age['Day'];
                        $age_string = @$this->request['data']['AppointmentCustomer']['age'];


                        ?>


                        <div class="col-sm-1">
                             <label>Title</label>
                            <?php echo $this->Form->input('title',array('type'=>'select','options'=>$name_title,'label'=>false,'class'=>'form-control')); ?>
                        </div>
                        <div class="col-sm-3">
                            <label>Name<span class="red">*</span></label>
                            <?php echo $this->Form->input('first_name',array('type'=>'text','id'=>'focusInput',"maxlength"=>"50",'placeholder'=>'Name','label'=>false,'class'=>'form-control patient_name','required'=>true)); ?>
                        </div>


                        <div class="col-sm-2">
                            <?php
                            $mobile = !empty($this->request->data['AppointmentCustomer']['mobile'])?substr($this->request->data['AppointmentCustomer']['mobile'],-10):'';
                            ?>

                            <label>Mobile<span class="red">*</span></label>
                            <?php echo $this->Form->input('mobile',array('value'=>$mobile,'type'=>'text','id'=>'mobile_number',"maxlength"=>"13",'placeholder'=>'Mobile Number','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                        </div>

                        <div class="col-sm-2">
                            <label>Age</label><br>
                            <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput year_input" value = "<?php echo $year; ?>" id="ageYear" type="number">
                            <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput month_input" value = "<?php echo $month; ?>" id="ageMonth" type="number">
                            <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput day_input" value = "<?php echo $day; ?>" id="ageDay" type="number">
                            <?php echo $this->Form->input('age',array('type'=>'hidden',"maxlength"=>"50",'placeholder'=>'Age','label'=>false,'class'=>'form-control cnt')); ?>
                        </div>


                        <div class="col-sm-2">
                            <label>Date Of Birth</label>
                            <?php echo $this->Form->input('dob',array('type'=>'text','value'=>$dob, 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt')); ?>

                        </div>

                        <div class="col-sm-2 gender_div">
                            <label>Gender</label>
                            <?php echo $this->Form->input('gender',array('type'=>'select','empty'=>'Please Select','options'=>$gender,'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>


                        <div class="col-sm-2 conceive_date_div">
                            <label>Conceive Date</label>
                            <?php echo $this->Form->input('conceive_date',array('type'=>'text','placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control')); ?>
                        </div>

                        <div class="col-sm-2 conceive_date_div">
                            <label>Expected Date</label>
                            <?php echo $this->Form->input('expected_date',array('type'=>'text','placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control')); ?>
                        </div>


                        <div class="col-sm-2 clear">
                            <label>Email</label>
                            <?php echo $this->Form->input('email',array('type'=>'email',"maxlength"=>"50",'placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>
                        </div>




                        <div class="col-sm-2">
                            <label>Address</label>
                            <?php echo $this->Form->input('address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                        </div>

                        <div class="col-sm-2">
                            <label>Marital Status</label>
                            <?php echo $this->Form->input('marital_status',array('type'=>'select','empty'=>'Please Select',"options"=>array("MARRIED"=>"Married","UNMARRIED"=>"Unmarried"),'label'=>false,'class'=>'form-control cnt')); ?>
                        </div>
                        <div class="col-sm-2">
                            <label>Blood Group</label>
                            <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>$blood_group,'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>

                        <div class="col-sm-2">
                            <label>Parents Mobile</label>
                            <?php echo $this->Form->input('parents_mobile',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Parents Mobile",'label'=>false,'class'=>'form-control cnt')); ?>
                        </div>

                        <div class="col-sm-2">
                            <label>Relation</label>
                            <div class="parent_holder">
                                <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O'),'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                                <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                            </div>
                        </div>



                        <div class="col-sm-2">
                            <label>Referred By (Name)</label>
                            <?php echo $this->Form->input('referred_by',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Referred By (Name)",'id'=>'referredBy','label'=>false,'class'=>'form-control cnt')); ?>
                        </div>

                        <div class="col-sm-2">
                            <label>Referred By (Mobile)</label>
                            <?php echo $this->Form->input('referred_by_mobile',array('type'=>'text','id'=>'referredByMobile',"maxlength"=>"15",'placeholder'=>"Referred By (Mobile)",'label'=>false,'class'=>'form-control cnt')); ?>
                        </div>


                        <?php if(isset($prescriptionFields['weight']) && ($prescriptionFields['weight']['status'] == 'ACTIVE')) { ?>
                            <div class="col-sm-1">
                                <label>Weight (KG)</label>
                                <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In KG)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>
                            </div>
                        <?php }?>

                        <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                            <div class="col-sm-1">
                                <label>Height (M)</label>
                                <?php echo $this->Form->input('height',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In CM)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                        <?php }?>

                        <?php if(isset($prescriptionFields['head circumference']) && ($prescriptionFields['head circumference']['status'] == 'ACTIVE')) { ?>
                            <div class="col-sm-2">
                                <label>Head Circumference</label>
                                <?php echo $this->Form->input('head_circumference',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In CM)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                        <?php }?>
                        <div class="col-sm-3">
                            <label>Reason Of Appointment</label>
                            <?php echo $this->Form->input('reason_of_appointment',array('type'=>'text',"maxlength"=>"250","list"=>"reason_of_appointment",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>
                        <div class="col-sm-3">
                            <label>Remark</label>
                            <?php echo $this->Form->input('notes',array('type'=>'text',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>

                        </div>

                    <?php }
                    else{ foreach($prescriptionFields as $key => $value){ $visible_class= ($value['form_status']=='ACTIVE')?'display_block':'display_none'; if($value['input']=='YES'){ ?>


                        <?php

                        $required = @($value['required']=="YES")?true:false;

                        if($value['column']=='age'){
                            $dob = @$this->AppAdmin->dateFormat($this->request['data']['AppointmentCustomer']['dob']);
                            $age = @$this->AppAdmin->create_age_array($this->request['data']['AppointmentCustomer']['age']);
                            $year = $age['Year'];
                            $month = $age['Month'];
                            $day = $age['Day'];
                            $age_string = @$this->request['data']['AppointmentCustomer']['age'];

                            ?>

                            <div class="col-sm-2 <?php echo $visible_class; ?>">
                                <label>Age</label><br>
                                <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput year_input" <?php echo ($required==true)?"data-required='1' required='required'":"data-required='0'"; ?> value = "<?php echo $year; ?>" id="ageYear" type="number">
                                <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput month_input" <?php echo ($required==true)?"data-required='1' required='required'":"data-required='0'"; ?> value = "<?php echo $month; ?>" id="ageMonth" type="number">
                                <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput day_input" <?php echo ($required==true)?"data-required='1' required='required'":"data-required='0'"; ?> value = "<?php echo $day; ?>" id="ageDay" type="number">
                                <?php echo $this->Form->input('age',array('autoComplete'=>'off','type'=>'hidden',"maxlength"=>"50",'placeholder'=>'Age','label'=>false,'class'=>'form-control cnt')); ?>
                            </div>

                        <?php }else if($value['column']=='gender' || $value['column']=='blood_group' || $value['column']=='marital_status'){

                            $option =array();
                            if($value['column']=='gender'){
                                $option =$gender;
                            }else if($value['column']=='blood_group'){
                                $option =$blood_group;
                            }else if($value['column']=='marital_status'){
                                $option =$marital_status;
                            }
                            ?>


                            <div class="col-sm-2  <?php echo $visible_class; ?>">
                                <label><?php echo $value['label']; ?></label>
                                <?php echo $this->Form->input($value['column'],array('type'=>'select','empty'=>'Please Select','options'=>$option,'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                            </div>

                        <?php  }else {


                            $column = $value['column'];
                            if($value['column']=='patient_name'){
                                $column = 'first_name';
                                $required =true;
                            }else if($value['column']=='patient_height'){
                                $column = 'height';
                            }else if($value['column']=='mobile'){
                                $required =true;
                            }else if($value['column']=='city_name'){
                                $visible_class .= " city_name_box";
                            }

                            if($value['column']=='dob'){
                                $dob = @$this->AppAdmin->dateFormat($this->request['data']['AppointmentCustomer']['dob']);
                                if(!empty($dob) && $dob != '0000-00-00'){
                                    $this->request->data['AppointmentCustomer']['dob'] = $dob;
                                }else{
                                    $this->request->data['AppointmentCustomer']['dob'] = '';
                                }

                            }

                            $class = ($value['column']=='conceive_date' || $value['column']=='expected_date')?'conceive_date_div':'';

                            ?>

                            <?php if($column =="parents_name"){ ?>
                                <div class="col-sm-2 <?php echo $visible_class; ?>">
                                    <label><?php echo $value['label']; ?></label>
                                    <div class="parent_holder">
                                        <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>$relation,'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                                        <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','type'=>'text',"maxlength"=>"50",'placeholder'=>$value['label'],'label'=>false,'class'=>'form-control cnt parent_name_input','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                    </div>
                                </div>
                            <?php }else if($column =="patient_category"){ ?>

                                <div class="col-sm-2 <?php echo $visible_class; ?>">
                                    <label><?php  echo $value['label']; ?></label>
                                    <?php echo $this->Form->input($value['column'],array('required'=>$required,'autoComplete'=>'off','type'=>'select','empty'=>'Please Select','options'=>$this->AppAdmin->get_patient_category_list_drp($login['User']['thinapp_id']),'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                </div>
                            <?php }else{ ?>

                                <?php if($column=='first_name'){ ?>
                                 <div class="col-sm-4  <?php echo $visible_class; ?> <?php echo $class; ?>">
                                    <div class="title">
                                        <label>Title</label>
                                        <?php echo $this->Form->input('title',array('type'=>'select','options'=>$name_title,'label'=>false,'class'=>'form-control')); ?>
                                    </div>
                                    <div class="patient_name_div">
                                        <label><?php echo $value['label']; ?></label>
                                        <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','value'=>@$this->request->data['AppointmentCustomer'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                    </div>
                                 </div>
                                <?php }else{ ?>
                                    <div class="col-sm-2  <?php echo $visible_class; ?> <?php echo $class; ?>">
                                        <label><?php echo $value['label']; ?></label>
                                        <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','value'=>@$this->request->data['AppointmentCustomer'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                                    </div>
                                <?php } ?>



                                <?php if($value['column']=='city_id'){ ?>
                                    <div class="col-sm-2 city_name_box">
                                        <label>City Name</label>
                                        <?php echo $this->Form->input('city_name',array('autoComplete'=>'off','value'=>@$this->request->data['AppointmentCustomer']['city_name'],'type'=>'text',"maxlength"=>"50",'placeholder'=> 'City Name','label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter City Name.')",'oninput'=>"setCustomValidity('')")); ?>
                                    </div>
                                <?php } ?>


                            <?php } ?>




                        <?php }}}} ?>


                    <div class="col-sm-12" style="text-align: right;">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                        <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                        <button type="submit" class="btn btn-success edit_patient_update_btn"><i class="fa fa-save"></i> Save</button>
                    </div>

                    <div class="clear"></div>

                </div>

                <?php echo $this->Form->end(); ?>

            </div>

            <?php } ?>
        </div>
    </div>
    </div>
    <datalist id="reason_of_appointment">
        <?php foreach($reason_of_appointment_list AS $list){ ?>
                <option value="<?php echo $list[0]; ?>">
        <?php } ?>
    </datalist>
    <style>


        #edit_customer_dialog label{
            font-size: 13px;
        }
        .display_none{
            display: none;
        }
        .display_block{
            display: block;
        }
        #edit_customer_dialog .ms-helper, .ms-helper  {
            display: none !important;
        }

        #edit_customer_dialog .ms-ctn .ms-sel-item {
            background: none;
            color: #434343;
            float: left;
            font-size: 13px;
            padding: 3px 4px;
            border-radius: 3px;
            border: 0px;
            margin: 0;
            margin: 0px 0px 0px 0;
        }



        .error_msg_div{
            width: 100%;
            display: block;
            text-align: center;
            height: 20px;
            color: #ff0202;
        }
        .red{color:red;font-size: 15px;}
        .relation_prefix {
            width: 36%;
        }
        .parent_name_input {
            width: 64%;
        }
        .parent_holder .input {
            display: contents;
        }
        .parent_holder {
            display: flex;
        }

        .conceive_date_div{
            display: none;
        }
        .customer_first_name, .customer_last_name{float: left;width: 70% !important;}
        .table-responsive{
            max-height: 205px;
            overflow-y: scroll;
        }
        textarea {
            height: 43px !important;
        }
        .ageInput {
            display: inline-block;
        }
        .year_input{
            width: 35%;
        }
        .month_input, .day_input{
            width: 30%;
        }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }


        #edit_customer_dialog .form-control {
            height: 32px !important;
            padding: 3px 2px !important;

        }

        #edit_customer_dialog .modal-dialog{
            width:80% !important;
            margin: 10px auto !important;
        }

        #edit_customer_dialog .modal-dialog .modal-body {
            padding: 6px !important;
        }
        #edit_customer_dialog .modal-header{
            padding: 8px !important;
        }

        #edit_customer_dialog .modal-dialog .modal-body .row {
            margin-left: 0px;
            margin-right: 0px;
        }

        div[class^='col-sm-']{
            padding-right: 4px;
            padding-left: 4px;
        }
        .ms-ctn .ms-sel-ctn {

            margin-left: -2px;

        }
        .city_name_box{
            display: none;
        }

        .patient_name_div{
            width: 70%;
            float: left;
        }
        .title{
            width: 30%;
            float: left;
        }

        .validate_lbl{
            font-size: 22px;
            color: red;
            position: absolute;
            padding: 5px 2px;
        }

    </style>
    <script>
        $(function () {


            $(".display_block").find('input, select').each(function(){
                if($(this).prop('required')){

                    if($(this).closest('.display_block').find('.validate_lbl').length==0){
                        $(this).closest('.display_block').find("label").append("<span class='validate_lbl'>*</span>");
                    }
                }
            });


            var first_load  =true;
            var country_obj = $("#AppointmentCustomerCountryId, #ChildrenCountryId");
            var state_obj = $("#AppointmentCustomerStateId, #ChildrenStateId");
            var city_obj = $("#AppointmentCustomerCityId, #ChildrenCityId");
            var city_name_obj = $("#AppointmentCustomerCityName, #ChildrenCityName");
            var selector = $(country_obj).attr('id');


            var valid_country = true;
            var  valid_state = true;
            var valid_city = true;

            var country,state,city;

            if($("#"+selector).closest('.col-sm-2').hasClass('display_block')){
                function show_loader(obj,flag){
                    var html = '<i class="fa fa-spinner fa-spin state_spin"></i>';
                    if(flag===true){


                        $(obj).append(html);
                    }else{
                        $(obj).find('i').remove();
                    }
                }
                function addMagicsuggest(){


                    var country_required = false;
                    var state_required = false;
                    var city_required = false;


                    if($(country_obj).attr('required')){
                        country_required = true;
                        valid_country = false;
                    }if($(state_obj).attr('required')){
                        state_required = true;
                        valid_state = false;
                    }if($(city_obj).attr('required')){
                        city_required = true;
                        valid_city = false;
                    }
                    country = $(country_obj).magicSuggest({
                        allowFreeEntries:false,
                        allowDuplicates:false,
                        data:<?php echo json_encode($country_list,true); ?>,
                        maxDropHeight: 345,
                        maxSelection: 1,
                        required: false,
                        noSuggestionText: '',
                        useTabKey: true,
                        selectFirst :true
                    });
                     state = $(state_obj).magicSuggest({
                        allowFreeEntries:false,
                        allowDuplicates:false,
                        maxDropHeight: 345,
                        maxSelection: 1,
                        required: false,
                        noSuggestionText: '',
                        useTabKey: true,
                        selectFirst :true
                    });
                     city = $(city_obj).magicSuggest({
                        allowFreeEntries:true,
                        allowDuplicates:false,
                        maxDropHeight: 345,
                        maxSelection: 1,
                        required: false,
                        noSuggestionText: '',
                        useTabKey: true,
                        selectFirst :true
                    });

                    $(country).off('selectionchange');
                    $(country).on('selectionchange', function(e,m){
                        var $this = this;
                        var obj_id = $(state_obj).attr('id');
                        var label = $("#"+obj_id).closest('.col-sm-2, .display_block').find('label');

                        var IdArr = this.getSelection();
                        if(IdArr[0])
                        {
                            var country_id =IdArr[0].id;
                            if(country_id){
                                valid_country =true;
                                $.ajax({
                                    type:'POST',
                                    url: baseurl+"app_admin/get_state_list_json",
                                    data:{country_id:country_id},
                                    beforeSend:function(){
                                        show_loader(label,true);
                                        $(state_obj,city_obj).attr('disabled',true).html('');
                                        state.clear();
                                        city.clear();
                                    },
                                    success:function(data){
                                        show_loader(label,false);
                                        state.setData(JSON.parse(data));
                                        state.input.focus();
                                        $(state_obj,city_obj).attr('disabled',false);
                                        if($(state_obj).val()!='' && $(state_obj).val()!='0' && first_load){
                                            state.setValue([$(state_obj).val()]);
                                            first_load = false;
                                        }
                                    },
                                    error: function(data){
                                    }
                                });
                            }else{
                                valid_country  = false;
                            }
                        }else{
                            valid_country  = false;
                        }



                    });


                    $(state).off('selectionchange');
                    $(state).on('selectionchange', function(e,m) {
                        var $this = this;
                        var obj_id = $(city_obj).attr('id');
                        var label = $("#"+obj_id).closest('.col-sm-2, .display_block').find('label');
                        var IdArr = this.getSelection();
                        if (IdArr[0]) {
                            var state_id =IdArr[0].id;
                            if(state_id){
                                valid_state = true;
                                $.ajax({
                                    type:'POST',
                                    url: baseurl+"app_admin/get_city_list_json",
                                    data:{state_id:state_id},
                                    beforeSend:function(){
                                        show_loader(label,true);
                                        $(city_obj).attr('disabled',true).html('');
                                    },
                                    success:function(data){
                                        show_loader(label,false);
                                        $(city_obj).attr('disabled',false);
                                        city.setData(JSON.parse(data));
                                        city.input.focus();
                                        if($(city_obj).val()!='' && $(city_obj).val()!='0' ){
                                            city.setValue([$(city_obj).val()]);
                                        }else{

                                            if($(city_name_obj).val()!='' ){
                                                city.setValue([$(city_name_obj).val()]);
                                            }
                                        }


                                    },
                                    error: function(data){
                                    }
                                });
                            }else{
                                valid_state = false;
                            }
                        }else{
                            valid_state = false;
                        }
                    });


                    $(city).off('selectionchange');
                    $(city).on('selectionchange', function(e,m) {
                        var $this = this;
                        var IdArr = this.getSelection();
                        if (IdArr[0]) {
                            var city_name =IdArr[0].name;
                            $(city_name_obj).val(city_name);
                            valid_city =true;
                        }else{
                            $(city_name_obj).val('');
                            valid_city = false;
                        }
                    });
                    $(city).off('blur');
                    $(city).on('blur', function(c){
                        var IdArr = this.getSelection();
                        if (IdArr[0]) {
                            var city_name =IdArr[0].name;
                            $(city_name_obj).val(city_name);
                            valid_city =true;
                        }else{
                            $(city_name_obj).val('');
                            valid_city = false;
                        }


                    });


                    if($(country_obj).val()!='' && $(country_obj).val()!='0'){
                        country.setValue([$(country_obj).val()]);
                    }

                }
                setTimeout(function () {
                    addMagicsuggest();
                },100);
            }else{

                $("#AppointmentCustomerCityId, #ChildrenCityId").val($("#AppointmentCustomerCityName, #ChildrenCityName").val());
                $(document).off('input',"#AppointmentCustomerCityId, #ChildrenCityId");
                $(document).on('input',"#AppointmentCustomerCityId, #ChildrenCityId",function(){
                    $("#AppointmentCustomerCityName, #ChildrenCityName").val($(this).val());
                });
            }



            $("input[type='text']").prop('placeholder','');

            var create_form_dynamic = "<?php echo ($create_form_dynamic==true)?"YES":"NO"; ?>";
           $("[type='text']").attr('autoComplete','off');


            var conceive = $("#AppointmentCustomerConceiveDate").val();
            var expected = $("#AppointmentCustomerExpectedDate").val();

           setTimeout(function () {
                $("#AppointmentCustomerConceiveDate, #AppointmentCustomerExpectedDate").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true});
                $("#ChildrenDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});
                $("#AppointmentCustomerDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});


                $("#AppointmentCustomerConceiveDate").val(conceive);
                $("#AppointmentCustomerExpectedDate").val(expected);


            },10);



            setTimeout(function () {
                   $("#AppointmentCustomerFirstName, #ChildrenChildName").focus();
            },1000);


            $(document).off("change","#AppointmentCustomerGender");
            $(document).on("change","#AppointmentCustomerGender",function(){

                if(create_form_dynamic=='NO') {
                   $("#edit_customer_dialog .col-sm-2").removeClass('clear');
                }

                $("#AppointmentCustomerConceiveDate, #AppointmentCustomerExpectedDate").val('');
                if($(this).val()=='FEMALE'){
                    $(".conceive_date_div").show();
                    if(create_form_dynamic=='NO') {
                        $("#edit_customer_dialog .col-sm-2:nth-child(8)").addClass('clear');
                    }
                }else{
                    $(".conceive_date_div").hide();
                    if(create_form_dynamic=='NO') {
                        $("#edit_customer_dialog .col-sm-2:nth-child(10)").addClass('clear');
                    }
                }
            });


            <?php if($user_type != 'CHILDREN'){ ?>
            $(document).off("input","#ageYear,#ageMonth,#ageDay");
            $(document).on("input","#ageYear,#ageMonth,#ageDay",function(e){
                var year = $("#ageYear").val();
                var month = $("#ageMonth").val();
                var day = $("#ageDay").val();
                var ageStr = "";
                if(year > 0)
                {
                    ageStr += year+"Years ";
                }
                if(month > 0)
                {
                    ageStr += month+"Months ";
                }
                if(day > 0)
                {
                    ageStr += day+"Days";
                }
                $("#AppointmentCustomerAge").val(ageStr);
            });


            <?php } ?>
            var jc;
            $(document).off("submit","#patient_form");
            $(document).on("submit","#patient_form",function(e){
                e.preventDefault();

                if(!valid_country){
                    country.input.blur();
                    country.input.focus();
                    return false;
                }else if(!valid_state){
                    state.input.blur();
                    state.input.focus();
                    return false;
                }else if(!valid_city){
                    city.input.blur();
                    city.input.focus();
                    return false;
                }else{
                    var is_dob_editable = $( "#is_dob_editable" ).val();
                    var last_dob = $( "#is_dob_editable" ).attr('last_dob');
                    var dob = $( "input[name='data[Children][dob]']" ).val();
                    var show_dialog = "<?php echo $show_dialog; ?>";
                    var form_object = $(this);
                    if(show_dialog=="YES" && dob != last_dob ){
                        jc = $.confirm({
                            title: 'Are you sure you want to update DOB?',
                            content: '<b>Note:-</b> Updating DOB will reset the vaccination chart of this child according to new DOB. Still want to continue?',
                            type: 'red',
                            buttons: {
                                ok: {
                                    text: "Yes",
                                    btnClass: 'btn-primary',
                                    keys: ['enter'],
                                    name:"ok",
                                    action: function(e){
                                        $("#reset_vaccination").val('YES');
                                        submitForm(form_object);
                                        return false;
                                    }
                                },
                                cancel: function(){
                                    console.log('the user clicked cancel');
                                }
                            }
                        });
                    }else{
                        $("#reset_vaccination").val('NO');
                        submitForm(this);
                    }

                }
            });

            function submitForm(obj){
                var data = $( obj ).serialize();
                var $btn = $(obj).find(".edit_patient_update_btn");
                $(".file_error").fadeOut('slow');
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/web_edit_customer",
                    data:{data:data,pi:"<?php echo $patient_id; ?>",psi:"<?php echo $pregnancy_semester_id; ?>",ai:"<?php echo $appointment_id; ?>"},
                    beforeSend:function(){
                        $btn.button('loading').html('Saving...');
                    },
                    success:function(response){
                        $btn.button('reset')
                        var response = JSON.parse(response);
                        if(response.status==1){
                            $("#edit_customer_dialog").modal('hide');
                            $("#appointment_date").trigger("changeDate");

                        }else{
                            $(".error_msg_div").html(response.message).fadeIn('slow');
                        }
                        if(jc){ jc.close();}
                    },
                    error: function(data){
                        $btn.button('reset');
                        $(".error_msg_div").html(response.message).fadeIn('slow');
                    }
                });
            }

            $(document).off('input','#ageYear, #ageMonth, #ageDay');
            $(document).on('input','#ageYear, #ageMonth, #ageDay',function(event) {
                if($(this).attr('data-required')==1){
                    if($("#ageYear").val()!='' || $("#ageMonth").val()!='' ||$("#ageDay").val()!=''){
                        $('#ageYear, #ageMonth, #ageDay').removeAttr('required');
                    }else{
                        $('#ageYear, #ageMonth, #ageDay').attr('required','required');
                    }
                }
            });



            $("#AppointmentCustomerGender").trigger("change");

            setTimeout(function () {
                $("#patient_name_box").focus();
            },50);




        });
    </script>
</div>


