<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);

echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js','remote-list.min.js','jquery-ui.min.js'));
echo $this->Html->css(array('jquery-ui.min.css'));
?>


<style>

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
        height: 30px;
        padding: 0px 3px;
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
    .ms-sel-ctn input {

        margin-left: 5px;
        height: 27px;

    }
    .ms-sel-ctn {

        margin-left: 0px !important;

    }
    .ms-helper {
        display: none !important;
    }
</style>
<style>
    .paymentTypeSelect {

    }

    .span_left {
        float: right;
    }
    .close.removeRowAdd {
        margin-top: 35px;
    }
    .address_col{
        margin-top: 10px;
    }
    .ageInput {
        width: 30%;
        display: inline-block;
    }
</style>
<style>
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
</style>
<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">


            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title">Add Package Receipt</h3>
                    <button type="button" id="add_new_patient_btn" style="float: right;" class="btn btn-primary">Add New Patient</button>
                        <?php
                        echo $this->element('billing_inner_header');
                        ?>
                        <div class="col-lg-12 right_box_div">

                        <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPayAdd')); ?>

                        <div class="Social-login-box payment_bx">

                            <div class="row" style="border-bottom: 1px solid;">
                                <label class="min_head">Patient Detail</label>
                            <div class="form-group" >

                                <div class="col-md-3">
                                    <label>UHID:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" autofocus="true"  search="uhid" data-len="6" id="uhid_input" autocomplete="off" name="uhid_input" required="true" class="form-control paymentTypeSelect" placeholder="">
                                </div>
                                <div class="col-md-3">
                                    <label>Patient Name:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="patient_name_input" search="name" data-len="3" autocomplete="off" name="patient_name_input" required="true" class="form-control paymentTypeSelect" placeholder="">
                                </div>
                                <div class="col-md-2">
                                    <label>Mobile Number:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="mobile_input" search="mobile" data-len="5" autocomplete="off" name="mobile_input" required="true" class="form-control paymentTypeSelect" placeholder="">
                                </div>
                                <div class="col-md-2">
                                    <label>Receipt Date:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="receipt_date1" autocomplete="off" value="<?php echo date("d/m/Y"); ?>" name="receipt_date" required="true" class="form-control" placeholder="">
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
                                <div class="col-md-3">
                                    <label>Parent's Name:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="parents_name_span" autocomplete="off" name="parent_name" class="form-control" placeholder="">
                                </div>
                                <div class="col-md-3">
                                    <label>Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <input type="text" id="address_span" autocomplete="off" name="address" class="form-control" placeholder="">
                                </div>


                                <div class="col-md-3">
                                    <label>Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>

                                    <select name="appointment_staff_id" id="appointmentStaffID"  oninvalid="this.setCustomValidity('Please select consultant.')" oninput="setCustomValidity('')" required="true" class="form-control">
                                        <option value="">Please Select</option>
                                        <?php foreach($doctor_list AS $docID => $docName ){ ?>
                                            <option value="<?php echo $docID; ?>"><?php echo $docName; ?></option>
                                        <?php }?>
                                    </select>


                                </div>
                                <div class="col-md-3">
                                    <label>Doctor Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                    <select name="appointment_address_id" id="appointmentAddressID" required="true" class="form-control">
                                        <option value="">Please Select</option>
                                    </select>
                                </div>

                            </div>
                            </div>


                            <div class="clear"></div>

                            <div class="row">
                                <label class="min_head">Reference Detail</label>
                                <div class="form-group">
                                    <div class="col-md-3 col-md-offset-3">
                                        <label>Referred By(Name):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                        <input type="text" name="reffered_by_name" class="form-control" placeholder="Referred By(Name)">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Referred By(Mobile):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                                        <input type="text" class="form-control"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10" minlength="10" name="reffered_by_mobile" placeholder="Referred By(Mobile)">
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <div class="row" id="previousPackageListHolder">

                            </div>
                            <div class="clear"></div>


                            <div class="row" id="addNewReceipt">
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
        margin-top: 35px;
    }
    .address_col{
        margin-top: 10px;
    }
    .ms-sel-item {
        color: #000000 !important;
    }
</style>

