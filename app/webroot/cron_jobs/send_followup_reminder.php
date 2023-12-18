<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$query = "SELECT fur.id, fur.thinapp_id, app_sta.name as doctor_name, IFNULL(app_cus.first_name,c.child_name) as customer_name, fur.reminder_date, u.firebase_token, IFNULL(app_cus.mobile,c.mobile) as mobile from follow_up_reminders as fur left join  appointment_staffs as app_sta on fur.doctor_id = app_sta.id  left join  appointment_customers as app_cus on app_cus.id = fur.appointment_customer_id left join childrens as c on c.id = fur.children_id and c.status = 'ACTIVE' left join users as u on (u.mobile = app_cus.mobile and u.thinapp_id = app_cus.thinapp_id) OR ( (u.mobile = c.mobile OR u.mobile = c.parents_mobile) and c.thinapp_id = u.thinapp_id ) WHERE (( (DATE(DATE_ADD(NOW(), INTERVAL 7 DAY)) =  DATE(fur.reminder_date)) OR (DATE(DATE_ADD(NOW(), INTERVAL 2 DAY)) =  DATE(fur.reminder_date)) OR (DATE(DATE_ADD(NOW(), INTERVAL 1 DAY)) =  DATE(fur.reminder_date)) OR ( DATE(fur.reminder_date) = DATE(NOW())) ) ) and date(fur.reminder_date) >= DATE(now())  group by concat(fur.thinapp_id,fur.appointment_customer_id,fur.children_id) HAVING mobile !=''";
$subscriber = $connection->query($query);
if ($subscriber->num_rows) {
    $message_data =array();
    $menu_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
    $counter=0;

    foreach($menu_list as $key => $data) {
        if (!empty($data)) {
            $thin_app_id = $data['thinapp_id'];
            $date = $data['reminder_date'];
            $customer_name = $data['customer_name'];
            $doctor_name = $data['doctor_name'];
            $firebase_token = $data['firebase_token'];
            $mobile = $data['mobile'];
            $label_date = date('Y-m-d', strtotime($date));
            $label = Custom::get_date_label($label_date);
            $date = date('d/m/Y', strtotime($date));
            //$message = "Hello " . trim($customer_name) . ", This is the reminder that  " . $doctor_name . " has scheduled your visit on $date".". Kindly visit\nThanks";
            $message = "REMINDER " . $doctor_name . " has scheduled your visit on $date".". Kindly visit";
            if (!empty($mobile)) {
                if (Custom::send_single_sms($mobile, $message, $thin_app_id)) {
                    $sql = "UPDATE follow_up_reminders SET reminder_status = ? where id = ?";
                    $stmt = $connection->prepare($sql);
                    $status = "SENT";
                    $stmt->bind_param('ss', $status, $data['id']);
                    if($stmt->execute()){
                        $connection->commit();
                    }
                    $counter++;
                }
            }
        }
    }

    $response['status'] = 1;
    $response['message'] = "Notification and sms Send";
    Custom::sendResponse($response);

} else {
    $response['status'] = 0;
    $response['message'] = "No follow up list found";
}
Custom::sendResponse($response);
exit();


