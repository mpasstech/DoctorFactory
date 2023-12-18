<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
    $response = array();
    $connection = ConnectionUtil::getConnection();
    $query = "SELECT acss.appointment_staff_id, acss.appointment_service_id,  acss.id as appointment_id, acss.thinapp_id, app_sta.name as doctor_name, IFNULL(app_cus.first_name,c.child_name) as customer_name, acss.appointment_datetime, app_add.address, u.firebase_token, IFNULL(app_cus.mobile,c.mobile) as mobile, acss.reminder_message, acss.reminder_date from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id  left join  appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id and c.status = 'ACTIVE' join  appointment_services as app_ser on acss.appointment_service_id = app_ser.id join appointment_addresses as app_add on acss.appointment_address_id = app_add.id left join users as u on (u.mobile = app_cus.mobile and u.thinapp_id = app_cus.thinapp_id) OR ( (u.mobile = c.mobile OR u.mobile = c.parents_mobile) and c.thinapp_id = u.thinapp_id ) where ((( (DATE(DATE_ADD(NOW(), INTERVAL 7 DAY)) =  DATE(acss.appointment_datetime)) OR (DATE(DATE_ADD(NOW(), INTERVAL 2 DAY)) =  DATE(acss.appointment_datetime)) OR (DATE(DATE_ADD(NOW(), INTERVAL 1 DAY)) =  DATE(acss.appointment_datetime)) OR ( DATE(acss.appointment_datetime) = DATE(NOW())) ) and ( acss.status ='CONFIRM' OR acss.status ='NEW' OR acss.status ='RESCHEDULE' ) and acss.reminder_date IS NULL and acss.reminder_message = '' ) OR ( (DATE(DATE_ADD(NOW(), INTERVAL 1 DAY)) =  DATE(acss.reminder_date)) OR ( DATE(acss.reminder_date) = DATE(NOW())) and ( acss.reminder_date IS NOT NULL and acss.reminder_date != '0000-00-00' )  and acss.reminder_message != '' ))";
    $subscriber = $connection->query($query);
    if ($subscriber->num_rows) {
        $message_data =array();
        $menu_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
        $counter=0;
        foreach($menu_list as $key => $appointment_data){
            if (!empty($appointment_data)) {
                $thin_app_id = $appointment_data['thinapp_id'];
                $appointment_id = $appointment_data['appointment_id'];
                $date = $appointment_data['appointment_datetime'];
                $customer_name = $appointment_data['customer_name'];
                $doctor_name = $appointment_data['doctor_name'];
                $address = $appointment_data['address'];
                $firebase_token = $appointment_data['firebase_token'];
                $mobile = $appointment_data['mobile'];
                $label_date  =date('Y-m-d',strtotime($date));
                $label  = Custom::get_date_label($label_date);
                $date  =date('d/m/Y h:i A',strtotime($date));
                $send_download_link = true;
                if(empty($appointment_data['reminder_date'])){
                    $message = "Appointment reminder : Hi ".trim($customer_name)." you have scheduled doctor's appointment with ".$doctor_name." for  $label ".$date;
                    $customer_sms = Custom::create_custom_sms_from_template($appointment_id,'REMINDER');
                    $message = !empty($customer_sms)?$customer_sms:$message;
                    $send_download_link = false;
                }else{
                    $label_date  =date('Y-m-d',strtotime($appointment_data['reminder_date']));
                    $show_date  =date('d-m-Y',strtotime($appointment_data['reminder_date']));
                    $label  = Custom::get_date_label($label_date);
                    $message = "Doctor reminder for Today,\nPurpose : ".$appointment_data['reminder_message'];
                }


                $option = array(
                    'thinapp_id' => $thin_app_id,
                    'customer_id' => 0,
                    'staff_id' => $appointment_data['appointment_staff_id'],
                    'service_id' => $appointment_data['appointment_service_id'],
                    'channel_id' => 0,
                    'role' => "CUSTOMER",
                    'flag' => 'APPOINTMENT',
                    'title' => "Appointment Reminder",
                    'message' => mb_strimwidth($message, 0, 250, '...'),
                    'description' => mb_strimwidth($message, 0, 250, '...'),
                    'chat_reference' => '',
                    'module_type' => 'APPOINTMENT',
                    'module_type_id' => $appointment_id,
                    'firebase_reference' => ""
                );
                Custom::send_notification_via_token($option, array($firebase_token), $thin_app_id);
                if (!empty($mobile)) {
                    $message_data[$counter]['mobile'] =$mobile;
                    $message_data[$counter]['message'] =$message;
                  //  Custom::send_single_sms($mobile,$message,$thin_app_id,false,$send_download_link);
                    $counter++;
                }
            }
        }

        $response['status'] = 1;
        $response['message'] = "Notification and sms Send";
        Custom::sendResponse($response);

    } else {
        $response['status'] = 0;
        $response['message'] = "No vaccination list found";
    }
    Custom::sendResponse($response);
    exit();


