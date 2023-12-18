<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$ward_list =$this->AppAdmin->getHospitalWardList($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown(true);
$reason_of_appointment_list = $this->AppAdmin->get_reason_of_appointment_list($login['User']['thinapp_id']);
$name_title =array(''=>'Select','Mr'=>'Mr','Mrs'=>'Mrs','Miss'=>'Miss','Master'=>'Master','Baby'=>'Baby');

echo $this->Html->script(array('magicsuggest-min.js'));
echo $this->Html->css(array('magicsuggest-min.css'));
?>



<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 admit_form_box">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">

                    <h3 class="screen_title">IPD Patient Registration </h3>
                    <?php //echo $this->element('app_admin_leftsidebar'); ?>
                    <div class="row">
                        <?php echo $this->element('app_admin_inventory_tab_add_ipd');

                        $disable_class = ($patient_type !='CU')?'disable_cls':'';

                        ?>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('HospitalIpd',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'admit_form')); ?>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 patient_detail_section">
                            <h3>Patient Detail</h3>
                            <div class="form-group ">

                                <div class="col-sm-1">
                                    <label>Title</label>
                                    <?php echo $this->Form->input('title',array('value'=>@$this->request->data[$object]['title'],'type'=>'select','options'=>$name_title,'label'=>false,'class'=>'form-control')); ?>
                                </div>

                                <div class="col-sm-3">
                                    <label>Patient Name</label>
                                    <?php echo $this->Form->input('name',array('value'=>$patient_name,'required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter  name.')",'oninput'=>"setCustomValidity('')" ,'type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                </div>


                                <div class="col-sm-2">
                                    <label>UHID</label>
                                    <?php echo $this->Form->input('uhid',array('value'=>$this->request->data[$object]['uhid'],'type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt','readonly'=>'readonly')); ?>
                                </div>


                                <div class="col-sm-2 gender_div">
                                    <label>Gender</label>
                                    <?php echo $this->Form->input('gender',array('value'=>$this->request->data[$object]['gender'],'type'=>'select','label'=>false,'empty'=>'Please Select','options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'class'=>"form-control $disable_class")); ?>
                                </div>

                                <div class="col-sm-2 conceive_date_div" style="display: <?php echo  @($patient_type =='CU' && $this->request->data[$object]['gender'] =='FEMALE')?'block':'none'; ?>;">
                                    <label>Conceive Date</label>
                                    <?php echo $this->Form->input('conceive_date',array('value'=>@$this->request->data[$object]['conceive_date'], 'type'=>'text','placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control')); ?>
                                </div>

                                <div class="col-sm-2 conceive_date_div" style="display: <?php echo @($patient_type =='CU' && $this->request->data[$object]['gender'] =='FEMALE')?'block':'none'; ?>;">
                                    <label>Expected Date</label>

                                    <?php echo $this->Form->input('expected_date',array('value'=>@$this->request->data[$object]['expected_date'],'type'=>'text','placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control')); ?>
                                </div>

                                <div class="col-sm-2">

                                    <label>DOB</label>
                                    <?php echo $this->Form->input('dob',array('autoComplete'=>'off','value'=>$this->AppAdmin->dateFormat($this->request->data[$object]['dob']),'type'=>'text','placeholder'=>'','label'=>false,'class'=>"form-control dob $disable_class")); ?>
                                </div>


                                <?php if($object =="AppointmentCustomer"){ ?>
                                <div class="col-sm-2">
                                    <label>Age</label>
                                    <?php echo $this->Form->input('age',array('type'=>'text','value'=>$this->request->data[$object]['age'],'placeholder'=>'Age','label'=>false,'class'=>'form-control')); ?>
                                </div>
                                <?php } ?>

                                <div class="col-sm-2">
                                    <label>Blood Group</label>
                                    <?php echo $this->Form->input('blood_group',array('value'=>$this->request->data[$object]['blood_group'],'type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>
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

                                        <?php
                                        $typeCU = isset($this->request['data']['AppointmentCustomer'])?'AppointmentCustomer':'Children';
                                        if($this->request['data'][$typeCU]['country_id']){ ?>
                                        msc.setValue(<?php echo json_encode(array($this->request['data'][$typeCU]['country_id']),true); ?>);
                                        <?php } ?>

                                        <?php if($this->request['data'][$typeCU]['country_id']){ ?>
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

                                        <?php if($this->request['data'][$typeCU]['state_id']){ ?>
                                        $.ajax({
                                            type:'POST',
                                            url: baseurl+"app_admin/get_city_list_json",
                                            data:{state_id:<?php echo $this->request['data'][$typeCU]['state_id']; ?>},
                                            beforeSend:function(){
                                            },
                                            success:function(data){
                                                mscity.setData(JSON.parse(data));
                                                <?php if($this->request['data'][$typeCU]['city_id'] && $this->request['data'][$typeCU]['state_id']){ ?>
                                                mscity.setValue(<?php echo json_encode(array($this->request['data'][$typeCU]['city_id']),true); ?>);
                                                <?php }
                                                else if($this->request['data'][$typeCU]['city_name'] != ""){ ?>
                                                mscity.setValue(<?php echo json_encode(array($this->request['data'][$typeCU]['city_name']),true); ?>);
                                                <?php } ?>
                                            },
                                            error: function(data){
                                            }
                                        });
                                        <?php }
                                        else if($this->request['data'][$typeCU]['city_name'] != ""){ ?>
                                        mscity.setValue(<?php echo json_encode(array($this->request['data'][$typeCU]['city_name']),true); ?>);
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
                            </div>

                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                            <h3>Admission Entry Form</h3>
                            <div class="form-group">


                                <div class="col-sm-2">
                                    <label>Consultant</label>

                                    <?php echo $this->Form->input('appointment_staff_id',array('value'=>@$this->request->data['HospitalIpd']['appointment_staff_id'],'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select consultant.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Consultant','type'=>'select','label'=>false,'options'=>$doctor_list,'class'=>'form-control message_type cnt')); ?>
                                </div>

                                <div class="col-sm-3">
                                    <label>Admission Ward/Room &nbsp; <a href="javascript:void(0);" class="get_bed_status">Bed Status </a></label>
                                    <?php echo $this->Form->input('hospital_service_category_id',array('value'=>@$this->request->data['HospitalIpd']['hospital_service_category_id'],'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select ward/room.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Ward/Room','type'=>'select','label'=>false,'options'=>$ward_list,'class'=>'form-control ward_drp')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <label>Select Room/Bed <i class="fa fa-spinner fa-spin service_spin" style="display:none;"></i></label>
                                    <?php $service_list  = $this->AppAdmin->getHospitalServiceList(@$this->request->data['HospitalIpd']['hospital_service_category_id']); ?>
                                    <?php echo $this->Form->input('hospital_service_id',array('value'=>@$this->request->data['HospitalIpd']['hospital_service_id'],'required'=>'required',/*'oninvalid'=>"this.setCustomValidity('Please select room/bed.')",'oninput'=>"setCustomValidity('')" ,*/'empty'=>'Select Ward','type'=>'select','label'=>false,'options'=>$service_list,'class'=>'form-control service_drp')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <label>Admit Date</label>
                                    <?php echo $this->Form->input('admit_date',array('value'=>@$this->AppAdmin->dateFormat($this->request->data['HospitalIpd']['admit_date'],true),'required'=>'required','value'=>date('d/m/Y h:i A'),'type'=>'text','label'=>false,'class'=>'form-control admit_date')); ?>
                                </div>



                                <div class="col-sm-3">
                                    <label>Reason for Appointment</label>
                                    <?php echo $this->Form->input('reason_for_appointment',array('value'=>@$this->request->data['HospitalIpd']['reason_for_appointment'],"list"=>"reason_of_appointment",'type'=>'text','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('appointment_address_id', array('value'=>@$this->request->data['HospitalIpd']['appointment_address_id'],'type' => 'select','empty'=>'Select Address','options'=>$this->AppAdmin->get_app_address($login['User']['thinapp_id']),'label' => 'Select Address', 'class' => 'form-control','required'=>'required')); ?>
                                </div>

                                <div class="col-sm-3">
                                    <label>Remark</label>
                                    <?php echo $this->Form->input('remark',array('value'=>@$this->request->data['HospitalIpd']['remark'],'type'=>'text','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>




                                <div class="col-sm-3">
                                    <label>Referred By (Name)</label>
                                    <?php echo $this->Form->input('referred_by_name',array('value'=>@$this->request->data['HospitalIpd']['referred_by_name'],'type'=>'text','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Referred By (Mobile)</label>
                                    <?php echo $this->Form->input('referred_by_mobile',array('value'=>@$this->request->data['HospitalIpd']['referred_by_mobile'],'type'=>'text',"onkeyup"=>"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')","maxlength"=>"10","minlength"=>"10",'label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-6">

                                    <label>Chief Complaint</label>
                                    <?php echo $this->Form->input('chief_complaint',array('value'=>@$this->request->data['HospitalIpd']['chief_complaint'],'type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-6">

                                    <label>Medical History</label>
                                    <?php echo $this->Form->input('medical_history',array('name'=>"data[AppointmentCustomer][medical_history]",'type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                            </div>



                            <div class="form-group">


                                <div class="col-sm-12 button_align">

                                    <label>&nbsp;</label>
                                    <button type="button" onclick="window.history.back();" class="btn btn-info">Back</button>
                                    <?php echo $this->Form->button('Reset',array('type'=>'reset','class'=>'btn-warning btn')); ?>
                                    <button type="submit" id="btnSubmit" class="btn btn-success">Save</button>


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
<datalist id="reason_of_appointment">
    <?php foreach($reason_of_appointment_list AS $list){ ?>
    <option value="<?php echo $list[0]; ?>">
        <?php } ?>
</datalist>
<style>
    .submit{display: contents;}
</style>


<script>
    $(document).ready(function(){


        $(".admit_date").datetimepicker({format: 'DD/MM/YYYY hh:mm A',defaultDate:new Date()});
        $(".dob").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});
        $("#HospitalIpdConceiveDate, #HospitalIpdExpectedDate").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});

        $(document).on("change","#HospitalIpdGender",function(){
            $("#HospitalIpdConceiveDate, #HospitalIpdExpectedDate").val('');
            if($(this).val()=='FEMALE'){
                $(".conceive_date_div").show();
            }else{
                $(".conceive_date_div").hide();
            }
        });

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


       var dob = $(".d" +
           "" +
           "ob").val();
        if(dob =='0000-00-00'){
            $(".dob").val('');
        }
        $('.datepicker').datepicker({autoclose:true,format:'yyyy-mm-dd'});

        $(document).on('click','.edit_img_icon',function(e){
            $(".hidden_file_browse").trigger("click");
        });




        $("#admit_form").submit(function (e) {

            $("#btnSubmit").attr("disabled", true);
            return true;

        });

        $(document).on('change','.hidden_file_browse',function(e){
            if($(this).val()){
                $(".upload_media").show();
                readURL(this);
            }else{
                $(".upload_media").hide();
            }
        });


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

        /*$(document).on('change','.country',function(e){
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
        } */





        $(document).on('change','.ward_drp',function(e){
            var ward_id =$(this).val();
            if(ward_id){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_hospital_service_list",
                    data:{ward_id:ward_id},
                    beforeSend:function(){
                        $('.service_spin').show();
                        $('.service_drp').attr('disabled',true).html('');
                    },
                    success:function(data){
                        $('.service_spin').hide();
                        $('.service_drp').attr('disabled',false).html(data);
                    },
                    error: function(data){
                    }
                });
            }

        });


        $(".disable_cls").each(function () {
            var val = $(this).val();
            $(this).replaceWith("<span type='text' class='form-control' readonly='readonly'>"+val+"</span>");
        });
    });
</script>


<style>
    .ms-ctn input {

        margin: -4px 0;

    }
    .ms-ctn .ms-sel-ctn {

        margin-left: -2px;

    }
    .error-message{
        position: absolute;
        bottom: -9px;
        font-size: 10px !important;
    }
    div[class^='col-sm-']{
        padding: 0px 3px !important;
    }
    div[class^='col-sm-'] input,
    div[class^='col-sm-'] .form-control,
    div[class^='col-sm-'] select
    {
        height: 30px !important;
        padding: 4px 4px !important;
    }
    textarea{
        min-height: 70px !important;
    }
</style>



