<?php
$login = $this->Session->read('Auth.User');
$show_referrer_on_receipt = $this->Session->read('Auth.User.Thinapp.show_referrer_on_receipt');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
$address_list =$this->AppAdmin->get_app_address($login['User']['thinapp_id']);
$medicalProductData = $this->AppAdmin->get_app_medical_product_list($login['User']['thinapp_id']);
$refferer_list =$this->AppAdmin->get_refferer_name_list($login['User']['thinapp_id']);
echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js'/*,'jquery-ui.min.js'*/));
//echo $this->Html->css(array('jquery-ui.min.css'));
$country_list =$this->AppAdmin->countryDropdown(true);

$show_days_on_receipt = !empty($login['Thinapp']['show_number_of_days_on_receipt'])?$login['Thinapp']['show_number_of_days_on_receipt']:'NO';


?>

<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>


<style>


    #ui-id-1{
        min-height: 150px !important;
    }

    #ui-id-2,#ui-id-3,#ui-id-4  {
        width: 700px !important;

    }

    div[class^="col-md"] {
        padding: 0px 4px !important;
    }
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
    .row{
        margin-left: 0px;
        margin-right: 0px;
    }
    .form-control, .btn{
        height: 30px ;
        padding: 0px 3px !important;
    }
    .search_main{
        margin-top: 28px;
        padding: 0px 0px;
        border-bottom: 1px solid;
    }

    .search_main input{
        padding: 6px !important;
    }

    .search_main .form-group div{
        margin-left: 1px !important;
        margin-right: 1px !important;
        padding:0px;
    }
    .action_btn{

        position: absolute;
        float: right;
        right: -35px;
    }
    .min_head{


        /* color: #508898; */
        font-size: 17px;

        text-align: center;
        font-weight: 600;
        background: #d6d6d6;
        width: 100%;
        margin: 10px 0px;
        position: relative;
        align-self: center;
    }

    #due_amount{
        width: 100%;
        float: left;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        margin: 0px 5px;


    }
    #pay_due_amount{

        width: 20px !important;
        height: 17px !important;
    }

    .label_box_row{
        margin-bottom: 15px;
    }
    .due_amount{
        text-align: center;
        /* padding: 0px 10%; */
        display: block;
        width: 100%;
        clear: both;
        margin: 0 auto;

        float: left;
        padding-left: 38%;
        font-size: 10px;
    }

    .show_due_lbl{
        width: 100%;
        text-align: center;
        font-size: 18px;
    }

