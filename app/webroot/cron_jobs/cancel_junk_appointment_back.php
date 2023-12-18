<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../instamojo-php/src/Instamojo.php";
$response = array();
$thinappArr = array();
$backgroudArr = array();
$connection = ConnectionUtil::getConnection();
$current_data = date('Y-m-d');
$selectIvrData = array();

$sql = "update appointment_customer_staff_services set status=?, cancel_by_user_id =?, cancel_date_time = ?,modified=? where (booking_payment_type = ? and is_paid_booking_convenience_fee =? and payment_by_user_id =? and payment_status =? and created < ?) OR (appointment_booked_from='IVR' AND TIME(DATE_ADD(appointment_datetime,INTERVAL 30 MINUTE)) < TIME(NOW()) AND  )";
$stmt = $connection->prepare($sql);
$status = 'CANCELED';
$created = Custom::created();
$cancel_by_user_id = MBROADCAST_APP_ADMIN_ID;
$booking_payment_type = 'ONLINE';
$payment_status = 'PENDING';
$payment_by_user_id = 0;
$minutes = 8;

$minutesIvr = 10;
$dateCompare = date('Y-m-d H:i:s', strtotime('-'.$minutes.' minutes'));
$dateCompareFrom = date('Y-m-d H:i:s', strtotime('-'.($minutes-1).' minutes'));
$dateCompareIvr = date('Y-m-d H:i:s', strtotime('-'.$minutesIvr.' minutes'));


$NOT_APPLICABLE =  'NOT_APPLICABLE';
$stmt->bind_param('sssssssss',  $status, $cancel_by_user_id, $created, $created, $booking_payment_type, $NOT_APPLICABLE, $payment_by_user_id, $payment_status, $dateCompare);
if ($stmt->execute()) {

    $selectSql = "SELECT `appointment_customer_staff_services`.`thinapp_id`,`appointment_customer_staff_services`.`id`,`appointment_customer_staff_services`.`appointment_datetime`,`appointment_customer_staff_services`.`queue_number`,`appointment_customers`.`first_name` AS `cus_name`,`childrens`.`child_name`,IFNULL(`appointment_customers`.`mobile`,`childrens`.`mobile`) AS `cus_mobile`,`appointment_staffs`.`name` AS `doc_name` FROM `appointment_customer_staff_services` LEFT JOIN `appointment_customers` ON (`appointment_customers`.`id` = `appointment_customer_staff_services`.`appointment_customer_id`) LEFT JOIN `childrens` ON (`childrens`.`id` = `appointment_customer_staff_services`.`children_id`) LEFT JOIN `appointment_staffs` ON (`appointment_staffs`.`id` = `appointment_customer_staff_services`.`appointment_staff_id`) WHERE (`appointment_customer_staff_services`.`appointment_booked_from` = 'APP' OR `appointment_customer_staff_services`.`appointment_booked_from` = 'DOCTOR_PAGE' OR (`appointment_customer_staff_services`.`booking_convenience_fee_restrict_ivr` = 'YES' AND `appointment_customer_staff_services`.`appointment_booked_from` = 'IVR' AND `appointment_customer_staff_services`.`created` < '".$dateCompareIvr."') ) AND `appointment_customer_staff_services`.`is_paid_booking_convenience_fee` = 'NO' and `appointment_customer_staff_services`.`created` < '".$dateCompare."' AND `appointment_customer_staff_services`.`status` IN ('NEW', 'CONFIRM', 'RESCHEDULE')";
    $selectRS = $connection->query($selectSql);


    if($selectRS->num_rows)
    {

        $selectData = mysqli_fetch_all($selectRS,MYSQLI_ASSOC);

        $updateSql = "UPDATE `appointment_customer_staff_services` SET status='".$status."', cancel_by_user_id ='".$cancel_by_user_id."', cancel_date_time = '".$created."',modified= '".$created."' WHERE (`appointment_booked_from` = 'APP' OR `appointment_booked_from` = 'DOCTOR_PAGE' OR (`booking_convenience_fee_restrict_ivr` = 'YES' AND `appointment_booked_from` = 'IVR' AND `appointment_customer_staff_services`.`created` < '".$dateCompareIvr."') ) AND `is_paid_booking_convenience_fee` = 'NO' and `created` < '".$dateCompare."' AND `status` IN ('NEW', 'CONFIRM', 'RESCHEDULE')";
        $connection->query($updateSql);

        foreach($selectData AS $appointment){
            $message = "Hi " . trim($appointment['cus_name']).trim($appointment['child_name']) . ", your token number ".$appointment['queue_number']." on " . date('d-m-Y H:i',strtotime($appointment['appointment_datetime'])) . " with " . trim($appointment['doc_name']) . ' has been canceled';
            $message .= "\nReason : Booking convenience fees not paid.";

            $backgroudArr[] = array('message'=>$message,'mobile'=>$appointment['cus_mobile']);
            $thinappArr[$appointment['thinapp_id']] = '';

        }

    }

    $selectIvrSql = "SELECT `appointment_customer_staff_services`.`thinapp_id`,`appointment_customer_staff_services`.`id` FROM `appointment_customer_staff_services` WHERE (`appointment_customer_staff_services`.`booking_convenience_fee_restrict_ivr` = 'YES' AND `appointment_customer_staff_services`.`appointment_booked_from` = 'IVR') AND `appointment_customer_staff_services`.`is_paid_booking_convenience_fee` = 'NO' AND `appointment_customer_staff_services`.`created` < '".$dateCompareFrom."' and `appointment_customer_staff_services`.`created` > '".$dateCompare."' AND `appointment_customer_staff_services`.`status` IN ('NEW', 'CONFIRM', 'RESCHEDULE')";
    $selectIvrRS = $connection->query($selectIvrSql);

    $selectIvrData = mysqli_fetch_all($selectIvrRS,MYSQLI_ASSOC);

    $response['cancel']['status'] = 1;
    $response['cancel']['message'] = "Data update successfully.";
} else {
    $response['cancel']['status'] = 0;
    $response['cancel']['message'] = "Sorry, unable to update data.";
}


Custom::sendResponse($response);
Custom::send_process_to_background();

foreach($thinappArr AS $thinappID => $val){
    Custom::send_web_tracker_notification($thinappID);
}

foreach($backgroudArr AS $smsData){
    Custom::send_single_sms($smsData['mobile'], $smsData['message'], 134,false,false);
}

foreach($selectIvrData AS $ivrData){

    $appointment_data = WebservicesFunction::get_appointment_all_data_id($ivrData['id']);

    $url = SITE_PATH.'booking_convenience/?token='.base64_encode($ivrData['id']);
    $url =Custom::short_url($url,134);
    $queue_number = Custom::create_queue_number($appointment_data);
    $queue_number = ($appointment_data['show_appointment_token'] == "NO") ? "" : $queue_number;
    $message = "To confirm your token no. ".$queue_number." please pay booking convenience fee by clicking on the following link else token will get cancel in 5 min.\n ".$url;
    Custom::send_single_sms($appointment_data['customer_mobile'], $message, 134, false, false);

}

exit();


