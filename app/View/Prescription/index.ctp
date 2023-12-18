<!DOCTYPE html>



<html>
<head class="row_content">
    <title>Prescription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';
        /* THIS MASTER CATEGORY ID VARIABLE USED INTO ADVANCE PRESCRTIPITON.JS FILE  */
        var medicine_master_category_id = '<?php echo MEDICINE_MASTER_CATEGORY_ID; ?>';
        var append ="";
        var step_object = new Object();
    </script>
    <?php

    $login = $this->Session->read('Auth.User');

    echo $this->Html->css(array('bootstrap.min.css','font-awesome.min.css','prescription.bundle.min.css','web_prescription.css?'.date('Ymd'),'web_prescription_taginput.css','easy-autocomplete.min.css','bootstrap-datepicker.min.css','jquery-confirm.min.css'),array("media"=>'all','fullBase' => true));
    echo $this->Html->script(array('prescription.bundle.min.js','bootstrap-popup-confirmation.min.js','web_prescription_taginput.js','html2canvas.js','canvas2image.js','printThis.js','flash-alert.js','jquery.easy-autocomplete.min.js','jquery.masked-input.min.js','bootstrap-datepicker.min.js','advance_prescription.js','custom_confirm.js','dom-to-image.js'),array('fullBase' => true));


    ?>


    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>

    <style>






        .modal { overflow: auto !important; }
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 43px;
            height: 20px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 15px;
            width: 15px;
            left: 1px;
            bottom: 3px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #2196F3;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }


        body{
            overflow: hidden;
            font-family: Montserrat !important;
        }
        #history_frame{
            padding: 0px;
            border: none;
            height: 300px;
            overflow-x: scroll;
            width: 100%;
        }

        .panel-heading tr th{
            /* width: 20%;*/
            font-size: 10px;
            font-weight: 500;
            text-align: left;
        }
        .head_lbl{
            /* width: 11% !important;*/
        }

        .toggle_btn_div{
            width: 30%;
            float: left;
        }

        .toggle_btn_div label{
            font-size: 10px;
            padding: 3px 2px;
        }


        /* advance prescripiton css start here*/


        #menu_button li{
            width: 49%;
            float: left;
            list-style: none;
            border: 1px solid #f4efef;
            margin: 1px;
            padding: 8px 1px;
            background: transparent;
            cursor: pointer;
        }

        #menu_button li:hover{
            background: #0a4fe9b5;
            color: #fff;
        }
        #menu_button .icon_span, #menu_button .label_text{
            width: 100%;
            display: block;
            text-align: center;
            float: left;
            text-transform: uppercase;

        }

        #menu_button .label_text{
        font-size: 12px;
        }

        #menu_button .icon_span{
            font-size: 24px;
        }

        .add_new_appointment_btn{
            margin: 2px 0px;
        }
        .top_loader_img{
            position: relative;
            z-index: 9999;
            margin-top: -33px;
            margin-right: 4px;
            float: right;
            border-radius: 20px;
            color: #ffffff;
            font-size: 20px;
        }
    </style>


