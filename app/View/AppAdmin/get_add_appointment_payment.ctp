<div class="modal fade" id="myModalFormAdd" role="dialog">
<?php $login = $this->Session->read('Auth.User'); ?>
<?php
    //$medicalProductData = $this->AppAdmin->get_app_medical_product_list($login['User']['thinapp_id']);
    $defaultProductData = $this->AppAdmin->get_app_default_medical_product_list($login['User']['thinapp_id']);
    //pr($defaultProductData);
?>
<style>
    #myModalPaymentTypeDetailAdd {
        z-index: 999999999;
    }
    .default_btn {
        display: inline-flex;
    }
    .default_btn > label > input {
        width: 15px !important;
    }
    .default_btn label {
        font-size: x-small;
        margin-top: 37px;
        margin-bottom: 0px;
    }
    .ms-helper{
        display: none !important;
    }
    ..ms-ctn .ms-sel-item{
        padding: 0px !important;
    }

    .form-control{
        height: 35px;
    }

    .show_due_lbl{
        width: 100%;
        text-align: center;
        font-size: 18px;
    }

    #myModalFormAdd .modal-body .ms-ctn .ms-sel-item {
        background: none;
        color: #6c6c6c;
        float: left;
        font-size: 13px;
        padding: 5px 5px;
        border-radius: 3px;
        border: none;
        margin: 1px 5px 1px 0;
        font-weight: 400;
    }

    #myModalFormAdd .modal-body .col-md-1{
        width: 7% !important;
    }
    #myModalFormAdd .modal-body .col-md-2{
        width: 10%;
    }
   #myModalFormAdd .modal-body .row .col-md-2:first-child{
        width: 34% !important;
       height: 35px;
    }
  #myModalFormAdd .modal-body .ms-ctn{
      position: relative;
      padding: 0px 8px;
      height: 35px;
    }


    #addOpdRowBtn{
        float: right;
        padding: 4px 3px;
        margin: 5px 0px;
        position: absolute;
        right: 15px;
        top: 0px;
    }



    #due_amount{
        width: 100%;
        float: left;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        margin: 0px 5px;


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
    .label_box_row{
        margin-bottom: 15px;
    }



</style>

<script>
    <?php if($login['USER_ROLE'] == 'ADMIN'){
    $checkBox = '<label><input type="checkbox" checked="checked" class="make_default">Default</label>';
        ?>
    var checkBox = '<label><input type="checkbox" class="make_default">Default</label>';
    <?php }else{
    $checkBox = '';
        ?>
    var checkBox = '';
    <?php } ?>
</script>

