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
    $receiptID = $orderData['TeethOrder']['order_number'];
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
            <td class="move_left"><h3><?php echo ucwords( $orderData['DentalSupplier']['name']); ?></h3></td>
            <td class="move_right contact_info">

                    <ul>
                        <?php if(!empty($orderData['DentalSupplier']['tel'])){ ?>
                            <li>Tel. : <?php echo $orderData['DentalSupplier']['tel']; ?></li>
                        <?php } ?>
                        <?php if(!empty($orderData['DentalSupplier']['whatsapp_mobile'])){ ?>
                            <li><i class="fa fa-whatsapp" aria-hidden="true"></i> : <?php echo $orderData['DentalSupplier']['whatsapp_mobile']; ?></li>
                        <?php } ?>
                        <?php if(!empty($orderData['DentalSupplier']['mobile'])){ ?>
                            <li>Mob. : <?php echo $orderData['DentalSupplier']['mobile']; ?></li>
                        <?php } ?>
                        <?php if(!empty($orderData['DentalSupplier']['email'])){ ?>
                            <li>Email. : <?php echo $orderData['DentalSupplier']['email']; ?></li>
                        <?php } ?>
                    </ul>

            </td>
        </tr>
        <tr>
            <td class="move_left"><b>Order No.</b> : <?php echo $receiptID; ?></td>
            <td class="move_right"><b>Date</b> : <?php echo date("d/m/Y",strtotime($orderData['TeethOrder']['order_number'])); ?></td>
        </tr>
        <tr>
            <td class="move_left"><b>Doctor/Clinic Name</b> : <?php echo $orderData['Thinapp']['name']; ?></td>

            <td class="move_right"><b>Patient Name</b> : <?php echo $orderData['TeethOrder']['patient_name']; ?></td>
        </tr>
        <tr>
            <td class="move_left"><b>Expected Delivery Date</b> : <?php echo date("d/m/Y",strtotime($orderData['TeethOrder']['expected_delivery_date'])); ?></td>
            <td class="move_right"><b>Gender</b> : <?php echo $orderData['TeethOrder']['gender']; ?></td>
        </tr>
        <tr>
            <td class="move_left"><b>MT</b> : <?php echo $orderData['TeethOrder']['mt']; ?></td>
            <td class="move_right"><b>BT</b> : <?php echo $orderData['TeethOrder']['bt']; ?></td>
        </tr>
        <tr>
            <td class="move_left"><b>Coping Trial</b> : <?php echo $orderData['TeethOrder']['coping_trial']; ?></td>
            <td class="move_right"><b>Finish</b> : <?php echo $orderData['TeethOrder']['finish']; ?></td>
        </tr>
    </table>


    <?php
        $numData = array("UPPER_LEFT"=>array(),"UPPER_RIGHT"=>array(),"LOWER_LEFT"=>array(),"LOWER_RIGHT"=>array(),);
        foreach($orderData['TeethNumberOrder'] AS $teethNum){
            $numData[$teethNum['type']][] = $teethNum['teeth_number_id'];
        }
    ?>

    <table class="teeth_number">
        <tr>
            <td class="order_form_heading" colspan="2"><b>Teeth Number</b></td>
        </tr>

        <tr>
            <td class="move_right teeth_num_tag">Upper Right</td>
            <td class="move_left teeth_num_tag">Upper Left</td>
        </tr>


        <tr>

            <td>
                <ul class="teeth_number_ul">
                    <?php foreach($teethNumberFormated['UPPER_RIGHT'] AS $data){ ?>
                        <li <?php echo (in_array($data['TeethNumber']['id'],$numData['UPPER_RIGHT']))?'class="selected"':''; ?> ><?php echo $data['TeethNumber']['number']; ?></li>
                    <?php } ?>
                </ul>

            </td>
            <td>

                <ul class="teeth_number_ul">
                    <?php foreach($teethNumberFormated['UPPER_LEFT'] AS $data){ ?>
                        <li <?php echo (in_array($data['TeethNumber']['id'],$numData['UPPER_LEFT']))?'class="selected"':''; ?> ><?php echo $data['TeethNumber']['number']; ?></li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
        <tr>
            <td class="move_right teeth_num_tag">Lower Left</td>
            <td class="move_left teeth_num_tag">Lower Right</td>
        </tr>
        <tr>
            <td>

                <ul class="teeth_number_ul">
                    <?php foreach($teethNumberFormated['LOWER_RIGHT'] AS $data){ ?>
                        <li <?php echo (in_array($data['TeethNumber']['id'],$numData['LOWER_RIGHT']))?'class="selected"':''; ?> ><?php echo $data['TeethNumber']['number']; ?></li>
                    <?php } ?>
                </ul>

            </td>

            <td>
                <ul class="teeth_number_ul">
                    <?php foreach($teethNumberFormated['LOWER_LEFT'] AS $data){ ?>
                        <li <?php echo (in_array($data['TeethNumber']['id'],$numData['LOWER_LEFT']))?'class="selected"':''; ?> ><?php echo $data['TeethNumber']['number']; ?></li>
                    <?php } ?>
                </ul>
            </td>
        </tr>
    </table>
    <table class="shade_table">
    <?php if(!empty($orderData['TeethShadeOrder'])){ ?>

            <?php
            $shade = array();
            foreach($orderData['TeethShadeOrder'] AS $data){
                $shade[] = $data['shade'];
            }
            ?>
            <tr>
                <td class="move_right"><b>Shade : </b></td>
                <td class="move_left"><?php echo ucwords(implode(",",$shade)); ?></td>
            </tr>
    <?php } ?>

        <?php if(!empty($orderData['TeethAttachmentOrder'])){ ?>

            <?php
            $attachment = array();
            foreach($orderData['TeethAttachmentOrder'] AS $data){
                $attachment[] = $data['name'];
            }
            ?>
            <tr>
                <td class="move_right"><b>Attachments : </b></td>
                <td class="move_left"><?php echo ucwords(implode(",",$attachment)); ?></td>
            </tr>
        <?php } ?>

    <?php if(!empty($orderData['TeethTextCategoryOrder'])){ ?>
            <?php
            $textCategory = array();
            foreach($orderData['TeethTextCategoryOrder'] AS $data){
                $textCategory[$data['teeth_text_category_name']][] = $data['teeth_text_sub_category_name'];
            }
            ?>

            <?php foreach($textCategory AS $key => $textCategoryVal){ ?>
                <tr>
                    <td class="move_right"><b><?php echo ucwords($key); ?> : </b></td>
                    <td class="move_left"><?php echo ucwords(implode(",",$textCategoryVal)); ?></td>
                </tr>
            <?php } ?>
    <?php } ?>

    <?php if(!empty($orderData['TeethImageCategoryOrder'])){ ?>
            <?php
            $imageCategory = array();
            foreach($orderData['TeethImageCategoryOrder'] AS $data){
                $imageCategory[$data['teeth_image_category_name']][] = "<img src='".$data['image_url']."' class='img_cat_icon'>&nbsp;&nbsp;".$data['teeth_image_sub_category_name'];
            }
            ?>

            <?php foreach($imageCategory AS $key => $imageCategoryVal){ ?>
                <tr>
                    <td class="move_right"><b><?php echo ucwords($key); ?> : </b></td>
                    <td class="move_left"><?php echo ucwords(implode(",",$imageCategoryVal)); ?></td>
                </tr>
            <?php } ?>


    <?php } ?>
        </table>

    <?php if(!empty($orderData['TeethOrder']['instructions'])){ ?>
        <table>
            <tr>
                <td class="order_form_heading"><b>Instructions</b></td>
            </tr>
            <tr>
                <td class="instruction_holder"><?php echo $orderData['TeethOrder']['instructions']; ?></td>
            </tr>
        </table>
    <?php } ?>


</div>

<?php if(!empty($orderData['TeethOrder']['attachment_url'])){ ?>
    <div class="attachment_holder">
        <a href="<?php echo $orderData['TeethOrder']['attachment_url'];?>" target="_blank">Click Here To View Attachment</a>
    </div>
<?php } ?>
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
        width: 211px;

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