<?php
$login = $this->Session->read('Auth.User');
$prescriptionFields = $this->AppAdmin->getHospitalPrescriptionFields($login['User']['thinapp_id']);
$reason_of_appointment_list = $this->AppAdmin->get_reason_of_appointment_list($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown(true);
$refferer_list =$this->AppAdmin->get_refferer_name_list_appointment($login['User']['thinapp_id']);
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

?>


<style>

    .add_cus_div_app_load .ms-helper, .ms-helper  {
        display: none !important;
    }


    .add_cus_div_app_load .form-control{
        margin-bottom: 3px !important;
        height: 27px !important;
    }

    .add_cus_div_app_load .ms-ctn .ms-sel-item {
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

    .ms-ctn .ms-sel-ctn {

        margin-left: -2px;

    }
    .red{color:red;font-size: 15px;}
    .relation_prefix {
        width: 30%;
    }
    .parent_name_input {
        width: 70%;
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




    #booking_form div[class^="col-sm"] {
        padding: 0px 6px;

    }

    .add_cus_div_app_load .row{
        margin: 0px !important;
    }
    .error_msg_div{
        width: 100%;
        text-align: center;
        color: #ea1212;
        position: absolute;
        margin-top: -21px;
        font-size: 20px;
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

    .display_block{
        display: block;
    }
    .display_none{
        display: none;
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
    .ui-autocomplete {
        max-height: 200px;
        min-height: 70px;
        overflow-y: auto;   /* prevent horizontal scrollbar */
        overflow-x: hidden; /* add padding to account for vertical scrollbar */
        z-index:10000 !important;
    }
</style>


<h4 style="padding: 2px 6px; font-weight: 600; margin: 0px;">Patient Detail</h4>


<label class="error_msg_div"></label>
<?php if($user_type == 'CHILDREN'){ ?>
    <div class="row">

        <?php echo $this->Form->create('Children',array('data-f'=>$folder_id,'data-p'=>$patient_id,'type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'booking_form')); ?>


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
                <?php echo $this->Form->input('child_name',array('type'=>'text',"maxlength"=>"25",'id'=>'focusInput','list'=>'nameList','placeholder'=>'Child Name','label'=>false,'class'=>'form-control patient_name','required'=>true)); ?>
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
                <?php echo $this->Form->input('dob',array('type'=>'text', 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'value'=>$dob,'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
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

            <div class="col-sm-4 clear">
                <label>Reason Of Appointment</label>
                <?php echo $this->Form->input('reason_of_appointment',array('type'=>'text',"list"=>"reason_of_appointment","maxlength"=>"250",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>
            </div>


            <div class="col-sm-4">
                <label>Remark</label>
                <?php echo $this->Form->input('notes',array('type'=>'text',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>
            </div>

            <?php if(isset($prescriptionFields['weight']) && ($prescriptionFields['weight']['status'] == 'ACTIVE')) { ?>
                <div class="col-sm-2">
                    <label>Weight(KG)</label>
                    <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In KG)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                </div>
            <?php }?>
            <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                <div class="col-sm-2">
                    <label>Height(CM)</label>
                    <?php echo $this->Form->input('height',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In CM)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                </div>
            <?php }?>
            <?php if(isset($prescriptionFields['head circumference']) && ($prescriptionFields['head circumference']['status'] == 'ACTIVE')) { ?>
                <div class="col-sm-2">
                    <label>Head Circumference</label>
                    <?php echo $this->Form->input('head_circumference',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In CM)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                </div>
            <?php }?>





        <?php }else{ foreach($prescriptionFields as $key => $value){    $required = @($value['required']=="YES")?true:false;  $visible_class= ($value['form_status']=='ACTIVE')?'display_block':'display_none';   if($value['input']=='YES'){ ?>


            <?php if(in_array($value['column'],array('dob','age','conceive_date','expected_date','marital_status','third_party_uhid'))){
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

                $list = "";
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
                }else if($value['column']=='reason_of_appointment'){
                    $list = "reason_of_appointment";
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
                                <?php echo $this->Form->input('title',array('required'=>@($value['required']=="YES")?true:false,'type'=>'select','options'=>$name_title,'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter Title.')",'oninput'=>"setCustomValidity('')")); ?>
                            </div>
                            <div class="patient_name_div">
                                <label><?php echo $value['label']; ?></label>
                                <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','list'=>$list,'value'=>@$this->request->data['Children'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control','id'=>'nameList','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                            </div>
                            </div>
                        <?php }else{ ?>
                         <div class="col-sm-2 <?php echo $visible_class; ?>">
                            <label><?php echo $value['label']; ?></label>
                            <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','list'=>$list,'value'=>@$this->request->data['Children'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                         </div>
                         <?php } ?>



                    <?php if($value['column']=='city_id'){ ?>
                        <div class="col-sm-2 city_name_box <?php echo $visible_class; ?>">
                            <label>City Name</label>
                            <?php echo $this->Form->input('city_name',array('required'=>$required,'autoComplete'=>'off','value'=>@$this->request->data['Children']['city_name'],'type'=>'text',"maxlength"=>"50",'placeholder'=> 'City Name','label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter City Name.')",'oninput'=>"setCustomValidity('')")); ?>
                        </div>
                    <?php } ?>

                <?php } ?>



            <?php }}}} ?>

        <div class="col-sm-12" style="text-align: right;">
            <label>&nbsp;</label>
            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
            <button type="submit" id="addAppointment" class="btn btn-success m_save"><i class="fa fa-save"></i> Save & Book</button>
            <button type="submit" id="addAppointmentCheckIn" class="btn btn-success m_save"><i class="fa fa-save"></i> Save & Check-In</button>
        </div>
        <?php echo $this->Form->input('slot_time',array('type'=>'hidden','value'=>@$this->request->data['slot_time'])); ?>
        <?php echo $this->Form->input('custom_token',array('type'=>'hidden','value'=>@$this->request->data['custom_token'])); ?>
        <?php echo $this->Form->input('queue_number',array('type'=>'hidden','value'=>@$this->request->data['queue_number'])); ?>


        <?php echo $this->Form->end(); ?>

    </div>
<?php }
else{ ?>
    <div class="row">
        <?php echo $this->Form->create('AppointmentCustomer',array('data-f'=>$folder_id,'data-p'=>$patient_id,'type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'booking_form')); ?>
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
                <?php echo $this->Form->input('first_name',array('type'=>'text','id'=>'focusInput',"maxlength"=>"50",'list'=>'nameList','placeholder'=>'Name','label'=>false,'class'=>'form-control patient_name','required'=>true)); ?>
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


            <div class="col-sm-2 conceive_date_div display_block">
                <label>Conceive Date</label>
                <?php echo $this->Form->input('conceive_date',array('type'=>'text','placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control')); ?>
            </div>

            <div class="col-sm-2 conceive_date_div display_block">
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
                    <label>Weight(KG)</label>
                    <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In KG)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>
                </div>
            <?php }?>

            <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                <div class="col-sm-1">
                    <label>Height(CM)</label>
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
                <?php echo $this->Form->input('reason_of_appointment',array('type'=>'text',"list"=>"reason_of_appointment","maxlength"=>"250",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>

            </div>
            <div class="col-sm-3">
                <label>Remark</label>
                <?php echo $this->Form->input('notes',array('type'=>'text',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>

            </div>

        <?php }else{ foreach($prescriptionFields as $key => $value){ $visible_class= ($value['form_status']=='ACTIVE')?'display_block':'display_none'; if($value['input']=='YES'){ ?>




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
                    <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput year_input"  <?php echo ($required==true)?"data-required='1' required='required'":"data-required='0'"; ?>   value = "<?php echo $year; ?>" id="ageYear" type="number">
                    <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput month_input"  <?php echo ($required==true)?"data-required='1' required='required'":"data-required='0'"; ?>  value = "<?php echo $month; ?>" id="ageMonth" type="number">
                    <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput day_input"  <?php echo ($required==true)?"data-required='1' required='required'":"data-required='0'"; ?>  value = "<?php echo $day; ?>" id="ageDay" type="number">
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


                <div class="col-sm-2 <?php echo $visible_class; ?>">
                    <label><?php echo $value['label']; ?></label>
                    <?php echo $this->Form->input($value['column'],array('type'=>'select','empty'=>'Please Select','options'=>$option,'label'=>false,'class'=>'form-control','required'=>$required,'oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                </div>

            <?php  }else {
                $list = "";

                $column = $value['column'];
                if($value['column']=='patient_name'){
                    $column = 'first_name';
                    $required =true;
                }else if($value['column']=='patient_height'){
                    $column = 'height';
                }else if($value['column']=='mobile'){
                    $required =true;
                }if($value['column']=='reason_of_appointment'){
                    $list = "reason_of_appointment";
                }




                $class = ($value['column']=='conceive_date' || $value['column']=='expected_date')?'conceive_date_div':'';

                ?>

                <?php if($column =="parents_name"){ ?>
                    <div class="col-sm-2 <?php echo $visible_class; ?>">
                        <label><?php echo $value['label']; ?></label>
                        <div class="parent_holder">
                            <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>$relation,'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                            <?php echo $this->Form->input($column,array('autoComplete'=>'off','type'=>'text',"maxlength"=>"50",'placeholder'=>$value['label'],'label'=>false,'class'=>'form-control cnt parent_name_input','required'=>$required,'oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                        </div>
                    </div>
                <?php }else if($column =="patient_category"){ ?>

                    <div class="col-sm-2 <?php echo $visible_class; ?>">
                        <label><?php  echo $value['label']; ?></label>
                        <?php echo $this->Form->input($value['column'],array('required'=>$required,'autoComplete'=>'off','type'=>'select','empty'=>'Please Select','options'=>$this->AppAdmin->get_patient_category_list_drp($login['User']['thinapp_id']),'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                    </div>
                <?php }else{ ?>


                    <?php if($column=='first_name'){ ?>
                        <div class="col-sm-4 <?php echo $class; ?> <?php echo $visible_class; ?>">
                            <div class="title">
                                <label>Title</label>
                                <?php echo $this->Form->input('title',array('required'=>@($value['required']=="YES")?true:false,'type'=>'select','options'=>$name_title,'label'=>false,'class'=>'form-control','oninvalid'=>"this.setCustomValidity('Please Enter Title.')",'oninput'=>"setCustomValidity('')")); ?>
                            </div>
                            <div class="patient_name_div">
                                <label><?php echo $value['label']; ?></label>
                                <?php echo $this->Form->input($column,array('required'=>$required,'list'=>$list,'autoComplete'=>'off','value'=>@$this->request->data['AppointmentCustomer'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control','id'=>'nameList','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                            </div>
                        </div>
                    <?php }else{ ?>
                        <div class="col-sm-2 <?php echo $class; ?> <?php echo $visible_class; ?>">
                            <label><?php echo $value['label']; ?></label>

                            <?php
                                $data_value = @$this->request->data['AppointmentCustomer'][$column];
                                if(!empty($data_value) && $column=='dob'){
                                    if($data_value != '0000-00-00'){
                                        $data_value = date('d/m/Y',strtotime($data_value));
                                    }else{
                                        $data_value = "";
                                    }
                                }

                            ?>

                            <?php echo $this->Form->input($column,array('required'=>$required,'list'=>$list,'autoComplete'=>'off','value'=>@$data_value,'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control cnt','oninvalid'=>"this.setCustomValidity('Please Enter ".$value['label'].".')",'oninput'=>"setCustomValidity('')")); ?>
                        </div>
                    <?php } ?>



                    <?php if($value['column']=='city_id'){ ?>
                        <div class="col-sm-2 city_name_box <?php echo $visible_class; ?>">
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
            <button type="submit" id="addAppointment" class="btn btn-success m_save"><i class="fa fa-save"></i> Save & Book</button>
            <button type="submit" id="addAppointmentCheckIn" class="btn btn-success m_save"><i class="fa fa-save"></i> Save & Check-In</button>
        </div>
        <?php echo $this->Form->input('slot_time',array('type'=>'hidden','value'=>@$this->request->data['slot_time'])); ?>
        <?php echo $this->Form->input('custom_token',array('type'=>'hidden','value'=>@$this->request->data['custom_token'])); ?>
        <?php echo $this->Form->input('queue_number',array('type'=>'hidden','value'=>@$this->request->data['queue_number'])); ?>
        <?php echo $this->Form->end(); ?>
    </div>
<?php } ?>


<datalist id="nameList">
    <?php foreach($datalistUser as $val){ ?>
    <option value="<?php echo $val; ?>">
        <?php } ?>
</datalist>
<datalist id="addressList">
    <?php foreach($datalistAddress as $val){ ?>
    <option value="<?php echo $val; ?>">
        <?php } ?>
</datalist>
<datalist id="reason_of_appointment">
    <?php foreach($reason_of_appointment_list AS $list){ ?>
    <option value="<?php echo $list[0]; ?>">
        <?php } ?>
</datalist>





<script>
    $(function () {



        $(".display_block").find('input, select').each(function(){
            if($(this).prop('required')){

                if($(this).closest('.display_block').find('.validate_lbl').length==0){
                    $(this).closest('.display_block').find("label").append("<span class='validate_lbl'>*</span>");
                }
            }
        });




        if($("#ageYear").val()!='' || $("#ageMonth").val()!='' ||$("#ageDay").val()!=''){
            $('#ageYear, #ageMonth, #ageDay').removeAttr('required');
        }

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
                    selectFirst :true,
                    required:country_required
                });

                state = $(state_obj).magicSuggest({
                    allowFreeEntries:false,
                    allowDuplicates:false,
                    maxDropHeight: 345,
                    maxSelection: 1,
                    required: false,
                    noSuggestionText: '',
                    useTabKey: true,
                    selectFirst :true,
                    required:state_required
                });

                city = $(city_obj).magicSuggest({
                    allowFreeEntries:true,
                    allowDuplicates:false,
                    maxDropHeight: 345,
                    maxSelection: 1,
                    required: false,
                    noSuggestionText: '',
                    useTabKey: true,
                    selectFirst :true,
                    required:city_required
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
                                    //state.input.focus();
                                    $(state_obj,city_obj).attr('disabled',false);
                                    if($(state_obj).val()!='' && $(state_obj).val()!='0'){
                                        state.setValue([$(state_obj).val()]);
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
                                    //city.input.focus();
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
                else
                {
                    country.setValue([101]);
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


        setTimeout(function () {
            if($("#app_search_customer").val()!=''){
                $("#nameList").focus();
            }else{
                $("#app_search_customer").focus();
            }
        },500);

        $("[type='text']").attr('autoComplete','off');

        var create_form_dynamic = "<?php echo ($create_form_dynamic==true)?"YES":"NO"; ?>";


        setTimeout(function () {

            var mobile = $("#app_search_customer").val();

            if( mobile !=""){
                $("[name='data[AppointmentCustomer][mobile]']").val($("#app_search_customer").val());
            }

            if( mobile !=""){
                $("[name='data[Children][mobile]']").val($("#app_search_customer").val());
            }


            var patient_name = $("#app_search_customer_name").val();
            if(patient_name !="" && $("#AppointmentCustomerFirstName").val() ==""){
                $("#AppointmentCustomerFirstName").val($("#app_search_customer_name").val());
            }

            if(patient_name !="" && $("#ChildrenChildName").val() ==""){
                $("#ChildrenChildName").val($("#app_search_customer_name").val());
            }

            $("#AppointmentCustomerFirstName, #ChildrenChildName").focus();

            $("#ChildrenDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});
            $("#AppointmentCustomerDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});

            $("#ChildrenDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});
            $("#AppointmentCustomerDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

            var conceive = $("#AppointmentCustomerConceiveDate").attr('value');
            var expected = $("#AppointmentCustomerExpectedDate").attr('value');

            $("#AppointmentCustomerConceiveDate, #AppointmentCustomerExpectedDate").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true});

            $("#AppointmentCustomerConceiveDate, #AppointmentCustomerExpectedDate").mask("99/99/9999", {placeholdclearBtn:'dd/mm/yyyy'});



            $("#AppointmentCustomerConceiveDate").val(conceive);
            $("#AppointmentCustomerExpectedDate").val(expected);


            $("input[name='data[AppointmentCustomer][height]'], input[name='data[AppointmentCustomer][weight]'], input[name='data[AppointmentCustomer][head_circumference]']").val('');
            $("input[name='data[Children][height]'], input[name='data[Children][weight]'], input[name='data[Children][head_circumference]']").val('');


        },10);

        $("#focusInput").focus();

        $(document).off("change","#AppointmentCustomerGender");
        $(document).on("change","#AppointmentCustomerGender",function(){

            if(create_form_dynamic=='NO') {
                $("#booking_form .col-sm-2").removeClass('clear');
            }
            $("#AppointmentCustomerConceiveDate, #AppointmentCustomerExpectedDate").val('');
            if($(this).val()=='FEMALE'){
                $(".conceive_date_div").show();
                if(create_form_dynamic=='NO') {
                    $("#booking_form .col-sm-2:nth-child(9)").addClass('clear');
                }
            }else{
                $(".conceive_date_div").hide();
                if(create_form_dynamic=='NO') {
                    $("#booking_form .col-sm-2:nth-child(11)").addClass('clear');
                }
            }



        });

        $("#AppointmentCustomerGender").trigger("change");

        $(document).off('click','#booking_form');
        $(document).on('click','#booking_form',function(event) {
            $(this).data('clicked',$(event.target));
        });




        $(document).off("submit","#booking_form");
        $(document).on("submit","#booking_form",function(e){
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
               var btnID = $(this).data('clicked').attr('id');
               var data = $( this ).serialize();
               var $btn = $("#"+btnID);
               var checkedIN = (btnID == 'addAppointmentCheckIn')?'YES':'NO';
               $(".file_error").fadeOut('slow');
               $.ajax({
                   type:'POST',
                   url: baseurl+"app_admin/web_book_appointment",
                   data:{
                       data:data,
                       d:btoa($("#dialog_doctor_drp").val()),
                       s:btoa($("#dialog_service_drp").val()),
                       a:btoa($("#dialog_address_drp").val()),
                       app_type:$("#search_cus_modal").attr('data-type'),
                       app_slot:$("#search_cus_modal").attr('data-slot'),
                       app_queue:$("#search_cus_modal").attr('data-queue'),
                       app_mobile:$("#app_search_customer").val(),
                       booking_date:$("#appointment_date").val(),
                       f:$("#booking_form").attr('data-f'),
                       p:$("#booking_form").attr('data-p'),
                       checked_in:checkedIN,
                       direct_book:false

                   },
                   beforeSend:function(){
                       isSearching = 'YES';
                       $('#'+btnID).button('loading').html('Loading...');
                       $(".app_error_msg").hide();
                       $(".customer_loading").show();
                   },
                   success:function(data){
                       isSearching = 'NO';
                       $('#'+btnID).button('reset');
                       $(".customer_loading").hide();

                       var response = JSON.parse(data);
                       if(response.status==1){

                           $("#search_cus_modal").modal("hide");
                           $("#appointment_date").trigger('changeDate');
                           if(response.payment_dialog){
                               var html = $(response.payment_dialog).filter('#myModalFormAdd');
                               $(html).modal('show');
                           }


                       }else{

                           $(".error_msg_div").html(response.message).fadeIn('slow');
                       }
                   },
                   error: function(data){
                       isSearching = 'NO';
                       $('#'+btnID).button('reset');
                       $(".customer_loading").hide();
                       $(".error_msg_div").html("Sorry something went wrong on server.").fadeIn('slow');
                   }
               });
           }

        });

        <?php if($user_type != 'CHILDREN'){ ?>


        $(document).off("reset","#booking_form");
        $(document).on("reset","#booking_form",function(){
            setTimeout(function () {
                $("#AppointmentCustomerGender").trigger("change");
            },5)

        });


        $(document).off("input","#ageYear,#ageMonth,#ageDay");
        $(document).on("input","#ageYear,#ageMonth,#ageDay",function(e){

            if($(this).attr('data-required')){
                if($(this).val()!='') {
                    $('#ageYear, #ageMonth, #ageDay').removeAttr('required');
                } else {
                    $('#ageYear, #ageMonth, #ageDay').attr('required', 'required');
                }
            }


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


        <?php
        function js_str($s){
            return '"' . addcslashes($s, "\0..\37\"\\") . '"';
        }
        function js_array($array){
            $temp = array_map('js_str', $array);
            return '[' . implode(',', $temp) . ']';
        }
        echo 'var availableTags = ', js_array($refferer_list), ';'; ?>


        $( "#AppointmentCustomerReferredBy,#ChildrenReferredBy,#referredBy" ).autocomplete({
            source: availableTags,
            delay: 100,
            classes: {
                "ui-autocomplete": "highlight"
            },
            select: function( event, ui ) {
                event.preventDefault();
                var name = ui.item.value.split('-');
                $("#AppointmentCustomerReferredBy,#ChildrenReferredBy,#referredBy").val(name[0]);
                if(name[1]){
                    $("#AppointmentCustomerReferredByMobile,#ChildrenReferredByMobile,#referredByMobile").val(name[1]);
                }else{
                    $("#AppointmentCustomerReferredByMobile,#ChildrenReferredByMobile,#referredByMobile").val('');
                }
            }
        });

    });
</script>

<script>
    $(document).off("change","#ChildrenTitle, #AppointmentCustomerTitle");
    $(document).on("change","#ChildrenTitle, #AppointmentCustomerTitle",function(){
        var value = $(this).val();
        var gender = "";
        var titleMale = ["Baba", "Dr.", "MR.", "Mr", "Master"];
        var titleFemale = ["DR.(MRS)", "DR.(MS)", "MS.", "Mrs.", "Miss.", "Baby"];
        if(titleMale.includes(value))
        {
            gender = 'MALE';
        }
        else if(titleFemale.includes(value))
        {
            gender = 'FEMALE';
        }

        if(gender != "")
        {
            $("#AppointmentCustomerGender ,#ChildrenGender").val(gender);
        }
    });
</script>