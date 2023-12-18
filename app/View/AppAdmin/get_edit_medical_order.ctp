<div class="modal fade" id="editPaymentModal" role="dialog" >
<?php
$login = $this->Session->read('Auth.User');
$doctor_list =$this->AppAdmin->getHospitalDoctorList($login['User']['thinapp_id']);
    $medicalProductData = $this->AppAdmin->get_app_medical_product_list($login['User']['thinapp_id'],true);
    echo $this->Html->script(array('magicsuggest-min.js'));
    echo $this->Html->css(array('magicsuggest-min.css'));
    $refferer_list=array();

$show_days_on_receipt = !empty($login['Thinapp']['show_number_of_days_on_receipt'])?$login['Thinapp']['show_number_of_days_on_receipt']:'NO';

?>

<style>

    #myModalPaymentTypeDetailAdd {
        z-index: 999999999;
    }

    .show_due_lbl{
        width: 100%;
        text-align: center;
        font-size: 18px;
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


    #editPaymentModal .ms-ctn .ms-sel-ctn{
        margin-left: 0px!important;
    }
    #editPaymentModal .modal-body .ms-ctn .ms-sel-item {
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

    #editPaymentModal .modal-body .col-md-1{
        width: 7% !important;
    }
    #editPaymentModal .modal-body .col-md-2{
        width: 10%;
    }
    #editPaymentModal .modal-body .row .col-md-2:first-child{
        width: 34%;
        height: 43px;
    }
    #editPaymentModal .modal-body .ms-ctn{
        position: relative;
        padding: 0px 8px;
        height: 35px;
    }



    #editAddOpdRowBtn{
        float: right;
        padding: 4px 3px;
        margin: 5px 0px;
        position: absolute;
        right: 15px;
        top: 0px;
    }


    #due_amount_edit{
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
    #editPaymentModal #AppointmentCustomerStaffServicePaymentStatus{
        height: 23px;
        margin: 0;
    }
    #editPaymentModal .detail_section{
        border-bottom: 1px solid #9d9d9d;
        background: #fafafa;
        margin-bottom: 10px;
    }

