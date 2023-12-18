<?php
/*************CREATED BY VISHWAJEET**************/
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$connection = ConnectionUtil::getConnection();
$today = date("Y-m-d");
$query = "SELECT `users`.`firebase_token`,`users`.`thinapp_id` FROM `users`
 RIGHT JOIN `thinapps` ON (`thinapps`.`id` = `users`.`thinapp_id`)
 RIGHT JOIN `medicine_reminder` ON (`medicine_reminder`.`user_id` = `users`.`id`)
 WHERE `medicine_reminder`.`start_date` <= '".$today."' AND 
 (`medicine_reminder`.`end_date` >= '".$today."' OR `medicine_reminder`.`is_forever` = 'YES')
 AND `medicine_reminder`.`remind_at_morning` = 'YES' AND `medicine_reminder`.`status` = 'ACTIVE'
 AND `users`.`app_installed_status` = 'INSTALLED' AND `thinapps`.`status` = 'ACTIVE'
 GROUP BY `medicine_reminder`.`user_id`";
$totalData = $connection->query($query);

if($totalData->num_rows)
{
	$dataToSend = array();
	$dataList = mysqli_fetch_all($totalData, MYSQLI_ASSOC);
	foreach($dataList AS $data)
	{
		$dataToSend[$data['thinapp_id']][] = $data['firebase_token'];
	}
	
	foreach($dataToSend AS $thinappID => $firebaseTokenArr)
	{
		$option = array(
			   'thinapp_id' => $thinappID,
			   'channel_id' => 0,
			   'role' => "USER",
			   'flag' => 'MEDICINE_REMINDER',
			   'title' => "Morning Medicine Reminder",
			   'message' => "It's Right Time To Take Your Morning Medicine!",
			   'description' => "Morning medicine reminder",
			   'chat_reference' => '',
			   'module_type' => "MEDICINE_REMINDER",
			   'module_type_id' => 0,
			   'firebase_reference' => ""
			  );
		Custom::send_notification_via_token($option,$firebaseTokenArr,$thinappID);
	}
	
}
die;