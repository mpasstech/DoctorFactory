<?php

date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
include_once "../PHPMailer/PHPMailerAutoload.php";

$html_array=array();
$mobile_array =explode(",",SEND_ALERT_MOBILES);
$email_array =explode(",",SEND_ALERT_EMAILS);
$html_array=array();
$date = date('Y-m-d');


if(!empty($email_array)){
    $array = array('green'=>'PAID','blue'=>'UNPAID','red'=>'VISITOR');

    foreach ($array as $bgColor =>$status){
        if($status=="VISITOR"){
            $list = Custom::getLastSixMonthAverageMpass($date);
        }else {
            $list = Custom::getLastSixMonthAverage($date,$status);
        }
        if(!empty($list)){
            $title_date =date('d-M-Y',strtotime($date));
            $title = ucfirst(strtolower($status))." Report For $title_date";
            $html = "";
            $html = "<h3 style='background: $bgColor;color: #fff;text-align: center;width: 100%;font-size:1.1rem; padding:1rem 0;display: block;margin: 10px 0px;'>$status TOKEN FEE REPORT FOR $title_date</h3>";
            $html .= "<table style='font-size:1rem; width: 100%;text-align: center;' border='1'><tr style='background-color: black;color: #fff;'><th>S.No</th><th>App Name</th><th>Last 6 <br> Months Token</th><th>Last 6 <br> Months Avg.</th><th>Last 6 <br> Days Token</th><th>Last 6 <br> Days Avg.</th><th>Last Week <br>Token</th><th>Last Week <br>Avg.</th><th>Percentage <br>(%)</th></tr>";
            $tot_six_months = $tot_six_days = $tot_month_avg = $tot_day_avg = $counter = $tot_week_days = $tot_week_avg=$final_percentage=0;
            foreach ($list as $key => $val){
                $s_no = ++$counter;
                $app_name = $val['app_name'];
                $last_six_months = $val['last_six_months'];
                $tot_six_months +=$last_six_months;

                $last_six_days = $val['last_six_days'];
                $tot_six_days+=$last_six_days;

                $last_week_days = $val['last_week_days'];
                $tot_week_days+=$last_week_days;

                $percentage = $val['percentage'].'%';
                $six_month_avg = $val['six_month_avg'];
                $tot_month_avg += $six_month_avg;

                $last_week_avg = $val['last_week_avg'];
                $tot_week_avg += $last_week_avg;

                $six_day_avg = $val['six_day_avg'];
                $tot_day_avg += $six_day_avg;
                $css = ($val['colour']=="red")?"background-color: red;color:#fff;":"";
                $html .= "<tr style='$css'><td>$s_no</td><td>$app_name</td><td>$last_six_months</td><td>$six_month_avg</td><td>$last_six_days</td><td>$six_day_avg</td><td>$last_week_days</td><td>$last_week_avg</td><td>$percentage</td></tr>";
            }

            $final_percentage= sprintf('%0.2f', ((($tot_day_avg*100)/$tot_month_avg)));
            $html .= "<tr><th></th><th>Total</th><th>$tot_six_months</th><th>$tot_month_avg</th><th>$tot_six_days</th><th>$tot_day_avg</th><th>$tot_week_days</th><th>$tot_week_avg</th><th>$final_percentage%</th></tr>";
            $html .="</table><br>";
            $url = SITE_PATH."wReport.php?s=$status&&d=$date";
            $html .= "<a style='text-decoration: none;background:yellow;color: #000;text-align: center;width: auto%;font-size:1.1rem; padding:1rem 0;display: block;margin: 10px 0px;' href='".$url."'>Click here to download this report</a>";


            $html_array[$status]['body'] = $html;
            $html_array[$status]['title'] = $title;
            $html_array[$status]['subject'] = $title;
        }
    }
}

if(!empty($html_array)){
    foreach ($html_array as $key => $data){
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
        $mail->setFrom(MENGAGE_EMAIL,$data['title']);
        foreach ($email_array as $email){
            $rep = explode("@",$email);
            $mail->addAddress($email, $rep[0]);
        }
        $mail->addReplyTo(MENGAGE_EMAIL, 'Information');
        $mail->Subject = $data['subject'];
        $mail->Body    = $data['body'];
        $mail->isHTML(true);
        if($mail->send()){
            echo "EMAIL SENT SUCCESSFULLY for ".$data['subject']."<br>";
        }else{
            echo "COULD NOT SEND EMAIL ".$data['subject']."<br>";
        }
    }
}

die("Done");