</head>
<body>
<div class="main_container">


    <?php
    if(empty($doctor['profile_photo'])){
        $path =Router::url('/images/channel-icon.png',true);
    }else{
        $path =$doctor['profile_photo'];
        $path =Router::url('/images/channel-icon.png',true);
    }

    $address_list = $this->AppAdmin->custom_dropdown_list($doctor['id'],$doctor['thinapp_id'],'ADDRESS');
    //$address_list = $this->AppAdmin->get_app_address($doctor['thinapp_id']);
    $service_list = $this->AppAdmin->custom_dropdown_list($doctor['id'],$doctor['thinapp_id'],'SERVICE');



    ?>

    <div class="row doctor_header" style="display:none;">
        <ul>

            <li class="doc_name">
                <div class="doc_logo">
                    <img src="<?php echo $path; ?>">
                </div>
                <label><?php echo $doctor['name']; ?></label>


            </li>
            <li class="doc_time"><label>




                </label></li>
        </ul>

    </div>

    <div class="row" id="main_window">
        <div class="column patient_list column_1">
            <h3 class="column_1_heading">OPD List</h3>
            <i style="display: block;" class="fa fa-circle-o-notch fa-spin top_loader_img"></i>

            <div class="card">
                <div class="left-menu-content">
                    <div class="top_option" style="display: none;">
                        <a class="btn btn-warning add_new_appointment_btn btn-xs" style="width: 100%; margin: 2px auto;"  href="javascript:void(0);"><i class="fa fa-plus"></i> Book New Appointment</a>

                        <?php echo $this->Form->input('top_address_drp',array('type'=>'select','div'=>false,'label'=>false,'options'=>$address_list,'class'=>'top_drp top_address_drp')); ?>
                        <?php echo $this->Form->input('top_service_drp',array('style'=>'width:50%;float:left;','type'=>'select','div'=>false,'label'=>false,'options'=>$service_list,'class'=>'top_drp top_service_drp')); ?>
                        <div class="input-group date" style="width:50%;float:left;height: 35px;">
                            <input type="text" class="top_date">
                            <div class="input-group-addon" style="border-radius: 0px;font-size: 18px;">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </div>
                        </div>
                    </div>

                    <div class="search_div">
                        <div class="input-group" style="height: 34px;">
                            <input type="text" class="form-control search_box text_box" placeholder="Search...">
                            <span class="input-group-addon search_icon">
                            <i class="fa fa-search"></i>
                        </span>
                        </div>
                    </div>
                    <ul class="patient_list_ul right_chat list-unstyled list_ul">

                    </ul>
                </div>
            </div>
        </div>
        <div class="column column_2">
            <div id="dashboard_box">
                <h3 class="column_2_heading">Summary

                    <div class="search_date_div" style="float: right;">
                        <input class="date_from" type="text" placeholder="--/--/----" value="<?php echo date('d/m/Y');?>">
                        <span>To</span>
                        <input class="date_to" type="text" placeholder="--/--/----" value="<?php echo date('d/m/Y');?>">
                    </div>
                </h3>
                <div class="recode_box" >

                    <div class="patient_visit_box">
                        <ul>
                            <li>
                                <div class="li_inner">
                                    <img src="<?php echo Router::url('/web_prescription/total_patients.png',true);?>">
                                    <label id="total_patient_lbl"><?php echo $total_records['total_patient']; ?></label>
                                    Total Patients
                                    <span class="display_bar" >
                                        <span style="background: #4EAC4C;"></span>
                                    </span>
                                </div>
                            </li>
                            <li>
                                <div class="li_inner">
                                    <img src="<?php echo Router::url('/web_prescription/total_visits.png',true);?>">
                                    <label id="total_visit"><?php echo $total_records['total_visits']; ?></label>
                                    Total Visits
                                    <span class="display_bar" >
                                        <span style="background: #3E9EC8;"></span>
                                    </span>


                                </div>
                            </li>
                            <li>
                                <img src="<?php echo Router::url('/web_prescription/total_charged.png',true);?>">
                                <label id="total_charged"><?php echo '---'//$total_records['total_charged']; ?> /-</label>
                                Total Charged
                                <span class="display_bar" >
                                        <span style="background: #DA544B;"></span>
                                    </span>


                            </li>

                            <li class="second_part_li">

                                <div class="li_inner">
                                    <img src="<?php echo Router::url('/web_prescription/total_received.png',true);?>">
                                    <label id="total_received"><?php echo $total_records['total_received']; ?> /-</label>
                                    Total Received
                                    <span class="display_bar" >
                                        <span style="background: #047D9A;"></span>
                                    </span>
                                </div>
                            </li>

                            <li class="second_part_li">

                                <div class="li_inner">
                                    <img src="<?php echo Router::url('/web_prescription/current_payment.png',true);?>">
                                    <label id="total_due"><?php echo $total_records['total_due']; ?> /-</label>
                                    Current Outstanding Payment
                                    <span class="display_bar" >
                                        <span style="background: #E5A043;"></span>
                                    </span>
                                </div>
                            </li>


                            
                        </ul>
                    </div>




                </div>
                <div class="graph_box">
                    <?php if(!empty($statsUrl)){ foreach ($statsUrl as $key => $urls){ ?>
                        <iframe scrolling="no" style="border:1px solid #e1e0e0; overflow:hidden; float:left;" width="33.33%;"  src="<?php echo $urls; ?>"></iframe>
                    <?php  }} ?>
                </div>
            </div>
            <div id="patient_detail_box"></div>
        </div>
        <div class="column column_3">
            <h3 class="column_3_heading">Quick Access Bar</h3>
            <div class="panel-group" id="menu_button">
                <li id="dashboard_btn"><span class="icon_span"><i class="fa fa-tachometer"></i></span><span class="label_text" >Dashboard</span></li>
                <li id = "address_list_btn"><span class="icon_span"><i class="fa fa-map-marker"></i></span><span class="label_text">Address</span></li>
                <li id = "doctor_list_btn"><span class="icon_span"><i class="fa fa-<?php echo ($login['USER_ROLE']=='ADMIN')?'users':'edit'; ?>"></i></span><span class="label_text"><?php echo ($login['USER_ROLE']=='ADMIN')?'Doctors':'Edit Profile'; ?></span></li>
                <li id="add_patient"><span class="icon_span"><i class="fa fa-user"></i></span><span class="label_text"  data-type="CUSTOMER">Add Patient</span></li>
                <li id = "sms_template_list_btn"><span class="icon_span"><i class="fa fa-envelope"></i></span><span class="label_text"  data-type="CHILDREN">SMS Template</span></li>
                <li id = "service_list_btn"><span class="icon_span"><i class="fa fa-server"></i></span><span class="label_text">Service List</span></li>
                <li id = "follow_up_list_btn"><span class="icon_span"><i class="fa fa-bell"></i></span><span class="label_text">Follow Up</span></li>

                <li id = "appointment_list_btn" data-ty="APPOINTMENT"><span class="icon_span"><i class="fa fa-calendar"></i></span><span class="label_text">Appointment</span></li>
                <li id = "children_list_btn" data-ty="CHILDREN"><span class="icon_span"><i class="fa fa-child"></i></span><span class="label_text">Children List</span></li>
                <li id = "patient_list_btn" data-ty="CUSTOMER"><span class="icon_span"><i class="fa fa-user"></i></span><span class="label_text">Patient List</span></li>
                <a style="padding: 10px 2px;" target="_blank" href="<?php echo Router::url('/app_admin/setting_print_prescription/web',true); ?>" class="label_text"><i class="fa fa-gear"></i> Prescription Setting</a>

            </div>
        </div>
        <div class="footer_bar"><span class="span_left"></span> Help Desk Number : +917412991122 10:00 AM To 07:00 PM  <span class="span_right"><a href="https://www.mengage.in" target="_blank">Powered By MEngage</a></span></div>
    </div>


    <div class="row" id="prescription_window" style="display: none;" >

    </div>


