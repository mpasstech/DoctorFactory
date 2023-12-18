<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
    $response = array();
    $connection = ConnectionUtil::getConnection();
	$curTime = date('Y-m-d H:i:s');
	
	$chkSendSql = "SELECT `id` FROM `contests` WHERE `sent_push_notification` = 'NO' AND `start_time` <= '$curTime' AND `end_time` >= '$curTime' LIMIT 1";
	$chkSendRS = $connection->query($chkSendSql);
    if ($chkSendRS->num_rows) {
		
		$funTypeSql = "SELECT `id` FROM `app_functionality_types` WHERE `label_key` = 'CONTEST' AND `status` = 'Y' LIMIT 1";
		$funTypeRS = $connection->query($funTypeSql);
		if ($funTypeRS->num_rows) {
			$funTypeData = mysqli_fetch_assoc($funTypeRS);
			$funID = $funTypeData['id'];
			
			$enableThinappSql = "SELECT `thinapp_id` FROM `app_enable_functionalities` WHERE `app_functionality_type_id` = '$funID'";
			$enableThinappRS = $connection->query($enableThinappSql);
			if ($enableThinappRS->num_rows) {
				$thinappIDs = array();
				while($enableThinappData = mysqli_fetch_assoc($enableThinappRS))
				{
					$thinappIDs[] = $enableThinappData['thinapp_id'];
				}
				
				$thinappIDs = "('".implode("','",$thinappIDs)."')";
				$condition = "u.thinapp_id IN $thinappIDs AND t.id IN $thinappIDs ";
				
				$query = "select u.firebase_token,t.id as app_id from users as u join thinapps as t on  u.thinapp_id = t.id  where $condition";
				$service_message_list = $connection->query($query);
				if ($service_message_list->num_rows) {
					$all_token_array =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
						
					$option = array(
                        'thinapp_id' => 0,
                        'channel_id' => 0,
                        'role' => "USER",
                        'flag' => 'CONTEST',
                        'title' => "New Contest Update.",
                        'message' => "New Contest Updated. Click to play and win!",
                        'description' => "",
                        'chat_reference' => '',
                        'module_type' => 'CONTEST',
                        'module_type_id' => 0,
                        'firebase_reference' => ""
                    );	
						
						
					$query = "UPDATE `contests` SET `sent_push_notification` = 'YES' WHERE `sent_push_notification` = 'NO'";
					$connection->query($query);	
					
					
					$final_array=array();
					foreach ($all_token_array as $key => $token_data){
						$final_array[$token_data['app_id']][] = $token_data['firebase_token'];
					}
					foreach ($final_array as $key_thin_app_id =>$token_array){
						$option['thinapp_id'] = $key_thin_app_id;
						Custom::send_notification_via_token($option,$token_array,$key_thin_app_id);
					}
				}
				
			}			
		}
		
	}
	
    exit();