<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPayAdd')); ?>


            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>


                <h4 class="modal-title" style="text-align: center;font-weight: bold;">Payment</h4>



            </div>
            <div class="modal-body">
                <input type="hidden" readonly="readonly" class="form-control" value="YES"  id="show_into_receipt" name="show_into_receipt">


                <button type="button" style="display: none;" class="btn btn-default btn-xs addOpdRowBtn" id="addOpdRowBtn">
                    <i class="fa fa-plus"> OPD Service</i>
                </button>

                <script>
                    var defaultFee = 0;
                </script>
                <?php if($bookingConvenienceFeeDetail['appointment_booked_from'] == 'IVR' && $bookingConvenienceFeeDetail['is_paid_booking_convenience_fee'] == 'NO'){ ?>
                    <div class="row ">
                        <div class="input number col-md-2">
                            <label for="">Service</label>
                            <input type="text" readonly="readonly" class="form-control" value="Booking Convenience Fees" placeholder="Service">
                        </div>
                        <div class="input number col-md-1">
                            <label for="">Quantity</label>
                            <input type="number" readonly="readonly" class="form-control" min="1" value="1" placeholder="Quantity" required="true">
                        </div>
                        <div class="input number col-md-1">
                            <label for="">Discount</label>
                            <input type="number"  readonly="readonly" class="form-control" min="0" value="0"  placeholder="Discount">
                        </div>
                        <div class="input number col-md-1">
                            <label for="">Dis. Type</label>
                            <input type="text" readonly="readonly" class="form-control" required="true" value="Percentage" placeholder="Dis. Type">
                        </div>
                        <div class="input number col-md-1">
                            <label for="">Tax Type</label>
                            <input type="text" readonly="readonly" class="form-control" value="No Tax" required="true" placeholder="Tax Type">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Tax Value</label>
                            <input type="text" readonly="readonly" class="form-control" required="true" value="0" placeholder="Tax Value">
                        </div>

                        <div class="input number col-md-2">
                            <label for="">Expiry</label>
                            <input type="text" readonly="readonly" class="form-control" required="true" value="Select" placeholder="Tax Value">
                        </div>

                        <div class="input number col-md-1">
                            <label for="">Price</label>
                            <input type="text" readonly="readonly" class="form-control" value="<?php echo $bookingConvenienceFeeDetail['booking_convenience_fee']; ?>" placeholder="Amount">
                        </div>
                        <div class="input number col-md-1">
                            <label for="">Amount</label>
                            <input type="text" readonly="readonly" class="form-control" value="<?php echo $bookingConvenienceFeeDetail['booking_convenience_fee']; ?>" placeholder="Amount">
                        </div>


                        <div class="input col-md-1">

                        </div>
                    </div>
                    <script>
                       defaultFee = <?php echo $bookingConvenienceFeeDetail['booking_convenience_fee']; ?>;
                    </script>
                <?php } ?>



                <div class="row opd_row">
                    <div class="input number col-md-2">
                        <label for="opdCharge">Service</label>
                        <input type="text" readonly="readonly" class="form-control" value="OPD" placeholder="Service">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Quantity</label>
                        <input type="number" readonly="readonly" class="form-control" min="1" value="1" placeholder="Quantity" required="true">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Discount</label>
                        <input type="number" name="discount_opd" id="discount_opd_add" class="form-control" min="0" value="0"  placeholder="Discount">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Dis. Type</label>
                        <select name="discount_opd_type" id="discount_opd_type_add" onclick="return false" class="form-control">
                            <option value="PERCENTAGE">Percentage</option>
                            <option value="AMOUNT">Amount</option>
                        </select>
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Tax Type</label>
                        <input type="text" readonly="readonly" class="form-control" value="No Tax" required="true" placeholder="Tax Type">
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Tax Value</label>
                        <input type="text" readonly="readonly" class="form-control" required="true" value="0" placeholder="Tax Value">
                    </div>

                    <div class="input number col-md-2">
                        <label for="opdCharge">Expiry</label>
                        <select name="quantity_id" disabled="disabled" class="form-control"><option value="">Select</option></select>
                    </div>

                    <div class="input number col-md-1">
                        <label for="opdCharge">Price</label>
                        <?php echo $this->Form->input('amount', array('readonly'=>($appointment_data['emergency_appointment']=='YES')?'readonly':'','type'=>'number','label'=>false,'min'=>"0" ,'required'=>true,'placeholder'=>'Price','class'=>'form-control ','id'=>"opdChargeAdd")); ?>
                    </div>
                    <div class="input number col-md-1">
                        <label for="opdCharge">Amount</label>
                        <input type="text" readonly="readonly" class="form-control" value="0" id="amountOPDAdd" placeholder="Amount">
                    </div>


                    <div class="input col-md-1">
                        <?php if($appointment_data['emergency_appointment']=='NO'){ ?>
                        <button type="button" class="close removeRowAdd" id="removeOpdRowBtn"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <?php } ?>
                    </div>
                </div>





                <div class="addProductAppendAdd">
                    <?php foreach($defaultProductData AS $defaultVal){ ?>
                        <div class="row addedRowAdd">
                            <div class="input number col-md-2">
                                <label for="opdCharge">Service/Product</label>
                                <input autocomplete="off" value="[<?php echo $defaultVal['id'];?>]" type="text" name="productName[]" class="form-control productNameAdd" placeholder="Service/Product" required="true">
                                <input type="hidden" name="productID[]" value="<?php echo $defaultVal['id'];?>" required="required">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Quantity</label>
                                <input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Discount</label>
                                <input name="discount[]" class="form-control" min="0" value="0" placeholder="Discount" type="number">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Dis. Type</label>
                                <select name="discountType[]" class="form-control">
                                    <option value="PERCENTAGE">Percentage</option>
                                    <option value="AMOUNT">Amount</option>
                                </select>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Tax Type</label>
                                <input type="text" readonly="readonly" value="<?php echo $defaultVal['tax_name']; ?>" name="tax_type[]" class="form-control" value="0" required="true" placeholder="Tax Type">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Tax Value</label>
                                <input type="text" readonly="readonly" value="<?php echo $defaultVal['rate']; ?>" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value">
                            </div>
                            <div class="input number col-md-2">
                                <label for="opdCharge">Expiry</label>
                                <select name="medical_product_quantity_id[]" class="form-control">
                                    <?php
                                    $thisPrice = $defaultVal['price'];
                                    if(empty($defaultVal['qty_data'])){ ?>
                                        <option value="">Select</option>
                                    <?php
                                    }
                                    else
                                    { ?>
                                        <?php
                                        foreach($defaultVal['qty_data'] AS $key => $qty){
                                            if($key == 0)
                                            {
                                                $thisPrice = $qty['mrp'];
                                            }
                                        ?>
                                        <option value="<?php echo $qty['id']; ?>" mrp="<?php echo $qty['mrp']; ?>" qty="<?php echo $qty['quantity']; ?>" sold="<?php echo $qty['sold']; ?>">
                                        <?php
                                        if($qty['expiry_date'] == '0000-00-00')
                                        {
                                            echo 'Not Available';
                                        }
                                        else
                                        {
                                            echo date('d/m/Y',strtotime($qty['expiry_date']));
                                        }
                                        ?>
                                    <?php } ?>
                                        </option>
                                    <?php } ?>

                                </select>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Price</label>
                                <input type="text" readonly="readonly" name="price[]" value="<?php echo $thisPrice; ?>" class="form-control" value="0" placeholder="Price">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Amount</label>
                                <input type="text" readonly="readonly" name="amount[]" value="<?php echo $thisPrice; ?>" class="form-control" placeholder="Amount">
                            </div>
                            <div class="input col-md-1 default_btn">
                                <button type="button" class="close removeRowAdd"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                <?php echo $checkBox; ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="row" >
                    <div class="input col-md-offset-11 col-md-1">
                        <button type="button" id="addMoreAdd" style="margin-top: 15px;" class="btn btn-primary">Add More</button>
                        <span class="subText">Press '+'</span>
                    </div>
                </div>

                <datalist id="text_editorsAdd">

                    <?php

                    $medicalProductData = $this->AppAdmin->get_app_medical_product_list($login['User']['thinapp_id']);
                    if(!empty($medicalProductData)){
                        foreach ($medicalProductData AS $key => $value) { ?>
                            <option value="<?php echo $value['name']; ?>" qtyList = '<?php echo json_encode($value['qty_data']); ?>' productID="<?php echo $value['id']; ?>" productPrice="<?php echo $value['price']; ?>"  taxType="<?php echo (isset($value['tax_name']))?$value['tax_name']:"No Tax"; ?>" taxRate="<?php echo isset($value['rate'])?$value['rate']:"0"; ?>" isPriceEditable="<?php echo isset($value['is_price_editable'])?$value['is_price_editable']:"0"; ?>"><?php echo $value['name']; ?></option>
                            <?php
                        }
                    }

                    ?>
                </datalist>

            </div>
            <div class="modal-footer">


                <div class="col-md-12 label_box_row">
                    <label class="show_due_lbl" id="due_amount" data-value="<?php echo !empty($due_amount)?$due_amount:0;; ?>" ></label>
                </div>

                <div class="input text  col-md-2 col-xs-offset-3">
                    <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_id','type'=>'select','label'=>'Select Payment Mode','empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['Thinapp']['id']),'class'=>'form-control cnt')); ?>
                </div>
                <div class="input text  col-md-2">
                    <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_name','type'=>'hidden','label'=>false,'value'=>'CASH')); ?>

                    <?php echo $this->Form->input('payment_description',array('id'=>'p_desc','type'=>'text','label'=>'Payment Description','class'=>'form-control cnt')); ?>
                </div>

                <div class="input text  col-md-1">
                    <?php echo $this->Form->input('display',array('readonly'=>'readonly','id'=>'total_display','type'=>'text','label'=>'Total Amount','class'=>'form-control')); ?>
                </div>

                <div class="input text  col-md-1">
                    <?php echo $this->Form->input('tot',array("autocomplete"=>"off",'id'=>'total_lbl','required'=>'required', 'type'=>'text','label'=>'Paid Amount','class'=>'form-control total_lbl')); ?>
                </div>




                <div class="input text  col-md-2">

                    <label>&nbsp;</label>
                    <?php echo $this->Form->input('id', array('type'=>'hidden','label'=>false,'required'=>true,'class'=>'form-control ','value'=>base64_encode($appointment_id))); ?>

                    <?php echo $this->Form->submit('Pay Now',array('class'=>'btn btn-info','id'=>'addPaySubmitAdd')); ?>


                </div>



            </div>
            <?php echo $this->Form->end(); ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var dialog_obj =null;

            function triggerOnSelectProduct($this){
                    if(!dialog_obj && $($this).find('input[name="quantity[]"]').attr('allow_add') != 'YES'){
                        var quantity = $($this).find('input[name="quantity[]"]').val();
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

            function addSuggestionToNewProduct(elm = ".addedRowAdd:last"){
                var obj = $(elm).find('input[name="productName[]"]');
                var raw_obj = $(elm);
                var ms = $(obj).magicSuggest({
                    allowFreeEntries:false,
                    allowDuplicates:false,
                    data:<?php echo json_encode($medicalProductData,true); ?>,
                    maxDropHeight: 345,
                    maxSelection: 1,
                    required: true,
                    noSuggestionText: '',
                    useTabKey: true,
                    selectFirst :true
                });
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

                            if (IdArr[0])
                            {

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
                                var discount = $($this).parent().nextAll().find('input[name="discount[]"]').val();
                                var discountType = $($this).parent().nextAll().find('select[name="discountType[]"]').val();
                                var totalAmount = calculateValue(discount, quantity, productPrice, discountType, taxRate);
                                $($this).parent().nextAll().find('input[name="amount[]"]').val(totalAmount);
                                if (isPriceEditable == 1) {
                                    $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly', false);
                                }
                                else {
                                    $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly', true);
                                }
                                triggerOnSelectProduct($this);
                                updateTotal();


                            }
                            else
                            {
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


            $(".addedRowAdd").each(function(index,elm){
                addSuggestionToNewProduct(elm);
            });




        var url = window.location.pathname.split("/");
        var action =url[url.length-1];

        $(document).off('change','#p_type_id');
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

        $(document).off("click",".closeMyModalPaymentTypeDetailAdd");
        $(document).on("click",".closeMyModalPaymentTypeDetailAdd",function(e){
            $("#myModalPaymentTypeDetailAdd").modal('hide');
        });

        $(document).off('hidden.bs.modal','#myModalPaymentTypeDetailAdd');
        $(document).on('hidden.bs.modal', '#myModalPaymentTypeDetailAdd', function (e) {
            e.stopPropagation();
            e.stopImmediatePropagation();
        });


        $(document).off('change','#p_type_id_edit');
        $(document).on('change','#p_type_id_edit',function(e){
            $('#p_type_name_edit').val($('#p_type_id_edit option:selected').text());
        });


        function updateShowOpdRowStatus(status){

            <?php if($login['USER_ROLE'] == 'ADMIN'){ ?>
                $.ajax({
                    type: 'POST',
                    url: baseurl + "app_admin/update_show_opd_row_status",
                    data: {status: status},
                    success: function (data) {
                        data = JSON.parse(data)

                        if(data.status == '0')
                        {
                            alert("Sorry something went wrong on server.");
                        }

                    },
                    error: function (data) {
                        alert("Sorry something went wrong on server.");
                    }
                });
            <?php } ?>

        }


        $(document).off('click','#removeOpdRowBtn');
        $(document).on('click','#removeOpdRowBtn', function () {
            $(".opd_row").hide();
            if(!$(".opd_row").attr('data-default')){
                $(".opd_row").attr('data-default',$(".opd_row #opdChargeAdd").val());
            }
            $("#show_into_receipt").val("NO");
            $(".opd_row #opdChargeAdd, .opd_row #amountOPDAdd").val(0);
            $("#addOpdRowBtn").show();
            updateShowOpdRowStatus('N');
            updateTotal();
        });



        $(document).off('click','#addOpdRowBtn');
        $(document).on('click','#addOpdRowBtn', function () {
            updateShowOpdRowStatus('Y');
            show_opd_row();
            updateTotal();
        });


        function show_opd_row(){
            $(".opd_row").show();
            $(".opd_row #opdChargeAdd, .opd_row #amountOPDAdd").val("<?php echo $appointment_data['service_amount']; ?>");
            $("#addOpdRowBtn").hide();
            $("#show_into_receipt").val("YES");
        }


            <?php if($showOpdRow == 'N'){ ?>
            setTimeout(function(){
                console.log("here");
                $('#removeOpdRowBtn').trigger("click");
            },100);
            <?php } ?>

        $(document).off('submit','#formPayAdd');
        $(document).on('submit','#formPayAdd',function(e){
            e.preventDefault();
            if($(".addProductAppendAdd .addedRowAdd").length > 0 || $("#show_into_receipt").val() =='YES' ){
                var data = $( "#formPayAdd, #paymentTypeDetailForm" ).serialize();
                console.log(data);
                var $btn = $("#addPaySubmitAdd");
                var dataArr = $( this ).serializeArray();
                var submit = true;

                /*if($("#show_into_receipt").val() =="YES" && $("#opdChargeAdd").val()==""){
                    $.alert("Price could not be empty!");
                    submit = false;
                }*/
                $.each(dataArr, function( key, value ) {
                    if(value.name == 'productID[]' && !(value.value > 0)){
                        $.alert("Service name could not be empty!");
                        submit = false;
                    }/*else if(value.name == 'price[]' && !(value.value > 0)){
                        $.alert("Price could not be empty!");
                        submit = false;
                    }*/
                });

                if(submit == true)
                {
                    if($("#total_lbl").val()!=""){
                        $.ajax({
                            type:'POST',
                            url: baseurl+"app_admin/web_pay_appointment",
                            data:data,
                            beforeSend:function(){
                                $btn.button('loading').val('Wait...');
                            },
                            success:function(data){
                                var response = JSON.parse(data);
                                $btn.button('reset');
                                if(response.status == 1)
                                {

                                    if(action=='add_appointment'){
                                        $("#appointment_date").trigger("changeDate");
                                    }
                                    else if(action=='view_staff_app_schedule')
                                    {

                                        $(payBtnChecked).closest("tr").find(".payment_status").html("Success");

                                        var AddButton = '<button type="button" class="btn btn-xs btn-info" ' +
                                            'onclick="var win = window.open(\''+baseurl+'app_admin/print_invoice/'+ response.data_id+'\', \'_blank\'); if (win) { win.focus(); } else { alert(\'Please allow popups for this website\'); }"' +
                                            ' ><i class="fa fa-money"></i> Receipt</button>';
                                        <?php if(($login['USER_ROLE'] == 'RECEPTIONIST' && $login['AppointmentStaff']['edit_appointment_payment'] =="YES" ) || $login['USER_ROLE'] == 'ADMIN' || $login['USER_ROLE'] =='DOCTOR' ){ ?>
                                        AddButton +='<button type="button" class="btn btn-primary btn-xs editBtn" data-id="'+ response.data_id+'"><i class="fa fa-inr" aria-hidden="true"></i> Edit</button>';
                                        <?php } ?>
                                        $(payBtnChecked).closest("tr").find(".app_btn_td .option_btn_panel").prepend(AddButton);
                                        var stat = (response.payment_type != 'CASH')?'ONLINE':'CASH';
                                        $(payBtnChecked).closest("tr").find(".payment_type_name").html(stat);
                                        var amt = '<a href="javascript:void(0);" rel="tooltip" class="show_amt_tooltip" data-original-title="" >'+
                                        '<i class="fa fa-inr" aria-hidden="true"></i>'+
                                        response.amount+
                                        '</a>';
                                        $(payBtnChecked).closest("tr").find(".amt").html(amt);
                                        $(payBtnChecked).remove();
                                        $btn.hide();
                                    }





                                    $("#myModalFormAdd").modal("hide");
                                    var win = window.open(baseurl+"app_admin/print_invoice/"+response.data_id, '_blank');
                                    if (win) {
                                        win.focus();
                                    } else {
                                        alert('Please allow popups for this website');
                                    }
                                }
                                else
                                {
                                    $btn.button('reset');
                                    alert(response.message);
                                }


                            },
                            error: function(data){
                                $btn.attr('disabled',false);
                                alert("Sorry something went wrong on server.");
                            }
                        });
                    }else{
                        $.alert("Paid amount can not be empty");
                    }

                }

            }else{
                $.alert("Please enter at least one service");
            }
        });

        show_opd_row();



        $(document).off('click','.removeRowAdd');
        $(document).on('click','.removeRowAdd',function () {
            $(this).parents('.addedRowAdd').remove();
            updateTotal();
        });

        $(document).off('click','#addMoreAdd');
        $(document).on('click','#addMoreAdd',function () {

            var htmlToAppend = '<div class="row addedRowAdd"><div class="input number col-md-2"><label for="opdCharge">Service/Product</label><input autocomplete="off" type="text" name="productName[]" class="form-control productNameAdd" placeholder="Service/Product" required="true"><input type="hidden" name="productID[]" required="required"></div><div class="input number col-md-1"><label for="opdCharge">Quantity</label><input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Discount</label><input name="discount[]" class="form-control" min="0" value="0" placeholder="Discount" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Dis. Type</label><select name="discountType[]" class="form-control"><option value="PERCENTAGE">Percentage</option><option value="AMOUNT">Amount</option></select></div><div class="input number col-md-1"><label for="opdCharge">Tax Type</label><input type="text" readonly="readonly" name="tax_type[]" class="form-control" value="0" required="true" placeholder="Tax Type"></div><div class="input number col-md-1"><label for="opdCharge">Tax Value</label><input type="text" readonly="readonly" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value"></div><div class="input number col-md-2"><label for="opdCharge">Expiry</label><select name="medical_product_quantity_id[]" class="form-control"><option value="">Select</option></select></div><div class="input number col-md-1"><label for="opdCharge">Price</label><input type="text" readonly="readonly" name="price[]" class="form-control" value="0" placeholder="Price"></div><div class="input number col-md-1"><label for="opdCharge">Amount</label><input type="text" readonly="readonly" name="amount[]" class="form-control" value="0" placeholder="Amount"></div><div class="input col-md-1 default_btn"><button type="button" class="close removeRowAdd"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>'+checkBox+'</div></div>';
            $(".addProductAppendAdd").append(htmlToAppend).find('input[name="productName[]"]').last().focus();
            addSuggestionToNewProduct();
        });

        $(document).off('keyup keypress blur change','#opdChargeAdd,#discount_opd_add,#discount_opd_type_add');
        $(document).on('keyup keypress blur change','#opdChargeAdd,#discount_opd_add,#discount_opd_type_add',function () {
            var opdChargeAddAMT = $("#opdChargeAdd").val();
            var discountOpdAMT = $("#discount_opd_add").val();
            var discountOpdType = $("#discount_opd_type_add").val();

            var priceOPDAdd = calculateValue(discountOpdAMT,1,opdChargeAddAMT,discountOpdType,0)

            $("#priceOPDAdd").val(opdChargeAddAMT);
            $("#amountOPDAdd").val(priceOPDAdd);
            updateTotal();
        });

        $(document).off('keyup keypress blur change input',"input[name='productID[]'], input[name='quantity[]'], input[name='discount[]'], input[name='price[]'], select[name='discountType[]']");
        $(document).on('keyup keypress blur change input',"input[name='productID[]'], input[name='quantity[]'], input[name='discount[]'], input[name='price[]'], select[name='discountType[]']",function () {
            var row = $(this).closest('.opd_row, .addedRowAdd');
            triggerOnOtherElement(row);
            triggerOnSelectProduct(row);
        });


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

                setTimeout(function(){updateTotal();},200);

            });
        }

        function updateTotal(){
            var total =parseFloat($("#amountOPDAdd").val())+defaultFee;
            var tmp = total.toString().split(".");
            if(tmp.length ==2){
                total = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
            }

            $( "#myModalFormAdd .modal-body input[name='amount[]']" ).each(function( index ) {

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

            var due = parseFloat($("#due_amount").attr('data-value'));
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
            $(this).parents('.addedRow').remove();
            updateTotal();
        });



        $(document).off('keypress');
        $(document).on('keypress',  function (e) {
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if($('#myModalForm').is(':visible')) {
                clickEventEdit(e);
            }else if($('#myModalFormAdd').is(':visible')) {
                clickEvent(e);
            }else{
                if(!$('.modal').is(':visible')){
                    clickShortCutEvent(e);
                }
            }
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





        $(document).off('keypress, change  keyup','#myModalFormAdd .modal-body input, #myModalFormAdd .modal-body select');
        $(document).on('keypress, change  keyup','#myModalFormAdd .modal-body input, #myModalFormAdd .modal-body select',function () {
            updateTotal();
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

        updateTotal();



        var base = "<?php echo Router::url('/app_admin/',true); ?>";


        function clickShortCutEvent(e){

            var action = "<?php echo $this->request->params['action']; ?>";
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if(action != 'add_appointment' && keyCode==97){
                /*key code = A*/
                e.preventDefault();
                window.location.href = base+"add_appointment";
            }else if(keyCode == 98){
                /*key code = B*/
                e.preventDefault();
                window.location.href = base+"add_hospital_receipt_search";
            }else if(keyCode == 109){
                /*key code = M*/
                e.preventDefault();
                window.location.href = base+"view_app_schedule";
            }else if(keyCode == 13 && !$('.modal').is(':visible') && action == 'add_appointment'){
                var active_obj = $("#load_slot_div").find(".is-selected").trigger('click');
            }

        }


        $(document).off('keyup keypress blur change','#opdCharge,#discount_opd,#discount_opd_type')
        $(document).on('keyup keypress blur change','#opdCharge,#discount_opd,#discount_opd_type',function () {
            var opdChargeAMT = $("#opdCharge").val();
            var discountOpdAMT = $("#discount_opd").val();
            var discountOpdType = $("#discount_opd_type").val();

            var priceOPD = calculateValue(discountOpdAMT,1,opdChargeAMT,discountOpdType,0)

            $("#priceOPD").val(opdChargeAMT);
            $("#amountOPD").val(priceOPD);

        });



        $(document).off('keyup keypress blur change',"input[name='productID[]'], input[name='quantity[]'], input[name='price[]'], input[name='discount[]'], select[name='discountType[]']");
        $(document).on('keyup keypress blur change',"input[name='productID[]'], input[name='quantity[]'], input[name='price[]'], input[name='discount[]'], select[name='discountType[]']",function () {
            $("input[list]").trigger('change');
        });

        });

        $(document).ready(function(){
            $(document).off('click','.make_default');
            $(document).on('click','.make_default',function(){
                if($(this). prop("checked") == true){
                    var isDefault = 'Y';
                }
                else
                {
                    var isDefault = 'N';
                }

                var productID = $(this).closest(".addedRowAdd").find('input[name="productID[]"]').val();
                if(productID == "")
                {
                    alert("Please select product first.");
                    return;
                }
                else
                {
                    $.ajax({
                        type: 'POST',
                        url: baseurl + "app_admin/update_default_product",
                        data: {productID: productID,isDefault:isDefault},
                        success: function (data) {
                            data = JSON.parse(data)

                            if(data.status == '0')
                            {
                                alert("Sorry something went wrong on server.");
                            }

                        },
                        error: function (data) {
                            alert("Sorry something went wrong on server.");
                        }
                    });
                }


            });
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

</div>