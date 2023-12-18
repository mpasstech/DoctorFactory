<?php



echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js','jquery.zoom.min'));



?>


<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>



<style>

    .zoomImg{
        background: #fff;
    }

    .header{
        display: none;
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
        padding: 5px 0px;
    }
    div[class^="col-md"] {
        padding: 0px 4px !important;
    }
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

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
    .close {
        margin-top: 30px;
    }

    .paymentTypeSelect {
        width: 50%;
        float: right;
    }
    .row{
        margin-left: 0px;
        margin-right: 0px;
    }
    .form-control, .btn{
        height: 30px !important;
        padding: 0px 3px !important;
    }
    .dateContainer {
        width: 33% !important;
        float: right;
        margin-right: 30%;
    }


    #show_image_dialog .modal-body{
        padding: 0px;
    }

    #show_image_dialog .modal-content{

    }
    #show_image_dialog .modal-body img{
        width: 100%;
        overflow-y: scroll;
        height: 100%;
    }

    #show_image_dialog .close{
        position: absolute;
        background: #fff;
        width: 30px;
        height: 30px;
        z-index: 999999999;
        right: -12px;
        color: #000;
        opacity: 1;
        border: 1px solid #fff;
        border-radius: 48px;
        padding: 0px 0px;
        top: -14px;
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

    .window_box{
        margin: 0;
        padding: 6px;
        border: 1px solid #999898;
        border-bottom: none;
    }

    .main_title{
        text-align: center;
        font-size: 2.5rem;
        padding: 0;
        margin: 0;
    }
    .zoom {
        display:inline-block;
        position: relative;
        background: #fff;
    }

    /* magnifying glass icon */
    .zoom:after {
        content:'';
        display:block;
        width:33px;
        height:33px;
        position:absolute;
        top:0;
        right:0;
        background:url(icon.png);
    }

    .zoom img {
        display: block;
    }

    .zoom img::selection { background-color: transparent; }

</style>


