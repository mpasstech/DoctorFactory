<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$query = "select u.thinapp_id, u.username, u.firebase_token, t.name,datediff(DATE(NOW()),DATE(m.modified)) as last_used from messages as m  left join  messages as in_msg on m.thinapp_id = in_msg.thinapp_id and m.id < in_msg.id join thinapps as t on t.id = m.thinapp_id and t.category_name != 'OTHER' AND t.status = 'ACTIVE' left join users as u on u.role_id = 5  and u.thinapp_id = m.thinapp_id where datediff(DATE(NOW()),DATE(m.created)) > 2 and in_msg.id IS NULL GROUP by m.thinapp_id";
$connection = ConnectionUtil::getConnection();
$festive_list = $connection->query($query);
if ($festive_list->num_rows) {
    $festive_list =  mysqli_fetch_all($festive_list,MYSQLI_ASSOC);
    foreach ($festive_list as $fes_key => $user_data){
        $thin_app_id = $user_data['thinapp_id'];
        $message = "It has been ".$user_data['last_used']." days, you haven't post on doctor's blog so kindly post to Engage your patients";
        $option = array(
            'thinapp_id' => $thin_app_id,
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
            'show_message_to'=>"ADMIN"
        );

        Custom::send_notification_via_token($option,array($user_data['firebase_token']),$thin_app_id);
    }
    $response['status'] = 1;
    $response['message'] = "Notification Send Successfully";
    Custom::sendResponse($response);
}else {
    $response['status'] = 0;
    $response['message'] = "No doctor found";
}

Custom::sendResponse($response);
exit();


