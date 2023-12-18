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
                        <?php echo $this->element('app_admin_dental_supplires'); ?>


                        <div class="Social-login-box payment_bx">

                            <?php echo $this->element('message'); ?>

                            <?php echo $this->Form->create('DentalOrderForm',array('type'=>'file','id'=>'form_post','method'=>'post','class'=>'form-horizontal')); ?>

                            <div class="form-group">
                                <div class="col-sm-2">
                                    <label>Supplier</label>
                                    <select name="dental_supplier_id" class='form-control cnt' required>
                                        <?php foreach($dentalSupplier AS $suppID => $suppName){ ?>
                                            <option value="<?php echo $suppID; ?>"><?php echo $suppName; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Patient Name</label>
                                    <input type="text" name="patient_name" placeholder="Patient Name" class='form-control cnt' required>
                                </div>
                                <div class="col-sm-2">
                                    <label>Gender</label>
                                    <select name="gender" class='form-control cnt' required>
                                        <option value="MALE">Male</option>
                                        <option value="FEMALE">Female</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <label>Age</label>
                                    <input type="text" name="age" placeholder="Age" class='form-control cnt'>
                                </div>
                                <div class="col-sm-2">
                                    <label>Date</label>
                                    <input type="text" name="date" data-date-format="dd/mm/yyyy"  placeholder="Date" class='date form-control cnt' required>
                                </div>
                                <div class="col-sm-2">
                                    <label>Expected Delivery Date</label>
                                    <input type="text" name="expected_delivery_date" data-date-format="dd/mm/yyyy" placeholder="Expected Delivery Date" class='date form-control cnt'>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-3">
                                    <label>MT</label>
                                    <input type="text" name="mt" placeholder="MT" class='form-control cnt' >
                                </div>
                                <div class="col-sm-3">
                                    <label>BT</label>
                                    <input type="text" name="bt" placeholder="BT" class='form-control cnt' >
                                </div>
                                <div class="col-sm-3">
                                    <label>Coping Trial</label>
                                    <input type="text" name="coping_trial" placeholder="Coping Trial" class='form-control cnt' >
                                </div>
                                <div class="col-sm-3">
                                    <label>Finish</label>
                                    <input type="text" name="finish" placeholder="Finish" class='form-control cnt' >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 upper_right">
                                    <label>Upper Right</label>
                                    <div class="number col-sm-12">
                                        <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $teethNumberData){ ?>
                                                <div id="ck-button">
                                                    <label>
                                                        <input type="checkbox" name="teeth_number[]" value="<?php echo $teethNumberData['TeethNumber']['id']; ?>"><span><?php echo $teethNumberData['TeethNumber']['number']; ?></span>
                                                    </label>
                                                </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-sm-6 upper_left">
                                    <label>Upper Left</label>
                                    <div class="number col-sm-12">
                                        <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $teethNumberData){ ?>
                                                <div id="ck-button">
                                                    <label>
                                                        <input type="checkbox" name="teeth_number[]" value="<?php echo $teethNumberData['TeethNumber']['id']; ?>"><span><?php echo $teethNumberData['TeethNumber']['number']; ?></span>
                                                    </label>
                                                </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-6 upper_right">
                                    <label>Lower Right</label>
                                    <div class="number col-sm-12">
                                        <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $teethNumberData){ ?>
                                                <div id="ck-button">
                                                    <label>
                                                        <input type="checkbox" name="teeth_number[]" value="<?php echo $teethNumberData['TeethNumber']['id']; ?>"><span><?php echo $teethNumberData['TeethNumber']['number']; ?></span>
                                                    </label>
                                                </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-sm-6 upper_left">
                                    <label>Lower Left</label>
                                    <div class="number col-sm-12">
                                        <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $teethNumberData){ ?>
                                                <div id="ck-button">
                                                    <label>
                                                        <input type="checkbox" name="teeth_number[]" value="<?php echo $teethNumberData['TeethNumber']['id']; ?>"><span><?php echo $teethNumberData['TeethNumber']['number']; ?></span>
                                                    </label>
                                                </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group text_category_holder">

                                <?php
                                $size = sizeof($teethTextCategory);
                                if($size < 6)
                                {
                                    $offset = (6 - $size);
                                }
                                else
                                {
                                    $offset = 0;
                                }


                                foreach($teethTextCategory AS $teethTextCategoryData){ ?>
                                    <div class="<?php if($size != 1){ ?>col-md-2 col-md-offset-<?php echo $offset; ?> <?php } ?>table_text_category">
                                        <table>
                                            <tr><th><?php echo $teethTextCategoryData['TeethTextCategory']['name'];?></th></tr>
                                            <?php foreach($teethTextCategoryData['TeethTextSubCategory'] AS $teethTextSubCategoryData){ ?>
                                                <tr><td>
                                                        <label>
                                                            <input type="checkbox" name="teeth_text_sub_category[]" value="<?php echo $teethTextSubCategoryData['id']; ?>">&nbsp;<span><?php echo $teethTextSubCategoryData['name']; ?></span>
                                                        </label>
                                                    </td></tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                <?php $offset = 0; } ?>
                            </div>

                            <div class="form-group image_category_holder">

                                <?php
                                $size = sizeof($teethImageCategory);
                                if($size < 6)
                                {
                                    $offset = (6 - $size);
                                }
                                else
                                {
                                    $offset = 0;
                                }
                                foreach($teethImageCategory AS $teethImageCategoryData){ ?>
                                    <div class="<?php if($size != 1){ ?>col-md-2 col-md-offset-<?php echo $offset; ?> <?php } ?> table_image_category">
                                        <table>
                                            <tr><th colspan="2"><?php echo $teethImageCategoryData['TeethImageCategory']['name'];?></th></tr>
                                            <?php foreach($teethImageCategoryData['TeethImageSubCategory'] AS $teethImageSubCategoryData){ ?>
                                                <tr>
                                                    <td>
                                                        <label>
                                                            <input type="checkbox" name="teeth_image_sub_category[]" value="<?php echo $teethImageSubCategoryData['id']; ?>">&nbsp;<span><?php echo $teethImageSubCategoryData['name']; ?></span>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        <img src="<?php echo $teethImageSubCategoryData['image_url']; ?>" >
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                <?php $offset = 0; } ?>
                            </div>

                            <div class="form-group shade_holder">
                                <label>Shade</label>
                                <div class="shade">
                                    <?php foreach($teethShade AS $teethShadeData){ ?>
                                        <label>
                                            <input type="checkbox" name="teeth_shades[]" value="<?php echo $teethShadeData['TeethShade']['id']; ?>">&nbsp;<span><?php echo $teethShadeData['TeethShade']['shade']; ?></span>
                                        </label>&nbsp;&nbsp;&nbsp;
                                    <?php } ?>
                                </div>

                            </div>

                            <div class="form-group attachment_holder">
                                <label>Attachments</label>
                                <div class="attachment">
                                    <?php foreach($teethAttachment AS $teethAttachmentData){ ?>
                                        <label>
                                            <input type="checkbox" name="teeth_attachments[]" value="<?php echo $teethAttachmentData['TeethAttachment']['id']; ?>">&nbsp;<span><?php echo $teethAttachmentData['TeethAttachment']['name']; ?></span>
                                        </label>&nbsp;&nbsp;&nbsp;
                                    <?php } ?>
                                </div>

                            </div>

                            <div class="form-group instruction_holder">

                                <div class="col-md-6 col-md-offset-3">
                                    <label>Instructions</label>
                                    <textarea class="form-control cnt" name="instructions" placeholder="Instructions"></textarea>
                                </div>
                            </div>

                            <div class="form-group instruction_holder">

                                <div class="col-md-4 col-md-offset-4">
                                    <label>Attachment</label>
                                    <input type="file" class="form-control cnt" name="attachment_url" placeholder="Instructions">
                                </div>
                            </div>

                            <div class="form-group instruction_holder">
                                <label>Sent By</label>
                                <div class="sent">

                                    <label>
                                        <input type="checkbox" name="sent[]" value="SMS">&nbsp;<span>SMS</span>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="sent[]" value="WHATSAPP">&nbsp;<span>WhatsApp</span>
                                    </label>
                                    &nbsp;&nbsp;&nbsp;
                                    <label>
                                        <input type="checkbox" name="sent[]" value="EMAIL">&nbsp;<span>Email</span>
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

    #ck-button label input {
        margin-right:100px;
    }

    #ck-button {
        margin:4px;
        background-color:#EFEFEF;
        border-radius:4px;
        border:1px solid #D0D0D0;
        overflow:auto;
        float:left;
    }

    #ck-button:hover {
        background:red;
    }

    #ck-button label {
        float:left;
        width:4.0em;
        margin-bottom:0px;
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
            url: baseurl+'/app_admin/add_supplier_order',
            type: 'POST',
            data: formData,
            beforeSend:function(){
                $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-2x fa-fw">');
            },
            success: function (data) {
                //alert(data);
                var result = JSON.parse(data);
                if(result.status = 1)
                {
                    window.location = baseurl+'/app_admin/list_dental_supplier_order'
                }
                else
                {
                    alert(result.message);
                }
                $btn.button('reset');
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