<div class="middle-block">
    <!-- Heading -->


    <div class="window_box col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <div class="row zoom"  id="prescription">
            <img  style="width: 100%;" src="<?php echo $file_path; ?>">
        </div>

    </div>
    <div class="window_box col-lg-8 col-md-8 col-sm-8 col-xs-8 main_invoice_box">
        <div class="row">
            <h3 class="main_title"> Proforma Invoice</h3>
            <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPayAdd')); ?>
            <div class="form-group" style="border-bottom: 1px solid;">
                <label class="min_head">Patient Detail</label>
                <div class="row">
                    <div class="col-md-12">
                        <table style="width: 100%;">

                            <tr>
                                <td>
                                    <b>UHID :-</b>
                                    <?php $customerUHID = (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['uhid']:$patientData['Children']['uhid']; ?>
                                    <?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['uhid']:$patientData['Children']['uhid']; ?>

                                </td>
                                <td> <b>Patient Name :-</b><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['first_name']:$patientData['Children']['child_name']; ?></td>
                                <td><b>Age/DOB :-</b><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['age']:$patientData['Children']['dob']; ?></td>
                                <td> <b>Gender :-</b><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['gender']:$patientData['Children']['gender']; ?></td>
                            </tr>
                        </table>
                        <div style="display: none;">
                            <b>Receipt Date:&nbsp;&nbsp;&nbsp;&nbsp;</b>
                            <?php if($login['USER_ROLE'] =='RECEPTIONIST' || $login['USER_ROLE'] =='DOCTOR' || $login['USER_ROLE'] =='STAFF'){ ?>
                                <input type="text" readonly="readonly" autocomplete="off" value="<?php echo date("d/m/Y"); ?>" name="receipt_date" required="true" class="form-control dateContainer" placeholder="Receipt Date"><br>
                            <?php }else{ ?>
                                <input type="text" id="receipt_date" autocomplete="off" value="<?php echo date("d/m/Y"); ?>" name="receipt_date" required="true" class="form-control dateContainer" placeholder="Receipt Date"><br>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-md-4" style="display:none;" >
                        <b>Parent's Name:&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['parents_name']:$patientData['Children']['parents_name']; ?><br>
                        <b>Mobile Number:&nbsp;&nbsp;&nbsp;&nbsp;</b><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['mobile']:$patientData['Children']['mobile']; ?><br>
                    </div>
                </div>
            </div>
            <div class="clear"></div>

            <div class="form-group" style="display: none;" >
                <label class="min_head">Select Doctor</label>
                <div class="row" style="background: #dcdada38;">


                    <div class="col-md-3">
                        <label>Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                        <input type="text" name="appointment_staff_id" class="form-control" id="appointmentStaffID" value="<?php echo base64_decode($doctor_id); ?>">
                    </div>
                    <div class="col-md-3">
                        <label>Doctor Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>

                        <input type="text" name="appointment_address_id" class="form-control" id="appointmentAddressID" value="0">


                    </div>
                    <div class="col-md-3" style="display: none;">
                        <label>Reffered By(Name):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                        <input type="text" name="reffered_by_name" class="form-control" placeholder="Reffered By(Name)">
                    </div>
                    <div class="col-md-3" style="display: none;">
                        <label>Reffered By(Mobile):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                        <input type="text" class="form-control"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10" minlength="10" name="reffered_by_mobile" placeholder="Reffered By(Mobile)">
                    </div>


                </div>
            </div>

            <div class="clear"></div>


            <div class="form-group ">
                <label class="min_head">Add Medicine Detail</label>
                <div class="row">
                    <div class="input number col-md-3">
                        <label for="opdCharge">Medicine Name</label>
                        <input type="text" name="productName[]" autocomplete="off" class="form-control productNameAdd" placeholder="Service" required="true">
                        <input type="hidden" name="productID[]" required="true">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Qty.</label>
                        <input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Dis.</label>
                        <input name="discount[]" class="form-control" required="true" min="0" value="0" placeholder="Discount" type="number">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Dis. Type</label>
                        <select name="discountType[]" required="true" class="form-control">
                            <option value="PERCENTAGE">Percentage</option>
                            <option value="AMOUNT">Amount</option>
                        </select>
                    </div>

                    <div class="input number col-md-1">
                        <label for="opdCharge">Tax Type</label>
                        <input type="text" readonly="readonly" required="true" name="tax_type[]" class="form-control" value="No Tax" placeholder="Tax Type">
                    </div>

                    <div class="input number col-md-1">
                        <label for="opdCharge">Tax Value</label>
                        <input type="text" readonly="readonly" required="true" name="tax_value[]" class="form-control" value="0" placeholder="Tax Value">
                    </div>

                    <div class="input number col-md-1">
                        <label for="opdCharge">Expiry</label>
                        <select name="medical_product_quantity_id[]" class="form-control"><option value="">Select</option></select>
                    </div>

                    <div class="input number col-md-1">
                        <label for="opdCharge">Price</label>
                        <input type="text" readonly="readonly" required="true" name="price[]" class="form-control" value="0" placeholder="Price">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Amount</label>
                        <input type="text" readonly="readonly" required="true" name="amount[]" class="form-control" value="0" placeholder="Amount">
                    </div>
                    <div class="input col-md-1">
                        <!--button type="button" class="close removeRowAdd"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button-->
                    </div>
                </div>

                <div class="addProductAppendAdd">

                </div>

                <div class="row" >
                    <div class="input col-md-offset-11 col-md-1">
                        <button type="button" id="addMoreAdd" class="btn btn-primary">Add More</button>
                        <span class="subText">Press '+'</span>
                    </div>
                </div>

            </div>
            <div class="clear"></div>

            <div class="row" style="border-top: 1px solid #e4dbdb;">
                <div class="form-group" >

                    <div class="input text  col-md-2 col-xs-offset-3" style="display:none;">
                        <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_id','type'=>'select','label'=>false,'empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['Thinapp']['id']),'class'=>'form-control cnt','style'=>'display:none;')); ?>
                    </div>
                    <div class="input text  col-md-2" style="display: none;">
                        <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_name','hidden'=>'text','label'=>false,'value'=>'CASH')); ?>

                        <?php echo $this->Form->input('hospitalEmergencyID',array('type'=>'hidden','label'=>false,'value'=>'0')); ?>
                        <?php echo $this->Form->input('isEmergency',array('type'=>'hidden','label'=>false,'value'=>'NO')); ?>

                        <?php echo $this->Form->input('payment_description',array('id'=>'p_desc','type'=>'text','label'=>false,'class'=>'form-control cnt','style'=>'display:none;')); ?>
                    </div>

                    <div class="input text  col-md-2 col-xs-offset-3">
                        <?php echo $this->Form->input('tot',array('readonly'=>'readonly','type'=>'text','label'=>'Total Amount','class'=>'form-control total_lbl')); ?>
                    </div>
                    <div class="input text  col-md-2">
                        <label>&nbsp;</label>
                        <input type="hidden" name="customerUHID" value="<?php echo $customerUHID; ?>">
                        <?php echo $this->Form->submit('Save and Send',array('class'=>'btn btn-info','id'=>'addPaySubmitAdd')); ?>
                    </div>

                </div>
            </div>

            <?php echo $this->Form->end(); ?>
        </div>

    </div>

