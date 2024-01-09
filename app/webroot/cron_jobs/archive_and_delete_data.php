<?php


include_once "../webservice/ConnectionUtil.php";
$response = array();
/* this code cancel junk appointment */
$connection =ConnectionUtil::getConnection();
$sql_url = "DELETE from short_urls where DATE(created) < DATE_SUB(NOW(), INTERVAL 3 MONTH) AND hits <= 5";
$stmt_short_url = $connection->prepare($sql_url);
$response[] = $stmt_short_url->execute();

$sql_appointment = "DELETE acss,mpo, mpod FROM appointment_customer_staff_services as acss left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id left JOIN medical_product_order_details as mpod ON mpo.id = mpod.medical_product_order_id where DATE(acss.appointment_datetime) <= DATE_SUB(LAST_DAY(DATE_SUB(NOW(), INTERVAL 1 MONTH)), INTERVAL 12 DAY) ";
$stmt_appointment = $connection->prepare($sql_appointment);
$response[] = $stmt_appointment->execute();

$sql_no_payment = "DELETE FROM appointment_customer_staff_services WHERE medical_product_order_id = 0 AND status NOT IN('NEW','CONFIRMED','RESCHEDUEL') AND  DATE(appointment_datetime) < DATE_SUB(NOW(), INTERVAL 1 DAY)";
$stmt_no_payment = $connection->prepare($sql_no_payment);
$response[] = $stmt_no_payment->execute();

$sql_customer = "DELETE ac, folder, ds, dfile FROM appointment_customers_archive as ac left join drive_folders_archive as folder on folder.appointment_customer_id= ac.id left join drive_shares_archive as ds on ds.drive_folder_id = folder.id left join drive_files_archive as dfile on dfile.drive_folder_id = folder.id where ac.thinapp_id IN('608','717')";
$stmt_customer = $connection->prepare($sql_customer);
$response[] = $stmt_customer->execute();

$sql_sms = "DELETE FROM sent_sms_details WHERE created < DATE_SUB(NOW(), INTERVAL 10 DAY)";
$sql_sms = $connection->prepare($sql_customer);
$response[] = $sql_sms->execute();



if(!in_array(false,$response)){
    $response['status'] = 1;
    $response['message'] = "Data archived successfully.";
    
} else {
    $response['status'] = 0;
    $response['message'] = "Sorry, unable to archived data.";
}

echo json_encode($response);die;

?>
