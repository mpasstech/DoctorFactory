<?php
/**
 * Created by PhpStorm.
 * User: vengage
 * Date: 2/1/2017
 * Time: 12:52 PM
 */

//namespace Custom;
include_once "ConnectionUtil.php";
include_once "WebservicesFunction.php";
require_once "UrlShort.php";
//include_once ("./constant.php");
$constant_path = explode(DIRECTORY_SEPARATOR,__FILE__);
$tot =count($constant_path);
unset($constant_path[$tot-2]);
unset($constant_path[$tot-1]);
include_once implode("/",$constant_path)."/constant.php";
include_once LOCAL_PATH . "app/Vendor/aws/aws-autoloader.php";
include_once LOCAL_PATH . "app/webroot/paytm/Checksum/PaytmChecksum.php";



/* NODE SOCKET IO START */
include_once LOCAL_PATH . "app/Vendor/elephant.io/vendor/autoload.php";
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

/* NODE SOCKET IO END */


//use Aws\S3\S3Client;
class Custom
{

    public static function updateCoins($actionType = null, $ownerUserID = 0, $actionTakenByUserID = 0, $messageID = 0, $thinappID = 0, $sharedOn = '')
    {


        $coinDistribution = unserialize(CoinDistribution);
        $coinToSave = $coinDistribution[$actionType];
        try {
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(FALSE);
            $sql = "INSERT INTO coins (owner_user_id, action_type, action_taken_by_user_id, coin_earned, message_id, thinapp_id, shared_on) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sssssss', $ownerUserID, $actionType, $actionTakenByUserID, $coinToSave, $messageID, $thinappID, $sharedOn);
            if ($stmt->execute()) {
                if ($actionType != 'REGISTER') {
                    $sql = "UPDATE gullaks SET total_coins = total_coins + ? where user_id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ss', $coinToSave, $ownerUserID);
                    if ($stmt->execute()) {
                        $connection->commit();
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    $connection->autocommit(FALSE);
                    $sql = "INSERT INTO gullaks (user_id, total_coins) VALUES (?, ?)";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ss', $ownerUserID, $coinToSave);
                    if ($stmt->execute()) {
                        $connection->commit();
                        return true;
                    } else {
                        return false;
                    }
                }

            } else {
                $connection->rollback();
            }

        } catch (Exception $e) {
            $connection->rollback();
            return false;
        }
    }


    public static function is_app_admin_login_first_time($thin_app_id)
    {
        $query = "select c.id from channels as c where c.channel_status = 'DEFAULT' and c.app_id  = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            //$staff_data = mysqli_fetch_assoc($service_message_list);
            return true;
        } else {
            return false;
        }
    }

    public static function get_app_default_channel_id($thin_app_id)
    {
        $query = "select c.id from channels as c where c.channel_status = 'DEFAULT' and c.app_id  = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['id'];
        } else {
            return 0;
        }
    }

    public static function getRandomString($length = 6)
    {
        //$validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZ123456789";
        $validCharacters = "123456789";
        $validCharNumber = strlen($validCharacters);
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return $result;
    }


    public static function getAllAppointmentDay()
    {
        $query = "select * from appointment_day_times where status  = 'ACTIVE' LIMIT 7";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
            return $data;
        } else {
            return false;
        }
    }


    public static function send_otp_back($param, $otp_type = "SMS")
    {
        if($otp_type=="SMS"){
            $otp = @$param['verification'];
            $mobile = @$param['mobile'];
       		$thin_app_id = isset($param['thinapp_id'])?$param['thinapp_id']:'0';
            $hash_key = @$param['HASH_KEY'];
        	//$hash_key = 'OTP';
        	
            $param['sms_type'] = "TRANSACTIONAL";
            $param['message'] = urlencode("<#> Hi, Your one time password for phone verification is:$otp\n").$hash_key;
        	//$param['message'] = "$otp is your OTP for phone verification";
            if (!empty($otp) && !empty($mobile)) {
            	
                $app_data = Custom::getThinAppData($thin_app_id);
                $sender_id = "MEngag";
                if(!empty($app_data['2factor_sender_id'])){
                    $sender_id = $app_data['2factor_sender_id'];
                }
                $mobile = substr($mobile, -10);
                $post['From'] = $sender_id;
                $post['To'] = $mobile;
                $post['TemplateName'] = 'OTP';
                $post['VAR1'] = $otp;
                $post['VAR2'] = $hash_key;
                $otp_type = "TRANS_SMS";
                $url = "https://2factor.in/API/R1/?module=$otp_type&apikey=" . OTP_SMS_API_KEY . "&to=" . $mobile . "&from=$sender_id&msg=".$param['message'];
            	
                
                $ch = curl_init(($url));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);

                return Custom::saveHistory($curl_scraped_page, $param, "2FACTOR");

            } else {
                return false;
            }
        }else{
            $otp = @$param['verification'];
            $mobile = @$param['mobile'];
            $param['sms_type'] = "TRANSACTIONAL";
            $param['message'] = "<#> Your verification code is:$otp";
            if (!empty($otp) && !empty($mobile)) {
                $otp_type = "VOICE";
                $mobile = substr($mobile, -10);
                $url = "https://2factor.in/API/V1/" . OTP_SMS_API_KEY . "/$otp_type/" . $mobile . "/" . $otp;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
                return Custom::saveHistory($curl_scraped_page, $param, "2FACTOR");
            } else {
                return false;
            }

        }


    }


	public static function send_otp($param, $otp_type = "SMS")
    {
        if($otp_type=="SMS"){
            $otp = @$param['verification'];
            $mobile = @$param['mobile'];
       		$thin_app_id = isset($param['thinapp_id'])?$param['thinapp_id']:'0';
            $hash_key = @$param['HASH_KEY'];
        	//$hash_key = 'OTP';
        	
            $param['sms_type'] = "TRANSACTIONAL";
            $param['message'] = urlencode("<#> Hi, Your one time password for phone verification is:$otp\n").$hash_key;
        	$param['message'] = urlencode("Your OTP for secure login is: $otp. Please do not share this code with anyone. Thank you!\nmPass");
            if (!empty($otp) && !empty($mobile)) {
            	
                $sender_id = "AMPASS";
                $mobile = substr($mobile, -10);
                $post['From'] = $sender_id;
                $post['To'] = $mobile;
                $post['TemplateName'] = 'MPASS OTP TEMPLATE';
            	$post['TemplateId'] = '1207169028067379904';
                $post['VAR1'] = $otp;
                $otp_type = "TRANS_SMS";
                $url = "https://2factor.in/API/R1/?module=$otp_type&apikey=" . OTP_SMS_API_KEY . "&to=" . $mobile . "&from=$sender_id&msg=".$param['message'];
                $ch = curl_init(($url));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
                return Custom::saveHistory($curl_scraped_page, $param, "2FACTOR");

            } else {
                return false;
            }
        }else{
            $otp = @$param['verification'];
            $mobile = @$param['mobile'];
            $param['sms_type'] = "TRANSACTIONAL";
            $param['message'] = "<#> Your verification code is:$otp";
            if (!empty($otp) && !empty($mobile)) {
                $otp_type = "VOICE";
                $mobile = substr($mobile, -10);
                $url = "https://2factor.in/API/V1/" . OTP_SMS_API_KEY . "/$otp_type/" . $mobile . "/" . $otp;
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
                return Custom::saveHistory($curl_scraped_page, $param, "2FACTOR");
            } else {
                return false;
            }

        }


    }


    public static function send_single_sms($mobile, $message, $thin_app_id, $app_name = false, $send_app_download_link = true)
    {

        if(Custom::create_mobile_number($mobile) != "+919999999999"){
            $app_sms = Custom::get_total_sms_thinapp($thin_app_id, "T");
            if ($app_sms >= 1) {
                if (!empty($message) && !empty($mobile)) {
                    $curl_scraped_page = '';
                    $app_data = Custom::getThinAppData($thin_app_id);
                    $site_url = $app_data['apk_url'];
                    $app_name = ($app_name === true) ? $app_data['name']."\n" : "";
                    $message = $app_name.$message;
                    $sender_id = "MENGAZ";
                	
                    if(!empty($app_data['2factor_sender_id'])){
                        $sender_id = $app_data['2factor_sender_id'];
                    }
                
                   $sender_id = "MEngag";
                	//$sender_id = "AMPASS";
                
                    if($send_app_download_link===true && !empty($site_url)){
                        if($thin_app_id!="821"){
                            $message .= "\nDownload App: " . $site_url;
                        }
                    }

                    $fields['From'] =$sender_id;
                    $fields['To'] =$mobile;
                    $fields['Msg'] =$message;

                    $url = "http://2factor.in/API/V1/".OTP_SMS_API_KEY."/ADDON_SERVICES/SEND/TSMS";
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $curl_scraped_page = curl_exec($ch);
                    $return_array[] = json_decode($curl_scraped_page, true);
                    curl_close($ch);
                    $param['sms_type'] = "TRANSACTIONAL";
                    $param['mobile'] = $mobile;
                    $param['message'] = $message;
                    $param['thinapp_id'] = $thin_app_id;
                    return Custom::saveHistory($curl_scraped_page, $param, "2FACTOR");


                    /* // start send sms via smshorizon
                     $curl_scraped_page = '';
                     $app_data = Custom::getThinAppData($thin_app_id);
                     $site_url = $app_data['apk_url'];
                     $app_name = ($app_name === false) ? $app_data['name'] : "MEngage Team";
                     if($send_app_download_link===true){
                         $message .= "\nClick Here To Download App: " . $site_url;
                     }
                     $message = urlencode($message);

                     $user = SMS_HORIZON_USER;
                     $apikey = SMS_HORIZON_KEY;
                     $senderid = SMS_HORIZON_SENDER_ID;
                     $type = "uni";
                     $url = ("http://smshorizon.co.in/api/sendsms.php?user=".$user."&apikey=".$apikey."&mobile=".$mobile."&senderid=".$senderid."&message=".$message."&type=".$type);
                     $ch = curl_init($url);
                     curl_setopt($ch, CURLOPT_HEADER, 0);
                     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                     $curl_scraped_page = curl_exec($ch);
                     curl_close($ch);
                     $param['sms_type'] = "TRANSACTIONAL";
                     $param['mobile'] = $mobile;
                     $param['message'] = $message;
                     $param['thinapp_id'] = $thin_app_id;
                     return Custom::saveHistory($curl_scraped_page, $param, "HOMEHORIZON");
                     //end send sms via smshorizon */



                } else {
                    return false;
                }
            }
        }

    }




    public static function getTotalCounForDrives($thinapp_id,$mobile,$user_id){
        $query = "select (select count(1) from drive_folders where mobile ='$mobile' and thinapp_id =$thinapp_id ) as total_folder,(select count(1) from drive_shares where share_with_mobile ='$mobile' and shared_object ='FOLDER' and thinapp_id =$thinapp_id ) as total_shared_folder,(select count(1) from drive_shares where share_with_mobile ='$mobile' and shared_object ='FILE' and thinapp_id =$thinapp_id ) as total_file_share";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function saveHistory($curl_scraped_page,$param,$getway ="GUPSHUP"){

        $mobile = trim($param['mobile']);
        $message = str_replace(' ', '%20', $param['message']);
        $receiver_mobile = $mobile;
        $message_text = $message;
        $sender_id = isset($param['sender_id'])?$param['sender_id']:0;
        $receiver_id = isset($param['receiver_id'])?$param['receiver_id']:0;
        $channel_id = isset($param['channel_id'])?$param['channel_id']:0;
        $message_id = isset($param['message_id'])?$param['message_id']:0;
        $sms_response = false;
        if($curl_scraped_page){
            if($getway=="GUPSHUP"){
                $curl_scraped_page = json_decode($curl_scraped_page);
                $status =   $sms_response =strtoupper($curl_scraped_page->response->status);
                $sms_response_id = $curl_scraped_page->response->id;
                $response_detail = $curl_scraped_page->response->details;
            }else if($getway=="HOMEHORIZON"){
                $status =  $sms_response=  "SUCCESS";
                $sms_response_id = $curl_scraped_page;
                $response_detail = "DETAIL_NOT_AVAILABLE";
            }else{
                $curl_scraped_page = json_decode($curl_scraped_page);
                $status =   $sms_response =strtoupper($curl_scraped_page->Status);
                if($status =="SUCCESS"){
                    $sms_response_id = $curl_scraped_page->Details;
                }else{
                    $sms_response_id = 0;
                }
                $response_detail = $curl_scraped_page->Details;
            }


        }else{
            $status = $sms_response=  strtoupper("REQUEST FAIL");
            $sms_response_id = "";
            $response_detail = "";
        }
        $thinapp_id =  $param['thinapp_id'];
        $router_name =$getway;

        $mobile_numbers = explode(",",$mobile);
        $result =array();
        if(!empty($mobile_numbers)){
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(FALSE);

            foreach ($mobile_numbers as $receiver_mobile){
                try{
                    $created = Custom::created();
                    $sql  = "INSERT INTO sent_sms_details (receiver_mobile, message_text, sender_id, receiver_id, channel_id, message_id, status, sms_response_id, response_detail, thinapp_id, router_name, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('sssssssssssss', $receiver_mobile, $message_text, $sender_id, $receiver_id, $channel_id, $message_id, $status, $sms_response_id, $response_detail, $thinapp_id, $router_name, $created, $created );
                    if($stmt->execute()){
                        if(!empty($curl_scraped_page)){
                            $thin_app_id =  $param['thinapp_id'];
                            $sms_type =  $param['sms_type'];

                            if($sms_type=="TRANSACTIONAL"  && strtoupper($sms_response)=="SUCCESS"){
                                $sql  = "UPDATE app_sms_statics SET total_transactional_sms = total_transactional_sms - ? where thinapp_id = ?";
                                $stmt = $connection->prepare($sql);
                                $total_less = Custom::smsStringWordCount($message_text, 'CREDIT');
                                $stmt->bind_param('ss', $total_less, $thin_app_id);
                                if($stmt->execute()) {
                                    $result[] =true;
                                }else{
                                    $result[] =false;
                                }
                            }else{
                                if(strtoupper($sms_response)=="SUCCESS"){
                                    $sql  = "UPDATE app_sms_statics SET total_promotional_sms = total_promotional_sms - ? where thinapp_id = ?";
                                    $stmt = $connection->prepare($sql);
                                    $total_less =1;
                                    $stmt->bind_param('ss', $total_less, $thin_app_id);
                                    if($stmt->execute()) {
                                        $result[] =true;
                                    }else{
                                        $result[] =false;
                                    }
                                }
                            }
                        }else{
                            $result[] =false;
                        }
                    }else{
                        $result[] =false;
                    }



                }catch(Exception $e){
                    $connection->rollback();
                    return false;
                }


            }

            if(!empty($result) && !in_array(false,$result)){
                $connection->commit();
                return true;
            }else{
                $connection->rollback();
                return false;
            }
        }


    }


    public static function getThinAppData($thin_app_id){
        $query = "select t.*, u.username from thinapps as t join users as u on u.thinapp_id = t.id and u.role_id = 5  where  t.id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function sendResponse($response)
    {
        ob_end_clean();
        header("Connection: close\r\n");
        header("Content-Encoding: none\r\n");
        ignore_user_abort(true); // optional
        ob_start();
        echo json_encode($response);
    }


    public static function get_total_sms_thinapp($thin_app_id, $type)
    {
        $query = $query = "select ass.total_promotional_sms, ass.total_transactional_sms from thinapps as thin join app_sms_statics as ass on thin.id=ass.thinapp_id  where  thin.id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            if ($type == 'T')
                return $staff_data['total_transactional_sms'];
            else
                return $staff_data['total_promotional_sms'];
        } else {
            return 0;
        }

    }


    public static function setInterval($f, $milliseconds)
    {
        $seconds=(int)$milliseconds/1000;
        while(true)
        {
            $f();
            sleep($seconds);
        }
    }

    public static function send_process_to_background()
    {
        try {
            session_write_close();
            ignore_user_abort(true);
            set_time_limit(0);
            $size = ob_get_length();
            header("Content-Length: $size");
            header('Connection: close');
            if (ob_get_contents()) {
                @ob_end_flush();
                @ob_flush();
                @flush();
                @ob_end_clean();
            }
            if (session_id()) session_write_close();
        }catch(Exception $e){

        }
    }

    public static function country_code_mobile_length($country_code){
        $code_array = array(
            "+91"=>'10',
            "+1"=>'4'
        );
        if(array_key_exists($country_code,$code_array)){
            return $code_array[$country_code];
        }
        return 0;
    }
    public static function create_mobile_number_new($number=null,$country_code="+91"){
        //$number = "1234567aa0";
        $total_string_length = strlen($number);
        $code_length = strlen($country_code);
        $country_code_length = Custom::country_code_mobile_length($country_code);
        $code = substr($number, 0,$code_length);

        if($code == $country_code){
            $total_string_length = $total_string_length- $code_length;
            $number = substr($number, $code_length,$country_code_length);
        }else{
            $number = substr($number, -$country_code_length);
        }
        if(is_numeric($number) && strlen($number) == $country_code_length && $total_string_length == strlen($number)){
            return $country_code.$number;
        }
        return false;
    }

    public static function create_mobile_number($number=null,$country_code="+91"){
        if(strlen($number) >= 10){
            $number = (string)str_replace(" ","",$number);
            $number = (string)str_replace("-","",$number);
            $number = ltrim($number, '0');
            $number = preg_replace("/[^0-9]/", "", $number);
            $number = substr($number, -10);
            $number = $country_code.$number;
            if(preg_match('#^(\+){0,1}(91){0,1}[0123456789](-|\s){0,1}[0-9]{9}$#', $number)){
                return $number;
            }

        }else{
            return false;
        }


    }

    public static function SendBlukSmsToNumbers($thin_app_id,$message,$number_array,$user_id){
        $app_sms = Custom::get_total_sms_thinapp($thin_app_id,"T");
        if($app_sms >= count($number_array)){
            if(!empty($number_array)){
                $app_data = Custom::getThinAppData($thin_app_id);
                $site_url = $app_data['apk_url'];
                $app_name =$app_data['name'];
                //$message .= "\nClick Here To Download App: " . $site_url;
                $message = $app_name."\n".$message;
                foreach($number_array as $key => $mobile){
                    Custom::send_single_sms($mobile,$message,$thin_app_id);
                }
            }
        }
    }


    public static function createAppoinmentDate($date_time,$slot_time){

        $date = date('Y-m-d', strtotime($date_time))." ".$slot_time;
        return (date('Y-m-d h:i A', strtotime($date)));

    }


    public static function sendFileShareMessage($object_type,$object_id,$mobile_numbers,$message,$thin_app_id,$user_id) {
        ignore_user_abort(true);
        set_time_limit(0);

        if(Custom::check_user_permission($thin_app_id,'SEND_DOCUMENT_SMS_BY_USER')=="YES"){
            $total_sms = Custom::get_total_sms_thinapp($thin_app_id,"T");
            if($object_type=="FOLDER"){
                $query = "select * from drive_folders where id = $object_id limit 1";
            }else{
                $query = "select * from drive_files where id = $object_id limit 1";
            }
            $connection = ConnectionUtil::getConnection();
            $subscriber_data = $connection->query($query);
            $send_sms = $message;
            if( $total_sms >= $subscriber_data->num_rows ) {
                if ($subscriber_data->num_rows) {
                	$app_data = Custom::getThinAppData($thin_app_id);
                    $apkUrl = $app_data['apk_url'];
                    $object_data = mysqli_fetch_all($subscriber_data, MYSQLI_ASSOC);
                    foreach ($mobile_numbers as $key => $mobile) {
                        $string = $object_id."##".$object_type."##".$mobile;
                        $path = FOLDER_PATH . Custom::encodeVariable($string);
                        $path = Custom::short_url($path,$thin_app_id);
                        $message = $send_sms ."\nClick here to view ". $path."\nDownload App: $apkUrl\n- Sent by MEngage";
                        Custom::send_single_sms($mobile, $message, $thin_app_id,false,false);
                    }
                }
            }
        }

    }



    public static function sendPollMessage($channel_id,$question_id,$thin_app_id,$user_id) {
        ignore_user_abort(true);
        set_time_limit(0);
        $total_sms = Custom::get_total_sms_thinapp($thin_app_id,"T");
        $query = "select id, mobile, app_id from subscribers where channel_id = $channel_id and app_id = $thin_app_id and app_user_id = 0 and status = 'SUBSCRIBED'";
        $connection = ConnectionUtil::getConnection();
        $subscriber_data = $connection->query($query);
        if( $total_sms >= $subscriber_data->num_rows ) {
            if ($subscriber_data->num_rows) {
                $subscriber_data = mysqli_fetch_all($subscriber_data, MYSQLI_ASSOC);
                $subscriber_data = array_chunk($subscriber_data, MULTI_CURL_LIMIT);
                foreach ($subscriber_data as $key => $subscriber_list) {
                    $site_url = SITE_PATH . "polls/" . base64_encode($subscriber_list['id']) . "/" . base64_encode($question_id);
                    $message = "You have received a poll. Click Here:" . $site_url;
                }
            }
        }
    }


    public  static function get_appointment_role($mobile,$thin_app_id,$role_id){
        if ($role_id == 5) {
            return 'ADMIN';
        }else{
            $staff_id = WebservicesFunction::get_staff_id_by_mobile($mobile, $thin_app_id);
            if ($staff_id > 0) {
                return 'STAFF';
            } else {
                return 'USER';
            }
        }
        return "USER";
    }



    public static function created()
    {
        return date('Y-m-d H:i:s');
    }

    public static function SendBulkSmsToNumbersWithMessage($mobile_array,$message_array,$thin_app_id,$user_id) {
        ignore_user_abort(true);
        set_time_limit(0);
        $total_sms = Custom::get_total_sms_thinapp($thin_app_id,"T");
        if( $total_sms >= count($mobile_array) ) {
            if (!empty($mobile_array) && !empty($message_array)  &&  count($mobile_array) == count($message_array) ) {
                $subscriber_data = array_chunk($mobile_array, MULTI_CURL_LIMIT);
                $app_data = Custom::getThinAppData($thin_app_id);
                $site_url = $app_data['apk_url'];
                $app_name =$app_data['name'];
                foreach ($subscriber_data as $key => $subscriber_list) {
                    if (!empty($subscriber_list)) {
                        foreach ($subscriber_list as $key => $mobile) {
                            if(!is_array($message_array)){
                                $message = $message_array;
                            }else{
                                $message = $message_array[$key];
                            }
                            $message = $app_name."\n".$message;
                            Custom::send_single_sms($mobile, $message, $thin_app_id);
                        }
                    }
                }
            }
        }
    }

    public static function SendBulkSmsToNumbers($mobile_array, $message, $thin_app_id,$app_name,$download_link){
        ignore_user_abort(true);
        set_time_limit(0);
        $total_sms = Custom::get_total_sms_thinapp($thin_app_id,"T");
        if( $total_sms >= count($mobile_array) ) {
            if (!empty($mobile_array) && !empty($message) ) {
                $mobile_number_array = array_chunk($mobile_array, MULTI_CURL_LIMIT);
                $app_data = Custom::getThinAppData($thin_app_id);
                //$app_name =$app_data['name'];
                //$message = $app_name."\n".$message;
                foreach ($mobile_number_array as $key => $mobile_numbers) {
                    if (!empty($mobile_numbers)) {
                        $mobile = implode(",",$mobile_numbers);
                        Custom::send_single_sms($mobile, $message, $thin_app_id,$app_name,$download_link);
                    }
                }
            }
        }
    }

    public static function separate_token_via_device_type($token_array,$thin_app_id) {
        if(!empty($token_array)){
            $token_array =  '"'.implode('","', $token_array).'"';
            $query = "select firebase_token, device_type,login_from from users  where  firebase_token IN ($token_array) and firebase_token !='' and thinapp_id = $thin_app_id";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $return_array['ANDROID']=array();
            $return_array['IOS']=array();
            if ($service_message_list->num_rows) {
                $data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                foreach ($data as $key => $token){
                    if($token['device_type']=="ANDROID"){
                        $return_array['ANDROID'][$token['login_from']][] = $token['firebase_token'];
                    }else if($token['device_type']=="IOS"){
                        $return_array['IOS'][$token['login_from']][] = $token['firebase_token'];
                    }
                }
                return $return_array;
            }
        }
        return false;
    }

    public static function send_notification_via_token($send_array=array(),$token_array=array(),$thin_app_id,$send_message_to_mengage=false)
    {
        $return_array = array();
        $separate_array = Custom::separate_token_via_device_type($token_array,$thin_app_id);
        if (!empty($token_array) && !empty($separate_array)) {
            foreach ($separate_array as $device_type => $login_type_list) {
                foreach($login_type_list as $login_from => $token_array) {
                    $array_chunk = array_chunk($token_array, MULTI_CURL_LIMIT);

                    $app_id = $thin_app_id;
                    if ($send_message_to_mengage === true) {
                        $app_id = MENGAGE_APP_ID;
                        $send_array['thinapp_id'] = MENGAGE_APP_ID;
                        $send_array['doctors_thin_app_id'] = $thin_app_id;
                    }else{
                        $send_array['thinapp_id'] = $thin_app_id;
                        $send_array['doctors_thin_app_id'] = $thin_app_id;
                    }

                    $thin_app_data = Custom::getThinAppData($app_id);
                    $server_key = $thin_app_data['firebase_server_key'];
                    $package_name = $thin_app_data['package_name'];

                    $send_array['APP_CATEGORY'] = $thin_app_data['category_name'];
                    //$lab_user = Custom::get_lab_user_static_data($thin_app_id,@$send_array['user_mobile'],@$send_array['role_id']);
                    //$send_array['lab_user_role'] = $lab_user['lab_user_role'];
                    //$send_array['lab_user_id'] = $lab_user['lab_user_id'];
                    $send_array['appointment_user_role'] = 'USER';

                    $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                    $send_array['notification_date'] = date('d M Y h:i A');
                    foreach ($array_chunk as $chunk_key => $tokens) {
                        if ($device_type == "ANDROID") {
                            if($login_from=="MENGAGE"){
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'data' => $send_array,
                                	'notification'=> array(
                                        'body'=>isset($send_array['description'])?$send_array['description']:"",
                                        'title'=>isset($send_array['title'])?$send_array['title']:"",
                                    )
                                );
                            }else{
                            
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'data' => $send_array,
                                	'restricted_package_name' => $package_name
                                );
                            }

                        } else {
                            $send_array['text'] = $send_array['message'];
                            $send_array['sound'] = 'default';
                            $send_array['badge'] = 1;

                            if($login_from=="MENGAGE"){
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'notification' => $send_array,
                                    'notification'=> array(
                                        'body'=>isset($send_array['description'])?$send_array['description']:"",
                                        'title'=>isset($send_array['title'])?$send_array['title']:"",
                                    )
                                );
                            }else{
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'notification' => $send_array,
                                    'notification'=> array(
                                        'body'=>isset($send_array['description'])?$send_array['description']:"",
                                        'title'=>isset($send_array['title'])?$send_array['title']:"",
                                    )
                                    /* 'restricted_package_name' => $package_name*/
                                );
                            }

                        }
                        $headers = array(
                            'Authorization:key=' . $server_key,
                            'Content-Type:application/json'
                        );
                        $module_type = @$send_array['module_type'];
                        $filter_array = array('APPOINTMENT', 'MESSAGE', 'HEALTH_TIP', 'CHAT', 'DOCUMENT', 'SHARE_DRIVE');
                        if ($device_type == "ANDROID" || ($device_type == "IOS" && in_array($module_type, $filter_array))) {

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                            $return_array[] = json_decode(curl_exec($ch), true);
                            curl_close($ch);
                        }
                    }
                }
            }
            return $return_array;
        }
    }




    public static function send_web_notification_via_token($send_array=array(),$token=null)
    {

        $return_array = array();
        $fields = array(
            'priority' => 'high',
            'registration_ids' => array($token),
            'notification' => $send_array
        );

        /*$fields = array(

                "data"=>array(
                "notification"=>array(
                    "title"=>"FCM Message",
                    "body"=>"This is an FCM Message",
                    "icon"=>"/itwonders-web-logo.png",
                    )
                ),
              "to"=>$token

        );*/

        $server_key = "AAAAnC1BcsM:APA91bFC9oY68p6FPQB3bFPHk_gGrwjotCSm1-E5ntSDC9Q0jRiCbEmube2h_eNvMEp9XgQXPe52cJc7EYyCvOhCudqGcCn0L6OQ-FUmQgJzN878geok7eoZMRPkelU4ivQu1GmklTKYKTl3Hp-mfZb3usy5Bs-dZw";
        $headers = array(
            'Authorization:key=' . $server_key,
            'Content-Type:application/json'
        );

        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $return_array[] = json_decode(curl_exec($ch), true);
        curl_close($ch);


    }



    public static function send_mengage_clinic_notification($option_array=array(),$token_or_id_array=array(),$send_via ='TOKEN')
    {
        $return_array = array();
        $implode_string =  '"'.implode('","', $token_or_id_array).'"';
        $condition  = " firebase_token IN($implode_string)";
        if($send_via=='ID'){
            $condition  = " user_id IN($implode_string)";
        }
        $query = "select firebase_token from mengage_clinic_user_tokens  where $condition  ";
        $connection = ConnectionUtil::getConnection();
        $token_list = $connection->query($query);
        if ($token_list->num_rows) {
            $token_array = array_column(mysqli_fetch_all($token_list,MYSQLI_ASSOC),'firebase_token');
            if (!empty($token_array)) {
                $thin_app_data = Custom::getThinAppData(MENGAGE_CLINIC);
                $server_key = $thin_app_data['firebase_server_key'];
                $package_name = $thin_app_data['package_name'];
                $option_array['APP_CATEGORY'] = $thin_app_data['category_name'];
                $option_array['appointment_user_role'] = 'USER';
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $option_array['notification_date'] = date('d M Y h:i A');
                $fields = array(
                    'priority' => 'high',
                    'registration_ids' => $token_array,
                    'data' => $option_array,
                    'restricted_package_name' => $package_name
                );
                $headers = array(
                    'Authorization:key=' . $server_key,
                    'Content-Type:application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $return_array[] = json_decode(curl_exec($ch), true);
                curl_close($ch);
                return $return_array;
            }
        }else{
            return false;
        }

    }


    public static function send_notification_by_user_id($send_array=array(),$user_ids=array(),$thin_app_id,$send_message_to_mengage=false){
        $limit = count($user_ids);
        $user_ids = implode(",",$user_ids);
        $connection = ConnectionUtil::getConnection();
        $return_array = array();
        $query = "select firebase_token,device_type,login_from from users where id IN ( $user_ids ) and app_installed_status ='INSTALLED' limit $limit";
        $firebase_token = $connection->query($query);
        if ($firebase_token->num_rows) {
            $firebase_token = mysqli_fetch_all($firebase_token, MYSQLI_ASSOC);
            $separate_array=array();
            foreach ($firebase_token as $key => $token){
                if($token['device_type']=="ANDROID"){
                    $separate_array['ANDROID'][$token['login_from']][] = $token['firebase_token'];
                }else if($token['device_type']=="IOS"){
                    $separate_array['IOS'][$token['login_from']][] = $token['firebase_token'];
                }
            }
            foreach ($separate_array as $device_type => $login_type_list) {
                foreach($login_type_list as $login_from => $token_array) {
                    $array_chunk = array_chunk($token_array, MULTI_CURL_LIMIT);

                    $app_id = $thin_app_id;
                    if ($send_message_to_mengage === true) {
                        $app_id = MENGAGE_APP_ID;
                        $send_array['thinapp_id'] = MENGAGE_APP_ID;
                        $send_array['doctors_thin_app_id'] = $thin_app_id;
                    }else{
                        $send_array['thinapp_id'] = $thin_app_id;
                        $send_array['doctors_thin_app_id'] = $thin_app_id;
                    }
                    $thin_app_data = Custom::getThinAppData($app_id);
                    $server_key = $thin_app_data['firebase_server_key'];
                    $package_name = $thin_app_data['package_name'];
                    $send_array['APP_CATEGORY'] = $thin_app_data['category_name'];
                    //$lab_user = Custom::get_lab_user_static_data($thin_app_id,@$send_array['user_mobile'],@$send_array['role_id']);
                    //$send_array['lab_user_role'] = $lab_user['lab_user_role'];
                    //$send_array['lab_user_id'] = $lab_user['lab_user_id'];
                    $send_array['appointment_user_role'] = 'USER';


                    $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                    $send_array['notification_date'] = date('d M Y h:i A');
                    foreach ($array_chunk as $chunk_key => $tokens) {
                        if ($device_type == "ANDROID") {
                            if($login_from=="MENGAGE"){
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'data' => $send_array,
                                	'notification'=> array(
                                        'body'=>isset($send_array['description'])?$send_array['description']:"",
                                        'title'=>isset($send_array['title'])?$send_array['title']:"",
                                    )
                                );
                            }else{
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'data' => $send_array,
                                	'notification'=> array(
                                        'body'=>isset($send_array['description'])?$send_array['description']:"",
                                        'title'=>isset($send_array['title'])?$send_array['title']:"",
                                    ),
                                    'restricted_package_name' => $package_name
                                );
                            }

                        } else {
                            $send_array['text'] = $send_array['message'];
                            $send_array['sound'] = 'default';
                            $send_array['badge'] = 1;

                            if($login_from=="MENGAGE"){
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'notification' => $send_array
                                );
                            }else{
                                $fields = array(
                                    'priority' => 'high',
                                    'registration_ids' => $tokens,
                                    'notification' => $send_array,
                                    'restricted_package_name' => $package_name
                                );
                            }


                        }
                        $headers = array(
                            'Authorization:key=' . $server_key,
                            'Content-Type:application/json'
                        );
                        $module_type = @$send_array['module_type'];
                        $filter_array = array('APPOINTMENT', 'MESSAGE', 'HEALTH_TIP', 'CHAT', 'DOCUMENT', 'SHARE_DRIVE');
                        if ($device_type == "ANDROID" || ($device_type == "IOS" && in_array($module_type, $filter_array))) {

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                            curl_setopt($ch, CURLOPT_POST, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                            $return_array[] = json_decode(curl_exec($ch), true);
                            curl_close($ch);
                        }
                    }
                }
            }
            return $return_array;
        }
    }




    public static function collaborator_list_for_channel($channel_id=null) {
        $query = "select * from collaborators  where  channel_id = $channel_id and status != 'CANCELED'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }

    }

    public static function is_collobrator_for_channel($user_id=null,$channel_id=null,$thinapp_id=null) {
        $query =    $query = "select * from collaborators  where  user_id = $user_id and channel_id = $channel_id and thinapp_id = $thinapp_id and status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function user_is_collaboraotr($user_id=null,$thin_app_id=null) {
        if(empty($user_id)){
            $user_id =0;
        }
        $query = "select id  from collaborators  where user_id = $user_id and status != 'CANCELED' and thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return "YES";
        }else{
            return "NO";
        }
    }


    public static function get_channel_and_subscriber_count($channel_id){
        $query =    $query = "select u.role_id as owner_role_id, ( SELECT count(s.id) FROM subscribers as s WHERE s.channel_id = c.id and s.status = 'SUBSCRIBED' ) AS subscriber_count, c.* from channels as c join users as u on c.user_id = u.id  where  c.id = $channel_id and c.status = 'Y' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_channel_and_user_count($channel_id){
        $query =    $query = "select u.role_id as owner_role_id, ( SELECT count(inu.id) FROM users as inu WHERE inu.thinapp_id = c.app_id and (inu.role_id = 1 OR inu.role_id = 5) ) AS subscriber_count, c.* from channels as c join users as u on c.user_id = u.id  where  c.id = $channel_id and c.status = 'Y' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }



    public static function get_app_user_count($thin_app_id){
        $query =    $query = "select count(id) as total_user from users where thinapp_id = $thin_app_id and (role_id = 1 OR role_id = 5) ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['total_user'];
        }else{
            return false;
        }
    }

    public static function get_appointment_customer_count($user_id){
        $query =    $query = "select count(ac.id) as cnt from appointment_customers as ac left join users as u on u.thinapp_id = ac.thinapp_id and u.mobile = ac.mobile and ac.status = 'ACTIVE'  where  u.id = $user_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['cnt'];
        }else{
            return 0;
        }
    }

    public static function is_file_share($share_with_mobile,$share_from_mobile,$drive_file_id,$drive_folder_id){
        $query =    $query = "select id from drive_shares WHERE share_with_mobile = $share_with_mobile and share_from_mobile=$share_from_mobile and drive_file_id = $drive_file_id and drive_folder_id =$drive_folder_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return true;
        }else{
            return false;
        }
    }

    public static function get_channel_by_id($channel_id){
        $query =    $query = "select c.* from channels as c where  c.id = $channel_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_message_by_id($message_id){
        $query =    $query = "select m.*,u.firebase_token  from messages as m left join users as u on m.owner_user_id = u.id where  m.id = $message_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_channel_owner_id($channel_id){
        $query =    $query = "select c.user_id from channels as c where  c.id = $channel_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['user_id'];
        }else{
            return 0;
        }
    }


    /* function add by mahendra*/
    public static function check_user_permission($thin_app_id,$label_key,$send_boolean = false) {

        $permission = '';
        $response = WebservicesFunction::readJson('get_app_enabled_functionality_' . $thin_app_id,'permission');
        if(isset($response['data']['features'][$label_key])){
            $permission =$response['data']['features'][$label_key];
            if($send_boolean===true && $permission =='NO'){
                return false;
            }
            return $permission;
        }
        if(empty($permission)){
            $query = "select uefp.permission from user_enabled_fun_permissions as uefp  join user_functionality_types as uft on uft.id = uefp.user_functionality_type_id where  uefp.thinapp_id = $thin_app_id and uft.label_key = '$label_key' limit 1";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                return $staff_data['permission'];
            }else{
                if($send_boolean===false){
                    return "NO";
                }else{
                    return false;
                }

            }
        }
    }

    /* function add by mahendra*/
    public static function check_module_enable_permission($thin_app_id,$lable_key) {
        $query = "select aef.id from app_enable_functionalities as aef  join app_functionality_types as aft on aft.id = aef.app_functionality_type_id where  aef.thinapp_id = $thin_app_id and aft.label_key = '$lable_key' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return "YES";
        }else{
            return "NO";
        }
    }


    public static function get_topic_name($channel_id){
        $query = "select topic_name from channels  where  id = $channel_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['topic_name'];
        }else{
            return false;
        }
    }

    public static function get_unregistered_subscriber_number($channel_id, $thin_app_id){
        $query = "select mobile from subscribers  where  channel_id = $channel_id and mobile != '' and app_id = $thin_app_id and app_user_id = 0 and status = 'SUBSCRIBED'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $numbers = array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC), 'mobile');
            return $numbers;
        }else{
            return false;
        }
    }

    public static function get_unregistered_subscriber_number_new($channel_id, $thin_app_id){
        //$query = "select sub.mobile,users.id from subscribers AS sub LEFT JOIN users ON (sub.mobile = users.mobile AND `users`.`app_installed_status` = 'INSTALLED') where sub.channel_id = $channel_id and sub.mobile != '' and sub.app_id = $thin_app_id and sub.app_user_id = 0 and sub.status = 'SUBSCRIBED' AND users.id <> 0";

        $query = "select DISTINCT( sub.mobile ),users.id from subscribers AS sub LEFT JOIN users ON (sub.mobile = users.mobile AND sub.app_id = users.thinapp_id AND `users`.`app_installed_status` = 'UNINSTALLED') where sub.channel_id = $channel_id and sub.mobile != '' and sub.app_id = $thin_app_id and sub.status = 'SUBSCRIBED'";

        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $numbers = array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC), 'mobile');
            return $numbers;
        }else{
            return false;
        }
    }



    public static function send_topic_notification($send_array=array(),$topic_name =null,$thin_app_id){
        if(empty($topic_name)){
            $topic_name = Custom::get_topic_name($send_array['channel_id']);
        }


        $thin_app_data = Custom::getThinAppData($thin_app_id);
        $package_name = $thin_app_data['package_name'];
        $send_array['APP_CATEGORY'] = $thin_app_data['category_name'];
        //$lab_user = Custom::get_lab_user_static_data($thin_app_id,@$send_array['user_mobile'],@$send_array['role_id']);
        //$send_array['lab_user_role'] = $lab_user['lab_user_role'];
        //$send_array['lab_user_id'] = $lab_user['lab_user_id'];
        $send_array['appointment_user_role'] = 'USER';


        $send_array['notification_date'] =date('d M Y h:i A');
        if(!empty($topic_name) && !empty(FIREBASE_KEY)){
            $server_key =FIREBASE_KEY;
            $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';



            if(!empty($package_name)){
                $fields = array(
                    'to' => '/topics/'.$topic_name,
                    'data' => $send_array,
                    'priority' =>'high'
                    //'restricted_package_name'=>$package_name
                );
            }else{
                $fields = array(
                    'to' => '/topics/'.$topic_name,
                    'data' => $send_array,
                    'priority' =>'high',
                );
            }
            $headers = array(
                'Authorization:key='.$server_key,
                'Content-Type:application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }

    }


    public static function add_subscribers_to_topic($topic_name,$user_firebase_token=array(), $user_ids=array()){

        if(empty($user_firebase_token)){
            if(!empty($user_ids)){
                $connection = ConnectionUtil::getConnection();
                $limit = count($user_ids);
                $user_ids = implode(",",$user_ids);
                $query = "select u.firebase_token from users as u where u.id IN ( $user_ids ) and u.status = 'Y' limit $limit";
                $subscriber = $connection->query($query);
                if ($subscriber->num_rows) {
                    $user_firebase_token = array_column(mysqli_fetch_all($subscriber, MYSQLI_ASSOC),'firebase_token');
                }
            }
        }

        if(!empty($user_firebase_token)){
            $server_key =FIREBASE_KEY;
            $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
            $fields = array(
                'to' => '/topics/'.$topic_name,
                'registration_tokens' => $user_firebase_token,
            );
            $headers = array(
                'Authorization:key='.$server_key,
                'Content-Type:application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }

    }



    public static function get_master_vaccination(){
        $query =    $query = "select * from master_vaccinations where status ='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function is_app_master_vaccination($thin_app_id){
        $query = "select id from app_master_vaccinations where thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            return true;
        }else{
            return false;
        }

    }



    public static function clone_master_vaccination($thin_app_id){
        $is_vacc = Custom::is_app_master_vaccination($thin_app_id);
        if (!$is_vacc) {
            $vac_list = Custom::get_master_vaccination();
            if(!empty($vac_list)){
                $created = Custom::created();
                $query = "INSERT INTO app_master_vaccinations (thinapp_id,full_name,vac_name,vac_dose_name,vac_time,remark,vac_type,category_type,created,modified) SELECT ?,full_name,vac_name,vac_dose_name,vac_time,remark,vac_type,category_type,?,? FROM master_vaccinations where status ='ACTIVE'";
                $connection = ConnectionUtil::getConnection();
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sss', $thin_app_id, $created, $created);
                if ($stmt->execute()) {
                    return true;
                }
            }
        }
        return false;
    }




    public static function is_child_vaccination($thin_app_id,$child_id){
        $query = "select id from child_vaccinations where thinapp_id = $thin_app_id and children_id = $child_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            return true;
        }else{
            return false;
        }

    }


    public static function get_app_master_vaccination($thin_app_id){
        $query =    $query = "select * from app_master_vaccinations where thinapp_id = $thin_app_id and status ='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function clone_child_vaccination($connection, $thin_app_id, $child_id,$dob,$app_master_vac_id =null){
        $condition ="";
        if(empty($app_master_vac_id)){
            $is_vacc = Custom::is_child_vaccination($thin_app_id,$child_id);
        }else{
            $is_vacc = false;
            $condition = " and id = ".$app_master_vac_id;
        }
        if (!$is_vacc) {
            $created = Custom::created();
            $dob =date('Y-m-d',strtotime($dob));
            $query = "INSERT INTO child_vaccinations (thinapp_id,children_id,app_master_vaccination_id,vac_date,category_type,created,modified) SELECT ?,?,id,CASE IF(vac_time <> 'BIRTH', SUBSTRING_INDEX(SUBSTRING_INDEX(vac_time,' ', 2), ' ',-1), 'DAY')  WHEN 'DAY' THEN date_add(?, INTERVAL IF(vac_time <> 'BIRTH', SUBSTRING_INDEX(SUBSTRING_INDEX(vac_time,' ', 1), ' ',-1), 0) DAY) WHEN 'WEEK' THEN date_add(?, INTERVAL IF(vac_time <> 'BIRTH', SUBSTRING_INDEX(SUBSTRING_INDEX(vac_time,' ', 1), ' ',-1), 0) WEEK) WHEN 'MONTH' THEN date_add(?, INTERVAL IF(vac_time <> 'BIRTH', SUBSTRING_INDEX(SUBSTRING_INDEX(vac_time,' ', 1), ' ',-1), 0) MONTH)  WHEN 'YEAR' THEN date_add(?, INTERVAL IF(vac_time <> 'BIRTH', SUBSTRING_INDEX(SUBSTRING_INDEX(vac_time,' ', 1), ' ',-1), 0) YEAR)  END AS newDate,category_type,?,? FROM app_master_vaccinations where category_type ='CHILD VACCINATION' and thinapp_id = $thin_app_id $condition";
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ssssssss', $thin_app_id, $child_id, $dob,$dob,$dob,$dob, $created, $created);
            if ($stmt->execute()) {
                return true;
            }
        }
        return false;
    }



    public static function update_child_vaccination_by_master_update($connection, $thin_app_id, $app_master_vaccination_id,$interval){

        $query = "select cv.id, c.dob from  child_vaccinations as cv join childrens as c on c.id = cv.children_id where cv.thinapp_id =$thin_app_id and cv.app_master_vaccination_id = $app_master_vaccination_id";
        $subscriber = $connection->query($query);
        $total_result =array();
        if ($subscriber->num_rows) {
            $child_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
            foreach ($child_list as $key => $value){
                $vac_date =$value['dob'];
                $created = Custom::created();
                if($interval != "BIRTH"){
                    $vac_date = Custom::add_date_method($vac_date,$interval);
                }
                $query = "update child_vaccinations set vac_date =?, modified =? where id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sss', $vac_date, $created, $value['id']);
                if ($stmt->execute()) {
                    $total_result[] =  true;
                }else{
                    $total_result[] =  false;
                }
            }
        }
        if(!in_array(false,$total_result)){
            return true;
        }else{
            return false;
        }
    }

    public static function update_clone_child_vaccination($connection, $thin_app_id, $child_id,$dob){

        $query = "select cv.id, amv.vac_time from  child_vaccinations as cv join app_master_vaccinations as amv on amv.id = cv.app_master_vaccination_id where cv.category_type ='CHILD VACCINATION' and cv.thinapp_id =$thin_app_id and cv.children_id = $child_id";
        $subscriber = $connection->query($query);
        $total_result =array();
        if ($subscriber->num_rows) {
            $child_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
            foreach ($child_list as $key => $value){
                $vac_date =$dob;
                $created = Custom::created();
                if($value['vac_time'] != "BIRTH"){
                    $vac_date = Custom::add_date_method($vac_date,$value['vac_time']);
                }
                $query = "update child_vaccinations set vac_date =?, modified =? where id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sss', $vac_date, $created, $value['id']);
                if ($stmt->execute()) {
                    $total_result[] =  true;
                }else{
                    $total_result[] =  false;
                }
            }
        }


        if(!in_array(false,$total_result)){
            return true;
        }else{
            return false;
        }
    }

    public static function get_child_adolescent_vaccination($connection, $thin_app_id, $child_id,$vac_id){

        $return_array=array();
        $query = "select vac_image as image, cv.id, amv.vac_name, cv.remark, cv.adolescent_dose_name as dose_name,DATE_FORMAT(IFNULL(cv.reschedule_date, cv.vac_date),'%d-%m-%Y') as vac_date, cv.status from child_vaccinations as cv join app_master_vaccinations as amv on cv.app_master_vaccination_id = amv.id and cv.children_id =$child_id and cv.app_master_vaccination_id = $vac_id and amv.status ='ACTIVE' order by vac_date asc";
        $subscriber = $connection->query($query);
        if ($subscriber->num_rows) {
            $return_array = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
        }
        return $return_array;
    }



    public static function get_child_adolescent_vaccination_rest_dose($connection, $thin_app_id, $child_id,$vac_id){
        $return_array = $list_array =array();
        $query = "select  cv.adolescent_dose_name as dose_name from child_vaccinations as cv join app_master_vaccinations as amv on cv.app_master_vaccination_id = amv.id and cv.children_id =$child_id and cv.app_master_vaccination_id = $vac_id and amv.status ='ACTIVE'";
        $subscriber = $connection->query($query);
        if ($subscriber->num_rows) {
            $list_array = array_column(mysqli_fetch_all($subscriber, MYSQLI_ASSOC),'dose_name');
        }
        $counter =0;
        for($dose_counter=1;$dose_counter<=15;$dose_counter++){
            if($dose_counter <= 10){
                $dose_name = "Dose ".$dose_counter;
                if(!in_array($dose_name,$list_array)){
                    $return_array[$counter]['name'] = $dose_name;
                    $counter++;
                }
            }else{
                $booster_counter = $dose_counter-10;
                $dose_name = "Booster ".$booster_counter;
                if(!in_array($dose_name,$list_array)){
                    $return_array[$counter]['name'] = $dose_name;
                    $counter++;
                }
            }
        }
        return $return_array;
    }




    public static function getDoctorAppointmentCountByAddress($connection,$thin_app_id, $doctor_id,$address_id,$date,$service_id = 0,$application_type=""){
        $return_array=array();
        $counter = $total_count =0;
        $service_condition = !empty($service_id)?" and acss.appointment_service_id = $service_id ":"";

        $enum_array = Custom::get_enum_values("appointment_customer_staff_services","status");
        $query = "select acss.status, count(1) as cnt from appointment_customer_staff_services as acss where   DATE(acss.appointment_datetime) = DATE('$date') and acss.appointment_staff_id = $doctor_id and acss.appointment_address_id = $address_id and acss.thinapp_id = $thin_app_id  and  acss.delete_status != 'DELETED' AND ( acss.is_paid_booking_convenience_fee ='NO' OR acss.is_paid_booking_convenience_fee ='NOT_APPLICABLE' OR acss.is_paid_booking_convenience_fee ='YES' ) $service_condition group by acss.status";
        $list_obj = $connection->query($query);
        if ($list_obj->num_rows) {
            $list  =  array_column(mysqli_fetch_all($list_obj, MYSQLI_ASSOC),"cnt","status");
            foreach ($enum_array as $key => $enum_name){
                if(array_key_exists($enum_name,$list)){
                    if($enum_name=="CONFIRM"){
                        $enum_name ='NEW';
                        $last_total = $return_array[ucfirst(strtolower($enum_name))];
                        $return_array[ucfirst(strtolower($enum_name))] = $last_total+ $list["CONFIRM"];
                        $total_count += $list["CONFIRM"];
                    }else{
                        $return_array[ucfirst(strtolower($enum_name))] = $list[$enum_name];
                        $total_count += $list[$enum_name];
                    }
                }else{
                    if($enum_name!='CONFIRM'){
                        $return_array[ucfirst(strtolower($enum_name))] = 0;
                    }
                }
            }
            $return_array["Total"] = $total_count;
        }else{
            foreach ($enum_array as $key => $enum_name){
                if($enum_name!='CONFIRM'){
                    $return_array[ucfirst(strtolower($enum_name))] = 0;
                }
            }
            $return_array["Total"] = 0;
        }
        $return_array['Success'] = $return_array['Pending'] = 0;
        $query = "select acss.payment_status, count(1) as cnt from appointment_customer_staff_services as acss where   DATE(acss.appointment_datetime) = DATE('$date') and acss.appointment_staff_id = $doctor_id and acss.appointment_address_id = $address_id and acss.thinapp_id = $thin_app_id  and acss.payment_status IN('PENDING','SUCCESS') AND ( acss.is_paid_booking_convenience_fee ='NO' OR acss.is_paid_booking_convenience_fee ='NOT_APPLICABLE' OR acss.is_paid_booking_convenience_fee ='YES' )  $service_condition group by acss.payment_status";
        $list_obj = $connection->query($query);
        if ($list_obj->num_rows) {
            $tmp = mysqli_fetch_all($list_obj,MYSQLI_ASSOC);
            foreach($tmp as $key => $value){
                $return_array[ucfirst(strtolower(($value['payment_status'])))]= $value['cnt'];
            }
        }
        $final_array = array();
        if ($application_type =="FLUTTER" || Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
            $counter=1;
            foreach($return_array as $key => $value){
                $tmp = array('filter_name'=>$key,'filter_count'=>$value);
                if(strtoupper($key) == "TOTAL"){
                    array_unshift($final_array,$tmp);
                }else{
                    $final_array[] = $tmp;
                }
            }
            return $final_array;
        }else{
            return $return_array;
        }
    }

    public static function getDoctorAppointmentCountByAddressAndService($connection,$thin_app_id, $doctor_id,$service_id, $address_id,$date){
        $return_array=array();
        $counter = $total_count =0;
        $enum_array = Custom::get_enum_values("appointment_customer_staff_services","status");
        $query = "select acss.status, count(1) as cnt from appointment_customer_staff_services as acss where   DATE(acss.appointment_datetime) = DATE('$date') and acss.appointment_staff_id = $doctor_id and acss.appointment_service_id = $service_id and  acss.appointment_address_id = $address_id and acss.thinapp_id = $thin_app_id  group by acss.status";
        $list_obj = $connection->query($query);
        if ($list_obj->num_rows) {
            $list  =  array_column(mysqli_fetch_all($list_obj, MYSQLI_ASSOC),"cnt","status");
            $tmp =array();
            foreach ($enum_array as $key => $enum_name){
                if(array_key_exists($enum_name,$list)){
                    $return_array[$key+1] = array('filter_name'=>ucfirst(strtolower($enum_name)),'filter_count'=>$list[$enum_name]);
                    $total_count += $list[$enum_name];
                }else{
                    $return_array[$key+1] = array('filter_name'=>ucfirst(strtolower($enum_name)),'filter_count'=>0);
                }
            }
            $return_array[0] = array('filter_name'=>'Total','filter_count'=>$total_count);

        }else{
            $return_array[0] = array('filter_name'=>'Total','filter_count'=>0);
            foreach ($enum_array as $key => $enum_name){
                $return_array[$key+1] = array('filter_name'=>ucfirst(strtolower($enum_name)),'filter_count'=>0);
            }


        }
        ksort($return_array);
        return ($return_array);
    }


    public static function get_doctor_appointment_list($connection, $thin_app_id, $doctor_id,$address_id, $date,$appointment_status,$customer_id = 0,$user_type ="CUSTOMER",$offset=null,$search=null,$user_role=null){

         $condition = " and delete_status != 'DELETED' AND ( acss.is_paid_booking_convenience_fee ='NO' OR acss.is_paid_booking_convenience_fee ='NOT_APPLICABLE' OR acss.is_paid_booking_convenience_fee ='YES' ) ";
       
        $order_by = "order by acss.appointment_datetime asc, acss.queue_number asc";
        $limit ='';;
        $smart_clinic = Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC');
        if (!empty($user_role) && $smart_clinic && strtoupper(strtolower($appointment_status)) =="TRACKER") {
                $service_data = Custom::get_doctor_service_data($doctor_id);
                $tracker_list = Custom::get_live_tracker_data($thin_app_id,$date,$doctor_id,$address_id,$service_data['id'],$user_role);
                $ids = array();
                if(!empty($tracker_list)){
                    foreach($tracker_list as $key => $appointment_data){
                        if(Custom::visible_live_tracker_data($appointment_data,$user_role)){
                            $ids[] = $appointment_data['id'];
                        }
                    }
                    $ids =  '"'.implode('","', $ids).'"';
                    $condition = " and acss.id IN($ids) ";
                    $order_by = " ORDER BY FIELD(acss.id, $ids) ";
                }
        }else{
            $smart_clinic = false;
            if(is_numeric($offset)){
                $limit = "limit ".($offset * APP_PAGINATION_LIMIT).", ".APP_PAGINATION_LIMIT;
            }
            if($appointment_status !="TOTAL"){
                if($appointment_status=="NEW" || $appointment_status =='CONFIRM'){
                    $condition .= " AND acss.status IN('NEW','CONFIRM') ";
                }else if($appointment_status=="SUCCESS" || $appointment_status=="PENDING"){
                    $condition .= " AND acss.payment_status ='$appointment_status'";
                }else{
                    $condition .= " AND acss.status ='$appointment_status' ";
                }
            }
        }

        if(!empty($search)){
            $condition .= " AND ( acss.queue_number ='$search' OR RIGHT(app_cus.mobile,10) ='$search' OR RIGHT(c.mobile,10)  ='$search' )";
            $limit = "limit 1";
        }




        $patientHistoryUrl = SITE_PATH.'tracker/get_patient_history/';
        $select = " IFNULL(app_cus.uhid,c.uhid) as uhid, acss.appointment_booked_from, acss.reason_of_appointment, acss.consulting_type, acss.consent_id, cs.response as ans_result, app_sta.is_online_consulting, acss.skip_tracker, acss.patient_queue_checked_in, acss.patient_queue_type, acss.custom_token, acss.emergency_appointment, acss.has_token, acss.created, mpo.payment_type_name, CONCAT('$patientHistoryUrl',TO_BASE64(IFNULL(app_cus.thinapp_id,c.thinapp_id)),'/',TO_BASE64(IFNULL(app_cus.uhid,c.uhid)),'/',TO_BASE64(acss.id)) as history_url, IF(abu.status='ACTIVE','BLOCK','UNBLOCK') AS is_block, acss.has_token, acss.skip_tracker as is_skip, IF(DATE_FORMAT(IFNULL(app_cus.dob,c.dob),'%d-%m-%Y') ='00-00-0000','',DATE_FORMAT(IFNULL(app_cus.dob,c.dob),'%d-%m-%Y')) as dob,IFNULL(app_cus.gender,c.gender) as gender,  app_sta.payment_mode,  app_sta.show_fees, acss.booking_payment_type, df.id as folder_id, df.folder_name, df.allow_add_file,   acss.booking_payment_type, acss.queue_number, IFNULL(app_cus.address,c.address) as customer_address, acss.appointment_customer_id, c.id as children_id, acss.booking_validity_attempt, acss.booking_validity_attempt, IFNULL(app_cus.profile_photo,c.image) as customer_photo , IFNULL(app_cus.mobile,c.mobile) as customer_mobile, app_add.id as address_id, app_sta.show_appointment_token, app_sta.id as doctor_id, app_ser.service_slot_duration as service_duration, app_sta.profile_photo, acss.appointment_patient_name as customer_name, acss.payment_status as payment_status, acss.id as appointment_id, app_add.address, acss.slot_time,  acss.appointment_datetime, app_ser.name as service_name, app_sta.sub_title, app_sta.name as staff_name, IFNULL((SELECT mpod.total_amount FROM medical_product_order_details as mpod where mpod.thinapp_id= acss.thinapp_id and  mpod.medical_product_order_id = acss.medical_product_order_id and mpod.medical_product_id = 0 and mpod.total_amount > 0 LIMIT 1 ),acss.amount) as service_amt, acss.status as app_status";
        if($customer_id  > 0){
            if($user_type == "CUSTOMER"){
                $condition .= " AND acss.appointment_customer_id =$customer_id ";
            }else if($user_type == "CHILDREN"){
                $condition .= " AND acss.children_id = $customer_id ";
            }
            $query = "select $select from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id AND app_sta.status ='ACTIVE' join  appointment_addresses as app_add on acss.appointment_address_id = app_add.id AND app_add.status ='ACTIVE' left join appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id AND app_cus.status ='ACTIVE' left join childrens as c on c.id = acss.children_id join  appointment_services as app_ser on acss.appointment_service_id = app_ser.id AND app_ser.status ='ACTIVE' left join app_blocked_users as abu on  ( (abu.mobile =app_cus.mobile and abu.thinapp_id = app_cus.thinapp_id ) OR ( abu.mobile =c.mobile and abu.thinapp_id = c.thinapp_id ) ) left join drive_folders as df on  df.id = acss.drive_folder_id left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id left join corona_survey as cs on cs.appointment_id = acss.id where  acss.appointment_staff_id = $doctor_id  and acss.thinapp_id =$thin_app_id $condition  $order_by $limit";
        }else{
            $query = "select $select from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id AND app_sta.status ='ACTIVE' join  appointment_addresses as app_add on acss.appointment_address_id = app_add.id AND app_add.status ='ACTIVE' left join appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id AND app_cus.status ='ACTIVE' left join childrens as c on c.id = acss.children_id join  appointment_services as app_ser on acss.appointment_service_id = app_ser.id AND app_ser.status ='ACTIVE'  left join app_blocked_users as abu on  ( (abu.mobile =app_cus.mobile and abu.thinapp_id = app_cus.thinapp_id ) OR ( abu.mobile =c.mobile and abu.thinapp_id = c.thinapp_id ) ) left join drive_folders as df on df.id = acss.drive_folder_id left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id left join corona_survey as cs on cs.appointment_id = acss.id where  DATE(acss.appointment_datetime) = DATE('$date')  and acss.appointment_staff_id = $doctor_id and acss.appointment_address_id = $address_id and acss.thinapp_id =$thin_app_id $condition  $order_by $limit";
        }


        $service_message_list = $connection->query($query);
        $response = array();
        $final_array = $normal_appointment = $emergency_appointment =  array();
        if ($service_message_list->num_rows) {
            $service_list = mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
            foreach ($service_list as $key => $value) {
                $final_array[$key]['smart_clinic']  = (strtoupper(strtolower($appointment_status)) =="TRACKER" && $smart_clinic)?'YES':'NO';
                $final_array[$key]['folder_id'] = $value['folder_id'];
                $final_array[$key]['folder_name'] = $value['folder_name'];
                $final_array[$key]['allow_add_file'] = $value['allow_add_file'];
                $final_array[$key]['title'] ='';
                $gender = ($value['gender'] == 'MALE') ? "Boy" : "Girl";
                if(!empty($value['dob']) && $value['dob'] !='0000-00-00'){
                    $age_string = Custom::dob_elapsed_string($value['dob']);
                    $final_array[$key]['title'] = $age_string . ", " . $gender;
                }
            	$final_array[$key]['uhid'] = $value['uhid'];
                $final_array[$key]['show_fees'] = $value['show_fees'];
            	$final_array[$key]['reason_of_appointment'] = $value['reason_of_appointment'];
                $final_array[$key]['is_skip'] = $value['is_skip'];
                $final_array[$key]['is_block'] = $value['is_block'];
                $final_array[$key]['customer_dob'] = $value['dob'];
                $final_array[$key]['customer_gender'] = $value['gender'];
                $final_array[$key]['customer_id'] = $value['appointment_customer_id'];
                $final_array[$key]['children_id'] = !empty($value['children_id'])?$value['children_id']:0;
                $final_array[$key]['children_appointment'] = !empty($value['children_id'])?"YES":"NO";
                $final_array[$key]['appointment_id'] = $value['appointment_id'];
                $final_array[$key]['address_id'] = $value['address_id'];
                $final_array[$key]['doctor_id'] = $value['doctor_id'];
                $final_array[$key]['service_name'] = $value['service_name'];
                $final_array[$key]['service_duration'] = $value['service_duration'];
                $final_array[$key]['customer_name'] = $value['customer_name'];
                $final_array[$key]['customer_mobile'] = $value['customer_mobile'];
                $final_array[$key]['customer_address'] = $value['customer_address'];
                $final_array[$key]['doctor_image'] = $value['profile_photo'];
                $final_array[$key]['customer_profile'] = $value['customer_photo'];
                $final_array[$key]['staff_name'] = $value['staff_name'];
                $final_array[$key]['designation'] = $value['sub_title'];
                $final_array[$key]['history_url'] = $value['history_url'];
                $final_array[$key]['emergency_appointment'] = $value['emergency_appointment'];
                $final_array[$key]['appointment_datetime'] = date('d-m-Y', strtotime($value['appointment_datetime']));
                $final_array[$key]['appointment_time'] = $value['slot_time'];
            	$final_array[$key]['app_type'] = $value['consulting_type'];
            	$final_array[$key]['info_link'] = "";
            	$string = ($value['consulting_type'] =='VIDEO')?'ONLINE':$value['consulting_type'];
                if($string=="CHAT"){
                    $string = "Chat Consultation";
                }else if($string=="AUDIO"){
                    $string = "Audio Consultation";
                }else if($string=="OFFLINE"){
                    $string = "Clinic Visit";
                }
                $final_array[$key]['consulting_type'] = $string;

               
            	if(!empty($value['ans_result']) && $value['consulting_type']=='OFFLINE'){
                    $final_array[$key]['info_link'] = SITE_PATH."tracker/corona_result/".$value['appointment_id'];
                }else if(!empty($value['consent_id'])){
                    $final_array[$key]['info_link']  = (FOLDER_PATH . "consent/" . base64_encode($value['consent_id'] . "##" . $thin_app_id))."/".base64_encode('DOCTOR');

                }
            
                if($value['show_appointment_token'] == "YES"){
                    $final_array[$key]['queue_number'] =Custom::create_queue_number($value);
                    if($value['emergency_appointment']=='YES'){
                        $final_array[$key]['appointment_time'] = date('h:i A', strtotime($value['created']));
                    }
                }else{
                    $final_array[$key]['queue_number'] = 0;
                }

                if(!empty($value['payment_type_name'])){
                    $final_array[$key]['payment_via'] = $value['payment_type_name'];
                }else{
                    $final_array[$key]['payment_via'] = ($value['booking_payment_type'] == "ONLINE" && $value['payment_status'] =='SUCCESS') ? "Online" : "-";
                }

                $final_array[$key]['booking_validity_attempt'] = $value['booking_validity_attempt'];
                if($value['booking_validity_attempt'] > 1 && $value['service_amt'] == 0) {
                    $final_array[$key]['service_amount'] = "Free";
                    $final_array[$key]['payment_via'] = "-";
                }else{
                    $final_array[$key]['service_amount'] = ucfirst(strtolower($value['service_amt']));;

                }

            	if($thin_app_id==128){
                	//$final_array[$key]['service_amount'] = '-';
                }
            
            
                $final_array[$key]['address'] = $value['address'];
                $final_array[$key]['payment_status'] = "Pending";
                if($value['payment_status']=="SUCCESS"){
                    $final_array[$key]['payment_status'] =  ucfirst(strtolower($value['payment_status']));
                }

                $final_array[$key]['app_status'] = $value['app_status'];
                if ($value['app_status'] == 'NEW' || $value['app_status'] == 'CONFIRM' || $value['app_status'] == 'RESCHEDULE') {
                    $final_array[$key]['status'] = 'BOOKED';
                }else{
                    $final_array[$key]['status'] = $value['app_status'];

                }

                if($button_list = Custom::create_appointment_button_array($thin_app_id, $user_role,$value,$appointment_status)){
                    foreach($button_list as $button_key =>$show_status){
                        $final_array[$key][$button_key] =$show_status;
                    }
                }





            }

        }
        return $final_array;


    }

    public static function get_doctor_appointment_list_new($connection, $thin_app_id, $doctor_id,$service_id, $address_id, $date,$appointment_status,$customer_id = 0,$user_type ="CUSTOMER",$user_role){

        $condition = " and acss.appointment_service_id = $service_id and acss.appointment_address_id = $address_id ";
        $order_by =" order by  acss.emergency_appointment asc, acss.has_token desc, acss.appointment_datetime desc, acss.queue_number desc ";
        $smart_clinic = Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC');
        if (!empty($user_role) && $smart_clinic && strtoupper(strtolower($appointment_status)) =="TRACKER") {
            $service_data = Custom::get_doctor_service_data($doctor_id);
            $tracker_list = Custom::get_live_tracker_data($thin_app_id,$date,$doctor_id,$address_id,$service_data['id'],$user_role);
            $ids = array();
            if(!empty($tracker_list)){
                foreach($tracker_list as $key => $appointment_data){
                    if(Custom::visible_live_tracker_data($appointment_data,$user_role)){
                        $ids[] = $appointment_data['id'];
                    }
                }
                $ids =  '"'.implode('","', $ids).'"';
                $condition = " and acss.id IN($ids) ";
                $order_by = " ORDER BY FIELD(acss.id, $ids) ";
            }
        }else{
            if($appointment_status !="TOTAL"){
                $smart_clinic = false;
                $appointment_status = strtoupper(strtolower($appointment_status));
                if($appointment_status=="NEW" || $appointment_status == "CONFIRM"){
                    $condition .= " AND acss.status IN('NEW','CONFIRM') ";
                }else if($appointment_status=="SUCCESS" || $appointment_status=="PENDING"){
                    $condition .= " AND acss.payment_status ='$appointment_status' ";
                }else{
                    $condition .= " AND acss.status ='$appointment_status' ";
                }
            }
        }


        if($customer_id  > 0){
            if($user_type == "CUSTOMER"){
                $condition .= " AND acss.appointment_customer_id =$customer_id ";
            }else if($user_type == "CHILDREN"){
                $condition .= " AND acss.children_id = $customer_id ";
            }
            $query = "select acss.skip_tracker, acss.patient_queue_checked_in, acss.patient_queue_type, acss.custom_token, acss.emergency_appointment, acss.has_token, acss.created, mpo.payment_type_name, acss.created, acss.has_token, app_sta.show_appointment_token, acss.emergency_appointment,  mpo.payment_type_name, IF(abu.status='ACTIVE','BLOCK','UNBLOCK') AS is_block, acss.skip_tracker as is_skip, IF(DATE_FORMAT(IFNULL(app_cus.dob,c.dob),'%d-%m-%Y') ='00-00-0000','',DATE_FORMAT(IFNULL(app_cus.dob,c.dob),'%d-%m-%Y')) as dob,IFNULL(app_cus.gender,c.gender) as gender,IFNULL(app_cus.uhid,c.uhid) as uhid,  app_sta.payment_mode,  app_sta.show_fees, acss.booking_payment_type, df.id as folder_id, df.folder_name, df.allow_add_file, acss.booking_payment_type, acss.queue_number, acss.appointment_customer_id, c.id as children_id, acss.booking_validity_attempt, acss.booking_validity_attempt, IFNULL(app_cus.profile_photo,c.image) as customer_photo , IFNULL(app_cus.mobile,c.mobile) as customer_mobile, app_add.id as address_id, app_sta.id as doctor_id, app_ser.service_slot_duration as service_duration, app_sta.profile_photo, IFNULL(app_cus.first_name,c.child_name) as customer_name, acss.payment_status as payment_status, acss.id as appointment_id, app_add.address, acss.slot_time,  acss.appointment_datetime, app_ser.name as service_name, app_sta.sub_title, app_sta.name as staff_name, acss.amount as service_amt, acss.status as app_status from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id AND app_sta.status ='ACTIVE' join  appointment_addresses as app_add on acss.appointment_address_id = app_add.id AND app_add.status ='ACTIVE' left join appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id AND app_cus.status ='ACTIVE' left join childrens as c on c.id = acss.children_id join  appointment_services as app_ser on acss.appointment_service_id = app_ser.id AND app_ser.status ='ACTIVE' left join app_blocked_users as abu on  ( (abu.mobile =app_cus.mobile and abu.thinapp_id = app_cus.thinapp_id ) OR ( abu.mobile =c.mobile and abu.thinapp_id = c.thinapp_id ) ) left join drive_folders as df on ( df.appointment_customer_id = app_cus.id OR df.children_id = c.id)  left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id where  acss.appointment_staff_id = $doctor_id  and acss.thinapp_id =$thin_app_id $condition $order_by ";
        }else{
            $query = "select acss.skip_tracker, acss.patient_queue_checked_in, acss.patient_queue_type, acss.custom_token, acss.emergency_appointment, acss.has_token, acss.created, mpo.payment_type_name, acss.created, acss.has_token, app_sta.show_appointment_token, acss.emergency_appointment, mpo.payment_type_name, IF(abu.status='ACTIVE','BLOCK','UNBLOCK') AS is_block, acss.skip_tracker as is_skip,  IF(DATE_FORMAT(IFNULL(app_cus.dob,c.dob),'%d-%m-%Y') ='00-00-0000','',DATE_FORMAT(IFNULL(app_cus.dob,c.dob),'%d-%m-%Y')) as dob, IFNULL(app_cus.gender,c.gender) as gender,IFNULL(app_cus.uhid,c.uhid) as uhid, app_sta.payment_mode, app_sta.show_fees, acss.booking_payment_type, df.id as folder_id, df.folder_name, df.allow_add_file, acss.booking_payment_type, acss.queue_number, acss.appointment_customer_id, c.id as children_id, acss.booking_validity_attempt, acss.booking_validity_attempt, IFNULL(app_cus.profile_photo,c.image) as customer_photo , IFNULL(app_cus.mobile,c.mobile) as customer_mobile, app_add.id as address_id, app_sta.id as doctor_id, app_ser.service_slot_duration as service_duration, app_sta.profile_photo, IFNULL(app_cus.first_name,c.child_name) as customer_name, acss.payment_status as payment_status, acss.id as appointment_id, app_add.address, acss.slot_time,  acss.appointment_datetime, app_ser.name as service_name, app_sta.sub_title, app_sta.name as staff_name, acss.amount as service_amt, acss.status as app_status from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id AND app_sta.status ='ACTIVE' join  appointment_addresses as app_add on acss.appointment_address_id = app_add.id AND app_add.status ='ACTIVE' left join appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id AND app_cus.status ='ACTIVE' left join childrens as c on c.id = acss.children_id join  appointment_services as app_ser on acss.appointment_service_id = app_ser.id AND app_ser.status ='ACTIVE'  left join app_blocked_users as abu on  ( (abu.mobile =app_cus.mobile and abu.thinapp_id = app_cus.thinapp_id ) OR ( abu.mobile =c.mobile and abu.thinapp_id = c.thinapp_id ) ) left join drive_folders as df on ( df.appointment_customer_id = app_cus.id OR df.children_id = c.id ) left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id where  DATE(acss.appointment_datetime) = DATE('$date')  and acss.appointment_staff_id = $doctor_id and acss.appointment_address_id = $address_id and acss.thinapp_id =$thin_app_id $condition $order_by ";
        }

        $patientHistoryUrl = SITE_PATH.'tracker/get_patient_history/';
        $service_message_list = $connection->query($query);
        $response = array();
        $final_array = array();
        if ($service_message_list->num_rows) {
            $service_list = mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
            foreach ($service_list as $key => $value) {
                $final_array[$key]['smart_clinic']  = ($smart_clinic)?'YES':'NO';
                $final_array[$key]['folder_id'] = $value['folder_id'];
                $final_array[$key]['folder_name'] = $value['folder_name'];
                $final_array[$key]['allow_add_file'] = $value['allow_add_file'];
                $final_array[$key]['title'] ='';
                $gender = ($value['gender'] == 'MALE') ? "Boy" : "Girl";
                if(!empty($value['dob']) && $value['dob'] !='0000-00-00'){
                    $age_string = Custom::dob_elapsed_string($value['dob']);
                    $final_array[$key]['title'] = $age_string . ", " . $gender;
                }
                $final_array[$key]['show_fees'] = $value['show_fees'];
                $final_array[$key]['is_block'] = $value['is_block'];
                $final_array[$key]['is_skip'] = $value['is_skip'];
                $final_array[$key]['customer_dob'] = $value['dob'];
                $final_array[$key]['customer_gender'] = $value['gender'];
                $final_array[$key]['customer_id'] = $value['appointment_customer_id'];
                $final_array[$key]['children_id'] = !empty($value['children_id'])?$value['children_id']:0;
                $final_array[$key]['children_appointment'] = !empty($value['children_id'])?"YES":"NO";
                $final_array[$key]['appointment_id'] = $value['appointment_id'];
                $final_array[$key]['address_id'] = $value['address_id'];
                $final_array[$key]['doctor_id'] = $value['doctor_id'];
                $final_array[$key]['service_name'] = $value['service_name'];
                $final_array[$key]['service_duration'] = $value['service_duration'];
                $final_array[$key]['customer_name'] = $value['customer_name'];
                $final_array[$key]['customer_mobile'] = $value['customer_mobile'];
                $final_array[$key]['doctor_image'] = $value['profile_photo'];
                $final_array[$key]['customer_profile'] = $value['customer_photo'];
                $final_array[$key]['staff_name'] = $value['staff_name'];
                $final_array[$key]['designation'] = $value['sub_title'];
                $final_array[$key]['queue_number'] = $value['queue_number'];
                $final_array[$key]['emergency_appointment'] = $value['emergency_appointment'];
                $final_array[$key]['history_url'] = $patientHistoryUrl.base64_encode($thin_app_id).'/'.base64_encode($value['uhid']);
                if(!empty($value['payment_type_name'])){
                    $final_array[$key]['payment_via'] = $value['payment_type_name'];
                }else{
                    $final_array[$key]['payment_via'] = ($value['booking_payment_type'] == "ONLINE" && $value['payment_status'] =='SUCCESS') ? "Online" : "-";
                }

                $final_array[$key]['appointment_datetime'] = date('d-m-Y', strtotime($value['appointment_datetime']));
                $final_array[$key]['appointment_time'] = $value['slot_time'];

                if($value['emergency_appointment']=='YES'){
                    $final_array[$key]['appointment_time'] = date('h:i A', strtotime($value['created']));
                }

                if($value['show_appointment_token'] == "YES"){
                    $final_array[$key]['queue_number'] = Custom::create_queue_number($value);
                }else{
                    $final_array[$key]['queue_number'] = 0;
                }

                $final_array[$key]['booking_validity_attempt'] = $value['booking_validity_attempt'];
                if($value['booking_validity_attempt'] > 1) {
                    $final_array[$key]['service_amount'] = "Free";
                    $final_array[$key]['payment_via'] = "-";
                }else{
                    $final_array[$key]['service_amount'] = ucfirst(strtolower($value['service_amt']));;

                }

                $final_array[$key]['address'] = $value['address'];
                $final_array[$key]['payment_status'] = "Pending";
                if($value['payment_status']=="SUCCESS"){
                    $final_array[$key]['payment_status'] =  ucfirst(strtolower($value['payment_status']));
                }
                $final_array[$key]['app_status'] = $value['app_status'];
                if ($value['app_status'] == 'NEW' || $value['app_status'] == 'CONFIRM' || $value['app_status'] == 'RESCHEDULE') {
                    $final_array[$key]['status'] = 'BOOKED';
                }else{
                    $final_array[$key]['status'] = $value['app_status'];
                }
                if($button_list = Custom::create_appointment_button_array($thin_app_id, $user_role,$value)){
                    foreach($button_list as $button_key =>$show_status){
                        $final_array[$key][$button_key] =$show_status;
                    }
                }

            }

        }
        return $final_array;
    }


    public static function  add_date_method($date,$interval){
        $explode_arr = explode(" ",$interval);
        if($explode_arr[0] == 1){
            $interval = " + ".$explode_arr[0]." ".$explode_arr[1];
        }else{
            $interval = " +".$explode_arr[0]." ".$explode_arr[1]."S";
        }
        $interval = strtolower($interval);
        return date('Y-m-d',strtotime($date." ".$interval));
    }





    public static function add_new_vaccination_to_child($connection,$app_master_vac_id,$thin_app_id){
        //$connection = ConnectionUtil::getConnection();
        $query = "select DISTINCT c.id, c.dob from childrens as c join child_vaccinations as cv on c.id = cv.children_id where cv.thinapp_id =$thin_app_id";
        $subscriber = $connection->query($query);
        $total_result =array();
        if ($subscriber->num_rows) {
            $child_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);

            foreach ($child_list as $key => $value){
                $total_result[] = Custom::clone_child_vaccination($connection, $thin_app_id, $value['id'],$value['dob'],$app_master_vac_id);
            }
        }

        if(!in_array(false,$total_result)){
            return true;
        }else{
            return false;
        }

    }


    public static function remove_subscribers_from_topic($topic_name,$user_firebase_token=array(), $user_ids=array()){

        if(empty($user_firebase_token)){
            if(!empty($user_ids)){
                $connection = ConnectionUtil::getConnection();
                $limit = count($user_ids);
                $user_ids = implode(",",$user_ids);
                $query = "select u.firebase_token from users as u where u.id IN ( $user_ids ) and u.status = 'Y' limit $limit";
                $subscriber = $connection->query($query);
                if ($subscriber->num_rows) {
                    $user_firebase_token = array_column(mysqli_fetch_all($subscriber, MYSQLI_ASSOC),'firebase_token');
                }
            }
        }

        if(!empty($user_firebase_token)){
            $server_key =FIREBASE_KEY;
            $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchRemove';
            $fields = array(
                'to' => '/topics/'.$topic_name,
                'registration_tokens' => $user_firebase_token,
            );
            $headers = array(
                'Authorization:key='.$server_key,
                'Content-Type:application/json'
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);
            curl_close($ch);
        }

    }

    public static function check_image_path_string($profile_photo){
        if (!empty($profile_photo)) {
            $exp = explode("/",$profile_photo);
            if(count($exp) > 2 ){
                if(end($exp) != "null" ){
                    $profile_photo = $profile_photo;
                }else{
                    return "";
                }
            }else{
                return false;
            }

        }
        return $profile_photo;
    }

    public static function get_user_by_id($user_id){


        $file_name = Custom::encrypt_decrypt('encrypt',"user_$user_id");
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"user"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select * from users  where  id = $user_id limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","user");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }



    }


    public static function get_user_education($user_id){
        $query =    $query = "select education from users  where  id = $user_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return !empty($staff_data['education'])?$staff_data['education']:"";
        }else{
            return "";
        }
    }

    public static function get_folder_by_id($folder_id){
        $query =    $query = "select * from drive_folders  where  id = $folder_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_folder_shared_user_mobile_and_id($drive_folder_id){

        //$query =    $query = "select share_with_mobile, share_to_user_id, share_from_mobile from drive_shares  where  drive_folder_id = $drive_folder_id and shared_object = 'FOLDER' UNION ALL select mobile as share_with_mobile, user_id as share_to_user_id from drive_folders  where  id =$drive_folder_id";
        $query = "SELECT IF((SELECT id FROM appointment_staffs AS app_sta WHERE app_sta.user_id > 0 and app_sta.user_id = u.id AND app_sta.staff_type ='DOCTOR' AND app_sta.status='ACTIVE' LIMIT 1) IS NOT NULL,'YES','NO') AS is_doctor, ds.share_with_mobile, IFNULL(u.id, ds.share_to_user_id) as share_to_user_id, ds.share_from_mobile from drive_shares as ds left join users as u on ds.share_with_mobile = u.mobile and ds.thinapp_id = u.thinapp_id  where  drive_folder_id = $drive_folder_id and shared_object = 'FOLDER'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_file_shared_user_mobile_and_id($drive_file_id){

        $query =    $query = "select share_with_mobile, share_to_user_id from drive_shares  where  drive_file_id = $drive_file_id and shared_object = 'FILE' UNION ALL select mobile as share_with_mobile, user_id as share_to_user_id from drive_files  where  id =$drive_file_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_folder_file_shared_user_mobile_and_id($drive_folder_id,$drive_file_id){

        $query =    $query = "select share_with_mobile, share_to_user_id from drive_shares  where  drive_folder_id = $drive_folder_id and shared_object = 'FOLDER' UNION ALL select mobile as share_with_mobile, user_id as share_to_user_id from drive_folders  where  id =$drive_folder_id UNION ALL select share_with_mobile, share_to_user_id from drive_shares  where  drive_file_id = $drive_file_id and shared_object = 'FILE' UNION ALL select mobile as share_with_mobile, user_id as share_to_user_id from drive_files  where  id =$drive_file_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }



    public static function get_user_role_id($user_id){
        $query =    $query = "select role_id from users  where  id = $user_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['role_id'];
        }else{
            return 0;
        }
    }


    public static function get_thinapp_admin_data($thin_app_id){

        $file_name = Custom::encrypt_decrypt('encrypt',"app_user_$thin_app_id");
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"user"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select * from users  where  thinapp_id = $thin_app_id and role_id = 5 limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","user");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }

    }

    public static function get_thin_app_default_doctor_id($thin_app_id){

        $file_name = Custom::encrypt_decrypt('encrypt',"default_doctor_$thin_app_id");
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"doctor"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select app_sta.id from appointment_staffs as app_sta join users as u on u.role_id = 5 and u.mobile = app_sta.mobile and u.thinapp_id = app_sta.thinapp_id where app_sta.status = 'ACTIVE' AND app_sta.staff_type = 'DOCTOR' and app_sta.thinapp_id = $thin_app_id limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list)['id'];
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","doctor");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return 0;
        }


    }

    public static function get_thinapp_data($thin_app_id){

        $connection = ConnectionUtil::getConnection();
        $query = "select * from thinapps  where  id = $thin_app_id";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }

    }

    public static function get_app_name_and_token($thn_app_id){
        $query = "select u.firebase_token,t.name as app_name, u.mobile, u.username from users as u join thinapps as t on t.id = u.thinapp_id  where  u.thinapp_id = $thn_app_id and u.role_id = 5 limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }




    public static function use_default_admin_folder($thn_app_id,$app_admin_id, $mobile){
        $query = "select count(id) as cnt from drive_folders  where  user_id = $app_admin_id and thinapp_id = $thn_app_id and folder_add_from_number = '$mobile'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['cnt'];
        }else{
            return false;
        }
    }

    public static function hide_number($mobile){
        //$mobile = substr_replace($mobile,'*',4,1);
        //$mobile = substr_replace($mobile,'*',7,1);
        //return substr_replace($mobile,'*',10,1);
        //return substr($mobile, 0, 3) . '******' .substr($mobile, 9, 4);
        return $mobile;
    }

    public static function get_enum_values($table_name, $field )
    {
        $connection = ConnectionUtil::getConnection();
        $query =  "SHOW COLUMNS FROM {$table_name} WHERE Field = '{$field}'";
        $files = $connection->query($query);
        $fields = mysqli_fetch_assoc($files);
        preg_match("/^enum\(\'(.*)\'\)$/", $fields['Type'], $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    public static function get_user_by_mobile($thinapp_id,$mobile){


        $file_name = Custom::encrypt_decrypt('encrypt',"user_$mobile"."_$thinapp_id");
        $staff_data = json_decode(WebservicesFunction::readJson($file_name,"user"),true);
        if(empty($staff_data) || date('m',strtotime($staff_data['created'])) != date('m')){
            $connection = ConnectionUtil::getConnection();
            $query = "select * from users  where  mobile = '$mobile' and thinapp_id = $thinapp_id and ( role_id = 1 OR role_id =5 )  limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","user");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }

    }


    public static function delete_user_cache($mobile,$thinapp_id){
        $user = Custom::get_user_by_mobile($thinapp_id,$mobile);
        $file_name = Custom::encrypt_decrypt('encrypt',"user_$mobile"."_$thinapp_id");
        $file_name_2 = Custom::encrypt_decrypt('encrypt',"user_".$user['id']);
        WebservicesFunction::deleteJson(array($file_name,$file_name_2),"user");
    }


    public static function get_app_staff_username($thinapp_id,$mobile){
        $query =    $query = "select fullname from app_staffs  where  mobile = '$mobile' and thinapp_id = $thinapp_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function create_topic_name($channel_id=null)
    {
        return "channel_".$channel_id."_".date('YmdHis')."_".rand(1000,99999);
    }


    /*********ENCRYPTION STARTS**********/
    static $SALT ="COS463U_^erf_R";
    public static function encodeVariable($variable) {
        $hash = sha1(rand(0, 500) . microtime() . Custom::$SALT);
        $hash=substr($hash, 0, 4);
        $signature = sha1(Custom::$SALT . $variable . $hash);
        $signature=substr($signature, 0, 4);

        $encodedVariable = base64_encode($signature . "-" . $variable . "-" . $hash);
        return $encodedVariable;
    }

    public static function decodeVariable($variable) {
        $data = explode('-', base64_decode($variable));
        $signature=sha1(Custom::$SALT . $data[1] . $data[2]);
        $signature=substr($signature, 0, 4);
        if ($data[0] !== $signature) {
            return null;
        } else {
            return $data[1];
        }
    }

    public static function get_file_folder_owner_mobile($connection,$id,$shared_object){
        if ($shared_object == 'FOLDER') {
            $query = "SELECT u.mobile FROM drive_folders as df join users as u on u.id = df.user_id WHERE df.id = $id LIMIT 1";
        } else {
            $query = "SELECT u.mobile FROM drive_files as df join users as u on u.id = df.user_id WHERE df.id = $id LIMIT 1";
        }

        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['mobile'];
        }else{
            return false;
        }

    }


    public static function get_working_days(){
        $query = "select id, day_name from appointment_day_times where  status = 'ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = array_column(mysqli_fetch_assoc($service_message_list),'id','day_name');
            return $staff_data;
        }else{
            return false;
        }
    }



    public static function totalSubscriberForChannel($channel_id){

        $query = "select count(*) as cnt from subscribers where  status = 'SUBSCRIBED' AND channel_id = $channel_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['cnt'];
        }
        return 0;

    }

    public static function createOpitonArray($action_type_id,$question_id,$app_id,$choice_array=array()){

        $optionArray = array();
        if(($action_type_id==1 || $action_type_id==7 || $action_type_id==8 || $action_type_id==6 )&& !empty($choice_array)){
            /* this is 1 for ranking 7 for dropdown and 8 for multiple choice*/
            foreach ($choice_array as $key => $value){
                $optionArray[$key]["choices"] = trim($value);
                $optionArray[$key]["action_question_id"] = $question_id;
                $optionArray[$key]["thinapp_id"] = $app_id;
            }


        }else if($action_type_id==2){

            $optionArray[0]["choices"] = "Short Answer";
            $optionArray[0]["action_question_id"] = $question_id;
            $optionArray[0]["thinapp_id"] = $app_id;

        }else if($action_type_id==3){

            foreach ($choice_array as $key => $value){
                $optionArray[$key]["choices"] = trim($value);
                $optionArray[$key]["action_question_id"] = $question_id;
                $optionArray[$key]["thinapp_id"] = $app_id;
            }

            /*  $optionArray[0]["choices"] = "Yes";
              $optionArray[0]["action_question_id"] = $question_id;
              $optionArray[0]["thinapp_id"] = $app_id;
              $optionArray[1]["choices"] = "No";
              $optionArray[1]["action_question_id"] = $question_id;
              $optionArray[1]["thinapp_id"] = $app_id;*/


        }else if($action_type_id==4){

            /* this action type for rating */
            for($count=0;$count <5;$count++){
                $optionArray[$count]["choices"] = $count+1;
                $optionArray[$count]["action_question_id"] = $question_id;
                $optionArray[$count]["thinapp_id"] = $app_id;
            }

        }else if($action_type_id==5){
            /* this action type for scaling */
            for($count=0;$count <10;$count++){
                $optionArray[$count]["choices"] = $count+1;
                $optionArray[$count]["action_question_id"] = $question_id;
                $optionArray[$count]["thinapp_id"] = $app_id;
            }


        }else if($action_type_id==9){
            $optionArray[0]["choices"] = "Date";
            $optionArray[0]["action_question_id"] = $question_id;
            $optionArray[0]["thinapp_id"] = $app_id;

        }else if($action_type_id==10){
            $optionArray[0]["choices"] = "Long Answer";
            $optionArray[0]["action_question_id"] = $question_id;
            $optionArray[0]["thinapp_id"] = $app_id;
        }
        return $optionArray;

    }


    public static function timeElapsedString($ptime)
    {

        $ptime = strtotime($ptime);
        $etime = time() - $ptime;

        if ($etime < 1)
        {
            return '0 seconds';
        }

        $a = array( 365 * 24 * 60 * 60  =>  'year',
            30 * 24 * 60 * 60  =>  'month',
            24 * 60 * 60  =>  'day',
            60 * 60  =>  'hour',
            60  =>  'minute',
            1  =>  'second'
        );
        $a_plural = array( 'year'   => 'years',
            'month'  => 'months',
            'day'    => 'days',
            'hour'   => 'hours',
            'minute' => 'min',
            'second' => 'sec'
        );

        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
            }
        }
    }

    public static function get_folder_data($drive_folder_id=null){

        $query = "select df.*, u.mobile, u.username from drive_folders as df join users as u on u.id = df.user_id  where  df.id = $drive_folder_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return_arr =array();
        if ($service_message_list->num_rows) {
            $return_arr = mysqli_fetch_assoc($service_message_list);
            return $return_arr;
        }
        return $return_arr;
    }

    public static function get_file_data($drive_file_id=null){

        $query = "select df.* from drive_files as df where  df.id = $drive_file_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return_arr =array();
        if ($service_message_list->num_rows) {
            $return_arr = mysqli_fetch_assoc($service_message_list);
            return $return_arr;
        }
        return $return_arr;
    }

    public static function get_question_choice($question_id){
        $query = "select * from question_choices  where  action_question_id = $question_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return_arr =array();
        if ($service_message_list->num_rows) {
            $return_arr = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $return_arr;
        }
        return $return_arr;
    }

    public static function getFileType($file_name)
    {

        $file = explode(".",$file_name);
        $ext = strtoupper(strtolower(end($file)));
        $image_ext = array('PNG', 'JPEG', 'JPG', 'GIF', 'BMP','JPEG','JFIF','JPEG','Exif','TIFF','GIF','BMP','PNG','PPM','PGM','PBM','PNM','WEBP','HDR','HEIF','BAT','BPG','CGM','SVG');
        $audio_ext = array('3GP', 'WAV', 'MP3', 'M4P', 'PCM','WAV','AIFF','MP3','AAC','OGG','WMA','FLAC','ALAC','WMA');
        $video_ext = array('MP4', 'FLV', 'AVI', 'WMV','MPG','MPEG','M4V','3GP','WEBM','MKV','FLV','VOB','OGV','OGG','DRC','GIF','GIFV','MNG','AVI','MOV','QT','WMV','YUV','RM','RMVB','ASF','AMV','MP4','M4P','M4V','MPG','MP2','MPEG','MPE','MPV','MPG','MPEG','M2V','M4V','SVI','3GP','3G2','MXF','ROQ','NSV','FLV','F4V','F4P','F4A','F4B');
        $pdf_ext = array('PDF');
        $apk_ext = array('APK');
        $doc_ext = array('DOC','DOCX','TXT','TEXT','XLSX','XLS','XML');
        if (in_array(($ext), $image_ext)) {
            return "IMAGE";
        } else if (in_array($ext, $audio_ext)) {
            return "AUDIO";
        } else if (in_array($ext, $video_ext)) {
            return "VIDEO";
        } else if (in_array($ext, $pdf_ext)) {
            return "PDF";
        } else if (in_array($ext, $doc_ext)) {
            return "DOCUMENT";
        } else if (in_array($ext, $apk_ext)) {
            return "APK";
        } else {
            return "OTHER";
        }
    }



    public static function getMessageType($file_name)
    {
        $file = explode(".",$file_name);
        $ext = strtoupper(strtolower(end($file)));
        $doc_ext = array('PDF','DOC','DOCX','TXT','TEXT','XLSX','XLS');
        if (in_array($ext, $doc_ext)) {
            return $ext;
        } else {
            return "OTHER";
        }
    }




    public static function createChatReference($from,$to,$thin_app_id)
    {
        $number =array($from,$to);
        sort($number);
        return implode("_",$number)."_".$thin_app_id;

    }


    public static function checkMessageType($file_url,$message_type){
        if(!empty($file_url) && $message_type == "FILE" ){
            return Custom::getMessageType($file_url);
        }else{
            return $message_type;
        }
    }

    public static function deleteFileToAws($key_name) {

        try{
            $bucket = AWS_BUCKET;
            $option = unserialize(AWS);
            $s3 = new Aws\S3\S3Client($option);
            $result = $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key'    => $key_name
            ));
            if($result['@metadata']['statusCode']){
                return true;
            }else{
                return false;
            }
        }catch (Exception $e){
            return false;
        }

    }

    public static function deleteMultipleFileToAws($keys =array()) {
        //include_once LOCAL_PATH . "app/Vendor/aws/aws-autoloader.php";
        try {
            $objects = array();
            foreach ($keys as $key => $filename) {
                if(!empty($filename)){
                    $objects[$key]['Key'] = $filename;
                }
            }
            if (!empty($objects)) {
                $bucket = AWS_BUCKET;
                $option = unserialize(AWS);
                $s3 = new Aws\S3\S3Client($option);
                $response = $s3->deleteObjects(array(
                    'Bucket'  => $bucket,
                    'Delete'=>array(
                        'Objects' => $objects
                    )
                ));
                if ($response['@metadata']['statusCode']) {
                    return true;
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
            return false;
        }
    }





    public static function search_remove($search,$array){
        if(($key = array_search($search, $array)) !== false) {
            unset($array[$key]);
        }
        return array_unique($array);
    }

    public static function create_playstor_url($package_name=null,$thin_app_id = 0){
        if(!empty($package_name)){
            $url = "https://play.google.com/store/apps/details?id=".$package_name."&hl=en";
            $url = Custom::short_url($url,$thin_app_id);
            return $url;
        }else{
            $url = "https://play.google.com/store/apps/details?id=com.vision.mbroadcast&hl=en";
            $url = Custom::short_url($url);
            return $url;
        }
    }


    public static function get_service_list_for_category($category_id,$thin_app_id){

        $query = "select app_ser.* from appointment_category_services as acs join appointment_services as app_ser on acs.appointment_service_id = app_ser.id  where  acs.appointment_category_id = $category_id and acs.thinapp_id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_address_list_for_service($service_id,$thin_app_id){

        $query = "select app_add.* from appointment_service_addresses as asa join appointment_addresses as app_add on asa.appointment_address_id = app_add.id  where  asa.appointment_service_id = $service_id and asa.thinapp_id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_staff_address_list($staff_id,$thin_app_id){

        if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
            $query = "select aa.id as address_id, aa.place, aa.address, das.from_time, das.to_time from appointment_staffs AS staff join doctor_appointment_setting AS das ON das.doctor_id = staff.id  AND staff.appointment_setting_type = das.setting_type JOIN appointment_addresses AS aa ON aa.id = das.appointment_address_id  where aa.status ='ACTIVE' AND das.doctor_id = $staff_id and das.thinapp_id = $thin_app_id GROUP BY das.appointment_address_id";
        }else{
            $query = "select aa.id as address_id, aa.place, aa.address, asa.from_time, asa.to_time from appointment_addresses as aa join appointment_staff_addresses as asa on asa.appointment_address_id = aa.id and aa.status ='ACTIVE' where asa.appointment_staff_id = $staff_id and asa.thinapp_id = $thin_app_id ";
        }
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function checkDateInterval($last_interval,$current_interval){

        if(!empty($last_interval)){
            $constant_date = "2017-01-01";
            $last_date = strtotime(date('Y-m-d',strtotime("+".$last_interval)));
            $current_date = strtotime(date('Y-m-d',strtotime("+".$current_interval)));
            if($current_date > $last_date){
                return $current_interval;
            }else{
                return $last_interval;
            }
        }else{
            return $current_interval;
        }

    }




    public static function get_doctor_address_list_drp($staff_id,$thin_app_id){

        $query = "select aa.id as id , aa.address, asa.from_time, asa.to_time from appointment_addresses as aa join appointment_staff_addresses as asa on asa.appointment_address_id = aa.id where asa.appointment_staff_id = $staff_id and asa.thinapp_id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_doctor_address_by_id($address_id,$staff_id,$thin_app_id){
        $query = "select aa.id as id , aa.address, asa.from_time, asa.to_time from appointment_addresses as aa join appointment_staff_addresses as asa on asa.appointment_address_id = aa.id where asa.appointment_staff_id = $staff_id and asa.appointment_address_id = $address_id AND aa.status = 'ACTIVE' and asa.thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_doctor_service_data($doctor_id)
    {
        $file_name = "doctor_service_data_$doctor_id";
        if(!$data = json_decode(WebservicesFunction::readJson($file_name,"doctor"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select t.pay_clinic_visit_fee_online, app_sta.emergency_appointment_fee, t.booking_convenience_fee_emergency, t.category_name, t.version_name, app_sta.is_audio_consulting, app_sta.is_chat_consulting, app_sta.is_online_consulting, app_sta.is_offline_consulting, t.booking_convenience_fee, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat, app_sta.payment_mode,  app_ser.*, t.show_expire_token_slot from appointment_staff_services as ass join thinapps as t on t.id = ass.thinapp_id join appointment_services as app_ser on app_ser.id = ass.appointment_service_id  join appointment_staffs as app_sta on app_sta.id = ass.appointment_staff_id where  ass.appointment_staff_id = $doctor_id limit 1";
            $doctor_data = $connection->query($query);
            if ($doctor_data->num_rows) {
                $data = mysqli_fetch_assoc($doctor_data);
                WebservicesFunction::createJson($file_name,json_encode($data),"CREATE","doctor");
            }
        }
        if (!empty($data)) {
            return $data;
        }else{
            return false;
        }
    }

	public static function get_doctor_default_data($doctor_id)
    {
        $connection = ConnectionUtil::getConnection();
        $query = "select ser.service_slot_duration, asa.appointment_address_id as address_id, ass.appointment_service_id as service_id from appointment_staffs as staff join appointment_staff_addresses as asa on asa.appointment_staff_id = staff.id join appointment_staff_services as ass on ass.appointment_staff_id = staff.id join appointment_services as ser on ser.id = ass.appointment_service_id where staff.id = $doctor_id limit 1";
        $doctor_data = $connection->query($query);
        if ($doctor_data->num_rows) {
            return mysqli_fetch_assoc($doctor_data);
        }
        return false;
    }

    public static function get_doctor_default_service_new_appointment($doctor_id)
    {
        $query = "select das.id, das.setting_type as appointment_setting_type, app_ser.*, t.show_expire_token_slot from doctor_appointment_setting as das join thinapps as t on t.id = das.thinapp_id join appointment_services as app_ser on app_ser.id = das.appointment_service_id join appointment_staffs as app_staff on app_staff.id = das.doctor_id and app_staff.appointment_setting_type = das.setting_type where das.doctor_id = $doctor_id and das.index_number = 1 group by app_ser.id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }


    public static function get_staff_list_for_service($service_id, $address_id, $thin_app_id){

        $query = "select app_sta.* from appointment_staffs as app_sta join appointment_staff_services as ass on app_sta.id = ass.appointment_staff_id join appointment_staff_addresses as asa on asa.appointment_staff_id = app_sta.id where  ass.appointment_service_id = $service_id and asa.appointment_address_id= $address_id and  app_sta.thinapp_id =$thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_search_customer_list($mobile, $thin_app_id){
        if($mobile!="+919999999999"){
            $query = "select * from ( select id, uhid, third_party_uhid, address, REPLACE(`mobile`,'+91','') AS mobile, user_id, CONCAT_WS(' ', first_name, last_name) AS name, profile_photo,'' as child_id,  (SELECT acss.appointment_datetime FROM appointment_customer_staff_services AS acss WHERE acss.appointment_customer_id = appointment_customers.id AND ( acss.status IN('CLOSED') OR acss.payment_status = 'SUCCESS') ORDER BY acss.appointment_datetime DESC LIMIT 1 ) AS last_visit from appointment_customers where mobile != '+919999999999' and mobile = '$mobile' and thinapp_id = $thin_app_id  and status = 'ACTIVE' UNION select id, uhid, '' as third_party_uhid, IF(address!='',address,patient_address) AS address, REPLACE(`mobile`,'+91','') AS mobile, user_id, child_name AS name, image as profile_photo, child_number as child_id,  (SELECT acss.appointment_datetime FROM appointment_customer_staff_services AS acss WHERE acss.children_id = childrens.id AND ( acss.status IN('CLOSED') OR acss.payment_status = 'SUCCESS') ORDER BY acss.appointment_datetime DESC LIMIT 1 ) AS last_visit from childrens where mobile != '+919999999999'  and (mobile = '$mobile' OR parents_mobile = '$mobile' ) and thinapp_id = $thin_app_id and status = 'ACTIVE' ) as final order by final.name asc LIMIT 15";
            //$query = "select  ac.id, IFNULL(ac.uhid,c.uhid) as uhid, ifnull(ac.third_party_uhid,c.third_party_uhid) as third_party_uhid, IFNULL(ac.address,c.address) as address, RIGHT(IFNULL(ac.mobile,c.mobile),10) AS mobile, IFNULL(ac.user_id,c.user_id) as user_id, IFNULL(ac.first_name,c.child_name) AS name, '' as profile_photo, c.id as child_id, acss.appointment_datetime as last_visit FROM appointment_customer_staff_services AS acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id and ac.status = 'ACTIVE'  left join childrens as c on c.id = acss.children_id and c.status = 'ACTIVE'  WHERE (acss.status != 'CLOSED'  OR acss.payment_status = 'SUCCESS') and (ac.mobile = '$mobile' OR c.mobile = '$mobile')  and acss.thinapp_id = $thin_app_id  ORDER BY acss.appointment_datetime DESC LIMIT 10";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                return $staff_data;
            }
        }
        return false;

    }

    public static function get_search_customer_list_uhid($uhid, $thin_app_id){

        $query = "select id, uhid, third_party_uhid, address, REPLACE(`mobile`,'+91','') AS mobile, user_id, CONCAT_WS(' ', first_name, last_name) AS name, profile_photo,'' as child_id, (SELECT acss.appointment_datetime FROM appointment_customer_staff_services AS acss WHERE acss.appointment_customer_id = appointment_customers.id AND ( acss.status IN('CLOSED') OR acss.payment_status = 'SUCCESS') ORDER BY acss.appointment_datetime DESC LIMIT 1 ) AS last_visit  from appointment_customers where ( uhid = '$uhid' OR third_party_uhid = '$uhid' )  and thinapp_id = $thin_app_id and status = 'ACTIVE' UNION select id, uhid, '' as third_party_uhid, IF(address!='',address,patient_address) AS address, REPLACE(`mobile`,'+91','') AS mobile, user_id, child_name AS name, image as profile_photo, child_number as child_id, (SELECT acss.appointment_datetime FROM appointment_customer_staff_services AS acss WHERE acss.children_id = childrens.id AND ( acss.status IN('CLOSED') OR acss.payment_status = 'SUCCESS') ORDER BY acss.appointment_datetime DESC LIMIT 1 ) AS last_visit  from childrens where uhid = '$uhid' and thinapp_id = $thin_app_id and status = 'ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_search_customer_list_name($name, $thin_app_id){
        $query = "SELECT * FROM ( select id, uhid, third_party_uhid, address, REPLACE(`mobile`,'+91','') AS mobile, user_id, CONCAT_WS(' ', first_name, last_name) AS name, profile_photo,'' as child_id, (SELECT acss.appointment_datetime FROM appointment_customer_staff_services AS acss WHERE acss.appointment_customer_id = appointment_customers.id AND ( acss.status IN('CLOSED') OR acss.payment_status = 'SUCCESS') ORDER BY acss.appointment_datetime DESC LIMIT 1 ) AS last_visit  from appointment_customers where first_name like '%$name%' and thinapp_id = $thin_app_id and status = 'ACTIVE' UNION select id, uhid, '' as third_party_uhid, IF(address!='',address,patient_address) AS address, REPLACE(`mobile`,'+91','') AS mobile, user_id, child_name AS name, image as profile_photo, child_number as child_id, (SELECT acss.appointment_datetime FROM appointment_customer_staff_services AS acss WHERE acss.children_id = childrens.id AND ( acss.status IN('CLOSED') OR acss.payment_status = 'SUCCESS') ORDER BY acss.appointment_datetime DESC LIMIT 1 ) AS last_visit  from childrens where child_name like '%$name%' and thinapp_id = $thin_app_id and status = 'ACTIVE' ) AS final ORDER BY final.name asc LIMIT 50";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }



    public static function is_customer_exist($mobile, $thin_app_id, $first_name){

        $query = "select id from appointment_customers where mobile = '$mobile' and thinapp_id = $thin_app_id and first_name = '$first_name' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return true;
        }else{
            return false;
        }
    }

    public static function get_customer_data($customer_id,$thin_app_id){
        $query = "select * from appointment_customers where id = $customer_id and thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }


    public static function get_customer_list_by_mobile($thinapp_id,$mobile,$role_id,$remove_country_code = false, $limit =''){

        $appointment_role = "ADMIN";
        if($role_id != 5 ){
            $appointment_role = Custom::hospital_get_user_role($mobile,$thinapp_id,$role_id);
        }
        if(!empty($limit)){
            $limit = " LIMIT $limit";
        }else{
            $limit = "";
        }
        $select_mobile = ($remove_country_code===true)?'RIGHT(ac.mobile,10)':'ac.mobile';
        $select_mobile .= ' as mobile';
        if($appointment_role == "USER"){
            $query = "select ac.address, 'CUSTOMER' as user_type, ac.id as customer_id, ac.profile_photo, $select_mobile, ac.first_name as name ,ac.gender, '' as patient_id from appointment_customers as ac join  appointment_customer_staff_services  as acss  on acss.appointment_customer_id = ac.id  where ac.thinapp_id = $thinapp_id and ac.mobile ='$mobile' group by ac.id UNION ALL select  '' as address, 'CHILDREN' as user_type, c.id as customer_id, c.image as profile_photo, '$mobile' as mobile ,c.child_name as name ,c.gender, c.child_number as patient_id from childrens as c  where c.thinapp_id = $thinapp_id and (c.mobile ='$mobile' OR c.parents_mobile = '$mobile') and c.status ='ACTIVE' ";
        }else{
            $query = "select 'CUSTOMER' as user_type, ac.id as customer_id, ac.profile_photo, ac.address, RIGHT(ac.mobile,10) as mobile, ac.first_name as name ,ac.gender, '' as patient_id from appointment_customers as ac left join  appointment_customer_staff_services  as acss  on acss.appointment_customer_id = ac.id and acss.booked_by = 'ADMIN'  where ac.thinapp_id = $thinapp_id group by ac.id  UNION ALL select   'CHILDREN' as user_type, c.id as customer_id, c.image as profile_photo, '' as address, RIGHT(c.mobile,10)  as mobile ,c.child_name as name ,c.gender, c.child_number as patient_id from childrens as c  left join  appointment_customer_staff_services  as acss  on acss.children_id = c.id and acss.booked_by = 'ADMIN' where c.thinapp_id = $thinapp_id  and c.status ='ACTIVE' order by customer_id desc";
        }

        //$query = "select * from ( $query ) as row order by row.customer_id desc $limit";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }else{
            return array();
        }

    }



    public static function search_customer_name($thinapp_id,$mobile,$first_name){
        $first_name = strtoupper(trim($first_name));
        $query = "select ac.*, df.id as folder_id from appointment_customers as ac left join drive_folders as df on df.appointment_customer_id = ac.id where ac.mobile = '$mobile' and ac.thinapp_id = $thinapp_id and UPPER(ac.first_name) = '$first_name' and ac.status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function search_child_name($thin_app_id,$mobile,$child_name){
        $child_name = strtoupper(trim($child_name));
        $query = "select c.*, u.id as patient_id, df.id as folder_id from childrens as c left join users as u on c.mobile = u.mobile and c.thinapp_id = u.thinapp_id left join drive_folders as df on df.children_id = c.id where c.thinapp_id = $thin_app_id and  c.status='ACTIVE' AND c.mobile = '$mobile' and UPPER(c.child_name) = '$child_name' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    /*public static function search_customer_name_junk($thinapp_id,$mobile,$first_name){
        $first_name = trim($first_name);
        $query = "select * from appointment_customers where mobile = '$mobile' and thinapp_id = $thinapp_id and (first_name = '$first_name' OR first_name LIKE '".$first_name."_%') and status = 'ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        return $service_message_list->num_rows;
    }*/


    public static function is_appointment_address_exist($thin_app_id, $palace, $address,$address_id=0,$country_id=0,$state_id=0,$city_id=0){
        if(!empty($address) && !empty($palace)){
            $condition = "";
            if(!empty($country_id)){
                $condition .= " and country_id = $country_id ";
            }
            if(!empty($state_id)){
                $condition .= " and state_id = $state_id ";
            }
            if(!empty($city_id)){
                $condition .= " and city_id = $city_id ";
            }
            if(!empty($address_id)){
                $condition .= " and id != $address_id ";
            }

            $palace = trim($palace);
            $address = trim($address);
            $query = "select id from appointment_addresses  where  thinapp_id = $thin_app_id AND status = 'ACTIVE' and place = '$palace' and address = '$address' $condition limit 1";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                return true;
            }else{
                return false;
            }
        }
        return true;
    }



    public static function get_default_channel_count($thin_app_id){
        //$query = "select ( SELECT count(s.id) FROM subscribers as s WHERE s.channel_id = c.id and s.status = 'SUBSCRIBED' ) AS subscriber_count from channels as c  where  c.app_id = $thin_app_id and  c.channel_status = 'DEFAULT' limit 1";
        $query = "SELECT count(s.id) as subscriber_count FROM subscribers as s join channels as c on s.channel_id = c.id WHERE  s.status = 'SUBSCRIBED' and c.channel_status = 'DEFAULT' and c.app_id = $thin_app_id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['subscriber_count'];
        }else{
            return 0;
        }
    }


    public static function get_appointment_by_id($appointment_id, $thin_app_id = null)
    {
        $condition = "";
        if(!empty($thin_app_id)){
            $condition = " and thinapp_id = $thin_app_id";
        }
        $query = $query = "select * from appointment_customer_staff_services  where  id = $appointment_id $condition";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }



    public static function get_child_by_id($child_id,$appointment_id=0){
        $query = "select IFNULL(cg.id,0) as child_growth_id, c.*, cg.height, cg.weight, cg.head_circumference, u.id as patient_id, df.id as folder_id, acss.referred_by, acss.referred_by_mobile, acss.reason_of_appointment, acss.notes from childrens as c left join users as u on c.mobile = u.mobile and c.thinapp_id = u.thinapp_id left join drive_folders as df on df.children_id = c.id LEFT JOIN appointment_customer_staff_services AS acss on acss.id = $appointment_id AND acss.children_id = c.id LEFT JOIN child_growths AS cg ON cg.children_id = c.id AND cg.id = (SELECT MAX(id) FROM child_growths AS c_growth WHERE c_growth.children_id = c.id AND DATE(created) = DATE(NOW()) ) where  c.status='ACTIVE' AND c.id = $child_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_child_folder_data($child_id, $thin_app_id){
        $query =    $query = "select df.* from drive_folders as df left join childrens as c on c.child_number = df.child_number and c.thinapp_id = df.thinapp_id  AND c.id = $child_id and c.thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_department_by_id($department_id,$thin_app_id){
        $query = "select * from appointment_categories  where  status='ACTIVE' AND id = $department_id and thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_doctor_by_id($doctor_id,$thin_app_id=0){

        $file_name = Custom::encrypt_decrypt('encrypt',"doctor_".$doctor_id);
        if(!$data = json_decode(Custom::encrypt_decrypt('decrypt',WebservicesFunction::readJson($file_name,"doctor")),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select * from appointment_staffs  where id = $doctor_id limit 1";
            $doctor_data = $connection->query($query);
            if ($doctor_data->num_rows) {
                $data = mysqli_fetch_assoc($doctor_data);
                $data_string =json_encode($data);
                $data_string = Custom::encrypt_decrypt('encrypt',$data_string);
                WebservicesFunction::createJson($file_name,$data_string,"CREATE","doctor");
            }
        }
        if (!empty($data)) {
            return $data;
        }else{
            return false;
        }

    }


    public static function get_address_by_id($address_id,$thin_app_id){
        $query = "select aa.*, IFNULL(c.name,'') AS country_name, IFNULL(s.name,'') AS state_name, IFNULL(city.name, '') AS city_name  from appointment_addresses as aa LEFT JOIN countries AS c ON c.id = aa.country_id LEFT JOIN states AS s ON s.id =aa.state_id LEFT JOIN cities AS city ON city.id =aa.city_id where  aa.status='ACTIVE' AND aa.id = $address_id and aa.thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_child_and_growth_by_id($child_id){
        $query =    $query = "select c.*,cg.id as child_growth_id, cg.month, cg.height,cg.weight,cg.head_circumference, u.id as patient_id from childrens as c left join child_growths as cg on c.id = cg.children_id and cg.month = 0 left join users as u on c.mobile = u.mobile and c.thinapp_id = u.thinapp_id where  c.status='ACTIVE' and c.id = $child_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function updat_folder_modified_date($connection, $drive_folder_id){
        $sql = "UPDATE  drive_folders SET  modified = ? where id = ?";
        $stmt_df = $connection->prepare($sql);
        $created = Custom::created();
        $stmt_df->bind_param('ss', $created, $drive_folder_id);
        if ($stmt_df->execute()){
            return true;
        }else{
            return false;
        }
    }

    public static function updateFileCount($connection, $drive_folder_id,$count,$type="ADD"){

        if(strtoupper($type)=="ADD"){
            $sql = "UPDATE  drive_folders SET  total_file_count = total_file_count + ? where id = ?";
        }else{
            $sql = "UPDATE  drive_folders SET  total_file_count = total_file_count - ? where id = ?";
        }
        $stmt_df = $connection->prepare($sql);
        $stmt_df->bind_param('ss', $count, $drive_folder_id);
        if ($stmt_df->execute()){
            return true;
        }else{
            return false;
        }
    }


    public static function updateMemoCount($connection, $drive_folder_id,$count,$type="ADD"){

        if(strtoupper($type)=="ADD"){
            $sql = "UPDATE  drive_folders SET  total_memo_count = total_memo_count + ? where id = ?";
        }else{
            $sql = "UPDATE  drive_folders SET  total_memo_count = total_memo_count - ? where id = ?";
        }
        $stmt_df = $connection->prepare($sql);
        $stmt_df->bind_param('ss', $count, $drive_folder_id);
        if ($stmt_df->execute()){
            return true;
        }else{
            return false;
        }
    }



    public static function get_child_growth_data($date,$child_id){
        $date = date('Y-m-d',strtotime(($date)));
        $query = "select * from child_growths  where  children_id = $child_id and DATE(date) = '$date' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_month_diffrence($date_from,$date_to){
        $date1 = date('Y-m-d',strtotime($date_from));
        $date2 = date('Y-m-d',strtotime($date_to));
        $d1 = $date1;
        $d2 = $date2;
        $y1 = date('Y',strtotime($d1));
        $y2 = date('Y',strtotime($d2));
        $m1 = date('m',strtotime($d1));
        $m2 = date('m',strtotime($d2));
        $day1 = date('d',strtotime($d1));
        $day2 = date('d',strtotime($d2));
        $yearDiff = $y2 - $y1;
        $monthDiff = $m2 - $m1;
        if($m2 > $m1)
        {
            $month = $m2 - $m1;
        }
        else
        {
            $month = 0;
        }

        if($yearDiff > 0 && $m1 > $m2)
        {
            $yearMonth = (($yearDiff*12) - ($m1 - $m2));
        }
        else
        {
            $yearMonth = $yearDiff*12;
        }

        if($day1 > $day2)
        {
            $month = ($month - 1);
        }
        return $yearMonth + $month;

        /*$begin = new DateTime( $date1 );
        $end = new DateTime( $date2 );
        return $begin->diff($end)->m;*/




    }

    public static function get_total_months($dob,$compare_date){

        if (!$compare_date) $compare_date = date('Y-m-d');
        $dob = explode('-', date('Y-m-d',strtotime($dob)));
        $compare_date = explode('-', $compare_date);
        $mnt = array(1 => 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        if (($compare_date[2]%400 == 0) or ($compare_date[2]%4==0 and $compare_date[2]%100!=0)) $mnt[2]=29;
        if($compare_date[0] < $dob[0]){
            $compare_date[0] += $mnt[$compare_date[1]-1];
            $compare_date[1]--;
        }
        if($compare_date[1] < $dob[1]){
            $compare_date[1] += 12;
            $compare_date[2]--;
        }
        return ($compare_date[1] - $dob[1]) ;
    }

    public static function get_child_with_vaccination($child_id,$vaccination_id){
        $query = "select alt_u.mobile as alt_mobile, alt_u.firebase_token as alt_firebase_token, u.firebase_token, cv.vac_done_date, cv.reschedule_date,cv.vac_date, u.id as patient_id, u.username as patient_name, c.child_name,c.user_id,c.mobile, amv.vac_name, amv.vac_dose_name from childrens as c join users as u on u.thinapp_id =c.thinapp_id and c.mobile=u.mobile left join users as alt_u on alt_u.thinapp_id =c.thinapp_id and c.parents_mobile=alt_u.mobile join child_vaccinations as cv on c.id = cv.children_id join app_master_vaccinations as amv on cv.app_master_vaccination_id = amv.id where  c.id = $child_id and cv.id = $vaccination_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_child_vaccination_by_id($vac_id){
        $query = "select cv.*, amv.vac_dose_name, amv.vac_name, amv.full_name from  child_vaccinations as cv join app_master_vaccinations as amv on amv.id = cv.app_master_vaccination_id  where  cv.id = $vac_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }



    public static function get_child_vaccination_by_id_array($vac_id_array){
        if(!empty($vac_id_array)){
            $limit  = count($vac_id_array);
            $id_string = implode(",", $vac_id_array);
            $query = "select cv.*, amv.vac_dose_name, amv.vac_name, amv.full_name from  child_vaccinations as cv join app_master_vaccinations as amv on amv.id = cv.app_master_vaccination_id  where  cv.id IN ($id_string) limit $limit";
            $connection = ConnectionUtil::getConnection();
            $return_data =array();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                foreach ($staff_data as $key => $data){
                    $return_data[$data["id"]] = $data;
                }
                return $return_data;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }

    public static function create_child_by_name($thin_app_id,$mobile,$name){
        $name = trim($name);
        $query = "select c.*, df.id as folder_id from childrens as c left join drive_folders as df on df.children_id = c.id where c.thinapp_id =$thin_app_id and c.mobile='$mobile' and c.child_name = '$name' and c.status ='ACTIVE' order by c.id desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function create_child_id($thin_app_id){

        /***** PLEASE DONT USE CHILD STATUS ACTIVE INOT WHERE CLOUS FOR THIS METHOD ******/
        $query = "select count(c.id) as cnt from childrens as c where c.thinapp_id =$thin_app_id order by id desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return "DR".($staff_data['cnt']+1000);
        }else{
            return "";
        }
    }


    public static function is_duplicate_vaccin_data($thin_app_id,$vac_name,$full_name, $vac_dose_name,$vac_time,$vac_type){

        $vac_name = trim($vac_name);
        $full_name = trim($full_name);
        $vac_dose_name = trim($vac_dose_name);
        $query = "select * from app_master_vaccinations where thinapp_id = $thin_app_id and vac_name='$vac_name' and full_name ='$full_name' and vac_dose_name = '$vac_dose_name' and vac_type ='$vac_type' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);

        }else{
            return 0;
        }

    }



    public static function send_health_and_emergency_notification($option,$thin_app_id,$send_notification_to = "ALL"){

        $condition ="";
        $return = array();
        if($thin_app_id == MBROADCAST_APP_ID){
            if($send_notification_to == "ADMIN"){
                $condition .= "  u.role_id = 5 ";
            }else if($send_notification_to == "USER"){
                $condition .= "  u.role_id = 1 ";
            }else{
                $condition .= " (u.role_id = 1 OR u.role_id = 5) ";
            }
            $query = "select  t.logo, t.name as app_name,  u.firebase_token, u.web_app_token, t.id as app_id from users as u join thinapps as t on  u.thinapp_id = t.id  and u.firebase_token IS NOT NULL and u.app_installed_status = 'INSTALLED' and t.category_name IN('DOCTOR','HOSPITAL') join app_enable_functionalities as aef on aef.thinapp_id = t.id join app_functionality_types as aft on aft.id = aef.app_functionality_type_id and aft.label_key = 'HEALTH_TIP' JOIN user_enabled_fun_permissions as uefp on uefp.app_functionality_type_id = aft.id and uefp.thinapp_id = aef.thinapp_id and uefp.permission = 'YES' where $condition and t.id = '788' order by t.id asc";

            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $all_token_array =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                $logo =$web_app_array= $final_array= $single_array= array();


                foreach ($all_token_array as $key => $token_data){
                    $final_array[$token_data['app_id']][] = $token_data['firebase_token'];
                    if(!empty($token_data['web_app_token'])){
                        $web_app_array[$token_data['app_id']]['token'][] = $token_data['web_app_token'];
                    }
                    if(empty($web_app_array[$token_data['app_id']]['logo'])){
                        $web_app_array[$token_data['app_id']]['logo'] =$token_data['logo'];
                        $web_app_array[$token_data['app_id']]['app_name'] =$token_data['app_name'];

                    }
                }

                $url = SITE_PATH.'/app_admin/view_cms_doc_dashboard/'.base64_encode($option['id']);
                foreach($web_app_array as $app_id =>$token_data){
                    $token_array = $token_data['token'];
                    foreach ($token_array as $key_1 => $token){
                        $option_arr = array(
                            'title' =>$token_data['app_name'],
                            'body' => $option['message'],
                            'logo'=>$token_data['logo'],
                            'url'=>$url,
                            'actions'=>array(
                                array(
                                    'action'=>$url,
                                    'title'=>'View Health Tip',
                                    'icon'=>'https://s3-ap-south-1.amazonaws.com/mengage/202106181921501388579434.png'
                                )
                            )
                        );
                        $return_array = Custom::send_web_app_notification_via_token($option_arr,$token);
                    }

                }

                foreach ($final_array as $key_thin_app_id =>$token_array){
                    if($key_thin_app_id == MENGAGE_APP_ID){
                        $mengage_option = $option;
                        $mengage_option['flag'] = "MENGAGE_".$option['flag'];
                        $mengage_option['module_type'] = "MENGAGE_".$option['module_type'];
                        $mengage_option['thinapp_id'] = MENGAGE_APP_ID;
                        $mengage_option['doctors_thin_app_id'] = $key_thin_app_id;
                        $return[] = Custom::send_notification_via_token($mengage_option,$token_array,$key_thin_app_id,true);
                    }else{

                        $option['thinapp_id'] = $key_thin_app_id;
                        $return[] = Custom::send_notification_via_token($option,$token_array,$key_thin_app_id);
                    }

                }




            }

            return $return;

        }else{

            $follower_token = Custom::get_thin_app_follower_user_token($thin_app_id);
            if (!empty($follower_token)) {
                $all_tokens = $follower_token;
            }
            $active_tokens = Custom::get_app_active_token($thin_app_id);
            if (!empty($active_tokens)) {
                $all_tokens = array_unique(array_merge($all_tokens,$active_tokens));
            }
            if(!empty($all_tokens)){
                Custom::send_notification_via_token($option, $all_tokens, $thin_app_id, false);
            }


        }
    }






    public static function update_reschedule_vac_date($connection,$thin_app_id,$child_id,$vac_data,$update_date,$vac_id , $total_days, $action_type){

        $full_name = $vac_data['full_name'];
        $vac_name = $vac_data['vac_name'];
        $dose_condition = "NOT_REMOVE_THIS_TEXT";
        $dose_arr = explode(" ", $vac_data['vac_dose_name']);
        $dose_condition = (count($dose_arr) == 2)?$dose_arr[0]:$dose_condition;

        $query = "select id,IFNULL(reschedule_date, vac_date) as vac_date from child_vaccinations where app_master_vaccination_id IN (select id from app_master_vaccinations where vac_name ='$vac_name' and full_name='$full_name' and vac_dose_name like '%$dose_condition%' and thinapp_id =$thin_app_id) and children_id =$child_id and status ='PENDING' and id > $vac_id";
        $subscriber = $connection->query($query);
        $total_result =array();
        if ($subscriber->num_rows) {
            $child_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
            foreach ($child_list as $key => $value){
                $vac_date =$value['vac_date'];
                $format ="Y-m-d";
                $days = ($total_days == 0 || $total_days == 1)?$total_days." day":$total_days." days";
                if($action_type=="ADD"){
                    $update_new_date =  date($format,strtotime($vac_date." + ".$days));
                }else{
                    $update_new_date =  date($format,strtotime($vac_date." - ".$days));
                }
                $created = Custom::created();
                $query = "update child_vaccinations set reschedule_date =?, modified =? where id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sss', $update_new_date, $created, $value['id']);
                if ($stmt->execute()) {
                    $total_result[] =  true;
                }else{
                    $total_result[] =  false;
                }
            }
        }
        if(!in_array(false,$total_result)){
            return true;
        }else{
            return false;
        }
    }


    public static function get_last_done_vaccination($connection,$thin_app_id,$child_id,$full_name, $vac_name){

        $query = "select IF(cv.vac_image IS NULL ,0,1)+ IF(cv.vac_image_2 IS NULL ,0,1) +IF(cv.vac_image_3 IS NULL ,0,1) as vac_image_count, cv.id, DATE_FORMAT(cv.vac_done_date,'%d-%m-%Y') as given_date, DATE_FORMAT(IFNULL(reschedule_date, vac_date),'%d-%m-%Y') as vac_date, cv.remark,cv.vac_image, cv.vac_image_2, cv.vac_image_3, if(amv.category_type='CHILD VACCINATION',amv.vac_dose_name,cv.adolescent_dose_name) as vac_dose , cv.status from child_vaccinations as cv join app_master_vaccinations as amv on cv.app_master_vaccination_id = amv.id where app_master_vaccination_id IN (select id from app_master_vaccinations where vac_name ='$vac_name' and full_name='$full_name' and thinapp_id =$thin_app_id) and cv.children_id =$child_id";
        $subscriber = $connection->query($query);
        $child_list =array();
        if ($subscriber->num_rows) {
            $child_list_1 = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
            foreach($child_list_1 as $key => $value){
                $child_list[$key] = $value;
                $child_list[$key]['vac_name'] = $vac_name;
            }
            return $child_list;
        }else{
            return $child_list;
        }


    }

    public static function getCountryList($web_service=false){


        $query = "select id,name from countries";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $list_array =array();
        if ($service_message_list->num_rows) {
            $list_array =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            if($web_service===false){
                $list_array = array_column($list_array,'name','id');
            }
            return $list_array;
        }else{
            return $list_array;
        }
    }



    public static function get_related_vaccination_ids($thin_app_id,$vac_data,$child_id){
        $full_name = $vac_data['full_name'];
        $vac_name = $vac_data['vac_name'];
        $dose_condition = "NOT_REMOVE_THIS_TEXT";
        $dose_arr = explode(" ", $vac_data['vac_dose_name']);
        $dose_condition = (count($dose_arr) == 2)?$dose_arr[0]:$dose_condition;
        $dose_condition = "'%".$dose_condition."%'";
        $query = "select cv.id from child_vaccinations as cv join  app_master_vaccinations as amv on cv.app_master_vaccination_id = amv.id where amv.vac_name ='$vac_name' and amv.full_name = '$full_name' and amv.vac_dose_name like $dose_condition and amv.thinapp_id = $thin_app_id and cv.children_id = $child_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $list_array =array();
        if ($service_message_list->num_rows) {
            $list_array =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $list_array = array_column($list_array,'id');
            return $list_array;
        }else{
            return $list_array;
        }
    }




    public static function getStateList($country_id,$web_service=false){
        $query = "select id,name from states where country_id=".$country_id;
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $list_array =array();
        if ($service_message_list->num_rows) {
            $list_array =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            if($web_service===false){
                $list_array = array_column($list_array,'name','id');
            }
            return $list_array;
        }else{
            return $list_array;
        }
    }

    public static function getCityList($state_id,$web_service=false){



        $query = "select id,name from cities where state_id=".$state_id;
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $list_array =array();
        if ($service_message_list->num_rows) {
            $list_array =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            if($web_service===false){
                $list_array = array_column($list_array,'name','id');
            }
            return $list_array;
        }else{
            return $list_array;
        }
    }

    public static function getDepartmentListByCategory($category_name,$webservice=false){
        $query = "select id,category_name from department_categories where department_name='$category_name' order by category_name asc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $list_array =array();
        if ($service_message_list->num_rows) {
            $list_array = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            if($webservice===false){
                return array_column($list_array,'category_name','id');
            }else{
                $list_arr[0]["id"] = "0";
                $list_arr[0]["category_name"] = "Select Category";
                return array_merge($list_arr,$list_array);
            }

        }else{
            return $list_array;
        }
    }

    public static function get_day_difference($date1,$date2,$format){

        $date1=date_create($date1);
        $date2=date_create($date2);

        $diff=date_diff($date1,$date2);
        return $diff->days;
    }

    public static function get_date_label($date){

        $current  = strtotime(date('Y-m-d'));
        $yesterday  = strtotime(date('Y-m-d',strtotime("-1 day")));
        $tomorrow  = strtotime(date('Y-m-d',strtotime("+1 day")));
        $old_date =date('Y-m-d',strtotime($date));
        $date  = strtotime($old_date);
        $day_label  = date('l',$date);


        if($date==$current){
            $day_label = "Today";
        }else if($date== $tomorrow){
            $day_label = "Tomorrow";
        }else if($date == $yesterday){
            $day_label = "Yesterday";
        }
        return $day_label ;
    }


    public static function getDatePosition($compare_date){
        $current_Date = date('Y-m-d');
        $current_month = date('m');
        $current_year = date('Y');
        $compare_date = date('Y-m-d',strtotime($compare_date));
        $compare_month = date('m',strtotime($compare_date));
        $compare_year = date('Y',strtotime($compare_date));
        $FirstDay = date("Y-m-d", strtotime('sunday last week'));
        $LastDay = date("Y-m-d", strtotime('sunday this week'));
        if($current_Date ==$compare_date) {
            return "TODAY";
        }else if($compare_date > $FirstDay && $compare_date < $LastDay) {
            return "WEEK";
        }else if($current_year == $compare_year  && $compare_month ==$current_month){
            return "MONTH";
        }else {
            return "";
        }
    }

    public static function dob_elapsed_string($dob,$now=false,$string_format=true)
    {


        $tot_year =$tot_month=$tot_day =0;
        if (!$now){
            $now = date('d-m-Y');
        }else{
            $now = date('d-m-Y',strtotime($now));
        }
        $dob = explode('-', date('d-m-Y',strtotime($dob)));

        $now = explode('-', $now);
        $mnt = array(1 => 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        if (($now[2]%400 == 0) or ($now[2]%4==0 and $now[2]%100!=0)) $mnt[2]=29;
        if($now[0] < $dob[0]){
            $now[0] += $mnt[(int)$now[1]];
            $now[1]--;
        }
        if($now[1] < $dob[1]){
            $now[1] += 12;
            $now[2]--;
        }

        $years = $tot_year=  ($now[2] - $dob[2]);
        $months = ($now[1] - $dob[1]) ;
        $days = ($now[0] - $dob[0]);


        if($years > 0){

            $tot_month =$months;
            $tot_day =$days;
            $return_time  = ($years > 1)?$years." Years":$years." Year";
            if($months > 0){
                $months = ($months > 1)?$months." Months":$months." Month";
                $return_time .= " - ".$months;
            }

            if($days > 0){
                $days = ($days > 1)?$days." Days":$days." Day";
                $return_time .= " - ".$days;
            }




        }else if($months >0 ){

            $tot_month =$months;
            $tot_day =$days;

            $return_time = ($months > 1)?$months." Months":$months." Month";
            if($days > 0){
                $days = ($days > 1)?$days." Days":$days." Day";
                $return_time .= " - ".$days;
            }

        }else{

            $tot_month =$months;
            $tot_day =$days;
            $return_time = $days." Day";
            if($years == 0 && $months ==0 && $days==0){
                $return_time = "Born Today";
            }else{
                $days = ($days > 1)?$days." Days":$days." Day";
                $return_time = $days;
            }
        }
        if($string_format===true){
            return $return_time;
        }else{
            return array(
                'year'=>$tot_year,
                'month'=>$tot_month,
                'day'=>$tot_day
            );
        }
    }

    /*  public static  function dob_elapsed_string($startDate)
      {
          $startDate = strtotime($startDate);
          $endDate = strtotime(date('Y-m-d'));
          if ($startDate === false || $startDate < 0 || $endDate === false || $endDate < 0 || $startDate > $endDate)
              return false;

          $years = date('Y', $endDate) - date('Y', $startDate);

          $endMonth = date('m', $endDate);
          $startMonth = date('m', $startDate);

          // Calculate months
          $months = $endMonth - $startMonth;
          if ($months <= 0)  {
              $months += 12;
              $years--;
          }
          if ($years < 0)
              $years  = 0;


          // Calculate the days
          $offsets = array();
          if ($years > 0)
              $offsets[] = $years . (($years == 1) ? ' year' : ' years');
          if ($months > 0)
              $offsets[] = $months . (($months == 1) ? ' month' : ' months');
          $offsets = count($offsets) > 0 ? '+' . implode(' ', $offsets) : 'now';

          $days = $endDate - strtotime($offsets, $startDate);
          $days = date('z', $days);


          if($years > 0){
              $return_time = $years." Year ";
              $return_time .= ($years == 1)?"":"s";

              $return_month = $months." Month";
              $return_month .= ($months == 1)?"":"s";
              $return_time .= $return_month;

          }else if($months >0 ){
              $return_time = $months." Month";
              $return_time .= ($months == 1)?"":"s";

              $return_days = $days." Day";
              $return_days .= ($days == 1)?"":"s";
              $return_time .= $return_days;


          }else{
              $return_time = $days." Day";
              if($days==0){
                  $return_time = "Born Today";
              }else{
                  $return_time .= ($days ==1 || $days ==0 )?"":"s";
              }
          }

          return $return_time;


      }*/

    /* public static function dob_elapsed_string($dob) {
           $date1=date_create(date('Y-m-d',strtotime($dob)));
           $date2=date_create(date('Y-m-d'));
           $diff=date_diff($date1,$date2);
           $year = $diff->y;
           $month = $diff->m;
           $days = $diff->days;
           $weeks = ($days % 7  == 0)?($days / 7):0;
           $return_time="";
           if($year > 0){
               $return_time = $year." Year";
               $return_time .= ($year == 1)?"":"s";
           }else if($month >0 ){
               $return_time = $month." Month";
               $return_time .= ($month == 1)?"":"s";
           }else if($weeks >0 ){
               $return_time = $weeks." Week";
               $return_time .= ($weeks == 1)?"":"s";
           }else{
               $return_time = $days." Day";
               if($days==0){
                   $return_time = "Born Today";
               }else{
                   $return_time .= ($days ==1 || $days ==0 )?"":"s";
               }

           }
           return $return_time;


       }*/


    public static function get_circle_data($thin_app_id, $app_wit_id){
        $query = "select * from app_circles  where  thinapp_id = $thin_app_id and with_app_id = $app_wit_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if($service_message_list->num_rows) {
            $list = mysqli_fetch_assoc($service_message_list);
            return $list;
        }else{
            return false;
        }
    }


    public static function get_customer_appoinment_for_date($thin_app_id,$customer_id,$address_id,$doctor_id,$date,$service_id){
        $date = date('Y-m-d',strtotime($date));
        $query = "select acss.* from appointment_customer_staff_services as acss where  acss.thinapp_id = $thin_app_id and acss.appointment_customer_id = $customer_id and acss.appointment_address_id = $address_id and acss.appointment_staff_id = $doctor_id and '$date' = DATE(acss.appointment_datetime) and acss.appointment_service_id = $service_id and  acss.status != 'CLOSED' and acss.status != 'CANCELED' order by acss.id desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function get_appointment_validity_data($thin_app_id,$customer_id,$address_id,$doctor_id,$date,$service_id,$appointment_status ="NEW",$appointment_role,$user_type,$queue_number=0,$slot_time = ""){

        $final_array=array();
        $allow_check = true;
        if(!empty($queue_number) && !empty($slot_time)){
            $is_token_booked = Custom::is_token_booked($date,  $doctor_id, $address_id, $service_id, $queue_number,$slot_time);
            $allow_check = ($is_token_booked === true)?false:true;
        }
        if ($allow_check === true) {
            $date = date('Y-m-d',strtotime($date));
            $now = date('Y-m-d');
            $limit = 1; $order = "desc";
            if(strtotime($date) >= strtotime($now) && $appointment_status =="RESCHEDULE"){
                $limit = 2;
            }

            $customer_condition = $inn_customer_condition = "";
            $inner_user_type_field = ($user_type=='CHILDREN')?"acss_inn.children_id":"acss_inn.appointment_customer_id";
            $user_type_field = ($user_type=='CHILDREN')?"acss.children_id":"acss.appointment_customer_id";
            $inn_customer_condition .= " and $inner_user_type_field = $customer_id and acss_inn.appointment_address_id = $address_id and acss_inn.appointment_staff_id = $doctor_id and acss_inn.appointment_service_id = $service_id";


            $app_data= Custom::getThinAppData($thin_app_id);
            $allow_book_new_appointment = true;


            $final_array['allow_book_new_appointment'] = "YES";
            $final_array['allow_add_free_appointment'] = "NO";
            $final_array['total_appointment'] = 0;
            $final_array['appointment_parent_id'] = 0;
            $final_array['total_appointment'] = 0;
            $final_array['last_appointment_id'] = 0;
            $final_array['appointment_parent_id'] = 0;

            if($app_data['multiple_booking_from_same_number']=='INACTIVE'){
                $customer_data = Custom::get_patient_detail($customer_id,$user_type);
                if(!empty($customer_data)){
                    $patient_mobile = Custom::create_mobile_number($customer_data['mobile']);
                    if(!empty($patient_mobile) && $patient_mobile != '+919999999999'){
                        $search_appointment_data = Custom::get_patient_appointment_booking_data_by_number($doctor_id,$address_id, $patient_mobile);
                        if(!empty($search_appointment_data)){
                            $allow_book_new_appointment = false;
                            $final_array['allow_book_new_appointment'] = "NO";
                            $app_date = date('d M Y h:i A', strtotime($search_appointment_data['appointment_datetime']));
                            $__app_date = date('Y-m-d', strtotime($search_appointment_data['appointment_datetime']));
                            $day_label  = Custom::get_date_label($__app_date);
                            $__app_date2 = date('Y-m-d', strtotime($date));
                            if($__app_date == $__app_date2){
                                $final_array['message'] = "This mobile number already booked an appointment for ".$day_label.", ".$app_date;
                            }else{
                                $final_array['message'] = "This mobile number already booked an appointment for ".$day_label.", ".$app_date.". You can reschedule this appointment";
                            }
                        }
                    }
                }
            }


            if($allow_book_new_appointment===true){
                $final_array['message'] = "Allow book appointment.";
                if(!empty($app_data)){
                    if($app_data['global_service_validity'] =='NO'){
                        $customer_condition .= " and $user_type_field = $customer_id and acss.appointment_address_id = $address_id and acss.appointment_staff_id = $doctor_id and acss.appointment_service_id = $service_id ";
                    }
                }
                if($user_type == "CUSTOMER"){
                    $customer_condition .= " and acss.appointment_customer_id = $customer_id ";
                    $inn_customer_condition .= " and acss_inn.appointment_customer_id = $customer_id ";
                }else{
                    $customer_condition .= " and acss.children_id = $customer_id ";
                    $inn_customer_condition .= " and acss_inn.children_id = $customer_id ";
                }
                $query = "select acss.booking_validity_attempt, IF(mpo.is_refunded='YES' AND mpod.total_amount = 0,'PENDING',acss.payment_status ) AS payment_status, (select acss_inn.appointment_datetime from appointment_customer_staff_services as acss_inn WHERE acss_inn.thinapp_id = $thin_app_id  $inn_customer_condition   AND DATE(acss_inn.appointment_datetime) >= DATE(NOW()) AND acss_inn.status IN ('NEW','CONFIRM','RESCHEDULE') and acss_inn.delete_status != 'DELETED' LIMIT 1 ) as upcoming_appointment_datetime, @parent_data:= (select acss_inn.appointment_datetime from appointment_customer_staff_services as acss_inn where acss_inn.id = acss.appointment_parent_id and acss_inn.booking_validity_attempt = 1) as parent_date, acss.appointment_datetime as last_appointment_date_time, acss.appointment_parent_id, acss.status, acss.id AS last_appointment_id, (select count(id) from appointment_customer_staff_services where thinapp_id = $thin_app_id and appointment_parent_id = acss.appointment_parent_id  and status != 'CANCELED') as total_appointment , IF(t.global_service_validity='YES',t.service_validity_days,app_ser.service_validity_time) as service_validity_time from appointment_customer_staff_services as acss join thinapps as t on t.id =acss.thinapp_id join appointment_services as app_ser on app_ser.id = acss.appointment_service_id LEFT JOIN medical_product_order_details AS mpod ON mpod.appointment_customer_staff_service_id = acss.id AND  mpod.medical_product_id = 0 AND mpod.service ='OPD' left JOIN medical_product_orders AS mpo ON mpo.id = mpod.medical_product_order_id AND mpo.is_opd ='Y' AND mpo.is_refunded='YES' where  acss.thinapp_id = $thin_app_id $customer_condition  and acss.status != 'CANCELED' and acss.delete_status != 'DELETED' order by acss.id $order limit $limit";
                //$query = "select acss.queue_number,acss.booking_validity_attempt, IF(mpo.is_refunded='YES' AND mpod.total_amount = 0,'PENDING',acss.payment_status ) AS payment_status, (select acss_inn.appointment_datetime from appointment_customer_staff_services as acss_inn WHERE acss_inn.thinapp_id = $thin_app_id  $inn_customer_condition   AND DATE(acss_inn.appointment_datetime) >= DATE(NOW()) AND acss_inn.status IN ('NEW','CONFIRM','RESCHEDULE') and acss_inn.delete_status != 'DELETED' LIMIT 1 ) as upcoming_appointment_datetime, @parent_data:= (select acss_inn.appointment_datetime from appointment_customer_staff_services as acss_inn where acss_inn.id = acss.appointment_parent_id and acss_inn.booking_validity_attempt = 1) as parent_date, acss.appointment_datetime as last_appointment_date_time, acss.appointment_parent_id, acss.status, acss.id AS last_appointment_id, (select count(id) from appointment_customer_staff_services where thinapp_id = $thin_app_id and appointment_parent_id = acss.appointment_parent_id  and status != 'CANCELED') as total_appointment , IF(t.global_service_validity='YES',t.service_validity_days,app_ser.service_validity_time) as service_validity_time from appointment_customer_staff_services as acss join thinapps as t on t.id =acss.thinapp_id join appointment_services as app_ser on app_ser.id = acss.appointment_service_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id LEFT JOIN medical_product_order_details AS mpod ON mpod.appointment_customer_staff_service_id = acss.id AND  mpod.medical_product_id = 0 AND mpod.service ='OPD' left JOIN medical_product_orders AS mpo ON mpo.id = mpod.medical_product_order_id AND mpo.is_opd ='Y' AND mpo.is_refunded='YES' where  (ac.mobile!='+919999999999' || c.mobile!='+919999999999') and acss.thinapp_id = $thin_app_id $customer_condition  and acss.status != 'CANCELED' and acss.delete_status != 'DELETED' order by acss.id $order limit $limit";
                
            	$connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if($service_message_list->num_rows) {
                    if($limit == 1){
                        $return_array =mysqli_fetch_assoc($service_message_list);
                    }else{
                        $return =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                        if(count($return) == 2){
                            $return_array = $return[1];
                        }else{
                            $return_array = $return[0];
                        }
                    }

                    $status = $return_array['status'];
                    $last_appointment_date_time = $return_array['last_appointment_date_time'];
                    $last_payment_status = $return_array['payment_status'];
                    $upcoming_appointment_datetime = $return_array['upcoming_appointment_datetime'];
                    $last_stamp = strtotime(date('Y-m-d H:i',strtotime($last_appointment_date_time)));
                    $current_stamp = strtotime(date('Y-m-d H:i'));
                    $final_array['appointment_parent_id'] = $return_array['appointment_parent_id'];

                    $final_array['total_appointment'] = $return_array['total_appointment'];


                    $final_array['last_appointment_id'] = $return_array['last_appointment_id'];
                    $final_array['appointment_parent_id'] = $return_array['appointment_parent_id'];

                    /* THIS CODE CHECK FOR FREE APPOINTMENT VALIDITY */

                    $service_validity_time = $return_array['service_validity_time'];
                    $total_appointment = $return_array['total_appointment'];
                    $parent_date = date('Y-m-d',strtotime($return_array['parent_date']));
                    $day = ($service_validity_time > 1)?" + $service_validity_time days":" + $service_validity_time day";
                    $interval_date = date('Y-m-d', strtotime($parent_date.$day));

                    $interval = ($service_validity_time > 1 )?" +$service_validity_time days":" +$service_validity_time day";
                    $parent_appointment_date = strtotime(date('Y-m-d', strtotime($return_array['parent_date'].$interval)));
                    $appointment_date = strtotime(date('Y-m-d',strtotime($date)));

                    if($appointment_date < $parent_appointment_date){
                        if( $appointment_status == "RESCHEDULE" && ($appointment_role == "ADMIN" ||$appointment_role == "STAFF" )){
                            if(strtotime($date) <= strtotime($interval_date) && $last_payment_status =="SUCCESS"){
                                $final_array['allow_add_free_appointment'] = "YES";
                            }else{
                                $final_array['allow_add_free_appointment'] = "NO";
                            }
                        }else{
                            if(strtotime($date) <= strtotime($interval_date) && $last_payment_status =="SUCCESS"){
                                $final_array['allow_add_free_appointment'] = "YES";
                            }else{
                                $final_array['allow_add_free_appointment'] = "NO";
                            }
                        }
                    }else{
                        if($service_validity_time > 0 ){
                            if(strtotime($date) < strtotime($interval_date)){
                                $final_array['allow_add_free_appointment'] = "YES";
                            }else{
                                $final_array['allow_add_free_appointment'] = "NO";
                            }
                        }else{
                            $final_array['allow_add_free_appointment'] = "NO";
                        }
                    }

                    $final_array['booking_validity_attempt'] = $return_array['booking_validity_attempt']+1;


                    /*--------------------END----------------------*/

                    if( $appointment_status == "NEW" &&  empty($upcoming_appointment_datetime)){
                        $final_array['allow_book_new_appointment'] = "YES";
                    }else if($appointment_status == "RESCHEDULE"){
                        $final_array['allow_book_new_appointment'] = "YES";

                    }else{
                        $final_array['allow_book_new_appointment'] = "NO";
                        $app_date = date('d M Y h:i A', strtotime($upcoming_appointment_datetime));
                        $__app_date = date('Y-m-d', strtotime($upcoming_appointment_datetime));
                        $day_label  = Custom::get_date_label($__app_date);
                        $__app_date2 = date('Y-m-d', strtotime($date));
                        if($__app_date == $__app_date2){
                            $final_array['message'] = "You have already booked an appointment for ".$day_label.", ".$app_date;
                        }else{
                            $final_array['message'] = "You have already booked an appointment for ".$day_label.", ".$app_date.". You can reschedule this appointment";
                        }
                    }

                }
            }

        }else{
            $final_array['allow_book_new_appointment'] = "NO";
            $final_array['message'] = "Token or time slot already booked";
        }
        return $final_array;


    }




    public static function update_appointment_attempt($connection,$thin_app_id,$reschedule_app_data_array){
        $query = "select acss.id from appointment_customer_staff_services as acss  where acss.thinapp_id = $thin_app_id and acss.status !='CANCELED' and acss.appointment_parent_id = ".$reschedule_app_data_array['appointment_parent_id']." order by acss.id asc";
        $result_array =array();
        $service_message_list = $connection->query($query);
        if($service_message_list->num_rows) {
            $list = array_column(mysqli_fetch_all($service_message_list,MYSQL_ASSOC),'id');
            foreach($list as $key => $appointment_id){
                $attempt_number = $key+1;
                if($reschedule_app_data_array['booking_validity_attempt']==1 && $attempt_number ==1){
                    $sql = "update appointment_customer_staff_services set payment_status=?, booking_validity_attempt =? where id =?";
                    $stmt_sub = $connection->prepare($sql);
                    $stmt_sub->bind_param('sss', $reschedule_app_data_array['payment_status'], $attempt_number, $appointment_id );
                }else{
                    $sql = "update appointment_customer_staff_services set booking_validity_attempt =? where id =?";
                    $stmt_sub = $connection->prepare($sql);
                    $stmt_sub->bind_param('ss', $attempt_number, $appointment_id );
                }

                if($stmt_sub->execute()){
                    $result_array[] = true;
                }else{
                    $result_array[] = false;
                }
            }
            if(!empty($result_array) && !in_array(false)){
                return true;
            }else{
                return false;
            }
        }else{
            return true;
        }
    }

    public static function get_doctor_blocked_slot($connection,$thin_app_id,$address_id,$doctor_id,$date, $service_id = 0){
        $file_name = "block_list_".$date."_".$doctor_id."_".$address_id;
        if (!$list = WebservicesFunction::readJson($file_name, "appointment/blocked")){
            if(!empty($service_id)){
                $date = date('Y-m-d',strtotime($date));
                $query = "select slot,is_date_blocked from appointment_bloked_slots where thinapp_id = $thin_app_id  and doctor_id = $doctor_id and ( (address_id = $address_id and service_id = $service_id) OR is_date_blocked='YES')  and book_date = '$date' limit 1";
                $service_message_list = $connection->query($query);
                if($service_message_list->num_rows) {
                    $list = mysqli_fetch_assoc($service_message_list);
                    if($list['is_date_blocked']=="YES"){
                        $list =  array('ALL_SLOT'=>'All date is bocked');
                    }else{
                        $list = explode(",", json_decode($list['slot'],true));
                    }
                }
            }else{
                $date = date('Y-m-d',strtotime($date));
                $query = "select slot from appointment_bloked_slots where thinapp_id = $thin_app_id and new_appointment = 'NO' and doctor_id = $doctor_id and address_id = $address_id and book_date = '$date' limit 1";
                $service_message_list = $connection->query($query);
                if($service_message_list->num_rows) {
                    $list = mysqli_fetch_assoc($service_message_list);
                    $list = explode(",", json_decode($list['slot'],true));
                }
            }
            $list = empty($list)?"false":$list;
           // WebservicesFunction::createJson($file_name, $list, 'CREATE', "appointment/blocked");
        }
        return ($list=='false')?false:$list;
    }


    public static function load_doctor_slot_by_address($booking_date,$doctor_id,$service_slot_duration,$thin_app_id,$address_id,$key_as_slot = false,$appointment_user_role = false,$add_more_slot =false,$expire_slots = false){
        $return_array = $last_array = array();
        $counter =  0;
        $queue_number = 1;
        $slot_array = WebservicesFunction::get_booking_slots($booking_date, $doctor_id, $service_slot_duration,$thin_app_id,$address_id,true);
    
    	
    
        $doctor_data = Custom::get_doctor_by_id($doctor_id,$thin_app_id);
        if($appointment_user_role=='USER' && ( $thin_app_id == 588 ) && $booking_date != date('Y-m-d')){
            return array();
        }else if($appointment_user_role=='USER' && ( $thin_app_id == 633 ) && strtotime($booking_date) > strtotime(date('Y-m-d'). ' + 2 days')){
            return array();
        }else{
        
        	$show_time_slot_to_patient = isset($doctor_data['show_time_slot_to_patient'])?$doctor_data['show_time_slot_to_patient']:'YES';
            if($show_time_slot_to_patient=='NO' && $appointment_user_role=='USER'){
                return array();
            }
        
        
            $show_appointment_time  = $show_appointment_token = "YES";
        	$show_booked_slot  = ($doctor_data['show_booked_slot_to_patient'] =='YES')?true:false;
            if($show_booked_slot===false && $appointment_user_role == 'USER'){
                $show_booked_slot = false;
            }
            if(!empty($doctor_data)){
                $show_appointment_time = $doctor_data['show_appointment_time'];
                $show_appointment_token = $doctor_data['show_appointment_token'];
            }
            if(!empty($slot_array)) {
                $valid_date = ($booking_date >= date('Y-m-d'));

                foreach ($slot_array as $slot_key => $slot) {
                    $new_date = date('d-D-M-Y', strtotime($booking_date));
                    $new_date = implode("##", explode("-", $new_date));
                    $index_key = ($key_as_slot === true)?$slot_key:$counter++;
                    if($expire_slots===true){
                        if ( $slot == 'AVAILABLE'  || ( $appointment_user_role != "USER" && $slot == 'EXPIRED' && $valid_date) ||  ( $show_booked_slot && $slot == 'BOOKED' )  || ( $appointment_user_role != "USER" && $slot == "BLOCKED") || ( $appointment_user_role != "USER" && $slot == "REFUND") ) {
                            $slot = ($slot=='EXPIRED')?'AVAILABLE':$slot;
                            $return_array[$index_key] = array('timestamp'=>strtotime($slot_key),'slot' => $slot_key, 'status' => $slot, 'date' => $new_date, 'queue_number' => $queue_number,'show_appointment_time'=>$show_appointment_time,'show_appointment_token'=>$show_appointment_token,'custom_slot'=>'NO');
                        }
                    }else{
                        if ( $slot == 'AVAILABLE'  || (  $show_booked_slot && $slot == 'BOOKED' )  || ( $appointment_user_role != "USER" && $slot == "BLOCKED") || ( $appointment_user_role != "USER" && $slot == "REFUND") ) {
                            $slot = ($slot=='EXPIRED' && $valid_date)?'AVAILABLE':$slot;
                            $return_array[$index_key] = array('timestamp'=>strtotime($slot_key),'slot' => $slot_key, 'status' => $slot, 'date' => $new_date, 'queue_number' => $queue_number,'show_appointment_time'=>$show_appointment_time,'show_appointment_token'=>$show_appointment_token,'custom_slot'=>'NO');
                        }
                    }
                    if($expire_slots===true && $valid_date){
                        $flag_array[]= 'EXPIRED';
                    }
                    $flag_array[]= 'AVAILABLE';
                    $flag_array[]= 'BOOKED';
                    $flag_array[]= 'BLOCKED';
                    $flag_array[]= 'REFUND';
                    $flag_array[]= 'NEW';
                    if (in_array($slot,$flag_array)) {
                        $last_array[] = array('timestamp'=>strtotime($slot_key), 'slot' => $slot_key, 'status' => $slot, 'date' => $new_date, 'queue_number' => $queue_number,'show_appointment_time'=>$show_appointment_time,'show_appointment_token'=>$show_appointment_token,'custom_slot'=>'NO');
                    }
                    if ($slot != 'BREAK') {
                        $queue_number++;
                    }
                }

                $return_array = Custom::array_order_by($return_array, 'timestamp', SORT_ASC);
                if(!empty($last_array) && $add_more_slot ===true){

                    $custom_tokens = Custom::get_doctor_custom_tokens($doctor_id,$address_id,$booking_date);
                    if(!empty($custom_tokens)){
                        foreach($custom_tokens as $custom_key => $custom){
                            $slot_key = $custom['slot_time'];
                            $queue_number = $custom['queue_number'];
                            $index_key = ($key_as_slot === true)?$slot_key:count($return_array);
                            $return_array[$index_key] = array('timestamp'=>strtotime($slot_key), 'slot' => $slot_key, 'status' => 'BOOKED', 'date' => $new_date, 'queue_number' => $queue_number,'show_appointment_time'=>$show_appointment_time,'show_appointment_token'=>$show_appointment_token,'custom_slot'=>'NO');
                        }
                        $return_array = Custom::array_order_by($return_array, 'timestamp', SORT_ASC);
                        $lastKey = end(array_keys($return_array));
                        $last_array = end($return_array);
                        $queue_number= $last_array['queue_number']+1;
                    }else{
                        $return_array = Custom::array_order_by($return_array, 'timestamp', SORT_ASC);
                        $lastKey = @end(array_keys($return_array));
                        $last_array = end($last_array);
                        $queue_number= $last_array['queue_number']+1;
                    }
                    $slot_key = date('h:i A',strtotime("+ ".$service_slot_duration, strtotime($last_array['slot'])));
                    $index_key = ($key_as_slot === true)?$slot_key:$lastKey+1;
                    $return_array[$index_key] = array('timestamp'=>strtotime($slot_key), 'slot' => $slot_key, 'status' => 'AVAILABLE', 'date' => $new_date, 'queue_number' => $queue_number,'show_appointment_time'=>$show_appointment_time,'show_appointment_token'=>$show_appointment_token,'custom_slot'=>'YES');


                }
            }

            if($key_as_slot === false){
                $return_array = json_decode(json_encode(array_values($return_array),JSON_NUMERIC_CHECK),true);
                return array_values(array_map("unserialize", array_unique(array_map("serialize", $return_array))));
            }else{

                return $return_array;
            }
        }


    }

    public static function load_doctor_slot_by_address_for_ivr($code,$booking_date, $doctor_id, $service_slot_duration, $thin_app_id, $address_id, $key_as_slot = false)
    {
        $return_array = array();
        $counter = 0;
        $queue_number = 1;

        $slot_array = WebservicesFunction::get_booking_slots($booking_date, $doctor_id, $service_slot_duration, $thin_app_id, $address_id, true);

        if (!empty($slot_array)) {
            $startTime = date("Y-m-d h:i A");
            $cenvertedTime = date('Y-m-d h:i A',strtotime('+30 minutes',strtotime($startTime)));
            foreach ($slot_array as $slot_key => $slot) {
                if ($code == 1) {
                    if ( (strtotime($slot_key) >= strtotime('11.59 PM') && strtotime($slot_key) < strtotime('11.59 AM')) && strtotime($slot_key) > $cenvertedTime) {

                        if ($slot == 'AVAILABLE' || $slot == 'BOOKED' || $slot == "BLOCKED") {
                            $new_date = date('d-D-M-Y', strtotime($booking_date));
                            $new_date = implode("##", explode("-", $new_date));
                            $index_key = ($key_as_slot === true) ? $slot_key : $counter++;
                            $return_array[$index_key] = array('slot' => $slot_key, 'status' => $slot, 'date' => $new_date, 'queue_number' => $queue_number);
                        }
                    }
                    if ($slot != 'BREAK') {
                        $queue_number++;
                    }
                } else if ($code == 2) {
                    $result = substr($slot_key, 0, 2);
                    if ((strtotime($slot_key) >= strtotime('12:00 PM') && strtotime($slot_key) < strtotime('05:00 PM')) && strtotime($slot_key) > strtotime($cenvertedTime)) {
                        if ($slot == 'AVAILABLE' || $slot == 'BOOKED' || $slot == "BLOCKED") {
                            $new_date = date('d-D-M-Y', strtotime($booking_date));
                            $new_date = implode("##", explode("-", $new_date));
                            $index_key = ($key_as_slot === true) ? $slot_key : $counter++;
                            $return_array[$index_key] = array('slot' => $slot_key, 'status' => $slot, 'date' => $new_date, 'queue_number' => $queue_number);
                        }
                    }
                    if ($slot != 'BREAK') {
                        $queue_number++;
                    }
                } else if ($code == 3) {
                    $result = substr($slot_key, 0, 2);
                    if ((strtotime($slot_key) >= strtotime('05:00 PM') && strtotime($slot_key) < strtotime('11:59 PM')) && strtotime($slot_key) > strtotime($cenvertedTime)) {
                        if ($slot == 'AVAILABLE' || $slot == 'BOOKED' || $slot == "BLOCKED") {
                            $new_date = date('d-D-M-Y', strtotime($booking_date));
                            $new_date = implode("##", explode("-", $new_date));
                            $index_key = ($key_as_slot === true) ? $slot_key : $counter++;
                            $return_array[$index_key] = array('slot' => $slot_key, 'status' => $slot, 'date' => $new_date, 'queue_number' => $queue_number);
                        }
                    }
                    if ($slot != 'BREAK') {
                        $queue_number++;
                    }
                }

            }


        }
        return $return_array;
    }



    public static function create_doctor_hours($connection, $thin_app_id, $doctor_id,$user_id){
        $query = "select * from  appointment_day_times where status ='ACTIVE'";
        $subscriber = $connection->query($query);
        $total_result =array();
        if ($subscriber->num_rows) {
            $list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
            $time_from = APPOINTMENT_WORKING_START_TIME;
            $time_to = APPOINTMENT_WORKING_END_TIME;
            $created = Custom::created();
            foreach ($list as $key => $value){
                $sql  = "INSERT INTO appointment_staff_hours (thinapp_id, user_id, appointment_staff_id, appointment_day_time_id, time_from, time_to, created, modified ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssssssss', $thin_app_id, $user_id, $doctor_id, $value['id'],$time_from, $time_to, $created, $created );
                if($stmt->execute()) {
                    $total_result[] =  true;
                }else{
                    $total_result[] =  false;
                }
            }
        }
        if(!in_array(false,$total_result)){
            return 1;
        }else{
            return 0;
        }
    }


    public static function get_appointment_id_by_slot($doctor_id, $address_id,$date,$slot_array, $sub_token = false){
        $date = date('Y-m-d',strtotime($date));
        $slot_string = "";
        if(!empty($slot_array)){
            $slot_string =  '"'.implode('","', $slot_array).'"';
            $slot_string = " AND slot_time IN ($slot_string) ";
        }

        if($sub_token===true){
            $sub_token = " and sub_token ='YES'";
        }else{
            $sub_token = " and sub_token ='NO'";
        }

        $query =    $query = "select id,appointment_datetime,status from appointment_customer_staff_services where appointment_staff_id = $doctor_id and appointment_address_id = $address_id and DATE(appointment_datetime) = '$date' $slot_string AND status IN ('NEW','CONFIRM','RESCHEDULE') $sub_token";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $app_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $app_data;
        }else{
            return false;
        }
    }

    public static function create_block_slot_string($slot_string){

        $slot_string = array_unique(explode(",", $slot_string));
        $time_stamp =array();
        foreach ($slot_string as $key  =>$value){ $time_stamp[strtotime($value)] = $value;}
        ksort($time_stamp);
        return json_encode(implode(",",$time_stamp));

    }

    public static function messageDateFormat($date){
        return date('d M, Y H:i',strtotime($date));
    }

     public static function short_url($longURL,$thin_app_id = 0){
        if(!empty($longURL)){
            try{
                $shortner = new UrlShort();
                $shortCode = $shortner->urlToShortCode($longURL,$thin_app_id);
                if(!empty($shortCode)){
                    return SHORT_URL.$shortCode;
                }else{
                    return $longURL;
                }
            }catch(Exception $e){
                return $longURL;
            }

        }else{
            return "";
        }
    }

    public static function send_subscriber_sms_notification($background_data,$send_notification_user_ids,$thin_app_id){
        if (!empty($background_data)) {
            /* this function send otp and send process in backround*/
            Custom::send_process_to_background();
            Custom::SendBlukSmsToNumbers($background_data['thinapp_id'], $background_data['message'], $background_data['mobile'], $background_data['user_id']);
            if (!empty($send_notification_user_ids)) {
                $user_ids = $background_data['notification']['user_ids'];
                Custom::send_notification_by_user_id($background_data['notification']['data'], $user_ids, $thin_app_id);
                Custom::add_subscribers_to_topic($background_data['notification']['topic_name'], array(), $send_notification_user_ids);
                foreach($user_ids as $key  => $id){
                    $file_name_array[] = "get_subscriber_list_app".$thin_app_id."_user".$id;
                }
                WebservicesFunction::deleteJson($file_name_array);
            }
        }
    }



    public static function get_total_static_likes($message_id){
        $query = "select *, (total_fb_share+total_twitter_share+total_gplus_share+total_whatsapp_share+total_other_share) as total_share from message_statics  where  message_id = $message_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if($service_message_list->num_rows) {
            $list = mysqli_fetch_assoc($service_message_list);
            return $list;
        }else{
            return false;
        }
    }

    public static function get_child_notification_data($thin_app_id, $child_id){

        $query = "select tu.username as admin_name, tu.firebase_token as admin_token, tu.mobile as admin_mobile, c.child_add_by, c.child_add_by_id, c.id, c.child_name, c.child_number, t.name as app_name, cu.username as main_parent_username, cu.firebase_token as main_parent_token, cu.app_installed_status as main_parent_installed, c.mobile as main_parent_mobile,  pu.username as alt_parent_username, pu.firebase_token as alt_parent_token, pu.app_installed_status as alt_parent_installed, c.parents_mobile as alt_parent_mobile from childrens as c join thinapps as t on c.thinapp_id = t.id left join users as tu on tu.role_id = 5 and c.thinapp_id = tu.thinapp_id left join users as cu on c.mobile = cu.mobile and c.thinapp_id = cu.thinapp_id left join users as pu on c.parents_mobile = pu.mobile and c.thinapp_id = pu.thinapp_id where c.id = $child_id and c.thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if($service_message_list->num_rows) {
            $list = mysqli_fetch_assoc($service_message_list);
            return $list;
        }else{
            return false;
        }

    }






    public static function get_bar_chart_data($thin_app_id,$from_date,$to_date, $group_by = false){

        $from_date = date('Y-m-d',strtotime($from_date));
        $to_date = date('Y-m-d',strtotime($to_date));
        $condition = " DATE(access_date_time) >= '$from_date' and DATE(access_date_time) <= '$to_date' ";
        if(!empty($thin_app_id)){
            $condition .= " AND thinapp_id = $thin_app_id ";
        }

        if($group_by === true){
            $query = "SELECT module_name as label,  COUNT(DISTINCT(user_id)) as total_count from functionality_stats where $condition group by module_name order by module_name asc";

        }else{
            $query = "SELECT module_name as label, COUNT(*) as total_count from functionality_stats where $condition group by module_name order by module_name asc";

        }

        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $data = mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            $return_array =array();
            foreach($data as $key =>$value){
                $return_array['data'][$key] =(int)$value['total_count'];
                $return_array['label'][$key] =$value['label'];
            }
            return $return_array;
        }else{
            return false;
        }

    }

    public static function get_bar_chart_sub_module_data($thin_app_id,$from_date,$to_date,$module_name){

        $from_date = date('Y-m-d',strtotime($from_date));
        $to_date = date('Y-m-d',strtotime($to_date));
        $condition = " module_name = '$module_name' and DATE(access_date_time) >= '$from_date' and DATE(access_date_time) <= '$to_date' ";
        if(!empty($thin_app_id)){
            $condition .= " AND thinapp_id = $thin_app_id ";
        }
        $query = "SELECT module_sub_name as label, COUNT(*) as total_count from functionality_stats where $condition group by module_sub_name order by module_sub_name asc";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $data = mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            $return_array =array();
            foreach($data as $key =>$value){
                $return_array['data'][$key] =(int)$value['total_count'];
                $return_array['label'][$key] =$value['label'];
            }
            return $return_array;
        }else{
            return false;
        }

    }

    public static function get_chart_user_static($thin_app_id,$from_date,$to_date){

        $from_date = date('Y-m-d',strtotime($from_date));
        $to_date = date('Y-m-d',strtotime($to_date));


        $condition = " and DATE(created) >= '$from_date' and DATE(created) <= '$to_date' ";
        if(!empty($thin_app_id)){
            $condition .= " AND thinapp_id = $thin_app_id ";
        }

        $condition2 = " and DATE(modified) >= '$from_date' and DATE(modified) <= '$to_date' ";
        if(!empty($thin_app_id)){
            $condition2 .= " AND thinapp_id = $thin_app_id ";
        }


        $query = "select 'Total Installed' AS label, count(*) as total_count from users where is_verified ='Y' AND status = 'Y' AND role_id =1 $condition UNION ALL select 'Total Uninstalled' AS label, count(*) as total_count from users where is_verified ='Y' AND status = 'Y' AND role_id =1 and app_installed_status = 'UNINSTALLED' $condition2";

        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $data = mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            $return_array =array();
            $total_installed = $total_uninstalled = 0;
            foreach($data as $key =>$value){
                if($key == 0){
                    $total_installed = (int)$value['total_count'];
                }else if($key==1){
                    $total_uninstalled = (int)$value['total_count'];
                }
                $return_array['data'][$key] =(int)$value['total_count'];
                $return_array['label'][$key] =$value['label'];

            }
            $ratio = round((($total_installed - $total_uninstalled)/$total_installed)*100)."%";
            $return_array['data'][2] =$ratio;
            $return_array['label'][2] ="Retention Rate";
            return $return_array;
        }else{
            return false;
        }

    }

    public static function get_user_enable_functionality_with_text($connection,$thin_app_id){

        $features =array();
        $fun_type_data = $connection->query("SELECT * FROM `user_functionality_types` WHERE `status` = 'Y'");
        if ($fun_type_data->num_rows) {
            $fun_type_data = mysqli_fetch_all($fun_type_data, MYSQLI_ASSOC);
        }
        $enable_fun = $connection->query("SELECT * FROM `user_enabled_fun_permissions` WHERE `thinapp_id` =" . $thin_app_id);
        $enable_fun_column = array();
        if ($enable_fun->num_rows) {
            $enable_fun = mysqli_fetch_all($enable_fun, MYSQLI_ASSOC);
            $enable_fun_column = array_column($enable_fun, 'permission', 'user_functionality_type_id');
        }
        foreach ($fun_type_data as $key => $value) {
            $features[$value['label_text']] = (array_key_exists($value['id'], $enable_fun_column)) ? $enable_fun_column[$value['id']] : "NO";
        }
        return $features;
    }

    public static function get_consent_template_by_id($thin_app_id,$consent_id){
        $query =    $query = "select * from consent_templates where  id = $consent_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function create_read_timeline_cache($fileName,$action="CREATE",$value=null){
        if(!empty($fileName) && !empty($value) && $action == "CREATE") {
            $dir = LOCAL_PATH . 'app/webroot/cache/timeline/';
            if(!is_dir($dir)){
                mkdir($dir, 0777,true);
            }
            $file_name = $fileName . '.json';
            $file_path = LOCAL_PATH . 'app/webroot/cache/timeline/' . $file_name;
            if (file_exists($file_path)) {
                $data_array = ",".$value;
                file_put_contents($file_path, $data_array,FILE_APPEND | LOCK_EX);
            } else {
                file_put_contents($file_path,$value);
            }
        }else if(!empty($fileName) && $action == "READ"){
            $file_path = LOCAL_PATH . "app/webroot/timeline/" . $fileName . '.json';
            if (file_exists($file_path)) {
                return file_get_contents($file_path);
            } else {
                return false;
            }
        }


    }

    public static function get_consent_by_id($thin_app_id, $consent_id){
        $query = "select c.*, IFNULL(u.username,c.receiver_mobile) as username from consents as c left join users as u on c.receiver_mobile = u.mobile and u.thinapp_id = c.thinapp_id where  c.id = $consent_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_children_list_timeline($thin_app_id,$user_id,$mobile){
        $connection = ConnectionUtil::getConnection();
        if (!$child_list = WebservicesFunction::readJson("user_child_list_$user_id", "timeline")){
            $query = "select id as children_id, child_name as children_name, child_number, image, dob, gender from childrens where thinapp_id = $thin_app_id and mobile = '$mobile' and status ='ACTIVE'";
            $data_list = $connection->query($query);
            if ($data_list->num_rows) {
                $child_list = mysqli_fetch_all($data_list, MYSQLI_ASSOC);
                WebservicesFunction::createJson("user_child_list_$user_id", $child_list, 'CREATE', "timeline");
            }
        }
        return $child_list;

    }

    public static function get_timeline_by_id($thin_app_id,$timeline_id){
        $connection = ConnectionUtil::getConnection();
        $query = "select * from child_timelines where thinapp_id = $thin_app_id and id = $timeline_id and status ='ACTIVE'";
        $data_list = $connection->query($query);
        if ($data_list->num_rows) {
            return mysqli_fetch_assoc($data_list);
        }else{
            return false;
        }
    }


    public static function get_timeline_notification_data($thin_app_id,$mobile,$action_id ){
        if(!empty($action_id)){
            $connection = ConnectionUtil::getConnection();
            $query = "select c.id as children_id, c.child_name, au.username as action_username, tu.firebase_token as user_token, cta.action_type, ctm.message from child_timeline_actions as cta left join child_timelines as ct on cta.child_timeline_id = ct.id left join child_timeline_media as ctm on ctm.id = cta.child_timeline_media_id left join users as au on au.id = cta.user_id left join users as tu on tu.id = ct.user_id join childrens as c on c.id = ct.children_id and c.mobile != '$mobile' and c.parents_mobile != '$mobile' where cta.thinapp_id = $thin_app_id  and cta.id =$action_id limit 1";
            $data_list = $connection->query($query);
            if ($data_list->num_rows) {
                return mysqli_fetch_assoc($data_list);
            }else{
                return false;
            }
        }else{
            return false;
        }

    }



    public static function uploadBase64FileToAws($base_string,$filename =null) {
        if(!empty($base_string)){

            $key = "image/";
            if(empty($filename)){
                $filename = $key.date('YmdHis').rand().".png";
            }else{
                $filename = $key.$filename;
            }
            $tmp = explode(",", $base_string);
            $imageData = base64_decode(end($tmp));
            $bucket = AWS_BUCKET;
            $option = unserialize(AWS);
            $s3Client = new Aws\S3\S3Client($option);
            $result = $s3Client->upload($bucket, $filename, $imageData, 'public-read');
            if($result['@metadata']['statusCode']==200){
                return $result->get('ObjectURL');
            }else{
                return false;
            }
        }else{
            return false;
        }

    }


    public static function get_message_stats_array($thin_app_id,$user_id,$message_id,$action_type){

        $connection = ConnectionUtil::getConnection();
        $action_value = $action_type . "-" . $user_id;
        $action_array =array();
        if (!$action_array = WebservicesFunction::readJson("post_action_$message_id", "doctor_blogs",false)) {
            $query = "select (CONCAT_WS('-',action_type, action_by)) as value from  message_actions where message_id = $message_id";
            $data_list = $connection->query($query);
            if ($data_list->num_rows) {
                $action_array = mysqli_fetch_all($data_list,MYSQLI_ASSOC);
                $action_arr = implode(',',array_column($action_array,'value'));
                if (!empty($action_array)) {
                    WebservicesFunction::createJson("post_action_$message_id", $action_arr,"CREATE","doctor_blogs",true);
                } else {
                    $action_array = array();
                }
            }

        }else{
            $action_array = explode(',',$action_array);
        }
        return $action_array;
    }

    public static function is_dob_editable($thin_app_id,$child_id)
    {
        $connection = ConnectionUtil::getConnection();
        $query = "select c.has_vaccination, c.id,dob_editable, IF(cv.id IS NULL,'NO','YES') has_updated_vaccine ,IF(cg.id IS NULL,'NO','YES') has_child_growth, IF(sp_cv.id IS NULL,'NO','YES') has_special_vaccination from childrens as c left join child_vaccinations as cv on cv.children_id = c.id and ( cv.status = 'DONE' OR cv.reschedule_date IS NOT NULL ) and cv.category_type = 'CHILD VACCINATION' left join child_vaccinations as sp_cv on sp_cv.children_id = c.id and sp_cv.category_type = 'SPECIAL/ADOLESCENT' left join child_growths as cg on cg.children_id = c.id and cg.show_into_graph ='YES' where c.id = $child_id group by c.id";
        $data_list = $connection->query($query);
        if($data_list->num_rows) {
            $action_array = mysqli_fetch_assoc($data_list);
            if($action_array['has_vaccination']=="YES"){
                if($action_array['dob_editable']=="YES"){
                    if($action_array['has_special_vaccination'] == 'NO' && $action_array['has_updated_vaccine'] == 'NO' &&  $action_array['has_child_growth'] == 'NO'){
                        return true;
                    }else{
                        $sql = "update childrens  set dob_editable =? where id = ? and thinapp_id=?";
                        $status = "NO";
                        $stmt_child = $connection->prepare($sql);
                        $stmt_child->bind_param('sss', $status, $child_id,$thin_app_id);
                        $stmt_child->execute();
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    public static function deleteUserCache($is_support_user,$user_id,$mobile,$thin_app_id){

        if($is_support_user == "YES"){
            WebservicesFunction::deleteJson(array(
                "support_user_token",
                "support_user"
            ));
        }
        /* this files are genrated form get_user_by_id and get_user_by_mobile custom.php file*/
        $file_list = array(
            Custom::encrypt_decrypt('encrypt',"user_$user_id"),
            Custom::encrypt_decrypt('encrypt',"user_$mobile"."_$thin_app_id"),
            Custom::encrypt_decrypt('encrypt',"app_user_$thin_app_id")
        );
        WebservicesFunction::deleteJson($file_list,"user");
        /* delete file when user log    in*/


    }

    public static function encrypt_decrypt($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = SECURITY_KEY;
        $secret_iv = SECURITY_IV;
        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

    public static function get_appointment_tracker_data($thin_app_id, $request_from = "SERVICE",$mobile=null,$doctor_id =0){

        $condition = $having = "";


        if(($request_from == "SERVICE" && !empty($thin_app_id)) || $request_from =="CRON_JOB"){
            if(!empty($thin_app_id) && $request_from == "SERVICE"){
                $condition .= " and acss.thinapp_id = $thin_app_id ";
            }
            if(!empty($doctor_id)){
                $condition .= " and acss.appointment_staff_id = $doctor_id ";
            }

            if(!empty($mobile)){
                $having = " and (app_cus.mobile = '$mobile' OR c.mobile = '$mobile')";
            }

            $query = "select IFNULL(app_cat.name,'') as doctor_category, acss.appointment_datetime, acss.id, acss.thinapp_id, app_ser.service_slot_duration, acss.appointment_address_id as address_id, asa.from_time, asa.to_time, app_sta.id as doctor_id, app_sta.name as doctor_name, app_sta.profile_photo, IFNULL(app_cus.first_name,c.child_name) as customer_name, acss.appointment_datetime, u.firebase_token,IFNULL(app_cus.mobile,c.mobile) as mobile, acss.queue_number, acss.slot_time as slot, acss.status from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id left join  appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id left join  childrens as c on c.id = acss.children_id join appointment_staff_addresses as asa on asa.appointment_staff_id = acss.appointment_staff_id  and asa.appointment_address_id = acss.appointment_address_id left join users as u on (( u.mobile = app_cus.mobile and u.thinapp_id = app_cus.thinapp_id) OR ( (c.mobile = u.mobile OR c.parents_mobile = u.mobile ) and c.thinapp_id = u.thinapp_id) ) join appointment_services as app_ser on app_ser.id = acss.appointment_service_id join user_enabled_fun_permissions as uefp on uefp.thinapp_id = acss.thinapp_id and uefp.user_functionality_type_id = ( select id from  user_functionality_types as uft where uft.label_key = 'SHOW_USER_TO_APPOINTMENT_TRACKER' and uft.status = 'Y' ) and uefp.permission = 'YES'  left join appointment_categories as app_cat on app_cat.id = app_sta.appointment_category_id where (  DATE(acss.appointment_datetime) = DATE(NOW())) and  acss.skip_tracker = 'NO' AND  ( acss.status ='CONFIRM' OR acss.status ='NEW' OR acss.status ='RESCHEDULE' OR acss.status ='CLOSED'  ) and  TIME(NOW()) >= TIME(STR_TO_DATE(CONCAT_WS(' ','01/01/2000',from_time), '%d/%m/%Y %h:%i %p')) AND TIME(NOW()) <= TIME(STR_TO_DATE(CONCAT_WS(' ','01/01/2000',to_time), '%d/%m/%Y %h:%i %p')) $condition $having order by acss.appointment_datetime asc, acss.thinapp_id asc";
            //echo $query;die;
            $connection = ConnectionUtil::getConnection();
            $sub_data = $connection->query($query);
            if ($sub_data->num_rows) {
                $return_array=array();
                $data = mysqli_fetch_all($sub_data,MYSQLI_ASSOC);

                foreach ($data as $key => $value){
                    $return_array[$value['doctor_id']]['address_id'] = $value['address_id'];
                    $return_array[$value['doctor_id']]['service_slot_duration'] = $value['service_slot_duration'];
                    $return_array[$value['doctor_id']]['doctor_category'] = $value['doctor_category'];
                    $return_array[$value['doctor_id']]['doctor_name'] = $value['doctor_name'];
                    $return_array[$value['doctor_id']]['profile_photo'] = $value['profile_photo'];
                    $return_array[$value['doctor_id']]['thinapp_id'] = $value['thinapp_id'];
                    $return_array[$value['doctor_id']]['from_time'] = $value['from_time'];
                    $return_array[$value['doctor_id']]['to_time'] = $value['to_time'];
                    $return_array[$value['doctor_id']]['doctor_data'][$value['slot']] = $value;
                    $return_array[$value['doctor_id']]['appointment_datetime'] = $value['appointment_datetime'];
                }
                return $return_array;
            }else{
                return false;
            }
        }
        return false;
    }

    public static function get_upcoming_appointment_user_token($thin_app_id,$doctor_id,$address_id,$appointment_id) {

        $query = "select u.firebase_token from appointment_customer_staff_services as acss join appointment_customers as ac on acss.appointment_customer_id = ac.id join users as u on u.id = ac.user_id where ( acss.thinapp_id = $thin_app_id and acss.status IN ('NEW','CONFIRM','RESCHEDULE') and acss.appointment_datetime > NOW() and acss.appointment_staff_id = $doctor_id and acss.appointment_address_id = $address_id and u.firebase_token IS NOT NULL and u.firebase_token !='' and DATE(acss.appointment_datetime) = DATE(NOW())) OR acss.id = $appointment_id";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $firebase_token = mysqli_fetch_all($list, MYSQLI_ASSOC);
            return array_values(array_column($firebase_token,'firebase_token'));

        }else{
            return false;
        }
    }

    public static function marge_slot_array($tracker, $thin_app_id){
        $final_array =array();
        $get_appointment_slot = WebservicesFunction::get_booking_slots(date('Y-m-d'),$tracker['doctor_id'],$tracker['service_slot_duration'],$thin_app_id, $tracker['address_id'],true);
        foreach ($get_appointment_slot as $time_slot  => $status){
            $queue_number = array_search($time_slot, array_keys($get_appointment_slot));
            $queue_number = ($queue_number !== false) ? $queue_number + 1 : 0;

            if($status=="BOOKED" || $status == "AVAILABLE"){
                $status="PENDING";
                $final_array[$time_slot] = array(
                    'customer_name'=>'',
                    'mobile'=>'',
                    'token_number'=>$queue_number,
                    'time_slot'=>$time_slot,
                    'status'=>$status
                );
            }

        }

        return $final_array;
    }


    public static function get_doctor_reports($thin_app_id,$day_type,$download_count, $from_date,$to_date, $query_for = 'DOCTOR'){

        $from_date = date('Y-m-d',strtotime($from_date));
        $to_date = date('Y-m-d',strtotime($to_date));
        $label = " DATE(aus.created) ";
        $group_by = " DATE(aus.created) ";
        $condition = " DATE(aus.created) >= '$from_date' and DATE(aus.created) <= '$to_date' ";
        $inner_thin_app = "";
        if(!empty($thin_app_id)){
            $condition .= " AND aus.thin_app_id = $thin_app_id ";
            $inner_thin_app = " and in_u.thinapp_id = ".$thin_app_id;

        }

        if($day_type=="WEEK"){
            $label = " CONCAT_WS(' - ',DATE(DATE_ADD(aus.created, INTERVAL(1-DAYOFWEEK(aus.created)) DAY)), DATE(DATE_ADD(aus.created, INTERVAL(7-DAYOFWEEK(aus.created)) DAY))) ";
            $group_by = " WEEK(aus.created) ";
        }else if($day_type=="MONTH"){
            $label = " DATE_FORMAT(aus.created, '%b-%Y')  ";
            $group_by = " MONTH(aus.created) ";
        }else if($day_type=="YEAR"){
            $label = " DATE_FORMAT(aus.created, '%Y')  ";
            $group_by = " YEAR(aus.created) ";
        }
        $user_role = 1;
        if($query_for=='DOCTOR'){
            $user_role = 5;
        }
        $query = "select ROUND((count(DISTINCT(aus.user_id))/ (select count(per_u.thinapp_id) from users as per_u where per_u.role_id = $user_role  and per_u.thinapp_id IN ( select in_u.thinapp_id from users as in_u where in_u.role_id = 1 $inner_thin_app  group by in_u.thinapp_id having count(in_u.id) >= $download_count ) ))*100,2) as total_count, $label as label  from app_user_statics as aus  join users as u on aus.user_id = u.id and u.role_id = $user_role and aus.thin_app_id = ( select in_u.thinapp_id from users as in_u where in_u.thinapp_id = aus.thin_app_id and in_u.role_id = 1 having count(in_u.id) >= $download_count ) where $condition group by $group_by ";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $data = mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            $return_array =array();

            if($day_type == "DAILY"){
                $range_of_dates = Custom::createDateRange($from_date,$to_date,'Y-m-d');
                foreach($range_of_dates as $key => $label){
                    $return_array['data'][$key] =(int)0;
                    $return_array['label'][$key] =$label;
                    foreach($data as $key_inner =>$value){
                        if($value['label'] ==  $label){
                            $return_array['data'][$key] =$value['total_count'];
                            $return_array['label'][$key] =$value['label'];
                            break;
                        }

                    }
                }



            }else if($day_type == "MONTH"){
                $start    = new DateTime($from_date);
                $start->modify('first day of this month');
                $end      = new DateTime($to_date);
                $end->modify('first day of next month');
                $interval = DateInterval::createFromDateString('1 month');
                $period   = new DatePeriod($start, $interval, $end);


                foreach($period as $key => $date){
                    $label = $date->format("M-Y");
                    $return_array['data'][$key] =(int)0;
                    $return_array['label'][$key] =$label;
                    foreach($data as $key_inner =>$value){
                        if($value['label'] ==  $label){
                            $return_array['data'][$key] =$value['total_count'];
                            $return_array['label'][$key] =$value['label'];
                            break;
                        }

                    }
                }
            }else if($day_type == "YEAR"){
                $getRangeYear   = range(gmdate('Y', strtotime($from_date)), gmdate('Y', strtotime($to_date)));

                foreach($getRangeYear as $key => $label){

                    $return_array['data'][$key] =(int)0;
                    $return_array['label'][$key] =$label;
                    foreach($data as $key_inner =>$value){
                        if($value['label'] ==  $label){
                            $return_array['data'][$key] =$value['total_count'];
                            $return_array['label'][$key] =$value['label'];
                            break;
                        }

                    }
                }
            }else{
                foreach($data as $key =>$value){
                    $return_array['data'][$key] =$value['total_count'];
                    $return_array['label'][$key] =$value['label'];
                }
            }

            return $return_array;
        }else{
            return false;
        }

    }


    public static function createDateRange($startDate, $endDate, $format = "Y-m-d")
    {
        $day = 86400;
        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);
        $numDays = round(($endTime - $startTime) / $day) + 1;
        $days = array();

        for ($i = 0; $i < $numDays; $i++) {
            $days[] = date($format, ($startTime + ($i * $day)));
        }

        return $days;
    }

    public static function getTimeSlot($interval, $start_time, $end_time,$format)
    {
        $start = new DateTime($start_time);
        $end = new DateTime($end_time);
        $startTime = $start->format($format);
        $endTime = $end->format($format);
        $i=0;
        $time = [];
        while(strtotime($startTime) <= strtotime($endTime)){
            $start = $startTime;
            $end = date($format,strtotime('+'.$interval,strtotime($startTime)));
            $startTime = date($format,strtotime('+'.$interval,strtotime($startTime)));
            $i++;
            if(strtotime($startTime) <= strtotime($endTime)){
                if(empty($time)){
                    $time[] = $start;
                }
                $time[] = $end;
            }
        }
        return $time;
    }

    public static function load_tiger_report($download_count,$avg_count){
        $query = "select t.id as thinapp_id, t.name as app_name,MIN(DATE(t.created)) as app_start_date, MAX(DATE(NOW())) as app_last_date, datediff(MAX(DATE(NOW())), MIN(DATE(t.created))) as app_total_days, count(u.id) as total_users, ROUND( count(u.id) /   datediff(MAX(DATE(NOW())), MIN(DATE(t.created)))  ) as app_per_day_user from users as u join thinapps as t on u.thinapp_id= t.id where t.category_name = 'DOCTOR' AND u.thinapp_id > 0 GROUP BY  u.thinapp_id having total_users >= $download_count OR  ( app_per_day_user >= $avg_count and total_users < $download_count ) order by  app_per_day_user desc";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            return mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
        }else{
            return false;
        }
    }

    public static function load_tiger_potential_report($download_count_from,$download_count_to,$avg_count){
        $query = "select t.id as thinapp_id, t.name as app_name,MIN(DATE(t.created)) as app_start_date, MAX(DATE(NOW())) as app_last_date, datediff(MAX(DATE(NOW())), MIN(DATE(t.created))) as app_total_days, count(u.id) as total_users, ROUND( count(u.id) /   datediff(MAX(DATE(NOW())), MIN(DATE(t.created)))  ) as app_per_day_user from users as u join thinapps as t on u.thinapp_id= t.id where t.category_name = 'DOCTOR' AND u.thinapp_id > 0 GROUP BY  u.thinapp_id having (total_users >= $download_count_from and total_users <= $download_count_to ) OR  ( app_per_day_user = $avg_count and total_users < $download_count_from ) order by  app_per_day_user desc";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            return mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
        }else{
            return false;
        }
    }


    public static function load_user_download_graph($thin_app_id,$from_date,$to_date){

        $from_date = date('Y-m-d',strtotime($from_date));
        $to_date = date('Y-m-d',strtotime($to_date));
        $condition = " DATE(u.created) >= '$from_date' and DATE(u.created) <= '$to_date' ";
        if(!empty($thin_app_id)){
            $condition .= " AND u.thinapp_id = $thin_app_id ";
        }

        $query = "SELECT DATE(u.created) as label, COUNT(u.id) as total_count from users as u where $condition group by DATE(u.created)";

        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $data = mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            $return_array =array();
            $range_of_dates = Custom::createDateRange($from_date,$to_date,'Y-m-d');
            foreach($range_of_dates as $key => $label){
                $return_array['data'][$key] =(int)0;
                $return_array['label'][$key] =$label;
                foreach($data as $key_inner =>$value){
                    if($value['label'] ==  $label){
                        $return_array['data'][$key] =$value['total_count'];
                        $return_array['label'][$key] =$value['label'];
                        break;
                    }

                }
            }
            return $return_array;
        }else{
            return false;
        }

    }

    public static function create_and_share_folder($thin_app_id, $share_with,$username,$patient_type ='CUSTOMER',$patient_id=0,$auto_share =true)
    {

        $share_with = Custom::create_mobile_number($share_with);
        if($share_with){
            $app_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
            if($patient_id > 0){
                $user_folder_id = Custom::check_patient_default_folder($thin_app_id, $patient_type, $patient_id);
            }else{
                $user_folder_id = Custom::is_patient_default_share_folder($thin_app_id, $share_with);
            }

            if ($app_admin_data && $user_folder_id == 0) {
                $data = array();
                $data['thin_app_id'] = $thin_app_id;
                $data['user_id'] = $app_admin_data['id'];
                $data['app_key'] = APP_KEY;
                $data['mobile'] = $app_admin_data['mobile'];
                $data['folder_name'] = $username;
                $data['description'] = "";
                $data['folder_type'] = "PUBLIC";
                $data['file_type'] = "";
                $data['file_name'] = "";
                $data['file_path'] = "";
                $data['file_size'] = "";
                $data['is_instruction_bucket'] = "NO";
                $data['allow_add_file'] = "YES";
                $data['folder_add_from_number'] = $share_with;
                if($patient_id > 0){
                    if($patient_type=='CUSTOMER'){
                        $data['appointment_customer_id'] = $patient_id;
                    }else if($patient_type=='CHILDREN'){
                        $data['children_id'] = $patient_id;
                    }
                }

                $result = WebservicesFunction::add_folder($data,false,false,true);
                $result = json_decode($result, true);

                $auto_share = false;
                $auto_share = Custom::check_user_permission($thin_app_id,'AUTOMATIC_FOLDER_SHARE',true);
                if($auto_share == "YES"){
                    $auto_share = true;
                }

                if ($result['status'] == 1 && $auto_share === true) {
                    $share_data = array();
                    $drive_file_id = 0;
                    $drive_folder_id = $result['folder_id'];
                    $share_data['thin_app_id'] = $thin_app_id;
                    $share_data['user_id'] = $app_admin_data['id'];
                    $share_data['app_key'] = APP_KEY;
                    $share_with_mobile[0]['mobile'] = $share_with;
                    $share_data['share_with_mobile'] = $share_with_mobile;
                    $share_data['share_from_mobile'] = $app_admin_data['mobile'];;
                    $share_data['username'] = $username;
                    $share_data['drive_file_id'] = $drive_file_id;
                    $share_data['drive_folder_id'] = $drive_folder_id;
                    $share_data['channel_id'] = 0;
                    $share_data['shared_object'] = 'FOLDER';
                    $share_data['status'] = 'SHARED';
                    $result = json_decode(WebservicesFunction::add_share($share_data, true,true),true);
                    $result['folder_id'] = $drive_folder_id;
                    return json_encode($result);
                }else{
                    return json_encode($result);
                }
            }else{
                $result['status'] = 0;
                $result['message'] = "Folder already exist";
                $result['folder_id'] =$user_folder_id;
                return json_encode($result);
            }
        }else{
            $result['status'] = 0;
            $result['message'] = "Invalid patient mobile";
            return json_encode($result);
        }

    }


    public static function is_patient_default_share_folder($thn_app_id,$mobile){
        $query = "select count(id) as cnt from drive_folders  where  folder_add_from_number = '$mobile' and thinapp_id = $thn_app_id and status = 'ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['cnt'];
        }else{
            return false;
        }
    }

    public static function get_patient_folder_id($thin_app_id, $patient_id, $patient_type = "CUSTOMER"){

        $label = ' appointment_customer_id ';
        if($patient_type=='CHILDREN'){
            $label = " children_id ";
        }
        $query = "select id from drive_folders  where thinapp_id = $thin_app_id and $label = $patient_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['id'];
        }else{
            return 0;
        }
    }



    public static function get_patient_folder($thin_app_id, $patient_id, $patient_type = "CUSTOMER"){

        $label = ' appointment_customer_id ';
        if($patient_type=='CHILDREN'){
            $label = " children_id ";
        }
        $query = "select * from drive_folders  where thinapp_id = $thin_app_id and $label = $patient_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);

        }else{
            return 0;
        }
    }

    public static function load_prescription_list($thin_app_id,$request_for="PRESCRIPTION_TAG",$gender =false){

        $condition = " and type ='$request_for' and status ='ACTIVE'";
        if($gender){
            $condition .= " and gender = '$gender'";
        }
        $query = "select id,name,description,status,thinapp_id,gender from prescription_tags where thinapp_id =$thin_app_id $condition ";

        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $final_array =  mysqli_fetch_all($service_message_list,MYSQL_ASSOC);
            $name = array();
            foreach($final_array as $key => $value){
                $name[] = $value['name'].'-'.$value['gender'];
            }
            $name = "'".implode("','",$name)."'";
            $query = "select id,name,description,status,thinapp_id,gender from prescription_tags where  thinapp_id = 0 $condition and CONCAT_WS('-',name,gender) NOT IN($name)";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $tmp = mysqli_fetch_all($service_message_list, MYSQL_ASSOC);
                $cnt =count($final_array);
                foreach ($tmp as $key => $value){

                    $final_array[++$cnt] = $value;
                }
            }
            return array_values($final_array);
        }else{
            $query = "select id,name,description,status,thinapp_id,gender from prescription_tags where  thinapp_id = 0 $condition";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                return mysqli_fetch_all($service_message_list, MYSQL_ASSOC);
            }else{
                return false;
            }
        }
    }



    public static function get_app_banners($thin_app_id){

        $file_name = Custom::encrypt_decrypt('encrypt',"banner_$thin_app_id");
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"user"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select index_number, path, url from banners  where  thinapp_id = $thin_app_id and status = 'ACTIVE' order by index_number asc";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_all($service_message_list,MYSQL_ASSOC);
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","banner");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_app_icons($thin_app_id){

        $file_name = "icon_$thin_app_id";
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"icon"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select logo, ic_launcher , left_drawer as left_drawer_bar from thinapps where  id = $thin_app_id";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","icon");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_appointment_notification_data($appointment_id)
    {
        $query = "select acss.appointment_datetime, acss.queue_number, IF(acss.appointment_customer_id = 0,acss.children_id,acss.appointment_customer_id) as appointment_customer_id, acss.appointment_service_id, acss.appointment_staff_id, app_sta.name as staff_name, acss.appointment_address_id as address_id, app_sta.mobile as staff_mobile,  IFNULL(app_cus.first_name,c.child_name) as cus_name, IFNULL(app_cus.mobile,c.mobile) as customer_mobile,  IFNULL(app_cus.user_id,c.user_id) as customer_user_id, app_sta.name as staff_name, app_sta.user_id as staff_user_id, app_ser.name as ser_name from appointment_customer_staff_services as acss join  appointment_staffs as app_sta on acss.appointment_staff_id = app_sta.id  left join  appointment_customers as app_cus on app_cus.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id join  appointment_services as app_ser on acss.appointment_service_id = app_ser.id where acss.id = $appointment_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function get_default_and_child_folder($thin_app_id, $mobile,$username)
    {
        $query = "select  df.id as folder_id, df.folder_name, IFNULL(u.username,'$username') as name,  df.folder_add_from_number as mobile, '' as patient_id from drive_folders as df left join users u on u.mobile = df.folder_add_from_number and u.thinapp_id = df.thinapp_id  where df.folder_add_from_number = '$mobile' and df.thinapp_id = $thin_app_id and df.status = 'ACTIVE' UNION select  df.id as folder_id, df.folder_name, ac.first_name as name,  df.folder_add_from_number as mobile, '' as patient_id from appointment_customers as ac join drive_folders as df on df.folder_add_from_number = ac.mobile and df.thinapp_id = ac.thinapp_id left join users u on u.mobile = df.folder_add_from_number and u.thinapp_id = df.thinapp_id  where df.folder_add_from_number = '$mobile' and df.thinapp_id = $thin_app_id and df.status = 'ACTIVE' UNION select  df.id as folder_id, df.folder_name, c.child_name as name,  IF(c.mobile='$mobile',c.mobile,c.parents_mobile) as mobile , c.child_number as patient_id from drive_folders as df  join childrens as c on c.child_number = df.child_number  and df.thinapp_id = c.thinapp_id and c.status = 'ACTIVE' AND ((c.mobile = '$mobile' OR c.parents_mobile='$mobile') and c.thinapp_id = $thin_app_id ) where df.thinapp_id = $thin_app_id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQL_ASSOC);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function has_special_character($string){
        if(preg_match('/[\'^Ã‚Â£$%&*()}{@#~?><>,|=_+Ã‚Â¬-]/', $string)){
            return true;
        }return false;
    }

    public static function get_instamojo_credential($thin_app_id)
    {
        $query = "select instamojo_salt, instamojo_api_key, instamojo_api_secret, private_api_key, private_auth_key from thinapps where id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

    public  static function set_layout_array($top="",$bottom="",$left= "",$right=""){
        return array(
            'TOP'=>$top,
            'BOTTOM'=>$bottom,
            'LEFT'=>$left,
            'RIGHT'=>$right

        );
    }

    public static function create_template_array($data_array,$create_title_string=false){
        $count_array = 0;
        foreach($data_array['template_array'] as $main_key => $main_array){
            foreach($main_array as $key => $tmp_array) {
                if (count($tmp_array['selected_tag']) > $count_array) {
                    $count_array = count($tmp_array['selected_tag']);
                }
            }
        }

        $tmp_array=array();

        if($create_title_string === true){
            foreach($data_array['template_array'] as $key => $main_array){
                foreach($main_array as $key_2 => $step_data) {
                    $return =array();
                    $str = implode(",",array_column($step_data['selected_tag'],'tag_title'));
                    $return['tag_title']  = !empty($str)?$str:"-";
                    $return['tag_id']  = 0;
                    $return['template_id']  = 0;
                    unset($data_array['template_array'][$key_2]['selected_tag']);
                    $data_array['template_array'][$key_2]['selected_tag'][0] = $return;
                }

            }
        }else{
            foreach($data_array['template_array'] as $key => $main_array){
                foreach($main_array as $key_2 => $step_data) {
                    $tot_cnt = $count_array;
                    $diff_cnt = $tot_cnt - $add_cnt;
                    for ($cnt = 0; $cnt < $diff_cnt; $cnt++) {
                        $count = $add_cnt + $cnt;
                        $data_array['template_array'][$key_2]['selected_tag'][$count]['template_id'] = 0;
                        $data_array['template_array'][$key_2]['selected_tag'][$count]['tag_id'] = 0;
                        $data_array['template_array'][$key_2]['selected_tag'][$count]['tag_title'] = '-';
                    }
                }
            }

        }


        //$tmp_array = json_decode(json_decode($list['template_array'],true),true)['template_array'];





        $res = $data_array;
        return json_encode($res);

    }

    public static function getMessageMultiImageData($message_file_url){
        if(!empty($message_file_url)){
            $tmp_array = explode(",",$message_file_url);
            $file_array = array();
            foreach($tmp_array as $key => $file){
                $break = explode("##",$file);
                $file_array[$key]['path'] =  $break[2];
                $file_array[$key]['file_name'] =  WebservicesFunction::get_file_name($break[2]);
                $file_array[$key]['original_filename'] =  $break[1];
                $file_array[$key]['type'] =  $break[0];
            }
            return $file_array;
        }else{
            return false;
        }
    }


    public static function get_doctor_reports_day_wise($download_count, $from_date,$to_date,$day_type, $role_id=1){

        $from_date = date('Y-m-d',strtotime($from_date));
        $to_date = date('Y-m-d',strtotime($to_date));

        $label = "DATE(count_1.created)";
        $inner_group_by = "DATE(c1.created)";
        $outer_group_by = "DATE(count_1.created)";
        if($day_type=="WEEK"){
            $label = " CONCAT_WS(' - ', DATE_FORMAT(DATE((count_1.created - INTERVAL WEEKDAY(count_1.created) Day)),'%d-%m-%Y'), DATE_FORMAT(DATE(DATE_ADD((SELECT count_1.created - INTERVAL (WEEKDAY(count_1.created)+1)DAY),INTERVAL 7 DAY)),'%d-%m-%Y')) ";

            $inner_group_by = " DATE(c1.created) ";
            $outer_group_by = " WEEK(count_1.created,1) ";
        }else if($day_type=="MONTH"){
            $label = " DATE_FORMAT(count_1.created, '%b-%Y')  ";
            $inner_group_by = " DATE(c1.created) ";
            $outer_group_by = " MONTH(count_1.created) ";
        }else if($day_type=="YEAR"){
            $label = " DATE_FORMAT(count_1.created, '%Y')  ";
            $inner_group_by = " DATE(c1.created) ";
            $outer_group_by = " YEAR(count_1.created) ";
        }


        $query = "select count(*) as active_users from users where app_installed_status = 'INSTALLED' AND role_id = $role_id";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $data = mysqli_fetch_assoc($sub_data);
            $active_users = $data['active_users'];
        }

        $query = "select '1' as day, sum(count_1.tot) as total, $label as on_date from (select count(c1.user_id) as tot, c1.id,c1.created from app_user_statics as c1 join users as u on c1.user_id = u.id and u.role_id = $role_id  where DATE(c1.created) >= '$from_date' and DATE(c1.created) <= '$to_date' and c1.thin_app_id IN (select in_u.thinapp_id from users as in_u where in_u.role_id = 1 group by in_u.thinapp_id having count(in_u.id) >= $download_count) group by c1.user_id ,$inner_group_by having count(c1.user_id)=1) as count_1 group by $outer_group_by UNION ALL select '2' as day, count(count_1.tot) as total, $label as on_date from (select count(c1.user_id) as tot, c1.id,c1.created from app_user_statics as c1 join users as u on c1.user_id = u.id and u.role_id = $role_id where DATE(c1.created) >= '$from_date' and DATE(c1.created) <= '$to_date' and c1.thin_app_id IN (select in_u.thinapp_id from users as in_u where in_u.role_id = 1 group by in_u.thinapp_id having count(in_u.id) >= $download_count)  group by c1.user_id, $inner_group_by having count(c1.user_id)=2) as count_1 group by $outer_group_by UNION ALL select '3' as day, count(count_1.tot) as total, $label as on_date from (select count(c1.user_id) as tot, c1.id,c1.created from app_user_statics as c1 join users as u on c1.user_id = u.id and u.role_id = $role_id where DATE(c1.created) >= '$from_date' and DATE(c1.created) <= '$to_date' and c1.thin_app_id IN (select in_u.thinapp_id from users as in_u where in_u.role_id = 1 group by in_u.thinapp_id having count(in_u.id) >= $download_count) group by c1.user_id, $inner_group_by having count(c1.user_id)=3) as count_1 group by $outer_group_by UNION ALL select '4' as day, count(count_1.tot) as total, $label as on_date from (select count(c1.user_id) as tot, c1.id,c1.created from app_user_statics as c1 join users as u on c1.user_id = u.id and u.role_id = $role_id where DATE(c1.created) >= '$from_date' and DATE(c1.created) <= '$to_date' and c1.thin_app_id IN (select in_u.thinapp_id from users as in_u where in_u.role_id = 1 group by in_u.thinapp_id having count(in_u.id) >= $download_count) group by c1.user_id, $inner_group_by having count(c1.user_id)=4) as count_1 group by $outer_group_by UNION ALL select '5' as day, count(count_1.tot) as two_day, $label as total from (select '5' as tot, c1.id,c1.created from app_user_statics as c1 join users as u on c1.user_id = u.id and u.role_id = $role_id where DATE(c1.created) >= '$from_date' and DATE(c1.created) <= '$to_date' and c1.thin_app_id IN (select in_u.thinapp_id from users as in_u where in_u.role_id = 1 group by in_u.thinapp_id having count(in_u.id) >= $download_count) group by c1.user_id, $inner_group_by having count(c1.user_id)>=5) as count_1 group by $outer_group_by";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $data = mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            $return_array =$all_dates =array();
            if($day_type=='DAILY') {
                $range_of_dates = Custom::createDateRange($from_date, $to_date, 'Y-m-d');
                foreach ($range_of_dates as $key => $date) {
                    if (!array_key_exists($date, $return_array)) {
                        $return_array[$date][1] = 0;
                        $return_array[$date][2] = 0;
                        $return_array[$date][3] = 0;
                        $return_array[$date][4] = 0;
                        $return_array[$date][5] = 0;
                    }
                }
                foreach ($data as $key => $value) {

                    if (empty($return_array[$value['on_date']][1])) {
                        $return_array[$value['on_date']][1] = 0;
                    }
                    if (empty($return_array[$value['on_date']][2])) {
                        $return_array[$value['on_date']][2] = 0;
                    }
                    if (empty($return_array[$value['on_date']][3])) {
                        $return_array[$value['on_date']][3] = 0;
                    }
                    if (empty($return_array[$value['on_date']][4])) {
                        $return_array[$value['on_date']][4] = 0;
                    }
                    if (empty($return_array[$value['on_date']][5])) {
                        $return_array[$value['on_date']][5] = 0;
                    }

                    $total = $value['total']."(".round((($value['total']/$active_users)*100),2)."%)";
                    $return_array[$value['on_date']][$value['day']] = $total;
                }
            }else if($day_type == "WEEK"){
                $range_of_dates = Custom::getWeekDateRange($from_date, $to_date);
                foreach ($range_of_dates as $key => $date) {
                    if (!array_key_exists($date, $return_array)) {
                        $return_array[$date][1] = 0;
                        $return_array[$date][2] = 0;
                        $return_array[$date][3] = 0;
                        $return_array[$date][4] = 0;
                        $return_array[$date][5] = 0;
                    }
                }
                foreach ($data as $key => $value) {

                    if (empty($return_array[$value['on_date']][1])) {
                        $return_array[$value['on_date']][1] = 0;
                    }
                    if (empty($return_array[$value['on_date']][2])) {
                        $return_array[$value['on_date']][2] = 0;
                    }
                    if (empty($return_array[$value['on_date']][3])) {
                        $return_array[$value['on_date']][3] = 0;
                    }
                    if (empty($return_array[$value['on_date']][4])) {
                        $return_array[$value['on_date']][4] = 0;
                    }
                    if (empty($return_array[$value['on_date']][5])) {
                        $return_array[$value['on_date']][5] = 0;
                    }
                    $total = $value['total']."(".round((($value['total']/$active_users)*100),2)."%)";
                    $return_array[$value['on_date']][$value['day']] = $total;
                }
            }else if($day_type == "MONTH"){
                $range_of_dates = Custom::getMonthListFromTwoDates($from_date, $to_date);
                foreach ($range_of_dates as $key => $date) {
                    if (!array_key_exists($date, $return_array)) {
                        $return_array[$date][1] = 0;
                        $return_array[$date][2] = 0;
                        $return_array[$date][3] = 0;
                        $return_array[$date][4] = 0;
                        $return_array[$date][5] = 0;
                    }
                }
                foreach ($data as $key => $value) {

                    if (empty($return_array[$value['on_date']][1])) {
                        $return_array[$value['on_date']][1] = 0;
                    }
                    if (empty($return_array[$value['on_date']][2])) {
                        $return_array[$value['on_date']][2] = 0;
                    }
                    if (empty($return_array[$value['on_date']][3])) {
                        $return_array[$value['on_date']][3] = 0;
                    }
                    if (empty($return_array[$value['on_date']][4])) {
                        $return_array[$value['on_date']][4] = 0;
                    }
                    if (empty($return_array[$value['on_date']][5])) {
                        $return_array[$value['on_date']][5] = 0;
                    }

                    $total = $value['total']."(".round((($value['total']/$active_users)*100),2)."%)";
                    $return_array[$value['on_date']][$value['day']] = $total;
                }
            }else if($day_type == "YEAR"){
                $range_of_dates = Custom::getYearListFromTwoDates($from_date, $to_date);
                foreach ($range_of_dates as $key => $date) {
                    if (!array_key_exists($date, $return_array)) {
                        $return_array[$date][1] = 0;
                        $return_array[$date][2] = 0;
                        $return_array[$date][3] = 0;
                        $return_array[$date][4] = 0;
                        $return_array[$date][5] = 0;
                    }
                }
                foreach ($data as $key => $value) {

                    if (empty($return_array[$value['on_date']][1])) {
                        $return_array[$value['on_date']][1] = 0;
                    }
                    if (empty($return_array[$value['on_date']][2])) {
                        $return_array[$value['on_date']][2] = 0;
                    }
                    if (empty($return_array[$value['on_date']][3])) {
                        $return_array[$value['on_date']][3] = 0;
                    }
                    if (empty($return_array[$value['on_date']][4])) {
                        $return_array[$value['on_date']][4] = 0;
                    }
                    if (empty($return_array[$value['on_date']][5])) {
                        $return_array[$value['on_date']][5] = 0;
                    }

                    $total = $value['total']."(".round((($value['total']/$active_users)*100),2)."%)";
                    $return_array[$value['on_date']][$value['day']] = $total;
                }
            }
            $return = array(
                'list'=>$return_array,
                'active_users'=>$active_users
            );
            return $return;
        }else{
            return false;
        }





    }

    public static function getYearListFromTwoDates($from_date, $to_date){
        $start_year    = new DateTime($from_date);
        $start_year    = $start_year->format("Y");
        $end_year    = new DateTime($to_date);
        $end_year    = $end_year->format("Y");
        $return = array();
        if($start_year == $end_year){
            $return[] = $start_year;
        }else{

            for($year = $start_year; $year <= $end_year; $year++) {
                $return[] =  $year;
            }
        }
        return $return;



    }
    public static function getMonthListFromTwoDates($from_date, $to_date){
        $start    = new DateTime($from_date);
        $start->modify('first day of this month');
        $end      = new DateTime($to_date);
        $end->modify('first day of next month');
        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);
        $return = array();
        foreach ($period as $dt) {
            $return[] =  $dt->format("M-Y");
        }
        return $return;
    }
    public static function getDateRangeForWeek($date){

        $date = new DateTime($date);
        $week = $date->format("W");
        $year = $date->format("Y");
        $dateTime = new DateTime();
        $dateTime->setISODate($year, $week);
        $result['start_date'] = $start =$dateTime->format('d-m-Y');
        $dateTime->modify('+6 days');
        $result['end_date'] = $end = $dateTime->format('d-m-Y');
        return $start .' - '.$end;
    }
    public static function getWeekDateRange($start_date, $end_date) {

        $range_array = array();
        $range_of_dates = Custom::createDateRange($start_date, $end_date, 'Y-m-d');

        foreach ($range_of_dates as $key => $date){
            $range = Custom::getDateRangeForWeek($date);;
            $return[$range] = $range;
        }

        return $return;

    }

    public static function convertTimeToMinutes($time_string,$add_second=true){
        if($add_second ===true){
            list($h, $m, $s) = explode(':', $time_string);
            return (($h * 3600) + ($m * 60) + $s)/60;
        }else{
            list($h, $m) = explode(':', $time_string);
            return (($h * 3600) + ($m * 60))/60;
        }


    }


    public static function getNewRefCode($userID){
        return str_shuffle("MENGAGE").$userID;
    }

    public static function configWalletForFirstTime($user_id, $thin_app_id)
    {


        $thinappID = $thin_app_id;
        $userID = $user_id;
        $dateTime = date('Y-m-d H:i:s');

        $chkReferalSetting ="SELECT `id`, `new_user_refer_amount`, `old_user_refer_amount` FROM `wallet_thinapp_settings` WHERE `thinapp_id` = '".$thinappID."' AND `is_enable_referral` = 'YES' LIMIT 1";

        $connection = ConnectionUtil::getConnection();
        $connection->autocommit(false);
        $settingRS = $connection->query($chkReferalSetting);
        if($settingRS->num_rows)
        {

            $refSettingData = mysqli_fetch_assoc($settingRS);
            $settingNewUserReferAmount = $refSettingData['new_user_refer_amount'];
            $settingOldUserReferAmount = $refSettingData['old_user_refer_amount'];




            $user_id = $userID;
            $thinapp_id = $thinappID;
            $referral_code = Custom::getNewRefCode($userID);
            $new_user_refer_amount = $settingNewUserReferAmount;
            $old_user_refer_amount = $settingOldUserReferAmount;
            $has_used_referral_code = 'NO';
            $status = 'ACTIVE';
            $created = $dateTime;
            $modified = $dateTime;
            $updateReferalCode = "INSERT INTO `wallet_user_referral_codes` SET `user_id` = '".$user_id."', `thinapp_id` = '".$thinapp_id."', `referral_code` = '".$referral_code."', 
								`new_user_refer_amount` = '".$new_user_refer_amount."', `old_user_refer_amount` = '".$old_user_refer_amount."', `has_used_referral_code` = '".$has_used_referral_code."', `status` = '".$status."'
								, `created` = '".$created."', `modified` = '".$modified."'";
            $connection->query($updateReferalCode);



            $total_amount = 0;
            $status = 'ACTIVE';

            $insertWalletUsersStr = "INSERT INTO `wallet_users` SET `user_id` = '".$user_id."', `thinapp_id` = '".$thinappID."',
								`total_amount` = '".$total_amount."',`status` = '".$status."',`created` = '".$created."', `modified` = '".$modified."'";
            $connection->query($insertWalletUsersStr);


            $connection->commit();



        }

    }


    public static function refresh_topic_token($user_id,$mobile,$thin_app_id){
        $response = $response_data = array();
        $connection = ConnectionUtil::getConnection();
        $query = "select c.status, c.topic_name, u.firebase_token, t.firebase_server_key from subscribers as sub join channels as c on c.id = sub.channel_id join users as u on sub.app_user_id = u.id join thinapps as t on t.id = sub.app_id where sub.app_user_id = $user_id and sub.mobile = '$mobile' and sub.status = 'SUBSCRIBED' and c.status = 'Y' and sub.app_id =$thin_app_id group by c.id";
        $subscriber = $connection->query($query);
        if ($subscriber->num_rows) {
            $topic_name_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
            $filed = array();
            if (!empty($topic_name_list)) {
                $mh = curl_multi_init();
                $handles = array();
                foreach ($topic_name_list as $key => $subscriber) {
                    if (!empty(FIREBASE_KEY) && !empty($subscriber['firebase_token'])) {
                        $server_key = FIREBASE_KEY;
                        $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                        $fields = array(
                            'to' => '/topics/' . $subscriber['topic_name'],
                            'registration_tokens' => array($subscriber['firebase_token']),
                        );
                        $headers = array(
                            'Authorization:key=' . $server_key,
                            'Content-Type:application/json'
                        );
                        $ch = curl_init();
                        $handles[] = $ch;
                        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                        curl_multi_add_handle($mh, $ch);
                    }
                }
                $running = null;
                do {
                    curl_multi_exec($mh, $running);
                } while ($running);

                foreach ($handles as $ch) {
                    $result = curl_multi_getcontent($ch);
                    curl_multi_remove_handle($mh, $ch);
                    curl_close($ch);
                }

                $response['status'] = 1;
                $response['message'] = "Token Refresh";

            }


        } else {
            $response['status'] = 0;
            $response['message'] = "No address list found";
        }
        return $response;
    }


    public static function calculate_prescription_resolution($dw, $dh, $thin_app_id=0,$role_id=0){

        $thin_app_id = !empty($thin_app_id)?$thin_app_id:0;
        $role_id = !empty($role_id)?$role_id:0;

        if(!empty($dh) && !empty($dw) && $role_id == 5 ){

            $query = "select * from prescription_resolutions where d_height = '$dh'  and d_weight = '$dw' and thinapp_id = $thin_app_id and role_id = $role_id limit 1";
            $connection = ConnectionUtil::getConnection();
            $layout = $connection->query($query);
            if (!$layout->num_rows) {
                $sql = "INSERT INTO prescription_resolutions (d_height, d_weight,thinapp_id, role_id) VALUES (?, ?, ?, ?)";
                $stmt1 = $connection->prepare($sql);
                $stmt1->bind_param('ssss', $dh, $dw, $thin_app_id, $role_id);
                $stmt1->execute();
            }

        }

        $return['dw'] = '1';
        $return['dh'] = '1';



        if ($dw == 540 && $dh == 960) {
            if ($dw == 540 && $dh < 960) {
                $return['dw'] = '.9';
                $return['dh'] = '1';
            } else if ($dw == 540 && $dh > 960) {
                $return['dw'] = '.9';
                $return['dh'] = '1.2';
            } else {
                $return['dw'] = '.9';
                $return['dh'] = '1.1';
            }
        }
        else if ($dw == 720 || $dh == 1280) {
            if ($dw == 720 && $dh < 1280) {
                $return['dw'] = '1.1';
                $return['dh'] = '1.1';
            } else if ($dw == 720 && $dh > 1280) {
                $return['dw'] = '1.1';
                $return['dh'] = '1.4';
            } else {
                $return['dw'] = '1.1';
                $return['dh'] = '1.3';
            }

        }else if ($dw == 800 || $dh == 1280) {
            if ($dw == 800 && $dh < 1280) {
                $return['dw'] = '1.3';
                $return['dh'] = '1.5';
            } else if ($dw == 800 && $dh > 1280) {
                $return['dw'] = '1.3';
                $return['dh'] = '1.5';
            } else {
                $return['dw'] = '1.3';
                $return['dh'] = '1.5';
            }

        }
        else {
            if ($dw == 1080 || $dh == 1920) {
                if ($dw == 1080 && $dh < 1920) {
                    $return['dw'] = '1.7';
                    $return['dh'] = '2.0';
                } else if ($dw == 1080 && $dh > 1920) {
                    $return['dw'] = '1.7';
                    $return['dh'] = '2.2';
                } else {
                    $return['dw'] = '1.7';
                    $return['dh'] = '2';
                }
            } else if ($dw == 1440 || $dh == 2880) {

                if ($dw == 1440 && $dh < 2880) {
                    $return['dw'] = '1.9';
                    $return['dh'] = '2';
                } else if ($dw == 1440 && $dh > 2880) {
                    $return['dw'] = '1.9';
                    $return['dh'] = '2.4';
                } else {
                    $return['dw'] = '1.9';
                    $return['dh'] = '2.2';
                }
            } else {
                $return['dw'] = '1';
                $return['dh'] = '1';
            }
        }

        return $return;

    }

    public static function send_appointment_tracker_on_booking($thin_app_id,$mobile){


        $tracker_data = Custom::get_appointment_tracker_data($thin_app_id,"SERVICE",$mobile);
        if(!empty($tracker_data)){
            $user_data = Custom::get_user_by_mobile($thin_app_id,$mobile);
            $option = array(
                'thinapp_id' => $thin_app_id,
                'staff_id' => 0,
                'customer_id' => 0,
                'service_id' => 0,
                'channel_id' => 0,
                'role' => "CUSTOMER",
                'flag' => 'APPOINTMENT_TRACKER',
                'title' => "New Tracker Request",
                'message' => "Your tracker message",
                'description' => "Your tracker message",
                'chat_reference' => '',
                'module_type' => 'APPOINTMENT_TRACKER',
                'module_type_id' => 0,
                'firebase_reference' => ""
            );
            return Custom::send_notification_via_token($option,array($user_data['firebase_token']),$thin_app_id);

        }
    }


    public static function get_doctor_break_slot($thin_app_id,$doctor_id,$day_time_id,$service_duration, $key_as_range = false){


        $query = "select time_from, time_to from appointment_staff_break_slots where appointment_staff_id = $doctor_id  and appointment_day_time_id = $day_time_id and status = 'OPEN'";
        $connection = ConnectionUtil::getConnection();
        $layout = $connection->query($query);
        if ($layout->num_rows) {
            $return_array = array();
            $data = mysqli_fetch_all($layout, MYSQLI_ASSOC);
            foreach($data as $key => $value){
                $booking_date = "1990-01-01";
                $start = "1990-01-01 ".$value['time_from'];
                $from_time = strtotime($start);
                $end = "1990-01-01 ".$value['time_to'];
                $end_time = strtotime($end);
                $start_break = $value['time_from'] ."-".$value['time_to'];

                if($key_as_range){
                    $return_array[$start_break][] =date('h:i A', strtotime($start));
                }else{
                    $return_array[] =date('h:i A', strtotime($start));

                }

                for ($time = $from_time; $time < $end_time;) {
                    $original_time = date('H:i', $time);
                    $inc_time = date('h:i A', strtotime($original_time . " + " . $service_duration));
                    $new_time = $booking_date . ' ' . date('H:i', $time);
                    $time = strtotime(date('Y-m-d H:i', strtotime($new_time . " + " . $service_duration)));
                    $end_time = strtotime(date('Y-m-d H:i', strtotime($booking_date . ' ' . date('H:i', $end_time))));


                    if ($time <= $end_time) {
                        if($key_as_range){
                            $return_array[$start_break][] =$inc_time;
                        }else{
                            $return_array[] =$inc_time;
                        }

                    }
                }

            }

            return $return_array;


        }


    }

    public static function get_app_sub_category_array(){
        $sub_category_list = array(
            array(
                'category_id'=>'TOP_FOLLOWER',
                'category_name'=>'TOP FOLLOWER'
            ),
            array(
                'category_id'=>'TOP_DOWNLOAD',
                'category_name'=>'TOP DOWNLOAD'
            ),
            array(
                'category_id'=>'AVAILABLE',
                'category_name'=>'AVAILABLE'
            ),
            array(
                'category_id'=>'NEW_DOCTOR',
                'category_name'=>'NEW DOCTOR'
            )
        );
        return array_values($sub_category_list);
    }

    public static function get_follower_count($thn_app_id){
        $query = "select count(id) as cnt from mengage_app_follwoers  where   follow_thinapp_id = $thn_app_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['cnt'];
        }else{
            return false;
        }
    }


    public static function get_app_functionality_data($thin_app_id, $user_id, $mobile,$main_app_category='DOCTOR',$d_height=0,$d_width=0){
        $data['thin_app_id'] = $thin_app_id;
        $data['user_id'] = $user_id;
        $data['app_key'] = APP_KEY;
        $data['mobile'] = $mobile;
        $data['d_height'] = $d_height;
        $data['d_width'] = $d_width;
        $data['main_app_category'] = $main_app_category;
        $result = WebservicesFunction::get_app_enabled_functionality($data);
        $result = json_decode($result, true);
        if ($result['status'] == 1) {
            return  $result['data'];
        }
        return array();
    }



    public static function pad_get_patient_vitals($thin_app_id,$patient_id,$patient_type){

        $pat_type = "CU";
        if($patient_type=="CHILDREN"){
            $pat_type = "CH";
        }
        $graph_url = SITE_PATH.'chart/vital_graph.php?p=';
        $query = "select CONCAT('$graph_url',$thin_app_id,'-',tmv.id,'-',$patient_id,'-','$pat_type') as graph_url, tmv.id as vital_id, IFNULL(tpv.id,0) as patient_vital_id, tmv.name, tmv.icon, tpv.value, tmv.unit from tab_master_vitals as tmv left JOIN  tab_patient_vitals AS tpv ON tpv.id = ( SELECT inner_tpv.id FROM tab_patient_vitals AS inner_tpv	WHERE inner_tpv.patient_id = $patient_id and inner_tpv.patient_type = '$patient_type' and inner_tpv.status = 'ACTIVE' AND inner_tpv.tab_master_vital_id = tmv.id ORDER BY id DESC LIMIT 1 ) where tmv.status = 'ACTIVE' order by tmv.id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
        }else{
            return false;
        }
    }

    public static function pad_can_update_vital($thin_app_id,$vital_id){
        $query = "select id from  tab_patient_vitals where id = $vital_id and status = 'ACTIVE' and DATE(created) = DATE(NOW())";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return true;
        }else{
            return false;
        }
    }

    public static function pad_get_patient_reminder_list($thin_app_id,$patient_id,$patient_type){

        $condition = " and fur.appointment_customer_id = $patient_id";
        if($patient_type=="CHILDREN"){
            $condition = " and fur.children_id = $patient_id";
        }
        $query = "select fur.id, fur.reminder_date, reminder_message as message from follow_up_reminders as fur where  fur.add_via ='TAB' and fur.status = 'ACTIVE' and fur.thinapp_id = $thin_app_id  $condition order by fur.id desc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
        }else{
            return false;
        }


    }


    public static function get_thin_app_follower_user_token($thin_app_id){

        $condition = "";
        if(!empty($thin_app_id)){
            $condition = " where maf.follow_thinapp_id = $thin_app_id";
        }
        $query = "select u.firebase_token from mengage_app_follwoers as maf join users as u on u.id = maf.user_id and u.app_installed_status = 'INSTALLED' $condition";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC),'firebase_token');
        }else{
            return false;
        }
    }

    public static function update_health_tip_flag($cms_id,$notification_param){
        $query = "update cms_doc_dashboards set notification_param =? where id = ?";
        $connection = ConnectionUtil::getConnection();
        $stmt = $connection->prepare($query);
        $stmt->bind_param('ss', $notification_param, $cms_id);
        if ($stmt->execute()) {
            return true;
        }else{
            return false;
        }

    }

    public static function get_tab_prescription_cat_name($category_id, $sub_category_id){
        $query = "select tpc.name as category_name, tps.name as sub_category_name from tab_prescription_categories as tpc left join tab_prescription_subcategories as tps on tpc.id = tps.tab_prescription_category_id and tps.id = $sub_category_id where tpc.id = $category_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }
    public static function get_total_step_count($thin_app_id, $category_id, $sub_category_id){
        $query = "select  count(*) as total from tab_prescription_steps where thinapp_id = $thin_app_id and tab_prescription_category_id=$category_id and tab_prescription_sub_category_id= $sub_category_id  and status ='ACTIVE' ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $data = mysqli_fetch_assoc($service_message_list);
            return $data['total'];
        }else{
            return false;
        }
    }
    public static function get_prescription_keyword_count($category_id){
        $file_name = "tab_prescription_keyword_category_count_".$category_id;
        $return =0;
        if(!$return = json_decode(WebservicesFunction::readJson($file_name,"prescription"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select count(*) as tot from tab_prescription_keywords where tab_prescription_category_id = $category_id";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                $return = $staff_data['tot'];
                WebservicesFunction::createJson($file_name,json_encode($staff_data['tot']),"CREATE","prescription");
            }
        }
        return $return;
    }

    public static function tab_get_prescription_by_id($template_id){
        $query = "select * from tab_prescription_templates  where  id = $template_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function tab_check_current_prescription($thin_app_id, $patient_id, $patient_type){

        $query = "select id from tab_patient_prescriptions  where  thinapp_id = $thin_app_id and patient_id=$patient_id and patient_type = '$patient_type' and prescripiton_status ='PENDING' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['id'];
        }else{
            return false;
        }
    }

    public static function get_child_graph($thin_app_id,$child_id,$category){

        $connection = ConnectionUtil::getConnection();

        $child_data = Custom::get_child_by_id($child_id);
        $child_years = Custom::dob_elapsed_string($child_data['dob'],false,false);
        $year_count = ($child_years['year']) + (($child_years['month'] > 0 )?1:0);
        $condition = "";
        if (!empty($year_count)) {
            $month_cnt = $year_count * 12;
            $condition = " and ccm.month <= " . $month_cnt;
        }else{
            $mon = ($child_years['month']==0)?6:$child_years['month'];
            $condition = " and ccm.month <= " . $mon;
        }

        $query = "select  MAX(DATE(date)), cg.date as growth_date, c.gender, ccm.gender, c.dob, cg.height,cg.weight,cg.head_circumference,ccm.month,ccm.category,ccm.median,ccm.m3sd,ccm.m2sd,ccm.m1sd,ccm.sd,ccm.p1sd,ccm.p2sd,ccm.p3sd from child_chart_masters as ccm left join child_growths as cg on ccm.month = cg.month and cg.children_id = $child_id and  cg.id IN (select MAX(id) from child_growths where children_id = $child_id and month = cg.month)left join childrens as c on cg.children_id = c.id and c.status = 'ACTIVE' and c.id = $child_id and c.thinapp_id = $thin_app_id  where ccm.gender = (select gender from childrens where id =$child_id) AND category = '$category'  $condition group by month,category order by category, month asc";
        $subscriber = $connection->query($query);
        if($subscriber->num_rows) {
            $graph_data = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
            $response['status'] = 1;
            $response['message'] = "Graph list found";
            $graph_array = array();
            foreach ($graph_data as $key => $value) {
                $height = isset($value['height']) ? $value['height'] : 0;
                $weight = isset($value['weight']) ? $value['weight'] : 0;
                $circumference = isset($value['head_circumference']) ? $value['head_circumference'] : 0;
                $day = !(empty($value['growth_date'])) ? date('d', strtotime($value['growth_date'])) : "0";
                $val = 0;
                if ($value['category'] == "HEIGHT") {
                    $val = $height;
                } else if ($value['category'] == "WEIGHT") {
                    $val = $weight;
                } else if ($value['category'] == "CIRCUMFERENCE") {
                    $val = $circumference;
                }


                if( $val==0 || $val >= $value['m3sd']) {
                    $return_array['data'][0][] =$value['m3sd'];
                }if($val==0 || $val >= $value['m1sd']) {
                    $return_array['data'][1][] =$value['m1sd'];
                }if($val==0 || $val >= $value['sd']) {
                    $return_array['data'][2][] =$value['sd'];
                }if($val==0 || $val >= $value['p1sd']) {
                    $return_array['data'][4][] =$value['p1sd'];
                }if($val==0 || $val >= $value['p3sd']) {
                    $return_array['data'][5][] =$value['p3sd'];
                }

                if(!empty($val)){
                    $return_array['data'][3][] =$val;

                }
                $return_array['label'][] =$value['month'];

            }
            $return_array['label'] = Custom::generate_label(count($return_array['label']));
            return json_encode($return_array);
        }else{
            return false;
        }

    }

    public static function generate_label($total_month){
        $return_array= array();
        $total_labels = 7;
        $total_flor = intval($total_month/$total_labels);
        $module = $total_month%$total_labels;
        $total_flor = ($module >0 )?$total_flor+1:$total_flor;
        for($count=0;$count<$total_labels;$count++){
            $return_array[] = $count*$total_flor;
        }
        return $return_array;

    }

    public static function tab_get_last_prescriptions($folder_id){
        $query = "select id, file_path from drive_files where drive_folder_id = $folder_id and is_tab_prescription = 'YES' order by id desc limit 3";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return =array();
        if ($service_message_list->num_rows) {
            $return = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);

        }
        return $return;
    }

    public static function update_topic_token($thin_app_id,$switch_thin_app_id,$firebase_token){

        $query = "select (select c.topic_name from channels as c where channel_status = 'DEFAULT' AND app_id = $thin_app_id limit 1) as last_topic, (select c.topic_name from channels as c where channel_status = 'DEFAULT' AND app_id = $switch_thin_app_id limit 1) as switch_topic";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return =array();
        if ($service_message_list->num_rows) {
            $return = mysqli_fetch_assoc($service_message_list);
            $res = Custom::remove_subscribers_from_topic($return['last_topic'],array($firebase_token));
            $res2 = Custom::add_subscribers_to_topic($return['switch_topic'], array($firebase_token));
            if($res && $res2){
                return true;
            }else{
                return false;
            }
        }

    }

    public static function created_time_slot_range($from_time,$to_time,$duration,$format='h:i A'){
        $return_array=array();
        $return_array[] = $from_time;
        $from_time = DateTime::createFromFormat($format, $from_time);
        $from_time = $new_time= $from_time->format($format);
        $to_time = DateTime::createFromFormat($format, $to_time);
        $to_time = $to_time->format($format);
        while(strtotime($to_time) > strtotime($from_time) ){
            $from_time = date($format, strtotime($duration, strtotime($from_time)));
            if(strtotime($to_time) >= strtotime($from_time)){
                $return_array[] = $from_time;
            }

        }
        return $return_array;
    }

    public static function get_appointment_data($doctor_id){
        $query = "select acss.slot_time, IF(ac.id IS NOT NULL,ac.first_name,c.child_name) as customer_name, IF(ac.id IS NOT NULL,ac.mobile,c.mobile) as mobile, acss.queue_number as token_number from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id and acss.children_id=0 left join childrens as c on c.id= acss.children_id and acss.appointment_customer_id = 0 where acss.appointment_staff_id = $doctor_id and DATE(acss.appointment_datetime) = DATE(NOW()) and acss.status !='CANCELED' ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
        }else{
            return false;
        }
    }

    public static function get_doctor_appointment_time($doctor_id,$date=null){
        if(empty($date)){
            $date_time_id = date('N');
        }else{
            $date_time_id=date('N',strtotime($date));
        }

        $query = "select time_from,time_to from appointment_staff_hours where appointment_day_time_id= $date_time_id and appointment_staff_id = $doctor_id and status ='OPEN'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function get_prescription_layout_by_id($prescription_id){

        $query = "select * from prescription_layouts where id = $prescription_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function get_doctor_by_mobile($mobile,$thin_app_id){
        $query = "select * from appointment_staffs  where staff_type= 'DOCTOR' and status='ACTIVE' AND mobile = '$mobile' and thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_doctor_record_by_mobile($mobile,$thin_app_id){
        $query = "select * from appointment_staffs  where mobile = '$mobile' and thinapp_id = $thin_app_id order by id desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function create_query_string($thin_app_id,$status_condition,$custom_condition,$order_by,$doctor_id){
        $patientHistoryUrl = SITE_PATH.'tracker/get_patient_history/';
        $common_query  = "select acss.has_token, acss.emergency_appointment, IF(IFNULL(ac.dob,c.dob) != '0000-00-00', DATE_FORMAT(IFNULL(ac.dob,c.dob),'%d-%m-%Y'),'') as dob,  CONCAT('$patientHistoryUrl',TO_BASE64(IFNULL(ac.thinapp_id,c.thinapp_id)),'/',TO_BASE64(IFNULL(ac.uhid,c.uhid))) as history_url, IFNULL(ac.gender,c.gender) AS gender, IF(ac.age IS NOT NULL, ac.age,'') AS age, IF(acss.payment_status ='FAILURE' ,'PENDING',acss.payment_status) as payment_status, acss.skip_tracker as is_skip, df.total_attachment, IF(acss.appointment_customer_id > 0, 'CUSTOMER','CHILDREN') as type, IF(acss.appointment_customer_id > 0, acss.appointment_customer_id,acss.children_id) as edit_patient_id, acss.amount as fees, acss.slot_time as appointment_time, IF(acss.status ='CLOSED',0,1) AS order_status, IF(c.id IS NOT NULL,c.child_number,'') as patient_id, IF(ac.id IS NOT NULL,'CUSTOMER','CHILD') as type, acss.status, acss.id as appointment_id, df.id as folder_id, df.folder_name, IF(ac.id IS NOT NULL,df.folder_add_from_number,c.mobile) as mobile, IFNULL(ac.first_name,c.child_name) as name, acss.queue_number, DATE_FORMAT(acss.appointment_datetime,'%d-%m-%Y %h:%i %p') as appointment_datetime from appointment_customer_staff_services as acss left join appointment_customers as ac on acss.appointment_customer_id = ac.id and ac.status = 'ACTIVE' left join childrens as c on c.id = acss.children_id AND c.status='ACTIVE' left join  drive_folders as df on (df.appointment_customer_id = ac.id OR df.children_id = c.id ) left join users as u on ( u.mobile = ac.mobile and u.thinapp_id = ac.thinapp_id ) OR ( u.mobile = c.mobile and u.thinapp_id = c.thinapp_id ) where acss.thinapp_id = $thin_app_id and  $status_condition and acss.appointment_staff_id = $doctor_id and DATE(acss.appointment_datetime) = DATE(NOW()) $custom_condition group by acss.id order by $order_by";
        return $common_query;
    }




    /*public static function get_vital_graph_value($thin_app_id, $vital_id, $patient_id, $patient_type){
        $query = "select tmv.minimum_value, tmv.maximum_value, tpv.value from tab_master_vitals as tmv join tab_patient_vitals as tpv on tmv.id = tpv.tab_master_vital_id and patient_id = $patient_id and tpv.patient_type = '$patient_type' and tpv.value !='' where tmv.id = $vital_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return =array();
        if ($service_message_list->num_rows) {

            $data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            foreach($data as $key => $value){
                $return['min'] = (int)$value['minimum_value'];
                $return['max'] = (int)$value['maximum_value'];
                $return['data'][] = (int)$value['value'];
            }
            return json_encode($return);
        }else{
            return false;
        }
    } */

    public static function get_vital_graph_value($thin_app_id, $vital_id, $patient_id, $patient_type){



        $query = "SELECT `tab_master_vitals`.`name`,`tab_master_vitals`.`unit` FROM `tab_master_vitals` WHERE `tab_master_vitals`.`id` = '".$vital_id."' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $vitalNameRS = $connection->query($query);

        $sql = "SELECT DATE_FORMAT(`created`,'%d/%m/%Y %H:%i:%s') AS created_date, `value` FROM `tab_patient_vitals` WHERE `status` = 'ACTIVE' AND `patient_type` = '".$patient_type."' AND `patient_id` = '".$patient_id."' AND `tab_master_vital_id` = '".$vital_id."' AND `thinapp_id` = '".$thin_app_id."' ORDER BY `created` DESC LIMIT 10";
        $tabPatientVitalsRS = $connection->query($sql);

        if ($vitalNameRS->num_rows && $tabPatientVitalsRS->num_rows) {
            $vitalNameData = mysqli_fetch_assoc($vitalNameRS);
            $vitalName = $vitalNameData['name'];
            $vitalUnit = $vitalNameData['unit'];
            $tabPatientVitalsData = mysqli_fetch_all($tabPatientVitalsRS,MYSQLI_ASSOC);
            $dates=array();
            $vitalValue=array();
            foreach($tabPatientVitalsData AS $value)
            {
                $dates[]= $value['created_date'];
                $vitalValue[]= array('x'=> $value['created_date'], 'y'=> $value['value']);
            }

            $return = array(
                'vitalName' => $vitalName,
                'dates' => $dates,
                'vitalValue' => $vitalValue,
                'vitalUnit' => $vitalUnit
            );

            return json_encode($return);
        }else{
            return false;
        }
    }

    public static function send_child_add_sms_and_notification($thin_app_id, $child_id){
        $nf_data = Custom::get_child_notification_data($thin_app_id, $child_id);
        if (!empty($nf_data)) {

            if($nf_data['child_add_by'] == 'DOCTOR'){
                $message = "Your child " . Custom::get_string_first_name($nf_data['child_name']) . ", ID-" . $nf_data['child_number'] . " has been added by " . Custom::get_doctor_first_name(trim($nf_data['admin_name']));
                $option = array(
                    'thinapp_id' => $thin_app_id,
                    'channel_id' => 0,
                    'role' => "USER",
                    'flag' => 'ADD_CHILD',
                    'title' => "New Child Added",
                    'message' => mb_strimwidth($message, 0, 250, '...'),
                    'description' => mb_strimwidth($message, 0, 250, '...'),
                    'chat_reference' => '',
                    'module_type' => 'VACCINATION',
                    'module_type_id' => $child_id,
                    'firebase_reference' => "",
                    'day_type' => 'ADD_CHILD'
                );
                if (!empty($nf_data['main_parent_token'])) {
                    Custom::send_notification_via_token($option, array($nf_data['main_parent_token']), $thin_app_id);
                }
                if (!empty($nf_data['alt_parent_token'])) {
                    Custom::send_notification_via_token($option, array($nf_data['alt_parent_token']), $thin_app_id);
                }
                if (!empty($nf_data['main_parent_mobile']) && (empty($nf_data['main_parent_installed']) || $nf_data['main_parent_installed'] =='UNINSTALLED') ) {
                    Custom::send_single_sms($nf_data['main_parent_mobile'], $message, $thin_app_id);
                }
                if (!empty($nf_data['alt_parent_mobile']) && ( empty($nf_data['alt_parent_installed']) ||  $nf_data['alt_parent_installed'] =='UNINSTALLED')) {
                    Custom::send_single_sms($nf_data['alt_parent_mobile'], $message, $thin_app_id);
                }
            }

        }

    }

    public static function send_notification_on_offline_save($thin_app_id, $drive_folder_id,$file_array,$mobile,$user_id,$only_doctor=false){


        $user_id_list = array();
        $folder_data = Custom::get_folder_data($drive_folder_id);
        if($only_doctor===true){
            $user_ids = Custom::get_folder_share_with_doctor_ids($drive_folder_id);
        }else{
            $user_id_list = Custom::get_folder_shared_user_mobile_and_id($drive_folder_id);
            $user_ids = array_column($user_id_list, "share_to_user_id");
            $user_ids = Custom::search_remove($user_id, $user_ids);
            $show_ipd = Custom::check_user_permission($thin_app_id,'SHOW_IPD_CATEGORY_TO_PATIENT');
            if($show_ipd == "NO" ||$show_ipd===false){
                if(!empty($user_ids)){
                    $tmp=array();
                    foreach($user_ids as $key => $value){
                        if($value['is_doctor'] =="YES"){
                            $tmp[]= $value;
                        }
                    }
                    $user_ids = $tmp;
                }
            }
        }


        $username = $mobile;
        $user_data = Custom::get_user_by_id($user_id);
        if (!empty($user_data)) {
            $username = $user_data['username'];
        }
        if (!empty($folder_data)) {
            if ($user_ids) {

                foreach ($file_array as $key => $file){
                    $label = $file['file_name'];
                    $message = $label . " added to folder " . $folder_data['folder_name'] . " by " . $username;
                    $option = array(
                        'thinapp_id' => $thin_app_id,
                        'channel_id' => 0,
                        'role' => "USER",
                        'flag' => 'FILE_ADD',
                        'title' => "New file added to folder " . $folder_data['folder_name'],
                        'message' => mb_strimwidth($message, 0, 250, '...'),
                        'description' => mb_strimwidth($message, 0, 250, '...'),
                        'chat_reference' => '',
                        'module_type' => 'DOCUMENT',
                        'module_type_id' => $drive_folder_id,
                        'firebase_reference' => ""
                    );

                    Custom::send_notification_by_user_id($option, $user_ids, $thin_app_id);
                }


            }
        }
        if ($user_id_list) {
            foreach ($file_array as $key => $file) {
                $label = (count($file_array) > 1) ? "New files" : "New file " . $file['file_name'];
                $message = $label . " added to folder " . $folder_data['folder_name'] . " by " . $username;
                $mobile_numbers = array_column($user_id_list, "share_with_mobile");
                $mobile_numbers = Custom::search_remove($mobile, $mobile_numbers);
                Custom::sendFileShareMessage("FOLDER", $drive_folder_id, $mobile_numbers, $message, $thin_app_id, $user_id);
            }
        }



    }

    public static function get_staff_role($mobile, $thin_app_id)
    {
        $query = "select staff_type from appointment_staffs  where  mobile = '$mobile' and thinapp_id = $thin_app_id  and status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['staff_type'];
        } else {
            return "";
        }
    }

    public  static function hospital_get_user_role($mobile,$thin_app_id,$role_id){
        if ($role_id == 5) {
            return 'ADMIN';
        }else{
            $staff_type = Custom::get_staff_role($mobile, $thin_app_id);
            if ($staff_type == 'DOCTOR') {
                return 'STAFF';
            }else if ($staff_type == 'RECEPTIONIST') {
                return 'RECEPTIONIST';
            } else {
                return 'USER';
            }
        }
        return "USER";
    }



    public static function load_patient_invoice_content($thin_app_id,$patient_id,$user_type,$doctor_id){

        if($user_type=="CHILDREN"){
            $query = "select app_sta.invoice_address_id, app_sta.t_and_c, app_ser.name as service_name, app_ser.service_amount, (select count(id) from tab_patient_invoices where thinapp_id = 134)+100001 as invoice_number,doctor.address, ac.child_name as patient_name, t.logo, t.name,doctor.mobile from childrens as ac join thinapps as t on t.id = ac.thinapp_id left join users as doctor on doctor.id = t.user_id left join appointment_staffs as app_sta on app_sta.id = $doctor_id left join appointment_staff_services as ass on ass.thinapp_id = $thin_app_id and ass.status = 'ACTIVE' and ass.appointment_staff_id = $doctor_id left join appointment_services as app_ser on app_ser.id = ass.appointment_service_id where ac.id = $patient_id limit 1";
        }else{
            $query = "select app_sta.invoice_address_id,  app_sta.t_and_c, app_ser.name as service_name, app_ser.service_amount, (select count(id) from tab_patient_invoices where thinapp_id = 134)+100001 as invoice_number,doctor.address, ac.first_name as patient_name, t.logo, t.name,doctor.mobile from appointment_customers as ac join thinapps as t on t.id = ac.thinapp_id left join users as doctor on doctor.id = t.user_id left join appointment_staffs as app_sta on app_sta.id = $doctor_id left join appointment_staff_services as ass on ass.thinapp_id = $thin_app_id and ass.status = 'ACTIVE' and ass.appointment_staff_id = $doctor_id left join appointment_services as app_ser on app_ser.id = ass.appointment_service_id where ac.id = $patient_id limit 1";
        }
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);

        } else {
            return false;
        }

    }



    public static function get_doctor_address_id($doctor_id)
    {
        $query = "select appointment_address_id  from appointment_staff_addresses where appointment_staff_id = $doctor_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'appointment_address_id');
            $return =array();
            foreach ($staff_data  as $key => $id){
                $return[$id]=$id;
            }
            return $staff_data;
        } else {
            return array();
        }
    }

    public static function lab_allow_send_request($thin_app_id,$role_type,$request_mobile)
    {
        $query = "select id from lab_pharmacy_users where thinapp_id= $thin_app_id and role_type = '$role_type' and mobile ='$request_mobile' and request_status != 'REJECTED' and status='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if (!$service_message_list->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    public static function lab_get_request_data($thin_app_id,$role_type,$request_mobile)
    {
        $query = "select id from lab_pharmacy_users where thinapp_id= $thin_app_id and role_type = '$role_type' and mobile ='$request_mobile' and request_status != 'REJECTED' and status='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $data = mysqli_fetch_assoc($service_message_list);
            return $data;
        } else {
            return false;
        }
    }

    public static function lab_get_user_by_id($lab_user_id)
    {
        $query = "select * from lab_pharmacy_users where id = $lab_user_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $data = mysqli_fetch_assoc($service_message_list);
            return $data;
        } else {
            return false;
        }
    }

    public static function lab_notification_data($lab_user_id)
    {
        $query = "select lab.lab_name, lab.request_status, lab.role_type, lab.thinapp_id, lab.name, lab.mobile,u.firebase_token, u.app_installed_status, lab.request_send_by, t.name as app_name, admin.firebase_token as doctor_token, admin.mobile as doctor_mobile, admin.app_installed_status as doctor_install_status from  lab_pharmacy_users as lab join thinapps as t on t.id = lab.thinapp_id left join users as u  on u.mobile = lab.mobile and u.thinapp_id = lab.thinapp_id left join users as admin on admin.thinapp_id = lab.thinapp_id and admin.role_id = 5 where lab.id = $lab_user_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $data = mysqli_fetch_assoc($service_message_list);
            return $data;
        } else {
            return false;
        }
    }

    public static function lab_upload_notification_data($upload_prescription_id)
    {
        $query = "select u.username, lpu.mobile, lab_u.role_id, lab_u.firebase_token as lab_user_token from lab_patient_uploaded_recordes as lpur left join users as u on u.id = lpur.user_id left join lab_pharmacy_users as lpu on lpu.id = lpur.lab_pharmacy_user_id left join users as lab_u on lab_u.mobile = lpu.mobile and lab_u.thinapp_id = lpu.thinapp_id where lpur.id = $upload_prescription_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $data = mysqli_fetch_assoc($service_message_list);
            return $data;
        } else {
            return false;
        }
    }

    public  static function lab_get_user_data($mobile,$thin_app_id,$role_id){

        $return = "";
        $file_name = Custom::encrypt_decrypt('encrypt',"user_$mobile".$thin_app_id);
        if(!$return = json_decode(WebservicesFunction::readJson($file_name,"lab_user"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select * from lab_pharmacy_users  where  mobile = '$mobile' and thinapp_id = $thin_app_id  and request_status = 'APPROVED' and status = 'ACTIVE' limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $return = mysqli_fetch_assoc($service_message_list);
                WebservicesFunction::createJson($file_name,json_encode($return),"CREATE","lab_user");
            }
        }

        return $return;

    }

    public static function lab_send_request_notification($lab_user_id){


        $user_data = Custom::lab_notification_data($lab_user_id);
        if(!empty($user_data)){
            $thin_app_id =  $user_data['thinapp_id'];
            if($user_data['request_send_by']=='APP_ADMIN'){
                $message = $user_data['app_name']." invites you to become their favourite pharmacist/lab";
                $sendArray = array(
                    'channel_id' => 0,
                    'thinapp_id' => $user_data['thinapp_id'],
                    'flag' => 'LAB_NEW_REQUEST',
                    'title' => 'New Message',
                    'message' => $message,
                    'description' => '',
                    'chat_reference' => '',
                    'module_type' => 'LAB_PHARMACY',
                    'module_type_id' => $lab_user_id,
                    'lab_user_id' => $lab_user_id,
                    'lab_user_role' => 'PATIENT',
                    'firebase_reference' => ""
                );

                if($user_data['app_installed_status'] == "INSTALLED"){
                    Custom::send_notification_via_token($sendArray, array($user_data['firebase_token']), $thin_app_id);
                }else{
                    $url = SITE_PATH."folder/lab_request/".base64_encode($lab_user_id);
                    //$url = Custom::short_url($url);
                    $message .= " Click to view ".$url;
                    Custom::send_single_sms($user_data['mobile'], $message, $thin_app_id);
                }

            }else if($user_data['request_send_by']=='LAB_PHARMACY') {

                $message = $user_data['name']." wants to  become your favourite ".strtolower($user_data['role_type']);
                $sendArray = array(
                    'channel_id' => 0,
                    'thinapp_id' => $user_data['thinapp_id'],
                    'flag' => 'LAB_NEW_REQUEST',
                    'title' => 'New Message',
                    'message' => $message,
                    'description' => '',
                    'chat_reference' => '',
                    'module_type' => 'LAB_PHARMACY',
                    'module_type_id' => $lab_user_id,
                    'lab_user_id' => $lab_user_id,
                    'firebase_reference' => ""
                );
                if($user_data['doctor_install_status'] == "INSTALLED"){
                    Custom::send_notification_via_token($sendArray, array($user_data['doctor_token']), $thin_app_id);
                }

            }


        }

    }


    public static function get_doctor_address_time($doctor_id,$address_id){


        $query = "select from_time as time_from, to_time as time_to from appointment_staff_addresses where appointment_address_id= $address_id and appointment_staff_id = $doctor_id and status ='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }


    public static function get_all_app_doctor_id($thin_app_id)
    {
        $query = "select id from appointment_staffs  where thinapp_id = $thin_app_id and  status  = 'ACTIVE' and staff_type ='DOCTOR'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = array_column(mysqli_fetch_all($list,MYSQLI_ASSOC),'id');
            return $data;
        } else {
            return false;
        }
    }

	public static function get_all_app_doctor_id_name($thin_app_id)
    {
        $query = "select id,name from appointment_staffs  where thinapp_id = $thin_app_id and  status  = 'ACTIVE' and staff_type ='DOCTOR' order by CAST(other_check_in_queue_after_number AS DECIMAL) asc";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = array_column(mysqli_fetch_all($list,MYSQLI_ASSOC),'name','id');
            return $data;
        } else {
            return false;
        }
    }

    public static function get_tab_prescription_templates($thin_app_id,$category_id,$sub_category_id,$name,$doctor_id){

        $name = trim($name);
        $query = "select * from tab_prescription_templates where doctor_id = $doctor_id and thinapp_id= $thin_app_id and tab_prescription_category_id=$category_id and tab_prescription_sub_category_id=$sub_category_id and template_name ='$name' and status ='ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_default_folder_data($thin_app_id,$mobile){

        /***** PLEASE DONT USE CHILD STATUS ACTIVE INOT WHERE CLOUS FOR THIS METHOD ******/
        $query = "select * from drive_folders where thinapp_id =$thin_app_id and folder_add_from_number = '$mobile' and status ='ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function update_doctor_prescription_category_id($thin_app_id,$doctor_id){
        $query = "select * from tab_prescription_categories where doctor_id = 0";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $list =  mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            foreach ($list as $key => $value){
                $prescription_category_id = $value['id'];
                $query = "select id from tab_prescription_categories where doctor_id = $doctor_id and tab_prescription_category_id=$prescription_category_id limit 1";
                $connection = ConnectionUtil::getConnection();
                $sub_data = $connection->query($query);
                if (!$sub_data->num_rows) {
                    $query = "insert into tab_prescription_categories (tab_prescription_category_id,name,thinapp_id,doctor_id,`index`,has_subcategory,created_date,modified_date,status,icon_path) select id, name,?,?,`index`,has_subcategory,now(),now(),status,icon_path from tab_prescription_categories as tpc where tpc.id = $prescription_category_id and tpc.doctor_id = 0";
                    $connection = ConnectionUtil::getConnection();
                    $stmt = $connection->prepare($query);
                    $stmt->bind_param('ss', $thin_app_id, $doctor_id);
                    if ($stmt->execute()) {
                        $last_id = $stmt->insert_id;
                        $sql = "UPDATE  tab_prescription_steps SET  tab_prescription_category_id = ? where doctor_id = ? and tab_prescription_category_id =?";
                        $stmt_df = $connection->prepare($sql);
                        $status = "INACTIVE";
                        $stmt_df->bind_param('sss', $last_id, $doctor_id, $prescription_category_id);
                        $stmt_df->execute();
                    }
                }
            }
            return true;
        }
        return false;
    }

    public static function clone_prescription_category_for_doctor($thin_app_id,$doctor_id){
        $query = "select * from tab_prescription_categories where doctor_id = 0 and thinapp_id  = 0";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            $list =  mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
            foreach ($list as $key => $value){
                Custom::get_prescription_category_id_for_doctor($thin_app_id,$doctor_id,$value['id']);
            }
            return true;
        }
        return false;
    }

    public static function get_prescription_category_id_for_doctor($thin_app_id,$doctor_id,$prescription_category_id){
        $query = "select id from tab_prescription_categories where doctor_id = $doctor_id and tab_prescription_category_id=$prescription_category_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if (!$sub_data->num_rows) {
            $query = "insert into tab_prescription_categories (tab_prescription_category_id,name,thinapp_id,doctor_id,`index`,has_subcategory,created,modified,status,icon_path) select id, name,?,?,`index`,has_subcategory,now(),now(),status,icon_path from tab_prescription_categories as tpc where tpc.id = $prescription_category_id and tpc.doctor_id = 0";
            $connection = ConnectionUtil::getConnection();
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $thin_app_id, $doctor_id);
            if ($stmt->execute()) {
                return $stmt->insert_id;

            }
        }else{
            return mysqli_fetch_assoc($sub_data)['id'];
        }
        return 0;

    }

    public static function clone_master_tab_steps($thin_app_id,$doctor_id){


        $file_name = "default_steps_$thin_app_id"."_".$doctor_id;
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"tab"),true)){
            $cache_label =array('has_default_steps'=>true);
            $is_step = Custom::has_tab_default_step($thin_app_id,$doctor_id);

            $result =array();
            if (!$is_step) {
                $master_steps = Custom::get_all_master_steps();
                $is_prescription_category = Custom::clone_prescription_category_for_doctor($thin_app_id,$doctor_id);
                $connection = ConnectionUtil::getConnection();
                //$connection->autocommit(false);
                if(!empty($master_steps)){
                    foreach ($master_steps as $key => $step){
                        $created = Custom::created();
                        $tab_master_prescription_step_id = $step['id'];
                        $tab_prescription_category_id = $step['tab_prescription_category_id'];
                        $tab_prescription_category_primery_id = Custom::get_prescription_category_id_for_doctor($thin_app_id,$doctor_id,$step['tab_prescription_category_id']);
                        $query = "INSERT INTO tab_prescription_steps (tab_prescription_category_primery_id, tab_master_prescription_step_id, thinapp_id,doctor_id,tab_prescription_category_id,tab_prescription_sub_category_id,step_title,created,modified) values (?,?,?,?,?,?,?,?,?)";
                        $stmt = $connection->prepare($query);
                        $sub_category_id =0;
                        $stmt->bind_param('sssssssss', $tab_prescription_category_primery_id,$tab_master_prescription_step_id, $thin_app_id, $doctor_id,$tab_prescription_category_id,$sub_category_id,$step['step_title'], $created, $created);
                        if ($stmt->execute()) {
                            $master_tags = Custom::get_all_master_tags($tab_master_prescription_step_id);
                            $step_id = $stmt->insert_id;
                            if(!empty($master_tags)){
                                foreach($master_tags as $key_tag => $tag){
                                    $created = Custom::created();
                                    $tab_master_prescription_tag_id = $tag['id'];
                                    $query = "INSERT INTO tab_prescription_tags (tab_master_prescription_tag_id, thinapp_id,doctor_id,tab_prescription_step_id,tag_name,created,modified) values (?,?,?,?,?,?,?)";
                                    $connection = ConnectionUtil::getConnection();
                                    $stmt_tag = $connection->prepare($query);
                                    $stmt_tag->bind_param('sssssss', $tab_master_prescription_tag_id, $thin_app_id, $doctor_id, $step_id, $tag['tag_name'], $created, $created);
                                    $result[] = $stmt_tag->execute();
                                }
                            }
                        }else{
                            $result[] = false;
                        }

                    }
                    if(!in_array(false,$result)){
                        //$connection->commit();
                        WebservicesFunction::createJson($file_name,json_encode($cache_label),"CREATE","tab");
                        return true;
                    }else{
                        //$connection->rollback();
                        return false;
                    }
                }

            }else{
                WebservicesFunction::createJson($file_name,json_encode($cache_label),"CREATE","tab");
                return true;

            }

        }


    }


    public static function get_all_master_steps(){
        $query = "select tmps.id, tmps.tab_prescription_category_id, tmps.step_title from tab_master_prescription_steps as tmps  where tmps.status ='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            return mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
        }else{
            return false;
        }

    }

    public static function get_all_master_tags($step_id){
        $query = "select * from tab_master_prescription_tags where status ='ACTIVE' and tab_master_prescription_step_id = $step_id";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            return mysqli_fetch_all($sub_data,MYSQLI_ASSOC);
        }else{
            return false;
        }

    }

    public static function has_tab_default_step($thin_app_id,$doctor_id){
        $query = "select id from tab_prescription_steps where thinapp_id = $thin_app_id and doctor_id = $doctor_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $sub_data = $connection->query($query);
        if ($sub_data->num_rows) {
            return true;
        }else{
            return false;
        }

    }


    public static function delete_customer($customer_id){
        $connection = ConnectionUtil::getConnection();
        $sql = "UPDATE  appointment_customers SET  status = ? where id = ?";
        $stmt_df = $connection->prepare($sql);
        $status = "INACTIVE";
        $stmt_df->bind_param('ss', $status, $customer_id);
        if ($stmt_df->execute()){
            return true;
        }else{
            return false;
        }
    }


    public static function get_child_by_mobile($thin_app_id, $mobile){
        $query =    $query = "select * from childrens where status='ACTIVE' AND thinapp_id = $thin_app_id and ( parents_mobile = '$mobile' OR mobile = '$mobile') limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_lab_user_static_data($thin_app_id, $mobile,$role_id){
        $lab_user = Custom::lab_get_user_data($mobile,$thin_app_id,$role_id);;
        $response['lab_user_role'] = !empty($lab_user)?"LAB_OWNER":"PATIENT";
        $response['lab_user_id'] = !empty($lab_user)?$lab_user['id']:0;
        return $response;
    }

    public static function get_doctor_first_name($name_string){
        $name_string =trim($name_string);
        $name_array =explode(" ",$name_string);
        if( count($name_array) > 0 ){
            if(count($name_array) >= 2){
                $sub_title = strtoupper(trim($name_array[0]));
                if($sub_title == "DR" || $sub_title == "DR." || $sub_title == "DOCTOR" ){
                    $ret = trim($name_array[0]).' '.@trim($name_array[1]).' '.@trim($name_array[2]);
                    return trim($ret);
                }else{
                    return "Dr ".trim($name_array[0]).' '.@trim($name_array[1]);
                }

               
            }
            return "Dr ".trim($name_string);

        }
        return $name_string;
    }
    public static function get_string_first_name($name_string){
        $title = array('MR','MRS','MR.','MRS.','BABY');
        $name_string =trim($name_string);
        $string_array = explode(" ",$name_string);
        if(count($string_array) >= 3){
            $name_string = !in_array(strtoupper($string_array[0]),$title)?$string_array[0]:$string_array[1];
        }else if(count($string_array) == 2){
            $name_string = !in_array(strtoupper($string_array[0]),$title)?$string_array[0]:$string_array[1];
        }
        return $name_string;

    }


    public static function add_extra_param_to_notification($thin_app_data){

        /*$request = file_get_contents("php://input");
        $data = json_decode($request, true);
        $role_id = 1;
        $mobile = "";
        $send_array =array();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = array();
            $mobile = isset($data['mobile']) ? $data['mobile'] : "";
            $role_id = isset($data['role_id']) ? $data['role_id'] : "";
        }*/

        $send_array =array();
        $thin_app_id = $thin_app_data['id'];
        $send_array['APP_CATEGORY'] = $thin_app_data['category_name'];
        //$lab_user = Custom::get_lab_user_static_data($thin_app_id,@$send_array['user_mobile'],@$send_array['role_id']);
        //$send_array['lab_user_role'] = $lab_user['lab_user_role'];
        //$send_array['lab_user_id'] = $lab_user['lab_user_id'];
        $send_array['appointment_user_role'] = 'USER';
        /*if (!empty($mobile) && !empty($role_id)) {
            if( $send_array['APP_CATEGORY']=="HOSPITAL"){
                $send_array['appointment_user_role'] = Custom::hospital_get_user_role($mobile, $thin_app_id, $role_id);
            }else{
                $send_array['appointment_user_role'] = Custom::get_appointment_role($mobile, $thin_app_id, $role_id);
            }
        }*/
        return $send_array;

    }
    /**************************CREATED BY VISHWAJEET START*************************/

    public static function get_appointment_stats_graph_value($thinappID){

        $query = "SELECT COUNT(`id`) AS `total`, DATE(created) AS date FROM `appointment_customer_staff_services` WHERE `thinapp_id` = '".$thinappID."' AND `status` <> 'CANCELED' AND `created` BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW() GROUP BY DATE(`created`)";
        $connection = ConnectionUtil::getConnection();
        $dataRS = $connection->query($query);
        $data = mysqli_fetch_all($dataRS,MYSQLI_ASSOC);

        $dataToSend = array();
        $dataToSendArr = array();
        $min = $max = 0;
        foreach($data AS $value)
        {
            $dataToSend[$value['date']] = $value['total'];
            $min = ($value['total'] < $min)?$value['total']:$min;
            $max = ($value['total'] > $max)?$value['total']:$max;
        }

        $period = new DatePeriod(
            new DateTime(date('Y-m-d', strtotime('-6 days'))),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime('+1 days')))
        );

        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d');

            $dataToSendArr[ date('D', strtotime($date)) ] = isset($dataToSend[$date])?(int)$dataToSend[$date]:0;
        }
        $dataReturn['min'] = (int)$min;
        $dataReturn['max'] = (int)$max;

        foreach($dataToSendArr as $key => $val)
        {
            $dataReturn['data'][] = array( 'x'=>$key,'y'=>$val );
            $dataReturn['labels'][] = $key;
        }

        return $dataReturn;


    }


    public static function get_medical_record_stats_graph_value($thinappID){

        $query = "SELECT COUNT(`id`) AS `total`, DATE(created) AS date FROM `drive_files` WHERE `thinapp_id` = '".$thinappID."' AND `created` BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW() GROUP BY DATE(`created`)";
        $connection = ConnectionUtil::getConnection();
        $dataRS = $connection->query($query);
        $data = mysqli_fetch_all($dataRS,MYSQLI_ASSOC);

        $dataToSend = array();
        $dataToSendArr = array();
        $min = $max = 0;
        foreach($data AS $value)
        {
            $dataToSend[$value['date']] = $value['total'];
            $min = ($value['total'] < $min)?$value['total']:$min;
            $max = ($value['total'] > $max)?$value['total']:$max;
        }

        $period = new DatePeriod(
            new DateTime(date('Y-m-d', strtotime('-6 days'))),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime('+1 days')))
        );

        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d');

            $dataToSendArr[ date('D', strtotime($date)) ] = isset($dataToSend[$date])?(int)$dataToSend[$date]:0;
        }
        $dataReturn['min'] = (int)$min;
        $dataReturn['max'] = (int)$max;

        foreach($dataToSendArr as $key => $val)
        {
            $dataReturn['data'][] = array( 'x'=>$key,'y'=>$val );
            $dataReturn['labels'][] = $key;
        }

        return $dataReturn;


    }

    public static function get_app_download_stats_graph_value($thinappID){

        $query = "SELECT COUNT(`id`) AS `total`, DATE(created) AS date FROM `users` WHERE `thinapp_id` = '".$thinappID."' AND `created` BETWEEN (NOW() - INTERVAL 7 DAY) AND NOW() GROUP BY DATE(`created`)";
        $connection = ConnectionUtil::getConnection();
        $dataRS = $connection->query($query);
        $data = mysqli_fetch_all($dataRS,MYSQLI_ASSOC);

        $dataToSend = array();
        $dataToSendArr = array();
        $min = $max = 0;
        foreach($data AS $value)
        {
            $dataToSend[$value['date']] = $value['total'];
            $min = ($value['total'] < $min)?$value['total']:$min;
            $max = ($value['total'] > $max)?$value['total']:$max;
        }

        $period = new DatePeriod(
            new DateTime(date('Y-m-d', strtotime('-6 days'))),
            new DateInterval('P1D'),
            new DateTime(date('Y-m-d', strtotime('+1 days')))
        );

        foreach ($period as $key => $value) {
            $date = $value->format('Y-m-d');

            $dataToSendArr[ date('D', strtotime($date)) ] = isset($dataToSend[$date])?(int)$dataToSend[$date]:0;
        }
        $dataReturn['min'] = (int)$min;
        $dataReturn['max'] = (int)$max;

        foreach($dataToSendArr as $key => $val)
        {
            $dataReturn['data'][] = $val;
            $dataReturn['labels'][] = $key;
        }

        return $dataReturn;


    }


    public static function get_sms_stats_graph_value($thinappID){

        $totalSentSql = "SELECT COUNT(`id`) AS `total` FROM `sent_sms_details` WHERE `thinapp_id` = '".$thinappID."' ";
        $connection = ConnectionUtil::getConnection();
        $totalSentRS = $connection->query($totalSentSql);
        $totalSentData = mysqli_fetch_assoc($totalSentRS);
        $totalSentSMS = $totalSentData['total'];

        $totalRemainingSql = "SELECT `total_transactional_sms` AS `total` FROM `app_sms_statics` WHERE `thinapp_id` = '".$thinappID."' ";
        $totalRemainingRS = $connection->query($totalRemainingSql);
        $totalRemainingData = mysqli_fetch_assoc($totalRemainingRS);
        $totalRemainingSMS = $totalRemainingData['total'];

        $datatoSend = array();
        $datatoSend['totalSentSMS'] = (int)$totalSentSMS;
        $datatoSend['totalRemainingSMS'] = (int)$totalRemainingSMS;

        return $datatoSend;

    }

    public static function get_refer_doc_stats_graph_value($thinappID){

        $totalReferSql = "SELECT COUNT(`id`) AS `total` FROM `doctor_refferals` WHERE `thinapp_id` = '".$thinappID."' ";
        $connection = ConnectionUtil::getConnection();
        $totalReferRS = $connection->query($totalReferSql);
        $totalReferData = mysqli_fetch_assoc($totalReferRS);
        $totalRefer = $totalReferData['total'];

        $totalPaidSql = "SELECT COUNT(`id`) AS `total` FROM `doctor_refferals` WHERE `thinapp_id` = '".$thinappID."' AND `status` = 'PAID' ";
        $totalPaidRS = $connection->query($totalPaidSql);
        $totalPaidData = mysqli_fetch_assoc($totalPaidRS);
        $totalPaid = $totalPaidData['total'];

        $totalContactedSql = "SELECT COUNT(`id`) AS `total` FROM `doctor_refferals` WHERE `thinapp_id` = '".$thinappID."' AND `status` = 'CONTACTED' ";
        $totalContactedRS = $connection->query($totalContactedSql);
        $totalContactedData = mysqli_fetch_assoc($totalContactedRS);
        $totalContacted = $totalContactedData['total'];

        $totalDeniedSql = "SELECT COUNT(`id`) AS `total` FROM `doctor_refferals` WHERE `thinapp_id` = '".$thinappID."' AND `status` = 'DENIED' ";
        $totalDeniedRS = $connection->query($totalDeniedSql);
        $totalDeniedData = mysqli_fetch_assoc($totalDeniedRS);
        $totalDenied = $totalDeniedData['total'];

        $totalConvertedSql = "SELECT COUNT(`id`) AS `total` FROM `doctor_refferals` WHERE `thinapp_id` = '".$thinappID."' AND `status` = 'CONVERTED' ";
        $totalConvertedRS = $connection->query($totalConvertedSql);
        $totalConvertedData = mysqli_fetch_assoc($totalConvertedRS);
        $totalConverted = $totalConvertedData['total'];

        $totalRerefferedSql = "SELECT COUNT(`id`) AS `total` FROM `doctor_refferals` WHERE `thinapp_id` = '".$thinappID."' AND `status` = 'REREFERRED' ";
        $totalRerefferedRS = $connection->query($totalRerefferedSql);
        $totalRerefferedData = mysqli_fetch_assoc($totalRerefferedRS);
        $totalRereffered = $totalRerefferedData['total'];

        $datatoSend = array();
        $datatoSend['totalRefer'] = (int)$totalRefer;
        $datatoSend['totalPaid'] = (int)$totalPaid;
        $datatoSend['totalContacted'] = (int)$totalContacted;
        $datatoSend['totalDenied'] = (int)$totalDenied;
        $datatoSend['totalConverted'] = (int)$totalConverted;
        $datatoSend['totalRereffered'] = (int)$totalRereffered;


        return $datatoSend;

    }



    public static function get_blog_stats_graph_value($thinappID){

        $sql = "SELECT `messages`.`id`, LEFT(`messages`.`title` , 12) AS `title`,
COUNT(`like_data`.`id`) AS `like_count`
FROM `messages`
LEFT JOIN `message_actions` AS `like_data` ON (`like_data`.`message_id` = `messages`.`id` AND `like_data`.`action_type` = 'LIKE')
WHERE `messages`.`thinapp_id` = '".$thinappID."' GROUP BY `messages`.`id` ORDER BY `messages`.`id` DESC LIMIT 8";

        $connection = ConnectionUtil::getConnection();
        $dataRS = $connection->query($sql);
        $likeData = mysqli_fetch_all($dataRS,MYSQLI_ASSOC);

        if(!empty($likeData))
        {
            $lables = array();
            $likes = array();
            $shares = array();
            $views = array();


            $sql = "SELECT `messages`.`id`,
COUNT(`share_data`.`id`) AS `share_count`
FROM `messages`
LEFT JOIN `message_actions` AS `share_data` ON (`share_data`.`message_id` = `messages`.`id` AND `share_data`.`action_type` = 'SHARE')
WHERE `messages`.`thinapp_id` = '".$thinappID."' GROUP BY `messages`.`id` ORDER BY `messages`.`id` DESC LIMIT 8";
            $dataRS = $connection->query($sql);
            $shareData = mysqli_fetch_all($dataRS,MYSQLI_ASSOC);

            $sql = "SELECT `messages`.`id`,
COUNT(`view_data`.`id`) AS `view_count`
FROM `messages`
LEFT JOIN `message_actions` AS `view_data` ON (`view_data`.`message_id` = `messages`.`id` AND `view_data`.`action_type` = 'VIEW')
WHERE `messages`.`thinapp_id` = '".$thinappID."' GROUP BY `messages`.`id` ORDER BY `messages`.`id` DESC LIMIT 8";
            $dataRS = $connection->query($sql);
            $viewData = mysqli_fetch_all($dataRS,MYSQLI_ASSOC);


            foreach($likeData AS $key => $value)
            {
                $lables[] = $value['title'];
                $likes[] = $value['like_count'];
                $shares[] = $shareData[$key]['share_count'];
                $views[] = $viewData[$key]['view_count'];
            }

            $lable = "'".implode("','",$lables)."'";
            $like = "'".implode("','",$likes)."'";
            $share = "'".implode("','",$shares)."'";
            $view = "'".implode("','",$views)."'";

            $dataToSend = array(
                'lable' => $lable,
                'like' => $like,
                'share' => $share,
                'view' => $view
            );

            return $dataToSend;

        }
        else
        {
            return false;
        }


    }

    public static function get_medical_certificate_data($thinappID,$patientID,$patientType,$doctorID){

        $doctorSql = "SELECT `id`,`username`, `mobile`, `address`, `image` FROM `users` WHERE `users`.`id` = '".$doctorID."' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $doctorRS = $connection->query($doctorSql);
        $doctorData = mysqli_fetch_assoc($doctorRS);

        if($patientType == 'CUSTOMER')
        {
            $userSql = "SELECT `id`,`first_name` as `username` FROM `appointment_customers` WHERE `appointment_customers`.`id` = '".$patientID."' LIMIT 1";
            $userRS = $connection->query($userSql);
            $userData = mysqli_fetch_assoc($userRS);
        }
        else
        {

            $userSql = "SELECT `id`,`child_name` as `username` FROM `childrens` WHERE `childrens`.`id` = '".$patientID."' LIMIT 1";
            $userRS = $connection->query($userSql);
            $userData = mysqli_fetch_assoc($userRS);
        }

        return array('doctorData'=>$doctorData,'userData'=>$userData);

    }


    public static function getUHID($thinappID,$customerID = null,$childID = null){

        if($customerID != null && $customerID != '')
        {

            $customerDataSql = "SELECT `created` FROM `appointment_customers` WHERE `id` = '".$customerID."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";

            $connection = ConnectionUtil::getConnection();

            $customerDataRS = $connection->query($customerDataSql);
            $customerRowData = mysqli_fetch_assoc($customerDataRS);
            $created =$customerRowData['created'];


            $customerSql = "SELECT COUNT(`id`) AS `total` FROM `appointment_customers` WHERE `thinapp_id` = '".$thinappID."' AND `created` <= '".$created."' AND YEAR(`created`) = YEAR('".$created."') AND MONTH(`created`) = MONTH('".$created."')";

            $customerRS = $connection->query($customerSql);
            $customerData = mysqli_fetch_assoc($customerRS);
            $subTotal = $customerData['total'];

            $childSql = "SELECT COUNT(`Children`.`id`) AS `total` FROM `childrens` AS `Children` WHERE YEAR(`Children`.`created`) = YEAR('".$created."') AND MONTH(`Children`.`created`) = MONTH('".$created."') AND `created` <= '".$created."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";
            $childRS = $connection->query($childSql);
            $childData = mysqli_fetch_assoc($childRS);
            $total = $subTotal + $childData['total'];

            return date('Ym',strtotime($created)).$total;


        }
        else
        {
            $childDataSql = "SELECT `created` FROM `childrens` WHERE `id` = '".$childID."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";

            $connection = ConnectionUtil::getConnection();

            $childDataRS = $connection->query($childDataSql);
            $childRowData = mysqli_fetch_assoc($childDataRS);
            $created =$childRowData['created'];

            $childSql = "SELECT COUNT(`id`) AS `total` FROM `childrens` WHERE `thinapp_id` = '".$thinappID."' AND `created` <= '".$created."' AND YEAR(`created`) = YEAR('".$created."') AND MONTH(`created`) = MONTH('".$created."')";
            $childRS = $connection->query($childSql);
            $childData = mysqli_fetch_assoc($childRS);
            $subTotal = $childData['total'];

            $customerSql = "SELECT COUNT(`Customer`.`id`) AS `total` FROM `appointment_customers` AS `Customer` WHERE YEAR(`Customer`.`created`) = YEAR('".$created."') AND MONTH(`Customer`.`created`) = MONTH('".$created."') AND `created` <= '".$created."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";
            $customerRS = $connection->query($customerSql);
            $customerData = mysqli_fetch_assoc($customerRS);
            $total = $subTotal + $customerData['total'];

            return date('Ym',strtotime($created)).$total;


        }
    }

    public static function getUHIDBeforInsertCustomer($thinappID,$created = null){
        $connection = ConnectionUtil::getConnection();

        $customerSql = "SELECT COUNT(`id`) AS `total` FROM `appointment_customers` WHERE `thinapp_id` = '".$thinappID."' AND `created` <= '".$created."' AND YEAR(`created`) = YEAR('".$created."') AND MONTH(`created`) = MONTH('".$created."')";

        $customerRS = $connection->query($customerSql);
        $customerData = mysqli_fetch_assoc($customerRS);
        $subTotal = $customerData['total'];

        $childSql = "SELECT COUNT(`Children`.`id`) AS `total` FROM `childrens` AS `Children` WHERE YEAR(`Children`.`created`) = YEAR('".$created."') AND MONTH(`Children`.`created`) = MONTH('".$created."') AND `created` <= '".$created."' AND `thinapp_id` = '".$thinappID."' LIMIT 1";
        $childRS = $connection->query($childSql);
        $childData = mysqli_fetch_assoc($childRS);
        $total = $subTotal + $childData['total'];

        return date('Ym',strtotime($created)).$total;

    }

    /**************************CREATED BY VISHWAJEET END*************************/


    public static function createTrackerDataCache($doctor_id,$tracker_data){
        $file_name = Custom::encrypt_decrypt('encrypt',"tracker_$doctor_id");
        return WebservicesFunction::createJson($file_name,json_encode($tracker_data),"CREATE","tracker");
    }

    public static function deleteDoctorTrackerCache($doctor_id){
        $file_name = Custom::encrypt_decrypt('encrypt',"tracker_$doctor_id");
        return WebservicesFunction::deleteJson(array($file_name),'tracker');
    }


    public static function getDoctorWebTrackerData($doctor_id_array,$hasOnlyPaid = false,$thin_app_id = 0){
        $limit = count($doctor_id_array);
        //$doctor_id_array = '"'.implode('","', $doctor_id_array).'"';
        $doctor_id_array = "'".implode("','", $doctor_id_array)."'";
        $order_by = " order by TIME(acss.appointment_datetime) asc, acss.id asc, acss.queue_number asc ";
        $paymentCondition = "";
        if($hasOnlyPaid == true){
            $paymentCondition = " AND acss.payment_status = 'SUCCESS' ";
        }
        $condition= " and acss.emergency_appointment ='NO' $paymentCondition ";
        if(Custom::check_app_enable_permission($thin_app_id,"SMART_CLINIC")){
            $tracker_assign = Custom::getThinAppData($thin_app_id)['smart_clinic_tracker_queue'];
            $order_key = ($tracker_assign=="MANUAL_ASSIGN")?"DESC":"ASC";
            $order_by = " ORDER BY CASE WHEN acss.patient_queue_type='NONE' THEN CAST(acss.queue_number  AS DECIMAL(10,2)) WHEN acss.patient_queue_type <> 'NONE' THEN CAST(acss.show_after_queue AS DECIMAL(10,2)) END ASC, acss.queue_assign_type, acss.queue_check_in_datetime $order_key ";
            $condition = " AND (acss.emergency_appointment ='NO' OR (acss.patient_queue_type='EMERGENCY_CHECKIN' AND acss.patient_queue_checked_in ='YES')) and ( acss.patient_queue_type='NONE' OR acss.patient_queue_checked_in='YES' ) AND acss.patient_queue_type<>'LAB_TEST' ";
        }

        $connection = ConnectionUtil::getConnection();

        $query = "select  app_sta.allow_emergency_appointment, acss.emergency_appointment, acss.patient_queue_type, acss.show_after_queue, IFNULL((SELECT status FROM active_tracker_voice WHERE appointment_staff_id = acss.appointment_staff_id LIMIT 1),'ACTIVE') as play_status, acss.id AS appointment_id, acss.appointment_staff_id, acss.appointment_datetime, acss.sub_token, acss.has_token, IFNULL(ac.first_name,c.child_name) as patient_name, if(acss.has_token='NO', CONCAT('WI-',acss.queue_number), acss.queue_number) as queue_number, acss.slot_time, abs.id as blocked_id, acss.appointment_staff_id as doctor_id, acss.id from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id LEFT join appointment_bloked_slots as abs on abs.doctor_id = acss.appointment_staff_id and acss.appointment_address_id = abs.address_id and abs.is_date_blocked = 'YES' and abs.book_date = DATE(acss.appointment_datetime) left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id left join appointment_staffs as app_sta on app_sta.id = acss.appointment_staff_id where ( acss.status IN ('NEW','CONFIRM','RESCHEDULE') OR ( acss.status ='REFUND' AND mpo.total_amount > 0 ))  and ( acss.payment_status = 'SUCCESS' OR acss.checked_in ='YES') AND acss.skip_tracker = 'NO' and acss.appointment_staff_id IN($doctor_id_array) and DATE(acss.appointment_datetime) = DATE(NOW()) $condition $order_by";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $list =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $tmp =array();
            foreach($list as $key =>$val){
                if(!array_key_exists($val['doctor_id'],$tmp) && empty($val['blocked_id'])){
                    $tmp[$val['doctor_id']]['play_status'] = $val['play_status'];
                    $tmp[$val['doctor_id']]['appointment_id'] = $val['appointment_id'];
                    $tmp[$val['doctor_id']]['appointment_datetime'] = $val['appointment_datetime'];
                    $tmp[$val['doctor_id']]['doctor_id'] = $val['id'];
                    $tmp[$val['doctor_id']]['next_patient_name']='';
                    $tmp[$val['doctor_id']]['next_patient_token']='';
                    $tmp[$val['doctor_id']]['next_patient_slot']='';
                    $tmp[$val['doctor_id']]['next_sub_token']='NO';
                    $tmp[$val['doctor_id']]['next_has_token']='NO';
                    $tmp[$val['doctor_id']]['allow_emergency_appointment'] = 'NO';
                }else{
                    if(empty($tmp[$val['doctor_id']]['next_patient_token'])){
                        $tmp[$val['doctor_id']]['play_status'] = $val['play_status'];
                        $tmp[$val['doctor_id']]['appointment_id'] = $val['appointment_id'];
                        $tmp[$val['doctor_id']]['appointment_datetime'] = $val['appointment_datetime'];
                        $tmp[$val['doctor_id']]['next_patient_name']=$val['patient_name'];

                        $tmp[$val['doctor_id']]['next_patient_token']=Custom::create_queue_number($val);
                        $tmp[$val['doctor_id']]['next_patient_slot']=$val['slot_time'];
                        $tmp[$val['doctor_id']]['next_sub_token']=$val['sub_token'];
                        $tmp[$val['doctor_id']]['next_has_token']=$val['has_token'];
                        $tmp[$val['doctor_id']]['allow_emergency_appointment'] = @$val['allow_emergency_appointment'];
                        $tmp[$val['doctor_id']]['has_token'] = $val['has_token'];
                        $tmp[$val['doctor_id']]['sub_token'] = $val['sub_token'];
                        $tmp[$val['doctor_id']]['next_patient_queue_type'] = $val['patient_queue_type'];
                        $tmp[$val['doctor_id']]['next_emergency_appointment'] = $val['emergency_appointment'];
                        $tmp[$val['doctor_id']]['next_show_after_queue'] = !empty($val['show_after_queue'])?$val['show_after_queue']:$val['queue_number'];
                    }
                }
            }
             $appointment_id_array = array_column(array_values($tmp),'doctor_id') ;
            $appointment_id_array = '"'.implode('","', $appointment_id_array).'"';
            $query = "select acss.emergency_appointment, acss.id AS appointment_id, acss.patient_queue_type, acss.show_after_queue, app_sta.show_extend_time_on_tracker, app_sta.allow_emergency_appointment, IFNULL((SELECT status FROM active_tracker_voice WHERE appointment_staff_id = acss.appointment_staff_id LIMIT 1),'ACTIVE') as play_status, app_sta.room_number, acss.appointment_staff_id as doctor_id, t.name as app_name, t.show_tracker_time, app_sta.name as doctor_name, IFNULL(ac.first_name,c.child_name) as patient_name,if(acss.has_token='NO', CONCAT('WI-',acss.queue_number), acss.queue_number) as token_number,acss.slot_time as time_slot, app_sta.profile_photo as doctor_image, cat.name as doctor_category, IF(t.show_sub_token_name_on_tracker='YES','NO',acss.sub_token) AS sub_token, acss.has_token from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id left join appointment_staffs as app_sta on app_sta.id = acss.appointment_staff_id left join appointment_categories as cat on cat.id = app_sta.appointment_category_id left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id where ( acss.status IN ('NEW','CONFIRM','RESCHEDULE') OR ( acss.status ='REFUND' AND mpo.total_amount > 0 ))  and ( acss.payment_status = 'SUCCESS' OR acss.checked_in ='YES') AND acss.skip_tracker = 'NO' AND acss.id IN($appointment_id_array) order by acss.appointment_staff_id asc";
            
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                $tmp_list = array();
                foreach($list as $key => $value){
                    $token = Custom::tracker_labels($value['patient_queue_type'],$value['sub_token']);
                    $value['queue_number']=$value['token_number'];
                    $value['token_number']=!empty($token)?$token:Custom::create_queue_number($value);
                    $tmp_list[$key] = $value;
                    $tmp_list[$key]['next_patient_name']=!empty($tmp[$value['doctor_id']]['next_patient_name'])?$tmp[$value['doctor_id']]['next_patient_name']:'N/A';
                    $tmp_list[$key]['next_patient_token'] =!empty($tmp[$value['doctor_id']]['next_patient_token'])?$tmp[$value['doctor_id']]['next_patient_token']:'N/A';

                    $tmp_list[$key]['next_patient_slot']=!empty($tmp[$value['doctor_id']]['next_patient_slot'])?$tmp[$value['doctor_id']]['next_patient_slot']:'N/A';
                    $tmp_list[$key]['next_appointment_datetime']=!empty($tmp[$value['doctor_id']]['appointment_datetime'])?$tmp[$value['doctor_id']]['appointment_datetime']:'N/A';
                    $tmp_list[$key]['next_sub_token']=@$tmp[$value['doctor_id']]['next_sub_token'];
                    $tmp_list[$key]['next_has_token']=@$tmp[$value['doctor_id']]['next_has_token'];
                    $tmp_list[$key]['next_patient_queue_type']=@$tmp[$value['doctor_id']]['next_patient_queue_type'];

                }
                return $tmp_list;
            }else{
                return false;
            }
        }else{
            return false;
        }



    }

    public static function getDoctorWebTrackerDataUpcomingList($doctor_id_array,$thin_app_id=0,$upcoming = 6,$address_id=0){
        $condition = "";
        $total_doctor = count($doctor_id_array);
        $limit = $total_doctor * $upcoming;
        $doctor_id_array = '"'.implode('","', $doctor_id_array).'"';
        $condition = !empty($address_id)?" and acss.appointment_address_id = $address_id ":"";
        $connection = ConnectionUtil::getConnection();
        $order_by = " order by acss.appointment_datetime asc, acss.queue_number asc ";
        if(Custom::check_app_enable_permission($thin_app_id,"SMART_CLINIC")){
            $tracker_assign = Custom::getThinAppData($thin_app_id)['smart_clinic_tracker_queue'];
            $order_key = ($tracker_assign=="MANUAL_ASSIGN")?"DESC":"ASC";
            $order_by = " ORDER BY CASE WHEN acss.patient_queue_type='NONE' THEN CAST(acss.queue_number  AS DECIMAL(10,2)) WHEN acss.patient_queue_type <> 'NONE' THEN CAST(acss.show_after_queue AS DECIMAL(10,2)) END ASC, acss.queue_assign_type, acss.queue_check_in_datetime $order_key ";
            $condition .= " AND ((acss.emergency_appointment ='YES' AND acss.checked_in='YES') OR (acss.emergency_appointment ='NO') OR (acss.patient_queue_type='EMERGENCY_CHECKIN' AND acss.patient_queue_checked_in ='YES')) and ( acss.patient_queue_type='NONE' OR acss.patient_queue_checked_in='YES' ) AND acss.patient_queue_type <> 'LAB_TEST' ";
        }

        $query = "select acss.consulting_type, app_staff.show_extend_time_on_tracker,  acss.id as appointment_id, acss.appointment_datetime, app_staff.show_appointment_time as show_tracker_time, acss.emergency_appointment, acss.patient_queue_type, acss.show_after_queue, app_staff.show_extend_time_on_tracker, app_staff.allow_emergency_appointment, t.name as app_name, acss.appointment_staff_id, acss.appointment_service_id, acss.appointment_address_id, acss.appointment_datetime, t.show_tracker_time, app_staff.profile_photo, app_staff.room_number, cat.name as department, app_staff.name as doctor_name, IF(t.show_sub_token_name_on_tracker='YES','NO',acss.sub_token) AS sub_token, acss.has_token, IFNULL(ac.first_name,c.child_name) as patient_name, if(acss.has_token='NO',CONCAT('WI-',acss.queue_number),acss.queue_number) AS queue_number, acss.slot_time, abs.id as blocked_id, acss.appointment_staff_id as doctor_id, acss.id from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id join appointment_staffs as app_staff  on app_staff.id = acss.appointment_staff_id left join appointment_categories as cat on cat.id = app_staff.appointment_category_id left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id LEFT join appointment_bloked_slots as abs on abs.doctor_id = acss.appointment_staff_id and acss.appointment_address_id = abs.address_id and abs.is_date_blocked = 'YES' and abs.book_date = DATE(acss.appointment_datetime) left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id where  ( acss.status IN ('NEW','CONFIRM','RESCHEDULE') OR ( acss.status ='REFUND' AND mpo.total_amount > 0 ))  and ( (acss.payment_status = 'SUCCESS' and acss.booking_validity_attempt =1 ) OR acss.checked_in ='YES') AND acss.skip_tracker = 'NO'  AND  acss.appointment_staff_id IN($doctor_id_array) and DATE(acss.appointment_datetime) = DATE(NOW())   $condition  $order_by limit $limit";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $list =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $tmp = $doctor_ids_array = array();
            $service_id = @$list[0]['appointment_service_id'];
            $doctor_id = @$list[0]['appointment_staff_id'];
            $appointment_date = @date('Y-m-d',strtotime($list[0]['appointment_datetime']));
            $address_id = @$list[0]['appointment_address_id'];
            foreach($list as $key =>$val){
                if(@count($tmp[$val['doctor_id']]['upcoming']) < $upcoming ){
                    $tmp[$val['doctor_id']]['doctor_name'] = $val['doctor_name'];
                    $tmp[$val['doctor_id']]['app_name'] = $val['app_name'];
                    $tmp[$val['doctor_id']]['room_number'] = $val['room_number'];
                    $tmp[$val['doctor_id']]['doctor_category'] = $val['department'];
                    $tmp[$val['doctor_id']]['doctor_id'] = $val['doctor_id'];
                    $tmp[$val['doctor_id']]['doctor_image'] = $val['profile_photo'];
                    $tmp[$val['doctor_id']]['allow_emergency_appointment'] = $val['allow_emergency_appointment'];
                    $tmp[$val['doctor_id']]['show_tracker_time'] = $val['show_tracker_time'];
                	


                    $time = date('h:i A',strtotime($val['appointment_datetime']));
                    if($val['show_extend_time_on_tracker']=="YES"){
                        $total_update_minutes = Custom::get_total_difference_minutes($service_id, $address_id, $val['doctor_id'], $val['appointment_datetime'],true);
                        if(!empty($total_update_minutes)){
                            $selectedTime = date('H:i:s',strtotime($val['appointment_datetime']));
                            $minutes_string = ($total_update_minutes >= 0)?"+$total_update_minutes minutes":"$total_update_minutes minutes";
                            $time = strtotime($minutes_string, strtotime($selectedTime));
                            $time = date('h:i A', $time);
                        }
                    }

                    if(!in_array($val['doctor_id'],$doctor_ids_array)){
                        $tmp[$val['doctor_id']]['current_patient'] = $val['patient_name'];
                        $tmp[$val['doctor_id']]['appointment_id'] = $val['appointment_id'];
                        $tmp[$val['doctor_id']]['show_extend_time_on_tracker'] = $val['show_extend_time_on_tracker'];
                        $tmp[$val['doctor_id']]['current_token'] = Custom::create_queue_number($val);
                        $tmp[$val['doctor_id']]['current_time'] = $time;
                        $tmp[$val['doctor_id']]['has_token'] = $val['has_token'];
                        $tmp[$val['doctor_id']]['sub_token'] = $val['sub_token'];
                        $tmp[$val['doctor_id']]['patient_queue_type'] = $val['patient_queue_type'];
                        $tmp[$val['doctor_id']]['emergency_appointment'] = $val['emergency_appointment'];
                        $tmp[$val['doctor_id']]['appointment_datetime'] = $val['appointment_datetime'];
                    	$tmp[$val['doctor_id']]['consulting_type'] = $val['consulting_type'];
                        $tmp[$val['doctor_id']]['show_after_queue'] = !empty($val['show_after_queue'])?$val['show_after_queue']:$val['queue_number'];
                    }else{
                        $tmp[$val['doctor_id']]['upcoming'][] = array(
                            'patient_name'=>$val['patient_name'],
                            'queue_number'=>Custom::create_queue_number($val),
                            'show_extend_time_on_tracker'=>$val['show_extend_time_on_tracker'],
                            'appointment_id'=>$val['appointment_id'],
                            'slot_time'=>$time,
                            'has_token'=>$val['has_token'],
                            'patient_queue_type'=>$val['patient_queue_type'],
                            'emergency_appointment'=>$val['emergency_appointment'],
                            'appointment_datetime'=>$val['appointment_datetime'],
                            'show_after_queue'=>!empty($val['show_after_queue'])?$val['show_after_queue']:$val['queue_number'],
                            'sub_token'=>$val['sub_token'],
                        	'consulting_type'=>$val['consulting_type']
                        );
                    }
                }

                $doctor_ids_array[$val['doctor_id']] =$val['doctor_id'];

            }
            return $tmp;
        }else{
            return false;
        }
    }


    public static function getDoctorPendingQueueTrackerData($doctor_id_array){
        $condition = " acss.patient_queue_type != 'NONE' AND acss.patient_queue_checked_in = 'YES'";
        $total_doctor = count($doctor_id_array);
        $limit = $total_doctor * 6;
        $doctor_id_array = '"'.implode('","', $doctor_id_array).'"';
        $connection = ConnectionUtil::getConnection();
        $query = "select acss.patient_queue_type, acss.emergency_appointment,  acss.show_after_queue, app_staff.show_extend_time_on_tracker, app_staff.allow_emergency_appointment, t.name as app_name, acss.appointment_staff_id, acss.appointment_service_id, acss.appointment_address_id, acss.appointment_datetime, t.show_tracker_time, app_staff.profile_photo, app_staff.room_number, cat.name as department, app_staff.name as doctor_name,  acss.sub_token, acss.has_token, IFNULL(ac.first_name,c.child_name) as patient_name, if(acss.has_token='NO',CONCAT('WI-',acss.queue_number),acss.queue_number) AS queue_number, acss.slot_time, abs.id as blocked_id, acss.appointment_staff_id as doctor_id, acss.id from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id join appointment_staffs as app_staff  on app_staff.id = acss.appointment_staff_id left join appointment_categories as cat on cat.id = app_staff.appointment_category_id left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id LEFT join appointment_bloked_slots as abs on abs.doctor_id = acss.appointment_staff_id and acss.appointment_address_id = abs.address_id and abs.is_date_blocked = 'YES' and abs.book_date = DATE(acss.appointment_datetime) where acss.status IN ('NEW','CONFIRM','RESCHEDULE') AND acss.skip_tracker = 'NO' and acss.appointment_staff_id IN($doctor_id_array) and DATE(acss.appointment_datetime) = DATE(NOW()) and acss.emergency_appointment ='NO' and $condition order by acss.appointment_datetime asc, acss.queue_number asc limit $limit";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $list =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $return = array();
            foreach($list as $key =>$val){
                $time = date('h:i A',strtotime($val['appointment_datetime']));
                $tmp = array(
                    'queue_number'=>Custom::create_queue_number($val),
                    'patient_name'=>$val['patient_name'],
                    'patient_queue_type'=>$val['patient_queue_type'],
                    'show_after_queue'=>$val['show_after_queue'],
                    'has_token'=>$val['has_token'],
                    'sub_token'=>$val['sub_token'],
                    'doctor_name'=>$val['doctor_name'],
                    'room_number'=>$val['room_number'],
                    'doctor_category'=>$val['department'],
                    'doctor_id'=>$val['doctor_id'],
                    'doctor_image'=>$val['profile_photo'],
                    'allow_emergency_appointment'=>$val['allow_emergency_appointment'],
                    'sub_token'=>$val['sub_token'],
                    'slot_time'=>$time
                );


                $return[$val['doctor_id']][] =$tmp;
            }
            return $return;
        }else{
            return false;
        }
    }

   


    public static function getDoctorWebTrackerDataUpcomingListNew($doctor_id_array,$hasOnlyPaid = false){

        if(!is_array($doctor_id_array))
        {
            $doctor_id_array = explode(',',$doctor_id_array);
        }
        $total_doctor = count($doctor_id_array);
        $limit = $total_doctor * 6;
        $doctor_id_array = '"'.implode('","', $doctor_id_array).'"';
        $connection = ConnectionUtil::getConnection();
        $paymentCondition = "";
        if($hasOnlyPaid == true)
        {
            $paymentCondition = " AND acss.payment_status = 'SUCCESS' ";
        }
        $condition = "";
        $order_by = " order by acss.appointment_datetime asc, acss.queue_number asc ";
        $thin_app_id = 0;
        $thinappSql = "SELECT staff.thinapp_id, t.kiosk_booking_auto_checkin FROM appointment_staffs as staff join thinapps as t on t.id = staff.thinapp_id WHERE staff.id IN (".$doctor_id_array.") LIMIT 1";
        $thinappRS = $connection->query($thinappSql);
        if ($thinappRS->num_rows) {
            $thinappData =  mysqli_fetch_assoc($thinappRS);
            $thin_app_id = $thinappData['thinapp_id'];
            if($thinappData['kiosk_booking_auto_checkin']=="YES"){
                $paymentCondition = '';
            }
        }
        if(Custom::check_app_enable_permission($thin_app_id,"SMART_CLINIC")){
            $tracker_assign = Custom::getThinAppData($thin_app_id)['smart_clinic_tracker_queue'];
            $order_key = ($tracker_assign=="MANUAL_ASSIGN")?"DESC":"ASC";
            $order_by = " ORDER BY CASE WHEN acss.patient_queue_type='NONE' THEN CAST(acss.queue_number  AS DECIMAL(10,2)) WHEN acss.patient_queue_type <> 'NONE' THEN CAST(acss.show_after_queue AS DECIMAL(10,2)) END ASC, acss.queue_assign_type, acss.queue_check_in_datetime $order_key ";
                // $order = " ORDER BY CASE WHEN acss.patient_queue_type='NONE' THEN acss.appointment_datetime WHEN acss.patient_queue_type <> 'NONE' THEN CAST(acss.show_after_queue AS DECIMAL(10,2)) END ASC, acss.queue_assign_type, acss.queue_check_in_datetime $order_key";
       
            
        $condition .= " AND ((acss.emergency_appointment ='YES' AND acss.checked_in='YES') OR (acss.emergency_appointment ='NO') OR (acss.patient_queue_type='EMERGENCY_CHECKIN' AND acss.patient_queue_checked_in ='YES')) and ( acss.patient_queue_type='NONE' OR acss.patient_queue_checked_in='YES' ) AND acss.patient_queue_type <> 'LAB_TEST' ";
        }

        $query = "select acss.appointment_booked_from, acss.consulting_type, acss.emergency_appointment,acss.patient_queue_type, acss.show_after_queue,app_staff.show_extend_time_on_tracker, app_staff.allow_emergency_appointment,t.name as app_name, acss.appointment_staff_id,acss.appointment_service_id, acss.appointment_address_id,acss.appointment_datetime, t.show_tracker_time,app_staff.profile_photo, app_staff.room_number,cat.name as department, app_staff.name as doctor_name,acss.sub_token, acss.has_token,IFNULL(ac.first_name,c.child_name) as patient_name,if(acss.has_token='NO',CONCAT('WI-',acss.queue_number),acss.queue_number) AS queue_number, acss.slot_time,abs.id as blocked_id,acss.appointment_staff_id as doctor_id,acss.id from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id join appointment_staffs as app_staff  on app_staff.id = acss.appointment_staff_id left join appointment_categories as cat on cat.id = app_staff.appointment_category_id	left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id LEFT join appointment_bloked_slots as abs on abs.doctor_id = acss.appointment_staff_id and acss.appointment_address_id = abs.address_id and abs.is_date_blocked = 'YES' and abs.book_date = DATE(acss.appointment_datetime)	left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id where	( acss.status IN ('NEW','CONFIRM','RESCHEDULE') OR ( acss.status ='REFUND' AND mpo.total_amount > 0 )) and ( (acss.payment_status = 'SUCCESS' and acss.booking_validity_attempt = 1 ) OR acss.checked_in ='YES')	AND acss.skip_tracker = 'NO' $paymentCondition AND  acss.appointment_staff_id IN($doctor_id_array) and DATE(acss.appointment_datetime) = DATE(NOW()) $condition  $order_by limit $limit";
    
    	
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $list =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $tmp = $doctor_ids_array = array();
            $service_id = @$list[0]['appointment_service_id'];
            $doctor_id = @$list[0]['appointment_staff_id'];
            $appointment_date = @date('Y-m-d',strtotime($list[0]['appointment_datetime']));
            $address_id = @$list[0]['appointment_address_id'];
            foreach($list as $key =>$val){
                if(@count($tmp[$val['doctor_id']]['upcoming']) < 5 ){
                    $docID = $val['doctor_id'];
                    $playStatus = Custom::getDoctorVoiceStatus($docID);
                    $tmp[$val['doctor_id']]['play_status'] = $playStatus;
                    $tmp[$val['doctor_id']]['doctor_name'] = $val['doctor_name'];
                    $tmp[$val['doctor_id']]['app_name'] = $val['app_name'];
                    $tmp[$val['doctor_id']]['room_number'] = $val['room_number'];
                    $tmp[$val['doctor_id']]['doctor_category'] = $val['department'];
                    $tmp[$val['doctor_id']]['doctor_id'] = $val['doctor_id'];
                    $tmp[$val['doctor_id']]['doctor_image'] = $val['profile_photo'];
                    $tmp[$val['doctor_id']]['allow_emergency_appointment'] = $val['allow_emergency_appointment'];

                    $time = date('h:i A',strtotime($val['appointment_datetime']));
                    if($val['show_extend_time_on_tracker']=="YES"){
                        $total_update_minutes = Custom::get_total_difference_minutes($service_id, $address_id, $val['doctor_id'], $val['appointment_datetime'],true);
                        if(!empty($total_update_minutes)){
                            $selectedTime = date('H:i:s',strtotime($val['appointment_datetime']));
                            $minutes_string = ($total_update_minutes >= 0)?"+$total_update_minutes minutes":"$total_update_minutes minutes";
                            $time = strtotime($minutes_string, strtotime($selectedTime));
                            $time = date('h:i A', $time);
                        }
                    }

                    if(!in_array($val['doctor_id'],$doctor_ids_array)){
                        $tmp[$val['doctor_id']]['current_patient'] = $val['patient_name'];
                        $tmp[$val['doctor_id']]['current_token'] = Custom::create_queue_number($val);
                        $tmp[$val['doctor_id']]['current_time'] = $time;
                        $tmp[$val['doctor_id']]['has_token'] = $val['has_token'];
                        $tmp[$val['doctor_id']]['sub_token'] = $val['sub_token'];
                        $tmp[$val['doctor_id']]['patient_queue_type'] = $val['patient_queue_type'];
                        $tmp[$val['doctor_id']]['emergency_appointment'] = $val['emergency_appointment'];
                    	$tmp[$val['doctor_id']]['consulting_type'] = $val['consulting_type'];
                    	$tmp[$val['doctor_id']]['appointment_booked_from'] = $val['appointment_booked_from'];
                        $tmp[$val['doctor_id']]['show_after_queue'] = !empty($val['show_after_queue'])?$val['show_after_queue']:$val['queue_number'];
                    }else{
                        $tmp[$val['doctor_id']]['upcoming'][] = array(
                            'patient_name'=>$val['patient_name'],
                            'queue_number'=>Custom::create_queue_number($val),
                            'slot_time'=>$time,
                            'has_token'=>$val['has_token'],
                            'patient_queue_type'=>$val['patient_queue_type'],
                            'emergency_appointment'=>$val['emergency_appointment'],
                            'show_after_queue'=>!empty($val['show_after_queue'])?$val['show_after_queue']:$val['queue_number'],
                            'sub_token'=>$val['sub_token'],
                        	'appointment_booked_from'=>$val['appointment_booked_from'],
                        	 'consulting_type'=>$val['consulting_type']
                        );
                    }
                }

                $doctor_ids_array[$val['doctor_id']] =$val['doctor_id'];

            }
            return $tmp;
        }else{
            return false;
        }
    }










    public static function get_device_data($device_id){
        $query = "select * from user_devices  where device_id = '$device_id' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return 0;
        }
    }


    public static function add_request_hit($thin_app_id,$action){
        /*try{
            $query = "INSERT INTO request_hits (thinapp_id,acton_name) values (?, ?)";
            $connection = ConnectionUtil::getConnection();
            $stmt = $connection->prepare($query);
            $stmt->bind_param('ss', $thin_app_id, $action);
            $stmt->execute();

        }catch (Exception $e){

        }*/
        return true;
    }
    public static function add_default_login_patient($post){
        return WebServicesFunction_2_3::tab_add_patient($post);
    }


    public static function get_doctor_info($doctor_id){
        
    
        
         
        
   $query = "SELECT  app_st.payment_mode, app_st.show_fees, app_st.show_mobile, app_st.sub_title, app_st.load_default_screen_on_dti, app_st.allow_upcoming_days_booking, app_st.increase_token_on_traker, app_st.other_check_in_queue_after_number, t.tune_tracker_media, t.website_url, di.doctor_code, di.ivr_number, app_st.description, t.name as web_app_name, address.address as app_address, asa.appointment_address_id as address_id, app_ser.name AS service_name, asa.id AS assoc_address_id, asa.from_time, asa.to_time, t.category_name, t.allowed_doctor_count, app_ser.id as service_id, app_st.department_category_id, app_st.country_id, app_st.city_id, app_st.state_id, t.allow_only_mobile_number_booking, t.package_name, app_st.emergency_appointment_fee, app_st.allow_emergency_appointment, app_ser.service_amount, app_ser.video_consulting_amount, app_ser.audio_consulting_amount, app_ser.chat_consulting_amount,  t.apk_url, app_st.thinapp_id, app_st.appointment_category_id, app_st.is_audio_consulting, app_st.is_chat_consulting, app_st.is_online_consulting, app_st.is_offline_consulting, dep_c.category_name as doctor_category, app_st.registration_number, t.logo, app_st.dob, app_st.profile_photo, app_st.id as doctor_id, t.id as thin_app_id, u.id as user_id, u.role_id, app_st.name as app_name, t.category_name, app_st.name, app_st.sub_title as education, dc.name as department_name, app_st.description, app_st.experience, app_st.mobile, app_st.show_mobile, app_st.email, app_st.address, app_ser.service_slot_duration, c.name as country_name, s.name as state_name, city.name as city_name, app_st.facebook_url, app_st.twitter_url, app_st.linkedin_url, app_st.instagram_url from appointment_staffs as app_st join thinapps as t on t.id = app_st.thinapp_id left join users as u on u.id = app_st.user_id left join department_categories as dep_c on dep_c.id = app_st.department_category_id left join appointment_categories as dc on app_st.appointment_category_id = dc.id left join appointment_staff_services as ass on ass.appointment_staff_id = app_st.id left join appointment_services as app_ser on app_ser.id = ass.appointment_service_id LEFT JOIN appointment_staff_addresses AS asa ON asa.appointment_staff_id = app_st.id left join appointment_addresses as address on address.id = asa.appointment_address_id left join countries as c on c.id = app_st.country_id left join states as s on s.id = app_st.state_id left join cities as city on city.id = app_st.city_id LEFT JOIN doctors_ivr AS di ON di.doctor_id = app_st.id where app_st.id =  $doctor_id group by app_st.id";
        
        
    
    $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function getDoctorAppTrackerData($user_id){
        $query = "select asa.from_time, asa.to_time, acss.id, acss.status, app_sta.id as doctor_id, t.name as app_name, app_sta.name as doctor_name, IFNULL(ac.first_name,c.child_name) as patient_name,acss.queue_number as token_number,acss.slot_time as time_slot from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id join user_enabled_fun_permissions as uefp on uefp.thinapp_id = t.id and uefp.user_functionality_type_id = 51 and uefp.permission = 'YES' left join appointment_customers as ac on ac.id = acss.appointment_customer_id and ac.user_id = $user_id left join childrens as c on c.id = acss.children_id and  c.user_id = $user_id left join appointment_staffs as app_sta on app_sta.id = acss.appointment_staff_id left join appointment_staff_addresses as asa on asa.appointment_staff_id = app_sta.id and TIME(STR_TO_DATE(asa.from_time, '%h:%i %p')) <= TIME(NOW()) and TIME(STR_TO_DATE(asa.to_time, '%h:%i %p')) >= TIME(NOW())  where acss.status IN ('NEW','CONFIRM','RESCHEDULE','CLOSED') AND acss.skip_tracker = 'NO' and  DATE(acss.appointment_datetime) = DATE(NOW()) group by acss.id order by TIME(acss.appointment_datetime) asc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $result =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $filter_array =array();
            foreach($result as $key => $val){
                $filter_array[$val['doctor_id']][] = $val;
            }
            return $filter_array;
        }else{
            return false;
        }
    }

    public static function get_doctor_by_user_id($user_id){
        $query = "select * from appointment_staffs  where staff_type= 'DOCTOR' and status='ACTIVE' AND user_id = $user_id  limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_doctor_blocked_date($thin_app_id,$address_id,$doctor_id,$limit,$start_date = null, $new_appointment = 'NO'){
        $connection = ConnectionUtil::getConnection();
        $start_date = empty($start_date)?date('Y-m-d'):$start_date;
        $start_date = $start_date->format('Y-m-d');

        if($new_appointment == "YES"){
            $query = "select book_date from appointment_bloked_slots where thinapp_id = $thin_app_id and new_appointment = '$new_appointment' AND doctor_id = $doctor_id and DATE(book_date) >= '$start_date' AND is_date_blocked = 'YES'";
        }else{
            $query = "select book_date from appointment_bloked_slots where thinapp_id = $thin_app_id and new_appointment = '$new_appointment' AND doctor_id = $doctor_id and address_id = $address_id and DATE(book_date) >= '$start_date' AND is_date_blocked = 'YES' limit $limit";
        }

        $service_message_list = $connection->query($query);
        if($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'book_date');

        }else{
            return array();
        }
    }

    public static function check_doctor_active_login_status($doctor_id,$password){
        if(!empty($doctor_id) && !empty($password)){
            $file_name = Custom::encrypt_decrypt('encrypt',"doctor_$doctor_id");
            if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"login_users"),true)){
                $connection = ConnectionUtil::getConnection();
                $query = "select password from appointment_staffs  where  id = $doctor_id and password = '$password' and status ='ACTIVE' limit 1";
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $staff_data = true;
                    WebservicesFunction::createJson($file_name,json_encode($staff_data['password']),"CREATE","login_users");
                }
            }
            if (!empty($staff_data)) {
                return $staff_data;
            }
        }
        return false;
    }

    public static function check_lab_pharmacy_active_login_status($doctor_id,$password){
        $file_name = Custom::encrypt_decrypt('encrypt',"lab_$doctor_id");
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"login_users"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select password from lab_pharmacy_users  where  id = $doctor_id and password = '$password' and status ='ACTIVE' and request_status = 'APPROVED' limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = true;
                WebservicesFunction::createJson($file_name,json_encode($staff_data['password']),"CREATE","login_lab_pharmacy");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }

    }

    public static function check_dental_supplier_active_login_status($dental_supplier_id,$password){
        $file_name = Custom::encrypt_decrypt('encrypt',"dental_supplier_$dental_supplier_id");
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"login_users"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select password from dental_suppliers  where  id = $dental_supplier_id and password = '$password' and status ='ACTIVE' limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = true;
                WebservicesFunction::createJson($file_name,json_encode($staff_data['password']),"CREATE","login_dental_supplier");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }

    }

    public static function create_user($thin_app_id, $mobile,$name ){

        $user_data = Custom::get_user_by_mobile($thin_app_id,$mobile);
        if(empty($user_data)){
            $user_role_id = 1;
            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $sql = "INSERT INTO users (role_id, username, mobile, thinapp_id, created, modified) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_user = $connection->prepare($sql);
            $stmt_user->bind_param('ssssss',  $user_role_id, $name, $mobile, $thin_app_id, $created, $created);
            if ($stmt_user->execute()) {
                return $stmt_user->insert_id;
            }
            return 0;
        }else{
            return $user_data['id'];
        }

    }



    public static function check_patient_default_folder($thin_app_id, $patient_type,$patient_id){
        $label = ' appointment_customer_id ';
        if($patient_type=='CHILDREN'){
            $label = " children_id ";
        }
        $query = "select id from drive_folders  where thinapp_id = $thin_app_id and $label = $patient_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['id'];
        }else{
            return 0;
        }
    }

    public static function get_customer_by_name($thin_app_id,$name,$mobile){
        $name = trim($name);
        $query = "select ac.*, df.id as folder_id from appointment_customers as ac left join drive_folders as df on df.appointment_customer_id = ac.id where ac.first_name = '$name' and ac.mobile = '$mobile' AND ac.thinapp_id = $thin_app_id and ac.status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function createLinkButton($patient_id,$patient_type,$uhid,$admit_status,$role='RECEPTIONIST'){

        $patient_id = base64_encode($patient_id);
        $patient_t = 'CU';
        if ($patient_type == 'CHILDREN') {
            $patient_t = 'CH';
        }
        $link_html = '';


        if ($admit_status != 'ADMIT') {
            $link_html .= '<a target = "_blank" class="btn btn-xs btn-info" href = "' . Router::url('/app_admin/admit_patient/', true) . $patient_t . '/' . $patient_id . '" ><i class="fa fa-sign-in" ></i > Admit</a >';
        } else {

        }

        if($role !="STAFF" && $role !='DOCTOR' && $role !='RECEPTIONIST') {
            $link_html .= ' <button type="button" class="btn btn-warning btn-xs edit_patient_btn"  data-pt= ' . $patient_type . ' data-pi=' . $patient_id . '><i class="fa fa-edit"></i> Edit </button>';
        }

        if ($uhid != '') {
            $link_html .= ' <a target = "_blank" href="' . Router::url('/app_admin/add_hospital_receipt_search?u=' . base64_encode($uhid), true) . '" target="_blank" class="btn btn-info btn-xs receipt_btn" ><i class="fa fa-edit"></i> Add Receipt </a>';

            $link_html .= ' <a target = "_blank" href="' . Router::url('/app_admin/hospital_patient_invoice_list/' . base64_encode($uhid), true) . '" target="_blank" class="btn btn-success btn-xs receipt_btn" ><i class="fa fa-list"></i> Receipt </a>';
        }
        $link_html .= ' <a target = "_blank" style="display: none;" class="btn btn-xs btn-warning" href="' . Router::url('/app_admin/patient_history/', true) . $patient_t . '/' . $patient_id . '"><i class="fa fa-sign-in"></i> History</a>';
        return  ($link_html);
    }

    public static function getDoctorCurrentAvailableTime($doctor_id){
        $query = "select  CONCAT(asa.from_time,' - ', asa.to_time) as time_string from appointment_staff_hours as ash right join appointment_staff_addresses  as asa on ash.appointment_staff_id = asa.appointment_staff_id AND ( TIME(NOW()) BETWEEN   ADDTIME(asa.from_time,IF(RIGHT(asa.from_time,2) = 'PM','12:00:00','00:00:00')) AND ADDTIME(asa.to_time,IF(RIGHT(asa.to_time,2) = 'PM','12:00:00','00:00:00'))  ) AND asa.status = 'ACTIVE'  where  ash.appointment_staff_id = $doctor_id and ash.status = 'OPEN'  and ash.appointment_day_time_id = (WEEKDAY(NOW())+1) LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['time_string'];
        }else{
            return false;
        }
    }


    public static function add_receipt_file($thin_app_id, $patient_id, $appointmentStaffID, $appointmentAddressID, $amount, $mobile, $created){


        $appointment_customer_id = $patient_id;
        $thinapp_id = $thin_app_id;
        $appointment_address_id = $appointmentAddressID;
        $appointment_staff_id = $appointmentStaffID;
        $appointment_service_id = 0;

        $selectServiceIDSql = "SELECT `appointment_service_id` FROM `appointment_staff_services` WHERE `appointment_staff_id` = '".$appointment_staff_id."' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $selectServiceIDRS = $connection->query($selectServiceIDSql);
        if ($selectServiceIDRS->num_rows) {
            $selectServiceIDData = mysqli_fetch_assoc($selectServiceIDRS);
            $appointment_service_id = $selectServiceIDData['appointment_service_id'];
        }

        $booking_date = $created;
        $appointment_datetime = $created;
        $booked_by = "ADMIN";
        $has_token = "NO";
        $created = $created;
        $modified = $created;
        $insertAppointmentSql = "INSERT INTO `appointment_customer_staff_services` (`appointment_customer_id`,`thinapp_id`,`appointment_address_id`,`appointment_staff_id`,`appointment_service_id`,`booking_date`,`appointment_datetime`,`booked_by`,`has_token`,`created`,`modified`) VALUES ('".$appointment_customer_id."','".$thinapp_id."','".$appointment_address_id."','".$appointment_staff_id."','".$appointment_service_id."','".$booking_date."','".$appointment_datetime."','".$booked_by."','".$has_token."','".$created."','".$modified."')";

        if($connection->query($insertAppointmentSql))
        {
            $appointment_customer_staff_service_id = $connection->insert_id;

            $total_amount = $amount;
            $is_opd = "Y";

            $insertInMedicalProductOrderSql = "INSERT INTO `medical_product_orders` (`appointment_customer_staff_service_id`,`appointment_staff_id`,`appointment_customer_id`,`appointment_address_id`,`thinapp_id`,`total_amount`,`is_opd`,`created`,`modified`) VALUES ('".$appointment_customer_staff_service_id."','".$appointment_staff_id."','".$appointment_customer_id."','".$appointment_address_id."','".$thinapp_id."','".$total_amount."','".$is_opd."','".$created."','".$modified."')";


            if($connection->query($insertInMedicalProductOrderSql)) {
                $medical_product_order_id = $connection->insert_id;

                $tax_type = "No Tax";
                $quantity = 1;
                $product_price = $amount;
                $service = "OPD";

                $insertOrderDetailSql = "INSERT INTO `medical_product_order_details` (`medical_product_order_id`,`service`,`appointment_customer_staff_service_id`,`appointment_staff_id`,`appointment_customer_id`,`thinapp_id`,`product_price`,`quantity`,`tax_type`,`amount`,`total_amount`,`created`,`modified`) VALUES ('".$medical_product_order_id."','".$service."','".$appointment_customer_staff_service_id."','".$appointment_staff_id."','".$appointment_customer_id."','".$thinapp_id."','".$product_price."','".$quantity."','".$tax_type."','".$amount."','".$total_amount."','".$created."','".$modified."')";
                $connection->query($insertOrderDetailSql);

                $updateAppointmentSql = "UPDATE `appointment_customer_staff_services` SET `medical_product_order_id` = '".$medical_product_order_id."',`amount` = '".$amount."',`amount` = '".$amount."',`payment_status` = 'SUCCESS' WHERE `id` = '".$appointment_customer_staff_service_id."'";
                $connection->query($updateAppointmentSql);
            }



        }


    }

    public static function get_doctor_appointment_setting_type($doctor_id){
        $query = "select type from doctor_appointment_setting_type where  doctor_id = $doctor_id and status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['type'];
        }else{
            return false;
        }
    }

    public static function doctor_appointment_address_list($thin_app_id,$doctor_id,$setting_type,$param){

        $condition = " and index_number = 1 ";
        if($setting_type =='DAY'){
            $condition .= " and das.appointment_day_time_id = $param";
        }else{
            $condition .= " and das.appointment_date = '$param'";
        }
        $query = "select  das.appointment_service_id, DATE_FORMAT(das.from_time,'%h:%i %p') as from_time, DATE_FORMAT(das.to_time,'%h:%i %p') as to_time, aa.id as address_id, aa.address, IF(das.id IS NOT NULL,'YES','NO') as is_associated from appointment_addresses as aa left join doctor_appointment_setting as das on aa.id = das.appointment_address_id and das.doctor_id = $doctor_id and setting_type = '$setting_type' $condition  where aa.thinapp_id = $thin_app_id and aa.status = 'ACTIVE'  ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function doctor_appointment_service_list($thin_app_id,$doctor_id,$setting_type,$param){

        $condition = "";
        if($setting_type =='DAY'){
            $condition = " and das.appointment_day_time_id = $param";
        }else{
            $condition = " and das.appointment_date = '$param'";
        }
        $query = "select app_ser.id as service_id, app_ser.name, IF(das.id IS NOT NULL,'YES','NO') as is_associated from appointment_services as app_ser left join doctor_appointment_setting as das on app_ser.id = das.appointment_service_id and das.doctor_id = $doctor_id and das.setting_type = '$setting_type' $condition where  app_ser.thinapp_id = $thin_app_id and app_ser.status = 'ACTIVE' group by app_ser.id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_service_by_date($thin_app_id,$doctor_id,$setting_type,$param){

        $condition = "";
        if($setting_type =='DAY'){
            $condition = " and das.appointment_day_time_id = $param";
        }else{
            $condition = " and das.appointment_date = '$param'";
        }
        $query = "select app_ser.id as service_id, app_ser.name,app_ser.service_amount, app_ser.video_consulting_amount,app_ser.audio_consulting_amount,app_ser.chat_consulting_amount  from appointment_services as app_ser join doctor_appointment_setting as das on app_ser.id = das.appointment_service_id and das.doctor_id = $doctor_id and das.setting_type = '$setting_type' $condition where  app_ser.thinapp_id = $thin_app_id and app_ser.status = 'ACTIVE' group by app_ser.id";
        
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_address_by_date($thin_app_id,$doctor_id,$service_id, $setting_type,$param){

        $condition = " and das.appointment_service_id = $service_id ";
        if($setting_type =='DAY'){
            $condition .= " and das.appointment_day_time_id = $param";
        }else{
            $condition .= " and das.appointment_date = '$param'";
        }
        $query = "select aa.id as address_id, aa.address from appointment_addresses as aa join doctor_appointment_setting as das on aa.id = das.appointment_address_id and das.doctor_id = $doctor_id and setting_type = '$setting_type' $condition where aa.thinapp_id = $thin_app_id and aa.status = 'ACTIVE' group by aa.id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }



    public static function new_get_appointment_slot($thin_app_id,$doctor_id,$service_id,$address_id, $setting_type,$date, $key_as_slots = false,$add_more_slot=false,$user_role="",$expired_slots =false){

        $connection = ConnectionUtil::getConnection();
        $booked_slot = $block_date = $final_array = array();
        $date = date('Y-m-d',strtotime($date));
        /* booked appointment slots */
        $query = "select slot_time from appointment_customer_staff_services where appointment_staff_id = $doctor_id and appointment_service_id = $service_id and appointment_address_id = $address_id and status IN ('NEW','CONFIRM','RESCHEDULE','REFUND') and delete_status != 'DELETED' AND  DATE(appointment_datetime) = '$date' and sub_token = 'NO' AND has_token ='YES'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $booked_slot = array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC),'slot_time');
        }


        $block_slots = Custom::get_doctor_blocked_slot($connection,$thin_app_id,$address_id,$doctor_id,$date,$service_id);
		$block_slots = empty($block_slots)?array():$block_slots;
        $condition = " das.doctor_id = $doctor_id and setting_type = '$setting_type' and das.appointment_service_id = $service_id and das.appointment_address_id = $address_id";
        if($setting_type =='DAY'){
            $param = date('N',strtotime($date));
            $condition .= " and das.appointment_day_time_id = $param";
        }else{
            $condition .= " and das.appointment_date = '$date'";
        }

        $query = "select app_staff.show_appointment_token, app_staff.show_appointment_time, das.from_time, das.to_time, app_ser.service_slot_duration from doctor_appointment_setting as das join appointment_staffs as app_staff on app_staff.id = das.doctor_id left join appointment_services as app_ser on app_ser.id = das.appointment_service_id where  $condition order by from_time asc";

        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            $slot_array = array();
            $service_slot_duration = @$staff_data[0]['service_slot_duration'];
            foreach($staff_data as $key =>$data){
                $slot_array[] = Custom::created_time_slot_range($data['from_time'],$data['to_time'],$data['service_slot_duration'],$format='H:i:s');

            }
            $counter=1;
            foreach ($slot_array as $key => $value){
                $show_appointment_time = $staff_data[$key]['show_appointment_time'];
                $show_appointment_token = $staff_data[$key]['show_appointment_token'];
                foreach ($value as $key2 => $time) {
                    $slot_time = date("h:i A", strtotime($time));
                    $compare_date_time = strtotime(date("Y-m-d H:i", strtotime($date . ' ' . $time)));
                    $current_date_time = strtotime(date("Y-m-d H:i"));
                    $status = '';
                    if (($compare_date_time > $current_date_time))  {
                        if (!array_key_exists('ALL_SLOT', $block_slots)) {
                            if (in_array($slot_time, $block_slots)) {
                                $status = 'BLOCKED';
                            } else if (in_array($slot_time, $booked_slot)) {
                                $status = 'BOOKED';
                            } else if (strtotime($date) > strtotime(date('Y-m-d'))) {
                                $status = 'AVAILABLE';
                            } else {
                                $status = 'AVAILABLE';
                            }
                        } else {
                            $status = 'BLOCKED';
                        }
                    } else {
                        if ($date >= date('Y-m-d') && $user_role != "USER") {
                            if (!array_key_exists('ALL_SLOT', $block_slots)) {
                                if (in_array($slot_time, $block_slots)) {
                                    $status = 'BLOCKED';
                                } else if (in_array($slot_time, $booked_slot)) {
                                    $status = 'BOOKED';
                                }else {
                                    $status = ($expired_slots===true)?'AVAILABLE':'EXPIRED';
                                }
                            }else {
                                $status = 'BLOCKED';
                            }
                        } else {
                            $status = 'EXPIRED';
                        }
                    }


                    $allow_add =false;
                    if($user_role == "USER" && $status == 'AVAILABLE'){
                        $allow_add =true;
                    }else if($user_role != "USER"){
                        if( $status=="EXPIRED" && $expired_slots ===false){
                            $allow_add = false;
                        }else{
                            $allow_add = true;
                        }

                    }

                    if($allow_add===true){
                        $tmp_array = array(
                            'slot' => $slot_time,
                            'token' => $counter,
                            'custom_slot' => 'NO',
                            'show_appointment_time' => $show_appointment_time,
                            'show_appointment_token' => $show_appointment_token,
                            'status' => $status
                        );
                        if($key_as_slots === true){
                            $final_array[$slot_time] = $tmp_array;
                        }else{
                            $final_array[] = $tmp_array;
                        }
                    }

                    $counter++;


                }
            }

            if(!empty($final_array) && $add_more_slot ===true){

                $custom_tokens = Custom::get_doctor_custom_tokens($doctor_id,$address_id,$date);
                if(!empty($custom_tokens)){
                    foreach($custom_tokens as $custom_key => $custom){
                        $slot_key = $custom['slot_time'];
                        $queue_number = $custom['queue_number'];
                        $index_key = ($key_as_slots === true)?$slot_key:count($final_array);
                        $final_array[$index_key] = array('slot' => $slot_key, 'status' => 'BOOKED', 'token' => $queue_number,'show_appointment_time'=>$show_appointment_time,'show_appointment_token'=>$show_appointment_token,'custom_slot'=>'NO');
                    }

                    $lastKey = end(array_keys($final_array));
                    $last_array = end($final_array);
                    $queue_number= $last_array['token']+1;

                }else{
                    $lastKey = end(array_keys($final_array));
                    $last_array = end($final_array);
                    $queue_number= $last_array['token']+1;
                }

                $slot_key = date('h:i A',strtotime("+ ".$service_slot_duration, strtotime($last_array['slot'])));
                $index_key = ($key_as_slots === true)?$slot_key:$lastKey+1;
                $final_array[$index_key] = array('slot' => $slot_key, 'status' => 'AVAILABLE', 'token' => $queue_number,'show_appointment_time'=>$show_appointment_time,'show_appointment_token'=>$show_appointment_token,'custom_slot'=>'YES');



            }


            return $final_array;
        }else{
            return false;
        }
    }

    public static function get_doctor_and_service_data($doctor_id,$service_id)
    {
         $query = "select t.pay_clinic_visit_fee_online, app_sta.emergency_appointment_fee, t.booking_convenience_fee_emergency, t.category_name, t.version_name, app_sta.is_audio_consulting, app_sta.is_chat_consulting, app_sta.is_online_consulting, app_sta.is_offline_consulting, t.booking_convenience_fee, t.booking_convenience_fee_video, t.booking_convenience_fee_audio, t.booking_convenience_fee_chat, app_sta.payment_mode, app_sta.appointment_setting_type, app_ser.*, t.show_expire_token_slot from appointment_services as app_ser  join thinapps as t on t.id = app_ser.thinapp_id join appointment_staffs as app_sta on app_sta.thinapp_id = app_ser.thinapp_id and app_sta.id = $doctor_id and app_ser.id = $service_id limit 1";
       
        
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }


    public static function get_thin_app_follower_user_token_by_tags($thin_app_id,$tags,$sendPnTo){

        $tagCondition = "";

        if(!empty($tags))
        {
            $tags = explode(",",$tags);
            $tags = "('".implode("','",$tags)."')";
            $tagCondition = "`patient_tags`.`patient_illness_tag_id` IN ".$tags;
        }

        if(!empty($sendPnTo))
        {
            if($sendPnTo == 'USER')
            {
                if($tagCondition != "")
                {
                    $sql = "SELECT `users`.`firebase_token` FROM `users` LEFT JOIN `appointment_customers` ON (`users`.`id` = `appointment_customers`.`user_id`) LEFT JOIN  `childrens` ON (`users`.`id` = `childrens`.`user_id`) LEFT JOIN `patient_tags` ON (`patient_tags`.`appointment_customer_id` = `appointment_customers`.`id` OR `patient_tags`.`children_id` = `childrens`.`id`) WHERE `users`.`thinapp_id` = '".$thin_app_id."' AND `users`.`app_installed_status` = 'INSTALLED' AND `users`.`role_id` <> '5' AND `users`.`firebase_token` <> '' AND ".$tagCondition." GROUP BY  `users`.`id`";
                }
                else
                {
                    $sql = "SELECT `users`.`firebase_token` FROM `users` WHERE `users`.`thinapp_id` = '".$thin_app_id."' AND `users`.`app_installed_status` = 'INSTALLED' AND `users`.`role_id` <> '5' AND `users`.`firebase_token` <> '' GROUP BY  `users`.`firebase_token`";
                }
            }
            else
            {

                if($tagCondition != "")
                {
                    $sql = "SELECT `users`.`firebase_token` FROM `users` LEFT JOIN `appointment_customers` ON (`users`.`id` = `appointment_customers`.`user_id`) LEFT JOIN  `childrens` ON (`users`.`id` = `childrens`.`user_id`) LEFT JOIN `patient_tags` ON (`patient_tags`.`appointment_customer_id` = `appointment_customers`.`id` OR `patient_tags`.`children_id` = `childrens`.`id`) WHERE `users`.`thinapp_id` = '".$thin_app_id."' AND `users`.`app_installed_status` = 'INSTALLED' AND `users`.`role_id` <> '5' AND `users`.`firebase_token` <> '' AND ".$tagCondition." AND (`appointment_customers`.`id` IS NOT NULL OR `patient_tags`.`children_id` IS NOT NULL) GROUP BY  `users`.`id`";
                }
                else
                {
                    $sql = "SELECT `users`.`firebase_token` FROM `users`  LEFT JOIN `appointment_customers` ON (`users`.`id` = `appointment_customers`.`user_id`) LEFT JOIN  `childrens` ON (`users`.`id` = `childrens`.`user_id`) WHERE `users`.`thinapp_id` = '".$thin_app_id."' AND `users`.`app_installed_status` = 'INSTALLED' AND `users`.`role_id` <> '5' AND `users`.`firebase_token` <> ''  AND (`appointment_customers`.`id` IS NOT NULL OR `patient_tags`.`children_id` IS NOT NULL) GROUP BY  `users`.`firebase_token`";
                }
            }
        }



        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($sql);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC),'firebase_token');
        }else{
            return false;
        }
    }

    public static function get_thin_app_follower_number_by_tags($thin_app_id,$tags,$sendPnTo,$channel_id){

        $tagCondition = "";

        if(!empty($tags))
        {
            $tags = explode(",",$tags);
            $tags = "('".implode("','",$tags)."')";
            $tagCondition = "`patient_tags`.`patient_illness_tag_id` IN ".$tags;
        }

        if(!empty($sendPnTo))
        {
            if($sendPnTo == 'USER')
            {
                if($tagCondition != "")
                {
                    $sql = "SELECT `users`.`mobile` AS `moble_unique` FROM `users` LEFT JOIN `appointment_customers` ON (`users`.`id` = `appointment_customers`.`user_id`) LEFT JOIN  `childrens` ON (`users`.`id` = `childrens`.`user_id`) LEFT JOIN `patient_tags` ON (`patient_tags`.`appointment_customer_id` = `appointment_customers`.`id` OR `patient_tags`.`children_id` = `childrens`.`id`) WHERE `users`.`thinapp_id` = '".$thin_app_id."' AND `users`.`app_installed_status` = 'UNINSTALLED' AND ".$tagCondition." GROUP BY  `moble_unique`";
                }
                else
                {
                    $sql = "SELECT `users`.`mobile` AS `moble_unique` FROM `users` WHERE `users`.`thinapp_id` = '".$thin_app_id."' AND `users`.`app_installed_status` = 'UNINSTALLED' AND `users`.`firebase_token` <> '' GROUP BY  `users`.`firebase_token`";
                }
            }
            else
            {

                if($tagCondition != "")
                {
                    $sql = "
                     SELECT `appointment_customers`.`mobile` AS `mobile_unique` FROM `appointment_customers` 
                     LEFT JOIN `patient_tags` ON (`patient_tags`.`appointment_customer_id` = `appointment_customers`.`id`)
                     LEFT JOIN `users` ON (`appointment_customers`.`user_id` = `users`.`id`)
                     WHERE `appointment_customers`.`thinapp_id` = '".$thin_app_id."'
                     AND ".$tagCondition."
                     AND (`users`.`id` IS NULL OR `users`.`app_installed_status` = 'UNINSTALLED')
                     
                     UNION
                     
                     SELECT `childrens`.`mobile` AS `mobile_unique` FROM `childrens`
                     LEFT JOIN `patient_tags` ON (`patient_tags`.`children_id` = `childrens`.`id`)
                     LEFT JOIN `users` ON (`childrens`.`user_id` = `users`.`id`)
                     WHERE `childrens`.`thinapp_id` = '".$thin_app_id."'
                     AND ".$tagCondition."
                     AND (`users`.`id` IS NULL OR `users`.`app_installed_status` = 'UNINSTALLED')
                     ";
                }
                else
                {
                    $sql = "SELECT `appointment_customers`.`mobile` AS `mobile_unique` FROM `appointment_customers` 
                    LEFT JOIN `users` ON (`appointment_customers`.`user_id` = `users`.`id`)
	                WHERE `appointment_customers`.`thinapp_id` = '".$thin_app_id."'
                    AND (`users`.`id` IS NULL OR `users`.`app_installed_status` = 'UNINSTALLED')
	 
	                UNION 
	 
                    SELECT `childrens`.`mobile` AS `mobile_unique` FROM `childrens`
                    LEFT JOIN `users` ON (`childrens`.`user_id` = `users`.`id`)
                    WHERE `childrens`.`thinapp_id` = '".$thin_app_id."'
                    AND (`users`.`id` IS NULL OR `users`.`app_installed_status` = 'UNINSTALLED')
	 
	                UNION 
	 
                    SELECT `users`.`mobile` AS `mobile_unique` FROM `users`
                    WHERE `users`.`thinapp_id` = '".$thin_app_id."'
                    AND `users`.`app_installed_status` = 'UNINSTALLED'
	 
	                UNION 
	 
                    SELECT `subscribers`.`mobile` AS `mobile_unique` FROM `subscribers`
                    LEFT JOIN `users` ON (`subscribers`.`app_user_id` = `users`.`id`)
                    WHERE `subscribers`.`app_id` = '".$thin_app_id."'
                    AND `subscribers`.`channel_id` = '".$channel_id."'
                    AND `subscribers`.`status` = 'SUBSCRIBED'
                    AND (`users`.`id` IS NULL OR `users`.`app_installed_status` = 'UNINSTALLED')";
                }
            }
        }



        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($sql);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC),'mobile_unique');
        }else{
            return false;
        }
    }


    public static function getLabWebTrackerData($thin_app_id,$labUserId){
        $sql = "SELECT `appointment_staffs`.`id` AS `doctor_id`, `lab_pharmacy_users`.`name` AS `app_name`, `appointment_staffs`.`name` AS `doctor_name`, IF(`appointment_customers`.`first_name` = '' OR `appointment_customers`.`first_name` IS NULL, `childrens`.`child_name`, `appointment_customers`.`first_name`) AS `patient_name`, `appointment_customer_staff_services`.`queue_number` AS `token_number`, `appointment_customer_staff_services`.`slot_time` AS `time_slot`, `appointment_staffs`.`profile_photo` AS `doctor_image`, `department_categories`.`category_name` AS `doctor_category`,IF(`lab_pharmacy_file_status`.`status` = '' OR `lab_pharmacy_file_status`.`status` IS NULL, 'NEW', REPLACE(`lab_pharmacy_file_status`.`status`,'_',' ')) AS `file_status` FROM `lab_pharmacy_tracker` LEFT JOIN `lab_pharmacy_users` ON (`lab_pharmacy_tracker`.`lab_pharmacy_user_id` = `lab_pharmacy_users`.`id`) LEFT JOIN `appointment_customer_staff_services` ON(`appointment_customer_staff_services`.`id` = `lab_pharmacy_tracker`.`appointment_customer_staff_service_id`) LEFT JOIN `appointment_staffs` ON (`appointment_staffs`.`id` = `appointment_customer_staff_services`.`appointment_staff_id`) LEFT JOIN `appointment_customers` ON (`appointment_customer_staff_services`.`appointment_customer_id` = `appointment_customers`.`id`) LEFT JOIN `childrens` ON (`appointment_customer_staff_services`.`children_id` = `childrens`.`id`) LEFT JOIN `department_categories` ON (`appointment_staffs`.`department_category_id` = `department_categories`.`id`) LEFT JOIN `lab_pharmacy_file_status` ON (`lab_pharmacy_file_status`.`appointment_customer_staff_service_id` = `lab_pharmacy_tracker`.`appointment_customer_staff_service_id`) WHERE `lab_pharmacy_users`.`id` = '".$labUserId."' AND `lab_pharmacy_users`.`thinapp_id` = '".$thin_app_id."'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($sql);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }else{
            return false;
        }

    }

    public static function getLoadLabPharmacyTrackerNew($thin_app_id,$labUserId){
        $date = date("Y-m-d");
        $sql = "
        SELECT 
        `lab_pharmacy_file_status`.`queue_number` AS `queue_number`,
        `lab_pharmacy_file_status`.`type` AS `type`,
        `lab_pharmacy_file_status`.`status` AS `status`,
        IFNULL(`appointment_staffs`.`name`,'') AS `doc_name`,
        IFNULL(`appointment_customers`.`first_name`,`childrens`.`child_name`) AS `patient_name`
        FROM `lab_pharmacy_file_status`
        LEFT JOIN `appointment_staffs` ON (`lab_pharmacy_file_status`.`appointment_staff_id` = `appointment_staffs`.id)
        LEFT JOIN `appointment_customers` ON (`lab_pharmacy_file_status`.`appointment_customer_id` = `appointment_customers`.`id`)
        LEFT JOIN `childrens` ON (`lab_pharmacy_file_status`.`children_id` = `childrens`.`id`)
        WHERE 
        `lab_pharmacy_file_status`.`status` IN ('CHECKED-IN','BILLING')
        AND `lab_pharmacy_user_id` = '".$labUserId."' AND DATE(`lab_pharmacy_file_status`.`modified`) = '".$date."'
        ORDER BY `lab_pharmacy_file_status`.`modified` DESC
        LIMIT 15
        ";

        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($sql);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }else{
            return false;
        }

    }

    public static function get_doctor_data_via_appointment_id($appointment_id)
    {
        $query = "select  app_sta.*  from appointment_customer_staff_services as acss  join appointment_staffs as app_sta on app_sta.id = acss.appointment_staff_id and app_sta.status = 'ACTIVE' where acss.id = $appointment_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function UpdateChildPatientDetail($appointment_customer_id,$children_id,$data){

        if(isset($data['gender']))
        {
            $gender = $data['gender'];
            $parent_name = $data['parent_name'];
            $address = $data['address'];

            if($appointment_customer_id > 0)
            {
                $id = $appointment_customer_id;
                $sql = "UPDATE `appointment_customers` SET `gender` = '".$gender."', `address` = '".$address."', `parents_name` = '".$parent_name."' WHERE `id` = '".$id."'";
            }
            else
            {
                $id = $children_id;
                $sql = "UPDATE `childrens` SET `gender` = '".$gender."', `patient_address` = '".$address."', `parents_name` = '".$parent_name."' WHERE `id` = '".$id."'";
            }
            $connection = ConnectionUtil::getConnection();
            return $connection->query($sql);
        }
        else
        {
            return true;
        }

    }
    public static function get_customer_by_id($customer_id,$appointment_id=0){

        $query = "select ac.*, df.id as folder_id , acss.referred_by, acss.referred_by_mobile, acss.reason_of_appointment, acss.notes from appointment_customers as ac left join drive_folders as df on df.appointment_customer_id = ac.id LEFT JOIN appointment_customer_staff_services AS acss on acss.id = $appointment_id AND acss.appointment_customer_id = ac.id where ac.id = $customer_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $data = mysqli_fetch_assoc($service_message_list);
            if($data['dob']=="1970-01-01"){
                $data['dob'] = "";
            }
            return $data;
        
        }else{
            return false;
        }
    }

    public static function get_folder_attachment_count($thin_app_id, $folder_id){

        $query = "select count(*) as total from drive_files as df where df.file_category_master_id = 7 and df.status = 'ACTIVE' AND df.drive_folder_id = $folder_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['total'];
        }else{
            return 0;
        }
    }

    public static function create_experience_string($string,$mode ="FULL"){
        if($string!=''){
            $tmp = explode('.',$string);
            $string = "";
            $space = ($mode =="FULL")?" ":"";
            if(count($tmp) > 1){
                if($tmp[0] > 0){
                    $year_label = "Y";
                    if($mode=="FULL"){
                        $year_label = ($tmp[0] > 1 )?"Years":"Year";
                    }
                    $string = $tmp[0].$space.$year_label." ";
                }
                if($tmp[1] > 0){
                    $month_label = "M";
                    if($mode=="FULL"){
                        $month_label = ($tmp[1] > 1 )?"Months":"Month";
                    }
                    $string .= $tmp[1].$space.$month_label;
                }
            }else if(count($tmp) == 1){
                if($tmp[0] > 0){
                    $year_label = "Y";
                    if($mode=="FULL"){
                        $year_label = ($tmp[0] > 1 )?"Years":"Year";
                    }
                    $string = $tmp[0].$space.$year_label;
                }
            }
        }
        return $string;


    }

    public static function check_app_enable_permission($thin_app_id,$lable_key) {

        $permission = '';
        $response = WebservicesFunction::readJson('get_app_enabled_functionality_' . $thin_app_id,'permission');
        if(isset($response['data']['features'][$lable_key])){
            $permission =$response['data']['features'][$lable_key];
            if($permission =='YES'){
                return true;
            }
        }
        if(empty($permission)){
            $query = "select id from app_functionality_types where label_key = '$lable_key'  limit 1";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $app_fun =  mysqli_fetch_assoc($service_message_list)['id'];
                $query = "select IF(count(*) > 0, 'YES','NO' ) as permission from app_enable_functionalities where thinapp_id = $thin_app_id and  app_functionality_type_id = $app_fun  limit 1";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $status =  mysqli_fetch_assoc($service_message_list)['permission'];
                    if($status == 'YES'){
                        return true;
                    }
                }
            }

        }
        return false;

    }



    public static function get_customer_first_id_by_mobile($thin_app_id,$mobile){

        $query = "select id from appointment_customers where mobile = '$mobile' and thinapp_id = $thin_app_id and status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['id'];
        }else{
            return false;
        }
    }

    public static function ive_get_doctor_custom_data($thin_app_id,$doctor_id,$new_appointment_setting = false){

        if($new_appointment_setting === true){
            $query = "select  app_staf.user_id, app_staf.name as doctor_name, app_staf.appointment_setting_type as setting_type, das.appointment_service_id as service_id, app_staf.user_id, u.role_id, app_staf.mobile, app_ser.service_slot_duration, das.appointment_address_id as  address_id from appointment_staffs as app_staf join doctor_appointment_setting as das on das.doctor_id = app_staf.id and das.setting_type = app_staf.appointment_setting_type AND das.index_number = 1 and ( (das.appointment_day_time_id = (WEEKDAY(NOW())+1)) OR (DATE(NOW()) = DATE(das.appointment_date)) ) and ( TIME(NOW()) between das.from_time and das.to_time OR das.from_time > TIME(NOW()) ) join appointment_services as app_ser on app_ser.id = das.appointment_service_id  left join users as u on u.id = app_staf.user_id where app_staf.id = $doctor_id and app_staf.status = 'ACTIVE' and app_staf.staff_type = 'DOCTOR' limit 1";
        }else{
            $query = "select app_staf.user_id, app_staf.name as doctor_name, app_staf.appointment_setting_type as setting_type, app_staf.user_id, u.role_id, app_staf.mobile, app_ser.service_slot_duration, aa.id as address_id,app_ser.id as service_id from appointment_staffs as app_staf join appointment_staff_services as ass on ass.appointment_staff_id = app_staf.id join appointment_services as app_ser on app_ser.id = ass.appointment_service_id join appointment_staff_addresses as asa on asa.appointment_staff_id = app_staf.id join appointment_addresses as aa on aa.id = asa.appointment_address_id left join users as u on u.id = app_staf.user_id where app_staf.id = $doctor_id and app_staf.status = 'ACTIVE' and app_staf.staff_type = 'DOCTOR' limit 1";
        }

        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }




    public static function send_web_tracker_notification($thin_app_id){

        @Custom::send_process_to_background();
        $trackerTokenSql = "SELECT `token`,`tracker_type`,`doctor_ids` FROM `tracker_firebase_token` WHERE `thinapp_id` = '".$thin_app_id."'";
        $connection = ConnectionUtil::getConnection();
        $trackerTokenRS = $connection->query($trackerTokenSql);
        if($trackerTokenRS->num_rows)
        {
            $mediaTrackerData = Custom::load_tracker_opd_media($thin_app_id);
            $newTrackerData = Custom::load_tracker_opd_new($thin_app_id);
            $updateSpeech = "UPDATE `speech_message_to_play` SET `status` = 'UNPLAY' WHERE `thinapp_id` = '".$thin_app_id."' AND `status` = 'PLAY'";
            $connection->query($updateSpeech);
            $trackerTokenData = mysqli_fetch_all($trackerTokenRS,MYSQLI_ASSOC);
            foreach($trackerTokenData AS $tokenData){
                if($tokenData['tracker_type'] == 'MEDIA')
                {
                    $dataToSend = $mediaTrackerData;
                }
                else if($tokenData['tracker_type'] == 'NEW')
                {
                    $dataToSend = $newTrackerData;
                }
                else if($tokenData['tracker_type'] == 'MULTIPLE')
                {
                    $dataToSend = Custom::load_tracker_opd_multiple($thin_app_id, $tokenData['doctor_ids']);
                }

                Custom::send_web_notification_via_token($dataToSend,$tokenData['token']);

            }


        }
    }

    public static function load_tracker_opd_media($thin_app_id)
    {
        $thinappSql = "SELECT `tracker_media_doctor_id` FROM `thinapps` WHERE `id` = '".$thin_app_id."' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $thinappRS = $connection->query($thinappSql);
        $thinappData = mysqli_fetch_assoc($thinappRS);

        if(!empty($thinappData['tracker_media_doctor_id']) && ($thinappData['tracker_media_doctor_id']))
        {
            $doctor_id_array = json_decode($thinappData['tracker_media_doctor_id']);
        }
        else
        {
            $doctor_id_array = Custom::get_all_app_doctor_id($thin_app_id);
        }

        $response = array();

        if (!empty($thin_app_id) && !empty($doctor_id_array)) {
            //$thin_app_id = base64_decode($thin_app_id);
            $tracker_data = Custom::getDoctorWebTrackerDataUpcomingListNew($doctor_id_array,true);
            $tracker_data = @array_values($tracker_data);

            $speechDataFormated = array();
            $speechSql = "SELECT message from speech_message_to_play WHERE `thinapp_id` = '".$thin_app_id."' AND `status` = 'PLAY'";
            $speechRS = $connection->query($speechSql);
            $speechData = mysqli_fetch_all($speechRS,MYSQLI_ASSOC);

            foreach($speechData AS $dataSpeech)
            {
                $speechDataFormated[] =  $dataSpeech["message"];
            }

            if(!empty($tracker_data))
            {
                $response['status'] = 1;
                $response['data'] = $tracker_data;
                $response['speech_data'] = $speechDataFormated;
            }
            else
            {
                $response['speech_data'] = $speechDataFormated;
            }
        }
        else
        {
            $response['status'] = 1;
        }
    
    	
        $break_array= array();
    	if(!empty($doctor_id_array) && is_array($doctor_id_array)) {
          foreach($doctor_id_array as $key =>$doctor_id){
            $break_data = Custom::emergency_patient($doctor_id);
            if(!empty($break_data)){
                $break_array[$doctor_id] = json_decode($break_data,true);
            }
        }
        }
        $response['break_array'] = $break_array;
    
        return $response;


    }

    public static function load_tracker_opd_new($thin_app_id)
    {



        $thinappSql = "SELECT `tracker_new_doctor_id` FROM `thinapps` WHERE `id` = '".$thin_app_id."' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $thinappRS = $connection->query($thinappSql);
        $thinappData = mysqli_fetch_assoc($thinappRS);

        if(!empty($thinappData['tracker_new_doctor_id']) && ($thinappData['tracker_new_doctor_id']))
        {
            $doctor_id_array = json_decode($thinappData['tracker_new_doctor_id']);
        }
        else
        {
            $doctorIDs = Custom::get_all_app_doctor_id($thin_app_id);
            $doctor_id_array = (isset($doctorIDs[0]))?$doctorIDs[0]:"";
            $doctor_id_array .=(isset($doctorIDs[1]))?",".$doctorIDs[1]:"";
        }

        $response = array();

        if (!empty($thin_app_id) && !empty($doctor_id_array)) {
            //$thin_app_id = base64_decode($thin_app_id);
            $tracker_data = Custom::getDoctorWebTrackerDataUpcomingListNew($doctor_id_array,true);
            $tracker_data = @array_values($tracker_data);

            $speechDataFormated = array();
            $speechSql = "SELECT message from speech_message_to_play WHERE `thinapp_id` = '".$thin_app_id."' AND `status` = 'PLAY'";
            $speechRS = $connection->query($speechSql);
            $speechData = mysqli_fetch_all($speechRS,MYSQLI_ASSOC);

            foreach($speechData AS $dataSpeech)
            {
                $speechDataFormated[] =  $dataSpeech["message"];
            }

            if(!empty($tracker_data))
            {
                $response['status'] = 1;
                $response['data'] = $tracker_data;
                $response['speech_data'] = $speechDataFormated;
            }
            else
            {
                $response['speech_data'] = $speechDataFormated;
            }
        }
        else
        {
            $response['status'] = 1;
        }
    
    	$break_array= array();
    	if(!empty($doctor_id_array)) {
        	if(!is_array($doctor_id_array)){
                $doctor_id_array =explode(",",$doctor_id_array);
            }
          foreach($doctor_id_array as $key =>$doctor_id){
            $break_data = Custom::emergency_patient($doctor_id);
            if(!empty($break_data)){
                $break_array[$doctor_id] = json_decode($break_data,true);
            }
        }
        }
        $response['break_array'] = $break_array;
    
        return $response;
    }

    public static function load_tracker_opd_multiple($thin_app_id,$doctorIDS)
    {


        if(!empty($doctorIDS))
        {
            $doctor_id_array = $doctorIDS;
        }
        else
        {
            $doctorIDs = Custom::get_all_app_doctor_id($thin_app_id);
            $doctor_id_array = implode(",",$doctorIDs);
        }
        $connection = ConnectionUtil::getConnection();
        $response = array();

        if (!empty($thin_app_id) && !empty($doctor_id_array)) {
            //$thin_app_id = base64_decode($thin_app_id);
            $tracker_data = Custom::getDoctorWebTrackerDataUpcomingListNew($doctor_id_array,true);

            $tracker_data = @array_values($tracker_data);

            $speechDataFormated = array();
            $speechSql = "SELECT message from speech_message_to_play WHERE `thinapp_id` = '".$thin_app_id."' AND `status` = 'PLAY'";
            $speechRS = $connection->query($speechSql);
            $speechData = mysqli_fetch_all($speechRS,MYSQLI_ASSOC);

            foreach($speechData AS $dataSpeech)
            {
                $speechDataFormated[] =  $dataSpeech["message"];
            }

            if(!empty($tracker_data))
            {
                $response['status'] = 1;
                $response['data'] = $tracker_data;
                $response['speech_data'] = $speechDataFormated;
            }
            else
            {
                $response['speech_data'] = $speechDataFormated;
            }
        }
        else
        {
            $response['status'] = 1;
        }
    
    	$break_array= array();
    	if(!empty($doctor_id_array)){
       		 if(!is_array($doctor_id_array)){
                $doctor_id_array =explode(",",$doctor_id_array);
            }
          foreach($doctor_id_array as $key =>$doctor_id){
            $break_data = Custom::emergency_patient($doctor_id);
            if(!empty($break_data)){
                $break_array[$doctor_id] = json_decode($break_data,true);
            }
        }
        }
        $response['break_array'] = $break_array;
    
        return $response;
    }




    public static function send_book_appointment_notification($data){
        $booking_request_from =  @$data['booking_request_from'];
        if($booking_request_from=="WEB"){
            $doctor_id =  $data['doctor_id'];
            $appointment_id =  $data['appointment_id'];
            $thin_app_id =  $data['thin_app_id'];
            if($thin_app_id != 600){
                Custom::lite_send_book_appointment_notification($appointment_id,$thin_app_id,$doctor_id);
            }
        }else{
            $background =  $data['background'];
            $user_type =  $data['user_type'];
            $doctor_id =  $data['doctor_id'];
            $address_id =  $data['address_id'];
            $appointment_id =  $data['appointment_id'];
            $drive_folder_id =  $data['drive_folder_id'];
            $doctor_data = Custom::get_doctor_by_id($doctor_id);
            $thin_app_id =  !empty($data['thin_app_id'])?$data['thin_app_id']:$doctor_data['thinapp_id'];
            if (!empty($background)) {

            Custom::send_process_to_background();
            $patient_data = Custom::getPatientByAppointmentId($appointment_id,$thin_app_id);
            $consulting_type =  $patient_data['consulting_type'];
            $booking_convenience_fee_restrict_ivr =  $patient_data['booking_convenience_fee_restrict_ivr'];
            if(!empty($patient_data)){
                $folder = json_decode(Custom::create_and_share_folder($thin_app_id, $patient_data['patient_mobile'],$patient_data['patient_name'],$patient_data['patient_type'],$patient_data['patient_id']),true);
                if(isset($folder['folder_id'])){
                    $drive_folder_id =$folder['folder_id'];
                    $connection = ConnectionUtil::getConnection();
                    $connection->autocommit(false);
                    $sql = "update appointment_customer_staff_services set drive_folder_id= ? where id =?";
                    $stmt_df = $connection->prepare($sql);
                    $stmt_df->bind_param('ss', $folder['folder_id'], $appointment_id);
                    if($stmt_df->execute()){
                        $connection->commit();
                    }
                }
            }

            $customer_sms = Custom::create_custom_sms_from_template($appointment_id,'BOOKING');

            if (isset($background['notification'])) {
                foreach ($background['notification'] as $key => $value) {
                    $option = $value['data'];
                    $user_id = $value['user_id'];
                    if($value['send_to']=='USER' && !empty($customer_sms)){
                        $option['message'] = $customer_sms;
                        if($booking_request_from=='DOCTOR_PAGE'){
                            Custom::send_web_app_booking_notification($patient_data['patient_mobile'],$thin_app_id,$customer_sms,$doctor_id);
                        }
                    }
                    $result = Custom::send_notification_by_user_id($option, array($user_id), $thin_app_id);
                }
            }

            if (Custom::check_app_enable_permission($thin_app_id, 'SHOW_USER_TO_NEW_APPOINTMENT_TRACKER') || Custom::check_app_enable_permission($thin_app_id, 'SHOW_USER_TO_APPOINTMENT_TRACKER')) {
                $token_array = Custom::get_upcoming_appointment_user_token($thin_app_id, $doctor_id, $address_id, $appointment_id);
                if (!empty($token_array)) {
                    $option = array(
                        'thinapp_id' => $thin_app_id,
                        'staff_id' => 0,
                        'customer_id' => 0,
                        'service_id' => 0,
                        'channel_id' => 0,
                        'role' => "CUSTOMER",
                        'flag' => 'APPOINTMENT_TRACKER',
                        'title' => "New Tracker Request",
                        'message' => "Your tracker message",
                        'description' => "Your tracker message",
                        'chat_reference' => '',
                        'module_type' => 'APPOINTMENT_TRACKER',
                        'module_type_id' => 0,
                        'firebase_reference' => ""
                    );
                    Custom::send_notification_via_token($option, $token_array, $data['thinapp_id']);
                }
            }

            if (isset($background['sms'])) {
                $send_appointment_sms = 'YES';
                if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                    $send_appointment_sms = Custom::check_user_permission($thin_app_id,'SEND_NEW_APPOINTMENT_BOOKING_SMS');
                } else {
                    $send_appointment_sms = Custom::check_user_permission($thin_app_id,'SEND_APPOINTMENT_BOOKING_SMS');
                }
                if($send_appointment_sms == "YES"){
                    foreach ($background['sms'] as $key => $value) {
                        $send_download_link = true;
                        $doctor_name = !empty($doctor_data)?$doctor_data['name']."\n":"";
                        $message = $doctor_name.$value['message'];
                        $message = str_replace("Plan to come 15 min before.","",$message);
                        $mobile = $value['mobile'];
                        if($value['send_to']=='USER'){

                            $autoAssignToken = Custom::check_app_enable_permission($thin_app_id, 'AUTO_ASSIGN_TOKEN_SYSTEM');
                            if($consulting_type=='OFFLINE' && $autoAssignToken && in_array($booking_request_from,array('APP','DOCTOR_PAGE'))){
                                $time = $patient_data['slot_time'];
                                $date = date('d-m-Y',strtotime($patient_data['appointment_datetime']));
                                $assign_minutes = AUTO_ASSIGN_TOKEN_MINUTES." minutes ";
                                $message =$w_message= "कृपया हमारे संपर्क नंबर को डाॅक्टर या हॉस्पिटल के नाम से सेव करे।\n\nआपके टाइम स्लॉट का विवरण\n\nदिनांक- $date\nनियुक्ति समय- $time\n\nनिर्धारित समय से $assign_minutes पहले आपको टोकन का मेसेज प्राप्त होगा, जिससे टोकन की लाइव स्थिति को ट्रैक कर सकते हैं।\n\nकभी-कभी, आपके नंबर में देरी हो सकती है क्योंकि डॉक्टर व्यस्त हो सकते हैं\n\nयह सिस्टम जनित संदेश है। कृपया जवाब न दें।";
                                    
                                $res = Custom::sendWhatsappSms($mobile,$w_message,$message);
                            }else{
                                $message = (!empty($customer_sms) )?$customer_sms:$message;
                                if($consulting_type!='OFFLINE'){
                                    $response = Custom::sendWhatsapp(1,$appointment_id,$thin_app_id);
                                }
                                if($booking_request_from == 'FACE_READER_TAB' || $booking_request_from == "DIALER"){
                                    if($thin_app_id!="821"){
                                        $form_url = SITE_PATH."homes/patient_opd_form/".base64_encode($appointment_id);
                                        $form_url = Custom::short_url($form_url,$thin_app_id);
                                        $message .="\nPlease fill OPD form by click on :- $form_url";
                                    }else{
                                        $response = Custom::sendWhatsappSms($mobile,$message);
                                    }
                                }
                                if (!Custom::check_app_enable_permission($thin_app_id, 'MOQ_HOSPITAL') && Custom::check_app_enable_permission($thin_app_id, 'WEB_PRESCRIPTION')) {
                                    $form_url = SITE_PATH . "homes/flags/" . base64_encode($appointment_id);
                                    $form_url = Custom::short_url($form_url, $thin_app_id);
                                    $message .= "\nProvide Info\n$form_url ";
                                    $send_download_link = false;
                                }
                                if($consulting_type=='OFFLINE'){

                                    $web_app_link = Custom::createWebAppUrl($thin_app_id,$doctor_id,$mobile,$patient_data['uhid'],$doctor_data['department_category_id']);


                                    $token = Custom::create_queue_number($patient_data);
                                    $time = $patient_data['slot_time'];
                                    $date = date('d-m-Y',strtotime($patient_data['appointment_datetime']));
                                    $receiptUrl = 'N/A';
                                    if($booking_request_from == "WEB"){
                                        if(!empty($patient_data['medical_product_order_id'])){
                                            $receiptUrl = Custom::short_url(SITE_PATH."app_admin/print_invoice/".base64_encode($patient_data['medical_product_order_id']));
                                        }
                                    }else{
                                        $receiptUrl = Custom::short_url(SITE_PATH."homes/receipt/".base64_encode($appointment_id));
                                    }

                                    $time_string = ($booking_request_from == "DIALER" && $thin_app_id==27)?' ':$time;

                                    if($thin_app_id==902 || $doctor_data['department_category_id']==32){
                                        $doctor_name = trim(preg_replace("/\([^)]+\)/","",$doctor_name));
                                        $message = "CONFIRMED\nService - $doctor_name\nToken-$token, Date-$date\nLive Token Status\n$web_app_link\nBy MEngage";
                                        $w_message = "CONFIRMED\nService - $doctor_name\nToken-$token, Date-$date\nLive Token Status\n$web_app_link";
                                    }else{

                                        if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
                                            $receiptUrl = " - ";
                                        }
                                        $message = "CONFIRMED\nToken-$token, Date-$date\nTime-$time_string\nToken Status\n$web_app_link\nPayment Receipt\n$receiptUrl\nBy MEngage";
                                    }

                                    if($mobile!='+919999999999'){

                                        $notify_apps = explode(",",NOTIFIY_THINAPPS);
                                        if(in_array($thin_app_id,$notify_apps)){
                                            if($thin_app_id!=892){
                                                $doctor_apps = Custom::short_url("https://mengage.in/doctor/",$thin_app_id);
                                            	$time = ($thin_app_id==730)?'':$time;
                                            	$doctor_name = $doctor_data['name'];	
                                                 $w_message = "धन्यवाद आपका टोकन बुक कर लिया गया है। भविष्य में आसानी और संदर्भ के लिए, कृपया हमारे संपर्क नंबर को $doctor_name के रूप में सहेजें। आप भविष्य मै डाॅक्टर से मिलने के लिए आसान तरीके से टोकन बुक कर सकते है और निम्न लिंक का उपयोग करके वर्तमान टोकन की लाइव स्थिति को ट्रैक कर सकते हैं - $web_app_link\n\nआपका टोकन विवरण -\n\nटोकन- $token\nदिनांक- $date\nनियुक्ति समय- $time\n\nकभी-कभी, आपके नंबर में देरी हो सकती है क्योंकि डॉक्टर आपात स्थिति में अन्य रोगियों के साथ व्यस्त हो सकते हैं।\n\nभुगतान रसीद- $receiptUrl\n\nब्लॉक न करें, व्हाट्सएप को लिंक को क्लिक करने योग्य बनाने के लिए जारी रखने दें।\n\nयह सिस्टम जनित संदेश है। कृपया जवाब न दें।";
                                               
                                            }
                                            $res = Custom::sendWhatsappSms($mobile,$w_message,$message);
                                        }else{
                                            if($booking_request_from=='DOCTOR_PAGE'){
                                                $res =  Custom::send_web_app_booking_notification($patient_data['patient_mobile'],$thin_app_id,$customer_sms,$doctor_id);
                                            }else{
                                                $result = Custom::send_single_sms($mobile, $message, $thin_app_id,false,false);
                                            }
                                        }



                                    }
                                }
                            }



                        }


                        if($value['send_to']=='DOCTOR'){
                            $send_download_link = false;
                            $message = $value['message'];
                            if($consulting_type!='OFFLINE'){
                                $response = Custom::sendWhatsapp(2,$appointment_id,$thin_app_id);
                            }
                        }
                    }
                }
            }
        }


        if ($user_type == "CUSTOMER") {
            $customer_id =  $data['patient_id'];
            if(!empty($customer_id) && !empty($drive_folder_id)){
                $customer_data = Custom::get_customer_by_id($customer_id);
                $created= Custom::created();
                if(!empty($customer_data)){
                    $customer_mobile = $customer_data['mobile'];
                    $query = "SELECT id FROM drive_shares WHERE shared_object ='FOLDER' and thinapp_id = $thin_app_id AND share_with_mobile = '$customer_mobile' AND drive_folder_id =  $drive_folder_id LIMIT 1";
                    $connection = ConnectionUtil::getConnection();
                    $connection->autocommit(false);
                    $shared = $connection->query($query);
                    if (!$shared->num_rows) {
                        $sql = "INSERT INTO drive_shares (thinapp_id, share_with_mobile, share_from_mobile, drive_file_id, drive_folder_id, share_from_user_id, share_to_user_id, shared_object, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_sub = $connection->prepare($sql);
                        $shared_object ='FOLDER';
                        $drive_file_id=0;
                        $stmt_sub->bind_param('ssssssssss', $thin_app_id, $customer_mobile, $doctor_data['mobile'], $drive_file_id, $drive_folder_id, $doctor_data['user_id'], $customer_data['user_id'], $shared_object, $created, $created);
                        if ($stmt_sub->execute()) {
                            $total_recod_save=1;
                            $sql = "UPDATE  drive_folders SET share_count = share_count + ?, modified = ? where id = ?";
                            $stmt = $connection->prepare($sql);
                            $stmt->bind_param('sss', $total_recod_save, $created, $drive_folder_id);
                            if($stmt->execute()){
                                $connection->commit();
                            }
                        }
                    }

                    }
                }
            }
        }


    }

    public static function lite_send_book_appointment_notification($appointment_id,$thin_app_id,$doctor_id){
        if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
            $send_appointment_sms = Custom::check_user_permission($thin_app_id,'SEND_NEW_APPOINTMENT_BOOKING_SMS');
        } else {
            $send_appointment_sms = Custom::check_user_permission($thin_app_id,'SEND_APPOINTMENT_BOOKING_SMS');
        }
        if($send_appointment_sms == "YES"){
            $appointmentData = Custom::getPatientByAppointmentId($appointment_id,$thin_app_id,$doctor_id);
            $token = Custom::create_queue_number($appointmentData);
            $doctor_data = Custom::get_doctor_by_id($doctor_id);
            $time = ($appointmentData["emergency_appointment"]=="YES" || $doctor_data["show_appointment_time"]=="NO")?" ":$appointmentData['slot_time'];
            $date = date('d-m-Y',strtotime($appointmentData['appointment_datetime']));
            $receiptUrl = 'N/A';
			$doctor_name = $doctor_data['name'];
            $mobile = $appointmentData['patient_mobile'];

            $web_app_link = Custom::createWebAppUrl($thin_app_id,$doctor_id,$mobile,$appointmentData['uhid'],$doctor_data['department_category_id']);
            if(!empty($appointmentData['medical_product_order_id'])){
                $receiptUrl = Custom::short_url(SITE_PATH."app_admin/print_invoice/".base64_encode($appointmentData['medical_product_order_id']));
            }
            $message = "CONFIRMED\nToken-$token, Date-$date\nTime-$time\nToken Status\n$web_app_link\nPayment Receipt\n$receiptUrl\nBy MEngage";
            if(Custom::check_app_enable_permission($thin_app_id, 'WHATS_APP')){
                $doctor_apps = Custom::short_url("https://mengage.in/doctor/",$thin_app_id);
                $w_message = "धन्यवाद आपका टोकन बुक कर लिया गया है। भविष्य में आसानी और संदर्भ के लिए, कृपया हमारे संपर्क नंबर को $doctor_name के रूप में सहेजें। आप भविष्य मै डाॅक्टर से मिलने के लिए आसान तरीके से टोकन बुक कर सकते है और निम्न लिंक का उपयोग करके वर्तमान टोकन की लाइव स्थिति को ट्रैक कर सकते हैं - $web_app_link\n\nआपका टोकन विवरण -\n\nटोकन- $token\nदिनांक- $date\nनियुक्ति समय- $time\n\nकभी-कभी, आपके नंबर में देरी हो सकती है क्योंकि डॉक्टर आपात स्थिति में अन्य रोगियों के साथ व्यस्त हो सकते हैं।\n\nभुगतान रसीद- $receiptUrl\n\nब्लॉक न करें, व्हाट्सएप को लिंक को क्लिक करने योग्य बनाने के लिए जारी रखने दें।\n\nयह सिस्टम जनित संदेश है। कृपया जवाब न दें।";
                $res = Custom::sendWhatsappSms($mobile,$w_message,$message);
            }else{
                $result = Custom::send_single_sms($mobile, $message, $thin_app_id,false,false);
            }
        }
    }

    public static function addZeroOPDReceipt($appointment_id,$children_id,$appointment_customer_id,$appointment_address_id,$thin_app_id, $appointment_staff_id,$opdCharge = 0){
        $connection = ConnectionUtil::getConnection();
        $appointmentCustomerStaffServiceID = $appointment_id;
        $appointment_staff_id = $appointment_staff_id;
        $appointment_customer_id = $appointment_customer_id;
        $children_id = $children_id;
        $thinapp_id = $thin_app_id;
        $total_amount = $opdCharge;
        $uniqueID = date("dmy").$appointment_id;
        $created = Custom::created();
        $medicalProductOrderInsert = "INSERT INTO `medical_product_orders` SET `unique_id` = '".$uniqueID."', `appointment_customer_staff_service_id` = '".$appointmentCustomerStaffServiceID."',`appointment_staff_id` = '".$appointment_staff_id."',`appointment_address_id` = '".$appointment_address_id."', `appointment_customer_id` = '".$appointment_customer_id."', `children_id` = '".$children_id."', `thinapp_id` = '".$thinapp_id."', `total_amount` = '".$total_amount."', `created` = '".$created."', `modified` = '".$created."'";
        $medicalProductOrderInsertRS = $connection->query($medicalProductOrderInsert);
        $medicalProductOrderID = $connection->insert_id;
        $medicalProductOrderDetailInsert = "INSERT INTO `medical_product_order_details` SET `medical_product_order_id` = '".$medicalProductOrderID."',`service` = 'OPD',`appointment_customer_staff_service_id` = '".$appointmentCustomerStaffServiceID."',`appointment_staff_id` = '".$appointment_staff_id."', `appointment_customer_id` = '".$appointment_customer_id."',`children_id` = '".$children_id."',`thinapp_id` = '".$thinapp_id."',`product_price` = '".$total_amount."',`quantity` = '1',`discount_type` = 'PERCENTAGE',`discount_value` = '0',`discount_amount` = '0',`amount` = '".$total_amount."',`total_amount` = '".$total_amount."',`created` = '".$created."', `modified` = '".$created."'";
        $connection->query($medicalProductOrderDetailInsert);
        return $medicalProductOrderID;
    }



    public static function get_doctor_service_new_appointment($thin_app_id,$doctor_id,$date){

        $query = "select app_ser.id as service_id, app_ser.name as service_name from doctor_appointment_setting as das join appointment_services as app_ser on app_ser.id = das.appointment_service_id join appointment_staffs as app_staff on app_staff.id = das.doctor_id and das.setting_type = app_staff.appointment_setting_type  and ( (das.appointment_day_time_id = (WEEKDAY(DATE('$date'))+1)) OR (DATE('$date') = DATE(das.appointment_date)) ) where das.doctor_id = $doctor_id and das.thinapp_id = $thin_app_id group by das.appointment_service_id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_doctor_address_new_appointment($thin_app_id,$doctor_id, $service_id,$date){

        $query = "select aa.id as address_id, aa.address from doctor_appointment_setting as das join appointment_addresses  as aa on aa.id = das.appointment_address_id join appointment_staffs as app_staff on app_staff.id = das.doctor_id and das.setting_type = app_staff.appointment_setting_type  and ( (das.appointment_day_time_id = (WEEKDAY(DATE('$date'))+1)) OR (DATE('$date') = DATE(das.appointment_date)) ) where das.doctor_id = $doctor_id and das.thinapp_id = $thin_app_id and das.appointment_service_id = $service_id group by das.appointment_address_id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }



    public static function new_get_doctor_booked_appointment($thin_app_id,$doctor_id,$service_id,$address_id,$date,$slot_as_key = false,$send_closed_appointment= false,$send_canceled_appointment= false)
    {

        $status = "'NEW','CONFIRM','RESCHEDULE','REFUND'";
        if($send_closed_appointment === true){
            $status .= ", 'CLOSED'";
        }
        if($send_canceled_appointment === true){
            $status .= ", 'CANCELED'";
        }
        $order = "order by acss.appointment_datetime asc, acss.queue_number asc";


        $query = "select acss.consulting_type, acss.is_paid_booking_convenience_fee, acss.patient_queue_checked_in, acss.patient_queue_type,acss.show_after_queue, IFNULL(ac.third_party_uhid, c.third_party_uhid) as third_party_uhid, acss.checked_in, acss.emergency_appointment, IF( (SELECT pda.id FROM patient_due_amounts AS pda WHERE acss.medical_product_order_id = pda.medical_product_order_id AND pda.settlement_by_order_id != acss.medical_product_order_id and pda.settlement_by_order_id > 0 limit 1) IS NOT NULL,'YES','NO') as due_amount_settled,( SELECT SUM(mpo.total_amount) FROM medical_product_orders AS mpo WHERE mpo.id = acss.medical_product_order_id  ) as total_billing, acss.has_token, acss.custom_token, '' as address, acss.id as appointment_id, IF(ac.id IS NOT NULL,'CUSTOMER','CHILDREN') as customer_type, IFNULL(ac.id,c.id) as customer_id, acss.slot_time, CONCAT(IFNULL(ac.title,c.title),' ',IFNULL(ac.first_name,c.child_name)) as name,IFNULL(ac.uhid,c.uhid) as uhid, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(ac.profile_photo,c.image) as profile_photo, acss.emergency_appointment, acss.queue_number, app_ser.service_amount, acss.sub_token, acss.skip_tracker, acss.has_token from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id =acss.appointment_customer_id left join childrens as c on c.id = acss.children_id left join appointment_services as app_ser on app_ser.id = acss.appointment_service_id where acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $doctor_id and acss.appointment_service_id = $service_id and acss.appointment_address_id = $address_id and acss.status IN ($status) and acss.delete_status != 'DELETED' and DATE(acss.appointment_datetime) = '$date' $order";
    
    	  

        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $return =array();
            if($slot_as_key === true){
                $list = mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
                foreach($list as $key =>$value){
                    $return[$value['slot_time']] = $value;
                }
                return $return;

            }else{
                return mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);

            }
        }
        return false;

    }

    public static function get_doctor_list_new_appointment($thin_app_id,$date){

        $query = "select app_staff.id as doctor_id, app_staff.name as doctor_name from doctor_appointment_setting as das  join appointment_staffs as app_staff on app_staff.id = das.doctor_id and das.setting_type = app_staff.appointment_setting_type  and ( (das.appointment_day_time_id = (WEEKDAY(DATE('$date'))+1)) OR (DATE('$date') = DATE(das.appointment_date)) ) and app_staff.status = 'ACTIVE' and app_staff.staff_type = 'DOCTOR' where  das.thinapp_id = $thin_app_id AND das.status = 'ACTIVE' group by das.doctor_id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_doctor_steps($doctor_id){

        $query = "select tpc.tab_prescription_category_id as category_id, tpc.name as category_name, tps.id as step_id, tps.step_title, tpt.id as tag_id, tpt.tag_name from tab_prescription_categories as tpc left join tab_prescription_steps as tps on tpc.tab_prescription_category_id = tps.tab_prescription_category_id and ( tps.tab_prescription_sub_category_id = 1 OR tps.tab_prescription_sub_category_id = 0 ) and tps.doctor_id = $doctor_id and tps.status ='ACTIVE' left join tab_prescription_tags as tpt on tpt.tab_prescription_step_id = tps.id and tpt.status='ACTIVE' where tpc.index <= 6 and tpc.status ='ACTIVE' and tpc.doctor_id = $doctor_id order by tpc.index ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_service_name_by_category($thin_app_id,$category_id){

        $query = "select UPPER(mp.name) as name from medical_products as mp where mp.thinapp_id = $thin_app_id and mp.status = 'ACTIVE' and mp.hospital_service_category_id = $category_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'name');
            return $staff_data;
        }else{
            return array();
        }
    }

    public static function get_app_default_doctor_code($thin_app_id){

        $query = "select di.doctor_code from doctors_ivr as di where di.thinapp_id = $thin_app_id and di.doctor_status = 'Active' order by di.id asc limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data['doctor_code'];
        }else{
            return 0;
        }
    }
    public static function nextOccurrence($day) {
        $currentDay = date('d');
        if ($day >= $currentDay) {
            // Get the timestamp of $day in this month
            $date = strtotime('+' . ($day - $currentDay) . ' days');
        } else {
            // Get the timestamp of the current day in next month, and subtract the days difference
            $date = strtotime('+1 month -' . ($currentDay - $day) . ' days');
        }
        return date('Y-m-d', $date);
    }


  
	public static function get_app_medical_product_list_back($thin_app_id,$send_out_of_stock_data=false){
        $condition = "";
        if(isset($_SESSION['Auth']))
        {
            $loginLab = $_SESSION['Auth']['User'];
            $roleLab = $loginLab['USER_ROLE'];
            if($roleLab =="LAB" || $roleLab =='PHARMACY'){
                $labPharmacyIsInhouse = $loginLab['LabPharmacyUser']['is_inhouse'];
                //$condition['lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
                if($labPharmacyIsInhouse == 'YES')
                {
                    $condition .= " AND mp.thinapp_id = $thin_app_id AND (mp.lab_pharmacy_user_id = '".$loginLab['LabPharmacyUser']['id']."' OR mp.lab_pharmacy_user_id = '0')";
                }
                else
                {
                    $condition .= " AND mp.thinapp_id = $thin_app_id AND mp.lab_pharmacy_user_id = '".$loginLab['LabPharmacyUser']['id']."'";
                }

            }
            else
            {
                //$condition .= " AND mp.thinapp_id = $thin_app_id OR ( mp.thinapp_id = $thin_app_id AND mp.lab_pharmacy_type = 'LAB' AND mp.lab_pharmacy_is_inhouse = 'YES' )";
                $condition .= " AND mp.thinapp_id = $thin_app_id and (mp.lab_pharmacy_is_inhouse = 'YES' OR mp.lab_pharmacy_type = 'NONE') and mp.module_type != 'HOSPITAL' ";
            }
        }

        if($send_out_of_stock_data==true){
            $having= " ((quantity - sold) > 0) OR ((sold - quantity) >= 0) OR quantity_id IS NULL OR quantity = 0";
        }else{
            $having= "((quantity - sold) > 0) OR quantity_id IS NULL OR quantity = 0";
        }



        $connection = ConnectionUtil::getConnection();
        $query = "select mpq.id as quantity_id, if(mpq.expiry_date='0000-00-00','Not Available',DATE_FORMAT(mpq.expiry_date, '%d/%m/%Y')) AS expiry, mpq.expiry_date, (mpq.quantity - mpq.dead) AS quantity, mpq.sold AS sold, mpq.mrp,  mp.id, CONCAT(mp.name,IF( mp.medicine_form <> '', CONCAT('(',mp.medicine_form,')') , '' )) AS name, mp.price, htr.name as tax_name, htr.rate, mp.is_price_editable, (SELECT (SUM(medi_pro_qty.quantity) - (SUM(medi_pro_qty.sold) + SUM(medi_pro_qty.dead))) FROM medical_product_quantities AS medi_pro_qty WHERE medi_pro_qty.medical_product_id = mp.id AND medi_pro_qty.status = 'ACTIVE' AND (( DATE(medi_pro_qty.expiry_date) >= DATE(NOW()) OR DATE(medi_pro_qty.expiry_date) = '0000-00-00' ) OR medi_pro_qty.expiry_date IS null) GROUP BY medi_pro_qty.medical_product_id LIMIT 1) AS total_qty from medical_products as mp left join hospital_service_categories as hsc on hsc.id = mp.hospital_service_category_id left join hospital_tax_rates as htr on htr.id = hsc.hospital_tax_rate_id left join medical_product_quantities as mpq on mpq.medical_product_id = mp.id and mpq.status = 'ACTIVE' AND mpq.status = 'ACTIVE' and (( DATE(mpq.expiry_date) >= DATE(NOW()) OR DATE(mpq.expiry_date) = '0000-00-00' ) OR mpq.expiry_date IS null) where mp.status ='ACTIVE' AND mp.is_billable ='1' ".$condition." HAVING $having ORDER BY mp.name ASC, mpq.expiry_date ASC";
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
            $dataToSend = array();
            foreach($data as $val){
                $val = array_map('utf8_encode', $val);
                $dataToSend[$val['id']]['id']= $val['id'];
                $dataToSend[$val['id']]['name']= $val['name'];
                $dataToSend[$val['id']]['price']= $val['price'];
                $dataToSend[$val['id']]['tax_name']= $val['tax_name'];
                $dataToSend[$val['id']]['rate']= $val['rate'];
                $dataToSend[$val['id']]['is_price_editable']= $val['is_price_editable'];
                $tmp =array();
                if(!empty($val['quantity_id'])){
                    $tmp['id'] = $val['quantity_id'];
                    $tmp['expiry'] = $val['expiry'];
                    $tmp['expiry_date'] = $val['expiry_date'];
                    $tmp['quantity'] = $val['quantity'];
                    $tmp['sold'] = $val['sold'];
                    $tmp['mrp'] = $val['mrp'];
                    $tmp['total_qty'] = $val['total_qty'];
                    $dataToSend[$val['id']]['qty_data'][] = $tmp;
                }else{
                    $dataToSend[$val['id']]['qty_data'] = $tmp;
                }

            }
            return array_values($dataToSend);
        }
        return array();

    }

	 public static function get_app_medical_product_list($thin_app_id,$send_out_of_stock_data=false){
        $condition = "";
        if(isset($_SESSION['Auth']))
        {
            $loginLab = $_SESSION['Auth']['User'];
            $roleLab = $loginLab['USER_ROLE'];
            if($roleLab =="LAB" || $roleLab =='PHARMACY'){
                $labPharmacyIsInhouse = $loginLab['LabPharmacyUser']['is_inhouse'];
                //$condition['lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
                if($labPharmacyIsInhouse == 'YES')
                {
                    $condition .= " AND mp.thinapp_id = $thin_app_id AND (mp.lab_pharmacy_user_id = '".$loginLab['LabPharmacyUser']['id']."' OR mp.lab_pharmacy_user_id = '0')";
                }
                else
                {
                    $condition .= " AND mp.thinapp_id = $thin_app_id AND mp.lab_pharmacy_user_id = '".$loginLab['LabPharmacyUser']['id']."'";
                }

            }
            else
            {
                //$condition .= " AND mp.thinapp_id = $thin_app_id OR ( mp.thinapp_id = $thin_app_id AND mp.lab_pharmacy_type = 'LAB' AND mp.lab_pharmacy_is_inhouse = 'YES' )";
                $condition .= " AND mp.thinapp_id = $thin_app_id and (mp.lab_pharmacy_is_inhouse = 'YES' OR mp.lab_pharmacy_type = 'NONE') and mp.module_type != 'HOSPITAL' ";
            }
        }

        if($send_out_of_stock_data==true){
            $having= " ((quantity - sold) > 0) OR ((sold - quantity) >= 0) OR quantity_id IS NULL OR quantity = 0";
        }else{
            $having= "((quantity - sold) > 0) OR quantity_id IS NULL OR quantity = 0";
        }
        $return =array();
        $file_name = "medical_product_$thin_app_id";
        if($send_out_of_stock_data==true || !$return = json_decode(WebservicesFunction::readJson($file_name,"medical_product"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select mpq.id as quantity_id, if(mpq.expiry_date='0000-00-00','Not Available',DATE_FORMAT(mpq.expiry_date, '%d/%m/%Y')) AS expiry, mpq.expiry_date, (mpq.quantity - mpq.dead) AS quantity, mpq.sold AS sold, mpq.mrp,  mp.id, CONCAT(mp.name,IF( mp.medicine_form <> '', CONCAT('(',mp.medicine_form,')') , '' )) AS name, mp.price, htr.name as tax_name, htr.rate, mp.is_price_editable, (SELECT (SUM(medi_pro_qty.quantity) - (SUM(medi_pro_qty.sold) + SUM(medi_pro_qty.dead))) FROM medical_product_quantities AS medi_pro_qty WHERE medi_pro_qty.medical_product_id = mp.id AND medi_pro_qty.status = 'ACTIVE' AND (( DATE(medi_pro_qty.expiry_date) >= DATE(NOW()) OR DATE(medi_pro_qty.expiry_date) = '0000-00-00' ) OR medi_pro_qty.expiry_date IS null) GROUP BY medi_pro_qty.medical_product_id LIMIT 1) AS total_qty from medical_products as mp left join hospital_service_categories as hsc on hsc.id = mp.hospital_service_category_id left join hospital_tax_rates as htr on htr.id = hsc.hospital_tax_rate_id left join medical_product_quantities as mpq on mpq.medical_product_id = mp.id and mpq.status = 'ACTIVE' AND mpq.status = 'ACTIVE' and (( DATE(mpq.expiry_date) >= DATE(NOW()) OR DATE(mpq.expiry_date) = '0000-00-00' ) OR mpq.expiry_date IS null) where mp.status ='ACTIVE' AND mp.is_billable ='1' ".$condition." HAVING $having ORDER BY mp.name ASC, mpq.expiry_date ASC";
            $list = $connection->query($query);
            if ($list->num_rows) {
                $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
                $dataToSend = array();
                foreach($data as $val){
                    $val = array_map('utf8_encode', $val);
                    $dataToSend[$val['id']]['id']= $val['id'];
                    $dataToSend[$val['id']]['name']= $val['name'];
                    $dataToSend[$val['id']]['price']= $val['price'];
                    $dataToSend[$val['id']]['tax_name']= $val['tax_name'];
                    $dataToSend[$val['id']]['rate']= $val['rate'];
                    $dataToSend[$val['id']]['is_price_editable']= $val['is_price_editable'];
                    $tmp =array();
                    if(!empty($val['quantity_id'])){
                        $tmp['id'] = $val['quantity_id'];
                        $tmp['expiry'] = $val['expiry'];
                        $tmp['expiry_date'] = $val['expiry_date'];
                        $tmp['quantity'] = $val['quantity'];
                        $tmp['sold'] = $val['sold'];
                        $tmp['mrp'] = $val['mrp'];
                        $tmp['total_qty'] = $val['total_qty'];
                        $dataToSend[$val['id']]['qty_data'][] = $tmp;
                    }else{
                        $dataToSend[$val['id']]['qty_data'] = $tmp;
                    }
    
                }
                $return = array_values($dataToSend);
                WebservicesFunction::createJson($file_name,json_encode($return),"CREATE","medical_product");
            
               
            }
        


        }
        return $return;

    }


    public static function get_app_default_medical_product_list($thin_app_id,$send_out_of_stock_data=false){
        $condition = "";
        if(isset($_SESSION['Auth']))
        {
            $loginLab = $_SESSION['Auth']['User'];
            $roleLab = $loginLab['USER_ROLE'];
            if($roleLab =="LAB" || $roleLab =='PHARMACY'){
                $labPharmacyIsInhouse = $loginLab['LabPharmacyUser']['is_inhouse'];
                //$condition['lab_pharmacy_user_id'] = $loginLab['LabPharmacyUser']['id'];
                if($labPharmacyIsInhouse == 'YES')
                {
                    $condition .= " AND mp.thinapp_id = $thin_app_id AND (mp.lab_pharmacy_user_id = '".$loginLab['LabPharmacyUser']['id']."' OR mp.lab_pharmacy_user_id = '0')";
                }
                else
                {
                    $condition .= " AND mp.thinapp_id = $thin_app_id AND mp.lab_pharmacy_user_id = '".$loginLab['LabPharmacyUser']['id']."'";
                }

            }
            else
            {
                //$condition .= " AND mp.thinapp_id = $thin_app_id OR ( mp.thinapp_id = $thin_app_id AND mp.lab_pharmacy_type = 'LAB' AND mp.lab_pharmacy_is_inhouse = 'YES' )";
                $condition .= " AND mp.thinapp_id = $thin_app_id and (mp.lab_pharmacy_is_inhouse = 'YES' OR mp.lab_pharmacy_type = 'NONE') and mp.module_type != 'HOSPITAL' ";
            }
        }

        if($send_out_of_stock_data==true){
            $having= " ((quantity - sold) > 0) OR ((sold - quantity) >= 0) OR quantity_id IS NULL OR quantity = 0";
        }else{
            $having= "((quantity - sold) > 0) OR quantity_id IS NULL OR quantity = 0";
        }



        $connection = ConnectionUtil::getConnection();
        $query = "select mpq.id as quantity_id, if(mpq.expiry_date='0000-00-00','Not Available',DATE_FORMAT(mpq.expiry_date, '%d/%m/%Y')) AS expiry, mpq.expiry_date, (mpq.quantity - mpq.dead) AS quantity, mpq.sold AS sold, mpq.mrp,  mp.id, CONCAT(mp.name,IF( mp.medicine_form <> '', CONCAT('(',mp.medicine_form,')') , '' )) AS name, mp.price, htr.name as tax_name, htr.rate, mp.is_price_editable, (SELECT (SUM(medi_pro_qty.quantity) - (SUM(medi_pro_qty.sold) + SUM(medi_pro_qty.dead))) FROM medical_product_quantities AS medi_pro_qty WHERE medi_pro_qty.medical_product_id = mp.id AND medi_pro_qty.status = 'ACTIVE' AND (( DATE(medi_pro_qty.expiry_date) >= DATE(NOW()) OR DATE(medi_pro_qty.expiry_date) = '0000-00-00' ) OR medi_pro_qty.expiry_date IS null) GROUP BY medi_pro_qty.medical_product_id LIMIT 1) AS total_qty from medical_products as mp left join hospital_service_categories as hsc on hsc.id = mp.hospital_service_category_id left join hospital_tax_rates as htr on htr.id = hsc.hospital_tax_rate_id left join medical_product_quantities as mpq on mpq.medical_product_id = mp.id and mpq.status = 'ACTIVE' AND mpq.status = 'ACTIVE' and (( DATE(mpq.expiry_date) >= DATE(NOW()) OR DATE(mpq.expiry_date) = '0000-00-00' ) OR mpq.expiry_date IS null) where mp.status ='ACTIVE' AND mp.is_billable ='1' AND mp.is_default ='Y' ".$condition." HAVING $having ORDER BY mp.name ASC, mpq.expiry_date ASC";
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data = mysqli_fetch_all($list,MYSQLI_ASSOC);
            $dataToSend = array();
            foreach($data as $val){
                $val = array_map('utf8_encode', $val);
                $dataToSend[$val['id']]['id']= $val['id'];
                $dataToSend[$val['id']]['name']= $val['name'];
                $dataToSend[$val['id']]['price']= $val['price'];
                $dataToSend[$val['id']]['tax_name']= $val['tax_name'];
                $dataToSend[$val['id']]['rate']= $val['rate'];
                $dataToSend[$val['id']]['is_price_editable']= $val['is_price_editable'];
                $tmp =array();
                if(!empty($val['quantity_id'])){
                    $tmp['id'] = $val['quantity_id'];
                    $tmp['expiry'] = $val['expiry'];
                    $tmp['expiry_date'] = $val['expiry_date'];
                    $tmp['quantity'] = $val['quantity'];
                    $tmp['sold'] = $val['sold'];
                    $tmp['mrp'] = $val['mrp'];
                    $tmp['total_qty'] = $val['total_qty'];
                    $dataToSend[$val['id']]['qty_data'][] = $tmp;
                }else{
                    $dataToSend[$val['id']]['qty_data'] = $tmp;
                }

            }
            return array_values($dataToSend);
        }
        return array();

    }


    public static function  array_order_by(){
        $args = func_get_args();
        $data = array_shift($args);
        foreach ($args as $n => $field) {
            if (is_string($field)) {
                $tmp = array();
                foreach ($data as $key => $row)
                    $tmp[$key] = $row[$field];
                $args[$n] = $tmp;
            }
        }
        $args[] = &$data;
        call_user_func_array('array_multisort', $args);
        return array_pop($args);
    }


    public static function get_user_stats_count($thin_app_id)
    {
        $query = "select * from ( select (select count(*) from users where thinapp_id = $thin_app_id and role_id = 1 and DATE(created) =DATE(NOW())) as today_downloads, (select count(*) from users where thinapp_id = $thin_app_id and role_id = 1 and YEARWEEK(NOW()) = YEARWEEK(created)  ) as week_downloads, (select count(*) from users where thinapp_id = $thin_app_id and role_id = 1 and MONTH(created) = MONTH(NOW())  ) as month_downloads,  (select count(*) from appointment_customer_staff_services  where thinapp_id = $thin_app_id and status NOT IN('CANCELED','REFUND') and DATE(appointment_datetime) =DATE(NOW())) as today_appointment, (select count(*) from appointment_customer_staff_services  where thinapp_id = $thin_app_id and status NOT IN('CANCELED','REFUND') and YEARWEEK(NOW()) = YEARWEEK(appointment_datetime)   ) as week_appointment, (select count(*) from appointment_customer_staff_services  where thinapp_id = $thin_app_id and status NOT IN('CANCELED','REFUND') and MONTH(appointment_datetime) = MONTH(NOW())    ) as month_appointment) as total";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function get_stats_appointment_list($thin_app_id,$from_date,$to_date)
    {
        $query = "select acss.appointment_datetime, IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) as patient_mobile, acss.queue_number,  app_staff.name as doctor_name from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id= acss.appointment_customer_id left join childrens as c on c.id = acss.children_id join appointment_staffs as app_staff on app_staff.id = acss.appointment_staff_id  where acss.thinapp_id = $thin_app_id and acss.status NOT IN('CANCELED','REFUND') AND DATE(acss.appointment_datetime) BETWEEN '$from_date' AND '$to_date' order by acss.appointment_datetime desc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        } else {
            return false;
        }
    }
    public static function get_stats_download_list($thin_app_id,$from_date,$to_date)
    {
        $query = "select u.created, u.username, u.mobile  from users as u where u.thinapp_id = $thin_app_id and u.role_id = 1 AND DATE(u.created) BETWEEN '$from_date' AND '$to_date' order by u.created desc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        } else {
            return false;
        }
    }


    public static function web_get_patient_list($address_id,$service_id,$doctor_id,$date,$search,$offset,$limit,$appointment_id=0){


        $condition = "";
        if(!empty($appointment_id)){
            $condition = " acss.id =".$appointment_id;
        }else{
            $search_via = "";
            if(!empty($search)){
                $search_via = " and ( ac.first_name like '%$search%' OR ac.mobile like '%$search%' OR c.child_name like '%$search%' OR c.mobile like '%$search%' )";
            }
            $condition = " acss.appointment_address_id = $address_id and acss.appointment_service_id = $service_id and  acss.appointment_staff_id = $doctor_id and acss.status IN('NEW','CONFIRM','RESCHEDULE','CANCELED','CLOSED') and DATE(acss.appointment_datetime) = '$date' $search_via order by FIELD(acss.`status`,'NEW','CONFIRM','RESCHEDULE','CLOSED','CANCELED'), acss.appointment_datetime asc limit $offset, $limit ";
        }

        $select = "select acss.thinapp_id, acss.payment_status, acss.status, df.id as folder_id, acss.id as appointment_id, IF(ac.id IS NOT NULL,'CUSTOMER','CHILDREN') as patient_type, IFNULL(ac.first_name,c.child_name) as patient_name, RIGHT(IFNULL(ac.mobile,c.mobile),10) as patient_mobile, IFNULL(ac.profile_photo,c.image) as profile_photo, IFNULL(ac.id,c.id) as patient_id, acss.sub_token, acss.has_token, acss.custom_token, app_ser.service_validity_time, IFNULL(ac.third_party_uhid,'') as third_party_uhid, IFNULL(ac.country_id,c.country_id) as country_id, IFNULL(ac.state_id,c.state_id) as state_id,IFNULL(ac.city_id,c.city_id) as city_id,  IFNULL(ac.city_name,c.city_name) as city_name, acss.referred_by, acss.referred_by_mobile, acss.notes, acss.reason_of_appointment, IFNULL(ac.blood_group,c.blood_group) as blood_group, marital_status, DATE_FORMAT(ac.conceive_date,'%d-%m-%Y') AS conceive_date, DATE_FORMAT(ac.expected_date,'%d-%m-%Y') AS expected_date, IFNULL(ac.email,'') as email, DATE_FORMAT(IFNULL(ac.dob,c.dob),'%d-%m-%Y') as dob, acss.slot_time, IFNULL(ac.parents_mobile,c.parents_mobile) as parents_mobile, IFNULL(ac.field1,c.field1) as field1, IFNULL(ac.field2,c.field2) as field2, IFNULL(ac.field3,c.field3) as field3, IFNULL(ac.field4,c.field4) as field4, IFNULL(ac.field5,c.field5) as field5, IFNULL(ac.field6,c.field6) as field6, IFNULL(ac.bp_systolic,c.bp_systolic) as bp_systolic, IFNULL(ac.bp_diasystolic,c.bp_diasystolic) as bp_diasystolic, IFNULL(ac.bmi,c.bmi) as bmi, IFNULL(ac.bmi_status,c.bmi_status) as bmi_status, IFNULL(ac.temperature,c.temperature) as temperature, IFNULL(ac.o_saturation,c.o_saturation) as o_saturation,  swp.header_rotate, swp.tb_rotate, swp.header_box_border, app_staff.barcode_on_prescription, DATE_FORMAT(mpo.created,'%d-%m-%Y %h:%i %p')  as receipt_datetime, swp.barcode, acss.id as appointment_id, swp.id as setting_id, swp.top, swp.left, swp.height, swp.width, swp.fields, swp.font_size, IFNULL(ac.address,c.patient_address) as patient_address, IFNULL(ac.gender,c.gender) as gender,  IFNULL(ac.uhid,c.uhid) as uhid, IFNULL(ac.height,cg.height) as patient_height, IFNULL(ac.weight,cg.weight) as weight, IFNULL(ac.head_circumference,cg.head_circumference) as head_circumference, DATE_FORMAT(acss.appointment_datetime,'%d-%m-%Y') AS appointment_datetime , IFNULL(ac.relation_prefix,c.relation_prefix) as relation_prefix, IFNULL(ac.parents_name,c.parents_name) as parents_name, acss.queue_number, IFNULL(ac.address,c.patient_address) as address, app_staff.name as doctor_name, IFNULL(ac.first_name,c.child_name) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age),c.dob) as age, app_ser.service_validity_time, acss.amount from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  LEFT JOIN child_growths AS cg ON cg.children_id = c.id AND cg.id = (SELECT MAX(id) FROM child_growths AS c_growth WHERE c_growth.children_id = c.id ) left join appointment_staffs as app_staff on app_staff.id = acss.appointment_staff_id left join appointment_services as app_ser on app_ser.id= acss.appointment_service_id  join appointment_addresses as aa on aa.id= acss.appointment_address_id left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id left join setting_web_prescriptions as swp on swp.thinapp_id = acss.thinapp_id left join drive_folders as df on (df.children_id= c.id OR df.appointment_customer_id = ac.id ) ";


        // $select = "select acss.sub_token, acss.has_token, acss.custom_token, app_ser.service_validity_time, mpo.created AS receipt_datetime, mpo.total_amount as amount, app_sta.NAME AS doctor_name, ac.height, ac.weight, ac.head_circumference, IFNULL(ac.uhid,c.uhid) as uhid, acss.thinapp_id, acss.status, acss.payment_status, IFNULL(ac.address,c.address) as address, RIGHT(IFNULL(ac.parents_mobile,c.parents_mobile),10) as parents_mobile, IFNULL(ac.parents_name,c.parents_name) as parents_name, df.id as folder_id, acss.id as appointment_id, IF(ac.id IS NOT NULL,'CUSTOMER','CHILDREN') as patient_type, IFNULL(ac.first_name,c.child_name) as patient_name, RIGHT(IFNULL(ac.mobile,c.mobile),10) as patient_mobile, IFNULL(ac.profile_photo,c.image) as profile_photo, IFNULL(ac.id,c.id) as patient_id, acss.queue_number, acss.has_token, acss.sub_token, DATE_FORMAT(acss.appointment_datetime,'%d-%m-%Y %h:%i %p') as appointment_datetime, IFNULL(ac.age,'') as age, DATE_FORMAT(IFNULL(ac.dob,c.dob),'%d-%m-%Y') as dob, IF(IFNULL(ac.blood_group, c.blood_group)='','N/A',IFNULL(ac.blood_group, c.blood_group)) as blood_group, IFNULL(ac.gender, c.gender) as gender from appointment_customer_staff_services as acss LEFT JOIN appointment_staffs AS app_sta ON app_sta.id = acss.appointment_staff_id LEFT JOIN appointment_services AS app_ser ON app_ser.id = acss.appointment_service_id LEFT JOIN medical_product_orders AS mpo ON mpo.appointment_customer_staff_service_id = acss.id AND mpo.is_opd = 'Y' left join appointment_customers as ac on ac.id= acss.appointment_customer_id left join childrens as c on c.id = acss.children_id left join drive_folders as df on (df.children_id= c.id OR df.appointment_customer_id = ac.id )";
        $query = " $select where $condition";

        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }


    }

    public static function get_patient_list_autocomplete($thin_app_id){
        $query = "select * from ( (select ac.id as patient_id, ac.first_name as patient_name, 'CUSTOMER' as patient_type, RIGHT(ac.mobile,10) AS mobile from appointment_customers as ac where ac.status ='ACTIVE' and ac.thinapp_id =$thin_app_id ) UNION ALL (select c.id as patient_id, c.child_name as patient_name, 'CHILDREN' as patient_type, RIGHT(c.mobile,10) AS mobile from childrens as c where c.status ='ACTIVE' AND c.thinapp_id = $thin_app_id ) ) as final";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        } else {
            return false;
        }
    }


    public static function get_patient_detail($patient_id,$patient_type){

        if($patient_type=="CHILDREN"){
            $query = "select c.user_id, c.id as patient_id, c.child_name as patient_name,  RIGHT(c.mobile,10) AS mobile, '' as email,  IF(c.dob IN('0000-00-00',NULL),'',DATE_FORMAT(c.dob,'%d-%m-%Y')) as dob, c.gender, IF(c.blood_group='','N/A',c.blood_group) as blood_group, '' as marital_status, c.address, c.parents_name, c.parents_mobile from childrens as c where c.id = $patient_id limit 1";
        }else{
            $query = "select ac.user_id, ac.id as pateint_id, ac.first_name as patient_name,  RIGHT(ac.mobile,10) AS mobile, ac.email, ac.age, IF(ac.dob IN('0000-00-00',NULL),'',DATE_FORMAT(ac.dob,'%d-%m-%Y')) as dob, ac.gender, IF(ac.blood_group='','N/A',ac.blood_group) as blood_group, ac.marital_status, ac.address , ac.parents_name, ac.parents_mobile from appointment_customers as ac where ac.id =$patient_id limit 1";
        }
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }


    public static function old_appointment_service_list($doctor_id){

        $query = "select app_ser.* from appointment_staff_services as ass join appointment_services as app_ser on app_ser.id = ass.appointment_service_id and app_ser.status = 'ACTIVE' where ass.appointment_staff_id = $doctor_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }


    }


    public static function new_appointment_service_list($doctor_id){
        $query = "select app_ser.* from doctor_appointment_setting as das join appointment_services as app_ser on app_ser.id = das.appointment_service_id and das.status = 'ACTIVE' where das.doctor_id = $doctor_id group by das.appointment_service_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }


    }


    public static function new_appointment_address_list($doctor_id){
        $query = "select aa.* from doctor_appointment_setting as das join appointment_addresses as aa on aa.id = das.appointment_address_id and aa.status = 'ACTIVE' where das.doctor_id = $doctor_id group by das.appointment_address_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }


    }

    public static function close_appointment_notification($notification_array){

        $background = $notification_array['background'];
        $labPharmacyUserData =$notification_array['labPharmacyUserData'];
        $appointment_data =$notification_array['appointment_data'];
        $tag_id = $notification_array['tag_id'];
        $appointment_customer_id = $notification_array['appointment_customer_id'];
        $appointment_staff_id = $notification_array['appointment_staff_id'];
        $children_id = $notification_array['children_id'];
        $thin_app_id = $notification_array['thin_app_id'];

        if (!empty($background)) {
            Custom::send_process_to_background();
            if (isset($background['notification'])) {
                Custom::send_web_tracker_notification($thin_app_id);
                foreach ($background['notification'] as $key => $value) {
                    $option = $value['data'];
                    $user_id = $value['user_id'];
                    Custom::send_notification_by_user_id($option, array($user_id), $thin_app_id);
                }
            }

            if (isset($background['sms'])) {
                foreach ($background['sms'] as $key => $value) {
                    $message = $value['message'];
                    $mobile = $value['mobile'];
                    //Custom::send_single_sms($mobile, $message, $thin_app_id);
                }
            }
            if(!empty($appointment_data)){
                /* send tracker notification to appointment users */
                $token_array = Custom::get_upcoming_appointment_user_token($thin_app_id,$appointment_data['doctor_id'],$appointment_data['address_id'],$appointment_data['appointment_id']);
                if(!empty($token_array)){
                    $option = array(
                        'thinapp_id' => $thin_app_id,
                        'staff_id' => 0,
                        'customer_id' => 0,
                        'service_id' => 0,
                        'channel_id' => 0,
                        'role' => "CUSTOMER",
                        'flag' => 'APPOINTMENT_TRACKER',
                        'title' => "New Tracker Request",
                        'message' => "Your tracker message",
                        'description' => "Your tracker message",
                        'chat_reference' => '',
                        'module_type' => 'APPOINTMENT_TRACKER',
                        'module_type_id' => 0,
                        'firebase_reference' => ""
                    );
                    Custom::send_notification_via_token($option,$token_array,$thin_app_id);
                }
            }
        }
        if(!empty($labPharmacyUserData))
        {

            $optionSend = array(
                'thinapp_id' => $thin_app_id,
                'channel_id' => 0,
                'flag' => 'LAB_REFRESH',
                'title' => ".",
                'message' => ".",
                'description' => ".",
                'chat_reference' => '',
                'module_type' => 'ABCD',
                'module_type_id' => 0,
                'firebase_reference' => ""
            );

            foreach ($labPharmacyUserData as $value) {

                $user_id = $value['user_id'];
                Custom::send_notification_by_user_id($optionSend, array($user_id), $thin_app_id);

            }
        }
        if(!empty($appointment_data)){



            $file_name = 'doctor_tracker_' . $appointment_data['appointment_staff_id']."_".date('Ymd',strtotime($appointment_data['appointment_datetime']));
            WebservicesFunction::deleteJson(array($file_name),"tracker");
            $result = Custom::manage_emergency_tracker_cache($appointment_data['appointment_id'],"DELETE");

        }
        if(!empty($tag_id)){


            $tag_id = explode(',',$tag_id);
            $tag_string = "'".implode("','",$tag_id)."'";
            $condition = !empty($appointment_customer_id)?" appointment_customer_id = $appointment_customer_id":" children_id = $children_id";
            $condition .= " and doctor_id = $appointment_staff_id and patient_illness_tag_id NOT IN($tag_string)";
            $query = " select patient_illness_tag_id from patient_tags where $condition";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $already_array = array();
            if ($service_message_list->num_rows) {
                $already_array = array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'patient_illness_tag_id');
            }

            $result=array_diff($tag_id,$already_array);
            foreach($result as $key => $id){

                $created = Custom::created();
                $sql = "INSERT INTO patient_tags (thinapp_id, doctor_id, appointment_customer_id, children_id,  patient_illness_tag_id,  created) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssssss', $thin_app_id, $appointment_staff_id, $appointment_customer_id, $children_id, $id,  $created);
                $stmt->execute();
            }

        }
    }

    public static function cancel_appointment_notification($notification_array,$request_type=""){

        $background = $notification_array['background'];
        $appointment_data =$notification_array['appointment_data'];
        $thin_app_id = $notification_array['thin_app_id'];
        $appointment_user_role = $notification_array['appointment_user_role'];
        $cancel_by = $notification_array['cancel_by'];
        $cancel_message = $notification_array['cancel_message'];
        $appointment_id = $notification_array['appointment_id'];
    	if(in_array($appointment_data['is_paid_booking_convenience_fee'],array('YES','NOT_APPLICABLE'))){
        if (!empty($background)) {
            Custom::send_process_to_background();
            $customer_sms = Custom::create_custom_sms_from_template($appointment_id,'CANCEL');
            if (isset($background['notification'])) {

                foreach ($background['notification'] as $key => $value) {
                    $option = $value['data'];
                    $user_id = $value['user_id'];
                    if(!empty($customer_sms) && $value['send_to'] =="USER"){
                        $option['message'] = $customer_sms;
                    }
                    $result = Custom::send_notification_by_user_id($option, array($user_id), $thin_app_id);
                }
            }
            if (isset($background['sms'])) {
                foreach ($background['sms'] as $key => $value) {
                    $message = $value['message'];
                    $mobile = $value['mobile'];
                    if(!empty($customer_sms) && $value['send_to'] =="USER"){
                        $message = $customer_sms;
                    }
                    //Custom::send_single_sms($mobile, $message, $thin_app_id);
                }
            }
        }
        if ($cancel_by == "DOCTOR" || $appointment_user_role == 'ADMIN' || $appointment_user_role == 'STAFF') {
            $download_link = true;
            if(!empty($customer_sms)){
                $cancel_message = $customer_sms;
                $download_link = false;
            }

            $mobile = $appointment_data['customer_mobile'];
            $cus_data = Custom::get_user_by_mobile($thin_app_id, $mobile);
            if(@$cus_data['app_installed_status'] != "INSTALLED" && $thin_app_id != 777) {
                Custom::send_single_sms($mobile, $cancel_message, $thin_app_id,false,$download_link);
            }
        }
        if(!empty($appointment_data)){

            $result = Custom::manage_emergency_tracker_cache($appointment_id,"DELETE");

            $token_array = Custom::get_upcoming_appointment_user_token($thin_app_id,$appointment_data['doctor_id'],$appointment_data['address_id'],$appointment_id);
            if(!empty($token_array)){
                $option = array(
                    'thinapp_id' => $thin_app_id,
                    'staff_id' => 0,
                    'customer_id' => 0,
                    'service_id' => 0,
                    'channel_id' => 0,
                    'role' => "CUSTOMER",
                    'flag' => 'APPOINTMENT_TRACKER',
                    'title' => "New Tracker Request",
                    'message' => "Your tracker message",
                    'description' => "Your tracker message",
                    'chat_reference' => '',
                    'module_type' => 'APPOINTMENT_TRACKER',
                    'module_type_id' => 0,
                    'firebase_reference' => ""
                );
                Custom::send_notification_via_token($option,$token_array,$thin_app_id);
            }

            $file_name = 'doctor_tracker_' . $appointment_data['appointment_staff_id']."_".date('Ymd',strtotime($appointment_data['appointment_datetime']));
            WebservicesFunction::deleteJson(array($file_name),"tracker");
            Custom::send_web_tracker_notification($thin_app_id);

        }
        }




    }

    public static function appointment_payment_notification($notification_array){
        $background = $notification_array['background'];
        $thin_app_id = $notification_array['thin_app_id'];
    	$appointment_id = @$notification_array['appointment_id'];
        $send_appointment_tracker = $notification_array['send_appointment_tracker'];
        if (!empty($background)) {

            Custom::send_process_to_background();
            if (isset($background['notification'])) {
                foreach ($background['notification'] as $key => $value) {
                    $option = $value['data'];
                    $user_id = $value['user_id'];
                    Custom::send_notification_by_user_id($option, array($user_id), $thin_app_id);
                }
            }

            if (isset($background['sms'])) {
                foreach ($background['sms'] as $key => $value) {
                    if(Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC')){
                        if($value['send_to']=='USER'){
                            $response = Custom::sendWhatsapp(1,$appointment_id,$thin_app_id);
                        }
                        if($value['send_to']=='DOCTOR'){
                            $response = Custom::sendWhatsapp(2,$appointment_id,$thin_app_id);
                        }
                    }
                    //  Custom::send_single_sms($mobile, $message, $thin_app_id);

                    if($send_appointment_tracker === true){
                        Custom::send_appointment_tracker_on_booking($thin_app_id,$mobile);
                    }
                }
            }


        }

    }


    public static function get_total_earning($address_id,$service_id,$doctor_id,$date=null){

        if(empty($date)){
            $date = date('Y-m-d');
        }

        $common_query = "select IFNULL(sum(mpo.total_amount),0) as total from appointment_customer_staff_services as acss join medical_product_orders as mpo on acss.id= mpo.appointment_customer_staff_service_id where  acss.appointment_address_id = $address_id and acss.appointment_service_id = $service_id and  acss.appointment_staff_id = $doctor_id and acss.payment_status = 'SUCCESS'";
        $today_query = "( $common_query and  DATE(acss.appointment_datetime) = '$date' ) as today_collection ";
        $month_query = "( $common_query and  MONTH(acss.appointment_datetime) = MONTH('$date') ) as month_collection ";
        $total_query = "( $common_query ) as total_collection ";
        $query  = " select $today_query, $month_query, $total_query ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $total = mysqli_fetch_assoc($service_message_list);
            return $total;
        }else{
            return false;
        }
    }



    public static function get_folder_share_with_doctor_ids($drive_folder_id){

        $query = "select app_staff.user_id from drive_shares as ds left join appointment_staffs as app_staff on app_staff.mobile = ds.share_with_mobile and ds.thinapp_id = app_staff.thinapp_id and app_staff.staff_type='DOCTOR' AND app_staff.status = 'ACTIVE' where ds.drive_folder_id = $drive_folder_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'user_id');
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_address_list_drp($thin_app_id){

        $query = "select app_add.id, app_add.address from  appointment_addresses as app_add where app_add.thinapp_id = $thin_app_id and app_add.status='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'address','id');
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_address_list($thin_app_id){
        $query = "select app_add.* from  appointment_addresses as app_add where app_add.thinapp_id = $thin_app_id and app_add.status='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }else{
            return false;
        }
    }

    public static function get_product_list_drp($thin_app_id){

        $condition = "";
        if(isset($_SESSION['Auth']))
        {
            $loginLab = $_SESSION['Auth']['User'];
            $roleLab = $loginLab['USER_ROLE'];
            if($roleLab =="LAB" || $roleLab =='PHARMACY'){
                $condition .= " AND mp.lab_pharmacy_user_id = ".$loginLab['LabPharmacyUser']['id'];
            }else{
                $condition .= " AND ((mp.lab_pharmacy_user_id = 0) OR (mp.lab_pharmacy_type = 'LAB' AND mp.lab_pharmacy_is_inhouse = 'YES' ))";
            }
        }

        $query = "select mp.id, mp.name from medical_products as mp join hospital_service_categories as hsc on hsc.id= mp.hospital_service_category_id and hsc.hospital_service_category_type_id IN(1,2) where mp.status ='ACTIVE' and mp.thinapp_id =$thin_app_id ".$condition." order by mp.name asc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'name','id');
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function send_save_prescription_notification($data){

        $send_notification = $data['send_notification'];
        $filter_array = $data['filter_array'];
        $thin_app_id = $data['thin_app_id'];
        $mobile = $data['mobile'];
        $user_id = $data['user_id'];
        $doctor_only = isset($data['doctor_only'])?$data['doctor_only']:true;
        if($send_notification === true &&  !empty($filter_array)){
            foreach($filter_array as $drive_folder_id =>$file_array){
                Custom::send_notification_on_offline_save($thin_app_id, $drive_folder_id,$file_array,$mobile,$user_id,$doctor_only);
            }
        }
    }


    public static function get_first_customer_by_mobile($thin_app_id,$mobile){
        $query = "select ac.*, df.id as folder_id from appointment_customers as ac left join drive_folders as df on df.appointment_customer_id = ac.id  where ac.mobile = '$mobile' and ac.thinapp_id = $thin_app_id and ac.status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }


    public static function get_app_default_prescription_folder($thin_app_id){
        $query = "select * from drive_folders  where thinapp_id = $thin_app_id and default_prescription_folder ='YES' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function add_app_default_prescription_folder($thin_app_id){

        $default = Custom::get_app_default_prescription_folder($thin_app_id);
        if(empty($default)){
            $data = array();
            $connection= ConnectionUtil::getConnection();
            $app_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
            $data['thin_app_id'] = $thin_app_id;
            $data['user_id'] = $app_admin_data['id'];
            $data['app_key'] = APP_KEY;
            $data['mobile'] = $app_admin_data['mobile'];
            $data['folder_name'] = 'Anonymous Prescription';
            $data['description'] = "";
            $data['folder_type'] = "PUBLIC";
            $data['is_instruction_bucket'] = "NO";
            $data['default_prescription_folder'] = "YES";
            $data['allow_add_file'] = "NO";
            $result = WebservicesFunction::add_folder($data, $connection);
            $result = json_decode($result, true);
            if ($result['status'] == 1) {
                return $result['folder_id'];
            }else{
                $drive_folder_id = 0;
            }
        }else{
            return $default['id'];
        }


    }

    public static function get_barcode_data($barcode){
        if(!empty($barcode)){
            /* 1 for online barcode AND single alfabate for offline barcode status */
            $barcode_status = substr($barcode,0,1);
            /* barcode for module e.g A= APPOINTMENT */
            $module_name = substr($barcode,1,1);
            /* UNIQUE ID OR KEY */
            $id = substr($barcode,2);
            if( $barcode_status == 1){
                if(( ctype_alpha($module_name) && strlen($module_name) ==1 ) && is_numeric($id)){
                    $return = array(
                        'STATUS'=>'ONLINE',
                        'MODULE'=>'APPOINTMENT',
                        'ID'=>$id
                    );
                    return $return;
                }
            }else{
                $module_name = substr($barcode,-6,1);
                /* barcode for module e.g A= APPOINTMENT */
                $barcode_status = substr($barcode,-8,1);
                /* UNIQUE ID OR KEY */
                $id = substr($barcode,-5);
                if( ctype_alpha($module_name)  && ( ctype_alpha($module_name) && strlen($module_name) ==1 ) && is_numeric($id)  ){
                    $return = array(
                        'STATUS'=>'OFFLINE',
                        'MODULE'=>'APPOINTMENT',
                        'ID'=>$id
                    );
                    return $return;
                }
            }
        }
        return false;
    }

    public static function find_illness_tag_by_name($thin_app_id, $doctor_id, $tag_name){
        $tag_name = strtoupper(trim($tag_name));
        $connection = ConnectionUtil::getConnection();
        $condition = " thinapp_id = $thin_app_id and status = 'ACTIVE' AND UPPER(tag_name) ='$tag_name' ";
        if(!empty($doctor_id)){
            $condition .= " and doctor_id = $doctor_id";
        }
        $query = " select id from patient_illness_tags where $condition limit 1";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }return false;

    }

    public static function get_patient_by_folder_id($folder_id)
    {
        $query = "select (CASE WHEN ac.id IS NOT NULL THEN 'CUSTOMER' WHEN c.id IS NOT NULL THEN 'CHILDREN' ELSE 'OTHER' END  ) as patient_type, IFNULL(IFNULL(ac.id,c.id),0) as patient_id, IFNULL(IFNULL(ac.first_name,c.child_name),'Anonymous') as patient_name, IFNULL(IFNULL(ac.uhid, c.uhid),0) as uhid, IFNULL(ac.mobile,c.mobile) as mobile from  drive_folders as df  left join appointment_customers as ac on ac.id = df.appointment_customer_id left join childrens as c on c.id= df.children_id where df.id = $folder_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function getBase64ImageSize($base64Image,$size_in ='MB'){ //return memory size in B, KB, MB

        if(!empty($base64Image)){
            $size_in_bytes = (int) (strlen(rtrim($base64Image, '=')) * 3 / 4);
            $size_in_kb    = $size_in_bytes / 1024;
            $size_in_mb    = $size_in_kb / 1024;
            if($size_in =="MB"){
                return $size_in_mb;
            }else{
                return $size_in_mb;
            }
        }
        return 0;

    }

    public static function utf8_to_unicode($str) {
        $unicode = array();
        $values = array();
        $lookingFor = 1;
        for ($i = 0; $i < strlen($str); $i++) {
            $thisValue = ord($str[$i]);
            if ($thisValue < 128) {
                $number = dechex($thisValue);
                $unicode[] = (strlen($number) == 1) ? '%u000' . $number : "%u00" . $number;
            } else {
                if (count($values) == 0)
                    $lookingFor = ( $thisValue < 224 ) ? 2 : 3;
                $values[] = $thisValue;
                if (count($values) == $lookingFor) {
                    $number = ( $lookingFor == 3 ) ?
                        ( ( $values[0] % 16 ) * 4096 ) + ( ( $values[1] % 64 ) * 64 ) + ( $values[2] % 64 ) :
                        ( ( $values[0] % 32 ) * 64 ) + ( $values[1] % 64
                        );
                    $number = dechex($number);
                    $unicode[] =
                        (strlen($number) == 3) ? "%u0" . $number : "%u" . $number;
                    $values = array();
                    $lookingFor = 1;
                } // if
            } // if
        }
        return implode("", $unicode);
    }

    public static function send_save_barcode_prescription_notification($data){

        $notification = @$data['notification'];
        $drive_folder_id = @$data['drive_folder_id'];
        $my_script_string = @$data['my_script_string'];
        $connection = ConnectionUtil::getConnection();
        $thin_app_id = @$data['thin_app_id'];
        $doctor_id = @$data['doctor_id'];
        $user_id = @$data['user_id'];
        $mobile = @$data['mobile'];
        $number_array = @$data['number_array'];
        $token_number = @$data['token_number'];
        $appointment_id = @$data['appointment_id'];

        if(!empty($notification)){
            $notification['doctor_only']= false;
            Custom::send_save_prescription_notification($notification);

            if(!empty($appointment_id)){
                $appointment_data = Custom::get_appointment_by_id($appointment_id,$thin_app_id);
                if(!empty($appointment_data) && in_array($appointment_data['status'],array('NEW','CONFIRM','RESCHEDULE'))){
                    $post=array();
                    $post['app_key'] = APP_KEY;
                    $post['user_id'] =$user_id;
                    $post['thin_app_id'] =$thin_app_id;
                    $post['appointment_id'] =$appointment_id;
                    $post['request_for'] ="CLIPBOARD";
                    $result = json_decode(WebservicesFunction::close_appointment($post,true),true);
                    if($result['status']==1){
                        $notification_array = $result['notification_array'];
                        Custom::close_appointment_notification($notification_array);
                    }
                }

            }


        }

        Custom::add_myscript_tag_with_patient($thin_app_id,$user_id,$mobile,$doctor_id,$drive_folder_id,$my_script_string,$token_number,$number_array);


    }

    public static function has_patient_by_number($thin_app_id,$mobile){
        $query = "select id from appointment_customers where mobile = '$mobile' AND thinapp_id = $thin_app_id and status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return true;
        }else{
            return false;
        }
    }


    public static function update_billing_created_by($medical_product_id,$user_id){
        $created = Custom::created();
        $connection = ConnectionUtil::getConnection();
        $sql = "update medical_product_orders set created_by_user_id = ?, modified=? where id = ? and (created_by_user_id IS NULL OR created_by_user_id = 0)";
        $stmt_df = $connection->prepare($sql);
        $created = Custom::created();
        $stmt_df->bind_param('sss', $user_id, $created, $medical_product_id);
        if ($stmt_df->execute()){
            return true;
        }else{
            return false;
        }
    }

    public static function get_doctor_next_available_slot($thin_app_id, $doctor_id, $address_id,$booking_date,$time_of_day,$custom_time = false,$appointment_user_role="",$service_id=0,$expire_slot=false,$add_more_slot=false,$doctor_data=false){

        if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
            if(!$doctor_data){
                $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id, true);
            }
            $service_id = !empty($service_id)?$service_id:$doctor_data['service_id'];
            $slot_list = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $doctor_data['setting_type'], $booking_date,false,$add_more_slot,$appointment_user_role,$expire_slot);
        } else {
            if(!$doctor_data){
                $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id);
            }
            $slot_list = Custom::load_doctor_slot_by_address($booking_date, $doctor_id, $doctor_data['service_slot_duration'], $thin_app_id, $address_id,false,$appointment_user_role,$add_more_slot,$expire_slot);
        }
        if (!empty($slot_list)) {
            $time_from =false;
            $time_to = false;

            if($custom_time===true){
                if (strtoupper($time_of_day) == "CURRENT") {
                    $time_from = "00:00:00";
                    $time_to = "23:59:00";
                }else{
                    $custom_time = Custom::get_doctor_ivr_custom_time($doctor_id,$time_of_day);
                    if(!empty($custom_time)){
                        $time_from = $custom_time['from_time'];
                        $time_to = $custom_time['to_time'];
                    }
                }
            }else{
                if (strtoupper($time_of_day) == "MORNING") {
                    $time_from = "00:00:00";
                    $time_to = "11:59:00";
                } else if (strtoupper($time_of_day) == "AFTERNOON") {
                    $time_from = "12:00:00";
                    $time_to = "16:59:00";
                } else if (strtoupper($time_of_day) == "EVENING") {
                    $time_from = "17:00:00";
                    $time_to = "23:59:00";
                }else if (strtoupper($time_of_day) == "CURRENT") {
                    $time_from = "00:00:00";
                    $time_to = "23:59:00";
                }
            }
            if($time_from !== false && $time_to !== false){
                $time_from = strtotime($booking_date . ' ' . $time_from);
                $time_to = strtotime($booking_date . ' ' . $time_to);
                foreach ($slot_list as $key => $slot) {
                    $slot_format = DateTime::createFromFormat('h:i A', $slot['slot']);
                    $slot_format = strtotime($booking_date . ' ' . $slot_format->format("H:i:s"));
                    if ($slot['status'] == 'AVAILABLE' && ($slot_format >= $time_from && $slot_format <= $time_to)) {
                        if($thin_app_id==900){
                            $time_from = strtotime($booking_date . ' ' . "12:57:00");
                            if($slot_format > $time_from){
                                return $slot['slot'];
                            }
                        }else{
                            return $slot['slot'];
                        }
                    }
                }
            }

        }
        return false;
    }

    public static function get_booked_token_id($thin_app_id,$doctor_id,$address_id,$service_id,$token_number,$booking_date){
        $service_condition = !empty($service_id)?" and acss.appointment_service_id = $service_id ":"";
        $query = "select id from appointment_customer_staff_services as acss where acss.thinapp_id=$thin_app_id and  acss.appointment_staff_id = $doctor_id and acss.appointment_address_id = $address_id $service_condition and DATE(acss.appointment_datetime) = '$booking_date' and acss.queue_number = '$token_number' and acss.status IN('NEW','CONFIRM','RESCHEDULE') limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return  mysqli_fetch_assoc($service_message_list)['id'];
        }else{
            return 0;
        }
    }

    public static function get_medicine_form_list()
    {
        $connection = ConnectionUtil::getConnection();
        $sql = "SELECT `name` FROM `medical_product_forms` WHERE `status` = 'ACTIVE' ORDER BY `name` DESC";
        $sqlRS = $connection->query($sql);
        $dataToSend = array();
        while($sqlData = mysqli_fetch_assoc($sqlRS))
        {
            $dataToSend[$sqlData['name']] = $sqlData['name'];
        }
        return $dataToSend;
    }

    public static function add_myscript_tag_with_file_id($thin_app_id,$user_id,$mobile,$doctor_id,$file_id){
        $connection = ConnectionUtil::getConnection();
        $connection->autocommit(true);
        $file_data = Custom::get_file_data_for_myscript($file_id);
        $my_script_key = Custom::getMyScriptActiveKey();
        if(!empty($my_script_key) && !empty($file_data) && !empty($file_data['raw_string']) && empty($file_data['myscript_instance_id']) ){
            $drive_folder_id = $file_data['folder_id'];
            $fields =array('applicationKey'=>$my_script_key,'textInput'=>$file_data['raw_string']);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, MYSCRIPT_URL);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));
            $return_array[] = json_decode(curl_exec($ch), true);
            curl_close($ch);
            if(isset($return_array[0]['result']['textSegmentResult']['candidates'][0]['label'])) {
                $myscript_string = $return_array[0]['result']['textSegmentResult']['candidates'][0]['label'];
                $myscript_instance_id = $return_array[0]['instanceId'];
                $created = Custom::created();
                $sql = "UPDATE  drive_files SET myscript_string =?, myscript_instance_id=?, modified = ? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssss', $myscript_string, $myscript_instance_id, $created, $file_id);
                if ($stmt->execute()) {
                    Custom::add_myscript_tag_with_patient($thin_app_id,$user_id,$mobile,$doctor_id,$drive_folder_id,$myscript_string);
                }

            }
        }
    }

    public static function add_myscript_tag_with_patient($thin_app_id, $user_id, $mobile, $doctor_id, $drive_folder_id, $my_script_string,$token_number='', $number_array=array()){


        $connection= ConnectionUtil::getConnection();
        $connection->autocommit(false);
        $patient_data = Custom::get_patient_by_folder_id($drive_folder_id);

        /* get all tags into brackets*/
        preg_match_all('/\((.*?)\)/', $my_script_string, $tmp);
        $tag_array = @($tmp[1]);
        $tmp_tag_list =array();
        if(!empty($tag_array)){


            $tag_id_array = array();
            $final_tag_array = array();
            foreach($tag_array  as $key => $tag){
                $tmp_tag = explode(',',$tag);
                $final_tag_array = array_merge($final_tag_array,$tmp_tag);
            }

            foreach($final_tag_array  as $key => $tag){
                $tag = trim($tag);
                $num = array(1,2,3,4,5,6,7,8,9,0);
                $string_len = strlen(str_replace($num, '', $tag));
                $number_len = strlen(preg_replace('/\D/', '', $tag));
                if(strlen($tag) > 2 && $string_len > $number_len){
                    $tmp_tag_list[] = $tag;
                    $created = Custom::created();
                    $is_tag = Custom::find_illness_tag_by_name($thin_app_id,$doctor_id,$tag);
                    if(empty($is_tag)){
                        $sql = "INSERT INTO patient_illness_tags (thinapp_id, doctor_id, tag_name, created, modified) VALUES (?,?,?,?,?)";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param('sssss', $thin_app_id, $doctor_id, $tag, $created, $created);
                        if($stmt->execute()){

                            $tag_id_array[] = $stmt->insert_id;
                        }
                    }else{
                        $tag_id_array[] = $is_tag['id'];
                    }
                }
            }

            if(!empty($tag_id_array) ){
                $save['thin_app_id'] =  $thin_app_id;
                $save['app_key'] =  APP_KEY;
                $save['user_id'] =  $user_id;
                $save['mobile'] =  $mobile;
                $save['doctor_id'] =  $doctor_id;
                $save['patient_type'] =  @$patient_data['patient_type'];
                $save['patient_id'] =  @$patient_data['patient_id'];
                $save['tag_id'] =  implode(',',$tag_id_array);
                $res = WebServicesFunction_2_3::add_illness_tag_to_patient($save);
            }


        }


        $pattern = "/((\d{1}|\d{2})-(\d{1}|\d{2})-(\d{4}|\d{2}))|(((\d{1}|\d{2})\/(\d{1}|\d{2})\/(\d{4}|\d{2})))/";
        preg_match_all($pattern, $my_script_string, $matches);
        $date_array = array();
        if(!empty($matches[0])){
            $date_array = $matches[0];
            $follow_up_date = $matches[0][count($matches[0])-1];
            $follow_up_date = str_replace(array('-','/'),'-',$follow_up_date);
            try{
                $created = Custom::created();
                $appointment_customer_id = $children_id =0;
                if($patient_data['patient_type']=='CUSTOMER'){
                    $appointment_customer_id = $patient_data['patient_id'];
                }else{
                    $children_id = $patient_data['patient_id'];
                }

                $year = (strlen(@end(explode('-',$follow_up_date)))==2)?'y':'Y';;
                $date = DateTime::createFromFormat("d-m-$year", $follow_up_date);
                $date = $date->format('Y-m-d');
                $current_date = date('Y-m-d');
                if(strtotime($date) > strtotime($current_date)){
                    $connection->autocommit(true);
                    $sql = "INSERT INTO follow_up_reminders (doctor_id,appointment_customer_id, children_id, thinapp_id, reminder_date, created) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt1 = $connection->prepare($sql);
                    $stmt1->bind_param('ssssss', $doctor_id, $appointment_customer_id, $children_id, $thin_app_id, $date,  $created);
                    $stmt1->execute();
                }
            }catch(Exception $e){

            }


        }

        /* try{

            $file = "myscript_test.txt";
            @unlink($file);
            $fh = fopen($file, 'w') or die("can't open file");
            $stringData = "My script :\n $my_script_string \n";
            fwrite($fh, $stringData);
            $stringData = "=======================================================================\nToken : $token_number \n";
            fwrite($fh, $stringData);
            if(!empty($number_array)){
                $stringData = "mobile: ".implode(',',$number_array)."\n";
                fwrite($fh, $stringData);
            } if(!empty($tmp_tag_list)){
                $stringData = "tags: ".implode(',',$tmp_tag_list)."\n";
                fwrite($fh, $stringData);
            } if(!empty($date_array)){
                $stringData = "Date: ".implode(',',$date_array)."\n";
                fwrite($fh, $stringData);
            }
            fclose($fh);

            $content = file_get_contents($file);
            $sql = "update drive_files set final_text = ? where myscript_string = ? and doctor_id =? and thinapp_id = ? order by id desc limit 1";
            $stmt_df = $connection->prepare($sql);
            $created = Custom::created();
            $stmt_df->bind_param('ssss', $content, $my_script_string, $doctor_id, $thin_app_id);
            if($stmt_df->execute()){
                $connection->commit();
            }
        }catch (Exception $e){

        }*/


    }

    public static function get_file_data_for_myscript($drive_file_id=null){

        $query = "select df.id as folder_id, dfile.raw_string, dfile.myscript_string, dfile.myscript_instance_id from drive_files as dfile join drive_folders as df on df.id = dfile.drive_folder_id where  dfile.id = $drive_file_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return_arr =array();
        if ($service_message_list->num_rows) {
            $return_arr = mysqli_fetch_assoc($service_message_list);
            return $return_arr;
        }
        return $return_arr;
    }

    public static function write_file_into_folder($folder_name,$file_name,$data){
        $dir = LOCAL_PATH . "app/webroot/" . $folder_name . "/";
        $fileUrl = "";
        if (!empty($folder_name)) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }
        if( !empty($file_name) && is_dir($dir)){
            $fileUrl = LOCAL_PATH . "app/webroot/" . $folder_name . "/" . $file_name . '.json';
        }
        if(!empty($fileUrl) && !empty($file_name) && is_dir($dir) && !empty($data)){
            $data= json_encode($data);
            file_put_contents($fileUrl, $data);
        }
    }

    public static function delete_file_from_folder($folder_name,$file_name_array)
    {
        if (!empty($file_name_array) && !empty($folder_name) ) {
            foreach ($file_name_array as $key => $file_name) {
                $file = LOCAL_PATH . "app/webroot/".$folder_name."/".$file_name.".json";
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }

    public static function get_all_firm_ids($thin_app_id) {

        $file_name = "app_$thin_app_id";
        if(!$response = json_decode(WebservicesFunction::readJson($file_name,"firm"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select thinapp_id from app_firms  where  firm_thinapp_id = $thin_app_id and status ='ACTIVE'";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $response = array_column(mysqli_fetch_all($service_message_list,MYSQL_ASSOC),'thinapp_id');
                WebservicesFunction::createJson($file_name,json_encode($response),"CREATE","firm");
            }
        }
        if (!empty($response)) {
            return $response;
        }else{
            return false;
        }
    }


    public static function get_doctor_all_address_new_appointment($thin_app_id,$doctor_id){

        $query = "select aa.id as address_id, aa.address from doctor_appointment_setting as das join appointment_addresses  as aa on aa.id = das.appointment_address_id join appointment_staffs as app_staff on app_staff.id = das.doctor_id and das.setting_type = app_staff.appointment_setting_type  where das.doctor_id = $doctor_id and das.thinapp_id = $thin_app_id  group by das.appointment_address_id ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function tab_add_new_tag($thin_app_id,$doctor_id,$tag_name,$step_id){
        $created = Custom::created();
        $tag_name = trim($tag_name);
        $query = "select id from tab_prescription_tags where tab_prescription_step_id = $step_id and thinapp_id = $thin_app_id and doctor_id= $doctor_id and tag_name = '$tag_name' and status = 'ACTIVE' ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $connection->autocommit(true);
        if (!$service_message_list->num_rows) {
            $sql = "INSERT INTO tab_prescription_tags (thinapp_id, tab_prescription_step_id, doctor_id, tag_name, created, modified) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_user = $connection->prepare($sql);
            $stmt_user->bind_param('ssssss',  $thin_app_id,$step_id,$doctor_id,$tag_name, $created, $created);
            if ($stmt_user->execute()) {

                return $stmt_user->insert_id;
            }
            return 0;
        }else{
            return 0;
        }


    }


    public static function update_app_categories($thin_app_id, $ids_array){
        if(!empty($ids_array)){
            $result =array();
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);
            $sql = "delete from hospital_categories where thinapp_id =?";
            $stmt_delete_date = $connection->prepare($sql);
            $stmt_delete_date->bind_param('s', $thin_app_id);
            $stmt_delete_date->execute();
            foreach ($ids_array as $key => $category_id){
                $created = Custom::created();
                $query = "INSERT INTO hospital_categories (thinapp_id,department_category_id,created,modified) values (?,?,?,?)";
                $stmt = $connection->prepare($query);
                $stmt->bind_param('ssss', $thin_app_id, $category_id, $created, $created);
                $result[] = $stmt->execute();
            }
            if(!in_array(false,$result)){
                $connection->commit();
                return true;
            }else{
                $connection->rollback();
                return false;
            }
        }
        return false;

    }

    public static function update_billing_payment_status($medical_product_id,$status = 'PAID'){
        $created = Custom::created();
        $connection = ConnectionUtil::getConnection();
        $sql = "update medical_product_orders set payment_status = ?, modified=? where id = ?";
        $stmt_df = $connection->prepare($sql);
        $created = Custom::created();
        $stmt_df->bind_param('sss', $status, $created, $medical_product_id);
        if ($stmt_df->execute()){
            return true;
        }else{
            return false;
        }
    }

    public static function get_tracker_template_list(){

        $query = "select * from tracker_templates where status = 'ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }else{
            return false;
        }
    }

    public static function get_all_doctor_list($thin_app_id){

        $query = "select ac.name as department_name,  app_staffs.* from appointment_staffs app_staffs left join appointment_categories as ac on ac.id = app_staffs.appointment_category_id where app_staffs.staff_type= 'DOCTOR' AND app_staffs.status = 'ACTIVE' AND app_staffs.thinapp_id =$thin_app_id order by app_staffs.name asc  ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function get_doctor_and_service_time($doctor_id,$service_id)
    {
        $query = "select app_sta.*, app_ser.service_amount, app_ser.service_slot_duration, t.show_expire_token_slot from appointment_services as app_ser join thinapps as t on t.id = app_ser.thinapp_id join appointment_staffs as app_sta on app_sta.thinapp_id = app_ser.thinapp_id and app_sta.id = $doctor_id and app_ser.id = $service_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function get_receipt_id_by_order_id($orderID){
        //$query = "SELECT CONCAT(DATE_FORMAT(i_mpo.created,'%d%m%y'),COUNT(i_mpo.id)) AS unique_id FROM medical_product_orders AS i_mpo WHERE  DATE(i_mpo.created) = '".date('Y-m-d',strtotime($created))."' AND i_mpo.id <= '".$orderID."' AND i_mpo.thinapp_id = '".$thinappID."' AND i_mpo.is_expense = 'N' LIMIT 1";
        $query = "select `MedicalProductOrder`.`is_expense`, `MedicalProductOrder`.`is_amount_received_ipd_exp`, (SELECT CONCAT(DATE_FORMAT(`MedicalProductOrder`.`created`,'%d%m%y'),COUNT(`id`)) FROM `medical_product_orders` AS `order` WHERE  DATE(`order`.`created`) = DATE(`MedicalProductOrder`.`created`) AND `order`.`id` <= `MedicalProductOrder`.`id` AND `order`.`thinapp_id` = `MedicalProductOrder`.`thinapp_id` AND ( (`order`.`is_expense` = 'N') OR (`order`.`is_expense` = 'Y' AND `order`.`is_amount_received_ipd_exp` = 'Y') ) ) AS `unique_id` from medical_product_orders as MedicalProductOrder where MedicalProductOrder.id = $orderID";

        $connection = ConnectionUtil::getConnection();
        $uniqueIDRS = $connection->query($query);
        if ($uniqueIDRS->num_rows) {
            $uniqueIDData = mysqli_fetch_assoc($uniqueIDRS);
            if(($uniqueIDData['is_amount_received_ipd_exp'] == 'N') && ($uniqueIDData['is_expense'] == 'Y'))
            {
                return '-';
            }
            else
            {
                return $uniqueIDData['unique_id'];
            }

        } else {
            return '';
        }
    }



    public static function has_category($thin_app_id,$department_ids){
        if(!empty($department_ids)){

            $department_ids = '"'.implode('","', $department_ids).'"';
            $query = "select count(id) as total from hospital_categories where thinapp_id = $thin_app_id and department_category_id IN($department_ids)";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $data = mysqli_fetch_assoc($service_message_list);
                return ($data['total'] > 0)?true:false;
            }
        }
        return false;
    }

    public static function get_patient_data_name_list($thin_app_id)
    {
        $query = "select DISTINCT ac.first_name, ac.id from appointment_customers  as ac where ac.thinapp_id = $thin_app_id order by rand() limit 10";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function get_patient_data_address_list($thin_app_id)
    {
        $query = "select DISTINCT ac.address, ac.id from appointment_customers  as ac where ac.thinapp_id = $thin_app_id and address != '' order by rand() limit 10";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        } else {
            return false;
        }
    }
    public static function get_patient_data_mobile_list($thin_app_id)
    {
        $query = "select RIGHT(ac.mobile,10) as mobile, ac.id from appointment_customers  as ac where ac.thinapp_id = $thin_app_id group by RIGHT(ac.mobile,10)  order by ac.created desc limit 30";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        } else {
            return false;
        }
    }


    public static function has_opd_receipt($appointment_id)
    {
        $query = "select id from medical_product_orders where appointment_customer_staff_service_id = $appointment_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return true;
        } else {
            return false;
        }
    }


    public static function get_doctor_filter_appointment_array($thin_app_id,$cache=true)
    {
        $response = array();
        if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
            $query = "select  das.from_time,  das.to_time, app_ser.service_slot_duration, app_staff.profile_photo as doctor_image, t.logo, app_staff.id as doctor_id, app_staff.name as doctor_name, app_ser.service_amount, app_ser.id as service_id, app_ser.name as service_name, aa.id as address_id, aa.address from doctor_appointment_setting as das join thinapps as t on t.id = das.thinapp_id join  appointment_addresses as aa on  aa.id = das.appointment_address_id and aa.status ='ACTIVE'  join appointment_services as app_ser on app_ser.id = das.appointment_service_id and app_ser.status = 'ACTIVE' join appointment_staffs as app_staff on app_staff.status = 'ACTIVE' and app_staff.staff_type = 'DOCTOR' AND app_staff.id = das.doctor_id and das.setting_type = app_staff.appointment_setting_type where das.thinapp_id = $thin_app_id";
        } else {
            $day_time_id = date('N');
            $query = "select  app_staff_add.from_time, app_staff_add.to_time, app_ser.service_slot_duration, app_staff.profile_photo as doctor_image, t.logo, app_staff.id as doctor_id, app_staff.name as doctor_name, app_ser.service_amount, app_ser.id as service_id, app_ser.name as service_name, aa.id as address_id, aa.address from appointment_staff_addresses as app_staff_add join thinapps as t on t.id = app_staff_add.thinapp_id join appointment_staff_services as ass on app_staff_add.appointment_staff_id = ass.appointment_staff_id AND ass.status = 'ACTIVE' join appointment_addresses as aa on  aa.id = app_staff_add.appointment_address_id AND aa.status ='ACTIVE'  join appointment_services as app_ser on app_ser.id = ass.appointment_service_id and app_ser.status ='ACTIVE' join appointment_staffs as app_staff on app_staff.status = 'ACTIVE' and app_staff.staff_type = 'DOCTOR' AND app_staff.id = app_staff_add.appointment_staff_id  where app_staff_add.thinapp_id = $thin_app_id";
        }
        $file_name = "appointment_setting_$thin_app_id";
        if($cache==true){

            if(!$response = json_decode(WebservicesFunction::readJson($file_name,"setting"),true)){
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                    $response = Custom::create_array($list);
                    WebservicesFunction::createJson($file_name,json_encode($response),"CREATE","setting");
                }
            }
        }else{
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                $response = Custom::create_array($list);

                WebservicesFunction::createJson($file_name,json_encode($response),"CREATE","setting");
            }
        }
        return $response;


    }

    public static function create_array($array){
        $response = array();
        if(!empty($array)){
            foreach($array as $key => $value){
                $response[$value['doctor_id']]['doctor_id'] = $value['doctor_id'] ;
                $response[$value['doctor_id']]['doctor_name'] =$value['doctor_name'] ;
                $response[$value['doctor_id']]['doctor_image'] =!empty($value['doctor_image'])?$value['doctor_image']:$value['logo'] ;
                $response[$value['doctor_id']]['service'][$value['service_id']]['service_id'] =$value['service_id'] ;
                $response[$value['doctor_id']]['service'][$value['service_id']]['service_name'] =$value['service_name'] ;
                $response[$value['doctor_id']]['service'][$value['service_id']]['service_amount'] =$value['service_amount'] ;
                $response[$value['doctor_id']]['service'][$value['service_id']]['service_slot_duration'] =$value['service_slot_duration'] ;

                $response[$value['doctor_id']]['service'][$value['service_id']]['address'][$value['address_id']] =array(
                    'from_time'=>$value['from_time'],
                    'to_time'=>$value['to_time'],
                    'address_id'=>$value['address_id'],
                    'address'=>$value['address']
                );
            }
        }

        return $response;
    }

    public static function get_sub_token_number($doctor_id, $service_id, $address_id, $slot_time, $date){
        $query = "select count(acss.id) as total from appointment_customer_staff_services as acss where acss.appointment_staff_id = $doctor_id and DATE(acss.appointment_datetime) = '$date' and acss.appointment_address_id = $address_id and acss.appointment_service_id = $service_id and acss.sub_token = 'YES' and acss.slot_time = '$slot_time'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return  mysqli_fetch_assoc($service_message_list)['total'];
        } else {
            return 0;
        }
    }


    public static function getRemoteFileSize($file_url, $return ="MB")
    {
        $head = array_change_key_case(get_headers($file_url, 1));
        $bites = isset($head['content-length']) ? $head['content-length'] : 0;
        if (!$bites) {
            return 0;
        }

        if($return=="KB"){
            return round($bites / 1024, 2);
        }else if($return=="MB"){
            return round($bites / 1048576, 2);
        }else if($return=="GB"){
            return round($bites / 1073741824, 2);
        }else if($return=="TB"){
            return round($bites / (1073741824 * 1024), 2);
        }else{
            /* DEFAULT IS BITE */
            return $bites;
        }

    }

    public static function getFileName($file_url){
        if(!empty($file_url)){
            $tmp = explode("/",$file_url);
            $tmp = end($tmp);
            if(!empty($tmp)){
                $tmp_array = explode(".", $tmp);
                return (count($tmp_array) >= 2)?$tmp:'';
            }
        }
        return "";

    }

    public static function create_age_array($age_string,$return_age_string = false){
        $tmp = explode(" ", $age_string);
        $return = array('Year'=>'','Month'=>'','Day'=>'');
        if(!empty($tmp)){
            foreach($tmp as $key => $word){

                if(stripos(strtoupper($word),'YEAR') !==false || strtoupper(preg_replace('/[0-9]+/', '', $word))  == 'Y' ){
                    preg_match('!\d+!', $word, $matches);
                    $return['Year'] = @$matches[0];
                }else if(stripos(strtoupper($word),'MONTH') !==false || strtoupper(preg_replace('/[0-9]+/', '', $word))  == 'M' ){
                    preg_match('!\d+!', $word, $matches);
                    $return['Month'] = @$matches[0];
                }else if( stripos(strtoupper($word),'DAY') !==false || strtoupper(preg_replace('/[0-9]+/', '', $word))  == 'D'){
                    preg_match('!\d+!', $word, $matches);
                    $return['Day'] = @$matches[0];
                }
            }
        }
        if($return_age_string===true){
            $tmp=array();
            if($return['Year'] > 0) {
                $tmp[] = $return['Year'] . 'Years';
            }if($return['Month'] > 0) {
                $tmp[] = $return['Month'] . 'Months';
            }if($return['Day'] > 0){
                $tmp[] = $return['Day'] . 'Days';
            }
            return !empty($tmp)?implode("-",$tmp):"";
        }else{
            return $return;
        }


    }

    public static function get_age_from_dob($dob,$return_array = false,$short_unit=false)
    {
        if(!empty($dob) && $dob != '0000-00-00' && $dob != '1970-01-01' ){
            if(is_numeric(str_replace("-","",$dob)) && strlen($dob) == 10) {
                $child_years = Custom::dob_elapsed_string($dob, false, false);
                if ($child_years) {
                    if ($return_array === true) {
                        return $child_years;
                    } else {
                        $tmp=array();
                        if($child_years['year'] > 0) {
                            $label = ($short_unit)?'Y':'Year';
                            $tmp[] = $child_years['year'] . $label;
                        }if($child_years['month'] > 0) {
                            $label = ($short_unit)?'M':'Month';
                            $tmp[] = $child_years['month'] . $label;
                        }if($child_years['day'] > 0){
                            $label = ($short_unit)?'D':'Day';
                            $tmp[] = $child_years['day'] . $label;
                        }
                        return !empty($tmp)?implode(" ",$tmp):"";
                    }
                }
            }else{
                return Custom::create_age_array($dob,true);
            }
        }
        return false;
    }

    public static function get_face_app_data($mobile){

        $mobile = Custom::create_mobile_number($mobile);
        if(!empty($mobile)){
            $connection = ConnectionUtil::getConnection();
            $query = "select t.id as thinapp_id, u.mobile, u.id as user_id, IFNULL(app_staff.id,0) as doctor_id from users as u join thinapps as t on t.id = u.thinapp_id left join appointment_staffs as app_staff on app_staff.user_id = u.id and app_staff.status ='ACTIVE' and app_staff.staff_type ='DOCTOR'  where role_id = 5 and u.mobile ='$mobile' limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                return  mysqli_fetch_assoc($service_message_list);
            }
        }
        return false;

    }


    public static function get_folder_list_for_web($login_role,$thin_app_id,$user_id){
        $connection = ConnectionUtil::getConnection();
        $condition = " df.thinapp_id = $thin_app_id";
        if($login_role =="ADMIN"){
            $condition .=" and df.user_id = $user_id";
        }else{
            $condition .= " and ( df.user_id = $user_id OR ( df.children_id > 0 or df.appointment_customer_id > 0))  ";
        }
        $query = "select df.id, IFNULL(IFNULL(ac.first_name,c.child_name),df.folder_name) as folder_name , IFNULL(IFNULL(ac.mobile,c.mobile),df.mobile) as mobile from drive_folders as df left join appointment_customers as ac on ac.id = df.appointment_customer_id left join childrens as c on c.id = df.children_id where $condition order by df.modified desc";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }
        return false;

    }

    public static function get_share_data($share_id)
    {
        $query = "select ds.*, IFNULL(df.folder_name, f.file_name) as name from drive_shares as ds left join drive_folders as df on df.id = ds.drive_folder_id left join drive_files as f on f.id = ds.drive_file_id where ds.id = $share_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

    public static function get_billing_table_column($thin_app_id){
        if(!$response = WebservicesFunction::readJson("billing_report_$thin_app_id","billing_report")){
            $connection = ConnectionUtil::getConnection();
            $query = "select billing_report_table_column from thinapps  where  id = $thin_app_id limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $service_message_list = mysqli_fetch_assoc($service_message_list);
                if(!empty($service_message_list['billing_report_table_column'])){
                    $response = json_encode($service_message_list['billing_report_table_column']);
                    WebservicesFunction::createJson("billing_report_$thin_app_id",($response),"CREATE","billing_report");
                }
            }
        }

        if(!empty($response)){
            $tmp_response = json_decode(json_decode($response,true),true);
            if(!isset($tmp_response['#'])){
                $index=0;
                $temp_list =array(
                    'column_0'=>"#",
                    'column_1'=>"UHID",
                    'column_2'=>"Patient Name",
                    'column_3'=>"Patient Mobile",
                    'column_4'=>"Patient Address",
                    'column_5'=>"Patient Email",
                    'column_6'=>"Receipt No",
                    'column_7'=>"date",
                    'column_8'=>"doctor",
                    'column_9'=>"Conceive Date",
                    'column_10'=>"Expected Date",
                    'column_11'=>"Payment Via",
                    'column_12'=>"Description",
                    'column_13'=>"Details",
                    'column_14'=>"Receipt Type",
                    'column_15'=>"Reason",
                    'column_16'=>"Remark",
                    'column_17'=>"Payment Status",
                    'column_18'=>"Total Amount",
                    'column_19'=>"Total Tax",
                    'column_20'=>"Biller",
                    'column_21'=>"Option"
                );
                $final_array =array();
                foreach ($tmp_response as $column => $visibility){
                    $final_array[$temp_list[$column]] = array("index"=>$index,'column_name'=>$temp_list[$column],'visibility'=>$visibility);
                    $index++;
                }
                $response = json_encode(json_encode($final_array));
            }
        }

        if (!empty($response)) {
            return $response;
        }else{
            return false;
        }

    }


    public static function createAccessKey($thin_app_id, $mobile,$mode = 'TEST'){
        if($mode=="TEST" || $mode =="PROD" ){
            return Custom::encrypt_decrypt('encrypt', $mode."XXX".$thin_app_id."XXX".$mobile);
        }return false;
    }

    public static function readAccessKey($string,$mode = 'TEST'){
        if($mode=="TEST" || $mode =="PROD" ){
            return Custom::encrypt_decrypt('decrypt', $string);
        }return false;
    }

    public static function addAccessKeyToApp($thin_app_id){
        $connection = ConnectionUtil::getConnection();
        $query = "select t.id, u.mobile from thinapps as t join users as u on u.thinapp_id = t.id and u.role_id = 5   where  t.id = $thin_app_id limit 1";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $service_message_list = mysqli_fetch_assoc($service_message_list);
            $key = Custom::createAccessKey($thin_app_id,$service_message_list['mobile'],MENGAGE_API_ACCESS_MODE);
            if(!empty($key)){
                $query = "update thinapps set mengage_api_accss_key =? where id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param('ss', $key, $thin_app_id);
                return $stmt->execute();
            }
        }
    }


    public static function get_appointment_custom_data($appointment_id)
    {
        $connection = ConnectionUtil::getConnection();
        $query = "SELECT t.show_opd_row, staff.show_extend_time_on_tracker, acss.emergency_appointment, IF(acss.emergency_appointment='YES',staff.emergency_appointment_fee,app_ser.service_amount) as service_amount, staff.emergency_appointment_fee, acss.emergency_appointment, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL,'CUSTOMER','CHILDREN') AS patient_type, acss.thinapp_id, acss.appointment_staff_id, acss.appointment_service_id, acss.appointment_address_id, acss.appointment_datetime, app_ser.service_slot_duration AS minutes from appointment_customer_staff_services AS acss JOIN appointment_services AS app_ser ON app_ser.id = acss.appointment_service_id JOIN appointment_staffs as staff on staff.id = acss.appointment_staff_id join thinapps as t on t.id = acss.thinapp_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id= acss.children_id WHERE acss.id = $appointment_id limit 1";
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data);
        }
        return false;
    }

    public static function get_total_difference_minutes($service_id, $address_id, $doctor_id, $appointment_datetime, $sum_closed_minutes = true)
    {

        $file_name = "tracker_difference_minutes__$doctor_id"."_".$address_id."_".date('Y-m-d',strtotime($appointment_datetime));
        if(!$total_minutes = json_decode(WebservicesFunction::readJson($file_name,"tracker"),true)){
            $condition = "";
            if($sum_closed_minutes === false){
                $condition = " and ttd.selected_type != 'NONE'";
            }

            $date = date('Y-m-d',strtotime($appointment_datetime));
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT sum(ttd.minutes) as total_minute FROM tracker_time_differences ttd join appointment_customer_staff_services as acss on acss.id = ttd.appointment_id and acss.appointment_datetime  WHERE  ttd.address_id = $address_id AND ttd.doctor_id = $doctor_id AND ttd.appointemnt_date = '$date' $condition";
            $data = $connection->query($query);
            if ($data->num_rows) {
                $sum = mysqli_fetch_assoc($data)['total_minute'];
                $total_minutes =  !empty($sum)?$sum:0;
            }else{
                $total_minutes = 0;
            }
            WebservicesFunction::createJson($file_name,json_encode($total_minutes),"CREATE","tracker");
        }
        return $total_minutes;


    }

    public static function update_tracker_time_difference($appointment_id){
        $appointment_data = Custom::get_appointment_custom_data($appointment_id);
        if(!empty($appointment_data) && @$appointment_data['show_extend_time_on_tracker']=='YES'){
            $thin_app_id = $appointment_data['thinapp_id'];
            $doctor_id = $appointment_data['appointment_staff_id'];
            $service_id = $appointment_data['appointment_service_id'];
            $address_id = $appointment_data['appointment_address_id'];
            $appointment_date = date('Y-m-d',strtotime($appointment_data['appointment_datetime']));
            $service_minutes = (int)$appointment_data['minutes'];
            $total_update_minutes = Custom::get_total_difference_minutes($service_id, $address_id, $doctor_id, $appointment_data['appointment_datetime'], true);
            $total_update_minutes += $service_minutes;
            $minutes_string = ($total_update_minutes >= 0)?"+$total_update_minutes minutes":"$total_update_minutes minutes";
            $time = strtotime($minutes_string, strtotime($appointment_data['appointment_datetime']));
            $new_datetime = date('H:i',$time);
            $close_datetime = date('H:i');
            $minutes = (strtotime($close_datetime) - strtotime($new_datetime))/60;
            $created= Custom::created();
            $appointment_date = date('Y-m-d',strtotime($appointment_data['appointment_datetime']));
            $assigned_service_id =0;
            $selected_type ='NONE';
            $connection = ConnectionUtil::getConnection();
            $sql = "INSERT INTO tracker_time_differences (selected_type, thinapp_id, doctor_id, address_id, service_id, assigned_service_id, appointment_id, minutes, appointemnt_date,  created, modified  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('sssssssssss', $selected_type, $thin_app_id, $doctor_id, $address_id, $service_id, $assigned_service_id, $appointment_id, $minutes, $appointment_date, $created, $created);
            $stmt->execute();
            Custom::delete_tracker_time_difference_cache($appointment_data['appointment_datetime'],$doctor_id,$address_id);
        }

    }

    public static function delete_tracker_time_difference($appointment_id){
        if(!empty($appointment_id)){
            $connection = ConnectionUtil::getConnection();
            $sql = "delete from  tracker_time_differences where appointment_id = ?";
            $delete_stmt = $connection->prepare($sql);
            $delete_stmt->bind_param('s', $appointment_id);
            $delete_stmt->execute();
        }

    }

    public static function get_service_by_id($service_id)
    {
        $query = "select * from appointment_services where id = $service_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }


    public static function get_active_doctor_list_prescription($thin_app_id){

        $query = "SELECT app_sta.id, app_sta.name, app_sta.profile_photo, app_sta.mobile, app_sta.barcode_on_prescription FROM appointment_staffs AS app_sta WHERE app_sta.thinapp_id = $thin_app_id AND app_sta.`status`  ='ACTIVE' AND app_sta.staff_type ='DOCTOR' ORDER BY app_sta.NAME asc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function get_doctor_collection($doctor_id, $from_date, $to_date, $address_id = 0, $created_by_user_id = 0, $custom_condition = ""){
    
    	

        $condition = " mpo.status = 'ACTIVE' and mpo.payment_status = 'PAID'";
        if(!empty($doctor_id)){
            $condition .= " and mpo.appointment_staff_id = $doctor_id ";
        }if(!empty($from_date) && !empty($to_date)){
            $condition .= " AND DATE(acss.appointment_datetime) BETWEEN '$from_date' AND '$to_date' ";
        }if(!empty($address_id)){
            $condition .= " AND acss.appointment_address_id = $address_id ";
        }if(!empty($created_by_user_id)){
            $condition .= " AND mpo.created_by_user_id = $created_by_user_id ";
        }if(!empty($custom_condition)){
            $condition .= $custom_condition;
        }

        $query = "SELECT final.id, final.payment_type, sum(final.total) as total FROM ((SELECT  IFNULL(hpt.id,0) as id, IFNULL(hpt.NAME,'Cash') AS payment_type, SUM(mpo.total_amount+mpo.refund_amount) AS total  FROM medical_product_orders AS mpo JOIN appointment_customer_staff_services AS acss ON acss.id = mpo.appointment_customer_staff_service_id LEFT JOIN hospital_payment_types AS hpt ON hpt.id = mpo.hospital_payment_type_id  WHERE $condition GROUP BY id) UNION ALL (SELECT  IFNULL(hpt.id,0) as id, IFNULL(hpt.NAME,'Cash') AS payment_type, -SUM(mpo.refund_amount) AS total  FROM medical_product_orders AS mpo JOIN appointment_customer_staff_services AS acss ON acss.id = mpo.appointment_customer_staff_service_id LEFT JOIN hospital_payment_types AS hpt ON hpt.id = mpo.refund_payment_type_id  WHERE $condition GROUP BY id )) AS final group by id";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQLI_ASSOC);
        }else{
            return false;
        }

    }



    public static function get_total_walk_in_appointment($doctor_id, $address_id, $date,$service_id,$slot_duration,$thin_app_id,$appointment_user_role,$slot_time){

        $default_value = 0;
        if(!empty($slot_time) && Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC') && Custom::check_app_enable_permission($thin_app_id, 'QUICK_APPOINTMENT')){
            $break_start = Custom::get_doctor_break_start($thin_app_id,$doctor_id,$date,$slot_time);
            if(!empty($slot_time) && !empty($break_start)){
                $last_slot_time_before_break = date("h:i A",strtotime('-'.$slot_duration,strtotime($break_start)));
                if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                    $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id, true);
                    $slot_list = Custom::new_get_appointment_slot($thin_app_id, $doctor_id, $service_id, $address_id, $doctor_data['setting_type'], $date,true,false,$appointment_user_role,true);
                } else {
                    $doctor_data = Custom::ive_get_doctor_custom_data($thin_app_id, $doctor_id);
                    $slot_list = Custom::load_doctor_slot_by_address($date, $doctor_id, $slot_duration, $thin_app_id, $address_id,true,$appointment_user_role,false,true);
                }
                $last_slot_data = $slot_list[$last_slot_time_before_break];
                if(isset($last_slot_data['queue_number']) && !empty(isset($last_slot_data['queue_number']))){
                    $default_value = (int)$last_slot_data['queue_number'];
                }else if(isset($last_slot_data['token']) && !empty(isset($last_slot_data['token']))){
                    $default_value = (int)$last_slot_data['token'];
                }
            }
        }
        $query = "SELECT COUNT(id) as total FROM appointment_customer_staff_services AS acss WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_address_id = $address_id AND DATE(acss.appointment_datetime) = '$date' AND acss.delete_status != 'DELETED' AND acss.has_token = 'NO'";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $total =mysqli_fetch_assoc($data)['total'];
            if($default_value > 0){
                return $default_value.'.'.$total;
            }
            return $total;
        }else{
            return false;
        }
    }

   public static function get_total_refund_by_payment_type($user_id,$from_date,$to_date,$address_id= 0,$order_ids=''){

        if(!empty($user_id) && !empty($from_date) && !empty($to_date)){

            $condition = !empty($address_id)?' and mpo.appointment_address_id = '.$address_id:"";

            $date_condition = " DATE(mpo.refund_date_time) BETWEEN '$from_date' and '$to_date' ";
            if(!empty($order_ids)){
                $order_ids =  '"'.implode('","', explode(',',$order_ids)).'"';
                $date_condition = " ( (DATE(mpo.refund_date_time) BETWEEN '$from_date' and '$to_date'  ) OR (mpo.id IN($order_ids))) ";
            }
            $query = "SELECT IFNULL(hpt.id,0) as id, IFNULL(hpt.name,'Cash') AS payment_name, SUM(mpo.refund_amount) AS refund_amount FROM medical_product_orders AS mpo  LEFT JOIN hospital_payment_types AS hpt ON mpo.refund_payment_type_id = hpt.id WHERE mpo.is_refunded='YES' and $date_condition and mpo.status ='ACTIVE' and mpo.refund_by_user_id =$user_id $condition GROUP BY mpo.refund_payment_type_id";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            $final_array =array();
            if ($data->num_rows) {
                $list =  mysqli_fetch_all($data,MYSQLI_ASSOC);
                foreach($list as $key => $value){
                    $final_array[$value['id']] = $value;
                }
                return $final_array;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }


    public static function update_patient_due_amount($display_amount, $thin_app_id,$medical_product_order_id,$user_id,$paid_amount,$patient_id,$patient_type){


        $condition_label="";
        if($patient_type=="CUSTOMER" || $patient_type ="CHILDREN"){
            if($patient_type=="CHILDREN"){
                $condition_label = "children_id";
            }else{
                $condition_label = "appointment_customer_id";
            }
            $patient_data= Custom::get_patient_detail($patient_id,$patient_type);
            $created =Custom::created();


            if(!empty($patient_data)){

                $connection = ConnectionUtil::getConnection();
                $connection->autocommit(false);
                $payment_status= "PAID";
                $result = array();
                $amount = sprintf('%0.2f',($display_amount - $paid_amount) );
                $last_due_amount_data = Custom::get_patient_amount_detail_by_order_id($medical_product_order_id);
                if($amount > 0){
                    if(!empty($last_due_amount_data)){

                        $last_due_amount = $amount;
                        $settlement_by_order_id = 0;
                        $payment_status = "DUE";
                        $paid_via_patient = "NO";
                        $query = "update patient_due_amounts set settlement_by_order_id =?, amount =?, paid_via_patient =?,  payment_status =?, modified_by_user_id =?, modified =? where id =?";
                        $update_stmt = $connection->prepare($query);
                        $update_stmt->bind_param('sssssss', $settlement_by_order_id, $last_due_amount, $paid_via_patient,  $payment_status, $user_id, $created, $last_due_amount_data['id']);
                        $result[] =$update_stmt->execute();

                    }else{

                        $payment_status = "PAID";
                        $paid_via_patient = "NO";

                        $query = "update patient_due_amounts set  paid_via_patient =?, settlement_by_order_id=?, payment_status =?, modified_by_user_id =?, modified =? where ( $condition_label =? and payment_status ='DUE' ) OR ( settlement_by_order_id = ?) ";
                        $update_stmt = $connection->prepare($query);
                        $update_stmt->bind_param('sssssss',  $paid_via_patient, $medical_product_order_id, $payment_status, $user_id, $created, $patient_id,$medical_product_order_id);
                        $result[] =$update_stmt->execute();


                        $sql = "INSERT INTO patient_due_amounts (thinapp_id, $condition_label, medical_product_order_id, created_by_user_id, amount, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $insert_stmt = $connection->prepare($sql);
                        $insert_stmt->bind_param('sssssss',  $thin_app_id, $patient_id, $medical_product_order_id, $user_id, $amount,  $created, $created);
                        $result[] =$insert_stmt->execute();
                    }
                }else if($amount == 0){
                    $payment_status = "PAID";
                    $paid_via_patient = "YES";
                    $query = "update patient_due_amounts set  paid_via_patient =?, settlement_by_order_id=?, payment_status =?, modified_by_user_id =?, modified =? where $condition_label =? and payment_status ='DUE'";
                    $update_stmt = $connection->prepare($query);
                    $update_stmt->bind_param('ssssss', $paid_via_patient, $medical_product_order_id, $payment_status, $user_id, $created, $patient_id);
                    $result[] =$update_stmt->execute();


                }



                if (!in_array(false,$result)) {
                    $connection->commit();
                    return  true;
                }else{
                    $connection->rollback();
                    return false;
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function get_patient_due_amount($patient_id,$patient_type, $medical_product_order_id=0){
        $condition_label= $condition = "";
        if($patient_type=="CUSTOMER" || $patient_type =="CHILDREN"){
            if($patient_type=="CHILDREN"){
                $condition_label = "AND children_id";
            }else{
                $condition_label = " AND appointment_customer_id ";
            }
            if(!empty($medical_product_order_id)){
                $condition .= " AND medical_product_order_id = $medical_product_order_id ";
            }

            $condition .= " AND status = 'ACTIVE' and payment_status ='DUE' ";
            $query = "SELECT IFNULL(SUM(amount),0) AS total_due FROM patient_due_amounts where payment_status = 'DUE' and status = 'ACTIVE' $condition_label = $patient_id   $condition";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                return mysqli_fetch_assoc($data)['total_due'];
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function get_patient_amount_by_settle_id($medical_product_order_id){
        $condition = " settlement_by_order_id =  $medical_product_order_id ";
        $query = "SELECT * FROM patient_due_amounts WHERE  ( settlement_by_order_id =  $medical_product_order_id ) order by id desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data);
        }else{
            return false;
        }
    }



    public static function get_patient_amount_detail_by_order_id($medical_product_order_id){
        $condition = " medical_product_order_id =  $medical_product_order_id ";
        $query = "SELECT * FROM patient_due_amounts where $condition order by id desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data);
        }else{
            return false;
        }
    }


    public static function get_refferer_name_list($thin_app_id){
        $list =array();
        $query = "SELECT DISTINCT UPPER(mpo.reffered_by_name) AS refferer_name, mpo.reffered_by_mobile FROM medical_product_orders AS mpo WHERE mpo.thinapp_id = $thin_app_id AND mpo.`status` ='ACTIVE' AND mpo.reffered_by_name!='' ORDER BY refferer_name asc";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $final_array =array();
            $tmp =  mysqli_fetch_all($data,MYSQL_ASSOC);
            foreach($tmp as $key => $value){
                if(!array_key_exists($value['refferer_name'],$list)){
                    $list[$value['refferer_name']] = $value['reffered_by_mobile'];
                }else if(array_key_exists($value['refferer_name'],$list) && !empty($value['reffered_by_mobile'])){
                    $list[$value['refferer_name']] = $value['reffered_by_mobile'];
                }
                $mobile = !empty($list[$value['refferer_name']])?"-".$list[$value['refferer_name']]:"";
                $final_array[$value['refferer_name']] = $value['refferer_name'].$mobile;
            }

            return array_values($final_array);
        }
        return $list;

    }

    public static function get_refferer_name_list_appointment($thin_app_id){
        $list =array();
        $query = "SELECT DISTINCT UPPER(acss.referred_by) AS refferer_name, acss.referred_by_mobile AS reffered_by_mobile FROM appointment_customer_staff_services AS acss WHERE acss.thinapp_id = $thin_app_id AND acss.referred_by!='' ORDER BY acss.referred_by asc";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $final_array =array();
            $tmp =  mysqli_fetch_all($data,MYSQL_ASSOC);
            foreach($tmp as $key => $value){
                if(!array_key_exists($value['refferer_name'],$list)){
                    $list[$value['refferer_name']] = $value['reffered_by_mobile'];
                }else if(array_key_exists($value['refferer_name'],$list) && !empty($value['reffered_by_mobile'])){
                    $list[$value['refferer_name']] = $value['reffered_by_mobile'];
                }
                $mobile = !empty($list[$value['refferer_name']])?"-".$list[$value['refferer_name']]:"";
                $final_array[$value['refferer_name']] = $value['refferer_name'].$mobile;
            }

            return array_values($final_array);
        }
        return $list;

    }

    public static function get_doctor_appointment_setting_array($appointment_id,$request_param)
    {

        $response =array();
        $appointment_data = Custom::get_appointment_by_id($appointment_id);
        if(!empty($appointment_data)){
            $thin_app_id =$appointment_data['thinapp_id'];
            $doctor_id =$appointment_data['appointment_staff_id'];
            $service_id =$appointment_data['appointment_service_id'];
            $address_id = $appointment_data['appointment_address_id'];
            $appointment_date = $appointment_data['appointment_datetime'];
            $response = array();
            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                $query = "SELECT  app_staff.appointment_setting_type, das.appointment_day_time_id, das.appointment_date, app_staff.profile_photo as doctor_image, t.logo, app_staff.id as doctor_id, app_staff.name as doctor_name, app_ser.service_amount, app_ser.id as service_id, app_ser.name as service_name, aa.id as address_id, aa.address from doctor_appointment_setting as das join thinapps as t on t.id = das.thinapp_id join  appointment_addresses as aa on  aa.id = das.appointment_address_id and aa.status ='ACTIVE'  join appointment_services as app_ser on app_ser.id = das.appointment_service_id and app_ser.status = 'ACTIVE' join appointment_staffs as app_staff on app_staff.status = 'ACTIVE' AND app_staff.id = das.doctor_id and das.setting_type = app_staff.appointment_setting_type where (das.appointment_date='0000-00-00' OR (das.appointment_date >= DATE(NOW()))) AND das.thinapp_id = $thin_app_id";
            } else {
                $query = "SELECT app_staff.profile_photo as doctor_image, t.logo, app_staff.id as doctor_id, app_staff.name as doctor_name, app_ser.service_amount, app_ser.id as service_id, app_ser.name as service_name, aa.id as address_id, aa.address from appointment_staff_addresses as app_staff_add join thinapps as t on t.id = app_staff_add.thinapp_id  join appointment_staff_services as ass on app_staff_add.appointment_staff_id = ass.appointment_staff_id AND ass.status = 'ACTIVE' join appointment_addresses as aa on  aa.id = app_staff_add.appointment_address_id AND aa.status ='ACTIVE'  join appointment_services as app_ser on app_ser.id = ass.appointment_service_id and app_ser.status ='ACTIVE' join appointment_staffs as app_staff on app_staff.status = 'ACTIVE' AND app_staff.id = app_staff_add.appointment_staff_id where app_staff_add.thinapp_id = $thin_app_id";
            }
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                $response = Custom::create_setting_array($list, $thin_app_id,$doctor_id, $service_id, $address_id, $appointment_date,$request_param);
            }
        }

        return $response;


    }
    public static function create_setting_array($array,$thin_app_id,$doctor_id, $service_id, $address_id, $appointment_date,$request_param){
        $response = array();
        $tmp_array =array();
        $selected_doctor =array();
        if(!empty($array)){
            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {


                foreach($array as $key => $value){

                    $blocked_dates = Custom::get_blocked_date_new_appointment($request_param['doctor_id'], $request_param['service_id'], $request_param['address_id'],$appointment_date);

                    $doctor_selected = ($value['doctor_id']==$doctor_id)?'YES':'NO';
                    $service_selected = ($doctor_selected=="YES" && $value['service_id']==$service_id)?'YES':'NO';
                    $address_selected = ($doctor_selected=="YES" && $value['address_id']==$address_id)?'YES':'NO';

                    if(($value['appointment_setting_type']=='DAY')){
                        $begin = new DateTime(date('Y-m-d'));
                        $end = new DateTime(date('Y-m-d', strtotime("+15 Days")));
                        $daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
                        foreach ($daterange as $key => $date) {
                            $new_date = $date->format("d-D-M-Y");
                            $label = implode("##", explode("-", $new_date));
                            $appointment_day_time_id =  $value['appointment_day_time_id']-1;
                            $weekday = date('N', strtotime($appointment_date));
                            $label_selected = ($appointment_day_time_id == $weekday)?'YES':'NO';

                            $is_date_blocked =in_array($date->format("Y-m-d"),$blocked_dates)?"YES":"NO";


                            $response[$value['doctor_id']]['doctor_id'] = $value['doctor_id'] ;
                            $response[$value['doctor_id']]['doctor_name'] =$value['doctor_name'] ;
                            $response[$value['doctor_id']]['is_selected'] =$doctor_selected;
                            $response[$value['doctor_id']]['doctor_image'] =!empty($value['doctor_image'])?$value['doctor_image']:$value['logo'] ;
                            $response[$value['doctor_id']]['date'][$label]['date'] = $label;
                            if($response[$value['doctor_id']]['is_selected'] == "NO" && array_search($label, array_keys($response[$value['doctor_id']]['date'])) == 0){
                                $response[$value['doctor_id']]['date'][$label]['is_selected'] = "YES";
                            }else{
                                if($response[$value['doctor_id']]['is_selected'] == "YES" && $date->format("Y-m-d") == date('Y-m-d', strtotime($appointment_date))){
                                    $response[$value['doctor_id']]['date'][$label]['is_selected'] = 'YES';
                                }else{
                                    $response[$value['doctor_id']]['date'][$label]['is_selected'] = $label_selected;
                                }
                            }

                            $response[$value['doctor_id']]['date'][$label]['is_date_blocked'] = $is_date_blocked;
                            $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['service_id'] =$value['service_id'] ;
                            $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['is_selected'] =$service_selected;

                            $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['service_name'] =$value['service_name'] ;
                            $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['service_amount'] =$value['service_amount'] ;
                            $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['address'][$value['address_id']] =array(
                                'address_id'=>$value['address_id'],
                                'is_selected'=>$address_selected,
                                'address'=>$value['address']
                            );
                        }

                    }else{

                        $label = date("d-D-M-Y",strtotime($value['appointment_date']));

                        $label = implode("##", explode("-", $label));
                        $label_selected = (date('Y-m-d',strtotime($value['appointment_date'])) == date('Y-m-d',strtotime($appointment_date)))?'YES':'NO';
                        $current_date = date("d-D-M-Y",strtotime($appointment_date));



                        $is_date_blocked =in_array(date('Y-m-d',strtotime($value['appointment_date'])),$blocked_dates)?"YES":"NO";

                        $response[$value['doctor_id']]['doctor_id'] = $value['doctor_id'] ;
                        $response[$value['doctor_id']]['doctor_name'] =$value['doctor_name'] ;
                        $response[$value['doctor_id']]['is_selected'] =$doctor_selected;
                        $response[$value['doctor_id']]['doctor_image'] =!empty($value['doctor_image'])?$value['doctor_image']:$value['logo'] ;
                        $response[$value['doctor_id']]['date'][$label]['date'] = $label;
                        if($response[$value['doctor_id']]['is_selected'] == "NO" && array_search($label, array_keys($response[$value['doctor_id']]['date'])) == 0){
                            $response[$value['doctor_id']]['date'][$label]['is_selected'] = "YES";
                        }else{
                            $response[$value['doctor_id']]['date'][$label]['is_selected'] = $label_selected;
                        }


                        $response[$value['doctor_id']]['date'][$label]['is_date_blocked'] = $is_date_blocked;
                        $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['service_id'] =$value['service_id'] ;
                        $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['is_selected'] =$service_selected;

                        $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['service_name'] =$value['service_name'] ;
                        $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['service_amount'] =$value['service_amount'] ;
                        $response[$value['doctor_id']]['date'][$label]['service'][$value['service_id']]['address'][$value['address_id']] =array(
                            'address_id'=>$value['address_id'],
                            'is_selected'=>$address_selected,
                            'address'=>$value['address']
                        );


                    }


                }
            } else {


                foreach($array as $key => $value){

                    $doctor_selected = ($value['doctor_id']==$doctor_id)?'YES':'NO';
                    $service_selected = ($doctor_selected=="YES" && $value['service_id']==$service_id)?'YES':'NO';
                    $address_selected = ($doctor_selected=="YES" &&  $value['address_id']==$address_id)?'YES':'NO';
                    $response[$value['doctor_id']]['doctor_id'] = $value['doctor_id'] ;
                    $response[$value['doctor_id']]['doctor_name'] =$value['doctor_name'] ;
                    $response[$value['doctor_id']]['is_selected'] =$doctor_selected ;
                    $response[$value['doctor_id']]['doctor_image'] =!empty($value['doctor_image'])?$value['doctor_image']:$value['logo'] ;
                    $response[$value['doctor_id']]['service'][$value['service_id']]['service_id'] =$value['service_id'] ;
                    $response[$value['doctor_id']]['service'][$value['service_id']]['service_name'] =$value['service_name'] ;
                    $response[$value['doctor_id']]['service'][$value['service_id']]['is_selected'] =$service_selected;
                    $response[$value['doctor_id']]['service'][$value['service_id']]['service_amount'] =$value['service_amount'] ;
                    $response[$value['doctor_id']]['service'][$value['service_id']]['address'][$value['address_id']] =array(
                        'address_id'=>$value['address_id'],
                        'is_selected'=>$address_selected,
                        'address'=>$value['address']
                    );

                }
            }


        }

        return $response;
    }


    public static function get_blocked_date_new_appointment($doctor_id, $service_id, $address_id,$appointment_date)
    {
        $response =array();
        $query = "SELECT bs.book_date FROM appointment_bloked_slots AS bs WHERE bs.doctor_id = $doctor_id AND bs.is_date_blocked ='YES'  AND bs.new_appointment ='YES' AND bs.address_id = $address_id AND bs.service_id=$service_id and bs.book_date >= '$appointment_date'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $response=  array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'book_date');
        }
        return $response;


    }


    public static function get_dashboard_icon(){

        $icon_list=array();

        $query = "select id,title,url FROM dashboard_icons where id NOT IN(3,11) order by title asc ";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $icon_list =  mysqli_fetch_all($data,MYSQLI_ASSOC);

        }
        return $icon_list;

    }

    public static function get_app_cms_list($thin_app_id){

        $list_array=array();
        $file_name = "app_cms_$thin_app_id";

        if(!$list_array = json_decode(WebservicesFunction::readJson($file_name,"cms"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT id,title, dashboard_icon_url, request_load_type, url  FROM cms_pages WHERE thinapp_id = $thin_app_id AND STATUS = 'ACTIVE'";
            $list = $connection->query($query);
            if ($list->num_rows) {
                $list = mysqli_fetch_all($list,MYSQL_ASSOC);
                foreach($list as $key =>$value){
                    $list_array[$key]['icon_name'] = $value['title'];
                    $list_array[$key]['icon_path'] = $value['dashboard_icon_url'];
                    if($value['request_load_type']=="URL"){
                        $list_array[$key]['target_link'] = $value['url'];
                    }else{
                        $list_array[$key]['target_link'] = SITE_PATH."cms/view_cms/".base64_encode($value['id']);
                    }
                }
                WebservicesFunction::createJson($file_name,json_encode($list_array),"CREATE","cms");
            }
        }
        if(empty($list_array)){
            return array();
        }
        return $list_array;
    }

    public static function get_patient_due_amount_data_by_id($id)
    {
        $query = "select * from patient_due_amounts where id = $id limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data);
        } else {
            return false;
        }
    }

    public static function is_allowed_add_doc($thinapp_id)
    {
        $query = "select `thinapps`.`allowed_doctor_count`,COUNT(`appointment_staffs`.`id`) AS `total_doc` from thinapps LEFT JOIN `appointment_staffs` ON (`appointment_staffs`.`thinapp_id` = `thinapps`.`id` AND `appointment_staffs`.`staff_type` = 'DOCTOR' AND `appointment_staffs`.`status` = 'ACTIVE') where `thinapps`.`id` = $thinapp_id GROUP BY `thinapps`.`id` limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $data = mysqli_fetch_assoc($data);
            if($data['allowed_doctor_count'] > $data['total_doc'])
            {
                return 0;
            }
            else
            {
                return $data['allowed_doctor_count'];
            }
        } else {
            return $data['allowed_doctor_count'];
        }
    }

    /* IVR METHOD START */
    public static function get_doctor_ivr_code($ivr_number){
        if(!empty($ivr_number)){
            $query = "select doctor_code from doctors_ivr  where ivr_number = '$ivr_number' and ivr_number != '' and doctor_status = 'Active' limit 1";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                return mysqli_fetch_assoc($data)['doctor_code'];
            }
        }
        return false;
    }

    public static function get_ivr_sub_step_name($doctor_id, $ivr_step_name,$ivr_digit){
        $query = "SELECT iss.ivr_sub_step_name from ivr_steps AS i_step JOIN ivr_sub_steps AS iss ON iss.ivr_step_id = i_step.ivr_steps_id WHERE iss.ivr_sub_step_value = $ivr_digit AND i_step.ivr_step_name = '$ivr_step_name' AND i_step.doctor_id = $doctor_id AND iss.STATUS = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data)['ivr_sub_step_name'];
        }
        return false;
    }

    public static function get_doctor_ivr_data($ivr_number){
        if(!empty($ivr_number)){
            $query = "select * from doctors_ivr  where ivr_number = '$ivr_number' and ivr_number != ''";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                $list =  mysqli_fetch_all($data,MYSQL_ASSOC);
                $final_array=array();
                foreach($list as $key => $value){
                    $final_array[$value['doctor_id']]= $value;
                }
                return $final_array;
            }
        }
        return false;
    }

    public static function update_iver_call_sid_cache($call_sid,$merge_array){
        $file_name = "input_" . $call_sid;
        $add_to_file_data=array();
        if (!$last_data = json_decode(WebservicesFunction::readJson($file_name, "ivr"), true)) {
            if(is_array($last_data)){
                $add_to_file_data = $last_data;
            }else{
                $add_to_file_data =$merge_array;
            }

        } else {
            $add_to_file_data =array_merge($last_data,$merge_array);
        }
        WebservicesFunction::createJson($file_name, json_encode($add_to_file_data), "CREATE", "ivr");
    }

    public static function get_ivr_call_sid_cache_data($call_sid){
        $file_name = "input_" . $call_sid;
        return json_decode(WebservicesFunction::readJson($file_name, "ivr"), true);

    }

    public static function get_doctor_ivr_custom_time($doctor_id,$time_flag){
        $time_flag =strtoupper($time_flag);
        $query = "SELECT iss.from_time, iss.to_time FROM ivr_sub_steps AS iss WHERE iss.doctor_id = $doctor_id AND UPPER(iss.ivr_sub_step_name) = '$time_flag' AND iss.STATUS = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data);
        }
        return false;
    }

    /* IVR METHOD END */

    public static function get_default_field_array(){
        return array(
            'uhid'=>array('status'=>'ACTIVE','order'=>1,'label'=>'UHID','column'=>'uhid','custom_field'=>'NO','input'=>'NO','form_field_order'=>14,'form_status'=>'INACTIVE'),
            'name'=>array('status'=>'ACTIVE','order'=>2,'label'=>'Patient Name','column'=>'patient_name','custom_field'=>'NO','input'=>'YES','form_field_order'=>1,'form_status'=>'ACTIVE'),
            'parents'=>array('status'=>'ACTIVE','order'=>3,'label'=>'Parents Name','column'=>'parents_name','custom_field'=>'NO','input'=>'YES','form_field_order'=>11,'form_status'=>'ACTIVE'),
            'Parents Mobile'=>array('status'=>'ACTIVE','order'=>4,'label'=>'Parents Mobile','column'=>'parents_mobile','custom_field'=>'NO','input'=>'YES','form_field_order'=>13,'form_status'=>'ACTIVE'),
            'age'=>array('status'=>'ACTIVE','order'=>5,'label'=>'Age','column'=>'age','custom_field'=>'NO','input'=>'YES','form_field_order'=>3,'form_status'=>'ACTIVE'),
            'gender'=>array('status'=>'ACTIVE','order'=>6,'label'=>'Gender','column'=>'gender','custom_field'=>'NO','input'=>'YES','form_field_order'=>5,'form_status'=>'ACTIVE'),
            'address'=>array('status'=>'ACTIVE','order'=>7,'label'=>'Address','column'=>'address','custom_field'=>'NO','input'=>'YES','form_field_order'=>9,'form_status'=>'ACTIVE'),
            'token_no'=>array('status'=>'ACTIVE','order'=>8,'label'=>'Token','column'=>'queue_number','custom_field'=>'NO','input'=>'NO','form_field_order'=>28,'form_status'=>'INACTIVE'),
            'date'=>array('status'=>'ACTIVE','order'=>9,'label'=>'Date','column'=>'appointment_datetime','custom_field'=>'NO','input'=>'NO','form_field_order'=>29,'form_status'=>'INACTIVE'),
            'weight'=>array('status'=>'ACTIVE','order'=>10,'label'=>'Weight (Kg)','column'=>'weight','custom_field'=>'NO','input'=>'YES','form_field_order'=>18,'form_status'=>'ACTIVE'),
            'mobile'=>array('status'=>'INACTIVE','order'=>11,'label'=>'Patient Mobile','column'=>'mobile','custom_field'=>'NO','input'=>'YES','form_field_order'=>2,'form_status'=>'ACTIVE'),
            'OPD Fee'=>array('status'=>'INACTIVE','order'=>12,'label'=>'OPD Fee','column'=>'amount','custom_field'=>'NO','input'=>'NO','form_field_order'=>30,'form_status'=>'INACTIVE'),
            'height'=>array('status'=>'INACTIVE','order'=>13,'label'=>'Height (Cm)','column'=>'patient_height','custom_field'=>'NO','input'=>'YES','form_field_order'=>17,'form_status'=>'ACTIVE'),
            'BP Systolic'=>array('status'=>'INACTIVE','order'=>14,'label'=>'BP Systolic','column'=>'bp_systolic','custom_field'=>'NO','input'=>'YES','form_field_order'=>20,'form_status'=>'INACTIVE'),
            'BP Diastolic'=>array('status'=>'INACTIVE','order'=>15,'label'=>'BP Diastolic','column'=>'bp_diasystolic','custom_field'=>'NO','input'=>'YES','form_field_order'=>21,'form_status'=>'INACTIVE'),
            'BMI'=>array('status'=>'INACTIVE','order'=>16,'label'=>'BMI','column'=>'bmi','custom_field'=>'NO','input'=>'YES','form_field_order'=>22,'form_status'=>'INACTIVE'),
            'BMI Status'=>array('status'=>'INACTIVE','order'=>17,'label'=>'BMI Status','column'=>'bmi_status','custom_field'=>'NO','input'=>'YES','form_field_order'=>23,'form_status'=>'INACTIVE'),
            'temperature'=>array('status'=>'INACTIVE','order'=>18,'label'=>'Temperature','column'=>'temperature','custom_field'=>'NO','input'=>'YES','form_field_order'=>24,'form_status'=>'INACTIVE'),
            'O.Saturation'=>array('status'=>'INACTIVE','order'=>19,'label'=>'O.Saturation','column'=>'o_saturation','custom_field'=>'NO','input'=>'YES','form_field_order'=>25,'form_status'=>'INACTIVE'),
            'validity time'=>array('status'=>'INACTIVE','order'=>20,'label'=>'Validity Upto','column'=>'service_validity_time','custom_field'=>'NO','input'=>'NO','form_field_order'=>31,'form_status'=>'INACTIVE'),
            'doctor name'=>array('status'=>'INACTIVE','order'=>21,'label'=>'Doctor Name','column'=>'doctor_name','custom_field'=>'NO','input'=>'NO','form_field_order'=>32,'form_status'=>'INACTIVE'),
            'token time'=>array('status'=>'INACTIVE','order'=>22,'label'=>'Token Time','column'=>'slot_time','custom_field'=>'NO','input'=>'NO','form_field_order'=>33,'form_status'=>'INACTIVE'),
            'head circumference'=>array('status'=>'INACTIVE','order'=>23,'label'=>'Head Circumference (Cm)','column'=>'head_circumference','custom_field'=>'NO','input'=>'YES','form_field_order'=>19,'form_status'=>'ACTIVE'),
            'DOB'=>array('status'=>'INACTIVE','order'=>24,'label'=>'DOB','column'=>'dob','custom_field'=>'NO','input'=>'YES','form_field_order'=>4,'form_status'=>'ACTIVE'),
            'E-mail'=>array('status'=>'INACTIVE','order'=>25,'label'=>'E-mail','column'=>'email','custom_field'=>'NO','input'=>'YES','form_field_order'=>8,'form_status'=>'ACTIVE'),
            'Conceive Date'=>array('status'=>'INACTIVE','order'=>26,'label'=>'Conceive Date','column'=>'conceive_date','custom_field'=>'NO','input'=>'YES','form_field_order'=>6,'form_status'=>'ACTIVE'),
            'Expected Date'=>array('status'=>'INACTIVE','order'=>27,'label'=>'Expected Date','column'=>'expected_date','custom_field'=>'NO','input'=>'YES','form_field_order'=>7,'form_status'=>'ACTIVE'),
            'Marital Status'=>array('status'=>'INACTIVE','order'=>28,'label'=>'Marital Status','column'=>'marital_status','custom_field'=>'NO','input'=>'YES','form_field_order'=>10,'form_status'=>'ACTIVE'),
            'Blood Group'=>array('status'=>'INACTIVE','order'=>29,'label'=>'Blood Group','column'=>'blood_group','custom_field'=>'NO','input'=>'YES','form_field_order'=>12,'form_status'=>'ACTIVE'),
            'receipt datetime'=>array('status'=>'INACTIVE','order'=>30,'label'=>'Receipt DateTime','column'=>'receipt_datetime','custom_field'=>'NO','input'=>'NO','form_field_order'=>34,'form_status'=>'INACTIVE'),
            'Refer By Name'=>array('status'=>'INACTIVE','order'=>31,'label'=>'Refer By Name','column'=>'referred_by','custom_field'=>'NO','input'=>'YES','form_field_order'=>15,'form_status'=>'ACTIVE'),
            'Refer By Mobile'=>array('status'=>'INACTIVE','order'=>32,'label'=>'Refer By Mobile','column'=>'referred_by_mobile','custom_field'=>'NO','input'=>'YES','form_field_order'=>16,'form_status'=>'ACTIVE'),
            'Reason Of Appointment'=>array('status'=>'INACTIVE','order'=>33,'label'=>'Reason Of Appointment','column'=>'reason_of_appointment','custom_field'=>'NO','input'=>'YES','form_field_order'=>26,'form_status'=>'ACTIVE'),
            'Remark'=>array('status'=>'INACTIVE','order'=>34,'label'=>'Remark','column'=>'notes','custom_field'=>'NO','input'=>'YES','form_field_order'=>27,'form_status'=>'ACTIVE'),
            'Last UHID'=>array('status'=>'INACTIVE','order'=>35,'label'=>'Last UHID','column'=>'third_party_uhid','custom_field'=>'NO','input'=>'YES','form_field_order'=>35,'form_status'=>'ACTIVE'),
            'Country'=>array('status'=>'INACTIVE','order'=>36,'label'=>'Country','column'=>'country_id','custom_field'=>'NO','input'=>'YES','form_field_order'=>36,'form_status'=>'ACTIVE'),
            'State'=>array('status'=>'INACTIVE','order'=>37,'label'=>'State','column'=>'state_id','custom_field'=>'NO','input'=>'YES','form_field_order'=>37,'form_status'=>'ACTIVE'),
            'City'=>array('status'=>'INACTIVE','order'=>38,'label'=>'City','column'=>'city_id','custom_field'=>'NO','input'=>'YES','form_field_order'=>38,'form_status'=>'ACTIVE'),
            'field1'=>array('status'=>'INACTIVE','order'=>39,'label'=>'field1','column'=>'field1','custom_field'=>'YES','input'=>'YES','form_field_order'=>39,'form_status'=>'ACTIVE'),
            'field2'=>array('status'=>'INACTIVE','order'=>40,'label'=>'field2','column'=>'field2','custom_field'=>'YES','input'=>'YES','form_field_order'=>40,'form_status'=>'ACTIVE'),
            'field3'=>array('status'=>'INACTIVE','order'=>41,'label'=>'field3','column'=>'field3','custom_field'=>'YES','input'=>'YES','form_field_order'=>41,'form_status'=>'ACTIVE'),
            'field4'=>array('status'=>'INACTIVE','order'=>42,'label'=>'field4','column'=>'field4','custom_field'=>'YES','input'=>'YES','form_field_order'=>42,'form_status'=>'ACTIVE'),
            'field5'=>array('status'=>'INACTIVE','order'=>43,'label'=>'field5','column'=>'field5','custom_field'=>'YES','input'=>'YES','form_field_order'=>43,'form_status'=>'ACTIVE'),
            'field6'=>array('status'=>'INACTIVE','order'=>44,'label'=>'field6','column'=>'field6','custom_field'=>'YES','input'=>'YES','form_field_order'=>44,'form_status'=>'ACTIVE'),
            'patient_category'=>array('status'=>'INACTIVE','order'=>45,'label'=>'Patient Category','column'=>'patient_category','custom_field'=>'NO','input'=>'YES','form_field_order'=>45,'form_status'=>'INACTIVE'),

        );
    }

    public static function get_app_prescription_fields($thin_app_id){
        $query = "select fields from setting_web_prescriptions  where thinapp_id =$thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return json_decode(mysqli_fetch_assoc($data)['fields'],true);
        }
        return false;
    }

    public static function get_child_last_growth($child_id){
        $query = "select * from child_growths  where children_id = $child_id order by id desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return json_decode(mysqli_fetch_assoc($data)['fields'],true);
        }
        return false;
    }

    public static function get_reason_of_appointment_list($thin_app_id){
        $query = "SELECT DISTINCT(`reason_of_appointment`) AS `reason_of_appointment` FROM `appointment_customer_staff_services` WHERE `thinapp_id` = '".$thin_app_id."' ORDER BY RAND() LIMIT 150";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_all($data,MYSQLI_NUM);
        }
        return array();
    }

   
	public static function delete_doctor_cache($doctor_id,$thin_app_id = 0){
        $data = Custom::get_doctor_by_id($doctor_id);
        $thin_app_id = @$data['thinapp_id'];
    	$mobile = @$data['mobile'];
        $post_id = @$data['post_id'];

        $file_name = Custom::encrypt_decrypt('encrypt', "doctor_$doctor_id");
        WebservicesFunction::deleteJson(array($file_name), 'login_users');
        $file_name = Custom::encrypt_decrypt('encrypt',"doctor_".$doctor_id);
        WebservicesFunction::deleteJson(array($file_name), 'doctor');
        $file_name = Custom::encrypt_decrypt('encrypt',"single_doctor_setting_$thin_app_id");
        WebservicesFunction::deleteJson(array($file_name), 'doctor');
        $file_name = Custom::encrypt_decrypt('encrypt',"doctor_counter_$thin_app_id");
        $file_name_1 = "doctor_service_data_$doctor_id";
        $file_name_2 = "doctor_ids_$thin_app_id";
    	$file_name_3 = "doctor_custom_data_".base64_encode($mobile);
        WebservicesFunction::deleteJson(array($file_name,$file_name_1,$file_name_2,$file_name_3), 'doctor');
        $file_name = array("slot_booking_data_$doctor_id","breaks_$doctor_id");
        WebservicesFunction::deleteJson($file_name, "appointment/$doctor_id");
        $file_name = array("doctor_hours_$doctor_id","doctor_slots_new_app_$doctor_id");
        WebservicesFunction::deleteJson($file_name, "appointment/hours");
        Custom::delete_hospital_cache($thin_app_id);
        $file_name = array("blog_post_$post_id");
        WebservicesFunction::deleteJson($file_name, "blog");

    }


	public static function delete_hospital_cache($thin_app_id){
        $file_array = array(
            "hospital_get_doctor_list_$thin_app_id"."_USER",
            "hospital_get_doctor_list_$thin_app_id"."_ADMIN",
            "hospital_get_doctor_list_$thin_app_id"."_RECEPTIONIST",
            "hospital_get_doctor_list_$thin_app_id"."_STAFF",
            "hospital_get_doctor_list_$thin_app_id"."_",
            "hospital_get_doctor_list_all$thin_app_id"."_USER",
            "hospital_get_doctor_list_all$thin_app_id"."_ADMIN",
            "hospital_get_doctor_list_all$thin_app_id"."_RECEPTIONIST",
            "hospital_get_doctor_list_all$thin_app_id"."_STAFF",
            "hospital_get_doctor_list_all$thin_app_id"."_",
        );
        WebservicesFunction::deleteJson($file_array, 'thinapp');
    }

    public static function get_appointment_selected_type($appointment_id){
        $query = "SELECT selected_type FROM tracker_time_differences WHERE appointment_id =$appointment_id and selected_type != 'NONE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data)['selected_type'];
        }
        return false;
    }



    public static function get_folder_data_by_time_stamp($time_stamp,$thin_app_id,$doctor_id){
        $query = "select df.*, u.mobile, u.username from drive_files as dfile join drive_folders as df on dfile.drive_folder_id = df.id join users as u on u.id = df.user_id  where  dfile.time_stamp = '$time_stamp' and dfile.doctor_id = $doctor_id and dfile.thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return_arr =array();
        if ($service_message_list->num_rows) {
            $return_arr = mysqli_fetch_assoc($service_message_list);
            return $return_arr;
        }
        return $return_arr;
    }

    public static function get_doctor_custom_tokens($doctor_id,$address_id,$booking_date,$service_id = 0, $queue_number=0){
        $condition = $limit = "";

        if($queue_number > 0){
            $condition .= " and acss.queue_number = '$queue_number' ";
            $limit = " limit 1";
        }
        if($service_id > 0){
            $condition .= " and acss.appointment_service_id = $service_id ";
        }

        $query = "SELECT acss.queue_number, acss.slot_time FROM appointment_customer_staff_services AS acss WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_address_id = $address_id AND DATE(acss.appointment_datetime) = '$booking_date' AND acss.custom_token = 'YES' AND acss.status != 'CANCELED' $condition ORDER BY acss.appointment_datetime asc $limit ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return_arr =array();
        if ($service_message_list->num_rows) {
            $return_arr = mysqli_fetch_all($service_message_list,MYSQL_ASSOC);
            return $return_arr;
        }
        return $return_arr;
    }
    public static function upload_file_on_drive(){





        $client = new Google_Client();
        $refresh_token = "1/ysHWUzXu0_7IrC-S_Irmt__klz1Qaj7J73dcQzmXS3I";
        $auth_code = "4/RQFdgc7w_QUl1B_GyhlOpgI8qUTsilbbsaaU71YSB71uob8OWmF55yt5whIAP8yJmXLMEuI4zDX5hfEJHn70fUs";

        $client->setClientId('1012167426695-ja4miqgtoiu18s1ddtndhoo8ldr0nggm.apps.googleusercontent.com');
        $client->setClientSecret('2cTHU_RFWWjZ9lXn3QgwBnyV');
        $client->setAccessType('offline');
        $client->setRedirectUri('https://mengage.in/doctor');
        $client->setScopes(array('https://www.googleapis.com/auth/drive.file'));

        $service = new Google_Service_Drive($client);

        //Insert a file
        $file = new Google_Service_Drive_DriveFile();
        $file->setName(uniqid().'.jpg');
        $file->setDescription('A test document');
        $file->setMimeType('image/jpeg');

        $data = file_get_contents("https://s3-ap-south-1.amazonaws.com/mengage/logo/app_logo_134.png");

        $createdFile = $service->files->create($file, array(
            'data' => $data,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart'
        ));

        print_r($createdFile);


    }


    public static function number_is_blocked($thin_app_id,$mobile,$login_mobile=''){

        $mobile = Custom::create_mobile_number($mobile);
        $condition = "thinapp_id = $thin_app_id and mobile = '$mobile' ";
        if(!empty($mobile) && !empty($login_mobile)){
            $login_mobile =Custom::create_mobile_number($login_mobile);
            $condition = "thinapp_id = $thin_app_id and ( mobile = '$mobile' OR mobile = '$login_mobile') ";
        }
        $query = "select status from app_blocked_users where $condition limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $status = mysqli_fetch_assoc($data)['status'];
            return ($status == "ACTIVE");
        }
        return false;
    }

    public static function delete_tracker_time_difference_cache($appointment_date,$doctor_id, $address_id){
        $file_name = "tracker_difference_minutes__$doctor_id"."_".$address_id."_".date('Y-m-d',strtotime($appointment_date));
        WebservicesFunction::deleteJson(array($file_name), 'tracker');
    }

    public static function get_localization_by_id($id,$list_for){

        if($list_for =="STATE"){
            $query = "select name from states where id = $id limit 1";
        }else if($list_for =="CITY"){
            $query = "select name from cities where id  = $id limit 1";
        }else{
            $query = "select name from countries where id =$id limit 1";
        }
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['name'];
        }else{
            return false;
        }
    }


    public static function is_doctor($user_id){
        $query = "select id from appointment_staffs where user_id = $user_id  and status = 'ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return true;
        }
        return false;
    }

    public static function setMyScriptkeyInactive($my_script_key){

        $key_array = Custom::getAllMyScriptKeys();
        foreach($key_array as $index => $data){
            if($data['key'] == $my_script_key){
                $key_array[$index]['status'] = 'INACTIVE';
            }
        }
        $file_name = "active_key_".date('Y_m');
        WebservicesFunction::createJson($file_name,json_encode($key_array),"CREATE","myscript");
    }

    public static function setMyScriptActiveKey($key_name){
        $key_array = Custom::getAllMyScriptKeys();
        foreach($key_array as $index => $data){
            if($data['key'] == $key_name){
                $key_array[$index]['status'] = 'ACTIVE';
            }
        }
        $file_name = "active_key_".date('Y_m');
        WebservicesFunction::createJson($file_name,json_encode($key_array),"CREATE","myscript");
    }

    public static function getMyScriptActiveKey(){
        $return_key="";
        $key_array = Custom::getAllMyScriptKeys();
        if(!empty($key_array)){
            foreach($key_array as $final_key){
                if($final_key['status']=='ACTIVE'){
                    return $final_key['key'];
                }
            }
        }
        return $return_key;
    }

    public static function setAllKeysActive(){

        $all_keys = explode(',',MY_SCRIPT_KEY_ARRAY);
        $save_array = array();
        foreach($all_keys as $index => $key){
            $save_array[$index]['key'] = $key;
            $save_array[$index]['status'] = 'ACTIVE';
        }
        $file_name = "active_key_".date('Y_m');
        WebservicesFunction::createJson($file_name,json_encode($save_array),"CREATE","myscript");

    }

    public static function getAllMyScriptKeys(){
        $file_name = "active_key_".date('Y_m');
        $constant_keys = explode(',',MY_SCRIPT_KEY_ARRAY);
        $key_array = json_decode(WebservicesFunction::readJson($file_name,"myscript"),true);
        if(empty($key_array) || count($constant_keys) != count($key_array)){
            Custom::setAllKeysActive();
            return json_decode(WebservicesFunction::readJson($file_name,"myscript"),true);
        }
        return $key_array;
    }





    public static function is_token_booked($booking_datetime,$doctor_id,$address_id,$service_id,$queue_number,$slot_time){

        $booking_date = date('Y-m-d',strtotime($booking_datetime));
        $query = "SELECT acss.id FROM appointment_customer_staff_services AS acss join appointment_staffs as app_staff on app_staff.id = acss.appointment_staff_id WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_address_id = $address_id AND acss.appointment_service_id = $service_id AND acss.status IN('NEW','CONFIRM','RESCHEDULE') AND acss.delete_status !='DELETED' AND DATE(acss.appointment_datetime) = '$booking_date' AND  ( (  app_staff.show_appointment_time ='YES' AND app_staff.show_appointment_token ='YES' AND ( slot_time = '$slot_time' AND acss.queue_number = '$queue_number') ) OR (app_staff.show_appointment_time ='YES' AND app_staff.show_appointment_token ='NO' AND slot_time = '$slot_time'  AND acss.queue_number = '$queue_number') OR (app_staff.show_appointment_token ='YES' AND app_staff.show_appointment_time ='NO' AND acss.queue_number = '$queue_number' AND slot_time = '$slot_time') ) LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return true;
        }
        return false;


    }


    public static function get_doctor_ivr_data_by_input($input,$doctor_ids){
        if(!empty($doctor_ids) && !empty($input)){
            $doctor_ids =  '"'.implode('","', $doctor_ids).'"';
            $query = "SELECT ivs.ivr_sub_step_name AS doctor_code, ivs.doctor_id FROM ivr_steps AS iv_s JOIN ivr_sub_steps AS ivs ON iv_s.ivr_steps_id = ivs.ivr_step_id AND iv_s.ivr_step_name ='SELECT_DOCTOR' AND ivs.ivr_sub_step_value = '$input' AND ivs.doctor_id IN($doctor_ids) limit 1";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                return mysqli_fetch_assoc($data);
            }
        }
        return false;
    }

    public static function get_app_active_token($thin_app_id){
        $query = "SELECT u.firebase_token FROM users as u where u.thinapp_id = $thin_app_id and u.role_id = 1 and u.app_installed_status ='INSTALLED' and u.firebase_token !='' ";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return array_column(mysqli_fetch_all($data,MYSQL_ASSOC),'firebase_token');
        }
        return false;
    }


    public static function allow_receptionist_to_reschedule($user_id){
        if(!empty($user_id)){
            $query = "SELECT staff.allow_reschedule_appointment FROM appointment_staffs AS staff WHERE staff.staff_type='RECEPTIONIST' AND staff.status='ACTIVE' AND staff.user_id = '$user_id' limit 1";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                $data =  mysqli_fetch_assoc($data);
                return ($data['allow_reschedule_appointment']=="YES");
            }
        }
        return true;
    }

    public static function add_doctor_default_category_and_service_for_clinic($thin_app_id, $doctor_id){
        $category_list =array(
            'Consultation'=>array('Consultation Charges','Emergency Visit Charges','Follow-up Visit Charges'),
            'Nursing'=>array('Nursing Charges','Dressing','Injection Charges','Nebulization'),
            'Opd Procedures'=>array('Root Canal','Endoscopy','Laparoscopy'),
            'Vaccinations'=>array('BCG','OVP','Hepatitis B-1','Hepatitis B-2','Hepatitis B-3','Measels','Chickenpox'),
            'Miscellaneous'=>array('Registration Charges'),
        );
        if(!empty($thin_app_id) && !empty($doctor_id)){
            $result =array();
            $query = "SELECT hsc.id FROM hospital_service_categories AS hsc WHERE hsc.doctor_id = $doctor_id and hsc.thinapp_id = $thin_app_id limit 1";
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(false);
            $data = $connection->query($query);
            if (!$data->num_rows) {
                $created = Custom::created();
                foreach($category_list as $category_name => $service_list){
                    $sql = "INSERT INTO hospital_service_categories (thinapp_id, doctor_id, name, created, modified, record_for) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt1 = $connection->prepare($sql);
                    $record_for = 'CLINIC';
                    $stmt1->bind_param('ssssss', $thin_app_id, $doctor_id, $category_name, $created, $created,$record_for);
                    if($stmt1->execute()){
                        $result[] =true;
                        $category_id =$stmt1->insert_id;
                        foreach ($service_list as $list_key  => $service_name){
                            $sql = "INSERT INTO medical_products (thinapp_id, doctor_id, hospital_service_category_id, name, created, modified) VALUES (?, ?, ?, ?, ?, ?)";
                            $stmt_service = $connection->prepare($sql);
                            $stmt_service->bind_param('ssssss', $thin_app_id, $doctor_id, $category_id, $service_name, $created, $created);
                            $result[] = $stmt_service->execute();
                        }
                    }else{
                        $result[] =false;
                    }



                }
            }
            if(!empty($result) && !in_array(false,$result)){
                $connection->commit();
                return true;
            }else{
                $connection->rollback();
            }
        }
        return false;
    }


    public static function getAllCountryList(){
        $file_name = "all_country_list";
        if(!$list_array = json_decode(WebservicesFunction::readJson($file_name,"localization"),true)){
            $query = "select id as value, name as label from countries order by name asc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $list_array =array();
            if ($service_message_list->num_rows) {
                $list_array =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                WebservicesFunction::createJson($file_name,json_encode($list_array),"CREATE","localization");
            }
        }
        return $list_array;

    }
    public static function getAllStateList(){
        $file_name = "all_state_list";
        if(!$list_array = json_decode(WebservicesFunction::readJson($file_name,"localization"),true)){
            $query = "select id as value,name as label from states order by name asc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $list_array =array();
            if ($service_message_list->num_rows) {
                $list_array =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                WebservicesFunction::createJson($file_name,json_encode($list_array),"CREATE","localization");
            }
        }
        return $list_array;

    }
    public static function getAllCityList(){
        $file_name = "all_city_list";
        if(!$list_array = json_decode(WebservicesFunction::readJson($file_name,"localization"),true)){
            $query = "SELECT cy.id AS value,cy.name as label from countries AS c left JOIN states AS s ON s.country_id = c.id left JOIN   cities AS cy ON cy.state_id = s.id  WHERE c.id = 101 AND cy.name !=''   order BY cy.name asc";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            $list_array =array();
            if ($service_message_list->num_rows) {
                $list_array =mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                WebservicesFunction::createJson($file_name,json_encode($list_array),"CREATE","localization");
            }
        }
        return $list_array;

    }



    public static function single_doctor_appointment_ids($thin_app_id)
    {
        $response =array();
        $file_name = Custom::encrypt_decrypt('encrypt',"single_doctor_setting_$thin_app_id");
        if(!$response = json_decode(WebservicesFunction::readJson($file_name,"doctor"),true)){
            if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
                $query = "SELECT   app_staff.id as doctor_id, app_ser.id as service_id, aa.id as address_id from doctor_appointment_setting as das join thinapps as t on t.id = das.thinapp_id join  appointment_addresses as aa on  aa.id = das.appointment_address_id and aa.status ='ACTIVE'  join appointment_services as app_ser on app_ser.id = das.appointment_service_id and app_ser.status = 'ACTIVE' join appointment_staffs as app_staff on app_staff.status = 'ACTIVE' AND app_staff.id = das.doctor_id and das.setting_type = app_staff.appointment_setting_type where (das.appointment_date='0000-00-00' OR (das.appointment_date >= DATE(NOW()))) AND das.thinapp_id = $thin_app_id";
            } else {
                $query = "SELECT app_staff.id as doctor_id, app_ser.id as service_id, aa.id as address_id from appointment_staff_addresses as app_staff_add join thinapps as t on t.id = app_staff_add.thinapp_id  join appointment_staff_services as ass on app_staff_add.appointment_staff_id = ass.appointment_staff_id AND ass.status = 'ACTIVE' join appointment_addresses as aa on  aa.id = app_staff_add.appointment_address_id AND aa.status ='ACTIVE'  join appointment_services as app_ser on app_ser.id = ass.appointment_service_id and app_ser.status ='ACTIVE' join appointment_staffs as app_staff on app_staff.status = 'ACTIVE' AND app_staff.id = app_staff_add.appointment_staff_id where app_staff_add.thinapp_id = $thin_app_id";
            }
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $response = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                WebservicesFunction::createJson($file_name,json_encode($response),"CREATE","doctor");
            }
        }

        if(count($response) != 1){
            return array();
        }else{
            return $response;
        }

    }

    public static function create_patient_urls($patient_id,$patient_type,$doctor_id=0){

        $type = "CU";
        if($patient_type=="CHILDREN"){
            $query = "SELECT c.thinapp_id, c.user_id, c.uhid, df.id AS  folder_id FROM childrens  AS c JOIN drive_folders AS df ON df.children_id = c.id WHERE c.id = $patient_id limit 1";
            $type = "CH";
        }else{
            $query = "SELECT ac.thinapp_id, ac.user_id, ac.uhid, df.id AS  folder_id FROM appointment_customers AS ac JOIN drive_folders AS df ON df.appointment_customer_id = ac.id WHERE ac.id = $patient_id limit 1";
        }
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        $return['history_url'] ='';
        $return['invoice_url'] ='';
        $return['medical_certificate_url'] ='';
        $return['graph_url'] ='';
        if ($service_message_list->num_rows) {
            $data = mysqli_fetch_assoc($service_message_list);
            $thin_app_id = $data['thinapp_id'];
            $uhid = $data['uhid'];
            $user_id = $data['user_id'];
            $folder_id = $data['folder_id'];
            $patientHistoryUrl = SITE_PATH.'tracker/get_patient_history/';
            $history_url = $patientHistoryUrl.base64_encode($thin_app_id)."/".base64_encode($uhid);
            $invoice_url = SITE_PATH . 'invoice/patient/index.php?t=' . $thin_app_id . '&&di=' . $doctor_id . "&&pi=$patient_id&&ty=$type&&fi=$folder_id";
            $medical_certificate_url = SITE_PATH . 'medical_certificate/medical_certificate.php?patient_type=' . $patient_type . '&user_id=' . $user_id . '&thinapp_id=' . $thin_app_id . "&patient_id=$patient_id&folder_id=$folder_id";
            $graph_url = SITE_PATH . 'chart/child_graph.php?t=' . $thin_app_id . '&&c=&&cat=';
            $return['history_url'] =$history_url;
            $return['invoice_url'] =$invoice_url;
            $return['medical_certificate_url'] =$medical_certificate_url;
            $return['graph_url'] =$graph_url;
        }
        return $return;


    }

    public static function get_admin_app_data_by_mobile($mobile){
        $query = "select t.*, u.username from thinapps as t join users as u on u.thinapp_id = t.id and u.role_id = 5 JOIN customer_lead AS cl ON cl.app_id = u.thinapp_id AND cl.`status`='DONE'  where  u.mobile = '$mobile' AND t.`status` ='ACTIVE' limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

    public static function is_category_or_service_name_exist($action_for,$thin_app_id,$name,$doctor_id,$category_id=0,$service_id=0){
        $name = strtoupper(trim($name));
        if($action_for=="CATEGORY"){
            $condition = !empty($category_id)?" and hsc.id != $category_id":"";
            $query = "SELECT hsc.id FROM hospital_service_categories AS hsc WHERE hsc.doctor_id = $doctor_id and hsc.thinapp_id = $thin_app_id and UPPER(hsc.name) = '$name' AND hsc.status = 'ACTIVE' $condition  limit 1";
        }else{
            $condition = !empty($service_id)?" and mp.id != $service_id":"";
            $query = "SELECT mp.id FROM medical_products AS mp WHERE mp.hospital_service_category_id = $category_id AND  mp.thinapp_id = $thin_app_id and UPPER(mp.name) = '$name' AND mp.status = 'ACTIVE' $condition limit 1";
        }

        $connection = ConnectionUtil::getConnection();
        $connection->autocommit(false);
        $data = $connection->query($query);
        if ($data->num_rows) {
            return true;
        }
        return false;
    }


    public static function get_menage_clinic_token_data($thinapp_id,$mobile){


        $file_name = Custom::encrypt_decrypt('encrypt',"user_$mobile"."_$thinapp_id");
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"user"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "select * from users  where  mobile = '$mobile' and thinapp_id = $thinapp_id and ( role_id = 1 OR role_id =5 )  limit 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","user");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }

    }

    public static function tab_get_service_list($doctor_id){
        $file_name = "doctor_service_$doctor_id";
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"CLINIC"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT mp.id as service_id, mp.name as service_name, mp.price  FROM hospital_service_categories as hsc left join medical_products as mp on mp.hospital_service_category_id = hsc.id and mp.status='ACTIVE' where hsc.status = 'ACTIVE' and  hsc.doctor_id = $doctor_id and record_for ='CLINIC'";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE",'CLINIC');
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }
    }


    public static function tab_get_payment_history($doctor_id,$patient_id,$patient_type){
        if($patient_type=="CUSTOMER"){
            $condition = " mpo.appointment_customer_id = $patient_id";
        }else{
            $condition = " mpo.children_id = $patient_id";
        }
        $query = "SELECT mpo.id, mpo.created, (SELECT pda.amount FROM patient_due_amounts AS pda WHERE pda.settlement_by_order_id = mpo.id AND pda.paid_via_patient ='YES' AND pda.payment_status = 'PAID' AND pda.status='ACTIVE' order by pda.id desc limit 1) AS due_paid_amount, GROUP_CONCAT(CONCAT(IFNULL(mp.name,mpod.service),'(',mpod.total_amount,')')) AS service_name, mpo.total_amount AS paid_amount, aa.address, mpo.charged_amount from  medical_product_orders AS mpo   LEFT JOIN medical_product_order_details AS mpod ON mpod.medical_product_order_id = mpo.id LEFT JOIN medical_products AS mp ON mp.id= mpod.medical_product_id LEFT JOIN appointment_addresses AS aa ON aa.id = mpo.appointment_address_id WHERE $condition and  mpo.appointment_staff_id = $doctor_id AND mpo.payment_add_via ='CLINIC' AND mpo.status = 'ACTIVE' GROUP BY mpo.id ORDER BY mpo.id desc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
        }else{
            return false;
        }
    }
    public static function tab_payment_modal_data($patient_id,$patient_type){
        if($patient_type=="CUSTOMER"){
            $query = "SELECT ac.first_name AS patient_name, IFNULL(pda.amount,0.00) AS due_amount from appointment_customers  AS ac LEFT JOIN patient_due_amounts AS pda ON pda.appointment_customer_id = ac.id AND pda.payment_status ='DUE' AND pda.`status` ='ACTIVE' WHERE ac.id = $patient_id ORDER BY pda.id desc limit 1";
        }else{
            $query = "SELECT c.child_name AS patient_name, IFNULL(pda.amount,0.00) AS due_amount from childrens AS c LEFT JOIN patient_due_amounts AS pda ON pda.children_id = c.id AND pda.payment_status ='DUE' AND pda.`status` ='ACTIVE' WHERE c.id =$patient_id ORDER BY pda.id desc limit 1";
        }
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }


    public static function tab_get_dashboard_counts($thin_app_id,$doctor_id,$from_date,$to_date){
        $query = "SELECT ((SELECT COUNT(id) FROM appointment_customers WHERE STATUS='ACTIVE' AND thinapp_id =$thin_app_id)+(SELECT COUNT(id) FROM childrens WHERE status='ACTIVE' AND thinapp_id = $thin_app_id)) AS total_patient, ((SELECT COUNT(id) FROM appointment_customers WHERE status='ACTIVE' AND thinapp_id =$thin_app_id AND DATE(created) BETWEEN '$from_date' AND '$to_date')+(SELECT COUNT(id) FROM childrens WHERE status='ACTIVE' AND thinapp_id = $thin_app_id AND DATE(created) BETWEEN '$from_date' AND '$to_date')) AS total_visits, IFNULL((SELECT SUM(total_amount) FROM medical_product_orders WHERE appointment_staff_id = $doctor_id AND payment_status='PAID' AND status='ACTIVE'),0) AS total_charged, IFNULL((SELECT SUM(total_amount) FROM medical_product_orders WHERE appointment_staff_id = $doctor_id AND payment_status='PAID' AND status='ACTIVE' AND DATE(created) BETWEEN '$from_date' AND '$to_date'),0) AS total_received, IFNULL((SELECT SUM(pda.amount) FROM patient_due_amounts pda JOIN medical_product_orders AS mpo ON mpo.id = pda.medical_product_order_id WHERE mpo.appointment_staff_id = $doctor_id AND pda.payment_status='DUE' AND pda.status='ACTIVE'),'0.00') AS total_due";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }


    public static function tab_get_template_by_id($template_id,$doctor_id=0){
        $query = "select * from tab_prescription_templates where id = $template_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function manage_emergency_tracker_cache($appointment_id,$action="CREATE"){

        $query = "SELECT acss.emergency_appointment, acss.appointment_staff_id AS doctor_id, app_staff.allow_emergency_appointment, IFNULL(ac.first_name,c.child_name) AS patient_name  from appointment_customer_staff_services AS acss JOIN appointment_staffs AS app_staff ON app_staff.id = acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id =acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id WHERE  acss.id = $appointment_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $appointment_data =  mysqli_fetch_assoc($data);
            $file_name = "emergency_".$appointment_data['doctor_id']."_".date('Ymd');
            if($action=="CREATE"){
                if($appointment_data['emergency_appointment']=="YES"){
                    $tracker_data['flag'] = "EMERGENCY";
                    $tracker_data['patient_name'] = $appointment_data['patient_name'];
                    WebservicesFunction::createJson($file_name, $tracker_data, 'CREATE','tracker');
                }else{
                    WebservicesFunction::deleteJson(array($file_name), 'tracker');
                }

            }else{
                WebservicesFunction::deleteJson(array($file_name), 'tracker');
            }
        }else{
            return false;
        }
        return true;

    }


    public static function  get_medicine_type_list(){
        return array("N/A","TABLET","CAPSULE","SYRUP","DRY SYRUP","LIQUID","AMPOULE","INJECTION","PRE-FILLED SYRINGE","CREAM","LOTION","OINTMENT","GEL","VIAL",
            "DROPS","SPRAY","DPI","MDI","POWDER","INJECTABLE POWDER","DUSTING POWDER","SUSPENSION","DRY SUSPENSION","DISPERSIBLE TABLET","POUCH","SACHET","INHALER","BUCCAL PASTE",
            "KIT","ROTACAPS","RESPULES","SOFTGELS");
    }


    public static function create_tag_layout($tag_data,$step_id){
        $master_medicine_name_step_id =MASTER_MEDICINE_NAME_STEP_ID;
        $tag_name = $tag_data['tag_name'];
        $tag_id = $tag_data['tag_id'];
        $type = $tag_data['tag_type'];
        $font_size ="";
        $edit_class = "edit_tag";
        if($master_medicine_name_step_id ==  $tag_data['tab_master_prescription_step_id']){
            $type = !empty($type)?"(".$type.")":'';
            $tag_name = strtoupper($tag_data['tag_name'].' '.$type);
            $font_size = 'font-size:12px;';
            $edit_class = "edit_medicine_btn";
        }

        $li_tag_id = "li_tag_".$tag_id;
        $tag_id = base64_encode($tag_id);
        $step_id = base64_encode($step_id);

        $style = "display: block;font-size: 7px;";
        $composition = (!empty($tag_data['composition']))?'<span style="'.$style.'">'.strtoupper($tag_data['composition']).'</span>':'';
        $composition_data = !empty($tag_data['composition'])?"<span class='company_lbl'>".strtoupper($tag_data['composition'])."</span>":'';

        return $string = "
        <li class='tag_box' id='$li_tag_id'>
            <div class='btn-group'>
               <button type='button' class='tag_button_class' style = '$font_size' data-ti='$tag_id' data-tn='$tag_name $composition'> $tag_name $composition_data </button>
                <button type='button' class='dropdown-toggle' data-toggle='dropdown'><span class='caret'></span> <span class='sr-only'>Toggle Dropdown</span></button>
                <ul class='dropdown-menu' role='menu'>
                    <li><a href='javascript:void(0);' class='delete_tag' data-si='$step_id' data-ti='$tag_id' ><i class='fa fa-trash'></i> Delete</a></li>
                    <li><a href='javascript:void(0);' class='$edit_class' data-si='$step_id' data-ti='$tag_id' data-tn='$tag_name'><i class='fa fa-edit'></i> Edit</a></li>
                </ul>
            </div>
        </li>";
    }

    public static function get_tag_by_ids($tag_id_array){
        $limit = count($tag_id_array);
        $tag_id_array = "'".implode("','", $tag_id_array)."'";
        $query = "select tps.tab_master_prescription_step_id, tpt.id as tag_id, tpt.tab_prescription_step_id as step_id, tpt.tag_name, tpt.company_name, tpt.composition, tpt.tag_type, tpt.tag_notes from tab_prescription_tags as tpt join tab_prescription_steps as tps on tps.id = tpt.tab_prescription_step_id where tpt.id IN($tag_id_array) limit $limit";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_all($service_message_list,MYSQL_ASSOC);
        }else{
            return false;
        }
    }

    public static function get_web_prescription_data($doctor_data,$thinapp_id,$patient_id,$patient_type,$address_id,$appointment_id=0){

        $barcode_on_prescription = $doctor_data['barcode_on_prescription'];
        $doctor_name = $doctor_data['name'];
        $category_id = $doctor_data['department_category_id'];

        if(!empty($appointment_id)){
            $condition = " acss.id = $appointment_id";
            $query = "select IFNULL(ac.allergy,c.allergy) as allergy, IFNULL(ac.flag,c.flag) as flag, swp.patient_detail_box, swp.tb_top, swp.tb_width, swp.prescription, swp.tb_left, dc.category_name, acss.has_token, acss.emergency_appointment,  aa.address AS clinic_address, aa.clinic_name, swp.prescription_base64, swp.field_layout_type, IFNULL(ac.third_party_uhid,'') as third_party_uhid, IFNULL(ac.country_id,c.country_id) as country_id, IFNULL(ac.state_id,c.state_id) as state_id,IFNULL(ac.city_id,c.city_id) as city_id,  IFNULL(ac.city_name,c.city_name) as city_name, acss.referred_by, acss.referred_by_mobile, acss.notes, acss.reason_of_appointment, IFNULL(ac.blood_group,c.blood_group) as blood_group, marital_status, DATE_FORMAT(ac.conceive_date,'%d-%m-%Y') AS conceive_date, DATE_FORMAT(ac.expected_date,'%d-%m-%Y') AS expected_date, IFNULL(ac.email,'') as email, DATE_FORMAT(IFNULL(ac.dob,c.dob),'%d-%m-%Y') as dob, acss.slot_time, IFNULL(ac.parents_mobile,c.parents_mobile) as parents_mobile, IFNULL(ac.field1,c.field1) as field1, IFNULL(ac.field2,c.field2) as field2, IFNULL(ac.field3,c.field3) as field3, IFNULL(ac.field4,c.field4) as field4, IFNULL(ac.field5,c.field5) as field5, IFNULL(ac.field6,c.field6) as field6, IFNULL(ac.bp_systolic,c.bp_systolic) as bp_systolic, IFNULL(ac.bp_diasystolic,c.bp_diasystolic) as bp_diasystolic, IFNULL(ac.bmi,c.bmi) as bmi, IFNULL(ac.bmi_status,c.bmi_status) as bmi_status, IFNULL(ac.temperature,c.temperature) as temperature, IFNULL(ac.o_saturation,c.o_saturation) as o_saturation,  swp.header_rotate, swp.tb_rotate, swp.header_box_border, app_staff.barcode_on_prescription, DATE_FORMAT(mpo.created,'%d-%m-%Y %h:%i %p')  as receipt_datetime, swp.barcode, acss.id as appointment_id, swp.id as setting_id, swp.top, swp.left, swp.height, swp.width, swp.fields, swp.font_size, IFNULL(ac.address,c.patient_address) as patient_address, IFNULL(ac.gender,c.gender) as gender,  IFNULL(ac.uhid,c.uhid) as uhid, IFNULL(ac.height,cg.height) as patient_height, IFNULL(ac.weight,cg.weight) as weight, IFNULL(ac.head_circumference,cg.head_circumference) as head_circumference, DATE_FORMAT(acss.appointment_datetime,'%d-%m-%Y') AS appointment_datetime , IFNULL(ac.relation_prefix,c.relation_prefix) as relation_prefix, IFNULL(ac.parents_name,c.parents_name) as parents_name, IF(acss.has_token = 'NO',CONCAT_WS('-','WI',acss.queue_number),acss.queue_number) AS queue_number, IFNULL(ac.address,c.patient_address) as address, app_staff.name as doctor_name, CONCAT(IFNULL(ac.title,c.title),' ',IFNULL(ac.first_name,c.child_name)) as patient_name, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age),c.dob) as age, app_ser.service_validity_time, acss.amount from appointment_customer_staff_services as acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id left join childrens as c on c.id = acss.children_id  LEFT JOIN child_growths AS cg ON cg.children_id = c.id AND cg.id = (SELECT MAX(id) FROM child_growths AS c_growth WHERE c_growth.children_id = c.id ) left join appointment_staffs as app_staff on app_staff.id = acss.appointment_staff_id left join appointment_services as app_ser on app_ser.id= acss.appointment_service_id  join appointment_addresses as aa on aa.id= acss.appointment_address_id left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id left join setting_web_prescriptions as swp on swp.thinapp_id = acss.thinapp_id and swp.setting_for ='WEB_PRESCRIPTION' LEFT JOIN department_categories AS dc ON dc.id = $category_id where $condition ";
        }else{
            if($patient_type=="CHILDREN"){
                $condition = " c.thinapp_id = $thinapp_id and c.id = $patient_id";
                $query = "select c.allergy, c.flag, swp.patient_detail_box,  dc.category_name, address.address AS clinic_address, address.clinic_name, swp.prescription_base64, swp.tb_text_font_size, swp.tb_label_font_size, swp.tb_heading_font_size, swp.header_box_border, swp.tag_box_border,  swp.fields, swp.field_layout_type, swp.tb_left, swp.tb_top, swp.tb_width, swp.prescription, swp.header_rotate, swp.field_layout_type, '' as third_party_uhid, c.country_id, c.state_id as state_id,c.city_id,  c.city_name, c.blood_group, '' as marital_status, '' AS conceive_date, '' AS expected_date, c.email, DATE_FORMAT(c.dob,'%d-%m-%Y') as dob, '' as slot_time, c.parents_mobile, c.field1, c.field2, c.field3, c.field4, c.field5, c.field6, c.bp_systolic,c.bp_diasystolic, c.bmi, c.bmi_status, c.temperature, c.o_saturation,  swp.header_rotate, swp.tb_rotate, swp.header_box_border, '$barcode_on_prescription' as barcode_on_prescription, '' as receipt_datetime, swp.barcode, swp.id as setting_id, swp.top, swp.left, swp.height, swp.width, swp.fields, swp.font_size,  c.gender,  c.uhid, c.height as patient_height, c.weight, c.head_circumference, '' AS appointment_datetime , c.relation_prefix, c.parents_name, '' AS queue_number, c.address as patient_address, '$doctor_name' as doctor_name, CONCAT(c.title,' ',c.child_name) as patient_name, c.mobile, IF(c.dob !='0000-00-00' AND c.dob !='' ,c.dob, '') as age  from  childrens as c left join setting_web_prescriptions as swp on swp.thinapp_id = c.thinapp_id and swp.setting_for ='WEB_PRESCRIPTION' LEFT JOIN appointment_addresses AS address ON address.id = $address_id AND address.thinapp_id = c.thinapp_id  LEFT JOIN department_categories AS dc ON dc.id = $category_id where $condition limit 1";
            }else{
                $condition = " ac.thinapp_id = $thinapp_id and ac.id = $patient_id";
                $query = "select ac.allergy, ac.flag, swp.patient_detail_box,  dc.category_name, address.address AS clinic_address, address.clinic_name,  swp.prescription_base64, swp.tb_text_font_size, swp.tb_label_font_size, swp.tb_heading_font_size, swp.header_box_border, swp.tag_box_border,  swp.fields, swp.field_layout_type, swp.tb_left, swp.tb_top, swp.tb_width, swp.prescription, swp.header_rotate, swp.field_layout_type, ac.third_party_uhid, ac.country_id, ac.state_id as state_id,ac.city_id,  ac.city_name, ac.blood_group, ac.marital_status, DATE_FORMAT(ac.conceive_date,'%d-%m-%Y') AS conceive_date, DATE_FORMAT(ac.expected_date,'%d-%m-%Y') AS expected_date, ac.email, DATE_FORMAT(ac.dob,'%d-%m-%Y') as dob, '' as slot_time, ac.parents_mobile, ac.field1, ac.field2, ac.field3, ac.field4, ac.field5, ac.field6, ac.bp_systolic,ac.bp_diasystolic, ac.bmi, ac.bmi_status, ac.temperature, ac.o_saturation,  swp.header_rotate, swp.tb_rotate, swp.header_box_border, '$barcode_on_prescription' as barcode_on_prescription, '' as receipt_datetime, swp.barcode, swp.id as setting_id, swp.top, swp.left, swp.height, swp.width, swp.fields, swp.font_size,  ac.gender,  ac.uhid, ac.height as patient_height, ac.weight, ac.head_circumference, '' AS appointment_datetime , ac.relation_prefix, ac.parents_name, '' AS queue_number, ac.address as patient_address, '$doctor_name' as doctor_name, CONCAT(ac.title,' ',ac.first_name) as patient_name, ac.mobile, IF(ac.dob !='0000-00-00' AND ac.dob !='' ,ac.dob, age) as age  from  appointment_customers as ac left join setting_web_prescriptions as swp on swp.thinapp_id = ac.thinapp_id and swp.setting_for ='WEB_PRESCRIPTION' LEFT JOIN appointment_addresses AS address ON address.id = $address_id AND address.thinapp_id = ac.thinapp_id LEFT JOIN department_categories AS dc ON dc.id = $category_id where $condition limit 1";
            }
        }


        $connection = ConnectionUtil::getConnection();
        $list_obj = $connection->query($query);
        if ($list_obj->num_rows) {
            return  mysqli_fetch_assoc($list_obj);
        }
        return false;
    }

    public static function create_queue_number($appointment_data){
        if(($appointment_data['has_token']=="NO")){
            $queue_explode = explode(".",$appointment_data['queue_number']);
            if(count($queue_explode)==2){
                $token = str_replace('WI-','',$queue_explode[1]);
                return "WI-".$token;
            }else{
                $token = str_replace('WI-','',$appointment_data['queue_number']);
                return "WI-".$token;
            }
        }else{
            if($appointment_data['emergency_appointment']=='YES'){
                $tmp = explode(".",$appointment_data['queue_number']);
                return  "E".$tmp[1];

            }else{
                return trim($appointment_data['queue_number']);
            }
        }
        return !empty($appointment_data['queue_number'])?$appointment_data['queue_number']:'';
    }

    public static function calculateBmi($patient_id,$patient_type){

        if($patient_type == 'CUSTOMER')
        {
            $sql = "SELECT `weight`,`height`,`bmi` FROM `appointment_customers` WHERE `id` = '".$patient_id."' LIMIT 1";
        }
        else
        {
            $sql = "SELECT `weight`,`height`,`bmi` FROM `childrens` WHERE `id` = '".$patient_id."' LIMIT 1";
        }

        $connection = ConnectionUtil::getConnection();
        $list_obj = $connection->query($sql);
        if ($list_obj->num_rows) {
            $data = mysqli_fetch_assoc($list_obj);
            if( empty($data['bmi']) && !empty($data['height']) && !empty($data['weight']) )
            {
                $bmi =  $data['weight'] / (($data['height']/100) * ($data['height']/100));
                return round($bmi,3);
            }
            else
            {
                return $data['bmi'];
            }
        }
        else
        {
            return "";
        }
    }

    public static function upload_web_prescription($data_array,$send_notification=false,$prescription_id=0){

        $base64 =$data_array['base64'];
        $thin_app_id =$data_array['thin_app_id'];
        $user_id =$data_array['user_id'];
        $mobile =$data_array['mobile'];
        $role_id =$data_array['role_id'];
        $folder_id =$data_array['folder_id'];
        $doctor_id =$data_array['doctor_id'];
        $prescription_image = Custom::uploadBase64FileToAws($base64);
        $file_name = explode("/", $prescription_image);
        $file_name = end($file_name);
        $post['thin_app_id'] = $thin_app_id;
        $post['user_id'] = $user_id;
        $post['app_key'] = APP_KEY;
        $post['mobile'] = $mobile;
        $post['role_id'] = $role_id;
        $post['file_array'][0]['file_type'] = Custom::getFileType($file_name);
        $post['file_array'][0]['file_name'] = $file_name;
        $post['file_array'][0]['file_path'] = $prescription_image;
        $post['file_array'][0]['file_size'] = Custom::getRemoteFileSize($prescription_image,"MB");



        $post['listing_type'] = "OTHER";
        $post['drive_folder_id'] = $folder_id;
        $post['doctor_id'] = $doctor_id;
        $post['category_id'] = 6;
        $post['is_tab_prescription'] = "YES";
        $result = json_decode(WebservicesFunction::add_file($post, $send_notification, true),true);
        if($result['status']==1){
            if(!empty($prescription_id)){
                $created = Custom::created();
                $drive_file_id = $result['data']['saved_file'][0]['file_id'];
                $connection = ConnectionUtil::getConnection();
                $connection->autocommit(true);
                $prescripiton_status ='COMPLETED';
                $query = "update tab_patient_prescriptions set prescripiton_status=?, drive_file_id =?, prescription_path=?, modified =? where id = ?";
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sssss', $prescripiton_status, $drive_file_id,$prescription_image, $created, $prescription_id);
                $stmt->execute();
            
            
            }
        }
        return json_encode($result);
    }


    public static function get_patient_master_vital_by_id($thin_app_id,$patient_id,$patient_type,$master_vital_id){
        $query = "SELECT tmv.*, tpv.id AS tab_patient_vital_id FROM tab_patient_vitals AS tpv JOIN tab_master_vitals tmv ON tmv.id = tpv.tab_master_vital_id WHERE tpv.tab_master_vital_id = $master_vital_id AND tpv.thinapp_id = $thin_app_id AND tpv.patient_id = $patient_id AND tpv.patient_type = '$patient_type' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function tab_get_folder_prescription__list($thin_app_id,$folder_id){
        $query = "SELECT tpp.prescription_html, dfile.is_medical_certificate, dfile.created, dfile.id AS file_id, dfile.file_name, dfile.file_type, dfile.file_size, dfile.file_path FROM drive_files AS dfile JOIN drive_folders AS df ON df.id = dfile.drive_folder_id left join tab_patient_prescriptions as tpp on tpp.drive_file_id = dfile.id  WHERE dfile.drive_folder_id = $folder_id and dfile.thinapp_id = $thin_app_id AND dfile.file_category_master_id = 6 ORDER BY dfile.created desc";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }else{
            return false;
        }
    }

    public static function allow_block_appointment_slot($thin_app_id,$user_id){
        $query = "SELECT s.allow_block_appointment_slot FROM appointment_staffs AS s WHERE s.staff_type='RECEPTIONIST' AND s.STATUS='ACTIVE' AND s.user_id =$user_id AND s.thinapp_id = $thin_app_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list)['allow_block_appointment_slot'];
        }else{
            return "NO";
        }
    }

    public static function get_step_master_category_id($step_id){
        $query = "SELECT tab_prescription_category_id FROM tab_prescription_steps WHERE id = $step_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list)['tab_prescription_category_id'];
        }else{
            return 0;
        }
    }


    public static function get_tag_master_category_id($tag_id){
        $query = "SELECT tps.tab_prescription_category_id FROM tab_prescription_tags AS tpt join tab_prescription_steps AS tps ON tps.id = tpt.tab_prescription_step_id  WHERE tpt.id = $tag_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list)['tab_prescription_category_id'];
        }else{
            return 0;
        }
    }

    public static function tab_delete_category_list_cache_file($doctor_id){
        $file_name = "tab_category_list_$doctor_id";
        WebservicesFunction::deleteJson(array($file_name),"tab");

    }
    public static function tab_delete_template_list_cache_file($doctor_id,$category_id){
        $file_name = "tab_category_template_list_$category_id"."_$doctor_id";
        WebservicesFunction::deleteJson(array($file_name),"tab");
    }

    public static function tab_delete_category_step_list_cache_file($doctor_id,$category_id){
        $file_name = "tab_category_step_list_$category_id"."_$doctor_id";
        WebservicesFunction::deleteJson(array($file_name),"tab");
    }


    public static function delete_tab_patient($thin_app_id,$patient_id, $patient_type){
        $status= "INACTIVE";
        $created = Custom::created();
        if($patient_type=="CUSTOMER"){
            $sql = "UPDATE appointment_customers SET status = ?, modified=? where thinapp_id = ? and id = ?";
        }else{
            $sql = "UPDATE childrens SET status = ?, modified=? where thinapp_id = ? and id = ?";
        }
        $connection = ConnectionUtil::getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('ssss', $status, $created, $thin_app_id, $patient_id);
        return $stmt->execute();
    }


    public static function tab_get_hospital_service_category_list($doctor_id){
        $query = "SELECT id,name,status,created,modified FROM hospital_service_categories WHERE doctor_id = $doctor_id AND status= 'ACTIVE' and record_for ='CLINIC'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }else{
            return false;
        }
    }

    public static function tab_get_medical_product_list($doctor_id){
        $query = "SELECT id,name,price,hospital_service_category_id,status,created,modified FROM medical_products WHERE doctor_id = $doctor_id and status= 'ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }else{
            return false;
        }
    }

    public static function tab_get_payment_list($doctor_id){
        $query = "SELECT mpo.id, mpo.appointment_customer_id, mpo.children_id, mpo.appointment_address_id AS address_id,mpo.charged_amount, mpo.clinic_total_amount as total_amount, mpo.total_amount AS paid_amount, mpo.created, mpo.modified  FROM medical_product_orders AS mpo   WHERE mpo.appointment_staff_id = $doctor_id and mpo.status= 'ACTIVE' AND mpo.payment_add_via ='CLINIC'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }else{
            return false;
        }
    }

    public static function tab_get_payment_order_detail_id($medical_product_order_id, $medical_product_id){
        $query = "SELECT mpod.id FROM medical_product_order_details AS mpod WHERE mpod.medical_product_order_id = $medical_product_order_id AND mpod.medical_product_id = $medical_product_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list);
        }else{
            return 0;
        }
    }

    public static function tab_get_medical_product_order($medical_product_order_id){
        $query = "SELECT * FROM medical_product_orders AS mpo WHERE mpo.id = $medical_product_order_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list);
        }else{
            return false;
        }
    }

    public static function tab_get_service_data($medical_product_id){
        $query = "SELECT * FROM medical_products AS mp WHERE mp.id = $medical_product_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list);
        }else{
            return false;
        }
    }


    public static function tab_get_payment_order_detail_list($doctor_id){
        $query = "SELECT mpod.id, mpo.id AS patient_payment_server_id, mp.name AS service_name, mp.price, mp.id AS medical_product_server_id, mpod.created, mpod.modified FROM medical_product_order_details AS mpod JOIN medical_product_orders AS mpo ON mpo.id = mpod.medical_product_order_id JOIN medical_products AS mp ON mp.id = mpod.medical_product_id WHERE mpo.appointment_staff_id = $doctor_id AND mpo.payment_add_via ='CLINIC'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }else{
            return 0;
        }
    }

    public static function get_ivr_data_by_doctor_id($doctor_id){
        $query = "SELECT * FROM doctors_ivr WHERE doctor_id = $doctor_id";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list);
        }
        return false;
    }

    public static function setup_ivr_data($thin_app_id, $doctor_id,$ivr_number="",$return=false){

        $doctor_data =Custom::get_doctor_by_id($doctor_id,$thin_app_id);
        if(!empty($doctor_data)){
            $connection = ConnectionUtil::getConnection();
            
            $created = Custom::created();
            $doctor_ivr = Custom::get_ivr_data_by_doctor_id($doctor_id);
            if(empty($doctor_ivr)){
                $doctor_code =Custom::get_ivr_random_number();
                $sql = "INSERT INTO doctors_ivr (thinapp_id, name, mobile_no, ivr_number, doctor_id, doctor_code, created,modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('ssssssss', $thin_app_id, $doctor_data['name'], $doctor_data['mobile'],$ivr_number,$doctor_data['id'], $doctor_code, $created, $created);
                if ($stmt->execute()) {
                    $doctor_ivr = Custom::get_ivr_data_by_doctor_id($doctor_id);
                }
            }
            if(!empty($doctor_ivr)){
            	$connection->autocommit(false);
                $query = "SELECT * FROM ivr_steps WHERE doctor_id = $doctor_id";
                $list = $connection->query($query);
                if (!$list->num_rows) {
                    $query = "SELECT * FROM ivr_steps WHERE doctor_id = 0 and thinapp_id = 0";
                    $list = $connection->query($query);
                    if ($list->num_rows) {
                        $result =array();

                        $master_step_list = mysqli_fetch_all($list,MYSQL_ASSOC);
                        foreach($master_step_list as $key => $master){
                            $doctor_code =$doctor_ivr['doctor_code'];
                            $sql = "INSERT INTO ivr_steps (thinapp_id, ivr_step_name, doctor_id) VALUES (?, ?, ?)";
                            $stmt = $connection->prepare($sql);
                            $stmt->bind_param('sss', $thin_app_id,$master['ivr_step_name'],$doctor_id);
                            if ($stmt->execute()) {
                                $master_step_id =  $stmt->insert_id;
                                if($master['ivr_step_name'] == 'SELECT_DOCTOR'){

                                    $query = "SELECT IFNULL(max(iss.ivr_sub_step_value),0) as last_doctor_number from ivr_sub_steps AS iss JOIN ivr_steps AS steps ON steps.ivr_steps_id = iss.ivr_step_id WHERE steps.ivr_step_name ='SELECT_DOCTOR' AND steps.thinapp_id = $thin_app_id";
                                    $list = $connection->query($query);
                                    if ($list->num_rows) {
                                        $time = "00:00:00";
                                        $status="ACTIVE";
                                        $last_number =  mysqli_fetch_assoc($list)['last_doctor_number'];
                                        $last_number = $last_number+1;
                                        $sql = "INSERT INTO ivr_sub_steps (ivr_step_id, ivr_sub_step_name, ivr_sub_step_value, from_time, to_time, thinapp_id, doctor_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                        $stmt_sub = $connection->prepare($sql);
                                        $stmt_sub->bind_param('ssssssss', $master_step_id, $doctor_code, $last_number, $time, $time, $thin_app_id, $doctor_id,$status );
                                        $result[] = $stmt_sub->execute();

                                    }

                                }else{
                                    $sub_step_list =Custom::get_ivr_master_sub_step_list($master['ivr_steps_id']);
                                    if(!empty($sub_step_list)){
                                        foreach($sub_step_list as $sub_key => $sub){
                                            $sql = "INSERT INTO ivr_sub_steps (ivr_step_id, ivr_sub_step_name, ivr_sub_step_value, from_time, to_time, thinapp_id, doctor_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                            $stmt_sub = $connection->prepare($sql);
                                            $stmt_sub->bind_param('ssssssss', $master_step_id, $sub['ivr_sub_step_name'], $sub['ivr_sub_step_value'], $sub['from_time'], $sub['to_time'], $thin_app_id, $doctor_id,$sub['status'] );
                                            $result[] = $stmt_sub->execute();
                                        }
                                    }
                                }

                            }else{
                                $result[] = false;
                            }
                        }

                        if(!empty($ivr_number)){
                            $sql = "UPDATE doctors_ivr SET ivr_number = ? where doctor_id = ?";
                            $stmt_number = $connection->prepare($sql);
                            $stmt_number->bind_param('ss', $ivr_number,$doctor_id);
                            $result[] = $stmt_number->execute();
                        }

                        if(!empty($result) && !in_array(false,$result)){
                            $connection->commit();
                        	if($return==true){
                                return true;
                            }
                            echo "Doctor IVR setup successfully\nDoctor Code Is : ".$doctor_ivr['doctor_code']  ;die;
                        }else{
                            echo "Unable to setup Doctor Ivr";die;
                        }

                    }else{
                        echo "Unable to find master step data"  ;die;
                    }


                }else{
                    echo "Doctor already setup ivr doctor code is :".$doctor_ivr['doctor_code']  ;die;
                }
            }else{
                echo "Unable to setup Doctor Ivr";die;
            }
        }else{
            echo "Doctor not register";die;
        }







    }

    public static function get_ivr_random_number(){
        start:
        $number = rand(10,9999);
        $query = "SELECT count(*) as total from doctors_ivr where doctor_code ='$number'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $total =  mysqli_fetch_assoc($list)['total'];
            if($total == 0){
                return $number;
            }else{
                goto start;
            }
        }

    }

    public static function get_ivr_master_sub_step_list($step_id){
        $query = "SELECT * FROM ivr_sub_steps WHERE ivr_step_id = $step_id";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }


    public static function appointment_day_list(){
        $query = "SELECT * FROM appointment_day_times WHERE status = 'ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }

    public static function tab_get_doctor_list($thin_app_id){
        $query = "SELECT id,name,sub_title AS degree, mobile,status,created,modified FROM appointment_staffs  WHERE thinapp_id = $thin_app_id AND status='ACTIVE' AND staff_type='DOCTOR'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }

    public static function tab_get_address_list($thin_app_id){
        $query = "SELECT * FROM appointment_addresses  WHERE thinapp_id = $thin_app_id AND status='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }

    public static function tab_get_patient_vital_list($thin_app_id){
        $query = "SELECT * FROM tab_patient_vitals  WHERE thinapp_id = $thin_app_id AND status='ACTIVE'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }
    public static function tab_get_patient_reminder_list($doctor_id){
        $query = "SELECT fur.id, IF(fur.appointment_customer_id > 0,fur.appointment_customer_id,fur.children_id) AS patient_id,  IF(fur.appointment_customer_id > 0,'CUSTOMER','CHILDREN')  as patient_type, fur.reminder_date, fur.reminder_message AS message, fur.created,fur.modified, fur.thinapp_id, fur.doctor_id FROM follow_up_reminders AS fur WHERE fur.`status` ='ACTIVE' AND fur.doctor_id = $doctor_id AND fur.add_via= 'TAB'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }

    public static function tab_get_sms_template_list($doctor_id){
        $query = "SELECT * FROM sms_templates AS fur WHERE status ='ACTIVE' AND doctor_id = $doctor_id";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }


    public static function tab_get_prescription_category_list(){
        $query = "SELECT * FROM tab_prescription_categories where thinapp_id = 0 and doctor_id = 0 ";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }

    public static function tab_get_prescription_step_list(){
        $query = "SELECT * FROM tab_master_prescription_steps ";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }


    public static function tab_get_prescription_step_tag_list($doctor_id){
        $query = "SELECT id,tab_prescription_step_id as tab_master_prescription_step_id,tag_name, status,composition, company_name, tag_notes as medicine_tags, tag_type as medicine_type, created, modified  FROM tab_prescription_tags WHERE doctor_id = $doctor_id ";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }


    public static function tab_get_prescription_template_list($doctor_id){
        $query = "SELECT * FROM tab_prescription_templates WHERE doctor_id = $doctor_id and status ='ACTIVE' and template_for ='TAB'";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }


    public static function tab_update_patient_vitals($thin_app_id,$patient_id, $patient_type,$vital_array){
        $height= @$vital_array['height'];
        $weight= @$vital_array['weight'];
        $head_circumference= @$vital_array['head_circumference'];
        $bp_systolic= @$vital_array['bp_systolic'];
        $bp_diasystolic= @$vital_array['bp_diasystolic'];
        $bmi= @$vital_array['bmi'];
        $bmi_status= @$vital_array['bmi_status'];
        $temperature= @$vital_array['temperature'];
        $o_saturation= @$vital_array['o_saturation'];
        if($patient_type=="CUSTOMER"){
            $sql = "UPDATE appointment_customers SET height = ?, weight=?, head_circumference=?, bp_systolic=?, bp_diasystolic=?, bmi=?, bmi_status=?, temperature=?, o_saturation=? where thinapp_id = ? and id = ?";
        }else{
            $sql = "UPDATE childrens SET height = ?, weight=?, head_circumference=?, bp_systolic=?, bp_diasystolic=?, bmi=?, bmi_status=?, temperature=?, o_saturation=? where thinapp_id = ? and id = ?";
        }
        $connection = ConnectionUtil::getConnection();
        $stmt = $connection->prepare($sql);
        $stmt->bind_param('sssssssssss', $height,$weight,$head_circumference,$bp_systolic,$bp_diasystolic,$bmi,$bmi_status,$temperature, $o_saturation, $thin_app_id, $patient_id);
        return $stmt->execute();
    }

    public static function get_all_appointment_list_by_date($date,$thin_app_id){

        $file_name = "update_patient_uhid_".$thin_app_id;
        $data_list = json_decode(WebservicesFunction::readJson($file_name,"NOT_TO_DELETE_CACHE"), true);
        $condition = "";
        if (!empty($data_list['patient_ids']) && $data_list['date'] == $date){
            $patient_ids =  '"'.implode('","', $data_list['patient_ids']).'"';
            $condition  = " AND acss.appointment_customer_id NOT IN($patient_ids)";
        }
        $query = "SELECT  ac.uhid, acss.has_token, acss.emergency_appointment, acss.queue_number,  IF(acss.payment_status='SUCCESS',IFNULL(hpt.NAME,'Cash'),'') AS payment_via, acss.payment_status, acss.id, IFNULL(mpo.total_amount,acss.amount) AS amount, ac.mobile as patient_mobile,  ac.id as patient_id, staff.name AS doctor_name, department.name AS doctor_department, ac.title, ac.first_name AS patient_name, ac.age as patient_age, ac.uhid as patient_uhid, ac.gender,ac.third_party_uhid as hospital_uhid, country.name AS country_name, state.NAME AS state_name, IFNULL(city.name,ac.city_name) AS city_name FROM appointment_customer_staff_services AS acss LEFT JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id LEFT JOIN appointment_categories AS department ON department.id= staff.appointment_category_id LEFT JOIN appointment_customers AS ac ON ac.id= acss.appointment_customer_id LEFT JOIN countries AS country ON country.id = ac.country_id LEFT JOIN states AS state ON state.id = ac.state_id LEFT JOIN cities AS city ON city.id= ac.city_id LEFT JOIN medical_product_orders AS mpo ON mpo.id = acss.medical_product_order_id LEFT JOIN hospital_payment_types AS hpt ON hpt.id = mpo.hospital_payment_type_id WHERE DATE(acss.appointment_datetime) = '$date' AND acss.thinapp_id = $thin_app_id and acss.appointment_customer_id > 0 and acss.payment_status ='SUCCESS'  AND acss.delete_status !='DELETED' $condition ORDER BY acss.id desc";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }



    public static function get_patient_category_list_drp($thin_app_id){
        return array(
            'CASH'=>'CASH',
            'CGHS'=>'CGHS',
            'CGHS 2014'=>'CGHS 2014',
            'ECGS'=>'ECGS',
            'FREE'=>'FREE',
            'GENERAL'=>'GENERAL',
            'ICICI PREDUNTIAL'=>'ICICI PREDUNTIAL',
            'JAIPUR DAIRY'=>'JAIPUR DAIRY',
            'MAX BUPA LIFE INSURANCE'=>'MAX BUPA LIFE INSURANCE',
            'MNIT'=>'MNIT',
            'POONAM'=>'POONAM',
            'RCDF'=>'RCDF',
            'RHB'=>'RHB',
            'RSANB'=>'RSANB',
            'RSMDC'=>'RSMDC',
            'RSMM'=>'RSMM',
            'TPA'=>'TPA',
        );
    }


    public static function update_paid_order_receipt_number_back($thin_app_id, $medical_product_order_id){

        $query = "SELECT COUNT(mpo_inner.id) as total FROM medical_product_orders AS mpo_inner join appointment_customer_staff_services as acss on acss.id = mpo_inner.appointment_customer_staff_service_id WHERE  DATE(mpo_inner.created) = DATE(NOW()) AND mpo_inner.is_opd ='Y' AND mpo_inner.paid_receipt_number !=''  and mpo_inner.thinapp_id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $connection->autocommit(true);
        $list = $connection->query($query);
        if ($list->num_rows) {
            $total =  (mysqli_fetch_assoc($list)['total'])+1;
            $sql  = "UPDATE medical_product_orders SET paid_receipt_number = ? where id = ? and payment_status ='PAID' and total_amount > 0";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ss', $total, $medical_product_order_id);
            return $stmt->execute();
        }
        return false;
    }

	public static function update_paid_order_receipt_number($thin_app_id, $medical_product_order_id){

        $medical_product_order_table = "medical_product_orders";    
        $appointment_customer_staff_services_table = "appointment_customer_staff_services";  
       
        $query = "SELECT COUNT(mpo_inner.id) as total FROM $medical_product_order_table AS mpo_inner join $appointment_customer_staff_services_table as acss on acss.id = mpo_inner.appointment_customer_staff_service_id WHERE  DATE(mpo_inner.created) = DATE(NOW()) AND mpo_inner.is_opd ='Y' AND mpo_inner.paid_receipt_number !=''  and mpo_inner.thinapp_id = $thin_app_id";
        $connection = ConnectionUtil::getConnection();
        $connection->autocommit(true);
        $list = $connection->query($query);
        if (!$list->num_rows) {
            $medical_product_order_table = "medical_product_orders_archive";
            $appointment_customer_staff_services_table = "appointment_customer_staff_services_archive";    
            $query = "SELECT COUNT(mpo_inner.id) as total FROM $medical_product_order_table AS mpo_inner join $appointment_customer_staff_services_table as acss on acss.id = mpo_inner.appointment_customer_staff_service_id WHERE  DATE(mpo_inner.created) = DATE(NOW()) AND mpo_inner.is_opd ='Y' AND mpo_inner.paid_receipt_number !=''  and mpo_inner.thinapp_id = $thin_app_id";
            $list = $connection->query($query);
        }    

        if ($list->num_rows) {
            $total =  (mysqli_fetch_assoc($list)['total'])+1;
            $sql  = "UPDATE $medical_product_order_table SET paid_receipt_number = ? where id = ? and payment_status ='PAID' and total_amount > 0";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ss', $total, $medical_product_order_id);
            return $stmt->execute();
        }
        return false;
    }

    public static function get_telemedicine_consent_data($thinappID){

        $sql = "SELECT `consent_title`,`consent_message`,`id` FROM `consent_templates` WHERE `for_telemedicine` = 'YES' AND `thinapp_id` = '".$thinappID."' AND `status` = 'ACTIVE' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($sql);
        if ($list->num_rows) {
            $data = mysqli_fetch_assoc($list);
            return $data;
        }
        else
        {
            return false;
        }

    }

    public static function add_telemedicine_consent($consent_template_id,$signature_image,$thin_app_id,$user_id,$mobile,$telemedicineLeadID){

        if(!empty($consent_template_id) && $consent_template_id){

            $app_admin_data = Custom::get_thinapp_admin_data($thin_app_id);
            $adminUserID = $app_admin_data['id'];
            $date = date("Y-m-d H:i:s");

            $consentTempSql = "SELECT `consent_title`,`consent_message` FROM `consent_templates` WHERE `id` = '".$consent_template_id."' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($consentTempSql);
            if ($list->num_rows) {

                $consentTempData = mysqli_fetch_assoc($list);
                $consent_title = $consentTempData['consent_title'];
                $consent_message = $consentTempData['consent_message'];

                $sql = "INSERT INTO `consents` SET `thinapp_id` = '".$thin_app_id."',`sender_user_id`='".$adminUserID."',`receiver_user_id`='".$user_id."',`consent_template_id`='".$consent_template_id."',`telemedicine_lead_id`='".$telemedicineLeadID."',`receiver_mobile`='".$mobile."',`consent_title`='".$consent_title."',`consent_message`='".$consent_message."',`signature_image`='".$signature_image."',`sent_time`='".$date."',`receiver_view_status`='SEEN',`view_time`='".$date."',`action_type`='AGREE',`action_from`='APP',`action_time`='".$date."',`created`='".$date."',`modified`='".$date."'";
                $connection->query($sql);
            }
        }

    }


    public static function dashboard_icon_array($label,$without_data_string=true){
        $icon_array =  array(
            'APPOINTMENT'=>'data:image/png;base64,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',
            'BLOG'=>'data:image/png;base64,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',
            'HEALTH_TIP'=>'data:image/png;base64,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',
            'MEDICAL_RECORD'=>'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFoAAABaCAYAAAA4qEECAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP+5c+rcEAAAOF0ftH+LC+zGoA7BoBt/qIl7gRoXgugdfeLZrIPQLUAoOnaV/Nw+H48PEWhkLnZ2eXk5NhKxEJbYcpXff5nwl/AV/1s+X48/Pf14L7iJIEyXYFHBPjgwsz0TKUcz5IJhGLc5o9H/LcL//wd0yLESWK5WCoU41EScY5EmozzMqUiiUKSKcUl0v9k4t8s+wM+3zUAsGo+AXuRLahdYwP2SycQWHTA4vcAAPK7b8HUKAgDgGiD4c93/+8//UegJQCAZkmScQAAXkQkLlTKsz/HCAAARKCBKrBBG/TBGCzABhzBBdzBC/xgNoRCJMTCQhBCCmSAHHJgKayCQiiGzbAdKmAv1EAdNMBRaIaTcA4uwlW4Dj1wD/phCJ7BKLyBCQRByAgTYSHaiAFiilgjjggXmYX4IcFIBBKLJCDJiBRRIkuRNUgxUopUIFVIHfI9cgI5h1xGupE7yAAygvyGvEcxlIGyUT3UDLVDuag3GoRGogvQZHQxmo8WoJvQcrQaPYw2oefQq2gP2o8+Q8cwwOgYBzPEbDAuxsNCsTgsCZNjy7EirAyrxhqwVqwDu4n1Y8+xdwQSgUXACTYEd0IgYR5BSFhMWE7YSKggHCQ0EdoJNwkDhFHCJyKTqEu0JroR+cQYYjIxh1hILCPWEo8TLxB7iEPENyQSiUMyJ7mQAkmxpFTSEtJG0m5SI+ksqZs0SBojk8naZGuyBzmULCAryIXkneTD5DPkG+Qh8lsKnWJAcaT4U+IoUspqShnlEOU05QZlmDJBVaOaUt2ooVQRNY9aQq2htlKvUYeoEzR1mjnNgxZJS6WtopXTGmgXaPdpr+h0uhHdlR5Ol9BX0svpR+iX6AP0dwwNhhWDx4hnKBmbGAcYZxl3GK+YTKYZ04sZx1QwNzHrmOeZD5lvVVgqtip8FZHKCpVKlSaVGyovVKmqpqreqgtV81XLVI+pXlN9rkZVM1PjqQnUlqtVqp1Q61MbU2epO6iHqmeob1Q/pH5Z/YkGWcNMw09DpFGgsV/jvMYgC2MZs3gsIWsNq4Z1gTXEJrHN2Xx2KruY/R27iz2qqaE5QzNKM1ezUvOUZj8H45hx+Jx0TgnnKKeX836K3hTvKeIpG6Y0TLkxZVxrqpaXllirSKtRq0frvTau7aedpr1Fu1n7gQ5Bx0onXCdHZ4/OBZ3nU9lT3acKpxZNPTr1ri6qa6UbobtEd79up+6Ynr5egJ5Mb6feeb3n+hx9L/1U/W36p/VHDFgGswwkBtsMzhg8xTVxbzwdL8fb8VFDXcNAQ6VhlWGX4YSRudE8o9VGjUYPjGnGXOMk423GbcajJgYmISZLTepN7ppSTbmmKaY7TDtMx83MzaLN1pk1mz0x1zLnm+eb15vft2BaeFostqi2uGVJsuRaplnutrxuhVo5WaVYVVpds0atna0l1rutu6cRp7lOk06rntZnw7Dxtsm2qbcZsOXYBtuutm22fWFnYhdnt8Wuw+6TvZN9un2N/T0HDYfZDqsdWh1+c7RyFDpWOt6azpzuP33F9JbpL2dYzxDP2DPjthPLKcRpnVOb00dnF2e5c4PziIuJS4LLLpc+Lpsbxt3IveRKdPVxXeF60vWdm7Obwu2o26/uNu5p7ofcn8w0nymeWTNz0MPIQ+BR5dE/C5+VMGvfrH5PQ0+BZ7XnIy9jL5FXrdewt6V3qvdh7xc+9j5yn+M+4zw33jLeWV/MN8C3yLfLT8Nvnl+F30N/I/9k/3r/0QCngCUBZwOJgUGBWwL7+Hp8Ib+OPzrbZfay2e1BjKC5QRVBj4KtguXBrSFoyOyQrSH355jOkc5pDoVQfujW0Adh5mGLw34MJ4WHhVeGP45wiFga0TGXNXfR3ENz30T6RJZE3ptnMU85ry1KNSo+qi5qPNo3ujS6P8YuZlnM1VidWElsSxw5LiquNm5svt/87fOH4p3iC+N7F5gvyF1weaHOwvSFpxapLhIsOpZATIhOOJTwQRAqqBaMJfITdyWOCnnCHcJnIi/RNtGI2ENcKh5O8kgqTXqS7JG8NXkkxTOlLOW5hCepkLxMDUzdmzqeFpp2IG0yPTq9MYOSkZBxQqohTZO2Z+pn5mZ2y6xlhbL+xW6Lty8elQfJa7OQrAVZLQq2QqboVFoo1yoHsmdlV2a/zYnKOZarnivN7cyzytuQN5zvn//tEsIS4ZK2pYZLVy0dWOa9rGo5sjxxedsK4xUFK4ZWBqw8uIq2Km3VT6vtV5eufr0mek1rgV7ByoLBtQFr6wtVCuWFfevc1+1dT1gvWd+1YfqGnRs+FYmKrhTbF5cVf9go3HjlG4dvyr+Z3JS0qavEuWTPZtJm6ebeLZ5bDpaql+aXDm4N2dq0Dd9WtO319kXbL5fNKNu7g7ZDuaO/PLi8ZafJzs07P1SkVPRU+lQ27tLdtWHX+G7R7ht7vPY07NXbW7z3/T7JvttVAVVN1WbVZftJ+7P3P66Jqun4lvttXa1ObXHtxwPSA/0HIw6217nU1R3SPVRSj9Yr60cOxx++/p3vdy0NNg1VjZzG4iNwRHnk6fcJ3/ceDTradox7rOEH0x92HWcdL2pCmvKaRptTmvtbYlu6T8w+0dbq3nr8R9sfD5w0PFl5SvNUyWna6YLTk2fyz4ydlZ19fi753GDborZ752PO32oPb++6EHTh0kX/i+c7vDvOXPK4dPKy2+UTV7hXmq86X23qdOo8/pPTT8e7nLuarrlca7nuer21e2b36RueN87d9L158Rb/1tWeOT3dvfN6b/fF9/XfFt1+cif9zsu72Xcn7q28T7xf9EDtQdlD3YfVP1v+3Njv3H9qwHeg89HcR/cGhYPP/pH1jw9DBY+Zj8uGDYbrnjg+OTniP3L96fynQ89kzyaeF/6i/suuFxYvfvjV69fO0ZjRoZfyl5O/bXyl/erA6xmv28bCxh6+yXgzMV70VvvtwXfcdx3vo98PT+R8IH8o/2j5sfVT0Kf7kxmTk/8EA5jz/GMzLdsAAAAgY0hSTQAAeiUAAICDAAD5/wAAgOkAAHUwAADqYAAAOpgAABdvkl/FRgAAA5ZJREFUeNrsnctLVFEcxz+TQguj18IeEFTgUBAmlhW0aFGBRkG2idAWBdFrUa0igv6Agh7Q+0GkRhZIEZQS1aqMCouKikgypIiojB6WZNO0uFeQmTPjzNx7z4z39/suf3OO98yH3/2ex++okXg8jip4jVAEClpBqxS0glbQKgWtoFUKWkEraJWCVtAqBa2gFbRKQYdHxRm0WQJsBqqAEuAf4KUsEwGKgFagTgroyBClrO3AgQCfPwt4Lh30AuBewM+/5b4xoj16t4XnLwai0kHPtzSGfdKtowcYZ2kcUeC11IzutziO/bqOtqPlQJmCtqODUj36I1BqeTzLgC53U5M3Ju6m7B3wM6ygC0lf3XX+Dhe6gg5Y34AK4G1YPLpQNQZoCdNkWMiqBOZ6+QHFeRz8G9ee+vM8+WXKaeRwAt0GNAN3gU5Jr4Qt0O3ATuCOVO+x4dF7gYWSIdvI6I3ASZ1LgwW9ZwjIVcD0PE/IfigOfAZu5GPD8giYk+Kzene3VRmypH0BrAJe2QRdDjwzxJsId0H2KTDblnW0p4B8GVgZcisut7nqOGWI1QmADNBny6P/AKNIrs50AVMFgL4JLLWR0S0GyFEhkAEu2LKOQ4bYViGQ+0hzyucn6PfAfUN8vRDQV3DOrgMHfd4Qq3U9W4LO2DrrOGaIbRACucedCAMH/ZjkUs9EoFoI6MahGvgF+rAhtobCP9AP8m32fR0dx7k3/Tsh3hHC8wyTOsng8o8fGd1sgBwVAnng+2MD9GlDbBNydMIG6G7gtiG+WgjkDjK8XOMVdIMhVg1MFgL6aKYNvYJuMsS2CLKNizZAd5BcTRgNrBAC+RLQawO0aRJYKyibG7JpnOs6+i8wwd16DtZLYIYAyJ+ASUAs6Iy+aoBcJgTywNo5lk2HXEEfF24bzdl2SGcdX4DxKV6bUg9b9uGuD7ksX9NldKoDIVO5ZhFyLq3ndPMqHegnWTxomyDbaMylUzrrqAGuG+BXJMRKcEo4RQIgPwTm+Z3RrSQXW88a2q0TAhngXK4dIxn8RfR6YBdOxWQa8D3h8wc4FxbDrpi78/0VFOjBFpG45ZyJc7lPgtpcO8Vv60hUbwrbkKIjXjpHPP4zhW5gigDIP4CxOL9RG3hGJ6pWCGSAa14gg7druzU4N91jIYccJ4Mqd9DWobJgHSoFraAVtEpBK2iVglbQClqloBW0SkEraAWtUtAKWqWgC0z/BwCwLccY9kEv+gAAAABJRU5ErkJggg==',
            'REMINDER'=>'data:image/png;base64,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',
            'PAYMENT'=>'data:image/png;base64,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',
            'SETTING'=>'data:image/png;base64,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',
            'CHAT'=>'data:image/png;base64,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',
        );
        if($without_data_string===true){
            $tmp = explode(',',@$icon_array[$label]);
            return @end($tmp);
        }else{
            return @$icon_array[$label];
        }

    }



    public static function update_page_type_id($cms_id,$dashboard_icon_url){
        $query = "SELECT * FROM dashboard_icons where url = '$dashboard_icon_url' ";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $page_category_id =  mysqli_fetch_assoc($list)['id'];
            $sql = "UPDATE cms_pages SET page_category_id = ? where id = ?";
            $connection = ConnectionUtil::getConnection();
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ss', $page_category_id,$cms_id);
            return ($stmt->execute())?$page_category_id:0;
        }
        return 0;

    }


    public static function get_customer_firebase_token_by_id($thin_app_id,$customer_id,$customer_type){
        if($customer_type='CHILDREN'){
            $query = "SELECT u.firebase_token FROM childrens AS c JOIN users AS u ON u.id = c.user_id WHERE c.thinapp_id = $thin_app_id and  c.id = $customer_id";
        }else{
            $query = "SELECT u.firebase_token FROM appointment_customers AS ac JOIN users AS u ON u.id = ac.user_id WHERE ac.thinapp_id = $thin_app_id and  ac.id = $customer_id";
        }
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return  mysqli_fetch_assoc($list)['firebase_token'];
        }
        return "";
    }

    public static function tab_get_category_data($doctor_id,$category_id){
        $query = "SELECT * from tab_prescription_categories AS tpc WHERE tpc.doctor_id = $doctor_id AND tpc.tab_prescription_category_id = $category_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return  mysqli_fetch_assoc($list);
        }
        return "";
    }


    public static function get_patient_appointment_booking_data($doctor_id, $address_id, $customer_id){
        $query = "SELECT acss.* FROM appointment_customer_staff_services AS acss WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_address_id = $address_id AND acss.appointment_customer_id = $customer_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status IN('NEW','CONFIRM','RESCHEDULE') LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return  mysqli_fetch_assoc($list);
        }
        return false;
    }


    public static function get_tmp_appointment_data($appointment_array){
        if (!empty($appointment_array)){
            $count = count($appointment_array);
            $ids = '"'.implode('","', $appointment_array).'"';
            $query = "SELECT acss.id, IFNULL(ac.gender,child.gender) AS gender, IFNULL(ac.age,'') AS age, staff.name AS doctor_name, c.name AS city, s.name AS state, cat.name AS department, cat.name AS department, IFNULL(hpt.name,'CASH') AS booking_via FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id LEFT JOIN hospital_payment_types AS hpt ON hpt.id = acss.hospital_payment_type_id LEFT JOIN appointment_categories AS cat ON cat.id = staff.appointment_category_id LEFT JOIN appointment_customers AS ac ON ac.id = acss.appointment_customer_id LEFT JOIN childrens AS child on child.id = acss.children_id LEFT JOIN states AS s ON (s.id = ac.state_id OR s.id  = child.state_id)  LEFT JOIN cities AS c ON (c.id = ac.city_id OR c.id = child.city_id) where acss.id IN ($ids) LIMIT $count";
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $list =  mysqli_fetch_all($list,MYSQL_ASSOC);
                $final=array();
                foreach($list as $key =>$value){
                    $final[$value['id']] =$value;
                }
                return $final;
            }
        }
        return false;
    }

    public static function reset_child_data_on_dob_update($children_id,$thin_app_id,$dob){
        $child_data = Custom::get_child_by_id($children_id);
        if(!empty($child_data)){
            $connection = ConnectionUtil::getConnection();
            if($child_data['thinapp_id']==$thin_app_id){
                $sql = "delete from child_vaccinations where children_id =? and thinapp_id=?";
                $c_vac = $connection->prepare($sql);
                $c_vac->bind_param('ss', $children_id, $thin_app_id);

                $sql = "delete from child_growths where children_id =?";
                $c_growth = $connection->prepare($sql);
                $c_growth->bind_param('s', $children_id);

                $sql = "delete from child_timelines where children_id =? and thinapp_id=?";
                $c_time = $connection->prepare($sql);
                $c_time->bind_param('ss', $children_id, $thin_app_id);

                $status = 'YES';
                $sql = "UPDATE childrens set dob_editable = ? where id =? and thinapp_id=?";
                $c_update = $connection->prepare($sql);
                $c_update->bind_param('sss', $status, $children_id, $thin_app_id);


                if($c_vac->execute() && $c_growth->execute() && $c_time->execute() && $c_update->execute()){
                    return Custom::clone_child_vaccination($connection, $thin_app_id, $children_id, $dob);
                }
            }
        }
        return false;
    }

    public static function get_check_in_last_token($doctor_id, $check_in_type){
        $query = "SELECT  acss.show_after_queue FROM appointment_customer_staff_services AS acss where acss.status IN ('NEW','CONFIRM','RESCHEDULE') AND acss.skip_tracker = 'NO' and acss.appointment_staff_id =$doctor_id and DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.patient_queue_type='$check_in_type' AND acss.patient_queue_checked_in ='YES' ORDER BY CAST(acss.show_after_queue AS DECIMAL(10,2)) desc LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return  mysqli_fetch_assoc($list)['show_after_queue'];
        }
        return false;
    }

    public static function get_live_tracker_data($thin_app_id,$booking_date,$doctor_id,$address_id,$service_id,$user_role){
        $tracker_assign = Custom::getThinAppData($thin_app_id)['smart_clinic_tracker_queue'];
        $order_key = ($tracker_assign=="MANUAL_ASSIGN")?"DESC":"ASC";
        $order = " ORDER BY CASE WHEN acss.patient_queue_type='NONE' THEN CAST(acss.queue_number  AS DECIMAL(10,2)) WHEN acss.patient_queue_type <> 'NONE' THEN CAST(acss.show_after_queue AS DECIMAL(10,2)) END ASC, acss.queue_assign_type, acss.queue_check_in_datetime $order_key";
    	//$order = " ORDER BY CASE WHEN acss.patient_queue_type='NONE' THEN acss.appointment_datetime WHEN acss.patient_queue_type <> 'NONE' THEN CAST(acss.show_after_queue AS DECIMAL(10,2)) END ASC, acss.queue_assign_type, acss.queue_check_in_datetime $order_key";
        
        
        $query = "select acss.booking_validity_attempt, acss.amount, acss.consulting_type, acss.is_paid_booking_convenience_fee, acss.send_to_lab_datetime, t.smart_clinic_tracker_queue, acss.appointment_staff_id, IFNULL(hpt.name,'CASH') AS booking_payment_type, acss.id, acss.payment_status, acss.slot_time as time, IFNULL(ac.first_name, c.child_name) as name, IFNULL(ac.mobile, c.mobile) as mobile, acss.emergency_appointment, acss.status, acss.patient_queue_checked_in, acss.patient_queue_type,acss.show_after_queue, IFNULL(ac.third_party_uhid, c.third_party_uhid) as third_party_uhid, acss.checked_in, acss.emergency_appointment, IF( (SELECT pda.id FROM patient_due_amounts AS pda WHERE acss.medical_product_order_id = pda.medical_product_order_id AND pda.settlement_by_order_id != acss.medical_product_order_id and pda.settlement_by_order_id > 0 limit 1) IS NOT NULL,'YES','NO') as due_amount_settled,( SELECT SUM(mpo.total_amount) FROM medical_product_orders AS mpo WHERE mpo.id = acss.medical_product_order_id ) as total_billing, acss.has_token, acss.custom_token, '' as address, acss.id as appointment_id, IF(ac.id IS NOT NULL,'CUSTOMER','CHILDREN') as customer_type, IFNULL(ac.id,c.id) as customer_id, acss.slot_time, CONCAT(IFNULL(ac.title,c.title),' ',IFNULL(ac.first_name,c.child_name)) as name,IFNULL(ac.uhid,c.uhid) as uhid, IFNULL(ac.mobile,c.mobile) as mobile, IFNULL(ac.profile_photo,c.image) as profile_photo, acss.emergency_appointment, acss.queue_number, app_ser.service_amount, acss.sub_token, acss.skip_tracker, acss.has_token from appointment_customer_staff_services as acss join thinapps as t on t.id = acss.thinapp_id left join appointment_customers as ac on ac.id =acss.appointment_customer_id left join childrens as c on c.id = acss.children_id left join appointment_services as app_ser on app_ser.id = acss.appointment_service_id LEFT JOIN hospital_payment_types AS hpt ON hpt.id = acss.hospital_payment_type_id left join medical_product_orders as mpo on mpo.id = acss.medical_product_order_id where acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $doctor_id and acss.appointment_service_id = $service_id and acss.appointment_address_id = $address_id and  ( acss.status IN ('NEW','CONFIRM','RESCHEDULE') OR ( acss.status ='REFUND' AND mpo.total_amount > 0 )) and acss.delete_status != 'DELETED' and ( ( acss.payment_status = 'SUCCESS' AND acss.booking_validity_attempt = 1 ) OR acss.checked_in ='YES' ) and DATE(acss.appointment_datetime) = '$booking_date' $order";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        $final_array=array();
        if ($list->num_rows) {
            return mysqli_fetch_all($list,MYSQL_ASSOC);
        }
        return false;
    }

    public static function is_token_is_alive($booking_date, $doctor_id, $service_id, $address_id,$queue_number,$send_time_queue_number){
        $query = "SELECT  id FROM appointment_customer_staff_services AS acss where acss.status IN ('NEW','CONFIRM','RESCHEDULE') AND acss.skip_tracker = 'NO' and acss.appointment_staff_id =$doctor_id and acss.appointment_service_id = $service_id and acss.appointment_address_id =$address_id and DATE(acss.appointment_datetime) = '$booking_date' AND acss.patient_queue_type != 'LAB_TEST' AND acss.queue_number = '$queue_number' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return true;
        }else{
            $query = "SELECT  DATE_SUB(queue_check_in_datetime, INTERVAL 1 SECOND) as less_date_time  FROM appointment_customer_staff_services AS acss where acss.status IN ('NEW','CONFIRM','RESCHEDULE') AND acss.skip_tracker = 'NO' and acss.appointment_staff_id =$doctor_id and acss.appointment_service_id = $service_id and acss.appointment_address_id =$address_id and DATE(acss.appointment_datetime) = '$booking_date' AND acss.patient_queue_type != 'LAB_TEST' AND acss.queue_number = '$send_time_queue_number' LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if ($list->num_rows) {
                return mysqli_fetch_assoc($list)['less_date_time'];
            }
        }
        return false;
    }


    public static function get_booking_convenience_fee_details($appointmentID){

        $sql = "SELECT `appointment_booked_from`,`is_paid_booking_convenience_fee`,`booking_convenience_fee` FROM `appointment_customer_staff_services` WHERE `id` = '".$appointmentID."' LIMIT 1";

        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($sql);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list);
        }
        return false;


    }

    public static function create_appointment_button_array($thin_app_id,$user_role,$appointment,$request_type=''){

        $skip_appointment = $appointment['skip_tracker'];
        $queue_type_status=$appointment['patient_queue_type'];
        $check_in_status = $appointment['patient_queue_checked_in'];
        $emergency_appointment =$appointment['emergency_appointment'];
        $appointment_status =@$appointment['status'];
        if(!isset($appointment['app_status'])){
            $appointment['app_status']=$appointment_status;
        }

        if(!isset($appointment['appointment_staff_id'])){
            $appointment['appointment_staff_id']=$appointment['doctor_id'];
        }

        $smart_clinic =Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC');
        $doctor_data =Custom::get_doctor_by_id($appointment['appointment_staff_id'],$thin_app_id);

        $return['SKIP'] = "NO";
        $return['LATE_CHECKIN'] = "NO";
        $return['REPORT_CHECKIN'] = "NO";
        $return['EMERGENCY_CHECKIN'] = "NO";
        $return['OTHER_CHECKIN'] = "NO";
        $return['EARLY_CHECKIN'] = "NO";
        $return['LAB_TEST'] = "NO";
    	$chat =Custom::check_app_enable_permission($thin_app_id, 'CHAT');
        $return['CHAT'] = 'NO';
        $return['VIDEO'] ='NO';
        $return['AUDIO'] ='NO';
        $return['WHATS_APP_VIDEO'] ='NO';
        if ($appointment['app_status'] == 'NEW' || $appointment['app_status'] == 'CONFIRM' || $appointment['app_status'] == 'RESCHEDULE') {
            $return['cancel'] ="YES";
        	if($appointment['payment_status'] == "SUCCESS"){
                $return['CHAT'] = ($appointment['consulting_type']=='CHAT')?'YES':'NO';;
                $return['VIDEO'] =($appointment['consulting_type']=='VIDEO')?'YES':'NO';
                $return['AUDIO'] =($appointment['consulting_type']=='AUDIO')?'YES':'NO';
            	if($appointment['consulting_type']=='VIDEO' && $appointment['appointment_booked_from']=='DOCTOR_PAGE'){
                    $return['WHATS_APP_VIDEO'] ='YES';
                }else{
                    if($appointment['consulting_type']=='VIDEO'){
                        $return['WHATS_APP_VIDEO'] =$doctor_data['allow_whats_app_video_call'];
                    }
                }
            }
            $return['reschedule'] =($appointment['custom_token']=='NO')?"YES":"NO";
        } else {
            $return['cancel'] ="NO";
            $return['reschedule'] ="NO";
        }
        $return['pay'] = "NO";
        if($doctor_data['payment_mode'] == "BOTH" || $doctor_data['payment_mode']== "ONLINE" || $doctor_data['payment_mode']== "CASH" ){
            if($appointment['payment_status'] == "PENDING" || $appointment['payment_status'] == "FAILURE") {
                if ($appointment['app_status'] == 'EXPIRED' || $appointment['app_status'] == 'NEW' || $appointment['app_status'] == 'CONFIRM' || $appointment['app_status'] == 'RESCHEDULE') {
                    $return['pay'] = "YES";
                }
            }
        }
        $return['patient_queue_type']  = $appointment['patient_queue_type'];
        $return['close'] = !in_array($appointment['app_status'],array("CLOSED","CANCELED","REFUND"))?"YES":"NO";
        if ($request_type=="TRACKER") {
            $return['cancel'] ="NO";
            $return['reschedule'] ="NO";
        }
        if($user_role !='USER'){
            $return['LAB_TEST'] = ($appointment['patient_queue_type']!='LAB_TEST')?"YES":'NO';

            if($user_role=="RECEPTIONIST"){
                $return['OTHER_CHECKIN'] = "YES";
                $return['EARLY_CHECKIN'] = "YES";
            }

            if($user_role=="RECEPTIONIST" && $check_in_status == "NO"){
                $return['REPORT_CHECKIN'] = "YES";
                $return['EMERGENCY_CHECKIN'] = ($appointment['emergency_appointment']=='YES')?'YES':'NO';
            }

            if($skip_appointment=="YES" && $user_role=="RECEPTIONIST"){
                $return['LATE_CHECKIN'] = "YES";
            }
            if($skip_appointment=="NO"){
                $return['SKIP'] = "YES";
            }
        }

        return $return;
    }

    public static function get_auto_assign_token($doctor_id,$thin_app_id,$check_in_type){

        $lab_report_patient_queue_after_number = $emergency_appointment_queue_after_number = $late_check_in_queue_after_number = $other_check_in_queue_after_number= $early_check_in_queue_after_number= 3;
        $doctor = Custom::get_doctor_by_id($doctor_id,$thin_app_id);
        $list = Custom::getDoctorWebTrackerDataUpcomingList(array($doctor_id),$thin_app_id,9999);
        $token_array = array();
        $current_token_value = !empty($list[$doctor_id]['show_after_queue'])?$list[$doctor_id]['show_after_queue']:$list[$doctor_id]['current_token']; ;
        if(!empty($list[$doctor_id]['upcoming'])){
            foreach($list[$doctor_id]['upcoming'] as $key =>$val){
                $token_array[] =  !empty($val['show_after_queue'])?$val['show_after_queue']:$val['queue_number'];
            }
        }
        array_unshift($token_array , $current_token_value);
        if(!empty($doctor['lab_report_patient_queue_after_number'])){
            $lab_report_patient_queue_after_number = $doctor['lab_report_patient_queue_after_number'];
        }if(!empty($doctor['emergency_appointment_queue_after_number'])){
            $emergency_appointment_queue_after_number = $doctor['emergency_appointment_queue_after_number'];
        }if(!empty($doctor['late_check_in_queue_after_number'])){
            $late_check_in_queue_after_number = $doctor['late_check_in_queue_after_number'];
        }if(!empty($doctor['other_check_in_queue_after_number'])){
            $other_check_in_queue_after_number = $doctor['other_check_in_queue_after_number'];
        }if(!empty($doctor['early_check_in_queue_after_number'])){
            $early_check_in_queue_after_number = $doctor['early_check_in_queue_after_number'];
        }

        $early_check_in_queue_after_number=0;
        $last_available = Custom::get_check_in_last_token($doctor_id, $check_in_type);
        if(!empty($last_available)){
            $last_available_index = $key = array_search($last_available, array_reverse($token_array,true));
            if($check_in_type=="REPORT_CHECKIN"){
                $show_after_queue = @$token_array[$last_available_index+$lab_report_patient_queue_after_number];
            }else if($check_in_type=="LATE_CHECKIN"){
                $show_after_queue = @$token_array[$last_available_index+$late_check_in_queue_after_number];
            }else if($check_in_type=="EMERGENCY_CHECKIN"){
                $show_after_queue = @$token_array[$last_available_index+$emergency_appointment_queue_after_number];
            }else if($check_in_type=="OTHER_CHECKIN"){
                $show_after_queue = @$token_array[$last_available_index+$other_check_in_queue_after_number];
            }else if($check_in_type=="EARLY_CHECKIN"){
                //$show_after_queue = @$token_array[$last_available_index+$early_check_in_queue_after_number];
                $show_after_queue = @$token_array[$early_check_in_queue_after_number];
            }
            $show_after_queue  = empty($show_after_queue)?end($token_array):$show_after_queue;
        }else{
            if($check_in_type=="REPORT_CHECKIN"){
                $show_after_queue = @$token_array[$lab_report_patient_queue_after_number];
            }else if($check_in_type=="LATE_CHECKIN"){
                $show_after_queue = @$token_array[$late_check_in_queue_after_number];
            }else if($check_in_type=="EMERGENCY_CHECKIN"){
                $show_after_queue = @$token_array[$emergency_appointment_queue_after_number];
            }else if($check_in_type=="OTHER_CHECKIN"){
                $show_after_queue = @$token_array[$other_check_in_queue_after_number];
            }else if($check_in_type=="EARLY_CHECKIN"){
                $show_after_queue = @$token_array[$early_check_in_queue_after_number];
            }
        }


        if(empty($show_after_queue)){
            return !empty(end($token_array))?end($token_array):0;
        }else{
            return (!empty(end($token_array)) && end($token_array) < $show_after_queue)?end($token_array):$show_after_queue;
        }

    }

    public static function skip_late_payment_appointment($appointment_id,$user_id){

        $sql = "SELECT acss.booked_by, u.id AS user_id, acss.thinapp_id, u.mobile, acss.sub_token,acss.emergency_appointment,acss.appointment_datetime,t.smart_clinic_tracker_queue FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id JOIN users AS u ON u.id = $user_id WHERE acss.id = $appointment_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($sql);
        if ($list->num_rows) {
            $appointment = mysqli_fetch_assoc($list);
            if(Custom::check_app_enable_permission($appointment['thinapp_id'], 'SMART_CLINIC') && $appointment['emergency_appointment'] =='NO' && $appointment['sub_token'] == 'NO' && strtotime("+5 MINUTES",strtotime($appointment['appointment_datetime'])) < strtotime(date('Y-m-d H:i')) ){
                if($appointment['smart_clinic_tracker_queue']=="AUTO_ASSIGN"){
                    $post = array();
                    $post['app_key'] = APP_KEY;
                    $post['user_id'] = $appointment['user_id'];
                    $post['thin_app_id'] = $appointment['thinapp_id'];
                    $post['mobile'] = $appointment['mobile'];
                    $post['appointment_id'] = $appointment_id;
                    $post['check_in_type'] = "LATE_CHECKIN";
                    $result = json_decode(WebServicesFunction_2_3::check_in_patient($post), true);
                    if($result['status']==1){
                        return true;
                    }
                }else{
                if($appointment['booked_by']=='USER'){
                    $skip_tracker ="YES";
                    $sql = "UPDATE appointment_customer_staff_services SET skip_tracker = ? where id = ?";
                    $connection = ConnectionUtil::getConnection();
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ss', $skip_tracker,$appointment_id);
                    return $stmt->execute();
                }
                }
            }

        }
        return false;
    }



    public static function tracker_labels($check_in_type,$sub_token)
    {
        if($check_in_type=="REPORT_CHECKIN"){
            return "Report";
        }else if($check_in_type=="LATE_CHECKIN"){
            return "NOT";
        }else if($check_in_type=="EARLY_CHECKIN" || $check_in_type=="OTHER_CHECKIN" || $check_in_type=="EMERGENCY_CHECKIN" || $sub_token=="YES"){
            return "Emergency";
        }
        return  "";
    }

    public static function get_waiting_time($thin_app_id,$doctor_id,$address_id,$service_id,$token_number,$service_minutes){
        if(Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC')){

            if(strpos("HOUR",$service_minutes)){
                $service_minutes = (int)$service_minutes * 60;
            }else{
                $service_minutes = (int)$service_minutes;
            }
            $total_minutes_diffrence = $auto_assign_appointment_count = 0;
            $query = "SELECT (TIMESTAMPDIFF(MINUTE, DATE_ADD(IF(acss.patient_queue_type='NONE', acss.appointment_datetime,acss.queue_check_in_datetime) ,interval $service_minutes MINUTE), acss.modified)) AS close_diffrence FROM appointment_customer_staff_services AS acss  WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_service_id = $service_id AND acss.appointment_address_id =$address_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND ( (acss.`status` ='CLOSED' and acss.patient_queue_type NOT IN('EMERGENCY_CHECKIN','LATE_CHECKING','REPORT_CHECKIN','EARLY_CHECKIN','OTHER_CHECKIN') ) OR( acss.patient_queue_type ='LAB_TEST' and acss.queue_check_in_datetime = '0000-00-00 00:00:00') OR (acss.skip_tracker ='YES' and acss.patient_queue_type='NONE' and acss.emergency_appointment='NO' and acss.sub_token ='NO')) ORDER BY acss.modified DESC LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $list = $connection->query($query);
            if ($list->num_rows) {
                $total_minutes_diffrence =  mysqli_fetch_assoc($list)['close_diffrence'];
            }
            /* $tracker_data = Custom::getDoctorWebTrackerDataUpcomingList(array($doctor_id),$thin_app_id,999);
             if(!empty($tracker_data[$doctor_id]['upcoming']) && !in_array($tracker_data[$doctor_id]['current_token'],array('N/A',$token_number))){
                 foreach($tracker_data[$doctor_id]['upcoming'] as $key => $upcoming){
                     if($upcoming['patient_queue_type'] !='NONE' && $upcoming['queue_number'] != $token_number ){
                         $auto_assign_appointment_count++;
                     }else if($upcoming['queue_number'] == $token_number){
                         break;
                     }
                 }
             }*/
            return ($total_minutes_diffrence)+($auto_assign_appointment_count * $service_minutes);
        }
        return 0;
    }





   
	public static function create_custom_sms_from_template($appointment_id,$sms_for){
         $sql = "SELECT psn.payment_sequence_number, bcfd.id AS convenience_id, acss.drive_folder_id, IFNULL(ac.mobile,c.mobile) as patient_mobile,  acss.medical_product_order_id, acss.appointment_staff_id,acss.appointment_service_id,acss.appointment_address_id, acss.custom_token, staff.show_appointment_time,staff.show_appointment_token, acss.thinapp_id, IFNULL(ac.uhid,c.uhid) as uhid, acss.reminder_message, reminder_date,  acss.cancel_reason, t.apk_url, acss.emergency_appointment, acss.sub_token, acss.has_token, acss.appointment_datetime,acss.queue_number, IFNULL(ac.first_name,c.child_name) AS patient_name, staff.name AS doctor_name, ast.appointment_booking_sms, ast.appointment_cancel_sms, ast.appointment_reschedule_sms, ast.appointment_reminder_sms FROM appointment_customer_staff_services AS acss JOIN thinapps as t on t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id =acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id left JOIN app_sms_templates AS ast ON acss.thinapp_id = ast.thinapp_id  LEFT JOIN booking_convenience_fee_details AS bcfd ON bcfd.appointment_customer_staff_service_id = acss.id LEFT JOIN payment_sequence_number AS psn ON psn.appointment_id = acss.id WHERE acss.id = $appointment_id LIMIT 1 ";
        
       
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($sql);
        $sms = false;
        if ($list->num_rows) {
            $data =  mysqli_fetch_assoc($list);
            $token_number = Custom::create_queue_number($data);
            $patient_name = Custom::get_string_first_name($data['patient_name']);
            $doctor_name = Custom::get_doctor_first_name($data['doctor_name']);
            $thin_app_id = $data['thinapp_id'];
        	$doctor_id = $data['appointment_staff_id'];
            if(CK_BIRLA_APP_ID==$thin_app_id){
                $token_number = Custom::get_ck_birla_token($doctor_id,$token_number);
            }
        
        	$address_id = $data['appointment_address_id'];
            $service_id = $data['appointment_service_id'];
            $order_id = $data['medical_product_order_id'];
        	$convenience_id = $data['convenience_id'];
            $drive_folder_id = $data['drive_folder_id'];
            $patient_mobile = $data['patient_mobile'];
            $uhid = $data['uhid'];

            $label_date = date('Y-m-d', strtotime($data['appointment_datetime']));
            $day_label = Custom::get_date_label($label_date);
            $reminder_date = $reminder_day= $payment_receipt_string= "";
            $smart_clinic =Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC');

            if(!empty($data['reminder_date'])){
                $reminder_date = date('d-m-Y',strtotime($data['reminder_date']));
                $label_date = date('Y-m-d', strtotime($data['reminder_date']));
                $reminder_day = Custom::get_date_label($label_date);
            }
            $token_string = !empty($data['show_appointment_token']=='YES')?"\nToken-[TOKEN_NUMBER]":"";
            $time_string = ($data['show_appointment_time']=='YES' && $data['emergency_appointment']=="NO" && $data['custom_token']=="NO")?"\nApprox Time-[APPOINTMENT_TIME]":"";
            $live_tracker_string = ($smart_clinic)?"\nLive Token Status\n[LIVE_TRACKER_URL]":"";
            $payment_receipt_string = (!empty($convenience_id) || !empty($order_id))?"\nPayment Receipt\n[PAYMENT_RECEIPT_URL]":"";
            $medical_record = "\nUpload Medical Record\n[MEDICAL_RECORD_URL]";
			$payment_queue = $data['payment_sequence_number'];
            $payment_queue_number = (!empty($payment_queue)) ? "\nSerial Number-$payment_queue" : "";
            $reason = !empty($data['cancel_reason'])?"\nReason-[CANCEL_REASON]":"";

            if($sms_for=="BOOKING"){
                $sms = "CONFIRMED$token_string, Date-[APPOINTMENT_DATE]".$time_string.$payment_queue_number.$live_tracker_string.$medical_record.$payment_receipt_string;
            $sms = "WELCOME TO [DOCTOR_NAME]\n\nService - OPD\nToken-[TOKEN_NUMBER], Date-[APPOINTMENT_DATE]\nLive Token Status\n[LIVE_TRACKER_URL]\nBy mPass";
                if($thin_app_id=="821"){
                    $sms = "CONFIRMED\nToken-[TOKEN_NUMBER], Date-[APPOINTMENT_DATE]\nLive Token Status\n[LIVE_TRACKER_URL]";
                }
            
            	//$sms = !empty($data['appointment_booking_sms'])?$data['appointment_booking_sms']:$sms;
            }else if($sms_for=="CANCEL"){
                //$sms = "CANCELLED\nDoctor-[DOCTOR_NAME]$token_string\nDate-[APPOINTMENT_DATE]$reason\nDownload App [DOWNLOAD_URL]";
                $sms = "CANCELLED $token_string Date-[APPOINTMENT_DATE]$reason\nDownload App [DOWNLOAD_URL]\n- Sent by MEngage";
                //$sms = !empty($data['appointment_cancel_sms'])?$data['appointment_cancel_sms']:$sms;
            }else if($sms_for=="RESCHEDULE"){
                //$sms = "RESCHEDULED\nDoctor-[DOCTOR_NAME]$token_string\nDate-[APPOINTMENT_DATE]".$time_string.$live_tracker_string."\nDownload App [DOWNLOAD_URL]";
                $sms = "RESCHEDULED $token_string Date-[APPOINTMENT_DATE]".$time_string.$live_tracker_string."\nDownload App [DOWNLOAD_URL]";
               // $sms = !empty($data['appointment_reschedule_sms'])?$data['appointment_reschedule_sms']:$sms;
            }else if($sms_for=="REMINDER"){
                $sms = "REMINDER!\nDoctor-[DOCTOR_NAME]$token_string\nDate-[APPOINTMENT_DATE]$time_string\nDownload App [DOWNLOAD_URL]";
                //$sms = !empty($data['appointment_reminder_sms'])?$data['appointment_reminder_sms']:$sms;
            }
            if(!empty($sms)){

                $sms = str_replace("[TOKEN_NUMBER]",$token_number,$sms);
                $sms = str_replace("[DOCTOR_NAME]",$doctor_name,$sms);
                $sms = str_replace("[PATIENT_NAME]",$patient_name,$sms);
                $sms = str_replace("[APPOINTMENT_DATE]",date('d-m-Y',strtotime($data['appointment_datetime'])),$sms);
                $sms = str_replace("[APPOINTMENT_TIME]",date('h:i A',strtotime($data['appointment_datetime'])),$sms);
                $sms = str_replace("[APPOINTMENT_DAY]",$day_label,$sms);
                $sms = str_replace("[CANCEL_REASON]",$data['cancel_reason'],$sms);
                $sms = str_replace("[APPOINTMENT_REMINDER_MESSAGE]",$data['reminder_message'],$sms);
                $sms = str_replace("[APPOINTMENT_REMINDER_DATE]",$reminder_date,$sms);
                $sms = str_replace("[APPOINTMENT_REMINDER_DAY]",$reminder_day,$sms);

                if(!empty($data['apk_url'])){
                    $sms = str_replace("[DOWNLOAD_URL]",$data['apk_url'],$sms);
                }
                if(strpos($sms, '[LIVE_TRACKER_URL]') !== false){
                    
                		
                	if($thin_app_id=='821' ){
                    	$track_url = Custom::short_url(SITE_PATH.'tracker/track_your_appointment/'.base64_encode($uhid).'/'.base64_encode($thin_app_id),$thin_app_id);
                    }else{
                		$track_url = Custom::short_url(SITE_PATH."doctor/index/?t=".base64_encode($doctor_id)."&uh=".base64_encode($uhid)."&ti=".base64_encode($thin_app_id)."&pm=".base64_encode($patient_mobile)."&tracker=web_tracker_url",$thin_app_id);    
                    }	
                
                
                	
                
                    
                    $sms = str_replace("[LIVE_TRACKER_URL]",$track_url,$sms);
                }
                if(strpos($sms, '[PAYMENT_RECEIPT_URL]') !== false){
                    $receiptUrl = Custom::short_url(SITE_PATH."homes/receipt/".base64_encode($appointment_id));
                    $sms = str_replace("[PAYMENT_RECEIPT_URL]",$receiptUrl,$sms);
                }

                if(strpos($sms, '[MEDICAL_RECORD_URL]') !== false){
                    $string = $drive_folder_id ."##FOLDER##".$patient_mobile;
                    $path = FOLDER_PATH . Custom::encodeVariable($string);
                    $path = Custom::short_url($path,$thin_app_id);
                    $sms = str_replace("[MEDICAL_RECORD_URL]",$path,$sms);
                }

                if(strpos($sms, '[OPD_START_TIME]') !== false || strpos($sms, '[OPD_END_TIME]') !== false){
                    $time_data = Custom::get_doctor_open_day_data($thin_app_id,$doctor_id,$address_id,$service_id);
                    if(!empty($time_data['time_from'])){
                        $sms = str_replace("[OPD_START_TIME]",date('h:i A',strtotime($time_data['time_from'])),$sms);
                    }else{
                        $sms = str_replace("[OPD_START_TIME]",'',$sms);
                    }
                    if(!empty($time_data['time_to'])){
                        $sms = str_replace("[OPD_END_TIME]",date('h:i A',strtotime($time_data['time_to'])),$sms);
                    }else{
                        $sms = str_replace("[OPD_END_TIME]",'',$sms);
                    }
                }

                return $sms;
            }
            return false;

        }
        return false;
    }

    public static function get_patient_appointment_booking_data_by_number($doctor_id, $address_id, $mobile_number){
        $query = "SELECT acss.* FROM appointment_customer_staff_services AS acss left join appointment_customers as ac on ac.id = acss.appointment_customer_id  left join childrens as c on c.id = acss.children_id WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_address_id = $address_id  AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status IN('NEW','CONFIRM','RESCHEDULE') AND (ac.mobile = '$mobile_number' OR c.mobile = '$mobile_number' ) and acss.delete_status != 'DELETED' order by acss.appointment_datetime desc LIMIT 1";
        
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return  mysqli_fetch_assoc($list);
        }
        return false;
    }



    public static function visible_live_tracker_data($appointment_data,$user_role){
        if(($user_role == 'RECEPTIONIST' && ( $appointment_data['payment_status']=='SUCCESS' || $appointment_data['checked_in']=='YES'))){
           return true;
        }else if((( ($appointment_data['emergency_appointment']=='NO' && $appointment_data['patient_queue_type']=='NONE' && $appointment_data['patient_queue_checked_in']=='NO') || (in_array($appointment_data['patient_queue_type'],array('EARLY_CHECKIN','LATE_CHECKIN','EMERGENCY_CHECKIN','REPORT_CHECKIN','OTHER_CHECKIN')) && $appointment_data['patient_queue_checked_in']=='YES') )  && ($appointment_data['skip_tracker'] =="NO" && $user_role != 'RECEPTIONIST'))){
            return true;
        }
        return false;
    }

    public static function get_first_checking_datetime_after_token_data($booking_date, $doctor_id, $service_id, $address_id,$queue_number){
        $query = "SELECT  queue_check_in_datetime FROM appointment_customer_staff_services AS acss where acss.status IN ('NEW','CONFIRM','RESCHEDULE') and acss.appointment_staff_id =$doctor_id and acss.appointment_service_id = $service_id and acss.appointment_address_id =$address_id and DATE(acss.appointment_datetime) = '$booking_date' AND acss.show_after_queue = '$queue_number' and  (acss.patient_queue_checked_in='YES' or (acss.patient_queue_type ='NONE' and acss.payment_status='SUCCESS')) ORDER BY acss.queue_check_in_datetime asc LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $data =  mysqli_fetch_assoc($list);
            $date =  $data['queue_check_in_datetime'];
            $time = strtotime($date)+1;
            $data['less_date'] = date("Y-m-d H:i:s", $time);
            return $data;
        }
        return false;
    }

	public static function emergency_patient($doctor_id=null){

        $break_file_name = "custom_active_sms_".$doctor_id;
        if($patient_data = WebservicesFunction::readJson($break_file_name,"tracker")){
            return $patient_data;
        }else{
            $file_name = "emergency_".$doctor_id."_".date('Ymd');
            if(!$patient_data = WebservicesFunction::readJson($file_name,"tracker")){
                return false;
            }
            return json_encode($patient_data);
        }
        return "[]";
    }
	
	public static function get_long_url($shortCode){
        $shortner = new UrlShort();
        return $shortner->shortCodeToUrl($shortCode);
    }

	public static function get_next_walk_in_time_after_break($doctor_id, $address_id, $date,$break_start,$break_end){

        $break_start = date("H:i:s",strtotime($break_start));
        $break_end = date("H:i:s",strtotime($break_end));

        $query = "SELECT DATE_FORMAT(DATE_ADD(acss.appointment_datetime, INTERVAL 1 MINUTE),'%h:%i %p') as minutes FROM appointment_customer_staff_services AS acss WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_address_id = $address_id AND DATE(acss.appointment_datetime) = '$date' AND acss.delete_status != 'DELETED' AND acss.status != 'CANCELED' AND acss.has_token = 'NO' AND TIME(acss.appointment_datetime) BETWEEN '$break_start' and '$break_end' order by acss.appointment_datetime desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data)['minutes'];
        }else{
            return date("h:i A",strtotime("+1 MINUTE",strtotime($break_start)));
        }
    }
	
	public static function walk_in_button_position($thin_app_id,$doctor_id, $address_id, $service_id,$date){

        $date = date('Y-m-d', strtotime($date));
        $day_number = date('N', strtotime($date));
        $query = "SELECT IFNULL((SELECT acss.slot_time FROM appointment_customer_staff_services AS acss WHERE acss.appointment_staff_id = $doctor_id AND acss.appointment_address_id = $address_id AND acss.appointment_service_id = $service_id AND DATE(acss.appointment_datetime) = '$date' AND acss.has_token = 'NO' and acss.emergency_appointment ='NO' AND acss.`status` != 'CANCELED' AND TIME(acss.appointment_datetime) BETWEEN STR_TO_DATE(asbs.time_from, '%l:%i %p' ) AND STR_TO_DATE(asbs.time_to, '%l:%i %p' ) ORDER BY acss.appointment_datetime DESC LIMIT 1 ),time_from) AS break_time FROM appointment_staff_break_slots asbs WHERE asbs.appointment_staff_id = $doctor_id AND asbs.appointment_day_time_id = $day_number AND asbs.`status` ='OPEN'";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return array_column(mysqli_fetch_all($data,MYSQL_ASSOC),'break_time');
        }
        return false;
    }

	public static function get_doctor_last_time_slot($appointment_id){
        $query = "SELECT asa.to_time FROM appointment_customer_staff_services AS acss JOIN appointment_staff_addresses AS asa ON asa.appointment_address_id = acss.appointment_address_id AND asa.appointment_staff_id = acss.appointment_staff_id WHERE acss.id = $appointment_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list)['to_time'];
        }
        return false;
    }

	public static function synchronize_active_login($thin_app_id=0){
        $condition = !empty($thin_app_id)?" and ulds.thinapp_id = $thin_app_id":"";
        $query = "SELECT ulds.id, ulds.thinapp_id, ulds.firebase_token FROM user_login_device_stats AS ulds JOIN user_devices AS ud ON ud.id = ulds.user_device_id WHERE ulds.role_id = 5 AND ulds.`status` ='ACTIVE'  $condition order by ulds.created desc";
        $connection = ConnectionUtil::getConnection();
        $user_data = $connection->query($query);
        if ($user_data->num_rows) {
            $list = mysqli_fetch_all($user_data, MYSQLI_ASSOC);
            $final_array = $result_array =  $user_id_array = array();
            foreach($list as $key => $new_list){
                $final_array[$new_list['thinapp_id']][$new_list['id']] = $new_list['firebase_token'];
            }
            $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
            foreach($final_array as $thin_app_id =>$token){
                $array_chunk = array_chunk($token, 300,true);
                $thin_app_data = Custom::getThinAppData($thin_app_id);
                $server_key = $thin_app_data['firebase_server_key'];
                $package_name = $thin_app_data['package_name'];
                foreach ($array_chunk as $chunk_key => $token_array){
                    $device_stats_id_array = array_keys($token_array);
                    $token_array = array_values($token_array);
                    $send_array = array(
                        'title' => "CHECK",
                        'message' => "CHECK",
                        'description' => "CHECK"
                    );

                    $fields = array(
                        'priority' => 'high',
                        'restricted_package_name' => $package_name,
                        'registration_ids' => $token_array,
                        'data' => $send_array
                    );
                    $headers = array(
                        'Authorization:key=' . $server_key,
                        'Content-Type:application/json'
                    );

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                    $curl_response = json_decode(curl_exec($ch), true);
                    curl_close($ch);
                    $curl_res = $curl_response['results'];
                    if(!empty($curl_res)){
                        foreach($curl_res as $key => $value){
                            $login_stats_id = $device_stats_id_array[$key];
                            $created = Custom::created();
                            if(isset($curl_res[$key]['error'])){
                                if($curl_res[$key]['error'] == "NotRegistered" || $curl_res[$key]['error'] == "InvalidRegistration"){
                                    $sql = "update user_login_device_stats set status = ?, modified=? where id = ?";
                                    $stmt = $connection->prepare($sql);
                                    $stmt->bind_param('sss', $status, $created, $login_stats_id);
                                    if ($stmt->execute()) {
                                        $response['status'] = 1;
                                        $response['message'] = "User logout successfully";
                                        $query = "SELECT user_id,user_device_id as device_id FROM user_login_device_stats where id = $login_stats_id limit 1";
                                        $connection = ConnectionUtil::getConnection();
                                        $list = $connection->query($query);
                                        if($list->num_rows) {
                                            $user_data = mysqli_fetch_assoc($list);
                                            $file_name = "active_" . $user_data['user_id'] . "_" . $user_data['device_id'];
                                            WebservicesFunction::deleteJson(array($file_name), 'login_users');
                                        }
                                    }
                                }
                            }

                        }
                    }

                }
            }
            return true;
        }
        return false;
    }

	public static function get_doctor_upcoming_blocked_dates($doctor_id,$address_id,$service_id){
        $query = "SELECT id, date_format(book_date,'%d/%m/%Y') as blocked_date FROM appointment_bloked_slots AS ab WHERE ab.doctor_id = $doctor_id AND ab.address_id = $address_id AND ab.service_id = $service_id AND ab.is_date_blocked ='YES' AND DATE(ab.book_date) >= DATE(NOW())";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            return $staff_data;
        }
        return false;
    }
	
	public static function getAppIvrNumber($thin_app_id){
        $query = "select ivr_number from doctors_ivr where doctor_status = 'Active' and  thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['ivr_number'];
        }
        return false;
    }
	
	public static function get_doctor_open_day_data($thin_app_id,$doctor_id,$address_id,$service_id){
        $day_number = date('N');
        if (Custom::check_app_enable_permission($thin_app_id, 'NEW_QUICK_APPOINTMENT')) {
            $query = "SELECT IFNULL(acss.slot_time,das.from_time) AS slot_time, acss.appointment_datetime, IFNULL(acss.queue_number,0) AS queue_number, das.from_time as time_from, das.to_time as time_to, das.status, (SELECT ser.service_slot_duration FROM appointment_services AS ser WHERE ser.id = $service_id) AS service_slot_duration  FROM doctor_appointment_setting AS das LEFT JOIN appointment_customer_staff_services AS acss ON acss.appointment_staff_id = das.doctor_id AND acss.appointment_service_id = $service_id AND acss.appointment_address_id =$address_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` <> 'CANCELED' AND acss.delete_status <> 'DELETED' AND acss.has_token ='YES' AND acss.emergency_appointment ='NO' AND acss.sub_token ='NO' WHERE das.doctor_id = $doctor_id AND das.appointment_day_time_id = $day_number  ORDER BY acss.appointment_datetime desc LIMIT 1";
        }else{
            $query = "SELECT  acss.slot_time, acss.appointment_datetime, acss.queue_number, STR_TO_DATE(ash.time_from,'%l:%i %p') AS time_from, STR_TO_DATE(ash.time_to,'%l:%i %p') AS time_to, IF(ash.status ='OPEN','ACTIVE','INACTIVE') AS status, (SELECT ser.service_slot_duration FROM appointment_staff_services AS ass join appointment_services AS ser ON ass.appointment_service_id = ser.id  where ass.appointment_staff_id = $doctor_id LIMIT 1 ) AS service_slot_duration  FROM appointment_staff_hours AS ash LEFT JOIN appointment_customer_staff_services AS acss ON acss.appointment_staff_id = ash.appointment_staff_id AND acss.appointment_service_id = (SELECT ass.appointment_service_id FROM appointment_staff_services AS ass where ass.appointment_staff_id = $doctor_id LIMIT 1) AND acss.appointment_address_id =$address_id AND DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` <> 'CANCELED' AND acss.delete_status <> 'DELETED' AND acss.has_token ='YES' AND acss.emergency_appointment ='NO' AND acss.sub_token ='NO' WHERE ash.appointment_staff_id = $doctor_id AND ash.appointment_day_time_id = $day_number  ORDER BY acss.appointment_datetime desc LIMIT 1";
        }
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }
        return false;
    }

	public static function get_doctor_break_start($thin_app_id,$doctor_id,$date,$slot_time){

        $date = date('Y-m-d', strtotime($date));
        $day_number = date('N', strtotime($date));
        $query = "SELECT asbs.time_from FROM appointment_staff_break_slots AS asbs WHERE asbs.appointment_staff_id = $doctor_id AND asbs.appointment_day_time_id='$day_number' AND asbs.`status` ='OPEN' AND asbs.time_to >= '$slot_time' AND asbs.time_from <= '$slot_time' limit 1";
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data)['time_from'];
        }
        return false;
    }

	public static function getColorCodeAppointmentSlots($thin_app_id){

        $list_array=array();
        $file_name = "color_code_$thin_app_id";

        if(!$list_array = json_decode(WebservicesFunction::readJson($file_name,"thinapp"),true)){
            $list_array[0] =array('type'=>'booked_with_payment_success','color_code'=>'#78f704');
            $list_array[1] =array('type'=>'booked_with_payment_pending','color_code'=>'#fbec3a');
            $list_array[2] =array('type'=>'available_slots','color_code'=>'#FFFFFF');
            $list_array[3] =array('type'=>'expired_time_slots','color_code'=>'#927d7d');
            $list_array[4] =array('type'=>'refunded_appointment','color_code'=>'#04f3ff');
            $list_array[5] =array('type'=>'blocked_slots','color_code'=>'#9765d8');
            $list_array[6] =array('type'=>'emergency','color_code'=>'#ff0000');
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT `type`, color_code from appointment_slot_colors WHERE thinapp_id = $thin_app_id";
            $list = $connection->query($query);
            if ($list->num_rows) {
                $saved_array = mysqli_fetch_all($list,MYSQL_ASSOC);
                $all_types = array_column($saved_array,'type');
                foreach($list_array as $key => $value){
                    if(!in_array($value['type'],$all_types)){
                        $saved_array[] = array('type'=>$value['type'],'color_code'=>$value['color_code']);
                    }
                }
                WebservicesFunction::createJson($file_name,json_encode($saved_array),"CREATE","thinapp");
            }else{

                WebservicesFunction::createJson($file_name,json_encode($list_array),"CREATE","thinapp");
            }
        }
        if(empty($list_array)){
            return array();
        }
        return $list_array;
    }

	public static function splitAfterDecimal($string,$afterDecimalNumber=2){
        $return=array();
        $string = str_split($string);
        $check_step = 0;
        $dot_found = false;
        foreach($string as $value){
            if($value=="."){$dot_found=true;}
            if($dot_found==true){++$check_step;}
            $return[] = $value;
            if($check_step==($afterDecimalNumber+1)){
                break;
            }
        }
        return implode("",$return);
    }
	
	public static function getFranchiseRoleLabel($string){
        if($string =="primary_owner_id"){
            return "Primary Owner";
        }else if($string =="secondary_owner_id"){
            return "Secondary Owner";
        }else if($string =="primary_mediator_id"){
            return "Primary Mediator";
        }else if($string =="secondary_mediator_id"){
            return "Secondary Mediator";
        }
    }

	public static function  telemedicineRequiredBalnce($base_amount,$return_amount_for=''){
        $return_amount_for = strtoupper(strtolower($return_amount_for));
        $convenc_fee =  (($base_amount * TELEMEDICINE_CONVENCE_RATE)/100);
        $gst_fee =  (($convenc_fee * TELEMEDICINE_GST_RATE)/100);
        $payment_getway_fee =  (($base_amount * TELEMEDICINE_PAYMENT_GETWAY_RATE)/100);
        if($return_amount_for=="GST"){
            return $gst_fee;
        }else if($return_amount_for=="CONVENCE"){
            return $convenc_fee - ($gst_fee + $payment_getway_fee );
        }else if($return_amount_for=="GETWAY"){
            return $payment_getway_fee;
        }else{
            return $base_amount + round($convenc_fee);
        }

    }

	public static function sendConsentMessage($thin_app_id,$consent_id,$receiver_mobile){
         $send_by_username = "";
        $query = "SELECT IFNULL(staff.name,u.username) AS username FROM consents AS c JOIN consent_templates AS ct ON ct.id = c.consent_template_id LEFT JOIN appointment_staffs AS staff ON (staff.id = ct.doctor_id) || (c.sender_user_id = staff.user_id) AND staff.staff_type = 'DOCTOR' and staff.status='ACTIVE' LEFT JOIN users AS u ON u.id = c.sender_user_id WHERE c.id = $consent_id and c.thinapp_id = $thin_app_id LIMIT 1";
        
        
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            $send_by_username =  mysqli_fetch_assoc($data)['username'];
        }else{
            $send_by_username = Custom::get_app_name_and_token($thin_app_id)['username'];
        }

        $message = "You have received a consent from " . trim($send_by_username);
        $sendArray = array(
            'channel_id' => 0,
            'thinapp_id' => $thin_app_id,
            'flag' => 'CONSENT',
            'title' => 'New Message',
            'message' => $message,
            'description' => '',
            'chat_reference' => '',
            'module_type' => 'CONSENT',
            'module_type_id' => $consent_id,
            'firebase_reference' => ""
        );
    	$get_user_data = Custom::get_user_by_mobile($thin_app_id, $receiver_mobile);
        if (!empty($get_user_data)) {
            Custom::send_notification_via_token($sendArray, array($get_user_data['firebase_token']), $thin_app_id);
        }
        $consent_link = (FOLDER_PATH . "consent/" . base64_encode($consent_id . "##" . $thin_app_id));
        $consent_link = Custom::short_url($consent_link,$thin_app_id);
        $message .= "\nClick the link to open " . $consent_link;
        Custom::send_single_sms($receiver_mobile, $message, $thin_app_id);
    }
	
	public static function  alllowDoctorRecording($module_id,$module_type){
       $query= "";
       if($module_type=="TELEMEDICINE"){
           $query = "SELECT staff.consultation_video_recording from telemedicine_call_logs AS tcl JOIN telemedicine_leads AS tl ON tcl.telemedicine_lead_id = tl.id JOIN appointment_staffs AS staff ON staff.id = tl.appointment_staff_id  WHERE tcl.telemedicine_lead_id = $module_id LIMIT 1 ";
       }else if($module_type=="APPOINTMENT"){
           $query = "SELECT staff.consultation_video_recording from telemedicine_call_logs AS tcl JOIN appointment_customer_staff_services  AS acss ON acss.id =  tcl.appointment_customer_staff_service_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id  WHERE tcl.appointment_customer_staff_service_id = $module_id LIMIT 1  ";
       }
       if(!empty($query)){
           $connection = ConnectionUtil::getConnection();
           $data = $connection->query($query);
           if ($data->num_rows ) {
              return mysqli_fetch_assoc($data)['consultation_video_recording'];
           }
       }
       return 'NO';


    }

    public static function sendWhatsapp($template,$module_id,$thin_app_id,$send_to='',$send_to_mobile=''){

        if(Custom::check_app_enable_permission($thin_app_id, 'WHATS_APP')){
            $option = false;
            if(in_array($template,array(1,2,3))){
                $msg_data = Custom::getAppointmentMessageData($module_id);
                if($msg_data && in_array($msg_data['appointment_booked_from'],array('APP','DOCTOR_PAGE'))) {
                    $uhid = $msg_data['uhid'];
                    $patient_mobile = $msg_data['patient_mobile'];
                    $consulting_type = $msg_data['consulting_type'];
                    $doctor_mobile = $msg_data['doctor_mobile'];
                    $doctor_name = $msg_data['doctor_name'];
                    $doctor_id = $msg_data['doctor_id'];
                    $patient_mobile = $msg_data['patient_mobile'];
                    $patient_name = $msg_data['patient_name'];
                    $drive_folder_id = $msg_data['drive_folder_id'];
                    $medical_product_order_id = $msg_data['medical_product_order_id'];
                    $booking_validity_attempt = $msg_data['booking_validity_attempt'];
                    $video_calling_on_web = (Custom::check_app_enable_permission($thin_app_id, 'WEB_VIDEO_CONSULTATION'))?'YES':'NO';
                    $uhid = $msg_data['uhid'];
                    $token = Custom::create_queue_number($msg_data);
                    if($thin_app_id==633){
                        $doctor_mobile = "+919928047880";
                        $token = "$token, Doctor :- $doctor_name";
                    }

                    $date = date('d-m-Y', strtotime($msg_data['appointment_datetime']));
                    $time = date('h:i A', strtotime($msg_data['appointment_datetime']));

                    $app_url = $msg_data['apk_url'];
                    if(in_array($msg_data['appointment_booked_from'],array('DOCTOR_PAGE','MQ_FORM'))){
                        $app_url = Custom::create_web_app_url($doctor_id,$thin_app_id);
                    }

                    $tracker_url = " ";
                    if (Custom::check_app_enable_permission($thin_app_id, 'SMART_CLINIC')) {
                        if($thin_app_id=='821'){
                            $tracker_url = Custom::short_url(SITE_PATH . 'tracker/track_your_appointment/' . base64_encode($uhid) . '/' . base64_encode($thin_app_id), $thin_app_id);
                        }else{
                            $tracker_url = Custom::short_url(SITE_PATH."doctor/index/?t=".base64_encode($doctor_id)."&uh=".base64_encode($uhid)."&ti=".base64_encode($thin_app_id)."&pm=".base64_encode($patient_mobile)."&tracker=web_tracker_url",$thin_app_id);
                        }
                    }
                    if($template==1){
                        $to_number = $patient_mobile;
                        $string = $drive_folder_id."##FOLDER##".$to_number;
                        $path = FOLDER_PATH . Custom::encodeVariable($string);

                        if($thin_app_id==607){
                            $folder_path = '-';
                        }else{
                            $folder_path = Custom::short_url($path,$thin_app_id);
                        }

                        $form_url = SITE_PATH . "homes/flags/" . base64_encode($module_id);
                        $symptoms_filing_url = Custom::short_url($form_url, $thin_app_id);
                        $receiptUrl = '-';
                        if(!empty($medical_product_order_id) && $booking_validity_attempt==1){
                            $receiptUrl = Custom::short_url(SITE_PATH."homes/receipt/".base64_encode($module_id));
                        }
                        $consulting_type = strtolower($consulting_type);
                        $reminder_url = Custom::short_url(SITE_PATH."homes/sendReminder/".base64_encode($module_id));
                        $body = "CONFIRMED\nToken- $token\nDate- $date\nAppointment Time- $time\n\nThe Doctor will send you $consulting_type link as per the token number allotted to you. Please wait for your token number, until Doctor send you $consulting_type link. You can also download app ( link given below ) and start $consulting_type through app. At times, it may be delayed as doctor could get busy as per emergencies with other patients.\nLive Token Status- $tracker_url\n\nUpload Medical Record- $folder_path\n\nPls provide Patient treatment related general information, if any in advance.\n$symptoms_filing_url\n\nSend reminder to the doctor\n$reminder_url\n\nPayment Receipt- $receiptUrl\n\nDownload App- $app_url\n\nDo not block, Allow WhatsApp to continue to make the link clickable.\nFor ease and reference in future, please save our contact number as Doctor xyz.\nThis is system generated message. Please do not reply.";
                    }
                    if($template==2){
                        $to_number = $doctor_mobile;
                        if($consulting_type=='VIDEO' && $video_calling_on_web == 'YES'){
                            $param = base64_encode($module_id."##DOCTOR");
                            $video_link = Custom::short_url(SITE_PATH."homes/video/$param");
                            $p_mobile = " ".$patient_mobile;
                            if(true){
                                $string = $drive_folder_id."##FOLDER##".$to_number;
                                $path = FOLDER_PATH .'record/'. Custom::encodeVariable($string);
                                $folder_path = Custom::short_url($path,$thin_app_id);
                                $body = "APPOINTMENT BOOKED\nPatient Name- $patient_name\nMobile No- $p_mobile\nConsultation Type- $consulting_type\nToken No- $token\nDate- $date\nTime- $time\nStart Video Call- $video_link\nUpload Medical Record- $folder_path";
                                $body .= "\nClick on \"Continue\" or Save number to open the link";

                            }else{
                                $body = "APPOINTMENT BOOKED\nPatient Name- $patient_name\nMobile No-$p_mobile\nConsultation Type- $consulting_type\nToken No- $token\nDate- $date\nTime- $time\nStart Video Call-$video_link\n";
                            }
                        }else{
                            $body = "APPOINTMENT BOOKED\nPatient Name- $patient_name\nType- $consulting_type\nToken- $token\nDate- $date\nCheck-in Time- $time\n";
                        }
                    }

                    if ($template == 8) {
                        $to_number = $patient_mobile;
                        $url = SITE_PATH.'booking_convenience/?token='.base64_encode($module_id);
                        $url =Custom::short_url($url,$thin_app_id);
                        $queue_number1 = Custom::create_queue_number($msg_data);
                        $queue_number1 = ($msg_data['show_appointment_token'] == "NO") ? "" : $queue_number1;
                        $doctor_name = Custom::getThinAppData($thin_app_id)['name'];
                        $body = "$doctor_name\nटोकन नंबर $queue_number1 को सुनिश्चित करने के लिए, निचे दिए गए लिंक पर क्लिक करके, सुविधा शुल्क का भुगतान करें, अन्यथा 5 मिनट में आपका टोकन निरस्त कर दिया जाएगा |\nभुगतान लिंक :- ".$url."\nडॉक्टर की फीस आपको हॉस्पिटल/क्लिनिक में ही जमा करवानी होगी |";
                    }

                    if($video_calling_on_web == 'YES' && $template==3 && !empty($send_to_mobile) && ($send_to=='DOCTOR' || $send_to=='PATIENT')){
                        $param = base64_encode($module_id."##".$send_to);
                        $video_link = Custom::short_url(SITE_PATH."homes/video/$param");
                        $to_number = $send_to_mobile;
                        // $body = "*Important information - अतिआवश्यक सुचना !*\n\n$doctor_name has started video call consultation, now you can also take video call consultation by clicking on the link below.\n\n$doctor_name ने वीडियो कॉल परामर्श शुरू कर दिया है अब आप निचे लिंक पर क्लिक करके भी वीडियो कॉल परामर्श ले सकते है \n".$video_link;
                        //$result = Custom::send_single_sms($to_number,$body,$thin_app_id,true);
                    }

                    $option = array("body"=>$body,"to_number" => $to_number);
                }

            }
            if(!empty($option)){
                $path = SITE_PATH."twilio_auth/whatsapp.php";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $path);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($option));
                $return_array = curl_exec($ch);
                curl_close($ch);
                return $return_array;
            }
        }
        return false;
    }

    public static function  getAppointmentMessageData($appointment_id){
        $query= "SELECT t.logo, acss.status as appointment_status, t.name as app_name, staff.id as doctor_id, acss.medical_product_order_id, acss.booking_validity_attempt, acss.drive_folder_id, t.video_calling_on_web, acss.thinapp_id, acss.appointment_booked_from, acss.consulting_type, IFNULL(ac.mobile,c.mobile) as patient_mobile, staff.mobile as doctor_mobile, IFNULL(ac.first_name,c.child_name) AS patient_name, IFNULL(ac.uhid,c.uhid) AS uhid, acss.sub_token, acss.queue_number,acss.has_token,acss.emergency_appointment,acss.sub_token,acss.custom_token,acss.appointment_datetime, t.apk_url, staff.name AS doctor_name FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id LEFT JOIN appointment_staffs AS staff ON staff.id= acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id= acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id WHERE acss.id = $appointment_id LIMIT 1";

        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows ) {
             return mysqli_fetch_assoc($data);
        }
        return false;

    }

	public static function  getDoctorIdForTelemedicine($module_id,$module_type){
        $query= "";
        if($module_type=="TELEMEDICINE"){
            $query = "SELECT tl.appointment_staff_id FROM telemedicine_leads AS tl WHERE tl.id = $module_id limit 1  ";
        }else if($module_type=="APPOINTMENT"){
            $query = "SELECT acss.appointment_staff_id FROM appointment_customer_staff_services AS acss WHERE acss.id = $module_id LIMIT 1";
        }
        if(!empty($query)){
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows ) {
                return mysqli_fetch_assoc($data)['appointment_staff_id'];
            }
        }
        return 0;
    }

	public static function getConsultingFee($consulting_type,$server_data){
        if($consulting_type=='VIDEO'){
            return $server_data['video_consulting_amount'];
        }else if($consulting_type=='AUDIO'){
            return $server_data['audio_consulting_amount'];
        }else if($consulting_type=='CHAT'){
            return $server_data['chat_consulting_amount'];
        }else{
            return $server_data['service_amount'];
        }
    }

	public static function getCashFreeOnlineAmount($appointment_id,$thin_app_id){
        $connection = ConnectionUtil::getConnection();
        $query = "SELECT bco.order_id as order_id, bcfd.payment_account_mode, bcfd.payment_getway, bcfd.payment_mode, bcfd.id as booking_convenience_order_detail_id, acss.medical_product_order_id, IFNULL(bcfd.reference_id,acss.transaction_id) AS refrence_id, bcfd.amount FROM appointment_customer_staff_services AS acss JOIN booking_convenience_fee_details AS bcfd ON bcfd.appointment_customer_staff_service_id = acss.id join booking_convenience_orders as bco on bco.id = bcfd.booking_convenience_order_id WHERE acss.id = $appointment_id AND acss.consulting_type IN('VIDEO','AUDIO','CHAT','OFFLINE') AND acss.thinapp_id = $thin_app_id";
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_assoc($data);
        }
        return false;
    }

    public static function cashFreeRefund($connection,$thin_app_id, $user_id, $medical_product_order_id, $referenceId,$refundAmount,$booking_convenience_order_detail_id,$refundNote='',$payment_mode=''){
        $appId = CASHFREE_APPID;
        $secretKey = CASHFREE_SECERET;
        $url = "https://api.cashfree.com/api/v1/order/refund";
        $testAppArray = Custom::getTestModeApp($thin_app_id);
        if(in_array($thin_app_id,$testAppArray)){
            $appId = CASHFREE_APPID_TEST;
            $secretKey = CASHFREE_SECERET_TEST;
            $url = "https://test.cashfree.com/api/v1/order/refund";
        }
        $refundNote = empty($refundNote)?'Refund by Doctor':$refundNote;
        if(!empty($referenceId) && !empty($refundAmount)) {

            if(strtolower($payment_mode)=='paytm'){
                $paytmParams = array();
                $paytmParams["body"] = array(
                    "mid" => PAYTM_MID,
                    "orderId" => $referenceId
                );
                $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), PAYTM_KEY);
                $paytmParams["head"] = array("signature"=> $checksum);

                $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
                $url = "https://securegw.paytm.in/v3/order/status";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $response = json_decode(curl_exec($ch),true);


                curl_close($ch);
                $txn_id = $response['body']['txnId'];
                $refund_id = $referenceId;
                $paytmParams = array();
                $paytmParams["body"] = array(
                    "mid"          => PAYTM_MID,
                    "txnType"      => "REFUND",
                    "orderId"      => $referenceId,
                    "txnId"        => $txn_id,
                    "refId"        => $refund_id,
                    "refundAmount" => $refundAmount,
                    "comments" => $refundNote
                );
                $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), PAYTM_KEY);
                $paytmParams["head"] = array("signature"=> $checksum);
                $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
                $url = "https://securegw.paytm.in/refund/apply";
                $ch2 = curl_init($url);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                $result = json_decode(curl_exec($ch2),true);
                curl_close($ch2);
                $response=array();
                $response['status']=0;
                $response['message']="Unable to refund from paytm";
                if(isset($result['body']['resultInfo']['resultCode'])){
                    $code = $result['body']['resultInfo']['resultCode'];
                    if($code=='601' || $code=='01'){
                        $response['status']="OK";
                        $response['message']=$result['body']['resultInfo']['resultMsg'];
                        $response['refundId'] = $refund_id;
                    }
                }

            }else{
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 300,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "appId=$appId&secretKey=$secretKey&referenceId=$referenceId&refundAmount=$refundAmount&refundNote=" . urlencode($refundNote),
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/x-www-form-urlencoded"
                    ),
                ));
                $response = json_decode(curl_exec($curl),true);
                curl_close($curl);
            	


            }
            if ($response['status']=='OK') {
                //$connection = ConnectionUtil::getConnection();
            	if(!empty($medical_product_order_id)){
                $sql = "UPDATE medical_product_orders SET cashfree_refund_id = ?, cashfree_refund_amount=? where id = ?";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sss', $response['refundId'], $refundAmount, $medical_product_order_id);
                $stmt->execute();
                }
                if(!empty($booking_convenience_order_detail_id)){
                    $created = Custom::created();
                    $status = 'REFUNDED';
                    $sql = "UPDATE booking_convenience_fee_details as bcfd  JOIN booking_convenience_orders as bco on bcfd.booking_convenience_order_id = bco.id SET bcfd.status = ?, bco.status = ?, bcfd.refund_datetime=?, bcfd.refund_by_user_id=? where bcfd.id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('sssss', $status, $status,$created, $user_id, $booking_convenience_order_detail_id);
                    $stmt->execute();
                }
                return json_encode($response);
            } else {
                return json_encode($response);
            }
        }
        return false;


    }
   

	 public static function phonepayRefund($connection,$thin_app_id, $user_id, $medical_product_order_id, $referenceId,$refundAmount,$booking_convenience_order_detail_id,$orderId,$refundNote='',$payment_mode=''){
        $secretKey = PHONE_PAY_SECRATE_KEY;
        $merchantId = PHONE_PAY_MERCHANT_ID;
        $postUrl = PHONE_PAY_API_REFUND_URL;

        $testAppArray = Custom::getTestModeApp($thin_app_id);
        if(in_array($thin_app_id,$testAppArray)){
            $secretKey = PHONE_PAY_SECRATE_KEY_TEST;
            $merchantId = PHONE_PAY_MERCHANT_ID_TEST;
            $postUrl = PHONE_PAY_API_REFUND_URL_TEST;
            
        }

        $refundNote = empty($refundNote)?'Refund by Doctor':$refundNote;
        if(!empty($referenceId) && !empty($refundAmount)) {
        
            $postData = array(
                "merchantId"=>$merchantId,
                "merchantTransactionId"=> $referenceId,
                "originalTransactionId"=> $orderId,
                "amount"=> $refundAmount*100,
            );
        
        //	echo "<pre>";
      //  print_r($postData);
        
        $payloadMain = base64_encode(json_encode($postData));
        $payload = $payloadMain."/pg/v1/refund".$secretKey;
        $Checksum = hash('sha256', $payload);
        $Checksum = $Checksum.'###1';
        
        
       // print_r($Checksum);die;
               
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $postUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
              'request' => $payloadMain
            ]),
            CURLOPT_HTTPHEADER => [
              "Content-Type: application/json",
              "X-VERIFY: ".$Checksum,
              "accept: application/json"
            ],
          ]);
        
        
            $response = json_decode(curl_exec($curl),true);
            curl_close($curl);
            if ($response['success']==true) {
                //$connection = ConnectionUtil::getConnection();
            	$refund_tran_id =$response['data']['transactionId'];
                if(!empty($medical_product_order_id)){
                    $sql = "UPDATE medical_product_orders SET cashfree_refund_id = ?, cashfree_refund_amount=? where id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('sss',$refund_tran_id, $refundAmount, $medical_product_order_id);
                    $stmt->execute();
                }

                if(!empty($booking_convenience_order_detail_id)){
                    $created = Custom::created();
                    $status = 'REFUNDED';
                    $sql = "UPDATE booking_convenience_fee_details as bcfd  JOIN booking_convenience_orders as bco on bcfd.booking_convenience_order_id = bco.id SET bcfd.getway_refund_id =?,  bcfd.status = ?, bco.status = ?, bcfd.refund_datetime=?, bcfd.refund_by_user_id=? where bcfd.id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ssssss', $refund_tran_id, $status, $status,$created, $user_id, $booking_convenience_order_detail_id);
                    $stmt->execute();
                }
                return json_encode($response);
            } else {
                return json_encode($response);
            }
        }
        return false;


    }



	
	public static function getPatientByAppointmentId($appointment_id,$thin_app_id){

        $query = "SELECT acss.medical_product_order_id, acss.slot_time, acss.appointment_datetime, acss.queue_number, acss.sub_token,acss.has_token,acss.custom_token,acss.emergency_appointment, IFNULL(ac.uhid,c.uhid) AS uhid, acss.booking_convenience_fee_restrict_ivr, acss.consulting_type, acss.appointment_booked_from, IFNULL(ac.first_name,c.child_name) AS patient_name,IFNULL(ac.mobile,c.mobile) AS patient_mobile, IFNULL(ac.id,c.id) AS patient_id, IF(ac.id IS NOT NULL,'CUSTOMER','CHILDREN') AS patient_type FROM appointment_customer_staff_services AS acss LEFT JOIN appointment_customers AS ac ON ac.id =acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id WHERE acss.id = $appointment_id and acss.thinapp_id =$thin_app_id LIMIT 1 ";
        
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        } else {
            return false;
        }
    }
	
	public static function createRoomName($thin_app_id,$module_id,$module_name='APPOINTMENT',$from_number='',$to_number=''){
        $recording = 'NO';
        if($module_name=='APPOINTMENT' && !empty($module_id)){
        	
        	$file_name = "join_$module_id";
            if ($join = json_decode(WebservicesFunction::readJson($file_name,"video_join"), true)) {
                if(!empty($join['room'])){
                    return $join['room'];
                }
            }
        
            $room['t']=$thin_app_id."";
            $room['mn'] =($module_name=='APPOINTMENT')?'APP':$module_name;
            $room['mi'] =$module_id;
            //$room['r'] =rand(10,999);
            if(empty($from_number) || empty($to_number)){
                $query = "SELECT staff.consultation_video_recording, acss.thinapp_id, IFNULL(ac.first_name,c.child_name) AS patient_name,IFNULL(ac.mobile,c.mobile) AS patient_mobile, staff.mobile AS doctor_mobile, staff.name AS doctor_name FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id LEFT JOIN appointment_customers AS ac ON ac.id =acss.appointment_customer_id LEFT JOIN childrens AS c ON c.id = acss.children_id WHERE acss.id = $module_id LIMIT 1 ";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $data = mysqli_fetch_assoc($service_message_list);
                    $room['fm']=$data['doctor_mobile'];
                    $room['tm']=$data['patient_mobile'];
                    $recording=$data['consultation_video_recording'];
                }
            }else{
                $room['fm']=$from_number;
                $room['tm']=$to_number;
                $query = "SELECT staff.consultation_video_recording FROM appointment_customer_staff_services AS acss JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id WHERE acss.id = $module_id LIMIT 1 ";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $data = mysqli_fetch_assoc($service_message_list);
                    $recording=$data['consultation_video_recording'];
                }
            }
            $room = base64_encode(json_encode($room));
            if($recording=='YES'){
                $ROOM_SID = file_get_contents(SITE_PATH.'twilio_auth/createRoom.php?room=' . $room.'&recording='.$recording);
            }
            return $room;
        }
        return false;

    }
	
	public static function readRoomName($room_string){
        if(!empty($room_string)){
            $array =  json_decode(base64_decode($room_string),true);
            $room['thin_app_id']=$array['t'];
            $room['module_name'] =($array['mn']=='APP')?'APPOINTMENT':$array['mn'];
            $room['module_id'] =$array['mi'];
            $room['from_mobile']=$array['fm'];
            $room['to_mobile']=$array['tm'];
            return $room;
        }
        return false;
    }

    public static function sendVideoConnectNotification($thin_app_id,$module_id,$from_mobile,$toMobile,$room,$doctor_id){

        if(!empty($toMobile)){
            $identity = substr(uniqid('', true), -7);
            $token = file_get_contents(SITE_PATH.'twilio_auth/video/?identity=' . $identity . '&room=' . $room);
            $connection = ConnectionUtil::getConnection();
            $strSql = "SELECT  u.firebase_token, t.name as app_name FROM users AS u JOIN thinapps AS t ON t.id = u.thinapp_id WHERE u.mobile = '$toMobile' AND u.thinapp_id = $thin_app_id LIMIT 1";
            $tokenRS = $connection->query($strSql);
            $tokenData = mysqli_fetch_assoc($tokenRS);
            $app_name = @$tokenData['app_name'];
            $title = 'New Video Call';
            if(!empty($app_name)){
                $title = "Video Call From :- ".$app_name;
            }
            $doctor_data = Custom::get_doctor_by_id($doctor_id);
            if(!empty($doctor_data)){
                $doctor_name = $doctor_data['name'];
            }else{
                $doctor_name = $app_name;
            }
            $sendArray = array(
                'channel_id' => 0,
                'thinapp_id' => $thin_app_id,
                'flag' => 'VIDEO_CALL',
                'title' => $title,
                'message' => "Incoming video call..",
                'description' => 'New Video Call',
                'chat_reference' => '',
                'type' => 'VIDEO_CALL',
                'module_type_id' => $module_id,
                'module_type' => 'VIDEO_CALL',
                'time' => date('m-d-Y H:i:s'),
                'room' => $room,
                'from_mobile' => $from_mobile,
                'to_number' => $from_mobile,
                'identity' => $identity,
                'token' => str_replace(array("\n", "\r"), '', $token),
                'firebase_reference' => "",
                'doctor_name'=>$doctor_name
            );
            Custom::send_notification_via_token($sendArray, array($tokenData['firebase_token']), $thin_app_id);


        }
        return false;
    }

    public static function sendVideoDisconnectNotification($thin_app_id,$module_id,$from_mobile,$toMobile,$room,$doctor_id){
        if(!empty($toMobile)){
        	
        	$file_name = "join_$module_id";
            if ($join = json_decode(WebservicesFunction::readJson($file_name,"video_join"), true)) {
                $join['decline']='true';
                $file_name = "join_$module_id";
                WebservicesFunction::createJson($file_name,json_encode($join),'CREATE','video_join');
            }
            $connection = ConnectionUtil::getConnection();
            $strSql = "SELECT `firebase_token` FROM `users` WHERE `mobile` = '" . $toMobile . "' AND `thinapp_id` = '" . $thin_app_id . "' LIMIT 1";
            $tokenRS = $connection->query($strSql);
            $tokenData = mysqli_fetch_assoc($tokenRS);
            $sendArray = array(
                'channel_id' => 0,
                'thinapp_id' => $thin_app_id,
                'flag' => 'VIDEO_DECLINE',
                'title' => 'Disconnected video call',
                'message' => "Disconnected video call",
                'description' => '',
                'chat_reference' => '',
                'type' => 'VIDEO_CALL',
                'module_type_id' => $module_id,
                'module_type' => 'VIDEO_CALL',
                'time' => date('m-d-Y H:i:s'),
                'room' => $room,
                'from_mobile' => $from_mobile,
                'to_number' => '',
                'identity' => '',
                'token' => '',
                'firebase_reference' => ""
            );
            return Custom::send_notification_via_token($sendArray, array($tokenData['firebase_token']), $thin_app_id);
        	

        }
        return false;
    }
	
	public static function getTestModeApp($thin_app_id = 0){
        $return =array();
        if(!empty($thin_app_id) &&  Custom::check_app_enable_permission($thin_app_id, 'TEST_PAYMENT_ACCOUNT_MODE')){
            return array($thin_app_id);
        }else{
            return array();
        }
    }

	public static function sendSmartClinicNotification($appointment_booked_from,$appointmentData, $thin_app_id,$appointment_id,$convenience_for,$appointmentCustomerID,$childrenID){



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
            $patient_name = $appointment_data['cus_name'];
            $option = array(
                'thinapp_id' => $thin_app_id,
                'customer_id' => 0,
                'staff_id' => $appointment_data['appointment_staff_id'],
                'service_id' => $appointment_data['appointment_service_id'],
                'channel_id' => 0,
                'role' => "STAFF",
                'flag' => 'APPOINTMENT',
                'title' => "New Appointment - $patient_name, $day_label",
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

            if(Custom::check_app_enable_permission($thin_app_id, 'WHATS_APP')){
                $consulting_type = $appointment_data['consulting_type'];

                $token = Custom::create_queue_number($appointment_data);
                $date = date('d-m-Y', strtotime($appointment_data['appointment_datetime']));
                $time = date('h:i A', strtotime($appointment_data['appointment_datetime']));
                $message = "APPOINTMENT BOOKED\nPatient Name- $patient_name\nType- $consulting_type\nToken- $token\nDate- $date\nCheck-in Time- $time\n";
                $background['sms'][] = array(
                    'message' => $message,
                    'mobile' => $appointment_data['staff_mobile'],
                    'send_to' => "DOCTOR"
                );
            }

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
                'title' => "Appointment Confirmed - $queue_number, $lbl_date $time_string",
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
    		$uhid = $appointment_data['uhid'];;
            $track_url = Custom::short_url(SITE_PATH.'tracker/track_your_appointment/'.base64_encode($uhid).'/'.base64_encode($thin_app_id),$thin_app_id);
            $message .= "\nLive tracker status\n$track_url";
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


            /* send consent sms */
          /*  $tmp_type = ($convenience_for=='ONLINE')?'ONLINE_APPOINTMENT':'OFFLINE_APPOINTMENT';
            $sql = "SELECT id from consent_templates where  doctor_id = $doctor_id and thinapp_id = $thin_app_id and consent_for ='$tmp_type' limit 1";
            $connection = ConnectionUtil::getConnection();
            $select_data = $connection->query($sql);
            if ($select_data->num_rows) {
                $admin_data = Custom::get_thinapp_admin_data($thin_app_id);
                $consent_template_id = mysqli_fetch_assoc($select_data)['id'];
                $post = array();
                $post['app_key'] = APP_KEY;
                $post['user_id'] = $admin_data['id'];
                $post['thin_app_id'] = $thin_app_id;
                $post['mobile'] = $admin_data['mobile'];
                $post['consent_template_id'] = $consent_template_id;
                $post['receiver_mobile'] = $appointmentData['patient_mobile'];
                $result = json_decode(WebServicesFunction_2_3::send_consent($post,true), true);
                if (!empty($result['consent_id'])) {
                    $query = "update appointment_customer_staff_services set consent_id =? where id = ?";
                    $stmt_type = $connection->prepare($query);
                    $consulting_type = 'VIDEO';
                    $stmt_type->bind_param('ss', $result['consent_id'], $appointment_id);
                    $stmt_type->execute();
                    Custom::sendConsentMessage($thin_app_id,$result['consent_id'],$appointmentData['patient_mobile']);
                }

            } */



    }

	
	public static function getDoctorListWithAppointmentTaken($thin_app_id,$folder_id){
         $query = "select DISTINCT staff.id,staff.name FROM appointment_customer_staff_services AS acss JOIN  appointment_staffs AS staff ON acss.appointment_staff_id = staff.id where acss.drive_folder_id = $folder_id AND acss.thinapp_id = $thin_app_id and staff.status = 'ACTIVE' ORDER BY acss.id desc";
      
        $connection = ConnectionUtil::getConnection();
        $data = $connection->query($query);
        if ($data->num_rows) {
            return mysqli_fetch_all($data,MYSQL_ASSOC);
        }
        return false;
    }

	public static function getPatientUserId($thin_app_id,$patient_id,$patient_type){
        if($patient_type=="CUSTOMER" || $patient_type=='CHILDREN'){
            if($patient_type=='CUSTOMER'){
                    $query = "SELECT u.id FROM users  AS u JOIN appointment_customers AS ac ON  (u.id = ac.user_id) OR (ac.mobile = u.mobile AND ac.thinapp_id = u.thinapp_id) WHERE ac.id = $patient_id and ac.thinapp_id =$thin_app_id LIMIT 1";
            }else{
                $query = "SELECT u.id FROM users  AS u JOIN childrens AS c ON  (u.id = c.user_id) OR (c.mobile = u.mobile AND c.thinapp_id = u.thinapp_id) WHERE c.id = $patient_id and ac.thinapp_id =$thin_app_id LIMIT 1";
           }
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                return mysqli_fetch_assoc($data)['id'];
            }
        }
        return false;
    }
	
	public static function getUserNameById($user_id){
            $query = "SELECT IFNULL(staff.name,u.username) AS username FROM users AS u LEFT JOIN appointment_staffs AS staff ON staff.user_id = u.id AND staff.status='ACTIVE' WHERE u.id = $user_id LIMIT 1";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                return mysqli_fetch_assoc($data)['username'];
            }
            return false;
    }

	public static function queryToArray($qry)
    {
        $result = array();
        //string must contain at least one = and cannot be in first position
        if(strpos($qry,'=')) {

            if(strpos($qry,'?')!==false) {
                $q = parse_url($qry);
                $qry = $q['query'];
            }
        }else {
            return false;
        }

        foreach (explode('&', $qry) as $couple) {
            list ($key, $val) = explode('=', $couple);
            $result[$key] = $val;
        }

        return empty($result) ? false : $result;
    }

	public static function manageRoomFile($action,$appointment_id,$status='',$room=''){
        if($action=='CREATE' && !empty($status) && !empty($room)){
            $save =json_encode(array('status'=>$status,'room'=>$room));
            $file_name = "join_$appointment_id";
            WebservicesFunction::createJson($file_name,$save,'CREATE','video_join');
        }else if($action=='DELETE'){
            $file_name = "join_$appointment_id";
            WebservicesFunction::deleteJson(array($file_name),'video_join');
        }

    }

	public static function roomEncode($action, $string) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = 'bcb0';
        $secret_iv = 'ab29';
        // hash
        $key = hash('sha256', $secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }

	public static function getAppointmentConvenienceData($appointment_id)
    {
        $query = "SELECT staff.show_appointment_time, bco.`status` AS con_fee_status, t.booking_convenience_fee, acss.has_token,acss.emergency_appointment,acss.custom_token,acss.sub_token,acss.queue_number FROM appointment_customer_staff_services AS acss JOIN thinapps AS t ON t.id = acss.thinapp_id JOIN appointment_staffs AS staff ON staff.id = acss.appointment_staff_id LEFT JOIN booking_convenience_orders AS bco ON bco.appointment_customer_staff_service_id = acss.id WHERE acss.id = $appointment_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        } else {
            return false;
        }
    }

	public static function get_mobile_register_staff($mobile,$thin_app_id){
        $query = "select * from appointment_staffs  where mobile = '$mobile' and thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }
	
	
	public static function updateSettlmentHistory_back($connection,$order_id,$thin_app_id){
        $created = Custom::created();
        $settlement_id=0;
        if(!empty($thin_app_id) && !empty($order_id)){
            $query = "select mpo.id as order_id, mpo.hospital_ipd_id from medical_product_orders as mpo JOIN hospital_ipd as hi on hi.id = mpo.hospital_ipd_id  where mpo.id= $order_id and mpo.thinapp_id = $thin_app_id LIMIT 1";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $his = mysqli_fetch_assoc($service_message_list);
                $ipd_id = $his['hospital_ipd_id'];
                $query = "select his.id as settlement_id from hospital_ipd_settlements as his where his.hospital_ipd_id= $ipd_id and his.thinapp_id = $thin_app_id  LIMIT 1";
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $settlement_id = mysqli_fetch_assoc($service_message_list)['settlement_id'];
                }
               if(!empty($settlement_id)){
                    $query = "select SUM(mpod.total_amount) as total_amount from medical_product_orders as mpo join medical_product_order_details as mpod on mpo.id = mpod.medical_product_order_id join medical_products as mp on mp.id=mpod.medical_product_id where mpo.hospital_ipd_id =$ipd_id and mpo.status='ACTIVE' and mpo.is_expense = 'Y' and mpo.total_amount > 0  order by mpo.created desc";
                    $list = $connection->query($query);
                    $total_expense =$total_deposit =0;
                    if ($list->num_rows) {
                        $total_expense = mysqli_fetch_assoc($list)['total_amount'];
                    }
                    $query = "select SUM(hda.amount) as total_amount from hospital_deposit_amounts as hda left join medical_product_orders as mpo on hda.id = mpo.hospital_deposit_amount_id left join hospital_payment_types as hpt on  hda.hospital_payment_type_id = hpt.id  where hda.hospital_ipd_id =$ipd_id and hda.status ='ACTIVE' and mpo.status ='ACTIVE' order by hda.id asc";
                    $list = $connection->query($query);
                    if ($list->num_rows) {
                        $total_deposit = mysqli_fetch_assoc($list)['total_amount'];
                    }

                    $total_refund = $total_rebate_amount = 0;
                    if($total_deposit > $total_expense){
                        $total_refund = $total_deposit -$total_expense;
                        $settlement_amount = $total_refund;
                    }else{
                        $settlement_amount = $total_expense-$total_deposit;
                    }
                    $payable_amount = $settlement_amount;
                    $payment_status = ($total_refund > 0) ? "REFUND" : "RECEIVED";

                    $sql = "UPDATE hospital_ipd_settlements SET total_deposit = ?, total_expense=?, total_refund=?, payable_amount=?, settlement_amount=?, total_rebate_amount=?, payment_status=? where id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ssssssss', $total_deposit,$total_expense,$total_refund,$payable_amount,$settlement_amount,$total_rebate_amount,$payment_status,$settlement_id);
                    if ($stmt->execute()) {
                        if($payment_status == "REFUND"){
                            $total_amount = 0;
                            $refund_amount = $settlement_amount;
                            $is_refunded = "YES";
                            $refund_date_time = Custom::created();
                        }else{
                            $total_amount = $settlement_amount;
                            $refund_amount = 0;
                            $is_refunded = "NO";
                            $refund_date_time = "0000-00-00 00:00:00";
                        }

                        
                      $sql = "UPDATE medical_product_orders SET total_amount = ?, refund_amount=?, is_refunded=?,refund_date_time=?, modified=? where hospital_ipd_settlement_id = ? and hospital_ipd_id=? and thinapp_id=? and is_settlement=?";
                        $stmt = $connection->prepare($sql);
                        $is_settlement = 'Y';
                        $stmt->bind_param('sssssssss', $total_amount,$refund_amount, $is_refunded, $refund_date_time,$created,$settlement_id,$ipd_id,$thin_app_id,$is_settlement);
                        return $stmt->execute();
                    }
                }
            }
        }

        return  true;
    }

	 public static function updateSettlmentHistory($connection,$order_id,$thin_app_id){
        $created = Custom::created();
        $settlement_id=0;
        if(!empty($thin_app_id) && !empty($order_id)){

            $medical_product_order_table = "medical_product_orders";    
            $medical_product_order_detail_table = "medical_product_order_details";    
            $query = "select mpo.id as order_id, mpo.hospital_ipd_id from $medical_product_order_table as mpo JOIN hospital_ipd as hi on hi.id = mpo.hospital_ipd_id  where mpo.id= $order_id and mpo.thinapp_id = $thin_app_id LIMIT 1";
            $service_message_list = $connection->query($query);
            if (!$service_message_list->num_rows) {
                $medical_product_order_table = "medical_product_orders_archive";    
                $medical_product_order_detail_table = "medical_product_order_details_archive";    
                $query = "select mpo.id as order_id, mpo.hospital_ipd_id from $medical_product_order_table as mpo JOIN hospital_ipd as hi on hi.id = mpo.hospital_ipd_id  where mpo.id= $order_id and mpo.thinapp_id = $thin_app_id LIMIT 1";
                $service_message_list = $connection->query($query);
            }
            
            if ($service_message_list->num_rows) {
                $his = mysqli_fetch_assoc($service_message_list);
                $ipd_id = $his['hospital_ipd_id'];
                $query = "select his.id as settlement_id from hospital_ipd_settlements as his where his.hospital_ipd_id= $ipd_id and his.thinapp_id = $thin_app_id  LIMIT 1";
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                    $settlement_id = mysqli_fetch_assoc($service_message_list)['settlement_id'];
                }
                if(!empty($settlement_id)){
                    $query = "select SUM(mpod.total_amount) as total_amount from $medical_product_order_table as mpo join $medical_product_order_detail_table as mpod on mpo.id = mpod.medical_product_order_id join medical_products as mp on mp.id=mpod.medical_product_id where mpo.hospital_ipd_id =$ipd_id and mpo.status='ACTIVE' and mpo.is_expense = 'Y' and mpo.total_amount > 0  order by mpo.created desc";
                    $list = $connection->query($query);
                    $total_expense =$total_deposit =0;
                    if ($list->num_rows) {
                        $total_expense = mysqli_fetch_assoc($list)['total_amount'];
                    }
                    $query = "select SUM(hda.amount) as total_amount from hospital_deposit_amounts as hda left join $medical_product_order_table as mpo on hda.id = mpo.hospital_deposit_amount_id left join hospital_payment_types as hpt on  hda.hospital_payment_type_id = hpt.id  where hda.hospital_ipd_id =$ipd_id and hda.status ='ACTIVE' and mpo.status ='ACTIVE' order by hda.id asc";
                    $list = $connection->query($query);
                    if ($list->num_rows) {
                        $total_deposit = mysqli_fetch_assoc($list)['total_amount'];
                    }

                    $total_refund = $total_rebate_amount = 0;
                    if($total_deposit > $total_expense){
                        $total_refund = $total_deposit -$total_expense;
                        $settlement_amount = $total_refund;
                    }else{
                        $settlement_amount = $total_expense-$total_deposit;
                    }
                    $payable_amount = $settlement_amount;
                    $payment_status = ($total_refund > 0) ? "REFUND" : "RECEIVED";

                    $sql = "UPDATE hospital_ipd_settlements SET total_deposit = ?, total_expense=?, total_refund=?, payable_amount=?, settlement_amount=?, total_rebate_amount=?, payment_status=? where id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('ssssssss', $total_deposit,$total_expense,$total_refund,$payable_amount,$settlement_amount,$total_rebate_amount,$payment_status,$settlement_id);
                    if ($stmt->execute()) {
                        if($payment_status == "REFUND"){
                            $total_amount = 0;
                            $refund_amount = $settlement_amount;
                            $is_refunded = "YES";
                            $refund_date_time = Custom::created();
                        }else{
                            $total_amount = $settlement_amount;
                            $refund_amount = 0;
                            $is_refunded = "NO";
                            $refund_date_time = "0000-00-00 00:00:00";
                        }


                        $sql = "UPDATE $medical_product_order_table SET total_amount = ?, refund_amount=?, is_refunded=?,refund_date_time=?, modified=? where hospital_ipd_settlement_id = ? and hospital_ipd_id=? and thinapp_id=? and is_settlement=?";
                        $stmt = $connection->prepare($sql);
                        $is_settlement = 'Y';
                        $stmt->bind_param('sssssssss', $total_amount,$refund_amount, $is_refunded, $refund_date_time,$created,$settlement_id,$ipd_id,$thin_app_id,$is_settlement);
                        return $stmt->execute();
                    }
                }
            }
        }

        return  true;
    }
	
	public static function get_doctor_via_ivr_code($doctor_ivr_code,$thin_app_id){
        if(!empty($doctor_ivr_code)){
            $query = "select staff.mobile from doctors_ivr as di join appointment_staffs as staff on staff.id = di.doctor_id where di.doctor_code = '$doctor_ivr_code' and di.doctor_status = 'Active' and di.thinapp_id=$thin_app_id limit 1";
            $connection = ConnectionUtil::getConnection();
            $data = $connection->query($query);
            if ($data->num_rows) {
                return mysqli_fetch_assoc($data);
            }
        }
        return false;
    }

	public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }	

	 public static function download_image_from_url($url,$newwidth,$newheight,$save_path) {
        $img = file_get_contents($url);
        $im = imagecreatefromstring($img);
        $width = imagesx($im);
        $height = imagesy($im);
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $white = imagecolorallocate($thumb, 255, 255, 255);
        imagefill($thumb, 0, 0, $white);

        imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        $result = imagepng($thumb,$save_path); //save image as jpg
        imagedestroy($thumb);
        imagedestroy($im);
        return $result;
    }
    public static function createIconFolder($dir){
        if(!is_dir($dir)){
            return mkdir($dir, 0777,true);
        }
        return true;
    }

	public static function get_recent_customer_list($thin_app_id,$mobile,$remove_country_code= false,$limit = ''){
        $select_mobile = ($remove_country_code===true)?'RIGHT(ac.mobile,10)':'ac.mobile';
        $limit = !empty($limit)?"limit $limit":'';
        $select_mobile .= ' as mobile';
        $query = "select ac.uhid, df.id AS folder_id, ac.thinapp_id, ac.id as patient_id, ac.address, $select_mobile, ac.first_name as patient_name ,ac.gender, ac.age, ac.dob from appointment_customers as ac JOIN drive_folders AS df ON df.appointment_customer_id= ac.id where ac.thinapp_id = $thin_app_id and ac.mobile = '$mobile'  order by ac.modified desc $limit";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $save= array();
            $customer_data =  mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
            foreach($customer_data as $key =>$customer){
                $save[$key] = $customer;
                $save[$key]['dob'] = (!empty($customer['dob']) && $customer['dob']!='0000-00-00')?date('d/m/Y',strtotime($customer['dob'])):'';
            }
            return $save;
        }else{
            return array();
        }
    }
	
	public static function smsStringWordCount($string, $return = 'COUNT',$return='')
    {
        $lines = explode("\n", $string);
        if (empty($string)) {
            $lines = 0;
        } else {
            $lines = count($lines) - 1;
        }
        $len = mb_strlen($string) + $lines;
        $regex = '~^[a-zA-Z]+$~';
        if (strlen($string) != strlen(utf8_decode($string))) {
            $sms = ceil($len / 67);
        } else {
            $sms = 1;
            if($len > 160){
                $sub_seq = $len - 160;
                $sms += ceil($sub_seq / 140);
            }
        }
        if($return=='ALL'){
            return array('sms'=>$sms,'len'=>$len);
        }else if ($return == 'COUNT') {
            return $len;
        }else {
            return $sms;
        }

    }
	
	public static function getAppointmentRoomName($appointment_id){
        $query = "select room from telemedicine_call_logs  where appointment_customer_staff_service_id =$appointment_id  limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['room'];
        }else{
            return false;
        }
    }

	public static function create_proforma($thin_app_id,$drive_file_id){
        if($thin_app_id==134){
            $query = "SELECT files.doctor_id, IFNULL(ac.uhid,c.uhid) AS uhid, df.children_id,df.appointment_customer_id, IFNULL(ac.first_name,c.child_name) AS patient_name, IFNULL(ac.mobile,c.mobile) AS mobile FROM drive_files AS files JOIN drive_folders AS df ON df.id = files.drive_folder_id LEFT JOIN appointment_customers AS ac ON ac.id = df.appointment_customer_id LEFT JOIN childrens AS c ON c.id= df.children_id WHERE files.file_category_master_id = 6 AND files.id = $drive_file_id limit 1";
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(true);
            $inv_data = $connection->query($query);
            if ($inv_data->num_rows) {
                $pat_Data = mysqli_fetch_assoc($inv_data);
                $uhid = base64_encode($pat_Data['uhid']);
                $created = Custom::created();
                $children_id = $pat_Data['children_id'];
                $appointment_customer_id = $pat_Data['appointment_customer_id'];
                $patient_name = $pat_Data['patient_name'];
                $patient_mobile = Custom::create_mobile_number($pat_Data['mobile']);
                $query = "INSERT INTO proforma_invoices (thinapp_id,patient_name,patient_mobile,appointment_customer_id, children_id, doctor_id,drive_file_id,created,modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $connection = ConnectionUtil::getConnection();
                $stmt = $connection->prepare($query);
                $stmt->bind_param('sssssssss', $thin_app_id, $patient_name, $patient_mobile, $appointment_customer_id,$children_id,$pat_Data['doctor_id'],$drive_file_id, $created, $created);
                if($stmt->execute()){
                    $pi = base64_encode($stmt->insert_id);
                    $order_link = Custom::short_url(SITE_PATH."homes/proforma_invoice/$pi");
                    $message = "Please create proforma invoice from following link\n".$order_link;
                    $res = Custom::send_single_sms("+918890720687", $message,134);
                }
            }
        }

    }

    public static function getLabUserData($lab_user_id){
        $query = "SELECT * FROM lab_pharmacy_users AS lpu  WHERE lpu.id = $lab_user_id  limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

	public static function updateSerialNumber($connection, $thinapp_id, $doctor_id, $appointment_id, $appointment_date)
    {

        $appointment_date = date('Y-m-d', strtotime($appointment_date));
        $query = "SELECT count(id) as total FROM appointment_customer_staff_services where DATE(appointment_datetime) = '$appointment_date' and thinapp_id=$thinapp_id";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $total = mysqli_fetch_assoc($service_message_list)['total'];
            $total = $total + 1;
            $sql = "INSERT INTO payment_sequence_number (thinapp_id, appointment_id, doctor_id, payment_sequence_number) VALUES (?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ssss', $thinapp_id, $appointment_id, $doctor_id, $total);
            if ($stmt->execute()) {
                return $total;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

	public static function sendWhatsappSms_back($to_number,$body,$callback_sms=null)
    {
        $StatusCallback = SITE_PATH."services/whatsapp_callback";
        $option = array("body" => $body, "to_number" => $to_number);
        if ($to_number != '+919999999999' && !empty($option)) {
            $path = SITE_PATH . "twilio_auth/whatsapp.php";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($option));
            $return_array = curl_exec($ch);
        	$response = json_decode($return_array,true);
            Custom::send_whats_sms_response($to_number,$body,0,$response,$callback_sms);
            curl_close($ch);
            return $return_array;
        }
        return false;
    }

	 public static function sendWhatsappSms($to_number,$body,$callback_sms=null)
    {
            
            $accountSid = TWILIO_SID;
            $authToken = TWILIO_TOKEN;
            $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $accountSid . '/Messages.json';
            $data = http_build_query([
                'From' => 'whatsapp:+19843004757',
                'To' =>"whatsapp:$to_number",  
                'Body' => $body,
            ]);
            $ch = curl_init($url);
            // Set cURL options
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $accountSid . ':' . $authToken);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $return_array = curl_exec($ch);
            $response = json_decode($return_array,true);
            Custom::send_whats_sms_response($to_number,$body,0,$response,$callback_sms);
            curl_close($ch);
            return true;
        
    }

	public static function createFileName($file_name){
        if(!empty($file_name)){
            $tmp_array = explode(".", $file_name);
            if(count($tmp_array) > 1){
                $ext = end($tmp_array);
                return date('Ymdhis').'_'.rand(100,9999).'.'.$ext;
            }
        }
        return $file_name;

    }

	 public static function get_tracker_counter_array($thin_app_id, $data_type =''){

        $file_name = "doctor_counter_".$thin_app_id;
        if(!$data = json_decode(Custom::encrypt_decrypt('decrypt',WebservicesFunction::readJson($file_name,"counter")),true)){
            $connection = ConnectionUtil::getConnection();
           $query = "SELECT dc.counter, dc.appointment_staff_id AS doctor_id,booking_type  FROM doctor_counters AS dc  WHERE dc.thinapp_id = $thin_app_id  AND dc.status !='INACTIVE'";
            $doctor_data = $connection->query($query);
            if ($doctor_data->num_rows) {
                $data = mysqli_fetch_all($doctor_data,MYSQLI_ASSOC);
                $data_string =json_encode($data);
                $data_string = Custom::encrypt_decrypt('encrypt',$data_string);
                WebservicesFunction::createJson($file_name,$data_string,"CREATE","counter");
            }
        }
        if(!empty($data)){
            $return =array();
            foreach ($data as $key =>$val){
                if($data_type=='LIST'){
                    $return[]= $val['counter'];
                }else{
                    $return[$val['doctor_id']][$val['booking_type']][]= $val['counter'];
                }
            }
            return $return;
        }
        return false;
    }
    public static function get_ck_birla_token($doctor_id,$token){
        $counter = Custom::get_tracker_counter_array(CK_BIRLA_APP_ID)[$doctor_id];
        $key = key($counter);
        $counter = '';
        if($key =='APPOINTMENT-PATIENT'){
            $counter = 'A';
        }else if($key =='WALK-IN'){
            $counter = 'W';
        }
        return $counter.$token;
    }
    public static function get_ck_birla_less_queue_counter($thin_app_id,$doctor_id){
        $doctor_condition = "AND dc.appointment_staff_id = acss.appointment_staff_id";
        if(empty($doctor_id)){
            $doctor_condition = "";
        }
        $query = "SELECT dc.booking_type, dc.counter, count(acss.id) AS total_appointment, dc.appointment_staff_id AS doctor_id FROM doctor_counters AS dc LEFT JOIN  appointment_customer_staff_services AS acss on DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') $doctor_condition AND dc.counter = acss.referred_by WHERE  dc.appointment_staff_id = $doctor_id AND dc.thinapp_id = $thin_app_id and dc.status ='OPEN' GROUP BY dc.counter ORDER BY total_appointment ASC, dc.counter asc  LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }
        return false;
    }
    public static function get_billing_counter_list($thin_app_id){
        $query = "SELECT dc.counter from doctor_counters AS dc WHERE dc.thinapp_id = $thin_app_id and dc.status='OPEN' and booking_type='BILLING'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list,MYSQLI_ASSOC),'counter');
        }
        return false;
    }
    public static function get_ck_birla_next_token_id($thin_app_id,$doctor_id){
        $query = "SELECT acss.id as appointment_id FROM appointment_customer_staff_services AS acss WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') and  acss.referred_by ='' and acss.patient_queue_type ='NONE' and acss.appointment_staff_id = $doctor_id and acss.thinapp_id=$thin_app_id and acss.skip_tracker='NO' LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['appointment_id'];
        }
        return false;
    }
    public static function get_billing_counter_list_data($thin_app_id){
        $file_name = "counter_$thin_app_id";
        if(!$counter_data = json_decode(WebservicesFunction::readJson($file_name,"counter"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT dc.*, IF(dc.booking_type='BILLING','Billing',staff.name)  as doctor_name  from doctor_counters AS dc left join appointment_staffs as staff on staff.id = dc.appointment_staff_id WHERE dc.thinapp_id = $thin_app_id and dc.status IN('OPEN','CLOSED') order by dc.counter asc";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $counter_data = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                WebservicesFunction::createJson($file_name,json_encode($counter_data),"CREATE","counter");
            }
        }
        if (!empty($counter_data)) {
            return $counter_data;
        }else{
            return false;
        }
    }
   
public static function load_counter_tracker_data($thin_app_id,$counter_wise_list = false){
          $counter_array =array();
          $get_counter_data = Custom::get_billing_counter_list_data($thin_app_id);
          foreach ($get_counter_data as $key =>$value){
            $label_name = '';
            if($value['booking_type']=='WALK-IN'){
                $label_name ='Walk-In Patient';
            }else if($value['booking_type']=='APPOINTMENT-PATIENT'){
                $label_name ='Appointment Patient';
            }else if($value['booking_type']=='BILLING'){
                $label_name ='Billing';
            }
          	 $counter_status = ($value['status']=='CLOSED')?'Closed':'-';
              $counter_array[$value['counter']]=array('booking_type'=>$value['booking_type'],'doctor_id'=>$value['appointment_staff_id'],'token'=>$counter_status,'label'=>$label_name,'counter'=>$value['counter']);
        }

        //$query = "SELECT acss.id as appointment_id, dc.booking_type, dc.counter, acss.queue_number, acss.appointment_staff_id AS doctor_id, IF(acss.last_assign_doctor_id > 0, acss.last_assign_doctor_id, acss.appointment_staff_id) AS booked_with_doctor_id  FROM doctor_counters AS dc LEFT JOIN  appointment_customer_staff_services AS acss on DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.checked_in ='YES' AND dc.counter = acss.referred_by and acss.patient_queue_type IN('NONE','BILLING_CHECKIN') WHERE dc.thinapp_id = $thin_app_id  AND dc.status IN('OPEN','CLOSED')  GROUP BY dc.counter  ORDER BY dc.counter ASC, acss.send_to_lab_datetime asc";
          $query = "SELECT acss.referred_by AS counter, acss.send_to_lab_datetime, acss.id as appointment_id, acss.queue_number, acss.appointment_staff_id AS doctor_id, IF(acss.last_assign_doctor_id > 0, acss.last_assign_doctor_id, acss.appointment_staff_id) AS booked_with_doctor_id  FROM appointment_customer_staff_services AS acss WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.checked_in ='YES' and acss.referred_by !='' and acss.patient_queue_type IN('NONE','BILLING_CHECKIN') AND  acss.thinapp_id = $thin_app_id and acss.skip_tracker='NO' ORDER BY  acss.send_to_lab_datetime ASC";
          $connection = ConnectionUtil::getConnection();
          $list = $connection->query($query);
          if ($list->num_rows) {
              $list = mysqli_fetch_all($list,MYSQLI_ASSOC);
              foreach ($list as $key =>$value){
                  if(!empty($value['queue_number'])){
                      $token = Custom::get_ck_birla_token($value['booked_with_doctor_id'],$value['queue_number']);
                  }else{
                      $token = ($counter_wise_list===false)?'-':'';
                  }
                  $last_array = $counter_array[$value['counter']];
                  $new_array = array('counter'=>$value['counter'],'token'=>$token,'appointment_id'=>$value['appointment_id']);
                  if(!array_key_exists('appointment_id',$counter_array[$value['counter']])){
                      $counter_array[$value['counter']] =array_merge($last_array,$new_array);
                      $appointment_id = $value['appointment_id'];
                      $booking_type = $counter_array[$value['counter']]['booking_type'];
                      if($booking_type=='BILLING'){
                          $flag = 'BILLING_COUNTER_FLUSH';
                      }else{
                          $flag = 'COUNTER_FLUSH';
                      }
                  
                  		  $counter = $counter_array[$value['counter']]['counter'];
                          //$flag = 'BILLING_COUNTER_FLUSH';
                          $query = "SELECT id from counter_patient_logs where appointment_id=$appointment_id and thinapp_id=$thin_app_id and flag='$flag' and date(created) = date(now()) limit 1";
                          $flush = $connection->query($query);
                          if (!$flush->num_rows) {
                              $created = Custom::created();
                              $sql = "INSERT INTO counter_patient_logs (thinapp_id, appointment_id, flag, counter, created) VALUES (?, ?, ?, ?, ?)";
                              $stmt_user = $connection->prepare($sql);
                              $stmt_user->bind_param('sssss', $thin_app_id,$appointment_id, $flag, $counter, $created);
                              $res = $stmt_user->execute();
                          }
                  
                  	
                  }
              }
          }
         if($counter_wise_list===false){
            $final =array();
            foreach ($counter_array as $key =>$value){
                $final[$value['label']][] =$value;
            }
            return $final;
         }
    
    	
        return $counter_array;
    }

public static function counter_get_doctor_active_appointment_data($thin_app_id,$doctor_id){
        $counter_array =array();
        $get_counter_data = Custom::get_billing_counter_list_data($thin_app_id);
        foreach ($get_counter_data as $key =>$value){
            $label_name = '';
            if($value['booking_type']=='WALK-IN'){
                $label_name ='Walk-In Patient';
            }else if($value['booking_type']=='APPOINTMENT-PATIENT'){
                $label_name ='Appointment Patient';
            }else if($value['booking_type']=='BILLING'){
                $label_name ='Billing';
            }
            $counter_status = ($value['status']=='CLOSED')?'Closed':'-';
            $counter_array[$value['counter']]=array('booking_type'=>$value['booking_type'], 'doctor_id'=>$value['appointment_staff_id'],'token'=>$counter_status,'label'=>$label_name,'counter'=>$value['counter']);
        }
        $query = "SELECT acss.* FROM appointment_customer_staff_services AS acss WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.checked_in ='YES' and acss.patient_queue_type IN('DOCTOR_CHECKIN') AND acss.appointment_staff_id = $doctor_id and acss.thinapp_id = $thin_app_id and acss.skip_tracker='NO'  ORDER BY  acss.send_to_lab_datetime ASC limit 1";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            return mysqli_fetch_assoc($list);
        }
        return false;
    }

	public static function assign_counter_to_next_token($thin_app_id,$doctor_id){
        $connection = ConnectionUtil::getConnection();
        $appointment_id = Custom::get_ck_birla_next_token_id($thin_app_id,$doctor_id);
        if(!empty($appointment_id)){
            $counter_data =  Custom::get_ck_birla_less_queue_counter($thin_app_id,$doctor_id);
            $counter = $counter_data['counter'];
            if(Custom::count_token_numbers_on_counter($doctor_id,$thin_app_id,$counter)==0){
                $send_to_lab_datetime = Custom::created();
                if(!empty($counter)){
                    $sql  = "UPDATE appointment_customer_staff_services SET send_to_lab_datetime = ?, referred_by =? where id = ?";
                    $stmt = $connection->prepare($sql);
                    $stmt->bind_param('sss', $send_to_lab_datetime, $counter, $appointment_id);
                    if($stmt->execute()){
                        if(in_array($counter_data['booking_type'],array('WALK-IN','APPOINTMENT-PATIENT'))){
                            $res = Custom::create_counter_log($thin_app_id, 'ASSIGN_COUNTER',$appointment_id,$counter,'AUTO ASSIGN');
                        }else if($counter_data['booking_type'] == 'BILLING'){
                            $res = Custom::create_counter_log($thin_app_id, 'SEND_TO_BILLING_COUNTER',$appointment_id,$counter,'AUTO ASSIGN');
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public static function count_token_numbers_on_counter($doctor_id,$thin_app_id,$counter){
        $query = "SELECT count(acss.id) as total  FROM appointment_customer_staff_services AS acss WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.checked_in ='YES' and acss.referred_by ='$counter' and acss.patient_queue_type IN('NONE','BILLING_CHECKIN') AND acss.appointment_staff_id = $doctor_id and acss.thinapp_id = $thin_app_id and acss.skip_tracker='NO'";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['total'];
        }else{
            return false;
        }
    }
	public static function create_web_app_url($doctor_id,$thin_app_id,$module='DOCTOR'){
            if($module=='QUTOT'){
                $long_url = SITE_PATH."qutot/web_app/$doctor_id/?t=".base64_encode($doctor_id);
            }else{
                $long_url = SITE_PATH."doctor/index/$doctor_id/?t=".base64_encode($doctor_id);
            }
            return Custom::short_url($long_url,$thin_app_id);
    }

	public static function create_counter_log($thin_app_id, $flag,$appointment_id, $counter='',$remark = ''){
        if($thin_app_id==CK_BIRLA_APP_ID){
            $created = Custom::created();
            $connection = ConnectionUtil::getConnection();
            $connection->autocommit(true);
            $sql = "INSERT INTO counter_patient_logs (thinapp_id, appointment_id, flag, counter, remark, created) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_user = $connection->prepare($sql);
            $stmt_user->bind_param('ssssss', $thin_app_id,$appointment_id, $flag, $counter, $remark, $created);
            if ($stmt_user->execute()) {
                return $stmt_user->insert_id;
            }
        }
        return false;
    }

	public static function deleteDir($dirPath) {
        if (is_dir($dirPath)) {
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::deleteDir($file);
                } else {
                    unlink($file);
                }
            }
            rmdir($dirPath);
        }
        return true;
    }

	public static function get_report_date_data($first_date, $second_date){
        $first_date = new DateTime($first_date);
        $second_date = new DateTime($second_date);
        $interval = $first_date->diff($second_date);
        $hour_minutes = $interval->format('%h') * 60;
        $total_minutes = $interval->format('%i')+$hour_minutes;
        $return['label'] =$total_minutes.'m '.$interval->format('%s').'s';
        $return['minutes'] =$total_minutes.'.'.$interval->format('%s');
    	$return['second'] =$interval->format('%s');
        return $return;
    }

	public static function get_manual_assign_token_queue_datetime($thin_app_id,$doctor_id,$counter){
        $query = "SELECT acss.send_to_lab_datetime FROM appointment_customer_staff_services AS acss WHERE DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.`status` IN('NEW','CONFIRM','RESCHEDULE') and  acss.referred_by ='$counter' and acss.appointment_staff_id = $doctor_id and acss.thinapp_id=$thin_app_id and acss.skip_tracker='NO' LIMIT 2";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $list =  mysqli_fetch_all($service_message_list, MYSQLI_ASSOC);
            if(count($list)==2){
                $data = Custom::get_report_date_data($list[0]['send_to_lab_datetime'], $list[1]['send_to_lab_datetime']);
                if($data['second'] == 2 ){
                    return date('Y-m-d H:i:s',strtotime('+1 second',strtotime($list[0]['send_to_lab_datetime'])));
                }else{
                    return  false;
                }
            }else if(count($list)==1){
                return date('Y-m-d H:i:s',strtotime('+1 second',strtotime($list[0]['send_to_lab_datetime'])));
            }
        }
        return Custom::created();
    }

	
	public static function send_web_app_booking_notification($mobile,$thin_app_id,$message,$doctor_id){
        $query = "SELECT u.web_app_token,t.logo FROM users AS u JOIN thinapps AS t ON t.id = u.thinapp_id WHERE u.mobile = '$mobile' AND u.thinapp_id = $thin_app_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $list =  mysqli_fetch_assoc($service_message_list);
            if(!empty($list['web_app_token'])){
                $url = SITE_PATH."doctor/index/$doctor_id/?t=".base64_encode($doctor_id)."&open=my_token";
                $option = array(
                    'title' =>"Appointment Booked",
                    'body' => $message,
                    'logo'=>$list['logo'],
                    'url'=>$url,
                	'doctor_id'=>$doctor_id,
                    'type'=>'BOOKING',
                    'actions'=>array(
                        array(
                            'action'=>$url,
                            'title'=>'Open App',
                            'icon'=>'https://s3-ap-south-1.amazonaws.com/mengage/202106181921501388579434.png'
                        )
                    )
                );
                $return_array = Custom::send_web_app_notification_via_token($option,$list['web_app_token']);
                if($return_array[0]['failure']=='1'){
                    Custom::send_single_sms($mobile,$message,$thin_app_id,false,false);
                }
            }else{
                Custom::send_single_sms($mobile,$message,$thin_app_id,false,false);
            }
        }

    }

	public static function send_web_app_notification_via_token($send_array=array(),$token=null)
    {

        $return_array = array();
        $fields = array(
            'priority' => 'high',
            'registration_ids' => array($token),
            'data' => $send_array
        );
        $server_key = "AAAAnC1BcsM:APA91bFC9oY68p6FPQB3bFPHk_gGrwjotCSm1-E5ntSDC9Q0jRiCbEmube2h_eNvMEp9XgQXPe52cJc7EYyCvOhCudqGcCn0L6OQ-FUmQgJzN878geok7eoZMRPkelU4ivQu1GmklTKYKTl3Hp-mfZb3usy5Bs-dZw";
        $headers = array(
            'Authorization:key=' . $server_key,
            'Content-Type:application/json'
        );
        $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $return_array[] = json_decode(curl_exec($ch), true);
        curl_close($ch);
        return $return_array;
    }

	public static function send_whats_sms_response($mobile,$message,$thin_app_id,$response,$callback_sms){

        $save['thin_app_id'] =$thin_app_id;
        $save['message'] =$message;
        $save['mobile'] =$mobile;
    	if(!empty($callback_sms)){
            $save['callback_sms'] =$callback_sms;
        }
        $message_id = isset($response['sid'])?$response['sid']:$response['MessageSid'];
        if(!empty($message_id)){
            WebservicesFunction::createJson($message_id,json_encode($save),"CREATE","NOT_TO_DELETE_CACHE/whatsapp");    
        }


    }

	public static function getTotalAlert($appointment_id){

        $query = "select counts from appointment_reminder_counts where  appointment_id = $appointment_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['counts'];
        }
        return 0;

    }

	

	public static function createChatBoatLink($thin_app_id,$mobile,$doctor_id=0,$short_url=true){
        $mobile = Custom::create_mobile_number($mobile);
        $logo ='';
        $app_name ='';
        $patient_name ='';
        $patient_id =0;
        $patient_mobile =substr($mobile,-10);
        $query = "SELECT ac.id as patient_id, ac.first_name AS patient_name, ac.mobile, t.name AS app_name, t.logo AS app_logo FROM appointment_customers AS ac JOIN thinapps AS t ON t.id = ac.thinapp_id  LEFT JOIN  appointment_customer_staff_services AS acss ON ac.id = acss.appointment_customer_id WHERE ac.mobile= '$mobile' AND  ac.thinapp_id=$thin_app_id AND ac.status = 'ACTIVE' ORDER BY acss.created DESC, ac.modified DESC LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $appointment_data = mysqli_fetch_assoc($service_message_list);
            $logo =$appointment_data['app_logo'];
            $app_name =$appointment_data['app_name'];
            $patient_name =$appointment_data['patient_name'];
            $patient_id =$appointment_data['patient_id'];
            $patient_mobile =substr($appointment_data['mobile'],-10);;
        }else{
            $app_data = Custom::get_thinapp_data($thin_app_id);
            $logo =$app_data['logo'];
            $app_name =$app_data['name'];
        }

        $final_array = array(
            'ti'=>$thin_app_id,
            'logo'=>$logo,
            'name'=>$app_name,
            'pn'=>$patient_name,
            'pm'=>$patient_mobile,
            'pi'=>$patient_id,
            'pt'=>'CUSTOMER',
            'di'=>$doctor_id,
        );
        if($short_url===true){
            return Custom::short_url(NODE_SERVER_URL."chatbot?p=".base64_encode(json_encode($final_array)),$thin_app_id);
        }else{
            return NODE_SERVER_URL."chatbot?p=".base64_encode(json_encode($final_array));
            
        }


    }

	public static function fortisGetNextToken($thin_app_id,$doctor_id,$currentToken,$orderbyPrev=false){

        $counter = ($orderbyPrev==false)?1:-1;
        $order = ($orderbyPrev==false)?'asc':'desc';
        $sub_token = explode(".",$currentToken);
        $castQueue = "CAST(acss.queue_number  AS DECIMAL(10,2))";
        if(count($sub_token)==2){
            $check_token = $sub_token[0].'.'.($sub_token[1]+$counter) ;
            $currentToken = $sub_token[0]+$counter;
            $max_token = $sub_token[0]+$counter;
            $sub_condition  = "AND ((($castQueue='$check_token') OR ($castQueue BETWEEN '$check_token' and '$max_token' and acss.sub_token='YES') OR ($castQueue='$currentToken')))";
        }else{
            $last_token = $currentToken;
            $max_token =$currentToken=  $currentToken+$counter;
            $sub_condition  = "AND ( ($castQueue > '$last_token' and $castQueue < '$max_token' and acss.sub_token='YES') OR ($castQueue = '$currentToken' ))";
        }
        $doctorData = Custom::get_doctor_by_id($doctor_id);
        if($doctorData['increase_token_on_traker']=='BOOKED_TOKEN_WISE'){
            if($orderbyPrev==true){
                $sub_condition = " AND $castQueue <= '$currentToken'";
            }else{
                $sub_condition = " AND $castQueue >= '$currentToken'";
            }
        }

        $query = "select acss.notes as remark, acss.id as appointment_id, acss.queue_number as token_number,acss.sub_token,acss.emergency_appointment,acss.appointment_patient_name as patient_name from appointment_customer_staff_services as acss  where acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $doctor_id  AND acss.status IN ('NEW','CONFIRM','RESCHEDULE')  and DATE(acss.appointment_datetime) = DATE(NOW()) $sub_condition ORDER BY CAST(acss.queue_number  AS DECIMAL(10,2)) $order limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
          //  $currentToken = (int)$currentToken+1;
            $return['token_number'] = $currentToken;
            $return['appointment_id'] = '0';
            $return['sub_token'] = 'NO';
            $return['emergency_appointment'] = 'NO';
            $return['patient_name'] = '';
            $return['remark'] = '';
            return $return;
        }

    }


    public static function fortisGetCurrentToken($thin_app_id,$doctor_id){
        $file_name = "fortis_tracker_".$doctor_id."_".date('Y-m-d');
        $tracker_data = json_decode(WebservicesFunction::readJson($file_name,"fortis"),true);
        if(!Custom::isCustomizedAppId($thin_app_id) && empty($tracker_data['current'])){
            $tracker_data['last'] = $tracker_data['current'] = false;
            $tracker_data['current'] = Custom::fortisGetNextToken($thin_app_id,$doctor_id,0);
            WebservicesFunction::createJson($file_name,json_encode($tracker_data),"CREATE","fortis");
        }
        return $tracker_data;
    }

    
public static function fortisUpdateToken($thin_app_id,$doctor_id,$orderbyPrev=false,$updateFlag="SKIP"){

        $file_name = "fortis_tracker_".$doctor_id."_".date('Y-m-d');
    
    	$file_array=array();
        for($counter = 1; $counter <= 10;$counter++){
            $file_array[] = "fortis_tracker_".$doctor_id."_".date('Y-m-d',strtotime("-$counter days"));;
        }
       WebservicesFunction::deleteJson($file_array,"fortis");
    
        $file_data = json_decode(WebservicesFunction::readJson($file_name,"fortis"),true);
        $tracker_data =array();
        $tracker_data['current'] = $tracker_data['last'] = false;
        $currentToken = !empty($file_data['current'])?$file_data['current']['token_number']:0;
        if(!empty($file_data['current'])){
            $tracker_data['last'] = $file_data['current'];
        }
        /* skip current token if booked */
        $appointment_id = $file_data['current']['appointment_id'];
        if(!empty($appointment_id)){
            $adminData = Custom::get_thinapp_admin_data($thin_app_id);
            $post['app_key'] = APP_KEY;
            $post['user_id'] = $adminData['id'];
            $post['thin_app_id'] = $adminData['thinapp_id'];
            $post['mobile'] = $adminData['mobile'];
            $post['appointment_id'] = $appointment_id;
            if($updateFlag=="SKIP"){
                //$result = json_decode(WebServicesFunction_2_3::appointment_skip($post, true), true);
            	 	$status = "YES";
                    $created = Custom::created();
                    $connection = ConnectionUtil::getConnection();
                    $sql = "update appointment_customer_staff_services set skip_tracker=?, modified=? where id =?";
                    $stmt_sub = $connection->prepare($sql);
                    $stmt_sub->bind_param('sss', $status, $created, $appointment_id );
                    $result = $stmt_sub->execute();
            		$log = Custom::insertTokenLog($thin_app_id,$doctor_id,$doctor_id,"SKIPPED");
            }else if($updateFlag=="CLOSE"){
                    $status = "CLOSED";
                    $created = Custom::created();
                    $connection = ConnectionUtil::getConnection();
                    $sql = "update appointment_customer_staff_services set status=?, modified=? where id =?";
                    $stmt_sub = $connection->prepare($sql);
                    $stmt_sub->bind_param('sss', $status, $created, $appointment_id );
                    $result = $stmt_sub->execute();
            		$log = Custom::insertTokenLog($thin_app_id,$doctor_id,$doctor_id,"CLOSED");
            }
        }
        /* skip current token if booked */


        $nextTokenData = Custom::fortisGetNextToken($thin_app_id,$doctor_id,$currentToken,$orderbyPrev);
        if(!empty($nextTokenData)){
            $tracker_data['current'] =$nextTokenData;
        }
        WebservicesFunction::createJson($file_name,json_encode($tracker_data),"CREATE","fortis");
		Custom::makeCallToUpcomingPatient($thin_app_id,$doctor_id);	
	
        return $tracker_data;
    }

public static function makeCallToUpcomingPatient($thin_app_id,$doctor_id){
        if($thin_app_id==134){
            $current_token = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            if(!empty($current_token['current']['token_number'])){
                ob_end_clean();
                header("Connection: close");
                ignore_user_abort();
                ob_start();
                $size = ob_get_length();
                header("Content-Length: $size");
                ob_end_flush();
                flush();
                $queueNumber = $current_token['current']['token_number'];
                $sub_token = explode(".",$queueNumber);
                if(count($sub_token)==2){
                    $queueNumber = $sub_token[0]+3;
                }else{
                    $queueNumber =$queueNumber+3;
                }
                $query = "select ac.mobile from appointment_customer_staff_services as acss join appointment_customers as ac on ac.id = acss.appointment_customer_id where DATE(acss.appointment_datetime) =date(NOW()) and acss.queue_number = '$queueNumber' and acss.`status` IN('NEW','CONFIRM','RESCHEDULE') AND acss.appointment_staff_id = $doctor_id and acss.thinapp_id = $thin_app_id limit 1";
                $connection = ConnectionUtil::getConnection();
                $service_message_list = $connection->query($query);
                if ($service_message_list->num_rows) {
                
                
                    $mobile = mysqli_fetch_assoc($service_message_list)['mobile'];
                    $post_data = array(
                        'From'     => $mobile,
                        'CallerId'       => "01414931640 ",
                        'Url'      => "http://my.exotel.com/mengage/exoml/start_voice/526534",
                        'CallType' => "trans"
                    );
                    $api_key    = "mengage";
                    $url = "https://api.exotel.com/v1/Accounts/$api_key/Calls/connect.json";
                    $ch  = curl_init();
                    curl_setopt($ch, CURLOPT_VERBOSE, 1);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'Authorization: Basic bWVuZ2FnZTphYWU2YTE4Y2UxOTAyMDg1ZjM1MWFhNzAxOTZiMzE2YTg5N2ZhNDBi',
                        'accept: application/json'
                    ));
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_FAILONERROR, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
                    $http_result = curl_exec($ch);
                    $res = curl_close($ch);
                }
            }
        }
    }

    public static function updateTokenOnAction($thin_app_id,$doctor_id,$token_number=0){
        if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
            $current_token = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            if(empty($token_number) || ($current_token['current']['token_number']==$token_number)){

                $res = Custom::fortisUpdateToken($thin_app_id,$doctor_id);
            }
        }
    }

   public static function fortisShowResponseString($tokenData,$return_array=false){
        $result_string ="";
        if($return_array===true){
            $result_string['token'] = $result_string['patient_name'] = '';
        }
        if(isset($tokenData['current']['token_number'])){
            $token = $tokenData['current']['token_number'];
            $emergency = !empty($tokenData['current']['remark'])?$tokenData['current']['remark']:"Emergency";
            $token = ($tokenData['current']['sub_token']=='YES')?$emergency:$token;
            $patient_name = !empty($tokenData['current']['patient_name'])?$tokenData['current']['patient_name']:'-';
            if($return_array===true){
                $result_string['token'] =$token;
                $result_string['patient_name'] =$patient_name;
            }else{
                $result_string ="<table><tr><th>Current Token </th><th>Patient Name</th></tr><tr><td class='current_token_number_td'>$token</td><td class='current_patient_name_td'>$patient_name</td></tr></table>";
            }
        }
        return $result_string;
        
    }

   
public static function fortisUpdatePatientNameCurrentToken($thin_app_id,$doctor_id,$appointmentData,$action){
        if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
            $tracker_data = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            if($tracker_data['current']['token_number']==$appointmentData['queue_number']){
                if($action =='CANCEL'){
                    $tmpSave['token_number'] = $tracker_data['current']['token_number'];
                    $tmpSave['appointment_id'] = '0';
                    $tmpSave['sub_token'] = 'NO';
                    $tmpSave['emergency_appointment'] = 'NO';
                    $tmpSave['patient_name'] = '';
                	$tmpSave['remark'] = '';
                    $tracker_data['current'] = $tmpSave;
                    $file_name = "fortis_tracker_".$doctor_id."_".date('Y-m-d');
                    WebservicesFunction::createJson($file_name,json_encode($tracker_data),"CREATE","fortis");
                }else if($action =='UPDATE'){
                    $tmpSave['token_number'] = $tracker_data['current']['token_number'];
                    $tmpSave['appointment_id'] = $appointmentData['id'];
                    $tmpSave['sub_token'] =  $appointmentData['sub_token'];
                    $tmpSave['emergency_appointment'] =  $appointmentData['emergency_appointment'];
                    $tmpSave['patient_name'] = $appointmentData['appointment_patient_name'];
                    $tracker_data['current'] = $tmpSave;
                	$tmpSave['remark'] = $appointmentData['notes'];
                    $file_name = "fortis_tracker_".$doctor_id."_".date('Y-m-d');
                    WebservicesFunction::createJson($file_name,json_encode($tracker_data),"CREATE","fortis");
                }
            }
        }
   		 return true;
    }

public static function fortisSetTokenMenual($thin_app_id,$doctor_id,$token_number){

		if(strtolower($token_number)=="open" || strtolower($token_number)=="closed"){
            $token_number = ucfirst(strtolower($token_number));
        }
	
		$booked_token_doctor_id =$doctor_id;
        if(Custom::isCustomizedAppId($thin_app_id)){
            $doctorData = Custom::get_doctor_by_id($doctor_id);
            $booked_token_doctor_id = $doctorData['show_token_into_digital_tud'];
        }

        $file_name = "fortis_tracker_".$doctor_id."_".date('Y-m-d');
        $file_data = json_decode(WebservicesFunction::readJson($file_name,"fortis"),true);
        $tracker_data = $return =array();
        $tracker_data['current'] = $tracker_data['last'] = false;
        if(!empty($file_data['current'])){
            $tracker_data['last'] = $file_data['current'];
        }

     
$query = "select acss.id as appointment_id, acss.queue_number as token_number,acss.sub_token,acss.emergency_appointment,acss.appointment_patient_name as patient_name from appointment_customer_staff_services as acss  where acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $booked_token_doctor_id  AND acss.status IN ('NEW','CONFIRM','RESCHEDULE','CLOSED') and DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.queue_number='$token_number' ORDER BY CAST(acss.queue_number  AS DECIMAL(10,2)) limit 1";
        
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $return = mysqli_fetch_assoc($service_message_list);
        }else{
            $return['token_number'] = $token_number;
            $return['appointment_id'] = '0';
            $return['sub_token'] = 'NO';
            $return['emergency_appointment'] = 'NO';
            $return['patient_name'] = '';
        }

        $tracker_data['current'] =$return;
        WebservicesFunction::createJson($file_name,json_encode($tracker_data),"CREATE","fortis");
        return $tracker_data;
    }


    public static function web_app_custom_login_data($user_id){
        $query = "SELECT u.thinapp_id as thin_app_id, u.id AS user_id,u.mobile AS user_mobile,staff.id AS doctor_id, u.role_id from users AS u  left join appointment_staffs AS staff ON staff.user_id = u.id and staff.staff_type= 'DOCTOR' AND staff.status='ACTIVE' where u.id = $user_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }
        return false;
    }

	public static function getThinappDefaultPatient($thin_app_id){
        $file_name = "default_patient_$thin_app_id";
        if(!$playStatus = json_decode(WebservicesFunction::readJson($file_name,"patient"),true)){
            $connection = ConnectionUtil::getConnection();
            $docSql = "SELECT  ac.id as patient_id,ac.first_name as patient_name,ac.mobile as patient_mobile, ac.uhid from appointment_customers as ac  where ac.mobile = '+919999999999' and ac.thinapp_id = $thin_app_id and ac.status = 'ACTIVE' LIMIT 1";
            $docRS = $connection->query($docSql);
            if ($docRS->num_rows) {
                $playStatus = mysqli_fetch_assoc($docRS);
                WebservicesFunction::createJson($file_name,json_encode($playStatus),"CREATE","patient");
            }
        }
        if (!empty($playStatus)) {
            return $playStatus;
        }else{
            return false;
        }

    }

    public static function get_patient_data_for_token_booking($thin_app_id,$patient_mobile,$patient_name,$customer_created_by=0){
        $add_new_patient = false;
        $connection = ConnectionUtil::getConnection();
        if($patient_mobile!='+919999999999'){
            $query = "SELECT  ac.id as patient_id,ac.first_name as patient_name,ac.mobile as patient_mobile,ac.uhid from appointment_customers as ac  where ac.mobile = '$patient_mobile' and ac.thinapp_id = $thin_app_id AND ac.first_name = '$patient_name' and ac.status = 'ACTIVE' LIMIT 1";
            $list = $connection->query($query);
            if ($list->num_rows) {
                return mysqli_fetch_assoc($list);
            }else{
                $add_new_patient = true;
            }
        }else{
            $add_new_patient = true;
        }

        if($add_new_patient===true){
            $country_code = "+91";
            $created = Custom::created();
            $sql = "INSERT INTO appointment_customers (first_name,  mobile, country_code, thinapp_id, customer_created_by,  created, modified) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_sub = $connection->prepare($sql);
            $stmt_sub->bind_param('sssssss', $patient_name,$patient_mobile,$country_code, $thin_app_id, $customer_created_by, $created, $created);
            if ($stmt_sub->execute()) {
                $return['patient_id'] = $stmt_sub->insert_id;
                $return['patient_name'] = $patient_name;
                $return['patient_mobile'] = $patient_mobile;
                return $return;
            }
        }
        return false;
    }

    public static function getBookedAppoitnment($thin_app_id,$doctor_id,$address_id,$patient_id,$booking_date){
        $query = "SELECT acss.appointment_datetime FROM appointment_customer_staff_services AS acss WHERE acss.appointment_customer_id = $patient_id AND DATE(acss.appointment_datetime) >= '$booking_date' AND acss.status IN('NEW','RESCHEDULE','CONFIRMED') AND acss.thinapp_id = $thin_app_id AND acss.appointment_address_id = $address_id AND acss.appointment_staff_id = $doctor_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

    public static function isTokenBooked($thin_app_id,$doctor_id,$address_id,$service_id,$booking_date,$token_number){
        $query = "SELECT acss.id FROM appointment_customer_staff_services AS acss WHERE  DATE(acss.appointment_datetime) = '$booking_date' AND acss.queue_number = '$token_number' AND  acss.status IN('NEW','RESCHEDULE','CONFIRMED') AND acss.thinapp_id = $thin_app_id AND acss.appointment_address_id = $address_id AND acss.appointment_staff_id = $doctor_id AND acss.appointment_service_id = $service_id LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }
    }

  
	public static function getDoctorCustomDataByMobile($thin_app_id,$mobile,$new_appointment_setting = false){
        $tmp =base64_encode($mobile);
        $file_name = "doctor_custom_data_$tmp";
        if(!$playStatus = json_decode(WebservicesFunction::readJson($file_name,"doctor"),true)){
            $connection = ConnectionUtil::getConnection();
            if($new_appointment_setting === true){
                $docSql = "select t.name as app_name, app_staf.department_category_id, app_staf.id as doctor_id, app_staf.user_id, app_staf.name as doctor_name, app_staf.appointment_setting_type as setting_type, das.appointment_service_id as service_id, app_staf.user_id, u.role_id, app_staf.mobile, app_ser.service_slot_duration, das.appointment_address_id as  address_id from appointment_staffs as app_staf join doctor_appointment_setting as das on das.doctor_id = app_staf.id and das.setting_type = app_staf.appointment_setting_type AND das.index_number = 1 and ( (das.appointment_day_time_id = (WEEKDAY(NOW())+1)) OR (DATE(NOW()) = DATE(das.appointment_date)) ) and ( TIME(NOW()) between das.from_time and das.to_time OR das.from_time > TIME(NOW()) ) join appointment_services as app_ser on app_ser.id = das.appointment_service_id  left join users as u on u.id = app_staf.user_id join thinapps as t on t.id = app_staf.thinapp_id where app_staf.thinapp_id = $thin_app_id and app_staf.mobile= '$mobile' and app_staf.status = 'ACTIVE' and app_staf.staff_type = 'DOCTOR' limit 1";
            }else{
                $docSql = "select t.name as app_name, app_staf.department_category_id, app_staf.id as doctor_id, app_staf.user_id, app_staf.name as doctor_name, app_staf.appointment_setting_type as setting_type, app_staf.user_id, u.role_id, app_staf.mobile, app_ser.service_slot_duration, aa.id as address_id,app_ser.id as service_id from appointment_staffs as app_staf join appointment_staff_services as ass on ass.appointment_staff_id = app_staf.id join appointment_services as app_ser on app_ser.id = ass.appointment_service_id join appointment_staff_addresses as asa on asa.appointment_staff_id = app_staf.id join appointment_addresses as aa on aa.id = asa.appointment_address_id left join users as u on u.id = app_staf.user_id join thinapps as t on t.id = app_staf.thinapp_id where app_staf.thinapp_id = $thin_app_id and app_staf.mobile= '$mobile' and app_staf.status = 'ACTIVE' and app_staf.staff_type = 'DOCTOR' limit 1";
            }
            $docRS = $connection->query($docSql);
            if ($docRS->num_rows) {
                $playStatus = mysqli_fetch_assoc($docRS);
                WebservicesFunction::createJson($file_name,json_encode($playStatus),"CREATE","doctor");
            }
        }
        if (!empty($playStatus)) {
            return $playStatus;
        }else{
            return false;
        }
    }


	public static function week_between_two_dates($date1, $date2)
    {
        $first = DateTime::createFromFormat('Y-m-d', $date1);
        $second = DateTime::createFromFormat('Y-m-d', $date2);
        if($date1 > $date2) return Custom::week_between_two_dates($date2, $date1);
        return floor($first->diff($second)->days/7);
    }

	public static function sunday_between_two_dates($date1, $date2)
    {
        $start = new DateTime($date1);
        $end = new DateTime($date2);
        $days = $start->diff($end, true)->days;
        return intval($days / 7) + ($start->format('N') + $days % 7 >= 7);
    }
     public static function total_days_between_two_dates($date1, $date2)
    {
        $start = new DateTime($date1);
        $end = new DateTime($date2);
        return $start->diff($end, true)->days+1;

    }

	

	 public static function getLastSixMonthAverage($endDate, $type='PAID')
    {
        $final = array();
        $file_name = "weekly_report_of_$type"."_$endDate";
        if(!$final = json_decode(WebservicesFunction::readJson($file_name,"reports"),true)){

            $join_type = ($type=='PAID')?' JOIN ':" LEFT JOIN ";
            $searchEndDate =  date('Y-m-d', strtotime($endDate. ' - 1 Days'));
            $last7dayDate =  date('Y-m-d', strtotime($endDate. ' - 6 Days'));
            $lastWeekdayDate =  date('Y-m-d', strtotime($endDate. ' - 13 Days'));
            $lastWeekdayEnd =  date('Y-m-d', strtotime($lastWeekdayDate. ' 6 Days'));

            $startDate =  date('Y-m-d', strtotime($endDate. ' - 6 Months'));

            $lastSixMonthSunday = Custom::sunday_between_two_dates($startDate, $endDate);
            $lastSixMonthTotalDays = Custom::total_days_between_two_dates($startDate, $endDate);
            $sixMonthsAvg = $lastSixMonthTotalDays-$lastSixMonthSunday;

            $lastWeekSunday = Custom::sunday_between_two_dates($lastWeekdayDate, $lastWeekdayEnd);

            $lastWeekTotalDays = Custom::total_days_between_two_dates($lastWeekdayDate, $lastWeekdayEnd);
            $lastWeekAvg = $lastWeekTotalDays-$lastWeekSunday;


            $lastSixDaysSunday = Custom::sunday_between_two_dates($last7dayDate, $endDate);
            $lastSixDaysTotalDays = Custom::total_days_between_two_dates($last7dayDate, $endDate);
            $sixDaysAvg = $lastSixDaysTotalDays-$lastSixDaysSunday;



            $finalArray = array();
            $query = "SELECT final.thinapp_id, final.app_name, sum(final.last_six_months) as last_six_months, SUM(final.last_six_days) as last_six_days, SUM(final.last_week_days) as last_week_days from ( ";
            $query .= "(SELECT acss.thinapp_id, t.name as app_name, count(acss.id) as last_six_months, SUM(if(DATE(acss.appointment_datetime) >= '$last7dayDate',1,0)) as last_six_days, SUM(if( (DATE(acss.appointment_datetime) >= '$lastWeekdayDate' && DATE(acss.appointment_datetime) <= '$lastWeekdayEnd'),1,0)) as last_week_days from appointment_customer_staff_services as acss $join_type booking_convenience_fee_details AS bcfd on bcfd.tx_status IN('SUCCESS','PAYMENT_SUCCESS') and bcfd.status='ACTIVE' and bcfd.appointment_customer_staff_service_id =acss.id join thinapps as t on t.id = acss.thinapp_id and t.status='ACTIVE' LEFT JOIN app_enable_functionalities AS aef ON aef.thinapp_id = acss.thinapp_id AND aef.app_functionality_type_id=52 WHERE DATE(acss.appointment_datetime) BETWEEN '$startDate' AND '$searchEndDate' GROUP BY acss.thinapp_id order by last_six_months desc, last_six_days desc )";
            $query .= " UNION ALL ";
            $query .= "(SELECT acss.thinapp_id, t.name as app_name, count(acss.id) as last_six_months, SUM(if(DATE(acss.appointment_datetime) >= '$last7dayDate',1,0)) as last_six_days, SUM(if( (DATE(acss.appointment_datetime) >= '$lastWeekdayDate' && DATE(acss.appointment_datetime) <= '$lastWeekdayEnd'),1,0)) as last_week_days from appointment_customer_staff_services_archive as acss $join_type booking_convenience_fee_details AS bcfd on bcfd.tx_status IN('SUCCESS','PAYMENT_SUCCESS') and bcfd.status='ACTIVE' and bcfd.appointment_customer_staff_service_id =acss.id join thinapps as t on t.id = acss.thinapp_id and t.status='ACTIVE' LEFT JOIN app_enable_functionalities AS aef ON aef.thinapp_id = acss.thinapp_id AND aef.app_functionality_type_id=52 WHERE DATE(acss.appointment_datetime) BETWEEN '$startDate' AND '$searchEndDate' GROUP BY acss.thinapp_id order by last_six_months desc, last_six_days desc)";
            $query .= " ) AS final GROUP BY final.thinapp_id order by final.last_six_months desc, final.last_six_days desc ";

        
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $list = mysqli_fetch_all($service_message_list,MYSQLI_ASSOC);
                foreach ($list as $key => $val){
                    $final[$key]= $val;
                    $final[$key]['six_month_avg']= $months_avg = sprintf('%0.2f', ($val['last_six_months']/$sixMonthsAvg));
                    $final[$key]['six_day_avg']= $days_avg= sprintf('%0.2f', ($val['last_six_days']/$sixDaysAvg));
                    $final[$key]['last_week_avg']= $week_avg= sprintf('%0.2f', ($val['last_week_days']/$lastWeekAvg));
                    $final[$key]['percentage']= sprintf('%0.2f', ((($days_avg*100)/$months_avg))-100);
                    $final[$key]['colour']='green';
                    if($final[$key]['percentage'] <= 0){
                        $final[$key]['colour']='red';
                    }
                }
                WebservicesFunction::createJson($file_name,json_encode($final),"CREATE","reports");
            }

        }
        return $final;

    }

    public static function getLastSixMonthAverageMpass($endDate)
    {
        $final = array();

        $file_name = "weekly_report_of_VISITOR"."_$endDate";
        if(!$final = json_decode(WebservicesFunction::readJson($file_name,"reports"),true)){
            $endDateParam = date('m-d-Y',strtotime($endDate));

            $last7dayDate =  date('Y-m-d', strtotime($endDate. ' - 6 Days'));
            $startDate =  date('Y-m-d', strtotime($endDate. ' - 6 Months'));

            $lastWeekdayDate =  date('Y-m-d', strtotime($endDate. ' - 13 Days'));
            $lastWeekdayEnd =  date('Y-m-d', strtotime($lastWeekdayDate. ' 6 Days'));


            $lastSixMonthSunday = Custom::sunday_between_two_dates($startDate, $endDate);
            $lastSixMonthTotalDays = Custom::total_days_between_two_dates($startDate, $endDate);
            $sixMonthsAvg = $lastSixMonthTotalDays-$lastSixMonthSunday;
            $lastSixDaysSunday = Custom::sunday_between_two_dates($last7dayDate, $endDate);
            $lastSixDaysTotalDays = Custom::total_days_between_two_dates($last7dayDate, $endDate);
            $sixDaysAvg = $lastSixDaysTotalDays-$lastSixDaysSunday;

            $lastWeekSunday = Custom::sunday_between_two_dates($lastWeekdayDate, $lastWeekdayEnd);
            $lastWeekTotalDays = Custom::total_days_between_two_dates($lastWeekdayDate, $lastWeekdayEnd);
            $lastWeekAvg = $lastWeekTotalDays-$lastWeekSunday;
            $finalArray = $listArray = array();

            $datesArray =array(
                'last_six_days'=>array('f'=>$last7dayDate,'e'=>$endDate),
                'last_six_months'=>array('f'=>$startDate,'e'=>$endDate),
                'last_week_days'=>array('f'=>$lastWeekdayDate,'e'=>$lastWeekdayEnd)
            );


            foreach ($datesArray as $label_key =>$dataArray){

                $fromDate = date('m-d-Y',strtotime($dataArray['f']));
                $endDateSearch = date('m-d-Y',strtotime($dataArray['e']));

                $url = "https://m-pass.azurewebsites.net/Pass/GetTotalNumberOfPassesForAllLocaiton?validFrom=$fromDate&validTill=$endDateSearch";
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if (!$err) {
                    $list = json_decode($response,true);
                    if(!empty($list)){
                        foreach ($list as $listKye =>$data){
                            $listArray[$data['LocationName']]["app_name"] = $data['LocationName'];
                            $listArray[$data['LocationName']][$label_key] = $data['TotalNumberOfPasses'];
                        }
                    }
                }
            }


            foreach ($listArray as $key => $val){
                $val['last_six_days'] = isset($val['last_six_days'])?$val['last_six_days']:0;
                $val['last_six_months'] = isset($val['last_six_months'])?$val['last_six_months']:0;
                $val['last_week_days'] = isset($val['last_week_days'])?$val['last_week_days']:0;
                $final[$key]= $val;
                $final[$key]['six_month_avg']= $months_avg = sprintf('%0.2f', ($val['last_six_months']/$sixMonthsAvg));
                $final[$key]['six_day_avg']= $days_avg= sprintf('%0.2f', ($val['last_six_days']/$sixDaysAvg));
                $final[$key]['last_week_avg']= $week_avg= sprintf('%0.2f', ($val['last_week_days']/$lastWeekAvg));
                $final[$key]['percentage']= sprintf('%0.2f', ((($days_avg*100)/$months_avg))-100);
                $final[$key]['colour']='green';
                if($final[$key]['percentage'] <= 0){
                    $final[$key]['colour']='red';
                }
            }
            WebservicesFunction::createJson($file_name,json_encode($final),"CREATE","reports");



        }
        return $final;

    }




    public static function createWebAppUrl($thin_app_id,$doctor_id,$mobile,$uhid,$department_category_id)
    {
        $web_app_link = "";
        if($department_category_id==32){
            $fortis_tracker_path = SITE_PATH."tracker/fortis?sh=y&t=".base64_encode($thin_app_id)."&pm=".base64_encode($mobile);
            $web_app_link = Custom::short_url($fortis_tracker_path,$thin_app_id);
        }else{
            if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
                       $web_app_link = Custom::short_url(SITE_PATH."tracker/fortis?sh=y&t=".base64_encode($doctor_id)."&pm=".base64_encode($mobile),$thin_app_id);
        
            }else{
                $web_app_link = Custom::short_url(SITE_PATH."doctor/index/?t=".base64_encode($doctor_id)."&uh=".base64_encode($uhid)."&ti=".base64_encode($thin_app_id)."&pm=".base64_encode($mobile)."&tracker=web_tracker_url",$thin_app_id);
            }
        }
        return $web_app_link;
    }

    public static function checkSlotHasPendingToken($thin_app_id,$doctor_id,$address_id,$service_id,$slot)
    {
        $condition = " thinapp_id = $thin_app_id and appointment_staff_id = $doctor_id and appointment_service_id = $service_id and appointment_address_id = $address_id and status IN('NEW','CONFIRM','RESCHEDULE') AND DATE(appointment_datetime) = DATE(NOW()) AND slot_time = '$slot' and reminder_message ='TOKEN_PENDING' ";
        $query =  "select id from appointment_customer_staff_services  where  $condition LIMIT 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return true;
        } else {
            return false;
        }
    }

	public static function convert_number($number)
    {
        if (($number < 0) || ($number > 999999999))
        {
            throw new Exception('Number is out of range');
        }
        $giga = floor($number / 1000000);
        // Millions (giga)
        $number -= $giga * 1000000;
        $kilo = floor($number / 1000);
        // Thousands (kilo)
        $number -= $kilo * 1000;
        $hecto = floor($number / 100);
        // Hundreds (hecto)
        $number -= $hecto * 100;
        $deca = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $result = '';
        if ($giga)
        {
            $result .= Custom::convert_number($giga) .  'Million';
        }
        if ($kilo)
        {
            $result .= (empty($result) ? '' : ' ') .Custom::convert_number($kilo) . ' Thousand';
        }
        if ($hecto)
        {
            $result .= (empty($result) ? '' : ' ') .Custom::convert_number($hecto) . ' Hundred';
        }
        $ones = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eightteen', 'Nineteen');
        $tens = array('', '', 'Twenty', 'Thirty', 'Fourty', 'Fifty', 'Sixty', 'Seventy', 'Eigthy', 'Ninety');
        if ($deca || $n) {
            if (!empty($result))
            {
                $result .= ' and ';
            }
            if ($deca < 2)
            {
                $result .= $ones[$deca * 10 + $n];
            } else {
                $result .= $tens[$deca];
                if ($n)
                {
                    $result .= '-' . $ones[$n];
                }
            }
        }
        if (empty($result))
        {
            $result = 'zero';
        }
        return $result;
    }

	 public static function getVoiceFileName($current_token,$doctor_name,$thin_app_id=null){
        if($doctor_name=="Dr. Paras Choudhary"){
            $doctor_name = "डॉक्टर पारस चौधरी";
        }
        $kaksh = "कक्ष";
        if(Custom::isCustomizedAppId($thin_app_id)){
            $number = preg_replace("/[^0-9]/", "", $doctor_name );
            						$counter_array=array('1'=>"one",'2'=>"two",'3'=>"three",'4'=>"four",'5'=>"five",'6'=>'six','7'=>"seven",'8'=>"eight",'9'=>'nine','0'=>'zero');
            
            $number = isset($counter_array[$number])?$counter_array[$number]:$doctor_name;
            $voiceText = ("टोकन नंबर $current_token | काउंटर number | $number पर आयें |");
        	
        	if($thin_app_id==902 && in_array(strtoupper($doctor_name),array('A-41','B-42','C-43'))){
                $current_token = Custom::convert_number($current_token);
                $kaksh = "room";
                $doc_num = str_replace("-"," ",$doctor_name);
                $voiceText = ("टोकन नंबर $current_token | ultra sound  | $doc_num room में आयें |");
            }

            if($thin_app_id==902 && !in_array(strtoupper($doctor_name),array('A-41','B-42','C-43'))){
                $current_token = Custom::convert_number($current_token);
                $kaksh = "room";
                $doc_num = str_replace("-"," ",$doctor_name);
                $voiceText = ("टोकन नंबर $current_token |");
            }
        
        	if($thin_app_id==905){
                $current_token = Custom::convert_number($current_token);
                $voiceText = ("कृपया ध्यान दें | टोकन नंबर $current_token | room number $number में आयें |");
            }
        
        	 if($thin_app_id==908){
                 $current_token = Custom::convert_number($current_token);
                preg_match_all('/\d+/', $doctor_name, $matches);
                $numbers = implode("", $matches[0]);
                $numbersArray = str_split($numbers);
                $tmp_string = array();
                foreach ($numbersArray as $num) {
                    $tmp_string[] = $counter_array[intval($num)];
                }
                $number = implode(" ",$tmp_string);
                $voiceText = ("कृपया ध्यान दें | टोकन नंबर $current_token | room number $number में आयें |");
            }
        
        	 if($thin_app_id==607){
                $current_token = Custom::convert_number($current_token);
                $voiceText = ("कृपया ध्यान दें | टोकन नंबर $current_token | डॉक्टर दिनेश खण्डेलवाल के कक्ष में आयें |");
            }
        

        }else{
            $voiceText = ("कृपया ध्यान दें | टोकन नंबर $current_token $doctor_name के केबिन में आयें |");
        }

        if(strtoupper($doctor_name)=="USG"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token सोनोग्राफी $kaksh में आयें |");
        }else if(strtoupper($doctor_name)=="USG-1"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token सोनोग्राफी $kaksh एक में आयें |");
        }else if(strtoupper($doctor_name)=="USG-2"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token सोनोग्राफी $kaksh दो में आयें |");
        }else if(strtoupper($doctor_name)=="X-RAY"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token एक्स-रे $kaksh में आयें |");
        }else if(strtoupper($doctor_name)=="X-RAY-1"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token एक्स-रे $kaksh एक में आयें |");
        }else if(strtoupper($doctor_name)=="X-RAY-2"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token एक्स-रे $kaksh दो में आयें |");
        }else if(strtoupper($doctor_name)=="CT-SCAN"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token CT-scan $kaksh में आयें |");
        }else if(strtoupper($doctor_name)=="MRI"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token MRI $kaksh में आयें |");
        }else if(strtoupper($doctor_name)=="PHARMACY"){
            $voiceText = ("कृपया ध्यान दें | टोकन  नंबर $current_token फार्मेसी काउंटर पे आएँ |");
        }



        $fileName = md5($voiceText) . ".wav";
        if($thin_app_id=="897"){
            $fileName = md5($voiceText) . ".mp3";
        }
        
     	if($thin_app_id=="907"){
            $fileName = "fortis_jaipur.mp3";
        }

        $filePath = LOCAL_PATH . "app/webroot/queue_tracker_voices/$fileName";
        $add_voice_file = false;
        if (!file_exists($filePath)) {
            $voiceText = urlencode($voiceText);
            
            $numeric = ($thin_app_id=='607')?"hcurrency":"currency";
        
            $url = "http://ivrapi.indiantts.co.in/tts?type=indiantts&text=$voiceText&api_key=f07989c0-cc36-11ec-a24c-514a8fe2a4ec&user_id=145392&action=play&&numeric=$numeric&&lang=hi_female_v1&&ver=2";

            $content = file_get_contents($url);
            if ($content === false) {} else {
                if (file_put_contents($filePath, $content) !== false) {
                    $add_voice_file = true;
                }
            }

        } else {
            $add_voice_file = true;
        }

        if ($add_voice_file) {
            return $fileName;
        }else{
            return false;
        }



    }

	public static function emitTokenPrint($data){
        try{
            $version = new Version2X(TOKEN_SOCKET_URL);
                $elephant = new ElephantIO\Client($version);
                $elephant->initialize();
                $res = $elephant->emit('printToken',$data);
                $elephant->close();            
        }catch(Exception $e){
        }
        return true;
    }

	public static function emitSocet($data){

        try{

            $thin_app_id =$data['thin_app_id'];
            if(!isset($data['reload'])){
                $data['reload'] = false;
                $data['dti_token'] = "";
            }

            if(!isset($data['play'])){
                $data['play'] = true;
            }

            $data['billingTokenString'] = "";
            $data['bookedTokenString'] = "";
            $data['activeTokens'] = "";
            $doctorData = Custom::get_doctor_by_id($data['doctor_id']);
			$data['associate_counter_id'] = $doctorData['show_token_into_digital_tud'];
            if(Custom::isCustomizedAppId($thin_app_id)){
                if(EHCC_APP_ID==$thin_app_id){
                    $data['billingTokenString']  = Custom::getBillingCounterTokenString($thin_app_id);
                }
                $data['bookedTokenString']  = Custom::getBookedTokenString($doctorData['show_token_into_digital_tud']);
                if(isset($data['doctor_ids']) && !empty($data['doctor_ids'])){
                    $doctor_ids = explode(",",$data['doctor_ids']);
                    $doctor_tokens =array();
                    foreach ($doctor_ids as $key => $d_id){
                        $res = Custom::fortisGetCurrentToken($thin_app_id,$d_id);
                        $doctorData = Custom::get_doctor_by_id($d_id,$thin_app_id);
                        if(!empty($res['current']['token_number']) && $data['associate_counter_id'] == $doctorData['show_token_into_digital_tud']){
                            $doctor_tokens[] = array('dId'=>$d_id,'tNumber'=>$res['current']['token_number'],'dName'=>$doctorData['name']);
                        }
                    }
                    $data['activeTokens']  =  json_encode($doctor_tokens);
                }
            }


            $res = Custom::fortisGetCurrentToken($data['thin_app_id'],$data['doctor_id']);
            $data['token'] = $token = $res['current']['token_number'];

            $data['patient_name'] = $res['current']['patient_name'];
 			$doctorData = Custom::get_doctor_by_id($data['doctor_id']);
            $doctor_name = $doctorData['name'];
            $data['doctor_name'] = $doctor_name;
            $fileName = Custom::getVoiceFileName($token,$doctor_name,$data['thin_app_id']);
            if (!empty($fileName)){
                $data['fileName'] =$fileName;
                $version = new Version2X(TOKEN_SOCKET_URL);
                $elephant = new ElephantIO\Client($version);
                $elephant->initialize();
                $res = $elephant->emit('updateToken',$data);
                $elephant->close();
            }
        }catch(Exception $e){

        }

        return true;
    }

    public static function createBookingArray($bookData,$break_end = "NO")
    {
        $return = array(
            'id'=>$bookData['appointment_id'],
            'appointment_id'=>$bookData['appointment_id'],
            'patient_name'=>$bookData['appointment_patient_name'],
            'total_billing'=>$bookData['total_amount'],
            'booking_payment_type'=>$bookData['booking_payment_type'],
            'is_paid_booking_convenience_fee'=>$bookData['is_paid_booking_convenience_fee'],
            'consulting_type'=>$bookData['consulting_type'],
            'sub_token'=>$bookData['sub_token'],
            'customer_id'=>$bookData['customer_id'],
        	'customer_type'=>$bookData['customer_type'],
            'booking_validity_attempt'=>$bookData['booking_validity_attempt'],
            'appointment_datetime'=>$bookData['appointment_datetime'],
            'medical_product_order_id'=>$bookData['medical_product_order_id'],
            'has_token'=>$bookData['has_token'],
            'emergency_appointment'=>$bookData['emergency_appointment'],
            'custom_token'=>$bookData['custom_token'],
            'amount'=>$bookData['amount'],
            'payment_status'=>$bookData['payment_status'],
            'uhid'=>$bookData['uhid'],
            'mobile'=>$bookData['mobile'],
            'queue_number'=>$bookData['queue_number'],
            'status'=>in_array($bookData['booking_status'],array("NEW","CONFIRM","RESCHEDULE"))?"BOOKED":$bookData['booking_status'],
            'appointment_status'=>$bookData['booking_status'],
            "slot"=>$bookData['slot_time'],
            "time"=>$bookData['slot_time'],
            "break_end"=>$break_end
        );
        return $return;
    }

	public static function insertTokenLog($thin_app_id,$doctor_id,$login_id,$flag){

        if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
            $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
            $remark = $res['current']['patient_name'];
            $appointment_id = $res['current']['appointment_id'];
            if(!empty($appointment_id)){
                $file_name = "dti_login_".$login_id;
                if($login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true)){
                    $counterData = Custom::get_doctor_by_id($login_data['doctor_id']);
                }else{
                    $counterData = Custom::get_doctor_by_id($login_id);
                }
                $counter = $counterData['name'];
                $created = Custom::created();
                $connection = ConnectionUtil::getConnection();
                $sql = "INSERT INTO counter_patient_logs (thinapp_id,created_by,appointment_id,flag,counter,remark,created) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sssssss', $thin_app_id,$login_id,$appointment_id,$flag,$counter,$remark,$created);
                if ($stmt->execute()) {
                    return true;
                }
                return false;
            }
        }
        return true;
    }

	public static function insertTokenLogCustom($thin_app_id,$doctor_id,$login_id,$flag,$appointment_id,$token,$remark){

        if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_MANAGEMENT_APP')){
            if(!empty($appointment_id)){
                $created = Custom::created();
                $connection = ConnectionUtil::getConnection();
                $sql = "INSERT INTO counter_patient_logs (thinapp_id,created_by,appointment_id,flag,counter,remark,created) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $connection->prepare($sql);
                $stmt->bind_param('sssssss', $thin_app_id,$login_id,$appointment_id,$flag,$token,$remark,$created);
                if ($stmt->execute()) {
                    return true;
                }
                return false;
            }
        }
        return true;
    }


	public static function getRoleDataByDoctorId($thin_app_id,$doctor_id){
        $query = "select staff.staff_type,u.role_id from appointment_staffs as staff join users as u on u.id = staff.user_id where staff.thinapp_id =$thin_app_id and  staff.id = $doctor_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $tmp =  mysqli_fetch_assoc($service_message_list);
            $tmp['staff_type'] = ($tmp['role_id']==5)?'ADMIN':$tmp['staff_type'];
            return $tmp;
        }
        return false;
    }

	 public static function get_thin_app_data_by_slug($slug){

        $connection = ConnectionUtil::getConnection();
        $query = "select * from thinapps  where  slug = '$slug' and status='ACTIVE'";
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);
        }else{
            return false;
        }

    }

    public static function get_staff_by_mobile($mobile,$thin_app_id){
        $query = "select * from appointment_staffs  where status='ACTIVE' AND mobile = '$mobile' and thinapp_id = $thin_app_id limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            $staff_data = mysqli_fetch_assoc($service_message_list);
            return $staff_data;
        }else{
            return false;
        }
    }

	public static function getPharmacyAppointmentId($doctor_id,$token_number){
        $doctor_data = Custom::get_doctor_by_id($doctor_id);
        $bookingCounterId = $doctor_data['show_token_into_digital_tud'];
        $token_number = trim($token_number);
        $query = "select acss.id from appointment_customer_staff_services as acss join appointment_staffs as staff on staff.id = acss.appointment_staff_id where acss.appointment_staff_id = $bookingCounterId and acss.queue_number = '$token_number' and DATE(acss.appointment_datetime) = DATE(NOW()) and acss.`status` IN('NEW','CONFIRM','RESCHEDULE') and staff.`status` ='ACTIVE' LIMIT 1 ";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list)['id'];
        }else{
            return 0;
        }
    }

	public static function getBillingCounterTokenString($thin_app_id)
    {
        $connection = ConnectionUtil::getConnection();
        $query = "select acss.queue_number from appointment_customer_staff_services as acss join appointment_staffs as staff on staff.id = acss.reminder_add_by_id AND staff.counter_booking_type='BILLING' where acss.thinapp_id = $thin_app_id and DATE(acss.appointment_datetime) = DATE(NOW()) and acss.`status` IN('NEW','CONFIRM','RESCHEDULE') order by acss.modified  ";
        $list = $connection->query($query);
        if ($list->num_rows) {
            $final_array = mysqli_fetch_all($list, MYSQLI_ASSOC);
            $tokenList = array_column($final_array,'queue_number');
            return  implode(", ",$tokenList);
        }
        return "";
    }

	public static function getBookedTokenString($doctor_id)
    {
        $connection = ConnectionUtil::getConnection();
        $query = "select acss.queue_number from appointment_customer_staff_services as acss join appointment_staffs as staff on staff.id = acss.appointment_staff_id where DATE(acss.appointment_datetime) = DATE(NOW()) and acss.appointment_staff_id = $doctor_id and acss.`status` IN('NEW','RESCHEDULE','CONFIRM')";
        $list = $connection->query($query);
        if ($list->num_rows) {
            $final_array = mysqli_fetch_all($list, MYSQLI_ASSOC);
            $tokenList = array_column($final_array,'queue_number');
            return implode(",",$tokenList);
        }
        return "";
    }


	public static function deleteDoctorCacheViaServiceId($service_id)
    {
        $connection = ConnectionUtil::getConnection();
        $query = "select ass.appointment_staff_id,ass.thinapp_id from appointment_staff_services as ass where ass.appointment_service_id = $service_id";
        $list = $connection->query($query);
        if ($list->num_rows) {
            $list = mysqli_fetch_all($list, MYSQLI_ASSOC);
            foreach ($list as $key =>$val){
                $res = Custom::delete_doctor_cache($val['appointment_staff_id'],$val['thinapp_id']);
            }
        }
        return true;
    }

	public static function getPaymentCounterId($thin_app_id)
    {
        $query = "select id from appointment_staffs  where thinapp_id = $thin_app_id and  status  = 'ACTIVE' and staff_type ='DOCTOR' and counter_booking_type IN('PAYMENT','BILLING') ";
        $connection = ConnectionUtil::getConnection();
        $list = $connection->query($query);
        if ($list->num_rows) {
            $final_array = mysqli_fetch_all($list, MYSQLI_ASSOC);
            $tokenList = array_column($final_array,'id');
            return implode(",",$tokenList);
        } else {
            return "";
        }
    }


public static function getDoctorVoiceStatus($doctor_id){

        $playStatus ='ACTIVE';
        $file_name = Custom::encrypt_decrypt('encrypt',"voice_status_$doctor_id");
        if(!$playStatus = json_decode(WebservicesFunction::readJson($file_name,"tracker"),true)){
            $connection = ConnectionUtil::getConnection();
            $docSql = "SELECT `status` FROM `active_tracker_voice` WHERE `appointment_staff_id` = '".$doctor_id."' LIMIT 1";
            $docRS = $connection->query($docSql);
            if ($docRS->num_rows) {
                $docData = mysqli_fetch_assoc($docRS);
                $playStatus = $docData['status'];
                WebservicesFunction::createJson($file_name,json_encode($playStatus),"CREATE","tracker");
            }
        }
        if (!empty($playStatus)) {
            return $playStatus;
        }else{
            return "ACTIVE";
        }

    }

	public static function isCustomizedAppId($thin_app_id){
       	if(Custom::check_app_enable_permission($thin_app_id, 'QUEUE_CUSTOMIZATION_APP')){
            return true;
        }
        return false;
    }

	public static function createDtiLoginFile($thin_app_id,$login_id,$counter_id,$IOTLogin=false){
        $staff_data = Custom::get_doctor_by_id($login_id,$thin_app_id);
        $tmp['login_id'] = $login_id;
        $tmp['name']= $staff_data['name'];
        $tmp['mobile']= $staff_data['mobile'];
        $tmp['password']= $staff_data['password'];
        $tmp['token'] =  (date('Y-m-d H:i:s').'_'.rand(1000,99999));
        $tmp['token_name'] =  md5("token_".$staff_data['id']);
        $tmp['doctor_id'] =  $counter_id;
        $thin_app_id = base64_encode($thin_app_id);
        $staff_id= base64_encode($login_id);
        $counter_id= base64_encode($counter_id);
        $tmp['dashboard']  = SITE_PATH."homes/mq_form/$thin_app_id/$staff_id";
        if($IOTLogin==true){
            $tmp['dashboard']  = SITE_PATH."homes/mq_form/$thin_app_id/$staff_id/iot";
        }
    
    	if(isset($staff_data['load_default_screen_on_dti'])){
            if($staff_data['load_default_screen_on_dti'] == "DIGITAL_TUD"){
                $tmp['dashboard']  = SITE_PATH."homes/mq_form/$thin_app_id/$staff_id/iot";
            }else{
                $tmp['dashboard']  = SITE_PATH."homes/mq_form/$thin_app_id/$staff_id";
            }
        }
    
    	if(Custom::check_app_enable_permission(base64_decode($thin_app_id), 'SINGLE_TOKEN_BOOKING_APP')   && $staff_data['staff_type']=='RECEPTIONIST'){
            $tmp['dashboard']  = SITE_PATH."homes/reception_dti/$thin_app_id/$staff_id";
        }
    	
    	
        $file_name = "dti_login_".$login_id;
        WebservicesFunction::createJson($file_name,json_encode($tmp),"CREATE","dti");
        return $tmp;
    }
	
	public static function changeCounterLoginLogoutStatus($thin_app_id,$counter_id,$login_id,$status){
        $status = !empty($status)?$status:'OPEN';
        $token = (Custom::isCustomizedAppId($thin_app_id))?$status:0;
        $res = Custom::fortisSetTokenMenual($thin_app_id,$counter_id,$token);
        $response['data'] = Custom::createDtiLoginFile($thin_app_id,$login_id,$counter_id,true);
        $file_name = "dti_login_".$login_id;
        $token = "";
        if($login_data = json_decode(WebservicesFunction::readJson($file_name,"dti"),true)){
            $token = $login_data['token'];
        }
        $reload = ($status=='CLOSED')?true:false;
        $res = Custom::emitSocet(array('thin_app_id'=>$thin_app_id,'doctor_id'=>$counter_id,'reload'=>$reload,'dti_token'=>$token));

    }

	public static function makeCallToPatientForTokenNumberComming($thin_app_id,$doctor_id){
    	//return false;
        $doctorData = Custom::get_doctor_by_id($doctor_id);
        $doctor_id = Custom::isCustomizedAppId($thin_app_id)?$doctorData['show_token_into_digital_tud']:$doctor_id;
        $res = Custom::fortisGetCurrentToken($thin_app_id,$doctor_id);
        $token_nubmer =$res['current']['token_number'];
        if($token_nubmer > 0){
            $query = "select acss.queue_number, RIGHT(ac.mobile,10) as mobile from appointment_customer_staff_services as acss join appointment_customers as ac on acss.appointment_customer_id = ac.id  where  acss.thinapp_id = $thin_app_id and acss.appointment_staff_id = $doctor_id  AND acss.status IN ('NEW','CONFIRM','RESCHEDULE') and acss.skip_tracker = 'NO' and DATE(acss.appointment_datetime) = DATE(NOW()) AND CAST(acss.queue_number  AS DECIMAL(10,2)) = '$token_nubmer' order by CAST(acss.queue_number  AS DECIMAL(10,2)) asc limit 1";
            $connection = ConnectionUtil::getConnection();
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $data = mysqli_fetch_assoc($service_message_list);
                $patientMobile  =$data["mobile"];
                if($patientMobile != "9999999999"){
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://api.exotel.com/v1/Accounts/mengage/Calls/connect.json');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    $url = "http://my.exotel.com/mengage/exoml/start_voice/579876";
                    curl_setopt($ch, CURLOPT_POSTFIELDS, "From=$patientMobile&CallerId=01141171845&Url=$url");
                    $headers = array();
                    $headers[] = 'Authorization: Basic bWVuZ2FnZTphYWU2YTE4Y2UxOTAyMDg1ZjM1MWFhNzAxOTZiMzE2YTg5N2ZhNDBi';
                    $headers[] = 'Accept: application/json';
                    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    $result = curl_exec($ch);
                    if (curl_errno($ch)) {
                        echo 'Error:' . curl_error($ch);
                    }
                    curl_close($ch);
                }
            }
            return true;
        }
        return false;
    }


	public static function callToPatient($patientMobile,$thin_app_id,$doctor_id,$appointment_id = 0,$call_count_number=1){
        if($patientMobile != "+919999999999"){
            $app_id ="634635";
            $callerId = "01140845349";
            if($thin_app_id==905){
                $app_id ="634635";
                $callerId = "01140845349";
            }

            
            if($thin_app_id==902){
                $app_list['2154'] = "708273";
                $app_list['2149'] = "708269";
                $app_list['2148'] = "708267";
                $app_list['2153'] = "708265";
                $app_list['2151'] = "708263";
                $app_list['2150'] = "708260";
                $app_list['2152'] = "708180";
                $app_id = "708336";
                if(isset($app_list[$doctor_id])){
                    $app_id = $app_list[$doctor_id];
                }
                $callerId = "01140845349";
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.exotel.com/v1/Accounts/mengage/Calls/connect.json');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            $url = "http://my.exotel.com/mengage/exoml/start_voice/$app_id";
            $StatusCallback = "https://mpasscheckin.com/doctor/homes/ivr_callback";
            $data = array(
                'From' => $patientMobile,
                'CallerId' => $callerId,
                'Url' => $url,
                'StatusCallback' => $StatusCallback,
                'CustomField' => $thin_app_id."###".$appointment_id,
            );

            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            $headers = array();
            $headers[] = 'Authorization: Basic bWVuZ2FnZTphYWU2YTE4Y2UxOTAyMDg1ZjM1MWFhNzAxOTZiMzE2YTg5N2ZhNDBi';
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $error_code = curl_errno($ch);
                $error_message = curl_error($ch);
                echo "cURL Error Code: $error_code\n";
                echo "cURL Error Message: $error_message\n";die;
            }

               
            if ($response !== false) {
                $responseData = json_decode($response, true);
                if (isset($responseData['Call']['Sid'])) {
                    $appointment_Data= Custom::get_appointment_by_id($appointment_id);
                    if(!empty($appointment_Data)){
                        $call_sid = $responseData['Call']['Sid'];
                        $patient_mobile = $patientMobile;
                        $doctor_mobile = $callerId;
                        $book_type = "OTHER";
                        $call_back_status = "CALLING";
                        $token_number = $appointment_Data['queue_number'];
                        $connection = ConnectionUtil::getConnection();
                        $created = Custom::created();
                        $sql = "INSERT INTO ivr_call_log (call_back_status, token_no, call_start, call_sid, booking_type,  thinapp_id, patient_mobile, doctor_mobile,  appointment_id, created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param('sssssssssss', $call_back_status, $token_number,$created,$call_sid, $book_type,  $thin_app_id, $patient_mobile, $doctor_mobile,   $appointment_id, $created, $created);
                        $res = $stmt->execute();
                    }
                    $res = Custom::insertTokenLog($thin_app_id,$doctor_id,$doctor_id,"IVR_CALL");
                    curl_close($ch);
                    return true;
                }
                else
                {

                    if($responseData["RestException"]["Status"]==403)
                    {
                         $dnd_status= Custom::exotelDndActivation($patientMobile);
                         if($dnd_status["status"]==true && $call_count_number < 2)
                         {
                            $call_count_number = $call_count_number+1;
                            return Custom::callToPatient($patientMobile,$thin_app_id,$doctor_id,$appointment_id,$call_count_number);
                        }
                    }
                }
            } 
        }
        curl_close($ch);
        return false;
    }

	public static function tokenCanceledCall($patientMobile,$exophone,$appId){
        if($patientMobile != "+919999999999"){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.exotel.com/v1/Accounts/mengage/Calls/connect.json');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            $url = "http://my.exotel.com/mengage/exoml/start_voice/$appId";
            curl_setopt($ch, CURLOPT_POSTFIELDS, "From=$patientMobile&CallerId=$exophone&Url=$url");
            $headers = array();
            $headers[] = 'Authorization: Basic bWVuZ2FnZTphYWU2YTE4Y2UxOTAyMDg1ZjM1MWFhNzAxOTZiMzE2YTg5N2ZhNDBi';
            $headers[] = 'Accept: application/json';
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
        }
        return false;
    }

	public static function get_avg_time_from_array($timeArray){
    
    
	    $totalSeconds = 0;
        foreach ($timeArray as $time) {
            $totalSeconds += strtotime($time) - strtotime('00:00:00');
        }
        $avgSeconds = $totalSeconds / count($timeArray);
        return round($avgSeconds/60);
    }

	public static function get_app_doctor_ids($thin_app_id){

        $file_name = "doctor_ids_$thin_app_id";
        if(!$staff_data = json_decode(WebservicesFunction::readJson($file_name,"doctor"),true)){
            $connection = ConnectionUtil::getConnection();
            $query = "SELECT GROUP_CONCAT(id) as ids FROM `appointment_staffs` where thinapp_id = $thin_app_id AND staff_type='DOCTOR' and status = 'ACTIVE'";
            $service_message_list = $connection->query($query);
            if ($service_message_list->num_rows) {
                $staff_data = mysqli_fetch_assoc($service_message_list);
                $staff_data =$staff_data['ids'];
                WebservicesFunction::createJson($file_name,json_encode($staff_data),"CREATE","doctor");
            }
        }
        if (!empty($staff_data)) {
            return $staff_data;
        }else{
            return false;
        }
    }

	public static function get_last_booked_token_of_app($thin_app_id){
        $query = "select acss.id,acss.queue_number,acss.slot_time from appointment_customer_staff_services as acss where acss.thinapp_id = $thin_app_id and DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status IN ('NEW','CONFIRM','RESCHEDULE')  order by CAST(acss.queue_number AS DECIMAL) desc limit 1";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return mysqli_fetch_assoc($service_message_list);

        } else {
            return false;
        }
    }

public static function get_all_booked_token_of_app($thin_app_id){
        $query = "select acss.queue_number,acss.slot_time from appointment_customer_staff_services as acss where acss.thinapp_id = $thin_app_id and DATE(acss.appointment_datetime) = DATE(NOW()) AND acss.status IN ('NEW','CONFIRM','RESCHEDULE','CLOSED')  order by CAST(acss.queue_number AS DECIMAL) asc";
        $connection = ConnectionUtil::getConnection();
        $service_message_list = $connection->query($query);
        if ($service_message_list->num_rows) {
            return array_column(mysqli_fetch_all($service_message_list, MYSQLI_ASSOC),"queue_number","slot_time");
        } else {
            return false;
        }
    }

public static function exotelDndActivation($number)
{
    $yourAccountId = 'mengage';
    $yourApiToken = 'aae6a18ce1902085f351aa70196b316a897fa40b';
    $phoneNumber = $number;
    $yourSid = "mengage";
    $exotelSubdomain = "api.exotel.com";



    $apiEndpoint = "https://{$exotelSubdomain}/v1/Accounts/{$yourSid}/CustomerWhitelist/";

    $data = array(
        'VirtualNumber' => $phoneNumber, 
        'Number' => $phoneNumber,
        'Language' => 'en'
    );

    // Build the POST data string
    $postData = http_build_query($data);

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $apiEndpoint,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded",
            "Authorization: Basic " . base64_encode("{$yourAccountId}:{$yourApiToken}")
        )
    ));

    // Execute cURL session
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        $response = array("status"=>false,"message"=>"DND acivated","subcode"=>204);
        
    } else {
        
        $xml = new SimpleXMLElement($response);
    
    // Access elements and attributes
    $total = $xml->Result->Total;
    $duplicate = $xml->Result->Duplicate;
    $processed = $xml->Result->Processed;
    $succeeded = (int)$xml->Result->Succeeded;
    $redundant = (int)$xml->Result->Redundant;
    $failed = $xml->Result->Failed;
    $message = $xml->Result->Message;
    // Check if the operation was successful
    if ($succeeded === 1 || ($succeeded === 0 && $redundant > 0)) {
        // Handle success
        $message = $xml->Result->Message;
        $response = array("status"=>true,"message"=>$message,"subcode"=>200);
        
    } else {
        
        $response = array("status"=>false,"message"=>"Operation failed","subcode"=>204);
    }
    
         
    }
    // Close cURL session
    curl_close($curl);
    return $response;

}



}
