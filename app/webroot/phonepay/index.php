<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_errors', 0);
include_once '../constant.php';
$baseUrl = SITE_PATH;

$redirect_url ="";

?>
<html>

<head>
    <title>Payment</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>

        .t_and_c_heading{
            text-align: center;
            padding: 0;
            margin: 0;
            color: #000;
            text-decoration: underline;
            font-weight: 600;

        }
        .t_and_c_body{
            padding: 2px 5px;
            margin: 0;
            font-size: 15px;
            border: 1px solid #eaeaea;
            overflow-y: scroll;
            height: 75%;
        }
        .accept-btn-holder button{
            background: green;
            color: #fff;
            padding: 7px 20px;
            border-radius: 19px;
            border: none;
            font-size: 15px;
            outline: none;
        }
        .accept-btn-holder{
            width: 100%;
            float: left;
            display: block;
            text-align: center;
        }


        .action_btn, .continue, .skip_btn{
            padding: 5px 30px;
            font-size: 22px;
            background: #2196F3;
            border-color: #2196F3;
            outline: none;
        }
        .skip_btn, .skip_btn:hover, .skip_btn:focus{
            background: #7e36f3;
            border-color: #7e36f3;

        }
        h1, h2{
            text-align: center;
        }
        .container{
            padding: 0px 15px;
        }
        p{
            font-size: 20px;
            text-align: center;
        }
        li{
            font-weight: 500;
        }
        h3{
            font-size: 26px;
        }


        .option {
            display: block;
            position: relative;
            padding-left: 34px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 17px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            float: left;
            width: 100%;
            font-weight: 500;
        }

        /* Hide the browser's default checkbox */
        .option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .option:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .option input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .option input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .option .checkmark:after {
            left: 9px;
            top: 5px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }



        .sub_title{
            font-size: 18px;
            padding: 10px 0px;
        }
        .inner_sub_title{
            font-size: 12px;
            display: block;
        }

        .top_nav{
            padding: 0px;
            margin: 10px 0px 20px 0px;
            display: block;
            width: 100%;
            float: left;
            border-bottom: 1px solid #e2e2e2;
        }
        .top_nav li{
            float: left;
            width: 50%;
            padding: 5px;
            list-style: none;

        }
        .top_nav .prev{
            text-align: left;
        }
        .top_nav .cancel, .top_nav .done {
            text-align: right;
        }
        .prev span{
            background: #5ecb55;
        }
        .cancel span, .done span{
            background: #ff4c4c;
        }
        .top_nav li span{
            padding: 4px 18px;
            color: #fff;
            border-radius: 26px;
            border: none;
            font-size: 15px;
        }


        #loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid green;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            100% {
                transform: rotate(360deg);
            }
        }

        .center {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
            z-index:9999999;
        }

        .p_subtitle{
            text-align: left;
            font-size: 18px;
        }

        .label_title{
            font-size: 20px;
        }

        .p_body{

            text-align: left;
            font-size: 17px;
            font-weight: 500;

        }
        .number_lbl{
            float: left;
            font-size: 20px;
            background: black;
            color: #fff;
            padding: 0px 9px;
            border-radius: 59px;
            width: 8%;
        }
        .number_lbl_heading{
            font-size: 18px;
            font-weight: 600;
            width: 100%;
            float: left;
            padding:0px 0px 5px 0px;
            margin: 0;
        }
        .res_body{
            display: block;
            float: left;
            width: 100%;
        }

        .content_ol {
            list-style: none;
            counter-reset: item;
        }
        .content_ol li {
            counter-increment: item;
            margin-bottom: 25px;
        }

        .content_ol li:before {
            left: 15px;
            content: counter(item);
            background: green;
            border-radius: 100%;
            color: white;
            /* width: 1.2em; */
            text-align: center;
            display: inline-block;
            position: absolute;
            float: left;
            padding: 3px 10px;
            font-size: 15px;
        }
        .warning-box{
            display: block;
            float: left;
            padding: 15px;
            background: #c5c5c5;
            color: #000;
            margin-bottom: 30px;
        }

        .data_container_box{
            display: block !important;;
            padding: 5px !important;
        }
        .lng_btn{
            padding: 9px 12px;
            float: left;
            width: auto;
            font-size: 19px;
            margin: 0 6px;
        }

        .result_content{
            display: block;
            width: 100%;
            float: left;
        }
    </style>
    <script>
        document.onreadystatechange = function() {
            if (document.readyState !== "complete") {
                document.querySelector("body").style.visibility = "hidden";
                document.querySelector("#loader").style.visibility = "visible";
            } else {
                document.querySelector("#loader").style.display = "none";
                document.querySelector("body").style.visibility = "visible";
            }
        };
    </script>

