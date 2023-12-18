<?php
ignore_user_abort(true);
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = array();
$connection = ConnectionUtil::getConnection();
$created = Custom::created();
$query = "SELECT COUNT(acss.thinapp_id) AS total_appointment, acss.thinapp_id FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id WHERE acss.thinapp_id > 82 AND t.`status`='ACTIVE' AND acss.`status` !='CANCELED' AND acss.consulting_type != 'OFFLINE' AND TIMESTAMPDIFF(MINUTE, '$created', acss.appointment_datetime) BETWEEN '1' AND '30'  GROUP BY acss.thinapp_id HAVING total_appointment <= 5";
$subscriber = $connection->query($query);
if ($subscriber->num_rows) {
    $message_data =array();
    $thin_app_ids = array_column(mysqli_fetch_all($subscriber, MYSQLI_ASSOC),'thinapp_id');
    $thin_app_ids =  '"'.implode('","', $thin_app_ids).'"';
    $query = "SELECT acss.status, acss.thinapp_id, IFNULL(ac.mobile,c.mobile) AS patient_mobile, acss.queue_number,acss.appointment_datetime, acss.consulting_type,staff.mobile AS doctor_number,staff.name AS doctor_name, ( SELECT reception.mobile FROM appointment_staffs AS reception WHERE reception.thinapp_id = acss.thinapp_id AND reception.`status`='ACTIVE' AND reception.staff_type='RECEPTIONIST' ) AS reception_mobile FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id   WHERE acss.thinapp_id IN($thin_app_ids)  AND  acss.`status` !='CANCELED' AND acss.consulting_type != 'OFFLINE'  AND  TIMESTAMPDIFF(MINUTE, '$created', acss.appointment_datetime) BETWEEN '1' AND '30' order by acss.id asc";
    $subscriber = $connection->query($query);
    if ($subscriber->num_rows) {
        $list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
        $filter_array = array();
        foreach($list as $key => $val){
            $filter_array[$val['thinapp_id']][]=$val;
        }
        foreach ($filter_array as $thin_app_id => $list){
                foreach($list as $key => $val){
                $number = $key+1;
                if(in_array($val['status'],array('NEW','CONFIRM','RESCHEDULE'))){
                    $patient_mobile = $val['patient_mobile'];
                    $queue_number = $val['queue_number'];
                    $consulting_type = $val['consulting_type'];
                    $doctor_number = $val['doctor_number'];
                    $doctor_name = $val['doctor_name'];
                    $reception_mobile = !empty($val['reception_mobile'])?$val['reception_mobile']:'N/A';
                    $date = date('d-m-Y',strtotime($val['appointment_datetime']));
                    $time = date('h:i A',strtotime($val['appointment_datetime']));
                    $message = "This is nth token booked for Doctor $doctor_name for token number:$queue_number \nDate : $date\nTime : $time\nType of Token : $consulting_type \nDoctor Tel number : $doctor_number\nDoctor Assistant tel number : $reception_mobile\nPatient Tel number : $patient_mobile\n\nNote : $number token is count of token from first token irrespective of dates";
                    $support = Custom::create_mobile_number("+918955004049");
                    Custom::sendWhatsappSms($support,$message);
                }
            }
        }
        $response['status'] = 1;
        $response['message'] = "What's app Send";

    }else{
        $response['status'] = 0;
        $response['message'] = "No list found";
    }


    Custom::sendResponse($response);
} else {
    $response['status'] = 0;
    $response['message'] = "No list found";
}
Custom::sendResponse($response);
exit();








