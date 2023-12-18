<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$connection = ConnectionUtil::getConnection();
$messageQueRs = $connection->query("SELECT * FROM `sms_queues` WHERE `cron_status` = 'NEW' OR `cron_status` = 'FAILED'");
while($messageQueData = mysqli_fetch_assoc($messageQueRs))
	{
				try {
						$currTime = date("Y-m-d H:i:s");
						$connection->query("UPDATE `sms_queues` SET `cron_status` = 'INPROGRESS', `cron_start_time` = '".$currTime."' WHERE `id` = '".$messageQueData['id']."'");
						$connection->autocommit(false);
						$connection->begin_transaction();		

						///$jsonData = file_get_contents($messageQueData['tmp_file_path']);
						$myfile = fopen($messageQueData['tmp_file_path'], "r");
						$jsonData=  fread($myfile,filesize($messageQueData['tmp_file_path']));
						$sms_type = $messageQueData['sms_type'];
						$dataToInsert = json_decode($jsonData, true);
						$data = array();
						$n = 0;
						foreach($dataToInsert as $value)
						{
							$data = array_chunk($value,30);
							foreach($data as $dataToSave)
							{
								$arrToSave = array();
								foreach($dataToSave as $key=>$inData)
								{
									if($inData['status'] != 'success'){ $n++; }
									$arrToSave[] = "('".$messageQueData['message_id']."','".$messageQueData['sent_by']."','+".$inData['phone']."','".$messageQueData['channel_id']."','".$messageQueData['thinapp_id']."','".strtoupper($inData['status'])."','".$messageQueData['message_text']."','APP','GUPSHUP','".$messageQueData['id']."','".$inData['details']."','".$currTime."','".$currTime."')";
								}
								$strToSave = implode(",",$arrToSave);							
								$connection->query("INSERT INTO `sent_sms_details` (`message_id`,`sender_id`,`receiver_mobile`,`channel_id`,`thinapp_id`,`status`,`message_text`,`sent_via`,`router_name`,`sms_response_id`,`response_detail`,`created`,`modified`) VALUES ".$strToSave);
							}
						}

						if($sms_type=="PROMOTIONAL"){
							$connection->query("UPDATE `app_sms_statics` SET `total_promotional_sms` = `total_promotional_sms`+$n WHERE `thinapp_id` = '".$messageQueData['thinapp_id']."'");
						}else{
							$connection->query("UPDATE `app_sms_statics` SET `total_transactional_sms` = `total_transactional_sms`+$n WHERE `thinapp_id` = '".$messageQueData['thinapp_id']."'");
						}

						$currTime = date("Y-m-d H:i:s");
						$connection->query("UPDATE `sms_queues` SET `cron_status` = 'COMPLETED', `cron_end_time` = '".$currTime."' WHERE `id` = '".$messageQueData['id']."'");
						$connection->commit();
				}
				catch (Exception $e){
					 $exceptionMessage = $e->getMessage();
					 $connection->rollback();
					 $currTime = date("Y-m-d H:i:s");
					 $connection->query("UPDATE `sms_queues` SET `cron_status` = 'FAILED', `cron_end_time` = '".$currTime."', `exception_message` = '".$exceptionMessage."' WHERE `id` = '".$messageQueData['id']."'");
				}

	}
?>