</head>
<body>
<div id="loader" class="center"></div>
<?php

include '../webservice/ConnectionUtil.php';
include '../webservice/Custom.php';
$connection = ConnectionUtil::getConnection();
$postUrl = "https://api-preprod.phonepe.com/apis/pg/v1/pay";
$mode = "PROD";
$request_from = (isset($_REQUEST['rf']) && !empty($_REQUEST['rf']))?$_REQUEST['rf']:'doctor';

$orderCurrency = 'INR';
$appointmentID = base64_decode(trim($_GET['token']));
//$appointmentID = (trim($_GET['token']));
$sql = "SELECT acss.booking_validity_attempt, t.pay_clinic_visit_fee_online, staff.emergency_appointment_fee,  acss.emergency_appointment, t.booking_convenience_fee_emergency, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat, acss.queue_number, bcfd.tx_time,bcfd.amount AS order_amount, t.version_name, staff.is_offline_consulting, staff.is_chat_consulting, staff.is_audio_consulting, service.video_consulting_amount, service.audio_consulting_amount, service.chat_consulting_amount, acss.consulting_type, t.booking_convenience_fee_online_consutlting_terms_condition as online_t_c, acss.booking_validity_attempt, service.service_amount AS consulting_fee, staff.is_online_consulting, t.booking_convenience_fee_terms_condition, acss.thinapp_id,acss.`status`,acss.children_id,acss.appointment_datetime,acss.appointment_customer_id,acss.booking_date, t.booking_convenience_fee, acss.is_paid_booking_convenience_fee,  IFNULL(ac.first_name,c.child_name ) AS `name`, IFNULL(ac.mobile,c.mobile) AS mobile, IFNULL(ac.email, c.email ) AS email FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id JOIN appointment_services AS service ON service.id = acss.appointment_service_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id LEFT JOIN booking_convenience_fee_details AS bcfd ON bcfd.appointment_customer_staff_service_id = acss.id WHERE acss.id = $appointmentID LIMIT 1";
$list = $connection->query($sql);
$appointmentData = mysqli_fetch_assoc($list);


$testAppArray = Custom::getTestModeApp($appointmentData['thinapp_id']);
if(in_array($appointmentData['thinapp_id'],$testAppArray)){
    $postUrl = "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";
    $mode = "DEV";
}


$thinappID = $appointmentData['thinapp_id'];
$orderId = $appointmentID.'-'.$thinappID."-".mt_rand(001,99999) ;

$RETURN_URL = $baseUrl."phonepay/redirect.php?ai=".base64_encode($appointmentID);
$NOTIFY_URL = $baseUrl."phonepay/callback.php?order_id=$orderId&&rf=$request_from";


$version = $appointmentData['version_name'];
if( ($version >= '2.8.9')){
    $appointment_type = 'OFFLINE';
    if(in_array($appointmentData['consulting_type'],array('ONLINE','VIDEO','AUDIO','CHAT'))){
        $appointment_type = 'ONLINE';
    }
}else{
    $appointment_type = 'OFFLINE';
    if($appointmentData['is_online_consulting']=='YES'){
        $appointment_type = 'ONLINE';
    }
}

$show_survey_form = false;
if($appointment_type=='ONLINE'){
    $show_survey_form = false;
}

?>