</div>




<div class="modal fade" id="addPatientModal" tabindex="-1" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-md">
        <button type="button" class="close" data-dismiss="modal" style="padding: 10px;">&times;</button>
        <ul class="nav nav-tabs patient_dialog_tab" >
            <li class="active"><a data-toggle="tab" href="#tab_add_patient">Add Patient</a></li>
            <li><a data-toggle="tab" href="#tab_add_children">Add Child</a></li>

        </ul>


        <div class="tab-content">
            <div id="tab_add_patient" class="tab-pane fade in active">
                <form id="add_patient_form">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <input type="hidden"  name="patient_type" class="form-control">
                                    </div>
                                    <div class="col-sm-12">
                                        <label style="width: 100%;">Patient Name </label>
                                        <input type="text"  name="patient_name" maxlength="20" minlength="3" oninvalid = 'this.setCustomValidity("Please enter  patient name")' oninput = 'setCustomValidity("")' required="required" class="form-control">
                                    </div>
                                    <div class="col-sm-12">
                                        <label style="width: 100%;">Patient Mobile </label>
                                        <input type="text"  data-masked-input="9999999999"  maxlength="10" minlength="10" name="patient_mobile" class="form-control" oninvalid = 'this.setCustomValidity("Please enter  10 digit mobile number")' oninput = 'setCustomValidity("")' required="required">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Age</label>
                                        <input type="text" name="age" data-masked-input="999"  maxlength="3" minlength="1"  class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>DOB</label>
                                        <input type="text" name="dob" data-masked-input="99-99-9999" placeholder="DD-MM-YYYY"  maxlength="10" class="form-control" oninvalid = 'this.setCustomValidity("Please enter  patient DOB")' oninput = 'setCustomValidity("")' >
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                            <button type="submit" class="btn btn-success save_patient_btn"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="tab_add_children" class="tab-pane fade">
                <form id="add_child_form">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <label style="width: 100%;">Patient Name </label>
                                        <input type="text"  name="patient_name" maxlength="20" minlength="3" oninvalid = 'this.setCustomValidity("Please enter  patient name")' oninput = 'setCustomValidity("")' required="required" class="form-control">
                                    </div>
                                    <div class="col-sm-12">
                                        <label style="width: 100%;">Patient Mobile </label>
                                        <input type="text"  data-masked-input="9999999999"  maxlength="10" minlength="10" name="patient_mobile" class="form-control" oninvalid = 'this.setCustomValidity("Please enter  10 digit mobile number")' oninput = 'setCustomValidity("")' required="required">
                                    </div>
                                    <!--    <div class="col-sm-3">
                                            <label>Age</label>
                                            <input type="text" name="age" data-masked-input="999"  maxlength="3" minlength="1"  class="form-control">
                                        </div>-->
                                    <div class="col-sm-6">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="MALE">Male</option>
                                            <option value="FEMALE">Female</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>DOB</label>
                                        <input type="text" name="dob" data-masked-input="99-99-9999" placeholder="DD-MM-YYYY"  maxlength="10" class="form-control" oninvalid = 'this.setCustomValidity("Please enter  patient DOB")' oninput = 'setCustomValidity("")' required="required">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                            <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                            <button type="submit" class="btn btn-success save_patient_btn"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>


