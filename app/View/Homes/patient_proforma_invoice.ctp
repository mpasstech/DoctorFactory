<?php

$font_size = "10px";
if(!empty($_COOKIE["invoice_font_size"])) {
    $font_size = $_COOKIE["invoice_font_size"];
}

$template = "default";
if(!empty($_COOKIE["template_invoice"])) {
    $template = $_COOKIE["template_invoice"];
}


if(!isset($_COOKIE["template_invoice"]) || ($_COOKIE["template_invoice"] == "default")) {
    setcookie("template_invoice", "default", time() + (86400 * 30), "/");
    }


$data = $invoiceData[0];

?>

<?php $login = $this->Session->read('Auth.User'); ?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">


    <meta http-equiv="cache-control" content="no-store"/>
    <meta http-equiv="expires" content="0"/>
    <meta http-equiv="pragma" content="no-cache"/>


    <script>
        var baseurl = '<?php echo Router::url('/',true); ?>';
        <?php
        $url = $this->request->url;
        $urlPrescrption = "";
        $urlMiniReceipt = str_replace('print_invoice','print_mini_invoice',$url);
        ?>
        var prescriptionURL = '<?php echo $urlPrescrption ?>';
        var miniReceiptURL = '<?php echo Router::url('/',true); ?><?php echo $urlMiniReceipt; ?>';
    </script>
    <script type="text/javascript" src='https://maps.google.com/maps/api/js?key=AIzaSyA9tEMyxDqxU1uroSkCTvfa8QeR4ZVE-Ug&sensor=false&libraries=places'></script>
    <?php  echo $this->Html->script(array('jquery.js','bootstrap4.min.js','jquery-confirm.min.js','sweetalert2.min.js','locationpicker.jquery.min.js')); ?>
    <?php  echo $this->Html->css(array('bootstrap4.min.css','font-awesome.min.css','jquery-confirm.min.css','sweetalert2.min.css')); ?>



    <style>


        .container {

            padding: 0;
            margin: 0;
            margin-left: auto;
            margin-right: auto;
        }
        .logo_img img {
            height: 50px;
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
            width: 30%;
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

        .logo_img img {
            margin-left: 21%;
        }
        .recept_detail_holder h2 {

            text-align: center;

        }

        .sub_table tr td{
            border: none !important;
            font-size: 0.7rem;
        }

        table{
            font-size: 0.8rem;
        }


        .sub_data_row{
            margin-bottom: 10px;
        }
        .proforma_invoice_h{
            margin: 0;
            padding: 4px 0px;
            text-align: center;
            background: #4072f8;
            color: #fff;

        }
        .doc_detail_box label{
            width: 100%;
            display: block;
            font-size: 0.8rem;
            padding-left: 5px;
        }

        .row{
            padding: 0;
            margin: 0;
        }

        div[class^="col-"]{
            padding:5px 6px;
        }
        .detail_sec{
            margin: 10px 0px;
        }

        .action_btn_box{
            margin: 10px 0px;
        }


        .message_container_box .card{

            width: 100% !important;
            font-size: 0.7rem;

        }

        .jconfirm-content{
            font-size:1rem;
        }
        .card_title_success span{

            float: right;
            color: #fff;
            font-size: 1.8rem;
            border-radius: 32px;
            border: 1px solid green;
            padding: 6px;
        }

        .card_title_success i{
            color: green;
        }

        .failure_box span{
            float: right;
            color: #fff;
            font-size: 1.5rem;
            border-radius: 45px;
            border: 1px solid red;
            padding: 6px 10px;
            width: 40px;
            height: 40px
        }

        .failure_box i{
            color: red;
        }


    </style>



    <?php
          $title = "Proforma Invoice";
        if($data['tx_status']=='SUCCESS'){
            $title = "Order Invoice";
        }


        ?>



    <title><?php echo $title; ?></title>
</head>
<body style="font-size: <?php echo $font_size; ?>">

<div class="container">

    <div class="row">
        <div class="col-12" style="padding: 0px;">
            <h3 class="proforma_invoice_h"><?php echo $title; ?></h3>
        </div>
    </div>

    <div class="row detail_sec">

        <div class="col-12" >

            <div class="card" >


                <div class="card-body">

                    <table>
                        <tr><td colspan="2"> <h5>Pharmacist Detail</h5></td></tr>
                        <tr>
                            <td><?php if($data['logo'] != ''){ ?>
                                    <img style="width: 20%; align-self: center;" class="card-img-top" src="<?php echo $data['logo']; ?>" >
                                <?php } ?></td>
                            <td>
                                <h6 style="text-align: center;"><b><?php echo ucwords($data['chemist_name']); ?></b></h6>
                                <?php if(!empty($data['chemist_mobile'])){ ?>
                                    <h6 style="text-align: center;"><?php echo ucwords($data['chemist_mobile']); ?></h6>
                                <?php } ?>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">


                                <?php $address =  $data['pharmacist_address'];
                                if(strtoupper($address) == 'ADDRESS GOES HERE'){
                                    $address = '';
                                }
                                ?>

                                <?php if(!empty($address)){ ?>
                                    <label style="display: block;">Address :- </label>
                                    <p><?php echo ucwords($address); ?></p>
                                <?php }; ?>
                            </td>
                        </tr>
                    </table>




                </div>
            </div>



        </div>



    </div>
    <div class="row">
        <div class="col-12">
            <table class="product_detail_holder">
                <?php if($invoice_type !="DUE"){ ?>
                    <tbody>

                    <tr>
                        <th align="left"><b>#</b></th>
                        <th  align="left" style="width: 60%"><b>Medicine Name</b></th>
                        <th align="right"><b>Rate</b></th>
                        <th align="center"><b>X</b></th>
                        <th align="left" style="width: 5%;"><b>Qty</b></th>
                    </tr>
                    <?php $counter=0; $discount =$tax = $total_amount =0;  foreach($invoiceData AS $key => $orderList ){
                        if($orderList['show_into_receipt']=="YES"){
                            $discount +=$orderList['discount_amount'];
                            $tax +=  $orderList['tax_value'];
                            $total_amount += $orderList['total_amount'];
                            ?>
                            <tr>
                                <td class=""><?php echo ++$counter; ?>



                                </td>
                                <td class="" ><?php echo ucwords($orderList['service_name']); ?></td>
                                <td class="" align="right"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['product_price']; ?></td>
                                <td class="" align="center"> <?php echo 'X'; ?></td>
                                <td class="" align="left"> <?php echo $orderList['quantity']; ?></td>
                            </tr>
                            <tr class="sub_data_row">
                                <td></td>
                                <td colspan="4">
                                    <table class="sub_table">
                                        <tr>
                                            <td class="hide_dis" align="left"><b>Discount :-</b>   <?php echo ($orderList['discount_amount'] > 0)?'<i class="fa fa-inr" aria-hidden="true"></i> '.$orderList['discount_amount']:''; ?></td>
                                            <td class="hide_tax" align="left"><b>Tax :-</b>
                                                <?php if($orderList['tax_value'] > 0)
                                                { ?>
                                                    <?php echo $orderList['tax_type']; ?> <?php echo $orderList['tax_value']; ?> Rs/-
                                                <?php } ?>

                                            </td>
                                            <td class="" align="left"><b>Amount :-</b> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $orderList['total_amount']; ?></td>

                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        <?php }} ?>
                    </tbody>
                    <tfoot>
                    <tr>
                        <th colspan="2" class="col_chk" align="right"><b>Total Amount&nbsp;</b></th>
                        <th colspan="3" style="text-align: center; "><span class="double_underline"><u><b><i class="fa fa-inr" aria-hidden="true"></i> <?php
                                        if(isset($data['total_paid'])  && ($invoice_type == 'OPD' || $invoice_type == 'IPD' )){
                                            echo $data['total_paid'];
                                        }else{
                                            echo $total_amount;
                                        }
                                        ?></b></u></span></th>
                    </tr>
                    </tfoot>

                <?php } ?>
            </table>
        </div>
    </div>

    <?php if($data['patient_response'] =='ACCEPT' && ($data['tx_status']=='SUCCESS' || $data['tx_status']=='FAILURE') ){ ?>
        <div class="row message_container_box">
            <?php if($data['tx_status']=='SUCCESS'){ ?>
            <div class="col-12 success_box" >
                <div class="card" >
                    <div class="card-body">

                        <h5 style="color: green;" class="card-title card_title_success">Order Status
                            <span style="float: right;">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </span>
                        </h5>

                        <h6 class="card-subtitle mb-2 text-muted">Payment received successfully</h6>
                        <p class="card-text">Your order number <b style="color: #9a0808; font-size: 0.8rem;"><?php echo $data['invoice_order_id']; ?></b> is under process you will notify on your registered mobile number.</p>


                        <label>Delivery Address :- </label>
                        <p><?php echo $data['delivery_address']; ?></p>
                        <p><b><label>Delivery Charges :- </label> <i class="fa fa-inr" aria-hidden="true"></i> <?php echo $data['delivery_charges']; ?> </b></p>


                        <a href="javascript:void(0);" class="card-link"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo $data['paid_amount']+$data['delivery_charges']; ?> </a>
                        <a href="javascript:void(0);" class="card-link"><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo date('d-m-Y h:i A',strtotime($data['payment_datetime'])); ?></a>
                    </div>
                </div>
            </div>
            <?php } ?>

            <?php if($data['tx_status']=='FAILURE' ){ ?>
            <div class="col-12 failure_box" >
                <div class="card" >
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted">Payment Failed <span style="float: right;"><i class="fa fa-times" aria-hidden="true"></i></span></h6>
                        <p class="card-text">Please try again to make payment with pay button</p>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    <?php } else if($data['patient_response'] =='DECLINE'){ ?>
    <div class="row message_container_box">

        <div class="col-12 failure_box" >
            <div class="card" >
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Order Canceled <span style="float: right;"><i class="fa fa-times" aria-hidden="true"></i></span></h6>
                    <p class="card-text">This request has been canceled by you.</p>

                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php if($view_by_camist===false && $data['patient_response'] !='DECLINE' &&  $data['tx_status']!='SUCCESS'){ ?>
         <div class="row action_btn_box">
        <div class="col-12" style="text-align: center;">
            <h6 style="font-weight: 600; margin: 10px 0px;">Delivery charges will be extra. Please provide location for delivery charges. </h6>
            <button type="button" class="btn btn-danger" id="cancel_btn">Cancel</button>
            <button type="button" class="btn btn-success" id="pay_btn">Select Loction</button>
        </div>
    </div>
    <?php } ?>
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

    .pac-container {
        z-index: 99999;
    }
    #err_msg{
        font-size: 0.9rem;
        height: 20px;
        padding: 5px 0px;
        width: 100%;
        display: block;
        text-align: center;
        color: red;
    }
    .swal2-title{
        padding: 5px !important;
    }

    .swal2-modal .swal2-buttonswrapper{
        margin-top: 10px !important;
    }

    .swal2-modal .swal2-title{
        margin: 0 !important;
    }
    .swal2-modal{
        padding: 0 !important;
    }
    .swal2-modal .swal2-styled {
        font-size: 12px;
        margin: 0px 5px 10;
        padding: 6px 25px;
    }

</style>
<script>
    $(function () {


       var result = "<?php echo ($order_node); ?>";
       var txStatus = "<?php echo (($txStatus)); ?>";
        if(result=='PROFORMA_INVOICE'){
            if(txStatus=='SUCCESS'){
                swal({
                    type:'success',
                    title: 'Payment Successfull',
                    text: "You will receive the acknowlagement message on SMS.",
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    customClass:"success-box",
                    showConfirmButton: true
                }).then(function (result) {
                    var url = "<?php echo Router::url('/homes/patient_proforma_invoice/'.$proforma_invoice_id,true); ?>";
                    window.location.replace(url);
                });
            }else if(txStatus=='FAILURE'){
                swal({
                    type:'error',
                    title: 'Payment Failure',
                    text: "Sorry, Your payment could not done. Please try again with pay button",
                    showCancelButton: false,
                    confirmButtonText: 'Ok',
                    customClass:"success-box",
                    showConfirmButton: true
                }).then(function (result) {
                    var url = "<?php echo Router::url('/homes/patient_proforma_invoice/'.$proforma_invoice_id,true); ?>";
                    window.location.replace(url);
                });
            }
        }
        $(document).on('click','#pay_btn',function(e){

            var thin_app_id =  "<?php echo $data['thinapp_id']; ?>";
            var mobile =  "<?php echo $data['mobile']; ?>";
            var patient_name =  "<?php echo $data['patient_name']; ?>";
            var order_amount =  "<?php echo base64_encode($data['total_paid']); ?>";
            var inv_id =  "<?php echo base64_encode($data['proforma_id']); ?>";

            var note= "PROFORMA_INVOICE";

            var $btn = $(this);
            var id = $(this).attr('data-id');
            var row = $(this).closest('tr');
            if(true){

                html_string = ' <div class="col-12" style="padding:0px 6px;">' +
                    '<label  control-label">Type your address</label>' +

                        '<input type="text" class="form-control" id="us3-address" /><label id="err_msg"></label>' +
                        '<input style="display:none;" type="text" class="form-control" id="us3-radius" />' +
                        '<input style="display:none;" type="text" class="form-control" style="width: 110px" id="us3-lat" />' +
                        '<input style="display:none;" type="text" class="form-control" style="width: 110px" id="us3-lng" />' +
                        '<div id="us3" style="width: 100%; height: 250px;"></div><br>' +
                        '<table class="table table-border"><tr><th>Detail</th><th>Amount</th></tr>' +
                        '<tr><td>Medicine Amount</td><td id="order_amount" data-amt='+atob(order_amount)+' ><i class="fa fa-inr"></i> '+atob(order_amount)+'</td></tr>'+
                        '<tr><td>Delivery Charges</td><td class="delivery_charges"></td></tr>'+
                        '<tr><th>Payable Amount</th><th class="paid_amount"></th></tr></table>'+

                    '</div>';

                    var swalBox = swal({
                    title: 'Pay Order',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Pay',
                    showConfirmButton: true,
                    showLoaderOnConfirm: false,
                    allowOutsideClick: false,
                    html: html_string,
                    preConfirm: function () {
                        var address =$("#us3-address").val();
                        var lat = $("#us3-lat").val();
                        var lng = $("#us3-lng").val();

                        var dc =  $(".delivery_charges").attr("data-amt");
                        var distance =  $(".paid_amount").attr("data-dis");
                        var paid_amount =  btoa($(".paid_amount").attr("data-amt"));



                        if(lat=='' || lng =='' || paid_amount==''){
                            $.alert('Please type your loction');
                            $(".swal2-cancel, .swal2-confirm").attr('disabled',false);
                        }else{

                            if(confirm("Ary you sure you want to order medicine on this address?")){
                                var send_data = {td:distance,dc:dc,pat_lat:lat,pat_lng:lng,pa:address,name:patient_name,mobile:mobile,thinapp_id:thin_app_id,order_amount:paid_amount,note:note,inv_id:inv_id};
                                send_data = $.param(send_data);
                                window.location.href = baseurl + "cashfree/payment.php?"+send_data;
                            }else{
                                $(".swal2-cancel, .swal2-confirm").attr('disabled',false);
                            }
                            return false;
                        }
                    }

                }).then(function (result) {
                    var okBtn = $('.swal2-confirm');
                });

                setTimeout(function () {
                    addMap();
                    $(document).off('input','#us3-address');
                    $(document).on('input','#us3-address',function(){
                        $("#us3-lat, #us3-lng").val('');
                        $("#err_msg").html("Please chose loction from autocomplte");
                        $(".delivery_charges, .paid_amount").html('');
                        $(".delivery_charges, .paid_amount").attr("data-amt",'');
                        $(".paid_amount").attr("data-dis",'');
                    });
                },100);
            }
        });

        function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
            var R = 6371; // Radius of the earth in km
            var dLat = deg2rad(lat2-lat1);  // deg2rad below
            var dLon = deg2rad(lon2-lon1);
            var a =
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
                Math.sin(dLon/2) * Math.sin(dLon/2)
            ;
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            var d = R * c; // Distance in km
            return (d);
        }

        function deg2rad(deg) {
            return deg * (Math.PI/180)
        }


        function addMap(){

            var lastLat = "26.9124";
            var lastLng = "75.7873";

            if(navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    lastLat = position.coords.latitude;
                    lastLng = position.coords.longitude;
                    appendMap(lastLat,lastLng);
                }, function(error) {
                    appendMap(lastLat,lastLng);
                });
            }else{
                appendMap(lastLat,lastLng);
            }
        }
        var geocoder = new google.maps.Geocoder;
        function appendMap(lastLat,lastLng){
            $('#us3').locationpicker({
                location: {
                    latitude: lastLat,
                    longitude: lastLng
                },
                radius: 30,
                address:true,
                inputBinding: {
                    latitudeInput: $('#us3-lat'),
                    longitudeInput: $('#us3-lng'),
                    radiusInput: $('#us3-radius'),
                    locationNameInput: $('#us3-address')

                },
                enableAutocomplete: true,
                onchanged: function (currentLocation, radius, isMarkerDropped) {
                    lastLat = currentLocation.latitude;
                    lastLng = currentLocation.longitude;
                    var latlng = {lat: lastLat, lng: lastLng};

                    geocoder.geocode({'location': latlng}, function (results, status) {
                        console.log(status);
                        if (status === 'OK') {
                            if (results[0].formatted_address) {
                                $('#us3-address').val(results[0].formatted_address);
                            }
                        }
                    });
                    showTotalAmount(currentLocation.latitude, currentLocation.longitude);
                }, oninitialized: function(component) {
                    showTotalAmount(lastLat,lastLng);
                }

            });
        }


        function showTotalAmount(latitude,longitude ){
            var delivery_charges =  "<?php echo $delivery_charges; ?>";
            var chemist_lat =  "<?php echo ($data['chemist_lat']); ?>";
            var chemist_lng =  "<?php echo ($data['chemist_lng']); ?>";

            $("#err_msg").html('');
            var distance = getDistanceFromLatLonInKm(chemist_lat, chemist_lng, latitude, longitude);
            $(".paid_amount").attr('data-dis',distance);
            distance = Math.round(distance);

            var total_dilivery_charges = distance*delivery_charges;
            $(".delivery_charges").html("<i class='fa fa-inr'></i> "+total_dilivery_charges);
            $(".delivery_charges").attr("data-amt",total_dilivery_charges);

            var order_amount = $("#order_amount").attr('data-amt');
            var total_paid = parseFloat(total_dilivery_charges)+parseFloat(order_amount);
            $(".paid_amount").html("<i class='fa fa-inr'></i> "+(total_paid));
            $(".paid_amount").attr('data-amt',total_paid);

        }



        $(document).on('click','#cancel_btn',function(e){
            var inv_id =  "<?php echo base64_encode($data['proforma_id']); ?>";
            var send_data = {pi:inv_id};
            var $btn = $(this);
            var id = $(this).attr('data-id');
            var row = $(this).closest('tr');
            var dialog = $.confirm({
                title: 'Cancel Order',
                content: 'Are you sure you do not want to order medicine from doctor?',
                keys: ['enter', 'shift'],
                buttons:{
                    Yes: {
                        keys: ['enter'],
                        action:function(e){
                            var $btn2 = $(this);
                            $.ajax({
                                type:'POST',
                                url: baseurl + "homes/decline_proforma",
                                data:(send_data),
                                beforeSend:function(){
                                    $btn.button('loading').html('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>  Wait..');
                                    $btn2.button('loading');
                                },
                                success:function(data){
                                    dialog.close();
                                    $btn.button('reset');
                                    $btn2.button('reset');
                                    var response = JSON.parse(data);
                                    if(response.status==1){
                                        $(".action_btn_box").hide();
                                        var url = "<?php echo Router::url('/homes/patient_proforma_invoice/'.$proforma_invoice_id,true); ?>";
                                        window.location.replace(url);
                                    }else{
                                        $.alert(response.message);
                                    }
                                },
                                error: function(data){
                                    $btn.button('reset');
                                    $btn2.button('reset');
                                    $.alert("Sorry something went wrong on server.");
                                }
                            });
                            return false;
                        }
                    },
                    Cancel: {
                        action:function () {
                            dialog.close();
                        }
                    }
                }


            });
        });


    })
</script>
</html>