</style>
<div class="modal-dialog modal-lg">
    <div class="modal-content" id="editPaymentModalBody" direct-billing="<?php echo @$orderDetails['MedicalProductOrder']['is_direct_billing']; ?>">
        <?php echo $this->Form->create('AppointmentCustomerStaffService',array( 'class'=>'form','id'=>'formPay')); ?>

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" style="text-align: center;font-weight: bold;">Edit Payment</h4>
        </div>
        <div class="modal-body">

            <?php if($orderDetails['MedicalProductOrder']['is_direct_billing']=="YES"){ ?>
            <div class="row detail_section" >
                <?php
                    $address_list =$this->AppAdmin->get_app_address($login['User']['thinapp_id']);
                    $refferer_list =$this->AppAdmin->get_refferer_name_list($login['User']['thinapp_id']);
                ?>
                <div class="col-md-3">
                    <label>Doctor:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <select name="appointment_staff_id" id="appointmentStaffID"   class="form-control">
                        <option value="">Please Select</option>
                        <?php foreach($doctor_list AS $docID => $docName ){ ?>
                            <option value="<?php echo $docID; ?>" <?php echo ($orderDetails['MedicalProductOrder']['appointment_staff_id']==$docID)?'selected':''; ?>><?php echo $docName; ?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Doctor Address:&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <select name="appointment_address_id" id="appointmentAddressID" required="required" class="form-control">
                        <option value="">Please Select</option>
                        <?php if(!empty($address_list)){ foreach ($address_list as $address_id => $address){ ?>
                            <option value="<?php echo $address_id;?>" <?php echo ($orderDetails['MedicalProductOrder']['appointment_address_id']==$address_id)?'selected':''; ?>><?php echo $address;?></option>
                        <?php }} ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Referred By(Name):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <input type="text" name="reffered_by_name" value="<?php echo @$orderDetails['MedicalProductOrder']['reffered_by_name']; ?>" id="edit_refferer_by_name" class="form-control" placeholder="Referred By(Name)">
                </div>
                <div class="col-md-3">
                    <label>Referred By(Mobile):&nbsp;&nbsp;&nbsp;&nbsp;</label><br>
                    <input type="text" id="edit_refferer_by_mobile" value="<?php echo @$orderDetails['MedicalProductOrder']['reffered_by_mobile']; ?>" class="form-control"  onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10" minlength="10" name="reffered_by_mobile" placeholder="Referred By(Mobile)">
                </div>

            </div>
            <?php } ?>

            <?php foreach($orderDetails['MedicalProductOrderDetail'] as $key => $list){ ?>

                <?php echo $this->Form->input('order_detail_id', array('type'=>'hidden', 'value'=>$list['id'],'label'=>false,'required'=>true)); ?>
                <?php echo $this->Form->input('show_into_receipt', array('type'=>'hidden','id'=>'edit_show_into_receipt', 'value'=>$list['show_into_receipt'],'label'=>false,'required'=>true)); ?>

                <?php if($orderDetails['MedicalProductOrder']['is_opd'] == 'Y'){ ?>
                    <?php if($list['medical_product_id'] == 0 && $key ==0 ){ ?>
                        <button type="button" style="display: <?php echo ($list['show_into_receipt']=='YES')?'none':'block'; ?>;" class="btn btn-default btn-xs editAddOpdRowBtn" id="editAddOpdRowBtn">
                            <i class="fa fa-plus"> OPD Service</i>
                        </button>
                        <div class="row edit_opd_row" data-width="<?php echo ($orderDetails['MedicalProductOrder']['is_direct_billing']=='YES')?'24%':''; ?>;" style="display:<?php echo ($list['show_into_receipt']=='YES')?'block':'none'; ?>;" data-default = "<?php echo $orderDetails['AppointmentCustomerStaffService']['AppointmentService']['service_amount']; ?>">
                            <div class="input number col-md-2" style="width:<?php echo ($show_days_on_receipt == 'YES')?'27%':'34%'; ?>">
                                <label for="opdCharge">Service/Product</label>
                                <input name="productName[]" type="text" readonly="readonly" value="OPD" class="form-control" placeholder="Service/Product">
                                <input type="hidden" name="productID[]" data-type="OPD" value="0" required="required">
                                <input type="hidden" name="medical_product_quantity_id[]"  value="0" required="required">

                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Quantity</label>
                                <input name="quantity[]" type="number" readonly="readonly" value="<?php echo $list['quantity']; ?>" class="form-control" min="1" value="1" placeholder="Quantity" required="true">
                            </div>

                            <div style="display:<?php echo ($show_days_on_receipt == 'YES')?'block':'none'; ?>" class="input number col-md-1 width-5">
                                <label for="day">Days</label>
                                <input name="days[]" class="form-control" readonly="readonly" min="1" value="<?php echo $list['days']; ?>" placeholder="" required="true" type="number">
                            </div>


                            <div class="input number col-md-1">
                                <label for="opdCharge">Discount</label>
                                <input type="number" value="<?php echo $list['discount_value']; ?>" name="discount[]" id="discount_opd"  class="form-control" min="0" value="0"  placeholder="Discount">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Dis. Type</label>
                                <select class="form-control" name="discountType[]" id="discount_opd_type" >
                                    <option value="PERCENTAGE" <?php echo ($list['discount_type'] == 'PERCENTAGE')?'selected="selected"':''; ?>>Percentage</option>
                                    <option value="AMOUNT" <?php echo ($list['discount_type'] == 'AMOUNT')?'selected="selected"':''; ?>>Amount</option>
                                </select>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Tax Type</label>
                                <input name="tax_type[]" type="text" readonly="readonly" class="form-control" value="No Tax" required="true" placeholder="Tax Type">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Tax Value</label>
                                <input name="tax_value[]" type="text" readonly="readonly" class="form-control" required="true" value="0" placeholder="Tax Value">
                            </div>
                            <div class="input number col-md-2">
                                <label for="opdCharge">Expiry</label>
                                <select name="quantity_id" disabled="disabled" class="form-control"><option value="">Select</option></select>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Price</label>
                                <?php echo $this->Form->input('amount', array('readonly'=>($orderDetails['AppointmentCustomerStaffService']['emergency_appointment']=='YES')?'readonly':'','name'=>'price[]','type'=>'text', 'value'=>$list['product_price'],'label'=>false,'min'=>"0" ,'required'=>true,'placeholder'=>'Price','class'=>'form-control ','id'=>"opdCharge")); ?>
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Amount</label>
                                <input  type="text" readonly="readonly" value="<?php echo $list['total_amount']; ?>" class="form-control" name="amount[]" id="amountOPD" placeholder="Amount">
                            </div>

                            <div class="input col-md-1">
                             <?php if($orderDetails['AppointmentCustomerStaffService']['emergency_appointment']=='NO'){ ?>
                                <button type="button" class="close removeRowAdd removeRow" id="editRemoveOpdRowBtn"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                             <?php } ?>
                            </div>

                        </div>
                    <?php }else{ ?>
                        <?php if($key ==1) echo '<div class="addProductAppend">' ?>
                                <div class="row addedRow">
                                    <div class="input number col-md-2" style="width:<?php echo ($show_days_on_receipt == 'YES')?'27%':'34%'; ?>;">
                                        <label for="opdCharge">Service/Product</label>
                                        <input type="text" name="productName[]" autocomplete="off" value="[<?php  echo $list['MedicalProduct']['id']; ?>]" class="form-control listHolder" placeholder="Service/Product" required="true">
                                        <input type="hidden" name="productID[]" value="<?php echo $list['MedicalProduct']['id']; ?>" required="required">
                                        <input type="hidden" name="orderDetailID[]" value="<?php echo $list['id']; ?>" required="true">
                                    </div>
                                    <div class="input number col-md-1">
                                        <label for="opdCharge">Quantity</label>
                                        <input name="quantity[]" class="form-control" value="<?php echo $list['quantity']; ?>" min="1" value="1" placeholder="Quantity" required="true" type="number">
                                    </div>
                                    <div style="display:<?php echo ($show_days_on_receipt == 'YES')?'block':'none'; ?>" class="input number col-md-1 width-5">
                                        <label for="day">Days</label>
                                        <input name="days[]" class="form-control" min="1" value="<?php echo $list['days']; ?>" placeholder="" required="true" type="number">
                                    </div>

                                    <div class="input number col-md-1">
                                        <label for="opdCharge">Discount</label>
                                        <input name="discount[]" class="form-control" value="<?php echo $list['discount_value']; ?>" min="0" value="0" placeholder="Discount" type="number">
                                    </div>
                                    <div class="input number col-md-1">
                                        <label for="opdCharge">Dis. Type</label>
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
                                        <input type="text" <?php echo ($list['MedicalProduct']['is_price_editable'] == 1)?"":'readonly="readonly"'; ?> value="<?php echo $list['product_price']; ?>" name="price[]" class="form-control" value="0" placeholder="Price">
                                    </div>
                                    <div class="input number col-md-1">
                                        <label for="opdCharge">Amount</label>
                                        <input type="text" readonly="readonly" value="<?php echo $list['total_amount']; ?>" name="amount[]" class="form-control" value="0" placeholder="Amount">
                                    </div>



                                    <div class="input col-md-1">
                                        <button type="button" class="close removeRow"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                                    </div>
                                </div>
                        <?php if($key+1 ==count($orderDetails['MedicalProductOrderDetail'])) echo '</div>' ?>
                    <?php } ?>
                    <?php }else{ ?>

                        <?php if($key ==0) echo '<div class="addProductAppend">' ?>
                        <div class="row addedRow">
                            <div class="input number col-md-2 service_name_div" style="width:<?php echo ($show_days_on_receipt == 'YES')?'27%':'34%'; ?>;">
                                <label for="opdCharge">Service</label>
                                <input type="text" name="productName[]" autocomplete="off" value="[<?php  echo $list['MedicalProduct']['id']; ?>]" class="form-control listHolder" placeholder="Service" required="true">
                                <input type="hidden" name="productID[]" value="<?php echo $list['MedicalProduct']['id']; ?>" required="required">
                                <input type="hidden" name="orderDetailID[]" value="<?php echo $list['id']; ?>" required="true">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Quantity</label>
                                <input name="quantity[]" class="form-control" value="<?php echo $list['quantity']; ?>" min="1" value="1" placeholder="Quantity" required="true" type="number">
                            </div>

                            <div style="display:<?php echo ($show_days_on_receipt == 'YES')?'block':'none'; ?>" class="input number col-md-1 width-5">
                                <label for="day">Days</label>
                                <input name="days[]" class="form-control" min="1" value="<?php echo $list['days']; ?>" placeholder="" required="true" type="number">
                            </div>

                            <div class="input number col-md-1">
                                <label for="opdCharge">Discount</label>
                                <input name="discount[]" class="form-control" value="<?php echo $list['discount_value']; ?>" min="0" value="0" placeholder="Discount" type="number">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Dis. Type</label>
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
                                <input type="text" <?php echo ($list['MedicalProduct']['is_price_editable'] == 1)?"":'readonly="readonly"'; ?> value="<?php echo $list['product_price']; ?>" name="price[]" class="form-control" value="0" placeholder="Price">
                            </div>
                            <div class="input number col-md-1">
                                <label for="opdCharge">Amount</label>
                                <input type="text" readonly="readonly" value="<?php echo $list['total_amount']; ?>" name="amount[]" class="form-control" value="0" placeholder="Amount">
                            </div>


                            <?php if($orderDetails['MedicalProductOrder']['is_direct_billing']=='YES'){ ?>
                            <div class="col-md-2">
                                <label>Select Doctor</label>
                                <select name="biller_appointment_staff_id[]" id="biller_appointment_staff_id" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach($doctor_list AS $docID => $docName ){ ?>
                                        <option value="<?php echo $docID; ?>" <?php echo ($list['appointment_staff_id']==$docID)?'selected':''; ?>><?php echo $docName; ?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <?php } ?>


                            <div class="input col-md-1">
                                <button type="button" class="close removeRow"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                            </div>
                        </div>
                        <?php if($key+1 ==count($orderDetails['MedicalProductOrderDetail'])) echo '</div>' ?>
                    <?php } ?>



            <?php } ?>

            <?php if(count($orderDetails['MedicalProductOrderDetail']) ==1 && $orderDetails['MedicalProductOrder']['is_opd'] == 'Y') echo '<div class="addProductAppend"></div>'; ?>


            <div class="row" >
                <div class="input col-md-offset-11 col-md-1">
                    <button type="button" style="margin-top: 15px;" id="addMore" class="btn btn-primary">Add More</button>
                    <span class="subText">Press '+'</span>
                </div>
            </div>


        </div>
        <div class="modal-footer">





            <?php
                    $total_due_paid = $this->AppAdmin->get_patient_amount_by_settle_id($orderDetails['MedicalProductOrder']['id']);
                    if(!empty($total_due_paid)){
                        $total_due_paid  =$total_due_paid['amount'];
                    }else{
                        $total_due_paid = 0;
                    }
            ?>
            <label class="show_due_lbl" id="due_amount_edit" data-value="<?php echo $total_due_paid; ?>"></label>


            <div class="input text  col-md-2 col-xs-offset-2">
                <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_id_edit','value'=>$orderDetails['MedicalProductOrder']['hospital_payment_type_id'],'type'=>'select','label'=>'Select Payment Mode','empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['User']['thinapp_id']),'class'=>'form-control cnt')); ?>
            </div>
            <div class="input text  col-md-2">
                <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_name_edit','type'=>'hidden','label'=>false,'value'=>$orderDetails['MedicalProductOrder']['payment_type_name'])); ?>

                <?php echo $this->Form->input('payment_description',array('value'=>$orderDetails['MedicalProductOrder']['payment_description'],'type'=>'text','label'=>'Payment Description','class'=>'form-control cnt')); ?>
            </div>

            <div class="input text  col-md-1">
                <?php echo $this->Form->input('display',array('readonly'=>'readonly','id'=>'total_display_edit','type'=>'text','label'=>'Total Amount','class'=>'form-control')); ?>
            </div>

            <div class="input text  col-md-1">
                <?php echo $this->Form->input('tot',array("autocomplete"=>"off",'value'=>$orderDetails['MedicalProductOrder']['total_amount'],'id'=>'total_lbl_edit','type'=>'text','label'=>'Paid Amount','required'=>'required','class'=>'form-control total_lbl_edit')); ?>
            </div>

            <?php if($orderDetails['MedicalProductOrder']['is_expense'] == "Y"){ ?>
                <!--div class="input text  col-md-1">
                    <lable style="font-size: 11px; font-weight: 600;">Amount Received?</lable>
                    <?php  echo $this->Form->input('payment_status', array('value'=>'PAID','type'=>'checkbox','checked'=>($orderDetails['MedicalProductOrder']['payment_status']=='PAID')?true:false,'label'=>false)); ?>
                </div-->
            <?php } ?>


            <div class="input text  col-md-1">
                <label style="display: block;width: 100%;">&nbsp;&nbsp;</label>
                <?php echo $this->Form->input('id', array('type'=>'hidden', 'value'=> base64_encode($orderDetails['MedicalProductOrder']['appointment_customer_staff_service_id']),'label'=>false,'required'=>true,'class'=>'form-control ','id'=>"idContainer")); ?>
                <input type="hidden" name="orderID" value="<?php echo $orderDetails['MedicalProductOrder']['id']; ?>" required="true">
                <button type="button" class="btn btn-info update_btn">Update</button>

            </div>


        </div>
        <?php echo $this->Form->end(); ?>
    </div>
