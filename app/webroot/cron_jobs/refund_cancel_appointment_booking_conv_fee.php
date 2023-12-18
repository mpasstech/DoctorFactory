<?php
include_once "../webservice/Custom.php";
$response = array();
$connection =ConnectionUtil::getConnection();
$query = "SELECT bcfd.payment_mode, acss.thinapp_id, bcfd.amount, acss.medical_product_order_id,bcfd.reference_id, bcfd.id AS booking_convenience_order_detail_id FROM appointment_customer_staff_services AS acss JOIN booking_convenience_fee_details AS bcfd ON acss.id  = bcfd.appointment_customer_staff_service_id WHERE acss.`status` = 'CANCELED' AND DATE(acss.appointment_datetime) >= DATE(NOW()) AND bcfd.`status` = 'ACTIVE' and acss.thinapp_id = 607";
$connection = ConnectionUtil::getConnection();
$app_list = $connection->query($query);
$total_refund = $not_refund= 0;
$total_app = 0;
$user_id = -1;
if ($app_list->num_rows) {
    $list = mysqli_fetch_all($app_list, MYSQLI_ASSOC);
    $total_app = count($list);
    $refund_reason = "Refund By Doctor";
    foreach ($list as $key => $value){
        $thin_app_id=$value['thinapp_id'];
        $referenceId=$value['reference_id'];
        $amount=$value['amount'];
        $payment_mode=$value['payment_mode'];
        $booking_convenience_order_detail_id=$value['booking_convenience_order_detail_id'];
        $medical_product_order_id=$value['medical_product_order_id'];
        $result = json_decode(Custom::cashFreeRefund($connection,$thin_app_id,$user_id,$medical_product_order_id, $referenceId,$amount,$booking_convenience_order_detail_id,$refund_reason,$payment_mode),true);
        if($result['status']=='OK'){
            $total_refund++;
        }else{
            $not_refund++;
        }
    }
}
$response['Total Appointment']=$total_app;
$response['Total Refund']=$total_refund;
$response['Total Not Refund']=$not_refund;
Custom::sendResponse($response);
exit();


