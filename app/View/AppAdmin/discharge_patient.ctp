<?php
$login = $this->Session->read('Auth.User');

$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$ward_list =$this->AppAdmin->getHospitalWardList($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown();
?>

<?php  echo $this->Html->script(array('printThis.js')); ?>


<style>
    html {
        overflow: auto;
    }
    body {
        height: auto;
        min-height: 100%;
        overflow: visible;
    }
    .header{
        top: 0;
    }
</style>


<style>
    .print_content span{
        display: inline-block !important;
    }

    .div_first label, .div_second label{
        float: left !important;
        width: auto !important;
    }




    .div_first span, .div_second span{
        float: left !important;
        display: inline-block !important;
        width: fit-content !important;
    }


    .sub_detail{
        border-bottom: 1px solid;
    }
    .sub_detail label, .sub_detail span{
        width: 100%;
        display: block;
    }
    body, .form-control{
        color:#000;

    }
    textarea{
        height: 70px !important;
    }
    .print_content{overflow: visible;}
    .print_btn{
        float: right;
        position: relative;
        right: 20px;
        top: 20px;

    }
    .saprate_div{
        background: #9a989840;
        min-height: 170px;
    }

    .td_first{
        border-right: 2px solid;
    }

    .div_first{

    }
    .div_second{}

    .patient_form_group{
        margin-bottom: 0px;
        padding:0px;
        width: 100%;
        float:left;
    }
    .saprate_div h3{
        font-size: 14px;
        text-decoration: underline;
    }

    #signup{
        width: 100%;
    }

    div[class^="col-sm-"]{padding: 0px 1px;margin: 0px;   }
    .value_form div[class^="col-sm-"]{
        margin-bottom: 15px;
    }

    .form-control{marging-top: 10px; margin-bottom: 2px !important;}


    span{margin: 0px;padding: 0px;} label{margin-bottom: 0px !important;}
    .value_form .form-group{
        margin-bottom: 6px;
    }

    .value_form{
        padding-right: 3px;
        padding-left: 3px;
    }
    .value_form .form-group {

        margin-left: 0px;
    }
    .form-control {
        width: 100%;
        height: 30px;
        padding: 1px 3px;
    }
    .col-sm-3 {
        width: 20%;
    }
    .ui-autocomplete {
        max-width: 92%;
        max-height: 70px !important;
        overflow-y: auto !important;
        overflow-x: auto !important;
        padding-right: 20px !important;
    }
    .template_btn {
        float: left;
        position: relative;
        right: 20px;
        top: 20px;
    }
    .hide_a{
        cursor: pointer;
    }

    td{
        word-wrap: break-word;
        height: auto !important;
    }

    .sub_table label{
        text-align: left !important;
    }
    body{
        overflow: visible !important;
    }