</div>

    <script>

        setTimeout(function(){
            if($("#edit_refferer_by_name").length == 1){
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
                $( "#edit_refferer_by_name" ).autocomplete({
                    source: availableTags,
                    delay: 100,
                    classes: {
                        "ui-autocomplete": "highlight"
                    },
                    select: function( event, ui ) {
                        event.preventDefault();
                        var name = ui.item.value.split('-');
                        $("#edit_refferer_by_name").val(name[0]);
                        if(name[1]){
                            $("#edit_refferer_by_mobile").val(name[1]);
                        }else{
                            $("#edit_refferer_by_mobile").val('');
                        }

                    }
                });
            }
        },100);


        $(document).off('input','#total_lbl_edit');
        $(document).on('input','#total_lbl_edit',function () {
            var show_value = parseFloat($("#total_display_edit").val()) - parseFloat($("#total_lbl_edit").val());
            var tmp = show_value.toString().split(".");
            if(tmp.length ==2){
                show_value = tmp[0]+'.'+tmp[1].toString().substr(0, 2);
            }

            if(parseFloat($("#total_display_edit").val()) ==parseFloat($("#total_lbl_edit").val())){
                show_value = 0;
            }else if(!show_value || show_value==""){
                show_value = parseFloat($("#total_display_edit").val());
            }

            var due = parseFloat($("#due_amount_edit").attr('data-value'));
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



        setTimeout(function(){
            $("#total_lbl_edit").trigger("input");
        },1000);


        function clearSelection(ms,obj){

            $(obj).find('input[name="productID[]"]').val("");
            $(obj).find('input[name="price[]"]').val(0);
            $(obj).find('input[name="amount[]"]').val(0);
            $(obj).find('input[name="tax_type[]"]').val("");
            $(obj).find('input[name="tax_value[]"]').val(0);
            $(obj).find('input[name="discountType[]"]').val("");
            $(obj).find('input[name="quantity[]"]').val(1);
            $(obj).find('input[name="discount[]"]').val(0);
            ms.removeFromSelection(ms.getSelection(), true);
            updateTotalEdit();
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
                setTimeout(function(){updateTotalEdit();},500);
            });
        }

        var dialog_obj =null;


        function triggerOnSelectProductEdit($this){

            if(!dialog_obj && $($this).find('input[name="quantity[]"]').attr('allow_add') != 'YES'){
                var quantity = $($this).find('input[name="quantity[]"]').val();
                var availableQty = $($this).find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('qty');
                var sold = $($this).parent().find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('sold');
                var totalUnsold = $($this).find('select[name="medical_product_quantity_id[]"]').find(":selected").attr('total_qty');
                var avalQty = (availableQty - sold);
                //console.log(totalUnsold+','+avalQty);
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
                                dialog_obj = null;
                                updateTotalEdit();
                            }
                        });
                    }else{
                        updateTotalEdit();
                    }
                }else{
                    updateTotalEdit();
                }
            }else{
                updateTotalEdit();
            }

        }

        $("#editPaymentModal .listHolder").each(function(){

            var raw_obj = $(this).closest('.addedRow');
            var ms = $(this).magicSuggest({
                allowFreeEntries: false,
                allowDuplicates: false,
                data:<?php echo json_encode($medicalProductData, true); ?>,
                maxDropHeight: 345,
                maxSelection: 1,
                required: true,
                noSuggestionText: '',
                useTabKey: true

            });
            $(ms).off('selectionchange');
            $(ms).on('selectionchange', function (e, m) {

                var IdArr = this.getSelection();
                if(IdArr.length > 0){
                    var allow_add_service= true;
                    $('input[name="productID[]"]').each(function (index,value) {
                        if($(this).val() == IdArr[0].id ){
                            allow_add_service = false;
                        }
                    });
                    if(allow_add_service===true){
                        $this = $(this.input[0]).closest('.form-control');

                        $($this).parent().nextAll().find('input[name="amount[]"]').val(0);
                        $($this).next('input[name="productID[]"]').val(0);
                        $($this).parent().nextAll().find('input[name="price[]"]').val(0);
                        $($this).parent().nextAll().find('input[name="tax_type[]"]').val("No Tax");
                        $($this).parent().nextAll().find('input[name="tax_value[]"]').val(0);
                        $($this).parent().nextAll().find('input[name="price[]"]').attr('readonly', true);
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

                            triggerOnSelectProductEdit($this);
                            updateTotalEdit();
                        }else{
                            triggerOnSelectProductEdit($this);
                            updateTotalEdit();
                        }
                    }else{
                        $.alert('This service already added')
                        clearSelection(ms,raw_obj);
                    }
                }else{

                    clearSelection(ms,raw_obj);
                }

            });
            $(ms).off('blur');
            $(ms).on('blur', function (c, e) {
                var selecteion = e.getSelection();
                if (!selecteion[0]) {
                    e.empty();
                }
            });


        });


        function addSuggestionToNewProduct(){
            var obj = $(".addProductAppend").find('input[name="productName[]"]').last();
            var raw_obj = $(".addedRow:last");
            var ms = $(obj).magicSuggest({
                allowFreeEntries:false,
                allowDuplicates:false,
                data:<?php echo json_encode($medicalProductData,true); ?>,
                maxDropHeight: 345,
                maxSelection: 1,
                required: true,
                noSuggestionText: '',
                useTabKey: true

            });
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

                            triggerOnSelectProductEdit($this);
                            updateTotalEdit();
                        }else{
                            triggerOnSelectProductEdit($this);
                            updateTotalEdit();
                        }
                    }else{
                        $.alert('This service already added')
                        clearSelection(ms,raw_obj);
                    }
                }else{
                    clearSelection(ms,raw_obj);
                }
            });
            $(ms).on('blur', function(c,e){
                var selecteion = e.getSelection();
                if(!selecteion[0])
                {
                    e.empty();
                }
            });
            $(ms).on('load', function(e,m){
                $(".ms-sel-ctn").last().find('input').focus();
            });


        }

        function clickEvent(e){
            var keyCode = (e.keyCode ? e.keyCode : e.which);

            if(keyCode==43){

                $("#addMore").trigger('click');
            }else if(keyCode == 45){
                e.preventDefault();
                $('.addProductAppend').find('.addedRow:last').remove();
                if($(".addProductAppend").find('input[name="productName[]"]').length ==0){
                    $("#opdChargeAdd").focus();
                }else{
                    $(".addProductAppend").find('input[name="productName[]"]').last().focus();
                }
                updateTotalEdit();
            }
        }

        $(document).off('keypress');
        $(document).on('keypress',  function (e) {
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if($('#editPaymentModalBody').is(':visible')) {
                clickEvent(e);
            }
        });



        $(document).off('click','.ms-close-btn');
        $(document).on('click','.ms-close-btn',function () {
            $(this).closest('.addedRow').find('input[productID[]]').val(0);
        });

        $(document).off('click','.removeRow');
        $(document).on('click','.removeRow',function () {
            $(this).parents('.addedRow').remove();
            updateTotalEdit();
        });



        $(document).off('click','#editRemoveOpdRowBtn');
        $(document).on('click','#editRemoveOpdRowBtn', function () {
            $(".edit_opd_row").hide();
            if(!$(".edit_opd_row").attr('data-default')){
                $(".edit_opd_row").attr('data-default',$(".edit_opd_row #editOpdChargeAdd").val());
            }

            $("#edit_show_into_receipt").val("NO");

            $(".edit_opd_row #opdCharge, .edit_opd_row #amountOPD").val(0);
            $("#editAddOpdRowBtn").show();
            updateTotalEdit();
        });

        $(document).off('click','#editAddOpdRowBtn');
        $(document).on('click','#editAddOpdRowBtn', function () {
            $(".edit_opd_row").show();
            var opd_amt = ($(".edit_opd_row").attr('data-default'))?$(".edit_opd_row").attr('data-default'):0;
            $(".edit_opd_row #opdCharge, .edit_opd_row #amountOPD").val(opd_amt);
            $("#editAddOpdRowBtn").hide();
            $("#edit_show_into_receipt").val("YES");

            updateTotalEdit();
        });

        var $show_days_on_receipt = "<?php echo $show_days_on_receipt; ?>";
        function rowAddEdit(){

           var doctor = "";
           if($("#editPaymentModalBody").attr('direct-billing')=="YES"){
                doctor = '<div class="col-md-2 doctor_section"><label>Select Doctor</label><select name="biller_appointment_staff_id[]" class="form-control"></select></div>';
           }

            var display = ($show_days_on_receipt=='YES')?'block':'none';
            var day_html = '<div style="display:'+display+';" class="input number col-md-1 width-5"><label for="day">Days</label><input name="days[]" class="form-control" min="1" value="1" placeholder="" required="true" type="number"></div>';
            var width = "34%";
            if(display=='block'){
                width= "27%";
            }
            var sty = "style='width:"+width+"'";

            var htmlToAppend = '<div class="row addedRow"><div class="input number col-md-2 service_name_div" '+sty+'><label for="opdCharge">Service/Product</label><input type="text" autocomplete="off" name="productName[]" class="form-control  listHolder" placeholder="Service/Product" required="true"><input type="hidden" name="productID[]" required="required"></div><div class="input number col-md-1"><label for="opdCharge">Quantity</label><input name="quantity[]" class="form-control" min="1" value="1" placeholder="Quantity" required="true" type="number"></div>'+day_html+'<div class="input number col-md-1"><label for="opdCharge">Discount</label><input name="discount[]" class="form-control" min="0" value="0" placeholder="Discount" type="number"></div><div class="input number col-md-1"><label for="opdCharge">Dis. Type</label><select name="discountType[]" class="form-control"><option value="PERCENTAGE">Percentage</option><option value="AMOUNT">Amount</option></select></div><div class="input number col-md-1"><label for="opdCharge">Tax Type</label><input type="text" readonly="readonly" name="tax_type[]" class="form-control" value="0" required="true" placeholder="Tax Type"></div><div class="input number col-md-1"><label for="opdCharge">Tax Value</label><input type="text" readonly="readonly" name="tax_value[]" class="form-control" required="true" value="0" placeholder="Tax Value"></div><div class="input number col-md-2"><label for="opdCharge">Expiry</label><select name="medical_product_quantity_id[]" class="form-control"><option value="">Select</option></select></div><div class="input number col-md-1"><label for="opdCharge">Price</label><input type="text" readonly="readonly" name="price[]" class="form-control" value="0" placeholder="Price"></div><div class="input number col-md-1"><label for="opdCharge">Amount</label><input type="text" readonly="readonly" name="amount[]" class="form-control" value="0" placeholder="Amount"></div>'+doctor+'<div class="input col-md-1"><button type="button" class="close removeRow"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button></div></div>';
            $(".addProductAppend").append(htmlToAppend).find('input[name="productName[]"]').last().focus();
            $(".addProductAppend").find('input[name="productName[]"]').last().val('');
            if($("#editPaymentModalBody").attr('direct-billing')=="YES"){
                $(".service_name_div:last").css('width',"24%");
            }
            add_doctor_to_dropdown($(".addedRow:last").find('select[name="biller_appointment_staff_id[]"]'));
            addSuggestionToNewProduct();
        }
        $(document).off('click','#addMore');
        $(document).on('click','#addMore',function () {
            rowAddEdit();
        });


        if($('#biller_appointment_staff_id').length > 0){
            $(".service_name_div").css('width',"24%");
        }



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
            var row = $(this).closest('.edit_opd_row, .addedRow');
            triggerOnOtherElement(row);
            triggerOnSelectProductEdit(row);
        });


        /*$(document).off('keypress, change  keyup','#editPaymentModalBody .modal-body input, #editPaymentModalBody .modal-body select');
        $(document).on('keypress, change  keyup','#editPaymentModalBody .modal-body input, #editPaymentModalBody .modal-body select',function () {
            var row = $(this).closest('.edit_opd_row, .addedRow');
            triggerOnOtherElementEdit(row);
        });
        */


        $(document).off('change','#p_type_id_edit');
        $(document).on('change','#p_type_id_edit',function(e){
            $('#p_type_name_edit').val($('#p_type_id_edit option:selected').text());

            var paymentTypeID = $('#p_type_id_edit').val();
            $("#paymentTypeDetailForm").trigger("reset");
            $('[name="card_no"]').val("");
            $('[name="holder_name"]').val("");
            $('[name="valid_upto"]').val("");
            $('[name="transaction_id"]').val("");
            $('[name="bank_account"]').val("");
            $('[name="beneficiary_name"]').val("");
            $('[name="txn_no"]').val("");
            $('[name="mobile_no"]').val("");
            $('[name="remark"]').val("");

            if(paymentTypeID == '<?php echo $orderDetails['MedicalProductOrder']['hospital_payment_type_id']; ?>')
            {
                $('[name="card_no"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['card_no'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['card_no']:""; ?>");
                $('[name="holder_name"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['holder_name'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['holder_name']:""; ?>");
                $('[name="valid_upto"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['valid_upto'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['valid_upto']:""; ?>");
                $('[name="transaction_id"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['transaction_id'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['transaction_id']:""; ?>");
                $('[name="bank_account"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['bank_account'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['bank_account']:""; ?>");
                $('[name="beneficiary_name"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['beneficiary_name'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['beneficiary_name']:""; ?>");
                $('[name="txn_no"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['txn_no'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['txn_no']:""; ?>");
                $('[name="mobile_no"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['mobile_no'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['mobile_no']:""; ?>");
                $('[name="remark"]').val("<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['remark'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['remark']:""; ?>");
            }

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


        $(document).off('show.bs.modal','#editPaymentModal');
        $(document).on('show.bs.modal','#editPaymentModal', function () {
            $(document).off('keypress');
            $(document).on('keypress',function(e){
                if($('#editPaymentModal').is(':visible')) {

                    var keyCode = (e.keyCode ? e.keyCode : e.which);

                    if(keyCode==43){
                        e.preventDefault();
                        rowAddEdit();

                    }else if(keyCode == 45){
                        e.preventDefault();
                        var obj = $('.removeRow:last');
                        $(obj).trigger('click');

                    }
                }
            });
        });






        function triggerOnOtherElementEdit(form){
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
            });
            setTimeout(function(){updateTotalEdit();},500);
        }

        var form_loaded = false;
        function updateTotalEdit(){
            var total = 0;
            $( "#editPaymentModalBody input[name='amount[]']" ).each(function( index ) {
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

            var due = parseFloat($("#due_amount_edit").attr('data-value'));

            if(form_loaded===true){
                $('#total_display_edit, #total_lbl_edit').val(total+due);
                $("#total_lbl_edit").trigger('input');

            }else{
                $('#total_display_edit').val(total+due);
                form_loaded = true;

            }




        }




         $(document).off('input change','#opdCharge, #discount_opd, #discount_opd_type');
        $(document).on('input change','#opdCharge, #discount_opd, #discount_opd_type',function(e){
            var row_obj = $(".edit_opd_row");
            var qty = $(row_obj).find("input[name='quantity[]']").val();

            var price = $(row_obj).find("input[name='price[]']").val();
            var discount = $(row_obj).find("input[name='discount[]']").val();
            var discount_type = $("#discount_opd_type").val();
            if(discount_type=="PERCENTAGE"){
                price = price * qty;
                discount = ( price * discount )/100;
                price = price - discount;
            }else{
                price = price * qty;
                price = price - discount;
            }
            $(row_obj).find("input[name='amount[]']").val(parseFloat(price));

            updateTotalEdit();
        });

        var url = window.location.pathname.split("/");
        var action =url[url.length-1];
        $(document).off('click','.update_btn');
        $(document).on('click','.update_btn',function(e){
            e.preventDefault();
            if($(".addProductAppend .addedRow").length > 0 || $("#edit_show_into_receipt").val() =='YES' ){



                var submit = true;
                $("#formPay input[name='productID[]'").each(function( key, value ) {

                    if($(this).attr('data-type') != "OPD" && value.name == 'productID[]' && !(value.value > 0))
                    {
                        $.alert("Service name could not be empty!");
                        submit = false;
                        return false;
                    }else if(value.name == 'price[]' && !(value.value > 0)){
                        $.alert("Price could not be empty!");
                        submit = false;
                        return false;
                    }
                });

                if(submit == true){
                    if($("#total_lbl_edit").val()!=""){
                        var data = $( "#paymentTypeDetailForm,#formPay" ).serialize();
                        data += "&show_into_receipt=" + encodeURIComponent($("#edit_show_into_receipt").val());
                        var $btn = $(this);
                        $.ajax({
                            type:'POST',
                            url: baseurl+"app_admin/web_edit_order",
                            data:data,
                            beforeSend:function(){
                                $btn.button('loading').html('Wait...');
                            },
                            success:function(data){

                                var response = JSON.parse(data)
                                if(response.status == 1)
                                {

                                    $btn.button('reset');
                                    if(action=='add_appointment'){
                                        $("#appointment_date").trigger("changeDate");
                                    }else if(action=='view_staff_app_schedule'){
                                        $btn.hide();
                                        $(".appointment_search_bar #search_btn").trigger("click");
                                    }else if(action=='ipd'){
                                        $btn.hide();
                                        $("#amount_deposit").modal('hide');

                                    }else{
                                        window.location.reload();
                                    }



                                    var win = window.open(baseurl+"app_admin/print_invoice/"+response.data_id+"/"+response.module, '_blank');
                                    if (win) {
                                        win.focus();
                                    } else {
                                        alert('Please allow popups for this website');
                                    }


                                    $("#editPaymentModal").modal('hide');



                                }
                                else
                                {
                                    $btn.attr('disabled',false);
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
                $.alert('Please enter at least one service');
            }



        });

        setTimeout(function(){
            updateTotalEdit();
        },100);




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
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['card_no'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['card_no']:""; ?>" class="form-control" name="card_no">
                            </div>
                            <div class="input number col-md-6 holder_name payment_detail_input">
                                <label for="opdCharge">Holder Name</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['holder_name'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['holder_name']:""; ?>" class="form-control" name="holder_name">
                            </div>
                            <div class="input number col-md-6 valid_upto payment_detail_input">
                                <label for="opdCharge">Valid Upto</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['valid_upto'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['valid_upto']:""; ?>" class="form-control" name="valid_upto">
                            </div>
                            <div class="input number col-md-6 transaction_id payment_detail_input">
                                <label for="opdCharge">Transaction ID</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['transaction_id'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['transaction_id']:""; ?>" class="form-control" name="transaction_id">
                            </div>
                            <div class="input number col-md-6 bank_account payment_detail_input">
                                <label for="opdCharge">Bank Account</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['bank_account'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['bank_account']:""; ?>" class="form-control" name="bank_account">
                            </div>
                            <div class="input number col-md-6 beneficiary_name payment_detail_input">
                                <label for="opdCharge">Beneficiary Name</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['beneficiary_name'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['beneficiary_name']:""; ?>" class="form-control" name="beneficiary_name">
                            </div>
                            <div class="input number col-md-6 txn_no payment_detail_input">
                                <label for="opdCharge">Txn No.</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['txn_no'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['txn_no']:""; ?>" class="form-control" name="txn_no">
                            </div>
                            <div class="input number col-md-6 mobile_no payment_detail_input">
                                <label for="opdCharge">Mobile No</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['mobile_no'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['mobile_no']:""; ?>" class="form-control" name="mobile_no">
                            </div>
                            <div class="input number col-md-6 remark payment_detail_input">
                                <label for="opdCharge">Remark</label>
                                <input type="text" value="<?php echo isset($paymentTypeDetail['HospitalPaymentTypeDetail']['remark'])?$paymentTypeDetail['HospitalPaymentTypeDetail']['remark']:""; ?>" class="form-control" name="remark">
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