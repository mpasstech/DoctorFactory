<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$tracker_data = Custom::get_appointment_tracker_data(0, $request_from = "CRON_JOB");
if(!empty($tracker_data)){
    $current_minutes = Custom::convertTimeToMinutes(date('H:i'),false);
    foreach($tracker_data as $doctor_id => $data){
        $service_slot_duration = $data['service_slot_duration'];
        $new_time = date('H:i',strtotime('+ '.$service_slot_duration,strtotime('2000-01-01 00:00:00')));
        $duration_minutes = Custom::convertTimeToMinutes($new_time,false);

        if($current_minutes%$duration_minutes==0){
            $thin_app_id = $data['thinapp_id'];
            $token_array = Custom::get_upcoming_appointment_user_token($thin_app_id,$doctor_id,$data['address_id'],0);
            if(!empty($token_array)){
                $option = array(
                    'thinapp_id' => $thin_app_id,
                    'staff_id' => 0,
                    'customer_id' => 0,
                    'service_id' => 0,
                    'channel_id' => 0,
                    'role' => "CUSTOMER",
                    'flag' => 'APPOINTMENT_TRACKER',
                    'title' => "New Tracker Request",
                    'message' => "Your tracker message",
                    'description' => "Your tracker message",
                    'chat_reference' => '',
                    'module_type' => 'APPOINTMENT_TRACKER',
                    'module_type_id' => 0,
                    'firebase_reference' => ""
                );
                Custom::send_notification_via_token($option,$token_array,$thin_app_id);

            }
        }
    }
    $response['status'] = 1;
    $response['message'] = "Notification send";
} else {
    $response['status'] = 0;
    $response['message'] = "No tracker list found";
}
Custom::sendResponse($response);
exit();