</style>
<div class="Home-section-2">



    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">



                    <h3 class="screen_title">Discharge Patient Form</h3>
                    <?php //echo $this->element('app_admin_leftsidebar'); ?>

                    <button class="btn btn-xs btn-info template_btn">Eye Hospital Template</button>

                    <button class="btn btn-xs btn-info print_btn">Print Report</button>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box print_box">


                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('HospitalDischarge',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>


                        <table style="width: 100%;">
                            <thead>
                                <tr>
                                    <td>
                                        <div id="header_div" class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;margin: 0px;">

                                            <table style="width: 100%;border-bottom:2px solid;">
                                                <tr>
                                                    <td colspan="3">
                                                        <div style="float: left;width: 11%;"><img style="height: 38px; float: left; width: 38px;" src="<?php echo $this->request->data['Thinapp']['logo']; ?>" /></div>
                                                        <h3 style="font-size: 20px; float: left; margin:11px; width: 70%;text-align: center;"><?php $app =$this->request->data['Thinapp']; echo !empty($app['receipt_header_title'])?$app['receipt_header_title']:$app['name']; ?></h3>
                                                        <label style=" margin: 0px !important; ;padding: 0px !important; float: left; width: 15%;text-align: right;">Discharge Report</label>
                                                    </td>
                                                </tr>
                                                <tr style="background: #9a989840;">
                                                    <td  class="td_first saprate_div" style="width:55%;">
                                                        <h3>Patient Detail</h3>
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td valign="top" style="width: 26%;"><label>Patient Name :</label></td>
                                                                <td valign="top"><span><?php echo $patient_name; ?></span></td>
                                                                <td valign="top" style="width: 15%;"><label>Mobile :</label></td>
                                                                <td valign="top"><span><?php echo $this->request->data[$object]['mobile']; ?></span></td>
                                                            </tr>

                                                            <tr>
                                                                <td valign="top"> <label>Marital Status :</label></td>
                                                                <td valign="top"> <span><?php echo @$this->request->data[$object]['marital_status']; ?></span></td>
                                                                <td valign="top"> <label>DOB :</label></td>
                                                                <td valign="top"><span><?php
                                                                        $dob = date('d-m-Y',strtotime($this->request->data[$object]['dob']));
                                                                        echo ($dob!='01-01-1970' && $dob!='30-11--0001')?$dob:'';

                                                                        ?> &nbsp;</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><label>UHID :</label></td>
                                                                <td valign="top"><span><?php echo $this->request->data[$object]['uhid']; ?></span></td>
                                                                <td valign="top"><label>Gender :</label></td>
                                                                <td valign="top"><span><?php echo $this->request->data[$object]['gender']; ?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><label>Blood Group :</label></td>
                                                                <td valign="top"><span><?php echo $this->request->data[$object]['blood_group']; ?></span></td>
                                                                <td valign="top"> <label>Age :</label></td>
                                                                <td valign="top"><span><?php

                                                                        $dob = $this->request->data[$object]['dob'];
                                                                        $dob =  ($dob!='1970-01-01' && $dob!='0001-11-30')?$dob:'';
                                                                        if(!empty($this->request->data[$object]['age'])){
                                                                            echo @$this->request->data[$object]['age'];
                                                                        }else{
                                                                            if(!empty($dob)){
                                                                                echo $res = Custom::get_age_from_dob($dob);
                                                                            }
                                                                        }

                                                                       ?>


                                                                    </span></td>
                                                            </tr>
                                                            <tr>
                                                                <td valign="top"> <label>Address :</label></td>
                                                                <td valign="top" colspan="5"> <span><?php
                                                                        if($object=="Children"){
                                                                            if(empty($this->request->data[$object]['address'])){
                                                                                echo  $this->request->data[$object]['patient_address'];
                                                                            }else{
                                                                                echo $this->request->data[$object]['address'];
                                                                            }
                                                                        }else{
                                                                            echo $this->request->data[$object]['address'];
                                                                        }

                                                                        ?></span></td>

                                                            </tr>


                                                        </table>
                                                    </td>

                                                    <td style="width:45%" class="saprate_div">
                                                        <h3>IPD Detail</h3>
                                                        <table style="width: 100%;">
                                                            <tr>
                                                                <td valign="top" style="width: 25%;"><label>Consulting Doctor :  </label></td>
                                                                <td valign="top"><span><?php echo $ipd_data['AppointmentStaff']['name']; ?></span></td>
                                                            </tr>


                                                            <tr>
                                                                <td valign="top">
                                                                    <?php $admit_date =  date('d/m/Y h:i A',strtotime($ipd_data['HospitalIpd']['admit_date'])); ?>
                                                                    <label>Admit Date : </label>
                                                                </td>
                                                                <td valign="top">
                                                                    <span class="admit_date_lbl"><?php echo $admit_date; ?></span>
                                                                    <a class="edit_admin_date hide_a" data-id="<?php echo base64_encode($ipd_data['HospitalIpd']['id']); ?>"  ><i class="fa fa-pencil"></i> Edit </a>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td valign="top"><label>Ward :</label></td>
                                                                <td valign="top">
                                                                    <span class="ward_name_span"><?php echo $ipd_data['HospitalServiceCategory']['name']; ?></span>
                                                                    <a class="edit_ward hide_a" data-id="<?php echo base64_encode($ipd_data['HospitalIpd']['id']); ?>"  ><i class="fa fa-pencil"></i> Edit </a>

                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td valign="top"><label>IPD Num. : </label></td>
                                                                <td valign="top"><span><?php echo $ipd_data['HospitalIpd']['ipd_unique_id']; ?></span></td>
                                                            </tr>

                                                            <tr>
                                                                <td valign="top"> <label>Chief Complaint : </label></td>
                                                                <td valign="top">
                                                                    <span class="chief_box_lbl"><?php echo $ipd_data['HospitalIpd']['chief_complaint']; ?></span>
                                                                    <a class="edit_chief_complaint hide_a" data-id="<?php echo base64_encode($ipd_data['HospitalIpd']['id']); ?>"  ><i class="fa fa-pencil"></i> Edit </a>

                                                                </td>
                                                            </tr>


                                                        </table>
                                                    </td>
                                                </tr>


                                            </table>




                                        </div>
                                        
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="value_form col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                            <h2>Discharge Details</h2>
                                            <div class="form-group sub_detail">


                                                <table style="width: 100%; text-align: left;" class="sub_table" >
                                                    <tr>
                                                        <th style="width: 30%;"><label class="content_det">Consultant Doctor</label></th>
                                                        <th style="width: 22%;"> <label class="content_det">Surgery Date</label></th>
                                                        <th style="width: 22%;"><label class="content_det">Discharge Date</label></th>
                                                        <th style="width: 10%;"><label class="content_det">Pulse</label></th>
                                                        <th style="width: 10%;"><label class="content_det">B.P</label></th>
                                                        <th style="width: 6%;"> <label class="content_det">Temperature</label></th>

                                                    </tr>
                                                    <tr>
                                                        <td class="content_det" valign="top">           <?php echo $this->Form->input('appointment_staff_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select consultant.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Category','type'=>'select','label'=>false,'options'=>$doctor_list,'class'=>'form-control message_type cnt')); ?>
                                                        </td>
                                                        <td class="content_det" valign="top">
                                                            <?php  if(isset($this->request->data['HospitalDischarge']['surgery_date'])){
                                                                $dateVal =  @date('d/m/Y H:i A',strtotime($this->request->data['HospitalDischarge']['surgery_date']));
                                                            }
                                                            else
                                                            {
                                                                $dateVal = '';// date('d/m/Y H:i A');
                                                            }
                                                            ?>

                                                            <?php echo $this->Form->input('surgery_date',array('value'=>$dateVal,'onkeydown'=>"return false;",'type'=>'text','label'=>false,'class'=>'form-control surgery_date','oninvalid'=>"this.setCustomValidity('Please select surgery date.')",'oninput'=>"setCustomValidity('')")); ?>

                                                        </td>
                                                        <td class="content_det" valign="top">
                                                            <?php  if(isset($this->request->data['HospitalDischarge']['discharge_date'])){
                                                                $dateVal =  @date('d/m/Y H:i A',strtotime($this->request->data['HospitalDischarge']['discharge_date']));
                                                            }
                                                            else
                                                            {
                                                                $dateVal = '';// date('d/m/Y H:i A');
                                                            }
                                                            ?>
                                                            <?php echo $this->Form->input('discharge_date',array('value'=>$dateVal,'onkeydown'=>"return false;",'required'=>'required','type'=>'text','label'=>false,'class'=>'form-control discharge_date','oninvalid'=>"this.setCustomValidity('Please select discharge date.')",'oninput'=>"setCustomValidity('')")); ?>

                                                        </td>
                                                        <td class="content_det" valign="top">
                                                            <?php echo $this->Form->input('pulse',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                                        </td>
                                                        <td class="content_det" valign="top"> <?php echo $this->Form->input('blood_pressure',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                                        </td>
                                                        <td class="content_det" valign="top">
                                                            <?php echo $this->Form->input('temprature',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                                        </td>

                                                    </tr>
                                                </table>








                                            </div>
                                            <br>
                                            <br>

                                            <div class="form-group">

                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Drug Allergies :-</label>
                                                    <?php echo $this->Form->input('drug_allergies',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_1')); ?>
                                                </div>


                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Final Diagnosis :-</label>
                                                    <?php echo $this->Form->input('final_diagnosis',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_2')); ?>
                                                </div>

                                            </div>

                                            <div class="form-group">

                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Chief Complaints :-</label>
                                                    <?php echo $this->Form->input('chief_complaints',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_3')); ?>
                                                </div>


                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Past History :-</label>
                                                    <?php echo $this->Form->input('patient_history',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_4')); ?>
                                                </div>

                                            </div>


                                            <div class="form-group">

                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Clinical Examination :-</label>
                                                    <?php echo $this->Form->input('clinical_examination',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_5')); ?>
                                                </div>


                                                <div class="col-sm-12">
                                                    <label class="content_lbl">Investigation Details :-</label>
                                                    <?php echo $this->Form->input('investigation_detail',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_6')); ?>


                                                </div>

                                            </div>


                                            <div class="form-group">

                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Operative Notes :-</label>
                                                    <?php echo $this->Form->input('operative_notes',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_7')); ?>
                                                </div>


                                                <div class="col-sm-12">
                                                    <label class="content_lbl">Course In The Hospital :-</label>
                                                    <?php echo $this->Form->input('course_in_the_hospital',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_8')); ?>


                                                </div>

                                            </div>


                                            <div class="form-group">

                                                <div class="col-sm-12">


                                                    <label class="content_lbl">Treatment Given :-</label>
                                                    <?php echo $this->Form->input('treatment_given',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_9')); ?>


                                                </div>


                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Condition At Discharge :- </label>
                                                    <?php echo $this->Form->input('condition_at_discharge',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_10')); ?>
                                                </div>

                                            </div>

                                            <div class="form-group">

                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Discharge Advice :-</label>
                                                    <?php echo $this->Form->input('discharge_advice',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_11')); ?>
                                                </div>


                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Medication :-</label>
                                                    <?php echo $this->Form->input('medication',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_12')); ?>
                                                </div>

                                            </div>

                                            <div class="form-group">

                                                <div class="col-sm-12">

                                                    <label class="content_lbl">Follow Up :-</label>
                                                    <?php echo $this->Form->input('follow_up',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt','id'=>'textarea_13')); ?>
                                                </div>

                                                <div class="col-sm-4 save_btn_box" style="float: right; text-align: right;">

                                                    <label>&nbsp;</label>


                                                    <?php echo $this->Form->button('Save',array('div'=>false,'type'=>'submit','class'=>'btn btn-info','id'=>'save')); ?>
                                                    <button type="reset" class="btn-success btn" >Reset</button>
                                                    <button type="button" onclick="window.history.back();" class="btn btn-warning">Back</button>

                                                </div>

                                            </div>


                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>



                        <?php echo $this->Form->end(); ?>


                    </div>








                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>

    <div class="print_content" style="margin:0 auto; width: 297mm;height: 210mm;"></div>


</div>




<script>
    $(document).ready(function(){


        $(document).on("click",".edit_ward",function(){
            var ipd_id = $(this).attr('data-id');
            var $btn_loading = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/get_update_ward",
                data:{ipd_id:ipd_id},
                beforeSend:function(){
                    $btn_loading.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Loading...');
                },
                success:function(data){
                    $btn_loading.button('reset');
                    var html = $(data).filter('#update_ward_modal');
                    $(html).modal('show');
                },
                error: function(data){
                    $btn_loading.button('reset');
                    $.alert("Sorry something went wrong on server.");
                }
            });
        });



        $(document).on('click','.edit_admin_date',function(e){
            var obj = $(this);
            var string =    "<input onkeydown='return false' type='text' id='edit_date_box' class='form-control'><div id='date_container'></div>";
            var defaultDate = $(".admit_date_lbl").text();
            var ipd_id = $(obj).attr('data-id');
            var dialog = $.confirm({
                title: 'Edit Admint Date',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Date & Time</label> ' +
                    string +
                    '</div>',
                buttons: {
                    save: {
                        text: 'Save',
                        btnClass: 'btn-blue save_tag_btn',
                        action: function () {
                            var date  =$("#edit_date_box").val();
                            var btn = $(".save_tag_btn");
                            $.ajax({
                                type: 'POST',
                                url: "<?php echo Router::url('/app_admin/update_ipd',true); ?>",
                                data: {flag:'admit_date','date':date,id:ipd_id},
                                beforeSend: function () {
                                    btn.button({loadingText: 'Saving...'}).button('loading');
                                },
                                success: function (data) {
                                    btn.button('reset');
                                    data = JSON.parse(data);
                                    if(data.status==1){
                                        $(".admit_date_lbl").html(date);
                                        dialog.close();
                                    }else{
                                        $.alert(data.message);
                                    }
                                },
                                error: function (data) {
                                    btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");

                                }
                            });
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {

                    $("#edit_date_box").val(defaultDate);
                    $("#edit_date_box").closest(".jconfirm-content").height("360");
                    $("#edit_date_box").datetimepicker({ keepOpen: true,showClear: false,format:'DD/MM/YYYY hh:mm A',ignoreReadonly: false});
                    $("#edit_date_box").datetimepicker('show');

                }
            });

        });

        $(document).on('click','.edit_chief_complaint ',function(e){
            var obj = $(this);
            var string =    "<input type='text' id='edit_chief_box' class='form-control'>";
            var defaultComplain = $(".chief_box_lbl").text();
            var ipd_id = $(obj).attr('data-id');
            var dialog = $.confirm({
                title: 'Edit Chief Complaint',
                content: '' +
                    '<div class="form-group">' +
                    '<label>Chief Complaint</label> ' +
                    string +
                    '</div>',
                buttons: {
                    save: {
                        text: 'Save',
                        btnClass: 'btn-blue save_tag_btn',
                        action: function () {
                            var chief_complain  =$("#edit_chief_box").val();
                            var btn = $(".save_tag_btn");
                            $.ajax({
                                type: 'POST',
                                url: "<?php echo Router::url('/app_admin/update_ipd',true); ?>",
                                data: {flag:'chief_complain','cc':chief_complain,id:ipd_id},
                                beforeSend: function () {
                                    btn.button({loadingText: 'Saving...'}).button('loading');
                                },
                                success: function (data) {
                                    btn.button('reset');
                                    data = JSON.parse(data);
                                    if(data.status==1){
                                        $(".chief_box_lbl").html(chief_complain);
                                        dialog.close();
                                    }else{
                                        $.alert(data.message);
                                    }
                                },
                                error: function (data) {
                                    btn.button('reset');
                                    $.alert("Sorry something went wrong on server.");

                                }
                            });
                            return false;
                        }
                    },
                    cancel: function () {
                        //close
                    },
                },
                onContentReady: function () {

                    $("#edit_chief_box").val(defaultComplain);


                }
            });

        });

        $('textarea[id^="textarea_"]').each(function(){
            CKEDITOR.replace( $(this).attr('id'),{
                toolbarGroups: [
                    { name: 'links', groups: [ 'links' ] },
                    { name: 'colors', groups: [ 'colors' ] },
                    {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
                    {name: 'styles', groups: ['styles']},
                    {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']}
                ],
                removeButtons:'Strike,Subscript,Superscript,BidiLtr,BidiRtl,Language,CopyFormatting,RemoveFormat',
                autoParagraph :false,
                enterMode : CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_P
            } );
        })

        $(".surgery_date").datetimepicker({showClear: true,format:'DD/MM/YYYY hh:mm A',ignoreReadonly: true});
        $(".discharge_date").datetimepicker({showClear: true,format:'DD/MM/YYYY hh:mm A',defaultDate:new Date(),ignoreReadonly: true});


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


        $("input,textarea").each(function(ind,elm){
            var name = $(elm).attr('name');
            var storageKey = "discharge_form."+name;
            var storageData = localStorage.getItem(storageKey);
            if(storageData != '' && storageData !== null)
            {
                storageData = JSON.parse(storageData);

                $( elm ).autocomplete({
                    source: storageData
                });

            }

        });


        $(document).on('submit','#sub_frm',function(e){
            e.preventDefault();

            var dataToSave = $(this).serializeArray();

            console.log(dataToSave);
            $.each(dataToSave,function(key,val){
                var storageKey = "discharge_form."+val.name;

                var storageData = localStorage.getItem(storageKey);
                console.log(storageData);
                if(storageData != '' && storageData !== null)
                {
                    storageData = JSON.parse(storageData);
                    storageData.push(val.value);
                    storageData = JSON.stringify(storageData);
                }
                else
                {
                    storageData = [];
                    storageData.push(val.value);
                    storageData = JSON.stringify(storageData);
                }
                console.log(storageData);
                localStorage.setItem(storageKey,storageData);
            });

            if(upload_file==true){
                $.alert('Please upload profile image');
                return false;
            }
            else
            {
                console.log("here");
                $(document).off('submit','#sub_frm');
                $('#sub_frm').submit();
            }

        });

        $(document).on('click','.print_btn',function(e){


            $('.print_content').show();
            var div ='<div style="display:block;width:100%;float:left; margin:0 auto !important;">'+$('.print_box').html()+"</div>";
            $('.print_content').html(div);

            $('.print_content textarea[id^="textarea_"]').each(function(){
                var text_id = $(this).attr('id');
                var desc = CKEDITOR.instances[text_id].getData();
                console.log(desc);
                if(desc==''){
                    $('.print_content #'+text_id).closest('.col-sm-12').remove();
                }else{
                    $('.print_content #'+text_id).closest('.textarea').html(desc);
                }

            })
            $('.print_content .hide_a').remove();
            $('.print_content').find('.save_btn_box').remove();
            $('.print_content').find('iframe').remove();
            $('.print_content').find('#successMessage').remove();

            $( '.print_content .input input, .print_content .input select, .print_content .input textarea' ).each(function(index){
                var val = $(this).val();
                if(this.nodeName=='SELECT'){
                    val = $(this).find('option:selected').text();
                }
                $(this).replaceWith("<span>"+val+"</span>");
            });

            //$('.print_content').makeCssInline();

            var myStyle = "<style media='print'> @media print{ .content_det{font-size:14px !important;} td.td_first{border-right:2px solid !important;} *{word-wrap: break-word !important;}   span{ overflow: visible !important; -webkit-print-color-adjust: exact !important; colour:red !important;} .content_lbl{ font-size:17px !important; font-weight:600;display:block !important; width:100% !important;} label{ font-size:17px !important;  }  body, html, #wrapper{ font-size:12px !important; overflow: visible !important;  margin: 0px auto !important; width: 100%; height: 100%; } div[class^='col-sm-']{padding: 0px 1px;margin: 0px;line-height: 1 !important;}  .saprate_div label{font-size:12px !important;font-weight:600;} .saprate_div span{ font-size:12px !important; overflow:visible !important; } } body,html{  overflow: visible !important; margin: 0px !important; width: 100%; height: 100%; } div[class^='col-sm-'] label{ padding-right: 3px!important;}   </style></head>"+$('.print_content').html();
            var iDiv = document.createElement('div');
            iDiv.id = 'final_div';
            iDiv.innerHTML = myStyle;
            $(iDiv).printThis({              // show the iframe for debugging
                importCSS: false,            // import page CSS
                importStyle: true,         // import style tags
                pageTitle: "",              // add title to print page
                removeInline: false,        // remove all inline styles from print elements
                printDelay: 333,            // variable print delay; depending on complexity a higher value may be necessary
                header: null,               // prefix to html
                footer: null,               // postfix to html
                base: false ,               // preserve the BASE tag, or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: false,              // copy canvas elements (experimental)
                doctypeString: " ",       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false       // copy classes from the html & body tag
            });


            $('.print_content').hide();




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




        $.extend($.fn, {
            makeCssInline: function() {
                this.each(function(idx, el) {
                    var style = el.style;
                    var properties = [];
                    for(var property in style) {
                        if($(this).css(property)) {
                            properties.push(property + ':' + $(this).css(property));
                        }
                    }
                    this.style.cssText = properties.join(';');
                    $(this).children().makeCssInline();
                });
            }
        });


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

        });
    });

    $(document).on("click",".template_btn",function(e){
        e.preventDefault();
        window.location = baseurl+"app_admin/get_discharge_eye_template/<?php echo $ipd_id;?>/<?php echo $discharge_id; ?>";
    });

</script>





