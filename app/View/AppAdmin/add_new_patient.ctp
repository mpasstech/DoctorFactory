<div class="modal fade" id="add_new_patient" role="dialog" >
    <?php
    $login = $this->Session->read('Auth.User');
    $prescriptionFields = $this->AppAdmin->getHospitalPrescriptionFields($login['User']['thinapp_id']);
    $reason_of_appointment_list = $this->AppAdmin->get_reason_of_appointment_list($login['User']['thinapp_id']);
    $country_list =$this->AppAdmin->countryDropdown(true);
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
    $gender =array('MALE'=>'Male','FEMALE'=>'Female');
    $marital_status =array('MARRIED'=>'MARRIED','UNMARRIED'=>'UNMARRIED');
    $relation =array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O');

    ?>
    <style>


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


        #add_new_patient_form div[class^="col-sm"] {
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
            margin-top: -38px;
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


        #add_new_patient .form-control {
            height: 29px;
            padding: 3px 3px;
        }
    </style>

    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'add_new_patient_form')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="text-align: center;font-weight: bold;">Add New Patient</h4>
            </div>
            <div class="modal-body">
                <label class="error_msg_div"></label>
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


                        <div class="col-sm-2">
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
                            <label>Country</label>
                            <?php echo $this->Form->input('country_id',array('type'=>'text','label'=>false,'class'=>'form-control country')); ?>
                        </div>
                        <div class="col-sm-2 state_box">
                            <?php $state_list =array();?>
                            <label>State  </label>
                            <?php echo $this->Form->input('state_id',array('type'=>'text','label'=>false,'class'=>'form-control state')); ?>
                        </div>
                        <div class="col-sm-2 city_box">
                            <?php $city_list =array();?>
                            <label>City </label>
                            <?php echo $this->Form->input('city_id',array('type'=>'text','label'=>false,'class'=>'form-control city')); ?>
                        </div>


                        <div class="col-sm-2 city_name_box">
                            <label>City Name </label>
                            <?php echo $this->Form->input('city_name',array('type'=>'text','label'=>false,'class'=>'form-control city_name')); ?>
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
                                <label>Weight</label>
                                <?php echo $this->Form->input('weight',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"(In KG)",'id'=>'weight','label'=>false,'class'=>'form-control cnt')); ?>
                            </div>
                        <?php }?>

                        <?php if(isset($prescriptionFields['height']) && ($prescriptionFields['height']['status'] == 'ACTIVE')) { ?>
                            <div class="col-sm-1">
                                <label>Height</label>
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


                        <?php if($value['column']=='age'){
                            $dob = @$this->AppAdmin->dateFormat($this->request['data']['AppointmentCustomer']['dob']);
                            $age = @$this->AppAdmin->create_age_array($this->request['data']['AppointmentCustomer']['age']);
                            $year = $age['Year'];
                            $month = $age['Month'];
                            $day = $age['Day'];
                            $age_string = @$this->request['data']['AppointmentCustomer']['age'];

                            ?>

                            <div class="col-sm-2 <?php echo $visible_class; ?>">
                                <label>Age</label><br>
                                <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput year_input" value = "<?php echo $year; ?>" id="ageYear" type="number">
                                <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput month_input" value = "<?php echo $month; ?>" id="ageMonth" type="number">
                                <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput day_input" value = "<?php echo $day; ?>" id="ageDay" type="number">
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
                                <?php echo $this->Form->input($value['column'],array('type'=>'select','empty'=>'Please Select','options'=>$option,'label'=>false,'class'=>'form-control')); ?>
                            </div>

                        <?php  }else {

                            $required =false;
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

                            $class = ($value['column']=='conceive_date' || $value['column']=='expected_date')?'conceive_date_div':'';

                            ?>

                            <?php if($column =="parents_name"){ ?>
                                <div class="col-sm-2 <?php echo $visible_class; ?>">
                                    <label><?php echo $value['label']; ?></label>
                                    <div class="parent_holder">
                                        <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>$relation,'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                                        <?php echo $this->Form->input($column,array('autoComplete'=>'off','type'=>'text',"maxlength"=>"50",'placeholder'=>$value['label'],'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                                    </div>
                                </div>
                            <?php }else{ ?>
                                <div class="col-sm-2 <?php echo $class; ?> <?php echo $visible_class; ?>">
                                    <label><?php echo $value['label']; ?></label>
                                    <?php echo $this->Form->input($column,array('required'=>$required,'autoComplete'=>'off','value'=>@$this->request->data['Children'][$column],'type'=>'text',"maxlength"=>"50",'placeholder'=> $value['label'],'label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            <?php } ?>




                        <?php }}}} ?>



                </div>

            </div>
            <div class="modal-footer">
                <div class="col-sm-12" style="text-align: right;">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="submit" id="addAppointment" class="btn btn-success m_save"><i class="fa fa-save"></i> Save & Book</button>
                </div>

            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <script>
        $(function () {


            function show_loader(obj,flag){
                var html = '<i class="fa fa-spinner fa-spin state_spin"></i>';
                if(flag===true){

                    console.log(obj);
                    $(obj).append(html);
                }else{
                    $(obj).find('i').remove();
                }
            }
            function addMagicsuggest(){

                var country_obj = $("#AppointmentCustomerCountryId, #ChildrenCountryId");
                var state_obj = $("#AppointmentCustomerStateId, #ChildrenStateId");
                var city_obj = $("#AppointmentCustomerCityId, #ChildrenCityId");
                var city_name_obj = $("#AppointmentCustomerCityName, #ChildrenCityName");
                console.log(city_name_obj);


                var country = $(country_obj).magicSuggest({
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
                var state = $(state_obj).magicSuggest({
                    allowFreeEntries:false,
                    allowDuplicates:false,
                    maxDropHeight: 345,
                    maxSelection: 1,
                    required: false,
                    noSuggestionText: '',
                    useTabKey: true,
                    selectFirst :true
                });
                var city = $(city_obj).magicSuggest({
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
                            $.ajax({
                                type:'POST',
                                url: baseurl+"app_admin/get_state_list_json",
                                data:{country_id:country_id},
                                beforeSend:function(){
                                    show_loader(label,true);
                                    $(state_obj,city_obj).attr('disabled',true).html('');
                                },
                                success:function(data){
                                    show_loader(label,false);
                                    state.setData(JSON.parse(data));
                                    state.input.focus();
                                    $(state_obj,city_obj).attr('disabled',false);
                                },
                                error: function(data){
                                }
                            });
                        }
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
                                },
                                error: function(data){
                                }
                            });
                        }
                    }
                });


                $(city).off('selectionchange');
                $(city).on('selectionchange', function(e,m) {
                    var $this = this;
                    var IdArr = this.getSelection();
                    if (IdArr[0]) {
                        var city_name =IdArr[0].name;
                        $(city_name_obj).val(city_name);
                    }else{
                        $(city_name_obj).val('');
                    }
                });
                $(city).off('blur');
                $(city).on('blur', function(c){
                    var IdArr = this.getSelection();
                    if (IdArr[0]) {
                        var city_name =IdArr[0].name;
                        $(city_name_obj).val(city_name);
                    }else{
                        $(city_name_obj).val('');
                    }


                });






            }
            addMagicsuggest();


            function show_loader(obj,flag){
                var html = '<i class="fa fa-spinner fa-spin state_spin"></i>';

                if(flag===true){
                    $(obj).append(html);
                }else{
                    $(obj).find('i').remove();
                }
            }

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


                $("#ChildrenDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});
                $("#AppointmentCustomerDob").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true,endDate: new Date()});

                $("#ChildrenDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});
                $("#AppointmentCustomerDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

                var conceive = $("#AppointmentCustomerConceiveDate").val();
                var expected = $("#AppointmentCustomerExpectedDate").val();
                $("#AppointmentCustomerConceiveDate, #AppointmentCustomerExpectedDate").datepicker({clearBtn: true,format: 'dd/mm/yyyy',autoclose:true});
                $("#AppointmentCustomerConceiveDate, #AppointmentCustomerExpectedDate").mask("99/99/9999", {placeholdclearBtn:'dd/mm/yyyy'});

                $("#AppointmentCustomerConceiveDate").val(conceive);
                $("#AppointmentCustomerExpectedDate").val(expected);
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

            $(document).off("submit","#booking_form");
            $(document).on("submit","#booking_form",function(e){
                e.preventDefault();
                var data = $( this ).serialize();
                var $btn = $("#addAppointment");
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
                        direct_book:false

                    },
                    beforeSend:function(){
                        isSearching = 'YES';
                        $('#addAppointment').button('loading').html('Loading...');
                        $(".app_error_msg").hide();
                        $(".customer_loading").show();
                    },
                    success:function(data){
                        isSearching = 'NO';
                        $('#addAppointment').button('reset');
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
                        $('#addAppointment').button('reset');
                        $(".customer_loading").hide();
                        $(".error_msg_div").html("Sorry something went wrong on server.").fadeIn('slow');
                    }
                });
            });


            $(document).off("reset","#booking_form");
            $(document).on("reset","#booking_form",function(){
                setTimeout(function () {
                    $("#AppointmentCustomerGender").trigger("change");
                },5)

            });


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


        });
    </script>

</div>

