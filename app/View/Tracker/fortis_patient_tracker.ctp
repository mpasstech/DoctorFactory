<?php
//pr($userAppointmentData);
//pr($otherTokenData);
//pr($showTracker);
//die;
?>
<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <link href="/doctor/home/icon/apple-touch-icon-48-precomposed.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="/doctor/home/icon/apple-touch-icon-32-precomposed.png" rel="apple-touch-icon-precomposed">
    <link href="/doctor/home/icon/favicon.png" rel="shortcut icon">
    <!--link rel="manifest" href="manifest-add-homepage.json"-->
    <?php echo $this->Html->css(array('bootstrap.min.css','font-awesome.min.css','jquery-confirm.min.css'),array("media"=>'all','fullBase' => true)); ?>
    <style>

        .profile_photo{
            width: 55px;
            height: 55px;
            float: left;
            border-radius: 50%;
            display: flex;
            padding: 1px;
            border: 3px solid;
            color: #fff;
            position: absolute;
        }
        .emer-message {
            text-align: center;
            min-height: 300px;
        }
        .emer-message h3 {
            font-weight: bold;
        }
        .lab p{
            font-size: 26px !important;
            padding-top: 19px !important;
        }
        .report{

            font-size: 80px !important;

        }
        .late p {

            font-size: 26px !important;
            padding-top: 19px !important;

        }
        .emergency{
            font-size: 50px !important;

        }
        .your_token, .current_token{
            min-height: 300px;
        }
        .doctor_name h3 {
            background-color: #4c7ab6;
            text-align: center;
            padding: 8px;
            margin: 0;
            color: #fff;
            height: 70px;
            font-size: 2.2rem;
            font-weight: 600;
            border-bottom: 1px solid #fff;

        }
        .doctor_name h3 span{
            display: block;
            text-align: center;
            width: 100%;
            font-weight: 500;
            font-size: 2rem;
            color: yellow;
        }

        .background-img img {
            width: 100%;
        }
        .background-img {
            position: absolute;
            width: 91%;
        }
        .emergency_cls{
            font-size: 50px !important;
            padding: 50px 0px !important;
            color: red;
        }
        .token-number {
            padding: 0px;
            text-align: center;
            font-size: 150px;
            font-weight: 600;

            text-align: center;
            margin: 0 auto;
            min-height: 130px;
        }


        .add_class {
            font-size: 45px !important;
            min-height: 50px !important;
        }
        .carousel-item img{
            width: 100%;
            float: left;

        }


        .token-number p {
            text-align: center;
            padding-top: 8px;
            font-size: 40px;
            width: 100%;
            font-weight: bold;
        }
        .lower-detail {
            float: right;
            height: 37px;
            padding-top: 10px;
            padding-left: 15px;
            color: #FFF;
        }
        .upper-detail {
            float: right;
            height: 37px;
            padding-top: 10px;
            padding-left: 15px;
            color: #FFF;
        }
        .token-detail {
            padding-top: 15px;
        }
        .aprox_token {
            font-size: 1.8rem;
            padding: 5px 0px;
            background: #4def8c;
            color: #000;
            margin: 1px 0px 0px 0px;
            font-weight: 600;
            text-align: center;
        }

        .aprox_text p {
            text-align: center;
            font-size: 12px;
            color: #FFF;
            font-weight: bold;
        }
        .text2 {
            line-height: 1px;
        }
        .text1 {
            padding-top: 1px;
        }
        .refresh-btn {
            text-align: center;
            margin-top: 44px;
        }
        .refresh-btn img {
            width: 60px;
        }

        .doctor_name{
            padding-left: 0px !important;
            padding-right: 0px !important;
        }

        .row{
            margin-right: 0px !important;
            margin-left: 0px !important;

        }

        .your_token{


            background-image: linear-gradient(to bottom, #39b13c , #4def8c);

        }
        .current_token{


            background-image: linear-gradient(to bottom, #4c7ab6 , #64a7c18c);

        }

        .current_token_heading{
            width: 100%;
            border: 1px solid yellow;
            height: 1px;
            margin: 40px auto;
        }
        .current_token_heading span{
            width: 60%;
            text-align: center;
            font-size: 1.9rem;
            font-weight: 600;
            background: yellow;
            margin: -22px 20%;
            padding: 10px 0px;
            border-radius: 30px;
            display: inline-block;

        }
        .bottom_sub_box{
            padding: 0;
            margin: 0;
            text-align: center;
            font-size: 1.8rem;
        }
        .bottom_box{

            padding: 10px 5px;
            color: #150c0c;
            font-size: 1.8rem;
            min-height: 110px;


        }

        .bottom_sub_box label{
            width: 100%;
            display: block;
            text-align: center;

        }

        .refresh_box button{
            background: transparent;
            font-size: 2.8rem;
            margin: 0px;
            text-align: right;
            outline: none;
        }
        .refresh_box{
            display: none;
        }

        .blink_me {
            animation: blinker 1s linear infinite;
        }

        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
        .warning_message i{
            display: block;
            font-size: 8.5rem;
            color: #a8a8cf;

        }

        #track_btn{
            padding: 10px;
            border-radius: 69px;
            border: 0;
            background: #364fff;
            color: #fff;
            margin: 36px auto;
            width: 100%;
        }
        .warning_message{
            text-align: center;
            font-size: 2.5rem;
            margin: 50% 0;
        }
        .warning_heading{
            font-size: 27px;
            text-align: center;
            margin: 16% 0px;
        }
        .member_cnt{
            color: red;
        }

        .li_box{
            list-style: none;
            padding: 14px 0px;
            margin: 5px;
            color: green;
            border: 1px solid;
            width: 50px;
            height: 50px;
            text-align: center;
            border-radius: 50%;
            font-weight: 600;
            float: left;
        }
        .li_box.active{
            background: green;
            color: #fff;
        }

        .jconfirm-content{
            float: left;
        }
    </style>


</head>
<body>
<div class="container-fluid">

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 doctor_name">

            <h3>
                <img class="profile_photo" src="<?php echo $appointmentData['profile_photo']; ?>" >
                <?php
                    echo "LIVE TOKEN STATUS"."<br>";
                    echo "<span>".$appointmentData['doctor_name']."</span>";

                ?></h3>
        </div>

        <?php if(!empty($appointmentData['appointment_datetime']) && date('Y-m-d',strtotime($appointmentData['appointment_datetime'])) == date('Y-m-d')){ ?>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 current_token">
                <h4 class="current_token_heading"><span>Current Token</span></h4>
                <div class="blink_me token-number current_token_box" id="live_token_box">-</div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 your_token">
                <h4 class="current_token_heading"><span>Your Token</span></h4>
                <?php if($appointmentData['sub_token']=='NO'){ ?>
                    <div class="token-number current_token_box"><?php echo $appointmentData['queue_number']; ?></div>
                <?php }else{ ?>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 emer-message">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2 style="color:red;" class="emergency_cls">EMERGENCY</h2></div>
                    </div>
                <?php } ?>
            </div>

        <?php }else { ?>

            <p class="warning_message">
                <i class="fa fa-clock-o"></i>
                Tracker is display only on appointment date.

            </p>
        <?php } ?>

    </div>


</div>

<script src="<?php echo Router::url('/js/jquery.js'); ?>"></script>
<script src="<?php echo Router::url('/js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo Router::url('/js/jquery-confirm.min.js'); ?>"></script>
<script>
    setInterval(function(){
        getCurrentToken();
    },15000);

    function getCurrentToken(){
        var data = {
            ti:btoa("<?php echo $thin_app_id; ?>"),
            di:btoa("<?php echo $doctor_id; ?>")
        };
        $.ajax({
            type:'POST',
            url: "<?php echo Router::url('/tracker/fortis_get_current_token',true); ?>",
            data:data,
            beforeSend:function(){
                //$(".current_token_box").prop('disabled',true).text('...');
            },
            success:function(response){
                var response = JSON.parse(response);
                var token = response.token_number;

                if(response.sub_token=='YES'){
                    token = "EMERGENCY"
                    $("#live_token_box").addClass("emergency_cls");
                }else{
                    $("#live_token_box").removeClass("emergency_cls");
                }
                $("#live_token_box").html(token);
            },
            error: function(data){
                $("#live_token_box").html("-");

            }
        });
    }
    getCurrentToken();



</script>


</body>
</html>