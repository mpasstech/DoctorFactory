<?php

    error_reporting(0);
    ini_set('display_errors', 0);
    include_once '../webservice/Custom.php';

     $prm = json_decode(base64_decode($_GET['prm']),true);
     $amount = $prm['bookingConvenienceFee'];
     $appointment_datetime = $prm['datetime'];
     $queue = $prm['queue'];
     $tx_time = $prm['tx_time'];
     $is_online_consulting = $prm['is_online_consulting'];
     $convenience_for = $prm['convenience_for'];
     $emergency_appointment = $prm['emergency_appointment'];
     $smart_clinic = $prm['smart_clinic'];
     $appointment_booked_from = $prm['appointment_booked_from'];
     $appointment_id = $prm['appointment_id'];
     $patient_mobile = $prm['mobile'];
     $thin_app_id = $prm['thin_app_id'];
     $doctor_id = $prm['doctor_id'];




?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" media="all" />
    <style>
        .payment-img img {
            width: 200px;
            max-width: 50%;
        }
        .payment-img {
            background-color: lightgray;
            padding: 10px 10px;
            color: #008001;
            font-weight: bold;
        }
        .bottom-text {
            color: #008001;
            font-size: 15px;
        }
        .table {

            max-width: 500px;
            width: 100%;
            display: inline-table;

        }
        td{
            text-align: left;
        }

    </style>
    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>

</head>
<body>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 text-center payment-img">
            <img src="../images/successful.png">
            <h2>Payment Successful</h2>
            <small><?php echo $tx_time; ?></small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <table class="table">
                <tbody>
                    <tr><th>Amount.</th><td> &nbsp;INR <?php echo $amount; ?></td></tr>
                    <tr><th>CheckIn time</th><td> &nbsp;<?php echo date("d/m/Y H:i",strtotime($appointment_datetime)); ?></td></tr>
                    <tr><th>Token number</th><td> &nbsp;<?php echo $queue; ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center bottom-text">
            <p>Thank You for your payment. An automated payment receipt will be sent to your registered mobile number.</p>
            <?php if($smart_clinic=='YES'){ ?>
                <label style="width: 25%;text-align: center;font-weight: 600; background: green;color: #fff;padding: 2px 15px; border-radius: 20px;">NOTE</label>

                <?php if($emergency_appointment=='YES'){ ?>
                     <p style="font-weight: 600; text-transform: uppercase;"></p>
                <?php } else if($convenience_for=='ONLINE'){ ?>
                    <p style="font-weight: 600; text-transform: uppercase;">Please wait for your turn. Doctor will start video call soon.</p>
               <?php }else{ ?>
                    <p style="font-weight: 600; text-transform: uppercase;">OPD Fees to be paid at reception.</p>
                <?php } ?>

            <?php } ?>
            <?php if($appointment_booked_from=='CHAT_BOAT'){
                    $url = Custom::createChatBoatLink($thin_app_id,$patient_mobile,$doctor_id);
                ?>
                <p><a href="<?php echo $url; ?>" class="btn btn-info">Close</a> </p>
            <?php } ?>
        </div>
    </div>
</div>

</body>
</html>