<div class="container-fluid" style="overflow-x: hidden;">

    <div id="t_and_c_screen" style="display: <?php echo ($show_survey_form)?'none':'block'; ?>" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php


        $allowPay = false;
        if($appointmentData['status'] == 'CANCELED')
        {
            $currDateTime = date("Y-m-d H:i:00");
            $appointmentDatetime = $appointmentData['appointment_datetime'];
            $select = "SELECT COUNT(`id`) as `total` FROM `appointment_customer_staff_services` WHERE `children_id` = '".$appointmentData['children_id']."' AND `appointment_customer_id` = '".$appointmentData['appointment_customer_id']."' AND `status` IN ('NEW', 'CONFIRM', 'RESCHEDULE') AND `booking_date` = '".$appointmentData['booking_date']."' LIMIT 1";
            $totalRS = $connection->query($select);
            $totalData = mysqli_fetch_assoc($totalRS);
            if ($totalData['total'] > 1) {
                echo "<h1>You have already booked a token.</h1>";
            } else {
                $allowPay = true;
            }
        }
        else
        {
            $allowPay = true;
        }


        if($appointmentData['is_paid_booking_convenience_fee'] == 'YES'){

            $prm = array(
            "bookingConvenienceFee"=>$appointmentData['order_amount'],
            "datetime"=>$appointmentData['appointment_datetime'],
            "queue"=>$appointmentData['queue_number'],
            "tx_time"=>$appointmentData['tx_time'],
            "is_online_consulting"=>$appointmentData['is_online_consulting'],
            "convenience_for"=>($appointmentData['consulting_type']=='OFFLINE')?'OFFLINE':'ONLINE'
            );
            $prm = base64_encode(json_encode($prm));
            $redirect_url = $baseUrl."booking_convenience/success.php?prm=".$prm;

        }
        else if($allowPay == true)
        {


            $orderNote = "Convenience Fee";

            $customerName = trim($appointmentData['name']);
            $customerPhone = trim($appointmentData['mobile']);
            $email = $thinappID."_engage@mengage.in";
            $customerEmail = trim(!empty($appointmentData['email'])?$appointmentData['email']:$email);
            $tc = $appointmentData['booking_convenience_fee_terms_condition'];
            $is_online_consulting = $appointmentData['is_online_consulting'];
            $consulting_fee = $service_amount = $appointmentData['consulting_fee'];

            $orderAmount = $convenience_fee= $appointmentData['booking_convenience_fee'];
            $convence_for = "OFFLINE";

            $orderNote ="$convenience_fee@$consulting_fee";
            if($appointment_type == "ONLINE" ){
                $orderNote = "Consulting and Convenience Fee";
                $tc = $appointmentData['online_t_c'];
                $convence_for = "ONLINE";
                $lbl_consulting_type =$appointmentData['consulting_type'];
                if($appointmentData['consulting_type']=='VIDEO'){
                    $consulting_fee =  $appointmentData['video_consulting_amount'];
                    $convenience_fee =  $appointmentData['booking_convenience_fee_video'];
                }else if($appointmentData['consulting_type']=='AUDIO'){
                    $consulting_fee = $appointmentData['audio_consulting_amount'];
                    $convenience_fee = $appointmentData['booking_convenience_fee_audio'];
                }else if($appointmentData['consulting_type']=='CHAT'){
                    $consulting_fee = $appointmentData['chat_consulting_amount'];
                    $convenience_fee = $appointmentData['booking_convenience_fee_chat'];
                }

                if(empty($consulting_fee)){
                   // $consulting_fee = $service_amount;
                }

                if($appointmentData['booking_validity_attempt'] == 1){
                    $orderAmount = $consulting_fee + $convenience_fee;
                }
                $orderNote = "$lbl_consulting_type@$convenience_fee@$orderAmount";


            }else{
                if (Custom::check_app_enable_permission($thinappID, 'SMART_CLINIC')){
                    $orderAmount = $convenience_fee;
                    if($appointmentData['emergency_appointment']=='YES'){
                        $consulting_fee = $appointmentData['emergency_appointment_fee'];
                        $convenience_fee = $appointmentData['booking_convenience_fee_emergency'];
                        $orderAmount = $consulting_fee+$convenience_fee;
                    }else{
                        if($appointmentData['pay_clinic_visit_fee_online']=='YES' && $appointmentData['booking_validity_attempt']==1){
                            $orderAmount = $consulting_fee + $convenience_fee;
                        }
                    }

                }else{
                    $orderAmount = $consulting_fee;
                    $orderNote = "Consulting Fee";
                }


                $orderNote = "OFFLINE@$convenience_fee@$orderAmount";


            }
            $currDate = date('Y-m-d H:i:s');
            if (Custom::check_app_enable_permission($thinappID, 'SMART_CLINIC')){
                $sql = "select id from booking_convenience_orders where appointment_customer_staff_service_id = $appointmentID limit 1";
                $list = $connection->query($sql);
                if (!$list->num_rows) {
                    $query = "INSERT INTO booking_convenience_orders (thinapp_id, appointment_customer_staff_service_id, order_id, amount, appointment_customer_id, children_id, status, convence_for, created, modified) VALUES(?,?,?,?,?,?,?,?,?,?)";
                    $connection = ConnectionUtil::getConnection();
                    $stmt = $connection->prepare($query);
                    $status = 'ACTIVE';
                    $stmt->bind_param('ssssssssss', $thinappID, $appointmentID,$orderId, $orderAmount, $appointmentData['appointment_customer_id'],$appointmentData['children_id'], $status,$convence_for, $currDate, $currDate);
                    $stmt->execute();
                }
            }

            ?>

            <form style="display:none;" id="redirectForm" method="post" action="form_post.php">
                <input type="hidden" name="merchantTransactionId" value="<?php echo $orderId; ?>"/>
                <input type="hidden" name="merchantUserId" value="<?php echo $customerPhone; ?>"/>
                <input type="hidden" name="thin_app_id" value="<?php echo $thinappID; ?>"/>
                <input type="hidden" name="mode" value="<?php echo $mode; ?>"/>
                <input type="hidden" name="amount" value="<?php echo $orderAmount; ?>"/>
                <input type="hidden" name="postUrl" value="<?php echo $postUrl; ?>"/>
                <input type="hidden" name="callbackUrl" value="<?php echo $NOTIFY_URL; ?>"/>
                <input type="hidden" name="returnkUrl" value="<?php echo $RETURN_URL; ?>"/>
                <input type="hidden" name="mobileNumber" value="<?php echo $customerPhone; ?>"/>
             </form>

            <?php $tc_display = 'none';

            if($tc != '') {
                $tc_display ='block';
            }

            ?>
            <div style="display: <?php echo $tc_display; ?>" class="terms-block">
                <div class="terms" style="white-space: pre-line">
                    <h3 class="t_and_c_heading">CONSENT FORM</h3>
                    <div class="t_and_c_body">
                        <?php if($tc != ''){
                            echo $tc;
                        } ?>
                    </div>
                </div>
                <div class="accept-btn-holder">
                    <button type="button"  class="accept-btn" >I AGREE</button>
                </div>
            </div>

        <?php }else if($allowPay == false){ ?>
            <h1>Something Went Wrong!</h1>
        <?php } ?>
    </div>

