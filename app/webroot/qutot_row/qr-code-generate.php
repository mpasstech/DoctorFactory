<?php
ignore_user_abort(true);
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../PHPMailer/PHPMailerAutoload.php";
$thin_app_id = base64_decode($_REQUEST['t']);
$app_data = Custom::get_thinapp_data($thin_app_id);
$url = Custom::short_url(SITE_PATH."qutot/qutot_user_booking/".base64_encode($thin_app_id),$thin_app_id);
session_start();
$email_url = SITE_PATH."qutot_row/qr-code-generate.php?t=".base64_encode($thin_app_id)."&e=";


$redirect =  SITE_PATH."qutot_row/qr-code-generate.php?t=".base64_encode($thin_app_id);
$email_barcode =  SITE_PATH."qutot_row/qr-code-generate.php?em=n&t=".base64_encode($thin_app_id);
$barcode =  "https://chart.googleapis.com/chart?cht=qr&chl=$url&chs=160x160&chld=L|0";

?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="author" content="mengage">
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/qutot.css" />
    <style type="text/css">
        .qr-code {
            width: 300px;
            height: 300px;
            margin: 10px;
        }

        .header_bar{
            background: blue;
            color: #fff;
            text-align: center;
            width: 100%;
            padding: 9px;
            margin: 0;
        }
        #barcode{
            margin: 15px 0px;
        }
        .print_box{
            float: left;
            width: 100%;
            display: block;
            border: 1px solid #c4d0ff;
            margin-bottom: 15px;
        }
        .qutot_font{
            text-align: center;
            font-size: 3.5rem;
            background: #9797ff;
            color: #fff;
            margin: 0;
            padding: 13px;
        }
        .entity_name{
            text-align: center;
            width: 100%;
            display: block;
            font-size: 2.5rem;
        }
        .short_desc{
            text-align: center;
            padding: 2rem 3rem;
            font-size: 2rem;
        }
    </style>
    <title><?php echo $app_data['name']; ?></title>
</head>
<body>
<div class="container-fluid" style="width:40%;background: #fff;">
    <div class="print_box">
        <h3 class="qutot_font" style="-webkit-print-color-adjust: exact;">QuToT</h3>
        <?php if(!empty($_SESSION['MAIL_SENT'])){ ?>
            <p id="message_box" style="width: 100%;text-align: center;padding: 5px;color:greenyellow;">
                <?php echo $_SESSION['MAIL_SENT']; ?>
            </p>
            <?php unset($_SESSION['MAIL_SENT']);  } ?>

        <div class="col-12 text-center barcode" id="barcode">
            <img style="display: none;" class="qr-code img-thumbnail img-responsive" />
        </div>
        <H4 class="entity_name"><?php echo $app_data['name']; ?></H4>

        <p class="short_desc">
            Alternatively you can search <span style="font-weight: 600; text-align: center;width: 100%;display: block;"> ( <?php echo $app_data['name']; ?> ) </span> on <b>qutot.com</b> and book walk-in token in  matter of few seconds.
        </p>

    </div>


    <div class="col-12 text-center">
        <!-- <button type="button" class="btn btn-info" id="download_btn">Download</button>-->
        <?php if(!isset($_REQUEST['em'])){ ?>
            <button type="button" class="btn btn-success" id="email">Email QR Code</button>
        <?php } ?>

        <button type="button" class="btn btn-success" id="print">Print QR Code</button>
    </div>


</div>


<?php

if(!empty($_REQUEST['e'])){
    $app_name = $app_data['name'];
    $message  = "This is QR code link for token of $app_name <br>Please click on below link for view code <br> $email_barcode";
    $mail = new PHPMailer;
    $email =$_REQUEST['e'];
    $mail->addCustomHeader("Mime-Version","1.0");
    //$mail->addCustomHeader("Content-Transfer-Encoding","quoted-printable");
    $mail->addCustomHeader("Content-Type","text/html; charset=\"UTF-8\"; format=flowed");
    //$mail->addCustomHeader("From",QUTOT_EMAIL);
    $mail->setFrom(QUTOT_EMAIL,$app_name);
    $rep = explode("@",$email);
    $mail->addAddress($email);
    $mail->addReplyTo(QUTOT_EMAIL, 'no-reply');
    $mail->Subject = "QR code for $app_name";
    $mail->Body    = $message;
    $mail->isHTML(true);
    if($mail->send()){
        $_SESSION['MAIL_SENT'] ="E-mail sent successfully";
    }

    header("Location: $redirect");
}

?>


<script src="https://code.jquery.com/jquery-3.5.1.js"></script>


<script>
    $(function () {

        let finalURL ="<?php echo $barcode; ?>";
        $(document).on('click','download',function(){
            getImgData( document.getElementById("barcode"));
        });

        $(document).on('click','#email',function(){
            if(email = prompt("Please enter email id")){
                window.location.href = "<?php echo $email_url; ?>"+email;
            }
        });

        $(document).on('click','#print',function(){
            printDiv();
        });

        function htmlEncode(value) {
            return $('<div/>').text(value)
                .html();
        }

        $('.qr-code').attr('src', finalURL);
        $('#download_btn').attr('href', finalURL);
        $('.qr-code').show();



        setTimeout(function () {
            $("#message_box").fadeOut('slow');
        },3000);

        function printDiv()
        {
            var divToPrint=document.getElementById('barcode');
            var newWin=window.open('','Print-Window');
            newWin.document.open();
            var css = '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />';
                css += '<link rel="stylesheet" href="../css/qutot.css" />';
                css += '<style type="text/css">  .qr-code{width:500px;height:500px;margin:10px;}\n' +
                    ' .header_bar{background-colour:blue;color:#fff;text-align:center;width:100%;padding:9px;margin:0;}\n' +
                    ' #barcode{margin:15px 0px;}\n' +
                    ' .print_box{float:left;width:100%;display:block;border:1px solid #c4d0ff;margin-bottom:15px;}\n' +
                    ' .qutot_font{text-align:center;font-size:3.5rem;background:#9797ff;color:#fff;margin:0;padding:13px;}\n' +
                    ' .entity_name{text-align:center;width:100%;display:block;font-size:4.5rem !important;}\n' +
                    ' .short_desc{text-align:center;padding:2rem 3rem;font-size:5rem !important;}\n' +
                    ' </style>' +
                    ' <style media="print" > ' +
                    ' @media print{ .short_desc{text-align:center;padding:2rem 3rem;font-size:4rem !important;} .qutot_font{-webkit-print-color-adjust: exact !important; text-align:center;font-size:3.5rem;background:#9797ff !important; color:#fff !important;margin:0;padding:13px;} .header_bar{ -webkit-print-color-adjust: exact !important; background-color:blue;color:#fff;text-align:center;width:100%;padding:9px;margin:0; } body { -webkit-print-color-adjust: exact !important; } .print_box{ float:left;width:100%; display:block; border:1px solid #c4d0ff; margin-bottom:15px; } }' +
                    '</style>';

            newWin.document.write('<html><head>'+css+'</head><body style="-webkit-print-color-adjust: exact;" onload="window.print()">'+$(".print_box").html()+'</body></html>');
            newWin.document.close();
            setTimeout(function(){newWin.close();},100);

        }


    });
</script>
</body>

</html>