<div class="modal fade" id="serviceManageModal" role="dialog" tabindex="-1" style="z-index: 99999;">
    <div class="modal-dialog modal-sm">
        <form id="service_manage_form">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-number="2">&times;</button>
                    <h4 class="modal-title manage_service_header"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label style="width: 100%;" class="label_name"></label>
                                <input type="text"  name="name" maxlength="50" minlength="3" oninvalid = 'this.setCustomValidity("This field can not be empty")' oninput = 'setCustomValidity("")' required="required" class="form-control">
                            </div>
                            <div class="col-sm-12 price_box_div">
                                <label style="width: 100%;" >Price</label>
                                <input type="number" name="price" min="0.00"  step="any"  class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-xs" data-number="2"><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" style="display: none;" class="btn btn-warning btn-xs" id="serviceModalResetButton"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="submit" class="btn btn-success btn-xs save_service_btn"><i class="fa fa-save"></i> Save</button>
                </div>
            </div>
        </form>
    </div>



</div>



<div id="patient_detail_object" style="display: none;"></div>






</body>

<style type="text/css">








    #menu_button .active{
        background: #0a4fe9b5;
        color: #fff;
    }

    .top_option{
        float: left;
        display: block;
    }



</style>

<script type="text/javascript">
    $(function () {

        $(document).on('click','[data-number="2"]',function (e) {
            $("#serviceModalResetButton").trigger('click');
            $("#serviceManageModal").modal('hide');
        });

        $(document).on('click','.add_new_appointment_btn',function (e) {
            var $btn =$(this);
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/add_new_appointment",
                beforeSend:function(){
                    $($btn).button({loadingText: '<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i> Book New Appointment'}).button('loading');

                },
                success:function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#addNewAppointment');
                    $(html).modal({backdrop: 'static', keyboard: false});
                },
                error: function(){
                    $btn.button('reset');
                    flash('Service',"Sorry something went wrong on server.",'left','danger');
                }
            });
        });





        $(document).on('click','.btnNext',function () {
            $('.column_2 .nav-tabs > .active').next('li').find('a').trigger('click');
            var inter = setInterval(function(){
                $('.column_2 .tab-pane.active input[id^="tag-input-"]:first').focus();
                clearInterval(inter);
            },150);
        });

        $(document).on('click','.btnPrev',function () {

            $('.column_2 .nav-tabs > .active').prev('li').find('a').trigger('click');
            var inter = setInterval(function(){
                $('.column_2 .tab-pane.active input[id^="tag-input-"]:last').focus();
                clearInterval(inter);
            },150);

        });


        $('#setp_tab a:first').tab('show');

        $('.column_3 .tag_container').append(append);

        $(document).on('click','.search_btn',function () {
            $(".search_div").slideToggle(300);
            $(".search_box").focus();
        });


        $(document).on('change','#print_with_save',function () {
            console.log($(this).val());
        });





        /* add tag input to text box*/

        function createTagObject(step_id,tag_json_string){

            var tages = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('tag_name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                local:JSON.parse(tag_json_string),
                identify: function(obj) { return obj.id; }
            });
            tages.initialize();
            var elt = $(step_id);
            elt.tagsinput({
                itemValue: 'id',
                itemText: 'tag_name',
                confirmKeys: [13, 188],
                typeaheadjs: {
                    name: 'tages',
                    displayKey: 'tag_name',
                    source: tages.ttAdapter()


                },
                trimValue: true
            });

        }


        var global_offset =0;
        var loading_list = false;
        function load_patient_list(offset,search_request=false,request_for="APPOINTMENT"){
            if(loading_list===false){
                $(".patient_list_ul").attr('data-rf',request_for);
                patient={};
                $(".list_not_found").remove();
                var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
                var address_id = $(".top_address_drp").val();
                var service_id = $(".top_service_drp").val();
                var date = $(".top_date").val();
                var search = $('.search_box').val();
                var last_icon = $('.search_div .search_icon').html();
                var loader =  '<div class="list_loader" ><img src="<?php echo Router::url('/img/ajaxloader.gif',true); ?>"></div>';
                var currentRequest  = $.ajax({
                    type:'POST',
                    url: baseUrl+"prescription/patient_list",
                    data:{request_for:request_for,date:date,ai:address_id,si:service_id,di:doctor_id,s:search,o:global_offset},
                    beforeSend:function(){
                        if(global_offset==0){
                            $(".top_loader_img").show();
                        }
                        if(currentRequest != null) {
                            currentRequest.abort();
                        }
                        loading_list = true;
                        $('.patient_list_ul').append(loader);

                    },
                    success:function(data){
                        $(".top_loader_img").hide();

                        listColumnHeight();


                        if(request_for=="APPOINTMENT"){
                            $(".top_option").slideDown(50);
                        }else{
                            $(".top_option").slideUp(50);
                        }
                        if(search != '') {
                            $('.search_div .search_icon').html(last_icon);
                        }
                        $(".list_loader").remove();

                        if(global_offset==0){

                            $('.patient_list_ul').html(data).scrollTop( 0 );
                        }else{
                            $('.patient_list_ul').append(data);
                        }

                        setTimeout(function () {
                            if(global_offset==0){


                                if($('.patient_list_li').length == 0 && $("#top_address_drp").val()!='' && $("#top_service_drp").val() !=''){
                                    $(".add_patient_btn").trigger('click');
                                }

                            }
                            global_offset++;
                            loading_list = false;
                        },300);


                    },
                    error: function(data){
                        $(".top_loader_img").hide();
                        $($btn).button('reset');
                        alert("Sorry something went wrong on server.");
                    }
                });
            }
        }


        $(document).on('keypress','.search_box',function (e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if(keycode==13){
                global_offset = 0;
                load_patient_list(0,true, $(".patient_list_ul").attr('data-rf'));
            }
        });

        $(document).on('click','.search_icon',function (e) {
            global_offset = 0;
            load_patient_list(0,true, $(".patient_list_ul").attr('data-rf'));
        });

        var lastScrollTop = 0;
        $('.patient_list_ul').scroll(function(e){
            var scrollAmount = $(this).scrollTop();
            if(scrollAmount > lastScrollTop){
                var documentHeight = $(this).height();
                var scrollPercent = (scrollAmount / documentHeight) * 100;
                if(scrollPercent > 15) {
                    load_patient_list(global_offset,false, $(".patient_list_ul").attr('data-rf'));
                }
            }
            lastScrollTop = scrollAmount;
        });






        var patient;
        $(document).on('click','.patient_list_li a',function (e) {

            $("#tag-input-0").focus();
            $(".patient_list_li a").removeClass('active_li');
            $(".patient_list_li").removeClass('selected_patient');

            $(this).closest(".patient_list_li").addClass('selected_patient');
            $(this).addClass('active_li');


            $("#expend_menu").show();
            $(this).find('.append_menu').hide().html($("#expend_menu")).slideDown(400);

            $('.action_div').hide();

            if ($(this).closest('li').find('.action_div button').length > 0){
                $(this).closest('li').find('.action_div').show().css('display','block');
            }

            var patient_type = $(this).attr('data-pt');
            var patient_id = $(this).attr('data-pi');
            var patient_id = $(this).attr('data-pi');
            var appointment_id = $(this).attr('data-ai');
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/patient_detail",
                data:{pi:patient_id,pt:patient_type,ai:appointment_id},
                beforeSend:function(){

                },
                success:function(result){
                    $("#patient_detail_box").html(result).show();
                    $("#dashboard_box").hide();
                },
                error: function(){


                }
            });

            bind_confirm();

        });


        $(document).on('keyup','.dob_box',function (e) {
            if($(this).val().match('\d{1,2}/\d{1,2}/\d{4}')){
                return ;
            }else{

            }

        });



        function listColumnHeight(){
            var column_1_heading = parseInt($(".column_1_heading").outerHeight());
            var footer = parseInt($( ".footer_bar" ).outerHeight());
            var column_1 = (parseInt($('.column_1').outerHeight())-footer)+10;
            var search_div = parseInt($(".search_div").outerHeight());
            if($('.list_ul').attr('data-rf')=='APPOINTMENT'){
                var top_option = parseInt($(".top_option").outerHeight());
                $('.list_ul').height(parseInt(column_1)-(top_option+search_div+column_1_heading));
            }else{
                $('.list_ul').height(parseInt(column_1)-(search_div+column_1_heading));
            }
        }

        function setHeight(){
            var height = $( window ).height();
            var footer = parseInt($( ".footer_bar" ).outerHeight());
            $('.column').height(parseInt(height)-footer);
            listColumnHeight();
            var column_height = parseInt($(".column_2").outerHeight());
            var column_2_heading = parseInt($(".column_2_heading").outerHeight());
            var recode_box = parseInt($(".recode_box").outerHeight());

            setTimeout(function () {
                var iframe_height = ((column_height-(column_2_heading+recode_box))/2)-3;
                $(".graph_box iframe").height(iframe_height+"px");
            },10);


            $(".span_left").width($(".column_1").width());
            $(".span_right").width($(".column_3").width());
        }

        $(window).on('resize', function(){
            setHeight();
        });

        setHeight();

        setTimeout(function(){$(".column").fadeIn(200);},100);

        $(".column").hide();








        $('input').attr('autocomplte',"off");







        function setACookie(cookie_name,cookie_value,exdays=365){
            cookie_value = JSON.stringify(cookie_value);
            var d = new Date();
            d.setTime(d.getTime() + (exdays*1000*60*60*24));
            var expires = "expires=" + d.toGMTString();
            window.document.cookie = cookie_name+"="+cookie_value+"; "+expires;
        }

        function readCookie(cookie_name) {
            var data=getCookie(cookie_name);
            if (data != "") {
                return JSON.parse(data);
            } else {
                return false;
            }
        }
        function getCookie(cname) {
            var name = cname + "=";
            var cArr = window.document.cookie.split(';');
            for(var i=0; i<cArr.length; i++) {
                var c = cArr[i].trim();
                if (c.indexOf(name) == 0)
                    return c.substring(name.length, c.length);
            }
            return "";
        }

        $(document).on("change", ".top_drp", function () {
            updateCookieData();
            global_offset = 0;
            load_patient_list(0);
        });


        function updateCookieData(){
            var setting_obj = new Object();
            setting_obj['address_id'] = $(".top_address_drp").val();
            setting_obj['service_id'] = $(".top_service_drp").val();
            setACookie('doctor_setting',setting_obj);
        }


        $('#addPatientModal').on('hidden.bs.modal', function () {
            $('#booking_frm').trigger('reset');
        })


        $(document).on('confirmed.bs.confirmation','.close_btn', function(event) {
            var $btn = $(this);
            var append_div = $(this).closest('li').find('.appointment_status');
            var action_div = $(this).closest('li').find('.action_div');
            var icon_data = JSON.parse(atob($(this).closest('li').find('.appointment_status').attr('data-icon')));
            var address_id = btoa($(".top_address_drp").val());
            var service_id = btoa($(".top_service_drp").val());
            var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
            var date = $(".top_date").val();
            var id = $(this).attr('data-ai');
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/close_appointment",
                data:{date:date,di:doctor_id,si:service_id,ai:address_id,id:id},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');

                },
                success:function(data){

                    $btn.button('reset');
                    data = JSON.parse(data);
                    if(data.status==1){

                        var index = $( ".selected_patient" ).index();
                        $( ".selected_patient").replaceWith( data.data.li );
                        $( ".patient_list_li:eq("+index+")" ).addClass("selected_patient");

                        flash('Appointment',data.message,'success','center');
                    }else{
                        flash('Appointment',data.message,'left','danger');
                    }
                },
                error: function(){
                    $btn.button('reset');

                    flash('Close Appointment',"Sorry something went wrong on server.",'left','danger');

                }
            });
        });

        $(document).on('click','.pay_btn', function(event) {
            var $btn = $(this);
            var append_div = $(this).closest('li').find('.payment_status');
            var address_id = btoa($(".top_address_drp").val());
            var service_id = btoa($(".top_service_drp").val());
            var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
            var id = $(this).attr('data-ai');
            var pi = $(this).attr('data-pi');
            var pt = ($(this).attr('data-pt'));

            var date = $(".top_date").val();
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/payment_modal",
                data:{app_i:id,date:date,di:doctor_id,si:service_id,ai:address_id,id:id,pi:pi,pt:pt},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success:function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#paymentModal');
                    $(html).modal('show');
                },
                error: function(){
                    $btn.button('reset');

                    flash('Payment',"Sorry something went wrong on server.",'left','danger');

                }
            });
        });

        $(document).on('confirmed.bs.confirmation','.cancel_btn', function(event) {
            var $btn = $(this);
            var append_div = $(this).closest('li').find('.appointment_status');
            var action_div = $(this).closest('li').find('.action_div');
            var icon_data = JSON.parse(atob($(this).closest('li').find('.appointment_status').attr('data-icon')));
            var id = $(this).attr('data-ai');
            var address_id = btoa($(".top_address_drp").val());
            var service_id = btoa($(".top_service_drp").val());
            var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
            var date = $(".top_date").val();
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/cancel_appointment",
                data:{date:date,di:doctor_id,si:service_id,ai:address_id,id:id},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success:function(data){
                    $btn.button('reset');
                    data = JSON.parse(data);
                    if(data.status==1){
                        var index = $( ".selected_patient" ).index();
                        $( ".selected_patient").replaceWith( data.data.li );
                        $( ".patient_list_li:eq("+index+")" ).addClass("selected_patient");
                        flash('Appointment',data.message,'success','center');
                    }else{
                        flash('Appointment',data.message,'left','danger');
                    }
                },
                error: function(){
                    $btn.button('reset');
                    flash('Close Appointment',"Sorry something went wrong on server.",'left','danger');
                }
            });
        });




        $('#addPatientModal').on('shown.bs.modal', function() {
            var data = readCookie('doctor_setting');
            if(data){
                $("#m_address_id").val(data.address_id);
                $("#m_service_id").val(data.service_id);
            }
            $("#modal_mobile").focus();
        })

        var data = readCookie('doctor_setting');
        var default_date = new Date();
        if(data){
           // $(".top_address_drp").val(data.address_id);
           // $(".top_service_drp").val(data.service_id);
            if(data.date){
                default_date = data.date;
            }
        }



        $('.top_date').datepicker({
            format: 'dd/mm/yyyy',
            setDate: new Date(),
            autoclose:true
        }).on('changeDate', function(e) {
            global_offset=0;
            load_patient_list(0);
        });;
        $('.top_date').datepicker('update', new Date());



        function bind_confirm(){
            $('.close_btn, .cancel_btn').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                title:'Are you sure?',
                placement:'right',
                popout:true,
                singleton:true,
                container: 'body'
            });
        }







        /* ========================advance prescription code start here ==============================*/


        $(document).on('hidden.bs.modal', '#addNewAppointment, #paymentServiceListModal, #smsTemplateListModal, #addressListModal, #addressManageModal, #doctorListModal, #doctorManageModal, #serviceListModal, #followUpListModal, #paymentModal, #printInvoiceModal, #stepModal, #manageMedicineModal, #show_image_dialog, #showPatientHistory, #loadCertificateModal', function () {
            $(this).remove();
        });

        $(document).on('click','#add_patient',function (e) {
            $("#addPatientModal").modal('show').find('form').trigger('reset');
        });




        $(document).on('submit','#add_patient_form',function (e) {
            e.preventDefault();
            var $btn = $(this).find(".save_patient_btn");
            var $form = $(this);
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/add_new_patient",
                data:$(this).serialize(),
                beforeSend:function(){
                    $btn.button('loading').html('Saving...');
                },
                success:function(response){
                    $btn.button('reset');
                    response = JSON.parse(response);
                    if(response.status == 1){
                        flash("Add Patient",response.message, "success",'center');
                        $(".list_not_found").remove();
                        $( ".patient_list_ul" ).prepend( response.data.li );
                        $( ".patient_list_ul li:first" ).trigger('click');


                        $("#addPatientModal").modal('hide');
                        $($form).trigger("reset");
                    }else{
                        flash("Add Patient",response.message, "warning",'center');
                    }
                },
                error: function(data){
                    $btn.button('reset');
                    $($form).trigger("reset");
                    flash("Add Patient","Sorry something went wrong on server.", "danger",'center');

                }
            });
        });

        $(document).on('submit','#add_child_form',function (e) {
            e.preventDefault();
            var $btn = $(this).find(".save_patient_btn");
            var $form = $(this);
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/add_new_child",
                data:$(this).serialize(),
                beforeSend:function(){
                    $btn.button('loading').html('Saving...');
                },
                success:function(response){
                    $btn.button('reset');
                    response = JSON.parse(response);
                    if(response.status == 1){
                        flash("Add Child",response.message, "success",'center');
                        $(".list_not_found").remove();
                        $( ".patient_list_ul" ).prepend( response.data.li );
                        $( ".patient_list_ul li:first" ).trigger('click');
                        $("#addPatientModal").modal('hide');
                        $($form).trigger("reset");
                    }else{
                        flash("Add Child",response.message, "warning",'center');
                    }
                },
                error: function(data){
                    $btn.button('reset');
                    $($form).trigger("reset");
                    flash("Add Child","Sorry something went wrong on server.", "danger",'center');

                }
            });
        });



        $(document).on('click','#sms_template_list_btn',function(){
            var $btn = $(this).find('.label_text');
            $.ajax({
                url: "<?php echo Router::url('/prescription/sms_template_list_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#smsTemplateListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });



        $(document).on('click','#doctor_list_btn',function(){
            var $btn = $(this).find('.label_text');
            $.ajax({
                url: "<?php echo Router::url('/prescription/doctor_list_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#doctorListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });
        $(document).on('click','#address_list_btn',function(){
            var $btn = $(this).find('.label_text');
            $.ajax({
                url: "<?php echo Router::url('/prescription/address_list_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#addressListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });
        $(document).on('click','#service_list_btn',function(){
            var $btn = $(this).find('.label_text');
            $.ajax({
                url: "<?php echo Router::url('/prescription/service_list_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#serviceListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });
        $(document).on('click','#follow_up_list_btn',function(){
            var $btn = $(this).find('.label_text');
            $.ajax({
                url: "<?php echo Router::url('/prescription/follow_up_list_modal',true);?>",
                type:'POST',
                beforeSend:function(){
                    $btn.button('loading').text('Wait..');
                },
                success: function(result){
                    $btn.button('reset');
                    var html = $(result).filter('#followUpListModal');
                    $(html).modal('show');
                },error:function () {
                    $btn.button('reset');
                }
            });
        });










        $(document).on('keyup',"#myInput", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable li").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        $('.date_from, .date_to').datepicker({
            format: 'dd/mm/yyyy',
            setDate: new Date(),
            autoclose:true
        }).on('changeDate', function(e) {
            get_total_records();
        });

        function update_dashboard_count(data){
           /* $("#total_patient_lbl").html(data.total_patient);
            $("#total_visit").html(data.total_visits);
            $("#total_charged").html(data.total_charged+"/-");
            $("#total_received").html(data.total_received+"/-");
            $("#total_due").html(data.total_due+"/-");*/

        }
        function get_total_records(){
            $.ajax({
                type:'POST',
                url: baseUrl+"prescription/get_dashboard_total",
                data:{fd:$('.date_from').val(),td:$('.date_to').val()},
                beforeSend:function(){
                },
                success:function(response){
                    response = JSON.parse(response);
                    update_dashboard_count(response);
                },
                error: function(data){
                    alert("Sorry something went wrong on server.");
                }
            });
        }



        $(document).on('click','#children_list_btn, #patient_list_btn, #appointment_list_btn',function(){
            global_offset =0;
            if($(this).attr('id')=='children_list_btn'){
                    $(".column_1_heading").html("Children List");
            }else if($(this).attr('id')=='patient_list_btn'){
                $(".column_1_heading").html("Patient List");
            }else if($(this).attr('id')=='appointment_list_btn'){
                $(".column_1_heading").html("Appointment List");
            }
            $(".search_box").val('');
            var type = $(this).attr('data-ty');
            load_patient_list(0,false,type);

        });



        $(document).on('click',"#menu_button li", function() {
            $("#menu_button li").removeClass('active');
           $(this).addClass('active');
        });


        $(document).on('click',"#dashboard_btn", function() {
             $("#dashboard_box").show();
             $("#patient_detail_box").html('');
            $('.date_from, .date_to').datepicker('setDate', new Date());
            $('.date_from').trigger('changeDate');

            $(".graph_box iframe").each(function (index,value){
               $(this).attr('src',$(this).attr('src'));
            });
        });


        $("#patient_list_btn").trigger("click");


        $("#menu_button li").removeClass("active");
        $("#dashboard_btn").addClass("active");

        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                if(($("#addNewAppointment").data('bs.modal') || {}).isShown ){
                    $("#addNewAppointment").modal('hide');
                }else if(($("#stepModal").data('bs.modal') || {}).isShown ){
                    $("#stepModal").modal('hide');
                }
            }
        });


    });
</script>

</html>







