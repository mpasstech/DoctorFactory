<!DOCTYPE html>

<html>
<head class="row_content">
    <title>Prescription</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php

    echo $this->Html->css(array('bootstrap.min.css','font-awesome.min.css','prescription.bundle.min.css','prescription.css','web_prescription_taginput.css','easy-autocomplete.min.css','bootstrap-datepicker.min.css'),array("media"=>'all','fullBase' => true));
    echo $this->Html->script(array('prescription.bundle.min.js','bootstrap-popup-confirmation.min.js','web_prescription_taginput.js','html2canvas.js','canvas2image.js','printThis.js','flash-alert.js','jquery.easy-autocomplete.min.js','jquery.masked-input.min.js','bootstrap-datepicker.min.js'),array('fullBase' => true));


    ?>


    <script>
        var baseUrl = '<?php echo Router::url('/',true); ?>';
        var append ="";
        var step_object = new Object();
    </script>
    <style>


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
    $service_list = $this->AppAdmin->custom_dropdown_list($doctor['id'],$doctor['thinapp_id'],'SERVICE');


    ?>

    <div class="row doctor_header">
        <ul>

            <li class="doc_name">
                <div class="doc_logo">
                    <img src="<?php echo $path; ?>">
                </div>
                <label><?php echo $doctor['name']; ?></label>

                <div class="top_option">
                    <?php echo $this->Form->input('top_address_drp',array('type'=>'select','div'=>false,'label'=>false,'options'=>$address_list,'class'=>'top_drp top_address_drp')); ?>
                    <?php echo $this->Form->input('top_service_drp',array('type'=>'select','div'=>false,'label'=>false,'options'=>$service_list,'class'=>'top_drp top_service_drp')); ?>


                </div>

            </li>
            <li class="doc_time"><label>

                    <div class="input-group date">
                        <input type="text" class="top_date">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </div>
                    </div>


                </label></li>
        </ul>

    </div>
    <div class="row patient_detail">





        <div class="col-sm-2">
            <div class="form-group">
                <label>Patient Name</label>
                <input type="text" id="pat_name" class="form-control" >
            </div>
        </div>



        <div class="col-sm-2">
            <div class="form-group">
                <label>Patient Mobile</label>
                <input type="text" id="pat_mobile" data-masked-input="9999999999"  maxlength="10" minlength="10" class="form-control" >
            </div>
        </div>

        <div class="col-sm-1 small_box">
            <div class="form-group">
                <label>Age</label>
                <input type="text" id="pat_age" data-masked-input="999"  maxlength="3" minlength="1" class="form-control" >
            </div>
        </div>

        <div class="col-sm-1">
            <div class="form-group">
                <label>DOB</label>
                <input type="text" id="pat_dob"  data-masked-input="99-99-9999" placeholder="DD-MM-YYYY" maxlength="10" class="form-control dob_box" >
            </div>
        </div>

        <div class="col-sm-1">
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" id="pat_gender" class="form-control">
                    <option value="MALE">Male</option>
                    <option value="FEMALE">Female</option>
                </select>

            </div>
        </div>

        <div class="col-sm-1">
            <div class="form-group">
                <label>Blood Group</label>
                <select name="gender" id="pat_blood_group" class="form-control">
                    <option value="N/A">N/A</option>
                    <option value="O+">O+</option>
                    <option value="A+">A+</option>
                    <option value="B+">B+</option>
                    <option value="AB+">AB+</option>
                    <option value="O-">O-</option>
                    <option value="A-">A-</option>
                    <option value="B-">B-</option>
                    <option value="AB-">AB-</option>

                </select>

            </div>
        </div>


        <div class="col-sm-2">
            <div class="form-group">
                <label>Parent Name</label>
                <input type="text" id="pat_par_name" class="form-control" >
            </div>
        </div>

        <div class="col-sm-1">
            <div class="form-group">
                <label>Parent Mobile</label>
                <input type="text" id="pat_par_mobile" data-masked-input="9999999999"  maxlength="10" minlength="10" class="form-control" >
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group">
                <label>Address</label>
                <input type="text" id="pat_address" class="form-control" >
            </div>
        </div>


        <div class="col-sm-1">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" class="btn-xs save_info"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>


    </div>
    <div class="row">
        <div class="column patient_list column_1">

            <div class="card">
                <div class="left_btn_container">
                    <a class="btn btn-warning btn-xs search_btn"  href="javascript:void(0);"><i class="fa fa-search"></i> Search</a>
                    <a class="btn btn-success add_patient_btn btn-xs"  href="javascript:void(0);"><i class="fa fa-plus"></i> Add New Patient</a>
                </div>
                <div class="left-menu-content">
                    <div class="search_div" style="display: none;">
                        <div class="input-group">
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


            <div class="amount_div">
                <ul class="amount_ul">
                    <li class="btn btn-success"><label >Today's Collection </label><span class="today_coll">0 Rs/-</span></li>
                    <li class="btn btn-info"><label >Month's Collection </label><span class="month_coll">0 Rs/-</span></li>
                    <li class="btn btn-danger"><label >Total Collection </label><span class="total_coll">0 Rs/-</span></li>
                </ul>



            </div>


        </div>
        <div class="column column_2">

            <div id="wrapper">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="setp_tab">
                    <?php foreach ($final_array as $key => $value){ ?>
                        <li><a  data-id="<?php echo 'cat_'.$value['category_id']; ?>" href="#<?php echo 'cat_'.$value['category_id']; ?>" data-toggle="tab"><?php echo $value['category_name']; ?></a></li>
                    <?php } ?>

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">


                    <?php $tab_counter =0; foreach ($final_array as $key => $value){ $tab_counter++; ?>


                        <script type="text/javascript">
                            var cat_id = '<?php echo 'cat_'.$value['category_id']; ?>';
                            var cat_name = '<?php echo $value['category_name']; ?>';
                            append += "<div style='display: none;' class='resize_font "+cat_id+"'><h5 class='category_name_heading'  >"+cat_name+"</h5><table>";

                            var table_th_string = "";
                            var table_td_string = "";
                        </script>

                        <div  data-cat="<?php echo $value['category_id']; ?>" class="category tab-pane fade in <?php echo ($key==1)?'active':''; ?>"  id="<?php echo 'cat_'.$value['category_id']; ?>">
                            <ul class="list-group">
                                <?php if(!empty($value['steps'])){ $setp_counter = 0; foreach ($value['steps'] as $step_key => $step_value){  ?>


                                    <li class="list-group-item step_li" data-step="<?php echo $step_value['step_id']; ?>">
                                        <label class="step_name_lbl"><?php echo $step_value['step_name']; ?></label>
                                        <input   id="<?php echo "step_".$step_value['step_id']; ?>" name="<?php echo "step_".$step_value['step_id']; ?>" type="text" class="tag_input_box form_group text_box"/>
                                        <script type="text/javascript">

                                            var step_id = '<?php echo "step_".$step_value['step_id']; ?>';
                                            var step_name = '<?php echo $step_value['step_name']; ?>';
                                            var obj = "#"+step_id;
                                            var jsonData = '<?php echo @json_encode($step_value['tags']); ?>';
                                            $(obj).tagSuggest({
                                                data: JSON.parse(jsonData),
                                                sortOrder: 'name',
                                                maxDropHeight: 200,
                                                name: step_id
                                            });
                                            step_object['<?php echo $step_value['step_id']; ?>'] = JSON.parse(jsonData);
                                            table_th_string += "<th style='display: none;' class='resize_font th_"+step_id+"'>"+step_name+"</th>";
                                            table_td_string += "<td style='display: none;' valign='top' class='resize_font "+step_id+"'></td>";

                                        </script>


                                    </li>


                                    <?php $setp_counter++; }} ?>

                            </ul>

                            <div class="btn_row row text-right">
                                <?php if(count($final_array) == $tab_counter){} ?>

                                <?php if(count($final_array) > $tab_counter){ ?>
                                    <button class="btnNext btn btn-success btn-xs">Next <i class="fa fa-arrow-right" ></i></button>
                                <?php } ?>

                                <?php if($tab_counter > 1){ ?>
                                    <button class="btnPrev btn btn-info btn-xs"><i class="fa fa-arrow-left" ></i> Prev</button>
                                <?php } ?>

                            </div>


                        </div>

                        <script type="text/javascript">
                            append += "<tr>"+table_th_string+"</tr>";
                            append += "<tr>"+table_td_string+"</tr>";
                            append += "</table></div>";
                        </script>

                    <?php  } ?>



                </div>

            </div>


        </div>
        <div class="column column_3 A4">

            <section  class="sheet padding-0mm">


                <div class="panel panel-info panel_default">
                    <div class="panel-heading">
                        <table id="heading_table">

                        </table>

                    </div>
                    <div class="panel-body">
                        <h1>Rx</h1>

                        <div class="tag_container">


                        </div>

                        <h6 class="signature"><?php echo $doctor['name']; ?></h6>
                    </div>

                </div>



            </section>

            <div class="prescription_btn_div">
                <div style="display: none;" class="toggle_btn_div">
                    <label>Print With Save</label>
                    <label class="switch">
                        <input type="checkbox" id="print_with_save">
                        <span class="slider round"></span>
                    </label>
                </div>

                <button type='button' class="btn btn-warning save_btn"><i class="fa fa-save"></i> Save & Print</button>
            </div>

        </div>
        <div class="column column_4">

            <div class="panel-group" id="accordion">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <a data-toggle="collapse" data-parent="#accordion" href="#pat_history">
                            <label class="panel-title">Patient History</label>
                        </a>
                    </div>
                    <div id="pat_history" class="panel-collapse collapse in">
                        <div class="panel-body history_panel_div">

                        </div>
                    </div>
                </div>
                <div class="panel panel-success">

                    <div class="panel-heading">
                        <a data-toggle="collapse" data-parent="#accordion" href="#vital">
                            <label class="panel-title">Vital</label>
                        </a>
                    </div>
