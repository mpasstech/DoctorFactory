<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPaySearch')); ?>

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-align: center;font-weight: bold;">Edit Payment</h4>
        </div>
        <div class="modal-body">

            <?php foreach($orderDetails['MedicalProductOrderDetail'] as $list){ ?>
                    <div class="row addedRowSearch">

                        <div class="input number col-md-2">
                            <label for="opdCharge">Product</label>
                            <input type="text" name="productName[]" autocomplete="off" value="[<?php echo $list['MedicalProduct']['id']; ?>]" class="form-control listHolderSearch" placeholder="Service" required="true">
                            <input type="hidden" name="productID[]" value="<?php echo $list['MedicalProduct']['id']; ?>" required="true">
                            <input type="hidden" name="orderDetailID[]" value="<?php echo $list['id']; ?>" required="true">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Quantity</label>
                            <input name="quantity[]" class="form-control" value="<?php echo $list['quantity']; ?>" min="1" value="1" placeholder="Quantity" required="true" type="number">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Discount</label>
                            <input name="discount[]" class="form-control" value="<?php echo $list['discount_value']; ?>" min="0" value="0" placeholder="Discount" type="number">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Discount Type</label>
                            <select name="discountType[]" class="form-control">
                                <option value="PERCENTAGE" <?php echo ($list['discount_type'] == 'PERCENTAGE')?'selected="selected"':''; ?>>Percentage</option>
                                <option value="AMOUNT" <?php echo ($list['discount_type'] == 'AMOUNT')?'selected="selected"':''; ?>>Amount</option>
                            </select>
                        </div>

                        <div class="input number col-md-1">
                            <label for="opdCharge">Tax Type</label>
                            <input type="text" readonly="readonly" value="<?php echo ($list['tax_type'] == 'NO_TEX')?'No Tax':$list['tax_type']; ?>" name="tax_type[]" class="form-control" value="0" required="true" placeholder="Tax Type">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Tax Value</label>
                            <input type="text" readonly="readonly" value="<?php echo $list['tax_value']; ?>" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value">
                        </div>

                        <div class="input number col-md-2">
                            <label for="opdCharge">Expiry</label>
                            <select name="medical_product_quantity_id[]" class="form-control">

                                <?php if( !empty($list['MedicalProduct']['MedicalProductQuantity'])){ ?>
                                    <?php foreach($list['MedicalProduct']['MedicalProductQuantity'] AS $qty){ ?>
                                        <option value="<?php echo $qty['id']; ?>" mrp="<?php echo $qty['mrp']; ?>" qty="<?php echo $qty['quantity']; ?>" sold="<?php echo $qty['sold']; ?>" <?php if($list['medical_product_quantity_id'] == $qty['id']){ echo 'selected="selected"'; } ?>>
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
                                        </option>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <option value="">Select</option>
                                <?php } ?>

                            </select>
                        </div>

                        <div class="input number col-md-1">
                            <label for="opdCharge">Price</label>
                            <input type="text" <?php echo ($list['MedicalProduct']['is_price_editable'] == 1)?"":"readonly='readonly'"; ?> value="<?php echo $list['product_price']; ?>" name="price[]" class="form-control" value="0" placeholder="Price">
                        </div>
                        <div class="input number col-md-1">
                            <label for="opdCharge">Amount</label>
                            <input type="text" readonly="readonly" value="<?php echo $list['total_amount']; ?>" name="amount[]" class="form-control" value="0" placeholder="Amount">
                        </div>

                        <div class="input col-md-1">
                            <button type="button" class="close removeRowSearch"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        </div>
                    </div>
            <?php } ?>

            <div class="addProductAppendSearch">

            </div>

            <div class="row" >
                <div class="input col-md-offset-11 col-md-1">
                    <button type="button" id="addMoreSearch" class="btn btn-primary">Add More</button>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <input type="hidden" name="orderID" value="<?php echo $orderDetails['MedicalProductOrder']['id']; ?>" id="orderIDSearch" required="true">
            <?php echo $this->Form->submit('Save',array('class'=>'Btn-typ3','id'=>'addPaySubmitSearch')); ?>
        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

<script>
    $(document).off('click','.removeRowSearch');
    $(document).on('click','.removeRowSearch',function () {
        if($('.addedRowSearch').length > 1)
        {
            $(this).parents('.addedRowSearch').remove();
        }
    });

    $(document).off('click','#addMoreSearch');
    $(document).on('click','#addMoreSearch',function () {
        var htmlToAppend = '<div class="row addedRow"><div class="input number col-md-2"><label for="opdCharge">Product</label><input type="text" name="productName[]" autocomplete="off" class="form-control  listHolderSearch" placeholder="Service" required="true"><input type="hidden" name="productID[]" required="true"></div><div class="input number col-md-1"><label for="opdCharge">Quantity</label><input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Discount</label><input name="discount[]" class="form-control" min="0" value="0" placeholder="Discount" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Discount Type</label><select name="discountType[]" class="form-control"><option value="PERCENTAGE">Percentage</option><option value="AMOUNT">Amount</option></select></div><div class="input number col-md-1"><label for="opdCharge">Tax Type</label><input type="text" readonly="readonly" name="tax_type[]" class="form-control" value="0" required="true" placeholder="Tax Type"></div><div class="input number col-md-1"><label for="opdCharge">Tax Value</label><input type="text" readonly="readonly" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value"></div><div class="input number col-md-2"><label for="opdCharge">Expiry</label><select name="medical_product_quantity_id[]" class="form-control"><option value="">Select</option></select></div><div class="input number col-md-1"><label for="opdCharge">Price</label><input type="text" readonly="readonly" name="price[]" class="form-control" value="0" placeholder="Price"></div><div class="input number col-md-1"><label for="opdCharge">Amount</label><input type="text" readonly="readonly" name="amount[]" class="form-control" value="0" placeholder="Amount"></div><div class="input col-md-1"><button type="button" class="close removeRow"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div></div>';
        $(".addProductAppendSearch").append(htmlToAppend).find('input[name="productName[]"]').last().focus();
    });




</script>