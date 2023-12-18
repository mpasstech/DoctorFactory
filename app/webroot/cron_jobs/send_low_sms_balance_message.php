<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$query = "select t.name, ass.id, u.username, u.mobile,ass.total_transactional_sms,ass.shoot_sms_on,ass.thinapp_id,u.firebase_token from app_sms_statics as ass join thinapps as t on t.id = ass.thinapp_id join users as u on ass.thinapp_id = u.thinapp_id and role_id = 5 where ass.total_transactional_sms <= 500 and t.category_name IN ('HOSPITAL','DOCTOR')";
$subscriber = $connection->query($query);
if ($subscriber->num_rows) {
    $send_notification_flag =false;
    $message_data =array();
    $data_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
    $counter=0;
    foreach ($data_list as $key => $value){
        $balance = $value['total_transactional_sms'];
        $last_sms_for = $value['shoot_sms_on'];
        if( ($balance <= 500 && $balance > 100) && $last_sms_for == 500){
            $message = "Dear ".$value['username'].", \nTotal sms remaining balance for your app ".$value['name']." is ".$value['total_transactional_sms'].". Please recharge your sms pack at mEngage website ".SITE_PATH." \n Happy Engagement with your patients !";
            updateSmsStatic($connection,$value['id'],100);
            Custom::send_single_sms($value['mobile'],$message,DOCTOR_FACTORY_APP_ID,true);
            $option = array(
                'thinapp_id' => $value['thinapp_id'],
                'channel_id' => 0,
                'role' => "USER",
                'flag' => 'CUSTOM_MESSAGE',
                'title' =>"Low SMS Reminder",
                'message' => $message,
                'description' => $message,
                'chat_reference' => '',
                'module_type' => "CUSTOM_MESSAGE",
                'module_type_id' => 0,
                'firebase_reference' => "",
                'show_message_to'=>"ADMIN"
            );
            Custom::send_notification_via_token($option,array($value['firebase_token']),$value['thinapp_id']);
        }else if( ($balance <= 100 && $balance > 10 ) && $last_sms_for == 100){
            $message = "Dear ".$value['username'].", \nTotal sms remaining balance for your app ".$value['name']." is ".$value['total_transactional_sms'].". Please recharge your sms pack at mEngage website ".SITE_PATH." \n Happy Engagement with your patients !";
            updateSmsStatic($connection,$value['id'],10);
            Custom::send_single_sms($value['mobile'],$message,DOCTOR_FACTORY_APP_ID,true);
            $message = "Hello MEngage Team\nTotal sms remaining balance for your app ".$value['name']." is ".$value['total_transactional_sms'].". Please concern with app admin soon";
            Custom::send_single_sms(SUPPORT_MOBILE,$message,DOCTOR_FACTORY_APP_ID,false,false);
            $option = array(
                'thinapp_id' => $value['thinapp_id'],
                'channel_id' => 0,
                'role' => "USER",
                'flag' => 'CUSTOM_MESSAGE',
                'title' =>"Low SMS Reminder",
                'message' => $message,
                'description' => $message,
                'chat_reference' => '',
                'module_type' => "CUSTOM_MESSAGE",
                'module_type_id' => 0,
                'firebase_reference' => "",
                'show_message_to'=>"ADMIN"
            );
            Custom::send_notification_via_token($option,array($value['firebase_token']),$value['thinapp_id']);

        }else if( ($balance <= 10 && $balance > 0) && $last_sms_for == 10){
            $message = "Dear ".$value['username'].", \nTotal sms remaining balance for your app ".$value['name']." is ".$value['total_transactional_sms'].". Please recharge your sms pack at mEngage website ".SITE_PATH." \n Happy Engagement with your patients !";
            updateSmsStatic($connection,$value['id'],0);
            Custom::send_single_sms($value['mobile'],$message,DOCTOR_FACTORY_APP_ID,true);
            $message = "Hello MEngage Team\nTotal sms remaining balance for your app ".$value['name']." is ".$value['total_transactional_sms'].". Please concern with app admin soon";
            Custom::send_single_sms(SUPPORT_MOBILE,$message,DOCTOR_FACTORY_APP_ID,false,false);
            $option = array(
                'thinapp_id' => $value['thinapp_id'],
                'channel_id' => 0,
                'role' => "USER",
                'flag' => 'CUSTOM_MESSAGE',
                'title' =>"Low SMS Reminder",
                'message' => $message,
                'description' => $message,
                'chat_reference' => '',
                'module_type' => "CUSTOM_MESSAGE",
                'module_type_id' => 0,
                'firebase_reference' => "",
                'show_message_to'=>"ADMIN"
            );
            Custom::send_notification_via_token($option,array($value['firebase_token']),$value['thinapp_id']);

        }
    }
    /* send message to users */

    $response['status'] = 1;
    $response['message'] = "Notification and sms Send";
    Custom::sendResponse($response);

} else {
    $response['status'] = 0;
    $response['message'] = "No vaccination list found";
}
Custom::sendResponse($response);


function updateSmsStatic($connection,$id,$last_sms){
    $created = Custom::created();
    $sql = "update app_sms_statics set shoot_sms_on =?, modified = ? where id = ?";
    $cm_stmt = $connection->prepare($sql);
    $cm_stmt->bind_param('sss', $last_sms,$created, $id);
    $cm_stmt->execute();
}
exit();


