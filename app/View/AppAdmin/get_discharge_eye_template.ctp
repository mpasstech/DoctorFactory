<?php
$login = $this->Session->read('Auth.User');

$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$ward_list =$this->AppAdmin->getHospitalWardList($login['User']['thinapp_id']);
$country_list =$this->AppAdmin->countryDropdown();
?>

<?php  echo $this->Html->script(array('printThis.js')); ?>

<div class="Home-section-2">



    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">



                    <h3 class="screen_title">Discharge Patient Form</h3>
                    <?php //echo $this->element('app_admin_leftsidebar'); ?>

                    <button class="btn btn-xs btn-info template_btn list_template_btn">List Template</button>

                    <select class="template_btn" style="margin-left: 10px;" id="template_input">
                        <option value="">Select Template</option>
                        <?php foreach($templateList AS $id => $name){ ?>
                            <option value="<?php echo $id; ?>" <?php echo ($templateID == $id)?'selected="selected"':''; ?>><?php echo $name; ?></option>
                        <?php } ?>
                    </select>

                    <button class="btn btn-xs btn-info print_btn">Print Report</button>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 custom_form_box print_box">

                        templateList
                        <?php echo $this->element('message'); ?>


                        <?php echo $this->Form->create('HospitalDischarge',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'sub_frm')); ?>



                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <div style="border-bottom: 2px solid #000;" class="form-group patient_form_group">

                                <div class="col-sm-12">

                                 

                                    <div class="col-sm-12">
                                        <div style="float: left;width: 11%;"><img style="height: 38px; float: left; width: 38px;" src="<?php echo $this->request->data['Thinapp']['logo']; ?>" /></div>
                                       <h3 style="font-size: 20px; float: left; margin:11px; width: 70%;text-align: center;"><?php $app =$this->request->data['Thinapp']; echo !empty($app['receipt_header_title'])?$app['receipt_header_title']:$app['name']; ?></h3>
                                       <label style=" margin: 0px !important; ;padding: 0px !important; float: left; width: 15%;text-align: right;">Discharge Report</label>
                                    </div>


                                </div>

                                <div class="col-sm-8 saprate_div div_first">
                                    <h3>Patient Detail</h3>
                                    <div class="col-sm-7" style="width: 66%;" >
                                        <label>Patient Name : </label>
                                        <span><?php echo $patient_name; ?></span>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Mobile : </label>
                                        <span><?php echo $this->request->data[$object]['mobile']; ?></span>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Marital Status : </label>
                                        <span><?php echo @$this->request->data[$object]['marital_status']; ?></span>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>DOB : </label>
                                        <span><?php
                                            $dob = date('d-m-Y',strtotime($this->request->data[$object]['dob']));
                                            echo ($dob!='01-01-1970' && $dob!='30-11--0001')?$dob:'';

                                            ?></span>
                                    </div>


                                    <div class="col-sm-4">
                                        <label>UHID : </label>
                                        <span><?php echo $this->request->data[$object]['uhid']; ?></span>
                                    </div>




                                    <div class="col-sm-4">
                                        <label>Gender : </label>
                                        <span><?php echo $this->request->data[$object]['gender']; ?></span>
                                    </div>

                                    <div class="col-sm-4">
                                        <label>Blood Group : </label>
                                        <span><?php echo $this->request->data[$object]['blood_group']; ?></span>
                                    </div>

                                    <div class="col-sm-3">
                                        <label>Age : </label>
                                        <span><?php echo @$this->request->data[$object]['age']; ?></span>
                                    </div>






                                    <div style="display: none;" class="col-sm-3">
                                        <label>E-mail : </label>
                                        <span><?php echo @$this->request->data[$object]['email']; ?></span>
                                    </div>


                                    <div class="col-sm-12">
                                        <label>Address : </label>
                                        <span><?php echo $this->request->data[$object]['address']; ?></span>
                                    </div>

                                </div>


                                <div class="col-sm-4 saprate_div div_second">
                                    <h3>IPD Detail</h3>
                                    <div class="col-sm-12">
                                        <label>Consultant Doctor : </label>
                                        <span><?php echo $ipd_data['AppointmentStaff']['name']; ?></span>
                                    </div>

                                    <div class="col-sm-12">
                                        <label>Admit Date : </label>
                                        <span><?php echo date('d-m-Y H:i',strtotime($ipd_data['HospitalIpd']['admit_date'])); ?></span>
                                    </div>

                                    <div class="col-sm-12">
                                        <label>Ward  : </label>
                                        <span><?php echo $ipd_data['HospitalServiceCategory']['name']; ?></span>
                                    </div>

                                    <div class="col-sm-12">
                                        <label>IPD Number : </label>
                                        <span><?php echo $ipd_data['HospitalIpd']['ipd_unique_id']; ?></span>
                                    </div>



                                    <div class="col-sm-12">
                                        <label>Chief Complaint : </label>
                                        <span><?php echo $ipd_data['HospitalIpd']['chief_complaint']; ?></span>
                                    </div>




                                </div>






                            </div>
                        </div>
                        <div class="value_form col-lg-12 col-md-12 col-sm-12 col-xs-12">


                            <h3>Discharge Details</h3>
                            <div class="form-group">


                                <div class="col-sm-2">
                                    <label>Consultant Doctor</label>
                                    <?php echo $this->Form->input('appointment_staff_id',array('required'=>'required','oninvalid'=>"this.setCustomValidity('Please select consultant.')",'oninput'=>"setCustomValidity('')" ,'empty'=>'Select Category','type'=>'select','label'=>false,'options'=>$doctor_list,'class'=>'form-control message_type cnt')); ?>
                                </div>
                                <div class="col-sm-2">
                                    <label>Surgery Date</label>
                                    <?php echo $this->Form->input('surgery_date',array('onkeydown'=>"return false;",'type'=>'text','label'=>false,'class'=>'form-control surgery_date','oninvalid'=>"this.setCustomValidity('Please select surgery date.')",'oninput'=>"setCustomValidity('')")); ?>
                                </div>
                                <div class="col-sm-2">
                                    <label>Discharge Date</label>
                                    <?php  if(isset($this->request->data['HospitalDischarge']['discharge_date'])){
                                       $dateVal =  @date('d/m/Y H:i A',strtotime($this->request->data['HospitalDischarge']['discharge_date']));
                                    }
                                    else
                                    {
                                        $dateVal = '';// date('d/m/Y H:i A');
                                    }
                                    ?>
                                    <?php echo $this->Form->input('discharge_date',array('value'=>$dateVal,'onkeydown'=>"return false;",'required'=>'required','type'=>'text','label'=>false,'class'=>'form-control discharge_date','oninvalid'=>"this.setCustomValidity('Please select discharge date.')",'oninput'=>"setCustomValidity('')")); ?>
                                </div>




                                <div class="col-sm-1">

                                    <label>Pulse</label>
                                    <?php echo $this->Form->input('pulse',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-1">
                                    <label>B.P</label>
                                    <?php echo $this->Form->input('blood_pressure',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-1" style="min-width:70px;">

                                    <label>Temprature</label>
                                    <?php echo $this->Form->input('temprature',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-3">
                                    <label>Consultation Referral</label>
                                    <?php echo $this->Form->input('consulation_referral',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-sm-2">
                                    <label>Rohini ID</label>
                                    <?php echo $this->Form->input('rohini_id',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-1">
                                    <label>Lama</label>
                                    <?php echo $this->Form->input('lama',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-1">
                                    <label>Death</label>
                                    <?php echo $this->Form->input('death',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>
                                <div class="col-sm-1">
                                    <label>MLC</label>
                                    <?php echo $this->Form->input('mlc',array('type'=>'text','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>

                                </div>


                            </div>


                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Drug Allergies :-</label>
                                    <?php echo $this->Form->input('drug_allergies',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">

                                    <label>Final Diagnosis :-</label>
                                    <?php echo $this->Form->input('final_diagnosis',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Chief Complaints :-</label>
                                    <?php echo $this->Form->input('chief_complaints',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">

                                    <label>Patient History :-</label>
                                    <?php echo $this->Form->input('patient_history',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>


                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Clinical Examination :-</label>
                                    <?php echo $this->Form->input('clinical_examination',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">
                                        <label>Investigation Details :-</label>
                                        <?php echo $this->Form->input('investigation_detail',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>


                                </div>

                            </div>





                            <div class="form-group">

                                <div class="col-sm-12">


                                    <label>Treatment Given :-</label>
                                    <?php echo $this->Form->input('treatment_given',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>


                                </div>


                                <div class="col-sm-12">

                                    <label>Condition At Discharge :- </label>
                                    <?php echo $this->Form->input('condition_at_discharge',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Discharge Advice :-</label>
                                    <?php echo $this->Form->input('discharge_advice',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="col-sm-12">

                                    <label>Medication :-</label>
                                    <?php echo $this->Form->input('medication',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                            </div>

                            <div class="form-group">

                                <div class="col-sm-12">

                                    <label>Follow Up :-</label>
                                    <?php echo $this->Form->input('follow_up',array('type'=>'textarea','placeholder'=>'','label'=>false,'class'=>'form-control cnt')); ?>
                                </div>

                                <div class="col-md-3 col-md-offset-10 save_btn_box">

                                    <label>&nbsp;</label>


                                    <?php if(in_array($login['USER_ROLE'],array('ADMIN','RECEPTIONIST'))){ ?>
                                        <?php echo $this->Form->button('Save',array('div'=>false,'type'=>'submit','class'=>'btn btn-info','id'=>'save')); ?>
                                        <button type="reset" class="btn-success btn" >Reset</button>
                                    <?php } ?>
                                    <button type="button" onclick="window.history.back();" class="btn btn-warning">Back</button>

                                </div>

                            </div>


                        </div>

                        <?php echo $this->Form->end(); ?>


                    </div>








                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>

    <div style="display: none;" class="print_content"></div>


</div>




<script>
    $(document).ready(function(){





        $(".surgery_date").datetimepicker({showClear: true,format:'DD/MM/YYYY hh:mm A',defaultDate:new Date(),ignoreReadonly: true});
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



            var div ='<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="height:100%;"><div style="height:100%;" class="container"><div style="height:100%;" class="row">'+$('.print_box').html()+"</div></div></div>";
            $('.print_content').html(div);
            $('.print_content').find('.save_btn_box').remove();
            $('.print_content').find('iframe').remove();



            $( '.print_content .input input, .print_content .input select, .print_content .input textarea' ).each(function(index){
                var val = $(this).val();
                if(this.nodeName=='SELECT'){
                    val = $(this).find('option:selected').text();
                }
                $(this).replaceWith("<span>"+val+"</span>");
            });


            $('.print_content').makeCssInline();

            var myStyle = "<style media='print'> @media print{ span{margin: 0px;padding: 0px;} label{margin-bottom: 0px !important;}  body, html, #wrapper{margin: 0px !important; width: 100%; height: 100%; } div[class^='col-sm-']{padding: 0px 1px;margin: 0px;line-height: 1 !important;} } body,html{font-size: 9px !important; margin: 0px !important; width: 100%; height: 100%; } div[class^='col-sm-'] label{font-size:12px !important; padding-right: 3px!important;} div[class^='col-sm-'] span{font-size:11px !important;} </style></head>"+$('.print_content').html();
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

    $(document).on("click",".list_template_btn",function(e){
        e.preventDefault();
        window.location = baseurl+"app_admin/list_discharge_eye_template";
    });

    $(document).on("change","#template_input",function(){
        var tempID = $(this).val();
        if(tempID != '')
        {
            window.location = baseurl+"app_admin/discharge_by_template/"+btoa(tempID)+"/<?php echo $ipd_id;?>/<?php echo $discharge_id; ?>";
        }

    });

</script>



<style>
    body, .form-control{
        color:#000;

    }
    textarea{
        height: 70px !important;
    }
    .print_content{height: 100%!important;overflow: visible;}
    .print_btn{
        float: right;
        position: relative;
        right: 20px;
        top: 20px;

    }
    .saprate_div{
        background: #9a989840;
        min-height: 157px;
        margin: 0px 2px;
        /*width: 49%;*/
        padding: 0px 6px;
    }

    .div_first{
        width: 58%;
        float: left;
        border-right: 1px solid;
        margin-right: 3px !important;
    }
    .div_second{width: 40%; float: left}

    .patient_form_group{
        margin-bottom: 0px;
        padding:0px;

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
</style>


