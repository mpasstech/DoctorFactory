<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown();
?>



<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 admit_form_box">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">

                    <h3 class="screen_title">Add Appointment</h3>
                    <?php //echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <div class="row">
                            <?php echo $this->element('app_admin_without_appointment'); ?>
                        </div>

                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 patient_detail_section">
                            <h3>Patient Detail</h3>
                            <div class="form-group ">

                                <div class="col-sm-2">
                                    <label>Patient Name</label>
                                    <?php echo $this->Form->input('first_name',array('name'=>"data[AppointmentCustomer][first_name]",'required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter  name.')",'oninput'=>"setCustomValidity('')" ,'type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <label>Mobile</label>
                                    <?php echo $this->Form->input('mobile',array('name'=>"data[AppointmentCustomer][mobile]",'type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-2">

                                    <label>Parent Name</label>
                                    <?php echo $this->Form->input('parents_name',array(''=>"data[AppointmentCustomer][parents_name]",'type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control')); ?>
                                </div>


                                <div class="col-sm-2">
                                    <label>Parent Mobile</label>
                                    <?php echo $this->Form->input('parents_mobile',array('name'=>"data[AppointmentCustomer][parents_mobile]",'oninvalid'=>"this.setCustomValidity('Please enter  name.')",'oninput'=>"setCustomValidity('')" ,'type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                </div>




                                <div class="col-sm-2">
                                    <label>Gender</label>
                                    <?php echo $this->Form->input('gender',array('name'=>"data[AppointmentCustomer][gender]",'type'=>'select','label'=>false,'options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'class'=>'form-control')); ?>
                                </div>

                                <div class="col-sm-2">

                                    <label>Age</label>
                                    <?php echo $this->Form->input('age',array('name'=>"data[AppointmentCustomer][age]",'type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control')); ?>
                                </div>



                                <div class="col-sm-2">

                                    <label>DOB</label>
                                    <?php echo $this->Form->input('dob',array('name'=>"data[AppointmentCustomer][dob]",'type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control dob')); ?>
                                </div>

                                <div class="col-sm-2">

                                    <label>Marital Status</label>
                                    <?php echo $this->Form->input('marital_status',array('name'=>"data[AppointmentCustomer][marital_status]",'type'=>'select',"options"=>array("MARRIED"=>"Married","UNMARRIED"=>"Unmarried"),'label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-2">

                                    <label>Blood Group</label>
                                    <?php echo $this->Form->input('blood_group',array('name'=>"data[AppointmentCustomer][blood_group]",'type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>
                                </div>



                                <div class="col-sm-2">
                                    <label>Country</label>
                                    <?php echo $this->Form->input('country_id',array('name'=>"data[AppointmentCustomer][country_id]",'oninvalid'=>"this.setCustomValidity('Please select country.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select Country'),'options'=>$country_list,'class'=>'form-control country')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php $state_list =array();?>
                                    <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                                    <?php echo $this->Form->input('state_id',array('name'=>"data[AppointmentCustomer][state_id]",'oninvalid'=>"this.setCustomValidity('Please select state.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select State'),'options'=>$state_list,'class'=>'form-control state')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php $city_list =array();?>
                                    <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                                    <?php echo $this->Form->input('city_id',array('name'=>"data[AppointmentCustomer][city_id]",'oninvalid'=>"this.setCustomValidity('Please select city.')",'oninput'=>"setCustomValidity('')" ,'type'=>'select','label'=>false,'empty'=>array('0'=>'Select City'),'options'=>$city_list,'class'=>'form-control city')); ?>
                                </div>

                                <div class="col-sm-6">

                                    <label>Address</label>
                                    <?php echo $this->Form->input('address',array('name'=>"data[AppointmentCustomer][address]",'type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-6">

                                    <label>Medical History</label>
                                    <?php echo $this->Form->input('medical_history',array('name'=>"data[AppointmentCustomer][medical_history]",'type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                            <h3>Appointment Form</h3>
                            <div class="form-group">


                                <div class="col-sm-4">
                                    <label>Consultant</label>
                                    <?php echo $this->Form->input('',array('value'=>@$this->request->data['AppointmentWithoutToken']['appointment_staff_id'],'name'=>"data[AppointmentWithoutToken][appointment_staff_id]",'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select consultant.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Consultant','type'=>'select','label'=>false,'options'=>$doctor_list,'id'=>'consultDoc','class'=>'form-control message_type cnt')); ?>
                                </div>

                                    <div class="col-sm-4">
                                        <label>Address</label>
                                        <?php echo $this->Form->input('',array('value'=>@$this->request->data['AppointmentWithoutToken']['appointment_address_id'],'name'=>"data[AppointmentWithoutToken][appointment_address_id]",'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select address.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Address','type'=>'select','label'=>false,'options'=>array(),'class'=>'form-control message_type cnt','id'=>'address_holder')); ?>
                                    </div>



                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <?php echo $this->Form->input('',array('value'=>@$this->request->data['AppointmentWithoutToken']['appointment_datetime'],'name'=>"data[AppointmentWithoutToken][appointment_datetime]",'required'=>'required','value'=>date('d/m/Y'),'type'=>'text','label'=>false,'class'=>'form-control admit_date')); ?>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="col-sm-6">
                                    <label>Reason Of Appointment</label>
                                    <?php echo $this->Form->input('',array('value'=>@$this->request->data['AppointmentWithoutToken']['reason_of_appointment'],'name'=>"data[AppointmentWithoutToken][reason_of_appointment]",'type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-6">
                                    <label>Notes</label>
                                    <?php echo $this->Form->input('',array('value'=>@$this->request->data['AppointmentWithoutToken']['notes'],'name'=>"data[AppointmentWithoutToken][notes]",'type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                            </div>


                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>&nbsp;</label>
                                    <?php echo $this->Form->submit('Save',array('class'=>'btn-info btn save_edit_end_btn')); ?>
                                </div>

                            </div>


                        </div>



                    </div>

                    <?php echo $this->Form->end(); ?>
                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>




<script>
    $(document).ready(function(){

        //$("#add_tab_new").addClass('active');


        //$(".admit_date").datetimepicker({format: 'DD/MM/YYYY',defaultDate:new Date()});
        $(".dob, .admit_date").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});


        var upload = false;
        var upload_file =false;
        //$(".channel_tap a").removeClass('active');
        //$("#v_add_channel").addClass('active');
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
        $('.datepicker').datepicker({autoclose:true,format:'yyyy-mm-dd'});


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




        $(document).on('change','#consultDoc',function(e){
            $("#address_holder").html('');
            var docID =$(this).val();
            if(docID){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_doc_address_list",
                    data:{docID:docID},
                    beforeSend:function(){

                    },
                    success:function(data){
                        $("#address_holder").html(data);
                    },
                    error: function(data){
                    }
                });
            }

        });

    });
</script>
<style>

    .error-message{
        position: absolute;
        bottom: -9px;
        font-size: 10px !important;
    }
</style>






