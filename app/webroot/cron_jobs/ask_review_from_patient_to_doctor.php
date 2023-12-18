<?php
include_once "../webservice/Custom.php";
$connection = ConnectionUtil::getConnection();
$query = "select staff.thinapp_id,staff.id, staff.name, staff.mobile from appointment_customer_staff_services as acss join appointment_staffs as staff on acss.appointment_staff_id = staff.id join department_categories as dc on dc.id = staff.department_category_id where date(acss.appointment_datetime) = DATE(NOW()) AND staff.department_category_id != 32 and acss.status != 'CANCELED' GROUP by acss.appointment_staff_id";
$connection = ConnectionUtil::getConnection();
$service_message_list = $connection->query($query);
$$data=array();
if ($service_message_list->num_rows) {
    $data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
    foreach ($data as $key =>  $app){
        $mobile = $app['mobile'];
        $thin_app_id = $app['thinapp_id'];
        $doctor_id = $app['id'];
         $app_url = SITE_PATH."doctor/index/$doctor_id/?t=".base64_encode($doctor_id)."&sal=y";
        $app_url = Custom::short_url($app_url,$thin_app_id);
        $message ="Please select $app_url to see the list of patients visited you today. To get better reviews from them , select those whom you think would be happy to give you good rating.";
        Custom::sendWhatsappSms($mobile,$message);
    }
}

die('Sucess:- '.count($data));
exit();


