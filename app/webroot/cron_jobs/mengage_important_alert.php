<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../PHPMailer/PHPMailerAutoload.php";
$mobile_array =explode(",",SEND_ALERT_MOBILES);
$email_array =explode(",",SEND_ALERT_EMAILS);

//$mobile_array = $email_array =array();
//$email_array[] = "mahendra@mengage.in";
//$mobile_array[] = "+918890720687";

$query = "select * from server_renewal_dates ";
$connection = ConnectionUtil::getConnection();
$list = $connection->query($query);
if ($list->num_rows) {
    $alert_list = mysqli_fetch_all($list,MYSQLI_ASSOC);
    $date_array =array();
    $date_array[] = date('Y-m-d', strtotime("+6 Days"));
    $date_array[] = date('Y-m-d', strtotime("+2 Days"));
    $date_array[] = date('Y-m-d', strtotime("+1 Days"));
    $date_array[] =date('Y-m-d');
    foreach ($alert_list as $key => $data){
        $last_date = $data['date'];
        if(in_array($last_date,$date_array)){
            $label = $data['label'];
            $date_label = date('d M, Y',strtotime($last_date));
            $email_message = "ATTENTION PLEASE\n\nDear MEngage Team,\n\nThe entity <b>$label</b> is going to expire on $date_label.\nPlease renew this entity as soon as possible other wise site will stop working.\n\nThankyou\nMEngage Cron Alert";
            $whats_message="Dear MEngage Team,\n\nThe entity $label is going to expire on $date_label.\nPlease renew this entity as soon as possible otherwise site will stop working.\n\nThankyou\nMEngage Support";
            if(!empty($mobile_array)){
                foreach ($mobile_array as $mobile){
                    $res= Custom::send_single_sms($mobile,$whats_message,1,false,false);
                    $res =Custom::sendWhatsappSms($mobile,$whats_message);
                }
            }
            if(!empty($email_array)){
                $email_message = str_replace("\n","<br>",$email_message);
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
                $mail->setFrom("engage@mengage.in","MEngage System Alert");
                foreach ($email_array as $email){
                    $rep = explode("@",$email);
                    $mail->addAddress($email, $rep[0]);
                }
                $mail->Subject = "Renewal of '$label'";
                $mail->Body    = $email_message;
                $mail->isHTML(true);
                $mail->send();
                echo "<pre>";
                print_r($mail);
            }

        }
    }

}

die;