</style>
<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding: 0;margin: 0;">
        <div class="container" style="width: 100%;">


            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Add Receipt</h3>
                        <button type="button" id="add_new_patient_btn" style="float: right;" class="btn btn-primary">Add New Patient</button>
                        <?php
                        echo $this->element('billing_inner_header');
                        ?>
                        <div class="col-lg-12 right_box_div">

                        <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPayAdd')); ?>

                        <div class="Social-login-box">

                            <div class="row" style="border-bottom: 1px solid;">
                                <label class="min_head">Patient Detail</label>
                            <div class="form-group" >

                                <div class="col-md-2">
                                    <label>UHID:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" autofocus="true"  search="uhid" data-len="6" id="uhid_input" autocomplete="off" name="uhid_input" required="true" class="form-control paymentTypeSelect" placeholder="">
                                </div>
                                <div class="col-md-2">
                                    <label>Patient Name:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="patient_name_input" search="name" data-len="3" autocomplete="off" name="patient_name_input" required="true" class="form-control paymentTypeSelect" placeholder="">
                                </div>
                                <div class="col-md-2">
                                    <label>Mobile Number:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="mobile_input" search="mobile" data-len="5" autocomplete="off" name="mobile_input" required="true" class="form-control paymentTypeSelect" placeholder="">
                                </div>
                                <div class="col-md-2">
                                    <label>Receipt Date:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                <?php if($login['USER_ROLE'] =='RECEPTIONIST' || $login['USER_ROLE'] =='DOCTOR' || $login['USER_ROLE'] =='STAFF'){ ?>
                                    <input type="text" readonly="readonly" autocomplete="off" value="<?php echo date("d/m/Y"); ?>" name="receipt_date" required="true" class="form-control" placeholder="">
                                <?php }else{ ?>
                                    <input type="text" id="receipt_date1" autocomplete="off" value="<?php echo date("d/m/Y"); ?>" name="receipt_date" required="true" class="form-control" placeholder="">
                                <?php } ?>


                                </div>
                                <div class="col-md-1">
                                    <label>Age/DOB:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="DOB_span" autocomplete="off" name="dob" class="form-control" placeholder="" readonly="readonly">
                                </div>
                                <div class="col-md-1">
                                    <label>Gender:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <select id="gender_span" name="gender" class="form-control" >
                                        <option value="MALE">Male</option>
                                        <option value="FEMALE">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Parent's Name:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="parents_name_span" autocomplete="off" name="parent_name" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-3">
                                    <label>Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="address_span" autocomplete="off" name="address" class="form-control" placeholder="">
                                </div>


                                <div class="col-md-2">
                                    <label>Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <select name="appointment_staff_id" id="appointmentStaffID"   class="form-control">
                                        <option value="">Please Select</option>
                                        <?php foreach($doctor_list AS $docID => $docName ){ ?>
                                            <option value="<?php echo $docID; ?>"><?php echo $docName; ?></option>
                                        <?php }?>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label>Doctor Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <select name="appointment_address_id" id="appointmentAddressID" required="required" class="form-control">
                                        <option value="">Please Select</option>
                                        <?php if(!empty($address_list)){ foreach ($address_list as $address_id => $address){ ?>
                                            <option value="<?php echo $address_id;?>"><?php echo $address;?></option>
                                        <?php }} ?>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label>Referred By(Name):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" name="reffered_by_name" id="refferer_by_name" class="form-control" placeholder="Referred By(Name)">
                                </div>
                                <div class="col-md-2">
                                    <label>Referred By(Mobile):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="refferer_by_mobile" class="form-control"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10" minlength="10" name="reffered_by_mobile" placeholder="Referred By(Mobile)">
                                </div>

                            </div>
                            </div>


                            <div class="clear"></div>


                            <div class="row">
                                <label class="min_head">
                                    <span>Billing Detail</span>
                                    <select id="show_referrer">
                                        <option value="NO">NO</option>
                                        <option value="YES">YES</option>
                                    </select>
                                    <span class="referrer_lbl">Show Referrer Name On Receipt</span>
                                </label>

                            <div class="form-group payment_bx">

                                <div class="item_div addProductAppendAdd">

                                </div>

                                <div class="row" >
                                    <div class="input col-md-offset-11 col-md-1">
                                        <button type="button" id="addMoreAdd" class="btn btn-primary">Add More</button>
                                        <span class="subText">Press '+'</span>
                                    </div>
                                </div>

                            </div>
                            </div>
                            <div class="clear"></div>


                            <div class="row" style="border-top: 1px solid #e2dede;">
                            <div class="form-group">


                                <div class="col-md-12 label_box_row">
                                    <label class="show_due_lbl" id="due_amount"></label>
                                </div>


                                <div class="input text  col-md-2">
                                    <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_id','type'=>'select','label'=>'Select Payment Mode','empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['Thinapp']['id']),'class'=>'form-control cnt')); ?>
                                </div>


                                <div class="input text  col-md-2">
                                    <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_name','type'=>'hidden','label'=>false,'value'=>'CASH')); ?>
                                    <?php echo $this->Form->input('payment_description',array('id'=>'p_desc','type'=>'text','label'=>'Payment Description','class'=>'form-control cnt')); ?>
                                </div>

                                <div class="input text  col-md-2">
                                    <?php echo $this->Form->input('display',array('readonly'=>'readonly','id'=>'total_display','type'=>'text','label'=>'Total Amount','class'=>'form-control')); ?>
                                </div>

                                <div class="input text  col-md-2">
                                    <?php echo $this->Form->input('tot',array("autocomplete"=>"off",'id'=>'total_lbl','type'=>'text','label'=>'Paid Amount','class'=>'form-control','required'=>'required')); ?>
                                </div>

                                <div class="input text  col-md-2">
                                    <label>&nbsp;</label>


                                    <?php // echo $this->Form->input('id', array('type'=>'hidden', 'value'=>$customerID, 'label'=>false,'required'=>true,'class'=>'form-control ','id'=>"idContainerAdd")); ?>

                                    <input type="hidden" name="customerUHID" id="customerUHID" required>
                                    <?php echo $this->Form->submit('Save & Print Receipt',array('class'=>'btn btn-info','id'=>'addPaySubmitAdd')); ?>
                                </div>
                            </div>
                            </div>

                        </div>

                            <input type="hidden" id="auto_patient_type"  name="auto_patient_type" class="form-control">


                            <?php echo $this->Form->end(); ?>

                    </div>






                </div>
                <!-- box 1 -->


            </div>
            <!--box 2 -->

        </div>
    </div>
</div>