<script>
    $(document).ready(function(){

        $("#receipt_date1").datepicker({clearBtn:true,dateFormat: 'dd/mm/yy',autoclose:true,orientation: "bottom auto"});
        $("#receipt_date1").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

        $("#AppointmentCustomerDob").datepicker({clearBtn:true,dateFormat: 'dd/mm/yy',autoclose:true,orientation: "bottom auto"});
        $("#AppointmentCustomerDob").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

        var uhidInputArr = {};
        var nameInputArr = {};
        var mobileInputArr = {};


        $("#appointmentAddressID").html("<option value=''>Please Select</option>");

        $(document).on('change','#p_type_id',function(e){
            $('#p_type_name').val($('#p_type_id option:selected').text());
        });

        function getPreviousPackageList(uhid){
            var appointmentStaffID = $("#appointmentStaffID").val();
            var appointmentAddressID = $("#appointmentAddressID").val();
            if(!(appointmentStaffID > 0))
            {
                $("#appointmentStaffID").focus();
                $("#previousPackageListHolder").html('');
                $("#addNewReceipt").html('');
            }
            else if(!(appointmentAddressID > 0))
            {
                $("#appointmentAddressID").focus();
                $("#previousPackageListHolder").html('');
                $("#addNewReceipt").html('');
            }
            else
            {
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_previous_package_list",
                    data:{auto_patient_type:$("#auto_patient_type").val(),uhid:uhid,appointmentStaffID:appointmentStaffID,appointmentAddressID:appointmentAddressID},
                    beforeSend:function(){
                    },
                    success:function(data){
                        $("#previousPackageListHolder").html(data);
                        $("#addNewReceipt").html('');
                    },
                    error: function(data){
                    }
                });
            }
        }

        $( "#uhid_input, #patient_name_input, #mobile_input" ).each(function (index) {

            var search = $(this).attr('search');
            var length = $(this).attr('data-len');
            var element = $(this);
            $( this ).autocomplete({
                source:baseurl+"app_admin/patient_input_search?search="+search,
                minLength: length,
                select: function( event, ui ) {
                    var selected = ui.item;
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
                    return false;
                }
            });


        });

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
            $("#previousPackageListHolder").html('');
            $("#addNewReceipt").html('');
        });

        $(document).on("click",'.add_more_package',function(){
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/add_more_package",
                beforeSend:function(){
                },
                success:function(data){
                    $("#addNewReceipt").html(data);
                },
                error: function(data){
                }
            });
        });

        $(document).on('change','#appointmentStaffID',function(e){
            $("#appointmentAddressID").html('');
            $("#previousPackageListHolder").html('');
            $("#addNewReceipt").html('');
            var docID =$(this).val();
            if(docID){
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/get_doc_address_list",
                    data:{docID:docID},
                    beforeSend:function(){
                    },
                    success:function(data){
                        $("#appointmentAddressID").html(data);
                        var uhid = $("#uhid_input").val();
                        if(uhid > 0)
                        {
                            getPreviousPackageList(uhid);
                        }
                        else
                        {
                            $("#uhid_input").focus();
                            $("#previousPackageListHolder").html('');
                            $("#addNewReceipt").html('');
                        }

                    },
                    error: function(data){
                    }
                });
            }
            else
            {
                $("#previousPackageListHolder").html('');
                $("#addNewReceipt").html('');
            }
        });

        $(document).on('change','#appointmentAddressID',function(e){
            var uhid = $("#uhid_input").val();
            if(uhid > 0)
            {
                getPreviousPackageList(uhid);
            }
            else
            {
                $("#uhid_input").focus();
                $("#previousPackageListHolder").html('');
                $("#addNewReceipt").html('');
            }
        });

        $(document).on('click','.close_this',function(){
            var conf = confirm("Do you want to close this package?");
            if(conf)
            {
                var packageID = $(this).attr('data-id');
                var $btn = $(this);
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/close_package",
                    data:{packageID:packageID},
                    beforeSend:function(){
                        $btn.attr('disabled',true);
                    },
                    success:function(data){
                        data = JSON.parse(data);
                        if(data.status == 1)
                        {
                            var uhid = $("#uhid_input").val();
                            if(uhid > 0)
                            {
                                getPreviousPackageList(uhid);
                            }
                            else
                            {
                                $("#uhid_input").focus();
                                $("#previousPackageListHolder").html('');
                                $("#addNewReceipt").html('');
                            }
                        }
                        else
                        {
                            $btn.attr('disabled',false);
                            alert(data.message);
                        }

                    },
                    error: function(data){
                        $btn.attr('disabled',false);
                        alert("Sorry something went wrong on server.");
                    }
                });

            }
        });

        $(document).on('click','.select_this',function(){
            var packageID = $(this).attr('data-id');
            var $btn = $(this);
            $.ajax({
                type:'POST',
                url: baseurl+"app_admin/get_add_package_receipt",
                data:{packageID:packageID},
                beforeSend:function(){
                    $btn.attr('disabled',true);
                },
                success:function(data){

                    $('#addNewReceipt').html(data);
                    $btn.attr('disabled',false);

                },
                error: function(data){
                    $btn.attr('disabled',false);
                    alert("Sorry something went wrong on server.");
                }
            });

        });

        $(document).on('submit','#formPayAdd',function(e){
            e.preventDefault();
            e.stopPropagation();
            var dataArr = $( this ).serializeArray();
            if(dataArr[dataArr.length - 2].name == 'add_package')
            {
                var data = $( this ).serialize();
                var $btn = $(".add_more_package_button");

                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/add_patient_to_package",
                    data:data,
                    beforeSend:function(){
                        $btn.attr('disabled',true);
                    },
                    success:function(data){
                        data = JSON.parse(data);
                        if(data.status == 1)
                        {
                            var uhid = $("#uhid_input").val();
                            if(uhid > 0)
                            {
                                getPreviousPackageList(uhid);
                            }
                            else
                            {
                                $("#uhid_input").focus();
                                $("#previousPackageListHolder").html('');
                                $("#addNewReceipt").html('');
                            }
                        }
                        else
                        {
                            $btn.attr('disabled',false);
                            alert(data.message);
                        }
                    },
                    error: function(data){
                        $btn.attr('disabled',false);
                        alert("Sorry something went wrong on server.");
                    }
                });
            }
            else
            {

                var data = $( this ).serialize();
                var $btn = $("#addPaySubmitAdd");

                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/add_package_receipt_ajax",
                    data:data,
                    beforeSend:function(){
                        $btn.attr('disabled',true);
                    },
                    success:function(data){



                        var response = JSON.parse(data)
                        if(response.status == 1)
                        {
                            var uhid = $("#uhid_input").val();
                            if(uhid > 0)
                            {
                                getPreviousPackageList(uhid);
                            }
                            else
                            {
                                $("#uhid_input").focus();
                                $("#previousPackageListHolder").html('');
                                $("#addNewReceipt").html('');
                            }

                            var ID = btoa(response.receipt_id);
                            window.open(baseurl+"app_admin/print_invoice/"+ID+"/IPD","_blank");
                            location.reload();

                        }
                        else
                        {
                            $btn.attr('disabled',false);
                            alert("Sorry something went wrong on server.");
                        }


                    },
                    error: function(data){
                        $btn.attr('disabled',false);
                        alert("Sorry something went wrong on server.");
                    }
                });

            }



        });

        $(document).on('change','#p_type_id',function(e){
            $('#p_type_name').val($('#p_type_id option:selected').text());
        });

        $(document).on('input','#amount_holder',function(){
            var amount = $(this).val();
            if(amount > 0)
            {
                var taxRate = $("#taxRate").val();
                if(taxRate > 0)
                {
                    var taxVal = parseFloat(amount,2)*(parseFloat(taxRate,2)/100);
                    $("#taxValue").val(parseFloat(taxVal,2));
                    $(".total_lbl").val(parseFloat(taxVal,2)+parseFloat(amount,2));
                }
                else
                {
                    $(".total_lbl").val(parseFloat(amount,2));
                }
            }
            else
            {
                $("#taxValue").val(0);
                $(".total_lbl").val(0);
            }

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
                    var response = JSON.parse(data)
                    if(response.status == 1)
                    {
                        $('#formAddNewPatient')[0].reset();
                        $("#myModalAddPatient").modal("hide");

                        $("#previousPackageListHolder").html('');
                        $("#addNewReceipt").html('');

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

    });
</script>



<style>
    .form-group .row{
        margin: 0px !important;

    }

    .item_div .input{
        padding-right: 0px;
        padding-left: 5px;
    }
    .item_div input{
        padding: 6px;
    }

</style>