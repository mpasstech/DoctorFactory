<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();

$current_data = date('Y-m-d');
$query = "select * from festivals where DATE(festival_date) = '$current_data'";
$connection = ConnectionUtil::getConnection();
$festive_list = $connection->query($query);
if ($festive_list->num_rows) {
    $festive_list =  mysqli_fetch_all($festive_list,MYSQLI_ASSOC);
    foreach ($festive_list as $fes_key => $festive){
        $query = "select u.firebase_token,t.id as app_id from users as u join thinapps as t on  u.thinapp_id = t.id  where t.category_name IN('DOCTOR','HOSPITAL') and u.app_installed_status = 'INSTALLED'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $all_token_array =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $final_array=array();
            foreach ($all_token_array as $key => $token_data){
                $final_array[$token_data['app_id']][] = $token_data['firebase_token'];
            }
            foreach ($final_array as $key_thin_app_id =>$token_array){
                $thin_app_id = $key_thin_app_id;
                $option = array(
                    'thinapp_id' => $thin_app_id,
                    'channel_id' => 0,
                    'role' => "USER",
                    'flag' => 'CUSTOM_MESSAGE',
                    'title' =>"Wishing Message",
                    'message' => $festive['message'],
                    'description' => $festive['message'],
                    'chat_reference' => '',
                    'module_type' => "CUSTOM_MESSAGE",
                    'module_type_id' => 0,
                    'firebase_reference' => "",
                    'show_message_to'=>"ALL"
                );
                Custom::send_notification_via_token($option,$token_array,$key_thin_app_id);
            }
            $response['status'] = 1;
            $response['message'] = "Notification Send Successfully";
            Custom::sendResponse($response);
        }else {
            $response['status'] = 0;
            $response['message'] = "No vaccination list found";
        }
    }
}else {
    $response['status'] = 0;
    $response['message'] = "No wishing day found";
}




Custom::sendResponse($response);
exit();