</div>


</body>

<script>
    $(function () {

        var $redirect_url = "<?php echo $redirect_url; ?>";
        var $tc_display = "<?php echo $tc_display; ?>";
        if($redirect_url!=''){
            window.location.href = $redirect_url;
        }


        var skip =  false;
        $("#survey_screen_english").addClass("data_container_box");

        var app_id =  "<?php echo @base64_encode($appointmentID); ?>";
        var baseurl =  "<?php echo $baseUrl; ?>";
        var show_survey_form =  "<?php echo ($show_survey_form)?'YES':'NO'; ?>";

        function termAndCondition(){
            if(show_survey_form=='YES' && skip == false){
                var res = JSON.stringify(getAnswer());
                $.ajax({
                    url: baseurl+'/tracker/addSurveyData',
                    data:{ai:app_id,res:res},
                    type:'POST',
                    success: function(result){}
                });
            }
            $("#t_and_c_screen").show();
            $("#survey_screen").hide();
        }


        $(document).on("click",".accept-btn",function () {
            $(this).button('loading').html('Please Wait..');
            $("#redirectForm").submit();
        });

        if($tc_display=='none'){
            $(".accept-btn").trigger('click');
        }



        $(document).on("click",".continue",function () {
            termAndCondition();
        });

        $(document).on("click",".skip_btn",function () {
            skip =true;
            termAndCondition();
        })

        $(document).on("click",".action_btn",function () {
            skip = false;

            $(".display_content").hide();
            var container_id ='';
            if($(this).attr('select-lan')=='ENGLISH'){
                $("#survey_screen_english").addClass("data_container_box");
                $("#survey_screen_hindi").removeClass("data_container_box");
                container_id = 'survey_screen_english';
            }else if($(this).attr('select-lan')=='HINDI'){
                $("#survey_screen_english").removeClass("data_container_box");
                $("#survey_screen_hindi").addClass("data_container_box");
                container_id = 'survey_screen_hindi';
            }else{
                container_id = $(this).closest('.data_container_box').attr('id');
            }


            var total_checked =  $(this).closest(".display_content").find(":checked").length;
            var symptoms='';
            if($("#"+container_id+" #step_3").find(":checked").length==1){
                symptoms = $("#"+container_id+" #step_3").find(":checked").val();
            }

            var value =  $(this).closest(".display_content").find(":checked").val();
            var id = $(this).attr('data-next');
            var last_button = $(this).attr('data-last');
            if(total_checked==1){
                var report_id = $(this).closest(".display_content").find(":checked").attr('data-report');
                if(report_id && report_id!=''){
                    id = report_id;
                }
            }

            if(last_button =='YES'){
                if(symptoms == 'None of the above' || symptoms == 'इनमे से कोई भी नहीं'){
                    id = "report_3";
                    if(value=="Yes" || value =='हाँ'){$("#"+container_id+' #report_3 .extra_visible').show(); }
                }else{
                    id = "report_4";
                    if(value=="Yes" || value =='हाँ'){$("#"+container_id+' #report_4 .extra_visible').show(); }
                }
                showHeader(id,container_id);
            }else{
                showHeader(id,container_id);
            }



        });

        function getAnswer(){
            var final = [];
            var obj = {};
            $(".data_container_box .display_content").each(function () {
                var question = $(this).find('.question').text();
                var sub_title = $(this).find('.sub_title').html();
                var option = [];
                $(this).find('.option input').each(function (index, obj) {
                        option.push($(obj).val());
                });
                if(question){
                    var answer_array = [];
                    $(this).find("input").each(function (indec, value) {
                        if($(this).is(":checked")){
                            answer_array.push($(this).val());
                        }
                    });
                    obj = {'question':question,'ans':answer_array,'sub_title':sub_title,'option':option};
                    final.push(obj);
                }
            });

            var container_id = $('.data_container_box').attr('id');
            var result_1 ="#"+container_id+" #report_1";
            var result_2 ="#"+container_id+" #report_2";
            var result_3 ="#"+container_id+" #report_3";
            var result_4 ="#"+container_id+" #report_4";

            var result = "";
            if($(result_1).is(":visible")){
                    result = $(result_1).find('.result_content').html();
            }else if($(result_2).is(":visible")){
                result = $(result_2).find('.result_content').html();
            }else if($(result_3).is(":visible")){
                result = $(result_3).find('.result_content').html();
            }else if($(result_4).is(":visible")){
                result = $(result_4).find('.result_content').html();
            }

            var res_obj = {'result':result};
            final.push(res_obj);
            return final;
        }

        $(document).on("click","input",function () {
            var total_checked =  $(this).closest(".display_content").find(":checked").length;
            var single_checked =  $(this).attr("data-single");
            var current_value = $(this).val();
            var btn = $(this).closest(".display_content").find('.action_btn');
            if(total_checked > 0){
                $(btn).attr('disabled',false);
            }else{
                $(btn).attr('disabled',true);
            }
            if(single_checked=='YES'){
                $(this).closest(".display_content").find('.input_box').each(function (index,value) {
                    if(current_value  != $(this).val() ){
                        $(this).attr('checked',false);
                    }
                });
            }else{
                $(this).closest(".display_content").find('.input_box').each(function (index,value) {
                    if($(this).attr('data-single')=='YES'){
                        $(this).attr('checked',false);
                    }
                });
            }
        });

        $(document).on("click",".prev",function () {
            $(".display_content").hide();
            var id = $(this).attr('data-prev');
            var container_id = $(this).closest('.data_container_box').attr('id');
            showHeader(id,container_id);
        });

        $(document).on("click",".done",function () {
            $(".display_content").hide();
            var id = "report_5";
            var container_id = $(this).closest('.data_container_box').attr('id');
            showHeader(id,container_id);

        });

        $(document).on("click",".cancel",function () {
            $('#reset_btn').trigger('click');
            $(".click_able_btn").attr('disabled',true);
            $(".display_content").hide();
            var container_id = $(this).closest('.data_container_box').attr('id');
            showHeader("step_0",container_id);
        });

        function showHeader(id,container_id){

            if(id!='step_0'){
                if(id =='report_3' || id =='report_4'){
                    $('.girl, .corona').hide();
                    $('.report').show();
                }else{
                    $('.girl, .report').hide();
                    $('.corona').show();
                }


                $("#"+container_id+" #"+id).show();
            }else{
                $('.girl').show();
                $('.corona, .report').hide();
                $("#step_0").show();
            }





        }

    })
</script>


</html>