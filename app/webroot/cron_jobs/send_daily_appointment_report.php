<?php
include_once "../webservice/Custom.php";
$connection = ConnectionUtil::getConnection();
$currentData = date('Y-m-d');


$totalSql = "SELECT COUNT(`id`) AS `total_appointment`,`thinapp_id` FROM `appointment_customer_staff_services` WHERE `booking_date` = '".$currentData."' GROUP BY `thinapp_id` HAVING total_appointment > 0";

$totalRS = $connection->query($totalSql);
$totalData = mysqli_fetch_all($totalRS, MYSQLI_ASSOC);

foreach($totalData AS $val)
{
    $thinappID = $val['thinapp_id'];
    $totalAppointment = $val['total_appointment'];

    $paidSql = "SELECT COUNT(`id`) AS `total_paid` FROM `appointment_customer_staff_services` WHERE `booking_date` = '".$currentData."' AND `thinapp_id` = '".$thinappID."' AND `payment_status` = 'SUCCESS' LIMIT 1";
    $paidRS = $connection->query($paidSql);
    $paidData = mysqli_fetch_assoc($paidRS);
    $paidAppointment = ($paidData['total_paid'] > 0)?$paidData['total_paid']:0;

    $cancelSql = "SELECT COUNT(`id`) AS `total_cancel` FROM `appointment_customer_staff_services` WHERE `booking_date` = '".$currentData."' AND `thinapp_id` = '".$thinappID."' AND `status` = 'CANCELED' LIMIT 1";
    $cancelRS = $connection->query($cancelSql);
    $cancelData = mysqli_fetch_assoc($cancelRS);
    $cancelAppointment = ($cancelData['total_cancel'] > 0)?$cancelData['total_cancel']:0;

    $refundSql = "SELECT COUNT(`id`) AS `total_refund` FROM `appointment_customer_staff_services` WHERE `booking_date` = '".$currentData."' AND `thinapp_id` = '".$thinappID."' AND `status` = 'REFUND' LIMIT 1";
    $refundRS = $connection->query($refundSql);
    $refundData = mysqli_fetch_assoc($refundRS);
    $refundAppointment = ($refundData['total_refund'] > 0)?$refundData['total_refund']:0;


    $message = "Good evening Doctor\nStats of today's OPD \n";
    $message .= "Total appointment:".$totalAppointment."\n";
    $message .= "Total paid:".$paidAppointment."\n";
    $message .= "Total cancel:".$cancelAppointment."\n";
    $message .= "Total refund:".$refundAppointment;

    $userSql = "SELECT `id`,`mobile` FROM `users` WHERE `thinapp_id` = '".$thinappID."' AND `role_id` = '5' LIMIT 1";
    $userRS = $connection->query($userSql);
    $userData = mysqli_fetch_assoc($userRS);
    $mobile = $userData['mobile'];
    $result = Custom::send_single_sms($mobile, $message, $thinappID,false,false);


}




$query = "SELECT t.name as app_name,acss.thinapp_id, t.phone FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id JOIN booking_convenience_fee_details AS bcfd ON acss.id  = bcfd.appointment_customer_staff_service_id WHERE  DATE(acss.appointment_datetime) = DATE(NOW()) and acss.thinapp_id =134 LIMIT 1";
$connection = ConnectionUtil::getConnection();
$service_message_list = $connection->query($query);
if ($service_message_list->num_rows) {
    $data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
    foreach ($data as $key =>  $app){
        $app_name = $app['app_name'];
        $thin_app_id = $app['thinapp_id'];
        $mobile = $app['phone'];
        $label = "today";
        $date = date('Y-m-d');
        $report_url = Custom::short_url(SITE_PATH.'tracker/dailyReportList/'.base64_encode($thin_app_id).'/'.$date,$thin_app_id);
        $message ="Daily Token Report\n\nYour app ( $app_name ) daily token report has been ready for $label. Please click on following link to view/download.\n\n$report_url\n\nThankyou\n\nTeam MEngage";
        Custom::sendWhatsappSms($mobile,$message);
    }
}






exit();


