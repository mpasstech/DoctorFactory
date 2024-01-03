<?php

$font_size = "10px";
if (!empty($_COOKIE["invoice_font_size"])) {
    $font_size = $_COOKIE["invoice_font_size"];
}

$template = "default";
if (!empty($_COOKIE["template_invoice"])) {
    $template = $_COOKIE["template_invoice"];
}




if (!isset($_COOKIE["template_invoice"]) || ($_COOKIE["template_invoice"] == "default")) {
    setcookie("template_invoice", "default", time() + (86400 * 30), "/");
?>




    <?php $login = $this->Session->read('Auth.User');

    $show_days_on_receipt = !empty($login['Thinapp']['show_number_of_days_on_receipt']) ? $login['Thinapp']['show_number_of_days_on_receipt'] : 'NO';
    $display_day = ($show_days_on_receipt == 'YES') ? 'block' : 'none';

    ?>
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
            var baseurl = '<?php echo Router::url('/', true); ?>';
            <?php
            $url = $this->request->url;
            $urlPrescrption = "";
            if ($invoice_type == "OPD") {
                $appointment_id = $data = @$invoiceData[0]['appointment_id'];
                $urlPrescrption = Router::url('/app_admin/print_prescription/' . base64_encode($appointment_id), true);
            }

            $urlMiniReceipt = str_replace('print_invoice', 'print_mini_invoice', $url);
            ?>
            var prescriptionURL = '<?php echo $urlPrescrption ?>';
            var miniReceiptURL = '<?php echo Router::url('/', true); ?><?php echo $urlMiniReceipt; ?>';
        </script>
        <?php echo $this->Html->script(array('jquery.js', 'printThis.js')); ?>
        <?php echo $this->Html->css(array('font-awesome.min.css')); ?>
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

            .user_biller_detail_gap {
                width: 20%;
            }

            .user_detail_holder {
                width: 40%;
            }

            body {
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

            .patient_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #FFFFFF;
                padding: 2px 0px 2px 6px;
            }

            .company_detail_holder {
                width: 60%;
            }

            .recept_detail_holder {
                width: 40%;
            }

            .title_holder {
                padding: 0px 0px 0px 6px;
            }

            .product_detail_holder tr th {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                background-color: #A9A9A9 !important;
                color: #FFFFFF;
                padding: 2px 3px 2px 3px;
                border: 1px solid #77797c;
            }

            .product_detail_holder tr td {
                padding: 4px 3px 4px 3px;
                border-right: 1px solid #77797c;
                border-left: 1px solid #77797c;
            }

            .product_detail_holder tr:nth-child(even) {
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
                background-color: #f2f2f2 !important;
            }

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

            .note-prescription {
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }

            .note-mini-receipt {
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }

            .footer_text {
                text-align: center;
            }

            .created_by {
                text-align: right;
            }

            .prescription_size {
                text-align: center;
            }

            .template {
                text-align: center;
                margin-top: 3px;
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

        <style>
            @media print {


                @page {
                    size: A4;
                    margin: 0;
                    font-size: 14px;
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                }

                table {
                    font-size: 14px;
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                }


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

                .user_biller_detail_gap {
                    width: 20%;
                }

                .user_detail_holder {
                    width: 40%;
                }

                body {

                    font-family: sans-serif;
                }

                .biller_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;
                    padding: 2px 0px 2px 6px;
                }

                .patient_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;
                    padding: 2px 0px 2px 6px;
                }

                .company_detail_holder {
                    width: 60%;
                }

                .recept_detail_holder {
                    width: 40%;
                }

                .title_holder {
                    padding: 0px 0px 0px 6px;
                }

                .product_detail_holder tr th {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;
                    padding: 2px 3px 2px 3px;
                    border: 1px solid #77797c;
                }

                .product_detail_holder tr td {
                    padding: 2px 3px 2px 3px;
                    border-right: 1px solid #77797c;
                    border-left: 1px solid #77797c;
                }

                .product_detail_holder tr:nth-child(even) {
                    color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                    background-color: #f2f2f2 !important;
                }

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

                .note-prescription {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .note-mini-receipt {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .footer_text {
                    text-align: center;
                }

                .created_by {
                    text-align: right;
                }

                .prescription_size {
                    text-align: center;
                }

                .template {
                    text-align: center;
                    margin-top: 3px;
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

        <?php
        $data = @$invoiceData[0];

        if (isset($data['created'])) {
            $receiptID = $data['receipt_id']; //$this->AppAdmin->get_receipt_id_by_order_id($data['medical_product_id']);
        } else {
            $receiptID = "-";
        }
        ?>

        <title>Receipt No.: <?php echo $receiptID; ?></title>
    </head>

    <body style="font-size: <?php echo $font_size; ?>">

        <div class="container">
            <br>
            <table>
                <tr>
                    <td align="left" valign="top" class="company_detail_holder">
                        <h2><?php echo ($data['receipt_header_title'] != '') ? $data['receipt_header_title'] : $data['app_name']; ?></h2>
                        <div class="logo_img">
                            <?php if ($data['logo'] != '' && $login['Thinapp']['id'] != '318') { ?>
                                <img src="<?php echo $data['logo']; ?>">
                            <?php } ?>

                        </div>
                    </td>
                    <td align="right" valign="top" class="recept_detail_holder">
                        <h2>

                            <?php

                            if ($data['is_refunded'] == "YES") {
                                echo 'Refund Receipt';
                            } else {
                                if ($invoice_type == "OPD") {
                                    echo !empty($data['receipt_top_left_title']) ? $data['receipt_top_left_title'] : "OPD Bill";
                                } else if ($invoice_type == "IPD") {
                                    echo (@$data['hospital_ipd_id'] > 0) ? 'IPD Bill' : 'Bill';
                                } else if ($invoice_type == "IPD_ADVANCE_DEPOSIT") {
                                    echo 'IPD Bill';
                                } else if ($invoice_type == "DUE") {
                                    echo 'DUE AMOUNT SETTLEMENT';
                                }
                            }


                            ?>

                        </h2>


                        <table>
                            <?php if (@$data['queue_number'] > 0 && $invoice_type == "OPD") {

                                $token = $this->AppAdmin->create_queue_number($data);

                            ?>

                                <?php if ($data['show_paid_user_order_number_on_receipt'] == "YES" && !empty($data['paid_receipt_number']) && $data['total_paid'] > 0) { ?>
                                    <tr>
                                        <td align="left" class="title_holder"><b>Paid Number:</b></td>
                                        <td align="right"><b><?php echo $data['paid_receipt_number']; ?></b></td>
                                    </tr>
                                <?php } ?>

                                <?php if ($data['show_token_on_receipt'] == "YES") { ?>
                                    <tr>
                                        <td align="left" class="title_holder"><b>Token:</b></td>
                                        <td align="right"><b><?php echo $token; ?></b></td>
                                    </tr>
                                <?php } ?>

                                <?php if ($data['show_token_time_on_receipt'] == "YES") { ?>
                                    <tr>
                                        <td align="left" class="title_holder"><b>Appointment Time:</b></td>
                                        <td align="right"><b><?php echo ($data['emergency_appointment'] == "YES") ? date('h:i A', strtotime($data['appointment_created'])) : $data['slot_time']; ?></b></td>
                                    </tr>

                                <?php } ?>
                            <?php } ?>

                            <tr>
                                <td align="left" class="title_holder"><b>Receipt No.:</b></td>
                                <td align="right">
                                    <?php echo !empty($receiptID) ? $receiptID : ' - ';  ?>
                                </td>
                            </tr>

                            <?php if ($login['User']['thinapp_id'] == '497') { ?>
                                <tr>
                                    <td align="left" class="title_holder"><b>Bill No.:</b></td>
                                    <td align="right"></td>
                                </tr>
                            <?php } ?>

                            <?php if (!empty($data['bill_id'])) { ?>
                                <tr>
                                    <td align="left" class="title_holder"><b>Bill No.:</b></td>
                                    <td align="right">
                                        <?php echo $data['bill_id'];  ?>
                                    </td>
                                </tr>
                            <?php } ?>



                            <tr>
                                <td align="left" class="title_holder"><b>UHID:</b></td>
                                <td align="right"><?php echo $data['uhid']; ?></td>
                            </tr>


                            <?php if (@$data['hospital_ipd_id'] > 0 && ($invoice_type == "IPD" || $invoice_type == "IPD_ADVANCE_DEPOSIT")) { ?>
                                <tr>
                                    <td align="left" class="title_holder"><b>IPD ID:</b></td>
                                    <td align="right"><?php echo !empty($data['ipd_unique_id']) ? $data['ipd_unique_id'] : "-"; ?></td>
                                </tr>
                                <tr>
                                    <td align="left" class="title_holder"><b>Admit Date.:</b></td>
                                    <td align="right"><?php echo date("d M Y", strtotime($data['admit_date'])); ?></td>
                                </tr>

                                <?php if ($data['admit_status'] == 'DISCHARGE') {
                                    $discharge_date =  $this->AppAdmin->getIPDDischargeDate($data['hospital_ipd_id'], $login['User']['thinapp_id']);
                                    if (!empty($discharge_date)) {   ?>
                                        <tr>
                                            <td align="left" class="title_holder"><b>Discharge Date.:</b></td>
                                            <td align="right"><?php echo date("d M Y", strtotime($discharge_date)); ?></td>
                                        </tr>
                                <?php }
                                } ?>

                            <?php } ?>

                            <tr>
                                <td align="left" class="title_holder"><b>Payment Mode:</b></td>
                                <td align="right"><?php if (!empty($data['payment_type_name'])) {
                                                        echo $data['payment_type_name'];
                                                    } else {
                                                        echo '-';
                                                    } ?></td>
                            </tr>
                            <?php if ($data['show_date_on_receipt'] == "YES" || $data['show_time_on_receipt'] == "YES") { ?>
                                <tr>
                                    <?php
                                    $print_date = array();
                                    $print_label = array();
                                    $date_key = !empty($data['billing_date']) ? 'billing_date' : 'appointment_created';
                                    if ($data['show_date_on_receipt'] == "YES") {
                                        $print_date[] = date('d M Y', strtotime(@$data[$date_key]));
                                        $print_label[] = "Date";
                                    }
                                    if ($data['show_time_on_receipt'] == "YES") {
                                        $print_date[] = date('h:i A', strtotime(@$data[$date_key]));
                                        $print_label[] = "Time";
                                    }

                                    ?>
                                    <td align="left" class="title_holder"><b>
                                            <?php echo "Receipt " . implode("/", $print_label) . ":"; ?>
                                        </b></td>
                                    <td align="right">
                                        <?php echo implode(" ", $print_date); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" class="user_biller_detail_holder">
                        <table>
                            <tr>
                                <td class="biller_detail_holder" valign="top">
                                    <table>
                                        <tr>
                                            <td colspan="2" align="left" class="biller_title"><b>Biller Detail</b></td>
                                        </tr>
                                        <?php if ($invoice_type == "DUE") { ?>
                                            <tr>
                                                <td align="left" valign="top" class="title_holder"><b>Organization Name:</b></td>
                                                <td align="right"><?php echo ucwords($data['app_name']); ?></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="top" class="title_holder"><b>Address:</b></td>
                                                <td align="right"><?php echo ucwords($data['address']); ?></td>
                                            </tr>

                                        <?php } else { ?>

                                            <?php $refer_lbl = "Consulting Doctor";
                                            if (!empty($data['consult_with']) && $data['show_doctor_on_receipt'] == "YES") {
                                                $refer_lbl = "Referring Doctor";  ?>
                                                <tr>
                                                    <td align="left" valign="top" class="title_holder"><b>Doctor:</b></td>
                                                    <td align="right"><?php echo ucwords($data['consult_with']); ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php if (!empty($data['department_name']) && $data['show_department_on_receipt'] == "YES") { ?>
                                                <tr>
                                                    <td align="left" valign="top" class="title_holder"><b>Department:</b></td>
                                                    <td align="right"><?php echo ucwords($data['department_name']); ?></td>
                                                </tr>
                                            <?php } ?>
                                            <?php $address =  $data['address'];
                                            if (strtoupper($address) == 'ADDRESS GOES HERE') {
                                                $address = '';
                                            }
                                            ?>
                                            <?php if (!empty($address) && !empty($invoice_type != "IPD_ADVANCE_DEPOSIT")) { ?>
                                                <tr>
                                                    <td align="left" valign="top" class="title_holder"><b>Address:</b></td>
                                                    <td align="right"><?php echo ucwords($address); ?></td>
                                                </tr>
                                            <?php }; ?>

                                            <?php if ($data['show_referrer_on_receipt'] == "YES" && !empty($data['reffered_by_name'])) { ?>
                                                <tr>
                                                    <td align="left" valign="top" class="title_holder"><b><?php echo $refer_lbl; ?>:</b></td>
                                                    <td align="right"><?php echo ucwords(strtolower($data['reffered_by_name'])); ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>


                                    </table>
                                </td>
                                <td class="user_biller_detail_gap"></td>
                                <td class="user_detail_holder" valign="top">
                                    <table>
                                        <tr>
                                            <td colspan="2" align="left" class="patient_title"><b>Patient Detail</b></td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top" class="title_holder"><b>Name:</b></td>
                                            <td align="right"><?php echo ucwords($data['patient_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top" class="title_holder"><b>Gender:</b></td>
                                            <td align="right"><?php echo ucwords(strtolower($data['gender'])); ?></td>
                                        </tr>
                                        <?php
                                        $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
                                        if (empty($ageStr)) {
                                            $ageStr = $data['age'];
                                        } ?>
                                        <tr>
                                            <td align="left" valign="top" class="title_holder"><b>Age:</b></td>
                                            <td align="right"><?php echo ($ageStr); ?></td>
                                        </tr>

                                        <?php
                                        $address =  $data['patient_address'];
                                        if ($address != ""  && strtoupper($address) != 'ADDRESS GOES HERE') { ?>
                                            <tr>
                                                <td align="left" valign="top" class="title_holder"><b>Address:</b></td>
                                                <td align="right"><?php echo ucwords($address); ?></td>
                                            </tr>
                                        <?php } ?>

                                        <?php
                                        $parentName =  $data['parents_name'];
                                        $relationPrefix =  $data['relation_prefix'];
                                        if ($parentName != "") { ?>
                                            <tr>
                                                <td align="left" valign="top" class="title_holder"><b><?php echo ($relationPrefix != '') ? $relationPrefix : "Parent's Name:"; ?></b></td>
                                                <td align="right"><?php echo ucwords($parentName); ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (!empty($data['mobile']) && $data['show_patient_mobile_on_receipt'] == "YES") { ?>
                                            <tr>
                                                <td align="left" valign="top" class="title_holder"><b>Mobile:</b></td>
                                                <td align="right"><?php echo $data['mobile']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                </tr>
            </table>


            <table class="product_detail_holder">

                <?php if ($invoice_type != "DUE") { ?>
                    <tbody>

                        <tr>
                            <th align="left"><b>#</b></th>
                            <?php if ($invoice_type == 'IPD') { ?>
                                <th colspan="3" align="left"><b><?php echo $data['is_package'] == 'N' ? 'Service' : 'Package'; ?></b></th>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <th colspan="1" align="left"><b>Batch</b></th>
                                    <th colspan="1" align="left" class="exp_date_th"><b>Expiry Date</b></th>
                                <?php } ?>
                                <th align="right"><b><?php echo $data['is_package'] == 'N' ? 'Rate' : 'Amount'; ?></b></th>
                            <?php } else { ?>
                                <th colspan="3" align="left"><b>Service</b></th>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <th colspan="1" align="left"><b>Batch</b></th>
                                    <th colspan="1" align="left" class="exp_date_th"><b>Expiry Date</b></th>
                                <?php } ?>
                                <th align="right"><b>Rate</b></th>
                            <?php } ?>


                            <th align="center"><b>X</b></th>
                            <th align="left"><b>Qty</b></th>
                            <th style="display:<?php echo $display_day; ?>;" align="left"><b>Days</b></th>
                            <th align="right" class="hide_dis"><b>Discount</b></th>
                            <th align="right" class="hide_tax"><b>Tax</b></th>
                            <th align="right"><b>Net Amount</b></th>
                        </tr>
                        <?php $counter = 0;
                        $discount = $tax = $total_amount = 0;
                        foreach ($invoiceData as $key => $orderList) {
                            if ($orderList['show_into_receipt'] == "YES") {
                                $discount += $orderList['discount_amount'];
                                $tax +=  $orderList['tax_value'];
                                $total_amount += $orderList['total_amount'];
                        ?>
                                <tr>
                                    <td class=""><?php echo ++$counter; ?></td>

                                    <td class="" colspan="3"><?php echo ucwords($orderList['service_name']); ?></td>

                                    <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                        <td class="" align="left"> <?php echo $orderList['batch']; ?></td>
                                        <td align="left" class="exp_date_td"> <?php echo ($orderList['expiry_date'] == '0000-00-00') ? '-' : $orderList['expiry_date']; ?></td>
                                    <?php } ?>
                                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['product_price']; ?></td>
                                    <td class="" align="center"> <?php echo 'X'; ?></td>
                                    <td class="" align="left"> <?php echo $orderList['quantity']; ?></td>
                                    <td style="display:<?php echo $display_day; ?>;" class="" align="left"> <?php echo $orderList['days']; ?></td>
                                    <td class="hide_dis" align="right" <?php echo ($orderList['discount_amount'] > 0) ? '' : 'style="padding-right: 17px;"'; ?>><?php echo ($orderList['discount_amount'] > 0) ? '<i class="fa fa-inr" aria-hidden="true"></i> ' . $orderList['discount_amount'] : '-'; ?></td>
                                    <td class="hide_tax" align="right">
                                        <?php if ($orderList['tax_value'] > 0) { ?>
                                            <?php echo $orderList['tax_type']; ?>@<?php echo $orderList['tax_value']; ?> %
                                        <?php } ?>

                                    </td>

                                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>

                                </tr>
                        <?php }
                        } ?>

                        <?php if (!empty($data['due_paid_amount'])) { ?>

                            <tr class="">
                                <td><?php echo ++$counter; ?></td>
                                <td colspan="3">Due Amount Payment</td>
                                <td class="" align="left"> </td>
                                <td class="" align="left"> </td>
                                <td class="" align="left"> </td>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <td class="" align="left"></td>
                                    <td class="" align="left" </td>
                                    <?php } ?>
                                    <td class="hide_dis" align="right"></td>

                                    <td class="hide_tax" align="right"></td>
                                    <td align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $data['due_paid_amount']; ?></td>
                            </tr>

                        <?php } ?>

                        <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                            <tr class="">
                                <td class="col_chk1 cols" colspan="12"><br /></td>
                            </tr>
                        <?php } else { ?>
                            <tr class="">
                                <td class="col_chk1" colspan="10"><br /></td>
                            </tr>
                        <?php } ?>





                        <tr>
                            <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                <th colspan="11" class="col_chk cols" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <?php } else { ?>
                                <th colspan="<?php echo ($show_days_on_receipt == 'YES') ? '10' : '9'; ?>" class="col_chk" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <?php } ?>

                            <th colspan="1" align="right"><span class="double_underline"><u><b><i class="fa fa-inr" aria-hidden="true"></i> <?php
                                                                                                                                            if (isset($data['total_paid'])  && ($invoice_type == 'OPD' || $invoice_type == 'IPD')) {
                                                                                                                                                echo $data['total_paid'];
                                                                                                                                            } else {
                                                                                                                                                echo $total_amount;
                                                                                                                                            }
                                                                                                                                            ?></b></u></span></th>
                        </tr>

                    </tbody>
                <?php } else { ?>

                    <tbody>

                        <tr>
                            <th align="left"><b>#</b></th>
                            <th align="left"><b>Service</b></th>
                            <th align="right"><b>Net Amount</b></th>
                        </tr>
                        <?php $counter = 0;
                        $discount = $tax = $total_amount = 0;
                        foreach ($invoiceData as $key => $orderList) {
                            if ($orderList['show_into_receipt'] == "YES") {
                                $discount += $orderList['discount_amount'];
                                $tax +=  $orderList['tax_value'];
                                $total_amount += $orderList['total_amount'];
                        ?>
                                <tr>
                                    <td><?php echo ++$counter; ?></td>
                                    <td>Due Amount Payment</td>
                                    <td align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>
                                </tr>
                        <?php }
                        } ?>
                        <tr class="">
                            <td colspan="3"><br /></td>
                        </tr>

                        <tr>
                            <th></th>
                            <th class="col_chk" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <th align="right">
                                <span class="double_underline">
                                    <u>
                                        <b>
                                            <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?>
                                        </b>
                                    </u>
                                </span>
                            </th>
                        </tr>

                    </tbody>


                <?php } ?>

            </table>




            <div class="footer_text">
                <?php $ipd_data = array();
                if ($invoice_type == "IPD_ADVANCE_DEPOSIT" || $invoice_type == "IPD") { ?>
                    <?php $ipd_data = @$this->AppAdmin->ipdTotalBalance($data['hospital_ipd_id']); ?>
                    <?php if ($data['admit_status'] != 'DISCHARGE' && !empty($ipd_data['advance'])) { ?>
                        <p><u>Available Balance on <?php echo date('d-m-Y h:i:s'); ?> <b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $ipd_data['advance'] - $ipd_data['expense']; ?></b></u></p>
                <?php }
                } ?>

                <?php if ($invoice_type == 'OPD' || ($invoice_type == 'IPD' && empty($ipd_data['ipd_unique_id']))) {
                    if (!empty($data['total_due_amount'])) { ?>
                        <p><u>Your Total Due Balance is : <?php echo $data['total_due_amount'] . " Rs/-"; ?></b></u></p>
                <?php }
                } ?>



                <p><?php echo $data['receipt_footer_title']; ?></p>


            </div>
            <div class="created_by">
                <p><?php echo !empty($data['created_by']) ? "By. " . $data['created_by'] : ''; ?></p>
            </div>


        </div>

        <div class="note">
            Note: Press 'Enter' to print or click here
        </div>
        <!--div class="note-mini-receipt">
        Note: Press 'M' to print mini receipt or click here
    </div-->
        <div class="note-prescription">
            Note: Press 'Space' to print prescription or click here
        </div>
        <div class="option">
            <div class="option_div"> <b>Font Size</b>&nbsp
                <select id="font_size">
                <option value="3px">3</option>
                    <option value="4px">4</option>
                    <option value="5px">5</option>
                    <option value="6px">6</option>
                    <option value="7px">7</option>
                    <option value="8px">8</option>
                    <option value="9px">9</option>
                    <option value="10px">10</option>
                    <option value="11px">11</option>
                    <option value="12px">12</option>
                    <option value="13px">13</option>
                    <option value="14px">14</option>
                    <option value="15px">15</option>
                    <option value="16px">16</option>
                    <option value="17px">17</option>
                </select>
            </div>
            <div class="option_div"> <b>Paper Size</b>&nbsp
                <select id="prescription_size">
                    <option value="A4">A4</option>
                    <option value="A4/4">A4/4</option>
                </select>
            </div>
            <div class="option_div"> <b>Template</b>&nbsp
                <select id="template">
                    <option value="default">Template 1</option>
                    <option value="new">Template 2</option>
                    <option value="eye">Eye Template</option>
                </select>
            </div>
        </div>



    </body>


    <style>
        .option {
            width: 47%;
            float: left;
            text-align: center;
            display: block;
            margin: 10px 30%;
            position: relative;
        }

        .option_div {
            width: 28%;
            float: left;
            font-size: 14px !important;
        }

        .option_div select {
            font-size: 14px !important;
        }
    </style>
    <script>
        $(function() {
            var dis = '<?php echo $discount; ?>';

            var tax = '<?php echo $tax; ?>';

            var tot = 0;

            if (dis == 0) {
                $('.hide_dis').remove();
                tot = tot + 1;
            }

            if (tax == 0) {
                $('.hide_tax').remove();
                tot = tot + 1;
            }

            $('.col_chk').attr('colspan', ($('.col_chk').attr('colspan') - tot));

            $('.col_chk1').attr('colspan', ($('.col_chk1').attr('colspan') - tot));

            var total_th = $(".product_detail_holder tbody tr:first-child th").length;

            $('.due_amount_label_td').attr('colspan', total_th);

            $(document).keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('.container').printThis({ // show the iframe for debugging
                        importCSS: true, // import page CSS
                        importStyle: true, // import style tags
                        pageTitle: "", // add title to print page
                        removeInline: false, // remove all inline styles from print elements
                        printDelay: 333, // variable print delay; depending on complexity a higher value may be necessary
                        header: null, // prefix to html
                        footer: null, // postfix to html
                        base: false, // preserve the BASE tag, or accept a string for the URL
                        formValues: true, // preserve input/form values
                        canvas: false, // copy canvas elements (experimental)
                        doctypeString: ".", // enter a different doctype for older markup
                        removeScripts: false, // remove script tags from print content
                        copyTagClasses: false // copy classes from the html & body tag
                    });
                } else if (e.which == 32) {
                    window.open(
                        prescriptionURL,
                        '_blank' // <- This is what makes it open in a new window.
                    );
                } else if (e.which == 109) {
                    //window.open(miniReceiptURL);
                }
            });

            $(document).on('click', ".note-prescription", function() {
                window.open(
                    prescriptionURL,
                    '_blank' // <- This is what makes it open in a new window.
                );
            });

            $(document).on('click', ".note-mini-receipt", function() {
                window.open(miniReceiptURL);
            });

            $(document).on('click', ".note", function(e) {
                e.preventDefault();
                e.stopPropagation();


                //$("#print body").text(" td,tr,body{font-size '"+$('body').css('font-size')+"'");
                console.log($("#print").text());
                // console.log($(printStyle).find('body'));


                $('.container').printThis({

                    importCSS: true, // import page CSS
                    importStyle: true, // import style tags
                    pageTitle: "", // add title to print page
                    removeInline: false, // remove all inline styles from print elements
                    printDelay: 333, // variable print delay; depending on complexity a higher value may be necessary
                    header: null, // prefix to html
                    footer: null, // postfix to html
                    base: false, // preserve the BASE tag, or accept a string for the URL
                    formValues: true, // preserve input/form values
                    canvas: false, // copy canvas elements (experimental)
                    doctypeString: ".", // enter a different doctype for older markup
                    removeScripts: false, // remove script tags from print content
                    copyTagClasses: false

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
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
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

        $(document).ready(function() {
            checkCookie();
        });

        function checkCookie() {
            var user = getCookie("print_size");
            if (user != "") {

                $("#prescription_size").val(user);

                if (user == 'A4') {
                    $(".container").css('width', '650px');
                } else {
                    $(".container").css('width', '450px');
                }

            } else {
                $("#prescription_size").val('A4');
                if (user != "" && user != null) {
                    setCookie("print_size", 'A4', 365);
                }
                $(".container").css('width', '650px');

            }
        }

        $(document).on("change", "#prescription_size", function() {
            var value = $(this).val();
            setCookie("print_size", value, 365);
            checkCookie();
        });

        $(document).on("change", "#template", function() {
            var value = $(this).val();
            setCookie("template_invoice", value, 365);
            location.reload();
        });


        $(document).on("change", "#font_size", function() {
            var value = $(this).val();
            setCookie("invoice_font_size", value, 365);
            $("body").css('font-size', value);
        });



        $(document).ready(function() {



            $("#font_size").val("<?php echo $font_size; ?>");
            $("#template").val("<?php echo $template; ?>");

            var chkEmpty = false;
            $('.exp_date_td').each(function() {

                if ($(this).html() != " -") {
                    console.log($(this).html());
                    chkEmpty = true;
                }
            });
            if (chkEmpty == true) {
                console.log(chkEmpty);
                $(".exp_date_td").show();
                $(".exp_date_th").show();
            } else {
                $(".exp_date_td").hide();
                $(".exp_date_th").hide();

                $(".cols").each(function() {
                    var defaultCol = $(this).attr("colspan");
                    $(this).attr("colspan", defaultCol - 1);
                });
            }
        });
    </script>


    </html>
<?php } else if ($_COOKIE["template_invoice"] == "new") { ?>
    <?php $login = $this->Session->read('Auth.User'); ?>
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
            var baseurl = '<?php echo Router::url('/', true); ?>';
            <?php
            $url = $this->request->url;
            $urlPrescrption = "";
            if ($invoice_type == "OPD") {
                $appointment_id = $data = @$invoiceData[0]['appointment_id'];
                $urlPrescrption = Router::url('/app_admin/print_prescription/' . base64_encode($appointment_id), true);
            }

            $urlMiniReceipt = str_replace('print_invoice', 'print_mini_invoice', $url);
            ?>
            var prescriptionURL = '<?php echo $urlPrescrption ?>';
            var miniReceiptURL = '<?php echo Router::url('/', true); ?><?php echo $urlMiniReceipt; ?>';
        </script>
        <?php echo $this->Html->script(array('jquery.js', 'printThis.js')); ?>
        <?php echo $this->Html->css(array('font-awesome.min.css')); ?>
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

            .user_biller_detail_gap {
                width: 20%;
            }

            .user_detail_holder {
                width: 40%;
            }

            body {
                font-size: 13px;
                font-family: sans-serif;
            }

            .biller_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                /*background-color: #A9A9A9 !important;
                color: #FFFFFF;*/
                padding: 2px 0px 2px 6px;
            }

            .patient_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                /*background-color: #A9A9A9 !important;
                color: #FFFFFF;*/
                padding: 2px 0px 2px 6px;
            }

            .company_detail_holder {
                width: 60%;
            }

            .recept_detail_holder {
                width: 40%;
            }

            .title_holder {
                padding: 0px 0px 0px 6px;
            }

            .product_detail_holder tr th {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                /*background-color: #A9A9A9 !important;
                color: #FFFFFF;*/
                padding: 2px 3px 2px 3px;
                /*border: 1px solid #77797c;*/
            }

            .product_detail_holder tr td {
                padding: 4px 3px 4px 3px;
                /*border-right: 1px solid #77797c;
                border-left: 1px solid #77797c;*/
            }

            .product_detail_holder tr:nth-child(even) {
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
                /*background-color: #f2f2f2 !important;*/
            }

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

            .note-prescription {
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }

            .note-mini-receipt {
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }

            .footer_text {
                text-align: center;
            }

            .created_by {
                text-align: right;
            }

            .prescription_size {
                text-align: center;
            }

            .template {
                text-align: center;
                margin-top: 3px;
            }

            h1 {

                line-height: 0px;

            }

            h2 {

                line-height: 0px;

            }

            h3 {

                line-height: 0px;

            }

            .logo_img img {
                margin-left: 21%;
            }

            .recept_detail_holder h2 {

                text-align: center;

            }

            .hospital_title {
                text-align: center;
                border-bottom: 1px solid black;
            }

            ul {
                margin-left: -36px !important;
                list-style-type: none;
            }

            ul li {
                width: 33.333333%;
                float: left;
            }

            .product_detail_holder tr {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                border-top: 1px solid #000000;
            }
        </style>
        <style media="print">
            @page {
                size: A4;
                margin: 0;
                font-size: 13px;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            table {
                font-size: 13px;
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

                .user_biller_detail_gap {
                    width: 20%;
                }

                .user_detail_holder {
                    width: 40%;
                }

                body {

                    font-family: sans-serif;
                }

                .biller_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .patient_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .company_detail_holder {
                    width: 60%;
                }

                .recept_detail_holder {
                    width: 40%;
                }

                .title_holder {
                    padding: 0px 0px 0px 6px;
                }

                .product_detail_holder tr th {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 3px 2px 3px;
                    /*border: 1px solid #77797c;*/
                }

                .product_detail_holder tr td {
                    padding: 2px 3px 2px 3px;
                    /*border-right: 1px solid #77797c;
                    border-left: 1px solid #77797c;*/
                }

                .product_detail_holder tr:nth-child(even) {
                    color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                    /*background-color: #f2f2f2 !important;*/
                }

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

                .note-prescription {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .note-mini-receipt {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .footer_text {
                    text-align: center;
                }

                .created_by {
                    text-align: right;
                }

                .prescription_size {
                    text-align: center;
                }

                .template {
                    text-align: center;
                    margin-top: 3px;
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

                h2 {

                    line-height: 0px;

                }

                h3 {

                    line-height: 0px;

                }

                .logo_img img {
                    margin-left: 21%;
                }

                .recept_detail_holder h2 {

                    text-align: center;

                }

                .hospital_title {
                    text-align: center;
                    border-bottom: 1px solid black;
                }

                ul {
                    margin-left: -36px !important;
                    list-style-type: none;
                }

                ul li {
                    width: 33.333333%;
                    float: left;
                }

                .product_detail_holder tr {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    border-top: 1px solid #000000;
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

                .user_biller_detail_gap {
                    width: 20%;
                }

                .user_detail_holder {
                    width: 40%;
                }

                body {

                    font-family: sans-serif;
                }

                .biller_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .patient_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .company_detail_holder {
                    width: 60%;
                }

                .recept_detail_holder {
                    width: 40%;
                }

                .title_holder {
                    padding: 0px 0px 0px 6px;
                }

                .product_detail_holder tr th {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 3px 2px 3px;
                    /*border: 1px solid #77797c;*/
                }

                .product_detail_holder tr td {
                    padding: 2px 3px 2px 3px;
                    /*border-right: 1px solid #77797c;
                    border-left: 1px solid #77797c;*/
                }

                .product_detail_holder tr:nth-child(even) {
                    color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                    /*background-color: #f2f2f2 !important;*/
                }

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

                .note-prescription {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .note-mini-receipt {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .footer_text {
                    text-align: center;
                }

                .created_by {
                    text-align: right;
                }

                .prescription_size {
                    text-align: center;
                }

                .template {
                    text-align: center;
                    margin-top: 3px;
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

                h2 {

                    line-height: 0px;

                }

                h3 {

                    line-height: 0px;

                }

                .logo_img img {
                    margin-left: 21%;
                }

                .recept_detail_holder h2 {

                    text-align: center;

                }

                .hospital_title {
                    text-align: center;
                    border-bottom: 1px solid black;
                }

                ul {
                    margin-left: -36px !important;
                    list-style-type: none;
                }

                ul li {
                    width: 33.333333%;
                    float: left;
                }

                .product_detail_holder tr {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    border-top: 1px solid #000000;
                }
            }
        </style>

        <?php
        $data = @$invoiceData[0];

        if (isset($data['created'])) {
            $receiptID = $receiptID = $data['receipt_id']; //$this->AppAdmin->get_receipt_id_by_order_id($data['medical_product_id']);
        } else {
            $receiptID = "-";
        }
        ?>

        <title>Receipt No.: <?php echo $receiptID; ?></title>
    </head>

    <body style="font-size: <?php echo $font_size; ?>">

        <div class="container">
            <br>


            <table class="top_table">
                <tr>
                    <td class="hospital_title">
                        <div class="logo_img">
                            <?php if ($data['logo'] != '') { ?>
                                <img style="height: 50px;margin-left: 0px;" src="<?php echo $data['logo']; ?>">
                            <?php } ?>

                        </div>
                    </td>
                    <td class="hospital_title">
                        <h1><?php echo ($data['receipt_header_title'] != '') ? $data['receipt_header_title'] : $data['app_name']; ?></h1>
                        <p>
                            <?php if ($invoice_type == "DUE") { ?>
                                <?php echo ucwords($data['address']); ?>
                            <?php } else {
                                $address =  $data['address'];
                                if (strtoupper($address) == 'ADDRESS GOES HERE') {
                                    $address = '';
                                }
                                if (!empty($address) && !empty($invoice_type != "IPD_ADVANCE_DEPOSIT")) {
                                    echo ucwords($address);
                                }
                            } ?>
                        </p>
                        <h3>
                            <?php
                            if ($invoice_type == "OPD") {
                                echo !empty($data['receipt_top_left_title']) ? $data['receipt_top_left_title'] : "OPD Bill";
                            } else if ($invoice_type == "IPD") {
                                echo (@$data['hospital_ipd_id'] > 0) ? 'IPD Bill' : 'Bill';
                            } else if ($invoice_type == "IPD_ADVANCE_DEPOSIT") {
                                echo 'IPD Bill';
                            } else if ($invoice_type == "DUE") {
                                echo 'DUE AMOUNT SETTLEMENT';
                            }
                            ?>
                        </h3>
                    </td>
                </tr>
                <tr>
                    <td class="doctor_patient_details" colspan="2">
                        <ul>
                            <li>
                                <b>Receipt No: </b>
                                <?php echo !empty($receiptID) ? $receiptID : ' - ';  ?>
                            </li>

                            <?php if ($login['User']['thinapp_id'] == '497') { ?>
                                <li>
                                    <b>Bill No: </b>
                                </li>
                            <?php } ?>

                            <?php if (!empty($data['bill_id'])) { ?>
                                <li>
                                    <b>Bill No: </b>
                                    <?php echo $data['bill_id'];  ?>
                                </li>
                            <?php } ?>

                            <?php if (@$data['queue_number'] > 0 && $invoice_type == "OPD") {

                                $token = $this->AppAdmin->create_queue_number($data);

                            ?>

                                <?php if ($data['show_token_on_receipt'] == "YES") { ?>
                                    <li><b>Token: </b><b><?php echo $token; ?></b></li>
                                <?php } ?>
                                <?php if ($data['show_token_time_on_receipt'] == "YES") { ?>

                                    <li><b>Appointment Time: </b><b><?php echo ($data['emergency_appointment'] == "YES") ? date('h:i A', strtotime($data['appointment_created'])) : $data['slot_time']; ?></b></li>
                                <?php } ?>
                            <?php } ?>

                            <?php if (@$data['hospital_ipd_id'] > 0 && ($invoice_type == "IPD" || $invoice_type == "IPD_ADVANCE_DEPOSIT")) { ?>
                                <li><b>IPD ID: </b><?php echo !empty($data['ipd_unique_id']) ? $data['ipd_unique_id'] : "-"; ?></li>
                                <li><b>Admit Date: </b><?php echo date("d M Y", strtotime($data['admit_date'])); ?></li>

                                <?php if ($data['admit_status'] == 'DISCHARGE') {
                                    $discharge_date =  $this->AppAdmin->getIPDDischargeDate($data['hospital_ipd_id'], $login['User']['thinapp_id']);
                                    if (!empty($discharge_date)) {   ?>
                                        <li><b>Discharge Date.:</b><?php echo date("d M Y", strtotime($discharge_date)); ?></li>
                                <?php }
                                } ?>




                            <?php }; ?>

                            <li><b>Payment Mode: </b><?php if (!empty($data['payment_type_name'])) {
                                                            echo $data['payment_type_name'];
                                                        } else {
                                                            echo '-';
                                                        } ?></li>
                            <?php if ($data['show_date_on_receipt'] == "YES" || $data['show_time_on_receipt'] == "YES") { ?>
                                <li>
                                    <?php
                                    $print_date = array();
                                    $print_label = array();
                                    $date_key = !empty($data['billing_date']) ? 'billing_date' : 'appointment_created';
                                    if ($data['show_date_on_receipt'] == "YES") {
                                        $print_date[] = date('d M Y', strtotime(@$data[$date_key]));
                                        $print_label[] = "Date";
                                    }
                                    if ($data['show_time_on_receipt'] == "YES") {
                                        $print_date[] = date('h:i A', strtotime(@$data[$date_key]));
                                        $print_label[] = "Time";
                                    }

                                    ?>
                                    <b><?php echo "Receipt " . implode("/", $print_label) . ":"; ?> </b>
                                    <?php echo implode(" ", $print_date); ?>
                                </li>
                            <?php } ?>

                            <li><b>UHID: </b><?php echo $data['uhid']; ?></li>


                            <li><b>Name: </b><?php echo ucwords($data['patient_name']); ?></li>
                            <li><b>Gender: </b><?php echo ucwords(strtolower($data['gender'])); ?></li>
                            <?php
                            $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
                            if (empty($ageStr)) {
                                $ageStr = $data['age'];
                            } ?>
                            <li><b>Age: </b><?php echo ($ageStr); ?></li>

                            <?php
                            $address =  $data['patient_address'];
                            if ($address != ""  && strtoupper($address) != 'ADDRESS GOES HERE') { ?>
                                <li><b>Address: </b><?php echo ucwords($address); ?></li>
                            <?php } ?>

                            <?php
                            $parentName =  $data['parents_name'];
                            $relationPrefix =  $data['relation_prefix'];
                            if ($parentName != "") { ?>
                                <li>
                                    <b><?php echo ($relationPrefix != '') ? $relationPrefix . ': ' : "Parent's Name:"; ?><?php echo ucwords($parentName); ?>
                                </li>
                            <?php } ?>

                            <?php if (!empty($data['mobile']) && $data['show_patient_mobile_on_receipt'] == "YES") { ?>
                                <li><b>Mobile: </b><?php echo $data['mobile']; ?></li>
                            <?php } ?>


                            <?php if ($data['height'] != "") { ?>
                                <li><b>Height: </b><?php echo $data['height']; ?></li>
                            <?php } ?>
                            <?php if ($data['weight'] != "") { ?>
                                <li><b>Weight: </b><?php echo $data['weight']; ?></li>
                            <?php } ?>




                            <?php if ($invoice_type == "DUE") { ?>
                                <li><b>Organization Name: </b><?php echo ucwords($data['app_name']); ?></li>
                            <?php } else { ?>

                                <?php $refer_lbl = "Consulting Doctor";
                                if (!empty($data['consult_with']) && $data['show_doctor_on_receipt'] == "YES") {
                                    $refer_lbl = "Referring Doctor";  ?>
                                    <li><b>Doctor: </b><?php echo ucwords($data['consult_with']); ?></li>
                                <?php } ?>
                                <?php if (!empty($data['department_name']) && $data['show_department_on_receipt'] == "YES") { ?>
                                    <li><b>Department: </b><?php echo ucwords($data['department_name']); ?></li>
                                <?php } ?>
                                <?php if ($data['show_referrer_on_receipt'] == "YES" && !empty($data['reffered_by_name'])) { ?>
                                    <li><b><?php echo $refer_lbl; ?>: </b><?php echo ucwords(strtolower($data['reffered_by_name'])); ?></li>
                                <?php } ?>
                            <?php } ?>



                            <?php if ($data['show_paid_user_order_number_on_receipt'] == "YES" && !empty($data['paid_receipt_number']) && $data['total_paid'] > 0) { ?>
                                <li><b>Paid Number: </b><?php echo $data['paid_receipt_number']; ?></li>

                            <?php } ?>



                        </ul>
                    </td>
                </tr>
            </table>






            <table class="product_detail_holder">

                <?php if ($invoice_type != "DUE") { ?>
                    <tbody>

                        <tr>
                            <th align="left"><b>#</b></th>


                            <?php if ($invoice_type == 'IPD') { ?>
                                <th colspan="3" align="left"><b><?php echo $data['is_package'] == 'N' ? 'Service' : 'Package'; ?></b></th>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <th colspan="1" align="left"><b>Batch</b></th>
                                    <th colspan="1" align="left" class="exp_date_th"><b>Expiry Date</b></th>
                                <?php } ?>
                                <th align="right"><b><?php echo $data['is_package'] == 'N' ? 'Rate' : 'Amount'; ?></b></th>
                            <?php } else { ?>
                                <th colspan="3" align="left"><b>Service</b></th>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <th colspan="1" align="left"><b>Batch</b></th>
                                    <th colspan="1" align="left" class="exp_date_th"><b>Expiry Date</b></th>
                                <?php } ?>
                                <th align="right"><b>Rate</b></th>
                            <?php } ?>


                            <th align="center"><b>X</b></th>
                            <th align="left"><b>Qty</b></th>
                            <th align="right" class="hide_dis"><b>Discount</b></th>
                            <th align="right" class="hide_tax"><b>Tax</b></th>
                            <th align="right"><b>Net Amount</b></th>
                        </tr>
                        <?php $counter = 0;
                        $discount = $tax = $total_amount = 0;
                        foreach ($invoiceData as $key => $orderList) {
                            if ($orderList['show_into_receipt'] == "YES") {
                                $discount += $orderList['discount_amount'];
                                $tax +=  $orderList['tax_value'];
                                $total_amount += $orderList['total_amount'];
                        ?>
                                <tr>
                                    <td class=""><?php echo ++$counter; ?></td>

                                    <td class="" colspan="3"><?php echo ucwords($orderList['service_name']); ?></td>

                                    <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                        <td class="" align="left"> <?php echo $orderList['batch']; ?></td>
                                        <td align="left" class="exp_date_td"> <?php echo ($orderList['expiry_date'] == '0000-00-00') ? '-' : $orderList['expiry_date']; ?></td>
                                    <?php } ?>
                                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['product_price']; ?></td>
                                    <td class="" align="center"> <?php echo 'X'; ?></td>
                                    <td class="" align="left"> <?php echo $orderList['quantity']; ?></td>
                                    <td class="hide_dis" align="right" <?php echo ($orderList['discount_amount'] > 0) ? '' : 'style="padding-right: 17px;"'; ?>><?php echo ($orderList['discount_amount'] > 0) ? '<i class="fa fa-inr" aria-hidden="true"></i> ' . $orderList['discount_amount'] : '-'; ?></td>
                                    <td class="hide_tax" align="right">
                                        <?php if ($orderList['tax_value'] > 0) { ?>
                                            <?php echo $orderList['tax_type']; ?>@<?php echo $orderList['tax_value']; ?> %
                                        <?php } ?>

                                    </td>

                                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>

                                </tr>
                        <?php }
                        } ?>

                        <?php if (!empty($data['due_paid_amount'])) { ?>

                            <tr class="">
                                <td><?php echo ++$counter; ?></td>
                                <td colspan="3">Due Amount Payment</td>
                                <td class="" align="left"> </td>
                                <td class="" align="left"> </td>
                                <td class="" align="left"> </td>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <td class="" align="left"></td>
                                    <td class="" align="left" </td>
                                    <?php } ?>
                                    <td class="hide_dis" align="right"></td>

                                    <td class="hide_tax" align="right"></td>
                                    <td align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $data['due_paid_amount']; ?></td>
                            </tr>

                        <?php } ?>

                        <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                            <tr class="">
                                <td class="col_chk1 cols" colspan="12"><br /></td>
                            </tr>
                        <?php } else { ?>
                            <tr class="">
                                <td class="col_chk1" colspan="10"><br /></td>
                            </tr>
                        <?php } ?>





                        <tr>
                            <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                <th colspan="11" class="col_chk cols" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <?php } else { ?>
                                <th colspan="9" class="col_chk" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <?php } ?>

                            <th colspan="1" align="right"><span class="double_underline"><u><b><i class="fa fa-inr" aria-hidden="true"></i> <?php
                                                                                                                                            if (isset($data['total_paid'])  && ($invoice_type == 'OPD' || $invoice_type == 'IPD')) {
                                                                                                                                                echo $data['total_paid'];
                                                                                                                                            } else {
                                                                                                                                                echo $total_amount;
                                                                                                                                            }
                                                                                                                                            ?></b></u></span></th>
                        </tr>

                    </tbody>
                <?php } else { ?>

                    <tbody>

                        <tr>
                            <th align="left"><b>#</b></th>
                            <th align="left"><b>Service</b></th>
                            <th align="right"><b>Net Amount</b></th>
                        </tr>
                        <?php $counter = 0;
                        $discount = $tax = $total_amount = 0;
                        foreach ($invoiceData as $key => $orderList) {
                            if ($orderList['show_into_receipt'] == "YES") {
                                $discount += $orderList['discount_amount'];
                                $tax +=  $orderList['tax_value'];
                                $total_amount += $orderList['total_amount'];
                        ?>
                                <tr>
                                    <td><?php echo ++$counter; ?></td>
                                    <td>Due Amount Payment</td>
                                    <td align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>
                                </tr>
                        <?php }
                        } ?>
                        <tr class="">
                            <td colspan="3"><br /></td>
                        </tr>

                        <tr>
                            <th></th>
                            <th class="col_chk" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <th align="right">
                                <span class="double_underline">
                                    <u>
                                        <b>
                                            <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?>
                                        </b>
                                    </u>
                                </span>
                            </th>
                        </tr>

                    </tbody>


                <?php } ?>

            </table>




            <div class="footer_text">
                <?php $ipd_data = array();
                if ($invoice_type == "IPD_ADVANCE_DEPOSIT" || $invoice_type == "IPD") { ?>
                    <?php $ipd_data = @$this->AppAdmin->ipdTotalBalance($data['hospital_ipd_id']); ?>
                    <?php if ($ipd_data['admit_status'] != 'DISCHARGE' && !empty($ipd_data['advance'])) { ?>
                        <p><u>Available Balance on <?php echo date('d-m-Y h:i:s'); ?> <b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $ipd_data['advance'] - $ipd_data['expense']; ?></b></u></p>
                <?php }
                } ?>

                <?php if ($invoice_type == 'OPD' || ($invoice_type == 'IPD' && empty($ipd_data['ipd_unique_id']))) {
                    if (!empty($data['total_due_amount'])) { ?>
                        <p><u>Your Total Due Balance is : <?php echo $data['total_due_amount'] . " Rs/-"; ?></b></u></p>
                <?php }
                } ?>



                <p><?php echo $data['receipt_footer_title']; ?></p>


            </div>



            <div class="created_by">
                <p><?php echo !empty($data['created_by']) ? "Thanks for visit " . $data['created_by'] : ''; ?></p>
            </div>


        </div>



        <div class="note">
            Note: Press 'Enter' to print or click here
        </div>

        <div class="note-prescription">
            Note: Press 'Space' to print prescription or click here
        </div>

        <div class="option">
            <div class="option_div"> <b>Font Size</b>&nbsp
                <select id="font_size">
                <option value="3px">3</option>
                    <option value="4px">4</option>
                    <option value="5px">5</option>
                    <option value="6px">6</option>
                    <option value="7px">7</option>
                    <option value="8px">8</option>
                    <option value="9px">9</option>
                    <option value="10px">10</option>
                    <option value="11px">11</option>
                    <option value="12px">12</option>
                    <option value="13px">13</option>
                    <option value="14px">14</option>
                    <option value="15px">15</option>
                    <option value="16px">16</option>
                    <option value="17px">17</option>
                </select>
            </div>
            <div class="option_div"> <b>Paper Size</b>&nbsp
                <select id="prescription_size">
                    <option value="A4">A4</option>
                    <option value="A4/4">A4/4</option>
                </select>
            </div>
            <div class="option_div"> <b>Template</b>&nbsp
                <select id="template">
                    <option value="default">Template 1</option>
                    <option value="new">Template 2</option>
                    <option value="eye">Eye Template</option>
                </select>
            </div>
        </div>



    </body>

    <style>
        .option {
            width: 47%;
            float: left;
            text-align: center;
            display: block;
            margin: 10px 30%;
            position: relative;
        }

        .option_div {
            width: 28%;
            float: left;
            font-size: 14px !important;
        }

        .option_div select {
            font-size: 14px !important;
        }
    </style>
    <script>
        $(function() {

            var dis = '<?php echo $discount; ?>';
            var tax = '<?php echo $tax; ?>';

            var tot = 0;
            if (dis == 0) {
                $('.hide_dis').remove();
                tot = tot + 1;
            }
            if (tax == 0) {
                $('.hide_tax').remove();
                tot = tot + 1;
            }





            $('.col_chk').attr('colspan', ($('.col_chk').attr('colspan') - tot));
            $('.col_chk1').attr('colspan', ($('.col_chk1').attr('colspan') - tot));
            var total_th = $(".product_detail_holder tbody tr:first-child th").length;

            $('.due_amount_label_td').attr('colspan', total_th);


            $(document).keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('.container').printThis({ // show the iframe for debugging
                        importCSS: true, // import page CSS
                        importStyle: true, // import style tags
                        pageTitle: "", // add title to print page
                        removeInline: false, // remove all inline styles from print elements
                        printDelay: 333, // variable print delay; depending on complexity a higher value may be necessary
                        header: null, // prefix to html
                        footer: null, // postfix to html
                        base: false, // preserve the BASE tag, or accept a string for the URL
                        formValues: true, // preserve input/form values
                        canvas: false, // copy canvas elements (experimental)
                        doctypeString: ".", // enter a different doctype for older markup
                        removeScripts: false, // remove script tags from print content
                        copyTagClasses: false // copy classes from the html & body tag
                    });
                } else if (e.which == 32) {
                    window.open(
                        prescriptionURL,
                        '_blank' // <- This is what makes it open in a new window.
                    );
                } else if (e.which == 109) {
                    //window.open(miniReceiptURL);
                }
            });
            $(document).on('click', ".note-prescription", function() {
                window.open(
                    prescriptionURL,
                    '_blank' // <- This is what makes it open in a new window.
                );
            });
            $(document).on('click', ".note-mini-receipt", function() {
                window.open(miniReceiptURL);
            });
            $(document).on('click', ".note", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('.container').printThis({ // show the iframe for debugging
                    importCSS: true, // import page CSS
                    importStyle: true, // import style tags
                    pageTitle: "", // add title to print page
                    removeInline: false, // remove all inline styles from print elements
                    printDelay: 333, // variable print delay; depending on complexity a higher value may be necessary
                    header: null, // prefix to html
                    footer: null, // postfix to html
                    base: false, // preserve the BASE tag, or accept a string for the URL
                    formValues: true, // preserve input/form values
                    canvas: false, // copy canvas elements (experimental)
                    doctypeString: ".", // enter a different doctype for older markup
                    removeScripts: false, // remove script tags from print content
                    copyTagClasses: false // copy classes from the html & body tag
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
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
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


        $(document).ready(function() {
            checkCookie();
        });

        function checkCookie() {
            var user = getCookie("print_size");
            if (user != "") {

                $("#prescription_size").val(user);

                if (user == 'A4') {
                    $(".container").css('width', '650px');
                } else {
                    $(".container").css('width', '450px');
                }

            } else {
                $("#prescription_size").val('A4');
                if (user != "" && user != null) {
                    setCookie("print_size", 'A4', 365);
                }
                $(".container").css('width', '650px');

            }
        }

        $(document).on("change", "#prescription_size", function() {
            var value = $(this).val();
            setCookie("print_size", value, 365);
            checkCookie();
        });

        $(document).on("change", "#template", function() {
            var value = $(this).val();
            setCookie("template_invoice", value, 365);
            location.reload();
        });



        $(document).on("change", "#font_size", function() {
            var value = $(this).val();
            setCookie("invoice_font_size", value, 365);
            $("body").css('font-size', value);
        });




        $(document).ready(function() {

            $("#font_size").val("<?php echo $font_size; ?>");
            $("#template").val("<?php echo $template; ?>");
            var chkEmpty = false;
            $('.exp_date_td').each(function() {

                if ($(this).html() != " -") {
                    console.log($(this).html());
                    chkEmpty = true;
                }
            });
            if (chkEmpty == true) {
                console.log(chkEmpty);
                $(".exp_date_td").show();
                $(".exp_date_th").show();
            } else {
                $(".exp_date_td").hide();
                $(".exp_date_th").hide();

                $(".cols").each(function() {
                    var defaultCol = $(this).attr("colspan");
                    $(this).attr("colspan", defaultCol - 1);
                });
            }
        });
    </script>


    </html>
<?php } else { ?>
    <?php $login = $this->Session->read('Auth.User'); ?>
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
            var baseurl = '<?php echo Router::url('/', true); ?>';
            <?php
            $url = $this->request->url;
            $urlPrescrption = "";
            if ($invoice_type == "OPD") {
                $appointment_id = $data = @$invoiceData[0]['appointment_id'];
                $urlPrescrption = Router::url('/app_admin/print_prescription/' . base64_encode($appointment_id), true);
            }

            $urlMiniReceipt = str_replace('print_invoice', 'print_mini_invoice', $url);
            ?>
            var prescriptionURL = '<?php echo $urlPrescrption ?>';
            var miniReceiptURL = '<?php echo Router::url('/', true); ?><?php echo $urlMiniReceipt; ?>';
        </script>
        <?php echo $this->Html->script(array('jquery.js', 'printThis.js')); ?>
        <?php echo $this->Html->css(array('font-awesome.min.css')); ?>
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

            .user_biller_detail_gap {
                width: 20%;
            }

            .user_detail_holder {
                width: 40%;
            }

            body {
                font-size: 13px;
                font-family: sans-serif;
            }

            .biller_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                /*background-color: #A9A9A9 !important;
                color: #FFFFFF;*/
                padding: 2px 0px 2px 6px;
            }

            .patient_title {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                /*background-color: #A9A9A9 !important;
                color: #FFFFFF;*/
                padding: 2px 0px 2px 6px;
            }

            .company_detail_holder {
                width: 60%;
            }

            .recept_detail_holder {
                width: 40%;
            }

            .title_holder {
                padding: 0px 0px 0px 6px;
            }

            .product_detail_holder tr th {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                /*background-color: #A9A9A9 !important;
                color: #FFFFFF;*/
                padding: 2px 3px 2px 3px;
                /*border: 1px solid #77797c;*/
            }

            .product_detail_holder tr td {
                padding: 4px 3px 4px 3px;
                /*border-right: 1px solid #77797c;
                border-left: 1px solid #77797c;*/
            }

            .product_detail_holder tr:nth-child(even) {
                color-adjust: exact;
                -webkit-print-color-adjust: exact;
                /*background-color: #f2f2f2 !important;*/
            }

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

            .note-prescription {
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }

            .note-mini-receipt {
                text-align: center;
                color: red;
                font-weight: bold;
                cursor: pointer;
            }

            .footer_text {
                text-align: center;
            }

            .created_by {
                text-align: right;
            }

            .prescription_size {
                text-align: center;
            }

            .template {
                text-align: center;
                margin-top: 3px;
            }

            h1 {

                line-height: 0px;

            }

            h2 {

                line-height: 0px;

            }

            h3 {

                line-height: 0px;

            }

            .logo_img img {
                margin-left: 21%;
            }

            .recept_detail_holder h2 {

                text-align: center;

            }

            .hospital_title {
                text-align: center;
                border-bottom: 1px solid black;
            }

            ul {
                margin-left: -36px !important;
                list-style-type: none;
            }

            ul li {
                width: 33.333333%;
                float: left;
            }

            .product_detail_holder tr {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                border-top: 1px solid #000000;
            }
        </style>
        <style media="print">
            @page {
                size: A4;
                margin: 0;
                font-size: 13px;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            table {
                font-size: 13px;
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

                .user_biller_detail_gap {
                    width: 20%;
                }

                .user_detail_holder {
                    width: 40%;
                }

                body {

                    font-family: sans-serif;
                }

                .biller_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .patient_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .company_detail_holder {
                    width: 60%;
                }

                .recept_detail_holder {
                    width: 40%;
                }

                .title_holder {
                    padding: 0px 0px 0px 6px;
                }

                .product_detail_holder tr th {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 3px 2px 3px;
                    /*border: 1px solid #77797c;*/
                }

                .product_detail_holder tr td {
                    padding: 2px 3px 2px 3px;
                    /*border-right: 1px solid #77797c;
                    border-left: 1px solid #77797c;*/
                }

                .product_detail_holder tr:nth-child(even) {
                    color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                    /*background-color: #f2f2f2 !important;*/
                }

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

                .note-prescription {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .note-mini-receipt {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .footer_text {
                    text-align: center;
                }

                .created_by {
                    text-align: right;
                }

                .prescription_size {
                    text-align: center;
                }

                .template {
                    text-align: center;
                    margin-top: 3px;
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

                h2 {

                    line-height: 0px;

                }

                h3 {

                    line-height: 0px;

                }

                .logo_img img {
                    margin-left: 21%;
                }

                .recept_detail_holder h2 {

                    text-align: center;

                }

                .hospital_title {
                    text-align: center;
                    border-bottom: 1px solid black;
                }

                ul {
                    margin-left: -36px !important;
                    list-style-type: none;
                }

                ul li {
                    width: 33.333333%;
                    float: left;
                }

                .product_detail_holder tr {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    border-top: 1px solid #000000;
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

                .user_biller_detail_gap {
                    width: 20%;
                }

                .user_detail_holder {
                    width: 40%;
                }

                body {

                    font-family: sans-serif;
                }

                .biller_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .patient_title {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 0px 2px 6px;
                }

                .company_detail_holder {
                    width: 60%;
                }

                .recept_detail_holder {
                    width: 40%;
                }

                .title_holder {
                    padding: 0px 0px 0px 6px;
                }

                .product_detail_holder tr th {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    /*background-color: #A9A9A9 !important;
                    color: #FFFFFF !important;*/
                    padding: 2px 3px 2px 3px;
                    /*border: 1px solid #77797c;*/
                }

                .product_detail_holder tr td {
                    padding: 2px 3px 2px 3px;
                    /*border-right: 1px solid #77797c;
                    border-left: 1px solid #77797c;*/
                }

                .product_detail_holder tr:nth-child(even) {
                    color-adjust: exact;
                    -webkit-print-color-adjust: exact;
                    /*background-color: #f2f2f2 !important;*/
                }

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

                .note-prescription {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .note-mini-receipt {
                    text-align: center;
                    color: red;
                    font-weight: bold;
                    cursor: pointer;
                }

                .footer_text {
                    text-align: center;
                }

                .created_by {
                    text-align: right;
                }

                .prescription_size {
                    text-align: center;
                }

                .template {
                    text-align: center;
                    margin-top: 3px;
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

                h2 {

                    line-height: 0px;

                }

                h3 {

                    line-height: 0px;

                }

                .logo_img img {
                    margin-left: 21%;
                }

                .recept_detail_holder h2 {

                    text-align: center;

                }

                .hospital_title {
                    text-align: center;
                    border-bottom: 1px solid black;
                }

                ul {
                    margin-left: -36px !important;
                    list-style-type: none;
                }

                ul li {
                    width: 33.333333%;
                    float: left;
                }

                .product_detail_holder tr {
                    -webkit-print-color-adjust: exact;
                    color-adjust: exact;
                    border-top: 1px solid #000000;
                }
            }
        </style>

        <?php
        $data = @$invoiceData[0];

        if (isset($data['created'])) {
            $receiptID = $receiptID = $data['receipt_id']; //$this->AppAdmin->get_receipt_id_by_order_id($data['medical_product_id']);
        } else {
            $receiptID = "-";
        }
        ?>

        <title>Receipt No.: <?php echo $receiptID; ?></title>
    </head>

    <body style="font-size: <?php echo $font_size; ?>">

        <div class="container">
            <br>


            <table class="top_table">
                <tr>
                    <td class="hospital_title">
                        <h1><?php echo ($data['receipt_header_title'] != '') ? $data['receipt_header_title'] : $data['app_name']; ?></h1>
                        <p>
                            <?php if ($invoice_type == "DUE") { ?>
                                <?php echo ucwords($data['address']); ?>
                            <?php } else {
                                $address =  $data['address'];
                                if (strtoupper($address) == 'ADDRESS GOES HERE') {
                                    $address = '';
                                }
                                if (!empty($address) && !empty($invoice_type != "IPD_ADVANCE_DEPOSIT")) {
                                    echo ucwords($address);
                                }
                            } ?>
                        </p>
                        <h3>
                            <?php
                            if ($invoice_type == "OPD") {
                                echo !empty($data['receipt_top_left_title']) ? $data['receipt_top_left_title'] : "OPD Bill";
                            } else if ($invoice_type == "IPD") {
                                echo (@$data['hospital_ipd_id'] > 0) ? 'IPD Bill' : 'Bill';
                            } else if ($invoice_type == "IPD_ADVANCE_DEPOSIT") {
                                echo 'IPD Bill';
                            } else if ($invoice_type == "DUE") {
                                echo 'DUE AMOUNT SETTLEMENT';
                            }
                            ?>
                        </h3>
                    </td>
                </tr>
                <tr>
                    <td class="doctor_patient_details">
                        <ul>
                            <li>
                                <b>Receipt No: </b>
                                <?php echo !empty($receiptID) ? $receiptID : ' - ';  ?>
                            </li>

                            <?php if ($login['User']['thinapp_id'] == '497') { ?>
                                <li>
                                    <b>Bill No: </b>
                                </li>
                            <?php } ?>

                            <?php if (!empty($data['bill_id'])) { ?>
                                <li>
                                    <b>Bill No: </b>
                                    <?php echo $data['bill_id'];  ?>
                                </li>
                            <?php } ?>

                            <?php if (@$data['queue_number'] > 0 && $invoice_type == "OPD") {


                                $token = $this->AppAdmin->create_queue_number($data);

                            ?>

                                <?php if ($data['show_token_on_receipt'] == "YES") { ?>
                                    <li><b>Token: </b><b><?php echo $token; ?></b></li>
                                <?php } ?>
                                <?php if ($data['show_token_time_on_receipt'] == "YES") { ?>
                                    <li><b>Appointment Time: </b><b><?php echo $data['slot_time']; ?></b></li>
                                <?php } ?>
                            <?php } ?>

                            <?php if (@$data['hospital_ipd_id'] > 0 && ($invoice_type == "IPD" || $invoice_type == "IPD_ADVANCE_DEPOSIT")) { ?>
                                <li><b>IPD ID: </b><?php echo !empty($data['ipd_unique_id']) ? $data['ipd_unique_id'] : "-"; ?></li>
                                <li><b>Admit Date: </b><?php echo date("d M Y", strtotime($data['admit_date'])); ?></li>
                            <?php }; ?>

                            <li><b>Payment Mode: </b><?php if (!empty($data['payment_type_name'])) {
                                                            echo $data['payment_type_name'];
                                                        } else {
                                                            echo '-';
                                                        } ?></li>
                            <?php if ($data['show_date_on_receipt'] == "YES" || $data['show_time_on_receipt'] == "YES") { ?>
                                <li>
                                    <?php
                                    $print_date = array();
                                    $print_label = array();
                                    $date_key = !empty($data['billing_date']) ? 'billing_date' : 'appointment_created';
                                    if ($data['show_date_on_receipt'] == "YES") {
                                        $print_date[] = date('d M Y', strtotime(@$data[$date_key]));
                                        $print_label[] = "Date";
                                    }
                                    if ($data['show_time_on_receipt'] == "YES") {
                                        $print_date[] = date('h:i A', strtotime(@$data[$date_key]));
                                        $print_label[] = "Time";
                                    }

                                    ?>
                                    <b><?php echo "Receipt " . implode("/", $print_label) . ":"; ?> </b>
                                    <?php echo implode(" ", $print_date); ?>
                                </li>
                            <?php } ?>

                            <li><b>UHID: </b><?php echo $data['uhid']; ?></li>


                            <li><b>Name: </b><?php echo ucwords($data['patient_name']); ?></li>
                            <li><b>Gender: </b><?php echo ucwords(strtolower($data['gender'])); ?></li>
                            <?php
                            $ageStr = $this->AppAdmin->getAgeStringFromDob($data['age']);
                            if (empty($ageStr)) {
                                $ageStr = $data['age'];
                            } ?>
                            <li><b>Age: </b><?php echo ($ageStr); ?></li>

                            <?php
                            $address =  $data['patient_address'];
                            if ($address != ""  && strtoupper($address) != 'ADDRESS GOES HERE') { ?>
                                <li><b>Address: </b><?php echo ucwords($address); ?></li>
                            <?php } ?>

                            <?php
                            $parentName =  $data['parents_name'];
                            $relationPrefix =  $data['relation_prefix'];
                            if ($parentName != "") { ?>
                                <li>
                                    <b><?php echo ($relationPrefix != '') ? $relationPrefix : "Parent's Name:"; ?><?php echo ucwords($parentName); ?>
                                </li>
                            <?php } ?>

                            <?php if (!empty($data['mobile']) && $data['show_patient_mobile_on_receipt'] == "YES") { ?>
                                <li><b>Mobile: </b><?php echo $data['mobile']; ?></li>
                            <?php } ?>


                            <?php if ($data['height'] != "") { ?>
                                <li><b>Height: </b><?php echo $data['height']; ?></li>
                            <?php } ?>
                            <?php if ($data['weight'] != "") { ?>
                                <li><b>Weight: </b><?php echo $data['weight']; ?></li>
                            <?php } ?>

                            <?php if ($invoice_type == "DUE") { ?>
                                <li><b>Organization Name: </b><?php echo ucwords($data['app_name']); ?></li>
                            <?php } else { ?>

                                <?php $refer_lbl = "Consulting Doctor";
                                if (!empty($data['consult_with']) && $data['show_doctor_on_receipt'] == "YES") {
                                    $refer_lbl = "Referring Doctor";  ?>
                                    <li><b>Doctor: </b><?php echo ucwords($data['consult_with']); ?></li>
                                <?php } ?>
                                <?php if (!empty($data['department_name']) && $data['show_department_on_receipt'] == "YES") { ?>
                                    <li><b>Department: </b><?php echo ucwords($data['department_name']); ?></li>
                                <?php } ?>
                                <?php if ($data['show_referrer_on_receipt'] == "YES" && !empty($data['reffered_by_name'])) { ?>
                                    <li><b><?php echo $refer_lbl; ?>: </b><?php echo ucwords(strtolower($data['reffered_by_name'])); ?></li>
                                <?php } ?>
                            <?php } ?>

                            <?php if ($data['show_paid_user_order_number_on_receipt'] == "YES" && !empty($data['paid_receipt_number']) && $data['total_paid'] > 0) { ?>
                                <li><b>Paid Number: </b><?php echo $data['paid_receipt_number']; ?></li>

                            <?php } ?>

                        </ul>
                    </td>
                </tr>
            </table>






            <table class="product_detail_holder">

                <?php if ($invoice_type != "DUE") { ?>
                    <tbody>

                        <tr>
                            <th align="left"><b>#</b></th>


                            <?php if ($invoice_type == 'IPD') { ?>
                                <th colspan="3" align="left"><b><?php echo $data['is_package'] == 'N' ? 'Service' : 'Package'; ?></b></th>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <th colspan="1" align="left"><b>Batch</b></th>
                                    <th colspan="1" align="left" class="exp_date_th"><b>Expiry Date</b></th>
                                <?php } ?>
                                <th align="right"><b><?php echo $data['is_package'] == 'N' ? 'Rate' : 'Amount'; ?></b></th>
                            <?php } else { ?>
                                <th colspan="3" align="left"><b>Service</b></th>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <th colspan="1" align="left"><b>Batch</b></th>
                                    <th colspan="1" align="left" class="exp_date_th"><b>Expiry Date</b></th>
                                <?php } ?>
                                <th align="right"><b>Rate</b></th>
                            <?php } ?>


                            <th align="center"><b>X</b></th>
                            <th align="left"><b>Qty</b></th>
                            <th align="right" class="hide_dis"><b>Discount</b></th>
                            <th align="right" class="hide_tax"><b>Tax</b></th>
                            <th align="right"><b>Net Amount</b></th>
                        </tr>
                        <?php $counter = 0;
                        $discount = $tax = $total_amount = 0;
                        foreach ($invoiceData as $key => $orderList) {
                            if ($orderList['show_into_receipt'] == "YES") {
                                $discount += $orderList['discount_amount'];
                                $tax +=  $orderList['tax_value'];
                                $total_amount += $orderList['total_amount'];
                        ?>
                                <tr>
                                    <td class=""><?php echo ++$counter; ?></td>

                                    <td class="" colspan="3"><?php echo ucwords($orderList['service_name']); ?></td>

                                    <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                        <td class="" align="left"> <?php echo $orderList['batch']; ?></td>
                                        <td align="left" class="exp_date_td"> <?php echo ($orderList['expiry_date'] == '0000-00-00') ? '-' : $orderList['expiry_date']; ?></td>
                                    <?php } ?>
                                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['product_price']; ?></td>
                                    <td class="" align="center"> <?php echo 'X'; ?></td>
                                    <td class="" align="left"> <?php echo $orderList['quantity']; ?></td>
                                    <td class="hide_dis" align="right" <?php echo ($orderList['discount_amount'] > 0) ? '' : 'style="padding-right: 17px;"'; ?>><?php echo ($orderList['discount_amount'] > 0) ? '<i class="fa fa-inr" aria-hidden="true"></i> ' . $orderList['discount_amount'] : '-'; ?></td>
                                    <td class="hide_tax" align="right">
                                        <?php if ($orderList['tax_value'] > 0) { ?>
                                            <?php echo $orderList['tax_type']; ?>@<?php echo $orderList['tax_value']; ?> %
                                        <?php } ?>

                                    </td>

                                    <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>

                                </tr>
                        <?php }
                        } ?>

                        <?php if (!empty($data['due_paid_amount'])) { ?>

                            <tr class="">
                                <td><?php echo ++$counter; ?></td>
                                <td colspan="3">Due Amount Payment</td>
                                <td class="" align="left"> </td>
                                <td class="" align="left"> </td>
                                <td class="" align="left"> </td>
                                <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                    <td class="" align="left"></td>
                                    <td class="" align="left" </td>
                                    <?php } ?>
                                    <td class="hide_dis" align="right"></td>

                                    <td class="hide_tax" align="right"></td>
                                    <td align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $data['due_paid_amount']; ?></td>
                            </tr>

                        <?php } ?>

                        <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                            <tr class="">
                                <td class="col_chk1 cols" colspan="12"><br /></td>
                            </tr>
                        <?php } else { ?>
                            <tr class="">
                                <td class="col_chk1" colspan="10"><br /></td>
                            </tr>
                        <?php } ?>





                        <tr>
                            <?php if ($login['USER_ROLE'] == 'PHARMACY') { ?>
                                <th colspan="11" class="col_chk cols" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <?php } else { ?>
                                <th colspan="9" class="col_chk" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <?php } ?>

                            <th colspan="1" align="right"><span class="double_underline"><u><b><i class="fa fa-inr" aria-hidden="true"></i> <?php
                                                                                                                                            if (isset($data['total_paid'])  && ($invoice_type == 'OPD' || $invoice_type == 'IPD')) {
                                                                                                                                                echo $data['total_paid'];
                                                                                                                                            } else {
                                                                                                                                                echo $total_amount;
                                                                                                                                            }
                                                                                                                                            ?></b></u></span></th>
                        </tr>

                    </tbody>
                <?php } else { ?>

                    <tbody>

                        <tr>
                            <th align="left"><b>#</b></th>
                            <th align="left"><b>Service</b></th>
                            <th align="right"><b>Net Amount</b></th>
                        </tr>
                        <?php $counter = 0;
                        $discount = $tax = $total_amount = 0;
                        foreach ($invoiceData as $key => $orderList) {
                            if ($orderList['show_into_receipt'] == "YES") {
                                $discount += $orderList['discount_amount'];
                                $tax +=  $orderList['tax_value'];
                                $total_amount += $orderList['total_amount'];
                        ?>
                                <tr>
                                    <td><?php echo ++$counter; ?></td>
                                    <td>Due Amount Payment</td>
                                    <td align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>
                                </tr>
                        <?php }
                        } ?>
                        <tr class="">
                            <td colspan="3"><br /></td>
                        </tr>

                        <tr>
                            <th></th>
                            <th class="col_chk" align="right"><b>Total Paid Amount&nbsp;</b></th>
                            <th align="right">
                                <span class="double_underline">
                                    <u>
                                        <b>
                                            <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?>
                                        </b>
                                    </u>
                                </span>
                            </th>
                        </tr>

                    </tbody>


                <?php } ?>

            </table>



            <div class="eye_temp_container">
                <table border="1" style="width:100%;">
                    <tr>
                        <th></th>
                        <th>D.Sp</th>
                        <th>D.Cy</th>
                        <th>Axis</th>
                        <th>V.A</th>
                        <th>D.Sp</th>
                        <th>D.Cy</th>
                        <th>Axis</th>
                        <th>V.A</th>
                    </tr>
                    <tr>
                        <th>D</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th>A</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>



            <div class="footer_text">
                <?php $ipd_data = array();
                if ($invoice_type == "IPD_ADVANCE_DEPOSIT" || $invoice_type == "IPD") { ?>
                    <?php $ipd_data = @$this->AppAdmin->ipdTotalBalance($data['hospital_ipd_id']); ?>
                    <?php if (!empty($ipd_data['advance'])) { ?>
                        <p><u>Available Balance on <?php echo date('d-m-Y h:i:s'); ?> <b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $ipd_data['advance'] - $ipd_data['expense']; ?></b></u></p>
                <?php }
                } ?>

                <?php if ($invoice_type == 'OPD' || ($invoice_type == 'IPD' && empty($ipd_data['ipd_unique_id']))) {
                    if (!empty($data['total_due_amount'])) { ?>
                        <p><u>Your Total Due Balance is : <?php echo $data['total_due_amount'] . " Rs/-"; ?></b></u></p>
                <?php }
                } ?>



                <p><?php echo $data['receipt_footer_title']; ?></p>


            </div>














            <div class="created_by">
                <p><?php echo !empty($data['created_by']) ? "Thanks for visit " . $data['created_by'] : ''; ?></p>
            </div>


        </div>



        <div class="note">
            Note: Press 'Enter' to print or click here
        </div>

        <div class="note-prescription">
            Note: Press 'Space' to print prescription or click here
        </div>

        <div class="option">
            <div class="option_div"> <b>Font Size</b>&nbsp
                <select id="font_size">
                <option value="3px">3</option>
                    <option value="4px">4</option>
                    <option value="5px">5</option>
                    <option value="6px">6</option>
                    <option value="7px">7</option>
                    <option value="8px">8</option>
                    <option value="9px">9</option>
                    <option value="10px">10</option>
                    <option value="11px">11</option>
                    <option value="12px">12</option>
                    <option value="13px">13</option>
                    <option value="14px">14</option>
                    <option value="15px">15</option>
                    <option value="16px">16</option>
                    <option value="17px">17</option>
                </select>
            </div>
            <div class="option_div"> <b>Paper Size</b>&nbsp
                <select id="prescription_size">
                    <option value="A4">A4</option>
                    <option value="A4/4">A4/4</option>
                </select>
            </div>
            <div class="option_div"> <b>Template</b>&nbsp
                <select id="template">
                    <option value="default">Template 1</option>
                    <option value="new">Template 2</option>
                    <option value="eye">Eye Template</option>
                </select>
            </div>
        </div>



    </body>

    <style>
        .option {
            width: 47%;
            float: left;
            text-align: center;
            display: block;
            margin: 10px 30%;
            position: relative;
        }

        .option_div {
            width: 28%;
            float: left;
            font-size: 14px !important;
        }

        .option_div select {
            font-size: 14px !important;
        }
    </style>
    <script>
        $(function() {

            var dis = '<?php echo $discount; ?>';
            var tax = '<?php echo $tax; ?>';

            var tot = 0;
            if (dis == 0) {
                $('.hide_dis').remove();
                tot = tot + 1;
            }
            if (tax == 0) {
                $('.hide_tax').remove();
                tot = tot + 1;
            }





            $('.col_chk').attr('colspan', ($('.col_chk').attr('colspan') - tot));
            $('.col_chk1').attr('colspan', ($('.col_chk1').attr('colspan') - tot));
            var total_th = $(".product_detail_holder tbody tr:first-child th").length;

            $('.due_amount_label_td').attr('colspan', total_th);


            $(document).keypress(function(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('.container').printThis({ // show the iframe for debugging
                        importCSS: true, // import page CSS
                        importStyle: true, // import style tags
                        pageTitle: "", // add title to print page
                        removeInline: false, // remove all inline styles from print elements
                        printDelay: 333, // variable print delay; depending on complexity a higher value may be necessary
                        header: null, // prefix to html
                        footer: null, // postfix to html
                        base: false, // preserve the BASE tag, or accept a string for the URL
                        formValues: true, // preserve input/form values
                        canvas: false, // copy canvas elements (experimental)
                        doctypeString: ".", // enter a different doctype for older markup
                        removeScripts: false, // remove script tags from print content
                        copyTagClasses: false // copy classes from the html & body tag
                    });
                } else if (e.which == 32) {
                    window.open(
                        prescriptionURL,
                        '_blank' // <- This is what makes it open in a new window.
                    );
                } else if (e.which == 109) {
                    //window.open(miniReceiptURL);
                }
            });
            $(document).on('click', ".note-prescription", function() {
                window.open(
                    prescriptionURL,
                    '_blank' // <- This is what makes it open in a new window.
                );
            });
            $(document).on('click', ".note-mini-receipt", function() {
                window.open(miniReceiptURL);
            });
            $(document).on('click', ".note", function(e) {
                e.preventDefault();
                e.stopPropagation();
                $('.container').printThis({ // show the iframe for debugging
                    importCSS: true, // import page CSS
                    importStyle: true, // import style tags
                    pageTitle: "", // add title to print page
                    removeInline: false, // remove all inline styles from print elements
                    printDelay: 333, // variable print delay; depending on complexity a higher value may be necessary
                    header: null, // prefix to html
                    footer: null, // postfix to html
                    base: false, // preserve the BASE tag, or accept a string for the URL
                    formValues: true, // preserve input/form values
                    canvas: false, // copy canvas elements (experimental)
                    doctypeString: ".", // enter a different doctype for older markup
                    removeScripts: false, // remove script tags from print content
                    copyTagClasses: false // copy classes from the html & body tag
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
            var expires = "expires=" + d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
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


        $(document).ready(function() {
            checkCookie();
        });

        function checkCookie() {
            var user = getCookie("print_size");
            if (user != "") {

                $("#prescription_size").val(user);

                if (user == 'A4') {
                    $(".container").css('width', '650px');
                } else {
                    $(".container").css('width', '450px');
                }

            } else {
                $("#prescription_size").val('A4');
                if (user != "" && user != null) {
                    setCookie("print_size", 'A4', 365);
                }
                $(".container").css('width', '650px');

            }
        }

        $(document).on("change", "#prescription_size", function() {
            var value = $(this).val();
            setCookie("print_size", value, 365);
            checkCookie();
        });

        $(document).on("change", "#template", function() {
            var value = $(this).val();
            setCookie("template_invoice", value, 365);
            location.reload();
        });



        $(document).on("change", "#font_size", function() {
            var value = $(this).val();
            setCookie("invoice_font_size", value, 365);
            $("body").css('font-size', value);
        });




        $(document).ready(function() {

            $("#font_size").val("<?php echo $font_size; ?>");
            $("#template").val("<?php echo $template; ?>");
            var chkEmpty = false;
            $('.exp_date_td').each(function() {

                if ($(this).html() != " -") {
                    console.log($(this).html());
                    chkEmpty = true;
                }
            });
            if (chkEmpty == true) {
                console.log(chkEmpty);
                $(".exp_date_td").show();
                $(".exp_date_th").show();
            } else {
                $(".exp_date_td").hide();
                $(".exp_date_th").hide();

                $(".cols").each(function() {
                    var defaultCol = $(this).attr("colspan");
                    $(this).attr("colspan", defaultCol - 1);
                });
            }
        });
    </script>


    </html>
<?php } ?>