</div>

<div class="modal fade" id="show_image_dialog" role="dialog" style="overflow: scroll !important;">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<script>

    $(function(){




        $('#prescription').zoom();

        $("#appointmentAddressID").html("<option value=''>Please Select</option>");

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
            $.each(dataArr, function( key, value ) {
                if(value.name == 'productID[]' && !(value.value > 0))
                {
                    $.alert("Medicine name could not be empty!");
                    submit = false;
                }
            });
            if(submit == true)
            {
                if(confirm("Are you sure this proforma invoice will be send to patient?")){
                    var d = "<?php echo $doctor_id; ?>";
                    var pi = "<?php echo $proforma_invoice; ?>";
                    var li = "<?php echo base64_encode($pharmacist['id']); ?>";


                    var last_text = $btn.html();
                    $.ajax({
                        type:'POST',
                        url: baseurl+"app_admin/web_add_hospital_receipt/"+d+"/"+pi+"/"+li,
                        data:data,
                        beforeSend:function(){
                            $btn.html('Saving..');
                            $btn.attr('disabled',true);
                        },
                        success:function(data){
                            var response = JSON.parse(data)
                            if(response.status == 1)
                            {
                                $.alert("Proforma Invoice Send Successfully");
                                var t = "<?php echo base64_encode($thinappID); ?>";
                                window.location = (baseurl+"homes/pharmacist/"+t);

                            }
                            else
                            {
                                $btn.attr('disabled',false);
                                $btn.html(last_text);
                                alert("Sorry something went wrong on server.");
                            }


                        },
                        error: function(data){
                            $btn.attr('disabled',false);
                            $btn.text(last_text);
                            alert("Sorry something went wrong on server.");
                        }
                    });
                }

            }
        });

        function updateTotal(){
            var total =0;
            $( ".main_invoice_box input[name='amount[]']" ).each(function( index ) {

                var tmp = parseFloat($(this).val()).toString().split(".");
                if(tmp.length ==2){
                    tmp = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
                }
                total += parseFloat(tmp);

            });
            $('.total_lbl').val(total);
        }

        <?php $medicalProductData = $this->AppAdmin->get_app_medical_product_list($login['User']['thinapp_id']); ?>

        var ms = $('.productNameAdd').magicSuggest({
            allowFreeEntries:false,
            allowDuplicates:false,
            data:<?php echo json_encode($medicalProductData,true); ?>,
            maxDropHeight: 345,
            maxSelection: 1,
            required: true,
            noSuggestionText: 'No result matching the term {{query}}',
            useTabKey: true
        });

        $(ms).on('selectionchange', function(e,m){

            $this = $(this.input[0]).closest('.form-control');

            $($this).parent().nextAll().find('input[name="amount[]"]').val(0);
            $($this).next('input[name="productID[]"]').val(0);
            $($this).parent().nextAll().find('input[name="price[]"]').val(0);
            $($this).parent().nextAll().find('input[name="tax_type[]"]').val("No Tax");
            $($this).parent().nextAll().find('input[name="tax_value[]"]').val(0);
            $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly',true);
            $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');




            var IdArr = this.getSelection();
            if(IdArr[0])
            {

                var dataArr = IdArr[0];

                var productID = dataArr.id;

                if($($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly'){


                    var productPrice = dataArr.price;


                    var qtyList = dataArr.qty_data;
                    if(qtyList.length > 0)
                    {
                        var isAlreadySelected = false;
                        var selecteQtyMrp = $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('mrp');
                        var selecteQtyId = $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').val();


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
                            $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html(qtyOption);
                        }
                        else
                        {
                            productPrice = selecteQtyMrp;
                        }


                    }
                    else
                    {
                        $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');
                    }





                }
                else
                {
                    var productPrice = $($this).parent().nextAll().find('input[name="price[]"]').val();
                }


                var taxType = dataArr.tax_name;
                var taxRate = dataArr.rate;
                var isPriceEditable = dataArr.is_price_editable;

                $($this).next('input[name="productID[]"]').val(productID);

                if($($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly'){
                    $($this).parent().nextAll().find('input[name="price[]"]').val(productPrice);
                }


                $($this).parent().nextAll().find('input[name="tax_type[]"]').val(taxType);
                $($this).parent().nextAll().find('input[name="tax_value[]"]').val(taxRate);

                var quantity = $($this).parent().nextAll().find('input[name="quantity[]"]').val();
                var discount = $($this).parent().nextAll().find('input[name="discount[]"]').val();
                var discountType = $($this).parent().nextAll().find('select[name="discountType[]"]').val();
                var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                $($this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount);


                if(isPriceEditable == 1)
                {
                    $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly',false);
                }
                else
                {
                    $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly',true);
                }

                triggerOnSelectProduct($this);
            }

            setTimeout(function(){updateTotal();},500);

        });

        $(ms).on('blur', function(c,e){
            var selecteion = e.getSelection();
            if(!selecteion[0])
            {
                e.empty();
            }
        });

        $(document).on('click focus','.productNameAdd',function(){
            var ms = $(this).magicSuggest({
                allowFreeEntries:false,
                allowDuplicates:false,
                data:<?php echo json_encode($medicalProductData,true); ?>,
                maxDropHeight: 345,
                maxSelection: 1,
                required: true,
                noSuggestionText: 'No result matching the term {{query}}',
                useTabKey: true
            });
            $(ms).on('selectionchange', function(e,m){

                $this = $(this.input[0]).closest('.form-control');

                $($this).parent().nextAll().find('input[name="amount[]"]').val(0);
                $($this).next('input[name="productID[]"]').val(0);
                $($this).parent().nextAll().find('input[name="price[]"]').val(0);
                $($this).parent().nextAll().find('input[name="tax_type[]"]').val("No Tax");
                $($this).parent().nextAll().find('input[name="tax_value[]"]').val(0);
                $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly',true);
                $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');




                var IdArr = this.getSelection();
                if(IdArr[0])
                {

                    var dataArr = IdArr[0];

                    var productID = dataArr.id;

                    if($($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly'){


                        var productPrice = dataArr.price;


                        var qtyList = dataArr.qty_data;
                        if(qtyList.length > 0)
                        {
                            var isAlreadySelected = false;
                            var selecteQtyMrp = $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('mrp');
                            var selecteQtyId = $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').val();


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
                                $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html(qtyOption);
                            }
                            else
                            {
                                productPrice = selecteQtyMrp;
                            }


                        }
                        else
                        {
                            $($this).parent().nextAll().find('select[name="medical_product_quantity_id[]"]').html('<option value="">Select</option>');
                        }





                    }
                    else
                    {
                        var productPrice = $($this).parent().nextAll().find('input[name="price[]"]').val();
                    }


                    var taxType = dataArr.tax_name;
                    var taxRate = dataArr.rate;
                    var isPriceEditable = dataArr.is_price_editable;

                    $($this).next('input[name="productID[]"]').val(productID);

                    if($($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'true' || $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly') == 'readonly'){
                        $($this).parent().nextAll().find('input[name="price[]"]').val(productPrice);
                    }


                    $($this).parent().nextAll().find('input[name="tax_type[]"]').val(taxType);
                    $($this).parent().nextAll().find('input[name="tax_value[]"]').val(taxRate);

                    var quantity = $($this).parent().nextAll().find('input[name="quantity[]"]').val();
                    var discount = $($this).parent().nextAll().find('input[name="discount[]"]').val();
                    var discountType = $($this).parent().nextAll().find('select[name="discountType[]"]').val();
                    var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                    $($this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount);


                    if(isPriceEditable == 1)
                    {
                        $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly',false);
                    }
                    else
                    {
                        $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly',true);
                    }

                    triggerOnSelectProduct($this);
                }

                setTimeout(function(){updateTotal();},500);

            });
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
        });

        function triggerOnSelectProduct($this){
            setTimeout(function(){
                var quantity = $($this).parent().siblings().find('input[name="quantity[]"]').val();
            var availableQty = $($this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('qty');
            var sold = $($this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('sold');
            var totalUnsold = $($this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('total_qty');
            var avalQty = (availableQty - sold);
            if((parseInt(totalUnsold) < parseInt(quantity)) && (parseInt(availableQty) > 0))
            {
                if(parseInt(avalQty) < 1)
                {
                    $.alert("Product/service is out of stock.Total "+totalUnsold+" available.");
                }
                else
                {
                    $.alert("Only "+avalQty+" quantity available of this product/service.Total "+totalUnsold+" available.");
                }
            }
        },500);
        }

        function triggerOnOtherElement(form){
            $(form).find('input[name="productID[]"]').each(function() {
                var productID = $(this).val();
                if(productID > 0)
                {

                    var optionFound = false,
                        datalist = <?php echo json_encode($medicalProductData,true); ?>;
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
                        var discount = $(this).parent().nextAll().find('input[name="discount[]"]').val();
                        var discountType = $(this).parent().nextAll().find('select[name="discountType[]"]').val();
                        var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                        $(this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount);


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

                setTimeout(function(){updateTotal();},500);

            });
        }

        $(document).on('change input','select[name="medical_product_quantity_id[]"]',function () {


            if($(this).val() != '')
            {
                var productPrice = $(this).find(":selected").attr('mrp');

                $(this).parent().nextAll().find('input[name="price[]"]').val(productPrice);

                var quantity = $(this).parent().siblings().find('input[name="quantity[]"]').val();
                var discount = $(this).parent().siblings().find('input[name="discount[]"]').val();
                var discountType = $(this).parent().siblings().find('select[name="discountType[]"]').val();
                var taxRate = $(this).parent().siblings().find('input[name="tax_value[]"]').val();
                var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                $(this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount);
            }



        });

        $(document).on('input','input[name="productName[]"]',function(e){
            setTimeout(function(){
                e.stopImmediatePropagation();
            var quantity = $(this).parent().siblings().find('input[name="quantity[]"]').val();
            var availableQty = $(this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('qty');
            var sold = $(this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('sold');
            var totalUnsold = $(this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('total_qty');
            var avalQty = (availableQty - sold);
            if((parseInt(totalUnsold) < parseInt(quantity)) && (parseInt(availableQty) > 0))
            {
                if(parseInt(avalQty) < 1)
                {
                    $.alert("Product/service is out of stock.Total "+totalUnsold+" available.");
                }
                else
                {
                    $.alert("Only "+avalQty+" quantity available of this product/service.Total "+totalUnsold+" available.");
                }
            }
        },500);

        });

        $(document).on('input','input[name="quantity[]"]',function(e){
            e.stopImmediatePropagation();
            var quantity = $(this).val();
            var availableQty = $(this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('qty');
            var sold = $(this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('sold');
            var totalUnsold = $(this).parent().siblings().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('total_qty');
            var avalQty = (availableQty - sold);
            if((parseInt(totalUnsold) < parseInt(quantity)) && (parseInt(availableQty) > 0))
            {
                if(parseInt(avalQty) < 1)
                {
                    $.alert("Product/service is out of stock.Total "+totalUnsold+" available.");
                }
                else
                {
                    $.alert("Only "+avalQty+" quantity available of this product/service.Total "+totalUnsold+" available.");
                }
            }
        });

        $(document).on('input','select[name="medical_product_quantity_id[]"]',function(e){
            var quantity = $(this).parent().siblings().find('input[name="quantity[]"]').val();
            var availableQty = $(this).find(":selected").attr('qty');
            var sold = $(this).find(":selected").attr('sold');
            var totalUnsold = $(this).find(":selected").attr('total_qty');
            var avalQty = (availableQty - sold);
            if((parseInt(totalUnsold) < parseInt(quantity)) && (parseInt(availableQty) > 0))
            {
                if(parseInt(avalQty) < 1)
                {
                    $.alert("Product/service is out of stock.Total "+totalUnsold+" available.");
                }
                else
                {
                    $.alert("Only "+avalQty+" quantity available of this product/service.Total "+totalUnsold+" available.");
                }
            }
        });

        $(document).on('click','.removeRowAdd',function () {
            $(this).parents('.addedRowAdd').remove();
            updateTotal();
        });

        function addRow(){
            var htmlToAppend = '<div class="row addedRowAdd"><div class="input number col-md-3"><label for="opdCharge">Medicine Name</label><input type="text" name="productName[]" autocomplete="off" class="form-control productNameAdd" placeholder="Service" required="true"><input type="hidden" name="productID[]" required="true"></div><div class="input number col-md-1"><label for="opdCharge">Quantity</label><input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Dis.</label><input name="discount[]" class="form-control" min="0" value="0" placeholder="Discount" required="true" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Dis. Type</label><select name="discountType[]" required="true" class="form-control"><option value="PERCENTAGE">Percentage</option><option value="AMOUNT">Amount</option></select></div><div class="input number col-md-1"><label for="opdCharge">Tax Type</label><input type="text" readonly="readonly" name="tax_type[]" class="form-control" value="No Tax" required="true" placeholder="Tax Type"></div><div class="input number col-md-1"><label for="opdCharge">Tax Value</label><input type="text" readonly="readonly" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value"></div><div class="input number col-md-1"><label for="opdCharge">Expiry</label><select name="medical_product_quantity_id[]" class="form-control"><option value="">Select</option></select></div><div class="input number col-md-1"><label for="opdCharge">Price</label><input type="text" readonly="readonly" name="price[]" class="form-control" required="true" value="0" placeholder="Price"></div><div class="input number col-md-1"><label for="opdCharge">Amount</label><input type="text" readonly="readonly" name="amount[]" class="form-control" required="true" value="0" placeholder="Amount"></div><div class="input col-md-1"><button type="button" class="close removeRowAdd"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div></div>';
            $(".addProductAppendAdd").append(htmlToAppend).find('input[name="productName[]"]').last().focus();
        }

        $(document).on('click','#addMoreAdd',function () {
            addRow();
        });

        $(document).on('keyup keypress blur change',"input[name='productID[]'], input[name='quantity[]'], input[name='price[]'], input[name='discount[]'], select[name='discountType[]']",function (e) {
            var form = $(this).closest('form');
            triggerOnOtherElement(form);
        });

        $(document).on('keypress',  function (e) {

            clickEvent(e);

        });



        $(document).on('keypress, change  keyup','.main_invoice_box input, .main_invoice_box select',function (e) {
            updateTotal();

        });





        function clickEvent(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(keyCode==43){
                $("#addMoreAdd").click();
                e.preventDefault();
                updateTotal();
            }else if(keyCode == 45){
                e.preventDefault();
                $('.addProductAppendAdd').find('.addedRowAdd:last').remove();
                if($(".addProductAppendAdd").find('input[name="productName[]"]').length ==0){
                    $(".productNameAdd").focus();
                }else{
                    $(".addProductAppendAdd").find('input[name="productName[]"]').last().focus();
                }
                updateTotal();


            }
        }

        updateTotal();




        $(document).on('change','#appointmentStaffID',function(e){
           /* $("#appointmentAddressID").html('');
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
                    },
                    error: function(data){
                    }
                });
            }*/
        });
        $("#appointmentStaffID").val("<?php echo base64_decode($doctor_id); ?>");
        $("#appointmentStaffID").trigger('change');



        $("#receipt_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});

        $("#receipt_date").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});
    });
    </script>
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