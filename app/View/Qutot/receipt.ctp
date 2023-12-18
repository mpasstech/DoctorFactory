<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            padding: 4px 3px 4px 3px;
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
        .note-mini-receipt{
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
        .template{
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
            table{
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
            .user_biller_detail_gap{
                width: 20%;
            }
            .user_detail_holder{
                width: 40%;
            }
            body{

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
            .note-mini-receipt{
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


    <title>Receipt No.: #<?php echo $bookingData['unique_id']; ?></title>
    <?php $baseUrl = Router::url('/',true);?>
</head>
<body style="font-size: 10px">

<div class="container">
    <br>
    <table>
        <tr>
            <td align="left" valign="top" class="company_detail_holder">
                <h2>MEngage Technologies PVT. LTD.</h2>
                <div class="logo_img">
                    <img src="<?php echo $baseUrl; ?>/images/logo.png" >
                </div>
            </td>
            <td align="right" valign="top" class="recept_detail_holder">
                <h2>
                    Receipt
                </h2>
                <table>
                    <tr><td align="left" class="title_holder"><b>Token:</b></td><td align="right"><b><?php echo $this->AppAdmin->create_queue_number($appointmentData['AppointmentCustomerStaffService']); ?></b></td></tr>

                    <?php if($appointmentData['AppointmentCustomerStaffService']['emergency_appointment']=='NO'){ ?>
                        <tr><td align="left" class="title_holder"><b>Appointment Time:</b></td><td align="right"><b><?php echo $appointmentData['AppointmentCustomerStaffService']['slot_time']; ?></b></td></tr>
                    <?php } ?>



                    <tr>
                        <td align="left" class="title_holder"><b>Receipt No.:</b></td><td align="right">
                            #<?php echo $bookingData['unique_id']; ?>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" class="title_holder"><b>Doctor:</b></td><td align="right">
                            <?php echo $appointmentData['AppointmentStaff']['name']; ?>
                        </td>
                    </tr>



                    <tr><td align="left" class="title_holder"><b>UHID:</b></td><td align="right"><?php echo $appointmentData['AppointmentCustomer']['uhid'].$appointmentData['Children']['uhid']; ?></td></tr>



                    <tr><td align="left" class="title_holder"><b>Payment Mode:</b></td><td align="right"><?php echo $bookingData['payment_mode']; ?></td></tr>
                    <tr>

                        <td align="left" class="title_holder">
                            <b>
                                Receipt Date/Time:
                            </b>
                        </td>
                        <td align="right">
                            <?php echo date('d M Y h:i A',strtotime($bookingData['tx_time'])); ?>
                        </td>

                    </tr>
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

                                <tr><td align="left" valign="top" class="title_holder"><b>Name:</b></td><td align="right">MEngage Technologies PVT. LTD.</td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Mobile:</b></td><td align="right">+91-8955004049</td></tr>
                            </table>
                        </td>
                        <td class="user_biller_detail_gap"></td>
                        <td class="user_detail_holder" valign="top">
                            <table>
                                <tr><td colspan="2" align="left" class="patient_title"><b>Patient Detail</b></td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Name:</b></td><td align="right"><?php echo $appointmentData['AppointmentCustomer']['first_name'].$appointmentData['Children']['child_name']; ?></td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Gender:</b></td><td align="right"><?php echo $appointmentData['AppointmentCustomer']['gender'].$appointmentData['Children']['gender']; ?></td></tr>
                                <tr><td align="left" valign="top" class="title_holder"><b>Mobile:</b></td><td align="right"><?php echo $appointmentData['AppointmentCustomer']['mobile'].$appointmentData['Children']['mobile']; ?></td></tr>
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


            <th colspan="3" align="left"><b>Service</b></th>
            <th align="right"><b>Rate</b></th>


            <th align="center"><b>X</b></th>
            <th align="left"><b>Qty</b></th>
            <th align="right"><b>Net Amount</b></th>
        </tr>
        <tr>
            <td class="">1</td>
            <td class="" colspan="3">Token Booking Convenience Fees</td>
            <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php $total =0; echo $total = $bookingData['booking_convenience_fee']; ?></td>
            <td class="" align="center"> X</td>
            <td class="" align="left"> 1</td>
            <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $bookingData['booking_convenience_fee']; ?></td>
        </tr>

        <?php if(!empty($bookingData['convence_for'] == 'ONLINE' || $appointmentData['AppointmentCustomerStaffService']['emergency_appointment']=='YES'){ ?>
            <tr>

                <?php
                        $label = "Online Consulting Fees";
                        if($appointmentData['AppointmentCustomerStaffService']['emergency_appointment']=='YES'){
                            $label = "Emergency Consulting Fees";
                        }
                ?>
                <td class="">2</td>
                <td class="" colspan="3"><?php  echo $label; ?></td>
                <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php $total += $bookingData['doctor_online_consulting_fee']; echo $bookingData['doctor_online_consulting_fee']; ?></td>
                <td class="" align="center"> X</td>
                <td class="" align="left"> 1</td>
                <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $bookingData['doctor_online_consulting_fee']; ?></td>
            </tr>
        <?php } ?>
        <tr class="">
            <td class="col_chk1" colspan="10" ><br /></td>
        </tr>

        <tr>
            <th colspan="7" class="col_chk" align="right"><b>Total Paid Amount&nbsp;</b></th>

            <th colspan="1" align="right"><span class="double_underline"><u><b><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $total; ?></b></u></span></th>
        </tr>

        </tbody>

    </table>




    <div class="footer_text">




        <p>Mail Us at engage@mengage.in</p>


    </div>
    <div class="created_by">
        <p></p>
    </div>


</div>

<div class="note">
    Note: Press 'Enter' to print or click here
</div>



</body>


<style>
    .option{
        width: 47%;
        float: left;
        text-align: center;
        display: block;
        margin: 10px 30%;
        position: relative;
    }
    .option_div{
        width: 28%;
        float: left;
        font-size:14px !important;
    }

    .option_div select{
        font-size:14px !important;
    }
</style>
<script>
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


            //$("#print body").text(" td,tr,body{font-size '"+$('body').css('font-size')+"'");
            console.log($("#print").text());
            // console.log($(printStyle).find('body'));


            $('.container').printThis({

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
                copyTagClasses: false

            });
        });


</script>


</html>