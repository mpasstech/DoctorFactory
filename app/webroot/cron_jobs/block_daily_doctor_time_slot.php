<?php
include_once "../webservice/Custom.php";
$response = array();
/* this code cancel junk appointment */
$connection =ConnectionUtil::getConnection();
//$query = "SELECT staff.id as doctor_id, staff.thinapp_id, asa.appointment_address_id as address_id, ass.appointment_service_id as service_id, abs.id as blocked_id, abs.is_date_blocked from app_enable_functionalities as aef  join app_functionality_types as aft on aef.app_functionality_type_id = aft.id join appointment_staffs as staff on staff.thinapp_id = aef.thinapp_id and staff.staff_type='DOCTOR' and staff.status = 'ACTIVE' and staff.department_category_id != 39 join appointment_staff_addresses as asa on asa.appointment_staff_id = staff.id join appointment_staff_services as ass on ass.appointment_staff_id = staff.id left join appointment_bloked_slots as abs on abs.doctor_id = staff.id and abs.service_id = ass.appointment_service_id and abs.address_id = asa.appointment_address_id and DATE(abs.book_date) = DATE(NOW()) where aft.label_key = \"AUTO_ASSIGN_TOKEN_SYSTEM\" group by staff.id";
$query = "SELECT staff.mobile, staff.id as doctor_id, staff.thinapp_id from app_enable_functionalities as aef  join app_functionality_types as aft on aef.app_functionality_type_id = aft.id join appointment_staffs as staff on staff.thinapp_id = aef.thinapp_id and staff.staff_type='DOCTOR' and staff.status = 'ACTIVE' and staff.department_category_id != 39 where aft.label_key = \"AUTO_ASSIGN_TOKEN_SYSTEM\" group by staff.id";
$created = Custom::created();
$date = date('Y-m-d');
$connection = ConnectionUtil::getConnection();
$app_list = $connection->query($query);
$total_save = 0;
if ($app_list->num_rows) {
    $list = mysqli_fetch_all($app_list, MYSQLI_ASSOC);
    foreach ($list as $key => $value){
      $thin_app_id = $value['thinapp_id'];
      $doctor_id = $value['doctor_id'];
      $doctor_mobile = $value['mobile'];
      $new_appointment = Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT');
      $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id,$doctor_id,$new_appointment_setting = false);
      $role_id = $doctor_data['role_id'];
      $appointment_user_role = Custom::get_appointment_role($doctor_mobile,$thin_app_id,$role_id);
      $new_appointment = ($new_appointment)?"YES":"NO";
      $service_id = $doctor_data['service_id'];
      $address_id = $doctor_data['address_id'];
        if ($new_appointment == "YES") {
            $doctor_data = Custom::get_doctor_by_id($doctor_id, $thin_app_id);
            $slots_list = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $doctor_data['appointment_setting_type'], $date,false,false,$appointment_user_role);
            if ($slots_list) {

                foreach ($slots_list as $slot => $val){
                    if($val['token']%3==0){
                        $doctor_slots[] =$val['slot'];
                    }
                }

                if(!empty($doctor_slots)){
                    $slot_string = implode(',', $doctor_slots);
                }
            }
        } else {
            $slots_list = Custom::load_doctor_slot_by_address($date, $doctor_id, $doctor_data['service_slot_duration'], $thin_app_id, $address_id,false,$appointment_user_role,false,false);
            if ($slots_list) {
                foreach ($slots_list as $key => $val){
                    if($val['queue_number']%3==0){
                        $doctor_slots[] =$val['slot'];
                    }
                }
                if(!empty($doctor_slots)){
                    $slot_string = implode(',', $doctor_slots);
                }
            }
        }
        if(!empty($slot_string)){
            $slot_string = Custom::create_block_slot_string($slot_string);
            $slot_array = explode(",", json_decode($slot_string, true));
            $is_date_blocked = "NO";
            $allow_submit = false;
            $user_id =1;

            $message="SLOT BLOCKED BY CRON JOB";
            $query = "SELECT slot.id,slot.book_date FROM appointment_bloked_slots AS slot WHERE slot.doctor_id = $doctor_id AND slot.address_id = $address_id AND slot.service_id = $service_id AND slot.book_date = DATE(NOW()) LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $block_data = mysqli_fetch_assoc($service_message_list);
                if($block_data["is_date_blocked"] !="YES"){
                    if ($new_appointment == "YES") {
                        $sql = "UPDATE  appointment_bloked_slots set  modify_by_user_id=?, slot=?, address_id =?, service_id =?, is_date_blocked =?, message = ?,  modified = ? where id = ?";
                        $stmt_sub = $connection->prepare($sql);
                        $stmt_sub->bind_param('ssssssss', $user_id, $slot_string, $address_id, $service_id, $is_date_blocked, $message, $created, $block_data['id']);
                    } else {
                        $sql = "UPDATE  appointment_bloked_slots set  modify_by_user_id=?, is_date_blocked =?, slot=?, message = ?,  modified = ? where id = ?";
                        $stmt_sub = $connection->prepare($sql);
                        $stmt_sub->bind_param('ssssss', $user_id, $is_date_blocked, $slot_string, $message, $created, $block_data['id']);
                    }
                    $allow_submit=true;
                }
            }else{
                $sql = "INSERT INTO appointment_bloked_slots (created_by_user_id, new_appointment, service_id, is_date_blocked, thinapp_id, doctor_id, address_id, book_date, slot, message, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt_sub = $connection->prepare($sql);
                $stmt_sub->bind_param('ssssssssssss', $user_id, $new_appointment, $service_id, $is_date_blocked, $thin_app_id, $doctor_id, $address_id, $date, $slot_string, $message, $created, $created);
                $allow_submit=true;
            }

            if ($stmt_sub->execute()) {

                $file_name = "block_list_".$date."_".$doctor_id."_".$address_id;
                WebservicesFunction::deleteJson(array($file_name),'appointment/blocked');

                $total_save++;
            }

        }
    }
}
$response['message'] = "Total $total_save doctors slots blocked";
Custom::sendResponse($response);
exit();


