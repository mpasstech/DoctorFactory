<?php
//namespace WebservicesFunction;
date_default_timezone_set("Asia/Kolkata");
include_once "Custom.php";


class QueueManagementWebService
{

  
public static function updateNextToken()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $doctor_id = isset($data['doctor_id'])?base64_decode($data['doctor_id']):0;
        $thin_app_id = isset($data['thin_app_id'])?base64_decode($data['thin_app_id']):0;
        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else {
            $response['status'] = 1;
            $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id);
            $current_appointment_id = !empty($res['current']['appointment_id'])?$res['current']['appointment_id']:'';
            $response['current_appointment_id'] = base64_encode($current_appointment_id);
            $response['string']=Custom::fortisShowResponseString($res,true);
        	Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id));
        	
        }
            echo json_encode($response);die;
    }

   public static function pine_token_booking($data=null)
    {

        if(empty($data)){
            $request = file_get_contents("php://input");
            $data = json_decode($request, true);
        }
        $notification_data= $response=array();
        $thin_app_id = isset($data['thin_app_id']) ? base64_decode($data['thin_app_id']) : "";
       
        $doctor_mobile = isset($data['doctor_mobile']) ? base64_decode($data['doctor_mobile']) : "";
        $patient_mobile = isset($data['patient_mobile']) ? $data['patient_mobile'] : "";
        $patient_name = isset($data ['patient_name']) ? $data['patient_name'] : "";
        $appointment_id = 0;
        $available_slot = $fixed_slot = isset($data['slot']) ? $data['slot'] : "";
        $queue_number =$fixed_queue_number = isset($data['token']) ? $data['token'] : "";
        $slot_status = isset($data['status']) ? $data['status']:"";
        $appointment_user_role = isset($data['role']) ? $data['role'] : "ADMIN";
        $remark = isset($data['remark']) ? $data['remark'] : "Emergency";
        $appointment_booked_from = isset($data['booked_from']) ? $data['booked_from'] : "PINE_DEVICE";

        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thinapp id';
        }else {

            $patient_mobile = !empty($patient_mobile)?$patient_mobile:"9999999999";
            $tmp_name = ($patient_mobile=='9999999999')?'Patient-'.rand(1111,999999):$patient_mobile;
            $patient_name = !empty($patient_name)?$patient_name:$tmp_name;
            $doctor_mobile = Custom::create_mobile_number($doctor_mobile);
            $patient_mobile = Custom::create_mobile_number($patient_mobile);
            $new_appointment = Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT');
            $doctor_data = Custom::getDoctorCustomDataByMobile($thin_app_id,$doctor_mobile,$new_appointment);
            if (!empty($doctor_data)) {
                $service_id =$doctor_data['service_id'];
                $doctor_id =$doctor_data['doctor_id'];
                $address_id = $doctor_data['address_id'];
                $time_of_day = 'CURRENT';
                $booking_date = date('Y-m-d');
                $custom_token = 'NO';
                $created_by_user_id = $doctor_data['user_id'];
                $appointment_user_role ='ADMIN';
                $expired_slot =true;
                if(empty($available_slot)){
                    $setting_type = $doctor_data['setting_type'];
                    $available_slot = Custom::get_doctor_next_available_slot($thin_app_id, $doctor_id, $address_id, $booking_date,$time_of_day, false, $appointment_user_role,0,$expired_slot);
                    if($new_appointment){
                        $slot_array = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $setting_type, $booking_date, true, false, $appointment_user_role,$expired_slot);
                        $queue_number = !empty($slot_array[$available_slot]['token']) ? $slot_array[$available_slot]['token'] : false;
                    }else{
                        $slot_array = Custom::load_doctor_slot_by_address($booking_date, $doctor_id, $doctor_data['service_slot_duration'], $thin_app_id, $address_id, true, $appointment_user_role, false, $expired_slot);
                        $queue_number = !empty($slot_array[$available_slot]['queue_number']) ? $slot_array[$available_slot]['queue_number'] : false;
                    }
                }
            	
            	
            	if(Custom::check_app_enable_permission($thin_app_id, 'SINGLE_TOKEN_BOOKING_APP')){
                    $lastBookedToken = Custom::get_last_booked_token_of_app($thin_app_id);
                    if(!empty($lastBookedToken)){
                        $allow_book = true;
                        if(!empty($fixed_queue_number) && ($fixed_queue_number < $lastBookedToken['queue_number'])){
                            $allow_book = false;
                        }
                        if($allow_book==true){
                            $bookedTokenList = Custom::get_all_booked_token_of_app($thin_app_id);
                            foreach ($slot_array as $slot => $slot_av_data){
                                if(!array_key_exists($slot_av_data['queue_number'],$bookedTokenList)){
                                    $queue_number = $slot_av_data['queue_number'];
                                    $available_slot = $slot;
                                    break;
                                }
                            }

                        }else{
                            $response["status"] = 0;
                            $response["message"] = "This token already booked.";
                            echo json_encode($response);die;
                        }
                    }
                }
            
                if(empty($queue_number)){
                    $appointment_data = Custom::load_doctor_slot_by_address($booking_date, $doctor_id, $doctor_data['service_slot_duration'], $thin_app_id, $address_id, false, 'ADMIN', true, true);
                    $appointment_data = end($appointment_data);
                    if($appointment_data['custom_slot']=='YES'){
                        $available_slot = $appointment_data['slot'];
                        $queue_number = $appointment_data['queue_number'];
                        $custom_token = 'YES';

                    }
                }

                if(!empty($queue_number) && !empty($available_slot)){

                    if($patient_mobile=="+919999999999"){
                        $patient_data = Custom::getThinappDefaultPatient($thin_app_id);
                        $patient_data['patient_name'] = $patient_name;
                    }else{
                        $patient_data = Custom::get_patient_data_for_token_booking($thin_app_id,$patient_mobile,$patient_name);
                    }

                    if($patient_data){
                        $connection = ConnectionUtil::getConnection();
                        $appointment_patient_name = $patient_data['patient_name'];
                        $appointment_customer_id = $patient_data['patient_id'];
                        $appointment_address_id = $doctor_data['address_id'];
                        $appointment_service_id = $doctor_data['service_id'];
                        $appointment_staff_id = $doctor_id;
                        $appointment_day_time_id = date('N', strtotime($booking_date));;

                        $appointment_datetime = date('Y-m-d H:i',strtotime($booking_date.' '.$available_slot));
                        $slot_duration =$doctor_data['service_slot_duration'];
                        $booked_by ='ADMIN';
                        $created = Custom::created();

                        if($patient_mobile=="+919999999999"){
                            $patient_already_booked = false;
                        }else{
                            $patient_already_booked = Custom::getBookedAppoitnment($thin_app_id,$doctor_id,$appointment_address_id,$appointment_customer_id,$booking_date);
                        }


                        if(!$patient_already_booked){
                            $sub_token = "NO";
                            if($slot_status=='BOOKED'){
                                $sub_token = "YES";
                                $count = Custom::get_sub_token_number($doctor_id, $service_id, $address_id, $available_slot, $booking_date);
                                $queue_number = $queue_number + (($count + 1) / 10);
                            }

                            if($patient_mobile=="+919999999999"){
                                $is_token_booked = false;
                            }else{
                                $is_token_booked = Custom::isTokenBooked($thin_app_id,$doctor_id,$address_id,$service_id,$booking_date,$queue_number);
                            }

                            if(!$is_token_booked){
                                $sql = "INSERT INTO appointment_customer_staff_services (notes, sub_token, appointment_patient_name, custom_token, created_by_user_id, appointment_booked_from, booked_by, queue_number, appointment_customer_id, appointment_address_id, thinapp_id, appointment_staff_id, appointment_service_id, appointment_day_time_id, booking_date, appointment_datetime, slot_duration, slot_time, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                $stmt_sub = $connection->prepare($sql);
                                $stmt_sub->bind_param('ssssssssssssssssssss',$remark, $sub_token,$appointment_patient_name,$custom_token, $created_by_user_id, $appointment_booked_from, $booked_by, $queue_number, $appointment_customer_id, $appointment_address_id, $thin_app_id, $appointment_staff_id, $appointment_service_id, $appointment_day_time_id, $booking_date, $appointment_datetime, $slot_duration, $available_slot, $created, $created);
                                if ($stmt_sub->execute()) {
                                    $appointment_id = $stmt_sub->insert_id;
                                    $response['status'] = 1;
                                    $response["message"] = 'Token Booked Successfully';
                                    $patient_array = explode("-",$appointment_patient_name);
                                    $customer_name = trim($patient_array[0]);
                                    $date = date('d-M-Y',strtotime($booking_date));

                                    if(Custom::isCustomizedAppId($thin_app_id)){
                                        $time = date('h:i A');
                                        $response['doctor_name'] = $doctor_data['app_name'];
                                        $doctor_name= $doctor_data['doctor_name'];
                                        $string = "Service :- $doctor_name\n";
                                        if(strtolower($customer_name)!="patient"){
                                            $string .= "Name :- $customer_name\n";
                                        }
                                        if($thin_app_id==607 || $thin_app_id==908){
                                            $string .= "Date   :- $date\nAppointment Time   :- $available_slot\nBooked Time   :- $time\n\n\n\n";
                                        }else{
                                            $string .= "Date   :- $date\nTime   :- $time\n\n\n\n";
                                        }

                                        $response['print_string'] = $string;
                                        $response['token_number'] = $queue_number;
                                    }else{
                                        $string = "Name :- $customer_name\nDate   :- $date\n\n\n\n";
                                        $response['print_string'] = $string;
                                        $response['doctor_name'] = $doctor_name= $doctor_data['doctor_name'];
                                        $response['token_number'] = $queue_number;
                                    }



                                } else {
                                    $response["status"] = 0;
                                    $response["message"] = 'Sorry, token not booked';
                                }
                            }else{
                                $response["status"] = 0;
                                $response["message"] = "Token $queue_number already booked";
                            }


                        }else{
                            $response["status"] = 0;
                            $response["message"] = "This patient has already token booked";
                        }

                    }else{
                        $response["status"] = 0;
                        $response["message"] = "Sorry, unable to add patient";
                    }

                } else {
                    $response["status"] = 0;
                    $response["message"] = "Tokens are not available more";
                }
            } else {
                $response["status"] = 0;
                $response["message"] = "Doctor is not available";
            }
        }

        Custom::sendResponse($response);
        Custom::send_process_to_background();
   		
   		$response["thin_app_id"] = base64_encode($thin_app_id);
        if($appointment_booked_from=="DESKTOP_APP"){
            $res = Custom::emitTokenPrint($response);
        }
   	
        if(!empty($appointment_id)){
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            $res = Custom::fortisUpdatePatientNameCurrentToken($thin_app_id,$doctor_id,$appointment_data,'UPDATE');
            $res_log = Custom::insertTokenLogCustom($thin_app_id,$doctor_id,$doctor_id,"APPOINTMENT_BOOKED",$appointment_id,$queue_number,$appointment_patient_name);
            if(Custom::isCustomizedAppId($thin_app_id)){

                $doctor_ids = Custom::get_app_doctor_ids($thin_app_id);
                $res = Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id,'play'=>false,'doctor_ids'=>$doctor_ids));
            }

        }
        if($appointment_id > 0 && $patient_data['patient_mobile']!='+919999999999'){
            if(Custom::check_user_permission($thin_app_id, 'SEND_APPOINTMENT_BOOKING_SMS') || Custom::check_user_permission($thin_app_id, 'SEND_NEW_APPOINTMENT_BOOKING_SMS')){
                $web_app_link = Custom::createWebAppUrl($thin_app_id,$doctor_id,$patient_data['patient_mobile'],$patient_data['uhid'],$doctor_data['department_category_id']);
                $receiptUrl = " - ";
                if($doctor_data['department_category_id']==32 || $thin_app_id=="897" || $thin_app_id=="908"){
                    $doctor_name = $doctor_data['doctor_name'];

                    $doctor_name = trim(preg_replace("/\([^)]+\)/","",$doctor_name));
                    $message = "CONFIRMED\nService - $doctor_name\nToken-$queue_number, Date-$date\nLive Token Status\n$web_app_link\nBy MEngage";
                    $w_message= "CONFIRMED\nService-$doctor_name\nToken-$queue_number, Date-$date\nLive Token Status\n$web_app_link";
                    $app_name = $doctor_data['app_name'];
                    $message= "WELCOME TO $app_name\n\nService - $doctor_name\nToken-$queue_number, Date-$date\nLive Token Status\n$web_app_link\nBy mPass";
                    $receiptUrl = "N/A";
                    $time = "-";
                    $message = "CONFIRMED\nToken-$queue_number, Date-$date\nTime-$time\nToken Status\n$web_app_link\nPayment Receipt\n$receiptUrl\nBy MEngage";


                }else{
                    $message = "CONFIRMED\nToken-$queue_number, Date-$date\nTime-$available_slot\nToken Status\n$web_app_link\nPayment Receipt\n$receiptUrl\nBy MEngage";
                    $w_message = "कृपया हमारे संपर्क नंबर को डाॅक्टर या हॉस्पिटल के नाम से सेव करे।\n\nवर्तमान टोकन की लाइव स्थिति को ट्रैक कर सकते हैं - $web_app_link\n\nआपका टोकन विवरण -\n\nटोकन- $queue_number\nदिनांक- $date\nनियुक्ति समय- $available_slot\n\nकभी-कभी, आपके नंबर में देरी हो सकती है क्योंकि डॉक्टर व्यस्त हो सकते हैं।\n\nयह सिस्टम जनित संदेश है। कृपया जवाब न दें।";
                }
                if(Custom::check_app_enable_permission($thin_app_id, 'WHATS_APP')){
                    $res = Custom::sendWhatsappSms($patient_data['patient_mobile'],$w_message,$message);
                }else{
                    $result = Custom::send_single_sms($patient_data['patient_mobile'], $message, $thin_app_id,false,false);
                }
            }
        }




    }

    public static function  pine_token_list(){
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = $t_id = isset($data['thin_app_id']) ? base64_decode($data['thin_app_id']) : "";
        $doctor_id =$d_id = isset($data['doctor_id']) ? base64_decode($data['doctor_id']) : "";
        $query = "SELECT t.name as app_name, staff.name as doctor_name, acss.appointment_datetime, t.logo, acss.drive_folder_id as folder_id,  acss.referred_by as counter, acss.appointment_staff_id, acss.patient_queue_type, acss.thinapp_id, acss.send_to_lab_datetime, acss.skip_tracker, acss.consulting_type, acss.id AS appointment_id, acss.payment_status, acss.appointment_patient_name AS patient_name, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL, 'CUSTOMER','CHILDREN') AS patient_type, IFNULL(ac.mobile,c.mobile) AS patient_mobile, acss.queue_number, acss.status FROM appointment_customer_staff_services AS acss left join thinapps as t on t.id = acss.thinapp_id LEFT JOIN appointment_customers AS ac ON ac.id=acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id left join appointment_staffs as staff on staff.id = acss.appointment_staff_id WHERE acss.appointment_staff_id = $doctor_id and acss.thinapp_id=$thin_app_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status !='DELETED' AND acss.is_paid_booking_convenience_fee !='NO' ORDER BY CAST(acss.queue_number  AS DECIMAL(10,2)) desc, acss.send_to_lab_datetime ASC";
        $connection = ConnectionUtil::getConnection();
        $data =$response = $final_array =array();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
            $thin_app_id =base64_encode($thin_app_id);
            $doctor_id =base64_encode($doctor_id);
            foreach ($data as $key => $list) {
                $tmp=array();
                $patient_array = explode("-",$list['patient_name']);
                $patient_name = $patient_array[0];
                $tmp['appointment_id'] =base64_encode($list['appointment_id']);
                $tmp['thinapp_id'] =base64_encode($list['thinapp_id']);
                $tmp['patient_name'] = $patient_name;
                $tmp['patient_mobile'] =substr($list['patient_mobile'], -10);
                $tmp['consulting_type'] =$list['consulting_type'];
                $tmp['queue_number'] =$token_number= $list['queue_number'];
                if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                    $status = ($list['skip_tracker'] == 'YES') ? 'Skipped' : 'Booked';
                    $tmp['status'] = ucfirst(strtolower($status));
                }else{
                    $tmp['status'] = ucfirst(strtolower($list['status']));
                }

                $tmp['send_to_doctor'] = 'NO';
                $tmp['skip_token'] = 'NO';
                $tmp['unskip_token'] = 'NO';
                $tmp['send_to_billing'] = 'NO';
                $tmp['assign_counter'] = 'NO';
                $tmp['medical_record'] = "";
                $tmp['cancel'] = "NO";
                $tmp['close'] = "NO";
                $date = date('d-M-Y',strtotime($list['appointment_datetime']));
                $tokenTime = date('h:i:A',strtotime($list['appointment_datetime']));

                $doctor_name = $list['doctor_name'];
                $customer_name = $list['patient_name'];
                $tmp['doctor_name'] = $doctor_name;
            	if(Custom::isCustomizedAppId($list['thinapp_id'])){
                    $time = date('h:i A');
                    $tmp['doctor_name'] = $list['app_name'];
                    $string = "Service :- $doctor_name\n";
                    if(strpos(strtolower($customer_name), "patient") === false){
                        $string .= "Name :- $customer_name\n";
                    }
                    if($list['thinapp_id']==607){
                        $string .= "Date   :- $date\nAppointment Time   :- $tokenTime\nBooked Time   :- $time\n\n\n\n";
                    }else{
                        $string .= "Date   :- $date\nTime   :- $time\n\n\n\n";
                    }
                }else{
                    $string = "Name :- $customer_name\nDate   :- $date\n\n\n\n";
                }
                $tmp['print_string'] = $string;
                $tmp['token_number'] = $token_number;
                if ($list['status'] == 'RESCHEDULE' || $list['status'] == 'NEW' || $list['status'] == 'CONFIRM') {
                    if ($list['thinapp_id'] == CK_BIRLA_APP_ID && $list['patient_queue_type'] != 'DOCTOR_CHECKIN') {
                        $tmp['send_to_doctor'] = 'YES';
                    }
                    if ($list['skip_tracker'] == 'NO') {
                        $tmp['skip_token'] = 'YES';
                    } else {
                        $tmp['unskip_token'] = 'YES';
                    }
                    if ($list['thinapp_id'] == CK_BIRLA_APP_ID ) {
                        if ($list['patient_queue_type'] == 'DOCTOR_CHECKIN') {
                            $tmp['send_to_billing'] = 'YES';
                        }
                        $tmp['assign_counter'] = 'YES';
                    }
                    if ($list['consulting_type'] != 'OFFLINE') {
                        $string = $list['folder_id'] . "##FOLDER##" . $list['patient_mobile'];
                        $folder_url = FOLDER_PATH . 'record/' . Custom::encodeVariable($string);
                        $tmp['medical_record'] = $folder_url;
                    }

                    $tmp['cancel'] = "YES";
                    $tmp['close'] = "YES";
                }
                $final_array[$key] = $tmp;
            }
        }
        if(!empty($final_array)){
            $response['status']=1;
            $response['message']="List Fount";
            $response['data']=$final_array;
            $res = Custom::fortisGetCurrentToken($t_id,$d_id);
            $response['string']=Custom::fortisShowResponseString($res,true);
        }else{
            $response['status']=0;
            $response['message']="No List Found";
        }
        echo json_encode($response);die;

    }

	public static function pine_get_app_list()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $final_array = $response=array();
        $mobile = isset($data['mobile']) ? $data['mobile'] : "";
        if (!$mobile = Custom::create_mobile_number($mobile)) {
            $response['status'] = 0;
            $response['message'] = 'Please enter 10 digit mobile number';
        }else {

            $query = "select t.id as app_id, t.name as app_name,t.logo, staff.profile_photo, staff.id as doctor_id, staff.mobile as doctor_mobile, staff.name as doctor_name from thinapps as t  join appointment_staffs as staff on staff.thinapp_id = t.id  and staff.status = 'ACTIVE' and staff.staff_type = 'DOCTOR' and staff.show_into_pine_device='YES' where t.status = 'ACTIVE' and t.phone = '$mobile'  and t.app_singup_from IN('AUTOMATION','QUTOT')";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $result =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                $filter_array =array();
                foreach($result as $key => $val){
                    $app_id = $thin_app_id= $val['app_id'];
                    $appointment_type = Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT');
                    if(!empty($val['doctor_id'])){
                        $doctor_id = $val['doctor_id'];
                        $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id,$doctor_id,$appointment_type);
                        if(!empty($doctor_data)){
                            $filter_array[$app_id][] = array(
                                'doctor_mobile'=>base64_encode($val['doctor_mobile']),
                                'doctor_name'=>$val['doctor_name'],
                                'profile_photo'=>$val['profile_photo'],
                                'di'=>base64_encode($doctor_id),
                                'ti'=>base64_encode($thin_app_id),
                                'ai'=>base64_encode($doctor_data['address_id']),
                                'du'=>$doctor_data['service_slot_duration']
                            );
                        }
                    }
                }
                //echo "<pre>";
                //print_r($filter_array);die;
                foreach($result as $key => $val){
                    $final_array[$val['app_id']] = array(
                        'app_name'=>$val['app_name'],
                        'logo'=>$val['logo'],
                        'thin_app_id'=>base64_encode($val['app_id']),
                        'doctor_list'=>isset($filter_array[$val['app_id']])?$filter_array[$val['app_id']]:array()
                    );
                }
                $response["status"] = 1;
                $response["message"] = "App Found";
                $response["data"] = array_values($final_array);
            }else{
                $response["status"] = 0;
                $response["message"] = "No app register with this number";
            }

        }

        Custom::sendResponse($response);
        exit();
    }

	public static function pine_set_current_token()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $doctor_id = isset($data['doctor_id'])?base64_decode($data['doctor_id']):0;
        $thin_app_id = isset($data['thin_app_id'])?base64_decode($data['thin_app_id']):0;
        $token_number = isset($data['token_number'])?$data['token_number']:0;
        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else if (empty($token_number)) {
            $response['status'] = 0;
            $response['message'] = 'Please enter token number';
        }else {
            $response['status'] = 1;
            $response['message'] = "Update Success";
            $res = Custom::fortisSetTokenMenual($thin_app_id,$doctor_id,$token_number);
            $response['current_token'] = @$res['current']['token_number'];
        	Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id));
        	

        }
        echo json_encode($response);die;
    }

    /* IoT button api start */

    public static function IoT_close_token()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
        $appointment_id = isset($data['token_id'])?base64_decode($data['token_id']):0;

        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else{

            if($appointment_id==0){
                $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id);
                $response['current_token'] = $res['current']['token_number'];
                $response['patient_name'] = $res['current']['patient_name'];
                $response['token_id'] = base64_encode($res['current']['appointment_id']);
                $response['status'] = 1;
                $response['message'] = "Current Token Found";
                echo json_encode($response);die;
            }

            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if($appointment_data){
                $thin_app_id = $appointment_data['thinapp_id'];
                $doctor_id = $appointment_data['appointment_staff_id'];
                $post = array();
                $admin_data = Custom::get_thinapp_admin_data($appointment_data['thinapp_id']);
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $admin_data['id'];
                $post['thin_app_id'] = $admin_data['thinapp_id'];
                $post['appointment_id'] = $appointment_id;
                $result = json_decode(WebservicesFunction::close_appointment($post, true), true);
                $result['current_token'] = "0";
                if ($result['status'] == 1) {

                    $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                    $response['status'] = 1;
                    $response['message'] = "Appointment not found";
                    $response['current_token'] = $res['current']['token_number'];
                    $response['token_id'] = base64_encode($res['current']['appointment_id']);
                    $response['patient_name'] = $res['current']['patient_name'];
                	
                    Custom::sendResponse($response);
                	Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id));
                    Custom::close_appointment_notification($result['notification_array']);die;

                } else {
                    Custom::sendResponse($result);die;
                }
            }else{
                $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
                $result['current_token'] = $res['current']['token_number'];
                $result['token_id'] = base64_encode($res['current']['appointment_id']);
                $response['status'] = 1;
                $response['message'] = "Appointment not found";
                echo json_encode($response);die;
            }
        }

        echo json_encode($response);die;
    }

    public static function IoT_skip_token()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
        $appointment_id = isset($data['token_id'])?base64_decode($data['token_id']):0;

        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else {
            if ($appointment_id == 0) {
                $res = Custom::fortisUpdateToken($thin_app_id, $doctor_id);
                $response['current_token'] = $res['current']['token_number'];
                $response['patient_name'] = $res['current']['patient_name'];
                $response['token_id'] = base64_encode($res['current']['appointment_id']);
                $response['status'] = 1;
                $response['message'] = "Current Token Found";
                echo json_encode($response);
                die;
            }
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if ($appointment_data) {
                $thin_app_id = $appointment_data['thinapp_id'];
                $doctor_id = $appointment_data['appointment_staff_id'];

                $connection = ConnectionUtil::getConnection();
                $skip_tracker = 'YES';
                $counter = '';
                $sql = "UPDATE appointment_customer_staff_services SET referred_by=?, skip_tracker = ? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sss', $counter, $skip_tracker, $appointment_id);
                if ($stmt->execute()) {
                    $result['status'] = 1;
                    $result['message'] = 'Appointment skipped successfully';
                    $is_update = Custom::updateTokenOnAction($thin_app_id, $doctor_id, $appointment_data['queue_number']);
                    $res = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);
                    $result['current_token'] = $res['current']['token_number'];
                    $result['token_id'] = base64_encode($res['current']['appointment_id']);
                    $result['patient_name'] = $res['current']['patient_name'];
                	Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id));
                } else {
                    $result['status'] = 1;
                    $result['message'] = 'Sorry, token could not skipped';
                }


                echo json_encode($result);
                die;
            } else {
                $response['status'] = 0;
                $response['message'] = "Appointment not found";
                echo json_encode($response);
                die;
            }
        }

        echo json_encode($response);

    }

    public static function IoT_send_to_billing_counter()
    {

        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
        $appointment_id = isset($data['token_id'])?base64_decode($data['token_id']):0;
        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else {
            if ($appointment_id == 0) {
                $res = Custom::fortisUpdateToken($thin_app_id, $doctor_id);
                $response['current_token'] = $res['current']['token_number'];
                $response['patient_name'] = $res['current']['patient_name'];
                $response['token_id'] = base64_encode($res['current']['appointment_id']);
                $response['status'] = 1;
                $response['message'] = "Current Token Found";
                echo json_encode($response);
                die;
            }
            $appointment_data = Custom::get_appointment_by_id($appointment_id);
            if ($appointment_data) {

                $thin_app_id = $appointment_data['thinapp_id'];
                $doctor_id = $appointment_data['appointment_staff_id'];
                $counter = '';
                $connection = ConnectionUtil::getConnection();
                $checkin_type = 'BILLING_CHECKIN';
                $send_to_lab_datetime = Custom::created();
                $sql = "UPDATE appointment_customer_staff_services SET send_to_lab_datetime = ?, referred_by =?, patient_queue_type =? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssss', $send_to_lab_datetime, $counter, $checkin_type, $appointment_id);
                if ($stmt->execute()) {
                    $result['status'] = 1;
                    $result['message'] = 'Token sent to billing counter successfully';
                    $is_update = Custom::updateTokenOnAction($thin_app_id, $doctor_id, $appointment_data['queue_number']);
                    $res = Custom::fortisGetCurrentToken($thin_app_id, $doctor_id);
                    $result['current_token'] = $res['current']['token_number'];
                    $result['token_id'] = base64_encode($res['current']['appointment_id']);
                    $result['patient_name'] = $res['current']['patient_name'];
                } else {
                    $result['status'] = 0;
                    $result['message'] = 'Sorry, unable to send appointment to billing counter';
                }


                echo json_encode($result);
                die;
            } else {
                $result['status'] = 0;
                $result['message'] = "Appointment not found";
                echo json_encode($result);
                die;
            }
        }
        echo json_encode($response);
        die;

    }

  	public static function IoT_current_token()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
    	$token = isset($data['token'])?$data['token']:'';
    	$response =array();    
    
        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else {
            
        	$res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            $response['current_token'] = $res['current']['token_number'];
            $response['patient_name'] = $res['current']['patient_name'];
            $response['token_id'] = base64_encode($res['current']['appointment_id']);
        
            $response['status'] = 1;
            $response['message'] = "Current Token Found";
        	$response['activeTokens']  =  "";
        
        	if($token==0 && Custom::isCustomizedAppId($thin_app_id)){
                $doctor_ids =isset($data['doctor_ids'])?$data['doctor_ids']:"";
                $res = Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$doctor_id,'play'=>false,'doctor_ids'=>$doctor_ids));
            	if(!empty($doctor_ids)){
                    $doctor_ids = explode(",",$data['doctor_ids']);
                    $doctor_tokens =array();
                    foreach ($doctor_ids as $key => $d_id){
                        $res = Custom::fortisGetCurrentToken($thin_app_id,$d_id);
                        $doctorData = Custom::get_doctor_by_id($d_id,$thin_app_id);
                        $doctor_tokens[] = array('tId'=>$d_id,'tNumber'=>$res['current']['token_number'],'dName'=>$doctorData['name']);
                    }
                    $response['activeTokens']  =  json_encode($doctor_tokens);
                }
            
            }
        	
            
        }
        echo json_encode($response);die;
    }

    public static function IoT_previous_token()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
    	$login_id = isset($data['user_id'])?$data['user_id']:0;
        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else {
            $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            $response['current_token'] = $res['current']['token_number'];
            $response['patient_name'] = $res['current']['patient_name'];
            $response['token_id'] = base64_encode($res['current']['appointment_id']);
            if($res['current']['token_number'] > 1){
                $token = $res['current']['token_number']-1;
                $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id,true);
                $response['current_token'] = $res['current']['token_number'];
                $response['patient_name'] = $res['current']['patient_name'];
                $response['token_id'] = base64_encode($res['current']['appointment_id']);
            	$res = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,"PREVIOUS");
          
            }
            $response['status'] = 1;
            $response['message'] = "Token Update";


        }
        Custom::sendResponse($response);
        Custom::send_process_to_background();
        Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id,'play'=>false));
    	
    	die;
    
    }

    public static function IoT_next_token()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
        $status = isset($data['status'])?$data['status']:'CLOSED';
        $login_id = isset($data['user_id'])?$data['user_id']:0;
        $token = isset($data['token'])?$data['token']:0;

        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else {
            $reloadTracker = false;
            $playSound = true;
        	$billingTokenString = "";
            $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            $response['current_token'] = $res['current']['token_number'];
            $response['patient_name'] = $res['current']['patient_name'];
            $response['token_id'] = base64_encode($res['current']['appointment_id']);
            $doctorData = Custom::get_doctor_by_id($doctor_id);
            $counterType  =$doctorData['counter_booking_type'];
            if($counterType=='BILLING'){
                $status ="SEND_TO_BILLING_COUNTER";
            }
            $res = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,$status);
            /* update next token start */
            $status = ($status=="CLOSED")?"CLOSE":"SKIP";
            
            if(!empty($token)){
                $res = Custom::fortisSetTokenMenual($thin_app_id,$doctor_id,$token);
            }else{
            	$res = Custom::fortisUpdateToken($thin_app_id,$doctor_id,false,$status);
            }
            $response['current_token'] = $res['current']['token_number'];
            $response['patient_name'] = $res['current']['patient_name'];
            $response['token_id'] = base64_encode($res['current']['appointment_id']);
            $res = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,"PLAY");
            $response['status'] = 1;
            $response['message'] = "Token Updated";
        }
        Custom::sendResponse($response);
        Custom::send_process_to_background();
        Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id,'play'=>$playSound));
    	
        die;


    }

    public static function IoT_play_current_token()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
    	$login_id = isset($data['user_id'])?$data['user_id']:0;
    
        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else {
            $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            $response['current_token'] =$token = $res['current']['token_number'];
            $response['patient_name'] = $res['current']['patient_name'];
            $response['token_id'] = base64_encode($res['current']['appointment_id']);
        	$response['status'] = 1;
            $response['message'] = "Current token voice played successfully";

        }
       	Custom::sendResponse($response);
        Custom::send_process_to_background();
        if(@$response['current_token'] > 0){
            $res = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,"PLAY");
            $doctor_ids =isset($data['doctor_ids'])?$data['doctor_ids']:"";
            Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id,'doctor_ids'=>$doctor_ids));
        }
    	
    	die;
    }

    /* IoT button api end */

	 /* THIS CODE IS FOR EHCC */
    public static function ChangeCounterType()
    {
        $request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $thin_app_id = isset($data['thin_app_id'])?$data['thin_app_id']:'';
        $doctor_id = isset($data['doctor_id'])?$data['doctor_id']:'';
        $status = isset($data['status'])?$data['status']:'';
        $login_id = isset($data['user_id'])?$data['user_id']:0;

        if (empty($thin_app_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid thin_app_id';
        } else if (empty($doctor_id)) {
            $response['status'] = 0;
            $response['message'] = 'Invalid Doctor Id';
        }else if (!in_array($status,array('BILLING','PAYMENT','BOOKING'))) {
            $response['status'] = 0;
            $response['message'] = 'Invalid counter status';
        }else {
            $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            $response['current_token'] = $res['current']['token_number'];
            $response['patient_name'] = $res['current']['patient_name'];
            $response['token_id'] = base64_encode($res['current']['appointment_id']);
            $doctorData = Custom::get_doctor_by_id($doctor_id);
            $counterType  =$doctorData['counter_booking_type'];
            $log_status = 'ASSIGN_COUNTER';
            if($status=='BILLING'){
                $log_status = "CHANGE_TO_BILLING_COUNTER";
            }else if($status=='PAYMENT'){
                $log_status = "CHANGE_TO_PAYMENT_COUNTER";
            }else if($status=='BOOKING'){
                $log_status = "CHANGE_TO_BOOKING_COUNTER";
            }

            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $sql = "update appointment_staffs set counter_booking_type = ?, modified=? where id = ?";
            $stmt_df = $connection->prepare($sql);
            $created = Custom::created();
            $stmt_df->bind_param('sss', $status, $created, $doctor_id);
            if ($stmt_df->execute()){
                $res = Custom::delete_doctor_cache($doctor_id);
                $res = Custom::insertTokenLog($thin_app_id,$doctor_id,$login_id,$log_status);
                $response['status'] = 1;
                $response['message'] = "Counter status Updated";
            }else{
                $response['status'] = 0;
                $response['message'] = "Not updated";
            }



        }
        Custom::sendResponse($response);
        Custom::send_process_to_background();
        Custom::emitSocet(array('doctor_id'=>$doctor_id,'thin_app_id'=>$thin_app_id,'loadBillingList'=>true));
        die;


    }

}