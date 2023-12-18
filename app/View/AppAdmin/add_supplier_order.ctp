<?php
$login = $this->Session->read('Auth.User');
?>

<div class="Home-section-2">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="container">
            <div class="row">
                <!--box 1 -->
                <!--box 1 -->
                <div class="middle-block">
                    <!-- Heading -->
                    <!--left sidebar-->

                    <?php // echo $this->element('admin_leftsidebar'); ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <?php echo $this->element('app_admin_supplires'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('OrderForm',array('type'=>'file','id'=>'form_post','method'=>'post','class'=>'form-horizontal')); ?>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <h3>Supplier*</h3>
                                    <select name="supplier_hospital_id" class='form-control cnt' required>
                                        <option value="">Select Supplier</option>
                                        <?php foreach($supplierHospital AS $suppID => $suppName){ ?>
                                            <option value="<?php echo $suppID; ?>"><?php echo $suppName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <h3>Patient Name*</h3>
                                    <input type="text" name="patient_name" placeholder="Patient Name" class='form-control cnt' required>
                                </div>
                                <div class="col-sm-2">
                                    <h3>Gender</h3>
                                    <select name="gender" class='form-control cnt' required>
                                        <option value="MALE">Male</option>
                                        <option value="FEMALE">Female</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <h3>Age</h3>
                                    <input type="text" name="age" placeholder="Age" class='form-control cnt'>
                                </div>
                                <div class="col-sm-2">
                                    <h3>Date</h3>
                                    <input type="text" name="date" data-date-format="dd/mm/yyyy"  placeholder="Date" class='date form-control cnt' required>
                                </div>
                                <div class="col-sm-2">
                                    <h5>Expected Delivery Date</h5>
                                    <input type="text" name="expected_delivery_date" data-date-format="dd/mm/yyyy" placeholder="Expected Delivery Date" class='date form-control cnt'>
                                </div>
                            </div>

                            <div class="form-group">
                                <div  class="col-md-12" >
                                    <div class="col-md-6">
                                        <?php foreach($formDetail AS $numType => $formField){
                                            if ($numType % 2 != 0) {
                                                continue;
                                            } ?>
                                            <?php if($formField['SupplierOrderForm']['is_supplier_teeth_number'] == 'YES'){ ?>
                                                <div class="col-md-6 teeth_container">
                                                    <div class="col-sm-6 upper_right">
                                                        <h3>Upper Right</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 upper_left">
                                                        <h3>Upper Left</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 upper_right">
                                                        <h3>Lower Right</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 upper_left">
                                                        <h3>Lower Left</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_text_textarea_field_id'] > 0 && $formField['SupplierTextTextareaField']['type'] == 'TEXT'){ ?>
                                                <div class="col-md-12">
                                                    <h3><?php echo $formField['SupplierTextTextareaField']['name'];?></h3>
                                                    <input type="text" class='form-control cnt' name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][text_textarea][<?php echo $formField['SupplierTextTextareaField']['id'];?>]">
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_text_textarea_field_id'] > 0 && $formField['SupplierTextTextareaField']['type'] == 'TEXTAREA'){ ?>
                                                <div class="col-md-12">
                                                    <h3><?php echo $formField['SupplierTextTextareaField']['name'];?></h3>
                                                    <textarea  class='form-control cnt' name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][text_textarea][<?php echo $formField['SupplierTextTextareaField']['id'];?>]"></textarea>
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_title_field_id'] > 0){ ?>
                                                <div class="col-md-12" style="text-align:center;">
                                                    <h3><?php echo $formField['SupplierTitleField']['name'];?></h3>
                                                    <?php foreach($formField['SupplierTitleField']['SupplierCheckboxRadioField'] AS $checkboxRadio){ ?>
                                                        <?php if($checkboxRadio['type'] == 'CHECKBOX'){ ?>
                                                            <div class="col-md-12" style="text-align:center;">
                                                                <label>
                                                                    <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][title_field_id][<?php echo $checkboxRadio['supplier_title_field_id']; ?>][]" value="<?php echo $checkboxRadio['id']; ?>" >&nbsp;&nbsp;<?php  echo $checkboxRadio['name']; ?>
                                                                </label>
                                                            </div>
                                                        <?php }else{ ?>
                                                            <div class="col-md-12" style="text-align:center;">
                                                                <label>
                                                                    <input type="radio" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][title_field_id][<?php echo $checkboxRadio['supplier_title_field_id']; ?>][]" value="<?php echo $checkboxRadio['id']; ?>" >&nbsp;&nbsp;<?php  echo $checkboxRadio['name']; ?>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_attachment_field_id'] > 0){ ?>
                                                <div class="col-md-12">
                                                    <h3><?php echo $formField['SupplierAttachmentField']['name'];?></h3>
                                                    <input type="file" class='form-control cnt' name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][attachment][<?php echo $formField['SupplierAttachmentField']['id'];?>]">

                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?php foreach($formDetail AS $numType => $formField){
                                            if ($numType % 2 == 0) {
                                                continue;
                                            } ?>
                                            <?php if($formField['SupplierOrderForm']['is_supplier_teeth_number'] == 'YES'){ ?>
                                                <div class="col-md-12 teeth_container">
                                                    <div class="col-sm-6 upper_right">
                                                        <h3>Upper Right</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 upper_left">
                                                        <h3>Upper Left</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 upper_right">
                                                        <h3>Lower Right</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 upper_left">
                                                        <h3>Lower Left</h3>
                                                        <div class="number col-sm-12">
                                                            <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $teethNumberData){ ?>
                                                                <div id="ck-button">
                                                                    <label>
                                                                        <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][teeth_number][]" value="<?php echo $teethNumberData['SupplierTeethNumber']['id']; ?>"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_text_textarea_field_id'] > 0 && $formField['SupplierTextTextareaField']['type'] == 'TEXT'){ ?>
                                                <div class="col-md-12">
                                                    <h3><?php echo $formField['SupplierTextTextareaField']['name'];?></h3>
                                                    <input type="text" class='form-control cnt' name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][text_textarea][<?php echo $formField['SupplierTextTextareaField']['id'];?>]">
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_text_textarea_field_id'] > 0 && $formField['SupplierTextTextareaField']['type'] == 'TEXTAREA'){ ?>
                                                <div class="col-md-12">
                                                    <h3><?php echo $formField['SupplierTextTextareaField']['name'];?></h3>
                                                    <textarea  class='form-control cnt' name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][text_textarea][<?php echo $formField['SupplierTextTextareaField']['id'];?>]"></textarea>
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_title_field_id'] > 0){ ?>
                                                <div class="col-md-12" style="text-align:center;">
                                                    <h3><?php echo $formField['SupplierTitleField']['name'];?></h3>
                                                    <?php foreach($formField['SupplierTitleField']['SupplierCheckboxRadioField'] AS $checkboxRadio){ ?>
                                                        <?php if($checkboxRadio['type'] == 'CHECKBOX'){ ?>
                                                            <div class="col-md-12" style="text-align:center;">
                                                                <label>
                                                                    <input type="checkbox" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][title_field_id][<?php echo $checkboxRadio['supplier_title_field_id']; ?>][]" value="<?php echo $checkboxRadio['id']; ?>" >&nbsp;&nbsp;<?php  echo $checkboxRadio['name']; ?>
                                                                </label>
                                                            </div>
                                                        <?php }else{ ?>
                                                            <div class="col-md-12" style="text-align:center;">
                                                                <label>
                                                                    <input type="radio" name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][title_field_id][<?php echo $checkboxRadio['supplier_title_field_id']; ?>][]" value="<?php echo $checkboxRadio['id']; ?>" >&nbsp;&nbsp;<?php  echo $checkboxRadio['name']; ?>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            <?php }
                                            else if($formField['SupplierOrderForm']['supplier_attachment_field_id'] > 0){ ?>
                                                <div class="col-md-12">
                                                    <h3><?php echo $formField['SupplierAttachmentField']['name'];?></h3>
                                                    <input type="file" class='form-control cnt' name="sort[<?php echo $formField['SupplierOrderForm']['sort_no']; ?>][attachment][<?php echo $formField['SupplierAttachmentField']['id'];?>]">

                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group instruction_holder">
                                <h3>Place Order Via</h3>
                                <div class="sent">

                                    <label>
                                        <input type="checkbox" name="sent[]" value="SMS" checked="checked">&nbsp;<span>SMS</span>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="sent[]" value="WHATSAPP"checked="checked">&nbsp;<span>WhatsApp</span>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="sent[]" value="EMAIL"checked="checked">&nbsp;<span>Email</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-5">
                                    <?php echo $this->Form->submit('Submit Order',array('class'=>'Btn-typ5','id'=>'btn_update')); ?>
                                </div>
                            </div>

                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
                <!-- box 1 -->
            </div>
            <!--box 2 -->
        </div>
    </div>
</div>

<style>
    .col-sm-2 h5 {

        line-height: 28px;

    }
    h5 {

        font-weight: bold;

    }
    label {
        font-weight: unset !important;
    }
    .shade label {

        font-weight: unset !important;

    }
    .attachment label {

        font-weight: unset !important;

    }
    .form-group.instruction_holder {

        text-align: center;

    }
    .sent label {

        font-weight: unset !important;

    }
    td img {

        width: 20px;

    }
    th{
        text-align: center;
        /*border: 2px solid;*/
    }
    td label {
        font-weight: unset !important;
    }
    .form-group.image_category_holder {

        text-align: center;

    }
    .table_image_category {

        display: inline-block;
        padding-left: 15px;

    }
    .form-group.text_category_holder {

       text-align: center;

    }
    .table_text_category {

        display: inline-block;
        padding-left: 15px;

    }
    .col-sm-6.upper_right {
        text-align: right;
    }
    .col-sm-6.upper_right .number #ck-button {
        float: right !important;
    }
    .col-sm-6.lower_right {
        text-align: right;
    }
    .col-sm-6.lower_right .number #ck-button {
        float: right !important;
    }
    .col-sm-6.upper_left {
        text-align: left;
    }
    .col-sm-6.lower_left {
        text-align: right;
    }
    .form-group.attachment_holder {
        text-align: center;
    }
    .form-group.shade_holder {
        text-align: center;
    }

    #ck-button:hover {
        background:red;
    }

    #ck-button label {
        float:left;
        width: 100%;
        margin-bottom:0px;
    }
    .btn.btn-primary.btn-xs {

        width: 69px;
        font-size: 10px;
        line-height: 2;
        font-weight: bold;

    }

    #ck-button label span {
        text-align:center;
        padding:3px 0px;
        display:block;
    }

    #ck-button label input {

        position: absolute;
        display: none;

    }

    #ck-button input:checked + span {
        background-color:#911;
        color:#fff;
    }
    .col-md-6.upper_right {

        text-align: right;

    }
    .upper_right > .number > #ck-button {

        float: right;

    }
    #ck-button {

        margin: 1px;
        background-color: #EFEFEF;
        border-radius: 4px;
        border: 1px solid #D0D0D0;
        overflow: auto;
        float: left;
        width: 22px;

    }
    textarea.form-control {
        height: 115px;
    }
    .col-md-6 > .col-md-12 {

        background: aliceblue;
        margin-top: 5px;
        width: 98%;
        margin-left: 1%;
        border-radius: .5em;

    }
    .teeth_container{padding-bottom: 20px;}
    .teeth_container{
        background: aliceblue;
        margin-top: 5px;
        width: 97%;
        margin-left: 1%;
        border-radius: .5em;
    }
</style>

<script>
    $(".date").datepicker('setDate',new Date());
    $(document).on("submit","#form_post",function(e){
        e.preventDefault();
        if(!confirm("Do you want to place this order?"))
        {
            return;
        }

        var formData = new FormData(this);
        $btn = $("#btn_update");
        $.ajax({
            url: baseurl+'/app_admin/save_supplier_order',
            type: 'POST',
            data: formData,
            beforeSend:function(){
                $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
            },
            success: function (data) {
                //alert(data);
                $btn.button('reset');
                var result = JSON.parse(data);
                if(result.status == 1)
                {
                    window.location = baseurl+'/app_admin/list_supplier_order'
                }
                else
                {
                    alert(result.message);
                }

            },
            error: function(err){
                $btn.button('reset');
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>