<!--
                    <div id="vital" class="panel-collapse collapse">
                        <div class="panel-body">
                            Comming soon
                        </div>
                    </div>-->
                </div>

            </div>




        </div>
    </div>


    <?php
    $left = $tb_left = '0%';
    $top = $tb_top= '0%';
    $width = $tb_width= '60%';
    $height = '8%';
    $fontSize = $tb_text_font_size= $tb_label_font_size= $tb_heading_font_size= '13px';

    $barcode = 'NO';
    $background_img = "";
    $header_box_border = $tag_box_border = "2px solid";

    if (!empty($prescription_setting)) {
        $data = $prescription_setting;
        $left = $data['left'];
        $top = $data['top'];
        $width = $data['width'];
        $height = $data['height'];
        $fontSize = $data['font_size'];
        $barcode = $data['barcode'];
        $tb_left = $data['tb_left'];
        $tb_top = $data['tb_top'];
        $tb_width = $data['tb_width'];
        $tb_text_font_size = !empty($data['tb_text_font_size'])?$data['tb_text_font_size']:$tb_text_font_size;
        $tb_label_font_size = !empty($data['tb_label_font_size'])?$data['tb_label_font_size']:$tb_label_font_size;
        $tb_heading_font_size = !empty($data['tb_heading_font_size'])?$data['tb_heading_font_size']:$tb_heading_font_size;
        $header_box_border = !empty($data['header_box_border'])?$data['header_box_border']:$header_box_border;
        $tag_box_border = !empty($data['tag_box_border'])?$data['tag_box_border']:$tag_box_border;
        $prescription = $data['prescription'];
        if(!empty($prescription)){

            $img = file_get_contents($prescription);
            $data = "data:image/png;base64,".base64_encode($img);
            $background_img = "background-image:url('".$data."');";
        }
    }


    ?>

    <style media="print">
        @page {
            size: A4;
            margin: 0;
        }
        @media print {
            html, body {
                width: 210mm;
                /*height: 297mm;*/
            }
        }
    </style>

    <div id="printThis" style="width:210mm; height:297mm; background-size: cover !important; <?php echo $background_img; ?>;display:none;" >
        <div><img id="barcode"/></div>
        <table id="header_box" class="header_box"    style="position:relative;border:<?php echo $header_box_border; ?>; left: <?php echo $left; ?>; font-size: <?php echo $fontSize; ?>; top: <?php echo $top; ?>; width: <?php echo $width; ?>; height: <?php echo $height; ?>;" >
        </table>
        <table class="tag_box" id="tag_box"   style="position:relative;border:<?php echo $tag_box_border; ?>; left: <?php echo $tb_left; ?>; font-size: <?php echo $tb_text_font_size; ?>; top: <?php echo $tb_top; ?>; width: <?php echo $tb_width; ?>; height: auto;" >


        </table>
    </div>

