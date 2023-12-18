<?php
error_reporting(0);

?>
<!DOCTYPE html>
<html>
<head>
    <title>My Token</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" media="all" />
    <style>

        .date_box{
            text-align: center;
            display: block;
            width: 100%;
            float: left;
            font-size: 3.5rem;
            padding: 10px 0px;
            margin: 0px;
            background: #00a200d9;
            color: #fff;
            font-weight: 600;

        }
        .token_box{
            text-align: center;
            width: 100%;
            font-size: 20rem;
            display: block;
            margin: 60px 0;
            float: left;

            }
            img{
                float: left;
                width: 100px;
                border: 3px solid;
                padding: 5px;
                border-radius: 57px;
                height: 100px;
            }
            .top_heading{
                font-size: 3.5rem;
                border-bottom: 3px solid;
                width: 70%;
                float: left;
            }
            ul{
                margin: 0;
                padding: 10px 0 0 0;
                border-top: 3px dotted #000000;
                float: left;
                width: 100%;
            }
            ul li{
                padding: 5px 10px;
                font-size: 2rem;
                font-weight: 500;
                text-align: left;
            }
            .valid{
                position: absolute;
                text-align: center;
                width: 100%;
                color: red;
                bottom: 0;
                font-weight: 600;
            }
        .app_name{
            background: #00a200;
            color: #fff;
            margin: 0;
            padding: 10px 0;
            text-align: center;
            font-size: 2.5rem;
            font-weight: 600;
            border-top: 3px solid #000;
        }
</style>


</head>
<body style="overflow: auto;">
<h3 class="app_name"><?php echo $data['app_name']; ?></h3>
<div class="date_box">
    <img src="<?php echo $data['logo']; ?>" >
    <label class="top_heading"><?php echo $data['slot_time']; ?></label><br>
    <?php echo date('d-M-Y',strtotime($data['appointment_datetime'])); ?>
</div>
<h1 class="token_box"><?php echo $token; ?> </h1>

<ul>
    <li>Patient Name : <b><?php echo $data['appointment_patient_name'] ; ?></b> </li>
    <li>Doctor Name : <b><?php echo $data['doctor_name'] ; ?></b> </li>
    <li></li>

</ul>

<p class="valid">
    THIS TOKEN SCREEN IS ONLY VALID FOR TODAY
</p>


</body>
</html>



