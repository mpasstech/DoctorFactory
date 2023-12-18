<?php


$postData = $_REQUEST;
$appointment_id = isset($postData['ai'])?$postData['ai']:0;
if(!empty($appointment_id)){

    include_once "../webservice/ConnectionUtil.php";
    include_once "../webservice/WebservicesFunction.php";

    $response = array();
    $connection = ConnectionUtil::getConnection();
    $query = "SELECT staff.mobile,staff.user_id,staff.thinapp_id FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id WHERE acss.id = $appointment_id limit 1";
    $subscriber = $connection->query($query);
    if ($subscriber->num_rows) {
        $user_data = mysqli_fetch_assoc($subscriber);
        $post=array(
            'thin_app_id'=>$user_data['thinapp_id'],
            'app_key'=>'MBROADCAST',
            'user_id'=>$user_data['user_id'],
            'mobile'=>$user_data['mobile'],
            'to_mobile'=>$postData['Called'],
            'telemedicine_lead_id'=>$appointment_id,
            'module_type'=>'APPOINTMENT',
            'sid'=>$postData['CallSid']
        );

       $response = json_decode(WebservicesFunction::log_audio_call_connect($post),true);
       if($response['status']==1){
            $response = json_decode(WebservicesFunction::log_audio_call_disconnect($post),true);
            echo $response['message'];die;
        }
    }
}
die;