</div>

<div class="modal fade" id="addPatientModal" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-md">
        <form id="booking_frm">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Pateint</h4>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="form-group">

                            <div class="col-sm-6">
                                <label style="width: 100%;">Mobile   <i style="float: right; display: none;" class="loading_mobile fa fa-spinner fa-pulse fa-fw"></i></label>
                                <input type="text" id="modal_mobile" data-masked-input="9999999999"  maxlength="10" minlength="10" name="m_pat_mobile" class="form-control">
                            </div>


                            <div class="col-sm-6">
                                <label style="width: 100%;">Name   <i style="float: right; display: none;" class="loading_name fa fa-spinner fa-pulse fa-fw"></i></label>

                                <input type="text" id="m_pat_name" name="m_pat_name" maxlength="20" minlength="3"  class="form-control">
                            </div>



                            <div class="col-sm-6">
                                <label>E-mail</label>
                                <input type="text"  name="m_email" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-1">
                                <label>Age</label>
                                <input type="text" name="m_age" data-masked-input="999"  maxlength="3" minlength="1"  class="form-control">
                            </div>

                            <div class="col-sm-2">
                                <label>Gender</label>
                                <select name="m_gender" class="form-control">
                                    <option value="MALE">Male</option>
                                    <option value="FEMALE">Female</option>
                                </select>

                            </div>

                            <div class="col-sm-3">
                                <label>DOB</label>
                                <input type="text" name="m_dob" data-masked-input="99-99-9999" placeholder="DD-MM-YYYY"  maxlength="10" class="form-control">
                            </div>



                            <div class="col-sm-6">

                                <label>Blood Group</label>
                                <select name="m_blood_group" class="form-control">
                                    <option value="N/A">N/A</option>
                                    <option value="O+">O+</option>
                                    <option value="A+">A+</option>
                                    <option value="B+">B+</option>
                                    <option value="AB+">AB+</option>
                                    <option value="O-">O-</option>
                                    <option value="A-">A-</option>
                                    <option value="B-">B-</option>
                                    <option value="AB-">AB-</option>
                                </select>

                            </div>



                            <div class="col-sm-6">
                                <label>Marital Status</label>
                                <select name="m_marital_staus" class="form-control">
                                    <option value="">Select Status</option>
                                    <option value="MARRIED">Married</option>
                                    <option value="UNMARRIED">Unmerried</option>
                                </select>

                            </div>









                            <div class="col-sm-6">
                                <label>Parent Name</label>
                                <input type="text"  name="m_par_name" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-6">
                                <label>Parent Mobile</label>
                                <input type="text"   name="m_par_mobile" data-masked-input="9999999999"  maxlength="10" minlength="10" class="form-control" placeholder="">

                            </div>
                            <div class="col-sm-6">
                                <label>Refer By Name</label>
                                <input type="text"  name="m_ref_name" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-6">
                                <label>Refer By Mobile</label>
                                <input type="text"  name="m_ref_mobile" data-masked-input="9999999999"  maxlength="10" minlength="10" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-12">
                                <label>Address</label>
                                <input type="text"  name="m_address" class="form-control" placeholder="">
                            </div>


                            <div class="col-sm-6">
                                <label>Reason Of Appointment</label>
                                <input type="text"  name="m_roa" class="form-control" placeholder="">
                            </div>

                            <div class="col-sm-6">
                                <label>Remark</label>
                                <input type="text"  name="m_remark" class="form-control" placeholder="">
                            </div>






                            <div class="col-sm-6">
                                <label>Select Address</label>
                                <?php echo $this->Form->input('m_address_id',array('id'=>'m_address_id','type'=>'select','label'=>false,'options'=>$address_list,'class'=>'form-control')); ?>
                            </div>
                            <div class="col-sm-6">
                                <label>Select Service</label>
                                <?php echo $this->Form->input('m_service_id',array('id'=>'m_service_id','type'=>'select','label'=>false,'options'=>$service_list,'class'=>'form-control')); ?>
                            </div>



                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <input type="hidden" name="m_pi" />
                    <input type="hidden" name="m_pt" />
                    <input type="hidden" name="m_di" value="<?php echo base64_encode($doctor['id']); ?>" />
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close"></i> Cancel</button>
                    <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Reset</button>
                    <button type="button" class="btn btn-success m_save"><i class="fa fa-save"></i> Save</button>

                </div>
            </div>
        </form>
    </div>
