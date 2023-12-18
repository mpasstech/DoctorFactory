<?php
error_reporting(0);
ini_set('display_errors', 0);
$prm = json_decode(base64_decode($_GET['prm']),true);

$amount = $prm['amount'];
$queue_number = $prm['queue_number'];
$date = $prm['date'];
$time = $prm['time'];
$referenceId = $prm['referenceId'];
$refundId = $prm['refundId'];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Token Booking Fail</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" media="all" />
    <style>

        .row{
            padding: 0.7rem;
        }
        .payment-img img {
            width: 200px;
            max-width: 50%;
        }
        .payment-img {
            background-color: lightgray;
            padding: 10px 10px;
            color: #e9170d;
            font-weight: bold;
        }
        .bottom-text {
            color: #e9170d;
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
            <img src="../images/failed.png">
            <h2>Token Booking Fail</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <table class="table">
                <tbody>
                <tr><th>Date</th><td> <?php echo $date; ?></td></tr>
                <tr><th>Token</th><td> <?php echo $queue_number; ?></td></tr>
                <tr><th>Amount</th><td> &nbsp;INR <?php echo $amount; ?></td></tr>
                <tr><th>Reference Id</th><td> <?php echo $referenceId; ?></td></tr>

                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center bottom-text" style="font-size: 1.5rem;">
            <p>Your token booking time has been expired. If your amount has been debited from your acount please do not worry your payment will be refunded within 7 working days.</p>
        </div>
    </div>
</div>

</body>
</html>