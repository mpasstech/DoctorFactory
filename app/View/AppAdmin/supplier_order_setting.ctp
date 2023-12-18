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

                                    <div class="form-group">
                                        <div class="col-md-12 field_button_holder">
                                            <div class="col-md-2 field_button">
                                                <button type="button" id="addTextField" class="add-field-btn btn btn-success"  ><i class="fa fa-plus fa-x"></i> Text</button>
                                            </div>
                                            <div class="col-md-2 field_button">
                                                <button type="button" id="addDescriptionField" class="add-field-btn btn btn-success"  ><i class="fa fa-plus fa-x"></i> Description</button>
                                            </div>
                                            <div class="col-md-2 field_button">
                                                <button type="button" id="addCheckboxField" class="add-field-btn btn btn-success"  ><i class="fa fa-plus fa-x"></i> Checkbox</button>
                                            </div>
                                            <div class="col-md-2 field_button">
                                                <button type="button" id="addRadioField" class="add-field-btn btn btn-success"  ><i class="fa fa-plus fa-x"></i> Radio</button>
                                            </div>
                                            <div class="col-md-2 field_button">
                                                <button type="button" id="addAttachmentField" class="add-field-btn btn btn-success"  ><i class="fa fa-plus fa-x"></i> Attachment</button>
                                            </div>
                                            <div class="col-md-2 field_button">
                                                <button type="button" id="addTeethNumberField" class="add-field-btn btn btn-success"  ><i class="fa fa-plus fa-x"></i> Teeth Num.</button>
                                            </div>
                                        </div>
                                    </div>

                            <?php echo $this->Form->create('DentalSupplierSetting',array('type'=>'file','id'=>'form_post','method'=>'post','class'=>'form-horizontal')); ?>

                            <div class="form-group form_holder">
                                <div class="col-md-12">
                                    <input type="hidden" id="ul1" name="ul1">
                                    <ul id="sortable1" class="connectedSortable col-md-6">

                                        <?php $checkboxSort = $radioSort = 0; ?>
                                        <?php foreach($formDetail AS $numType => $field){
                                            if ($numType % 2 != 0) {
                                                continue;
                                            }
                                            ?>

                                            <?php if($field['SupplierOrderForm']['supplier_text_textarea_field_id'] > 0){ ?>
                                                <?php if($field['SupplierTextTextareaField']['type'] == 'TEXT'){ ?>
                                                    <li class="col-md-12 ui-state-default">
                                                        <div class="col-md-12 text_field_holder holder">
                                                            <div class="col-md-10">
                                                                <input type="text" name="field[][text]" value="<?php echo $field['SupplierTextTextareaField']['name']; ?>" class="form-control cnt" placeholder="Text Title" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_text btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php }else if($field['SupplierTextTextareaField']['type'] == 'TEXTAREA'){ ?>
                                                    <li class="col-md-12 ui-state-default">
                                                        <div class="col-md-12 textarea_field_holder holder">
                                                            <div class="col-md-10">
                                                                <textarea name="field[][textarea]" class="form-control cnt" placeholder="Textarea Title" required><?php echo $field['SupplierTextTextareaField']['name']; ?></textarea>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_textarea btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            <?php } else if($field['SupplierOrderForm']['supplier_title_field_id'] > 0 && $field['SupplierTitleField']['type'] == 'CHECKBOX'){ ?>

                                                <?php $checkboxSort = $field['SupplierOrderForm']['sort_no']; ?>
                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 checkbox_holder holder">
                                                        <div class="col-md-12 checkbox_title_holder">
                                                            <div class="col-md-10">
                                                                <input type="text" name="field[][checkbox_title][<?php echo $checkboxSort; ?>]" value="<?php echo $field['SupplierTitleField']['name']; ?>" class="form-control cnt" value="" data-index="<?php echo $checkboxSort; ?>" placeholder="Checkbox Title">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_checkbox_title btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 checkbox_options_holder">

                                                            <?php foreach($field['SupplierTitleField']['SupplierCheckboxRadioField'] AS $checkboxField){ ?>
                                                                <div class="col-md-10 checkbox_option_holder">
                                                                    <div class="col-md-10" >
                                                                        <input type="text" name="option[checkbox_option][<?php echo $checkboxSort; ?>][]" value="<?php echo $checkboxField['name']; ?>" class="form-control cnt"  placeholder="Checkbox Option">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="remove_checkbox_option btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="add_checkbox_option btn-remove btn btn-success btn-xs"  ><i class="fa fa-plus fa-2x"></i>Add</button>
                                                        </div>
                                                    </div>
                                                </li>

                                            <?php } else if($field['SupplierOrderForm']['supplier_title_field_id'] > 0 && $field['SupplierTitleField']['type'] == 'RADIO'){ ?>
                                                <?php $radioSort = $field['SupplierOrderForm']['sort_no']; ?>


                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 radio_holder holder">
                                                        <div class="col-md-12 radio_title_holder">
                                                            <div class="col-md-10">
                                                                <input type="text" name="field[][radio_title][<?php echo $radioSort; ?>]" value="<?php echo $field['SupplierTitleField']['name']; ?>" class="form-control cnt" value="" data-index="<?php echo $radioSort; ?>" placeholder="Radio Title">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_radio_title btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 radio_options_holder">

                                                            <?php foreach($field['SupplierTitleField']['SupplierCheckboxRadioField'] AS $radioField){ ?>
                                                                <div class="col-md-10 radio_option_holder">
                                                                    <div class="col-md-10" >
                                                                        <input type="text" name="option[radio_option][<?php echo $radioSort; ?>][]" value="<?php echo $radioField['name']; ?>" class="form-control cnt"  placeholder="Radio Option">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="remove_radio_option btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="add_radio_option btn-remove btn btn-success btn-xs" ><i class="fa fa-plus fa-2x"></i>Add</button>
                                                        </div>
                                                    </div>
                                                </li>


                                            <?php } else if($field['SupplierOrderForm']['supplier_attachment_field_id'] > 0 ){ ?>
                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 attachment_field_holder holder">
                                                        <div class="col-md-10">
                                                            <input type="text" name="field[][attachment]" value="<?php echo $field['SupplierAttachmentField']['name']; ?>" class="form-control cnt" value="" placeholder="Attachment Title" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="remove_attachment btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php }else if($field['SupplierOrderForm']['is_supplier_teeth_number'] == 'YES'){ ?>
                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 teeth_number_field_holder holder">
                                                        <div class="form-group">
                                                            <div class="col-md-6 upper_right">
                                                                <label>Upper Right</label>
                                                                <div class="number col-sm-12">
                                                                    <input type="hidden" name="field[][teeth_num]" value="YES">
                                                                    <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 upper_left">
                                                                <label>Upper Left</label>
                                                                <div class="number col-sm-12">
                                                                    <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-6 upper_right">
                                                                <label>Lower Right</label>
                                                                <div class="number col-sm-12">
                                                                    <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 upper_left">
                                                                <label>Lower Left</label>
                                                                <div class="number col-sm-12">
                                                                    <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-offset-10 col-md-2">
                                                            <button type="button" class="remove_teeth_number btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>

                                        <?php } ?>
                                    </ul>
                                    <input type="hidden" id="ul2" name="ul2">
                                    <ul id="sortable2" class="connectedSortable col-md-6">

                                        <?php foreach($formDetail AS $numType => $field){
                                            if ($numType % 2 == 0) { continue; }
                                            ?>

                                            <?php if($field['SupplierOrderForm']['supplier_text_textarea_field_id'] > 0){ ?>
                                                <?php if($field['SupplierTextTextareaField']['type'] == 'TEXT'){ ?>
                                                    <li class="col-md-12 ui-state-default">
                                                        <div class="col-md-12 text_field_holder holder">
                                                            <div class="col-md-10">
                                                                <input type="text" name="field[][text]" value="<?php echo $field['SupplierTextTextareaField']['name']; ?>" class="form-control cnt" placeholder="Text Title" required>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_text btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php }else if($field['SupplierTextTextareaField']['type'] == 'TEXTAREA'){ ?>
                                                    <li class="col-md-12 ui-state-default">
                                                        <div class="col-md-12 textarea_field_holder holder">
                                                            <div class="col-md-10">
                                                                <textarea name="field[][textarea]" class="form-control cnt" placeholder="Textarea Title" required><?php echo $field['SupplierTextTextareaField']['name']; ?></textarea>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_textarea btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php } ?>
                                            <?php } else if($field['SupplierOrderForm']['supplier_title_field_id'] > 0 && $field['SupplierTitleField']['type'] == 'CHECKBOX'){ ?>

                                                <?php $checkboxSort = $field['SupplierOrderForm']['sort_no']; ?>
                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 checkbox_holder holder">
                                                        <div class="col-md-12 checkbox_title_holder">
                                                            <div class="col-md-10">
                                                                <input type="text" name="field[][checkbox_title][<?php echo $checkboxSort; ?>]" value="<?php echo $field['SupplierTitleField']['name']; ?>" class="form-control cnt" value="" data-index="<?php echo $checkboxSort; ?>" placeholder="Checkbox Title">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_checkbox_title btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 checkbox_options_holder">

                                                            <?php foreach($field['SupplierTitleField']['SupplierCheckboxRadioField'] AS $checkboxField){ ?>
                                                                <div class="col-md-10 checkbox_option_holder">
                                                                    <div class="col-md-10" >
                                                                        <input type="text" name="option[checkbox_option][<?php echo $checkboxSort; ?>][]" value="<?php echo $checkboxField['name']; ?>" class="form-control cnt"  placeholder="Checkbox Option">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="remove_checkbox_option btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="add_checkbox_option btn-remove btn btn-success btn-xs"  ><i class="fa fa-plus fa-2x"></i>Add</button>
                                                        </div>
                                                    </div>
                                                </li>

                                            <?php } else if($field['SupplierOrderForm']['supplier_title_field_id'] > 0 && $field['SupplierTitleField']['type'] == 'RADIO'){ ?>
                                                <?php $radioSort = $field['SupplierOrderForm']['sort_no']; ?>


                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 radio_holder holder">
                                                        <div class="col-md-12 radio_title_holder">
                                                            <div class="col-md-10">
                                                                <input type="text" name="field[][radio_title][<?php echo $radioSort; ?>]" value="<?php echo $field['SupplierTitleField']['name']; ?>" class="form-control cnt" value="" data-index="<?php echo $radioSort; ?>" placeholder="Radio Title">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="remove_radio_title btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-10 radio_options_holder">

                                                            <?php foreach($field['SupplierTitleField']['SupplierCheckboxRadioField'] AS $radioField){ ?>
                                                                <div class="col-md-10 radio_option_holder">
                                                                    <div class="col-md-10" >
                                                                        <input type="text" name="option[radio_option][<?php echo $radioSort; ?>][]" value="<?php echo $radioField['name']; ?>" class="form-control cnt"  placeholder="Radio Option">
                                                                    </div>
                                                                    <div class="col-md-2">
                                                                        <button type="button" class="remove_radio_option btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                                    </div>
                                                                </div>
                                                            <?php } ?>

                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="add_radio_option btn-remove btn btn-success btn-xs" ><i class="fa fa-plus fa-2x"></i>Add</button>
                                                        </div>
                                                    </div>
                                                </li>


                                            <?php } else if($field['SupplierOrderForm']['supplier_attachment_field_id'] > 0 ){ ?>
                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 attachment_field_holder holder">
                                                        <div class="col-md-10">
                                                            <input type="text" name="field[][attachment]" value="<?php echo $field['SupplierAttachmentField']['name']; ?>" class="form-control cnt" value="" placeholder="Attachment Title" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="button" class="remove_attachment btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php }else if($field['SupplierOrderForm']['is_supplier_teeth_number'] == 'YES'){ ?>
                                                <li class="col-md-12 ui-state-default">
                                                    <div class="col-md-12 teeth_number_field_holder holder">
                                                        <div class="form-group">
                                                            <div class="col-md-6 upper_right">
                                                                <label>Upper Right</label>
                                                                <div class="number col-sm-12">
                                                                    <input type="hidden" name="field[][teeth_num]" value="YES">
                                                                    <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 upper_left">
                                                                <label>Upper Left</label>
                                                                <div class="number col-sm-12">
                                                                    <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-6 upper_right">
                                                                <label>Lower Right</label>
                                                                <div class="number col-sm-12">
                                                                    <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 upper_left">
                                                                <label>Lower Left</label>
                                                                <div class="number col-sm-12">
                                                                    <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $teethNumberData){ ?>
                                                                        <div id="ck-button">
                                                                            <label>
                                                                                <input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-offset-10 col-md-2">
                                                            <button type="button" class="remove_teeth_number btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>

                                        <?php } ?>
                                    </ul>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-sm-2 col-sm-offset-5">
                                    <?php echo $this->Form->submit('Update Order Form',array('class'=>'btn btn-primary','id'=>'btn_update')); ?>
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

    #sortable1 li{

        margin-top: 15px;
        border-radius:0.5em;
        padding:0px;
    }
    #sortable1{
        list-style-type: none;
    }

    #sortable2 li{

        margin-top: 15px;
        border-radius:0.5em;
        padding:0px;
    }
    #sortable2{
        list-style-type: none;
    }

    /*#sortable li:nth-child(even) {
        background: red;
        float: right;
    }
    #sortable li:nth-child(odd) {
        background: green;
        float: left;
    }*/



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
        width: 24px;

    }
    textarea.form-control {
        height: 115px;
    }

    .form_holder {

        padding-top: 109px;

    }
    .holder {

        padding-top: 15px;
        padding-bottom: 7px;

    }
    .col-md-2 {

        padding: 0px 5px 0px 5px;

    }
    .ui-state-highlight { height: 1.5em; line-height: 1.2em; }


</style>

<script>

    $(document).ready(function () {


        appendTo = "#sortable1";
        function updateAppendTo(){
            var sortable1 = $("#sortable1").height();
            var sortable2 = $("#sortable2").height();
            $("#ul1").val($("#sortable1 li").length);
            $("#ul2").val($("#sortable2 li").length);
            if((sortable1 - sortable2) >= 0)
            {
                console.log((sortable1 - sortable2));

                appendTo = "#sortable2";
            }
            else
            {
                appendTo = "#sortable1";
            }
        }
        updateAppendTo();

        $( "#sortable1, #sortable2" ).sortable({
            connectWith: ".connectedSortable",
            forceHelperSize: true,
            forcePlaceholderSize: true,
            revert: true,
            cursor: "move",
            tolerance: "pointer"
        }).disableSelection();



        $(document).on("click","#addTextField",function(){


            var htmlToAdd = '<li class="col-md-12 ui-state-default">'+
                '<div class="col-md-12 text_field_holder holder">'+
                    '<div class="col-md-10">'+
                        '<input type="text" name="field[][text]" class="form-control cnt" placeholder="Text Title" required>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<button type="button" class="remove_text btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                    '</div>'+
                '</div>'+
             '</li>';

            $(appendTo).append(htmlToAdd);
            updateAppendTo();
        });

        $(document).on("click",".remove_text",function(){
            $(this).parents(".ui-state-default").remove();
            updateAppendTo();
        });

        $(document).on("click","#addDescriptionField",function(){
            var htmlToAdd = '<li class="col-md-12 ui-state-default">'+
                '<div class="col-md-12 textarea_field_holder holder">'+
                    '<div class="col-md-10">'+
                        '<textarea name="field[][textarea]" class="form-control cnt" placeholder="Textarea Title" required></textarea>'+
                    '</div>'+
                    '<div class="col-md-2">'+
                        '<button type="button" class="remove_textarea btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                    '</div>'+
                '</div>'+
            '</li>';
            $(appendTo).append(htmlToAdd);
            updateAppendTo();
        });

        $(document).on("click",".remove_textarea",function(){
            $(this).parents(".ui-state-default").remove();
            updateAppendTo();
        });

        $(document).on("click","#addAttachmentField",function(){
            var htmlToAdd = '<li class="col-md-12 ui-state-default">'+
                                '<div class="col-md-12 attachment_field_holder holder">'+
                                    '<div class="col-md-10">'+
                                        '<input type="text" name="field[][attachment]" class="form-control cnt" value="" placeholder="Attachment Title" required>'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<button type="button" class="remove_attachment btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                                    '</div>'+
                                '</div>'+
                            '</li>';
            $(appendTo).append(htmlToAdd);
            updateAppendTo();
        });

        $(document).on("click",".remove_attachment",function(){
            $(this).parents(".ui-state-default").remove();
            updateAppendTo();
        });




        $(document).on("click","#addTeethNumberField",function(){
            var htmlToAdd = '<li class="col-md-12 ui-state-default">'+'<div class="col-md-12 teeth_number_field_holder holder">'+'<div class="form-group">'+
                            '<div class="col-md-6 upper_right">'+'<label>Upper Right</label>'+'<div class="number col-sm-12">'+'<input type="hidden" name="field[][teeth_num]" value="YES">'+
                            <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $teethNumberData){ ?>
                            '<div id="ck-button">'+'<label>'+'<input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>'+'</label>'+
                            '</div>'+
                            <?php } ?>
                            '</div>'+'</div>'+'<div class="col-md-6 upper_left">'+'<label>Upper Left</label>'+'<div class="number col-sm-12">'+
                            <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $teethNumberData){ ?>
                            '<div id="ck-button">'+'<label>'+'<input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>'+
                            '</label>'+'</div>'+
                            <?php } ?>
                            '</div>'+'</div>'+'</div>'+'<div class="form-group">'+'<div class="col-md-6 upper_right">'+'<label>Lower Right</label>'+
                            '<div class="number col-sm-12">'+
                            <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $teethNumberData){ ?>
                            '<div id="ck-button">'+'<label>'+'<input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>'+
                            '</label>'+'</div>'+
                            <?php } ?>
                            '</div>'+'</div>'+'<div class="col-md-6 upper_left">'+'<label>Lower Left</label>'+'<div class="number col-sm-12">'+
                            <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $teethNumberData){ ?>
                            '<div id="ck-button">'+'<label>'+'<input type="checkbox"><span><?php echo $teethNumberData['SupplierTeethNumber']['number']; ?></span>'+
                            '</label>'+'</div>'+
                            <?php } ?>
                            '</div>'+'</div>'+'</div>'+'<div class="col-md-offset-10 col-md-2">'+'<button type="button" class="remove_teeth_number btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                            '</div>'+'</div>'+'</li>';
            $(appendTo).append(htmlToAdd);
            updateAppendTo();
        });

        $(document).on("click",".remove_teeth_number",function(){
            $(this).parents(".ui-state-default").remove();
            updateAppendTo();
        });




        var checkboxIndex = <?php echo $checkboxSort+1; ?>;
        $(document).on("click","#addCheckboxField",function(){
            var htmlToAdd = '<li class="col-md-12 ui-state-default">'+
                            '<div class="col-md-12 checkbox_holder holder">'+
                            '<div class="col-md-12 checkbox_title_holder">'+
                            '<div class="col-md-10">'+
                            '<input type="text" name="field[][checkbox_title]['+checkboxIndex+']" class="form-control cnt" value="" data-index="'+checkboxIndex+'" placeholder="Checkbox Title">'+
                            '</div>'+
                            '<div class="col-md-2">'+
                            '<button type="button" class="remove_checkbox_title btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                            '</div>'+
                            '</div>'+
                            '<div class="col-md-10 checkbox_options_holder">'+
                            '<div class="col-md-10 checkbox_option_holder">'+
                            '<div class="col-md-10" >'+
                            '<input type="text" name="option[checkbox_option]['+checkboxIndex+'][]" class="form-control cnt"  placeholder="Checkbox Option">'+
                            '</div>'+
                            '<div class="col-md-2">'+
                            '<button type="button" class="remove_checkbox_option btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                            '</div>'+
                            '</div>'+
                            '</div>'+
                            '<div class="col-md-2">'+
                            '<button type="button" class="add_checkbox_option btn-remove btn btn-success btn-xs"  ><i class="fa fa-plus fa-2x"></i>Add</button>'+
                            '</div>'+
                            '</div>'+
                            '</li>';
            $(appendTo).append(htmlToAdd);
            updateAppendTo();
            checkboxIndex++;
        });

        $(document).on("click",".remove_checkbox_title",function(){
            $(this).parents(".ui-state-default").remove();
            updateAppendTo();
        });

        $(document).on("click",".remove_checkbox_option",function(){
            $(this).parents(".checkbox_option_holder").remove();
            updateAppendTo();
        });

        $(document).on("click",".add_checkbox_option",function(){
            var index = $(this).parent().siblings(".checkbox_title_holder").find('input').attr("data-index");
            console.log(index);
            var htmlToAdd = '<div class="col-md-10 checkbox_option_holder">'+
                '<div class="col-md-10" >'+
                '<input type="text" name="option[checkbox_option]['+index+'][]" class="form-control cnt"  placeholder="Checkbox Option">'+
                '</div>'+
                '<div class="col-md-2">'+
                '<button type="button" class="remove_checkbox_option btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                '</div>'+
                '</div>';
            $(this).parent().siblings(".checkbox_options_holder").append(htmlToAdd);
            updateAppendTo();
        });




        var radioIndex = <?php echo $radioSort+1; ?>;
        $(document).on("click","#addRadioField",function(){
            var htmlToAdd = '<li class="col-md-12 ui-state-default">'+
                '<div class="col-md-12 radio_holder holder">'+
                '<div class="col-md-12 radio_title_holder">'+
                '<div class="col-md-10">'+
                '<input type="text" name="field[][radio_title]['+radioIndex+']" class="form-control cnt" value="" data-index="'+radioIndex+'" placeholder="Radio Title">'+
                '</div>'+
                '<div class="col-md-2">'+
                '<button type="button" class="remove_radio_title btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                '</div>'+
                '</div>'+
                '<div class="col-md-10 radio_options_holder">'+
                '<div class="col-md-10 radio_option_holder">'+
                '<div class="col-md-10" >'+
                '<input type="text" name="option[radio_option]['+radioIndex+'][]" class="form-control cnt"  placeholder="Radio Option">'+
                '</div>'+
                '<div class="col-md-2">'+
                '<button type="button" class="remove_radio_option btn-remove btn btn-danger btn-xs"  ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                '</div>'+
                '</div>'+
                '</div>'+
                '<div class="col-md-2">'+
                '<button type="button" class="add_radio_option btn-remove btn btn-success btn-xs" ><i class="fa fa-plus fa-2x"></i>Add</button>'+
                '</div>'+
                '</div>'+
                '</li>';
            $(appendTo).append(htmlToAdd);
            updateAppendTo();
            radioIndex++;
        });

        $(document).on("click",".remove_radio_title",function(){
            $(this).parents(".ui-state-default").remove();
            updateAppendTo();
        });

        $(document).on("click",".remove_radio_option",function(){
            $(this).parents(".radio_option_holder").remove();
            updateAppendTo();
        });

        $(document).on("click",".add_radio_option",function(){
            var index = $(this).parent().siblings(".radio_title_holder").find('input').attr("data-index");
            var htmlToAdd = '<div class="col-md-10 radio_option_holder">'+
                '<div class="col-md-10" >'+
                '<input type="text" name="option[radio_option]['+index+'][]" class="form-control cnt" placeholder="Radio Option">'+
                '</div>'+
                '<div class="col-md-2">'+
                '<button type="button" class="remove_radio_option btn-remove btn btn-danger btn-xs" ><i class="fa fa-remove fa-2x"></i>Remove</button>'+
                '</div>'+
                '</div>';
            $(this).parent().siblings(".radio_options_holder").append(htmlToAdd);
            updateAppendTo();
        });


        $(document).on("submit","#form_post",function(e){
            e.preventDefault();
            var formData = new FormData(this);
            $btn = $("#btn_update");
            $.ajax({
                url: baseurl+'/app_admin/update_supplier_setting',
                type: 'POST',
                data: formData,
                beforeSend:function(){
                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
                },
                success: function (data) {
                    console.log(data);
                    $btn.button('reset');
                    var result = JSON.parse(data);
                    if(result.status = 1)
                    {
                        window.location = baseurl+'/app_admin/list_supplier_order'
                    }
                    else
                    {
                        alert(result.message);
                    }

                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


    });

</script>