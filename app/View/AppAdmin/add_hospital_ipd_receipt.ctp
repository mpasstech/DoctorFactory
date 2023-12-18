<?php // pr($patientData); pr($medicalProductData); ?>
<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
echo $this->Html->script(array('jquery.maskedinput-1.2.2-co.min.js'));

$show_days_on_receipt = !empty($login['Thinapp']['show_number_of_days_on_receipt'])?$login['Thinapp']['show_number_of_days_on_receipt']:'NO';
$show_days_on_receipt = 'YES';


?>


<?php echo $this->Html->script(array('magicsuggest-min.js')); ?>
<?php echo $this->Html->css(array('magicsuggest-min.css')); ?>


<style>
    .dashboard_icon_li li {

        text-align: center;
        width: 18%;

    }
    .row{
        margin-left: 0px;
        margin-right: 0px;
    }
    .form-control{
        height: 30px !important;
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
    .close {
        margin-top: 35px;
    }
</style>
<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
           <!-- --><?php /*echo $this->element('billing_inner_header'); */?>

            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <h3 class="screen_title"> Add IPD Receipt</h3>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >

                        <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPayAdd')); ?>

                        <div class="Social-login-box payment_bx">
                            <div class="form-group row">
                                <div class="col-sm-12" style="border-bottom: 1px solid;">
                                    <div class="form-group">
                                        <div class="col-md-2">
                                            <?php $customerUHID = (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['uhid']:$patientData['Children']['uhid']; ?>
                                            <label>UHID:</label><br><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['uhid']:$patientData['Children']['uhid']; ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Patient Name:</label><br><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['first_name']:$patientData['Children']['child_name']; ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Mobile Number:</label><br><?php echo (isset($patientData['AppointmentCustomer']))?$patientData['AppointmentCustomer']['mobile']:$patientData['Children']['mobile']; ?>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Receipt Date:</label><input type="text" id="receipt_date" autocomplete="off" value="<?php echo date("d/m/Y"); ?>" name="receipt_date" required="true" class="form-control dateContainer" placeholder="Receipt Date">
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $this->Form->input('doctor', array('value'=>@$doctor_id,'type' => 'select','empty'=>'Please Select','options'=>$doctor_list,'label' => 'Select Doctor', 'class' => 'form-control')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>

                            <div class="form-group ">
                                <div class="row">
                                        <div class="input number col-md-2">
                                            <label for="opdCharge">Service</label>
                                            <input type="text" name="productName[]" autocomplete="off" class="form-control productNameAdd" placeholder="Service" required="true">
                                            <input type="hidden" name="productID[]" required="true">
                                        </div>
                                        <div class="input number col-md-1">
                                            <label for="opdCharge">Quantity</label>
                                            <input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number">
                                        </div>




                                    <div style="display:<?php echo ($show_days_on_receipt == 'YES')?'block':'none'; ?>" class="input number col-md-1 width-5">
                                        <label for="day">Days</label>
                                        <input name="days[]" class="form-control" min="1" value="1" placeholder="" required="true" type="number">
                                    </div>


                                    <div class="input number col-md-1">
                                            <label for="opdCharge">Discount</label>
                                            <input name="discount[]" class="form-control" required="true" min="0" value="0" placeholder="Discount" type="number">
                                        </div>
                                        <div class="input number col-md-1">
                                            <label for="opdCharge">Discount Type</label>
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

                                        <div class="input number col-md-2">
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




                        <div class="row" style="border-top: 1px solid #e2dede;">
                            <div class="form-group">




                                <div class="input text  col-md-1">
                                    <?php echo $this->Form->input('tot',array('readonly'=>'readonly','type'=>'text','label'=>'Total Amount','class'=>'form-control total_lbl')); ?>
                                </div>


                                <div class="input text  col-md-2">
                                    <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_id','type'=>'select','label'=>'Select Payment Mode','empty'=>array('0'=>'Cash'),'options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['Thinapp']['id']),'class'=>'form-control cnt')); ?>
                                    <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_name','type'=>'hidden','label'=>false,'value'=>'CASH')); ?>
                                </div>
                            <div class="input text  col-md-2" >
                                <?php echo $this->Form->input('payment_description',array('id'=>'p_desc','type'=>'text','label'=>'Payment Description','class'=>'form-control cnt')); ?>
                            </div>

                                <div class="input text  col-md-1">
                                    <lable style="font-size: 11px; font-weight: 600;">Amount Received?</lable>
                                    <?php  echo $this->Form->input('payment_status', array('type'=>'checkbox','checked'=>false,'label'=>false)); ?>
                                </div>

                                <div class="input text  col-md-2">
                                    <label>&nbsp;</label>
                                <input type="hidden" name="customerUHID" value="<?php echo $customerUHID; ?>">
                                <input type="hidden" name="hospital_ipd_id" value="<?php echo base64_decode($ipdID); ?>">

                                <?php

                                    echo $this->Form->submit('Save & Print Receipt',array('class'=>'btn btn-info','id'=>'addPaySubmitAdd'));

                                ?>

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
</div>

