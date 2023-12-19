<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../PHPMailer/PHPMailerAutoload.php";
include_once "../twilio_auth/config.php";

$mobile_array =explode(",",SEND_ALERT_MOBILES);
$email_array =explode(",",SEND_ALERT_EMAILS);

//$mobile_array =array("+918890720687");
//$email_array =array("mahendra@mengage.in");

$folder_name = "account_balance";
/* This curl send 2FACTORY SMS LOW BALANCE */
$send_sms = false;
$message_array=array();
$url = "http://2factor.in/API/V1/".OTP_SMS_API_KEY."/ADDON_SERVICES/BAL/TRANSACTIONAL_SMS";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = json_decode(curl_exec($ch),true);
curl_close($ch);
if($response['Status']=='Success'){
    $balance = $response['Details'];
    $file_name ="2factor_send_sms_on";
    if($balance >= 10000){
        WebservicesFunction::createJson($file_name,json_encode(10000),"CREATE",$folder_name);
    }
    if(!$sms_sent_on = json_decode(WebservicesFunction::readJson($file_name,$folder_name),true)){
        $sms_sent_on = false;
    }
    if($balance <= 10000 && ($sms_sent_on===false || $sms_sent_on == 10000)){
        WebservicesFunction::createJson($file_name,json_encode(5000),"CREATE",$folder_name);
        $send_sms=true;
    }else if($balance <= 5000 && $sms_sent_on == 5000){
        WebservicesFunction::createJson($file_name,json_encode(2000),"CREATE",$folder_name);
        $send_sms=true;
    }else if($balance <= 2000 && $sms_sent_on == 2000){
        WebservicesFunction::createJson($file_name,json_encode(1000),"CREATE",$folder_name);
        $send_sms=true;
    }else if($balance <= 1000 && $sms_sent_on == 1000){
        WebservicesFunction::createJson($file_name,json_encode(500),"CREATE",$folder_name);
        $send_sms=true;
    }else if($balance <= 500 && $sms_sent_on == 500){
        WebservicesFunction::createJson($file_name,json_encode(100),"CREATE",$folder_name);
        $send_sms=true;
    }else if($balance <= 100 && $sms_sent_on  == 100){
        WebservicesFunction::createJson($file_name,json_encode(0),"CREATE",$folder_name);
        $send_sms=true;
    }else if($balance <=0 ){
        WebservicesFunction::createJson($file_name,json_encode(0),"CREATE",$folder_name);
        $send_sms=true;
    }
    if($send_sms===true){
        $message_array[] = "2FACTOR SMS ALERT\nDear MEngage Team,\n2factory sms getway balance running low. Total sms balance left : $balance. Please recharge sms balance as soon as possible.\nThankyou";
    }

}

/* END 2FACTORY SMS LOW BALANCE */
$sms_sent_on = $send_sms= false;
$url = "https://api.twilio.com/2010-04-01/Accounts/$ACCOUNT_SID/Balance.json";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_USERPWD, $ACCOUNT_SID . ":" . $TOKEN);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = json_decode(curl_exec($ch),true);
if(!curl_errno($ch)){
    $twilio_balance = sprintf("%01.2f", $response['balance']);
    if($twilio_balance){
        $currency = $response['currency'];
        $file_name ="twilio_send_sms_on";
        if($twilio_balance >= 15){
            WebservicesFunction::createJson($file_name,json_encode(15),"CREATE",$folder_name);
        }
        if(!$sms_sent_on = json_decode(WebservicesFunction::readJson($file_name,$folder_name),true)){
            $sms_sent_on = false;
        }
        if($twilio_balance <= 15 && ($sms_sent_on===false || $sms_sent_on == 15)){
            WebservicesFunction::createJson($file_name,json_encode(10),"CREATE",$folder_name);
            $send_sms=true;
        }else if($twilio_balance <= 10 && $sms_sent_on == 10){
            WebservicesFunction::createJson($file_name,json_encode(5),"CREATE",$folder_name);
            $send_sms=true;
        }else if($twilio_balance <= 5 && $sms_sent_on == 5){
            WebservicesFunction::createJson($file_name,json_encode(0),"CREATE",$folder_name);
            $send_sms=true;
        }else if($twilio_balance <=0 ){
            WebservicesFunction::createJson($file_name,json_encode(0),"CREATE",$folder_name);
            $send_sms=true;
        }
        if($send_sms===true){
            $message_array[] = "TWILIO BALANCE ALERT\nDear MEngage Team,\nTwilio getway balance running low. Total balance left : $twilio_balance $currency. Please recharge balance as soon as possible.\nThankyou";
        }
    }
}


/* EXOTEL API */
$accountSid = 'mengage';
$apiToken = 'aae6a18ce1902085f351aa70196b316a897fa40b';
$url = "https://api.exotel.com/v1/Accounts/{$accountSid}/Balance.json";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERPWD, "{$accountSid}:{$apiToken}");
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
if ($httpCode === 200) {
    $balanceInfo = json_decode($response, true);
    $creditBalance = $balanceInfo['Balance']['Amount'];
    if($creditBalance <= 500 ){
        $message_array[] = "EXOTEL IVR ALERT\nDear MEngage Team,\nExotel IVR getway balance running low. Total credit balance left : $creditBalance. Please recharge credit balance as soon as possible.\nThankyou";
    }
} else {
    echo "Failed to retrieve Exotel credit balance. Error: $response";
}
curl_close($ch);




if(!empty($message_array)){
    foreach ($message_array as $key =>$message){
        if(!empty($mobile_array)){
            foreach ($mobile_array as $mobile){
                $res = Custom::send_single_sms($mobile,$message,1,false,false);
                $res =Custom::sendWhatsappSms($mobile,$message);
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
            $mail->addCustomHeader("Content-Transfer-Encoding","quoted-printable");
            $mail->addCustomHeader("Content-Type","text/html; charset=\"UTF-8\"; format=flowed");
            $mail->setFrom(MENGAGE_EMAIL,"MEngage Cron Alert");
            foreach ($email_array as $email){
                $rep = explode("@",$email);
                $mail->addAddress($email, $rep[0]);
            }
            $mail->addReplyTo(MENGAGE_EMAIL, 'Information');
            $mail->Subject = "SUBSCRIPTION REMINDER ALERT";
            $mail->Body    = $message;
            $mail->isHTML(true);
            $mail->send();
        }
    }
}
die("SMS Balance is :".$balance."<br>Twilio Balance is:".$twilio_balance." $currency");
die;




