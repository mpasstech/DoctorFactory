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
        .logo_img img {
            height: 95px;
        }
        .container table {
            width: 100%;
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
        }
        .biller_title {
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            background-color: #A9A9A9 !important;
            color: #FFFFFF;
            padding: 2px 0px 2px 6px;
        }
        .patient_title{
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
            background-color: #A9A9A9 !important;
            color: #FFFFFF;
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
            color: #FFFFFF;
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
                width: 650px;
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
            }
            .biller_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #FFFFFF !important;
                padding: 2px 0px 2px 6px;
            }
            .patient_title{
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #FFFFFF !important;
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
                color: #FFFFFF !important;
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
                width: 650px;
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
            }
            .biller_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #FFFFFF !important;
                padding: 2px 0px 2px 6px;
            }
            .patient_title{
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #FFFFFF !important;
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
                color: #FFFFFF !important;
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

</head>
<body>


<?php $ipd_data = $this->AppAdmin->ipdTotalBalance($orderDetails['MedicalProductOrder']['hospital_ipd_id']); ?>
<div class="container">
    <br>
    <table>
        <tr>
            <td align="left" valign="top" class="company_detail_holder">
                <h2>
                    <?php if(isset($titleArr['receipt_header_title'])){ echo $titleArr['receipt_header_title']; } else{ ?>
                        <?php echo ($orderDetails['Thinapp']['receipt_header_title'] != '')?$orderDetails['Thinapp']['receipt_header_title']:$orderDetails['Thinapp']['name']; ?>
                    <?php } ?>


                </h2>
                <div class="logo_img">
                    <?php if($orderDetails['Thinapp']['logo'] != ''){ ?>
                        <img src="<?php echo $orderDetails['Thinapp']['logo']; ?>" >
                    <?php } ?>

                </div>
            </td>
            <td align="right" valign="top" class="recept_detail_holder">
                <h2><?php echo ($orderDetails['MedicalProductOrder']['hospital_ipd_id'] > 0)?'IPD':''; ?> Bill</h2>
                <table>
                    <tr><td align="left" class="title_holder"><b>UHID:</b></td><td align="right"><?php echo $UHID; ?></td></tr>
                    <tr><td align="left" class="title_holder"><b>Receipt No.:</b></td><td align="right">
                            <?php echo ($orderDetails['MedicalProductOrder']['payment_status']=='PAID')?@$orderDetails[0]['unique_id']:' - ';  ?>
                        </td></tr>

                    <?php if($orderDetails['MedicalProductOrder']['hospital_ipd_id'] > 0){ ?>
                        <tr><td align="left" class="title_holder"><b>IPD ID:</b></td><td align="right"><?php echo !empty($ipd_data['ipd_unique_id'])?$ipd_data['ipd_unique_id']:"-"; ?></td></tr>
                        <tr><td align="left" class="title_holder"><b>Admit Date.:</b></td><td align="right"><?php echo date("d M Y",strtotime($ipd_data['admit_date'])); ?></td>
                    <?php }; ?>

                    <tr><td align="left" class="title_holder"><b>Payment Mode:</b></td><td align="right"><?php if(isset($orderDetails['MedicalProductOrder']['payment_type_name'])){  echo $orderDetails['MedicalProductOrder']['payment_type_name']; } else{ echo '-'; } ?></td></tr>

                    <tr><td align="left" class="title_holder"><b>Receipt Date.:</b></td><td align="right"><?php
                            if(isset($orderDetails['MedicalProductOrder'])) {
                                echo date('d M Y h:i A', strtotime(@$orderDetails['MedicalProductOrder']['created']));
                            }
                            else
                            {
                                echo date('d M Y h:i A', strtotime($orderDetails['AppointmentCustomerStaffService']['created']));
                            } ?></td></td></tr>

                </table>
            </td>
        </tr>
        <tr><td colspan="2"></td></tr>
        <tr>
            <td colspan="2" valign="top" class="user_biller_detail_holder">
                <table>
                    <tr>
                        <td class="biller_detail_holder" valign="top">

                            <table>
                                <tr><td colspan="2" align="left" class="biller_title"><b>Biller Detail</b></td></tr>

                                <tr><td align="left" valign="top" class="title_holder"><b>Doctor:</b></td><td align="right"><?php echo  !empty($orderDetails['AppointmentStaff'])?ucwords($orderDetails['AppointmentStaff']['name']):$orderDetails['Thinapp']['name']; ?></td></tr>

                                <?php if(isset($orderDetails['AppointmentStaff']['DepartmentCategory']['category_name']) != ''){ ?>
                                    <tr><td align="left" valign="top" class="title_holder"><b>Department:</b></td><td align="right"><?php echo ucwords($orderDetails['AppointmentStaff']['DepartmentCategory']['category_name']); ?></td></tr>
                                <?php } ?>
                                <?php
                                //$address =  isset($orderDetails['AppointmentAddress']['address'])?$orderDetails['AppointmentAddress']['address']:$orderDetails['Thinapp']['address'];
                                $address =  isset($orderDetails['AppointmentAddress']['address'])?$orderDetails['AppointmentAddress']['address']:'';
                                if($address != ""  && strtoupper($address) != 'ADDRESS GOES HERE'){ ?>
                                <tr><td align="left" valign="top" class="title_holder"><b>Address:</b></td><td align="right"><?php echo ucwords($address); ?></td></tr>
                                <?php } ?>
                            </table>
                        </td>
                        <td class="user_biller_detail_gap"></td>
                        <td class="user_detail_holder" valign="top">
                            <table>
                                <tr><td colspan="2" align="left" class="patient_title"><b>Patient Detail</b></td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Name:</b></td><td align="right"><?php echo ucwords((isset($orderDetails['AppointmentCustomer']['first_name']) && $orderDetails['AppointmentCustomer']['first_name'] != '')?$orderDetails['AppointmentCustomer']['first_name']:$orderDetails['Children']['child_name']); ?></td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Gender:</b></td><td align="right"><?php echo ucwords(strtolower((isset($orderDetails['AppointmentCustomer']['gender']) && $orderDetails['AppointmentCustomer']['gender'] != '')?$orderDetails['AppointmentCustomer']['gender']:$orderDetails['Children']['gender'])); ?></td></tr>
                                <?php if(is_array($age)){
                                    $ageStr = array();
                                    if($age['year'] > 0){

                                        $ageStr[] = $age['year']."Year";

                                    }else if($age['month'] > 0){

                                        $ageStr[]= $age['month']."Month";

                                    }else if($age['day'] > 0){
                                        $ageStr[] = $age['day']."Day";
                                    }

                                    if(!empty($ageStr)){
                                        $ageStr = implode(" ",$ageStr);
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
                                $address =  (isset($orderDetails['AppointmentCustomer']['address']))?$orderDetails['AppointmentCustomer']['address']:$orderDetails['Children']['patient_address'];
                                if($address != ""  && strtoupper($address) != 'ADDRESS GOES HERE'){ ?>
                                    <tr><td align="left" valign="top" class="title_holder"><b>Address:</b></td><td align="right"><?php echo ucwords($address); ?></td></tr>
                                <?php } ?>

                                <?php
                                $parentName =  (isset($orderDetails['AppointmentCustomer']['parents_name']))?$orderDetails['AppointmentCustomer']['parents_name']:$orderDetails['Children']['parents_name'];
                                $relationPrefix =  (isset($orderDetails['AppointmentCustomer']['relation_prefix']))?$orderDetails['AppointmentCustomer']['relation_prefix']:$orderDetails['Children']['relation_prefix'];
                                if($parentName != ""){ ?>
                                    <tr >
                                        <td align="left" valign="top" class="title_holder"><b><?php echo ($relationPrefix != '')?$relationPrefix:"Parent's Name:";?></b></td>
                                        <td align="right"><?php echo ucwords($parentName); ?></td>
                                    </tr>
                                <?php } ?>

                                <tr><td align="left" valign="top" class="title_holder"><b>Mobile:</b></td><td align="right"><?php echo (isset($orderDetails['AppointmentCustomer']['mobile']))?$orderDetails['AppointmentCustomer']['mobile']:$orderDetails['Children']['mobile']; ?></td></tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr><td colspan="2"></td></tr>
    </table>


    <table class="product_detail_holder">

        <tbody>

        <tr>
            <th align="left"><b>#</b></th>
            <th colspan="3" align="left"><b><?php echo $orderDetails['MedicalProductOrder']['is_package'] == 'N'?'Service':'Package'; ?></b></th>
            <th align="right"><b><?php echo $orderDetails['MedicalProductOrder']['is_package'] == 'N'?'Rate':'Amount'; ?></b></th>
            <th align="center"><b>X</b></th>
            <th align="left"><b>Qty</b></th>
            <th align="right" class="hide_dis"><b>Discount</b></th>
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
                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderDetails['MedicalProductOrder']['is_package'] == 'N'?$orderList['product_price']:$orderList['amount']; ?></td>
                    <td class="" align="center"> <?php echo 'X'; ?></td>
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
                <td class="" align="center"> <?php echo 'X'; ?></td>
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
            <th colspan="9" class="col_chk" align="right"><b>Total Payable Amount&nbsp;</b></th>
            <th colspan="1" align="right"><span class="double_underline"><u><b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo isset($orderDetails['MedicalProductOrder']['total_amount'])?$orderDetails['MedicalProductOrder']['total_amount']:0; ?></b></u></span></th>
        </tr>

        </tbody>

    </table>


    <div class="footer_text">
        <?php if(!empty($ipd_data['advance'])){ ?>
            <p><u>Available Balance on <?php echo date('d-m-Y h:i:s'); ?> <b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $ipd_data['advance'] - $ipd_data['expense']; ?></b></u></p>
        <?php }?>
        <?php if($orderDetails['MedicalProductOrder']['is_package'] == 'Y'){ ?>
            <p><u>Total Remaining Amount <b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderDetails['MedicalProductOrder']['total_outstanding_amount']; ?> | </b>Total Received Amount <b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderDetails['MedicalProductOrder']['total_received_amount']; ?></b></u></p>
        <?php }?>
        <p><?php if(isset($titleArr['receipt_footer_title'])){ echo $titleArr['receipt_footer_title']; } else{ ?>
            <?php echo $orderDetails['Thinapp']['receipt_footer_title']; ?>
            <?php } ?>
        </p>
    </div>
    <?php if($orderDetails['MedicalProductOrder']['lab_pharmacy_user_id'] == 0){ ?>
        <div class="created_by">
            <p><?php if(!empty($createdBy)){ ?> By: <?php echo $createdBy; ?> <?php } ?></p>
        </div>
    <?php } ?>



</div>

<div class="note">
    Note: Press enter to print or click here
</div>
<div class="prescription_size"> <b>Size</b>&nbsp
    <select id="prescription_size">
        <option value="A4">A4</option>
        <option value="A4/4">A4/4</option>
    </select>
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



    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires="+d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }


    $(document).ready(function(){
        checkCookie();
    });

    function checkCookie() {
        var user = getCookie("print_size");
        if (user != "") {

            $("#prescription_size").val(user);

            if(user == 'A4')
            {
                $(".container").css('width','650px');
            }
            else
            {
                $(".container").css('width','450px');
            }

        } else {
            $("#prescription_size").val('A4');
            if (user != "" && user != null) {
                setCookie("print_size", 'A4', 365);
            }
            $(".container").css('width','650px');

        }
    }

    $(document).on("change","#prescription_size",function(){
        var value = $(this).val();
        setCookie("print_size", value, 365);
        checkCookie();
    });



</script>
</html>