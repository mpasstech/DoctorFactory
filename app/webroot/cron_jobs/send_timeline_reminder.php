<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$current_data = date('Y-m-d');
$query = "select  u.thinapp_id, u.username, u.firebase_token, c.child_name, ctm.parent_timeline_date from child_timeline_media as ctm join childrens as c on c.id = ctm.children_id and c.`status` ='ACTIVE'   right join users as u on (u.mobile = c.mobile OR u.mobile = c.parents_mobile) and c.thinapp_id = u.thinapp_id where   ctm.`status` = 'ACTIVE' and ctm.update_status = 'PENDING' AND DATEDIFF(DATE(NOW()),DATE(ctm.modified)) IN(7,14,21) group by c.id order by ctm.id desc ";
$connection = ConnectionUtil::getConnection();
$service_message_list = $connection->query($query);
if ($service_message_list->num_rows) {
    $final_array =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
    foreach ($final_array as $key_thin_app_id =>$child){
        $message = "Your baby ".$child['child_name']." is growing up,kindly store your baby ".$child['child_name']."'s memories in timeline";
        $thin_app_id = $child['thinapp_id'];
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
            'show_message_to'=>"ALL"
        );
        Custom::send_notification_via_token($option,array($child['firebase_token']),$thin_app_id);
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


