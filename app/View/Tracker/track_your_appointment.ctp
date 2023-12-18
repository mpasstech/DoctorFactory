<?php
//pr($userAppointmentData);
//pr($otherTokenData);
//pr($showTracker);
//die;


if($thinappID==892){
    header('location: https://mengage.in/doctor/tracker/fortis?t=ODky');
    die;
}


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
        .doctor_name h3 {
            background-color: #4c7ab6;
            text-align: center;
            padding: 8px;
            margin: 0;
            color: #fff;

        }
        .background-img img {
            width: 100%;
        }
        .background-img {
            position: absolute;
            width: 91%;
        }
        .token-number {
            padding: 0px;
            text-align: center;
            font-size: 95px;
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


<?php
$adverClass = '';
$show_add_banner = $this->AppAdmin->check_app_enable_permission($thinappID,"ADVERTISEMENT_BANNER");
if($show_add_banner){
    $adverClass = "add_class";
}
?>


<div class="container-fluid">




    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 doctor_name">
            <h3><?php
                if($showTracker==true){
                    echo $userAppointmentData['AppointmentStaff']['name'];
                }else{
                    echo "Appointment Tracker";
                }
                ?></h3>
        </div>

        <?php if($showTracker == true){ ?>
            <?php
            $tokenNum = 'N/A';
            $tokenClass="";
            $timeSrt = "";
            if(empty($break_array)) {
                if (isset($otherTokenData[0]['token_number'])) {
                    if ($otherTokenData[0]['emergency_appointment'] == 'YES') {
                        $tokenNum = 'EMERGENCY';
                        $tokenClass = "emergency";
                    } else if ($otherTokenData[0]['patient_queue_type'] == 'EMERGENCY_CHECKIN') {
                        $tokenNum = 'EMERGENCY';
                        $tokenClass = "emergency";
                    } else if ($otherTokenData[0]['patient_queue_type'] == 'LATE_CHECKIN') {
                        $tokenNum = 'NOT';
                        $tokenClass = "late";
                    } else if ($otherTokenData[0]['patient_queue_type'] == 'REPORT_CHECKIN') {
                        $tokenNum = 'REPORT';
                        $tokenClass = "report";
                    } else if ($otherTokenData[0]['patient_queue_type'] == 'LAB_TEST') {
                        $tokenNum = 'LAB';
                        $tokenClass = "lab";
                    } else if ($otherTokenData[0]['token_number'] == 'Emergency') {
                        $tokenNum = 'EMERGENCY';
                        $tokenClass = "emergency";
                    } else {
                        $tokenNum = $otherTokenData[0]['token_number'];
                        $timeSrt = $otherTokenData[0]['time_slot'];
                    }
                }
            }
            ?>
            <?php if(empty($break_array)){ ?>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 current_token">
                    <h4 class="current_token_heading"><span>Current Token</span></h4>
                    <div class="blink_me token-number  <?php echo $tokenClass; ?>  <?php echo $adverClass; ?>"><?php
                        if($thinappID==CK_BIRLA_APP_ID && $tokenNum !="N/A"){
                            $doctor_id = $userAppointmentData['AppointmentCustomerStaffService']['appointment_staff_id'];
                            echo $this->AppAdmin->get_ck_birla_token($doctor_id,$tokenNum);
                        }else{
                            echo $tokenNum;
                        }



                        ?></div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bottom_box">

                        <?php if($thinappID!=892){ ?>
                        <label>Name : </label><?php echo isset($otherTokenData[0]['patient_name'])?$otherTokenData[0]['patient_name']:'N/A'; ?>
                        <br>
                        <?php  if($userAppointmentData['AppointmentStaff']['show_appointment_time']=='YES') {
                            echo "<label>Time : </label>";
                            echo $timeSrt;
                        }

                        ?>

                        <?php } ?>

                    </div>
                </div>

            <?php }else{ ?>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 emer-message">
                    <?php if($break_array['flag'] != 'CUSTOM'){ ?>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><h2 style="color:red;">EMERGENCY</h2></div>
                    <?php } ?>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><h3><?php echo $break_array['patient_name']; ?></h3></div>
                </div>
            <?php } ?>


            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 your_token">

                <?php
                $tokenNum = 'N/A';
                $tokenClass="";
                $timeSrt = "";
                if(isset($userAppointmentData['AppointmentCustomerStaffService']['queue_number']))
                {
                    if($userAppointmentData['AppointmentCustomerStaffService']['emergency_appointment'] == 'YES')
                    {
                        $tokenNum = 'EMERGENCY';
                        $tokenClass="emergency";
                    }
                    else if($userAppointmentData['AppointmentCustomerStaffService']['patient_queue_type'] == 'EMERGENCY_CHECKIN')
                    {
                        $tokenNum= 'EMERGENCY';
                        $tokenClass="emergency";
                    }
                    else if($userAppointmentData['AppointmentCustomerStaffService']['patient_queue_type'] == 'LATE_CHECKIN')
                    {
                        $tokenNum = 'NOT';
                        $tokenClass="late";
                    }
                    else if($userAppointmentData['AppointmentCustomerStaffService']['patient_queue_type'] == 'REPORT_CHECKIN')
                    {
                        $tokenNum = 'REPORT';
                        $tokenClass="report";
                    }
                    else if($userAppointmentData['AppointmentCustomerStaffService']['patient_queue_type'] == 'LAB_TEST')
                    {
                        $tokenNum = 'LAB';
                        $tokenClass="lab";
                    }
                    else if ($userAppointmentData['AppointmentCustomerStaffService']['queue_number'] == 'Emergency')
                    {
                        $tokenNum= 'EMERGENCY';
                        $tokenClass="emergency";
                    }
                    else
                    {
                        $tokenNum = $userAppointmentData['AppointmentCustomerStaffService']['queue_number'];
                        $timeSrt = $userAppointmentData['AppointmentCustomerStaffService']['slot_time'];
                    }
                }
                ?>
                <h4 class="current_token_heading"><span>Your Token</span></h4>
                <div class="token-number  <?php echo $adverClass; ?> <?php echo $tokenClass; ?>"><?php
                    $counter_token = $tokenNum;
                    if($thinappID==CK_BIRLA_APP_ID && $tokenNum !="N/A"){
                        $doctor_id = $userAppointmentData['AppointmentCustomerStaffService']['appointment_staff_id'];
                        echo $counter_token = $this->AppAdmin->get_ck_birla_token($doctor_id,$tokenNum);
                    }else{
                        echo $tokenNum;
                    }

                    ?></div>


                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 bottom_box">
                       <?php if($thinappID!=892){ ?>
                            <label>Name : </label>
                            <?php
                                echo isset($userAppointmentData['AppointmentCustomer']['first_name'])?$userAppointmentData['AppointmentCustomer']['first_name']:$userAppointmentData['Children']['child_name'];
                                $reason = $userAppointmentData['AppointmentCustomerStaffService']['reason_of_appointment'];
                                if($userAppointmentData['Thinapp']['category_name']=='TEMPLE' && $reason > 1) {
                                    echo "<span class='member_cnt'> ( + $reason members )</span>";
                                }
                            ?>
                            <br>
                            <?php  if($userAppointmentData['AppointmentStaff']['show_appointment_time']=='YES') {
                                echo "<label>Time : </label>";
                                echo $timeSrt;
                            } ?>
                        <?php }else{ ?>
                            <?php  if($userAppointmentData['AppointmentCustomerStaffService']['patient_queue_type']=='BILLING_CHECKIN') { ?>
                                    <button data-ct="<?php echo $counter_token; ?>" id="track_btn" data-q="<?php echo $tokenNum; ?>" data-t="<?php echo base64_encode($thinappID); ?>" data-di="<?php echo base64_encode($userAppointmentData['AppointmentCustomerStaffService']['appointment_staff_id']); ?>" type="button">Track Your Number</button>

                        <?php }} ?>
                </div>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 aprox_token">

                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <?php if($waiting_time > 0){

                        $time_string = "";
                        $hours = floor($waiting_time / 60);
                        if($hours > 0){
                            $time_string = ($hours > 1)?$hours." Hours ":$hours." Hour ";
                        }
                        $minutes = ($waiting_time -   floor($waiting_time / 60) * 60);
                        if($minutes > 0){
                            $time_string .= ($minutes > 1)?$minutes." Minutes ":$minutes." Minute ";
                        }

                        ?>

                    <?php }else{  $time_string = "0"; } ?>

                    <?php if(false){ ?>
                        <p class="text1">Your Waiting Time Is </p>
                        <p class="text2"><?php echo $time_string; ?></p>
                    <?php } ?>


                </div>
                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 refresh_box" style="text-align: right;">
                    <button  class="refresh_btn btn"><i class="fa fa-refresh"></i><br></button>
                </div>

            </div>
            <!-- banner start -->
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin: 0;padding: 0;">
                <?php $ban_list  = $this->AppAdmin->getAddBanner(); ?>
                <?php if($show_add_banner && !empty($ban_list)){ ?>
                    <div class="row add_container">
                        <div id="home_advertisement" class="carousel slide" data-ride="carousel" >
                            <div class="carousel-inner">
                                <?php foreach ($ban_list as $key => $banner) {   ?>
                                    <div class="carousel-item <?php echo ($key==0)?'active':''; ?>">
                                        <img src="<?php echo $banner['path']; ?>" class="d-block w-100" alt="...">
                                    </div>
                                <?php }   ?>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <?php $ban_list  = $this->AppAdmin->getAddBanner(); ?>


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
        //window.location.reload();
    },15000);

    $('.carousel').carousel({
        interval: 3000
    });

    $(".refresh_btn").click(function(){
        $(this).button('loading').html("").addClass("fa fa-spinner fa-pulse");
        window.location.reload();
    })


    $(document).on("click","#track_btn",function(e){
        var $btn =  $(this);
        var text =  $(this).text();
        var data = {
            t:$(this).attr('data-t'),
            di:$(this).attr('data-di'),
            q:$(this).attr('data-q'),
        };
        var ct = $(this).attr('data-ct');
        $.ajax({
            type:'POST',
            url: "<?php echo Router::url('/tracker/get_previous_token_list',true); ?>",
            data:data,
            beforeSend:function(){
                $($btn).prop('disabled',true).text('Please Wait...');
            },
            success:function(response){
                $($btn).prop('disabled',false).text(text);
                var response = JSON.parse(response);
                if(response.status == 1){

                    var content = '';
                    var total_tokens =0;
                    $.each(response.list,function (index,value) {
                        var class_name = (value==ct)?'li_box active':'li_box';
                        content += "<li class='"+class_name+"'>"+value+"</li>";
                        total_tokens++;
                    })

                    total_tokens = total_tokens-1;

                    var tok_lbl = (total_tokens>1)?'Tokens':'Token';
                    if(total_tokens==0){
                        tok_lbl = 'Now is your turn for billing';
                    }else{
                        tok_lbl = total_tokens+' '+tok_lbl;
                        tok_lbl = 'Your turn will come after '+tok_lbl+' for Billing';
                    }


                    var jc = $.confirm({
                        title: tok_lbl,
                        columnClass:'box_container',
                        content: content,
                        html:true,
                        buttons: {
                            cancel: {
                                btnClass: 'emr_not_slot_time_btn',
                                text: 'Close',
                                action: function () {
                                    jc.close();
                                }
                            }
                        },
                        onContentReady: function () {

                        }
                    });
                }
            },
            error: function(data){
                $($btn).prop('disabled',false).text(text);
                alert("Sorry something went wrong on server.");
            }
        });
    });


    setInterval(function(){
        window.location.reload();
    },10000);


</script>

<script>
    if ('serviceWorker' in navigator) {
        console.log("Will the service worker register?");
        navigator.serviceWorker.register('<?php echo Router::url('/service-worker.js'); ?>').then(function(reg){
            console.log("Yes, it did.");
        }).catch(function(err) {
            console.log("No it didn't. This happened:", err);
        });
    }
</script>


</body>
</html>