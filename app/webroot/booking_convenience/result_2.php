<?php
    error_reporting(0);
    ini_set('display_errors', 0);
    ignore_user_abort(true);
    set_time_limit(0);
    $baseUrl = "https://mengage.in/doctor/";
    $orderIdRow = $orderId = $_POST['orderId'];
    $orderIdArr = explode('-',$orderId);
    $orderId = $orderIdArr[0];
    $orderAmount = $_POST['orderAmount'];
    $referenceId = $_POST['referenceId'];
    $txStatus = $_POST['txStatus'];
    $paymentMode = $_POST['paymentMode'];
    $txMsg = $_POST['txMsg'];
    $txTime = $_POST['txTime'];
	
	if($txStatus == 'SUCCESS')
	{
        include_once '../webservice/WebservicesFunction.php';
	    include_once '../webservice/ConnectionUtil.php';
        include_once '../webservice/Custom.php';
        $connection = ConnectionUtil::getConnection();






        $sql = "SELECT `appointment_customer_staff_services`.`thinapp_id`,`appointment_customer_staff_services`.`appointment_service_id`,`appointment_customer_staff_services`.`slot_time`,`appointment_customer_staff_services`.`id`,`appointment_customer_staff_services`.`booking_convenience_fee`,`appointment_customer_staff_services`.`status`,`appointment_customer_staff_services`.`booking_convenience_fee_restrict_ivr`,`appointment_customer_staff_services`.`appointment_booked_from`,`appointment_customer_staff_services`.`booking_doctor_share_percentage`,`appointment_customer_staff_services`.`booking_doctor_share_fee`,`appointment_customer_staff_services`.`booking_payment_getway_fee_percentage`,`appointment_customer_staff_services`.`booking_payment_getway_fee`,`appointment_customer_staff_services`.`booking_mengage_share_fee`,`appointment_customer_staff_services`.`appointment_staff_id`,`appointment_customer_staff_services`.`appointment_customer_id`,`appointment_customer_staff_services`.`appointment_address_id`,`appointment_customer_staff_services`.`booking_date`,`appointment_customer_staff_services`.`children_id`,`appointment_customer_staff_services`.`appointment_datetime`,`appointment_customer_staff_services`.`queue_number`,IFNULL(`appointment_customers`.`mobile`,`childrens`.`mobile`) AS `mobile` FROM `appointment_customer_staff_services` LEFT JOIN `appointment_customers` ON (`appointment_customer_staff_services`.`appointment_customer_id` = `appointment_customers`.`id`) LEFT JOIN `childrens` ON (`appointment_customer_staff_services`.`children_id` = `childrens`.`id`) WHERE `appointment_customer_staff_services`.`id` = '".$orderId."' LIMIT 1";
        $list = $connection->query($sql);
        $appointmentData = mysqli_fetch_assoc($list);
        $date = date("Y-m-d H:i:s");
        $thin_app_id = $thinappID = $appointmentData['thinapp_id'];
        $appointment_id = $appointmentCustomerStaffServiceID = $appointmentData['id'];
        $bookingConvenienceFee = $appointmentData['booking_convenience_fee'];
        $bookingDoctorSharePercentage = $appointmentData['booking_doctor_share_percentage'];
        $bookingDoctorShareFee = $appointmentData['booking_doctor_share_fee'];
        $bookingPaymentGetwayFeePercentage = $appointmentData['booking_payment_getway_fee_percentage'];
        $bookingPaymentGetwayFee = $appointmentData['booking_payment_getway_fee'];
        $bookingMengageShareFee = $appointmentData['booking_mengage_share_fee'];
        $bookingConvenienceFeeRestrictIvr = $appointmentData['booking_convenience_fee_restrict_ivr'];

        $appointment_booked_from = $appointmentData['appointment_booked_from'];
        $appointmentCustomerID = $appointmentData['appointment_customer_id'];
        $childrenID = $appointmentData['children_id'];
        $appointment_datetime = $appointmentData['appointment_datetime'];
        $queue_number1 = $queue_number = $appointmentData['queue_number'];
        $mobile = $appointmentData['mobile'];
        $status = $appointmentData['status'];
        $appointmentStaffID = $appointmentData['appointment_staff_id'];
        $appointmentAddressID = $appointmentData['appointment_address_id'];
        $bookingDate = $appointmentData['booking_date'];
        $slotTime = $appointmentData['slot_time'];
        $appointmentServiceID = $appointmentData['appointment_service_id'];





        if($status == 'CANCELED')
        {
            $chkUniqueSql = "SELECT * FROM `appointment_customer_staff_services` WHERE `appointment_staff_id` = '".$appointmentStaffID."' AND `appointment_datetime` = '".$appointment_datetime."' AND `queue_number` = '".$queue_number."' AND (`appointment_customer_id` <> '".$appointmentCustomerID."' OR `children_id` <> '".$childrenID."')";
            $chkUniqueRS = $connection->query($chkUniqueSql);
            if($chkUniqueRS->num_rows)
            {
                $count = Custom::get_sub_token_number($appointmentStaffID, $appointmentServiceID, $appointmentAddressID, $slotTime, $bookingDate);
                $queue_number1 = $queue_number = $queue_number + (($count + 1) / 10);
                $updateSql = "UPDATE `appointment_customer_staff_services` SET `cancel_date_time` = '0000-00-00 00:00:00',`cancel_by_user_id` = '0',`status` = 'NEW',`queue_number`='".$queue_number."',`sub_token` = 'YES',`modified` = '".$date."' WHERE `id` = '".$appointmentCustomerStaffServiceID."'";
                $connection->query($updateSql);
            }
            else
            {
                $updateSql = "UPDATE `appointment_customer_staff_services` SET  `cancel_date_time` = '0000-00-00 00:00:00',`cancel_by_user_id` = '0',`status` = 'NEW',`modified` = '".$date."' WHERE `id` = '".$appointmentCustomerStaffServiceID."'";
                $connection->query($updateSql);
            }
        }

        $selectSql = "SELECT * FROM `booking_convenience_fee_details` WHERE `appointment_customer_staff_service_id` = '".$appointmentCustomerStaffServiceID."' LIMIT 1";
        $selectRS = $connection->query($selectSql);
        if($selectRS->num_rows)
        {
            $prm = array(
                "bookingConvenienceFee"=>$orderAmount,
                "datetime"=>$appointment_datetime,
                "queue"=>$queue_number1,
                "tx_time"=>$txTime
            );
            $prm = base64_encode(json_encode($prm));
            
        }
    	else
        {

            $updatePaymentInAppointmentSql = "UPDATE `appointment_customer_staff_services` SET `is_paid_booking_convenience_fee` = 'YES', `modified` = '".$date."' WHERE `id` = '".$appointmentCustomerStaffServiceID."'";
            $connection->query($updatePaymentInAppointmentSql);


            $insertPaymentSql = "INSERT INTO `booking_convenience_fee_details` SET `thinapp_id` = '".$thinappID."',`appointment_customer_staff_service_id` = '".$appointmentCustomerStaffServiceID."',`appointment_customer_id` = '".$appointmentCustomerID."',`children_id` = '".$childrenID."',`amount` = '".$orderAmount."',`booking_convenience_fee` = '".$bookingConvenienceFee."',`booking_doctor_share_percentage` = '".$bookingDoctorSharePercentage."',`booking_doctor_share_fee` = '".$bookingDoctorShareFee."',`booking_payment_getway_fee_percentage` = '".$bookingPaymentGetwayFeePercentage."',`booking_payment_getway_fee` = '".$bookingPaymentGetwayFee."',`booking_mengage_share_fee` = '".$bookingMengageShareFee."',`reference_id` = '".$referenceId."',`tx_status` = '".$txStatus."',`payment_mode` = '".$paymentMode."',`tx_msg` = '".$txMsg."',`tx_time` = '".$txTime."',`created_by_user_id` = '0',`payment_account` = 'MENGAGE',`is_settled` = 'NO',`status` = 'ACTIVE',`created` = '".$date."',`modified` = '".$date."'";

            $connection->query($insertPaymentSql);
            $insertID = $connection->insert_id;
            $uniqueID = str_pad($insertID, 10, "0", STR_PAD_LEFT);
            $query = "UPDATE `booking_convenience_fee_details` SET `unique_id` = '$uniqueID' WHERE `id` = '$insertID'";


            $connection->query($query);



            $receiptUrl = $baseUrl."homes/receipt/".base64_encode($appointmentCustomerStaffServiceID);
            $message = "Payment of INR ".$orderAmount." received towards token booking convenience fees. Click on link to find receipt. ".$receiptUrl;
            Custom::send_single_sms($mobile, ($message), 134, false, false);

			$currDate = date('Y-m-d H:i:s');
            $updateBookingOrder = "UPDATE `booking_convenience_orders` SET `status` = 'PAID', `modified`= '".$currDate."' WHERE `order_id` = '".$orderIdRow."'";
            $connection->query($updateBookingOrder);




            $appointment_id = $appointmentCustomerStaffServiceID;


            $appointment_data = WebservicesFunction::get_appointment_all_data_id($appointment_id);
            $app_date = date('d/m/Y h:i A', strtotime($appointment_data['appointment_datetime']));
            $app_time = date('h:i A', strtotime($appointment_data['appointment_datetime']));
            $label_date = date('Y-m-d', strtotime($appointment_data['appointment_datetime']));
            $day_label = Custom::get_date_label($label_date);
            $staff_name = trim($appointment_data['staff_name']);
            $doctor_id = $appointment_data['appointment_staff_id'];
            $queue_number = Custom::create_queue_number($appointment_data);
            $queue_number =  "Token Number : $queue_number";
            $day_label = (strtoupper($day_label) == "TODAY") ? $day_label . ', Time ' . $app_time : $day_label . ', ' . $app_date;
            $message = "Hi " . Custom::get_doctor_first_name($staff_name) . ", appointment, $queue_number with patient " . Custom::get_string_first_name($appointment_data['cus_name']) . ', has been confirmed on ' . $day_label . '.';
            $option = array(
                'thinapp_id' => $thin_app_id,
                'customer_id' => 0,
                'staff_id' => $appointment_data['appointment_staff_id'],
                'service_id' => $appointment_data['appointment_service_id'],
                'channel_id' => 0,
                'role' => "STAFF",
                'flag' => 'APPOINTMENT',
                'title' => "New Appointment Request",
                'message' => mb_strimwidth($message, 0, 250, '...'),
                'description' => "",
                'chat_reference' => '',
                'module_type' => 'APPOINTMENT',
                'module_type_id' => $appointment_id,
                'doctor_id' => $doctor_id,
                'firebase_reference' => ""
            );
            $background['notification'][0]['data'] = $option;
            $background['notification'][0]['user_id'] = $appointment_data['staff_user_id'];
            $background['notification'][0]['send_to'] = "DOCTOR";
            $day_label = (strtoupper($day_label) == "TODAY") ? $day_label . ', Time ' . $app_time : $day_label . ', ' . $app_date;
            //$message = "Hi " . Custom::get_string_first_name($appointment_data['cus_name']) . ", appointment, $queue_number with " . Custom::get_doctor_first_name($staff_name) . ', has been confirmed on ' . $day_label.'. Please plan to come 15 min before.';
            $lbl_date = date('d-m-Y', strtotime($appointment_data['appointment_datetime']));
            $lbl_time = date('h:i A', strtotime($appointment_data['appointment_datetime']));
            $time_string = ($appointment_data['show_appointment_time'] == "YES" && $appointment_data['emergency_appointment']=="NO" && $appointment_data['custom_token']=="NO") ? " Time:$lbl_time," :"" ;
            $queue_number = ($appointment_data['show_appointment_token'] == "NO") ? "" : $queue_number;
            $message = "Appointment booked for " . Custom::get_string_first_name($appointment_data['cus_name']) . ".$queue_number,$time_string Date: $lbl_date.";

            $option = array(
                'thinapp_id' => $thin_app_id,
                'staff_id' => 0,
                'customer_id' => $appointment_data['appointment_customer_id'],
                'service_id' => $appointment_data['appointment_service_id'],
                'channel_id' => 0,
                'role' => "CUSTOMER",
                'flag' => 'APPOINTMENT',
                'title' => "New Appointment Request",
                'message' => mb_strimwidth($message, 0, 250, '...'),
                'description' => "",
                'chat_reference' => '',
                'module_type' => 'APPOINTMENT',
                'module_type_id' => $appointment_id,
                'firebase_reference' => "",
                'doctor_id' => $doctor_id
            );
            $background['notification'][1]['data'] = $option;
            $background['notification'][1]['user_id'] = $appointment_data['customer_user_id'];
            $background['notification'][1]['send_to'] = "USER";
            $background['sms'][] = array(
                'message' => $message,
                'mobile' => $appointment_data['customer_mobile'],
                'send_to' => "USER"
            );

            if(!empty($appointmentCustomerID) || $appointmentCustomerID != 0)
            {
                $pat_cus_id = $appointmentCustomerID;
                $user_type = 'CUSTOMER';
            }
            else
            {
                $pat_cus_id = $childrenID;
                $user_type = 'CHILDREN';
            }
            $notification_data = array(
                'background' => $background,
                'thin_app_id' => $thin_app_id,
                'thinapp_id' => $thin_app_id,
                'doctor_id' => $doctor_id,
                'user_type' => $user_type,
                'patient_id' => @$pat_cus_id,
                'booking_request_from'=>$appointment_booked_from,
                'address_id'=>$appointment_data['appointment_address_id'],
                'appointment_id'=>@$appointment_id
            );
            Custom::send_book_appointment_notification($notification_data);
            $prm = array(
                "bookingConvenienceFee"=>$orderAmount,
                "datetime"=>$appointment_datetime,
                "queue"=>$queue_number1,
                "tx_time"=>$txTime
            );
            $prm = base64_encode(json_decode($prm));

            

        }
    	header("Location: ".$baseUrl."booking_convenience/success.php?prm=".$prm);
    	die();

	}
	else
	{


        include_once '../webservice/WebservicesFunction.php';
        include_once '../webservice/ConnectionUtil.php';
        include_once '../webservice/Custom.php';
        $connection = ConnectionUtil::getConnection();

        $sql = "SELECT `appointment_customer_staff_services`.`appointment_datetime`, `appointment_customer_staff_services`.`id`, `appointment_customer_staff_services`.`thinapp_id`,`appointment_customer_staff_services`.`queue_number` FROM `appointment_customer_staff_services` WHERE `appointment_customer_staff_services`.`id` = '".$orderId."' LIMIT 1";
        $list = $connection->query($sql);
        $appointmentData = mysqli_fetch_assoc($list);

        $appointment_datetime = $appointmentData['appointment_datetime'];
        $queue_number1 = $queue_number = $appointmentData['queue_number'];
        $thinapp_id = $appointmentData['thinapp_id'];
        $appointment_id = $appointmentData['id'];
    
    
    $currDate = date('Y-m-d H:i:s');

            $updateBookingOrder = "UPDATE `booking_convenience_orders` SET `status` = 'FAILED', `modified`= '".$currDate."' WHERE `order_id` = '".$orderIdRow."'";
      $connection->query($updateBookingOrder);

        /*$post['app_key'] = MBROADCAST_APP_NAME;
        $post['user_id'] = MBROADCAST_APP_ADMIN_ID;
        $post['thin_app_id'] = $thinapp_id;
        $post['appointment_id'] = $appointment_id;
        $post['message'] = '';
        $post['cancel_by'] = "DOCTOR";
        $res = WebservicesFunction::cancel_appointment($post, false ,true); */

        $prm = array(
            "bookingConvenienceFee"=>$orderAmount,
            "datetime"=>$appointment_datetime,
            "queue"=>$queue_number1,
            "tx_time"=>$txTime
        );
        $prm = base64_encode(json_decode($prm));

		header("Location: ".$baseUrl."booking_convenience/failer.php?prm=".$prm);
	}
?>