<style>
    .paymentTypeSelect {
        width: 50%;
        float: right;
    }

</style>

<style>
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

</style>

<script>

    $(function(){

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
        $("#receipt_date").datepicker({clearBtn:true,format: 'dd/mm/yyyy',autoclose:true,orientation: "bottom auto"});
        $("#receipt_date").mask("99/99/9999", {placeholder: 'dd/mm/yyyy'});

        $(document).on('submit','#formPayAdd',function(e){
            e.preventDefault();
            var data = $( this ).serialize();
            var $btn = $("#addPaySubmitAdd");

            var dataArr = $(  "#formPayAdd, #paymentTypeDetailForm"  ).serializeArray();
            var submit = true;
            $.each(dataArr, function( key, value ) {
                if(value.name == 'productID[]' && !(value.value > 0))
                {
                    $.alert("Service name could not be empty!");
                    submit = false;
                }
            });

            if(submit == true)
            {
                $.ajax({
                    type:'POST',
                    url: baseurl+"app_admin/web_add_hospital_ipd_receipt",
                    data:data,
                    beforeSend:function(){
                        $btn.attr('disabled',true);
                    },
                    success:function(data){
                        var response = JSON.parse(data);
                        if(response.status == 1)
                        {

                            $("#myModalFormAdd").modal("hide");
                            var ID = btoa(response.receipt_id);
                            window.location = (baseurl+"app_admin/print_invoice/"+ID+"/IPD");

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

        function updateTotal(){
            var total =0;
            $( ".payment_bx input[name='amount[]']" ).each(function( index ) {

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
                var days = $($this).parent().nextAll().find('input[name="days[]"]').val();
                var discount = $($this).parent().nextAll().find('input[name="discount[]"]').val();
                var discountType = $($this).parent().nextAll().find('select[name="discountType[]"]').val();

                var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
              //  alert(days);

                $($this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount*days);


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
                    var days = $($this).parent().nextAll().find('input[name="days[]"]').val();
                    var discount = $($this).parent().nextAll().find('input[name="discount[]"]').val();
                    var discountType = $($this).parent().nextAll().find('select[name="discountType[]"]').val();

                    var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                    $($this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount*days);


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
                        var days = $(this).parent().nextAll().find('input[name="days[]"]').val();
                        var discount = $(this).parent().nextAll().find('input[name="discount[]"]').val();
                        var discountType = $(this).parent().nextAll().find('select[name="discountType[]"]').val();
                        var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                        $(this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount * days);


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

        $(document).on('change input','select[name="medical_product_quantity_id[]"]',function (){


            if($(this).val() != '')
            {
                var productPrice = $(this).find(":selected").attr('mrp');

                $(this).parent().nextAll().find('input[name="price[]"]').val(productPrice);

                var quantity = $(this).parent().siblings().find('input[name="quantity[]"]').val();
                var days = $(this).parent().siblings().find('input[name="days[]"]').val();
                var discount = $(this).parent().siblings().find('input[name="discount[]"]').val();
                var discountType = $(this).parent().siblings().find('select[name="discountType[]"]').val();
                var taxRate = $(this).parent().siblings().find('input[name="tax_value[]"]').val();
                var totalAmount = calculateValue(discount,quantity,productPrice,discountType,taxRate);
                $(this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount* days);
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

        $(document).on('click','.removeRowAdd',function (){
            $(this).parents('.addedRowAdd').remove();
            updateTotal();
        });
        var $show_days_on_receipt = "<?php echo $show_days_on_receipt; ?>";
        function addRow(){

            var display = ($show_days_on_receipt=='YES')?'block':'none';
            var day_html = '<div style="display:'+display+';" class="input number col-md-1 width-5"><label for="day">Days</label><input name="days[]" class="form-control" min="1" value="1" placeholder="" required="true" type="number"></div>';

            var htmlToAppend = '<div class="row addedRowAdd"><div class="input number col-md-2"><label for="opdCharge">Service</label><input type="text" name="productName[]" autocomplete="off" class="form-control productNameAdd" placeholder="Service" required="true"><input type="hidden" name="productID[]" required="true"></div><div class="input number col-md-1"><label for="opdCharge">Quantity</label><input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number"></div>'+day_html+'<div class="input number col-md-1"><label for="opdCharge">Discount</label><input name="discount[]" class="form-control" min="0" value="0" placeholder="Discount" required="true" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Discount Type</label><select name="discountType[]" required="true" class="form-control"><option value="PERCENTAGE">Percentage</option><option value="AMOUNT">Amount</option></select></div><div class="input number col-md-1"><label for="opdCharge">Tax Type</label><input type="text" readonly="readonly" name="tax_type[]" class="form-control" value="0" required="true" placeholder="Tax Type"></div><div class="input number col-md-1"><label for="opdCharge">Tax Value</label><input type="text" readonly="readonly" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value"></div><div class="input number col-md-2"><label for="opdCharge">Expiry</label><select name="medical_product_quantity_id[]" class="form-control"><option value="">Select</option></select></div><div class="input number col-md-1"><label for="opdCharge">Price</label><input type="text" readonly="readonly" name="price[]" class="form-control" required="true" value="0" placeholder="Price"></div><div class="input number col-md-1"><label for="opdCharge">Amount</label><input type="text" readonly="readonly" name="amount[]" class="form-control" required="true" value="0" placeholder="Amount"></div><div class="input col-md-1"><button type="button" class="close removeRowAdd"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div></div>';
            $(".addProductAppendAdd").append(htmlToAppend).find('input[name="productName[]"]').last().focus();
        }

        $(document).on('click','#addMoreAdd',function (){
            addRow();
        });

        $(document).on('keyup keypress blur change',"input[name='days[]'], input[name='productID[]'], input[name='quantity[]'], input[name='price[]'], input[name='discount[]'], select[name='discountType[]']",function (e){
            var form = $(this).closest('form');
            triggerOnOtherElement(form);
        });

        $(document).on('keypress',  function (e){

            clickEvent(e);

        });

        $(document).on('keypress, change  keyup','.payment_bx input, .payment_bx select',function () {
            updateTotal();
        });

        function updateTotal(){
            var total =0;
            $( ".payment_bx input[name='amount[]']" ).each(function( index ) {

                var tmp = parseFloat($(this).val()).toString().split(".");
                if(tmp.length ==2){
                    tmp = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
                }
                total += parseFloat(tmp);

            });
            $('.total_lbl').val(total);
        }

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
            $("#appointmentAddressID").html('');
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
            }
        });

    })
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