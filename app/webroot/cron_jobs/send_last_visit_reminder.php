<?php




error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = $curl_response =$update_array=$installed_array = array();
$connection = ConnectionUtil::getConnection();
$sent_numbers =array();
$query = "SELECT DISTINCT mobile FROM last_visite_reminders";
$service_message_list = $connection->query($query);
if ($service_message_list->num_rows) {
    $sent_numbers = array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC), 'mobile');
}


$query = "SELECT DISTINCT mobile, doctor_id, appointment_patient_name, doctor_name,thinapp_id,appointment_datetime FROM ( (SELECT staff.id as doctor_id, ac.mobile, acss.appointment_patient_name, staff.name AS doctor_name, ac.thinapp_id, acss.appointment_datetime  FROM appointment_customers AS ac JOIN appointment_customer_staff_services AS acss ON acss.appointment_customer_id = ac.id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id LEFT JOIN last_visite_reminders AS lvr ON lvr.thinapp_id = ac.thinapp_id AND lvr.mobile = ac.mobile WHERE  lvr.id IS NULL AND acss.`status` = 'CLOSED' AND TIMESTAMPDIFF(MONTH, DATE(acss.appointment_datetime), DATE(NOW())) BETWEEN '2' AND '5'  AND ac.mobile != '+919999999999' and acss.thinapp_id IN (730,726,728,497,651,318,552,815,713,182,776) ORDER BY acss.appointment_datetime DESC  ) UNION ALL (SELECT staff.id as doctor_id, c.mobile, acss.appointment_patient_name, staff.name AS doctor_name, c.thinapp_id, acss.appointment_datetime  FROM childrens AS c JOIN appointment_customer_staff_services AS acss ON acss.children_id = c.id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id LEFT JOIN last_visite_reminders AS lvr ON lvr.thinapp_id = c.thinapp_id AND lvr.mobile = c.mobile WHERE  lvr.id IS NULL AND acss.`status` = 'CLOSED' AND TIMESTAMPDIFF(MONTH, DATE(acss.appointment_datetime), DATE(NOW())) BETWEEN '2' AND '5'  AND c.mobile != '+919999999999' and acss.thinapp_id IN (730,726,728,497,651,318,552,815,713,182,776) ORDER BY acss.appointment_datetime DESC  ) ) AS final  limit 500";
$subscriber = $connection->query($query);
$doctor_url_array = array();
if ($subscriber->num_rows) {
    $final_array = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
    foreach($final_array as $key =>$data){
        if(!in_array($data['mobile'],$sent_numbers)) {
            $doctor_name = $data['doctor_name'];
            $doctor_id = $data['doctor_id'];
            $thin_app_id = $data['thinapp_id'];

            if (!isset($doctor_url_array[$doctor_id])) {
                $doctor_url_array[$doctor_id] = $web_app_url = Custom::create_web_app_url($doctor_id, $thin_app_id);
            } else {
                $web_app_url = $doctor_url_array[$doctor_id];
            }
            $date = date('d-m-Y', strtotime($data['appointment_datetime']));
            $message = "पिछली बार आप डॉक्टर $doctor_name के पास $date को गए थे। आशा है कि आप तब से स्वस्थ होंगे। भविष्य में डॉक्टर से मिलने के लिए आसान और तत्काल पहुंच के लिए आप निम्नलिखित लिंक का उपयोग करके अपने घर के आराम से टोकन बुक कर सकते हैं।\n$web_app_url\n\nआप इस नंबर को 'माई डॉक्टर' के रूप में सहेज सकते हैं।\n\nयह सिस्टम जनित संदेश है। कृपया जवाब न दें।";
            //echo "<pre>".$message."<br><br>";
            $option = array("body" => $message, "to_number" => $data['mobile']);

            $path = SITE_PATH . "twilio_auth/last_visit_whats_app.php";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($option));
            $return_array = curl_exec($ch);
            $response = json_decode($return_array, true);
            curl_close($ch);
            $message_id = isset($response['sid']) ? $response['sid'] : $response['MessageSid'];
            $created = Custom::created();
            $sql = "INSERT INTO last_visite_reminders (thinapp_id, mobile,last_sent_date) VALUES (?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sss', $data['thinapp_id'], $data['mobile'], $created);
            $res = $stmt->execute();
        }


    }
}else{
    echo "No list found";die;
}

exit();


