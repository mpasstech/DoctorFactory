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
        <?php
        $url = $this->request->url;
        $urlPrescrption = str_replace('print_mini_invoice','print_prescription',$url);
        $urlDetailReceipt = str_replace('print_mini_invoice','print_invoice',$url);
        ?>
        var prescriptionURL = '<?php echo Router::url('/',true); ?><?php echo $urlPrescrption; ?>';
        var detailReceiptURL = '<?php echo Router::url('/',true); ?><?php echo $urlDetailReceipt; ?>';
    </script>
    <?php  echo $this->Html->script(array('jquery.js','printThis.js')); ?>
    <?php  echo $this->Html->css(array('font-awesome.min.css')); ?>
    <style>
        body{
            font-size: 11px !important;
            font-weight: 500 !important;
        }
        .container {
            width: 80mm;
            padding: 0;
            margin: 0;
            margin-left: auto;
            margin-right: auto;
        }
        .logo_img img {
            height: 95px;
        }
        .container table {
            width: 100%;
            max-width: 100%;
        }
        .biller_detail_holder {
            width: 40%;
        }
        .user_biller_detail_gap{
            width: 20%;
        }
        .user_detail_holder{
            width: 40%;
        }
        body{
            font-size: 10px;
            font-family: sans-serif;
            font-weight: 500;
        }
        .biller_title {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            background-color: #A9A9A9 !important;
            color: #000000;
            padding: 2px 0px 2px 6px;
        }
        .patient_title{
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            background-color: #A9A9A9 !important;
            color: #000000;
            padding: 2px 0px 2px 6px;
        }
        .company_detail_holder{
            width: 60%;
        }
        .recept_detail_holder{
            width: 40%;
        }
        .title_holder {
            padding: 0px 0px 0px 6px;
        }
        .product_detail_holder tr th{
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            background-color: #A9A9A9 !important;
            color: #000000;
            padding: 2px 3px 2px 3px;
            border: 1px solid #77797c;
        }
        .product_detail_holder tr td{
            padding: 2px 3px 2px 3px;
            border-right: 1px solid #77797c;
            border-left: 1px solid #77797c;
        }
        .product_detail_holder tr:nth-child(even) {color-adjust: exact;-webkit-print-color-adjust: exact; background-color: #f2f2f2 !important;}
        .product_detail_holder {
            border-collapse: collapse;
            width: 100%;
        }

        .note {

            text-align: center;
            color: red;
            font-weight: bold;
            cursor: pointer;
        }
        .note-prescription{
            text-align: center;
            color: red;
            font-weight: bold;
            cursor: pointer;
        }
        .note-detail-receipt{
            text-align: center;
            color: red;
            font-weight: bold;
            cursor: pointer;
        }
        .footer_text{
            text-align: center;
        }
        .created_by{
            text-align: right;
        }
        .prescription_size {
            text-align: center;
        }
        h2 {

            line-height: 0px;

        }
        .logo_img img {
            margin-left: 21%;
        }
        .recept_detail_holder h2 {

            text-align: center;

        }

    </style>
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
        @media print {
            .container {
                width: 80mm;
                padding: 0;
                margin: 0;
                margin-left: auto;
                margin-right: auto;
            }
            .logo_img img {
                height: 95px;
            }
            .container table {
                width: 100%;
                max-width: 100%;
            }
            .biller_detail_holder {
                width: 40%;
            }
            .user_biller_detail_gap{
                width: 20%;
            }
            .user_detail_holder{
                width: 40%;
            }
            body{
                font-size: 10px;
                font-family: sans-serif;
                font-weight: 500;
            }
            .biller_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #000000 !important;
                padding: 2px 0px 2px 6px;
            }
            .patient_title{
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #000000 !important;
                padding: 2px 0px 2px 6px;
            }
            .company_detail_holder{
                width: 60%;
            }
            .recept_detail_holder{
                width: 40%;
            }
            .title_holder {
                padding: 0px 0px 0px 6px;
            }
            .product_detail_holder tr th{
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #000000 !important;
                padding: 2px 3px 2px 3px;
                border: 1px solid #77797c;
            }
            .product_detail_holder tr td{
                padding: 2px 3px 2px 3px;
                border-right: 1px solid #77797c;
                border-left: 1px solid #77797c;
            }
            .product_detail_holder tr:nth-child(even) {color-adjust: exact;-webkit-print-color-adjust: exact; background-color: #f2f2f2 !important;}
            .product_detail_holder {
                border-collapse: collapse;
                width: 100%;
            }

            .note {

                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }
            .note-prescription{
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }
            .note-detail-receipt{
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }
            .footer_text{
                text-align: center;
            }
            .created_by{
                text-align: right;
            }
            .prescription_size {
                text-align: center;
            }
            h2 {

                line-height: 0px;

            }
            .logo_img img {
                margin-left: 21%;
            }
            .recept_detail_holder h2 {

                text-align: center;

            }
        }

        @media print {
            .container {
                width: 80mm;
                padding: 0;
                margin: 0;
                margin-left: auto;
                margin-right: auto;
            }
            .logo_img img {
                height: 95px;
            }
            .container table {
                width: 100%;
                max-width: 100%;
            }
            .biller_detail_holder {
                width: 40%;
            }
            .user_biller_detail_gap{
                width: 20%;
            }
            .user_detail_holder{
                width: 40%;
            }
            body{
                font-size: 10px;
                font-family: sans-serif;
                font-weight: 500;
            }
            .biller_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #000000 !important;
                padding: 2px 0px 2px 6px;
            }
            .patient_title{
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #000000 !important;
                padding: 2px 0px 2px 6px;
            }
            .company_detail_holder{
                width: 60%;
            }
            .recept_detail_holder{
                width: 40%;
            }
            .title_holder {
                padding: 0px 0px 0px 6px;
            }
            .product_detail_holder tr th{
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #000000 !important;
                padding: 2px 3px 2px 3px;
                border: 1px solid #77797c;
            }
            .product_detail_holder tr td{
                padding: 2px 3px 2px 3px;
                border-right: 1px solid #77797c;
                border-left: 1px solid #77797c;
            }
            .product_detail_holder tr:nth-child(even) {color-adjust: exact;-webkit-print-color-adjust: exact; background-color: #f2f2f2 !important;}
            .product_detail_holder {
                border-collapse: collapse;
                width: 100%;
            }

            .note {

                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }
            .note-prescription{
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }
            .note-detail-receipt{
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }
            .footer_text{
                text-align: center;
            }
            .created_by{
                text-align: right;
            }
            .prescription_size {
                text-align: center;
            }
            h2 {

                line-height: 0px;

            }
            .logo_img img {
                margin-left: 21%;
            }
            .recept_detail_holder h2 {

                text-align: center;

            }
        }
    </style>

    <title>Receipt No.: <?php echo @$orderDetails[0]['unique_id']; ?></title>
</head>
<body>


<div class="container">
    <br>
    <table>
        <tr>
            <td align="center" colspan="2" valign="top">
                <h2><?php echo ($appointmentData['Thinapp']['receipt_header_title'] != '')?$appointmentData['Thinapp']['receipt_header_title']:$appointmentData['Thinapp']['name']; ?></h2>
            </td>
        </tr>

        <tr><td align="center" colspan="2" valign="top"><b>OPD Bill</b></td></tr>


                    <?php if($appointmentData['AppointmentCustomerStaffService']['queue_number'] > 0){ ?>
                        <tr><td align="left" class="title_holder"><b>Token:</b></td><td align="right"><b><?php echo $appointmentData['AppointmentCustomerStaffService']['queue_number']; ?></b></td></tr>
                        <tr><td align="left" class="title_holder"><b>Token Time:</b></td><td align="right"><b><?php echo $appointmentData['AppointmentCustomerStaffService']['slot_time']; ?></b></td></tr>
                    <?php } ?>
                    <tr><td align="left" class="title_holder"><b>UHID:</b></td><td align="right"><?php echo $UHID; ?></td></tr>
                    <tr><td align="left" class="title_holder"><b>Receipt No.:</b></td><td align="right"><?php if(isset($orderDetails['MedicalProductOrder'])){  echo @$orderDetails[0]['unique_id']; } else{ echo '-'; } ?></td></tr>
                    <tr><td align="left" class="title_holder"><b>Payment Mode:</b></td><td align="right"><?php if(isset($orderDetails['MedicalProductOrder']['payment_type_name'])){  echo $orderDetails['MedicalProductOrder']['payment_type_name']; } else{ echo '-'; } ?></td></tr>
                    <tr><td align="left" class="title_holder"><b>Receipt Date.:</b></td><td align="right"><?php
                            if(isset($orderDetails['MedicalProductOrder'])) {
                                echo date('d M Y h:i A', strtotime(@$orderDetails['MedicalProductOrder']['created']));
                            }
                            else
                            {
                                echo date('d M Y h:i A', strtotime($appointmentData['AppointmentCustomerStaffService']['created']));
                            } ?></td></tr>

        <tr><td ></td></tr>

                                <tr><td colspan="2" align="center" class="biller_title"><b>Biller Detail</b></td></tr>
                                <?php if(isset($appointmentData['AppointmentStaff']['name'])){ ?>
                                    <tr><td align="left" valign="top" class="title_holder"><b>Doctor:</b></td><td align="right"><?php echo ucwords($appointmentData['AppointmentStaff']['name']); ?></td></tr>
                                <?php } ?>
                                <?php if(isset($appointmentData['AppointmentStaff']['DepartmentCategory']['category_name']) != ''){ ?>
                                    <tr><td align="left" valign="top" class="title_holder"><b>Department:</b></td><td align="right"><?php echo ucwords($appointmentData['AppointmentStaff']['DepartmentCategory']['category_name']); ?></td></tr>
                                <?php } ?>
                                <?php $address =  $appointmentData['AppointmentAddress']['address'];
                                if(strtoupper($address) == 'ADDRESS GOES HERE'){
                                    $address = '';
                                }
                                ?>
                                <tr><td align="left" valign="top" class="title_holder"><b>Address:</b></td><td align="right"><?php echo ucwords($address); ?></td></tr>


                                <tr><td colspan="2" align="center" class="patient_title"><b>Patient Detail</b></td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Name:</b></td><td align="right"><?php echo ucwords(($appointmentData['AppointmentCustomer']['first_name'] != '')?$appointmentData['AppointmentCustomer']['first_name']:$appointmentData['Children']['child_name']); ?></td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Gender:</b></td><td align="right"><?php echo ucwords(($appointmentData['AppointmentCustomer']['gender'] != '')?$appointmentData['AppointmentCustomer']['gender']:$appointmentData['Children']['gender']); ?></td></tr>
                                <?php if(is_array($age)){
                                    $ageStr = array();
                                    if($age['year'] > 0){
                                        $ageStr[] = $age['year']."Y";

                                    }else if($age['month'] > 0){

                                        $ageStr[]= $age['month']."M";

                                    }else if($age['day'] > 0){
                                        $ageStr[] = $age['day']."D";
                                    }

                                    if(!empty($ageStr)){
                                        $ageStr = implode("-",$ageStr);
                                    }else{
                                        $ageStr ='';
                                    }

                                }
                                else
                                {
                                    $ageStr = $age;
                                } ?>
                                <tr><td align="left" valign="top" class="title_holder"><b>Age:</b></td><td align="right"><?php echo ( $ageStr); ?></td></tr>

                                <?php
                                $address =  ($appointmentData['AppointmentCustomer']['address'] != '')?$appointmentData['AppointmentCustomer']['address']:$appointmentData['Children']['patient_address'];
                                if($address != ""  && strtoupper($address) != 'ADDRESS GOES HERE'){ ?>
                                    <tr><td align="left" valign="top" class="title_holder"><b>Address:</b></td><td align="right"><?php echo ucwords($address); ?></td></tr>
                                <?php } ?>

                                <?php
                                $parentName =  ($appointmentData['AppointmentCustomer']['parents_name'] != '')?$appointmentData['AppointmentCustomer']['parents_name']:$appointmentData['Children']['parents_name'];
                                $relationPrefix =  ($appointmentData['AppointmentCustomer']['relation_prefix'] != '')?$appointmentData['AppointmentCustomer']['relation_prefix']:$appointmentData['Children']['relation_prefix'];
                                if($parentName != ""){ ?>
                                    <tr >
                                        <td align="left" valign="top" class="title_holder"><b><?php echo ($relationPrefix != '')?$relationPrefix:"Parent's Name:";?></b></td>
                                        <td align="right"><?php echo ucwords($parentName); ?></td>
                                    </tr>
                                <?php } ?>

                                <tr><td align="left" valign="top" class="title_holder"><b>Mobile:</b></td><td align="right"><?php echo ($appointmentData['AppointmentCustomer']['mobile'] != '')?$appointmentData['AppointmentCustomer']['mobile']:$appointmentData['Children']['mobile']; ?></td></tr>

        <tr><td colspan="2"></td></tr>
    </table>


    <table class="product_detail_holder">

        <tbody>

            <tr>
                <th align="left"><b>#</b></th>
                <th colspan="3" align="left"><b>Service</b></th>
                <th align="right"><b>Rate</b></th>
                <th align="left"><b>Qty</b></th>
                <th align="right" class="hide_dis"><b>Dis.</b></th>
                <th align="right" class="hide_tax"><b>Tax</b></th>
                <th align="right"><b>Net Amount</b></th>
            </tr>
            <?php $discount =$tax =0; if( isset($orderDetails['MedicalProductOrderDetail']) && !empty($orderDetails['MedicalProductOrderDetail']) ){ ?>
                <?php foreach($orderDetails['MedicalProductOrderDetail'] AS $key => $orderList ){

                    $discount +=$orderList['discount_amount'];
                    $tax +=  $orderList['tax_value'];
                    ?>
                    <tr>
                        <td class=""><?php echo ++$key; ?></td>
                        <td class="" colspan="3"><?php echo ucwords(isset($orderList['MedicalProduct']['name'])?$orderList['MedicalProduct']['name']:$orderList['service']); ?></td>
                        <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['product_price']; ?></td>
                        <td class="" align="left"> <?php echo $orderList['quantity']; ?></td>
                        <td class="hide_dis" align="right" <?php echo ($orderList['discount_amount'] > 0)?'':'style="padding-right: 17px;"'; ?>><?php echo ($orderList['discount_amount'] > 0)?'<i class="fa fa-inr" aria-hidden="true"></i> '.$orderList['discount_amount']:'-'; ?></td>
                        <td class="hide_tax" align="right">
                            <?php if($orderList['tax_value'] > 0)
                            { ?>
                                <?php echo $orderList['tax_type']; ?>@<?php echo $orderList['tax_value']; ?> %
                            <?php } ?>

                        </td>
                        <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>
                    </tr>
                <?php } ?>
            <?php }
            else{ ?>
                <tr class="" >
                    <td class="">1</td>
                    <td class="" colspan="3">OPD</td>
                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> 0</td>
                    <td class="" align="left"> 1</td>
                    <td class="hide_dis" align="right" >-</td>
                    <td class="hide_tax" align="right"> No Tax</td>
                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> 0</td>
                </tr>
            <?php }?>
            <tr class="">
                <td class="col_chk1" colspan="10" ><br /></td>
            </tr>
            <tr>
                <th colspan="8" class="col_chk" align="right"><b>Total Payable Amount&nbsp;</b></th>
                <th colspan="1" align="right"><span class="double_underline"><u><b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo isset($orderDetails['MedicalProductOrder']['total_amount'])?$orderDetails['MedicalProductOrder']['total_amount']:0; ?></b></u></span></th>
            </tr>

        </tbody>

    </table>


    <div class="footer_text">
        <p><?php echo $orderDetails['Thinapp']['receipt_footer_title']; ?></p>


    </div>
    <div class="created_by">
        <p><?php if(!empty($createdBy)){ ?> By: <?php echo $createdBy; ?> <?php } ?></p>
    </div>


</div>

<div class="note">
    Note: Press 'Enter' to print or click here
</div>
<div class="note-detail-receipt">
    Note: Press 'D' to print detail receipt or click here
</div>
<div class="note-prescription">
    Note: Press 'Space' to print prescription or click here
</div>

</body>
<script>
    $(function () {

        var dis = '<?php echo $discount;?>';
        var tax = '<?php echo $tax;?>';

        var tot = 0;
        if(dis ==0){
            $('.hide_dis').remove();
            tot = tot+1;
        }

        if(tax ==0){
            $('.hide_tax').remove();

            tot = tot+1;
        }



        $('.col_chk').attr('colspan',($('.col_chk').attr('colspan')-tot));
        $('.col_chk1').attr('colspan',($('.col_chk1').attr('colspan')-tot));


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
            } else if(e.which == 32) {
                window.open(
                    prescriptionURL,
                    '_blank' // <- This is what makes it open in a new window.
                );
            } else if(e.which == 109) {
                window.open(detailReceiptURL);
            }
        });
        $(document).on('click',".note-prescription",function(){
            window.open(
                prescriptionURL,
                '_blank' // <- This is what makes it open in a new window.
            );
        });
        $(document).on('click',".note-detail-receipt",function(){
            window.open(detailReceiptURL);
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