</div>


</body>

<script type="text/javascript">
    $(function () {



        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

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
        function load_patient_list(offset,search_request=false){
            if(loading_list===false){
                patient={};
                $(".list_not_found").remove();
                var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
                var address_id = $(".top_address_drp").val();
                var service_id = $(".top_service_drp").val();
                var date = $(".top_date").val();
                var search = $('.search_box').val();
                var last_icon = $('.search_div .search_icon').html();
                var loader =  '<div class="list_loader" ><img src="<?php echo Router::url('/img/ajaxloader.gif',true); ?>"></div>';
                $.ajax({
                    type:'POST',
                    url: baseUrl+"doctor/web_prescription_patient_list",
                    data:{date:date,ai:address_id,si:service_id,di:doctor_id,s:search,o:offset},
                    beforeSend:function(){
                        loading_list = true;
                        if(search_request===true){
                            $('.search_div .search_icon').html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
                        }else{
                            $('.patient_list_ul').append(loader);
                        }

                    },
                    success:function(data){

                        if(search_request===true){
                            $('.search_div .search_icon').html(last_icon);
                            global_offset =0;
                        }else{
                            $(".list_loader").remove();

                        }
                        if(offset==0){
                            $('.patient_list_ul').html(data);
                        }else{
                            $('.patient_list_ul').append(data);
                        }
                        var inter = setInterval(function () {
                            if(offset==0){
                                $('.patient_list_li:first-child').trigger('click');
                                if($('.patient_list_li').length == 0 && $("#top_address_drp").val()!='' && $("#top_service_drp").val() !=''){
                                    $(".add_patient_btn").trigger('click');
                                }


                            }
                            loading_list = false;
                            global_offset++;
                            clearInterval(inter);
                        },300);



                    },
                    error: function(data){
                        alert("Sorry something went wrong on server.");
                    }
                });
            }

        }


        $(document).on('keypress','.search_box',function (e) {

            var keycode = (e.keyCode ? e.keyCode : e.which);
            if(keycode==13){
                load_patient_list(0,true);
            }

        });


        var lastScrollTop = 0;
        $('.patient_list_ul').scroll(function(e){

            var scrollAmount = $(this).scrollTop();
            if(scrollAmount > lastScrollTop){
                var documentHeight = $(this).height();
                var scrollPercent = (scrollAmount / documentHeight) * 100;
                if(scrollPercent > 15) {
                    load_patient_list(global_offset);
                }
            }
            lastScrollTop = scrollAmount;
        });






        var patient;
        $(document).on('click','.patient_list_li',function (e) {

            var node = e.target.nodeName;
            /* THIS conditin will not work for li action button when click*/
            if(node!='BUTTON' && node!='I') {


                $("#tag-input-0").focus();
                $(".patient_list_li").removeClass('active_li');
                $(this).addClass('active_li');


                $("#expend_menu").show();
                $(this).find('.append_menu').hide().html($("#expend_menu")).slideDown(400);
                $('.action_div').slideUp(50);

                $(this).closest('li').find('.action_div').slideDown(300);

                patient = atob($(this).attr('data-row'));
                patient = JSON.parse(patient);

                var app_id = btoa(patient.thinapp_id);
                var uhid = btoa(patient.uhid);

                var history_url =  baseUrl+"app_admin/get_patient_history/"+app_id+"/"+uhid;
                var name = patient.patient_name;
                var mobile = patient.patient_mobile;
                var patient_type = patient.patient_type;
                var patient_id = patient.patient_id;
                var appointment_id = patient.appointment_id;
                var appointment_datetime = patient.appointment_datetime;
                var token = patient.queue_number;
                var gender = patient.gender;
                var age = patient.age;
                var dob = patient.dob;
                var parents_mobile = patient.parents_mobile;
                var parents_name = patient.parents_name;
                var address = patient.address;
                var blood_group = patient.blood_group;

                $('#pat_par_name').val(parents_name);
                $('#pat_par_mobile').val(parents_mobile);
                $('#pat_address').val(address);


                $('#pat_name').val(name);
                $('#lbl_pat_name').html(name);
                $('#pat_mobile').val(mobile);
                $('#lbl_pat_mobile').html(mobile);
                $('#pat_age').val(age);
                $('#lbl_pat_age').html(age);
                $('#pat_dob').val(dob);

                $('#pat_blood_group').val(blood_group);
                $('#pat_gender').val(gender);
                $('#lbl_pat_gender').html(gender);
                $('#lbl_app_date').html(appointment_datetime);
                $('#lbl_token').html(token);

                create_header($(this).attr('data-row'));

                $(".history_panel_div").html('<iframe id="history_frame" src="'+history_url+'" ></iframe>');
                var height = $( window ).height() - ($( '.doctor_header' ).height() + $('.patient_detail').height());
                $('#history_frame').height(parseInt(height));


                bind_confirm();
            }




        });

        function create_header(raw_data){
            var fields = '<?php echo $fields; ?>';
            $.ajax({
                type:'POST',
                url: baseUrl+"doctor/create_header",
                data:{d:raw_data,f:fields},
                beforeSend:function(){

                },
                success:function(data){
                    $("#heading_table").html(data);
                }
            });


        }

        $(document).on('click','.add_patient_btn',function (e) {
            $("#addPatientModal").modal('show');
        });

        $(document).on('keyup','.dob_box',function (e) {
            if($(this).val().match('\d{1,2}/\d{1,2}/\d{4}')){
                return ;
            }else{

            }

        });


        function update_print_prescription(){
            var header_table = $.trim($("#heading_table").html());
            var max_th_count = $("#heading_table tr:first-child th").length;
            $("#header_box").html(header_table);
            var last_row_th = $("#header_box tr:last-child th").length;
            if(last_row_th < max_th_count){
                for(var i=0; i < (max_th_count-last_row_th); i++){
                    $("#header_box tr:last-child").append("<th></th>");
                }
            }
            var tag_body="";
            $(".tag_container .resize_font:visible").each(function(index,value){
                tag_body += "<tr><td class='tag_heading_td'>"+$.trim($(this).find('.category_name_heading').text())+"</td><tr>";
                $(this).find("tbody tr").each(function(tr_index,tr_value) {
                    tag_body += "<tr>"+$(this).html()+"</tr>";
                })
            });
            $("#tag_box").html(tag_body);
            $("#tag_box th").css('font-size','<?php echo $tb_label_font_size; ?>');
            $("#tag_box td").css('font-size','<?php echo $tb_text_font_size; ?>');
            $("#tag_box .tag_heading_td").css('font-size','<?php echo $tb_heading_font_size; ?>');

        }

        $(document).on('click','.print_btn',function (e) {
            update_print_prescription();
            print_prescription();
        });

        function print_prescription(){
             $('#printThis').printThis({              // show the iframe for debugging
                importCSS: true,            // import page CSS
                importStyle: true,         // import style tags
                pageTitle: "",              // add title to print page
                removeInline: false,        // remove all inline styles from print elements
                printDelay: 0,            // variable print delay; depending on complexity a higher value may be necessary
                header: null,               // prefix to html
                footer: null,               // postfix to html
                base: false ,               // preserve the BASE tag, or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: false,              // copy canvas elements (experimental)
                doctypeString: " ",       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false       // copy classes from the html & body tag
            });
        }

        $(document).on('click','.save_btn, .save_print_btn',function (e) {

            var $btn = $(this);
            $btn.button('loading').html('Saving...');
            update_print_prescription();

            var final_array = new Object();
            var new_tag =[];
            var tag_object =[];
            $(".column_2 .category").each(function () {
                var cat_id = $(this).attr('id');
                var temp = [];
                $(this).find('li .tag-ctn').each(function () {
                    var step_input_box_id = $(this).attr('id');
                    var category_id = $(this).closest('.category').attr('data-cat');
                    var step_id = $(this).closest('.step_li').attr('data-step');
                    var input_values = $("#"+step_input_box_id).tagSuggest().getSelectedItems();
                    tag_object.push($("#"+step_input_box_id));
                    if(input_values.length > 0){
                        var inner_tmp = new Object();
                        inner_tmp['step_id'] = step_input_box_id;
                        inner_tmp['selected_tag'] = input_values;
                        $( input_values ).each(function( index, obj ) {
                            if(obj.id == obj.name){
                                var tmp_tag = new Object();
                                tmp_tag['step_id'] = step_id;
                                tmp_tag['category_id'] = category_id;
                                tmp_tag['tag_name'] = obj.name;
                                new_tag.push(tmp_tag);
                            }
                        });
                        inner_tmp['category_id'] = category_id;
                        temp.push(inner_tmp);
                    }
                });
                if(temp.length > 0){
                    final_array[cat_id] = temp;
                }
            });
            if(patient &&  Object.keys(patient).length > 0){
                if(Object.keys(final_array).length > 0 ){
                    html2canvas(document.querySelector("#printThis"),{'useCORS':true}).then(function(canvas) {
                        var baseEncodeImage = canvas.toDataURL("image/png");
                        var doctor_id = '<?php echo base64_encode($doctor['id']); ?>';
                        var address_id = $(".top_address_drp").val();
                        var service_id = $(".top_service_drp").val();
                        var appointment_id = btoa(patient.appointment_id);
                        var date = $(".top_date").val();
                        $.ajax({
                            type:'POST',
                            url: baseUrl+"doctor/save_prescription",
                            data:{nt:JSON.stringify(new_tag),date:date,app_i:appointment_id,ai:address_id,si:service_id,di:doctor_id,pi:btoa(patient.patient_id),pt:patient.patient_type, pre_base64:baseEncodeImage,fi:patient.folder_id,ps:JSON.stringify(final_array)},
                            beforeSend:function(){
                                $btn.button('loading').html('Saving...');
                            },
                            success:function(data){
                                data = JSON.parse(data);
                                $btn.button('reset');

                                if(data.status == 1){
                                    var print_html = $("#printThis").html();

                                    flash("Prescription",data.message, "success",'center');
                                    var index = ($( ".patient_list_li.active_li" ).index())+1;
                                    if(data.data.tags.length  > 0 ){
                                        $( data.data.tags ).each(function( index, obj ) {
                                            var temp_obj = {'id':obj.id,'name':obj.name};
                                            step_object[obj.step_id].push(temp_obj);
                                            var step_id = "step_"+obj.step_id;
                                            $("#"+step_id).tagSuggest().setData(step_object[obj.step_id]);
                                        });
                                    }
                                    $( ".patient_list_li.active_li" ).replaceWith( data.data.li );
                                    $(tag_object).each(function () {
                                        $(this).tagSuggest().clear();
                                    });
                                    $(".tag_container .resize_font").hide();

                                    $(".patient_list_li:eq("+index+")").trigger('click');

                                    $("#printThis").html(print_html);
                                    print_prescription();

                                }else{

                                    flash("Error",data.message, "danger");



                                }


                            },
                            error: function(data){
                                $btn.button('reset');
                                alert("Sorry something went wrong on server.");
                            }
                        });
                    });
                }else{
                    flash("Warning","Please input prescription values", "warning");
                }
            }else{
                print_prescription();
                flash("Warning","Please input prescription values", "warning");
            }

        });




        $(document).on('click','.save_info',function (e) {


            var $btn = $(this);

            if(patient &&  Object.keys(patient).length > 0){
                var name = $('#pat_name').val();
                var mobile = $('#pat_mobile').val();
                var age = $('#pat_age').val();
                var dob = $('#pat_dob').val();
                var blood_group =  $('#pat_blood_group').val();
                var gender =  $('#pat_gender').val();
                var pt =  patient.patient_type;
                var pi =  btoa(patient.patient_id);
                var ai =  (patient.appointment_id);
                var parents_name = $('#pat_par_name').val();
                var parents_mobile = $('#pat_par_mobile').val();
                var address = $('#pat_address').val();
                var address_id = $(".top_address_drp").val();
                var service_id = $(".top_service_drp").val();
                var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
                var date = $(".top_date").val();
                if(name ==""){
                    flash("Warning","Please input patient name", "warning");
                }else if(mobile ==""){

                    flash("Warning","Please enter patient mobile number", "warning");
                }else{


                    $.ajax({
                        type:'POST',
                        url: baseUrl+"doctor/update_detail",
                        data:{date:date,di:doctor_id,add_id:address_id,si:service_id,pn:parents_name,pm:parents_mobile,address:address,ai:ai,name:name,mobile:mobile,age:age,dob:dob,blood_group:blood_group,gender:gender,pt:pt,pi:pi},
                        beforeSend:function(){
                            $btn.button('loading').html('Saving...');
                        },
                        success:function(data){
                            $btn.button('reset');

                            data = JSON.parse(data);

                            if(data.status == 1){
                                flash("Edit Patient",data.message, "success");
                                var index = $( ".patient_list_li.active_li" ).index();

                                $( ".patient_list_li.active_li" ).replaceWith( data.data.li );
                                $(".patient_list_li:eq("+index+")").trigger('click');
                            }else{
                                flash("Edit Patient",data.message, "danger");
                            }

                        },
                        error: function(data){
                            $btn.button('reset');
                            alert("Sorry something went wrong on server.");
                        }
                    });
                }
            }else{
                flash("Warning","Please select patient first", "warning");
            }






        });


        function flash(title,message,type,position='right',closeTime=3000){
            $.alert(message, {
                autoClose: true,
                closeTime: closeTime,
                withTime: false,
                type: type,
                position: [position, [-0.42, 0]],
                title: title,
                icon: false ,
                close: true,
                speed: 'normal',
                isOnly: true,
                minTop: 10,
                animation: false,
                animShow: 'fadeIn',
                animHide: 'fadeOut',
                onShow: function () {
                },
                onClose: function () {
                }

            });
        }

        function setHeight(){
            var height = $( window ).height() - ($( '.doctor_header' ).height() + $('.patient_detail').height());
            $('.column').height(parseInt(height));

            $('.tab-content').height(parseInt($('.column_2').height())-50);
            $('.panel_default').height((parseInt(height)-35));
            $('.list_ul').height((parseInt($('.column_1').height())-parseInt($(".amount_ul").height()))-90);



        }

        $(window).on('resize', function(){
            setHeight();
        });

        setHeight();

        var down = setInterval(function () {
            $(".column").fadeIn(200);
            clearInterval(down);
        },100);
        $(".column").hide();



        var patient = "<?php echo base64_encode(json_encode($all_patient)); ?>";
        patient  = atob(patient);
        var mobile_option = {
            data: JSON.parse(patient),
            getValue: "mobile",
            list:{
                onChooseEvent: function() {
                    var index = $("#modal_mobile").getSelectedItemIndex();
                    var data = $("#modal_mobile").getSelectedItemData();
                    load_detail(data,'loading_mobile');
                },match: {
                    enabled: true
                },maxNumberOfElements: 10,
            },
            template: {
                type: "description",
                fields: {
                    description: "patient_name"
                }
            }
        };
        $("#modal_mobile").easyAutocomplete(mobile_option);


        var name_option = {
            data: JSON.parse(patient),
            getValue: "patient_name",
            list:{
                onChooseEvent: function() {
                    var index = $("#m_pat_name").getSelectedItemIndex();
                    var data = $("#m_pat_name").getSelectedItemData();
                    load_detail(data,'loading_name');
                },match: {
                    enabled: true
                },maxNumberOfElements: 10,
            },
            template: {
                type: "description",
                fields: {
                    description: "mobile"
                }
            }
        };
        $("#m_pat_name").easyAutocomplete(name_option);


        $('#modal_mobile').on('keyup', function (e) {

            var keycode = (e.keyCode ? e.keyCode : e.which);

            if(keycode==8){
                //  $('#booking_frm').trigger('reset');
            }
        });


        $('input').attr('autocomplte',"off");

        function load_detail(data,loader){


            var pi = btoa(data.patient_id);
            var pt = data.patient_type;


            $.ajax({
                type:'POST',
                url: baseUrl+"doctor/get_patient_detail",
                data:{pi:pi,pt:pt},
                beforeSend:function(){
                    $("."+loader).fadeIn();
                },
                success:function(response){
                    response = JSON.parse(response);
                    $("."+loader).fadeOut();
                    if(response.status == 1){

                        response = response.data.detail;
                        $("[name='m_pat_name']").val(response.patient_name);
                        $("[name='m_pat_mobile']").val(response.mobile);
                        $("[name='m_email']").val(response.email);
                        $("[name='m_age']").val(response.age);
                        $("[name='m_gender']").val(response.gender);
                        $("[name='m_dob']").val(response.dob);
                        $("[name='m_marital_staus']").val(response.marital_status);
                        var blood_group = (response.blood_group =="")?"N/A":response.blood_group;
                        $("[name='m_blood_group']").val(blood_group);
                        $("[name='m_par_name']").val(response.parents_name);
                        $("[name='m_par_mobile']").val(response.parents_mobile);
                        $("[name='m_address']").val(response.address);
                        $("[name='m_pi']").val(pi);
                        $("[name='m_pt']").val(pt);
                    }
                },
                error: function(data){
                    $btn.button('reset');
                    flash("Patient Detail","Sorry something went wrong on server.", "danger",'center');
                }
            });
        }



        $(document).on('click','.m_save',function (e) {
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseUrl+"doctor/book_new_appointment",
                data:$('#booking_frm').serialize(),
                beforeSend:function(){
                    $btn.button('loading').html('Saving...');
                },
                success:function(response){
                    $btn.button('reset');
                    response = JSON.parse(response);
                    if(response.status == 1){
                        flash("Add Patient",response.message, "success",'center');
                        $("#addPatientModal").modal('hide');
                        $( ".patient_list_ul" ).prepend( response.data.li );
                        $( ".patient_list_ul li:first" ).trigger( 'click' );
                        set_amount_label(response.data.total);

                    }else{
                        flash("Add Patient",response.message, "warning",'center');
                    }
                },
                error: function(data){
                    $btn.button('reset');
                    flash("Add Patient","Sorry something went wrong on server.", "danger",'center');

                }
            });


        });

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
            load_patient_list(0);


        });


        function updateCookieData(){
            var setting_obj = new Object();
            setting_obj['address_id'] = $(".top_address_drp").val();
            setting_obj['service_id'] = $(".top_service_drp").val();

            setting_obj['date'] = $(".top_date").val();
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
                url: baseUrl+"doctor/close_appointment",
                data:{date:date,di:doctor_id,si:service_id,ai:address_id,id:id},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');

                },
                success:function(data){

                    $btn.button('reset');
                    data = JSON.parse(data);
                    if(data.status==1){
                        var index = $( ".patient_list_li.active_li" ).index();
                        $( ".patient_list_li.active_li" ).replaceWith( data.data.li );
                        $( ".patient_list_li:eq("+index+")" ).trigger( 'click' );
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

        $(document).on('confirmed.bs.confirmation','.pay_btn', function(event) {
            var $btn = $(this);
            var append_div = $(this).closest('li').find('.payment_status');
            var address_id = btoa($(".top_address_drp").val());
            var service_id = btoa($(".top_service_drp").val());
            var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
            var id = $(this).attr('data-ai');
            var date = $(".top_date").val();
            $.ajax({
                type:'POST',
                url: baseUrl+"doctor/appointment_payment",
                data:{date:date,di:doctor_id,si:service_id,ai:address_id,id:id},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success:function(data){

                    $btn.button('reset');
                    data = JSON.parse(data);
                    if(data.status==1){
                        var index = $( ".patient_list_li.active_li" ).index();
                        $( ".patient_list_li.active_li" ).replaceWith( data.data.li );
                        $( ".patient_list_li:eq("+index+")" ).trigger( 'click' );
                        set_amount_label(data.data.total);
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
                url: baseUrl+"doctor/cancel_appointment",
                data:{date:date,di:doctor_id,si:service_id,ai:address_id,id:id},
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                },
                success:function(data){
                    $btn.button('reset');
                    data = JSON.parse(data);
                    if(data.status==1){
                        var index = $( ".patient_list_li.active_li" ).index();
                        $( ".patient_list_li.active_li" ).replaceWith( data.data.li );
                        $( ".patient_list_li:eq("+index+")" ).trigger( 'click' );
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
            $(".top_address_drp").val(data.address_id);
            $(".top_service_drp").val(data.service_id);
            if(data.date){
                default_date = data.date;
            }
        }
        $('.top_date').datepicker({
            format: 'dd/mm/yyyy',
            setDate: new Date(),
            autoclose:true
        }).on('changeDate', function(e) {
            updateCookieData();
            load_patient_list(0);
        });;
        $('.top_date').datepicker('update', default_date);




        function set_amount_label(data=null){
            if(data){
                var unit = "Rs /-";
                $( ".today_coll" ).html( data.today_collection+" "+unit );
                $( ".month_coll" ).html( data.month_collection+" "+unit );
                $( ".total_coll" ).html( data.total_collection+" "+unit );
            }else{
                $('.today_coll, .month_coll, .total_coll').html( " - "+unit );
            }

        }


        function update_total_amount(data=null){
            var address_id = btoa($(".top_address_drp").val());
            var service_id = btoa($(".top_service_drp").val());
            var doctor_id = '<?php echo base64_encode($doctor["id"]); ?>';
            $.ajax({
                type:'POST',
                url: baseUrl+"doctor/get_total_amount",
                data:{di:doctor_id,si:service_id,ai:address_id},
                beforeSend:function(){
                    $('.today_coll, .month_coll, .total_coll').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
                },
                success:function(data){

                    data = JSON.parse(data);
                    if(data.status==1){
                        set_amount_label(data.data);
                    }else{
                        set_amount_label();
                    }
                },
                error: function(){
                    set_amount_label();
                }
            });


        }

        update_total_amount();
        load_patient_list(0);


        function bind_confirm(){
            $('.close_btn, .cancel_btn, .pay_btn').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                title:'Are you sure?',
                placement:'bottom',
                popout:true,
                singleton:true,
                container: 'body'
            });
        }

        $(document).on('keydown', function(e){
            console.log(e.keyCode);
             if(e.ctrlKey && e.shiftKey){
                var obj = $("#setp_tab").find(".active");
                $(obj).prev('li').find('a').trigger('click');
            }else if(!e.ctrlKey && e.shiftKey){
                var obj = $("#setp_tab").find(".active");
                $(obj).next('li').find('a').trigger('click');
            }else if(e.keyCode ==107){
                $(".add_patient_btn").trigger('click');

            }
        } );

        setTimeout(function(){
            $("#printThis").show();
        },30);

    });
</script>

</html>







