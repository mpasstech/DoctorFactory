<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$current_data = date('Y-m-d');
$query = "select u.thinapp_id, u.firebase_token from users as u join thinapps as t on t.id = u.thinapp_id and t.category_name IN('DOCTOR','HOSPITAL') AND t.status ='ACTIVE' where u.status = 'Y' and u.app_installed_status = 'INSTALLED' AND u.is_verified='Y'";
$connection = ConnectionUtil::getConnection();
$service_message_list = $connection->query($query);
if ($service_message_list->num_rows) {

    $all_token_array =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
    $final_array=array();
    $message = "Store medical data lifelong in your medical records folder to \n 1. Access 24X7 .\n2. Do not worry about data lose. \n3. Share instantly via mobile number.\n4. Ultra fast and secure.\n5. Maintain patient privacy. \n6. Store on cloud.";
    foreach ($all_token_array as $key => $token_data){
        $final_array[$token_data['thinapp_id']][] = $token_data['firebase_token'];
    }
    foreach ($final_array as $key_thin_app_id =>$token_array){
        $thin_app_id = $key_thin_app_id;
        $option = array(
            'thinapp_id' => $key_thin_app_id,
            'channel_id' => 0,
            'role' => "USER",
            'flag' => 'CUSTOM_MESSAGE',
            'title' =>"Wishing Message",
            'message' => $message,
            'description' => $message,
            'chat_reference' => '',
            'module_type' => "CUSTOM_MESSAGE",
            'module_type_id' => 0,
            'firebase_reference' => "",
            'show_message_to'=>"USER"
        );
        Custom::send_notification_via_token($option,$token_array,$key_thin_app_id);
    }
    $response['status'] = 1;
    $response['message'] = "Notification Send Successfully";
    Custom::sendResponse($response);


}else {
    $response['status'] = 0;
    $response['message'] = "No timeline list found";
}
Custom::sendResponse($response);
exit();


