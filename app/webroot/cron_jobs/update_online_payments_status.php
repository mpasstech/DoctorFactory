<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../instamojo-php/src/Instamojo.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$current_data = date('Y-m-d');
$query = "select acss.thinapp_id, IFNULL(u.id,acss.created_by_user_id) as user_id, acss.id as appointment_id, IFNULL(ac.first_name,c.child_name) as pateint_name, acss.created, IFNULL(ac.mobile,c.mobile) as mobile from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id left join users as u on (u.mobile = ac.mobile and u.thinapp_id = ac.thinapp_id ) OR (u.mobile = c.mobile and u.thinapp_id = c.thinapp_id ) where  acss.booking_payment_type ='ONLINE' and acss.payment_status IN('PENDING','FAILURE') and  DATE(acss.created) = DATE(NOW()) and TIMESTAMPDIFF(MINUTE,acss.created,NOW()) <= 6";
$connection = ConnectionUtil::getConnection();
$app_list = $connection->query($query);
$insta_result_array = $notification_array = array();
if ($app_list->num_rows) {
    $app_list = mysqli_fetch_all($app_list, MYSQLI_ASSOC);
    foreach ($app_list as $counter => $appointment) {
        $insta_response =array();
        $credential = Custom::get_instamojo_credential($appointment['thinapp_id']);
        if ($credential) {
            $private_api_key = $credential['private_api_key'];
            $private_auth_key = $credential['private_auth_key'];
            if (empty($private_api_key) && empty($private_auth_key)) {
                $private_api_key = INSTAMOJO_PRIVATE_API_KEY;
                $private_auth_key = INSTAMOJO_PRIVATE_AUTH_TOKEN;
            }
        } else {
            $private_api_key = INSTAMOJO_PRIVATE_API_KEY;
            $private_auth_key = INSTAMOJO_PRIVATE_AUTH_TOKEN;
        }
        $api = new Instamojo\Instamojo($private_api_key, $private_auth_key);
        $min = gmdate("Y-m-d\TH:i:s\Z", strtotime(date('Y-m-d') . " " . "00:00:00"));
        $max = gmdate("Y-m-d\TH:i:s\Z", strtotime(date('Y-m-d') . " " . "23:59:00"));
        $insta_response = $api->paymentRequestsList(array(
            "max_created_at" => $max,
            "min_created_at" => $min,
            "status" => "Completed"
        ));
        if (!empty($insta_response) && count($insta_response) > 0) {
            $appointment_id = $appointment['appointment_id'];
            $exit_from_loop = false;
            foreach ($insta_response as $key => $transaction) {
                $purpose = $appointment['thinapp_id'] . "XXXAPPOINTMENTXXX" . $appointment_id;
                if (strtoupper($transaction['status']) == "COMPLETED" && strtoupper(strtolower($transaction['purpose'])) == $purpose) {
                    $post['app_key'] = APP_KEY;
                    $post['user_id'] = $appointment['user_id'];
                    $post['thin_app_id'] = $appointment['thinapp_id'];
                    $post['appointment_id'] = $appointment_id;
                    $post['status'] = "SUCCESS";
                    $post['transaction_id'] = $transaction['id'];
                    $_SERVER['REQUEST_METHOD'] = 'POST';
                    $result[$counter] = json_decode(WebservicesFunction::update_appointment_payment_status($post, true), true);
                    if ($result[$counter]['status'] == 1) {
                        $response['purpose'][] = $purpose;
                        $notification_array[] = $result[$counter]['notification_array'];
                        unset($result[$counter]['notification_array']);
                    }
                }
            }
        }
    }
    $response['cancel']['status'] = 1;
    $response['cancel']['message'] = "Payment Updated";

}else{
    $response['cancel']['status'] = 0;
    $response['cancel']['message'] = "No pending online payment";
}
Custom::sendResponse($response);
Custom::send_process_to_background();
if(!empty($notification_array)){
    foreach($notification_array as $key => $options){
        Custom::appointment_payment_notification($options);
    }
}
exit();


