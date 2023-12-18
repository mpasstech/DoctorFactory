<?php
include_once "../webservice/Custom.php";
$response = array();
/* this code cancel junk appointment */
$connection =ConnectionUtil::getConnection();

$query = "SELECT staff.department_category_id, IFNULL(ac.uhid,c.uhid) as uhid, t.name AS app_name, IFNULL(ac.mobile,c.mobile) as patient_mobile, acss.queue_number, acss.slot_time, staff.appointment_setting_type, acss.slot_duration, staff.mobile, staff.user_id, acss.appointment_service_id, acss.appointment_address_id, acss.id AS appointment_id, acss.appointment_staff_id AS doctor_id, acss.thinapp_id, acss.appointment_datetime  FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id JOIN thinapps AS t ON t.id = acss.thinapp_id left JOIN appointment_customers AS ac ON ac.id= acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id WHERE acss.status IN('NEW','CONFIRM','RESCHEDULE') AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.reminder_message ='TOKEN_PENDING' AND (acss.appointment_datetime) <= (DATE_ADD(CONVERT_TZ(NOW(),'+00:00','+05:30'), INTERVAL 15 MINUTE))  order by acss.appointment_datetime asc";
$created = Custom::created();
$date = date('Y-m-d');
$connection = ConnectionUtil::getConnection();
$app_list = $connection->query($query);
$total_save = 0;
$whatsapp_array =array();
if ($app_list->num_rows) {
    $list = mysqli_fetch_all($app_list, MYSQLI_ASSOC);



    foreach ($list as $key => $value){
        $doctor_id = $value['doctor_id'];
        $thin_app_id = $value['thinapp_id'];
        $department_category_id = $value['department_category_id'];
        $uhid = $value['uhid'];
        $app_name = $value['app_name'];
        $patient_mobile = $value['patient_mobile'];
        $appointment_id = $value['appointment_id'];
        $doctor_mobile = $value['mobile'];
        $service_slot_duration = $value['slot_duration'];
        $service_id = $value['appointment_service_id'];
        $address_id = $value['appointment_address_id'];
        $slot_time =$update_slot= $value['slot_time'];
        $update_token = $value['queue_number'];
        $auto_assign_token_minutes = AUTO_ASSIGN_TOKEN_MINUTES;
        $date = date("Y-m-d");
        if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
            $current_token = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            $current_appointment_id = $current_token['current']['appointment_id'];
            $appointmentData = Custom::get_appointment_by_id($current_appointment_id,$thin_app_id);
            $currentDateTime = date("Y-m-d H:i");
            $currentAppointmentDateTime =$appointmentData['appointment_datetime'];
            if(strtotime($currentDateTime) > strtotime($currentAppointmentDateTime)){


                $new_appointment = Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT');
                $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id,$doctor_id,$new_appointment_setting = false);
                $role_id = "5";
                $appointment_user_role = "ADMIN";
                $new_appointment = ($new_appointment)?"YES":"NO";
                if ($new_appointment == "YES") {
                    $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                    $slots_list = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $doctor_data['appointment_setting_type'], $date,false,false,$appointment_user_role,true);
                } else {
                    $slots_list = Custom::load_doctor_slot_by_address($date, $doctor_id, $service_slot_duration, $thin_app_id, $address_id,false,false,false,true);
                }
            }
        }else{
            $data = Custom::getDoctorWebTrackerData(array($doctor_id),true,$thin_app_id);
            $appointmentData = $data[$doctor_id][0]['appointment_datetime'];
            $currentDateTime = date("Y-m-d H:i");
            $currentAppointmentDateTime =$appointmentData['appointment_datetime'];
            if(strtotime($currentDateTime) > strtotime($currentAppointmentDateTime)){
                $new_appointment = Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT');
                $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id,$doctor_id,$new_appointment_setting = false);
                $role_id = "5";
                $appointment_user_role = "ADMIN";
                $new_appointment = ($new_appointment)?"YES":"NO";
                if ($new_appointment == "YES") {
                    $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
                    $slots_list = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $doctor_data['appointment_setting_type'], $date,false,false,$appointment_user_role,true);
                } else {

                    $slots_list = Custom::load_doctor_slot_by_address($date, $doctor_id, $service_slot_duration, $thin_app_id, $address_id,false,false,false,true);
                }
            }
        }
        if ($slots_list) {
            $allowUpdate = false;

            $currentTokenAppointmentTimeStamp =0;
            if(!empty($appointmentData['appointment_datetime'])){
                $currentTokenAppointmentTimeStamp =strtotime(date("h:i a",strtotime($appointmentData['appointment_datetime'])));
            }


            $currentTimeStamp =strtotime(date("h:i a"));
            $minutes_diffrence = (int)(($currentTimeStamp - $currentTokenAppointmentTimeStamp)/60) ;



            if($minutes_diffrence <= 0 || $currentTokenAppointmentTimeStamp==0){
                $update_slot = $slot_time;
                $allowUpdate=true;

            }else{
                foreach ($slots_list as $key_2 => $val){
                    $new_slot =strtotime($slot_time)+$minutes_diffrence;
                    if(strtotime($val['slot']) >=  $new_slot){
                        //$minutes_diffrence = (strtotime($val['slot']) - $new_slot)/60;
                        if($val['status']=="BLOCKED" || $val['status']=="AVAILABLE"){
                            $update_token = $val['queue_number'];
                            $update_slot = $val['slot'];
                            $allowUpdate=true;
                            break;
                        }else if($val['status']=="BOOKED"){
                            $allowToken = Custom::checkSlotHasPendingToken($thin_app_id,$doctor_id,$address_id,$service_id,$val['slot']);
                            if($allowToken){
                                $update_token = $val['queue_number'];
                                $update_slot = $val['slot'];
                                $allowUpdate=true;
                                break;
                            }
                        }
                    }
                }

            }



            if($allowUpdate){


                $date =date('Y-m-d');

                $appointment_datetime = $date." ".date('H:i',strtotime($update_slot));
                $reminder_message = "TOKEN_ASSIGNED";
                $created = Custom::created();
                $sql = "update appointment_customer_staff_services set appointment_datetime= ?, slot_time=?, queue_number=?, reminder_message=?, modified=?  where id =?";
                $stmt_df = $connection->prepare($sql);
                $stmt_df->bind_param('ssssss', $appointment_datetime, $update_slot, $update_token,$reminder_message,$created, $appointment_id);
                if($stmt_df->execute()){
                    $web_app_url = Custom::createWebAppUrl($thin_app_id,$doctor_id,$patient_mobile,$uhid,$department_category_id);
                    $whatsapp_message = "$app_name welcomes you. \n\nFollowing token is allotted to you \nDate : $date\nToken Number : $update_token \n\nSelect following link for live tracking of your token \n$web_app_url\n\nAt times your token number could be delayed due to doctor busy schedule. \n\nThis is system generated message. Please do not reply";
                    $whatsapp_array[] =array('mobile'=>$patient_mobile,'message'=>$whatsapp_message);
                }
            }

        }

    }
}
if(!empty($whatsapp_array)){
    foreach ($whatsapp_array as $key => $value){
        $res = Custom::sendWhatsappSms($value['mobile'],$value['message'],$value['message']);
    }
}

$response['message'] = "Total $total_save doctors slots blocked";
Custom::sendResponse($response);
exit();


