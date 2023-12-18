<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../PHPMailer/PHPMailerAutoload.php";

$response = array();
$connection = ConnectionUtil::getConnection();

/* THIS SECTION SEND ALERT TO App Admin */
$query = "SELECT t.id, t.name, t.end_date, t.subscription_price,t.phone, DATEDIFF(t.end_date,NOW()) as day_diff FROM thinapps AS t where t.category_name IN('DOCTOR','HOSPITAL') AND t.`status` ='ACTIVE' AND t.subscription_alert_via_cron = 'ACTIVE'  AND ( DATEDIFF(t.end_date,NOW()) <=30 OR DATEDIFF(t.end_date,NOW())<=0 ) AND ((t.subscription_alert_type='DAILY') OR (t.subscription_alert_type='WEEKLY' AND MOD(DATEDIFF(t.end_date,NOW()),7) =0 ))";
$list = $connection->query($query);
if ($list->num_rows) {
    $list =  mysqli_fetch_all($list,MYSQLI_ASSOC);
    foreach ($list as $fes_key => $value){

        $app_name = $value['name'];
        $mobile = $value['phone'];
        $amount = $value['subscription_price'];
        $date = date('d-F-Y',strtotime($value['end_date']));
        $warning_message ="will expire on $date";
        $support_mobile = SUPPORT_MOBILE;

        $day_lbl = $verb ='';
        if($value['day_diff'] ==0){
            $verb = "will";
            $day_lbl="Today";
        }else if($value['day_diff'] < 0){
            $verb = "has";
            $day_lbl="$date";
        }


        if($amount > 0){
            $message = "Hello Sir/Ma'am,\nThis is just to remind you that your annual App/Software subscription of amount INR $amount Rs/- $verb expired on $day_lbl for application $app_name";
            $message .= "\nFor renew you subscription and any query, please contact us\nContact Number: $support_mobile\nBest Regards,\nTeam MEngage";
        }else{
            $message = "Hello Sir/Ma'am,\nThis is just to remind you that your annual subscription of App/Software provided by MEngage $verb expired on $day_lbl application $app_name";
            $message .= "\nWe will contact you shortly for further details.";
            $message .= "\nFor renew you subscription and any query, please contact us\nContact Number: $support_mobile\nBest Regards,\nTeam MEngage";
        }
        Custom::send_single_sms($mobile,$message,134,false,false);
    }
}else {
    $response['status'] = 0;
    $response['message'] = "No list found";
}
Custom::sendResponse($response);
echo "<pre>";
print_r($message);
echo "<br>";


/* THIS SECTION SEND ALERT TO MEngage Team */
$six_month_date = date("Y-m-d",strtotime('-6 MONTHS'));
$mobile_array =explode(",",SEND_ALERT_MOBILES);
$email_array =explode(",",SEND_ALERT_EMAILS);
$message = "";
$send_sms = false;
$current_data = date('Y-m-d');
$query = "SELECT t.id, COUNT(acss.thinapp_id) AS tot_appointment, DATEDIFF(t.end_date,NOW()) AS total_days_left, t.name,t.subscription_start_date,t.end_date FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id AND DATE(acss.appointment_datetime) >= '$six_month_date' AND t.send_subscription_sms_before > 0 AND t.category_name IN('DOCTOR','HOSPITAL') AND t.`status` ='ACTIVE' AND DATEDIFF(t.end_date,NOW()) IN ('1','15')  GROUP BY acss.thinapp_id HAVING tot_appointment >= 50";
$list = $connection->query($query);
if ($list->num_rows) {
    $list =  mysqli_fetch_all($list,MYSQLI_ASSOC);
    $app_lbl = (count($list)>1)?'apps':'app';
    $message = "Subscription Reminder\n\nHello Team, Subscription pending of following $app_lbl\n\n";
    foreach ($list as $fes_key => $value){
        $send_subscription_sms_before=0;
        if($value['total_days_left']==15){
            $send_subscription_sms_before=1;
        }
        $sql = "update thinapps set send_subscription_sms_before=?, modified=? where id =?";
        $stmt = $connection->prepare($sql);
        $created = Custom::created();
        $stmt->bind_param('sss',  $send_subscription_sms_before, $created, $value['id']);
        if ($stmt->execute()) {
            $send_sms =true;
            $response['status'] = 1;
            $response['message'] = "Data update successfully.";
            $app_name = $value['name'];
            $end_date = date('d-m-Y',strtotime($value['end_date']));
            $message .= ($fes_key+1).". $app_name, Expire On:- $end_date.\n\n";
        } else {
            $response['cancel']['status'] = 0;
            $response['cancel']['message'] = "Sorry, unable to update data.";
        }
    }
}else {
    $response['status'] = 0;
    $response['message'] = "No list found";
}
if($send_sms===true){
    $message .= "Please contact to doctor for subscription renewal\nThankyou";
    if(!empty($mobile_array)){
        foreach ($mobile_array as $mobile){
            Custom::send_single_sms($mobile,$message,134,false,false);
        }
    }

    if(!empty($email_array)){
        $message = str_replace("\n","<br>",$message);
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MENGAGE_EMAIL;
        $mail->Password   = MENGAGE_PASSWORD;
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;
        //$mail->addCustomHeader("Mime-Version","1.0");
        $mail->addCustomHeader("Content-Transfer-Encoding","quoted-printable");
        $mail->addCustomHeader("Content-Type","text/html; charset=\"UTF-8\"; format=flowed");
        //$mail->addCustomHeader("From",SUPER_ADMIN_EMAIL);
        $mail->setFrom(SUPER_ADMIN_EMAIL,"MEngage Cron Alert");
        foreach ($email_array as $email){
            $rep = explode("@",$mail);
            $mail->addAddress($email, $rep[0]);     // Add a recipient
        }
        $mail->addReplyTo(SUPER_ADMIN_EMAIL, 'Information');
        $mail->Subject = "SUBSCRIPTION REMINDER ALERT";
        $mail->Body    = $message;
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->send();
    }
}

Custom::sendResponse($response);
echo "<pre>";
print_r($message);
echo "<br>";
die;



