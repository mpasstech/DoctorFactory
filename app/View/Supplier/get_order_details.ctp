<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        var baseurl = '<?php echo Router::url('/',true); ?>';
    </script>
    <?php  echo $this->Html->script(array('jquery.js','printThis.js')); ?>
    <?php  echo $this->Html->css(array('font-awesome.min.css')); ?>
    <style>
        .container {
            width: 650px;
            padding: 0;
            margin: 0;
            margin-left: auto;
            margin-right: auto;
        }

    </style>
    <?php
    $receiptID = $orderData['SupplierOrder']['order_no'];
    ?>

    <title>Order No.: <?php echo $receiptID; ?></title>
</head>
<body>


<div class="container">
    <br>

    <table class="supplier_details">
        <tr>
            <td class="order_form_heading" colspan="2"><b>ORDER FORM</b></td>
        </tr>
        <tr>
            <td class="move_left"><h3><?php echo ucwords( $orderData['SupplierHospital']['name']); ?></h3></td>
            <td class="move_right contact_info">

                    <ul>
                        <?php if(!empty($orderData['SupplierHospital']['tel'])){ ?>
                            <li>Tel. : <?php echo $orderData['SupplierHospital']['tel']; ?></li>
                        <?php } ?>
                        <?php if(!empty($orderData['SupplierHospital']['whatsapp_mobile'])){ ?>
                            <li><i class="fa fa-whatsapp" aria-hidden="true"></i> : <?php echo $orderData['SupplierHospital']['whatsapp_mobile']; ?></li>
                        <?php } ?>
                        <?php if(!empty($orderData['SupplierHospital']['mobile'])){ ?>
                            <li>Mob. : <?php echo $orderData['SupplierHospital']['mobile']; ?></li>
                        <?php } ?>
                        <?php if(!empty($orderData['SupplierHospital']['email'])){ ?>
                            <li>Email. : <?php echo $orderData['SupplierHospital']['email']; ?></li>
                        <?php } ?>
                    </ul>

            </td>
        </tr>
        <tr>
            <td class="move_left"><b>Order No.</b> : <?php echo $receiptID; ?></td>
            <td class="move_right"><b>Date</b> : <?php echo date("d/m/Y",strtotime($orderData['SupplierOrder']['date'])); ?></td>
        </tr>
        <tr>
            <td class="move_left"><b>Doctor/Clinic Name</b> : <?php echo $orderData['Thinapp']['name']; ?></td>

            <td class="move_right"><b>Patient Name</b> : <?php echo $orderData['SupplierOrder']['patient_name']; ?></td>
        </tr>
        <tr>
            <td class="move_left"><b>Expected Delivery Date</b> : <?php echo date("d/m/Y",strtotime($orderData['SupplierOrder']['expected_delivery_date'])); ?></td>
            <td class="move_right"><b>Gender</b> : <?php echo $orderData['SupplierOrder']['gender']; ?></td>
        </tr>
    </table>


    <table class="shade_table">
    <?php foreach($orderData['SupplierOrderDetail'] AS $orderDetail){
        if($orderDetail['is_supplier_teeth_number'] == 'YES')
        {
            $numData = array("UPPER_LEFT"=>array(),"UPPER_RIGHT"=>array(),"LOWER_LEFT"=>array(),"LOWER_RIGHT"=>array(),);
            foreach($orderDetail['SupplierTeethNumberOrder'] AS $teethNum){
                $numData[$teethNum['type']][] = $teethNum['supplier_teeth_number_id'];
            }
            ?>
                <tr>
                    <td class="order_form_heading no_border" colspan="2"><b>Teeth Number</b></td>
                </tr>

                <tr>
                    <td class="move_right teeth_num_tag no_border">Upper Right</td>
                    <td class="move_left teeth_num_tag no_border">Upper Left</td>
                </tr>


                <tr>

                    <td class="no_border">
                        <ul class="teeth_number_ul">
                            <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $data){ ?>
                                <li <?php echo (in_array($data['SupplierTeethNumber']['id'],$numData['UPPER_RIGHT']))?'class="selected"':''; ?> ><?php echo $data['SupplierTeethNumber']['number']; ?></li>
                            <?php } ?>
                        </ul>

                    </td>
                    <td class="no_border">

                        <ul class="teeth_number_ul">
                            <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $data){ ?>
                                <li <?php echo (in_array($data['SupplierTeethNumber']['id'],$numData['UPPER_LEFT']))?'class="selected"':''; ?> ><?php echo $data['SupplierTeethNumber']['number']; ?></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="move_right teeth_num_tag no_border">Lower Left</td>
                    <td class="move_left teeth_num_tag no_border">Lower Right</td>
                </tr>
                <tr>
                    <td class="no_border">

                        <ul class="teeth_number_ul">
                            <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $data){ ?>
                                <li <?php echo (in_array($data['SupplierTeethNumber']['id'],$numData['LOWER_RIGHT']))?'class="selected"':''; ?> ><?php echo $data['SupplierTeethNumber']['number']; ?></li>
                            <?php } ?>
                        </ul>

                    </td>

                    <td class="no_border">
                        <ul class="teeth_number_ul">
                            <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $data){ ?>
                                <li <?php echo (in_array($data['SupplierTeethNumber']['id'],$numData['LOWER_LEFT']))?'class="selected"':''; ?> ><?php echo $data['SupplierTeethNumber']['number']; ?></li>
                            <?php } ?>
                        </ul>
                    </td>
                </tr>
    <?php
        }
        else if($orderDetail['is_supplier_checkbox_radio'] == 'YES'){
            ?>
        <tr>
            <td class="move_right"><b><?php echo $orderDetail['title']; ?> : </b></td>
            <td class="move_left">
            <?php $detailToShow = array(); foreach($orderDetail['SupplierCheckboxRadioOrder'] AS $orderDetail){
                $detailToShow[] = $orderDetail['supplier_checkbox_radio_name'];
            }   echo ucwords(implode(",",$detailToShow)); ?>
            </td>
        </tr>
            <?php
        }
        else if($orderDetail['is_supplier_text_textarea'] == 'YES'){
            ?>
            <tr>
                <td class="move_right"><b><?php echo $orderDetail['title']; ?> : </b></td>
                <td class="move_left">
                    <?php $detailToShow = array(); foreach($orderDetail['SupplierTextTextareaFieldOrder'] AS $orderDetail){
                        $detailToShow[] = $orderDetail['response'];
                    }   echo ucwords(implode(",",$detailToShow)); ?>
                </td>
            </tr>
    <?php
        }
    }
    ?>
    </table>
