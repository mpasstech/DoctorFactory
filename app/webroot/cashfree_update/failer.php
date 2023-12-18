<?php
error_reporting(0);
ini_set('display_errors', 0);
$data = json_decode(base64_decode($_REQUEST['PM']),true);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Failed</title>
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
            <h2>Payment Failed</h2>
            <small><?php echo $data['txTime']; ?></small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <table class="table">
                <tbody>
                <tr><th>Amount.</th><td> &nbsp;INR <?php echo $data['orderAmount']; ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center bottom-text">
            <p>Sorry, Your payment was not successful.</p>
        </div>
    </div>
</div>

</body>
</html>