<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$ward_list =$this->AppAdmin->getHospitalWardList($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown(true);
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

                    <h3 class="screen_title">Patient Detail</h3>
                    <?php //echo $this->element('app_admin_leftsidebar'); ?>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box">

                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('HospitalEmergency',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 patient_detail_section">
                            <h3>Patient Detail</h3>
                            <div class="form-group ">

                                <div class="col-sm-2">
                                    <label>Patient Name</label>
                                    <?php echo $this->Form->input('name',array('value'=>$patient_name,'required'=>'required','oninvalid'=>"this.setCustomValidity('Please enter  name.')",'oninput'=>"setCustomValidity('')" ,'type'=>'text','placeholder'=>'','label'=>false,'id'=>'mobile','class'=>'form-control')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <label>UHID</label>
                                    <?php echo $this->Form->input('uhid',array('value'=>$this->request->data[$object]['uhid'],'type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt','readonly'=>'readonly')); ?>
                                </div>

                                <div class="col-sm-1">
                                    <label>Gender</label>
                                    <?php echo $this->Form->input('gender',array('value'=>$this->request->data[$object]['gender'],'type'=>'select','label'=>false,'options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'class'=>'form-control','readonly'=>($patient_type !='CU')?'readonly':'')); ?>
                                </div>
                                <div class="col-sm-2">

                                    <label>DOB</label>
                                    <?php echo $this->Form->input('dob',array('value'=>$this->request->data[$object]['dob'],'type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control dob','readonly'=>($patient_type !='CU')?'readonly':'')); ?>
                                </div>
                                <div class="col-sm-2">

                                    <label>Marital Status</label>
                                    <?php echo $this->Form->input('marital_status',array('name'=>"data[AppointmentCustomer][marital_status]",'type'=>'select',"options"=>array("MARRIED"=>"Married","UNMARRIED"=>"Unmarried"),'label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <?php if($object !='CHILDREN'){ ?>
                                    <div class="col-sm-1">

                                        <label>Blood Group</label>
                                        <?php echo $this->Form->input('blood_group',array('name'=>"data[AppointmentCustomer][blood_group]",'type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Age</label>
                                        <?php echo $this->Form->input('age',array('type'=>'text','value'=>$this->request->data[$object]['age'],'placeholder'=>'Age','label'=>false,'class'=>'form-control')); ?>
                                    </div>
                                <?php }else{ ?>
                                    <div class="col-sm-2">

                                        <label>Blood Group</label>
                                        <?php echo $this->Form->input('blood_group',array('name'=>"data[AppointmentCustomer][blood_group]",'type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>
                                    </div>
                                <?php } ?>


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


                            <h3>Emergency Entry Form</h3>
                            <div class="form-group">


                                <div class="col-sm-3">
                                    <label>Consultant</label>

                                    <?php echo $this->Form->input('',array('name'=>"data[HospitalEmergency][appointment_staff_id]",'value'=>@$this->request->data['HospitalEmergency']['appointment_staff_id'],'required'=>'required','oninvalid'=>"this.setCustomValidity('Please select consultant.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Consultant','type'=>'select','label'=>false,'options'=>$doctor_list,'class'=>'form-control message_type cnt')); ?>
                                </div>


                                <div class="col-sm-3">
                                    <label>Admission Ward/Room</label>
                                    <?php echo $this->Form->input('',array('value'=>@$this->request->data['HospitalEmergency']['hospital_service_category_id'],'name'=>"data[HospitalIpd][hospital_service_category_id]",'oninvalid'=>"this.setCustomValidity('Please select ward/room.')",'oninput'=>"setCustomValidity('')" ,'empty'=>array('0'=>'Select Ward/Room'),'type'=>'select','label'=>false,'options'=>$ward_list,'class'=>'form-control ward_drp cnt')); ?>
                                </div>


                                <div class="col-sm-3">
                                    <label>Select Room/Bad <i class="fa fa-spinner fa-spin service_spin" style="display:none;"></i></label>
                                    <?php $service_list  = $this->AppAdmin->getHospitalServiceList(@$this->request->data['HospitalEmergency']['hospital_service_category_id']); ?>
                                    <?php echo $this->Form->input('',array('value'=>@$this->request->data['HospitalEmergency']['hospital_service_id'],'name'=>"data[HospitalEmergency][hospital_service_id]",'oninvalid'=>"this.setCustomValidity('Please select ward.')",'oninput'=>"setCustomValidity('')" ,'empty'=>array('0'=>'Select Room/Bad'),'type'=>'select','label'=>false,'options'=>$service_list,'class'=>'form-control service_drp')); ?>
                                </div>





                                <div class="col-sm-3">
                                    <label>Admit Date</label>
                                    <?php echo $this->Form->input('',array('name'=>"data[HospitalEmergency][admit_date]",'value'=>@$this->request->data['HospitalEmergency']['admit_date'],'required'=>'required','type'=>'text','label'=>false,'class'=>'form-control admit_date')); ?>
                                </div>



                                <div class="col-sm-12">
                                    <label>Remark</label>
                                    <?php echo $this->Form->input('',array('name'=>"data[HospitalEmergency][remark]",'value'=>@$this->request->data['HospitalEmergency']['remark'],'type'=>'text','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>





                            <div class="form-group">

                                <div class="col-sm-6">

                                    <label>Chief Complaint</label>
                                    <?php echo $this->Form->input('chief_complaint',array('name'=>"data[HospitalEmergency][chief_complaint]",'value'=>@$this->request->data['HospitalEmergency']['appointment_staff_id'],'type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Referred By (Name)</label>
                                    <?php echo $this->Form->input('referred_by_name',array('value'=>@$this->request->data['HospitalEmergency']['referred_by_name'],'type'=>'text','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>
                                <div class="col-sm-3">
                                    <label>Referred By (Mobile)</label>
                                    <?php echo $this->Form->input('referred_by_mobile',array('value'=>@$this->request->data['HospitalEmergency']['referred_by_mobile'],'type'=>'text',"onkeyup"=>"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')","maxlength"=>"10","minlength"=>"10",'label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-3">

                                    <label>&nbsp;</label>
                                    <?php echo $this->Form->submit('Save',array('class'=>'btn-info btn','id'=>'signup','style'=>'width:100%;')); ?>
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


        $(".admit_date").datetimepicker({format: 'DD/MM/YYYY hh:mm A',defaultDate:new Date()});
        $(".dob").datepicker({format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto",endDate: new Date()});

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
        $('.datepicker').datepicker({autoclose:true,format:'yyyy-mm-dd'});

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
        }*/


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
    });
</script>






