<?php
ignore_user_abort(true);
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$created = Custom::created();
//$created = '2021-05-21 11:00:00';
$query = "SELECT t.logo, acss.id, acss.thinapp_id, acss.sub_token, acss.custom_token, acss.has_token, acss.emergency_appointment,  IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) AS patient_mobile, acss.queue_number,acss.appointment_datetime, acss.consulting_type,staff.mobile AS doctor_number,staff.name AS doctor_name FROM appointment_customer_staff_services AS acss JOIN thinapps as t on t.id = acss.thinapp_id  JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id   WHERE DATE(acss.appointment_datetime) = DATE(NOW())  AND  acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.consulting_type != 'OFFLINE'  AND t.status ='ACTIVE' AND  TIMESTAMPDIFF(MINUTE, '$created', acss.appointment_datetime) BETWEEN '1' AND '30' order by acss.id asc";
$subscriber = $connection->query($query);
if ($subscriber->num_rows) {
    $list_data = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
    foreach ($list_data as $key => $val){
        $patient_mobile = $val['patient_mobile'];
        $patient_name = $val['patient_name'];
        $thin_app_id = $val['thinapp_id'];
        $queue_number = Custom::create_queue_number($val);
        $consulting_type = $val['consulting_type'];
        $doctor_number = $val['doctor_number'];
        $reception_mobile = !empty($val['reception_mobile'])?$val['reception_mobile']:'N/A';
        $date = date('d-m-Y',strtotime($val['appointment_datetime']));
        $time = date('h:i A',strtotime($val['appointment_datetime']));
        $telemedicine_link = '';
        if($consulting_type=='VIDEO'){
            $param = base64_encode($val['id']."##DOCTOR");
            $telemedicine_link = SITE_PATH."homes/video/$param";
            $telemedicine_link = Custom::short_url($telemedicine_link,$thin_app_id);
        }
        if($consulting_type=='AUDIO'){
            $telemedicine_link = "https://mngz.in:3005/?".base64_encode("ai=".$val['id']."&m=".$patient_mobile."&n=".$patient_name."&l=".$val['logo']);
            $telemedicine_link = Custom::short_url($telemedicine_link,$thin_app_id);
        }
        $whats_app_link = Custom::short_url("https://api.whatsapp.com/send?phone=$patient_mobile",$thin_app_id);
        $con_label = (strtolower($consulting_type));
        $message = "PATIENT $consulting_type CALL REMINDER\nName - $patient_name\nToken No - $queue_number     Time -$time\nStart $con_label Call from App Using Link \n$telemedicine_link\nIn case you wish to make $con_label call from whatsapp to use  following link\n$whats_app_link\nNote : It is better to use first link through app to better organise patient interaction";
        $support = Custom::create_mobile_number(SUPPORT_MOBILE);
        Custom::sendWhatsappSms($doctor_number,$message);
    }
    $response['status'] = 1;
    $response['message'] = "What's app Send";

}else{
    $response['status'] = 0;
    $response['message'] = "No list found";
}
Custom::sendResponse($response);
exit();








