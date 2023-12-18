<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$date = date("Y-m-d");

$query = "SELECT (SUM(`medical_product_orders`.`refund_amount`)+SUM(`hospital_ipd_settlements`.`total_refund`)) AS `total_refund`,SUM(IF(`medical_product_orders`.`refund_amount` > 0 OR `hospital_ipd_settlements`.`total_refund` > 0,1,0)) AS `total_refund_count`,`users`.`mobile` AS `mobile`,`medical_product_orders`.`thinapp_id` AS `thinapp_id`, (SUM( IF(`medical_product_orders`.`hospital_ipd_settlement_id` = 0 , (IF(`medical_product_orders`.`payment_status` = 'PAID',(`medical_product_orders`.`total_amount`+`medical_product_orders`.`refund_amount`),0)), (IF(`medical_product_orders`.`is_settlement` = 'Y', (IF(`hospital_ipd_settlements`.`payment_status` = 'RECEIVED',`hospital_ipd_settlements`.`settlement_amount`,0)), (IF(`medical_product_orders`.`payment_status` = 'PAID',`medical_product_orders`.`total_amount`,0)) )) ) ) - SUM(`hospital_ipd_settlements`.`total_rebate_amount`)) AS `total_received` FROM `medical_product_orders` LEFT JOIN `hospital_ipd_settlements` ON (`hospital_ipd_settlements`.`id` = `medical_product_orders`.`hospital_ipd_settlement_id`) LEFT JOIN `users` ON (`medical_product_orders`.`thinapp_id` = `users`.`thinapp_id`) WHERE `users`.`role_id` = 5 AND `medical_product_orders`.`status` = 'ACTIVE' AND DATE(`medical_product_orders`.`created`) = '".$date."' GROUP BY `medical_product_orders`.`thinapp_id`";
$dataRS = $connection->query($query);
while($data = mysqli_fetch_assoc($dataRS))
{
	$message = "Greetings from MEngage\nYour todays total collection :- ".$data['total_received']."\nYour todays total refund :- ".$data['total_refund']."\nTotal number of refuds :- ".$data['total_refund_count'];
	Custom::send_single_sms($data['mobile'],$message,$data['thinapp_id'],false,false);
}

$query = "SELECT COUNT(`appointment_customer_staff_services`.`id`) AS `total_appointment`,SUM(IF(`appointment_customer_staff_services`.`payment_status` = 'SUCCESS',1,0)) AS `total_paid_appointment`, COUNT(`users`.`id`) AS `total_users`,`admin`.`mobile` AS `mobile`,`thinapps`.`id` AS `thinapp_id` FROM `thinapps` LEFT JOIN `appointment_customer_staff_services` ON(`appointment_customer_staff_services`.`thinapp_id` = `thinapps`.`id`) LEFT JOIN `users` ON (`users`.`thinapp_id` = `thinapps`.`id`) LEFT JOIN `users` AS `admin` ON (`admin`.`thinapp_id` = `thinapps`.`id`) WHERE DATE(`appointment_customer_staff_services`.`appointment_datetime`) = '".$date."' AND DATE(`users`.`created`) = '".$date."' GROUP BY `thinapps`.`id`";

$dataRS = $connection->query($query);
while($data = mysqli_fetch_assoc($dataRS))
{
	if($data['total_appointment'] > 0)
	{
	$message = "Greetings from MEngage\nYour todays new downloads :- ".$data['total_users']."\nYour todays total appointments :- ".$data['total_appointment']."\nTotal paid appointments :- ".$data['total_paid_appointment'];
	Custom::send_single_sms($data['mobile'],$message,$data['thinapp_id'],false,false);	
	}
	
}


exit();


