<?php
error_reporting(0);
ini_set('display_errors', 0);

    include_once '../webservice/ConnectionUtil.php';
    include_once '../webservice/Custom.php';

$ai = (isset($_GET['ai']))?base64_decode($_GET['ai']):'';
if(!empty($ai)){

    $connection = ConnectionUtil::getConnection();
    $sql = "select acss.thinapp_id, acss.appointment_staff_id, bco.status, acss.appointment_booked_from, acss.appointment_datetime, bcfd.amount, t.name as app_name, acss.queue_number, bcfd.booking_convenience_fee, acss.consulting_type, bcfd.tx_status, bcfd.tx_time, bcfd.reference_id, bcfd.getway_refund_id from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id left join booking_convenience_orders as bco on bco.appointment_customer_staff_service_id = acss.id  left join booking_convenience_fee_details as bcfd on bco.id = bcfd.booking_convenience_order_id  where acss.id =$ai limit 1";
    $data = $connection->query($sql);
    if ($data->num_rows) {

        $prm = mysqli_fetch_assoc($data);
        $amount = $prm['amount'];
        $status = $prm['status'];
       
        $app_name = $prm['app_name'];
        $appointment_datetime = $prm['appointment_datetime'];
      
        $queue_number = $prm['queue_number'];
        $consulting_type = $prm['consulting_type'];
        $tx_time = $prm['tx_time'];
        $reference_id = $prm['reference_id'];
        $tx_status = $prm['tx_status'];
        $doctor_id = $prm['appointment_staff_id'];
        $eny_doctor_id = base64_encode($doctor_id);
       
        $web_app_url = SITE_PATH."doctor/index/$doctor_id/?t=$eny_doctor_id&wa=n&back=no&sal=y";
        
        if($status=='ACTIVE'){
            $fa_class = "fa fa-clock-o";
           
            $payment_title = "Payment Processing";
            $colorCode = "#14beff";
        }else if($status=='PAID'){
            $fa_class = "fa fa-check";
            $colorCode = "#008001";
           
            $payment_title = "Payment Successfully";
        }else if($status=='REFUNDED'){
            $fa_class = "fa fa-check";
            $colorCode = "#dff200";
            $reference_id = $prm['getway_refund_id'];
            $payment_title = "Payment Refunded";
        }else{
            $fa_class = "fa fa-exclamation-triangle";
           
            $payment_title = "Payment Failure";
            $colorCode = "#e30505";
        }


    }

?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" media="all" />
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.css" media="all" />
    <style>



        .status_box{
            width: 150px;
            height: 150px;
            margin: 0 auto;
            display: block;
            border: 1px solid;
            font-size: 8rem;
            border-radius: 63%;
            padding: 4%;
            color: #fff;
        }

       

        
       

        header h3{
            
            text-align: center;
            background: #013ec4;
            color: #fff;
            margin: 0;
            padding: 10px 0px;

        }
        .payment-img img {
            width: 200px;
            max-width: 50%;
        }
        .payment-img {
          
            padding: 40px 10px;
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

<header>
    <h3 style="background:<?php echo $colorCode; ?>;"><?php echo $app_name; ?></h3>
</header>
<div class="container-fluid">

    <div class="row">

        <div class="col-md-12 text-center payment-img">
            <div class="status_box" style="background:<?php echo $colorCode; ?>;border-color:<?php echo $colorCode; ?>"><i class="<?php echo $fa_class; ?>"></i></div>
            <h2 style="color:<?php echo $colorCode; ?>;" ><?php echo $payment_title; ?></h2>
            <small style="color:<?php echo $colorCode; ?>;"><?php echo $tx_time; ?></small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <table class="table">
                <tbody>
                <tr><th>Tranjaction Id </th><td> <?php echo $reference_id; ?></td></tr>
                <tr><th>Token Number</th><td> <?php echo $queue_number; ?></td></tr>
                <tr><th>Paid Amount</th><td> &nbsp;INR <?php echo $amount; ?></td></tr>
                <tr><th>CheckIn Time</th><td> &nbsp;<?php echo date("d/m/Y H:i",strtotime($appointment_datetime)); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <?php if($tx_status=='PAYMENT_SUCCESS'){ ?>
        <div class="row">
        <div class="col-md-12 text-center bottom-text">
            <p>Thank You for your payment. An automated payment receipt will be sent to your registered mobile number.</p>
        </div>
    </div>
    <?php } ?>


    <div class="row">
        <div class="col-md-12 text-center bottom-text">
            <br>
            <a class="btn btn-default btn-xm" href="<?php echo $web_app_url; ?>" >View Token</a>
        </div>
    </div>


   
</div>

</body>
</html>


<?php }else{ ?>
    
    <!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" media="all" />
    <link rel="stylesheet" type="text/css" href="../css/font-awesome.css" media="all" />
    <style>



        .status_box{
            width: 150px;
            height: 150px;
            margin: 0 auto;
            display: block;
            border: 1px solid;
            font-size: 8rem;
            border-radius: 63%;
            padding: 4%;
            color: #fff;
        }

       

        
       

        header h3{
            
            text-align: center;
            background: #013ec4;
            color: #fff;
            margin: 0;
            padding: 10px 0px;

        }
        .payment-img img {
            width: 200px;
            max-width: 50%;
        }
        .payment-img {
          
            padding: 40px 10px;
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
        <div class="col-md-12 text-center bottom-text">
            <h2 style='color:red; text-align:center;width:100%;display:block;'>
                <i class="fa fa-exclamation-triangle" style="
    display: block;
    margin: 15px auto;
    font-size: 10rem;
"></i>
                     Invalid Request
        </h2>
        </div>
    </div>


   
</div>

</body>
</html>
 


<?php } ?>
