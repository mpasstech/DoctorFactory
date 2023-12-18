<html>
<head>
    <title>Appointment Tracker</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />

    <link href="<?php echo Router::url('/',true); ?>home/icon/apple-touch-icon-48-precomposed.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="<?php echo Router::url('/',true); ?>home/icon/apple-touch-icon-32-precomposed.png" rel="apple-touch-icon-precomposed">
    <link href="<?php echo Router::url('/',true); ?>home/icon/favicon.png" rel="shortcut icon">

    <link href="/doctor/home/icon/apple-touch-icon-48-precomposed.png" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="/doctor/home/icon/apple-touch-icon-32-precomposed.png" rel="apple-touch-icon-precomposed">
    <link href="/doctor/home/icon/favicon.png" rel="shortcut icon">
    <!--link rel="manifest" href="manifest-add-homepage.json"-->
    <script>
        var baseurl ="<?php echo Router::url('/',true);?>";
        var enableReload = true;
    </script>

    <?php echo $this->Html->css(array('bootstrap.min.css',),array("media"=>'all','fullBase' => true)); ?>
</head>
<body>








    <?php if($doctorID == null){ ?>



        <style>

            /* BASIC */

            html {
                background-color: #56baed;
            }

            body {
                font-family: "Poppins", sans-serif;
                height: 100vh;
            }

            a {
                color: #92badd;
                display:inline-block;
                text-decoration: none;
                font-weight: 400;
            }

            h2 {
                text-align: center;
                font-size: 16px;
                font-weight: 600;
                text-transform: uppercase;
                display:inline-block;
                margin: 40px 8px 10px 8px;
                color: #cccccc;
            }



            /* STRUCTURE */

            .wrapper {
                display: flex;
                align-items: center;
                flex-direction: column;
                justify-content: center;
                width: 100%;
                min-height: 100%;
                padding: 20px;
            }

            #formContent {
                -webkit-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
                background: #fff;
                padding: 30px;
                width: 90%;
                max-width: 450px;
                position: relative;
                padding: 0px;
                -webkit-box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
                box-shadow: 0 30px 60px 0 rgba(0,0,0,0.3);
                text-align: center;
            }

            #formFooter {
                background-color: #f6f6f6;
                border-top: 1px solid #dce8f1;
                padding: 25px;
                text-align: center;
                -webkit-border-radius: 0 0 10px 10px;
                border-radius: 0 0 10px 10px;
            }



            /* TABS */

            h2 {
                color: #000000;
                border-bottom: 2px solid #5fbae9;
            }




            /* FORM TYPOGRAPHY*/

            input[type=button], input[type=submit], input[type=reset]  {
                background-color: #56baed;
                border: none;
                color: white;
                padding: 15px 80px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                text-transform: uppercase;
                font-size: 13px;
                -webkit-box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
                box-shadow: 0 10px 30px 0 rgba(95,186,233,0.4);
                -webkit-border-radius: 5px 5px 5px 5px;
                border-radius: 5px 5px 5px 5px;
                margin: 5px 20px 40px 20px;
                -webkit-transition: all 0.3s ease-in-out;
                -moz-transition: all 0.3s ease-in-out;
                -ms-transition: all 0.3s ease-in-out;
                -o-transition: all 0.3s ease-in-out;
                transition: all 0.3s ease-in-out;
            }

            input[type=button]:hover, input[type=submit]:hover, input[type=reset]:hover  {
                background-color: #39ace7;
            }

            input[type=button]:active, input[type=submit]:active, input[type=reset]:active  {
                -moz-transform: scale(0.95);
                -webkit-transform: scale(0.95);
                -o-transform: scale(0.95);
                -ms-transform: scale(0.95);
                transform: scale(0.95);
            }

            select, input[type=text] {
                background-color: #f6f6f6;


                color: #0d0d0d;
                padding: 15px 32px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 5px;
                width: 85%;
                border: 2px solid #f6f6f6;
                border-bottom: 2px solid #5fbae9;
                -webkit-transition: all 0.5s ease-in-out;
                -moz-transition: all 0.5s ease-in-out;
                -ms-transition: all 0.5s ease-in-out;
                -o-transition: all 0.5s ease-in-out;
                transition: all 0.5s ease-in-out;
                -webkit-border-radius: 5px 5px 5px 5px;
                border-radius: 5px 5px 5px 5px;
            }

            select, input[type=text]:focus {
                background-color: #f6f6f6;
                border-bottom: 2px solid #5fbae9;
            }

            select, input[type=text]:placeholder {
                color: #cccccc;
            }



            /* ANIMATIONS */

            /* Simple CSS3 Fade-in-down Animation */
            .fadeInDown {
                -webkit-animation-name: fadeInDown;
                animation-name: fadeInDown;
                -webkit-animation-duration: 1s;
                animation-duration: 1s;
                -webkit-animation-fill-mode: both;
                animation-fill-mode: both;
            }

            @-webkit-keyframes fadeInDown {
                0% {
                    opacity: 0;
                    -webkit-transform: translate3d(0, -100%, 0);
                    transform: translate3d(0, -100%, 0);
                }
                100% {
                    opacity: 1;
                    -webkit-transform: none;
                    transform: none;
                }
            }

            @keyframes fadeInDown {
                0% {
                    opacity: 0;
                    -webkit-transform: translate3d(0, -100%, 0);
                    transform: translate3d(0, -100%, 0);
                }
                100% {
                    opacity: 1;
                    -webkit-transform: none;
                    transform: none;
                }
            }

            /* Simple CSS3 Fade-in Animation */
            @-webkit-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
            @-moz-keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
            @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }

            .fadeIn {
                opacity:0;
                -webkit-animation:fadeIn ease-in 1;
                -moz-animation:fadeIn ease-in 1;
                animation:fadeIn ease-in 1;

                -webkit-animation-fill-mode:forwards;
                -moz-animation-fill-mode:forwards;
                animation-fill-mode:forwards;

                -webkit-animation-duration:1s;
                -moz-animation-duration:1s;
                animation-duration:1s;
            }

            .fadeIn.first {
                -webkit-animation-delay: 0.4s;
                -moz-animation-delay: 0.4s;
                animation-delay: 0.4s;
            }

            .fadeIn.second {
                -webkit-animation-delay: 0.6s;
                -moz-animation-delay: 0.6s;
                animation-delay: 0.6s;
            }

            .fadeIn.third {
                -webkit-animation-delay: 0.8s;
                -moz-animation-delay: 0.8s;
                animation-delay: 0.8s;
            }

            .fadeIn.fourth {
                -webkit-animation-delay: 1s;
                -moz-animation-delay: 1s;
                animation-delay: 1s;
            }

            /* Simple CSS3 Fade-in Animation */
            .underlineHover:after {
                display: block;
                left: 0;
                bottom: -10px;
                width: 0;
                height: 2px;
                background-color: #56baed;
                content: "";
                transition: width 0.2s;
            }

            .underlineHover:hover {
                color: #0d0d0d;
            }

            .underlineHover:hover:after{
                width: 100%;
            }



            /* OTHERS */

            *:focus {
                outline: none;
            }

            #icon {
                width:60%;
            }

        </style>




        <div class="wrapper fadeInDown">
            <div id="formContent">
                <!-- Tabs Titles -->

                <!-- Icon -->
                <div class="fadeIn first">
                    <h2>Please Enter Mobile Number</h2>
                </div>

                <!-- Login Form -->
                <form method="POST" id="get_doctor_appointment_tracker">
                    <input type="text" required id="mobile" class="fadeIn second" name="login" placeholder="Mobile Number">

                    <select  id="thinapp_id" required class="slug_drp fadeIn third">
                        <option value="">Select Organization</option>
                    </select>
                    <input type="submit" class="fadeIn fourth" value="Submit">
                </form>


            </div>
        </div>

        <script src="<?php echo Router::url('/js/jquery.js')?>"></script>
        <script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>

        <script>


            function checkOrg(){
                var mob_obj =$("#mobile");
                if(/^\d{10}$/.test((mob_obj.val()).trim())){
                    $(mob_obj).css('border-color',"#ccc");
                    var mob = $(mob_obj).val();
                    var role_type = "DOCTOR";
                    if(mob!=""){
                        $.ajax({
                            url:baseurl+"app_admin/get_org",
                            type:'POST',
                            data:{mob:"+91"+mob,role_type:role_type},
                            beforeSend:function(){

                                $('.slug_drp').html('<option>Select Organization</option>');
                            },
                            success:function(res){
                                if(res!=0){
                                    $(".slug_drp").html(res);
                                    $(".slug_drp").prop('selectedIndex', 0);

                                }else{

                                    $(".slug_drp").html("<option>Select Organization</option>");

                                }
                            },
                            error:function () {
                                $(".slug_drp").html("<option>Select Organization</option>");
                            }
                        });
                    }
                }else{
                    $(this).css('border-color',"red");
                }
            }


            $(document).on("input","#mobile", function(e) {
                    checkOrg();
            });


            $(document).on("submit","#get_doctor_appointment_tracker",function(e){
                e.preventDefault();
                var mobile = $("#mobile").val();
                var thinapp_id = $("#thinapp_id").val();


                $.ajax({
                    url:baseurl+"tracker/get_tracker_doctor_id",
                    type:'POST',
                    data:{mobile:"+91"+mobile,thinapp_id:thinapp_id},

                    success:function(res){

                        res = JSON.parse(res);
                        console.log(res);
                        if(res.status == 1)
                        {
                            var url = window.location.href;

                                url += '/'+res.doctor_id;

                            window.location.href = url;
                        }
                        else
                        {
                            window.location.reload();
                        }

                    },
                    error:function () {
                        $(".slug_drp").html("<option>Select Organization</option>");
                    }
                });

            });

        </script>



    <?php }
    else
        { ?>


        <style>
            .lab p{
                font-size: 26px !important;
                padding-top: 19px !important;
            }
            .report p {

                font-size: 16px !important;
                padding-top: 26px !important;

            }
            .late p {

                font-size: 26px !important;
                padding-top: 19px !important;

            }
            .emergency p {
                font-size: 12px !important;
                padding-top: 28px !important;
            }
            .doctor_name h3 {
                background-color: #efe9ec;
                text-align: center;
                padding: 8px;
            }
            .background-img img {
                width: 100%;
            }
            .background-img {
                position: absolute;
                width: 91%;
            }
            .token-number {
                height: 74px;
                padding: unset;
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
                padding-left: 22px;
                color: #FFF;
            }
            .upper-detail {
                float: right;
                height: 37px;
                padding-top: 10px;
                padding-left: 22px;
                color: #FFF;
            }
            .token-detail {
                padding-top: 15px;
            }
            .aprox_token {
                height: 80px;
                padding-top: 25px;
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
        </style>


        <div class="container-fluid">
                <?php if(!empty($dataToShow)){ ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 doctor_name">
                            <h3><?php echo @$dataToShow['currentToken']['doctor_name']; ?></h3>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 current_token">

                            <div class="background-img">
                                <img src="<?php echo Router::url('/tracker_img/pink_stripe.png')?>">
                            </div>



                            <?php
                            $tokenNum = 'N/A';
                            $tokenClass="";
                            $timeSrt = "";
                            if(isset($dataToShow['currentToken']['token_number']))
                            {
                                if($dataToShow['currentToken']['emergency_appointment'] == 'YES')
                                {
                                    $tokenNum = 'EMERGENCY';
                                    $tokenClass="emergency";
                                }
                                else if($dataToShow['currentToken']['patient_queue_type'] == 'EMERGENCY_CHECKIN')
                                {
                                    $tokenNum = 'EMERGENCY';
                                    $tokenClass="emergency";
                                }
                                else if($dataToShow['currentToken']['patient_queue_type'] == 'LATE_CHECKIN')
                                {
                                    $tokenNum = 'NOT';
                                    $tokenClass="late";
                                }
                                else if($dataToShow['currentToken']['patient_queue_type'] == 'REPORT_CHECKIN')
                                {
                                    $tokenNum = 'REPORT';
                                    $tokenClass="report";
                                }
                                else if($dataToShow['currentToken']['patient_queue_type'] == 'LAB_TEST')
                                {
                                    $tokenNum = 'LAB';
                                    $tokenClass="lab";
                                }
                                else if ($dataToShow['currentToken']['token_number'] == 'Emergency')
                                {
                                    $tokenNum = 'EMERGENCY';
                                    $tokenClass="emergency";
                                }
                                else
                                {
                                    $tokenNum = $dataToShow['currentToken']['token_number'];
                                    $timeSrt = $dataToShow['currentToken']['time_slot'];
                                }
                            }
                            ?>

                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 token-detail">
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 token-number <?php echo $tokenClass; ?>"><p><?php echo $tokenNum; ?></p></div>
                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 upper-detail">Current Token<?php echo ($timeSrt != '')?' - '.$timeSrt:''; ?></div>
                                <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 lower-detail"><?php echo isset($dataToShow['currentToken']['patient_name'])?$dataToShow['currentToken']['patient_name']:'N/A'; ?></div>
                            </div>

                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 aprox_token">
                            <div class="background-img">

                                <?php if(@$dataToShow['isLate'] == true){ ?>
                                    <img src="<?php echo Router::url('/tracker_img/middle_stripe_red.png')?>">
                                <?php }else{ ?>
                                    <img src="<?php echo Router::url('/tracker_img/middle_stripe_green.png')?>">
                                <?php } ?>


                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 aprox_text">
                                <p class="text1"><?php echo @$dataToShow['isLate'] == true?'Late':'Early';?></p>
                                <p class="text2"><?php echo @$dataToShow['timeDeff']; ?></p>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 your_token">

                            <?php if(@$dataToShow['currentToken']['appointment_id'] > 1){ ?>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 token-detail text-center">
                                    <button type="button" id="close_appointment" data-id="<?php echo base64_encode($dataToShow['currentToken']['appointment_id']); ?>" class="btn btn-danger">Close Appointment</button>
                                </div>
                            <?php } ?>



                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="refresh-btn">
                                <img onclick="window.location.reload()" src="<?php echo Router::url('/tracker_img/refresh.png')?>">
                            </div>
                        </div>

                    </div>

            <?php } else{ ?>
                    <p style="color:#000000; text-align:center;">No data to display</p>
                    <script>
                        enableReload = true;
                    </script>
                <?php }?>







        </div>

        <script src="<?php echo Router::url('/js/jquery.js')?>"></script>
        <script src="<?php echo Router::url('/js/bootstrap.min.js')?>"></script>

        <script>

            $(document).on('click','#close_appointment',function(){
                if(enableReload == true)
                {
                    enableReload = false;

                    var appointmentID = $(this).attr('data-id');
                    var $btn = $(this);

                    $.ajax({
                        type:'POST',
                        url: baseurl+"tracker/close_appointment",
                        data:{id:appointmentID},
                        beforeSend:function(){
                            $btn.attr('disabled','disabled');
                        },
                        success:function(data){
                            enableReload = true;
                            window.location.reload();
                        },
                        error: function(data){
                            $btn.removeAttr('disabled');
                            enableReload = true;
                        }
                    });


                }

            });




            setInterval(function(){
                if(enableReload == true)
                {
                    window.location.reload();
                }
            },15000);
        </script>



    <?php } ?>

</body>
</html>