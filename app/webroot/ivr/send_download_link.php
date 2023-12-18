<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$call_sid = isset($_GET['CallSid']) ? $_GET['CallSid'] : 0;
$from = isset($_GET['From']) ? Custom::create_mobile_number($_GET['From']) : "";
$to = isset($_GET['To']) ? $_GET['To'] : "";
$doctor_ivr_data = Custom::get_doctor_ivr_data($to);
foreach ($doctor_ivr_data as $doctor_id =>$ivr_data){
        $app_data = Custom::getThinAppData($ivr_data['thinapp_id']);
        $doctor_data = Custom::get_doctor_by_id($doctor_id);
        $apk_url = $app_data['apk_url'];
        $message = "Save Link to Pre-book your Doctor Token from Home for Telemedicine\nLink: $apk_url";
        if(Custom::send_single_sms($from,$message,$doctor_data['thinapp_id'],false,false)){
            die('SUCCESS');
        }else{
            die('FAIL');
        }

}
exit();


