<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$query = "select t.id as thin_app_id, u.app_installed_status, t.birthday_sms_template, c.child_name, IFNULL(u.mobile,c.mobile) as mobile, t.name as app_name, u.firebase_token from childrens as c  join thinapps as t on t.id = c.thinapp_id left join users as u on ( u.mobile = c.mobile OR u.mobile = c.parents_mobile ) and u.thinapp_id = c.thinapp_id left join user_enabled_fun_permissions as uefp on uefp.thinapp_id = c.thinapp_id and uefp.permission ='YES' AND uefp.user_functionality_type_id = 47 where uefp.permission IS NOT NULL and MONTH(c.dob) = MONTH(NOW()) and DAY(c.dob) = DAY(NOW())";
$subscriber = $connection->query($query);
if ($subscriber->num_rows) {
	$message_data =array();
	$menu_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
	$counter=0;
	foreach($menu_list as $key => $data){
		$message = !empty($data['birthday_sms_template'])?$data['birthday_sms_template']:BIRTHDAY_DEFAULT_TEMPLATE;
		$message = str_ireplace("#APP", $data['app_name'],$message);
		$message = str_ireplace("#CHILD", $data['child_name'],$message);
		if (!empty($data['firebase_token'])) {
			$option = array(
				'thinapp_id' => $data['thin_app_id'],
				'channel_id' => 0,
				'role' => "USER",
				'flag' => 'BIRTHDAY_WISH',
				'title' => "Birthday Wish",
				'message' => $message,
				'description' => $message,
				'chat_reference' => '',
				'module_type' => "BIRTHDAY_WISH",
				'module_type_id' => 0,
				'firebase_reference' => ""
			);
			Custom::send_notification_via_token($option, array($data['firebase_token']), $data['thin_app_id']);

		}

		if (!empty($data['mobile']) && !empty($message)) {
			if($data['app_installed_status'] == "UNINSTALLED" || empty($data['app_installed_status']) ){
				Custom::send_single_sms($data['mobile'],$message,$data['thin_app_id']);

			}
		}
	}
	$response['status'] = 1;
	$response['message'] = "Notification and sms Send";
	Custom::sendResponse($response);

} else {
	$response['status'] = 0;
	$response['message'] = "No birthday guys found";
}
Custom::sendResponse($response);
exit();


