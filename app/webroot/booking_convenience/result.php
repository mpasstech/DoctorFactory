<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
error_reporting(0);
ignore_user_abort(true);
set_time_limit(0);
include_once '../constant.php';
$baseUrl = SITE_PATH;

$orderIdRow = $orderId = $_POST['orderId'];
$orderIdArr = explode('-',$orderId);
$orderId = $orderIdArr[0];
$orderAmount = $_POST['orderAmount'];
$referenceId = $_POST['referenceId'];
$txStatus = $_POST['txStatus'];
$paymentMode = $_POST['paymentMode'];
$txMsg = $_POST['txMsg'];
$txTime = $_POST['txTime'];
$request_from = (isset($_REQUEST['rf']) && !empty($_REQUEST['rf']))?$_REQUEST['rf']:'doctor';

if($txStatus == 'SUCCESS')
{
    include_once '../webservice/WebservicesFunction.php';
    include_once '../webservice/WebServicesFunction_2_3.php';
    include_once '../webservice/ConnectionUtil.php';
    include_once '../webservice/Custom.php';
    $connection = ConnectionUtil::getConnection();
    $appointment_refund = false;
    $refundId = '';

    $sql = "SELECT t.pay_clinic_visit_fee_online, acss.appointment_booked_from, t.booking_convenience_fee_emergency, acss.sub_token,acss.custom_token,acss.has_token, acss.emergency_appointment, acss.drive_folder_id, staff.mobile AS doctor_mobile, acss.appointment_booked_from, acss.consulting_type, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat, acss.emergency_appointment, acss.custom_token, staff.show_appointment_token, staff.show_appointment_time,  acss.amount, IFNULL(ac.mobile,c.mobile) as patient_mobile, IFNULL(bco.convence_for,'OFFLINE') AS convence_for , acss.booking_validity_attempt, staff.user_id, service.service_amount AS consulting_fee, staff.is_online_consulting, acss.appointment_datetime,acss.queue_number, acss.id, acss.thinapp_id, t.booking_convenience_gst_percentage, acss.appointment_booked_from,acss.appointment_customer_id,acss.children_id,acss.appointment_datetime,acss.queue_number,IFNULL(ac.mobile,c.mobile) AS mobile, acss.appointment_patient_name,acss.`status` ,acss.appointment_staff_id,acss.appointment_address_id,acss.booking_date,acss.slot_time,acss.appointment_service_id,  t.booking_convenience_fee, t.booking_doctor_share_percentage,t.booking_payment_getway_fee_percentage,t.booking_convenience_fee_restrict_ivr, t.booking_convenience_fee_terms_condition, scma.primary_owner_id,scma.primary_owner_share_percentage,scma.primary_mediator_id,scma.primary_mediator_share_percentage,scma.secondary_owner_id,scma.secondary_owner_share_percentage,scma.secondary_mediator_id,scma.secondary_mediator_share_percentage FROM appointment_customer_staff_services AS acss JOIN  thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id JOIN appointment_services AS service ON service.id = acss.appointment_service_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id left join smart_clinic_mediator_associate as scma on t.id = scma.thinapp_id LEFT JOIN booking_convenience_orders AS bco ON bco.appointment_customer_staff_service_id = acss.id where acss.id = $orderId limit 1";
    $list = $connection->query($sql);
    if ($list->num_rows) {

        $appointmentData = mysqli_fetch_assoc($list);
        $date = date("Y-m-d H:i:s");
        $thin_app_id = $thinappID = $appointmentData['thinapp_id'];
        $smart_clinic = (Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC'))?"YES":"NO";


        $appointment_id = $appointmentCustomerStaffServiceID = $appointmentData['id'];
        $appointment_booked_from = $appointmentData['appointment_booked_from'];
        $convenience_for = $appointmentData['convence_for'];
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
        $primaryOwnerId = !empty($appointmentData['primary_owner_id'])?$appointmentData['primary_owner_id']:0;
        $secondaryOwnerId = !empty($appointmentData['secondary_owner_id'])?$appointmentData['secondary_owner_id']:0;
        $primaryMediatorId = !empty($appointmentData['primary_mediator_id'])?$appointmentData['primary_mediator_id']:0;
        $secondaryMediatorId = !empty($appointmentData['secondary_mediator_id'])?$appointmentData['secondary_mediator_id']:0;
        $bookingConvenienceFee = $appointmentData['booking_convenience_fee'];
        $emergency_appointment = $appointmentData['emergency_appointment'];
        $doctor_online_consulting_fee = 0;

        if($appointmentData['consulting_type']=='VIDEO'){
            $bookingConvenienceFee =  $appointmentData['booking_convenience_fee_video'];
        }else if($appointmentData['consulting_type']=='AUDIO'){
            $bookingConvenienceFee = $appointmentData['booking_convenience_fee_audio'];
        }else if($appointmentData['consulting_type']=='CHAT'){
            $bookingConvenienceFee = $appointmentData['booking_convenience_fee_chat'];
        }else if($emergency_appointment=='YES'){
            $bookingConvenienceFee = $appointmentData['booking_convenience_fee_emergency'];
        }


        $bookingDoctorSharePercentage= $bookingMengageShareFee =$bookingDoctorShareFee =0;
        $bookingPaymentGetwayFeePercentage = $bookingPaymentGetwayFee =$bookingConvenienceGSTPercentage=$bookingConvenienceGSTFee=0;
        $primaryOwnerShareFee = $secondaryOwnerShareFee = $primaryMediatorShareFee = $secondaryMediatorShareFee=0;
        $primaryOwnerSharePercentage = $secondaryOwnerSharePercentage = $primaryMediatorSharePercentage = $secondaryMediatorSharePercentage=0;
        if($appointmentData['pay_clinic_visit_fee_online'] =='YES' || $convenience_for=="ONLINE" || $emergency_appointment=='YES' ){
            if($appointmentData['booking_validity_attempt']==1){
                $doctor_online_consulting_fee = $bookingDoctorShareFee = $appointmentData['amount'];
            }
            $bookingMengageShareFee  = $bookingConvenienceFee;
        }else{

            /* PAYMENT GETWAY FEE CALCULATION */
            $bookingPaymentGetwayFeePercentage = $appointmentData['booking_payment_getway_fee_percentage'];
            $bookingPaymentGetwayFee = (($appointmentData['booking_payment_getway_fee_percentage'] / 100) * $bookingConvenienceFee);
            $bookingPaymentGetwayFee = Custom::splitAfterDecimal($bookingPaymentGetwayFee);


            /* GST CALCULATION  FEE*/
            $bookingConvenienceGSTPercentage = $appointmentData['booking_convenience_gst_percentage'];
            $left_amount = ($bookingConvenienceFee-$bookingPaymentGetwayFee);
            $bookingConvenienceGSTFee = Custom::splitAfterDecimal((($bookingConvenienceGSTPercentage / 100) * $left_amount));



            $baseAmount = $bookingConvenienceFee-($bookingPaymentGetwayFee+$bookingConvenienceGSTFee);
            /* DOCTOR SHARE CALCULATION */
            $bookingDoctorSharePercentage = $appointmentData['booking_doctor_share_percentage'];
            $bookingDoctorShareFee = Custom::splitAfterDecimal((($bookingDoctorSharePercentage / 100) * $baseAmount));



            /* PRIMARY OWNER SHARE CALCULATION */
            if(!empty($primaryOwnerId)){
                $primaryOwnerSharePercentage = $appointmentData['primary_owner_share_percentage'];
                $primaryOwnerShareFee = Custom::splitAfterDecimal((($primaryOwnerSharePercentage / 100) * $baseAmount));
            }

            /* SECONDARY OWNER SHARE CALCULATION */
            if(!empty($secondaryOwnerId)){
                $secondaryOwnerSharePercentage = $appointmentData['secondary_owner_share_percentage'];
                $secondaryOwnerShareFee = Custom::splitAfterDecimal((($secondaryOwnerSharePercentage / 100) * $baseAmount));
            }

            /* PRIMARY MEDIATOR SHARE CALCULATION */
            if(!empty($primaryMediatorId)){
                $primaryMediatorSharePercentage = $appointmentData['primary_mediator_share_percentage'];
                $primaryMediatorShareFee = Custom::splitAfterDecimal((($primaryMediatorSharePercentage / 100) * $baseAmount));
            }

            /* SECONDARY MEDIATOR SHARE CALCULATION */
            if(!empty($primaryMediatorId)){
                $secondaryMediatorSharePercentage = $appointmentData['secondary_mediator_share_percentage'];
                $secondaryMediatorShareFee = Custom::splitAfterDecimal((($secondaryMediatorSharePercentage / 100) * $baseAmount));
            }

            $total_remaining_amount = ($bookingDoctorShareFee + $primaryOwnerShareFee+$secondaryOwnerShareFee+$primaryMediatorShareFee+$secondaryMediatorShareFee);
            $bookingMengageShareFee = ($baseAmount - $total_remaining_amount);

        }

        if($status == 'CANCELED')
        {
            $chkUniqueSql = "SELECT acss.id FROM appointment_customer_staff_services AS acss WHERE acss.appointment_staff_id = $appointmentStaffID AND acss.appointment_address_id =$appointmentAddressID AND acss.appointment_service_id = $appointmentServiceID AND acss.queue_number = '$queue_number1' AND acss.slot_time ='$slotTime' AND acss.status IN('NEW','CONFIRM','RESCHEDULE') AND DATE(acss.appointment_datetime) = '$bookingDate' AND acss.thinapp_id = $thin_app_id LIMIT 1";
            $chkUniqueRS = $connection->query($chkUniqueSql);
            if($chkUniqueRS->num_rows)
            {
                if($thin_app_id==607 || $thin_app_id==134){
                    $appointment_refund = true;
                }else{
                    $bookingDate = date('Y-m-d',strtotime($appointment_datetime));
                    $count = Custom::get_sub_token_number($appointmentStaffID, $appointmentServiceID, $appointmentAddressID, $slotTime, $bookingDate);
                    $queue_number1 = $queue_number = $queue_number + (($count + 1) / 10);
                    $updateSql = "UPDATE `appointment_customer_staff_services` SET `cancel_date_time` = '0000-00-00 00:00:00',`cancel_by_user_id` = '0',`status` = 'NEW',`queue_number`='".$queue_number."',`sub_token` = 'YES',`modified` = '".$date."' WHERE `id` = '".$appointmentCustomerStaffServiceID."'";
                    $connection->query($updateSql);
                }
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
                "queue"=>Custom::create_queue_number($appointmentData),
                "tx_time"=>$txTime,
                "is_online_consulting"=>$appointmentData['is_online_consulting'],
                "emergency_appointment"=>$appointmentData['emergency_appointment'],
                "appointment_booked_from"=>$appointmentData['appointment_booked_from'],
                "mobile"=>$appointmentData['mobile'],
                "thin_app_id"=>$appointmentData['thinapp_id'],
                "doctor_id"=>$appointmentData['appointment_staff_id'],
                "appointment_id"=>$appointment_id,
                "convenience_for"=>$convenience_for,
                'smart_clinic'=>$smart_clinic
            );
            $prm = base64_encode(json_encode($prm));

        }
        else
        {

            $error = false;

            if (Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC')) {
                $connection->autocommit(false);
                /* UPDATE APPOINTMENT TABLE PAYMENT STATUS FOR BOOKING CONVENIENCE FEE */
                $query = "update appointment_customer_staff_services set is_paid_booking_convenience_fee=?, modified=? where id = ?";
                $stmt_appointment = $connection->prepare($query);
                $is_paid_booking_convenience_fee ="YES";
                $stmt_appointment->bind_param('sss',$is_paid_booking_convenience_fee,$date,$appointmentCustomerStaffServiceID );

                /* INSERT BOOKING ORDER DETAIL */
                $sql = "SELECT id from booking_convenience_fee_details where  appointment_customer_staff_service_id = $appointment_id and thinapp_id = $thinappID limit 1";
                $list = $connection->query($sql);
                if (!$list->num_rows) {

                    $booking_convenience_order_id =0;
                    $sql = "SELECT id from booking_convenience_orders where  appointment_customer_staff_service_id = $appointment_id and thinapp_id = $thinappID limit 1";
                    $select_data = $connection->query($sql);
                    if ($select_data->num_rows) {
                        $booking_convenience_order_id = mysqli_fetch_assoc($select_data)['id'];
                    }

                    $created_by_user_id =0;
                    $payment_account='MENGAGE';
                    $is_settled='NO';
                    $status='ACTIVE';
                    $query = "insert into booking_convenience_fee_details  (booking_convenience_order_id,thinapp_id, appointment_customer_staff_service_id, appointment_customer_id, children_id, amount, booking_convenience_fee, booking_doctor_share_percentage, booking_doctor_share_fee, booking_payment_getway_fee_percentage, booking_payment_getway_fee, booking_mengage_share_fee, reference_id, tx_status, payment_mode, tx_msg, tx_time, created_by_user_id, payment_account, is_settled, status, created, modified, primary_owner_id, primary_owner_share_percentage, primary_owner_share_fee, primary_mediator_id, primary_mediator_share_percentage, primary_mediator_share_fee, secondary_owner_id, secondary_owner_share_percentage, secondary_owner_share_fee, secondary_mediator_id, secondary_mediator_share_percentage, secondary_mediator_share_fee,booking_convenience_gst_percentage,booking_convenience_gst_fee,doctor_online_consulting_fee) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                    $stmt_booking_detail = $connection->prepare($query);
                    $stmt_booking_detail->bind_param('ssssssssssssssssssssssssssssssssssssss', $booking_convenience_order_id,$thinappID,$appointment_id, $appointmentCustomerID,$childrenID,$orderAmount,$bookingConvenienceFee,$bookingDoctorSharePercentage,$bookingDoctorShareFee,$bookingPaymentGetwayFeePercentage,$bookingPaymentGetwayFee,$bookingMengageShareFee,$referenceId,$txStatus,$paymentMode,$txMsg,$txTime,$created_by_user_id,$payment_account,$is_settled,$status,$date,$date,$primaryOwnerId, $primaryOwnerSharePercentage, $primaryOwnerShareFee,$primaryMediatorId,$primaryMediatorSharePercentage,$primaryMediatorShareFee,$secondaryOwnerId,$secondaryOwnerSharePercentage,$secondaryOwnerShareFee,$secondaryMediatorId,$secondaryMediatorSharePercentage,$secondaryMediatorShareFee,$bookingConvenienceGSTPercentage,$bookingConvenienceGSTFee,$doctor_online_consulting_fee);
                    if($stmt_appointment->execute() && $stmt_booking_detail->execute()){

                        /* UPDATE UNIQUE ID FOR ORDER DETAIL TABLE */
                        $insertID = $stmt_booking_detail->insert_id;
                        $uniqueID = str_pad($insertID, 10, "0", STR_PAD_LEFT);
                        $query = "update booking_convenience_fee_details set unique_id =? where id = ?";
                        $stmt_update_detail = $connection->prepare($query);
                        $stmt_update_detail->bind_param('ss', $uniqueID,  $insertID);

                        /* UPDATE ORDER TABLE STATUS */
                        $order_status='PAID';
                        $query = "update booking_convenience_orders set status =?,modified=? where id = ?";
                        $stmt_update_order = $connection->prepare($query);
                        $stmt_update_order->bind_param('sss', $order_status, $currDate, $booking_convenience_order_id);
                        if($stmt_update_detail->execute() && $stmt_update_order->execute()){
                            $connection->commit();
                            $connection->autocommit(true);
                            $user_id = $appointmentData['user_id'];
                            if(empty($user_id)){
                                $user_id = Custom::get_thinapp_admin_data($thin_app_id)['id'];
                            }



                            if($appointmentData['pay_clinic_visit_fee_online'] =='YES' || $convenience_for=="ONLINE" || $emergency_appointment=='YES' ){
                                $post = array();
                                $post['app_key'] = APP_KEY;
                                $post['user_id'] = $user_id;
                                $post['thin_app_id'] = $thin_app_id;
                                $post['appointment_id'] = $appointment_id;
                                $post['status'] = "SUCCESS";
                                $post['transaction_id'] = $referenceId;
                                $result = json_decode(WebservicesFunction::update_appointment_payment_status($post, true), true);
                                if (!empty($result['notification_array'])) {
                                    //Custom::appointment_payment_notification($result['notification_array']);
                                }
                            }

                            if($appointment_refund===true){
                                $refund_reason = 'Your token booking time is expired';
                                $detail_data = Custom::getCashFreeOnlineAmount($appointmentCustomerStaffServiceID,$thin_app_id);
                                if (!empty($detail_data)) {
                                    $referenceId = $detail_data['refrence_id'];
                                    $medical_product_order_id = $detail_data['medical_product_order_id'];
                                    $amount = $detail_data['amount'];
                                    if(!empty($referenceId) && !empty($amount)){
                                        $result = json_decode(Custom::cashFreeRefund($connection,$thin_app_id,$user_id,$medical_product_order_id, $referenceId,$amount,$detail_data['booking_convenience_order_detail_id'],$refund_reason,$detail_data['payment_mode']),true);
                                        if($result['status']=='OK'){
                                            $refundId =$result['refundId'];
                                        }
                                    }
                                }


                            }else{
                                $background['sms'][] = array(
                                    'message' => '',
                                    'mobile' => $appointmentData['patient_mobile'],
                                    'send_to' => "USER"
                                );
                                $background['sms'][] = array(
                                    'message' => '',
                                    'mobile' => $appointmentData['doctor_mobile'],
                                    'send_to' => "DOCTOR"
                                );
                                $notification_data = array(
                                    'background' => $background,
                                    'thin_app_id' => $thin_app_id,
                                    'doctor_id' => $appointmentData['appointment_staff_id'],
                                    'user_type' => !empty($appointmentCustomerID)?'CUSTOMER':'CHILDREN',
                                    'patient_id' => !empty($appointmentCustomerID)?$appointmentCustomerID:$childrenID,
                                    'booking_request_from'=>$appointmentData['appointment_booked_from'],
                                    'address_id'=>$appointmentData['appointment_address_id'],
                                    'appointment_id'=>$appointment_id,
                                    'consulting_type'=>$appointmentData['consulting_type'],
                                    'drive_folder_id'=>$appointmentData['drive_folder_id']
                                );
                                Custom::send_process_to_background();
                                $result = Custom::send_book_appointment_notification($notification_data);
                            }



                        }else{
                            $error = true;
                            $connection->rollback();
                        }
                    }else{
                        $error = true;
                        $connection->rollback();
                    }


                }

            }else{

                $user_id = $appointmentData['user_id'];
                if(empty($user_id)){
                    $user_id = Custom::get_thinapp_admin_data($thin_app_id)['id'];
                }
                $post = array();
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $user_id;
                $post['thin_app_id'] = $thin_app_id;
                $post['appointment_id'] = $appointment_id;
                $post['status'] = "SUCCESS";
                $post['transaction_id'] = $referenceId;
                $result = json_decode(WebservicesFunction::update_appointment_payment_status($post, true), true);
                if ($appointment_refund===false && !empty($result['notification_array'])) {
                    Custom::sendSmartClinicNotification($appointment_booked_from,$appointmentData, $thin_app_id,$appointment_id,$convenience_for,$appointmentCustomerID,$childrenID);
                }

                if($appointment_refund===true){
                    $refund_reason = 'Your token booking time is expired';
                    $detail_data = Custom::getCashFreeOnlineAmount($appointmentCustomerStaffServiceID,$thin_app_id);
                    if (!empty($detail_data)) {
                        $referenceId = $detail_data['refrence_id'];
                        $medical_product_order_id = $detail_data['medical_product_order_id'];
                        $amount = $detail_data['amount'];
                        if(!empty($referenceId) && !empty($amount)){
                            $result = json_decode(Custom::cashFreeRefund($connection,$thin_app_id,$user_id,$medical_product_order_id, $referenceId,$amount,$detail_data['booking_convenience_order_detail_id'],$refund_reason,$detail_data['payment_mode']),true);
                            if($result['status']=='OK'){
                                $refundId =$result['refundId'];
                            }
                        }
                    }


                }
            }

            $prm = array(
                "bookingConvenienceFee"=>$orderAmount,
                "datetime"=>$appointment_datetime,
                "queue"=>Custom::create_queue_number($appointmentData),
                "tx_time"=>$txTime,
                "is_online_consulting"=>$appointmentData['is_online_consulting'],
                "emergency_appointment"=>$appointmentData['emergency_appointment'],
                "appointment_booked_from"=>$appointmentData['appointment_booked_from'],
                "mobile"=>$appointmentData['mobile'],
                "thin_app_id"=>$appointmentData['thinapp_id'],
                "doctor_id"=>$appointmentData['appointment_staff_id'],
                "appointment_id"=>$appointment_id,
                "convenience_for"=>$convenience_for,
                'smart_clinic'=>$smart_clinic
            );
            $prm = base64_encode(json_encode($prm));


        }

        if($appointment_refund===true){
            if($thin_app_id==607){
                $callRes = Custom::tokenCanceledCall($mobile,"01414937900","589419");
            }

            $doctorData = Custom::get_doctor_by_id($appointmentData['appointment_staff_id']);
            $doctor_name = $doctorData['name'];
            $token_number = $appointmentData['queue_number'];
            $app_date =date('d/m/Y',strtotime($appointmentData['appointment_datetime']));
            $cancelWhats = "अपॉइंटमेंट टोकन निरस्त\n\nडॉक्टर का नाम  :- $doctor_name\nदिनांक   :- $app_date\nटोकन  :- $token_number\n\nशमा करे  डॉक्टर $doctor_name का अपॉइंटमेंट टोकन बुक नहीं हो पाया हैं ! अगर अपने टोकन फी का भुक्तान किया हैं तो  कृपया  निश्चिन्त रहिये आपको टोकन फी  सात दिनों के भीतर वापस कर दी जाएगी |\nधन्यवाद|";
            $res = Custom::sendWhatsappSms($mobile,$cancelWhats,$cancelWhats);


            $controller ="doctor/index/";
            $return['queue_number']=$queue_number;
            $return['date']=date('d/m/Y',strtotime($appointmentData['appointment_datetime']));
            $return['time']=date('h:i A',strtotime($appointmentData['appointment_datetime']));
            $return['amount']=$orderAmount;
            $return['referenceId']=$referenceId;
            $return['refundId']=$refundId;
            header("Location: ".$baseUrl."booking_convenience/refunded.php?prm=".base64_encode(json_encode($return)));die;
        }

        if($appointmentData['appointment_booked_from']=='DOCTOR_PAGE'){
            $return['status_type']="success";
            $return['id']=$appointmentData['id'];
            $return['appointment_datetime']=$appointmentData['appointment_datetime'];
            $return['show_appointment_time']=$appointmentData['show_appointment_time'];
            $return['emergency_appointment']=$appointmentData['emergency_appointment'];
            $return['custom_token']=$appointmentData['custom_token'];
            $return['show_appointment_token']=$appointmentData['show_appointment_token'];
            $return['appointment_staff_id']=$appointmentData['appointment_staff_id'];
            $return['cus_name']=$appointmentData['appointment_patient_name'];
            $return['queue_number']=Custom::create_queue_number($appointmentData);
            $controller = ($request_from=='qutot')?"qutot/web_app/":"doctor/index/";
            header("Location: ".$baseUrl.$controller."?t=".base64_encode($appointmentData['appointment_staff_id'])."&a=".base64_encode(json_encode($return)));
        }else{
            header("Location: ".$baseUrl."booking_convenience/success.php?prm=".$prm);
        }
        die();

    }else{
        die('Invalid Appointment');
    }






}
else
{


    include_once '../webservice/WebservicesFunction.php';
    include_once '../webservice/ConnectionUtil.php';
    include_once '../webservice/Custom.php';
    $connection = ConnectionUtil::getConnection();

    $sql = "SELECT acss.sub_token, acss.has_token, acss.emergency_appointment, acss.custom_token, staff.show_appointment_token, staff.show_appointment_time,  acss.amount, IFNULL(ac.mobile,c.mobile) as patient_mobile, IFNULL(bco.convence_for,'OFFLINE') AS convence_for , acss.booking_validity_attempt, staff.user_id, service.service_amount AS consulting_fee, staff.is_online_consulting, acss.appointment_datetime,acss.queue_number, acss.id, acss.thinapp_id, t.booking_convenience_gst_percentage, acss.appointment_booked_from,acss.appointment_customer_id,acss.children_id,acss.appointment_datetime,acss.queue_number,IFNULL(ac.mobile,c.mobile) AS mobile, acss.appointment_patient_name,acss.`status` ,acss.appointment_staff_id,acss.appointment_address_id,acss.booking_date,acss.slot_time,acss.appointment_service_id,  t.booking_convenience_fee, t.booking_doctor_share_percentage,t.booking_payment_getway_fee_percentage,t.booking_convenience_fee_restrict_ivr, t.booking_convenience_fee_terms_condition, scma.primary_owner_id,scma.primary_owner_share_percentage,scma.primary_mediator_id,scma.primary_mediator_share_percentage,scma.secondary_owner_id,scma.secondary_owner_share_percentage,scma.secondary_mediator_id,scma.secondary_mediator_share_percentage FROM appointment_customer_staff_services AS acss JOIN  thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id JOIN appointment_services AS service ON service.id = acss.appointment_service_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id left join smart_clinic_mediator_associate as scma on t.id = scma.thinapp_id LEFT JOIN booking_convenience_orders AS bco ON bco.appointment_customer_staff_service_id = acss.id where acss.id = $orderId limit 1";
    $list = $connection->query($sql);
    $appointmentData = mysqli_fetch_assoc($list);

    $appointment_datetime = $appointmentData['appointment_datetime'];
    $queue_number1 = $queue_number = $appointmentData['queue_number'];
    $thinapp_id = $appointmentData['thinapp_id'];
    $appointment_id = $appointmentData['id'];


    $currDate = date('Y-m-d H:i:s');

    $updateBookingOrder = "UPDATE `booking_convenience_orders` SET `status` = 'FAILED', `modified`= '".$currDate."' WHERE `order_id` = '".$orderIdRow."'";
    $connection->query($updateBookingOrder);


    $prm = array(
        "bookingConvenienceFee"=>$orderAmount,
        "datetime"=>$appointment_datetime,
        "queue"=>Custom::create_queue_number($appointmentData),
        "appointment_booked_from"=>$appointmentData['appointment_booked_from'],
        "mobile"=>$appointmentData['mobile'],
        "thin_app_id"=>$appointmentData['thinapp_id'],
        "doctor_id"=>$appointmentData['appointment_staff_id'],
        "appointment_id"=>$appointment_id,
        "tx_time"=>$txTime
    );
    $prm = base64_encode(json_encode($prm));

    if($appointmentData['appointment_booked_from']=='DOCTOR_PAGE'){
        $return['status_type']="fail";
        $return['id']=$appointmentData['id'];
        $return['appointment_datetime']=$appointmentData['appointment_datetime'];
        $return['show_appointment_time']=$appointmentData['show_appointment_time'];
        $return['emergency_appointment']=$appointmentData['emergency_appointment'];
        $return['custom_token']=$appointmentData['custom_token'];
        $return['show_appointment_token']=$appointmentData['show_appointment_token'];
        $return['appointment_staff_id']=$appointmentData['appointment_staff_id'];
        $return['cus_name']=$appointmentData['appointment_patient_name'];
        $return['queue_number']=Custom::create_queue_number($appointmentData);
        $controller = ($request_from=='qutot')?"qutot/web_app/":"doctor/index/";
        header("Location: ".$baseUrl.$controller."?t=".base64_encode($appointmentData['appointment_staff_id'])."&a=".base64_encode(json_encode($return)));
    }else{
        header("Location: ".$baseUrl."booking_convenience/failer.php?prm=".$prm);
    }
}
?>