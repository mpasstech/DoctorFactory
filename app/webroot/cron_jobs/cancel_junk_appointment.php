<?php
include_once "../webservice/Custom.php";
$response = array();
/* this code cancel junk appointment */
$connection =ConnectionUtil::getConnection();
$minutes = 10;
//$query = "select acss.id, acss.thinapp_id, IFNULL(ac.mobile,c.mobile) as mobile from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id= acss.appointment_customer_id left join childrens as c on c.id = acss.children_id where ( (acss.booking_payment_type = 'ONLINE' and acss.payment_status ='PENDING' AND acss.payment_by_user_id=0 AND acss.is_paid_booking_convenience_fee = 'NOT_APPLICABLE') || ( acss.is_paid_booking_convenience_fee = 'NO') ) and acss.thinapp_id != 803 and acss.appointment_booked_from IN('APP','DOCTOR_PAGE','IVR') and acss.status IN('NEW','RESCHEDULE') AND ( ( DATE(acss.created) = DATE(NOW()) and TIMESTAMPDIFF(MINUTE,acss.created,CONVERT_TZ(NOW(),'+00:00','+05:30')) > $minutes ) OR ( DATE(acss.appointment_datetime) > DATE(NOW())) )";
$query = "select acss.id, acss.thinapp_id, IFNULL(ac.mobile,c.mobile) as mobile from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id= acss.appointment_customer_id left join childrens as c on c.id = acss.children_id where ( (acss.booking_payment_type = 'ONLINE' and acss.payment_status ='PENDING' AND acss.payment_by_user_id=0 AND acss.is_paid_booking_convenience_fee = 'NOT_APPLICABLE') || ( acss.is_paid_booking_convenience_fee = 'NO') ) and acss.thinapp_id != 803 and acss.appointment_booked_from IN('APP','DOCTOR_PAGE','IVR') and acss.status IN('NEW','RESCHEDULE') AND ( DATE(acss.created) >= DATE(NOW()) and TIMESTAMPDIFF(MINUTE,acss.created,CONVERT_TZ(NOW(),'+00:00','+05:30')) > $minutes )";

$connection = ConnectionUtil::getConnection();
$app_list = $connection->query($query);
if ($app_list->num_rows) {
    $list = mysqli_fetch_all($app_list, MYSQLI_ASSOC);
    $id_string = '"' . implode('","', array_column($list,'id')) . '"';
    $sql = "update appointment_customer_staff_services set status=?, cancel_by_user_id =?, cancel_date_time = ?, modified=? where id IN($id_string)";
    $stmt = $connection->prepare($sql);
    $status = 'CANCELED';
    $created = Custom::created();
    $cancel_by_user_id = MBROADCAST_APP_ADMIN_ID;
    $stmt->bind_param('ssss',  $status, $cancel_by_user_id, $created, $created);
    if ($stmt->execute()) {
        $response['cancel']['status'] = 1;
        $response['cancel']['message'] = "Data update successfully.";
        Custom::sendResponse($response);
        $array =array();
        foreach ($list as $key=>$data){
            $array[$data['thinapp_id']][] =$data;
        }
        $message ="Your token has not been booked please retry again.\n-Sent By MEngage";
        foreach ($array as $thin_app_id=>$data){
            $mobile_numbers = array_column($data,'mobile');
            Custom::SendBulkSmsToNumbers($mobile_numbers, $message, $thin_app_id,true,false);
        }
    } else {
        $response['cancel']['status'] = 0;
        $response['cancel']['message'] = "Sorry, unable to update data.";
    }
}

Custom::sendResponse($response);
exit();