</div>

<?php foreach($orderData['SupplierOrderDetail'] AS $orderDetail){
    if($orderDetail['is_supplier_attachment'] == 'YES'){ ?>
        <?php foreach($orderDetail['SupplierAttachmentOrder'] AS $attachment){ ?>
            <div class="attachment_holder">
                <a href="<?php echo $attachment['url']; ?>" target="_blank">Click Here To View <?php echo $attachment['name']; ?></a>
            </div>
        <?php } ?>
    <?php }
} ?>
<div class="note">
    Note: Press 'Enter' to print or click here
</div>


</body>

<style media="print">
    @page {
        size: A4;
        margin: 0;
        font-size: 10px;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    table{
        font-size: 10px;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
</style>

<style>
    .no_border {

        border: unset !important;
        border-right-color: unset;
        border-right-style: unset;
        border-right-width: unset;
        border-left-color: unset;
        border-left-style: unset;
        border-left-width: unset;
        border-left: 1px solid !important;
        border-right: 1px solid !important;

    }
    .note {

        text-align: center;
        color: red;
        font-weight: bold;
        cursor: pointer;
    }
    .order_form_heading {
        text-align: center;
    }
    .move_left{ text-align: left;}
    .move_right{ text-align: right;}
    .contact_info ul{ list-style-type:none; font-size: xx-small;}
    .container table {
        width: 100%;
    }
    .teeth_number_ul {

        list-style-type: none;
        width: 100%;
        display: inline-flex;

    }
    .teeth_number_ul li {

        width: 9%;
        text-align: center;
        border: 1px solid;

    }
    li.selected {

        background: gray;
        color: #FFF;
        border: none;

    }
    .teeth_num_tag {

        padding: 0px 15px 0px 15px;

    }
    .shade_table tbody tr td {

        width: 50%;

    }
    .text_category_table tbody tr td {

        width: 50%;

    }
    .image_category_table tbody tr td {

        width: 50%;

    }
    .instruction_holder{
        text-align:center;
    }
    .shade_table {
        border-collapse: collapse;
    }

    .shade_table td {
        border: 1px solid black;
    }
    .attachment_holder {

        padding: 0;
        margin: 0;
        margin-right: 0px;
        margin-left: 0px;
        margin-right: 0px;
        margin-left: 0px;
        margin-left: auto;
        margin-right: auto;
        width: 100%;
        text-align:center;

    }
    .attachment_holder a {

        color: red;

    }
</style>

<script>
    $(function () {
        $(document).keypress(function(e) {
            if(e.which == 13) {
                e.preventDefault();
                e.stopPropagation();
                $('.container').printThis({              // show the iframe for debugging
                    importCSS: true,            // import page CSS
                    importStyle: true,         // import style tags
                    pageTitle: "",              // add title to print page
                    removeInline: false,        // remove all inline styles from print elements
                    printDelay: 333,            // variable print delay; depending on complexity a higher value may be necessary
                    header: null,               // prefix to html
                    footer: null,               // postfix to html
                    base: false ,               // preserve the BASE tag, or accept a string for the URL
                    formValues: true,           // preserve input/form values
                    canvas: false,              // copy canvas elements (experimental)
                    doctypeString: ".",       // enter a different doctype for older markup
                    removeScripts: false,       // remove script tags from print content
                    copyTagClasses: false       // copy classes from the html & body tag
                });
            }
        });

        $(document).on('click',".note",function(e){
            e.preventDefault();
            e.stopPropagation();
            $('.container').printThis({              // show the iframe for debugging
                importCSS: true,            // import page CSS
                importStyle: true,         // import style tags
                pageTitle: "",              // add title to print page
                removeInline: false,        // remove all inline styles from print elements
                printDelay: 333,            // variable print delay; depending on complexity a higher value may be necessary
                header: null,               // prefix to html
                footer: null,               // postfix to html
                base: false ,               // preserve the BASE tag, or accept a string for the URL
                formValues: true,           // preserve input/form values
                canvas: false,              // copy canvas elements (experimental)
                doctypeString: ".",       // enter a different doctype for older markup
                removeScripts: false,       // remove script tags from print content
                copyTagClasses: false       // copy classes from the html & body tag
            });
        });
        $(document).keydown(function(e) {
            // ESCAPE key pressed
            var keyCode = (e.keyCode ? e.keyCode : e.which);
            if (keyCode == 27) {
                window.close();
            }
        });
    });




</script>


</html>