<div class="modal fade" id="myModalAddPatient" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add New Patient</h4>
            </div>

            <div class="modal-body">
                <?php echo $this->Form->create('AppointmentCustomer',array('type'=>'file','method'=>'post','class'=>'form-horizontal','id'=>'formAddNewPatient')); ?>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Name<span class="red">*</span></label>
                        <?php echo $this->Form->input('first_name',array('type'=>'text',"maxlength"=>"30",'id'=>'focusInput','list'=>'nameList','placeholder'=>'Name','label'=>false,'class'=>'form-control cnt','required'=>true)); ?>
                        <?php echo $this->Form->input('patient_type',array('type'=>'hidden','value'=>'CUSTOMER','label'=>false,'required'=>true)); ?>
                    </div>

                    <div class="col-sm-6">
                        <label>Mobile<span class="red">*</span></label>
                        <?php echo $this->Form->input('mobile',array('type'=>'text', "onkeyup"=>"if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')",'id'=>'mobile',"maxlength"=>"10","minlength"=>"10",'placeholder'=>"9999999999",'label'=>false,'class'=>'form-control cnt', 'required'=>"required")); ?>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Age</label><br>
                        <input max="150" min="0" placeholder="Year" class="form-control cnt ageInput" id="ageYear" type="number">
                        <input max="12" min="0" placeholder="Month" class="form-control cnt ageInput" id="ageMonth" type="number">
                        <input max="31" min="0" placeholder="Day" class="form-control cnt ageInput" id="ageDay" type="number">
                        <?php echo $this->Form->input('age',array('type'=>'hidden',"maxlength"=>"50",'placeholder'=>'Age','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Date Of Birth</label>
                        <?php echo $this->Form->input('dob',array('type'=>'text', 'pattern'=>"\d{1,2}/\d{1,2}/\d{4}","title"=>"Please enter valid DD/MM/YYYY formate date",'placeholder'=>'DD/MM/YYYY','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>


                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Gender</label>
                        <?php echo $this->Form->input('gender',array('type'=>'select','options'=>array('MALE'=>'Male','FEMALE'=>'Female'),'label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Email</label>
                        <?php echo $this->Form->input('email',array('type'=>'email',"maxlength"=>"50",'placeholder'=>'Email','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Address</label>
                        <?php echo $this->Form->input('address',array('type'=>'text',"maxlength"=>"100",'placeholder'=>'Address','list'=>'addressList','label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Marital Status</label>
                        <?php echo $this->Form->input('marital_status',array('type'=>'select',"options"=>array("MARRIED"=>"Married","UNMARRIED"=>"Unmarried"),'label'=>false,'class'=>'form-control cnt')); ?>

                    </div>

                </div>

                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Blood Group</label>
                        <?php echo $this->Form->input('blood_group',array('type'=>'select','options'=>array('N/A'=>'N/A','O+'=>'O+','A+'=>'A+','B+'=>'B+','AB+'=>'AB+','O-'=>'O-','A-'=>'A-','B-'=>'B-','AB-'=>'AB-'),'label'=>false,'class'=>'form-control cnt')); ?>

                    </div>
                    <div class="col-sm-6">
                        <label>Relation</label>
                        <div class="parent_holder">
                            <?php echo $this->Form->input('relation_prefix',array('type'=>'select','options'=>array('S/O'=>'S/O','D/O'=>'D/O','W/O'=>'W/O','C/O'=>'C/O'),'label'=>false,'class'=>'form-control cnt relation_prefix')); ?>
                            <?php echo $this->Form->input('parents_name',array('type'=>'text',"maxlength"=>"50",'placeholder'=>"Name",'label'=>false,'class'=>'form-control cnt parent_name_input')); ?>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <label>Country</label>
                        <?php echo $this->Form->input('country_id',array('type'=>'text','label'=>false,'class'=>'form-control country')); ?>
                    </div>
                    <div class="col-sm-6">
                        <?php $state_list =array();?>
                        <label>State <i class="fa fa-spinner fa-spin state_spin" style="display:none;"></i> </label>
                        <?php echo $this->Form->input('state_id',array('type'=>'text','label'=>false,'class'=>'form-control state')); ?>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <?php $city_list =array();?>
                        <label>City <i class="fa fa-spinner fa-spin city_spin" style="display:none;"></i></label>
                        <?php echo $this->Form->input('city_id',array('type'=>'text','label'=>false,'class'=>'form-control city')); ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <label>&nbsp;</label>
                        <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ5','id'=>"submitAddPatientBtn")); ?>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
            </div>

        </div>
    </div>
</div>

<style>
    .paymentTypeSelect {

    }


    .span_left {
        float: right;
    }
    .close.removeRowAdd {
        margin-top: 27px;
    }
    .address_col{
        margin-top: 10px;
    }
    .ageInput {
        width: 30%;
        display: inline-block;
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
    .ms-ctn .ms-sel-ctn {

        margin-left: unset;
        max-height: 26px;
        min-height: 26px;

    }
    #formPaySearch input, #formPaySearch select, #formPay input, #formPay select, #formPayAdd input, #formPayAdd select {

        padding: unset;
        width: 100%;

    }
    .ms-res-ctn .ms-res-item {

        line-height: unset;
        text-align: left;
        padding: 9px 5px;
        color: #666;
        cursor: pointer;

    }
    .ms-helper{ display:none !important; }
    .ms-sel-ctn .ms-sel-item{ color: #000000 !important;min-height: 24px; }


    .ms-close-btn {
        top: 0;
        position: absolute;
        right: 33px;
    }

    .referrer_lbl{
        float: right!important;
        font-size: 13px !important;
        margin: 3px!important;
        color: red;
        text-decoration: underline;
    }
    #show_referrer{
        width: 5% !important;
        float: right !important;
        margin: 2px 3px !important;
        font-size: 14px !important;

    }

    .due_amount_span{
        float: left;
        text-align: left;
        width: 50%;
    }
    .total_due_span{
        float: right;
        text-align: right;
        width: 50%;
    }


</style>



<script>
    $(document).ready(function(){
        var medicalProductData = <?php echo json_encode($medicalProductData,true); ?>;
        var msArr = [];

        <?php

        function js_str($s){
            return '"' . addcslashes($s, "\0..\37\"\\") . '"';
        }
        function js_array($array){
            $temp = array_map('js_str', $array);
            return '[' . implode(',', $temp) . ']';
        }
        echo 'var availableTags = ', js_array($refferer_list), ';';
        ?>
        $( "#refferer_by_name" ).autocomplete({
            source: availableTags,
            delay: 100,
            classes: {
                "ui-autocomplete": "highlight"
            },
            select: function( event, ui ) {
                event.preventDefault();
                var name = ui.item.value.split('-');
                $("#refferer_by_name").val(name[0]);
                if(name[1]){
                    $("#refferer_by_mobile").val(name[1]);
                }else{
                    $("#refferer_by_mobile").val('');
                }

            }
        });


        var total_address = $("#appointmentAddressID > option").length;
        if(total_address == 2){
            $("#appointmentAddressID")[0].selectedIndex = 1;
        }



        $("#show_referrer").val("<?php echo $show_referrer_on_receipt ?>");


        $(document).on('change','#show_referrer', function() {
             var value = $(this).val();

               $.ajax({
                type: 'POST',
                url: baseurl + "app_admin/change_referrer_status",
                data: {status:value},
                success: function (data) {
                },
                error: function (data) {
                    alert("Sorry something went wrong on server.");
                }
            });

        });




        $("#receipt_date1").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});

        /*$("#receipt_date1").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});*/

        $("#AppointmentCustomerDob").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});

        $("#AppointmentCustomerDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

        var uhidInputArr = {};

        var nameInputArr = {};

        var mobileInputArr = {};


        $(document).on('change','#p_type_id',function(e){
            $('#p_type_name').val($('#p_type_id option:selected').text());

            var paymentTypeID = $('#p_type_id').val();
            $("#paymentTypeDetailForm").trigger("reset");
            $(".payment_detail_input").hide();
            if(paymentTypeID == '<?php echo DEFAULT_GOOGLE_PAY; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("Google Pay");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_PHONE_PE; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("PhonePe");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_PAYTM; ?>')
            {
                $(".remark").show();
                $(".mobile_no").show();
                $(".txn_no").show();
                $(".holder_name").show();
                $(".modal-title-payment-type").text("Paytm");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_NEFT; ?>')
            {
                $(".remark").show();
                $(".bank_account").show();
                $(".beneficiary_name").show();
                $(".modal-title-payment-type").text("NEFT");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_DEBIT_CARD; ?>')
            {
                $(".remark").show();
                $(".card_no").show();
                $(".holder_name").show();
                $(".valid_upto").show();
                $(".transaction_id").show();
                $(".modal-title-payment-type").text("Debit Card");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }
            else if(paymentTypeID == '<?php echo DEFAULT_CREDIT_CARD; ?>')
            {
                $(".remark").show();
                $(".card_no").show();
                $(".holder_name").show();
                $(".valid_upto").show();
                $(".transaction_id").show();
                $(".modal-title-payment-type").text("Credit Card");
                $("#myModalPaymentTypeDetailAdd").modal("show");
            }

        });

        $(document).on('submit','#formPayAdd',function(e){
        e.preventDefault();
        var data = $( "#formPayAdd, #paymentTypeDetailForm" ).serialize();
        var $btn = $("#addPaySubmitAdd");
        var dataArr = $( this ).serializeArray();
        var submit = true;

            var total_empty_doctor = 0;
            $.each(dataArr, function (key, value) {
                if (value.name == 'biller_appointment_staff_id[]' && !value.value) {
                    total_empty_doctor++;
                }
            });


            if ( $("#refferer_by_name").val() == '' &&  total_empty_doctor > 0 && $("#appointmentStaffID").val() == ''  ) {
                $.alert("Either Select Doctor Or Enter Referred By(Name) ");
                submit = false;
            }

            if (submit === true) {
                $.each(dataArr, function (key, value) {
                    if (value.name == 'productID[]' && !(value.value > 0)) {
                        $.alert("Service name could not be empty!");
                        submit = false;
                    }
                });
            }



            if (submit == true) {
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/web_add_hospital_receipt",
                    data: data,
                    beforeSend: function () {
                        $btn.attr('disabled', true);
                    },
                    success: function (data) {
                        var response = JSON.parse(data)
                        if (response.status == 1) {
                            $btn.attr('disabled', false);
                            $('#formPayAdd')[0].reset();
                            $("#myModalFormAdd").modal("hide");
                            if ($(".doctor_drp").length > 0) {
                                $("#address").trigger('change');
                            } else {
                                $(".slot_date").trigger('changeDate');
                            }

                            var ID = btoa(response.receipt_id);
                            window.open(baseurl + "app_admin/print_invoice/" + ID + "/IPD", "_blank");
                            location.reload();

                        }
                        else {
                            $btn.attr('disabled', false);
                            alert("Sorry something went wrong on server.");
                        }


                    },
                    error: function (data) {
                        $btn.attr('disabled', false);
                        alert("Sorry something went wrong on server.");
                    }
                });
            }



    });



        $(document).on('change','#appointmentStaffID',function(e){
            var docID =$(this).val();
            $('select[name="biller_appointment_staff_id[]"]').val(docID);
        });


        $(document).on('change','select[name="biller_appointment_staff_id[]"]',function(e){

            $("#appointmentStaffID").val('');
        });


        $( "#uhid_input, #patient_name_input, #mobile_input" ).each(function (index) {
            var search = $(this).attr('search');
            var length = $(this).attr('data-len');
            var element = $(this);
            $( this ).autocomplete({
                source:baseurl+"app_admin/patient_input_search?search="+search,
                minLength: length,
                select: function( event, ui ) {
                    var selected = ui.item;
                    fill_box_values(selected);
                    return false;
                }
            });
        });

        function add_search_patient_value(search){
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/patient_input_search?search=UHID&single_search=YES&term="+search,
                success:function(data){
                    var response = JSON.parse(data);
                    if(response[0]){
                        fill_box_values(response[0]);
                    }

                },
                error: function(data){

                }
            });
        }
        var uhid = <?php echo isset($this->request->query['u'])?base64_decode($this->request->query['u']):'0'; ?>;
        if(uhid != 0){
            add_search_patient_value(uhid);
        }



        function fill_box_values(selected){
            $("#mobile_input").val(selected.mobile);
            $("#uhid_input").val(selected.uhid);
            $("#patient_name_input").val(selected.name);
            $("#DOB_span").val(selected.age);
            $("#gender_span").val(selected.gender);
            $("#address_span").val(selected.address);
            $("#customerUHID").val(selected.uhid);
            $("#parents_name_span").val(selected.parents_name);
            $("#auto_patient_type").val(selected.auto_patient_type);
            $('#appointmentStaffID').focus();
            var patient = atob(selected.auto_patient_type);
            var tmp = patient.split("-");
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/get_due_amount",
                data:{pi:btoa(tmp[1]),pt:tmp[0]},
                success:function(data){
                    $("#due_amount").attr('data-value',data);
                    updateTotal();
                },
                error: function(data){

                }
            });

        }

        $(document).on("input","#mobile_input, #patient_name_input, #uhid_input",function(){

            if($(this).attr('search') !='mobile'){
                $("#mobile_input").val('');
            }
            if($(this).attr('search') !='uhid'){
                $("#uhid_input").val('');
            }
            if($(this).attr('search') !='name'){
                $("#patient_name_input").val('');
            }

            $("#DOB_span").val('');
            $("#gender_span").val('');
            $("#address_span").val('');
            $("#customerUHID").val();
            $("#parents_name_span").val("");
            $("#auto_patient_type").val("");
        });

        $(document).on("click","#add_new_patient_btn",function(){
            $("#myModalAddPatient").modal("show");
            setTimeout(function(){ $("#focusInput").focus(); },1500);
        });

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

        $(document).on("submit","#formAddNewPatient",function(e){
            e.preventDefault();
            var data = $( this ).serialize();
            var $btn = $("#submitAddPatientBtn");

            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/add_patient_before_receipt",
                data:data,
                beforeSend:function(){
                    $btn.attr('disabled',true);
                },
                success:function(data){
                    $btn.attr('disabled',false);
                    var response = JSON.parse(data);
                    if(response.status == 1)
                    {
                        $('#formAddNewPatient')[0].reset();
                        $("#myModalAddPatient").modal("hide");

                        $("#patient_name_input").val(response.data.first_name);
                        $("#uhid_input").val(response.data.uhid);
                        $("#mobile_input").val(response.data.mobile);
                        $("#DOB_span").val(response.data.age);
                        $("#gender_span").val(response.data.gender);
                        $("#address_span").val(response.data.address);
                        $("#customerUHID").val(response.data.uhid);
                        $("#parents_name_span").val(response.data.parents_name);
                        $("#auto_patient_type").val(btoa('CUSTOMER-'+response.data.id));
                        $('#appointmentStaffID').focus();



                    }
                    else
                    {
                        alert("Sorry something went wrong on server.");
                    }


                },
                error: function(data){
                    $btn.attr('disabled',false);
                    alert("Sorry something went wrong on server.");
                }
            });
        });



        var dialog_obj =null;
        function triggerOnSelectProduct($this){
            if(!dialog_obj && $($this).find('input[name="quantity[]"]').attr('allow_add') != 'YES'){
                var quantity = $($this).find('input[name="quantity[]"]').val();
                var days = $($this).find('input[name="days[]"]').val();
                var availableQty = $($this).find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('qty');
                var sold = $($this).parent().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('sold');
                var totalUnsold = $($this).find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('total_qty');
                var avalQty = (availableQty - sold);
                if (totalUnsold != 'undefined' && avalQty != 'NaN') {
                    if ((parseInt(totalUnsold) < parseInt(quantity)) && (parseInt(availableQty) > 0)) {
                        var message = '';
                        if (parseInt(avalQty) < 1) {
                            message = "Product/service is out of stock.Total " + totalUnsold + " available. Are you sure you want to continue?";
                        }
                        else {
                            message = "Only " + avalQty + " quantity available of this product/service. Are you sure you want to continue?";
                        }
                        dialog_obj = $.confirm({
                            title: 'Quantity Over',
                            content: message,
                            type: 'yellow',
                            buttons: {
                                ok: {
                                    text: "Yes",
                                    btnClass: 'btn-success',
                                    keys: ['enter'],
                                    name:"ok",
                                    action: function(e){

                                        dialog_obj.close();
                                        $($this).find('input[name="quantity[]"]').attr('allow_add','YES');

                                    }
                                },
                                cancel:{
                                    text: "No",
                                    btnClass: 'btn-default',
                                    keys: ['enter'],
                                    name:"No",
                                    action: function(e){
                                        if(avalQty >= 1){
                                            $($this).find('input[name="quantity[]"]').val(avalQty);
                                        }

                                        dialog_obj.close();
                                    }
                                }
                            },
                            onDestroy:function(){
                                updateTotal();
                                dialog_obj = null;

                            }
                        });
                    }
                }else{
                    updateTotal();
                }
            }else{
                updateTotal();
            }
        }
        function clearSelection(ms,obj){
            $(obj).closest(".addedRowAdd").find('input[name="productID[]"]').val("");
            $(obj).closest(".addedRowAdd").find('input[name="price[]"]').val(0);
            $(obj).closest(".addedRowAdd").find('input[name="amount[]"]').val(0);
            $(obj).closest(".addedRowAdd").find('input[name="tax_type[]"]').val("");
            $(obj).closest(".addedRowAdd").find('input[name="tax_value[]"]').val(0);
            $(obj).closest(".addedRowAdd").find('input[name="discountType[]"]').val("");
            $(obj).closest(".addedRowAdd").find('input[name="quantity[]"]').val(1);
            $(obj).closest(".addedRowAdd").find('input[name="discount[]"]').val(0);
            ms.removeFromSelection(ms.getSelection(), true);
            updateTotal();
        }
        function addSuggestionToNewProduct(){
            var obj = $(".addedRowAdd:last").find('input[name="productName[]"]');
            var raw_obj = $(".addedRowAdd:last");
            var ms = $(obj).magicSuggest({
                allowFreeEntries:false,
                allowDuplicates:false,
                data:medicalProductData,
                maxDropHeight: 345,
                maxSelection: 1,
                required: true,
                noSuggestionText: '',
                useTabKey: true,
                selectFirst :true
            });
            msArr.push(ms);
            $(ms).off('selectionchange');
            $(ms).on('selectionchange', function(e,m){
                var IdArr = this.getSelection();
                if(IdArr.length > 0){
                    var allow_add_service= true;
                    $('input[name="productID[]"]').each(function (index,value) {
                        if($(this).val() == IdArr[0].id ){
                            allow_add_service = false;
                        }
                    });
                    if(allow_add_service===true){
                        var $this = $(this.input[0]).closest('.form-control');
                        $($this).parent().nextAll().find('input[name="amount[]"]').val(0);
                        $($this).next('input[name="productID[]"]').val(0);
                        $($this).parent().nextAll().find('input[name="price[]"]').val(0);
                        $($this).parent().nextAll().find('input[name="tax_type[]"]').val("No Tax");
                        $($this).parent().nextAll().find('input[name="tax_value[]"]').val(0);
                        $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly',true);
                        $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');

                        if (IdArr[0]) {

                            var dataArr = IdArr[0];
                            var productID = dataArr.id;



                            if ($($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly') {


                                var productPrice = dataArr.price;


                                var qtyList = dataArr.qty_data;
                                if (qtyList.length > 0) {
                                    var isAlreadySelected = false;
                                    var selecteQtyMrp = $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('mrp');
                                    var selecteQtyId = $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').val();


                                    var qtyOption = "";
                                    qtyList.forEach(function (item) {
                                        qtyOption += '<option value="' + item.id + '" mrp="' + item.mrp + '" qty="' + item.quantity + '" total_qty="' + item.total_qty + '" sold="' + item.sold + '">' + item.expiry + '</option>';
                                        if (item.id == selecteQtyId) {
                                            isAlreadySelected = true;
                                        }
                                    });

                                    if (isAlreadySelected == false) {
                                        productPrice = qtyList[0].mrp;
                                        $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html(qtyOption);
                                    }
                                    else {
                                        productPrice = selecteQtyMrp;
                                    }


                                }
                                else {
                                    $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');
                                }


                            }
                            else {
                                var productPrice = $($this).parent().nextAll().find('input[name="price[]"]').val();
                            }
                            var taxType = dataArr.tax_name;
                            var taxRate = dataArr.rate;
                            var isPriceEditable = dataArr.is_price_editable;
                            $($this).next('input[name="productID[]"]').val(productID);
                            if ($($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly') {
                                $($this).parent().nextAll().find('input[name="price[]"]').val(productPrice);
                            }
                            $($this).parent().nextAll().find('input[name="tax_type[]"]').val(taxType);
                            $($this).parent().nextAll().find('input[name="tax_value[]"]').val(taxRate);

                            var quantity = $($this).parent().nextAll().find('input[name="quantity[]"]').val();
                            var days = $($this).parent().nextAll().find('input[name="days[]"]').val();

                            var discount = $($this).parent().nextAll().find('input[name="discount[]"]').val();
                            var discountType = $($this).parent().nextAll().find('select[name="discountType[]"]').val();
                            var totalAmount = calculateValue(discount, quantity, productPrice, discountType, taxRate);


                            $($this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount*days);
                            if (isPriceEditable == 1) {
                                $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly', false);
                            }
                            else {
                                $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly', true);
                            }
                            triggerOnSelectProduct($this);
                            updateTotal();


                        }else{
                            triggerOnSelectProduct($this);
                            updateTotal();
                        }
                    }else{
                        $.alert('This service already added');
                        clearSelection(ms,raw_obj);
                    }
                }else{
                    clearSelection(ms,raw_obj);
                }
            });
            $(ms).off('blur');
            $(ms).on('blur', function(c,e){
                var selecteion = e.getSelection();
                if(!selecteion[0])
                {
                    e.empty();
                }
            });
            setTimeout(function(){
                $(".ms-sel-ctn").last().find('input').focus();
            },1000);


        }

        $(document).off('click','.removeRowAdd');
        $(document).on('click','.removeRowAdd',function () {
            $(this).parents('.addedRowAdd').remove();
            updateTotal();
        });

        var $show_days_on_receipt = "<?php echo $show_days_on_receipt; ?>";
        $(document).off('click','#addMoreAdd');
        $(document).on('click','#addMoreAdd',function () {
            var doctor = '<div class="col-md-2 width-15"><label>Select Doctor</label><select name="biller_appointment_staff_id[]" class="form-control"></select></div>';

            var display = ($show_days_on_receipt=='YES')?'block':'none';
            var day_html = '<div style="display:'+display+';" class="input number col-md-1 width-5"><label for="day">Days</label><input name="days[]" class="form-control" min="1" value="1" placeholder="" required="true" type="number"></div>';

            var htmlToAppend = '<div class="row addedRowAdd"><div class="input number width-18 col-md-2"><label for="opdCharge">Service/Product</label><input autocomplete="off" type="text" name="productName[]" class="form-control productNameAdd" placeholder="Service/Product" required="true"><input type="hidden" name="productID[]" required="required"></div><div class="input number col-md-1 width-5"><label for="opdCharge">Qty.</label><input name="quantity[]" class="form-control" min="1" value="1" placeholder="" required="true" type="number"></div>'+day_html+'<div class="input number width-5 col-md-1"><label for="opdCharge">Dis.</label><input name="discount[]" class="form-control" min="0" value="0" placeholder="" type="number"></div><div class="input number width-6 col-md-1"><label for="opdCharge">Dis. Type</label><select name="discountType[]" class="form-control"><option value="PERCENTAGE">Percentage</option><option value="AMOUNT">Amount</option></select></div><div class="input number col-md-1"><label for="opdCharge">Tax Type</label><input type="text" readonly="readonly" name="tax_type[]" class="form-control" value="0" required="true" placeholder="Tax Type"></div><div class="input number col-md-1"><label for="opdCharge">Tax Value</label><input type="text" readonly="readonly" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value"></div><div class="input number width-9 col-md-2"><label for="opdCharge">Expiry</label><select name="medical_product_quantity_id[]" class="form-control"><option value="">Select</option></select></div><div class="input number col-md-1"><label for="opdCharge">Price</label><input type="text" readonly="readonly" name="price[]" class="form-control" value="0" placeholder="Price"></div><div class="input number col-md-1"><label for="opdCharge">Amount</label><input type="text" readonly="readonly" name="amount[]" class="form-control" value="0" placeholder="Amount"></div>'+doctor+'<div class="input width-5 col-md-1"><button type="button" class="close removeRowAdd"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div></div>';
            $(".addProductAppendAdd").append(htmlToAppend).find('input[name="productName[]"]').last().focus();
            add_doctor_to_dropdown($(".addedRowAdd:last").find('select[name="biller_appointment_staff_id[]"]'));
            addSuggestionToNewProduct();

        });



        function add_doctor_to_dropdown(obj){

            var doctor = <?php echo json_encode($doctor_list); ?>;
            var option = "<option value=''>Select Doctor</option>";
            if(doctor){
                Object.keys(doctor).forEach(function(key){
                    option += "<option value ='"+key+"'>"+doctor[key]+"</option>";
                });
           }
           $(obj).html(option).val($("#appointmentStaffID").val());


        }

        $(document).off('keyup keypress blur change input',"input[name='days[]'], input[name='productID[]'], input[name='quantity[]'], input[name='discount[]'], input[name='price[]'], select[name='discountType[]']");
        $(document).on('keyup keypress blur change input',"input[name='days[]'], input[name='productID[]'], input[name='quantity[]'], input[name='discount[]'], input[name='price[]'], select[name='discountType[]']",function () {
            var row = $(this).closest('.addedRowAdd');
            triggerOnOtherElement(row);
            triggerOnSelectProduct(row);
        });


        function triggerOnOtherElement(form){
            $(form).find('input[name="productID[]"]').each(function() {
                var productID = $(this).val();
                if(productID > 0)
                {

                    var optionFound = false,
                        datalist = medicalProductData;
                    var selected;


                    $.each( datalist, function( key, value ) {
                        if(value.id == productID )
                        {
                            selected = value;
                            optionFound = true;
                            return false;
                        }
                    });


                    if (optionFound) {
                        var productID = selected.id;

                        if($(this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $(this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly'){


                            var productPrice = selected.price;


                            var qtyList = selected.qty_data;
                            if(qtyList.length > 0)
                            {
                                var isAlreadySelected = false;
                                var selecteQtyMrp = $(this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('mrp');
                                var selecteQtyId = $(this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').val();


                                var qtyOption = "";
                                qtyList.forEach(function(item){
                                    qtyOption += '<option value="'+item.id+'" mrp="'+item.mrp+'" qty="'+item.quantity+'" total_qty="'+item.total_qty+'" sold="'+item.sold+'">'+item.expiry+'</option>';
                                    if(item.id == selecteQtyId)
                                    {
                                        isAlreadySelected = true;
                                    }
                                });

                                if(isAlreadySelected == false)
                                {
                                    productPrice = qtyList[0].mrp;
                                    $(this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html(qtyOption);
                                }
                                else
                                {
                                    productPrice = selecteQtyMrp;
                                }


                            }
                            else
                            {
                                $(this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');
                            }

                        }
                        else
                        {
                            var productPrice = $(this).parent().nextAll().find('input[name="price[]"]').val();
                        }


                        var taxType = selected.tax_name;
                        var taxRate = selected.rate;
                        var isPriceEditable = selected.is_price_editable;

                        $(this).next('input[name="productID[]"]').val(productID);

                        if($(this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $(this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly'){
                            $(this).parent().nextAll().find('input[name="price[]"]').val(productPrice);
                        }


                        $(this).parent().nextAll().find('input[name="tax_type[]"]').val(taxType);
                        $(this).parent().nextAll().find('input[name="tax_value[]"]').val(taxRate);

                        var quantity = $(this).parent().nextAll().find('input[name="quantity[]"]').val();
                        var days = $(this).parent().nextAll().find('input[name="days[]"]').val();
                        var discount = $(this).parent().nextAll().find('input[name="discount[]"]').val();
                        var discountType = $(this).parent().nextAll().find('select[name="discountType[]"]').val();
                        var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                        $(this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount*days);
                        if(isPriceEditable == 1)
                        {
                            $(this).parent().nextAll().find('input[name="price[]"]').attr('readonly',false);
                        }
                        else
                        {
                            $(this).parent().nextAll().find('input[name="price[]"]').attr('readonly',true);
                        }

                    } else {
                        $(this).parent().nextAll().find('input[name="amount[]"]').val(0);
                        $(this).next('input[name="productID[]"]').val(0);
                        $(this).parent().nextAll().find('input[name="price[]"]').val(0);
                        $(this).parent().nextAll().find('input[name="tax_type[]"]').val("No Tax");
                        $(this).parent().nextAll().find('input[name="tax_value[]"]').val(0);
                        $(this).parent().nextAll().find('input[name="price[]"]').attr('readonly',true);
                        $(this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');
                    }

                }

                setTimeout(function(){updateTotal();},200);

            });
        }

        function updateTotal(){

            var total=0;
            $( "#formPayAdd input[name='amount[]']" ).each(function( index ) {

                var tmp = parseFloat($(this).val()).toString().split(".");
                if(tmp.length ==2){
                    tmp = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
                }
                total = parseFloat(total)+parseFloat(tmp);
                if(isNaN(total))
                {
                    total = 0;
                }
            });

           total = isNaN(total)?0:parseFloat(total);
            var due = parseFloat($("#due_amount").attr('data-value'));
           if(due){
               due = isNaN(due)?0:parseFloat(due);
           }else{
               due =0;
           }

            $('#total_display, #total_lbl').val(total+due);
            $("#total_lbl").trigger("input");


        }


        $(document).off('input','#total_lbl');
        $(document).on('input','#total_lbl',function () {
            var show_value = parseFloat($("#total_display").val()) - parseFloat($("#total_lbl").val());
            var tmp = show_value.toString().split(".");
            if(tmp.length ==2){
                show_value = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
            }
            if(parseFloat($("#total_display").val()) ==parseFloat($("#total_lbl").val())){
                show_value = 0;
            }else if(!show_value || show_value==""){
                show_value = parseFloat($("#total_display").val());
            }
            var due = parseFloat($("#due_amount").attr('data-value'));
            show_value = isNaN(show_value)?0:show_value;
            due = isNaN(due)?0:due;
            var tmp = due.toString().split(".");
            if(tmp.length ==2){
                due = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
            }

            $(".show_due_lbl").html("<span class='due_amount_span'>This patient's last due amount is : "+due+" Rs/-</span><span class='total_due_span'>Total due amount will be : "+show_value+" Rs/-</span>");

            if(due > 0){
                $(".due_amount_span").show();
            }else{
                $(".due_amount_span").hide();
            }

        });



        $(document).off('change','#pay_due_amount')
        $(document).on('change','#pay_due_amount',function(e){
            updateTotal();
        });





        $(document).off('click','.removeRow');
        $(document).on('click','.removeRow',function () {
            $(this).parents('.addedRowAdd').remove();
            updateTotal();
        });



        $(document).off('keypress');
        $(document).on('keypress',  function (e) {
            clickEvent(e);
        });



        $(document).off('keydown');
        $(document).on('keydown',  function (e) {
            var action = "<?php echo $this->request->params['action']; ?>";
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(keyCode == 9 && !$('.modal').is(':visible') && action == 'add_appointment'){
                var active_obj = $("#load_slot_div").find(".is-selected");
                $("#load_slot_div ul li a").removeClass("is-selected");
                if(e.shiftKey){
                    $(active_obj).closest("li").prev('li').find("a:first-child").addClass("is-selected").focus();
                }else{
                    $(active_obj).closest("li").next('li').find("a:first-child").addClass("is-selected").focus();
                }
            }
        });




          function clickEvent(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(keyCode==43){
                e.preventDefault();
                $("#addMoreAdd").trigger('click');
            }else if(keyCode == 45){
                e.preventDefault();
                $('.addProductAppendAdd').find('.addedRowAdd:last').remove();
                if($(".addProductAppendAdd").find('input[name="productName[]"]').length ==0){
                    $("#opdChargeAdd").focus();
                }else{
                    $(".addProductAppendAdd").find('input[name="productName[]"]').last().focus();
                }
                updateTotal();
            }
        }



        var base = "<?php echo Router::url('/app_admin/',true); ?>";


        $(document).off('keyup keypress blur change',"input[name='days[]'], input[name='productID[]'], input[name='quantity[]'], input[name='price[]'], input[name='discount[]'], select[name='discountType[]']");
        $(document).on('keyup keypress blur change',"input[name='days[]'], input[name='productID[]'], input[name='quantity[]'], input[name='price[]'], input[name='discount[]'], select[name='discountType[]']",function () {
            $("input[list]").trigger('change');
        });

        $("#addMoreAdd").trigger('click');


        $('#addPharmaModal, #addLabModal, #addServiceModal').on('hidden.bs.modal', function (e) {

            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/get_app_medical_product_list",
                beforeSend:function(){
                },
                success:function(data){
                    medicalProductData = JSON.parse(data);
                    msArr.forEach(function(msObj){
                        msObj.setData(medicalProductData);
                    });
                },
                error: function(data){

                }
            });
        });



    });
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


<style>
    .form-group .row{
        margin: 0px !important;

    }

    .ui-autocomplete {
        max-height: 200px;
        min-height: 70px;
        overflow-y: auto;   /* prevent horizontal scrollbar */
        overflow-x: hidden; /* add padding to account for vertical scrollbar */
        z-index:1000 !important;
    }



    .item_div .input{
        padding-right: 0px;
        padding-left: 5px;
    }
    .item_div input{
        padding: 6px;
    }

</style>

<div class="modal fade" id="myModalPaymentTypeDetailAdd" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <form method="POST" id="paymentTypeDetailForm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close closeMyModalPaymentTypeDetailAdd">&times;</button>
                    <h4 class="modal-title modal-title-payment-type" style="text-align: center;font-weight: bold;">Payment</h4>
                </div>
                <div class="modal-body">

                    <div class="row opd_row">
                        <div class="input number col-md-6 card_no payment_detail_input">
                            <label for="opdCharge">Card No.</label>
                            <input type="text" class="form-control" name="card_no">
                        </div>
                        <div class="input number col-md-6 holder_name payment_detail_input">
                            <label for="opdCharge">Holder Name</label>
                            <input type="text" class="form-control" name="holder_name">
                        </div>
                        <div class="input number col-md-6 valid_upto payment_detail_input">
                            <label for="opdCharge">Valid Upto</label>
                            <input type="text" class="form-control" name="valid_upto">
                        </div>
                        <div class="input number col-md-6 transaction_id payment_detail_input">
                            <label for="opdCharge">Transaction ID</label>
                            <input type="text" class="form-control" name="transaction_id">
                        </div>
                        <div class="input number col-md-6 bank_account payment_detail_input">
                            <label for="opdCharge">Bank Account</label>
                            <input type="text" class="form-control" name="bank_account">
                        </div>
                        <div class="input number col-md-6 beneficiary_name payment_detail_input">
                            <label for="opdCharge">Beneficiary Name</label>
                            <input type="text" class="form-control" name="beneficiary_name">
                        </div>
                        <div class="input number col-md-6 txn_no payment_detail_input">
                            <label for="opdCharge">Txn No.</label>
                            <input type="text" class="form-control" name="txn_no">
                        </div>
                        <div class="input number col-md-6 mobile_no payment_detail_input">
                            <label for="opdCharge">Mobile No</label>
                            <input type="text" class="form-control" name="mobile_no">
                        </div>
                        <div class="input number col-md-6 remark payment_detail_input">
                            <label for="opdCharge">Remark</label>
                            <input type="text" class="form-control" name="remark">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </form>
    </div>
</div>