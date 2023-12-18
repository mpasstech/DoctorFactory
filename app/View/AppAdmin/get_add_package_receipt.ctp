<?php
$login = $this->Session->read('Auth.User');
?>
<div class="clear"></div>

<div class="row">
    <label class="min_head">Billing Detail</label>
    <div class="form-group">

        <div class="item_div row">
            <div class="input number col-md-3">
                <label for="opdCharge">Service</label>
                <input type="text" name="productName" readonly="readonly" value="<?php echo $packageData['MedicalPackageDetail']['service']; ?>" autocomplete="off" class="form-control" required="true">
                <input type="hidden" name="packageDetailID" value="<?php echo $packageData['MedicalPackageDetail']['id']; ?>" required="true">
            </div>

            <div class="input number col-md-2">
                <label for="opdCharge">Tax Type</label>
                <input type="text" readonly="readonly" required="true" value="<?php echo isset($packageData['MedicalProduct']['HospitalTaxRate']['name'])?$packageData['MedicalProduct']['HospitalTaxRate']['name']:'No Tax'; ?>@<?php echo isset($packageData['MedicalProduct']['HospitalTaxRate']['rate'])?$packageData['MedicalProduct']['HospitalTaxRate']['rate']:0; ?>" name="tax_type" class="form-control" placeholder="Tax Type">
                <input type="hidden" value="<?php echo isset($packageData['MedicalProduct']['HospitalTaxRate']['rate'])?$packageData['MedicalProduct']['HospitalTaxRate']['rate']:0; ?>" id="taxRate">
            </div>

            <div class="input number col-md-2">
                <label for="opdCharge">Tax Value</label>
                <input type="text" readonly="readonly" required="true" id="taxValue" name="tax_value" class="form-control" value="0" placeholder="Tax Value">
            </div>

            <div class="input number col-md-2">
                <label for="opdCharge">Total Outstanding Amount</label>
                <input type="text" readonly="readonly" required="true" name="total_outstanding_amount" class="form-control" value="<?php echo $packageData['MedicalPackageDetail']['total_outstanding_amount']; ?>" placeholder="Price">
            </div>
            <div class="input number col-md-3">
                <label for="opdCharge">Amount</label>
                <input type="text" name="amount" class="form-control" id="amount_holder" value="0" placeholder="Amount">
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>

<div class="row" style="border-top: 1px solid #e2dede;">
    <div class="form-group">



        <div class="input text  col-md-2 col-xs-offset-4">
            <?php echo $this->Form->input('tot',array('readonly'=>'readonly','type'=>'text','label'=>'Total Amount','class'=>'form-control total_lbl')); ?>
        </div>

        <div class="input text  col-md-2">
            <?php echo $this->Form->input('hospital_payment_type_id',array('id'=>'p_type_id','type'=>'select','label'=>'Select Payment Mode','empty'=>'Cash','options'=>$this->AppAdmin->getHospitalPaymentTypeArray($login['Thinapp']['id']),'class'=>'form-control cnt')); ?>
        </div>


        <div class="input text  col-md-2">
            <?php echo $this->Form->input('payment_type_name',array('id'=>'p_type_name','type'=>'hidden','label'=>false,'value'=>'CASH')); ?>
            <?php echo $this->Form->input('payment_description',array('id'=>'p_desc','type'=>'text','label'=>'Payment Description','class'=>'form-control cnt')); ?>
        </div>


        <div class="input text  col-md-2">
            <label>&nbsp;</label>
            <?php echo $this->Form->submit('Save & Print Receipt',array('class'=>'btn btn-info','id'=>'addPaySubmitAdd')); ?>
        </div>
    </div>
</div>