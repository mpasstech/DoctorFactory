<?php
$login = $this->Session->read('Auth.User');
$prescriptionFields = $this->AppAdmin->getHospitalPrescriptionFields($login['User']['thinapp_id']);
$reason_of_appointment_list = $this->AppAdmin->get_reason_of_appointment_list($login['User']['thinapp_id']);
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown(true);
?>

<style>
    .ms-ctn .ms-sel-ctn {

        margin-left: -2px;

    }
    .red{color:red;font-size: 15px;}
    .relation_prefix {
        width: 25%;
    }
    .parent_name_input {
        width: 75%;
    }
    .parent_holder .input {
        display: contents;
    }
    .parent_holder {
        display: flex;
    }
    .clear_both{
        clear: both;
    }
</style>
 <?php if(!empty($post) && ($post['type'] != 'NEW')){ ?>
        <?php if($post['user_type'] == 'CHILDREN'){ ?>

             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                         <?php echo $this->Form->create('Children',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'formAdd')); ?>

                            <?php
                            $dob = $this->request['data']['Children']['dob'];
                            if($dob != '' || $dob != '0000-00-00' || $dob != null )
                            {
                                $dob = date("d/m/Y",strtotime($dob));
                            }
                            else
                                {
                                    $dob = '';
                                }

                            $parents_mobile =$this->request['data']['Children']['parents_mobile'];
                            if($parents_mobile != '' || $parents_mobile != null )
                            {
                                $parents_mobile = str_replace("+91","",$parents_mobile);
                            }
                            else
                            {
                                $parents_mobile = '';
                            }
                            ?>

                                <div class="col-sm-6">
                                    <label>Child Name<span class="red">*</span></label>
                                    <?php echo $this->Form->input('child_name',array('type'=>'text',"maxlength"=>"25",'id'=>'focusInput','list'=>'nameList','placeholder'=>'Child Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                    <?php echo $this->Form->input('id',array('type'=>'hidden','label'=>false,'required'=>true)); ?>
                                    <?php echo $this->Form->input('patient_type',array('type'=>'hidden','value'=>'CHILDREN','label'=>false,'required'=>true)); ?>

                                </div>

                                <div class="col-sm-6">
                                    <label>Date Of Birth<span class="red">*</span></label>
                                    <?php echo $this->Form->input('dob',array('type'=>'text', 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'value'=>$dob,'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                    <?php echo $this->Form->input('postData',array('type'=>'hidden','value'=>json_encode($post),'label'=>false)); ?>
                                </div>

                             <div class="col-sm-6">
                                 <label>Relation</label>
                                 <div class="parent_holder">
                                     <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O'),'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                                     <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                                 </div>
                             </div>
                             <div class="col-sm-6">
                                 <label>Parent Mobile</label>
                                 <?php echo $this->Form->input('parents_mobile',array('type'=>'text','value'=>$parents_mobile,'placeholder'=>'Parents Mobile',"pattern"=>"[56789][0-9]{9}",'title'=>"Please enter valid 10 digit mobile number",'label'=>false,'class'=>'form-control cnt')); ?>

                             </div>
                            <div class="col-sm-6">
                                <label>Gender</label>
                                <?php echo $this->Form->input('gender',array('type'=>'select','options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                            <div class="col-sm-6">
                                <label>Blood Group</label>
                                <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                             <div class="col-sm-6">
                                 <label>Reason Of Appointment</label>
                                 <?php echo $this->Form->input('reason_of_appointment',array('type'=>'textarea',"list"=>"reason_of_appointment","maxlength"=>"250",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>

                             </div>
                             <div class="col-sm-6">
                                 <label>Remark</label>
                                 <?php echo $this->Form->input('notes',array('type'=>'textarea',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>

                             </div>
                                 <div class="col-sm-6">
                                     <label>Address</label>
                                     <?php echo $this->Form->input('patient_address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                                 </div>

                                <div class="col-sm-6">

                                    <label>Consulting Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <?php echo $this->Form->input('appointment_staff_id',array('type'=>'select',"id"=>"appointmentStaffID",'options'=>$doctor_list,'empty'=>'Please Select','label'=>false,'class'=>'form-control cnt')); ?>
                                    <!--select name="appointment_staff_id" id="appointmentStaffID" required="true" class="form-control">
                                        <option value="">Please Select</option>
                                        <?php foreach($doctor_list AS $docID => $docName ){ ?>
                                            <option value="<?php echo $docID; ?>"><?php echo $docName; ?></option>
                                        <?php }?>
                                    </select-->
                                </div>

                                 <div class="col-sm-6">
                                     <label>Appointment Service:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                     <select name="data[Children][appointment_service_id]" id="appointmentServiceID" required="true" class="form-control">
                                         <option value="">Please Select</option>
                                     </select>
                                 </div>


                                 <div class="col-sm-6">
                                     <label>Appointment Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                     <select name="data[Children][appointment_address_id]" id="appointmentAddressID" required="true" class="form-control">
                                         <option value="">Please Select</option>
                                     </select>
                                 </div>

                                 <div class="col-sm-6">
                                     <label>Referred By (Name)</label>
                                     <?php echo $this->Form->input('referred_by',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Referred By (Name)",'id'=>'referredBy','label'=>false,'class'=>'form-control cnt')); ?>

                                 </div>


                                     <div class="col-sm-6">
                                         <label>Referred By (Mobile)</label>
                                         <?php echo $this->Form->input('referred_by_mobile',array('type'=>'text','id'=>'referredByMobile',"maxlength"=>"15",'placeholder'=>"Referred By (Mobile)",'label'=>false,'class'=>'form-control cnt')); ?>

                                     </div>

                                    <?php if(isset($prescriptionFields['weight']) && ($prescriptionFields['weight']['status'] == 'ACTIVE')) { ?>
                                        <div class="col-sm-6">
                                            <label>Weight (KG)</label>
                                            <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Weight (in Kilograms)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                                        </div>
                                    <?php }?>




                                     <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                                         <div class="col-sm-6">
                                             <label>Height (M)</label>
                                             <?php echo $this->Form->input('height',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"height (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                                         </div>
                                     <?php }?>

                                     <?php if(isset($prescriptionFields['head circumference']) && ($prescriptionFields['head circumference']['status'] == 'ACTIVE')) { ?>
                                         <div class="col-sm-6">
                                             <label>Head Circumference</label>
                                             <?php echo $this->Form->input('head_circumference',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Head Circumference (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                                         </div>
                                     <?php }?>

                             <div class="col-sm-6 col-sm-offset-3">
                                 <label>&nbsp;</label>
                                 <?php echo $this->Form->submit('Save & Book',array('class'=>'Btn-typ5','id'=>'addAppointment')); ?>
                             </div>


                         <?php echo $this->Form->end(); ?>

             </div>


        <?php }
        else
            { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'formAdd')); ?>
                    <?php
                        $dob = $this->request['data']['AppointmentCustomer']['dob'];
                        if(!empty($dob) && $dob != '' && $dob != '0000-00-00' && $dob != null )
                        {

                            $dob = date("d/m/Y",strtotime($dob));
                        }
                        else
                        {
                            $dob = '';
                        }
                    ?>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Name<span class="red">*</span></label>
                                <?php echo $this->Form->input('first_name',array('type'=>'text','id'=>'focusInput',"maxlength"=>"50",'list'=>'nameList','placeholder'=>'Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                                <?php echo $this->Form->input('id',array('type'=>'hidden','label'=>false,'required'=>true)); ?>
                                <?php echo $this->Form->input('patient_type',array('type'=>'hidden','value'=>'CUSTOMER','label'=>false,'required'=>true)); ?>
                            </div>

                            <div class="col-sm-6 ">
                                <label style="display: block;">Age</label>
                                <div class="input text ">

                                    <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput" id="ageYear" type="number">
                                    <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput" id="ageMonth" type="number">
                                    <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput" id="ageDay" type="number">
                                    <?php echo $this->Form->input('age',array('type'=>'hidden',"maxlength"=>"50",'placeholder'=>'Age','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                            </div>




                            



                            <div class="col-sm-6 clear_both">
                                <label>Date Of Birth</label>
                                <?php echo $this->Form->input('dob',array('type'=>'text','value'=>$dob, 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt')); ?>
                                <?php echo $this->Form->input('postData',array('type'=>'hidden','value'=>json_encode($post),'label'=>false)); ?>
                            </div>

                            <div class="col-sm-6">
                                <label>Gender</label>
                                <?php echo $this->Form->input('gender',array('type'=>'select','options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>

                            <div class="col-sm-6">
                                <label>Email</label>
                                <?php echo $this->Form->input('email',array('type'=>'email',"maxlength"=>"50",'placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>

                            <div class="col-sm-2">
                                <label>Country</label>
                                <?php echo $this->Form->input('country_id',array('type'=>'text','label'=>false,'class'=>'form-control country')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php $state_list =array();?>
                                <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                                <?php echo $this->Form->input('state_id',array('type'=>'text','label'=>false,'class'=>'form-control state')); ?>
                            </div>
                            <div class="col-sm-2">
                                <?php $city_list =array();?>
                                <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                                <?php echo $this->Form->input('city_id',array('type'=>'text','label'=>false,'class'=>'form-control city')); ?>
                            </div>

                            <?php if(!isset($this->request['data']['AppointmentCustomer'])){ ?>
                                <script>
                                    function addMagicsuggest(html){
                                        var msc = $(html).find(".country").magicSuggest({
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

                                        var ms = $(html).find(".state").magicSuggest({
                                            allowFreeEntries:false,
                                            allowDuplicates:false,
                                            maxDropHeight: 345,
                                            maxSelection: 1,
                                            required: false,
                                            noSuggestionText: '',
                                            useTabKey: true,
                                            selectFirst :true
                                        });

                                        var mscity = $(html).find(".city").magicSuggest({
                                            allowFreeEntries:true,
                                            allowDuplicates:false,
                                            maxDropHeight: 345,
                                            maxSelection: 1,
                                            required: false,
                                            noSuggestionText: '',
                                            useTabKey: true,
                                            selectFirst :true
                                        });

                                        $(msc).on('selectionchange', function(e,m){
                                            var $this = this;
                                            var IdArr = this.getSelection();
                                            if(IdArr[0])
                                            {
                                                var country_id =IdArr[0].id;
                                                if(country_id){
                                                    $.ajax({
                                                        type:'POST',
                                                        url: baseurl+"app_admin/get_state_list_json",
                                                        data:{country_id:country_id},
                                                        beforeSend:function(){

                                                            //$('.state_spin').show();
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").show();
                                                            $('.city, .state').attr('disabled',true).html('');
                                                        },
                                                        success:function(data){
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").hide();
                                                            ms.setData(JSON.parse(data));
                                                            $('.city, .state').attr('disabled',false);
                                                        },
                                                        error: function(data){
                                                        }
                                                    });
                                                }
                                            }

                                        });

                                        $(ms).on('selectionchange', function(e,m) {
                                            var $this = this;
                                            var IdArr = this.getSelection();
                                            if (IdArr[0]) {
                                                var state_id =IdArr[0].id;
                                                if(state_id){
                                                    $.ajax({
                                                        type:'POST',
                                                        url: baseurl+"app_admin/get_city_list_json",
                                                        data:{state_id:state_id},
                                                        beforeSend:function(){
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").show();
                                                            $('.city').attr('disabled',true).html('');
                                                        },
                                                        success:function(data){
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").hide();
                                                            mscity.setData(JSON.parse(data));
                                                        },
                                                        error: function(data){
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                    addMagicsuggest($(document));
                                </script>
                            <?php }
                            else{ ?>
                                <script>
                                    function addMagicsuggest(html){
                                        var msc = $(html).find(".country").magicSuggest({
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

                                        var ms = $(html).find(".state").magicSuggest({
                                            allowFreeEntries:false,
                                            allowDuplicates:false,
                                            maxDropHeight: 345,
                                            maxSelection: 1,
                                            required: false,
                                            noSuggestionText: '',
                                            useTabKey: true,
                                            selectFirst :true
                                        });

                                        var mscity = $(html).find(".city").magicSuggest({
                                            allowFreeEntries:true,
                                            allowDuplicates:false,
                                            maxDropHeight: 345,
                                            maxSelection: 1,
                                            required: false,
                                            noSuggestionText: '',
                                            useTabKey: true,
                                            selectFirst :true
                                        });

                                        <?php if($this->request['data']['AppointmentCustomer']['country_id']){ ?>
                                        msc.setValue(<?php echo json_encode(array($this->request['data']['AppointmentCustomer']['country_id']),true); ?>);
                                        <?php } ?>

                                        <?php if($this->request['data']['AppointmentCustomer']['country_id']){ ?>
                                        $.ajax({
                                            type:'POST',
                                            url: baseurl+"app_admin/get_state_list_json",
                                            data:{country_id:<?php echo $this->request['data']['AppointmentCustomer']['country_id']; ?>},
                                            beforeSend:function(){
                                            },
                                            success:function(data){
                                                ms.setData(JSON.parse(data));
                                                <?php if($this->request['data']['AppointmentCustomer']['state_id']){ ?>
                                                ms.setValue(<?php echo json_encode(array($this->request['data']['AppointmentCustomer']['state_id']),true); ?>);
                                                <?php } ?>

                                            },
                                            error: function(data){
                                            }
                                        });
                                        <?php } ?>

                                        <?php if($this->request['data']['AppointmentCustomer']['state_id']){ ?>
                                        $.ajax({
                                            type:'POST',
                                            url: baseurl+"app_admin/get_city_list_json",
                                            data:{state_id:<?php echo $this->request['data']['AppointmentCustomer']['state_id']; ?>},
                                            beforeSend:function(){
                                            },
                                            success:function(data){
                                                mscity.setData(JSON.parse(data));
                                                <?php if($this->request['data']['AppointmentCustomer']['city_id'] && $this->request['data']['AppointmentCustomer']['state_id']){ ?>
                                                mscity.setValue(<?php echo json_encode(array($this->request['data']['AppointmentCustomer']['city_id']),true); ?>);
                                                <?php }
                                                else if($this->request['data']['AppointmentCustomer']['city_name'] != ""){ ?>
                                                mscity.setValue(<?php echo json_encode(array($this->request['data']['AppointmentCustomer']['city_name']),true); ?>);
                                                <?php } ?>
                                            },
                                            error: function(data){
                                            }
                                        });
                                        <?php }
                                        else if($this->request['data']['AppointmentCustomer']['city_name'] != ""){ ?>
                                        mscity.setValue(<?php echo json_encode(array($this->request['data']['AppointmentCustomer']['city_name']),true); ?>);
                                        <?php } ?>


                                        $(msc).on('selectionchange', function(e,m){
                                            var $this = this;
                                            var IdArr = this.getSelection();
                                            if(IdArr[0])
                                            {
                                                var country_id =IdArr[0].id;
                                                if(country_id){
                                                    $.ajax({
                                                        type:'POST',
                                                        url: baseurl+"app_admin/get_state_list_json",
                                                        data:{country_id:country_id},
                                                        beforeSend:function(){

                                                            //$('.state_spin').show();
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").show();
                                                            $('.city, .state').attr('disabled',true).html('');
                                                        },
                                                        success:function(data){
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".state_spin").hide();
                                                            ms.setData(JSON.parse(data));
                                                            $('.city, .state').attr('disabled',false);
                                                        },
                                                        error: function(data){
                                                        }
                                                    });
                                                }
                                            }

                                        });

                                        $(ms).on('selectionchange', function(e,m) {
                                            var $this = this;
                                            var IdArr = this.getSelection();
                                            if (IdArr[0]) {
                                                var state_id =IdArr[0].id;
                                                if(state_id){
                                                    $.ajax({
                                                        type:'POST',
                                                        url: baseurl+"app_admin/get_city_list_json",
                                                        data:{state_id:state_id},
                                                        beforeSend:function(){
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").show();
                                                            $('.city').attr('disabled',true).html('');
                                                        },
                                                        success:function(data){
                                                            $($($this)[0]['container'][0]).parents(".col-sm-2").siblings(".col-sm-2").find(".city_spin").hide();
                                                            mscity.setData(JSON.parse(data));
                                                        },
                                                        error: function(data){
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                    addMagicsuggest($(document));
                                </script>
                            <?php } ?>

                            <div class="col-sm-6">
                                <label>Address</label>
                                <?php echo $this->Form->input('address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>

                            <div class="col-sm-6">
                                <label>Relation</label>
                                <div class="parent_holder">
                                    <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O'),'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                                    <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label>Mobile</label>
                                <?php echo $this->Form->input('mobile',array('type'=>'text','placeholder'=>'Mobile','label'=>false,'class'=>'form-control cnt')); ?>

                            </div>

                            <div class="col-sm-6">
                                <label>Marital Status</label>
                                <?php echo $this->Form->input('marital_status',array('type'=>'select',"options"=>array("MARRIED"=>"Married","UNMARRIED"=>"Unmarried"),'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                            <div class="col-sm-6">
                                <label>Blood Group</label>
                                <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>

                            <div class="col-sm-6">
                                <label>Reason Of Appointment</label>
                                <?php echo $this->Form->input('reason_of_appointment',array('type'=>'textarea',"list"=>"reason_of_appointment","maxlength"=>"250",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>
                            <div class="col-sm-6">
                                <label>Notes</label>
                                <?php echo $this->Form->input('notes',array('type'=>'textarea',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>

                            <div class="col-sm-6">
                                <label>Consulting Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                <?php echo $this->Form->input('appointment_staff_id',array('type'=>'select',"id"=>"appointmentStaffID",'options'=>$doctor_list,'empty'=>'Please Select','label'=>false,'class'=>'form-control cnt')); ?>
                            </div>

                            <div class="col-sm-6">
                                <label>Appointment Service:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                <select name="data[AppointmentCustomer][appointment_service_id]" id="appointmentServiceID" required="true" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                            </div>


                            <div class="col-sm-6">
                                <label>Appointment Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                <select name="data[AppointmentCustomer][appointment_address_id]" id="appointmentAddressID" required="true" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                            </div>





                            <div class="col-sm-6">
                                <label>Referred By (Name)</label>
                                <?php echo $this->Form->input('referred_by',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Referred By (Name)",'id'=>'referredBy','label'=>false,'class'=>'form-control cnt')); ?>
                            </div>


                            <div class="col-sm-6">
                                <label>Referred By (Mobile)</label>
                                <?php echo $this->Form->input('referred_by_mobile',array('type'=>'text','id'=>'referredByMobile',"maxlength"=>"15",'placeholder'=>"Referred By (Mobile)",'label'=>false,'class'=>'form-control cnt')); ?>

                            </div>

                            <?php if(isset($prescriptionFields['weight']) && ($prescriptionFields['weight']['status'] == 'ACTIVE')) { ?>
                                <div class="col-sm-6">
                                    <label>Weight (KG)</label>
                                    <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Weight (in Kilograms)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                            <?php }?>


                            <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                                <div class="col-sm-6">
                                    <label>Height (M)</label>
                                    <?php echo $this->Form->input('height',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"height (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                            <?php }?>

                            <?php if(isset($prescriptionFields['head circumference']) && ($prescriptionFields['head circumference']['status'] == 'ACTIVE')) { ?>
                                <div class="col-sm-6">
                                    <label>Head Circumference</label>
                                    <?php echo $this->Form->input('head_circumference',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Head Circumference (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                            <?php }?>

                            <div class="col-sm-6 col-sm-offset-3">
                            <label>&nbsp;</label>
                            <?php echo $this->Form->submit('Save & Book',array('class'=>'Btn-typ5','id'=>'addAppointment')); ?>
                        </div>

                    <?php echo $this->Form->end(); ?>
                </div>
            <?php } ?>
    <?php }
    else
    { ?>
        <?php if($post['user_type'] == 'CHILDREN'){ ?>

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

            <?php echo $this->Form->create('Children',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'formAdd')); ?>



                <div class="col-sm-6">
                    <label>Child Name<span class="red">*</span></label>
                    <?php echo $this->Form->input('child_name',array('type'=>'text','id'=>'focusInput','list'=>'nameList','placeholder'=>'Child Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                    <?php echo $this->Form->input('patient_type',array('type'=>'hidden',"maxlength"=>"25",'value'=>'CHILDREN','label'=>false,'required'=>true)); ?>

                </div>

                <div class="col-sm-6">
                    <label>Date Of Birth<span class="red">*</span></label>
                    <?php echo $this->Form->input('dob',array('type'=>'text', 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>

                </div>




                <div class="col-sm-6">
                    <label>Relation</label>
                    <div class="parent_holder">
                        <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O'),'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                        <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Parent Mobile</label>
                    <?php echo $this->Form->input('parents_mobile',array('type'=>'text','placeholder'=>'Parents Mobile',"pattern"=>"[56789][0-9]{9}",'title'=>"Please enter valid 10 digit mobile number",'label'=>false,'class'=>'form-control cnt')); ?>

                </div>


                <div class="col-sm-6">
                    <label>Gender</label>
                    <?php echo $this->Form->input('gender',array('type'=>'select','options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'label'=>false,'class'=>'form-control cnt')); ?>
                    <?php echo $this->Form->input('postData',array('type'=>'hidden','value'=>json_encode($post),'label'=>false)); ?>
                </div>
                <div class="col-sm-6">
                    <label>Blood Group</label>
                    <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <div class="col-sm-6">
                    <label>Reason Of Appointment</label>
                    <?php echo $this->Form->input('reason_of_appointment',array('type'=>'textarea',"list"=>"reason_of_appointment","maxlength"=>"250",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <div class="col-sm-6">
                    <label>Notes</label>
                    <?php echo $this->Form->input('notes',array('type'=>'textarea',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <div class="col-sm-6">
                    <label>Address</label>
                    <?php echo $this->Form->input('patient_address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <div class="col-sm-6">
                    <label>Consulting Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>

                    <?php echo $this->Form->input('appointment_staff_id',array('type'=>'select',"id"=>"appointmentStaffID",'options'=>$doctor_list,'empty'=>'Please Select','label'=>false,'class'=>'form-control cnt')); ?>


                </div>

                <div class="col-sm-6">
                    <label>Appointment Service:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <select name="data[Children][appointment_service_id]" id="appointmentServiceID" required="true" class="form-control">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <label>Appointment Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <select name="data[Children][appointment_address_id]" id="appointmentAddressID" required="true" class="form-control">
                        <option value="">Please Select</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Referred By (Name)</label>
                    <?php echo $this->Form->input('referred_by',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Referred By (Name)",'id'=>'referredBy','label'=>false,'class'=>'form-control cnt')); ?>

                </div>


                <div class="col-sm-6">
                    <label>Referred By (Mobile)</label>
                    <?php echo $this->Form->input('referred_by_mobile',array('type'=>'text','id'=>'referredByMobile',"maxlength"=>"15",'placeholder'=>"Referred By (Mobile)",'label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <?php if(isset($prescriptionFields['weight']) && ($prescriptionFields['weight']['status'] == 'ACTIVE')) { ?>
                    <div class="col-sm-6">
                        <label>Weight (KG)</label>
                        <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Weight (in Kilograms)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                <?php }?>






                <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                    <div class="col-sm-6">
                        <label>Height (M)</label>
                        <?php echo $this->Form->input('height',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"height (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                <?php }?>
                <?php if(isset($prescriptionFields['head circumference']) && ($prescriptionFields['head circumference']['status'] == 'ACTIVE')) { ?>
                    <div class="col-sm-6">
                        <label>Head Circumference</label>
                        <?php echo $this->Form->input('head_circumference',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Head Circumference (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                <?php }?>

                <div class="col-sm-6 col-sm-offset-3">
                    <label>&nbsp;</label>
                    <?php echo $this->Form->submit('Save & Book',array('class'=>'Btn-typ5','id'=>'addAppointment')); ?>
                </div>

            <?php echo $this->Form->end(); ?>

        </div>


    <?php }
        else
    { ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'formAdd')); ?>
                <div class="col-sm-6">
                    <label>Name<span class="red">*</span></label>
                    <?php echo $this->Form->input('first_name',array('type'=>'text',"maxlength"=>"30",'id'=>'focusInput','list'=>'nameList','placeholder'=>'Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                    <?php echo $this->Form->input('patient_type',array('type'=>'hidden','value'=>'CUSTOMER','label'=>false,'required'=>true)); ?>
                </div>

                <div class="col-sm-6">
                    <label style="display: block;">Age</label>
                    <div class="text input">
                        <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput" id="ageYear" type="number">
                        <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput" id="ageMonth" type="number">
                        <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput" id="ageDay" type="number">
                    </div>

                    <?php echo $this->Form->input('age',array('type'=>'hidden',"maxlength"=>"50",'placeholder'=>'Age','label'=>false,'class'=>'form-control cnt')); ?>

                </div>

                <div class="col-sm-6 clear_both">
                    <label>Date Of Birth</label>
                    <?php echo $this->Form->input('dob',array('type'=>'text', 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt')); ?>
                    <?php echo $this->Form->input('postData',array('type'=>'hidden','value'=>json_encode($post),'label'=>false)); ?>

                </div>

                <div class="col-sm-6">
                    <label>Gender</label>
                    <?php echo $this->Form->input('gender',array('type'=>'select','options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'label'=>false,'class'=>'form-control cnt')); ?>

                </div>

                <div class="col-sm-6">
                    <label>Email</label>
                    <?php echo $this->Form->input('email',array('type'=>'email',"maxlength"=>"50",'placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <div class="col-sm-6">
                    <label>Address</label>
                    <?php echo $this->Form->input('address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                </div>

                <div class="col-sm-6">
                    <label>Marital Status</label>
                    <?php echo $this->Form->input('marital_status',array('type'=>'select',"options"=>array("MARRIED"=>"Married","UNMARRIED"=>"Unmarried"),'label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <div class="col-sm-6">
                    <label>Blood Group</label>
                    <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>

                </div>

                <div class="col-sm-6">
                    <label>Reason Of Appointment</label>
                    <?php echo $this->Form->input('reason_of_appointment',array('type'=>'textarea',"list"=>"reason_of_appointment","maxlength"=>"250",'placeholder'=>"Reason Of Appointment",'label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <div class="col-sm-6">
                    <label>Notes</label>
                    <?php echo $this->Form->input('notes',array('type'=>'textarea',"maxlength"=>"250",'placeholder'=>"Notes",'label'=>false,'class'=>'form-control cnt')); ?>

                </div>

                <div class="col-sm-6">
                    <label>Relation</label>
                    <div class="parent_holder">
                        <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O'),'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                        <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <label>Consulting Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>

                    <?php echo $this->Form->input('appointment_staff_id',array('type'=>'select',"id"=>"appointmentStaffID",'options'=>$doctor_list,'empty'=>'Please Select','label'=>false,'class'=>'form-control cnt')); ?>

                </div>

                <div class="col-sm-6">
                    <label>Appointment Service:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <select name="data[AppointmentCustomer][appointment_service_id]" id="appointmentServiceID" required="true" class="form-control">
                        <option value="">Please Select</option>
                    </select>
                </div>

                <div class="col-sm-6">
                    <label>Appointment Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <select name="data[AppointmentCustomer][appointment_address_id]" id="appointmentAddressID" required="true" class="form-control">
                        <option value="">Please Select</option>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>Referred By (Name)</label>
                    <?php echo $this->Form->input('referred_by',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Referred By (Name)",'id'=>'referredBy','label'=>false,'class'=>'form-control cnt')); ?>

                </div>



                <div class="col-sm-6">
                    <label>Referred By (Mobile)</label>
                    <?php echo $this->Form->input('referred_by_mobile',array('type'=>'text','id'=>'referredByMobile',"maxlength"=>"15",'placeholder'=>"Referred By (Mobile)",'label'=>false,'class'=>'form-control cnt')); ?>

                </div>
                <?php if(isset($prescriptionFields['weight']) && ($prescriptionFields['weight']['status'] == 'ACTIVE')) { ?>
                    <div class="col-sm-6">
                        <label>Weight (KG)</label>
                        <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Weight (in Kilograms)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                <?php }?>


                <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                    <div class="col-sm-6">
                        <label>Height (M)</label>
                        <?php echo $this->Form->input('height',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"height (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                <?php }?>
                <?php if(isset($prescriptionFields['head circumference']) && ($prescriptionFields['head circumference']['status'] == 'ACTIVE')) { ?>
                    <div class="col-sm-6">
                        <label>Head Circumference</label>
                        <?php echo $this->Form->input('head_circumference',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Head Circumference (in Centimeters)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                <?php }?>

                <div class="col-sm-6 col-sm-offset-3">
                    <label>&nbsp;</label>
                    <?php echo $this->Form->submit('Save & Book',array('class'=>'Btn-typ5','id'=>'addAppointment')); ?>
                </div>
            <?php echo $this->Form->end(); ?>
        </div>
    <?php }
    } ?>


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
<style>
    .customer_first_name, .customer_last_name{float: left;width: 70% !important;}
    .table-responsive{
        max-height: 205px;
        overflow-y: scroll;
    }
    textarea {

        height: 43px !important;

    }
    .ageInput {
        width: 30%;
        display: inline-block;
    }
</style>
 <script>
     $(function () {
         $("#ChildrenDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});
         $("#AppointmentCustomerDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});

         $("#ChildrenDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});
         $("#AppointmentCustomerDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

         $("#focusInput").focus();

     $(document).off("submit","#formAdd");
     $(document).on("submit","#formAdd",function(e){
         e.preventDefault();
         var data = $( this ).serialize();

         var $btn = $("#addAppointment");
         $(".file_error").fadeOut('slow');

            $.ajax({
                 type:'POST',
                 url: baseurl+"app_admin/web_book_appointment_without_token",
                 data:data,
                 beforeSend:function(){
                     $('#addAppointment').button('loading').html('Loading...');
                     $(".customer_loading").show();
                 },
                 success:function(data){

                     $('#addAppointment').button('reset');
                     $(".customer_loading").hide();

                     var response = JSON.parse(data);
                     if(response.status==1){
                         //$(".slot_date").trigger('changeDate');
                         $("#search_cus_modal_without_token").modal("hide");
                         addReceiptWithoutToken(response.appointmentID);
                     }else{
                         $(".app_error_msg").html(response.message).fadeIn('slow');
                     }

                 },
                 error: function(data){
                    console.log(data);
                     $('#addAppointment').button('reset');
                     $(".customer_loading").hide();
                     $(".app_error_msg").html("Sorry something went wrong on server.").fadeIn('slow');

                 }
            });


     });

        <?php if($post['user_type'] != 'CHILDREN'){ ?>
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

             $(document).ready(function(){
                 var age = $("#AppointmentCustomerAge").val();
                 if(age != "")
                 {
                     age = age.split(" ");

                     if(age.length >= 1) {

                         if($.isNumeric( age ) && age.length == 1){
                             $("#ageYear").val(age);
                         }else{
                             if (age[0].indexOf("Years") >= 0) {
                                 var year = age[0].replace("Years", "");
                                 $("#ageYear").val(year);
                             }
                             else if (age[0].indexOf("Months") >= 0) {
                                 var months = age[0].replace("Months", "");
                                 $("#ageMonth").val(months);
                             }
                             else if (age[0].indexOf("Days") >= 0) {
                                 var days = age[0].replace("Days", "");
                                 $("#ageDay").val(days);
                             }
                         }

                     }
                     if(age.length >= 2) {

                         if (age[1].indexOf("Years") >= 0) {
                             var year = age[1].replace("Years", "");
                             $("#ageYear").val(year);
                         }
                         else if (age[1].indexOf("Months") >= 0) {
                             var months = age[1].replace("Months", "");
                             $("#ageMonth").val(months);
                         }
                         else if (age[1].indexOf("Days") >= 0) {
                             var days = age[1].replace("Days", "");
                             $("#ageDay").val(days);
                         }
                     }


                     if(age.length >= 3) {
                         if (age[2].indexOf("Years") >= 0) {
                             var year = age[2].replace("Years", "");
                             $("#ageYear").val(year);
                         }
                         else if (age[2].indexOf("Months") >= 0) {
                             var months = age[2].replace("Months", "");
                             $("#ageMonth").val(months);
                         }
                         else if (age[2].indexOf("Days") >= 0) {
                             var days = age[2].replace("Days", "");
                             $("#ageDay").val(days);
                         }
                     }



                 }
             });
        <?php } ?>



     $(document).off('change','#appointmentStaffID');
     $(document).on('change','#appointmentStaffID',function(e){
         get_service_list();
     });

     $(document).off('change','#appointmentServiceID');
     $(document).on('change','#appointmentServiceID',function(e){
         get_address_list();
     });

     function get_service_list(){
         $("#appointmentServiceID, #appointmentAddressID").html('');
         var docID =$("#appointmentStaffID").val();
         if(docID){
             $.ajax({
                 type:'POST',
                 url: baseurl+"app_admin/get_doc_service_list",
                 data:{docID:docID},
                 beforeSend:function(){
                 },
                 success:function(data){
                     $("#appointmentServiceID").html(data);
                     if($("#appointmentServiceID option").length == 1){
                         get_address_list(true);
                     }
                 },
                 error: function(data){
                 }
             });
         }
     }
     function get_address_list(first_service){
         $("#appointmentAddressID").html('');
         var serID =$("#appointmentServiceID").val();
         if(first_service===true){
              serID =$("#appointmentServiceID option:first").val();
         }
         var docID =$("#appointmentStaffID").val();
         if(serID){
             $.ajax({
                 type:'POST',
                 url: baseurl+"app_admin/get_doc_address_list",
                 data:{docID:docID,serID:serID},
                 beforeSend:function(){
                 },
                 success:function(data){
                     $("#appointmentAddressID").html(data);
                 },
                 error: function(data){
                 }
             });
         }
     }



     function addReceiptWithoutToken(appointmentID){

         var id = appointmentID;
         var ipdCharge = "";
         var vaccinationCharge = "";
         var otherCharge = "";
         var opdCharge = "";

         $("#idContainerAddWithoutToken").val(id);
         $("#amountOPDAddWithoutToken").val(opdCharge);
         $("#ipdChargeAddWithoutToken").val(ipdCharge);
         $("#vaccinationChargeAddWithoutToken").val(vaccinationCharge);
         $("#otherChargeAddWithoutToken").val(otherCharge);
         $("#opdChargeAddWithoutToken").val(opdCharge);
         $("#priceOPDAddWithoutToken").val(opdCharge);

         $("#myModalFormAddWithoutToken").modal("show");
         setTimeout(function(){
             $("#opdChargeAddWithoutToken").focus();
             updateTotalWithoutToken();
         },1500);

     }

     function updateTotalWithoutToken(){
         var total =parseFloat($("#amountOPDAddWithoutToken").val());

         var tmp = total.toString().split(".");
         if(tmp.length ==2){
             total = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
         }

         $( "#myModalFormAddWithoutToken .modal-body input[name='amount[]']" ).each(function( index ) {

             var tmp = parseFloat($(this).val()).toString().split(".");
             if(tmp.length ==2){
                 tmp = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
             }
             total = parseFloat(total)+parseFloat(tmp);

         });

         if(isNaN(total))
         {
             $('.total_lblWithoutToken').val(0);
         }
         else
         {
             $('.total_lblWithoutToken').val(total);
         }


     }



     $(document).off('hidden.bs.modal','#myModalFormAddWithoutToken');



     });

 </script>


