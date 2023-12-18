<?php

/*
 * component to create thumbnails by phpThumb 
 *  
 * @author Sebastian Bechtel <kontakt@sebastian-bechtel.info> 
 * @varsion 1.0 
 * @package default 
 */
App::import('Vendor', 'PHPMailer', array('file' => 'PHPMailer/PHPMailerAutoload.php'));


use Aws\S3\S3Client;


class CustomComponent extends Component {



    

    /*updated by mahendra*/
    public function send_otp($param) {
        if(!empty($param)
            && isset($param['mobile'])&& !empty($param['mobile'])
            && isset($param['username']) && !empty($param['username'])
            && isset($param['thinapp_id']) && !empty($param['thinapp_id'])
            && isset($param['verification']) && !empty($param['verification'])

        ) {
            $param['sms_type']="TRANSACTIONAL";
            $username =$param['username'];
            $verification_code =$param['verification'];
            $mobile =$param['mobile'];
               $username = str_replace(' ', '%20', $username);
                $mobile = trim($mobile);
                $verification_code = trim($verification_code);
                $curl_scraped_page = '';
                $message = urldecode($verification_code . "%20is%20your%20verification%20code%20for%20mBroadcast.");
                $param['message']= $message;
                $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroadcast&send_to=" . $mobile;
                $url .= "&msg=" . $verification_code . "%20is%20your%20verification%20code%20for%20mBroadcast.";
                $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
                return $this->saveHistory($curl_scraped_page,$param);
        }else{
            return false;
        }

    }


    /* update by mahendra saini and web service also send message form this function*/
    public function send_message_system($param=array()) {

        if(!empty($param)
            && isset($param['mobile'])&& !empty($param['mobile'])
            && isset($param['message']) && !empty($param['message'])
            && isset($param['thinapp_id']) && !empty($param['thinapp_id'])

        ) {
            $param['sms_type']="PROMOTIONAL";
            $mobile = trim($param['mobile']);
            $message = str_replace(' ', '%20', $param['message']);
            $curl_scraped_page = '';
            $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroad&send_to=" . $mobile;
            $url .= "&msg=" . ($message);
            $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
            curl_close($ch);
            return $this->saveHistory($curl_scraped_page,$param);

        } else{
            return false;
        }
    }


    // this function for send promotional messages
    public function route_sms_api($mobile = null, $messge = null, $type = null, $mask = null) {
        //echo $mobile."<br>";
        //die('sadsadsad');
        //$username = str_replace(' ', '%20', $username);
        $mobile = trim($mobile);
        $messge = str_replace(' ', '%20', $messge);
        $curl_scraped_page = '';
        $url = "http://sms6.routesms.com:8080/bulksms/bulksms?username=" . ROUTE_SMS_API_USERNAME . "&password=" . ROUTE_SMS_API_PASSWORD . "&type=" . $type . "&dlr=1&destination=" . $mobile . "&source=" . $mask . "&message=" . $messge;
        //echo $url ;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // The maximum number of seconds to allow cURL functions to execute.
        //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        //$curl_scraped_page="success | 91123456789 | 22***************-29***************";
        //echo $curl_scraped_page;
        return $curl_scraped_page;
    }


    /* FUNCTION CREATE BY MAHENDRA*/
    function saveHistory($curl_scraped_page,$param){

        $curl_scraped_page = json_decode($curl_scraped_page);
        $mobile = trim($param['mobile']);
        $message = str_replace(' ', '%20', $param['message']);
        $SentSmsDetail = ClassRegistry::init('SentSmsDetail');
        $SentSmsDetail->create();
        $SentSmsDetail->set('receiver_mobile', $mobile);
        $SentSmsDetail->set('message_text',$message);
        if(isset($param['sender_id'])){
            $SentSmsDetail->set('sender_id',$param['sender_id']);
        }
        if(isset($param['receiver_id'])){
            $SentSmsDetail->set('receiver_id',$param['receiver_id']);
        }
        if(isset($param['channel_id'])){
            $SentSmsDetail->set('channel_id',$param['channel_id']);
        }
        if(isset($param['message_id'])){
            $SentSmsDetail->set('message_id',$param['message_id']);
        }
        if($curl_scraped_page){
            $SentSmsDetail->set('status', strtoupper($curl_scraped_page->response->status));
            $SentSmsDetail->set('sms_response_id',$curl_scraped_page->response->id);
            $SentSmsDetail->set('response_detail',$curl_scraped_page->response->details);
        }else{
            $SentSmsDetail->set('status', strtoupper("REQUEST FAIL"));
        }
        $SentSmsDetail->set('thinapp_id', $param['thinapp_id']);
        $SentSmsDetail->set('router_name', 'GUPSHUP');
        $SentSmsDetail->save();
        if(!empty($curl_scraped_page) && strtoupper($curl_scraped_page->response->status)=="SUCCESS"){
            $thin_app_id =  $param['thinapp_id'];
            $sms_type =  $param['sms_type'];
            $sms_static = ClassRegistry::init('AppSmsStatic');
            if($sms_type=="TRANSACTIONAL"){
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_transactional_sms' => 'AppSmsStatic.total_transactional_sms - 1'),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
            }else{
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_promotional_sms' => 'AppSmsStatic.total_promotional_sms - 1'),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
            }
            return $curl_scraped_page;
        }else{
            return false;
        }
    }


    public function send_process_to_background(){
        // buffer all upcoming output
        ob_start();
        // get the size of the output
        $size = ob_get_length();

        // send headers to tell the browser to close the connection
        header("Content-Length: $size");
        header('Connection: close');

        // flush all output

        ob_end_flush();
        ob_flush();
        flush();


        // close current session
        if (session_id()) session_write_close();

        /******** background process starts here ********/
    }

/* this is global function send notifcation to device if app user else send sms */


    /* function add by mahendra*/
    public function send_notification_or_sms_to_device($send_array=array(),$mobile_sms=null){

        $this->send_process_to_background();
        /*   start process for send in backgound*/

        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$send_array['thinapp_id']
            )
        ));
        if (!empty($thinapp)){
            $user_data =  ClassRegistry::init('User')->find('first',array(
                'conditions'=>array(
                    'User.id'=>$send_array['user_id']
                ),
                'fields'=>array('User.firebase_token','User.mobile'),
                'contain'=>false
            ));
            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($user_data['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'to' => $user_data['User']['firebase_token'],
                    'data' => $send_array['data']
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
                $curl_scraped_page = json_decode(curl_exec($ch));
                curl_close($ch);

                if(!empty($curl_scraped_page) && strtoupper($curl_scraped_page->success)==1){


                    return $curl_scraped_page;
                }else{
                    if(!empty($mobile_sms) && !empty($user_data['User']['mobile'])){
                        $param =array(
                            'mobile'=>$user_data['User']['mobile'],
                            'message'=>$mobile_sms,
                            'thinapp_id'=>$send_array['thinapp_id'],
                        );
                        return $this->send_message_system($param);

                    }
                    return false;
                }


            }else{
                if(!empty($mobile_sms) && !empty($user_data['User']['mobile'])){
                    $param =array(
                        'mobile'=>$user_data['User']['mobile'],
                        'message'=>$mobile_sms,
                        'thinapp_id'=>$send_array['thinapp_id'],
                    );
                    return $this->send_message_system($param);

                }
                return false;
            }

        }else{
            return false;
        }

        /*   end process for send in backgound*/

    }






    function getRandomString($length = 6) {
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


    public function getConferenceUrl(){

        $url = Router::url('/',true);
        $url = rtrim($url,'/');
        return $url.":5000/#";
    }
    function getRandomAppKey($length = 8) {
        $validCharacters = "ABCDEFGHIJKLMNOPQRSTUXYVWZ123456789";
        $validCharNumber = strlen($validCharacters);
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return md5(uniqid($result));
    }

    function getUnqiePassword($length = 6) {
        $validCharacters = strtolower("ABCDEFGHIJKLMNOPQRSTUXYVWZ123456789");
        $validCharNumber = strlen($validCharacters);
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $index = mt_rand(0, $validCharNumber - 1);
            $result .= $validCharacters[$index];
        }
        return $result;
    }


    public function send_message_android($device_id, $message) {
        $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => array($device_id),
            'data' => array("message" => $message),
        );

        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        return $result;
        if ($result === FALSE) {
            //echo $result;
            return false;
        } else {
            //echo $result;
            return true;
        }

        // Close connection
        curl_close($ch);
        //echo $result;
    }



    public function GetUserImage($user_id) {
        $user = ClassRegistry::init('User');
        $user_data = $user->find('first', array('conditions' => array('User.id' => $user_id), 'fields' => array('image'), 'recursive' => -1));
        if (!empty($user_data['User']['image']) && isset($user_data['User']['image'])) {
            $user_image = $user_data['User']['image'];
        } else {
            $user_image = SITE_URL . 'img/profile/profile_default.png';
        }
        return $user_image;
    }

    public function GetChannelImage($channel_id) {
        $channel_obj = ClassRegistry::init('Channel');
        $channel = $channel_obj->find('first', array('conditions' => array('Channel.id' => $channel_id), 'fields' => array('Channel.image', 'Channel.user_id'), 'recursive' => -1));

        if (!empty($channel['Channel']['image']) && isset($channel['Channel']['image'])) {
            $channel_image = $channel['Channel']['image'];
        } else {
            if(!empty($channel))
            {
                $channel_image = $this->GetUserImage($channel['Channel']['user_id']);
            }
            else
            {
                $channel_image = "";
            }
        }
        return $channel_image;
    }

    public function GetMessageImage($message_id) {
        $message_obj = ClassRegistry::init('Message');
        $message_data = $message_obj->find('first', array('conditions' => array('Message.id' => $message_id), 'fields' => array('image', 'short_url'), 'recursive' => -1));
        if (!empty($message_data['Message']['image']) && isset($message_data['User']['image'])) {
            $message_image = $message_data['Message']['short_url'];
        } else {
            $message_image = SITE_URL . 'img/message/message_default.png';
        }
        return $message_image;
    }

    public function UserCheck($user_id = null) {
        if (isset($user_id)) {
            $user_obj = ClassRegistry::init('User');
            $user_data = $user_obj->find('first', array('conditions' => array('User.id' => $user_id, 'User.is_verified' => 'Y', 'User.status' => 'Y'), 'fields' => array('id'), 'recursive' => -1));
            if (isset($user_data) && !empty($user_data)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function TokenCheck($token = null) {
        if (isset($token)) {
            $user_obj = ClassRegistry::init('User');
            $user_data = $user_obj->find('first', array('conditions' => array('User.app_key' => $token, 'User.is_verified' => 'Y', 'User.status' => 'Y'), 'fields' => array('id', 'credit'), 'recursive' => -1));
            if (isset($user_data) && !empty($user_data)) {
                return $user_data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function ChannelCheck($channel_name = null, $user_id = null) {
        $channel_obj = ClassRegistry::init('Channel');
        //echo $channel_name; die;
        $channel_name = trim($channel_name);
        if (isset($channel_name)) {
            $channel_data = $channel_obj->find('first', array('conditions' => array('Channel.channel_name LIKE ' => "%" . $channel_name . "%", 'Channel.user_id' => $user_id), 'fields' => array('id', 'is_transactional'), 'recursive' => -1));
            if (isset($channel_data) && !empty($channel_data)) {
                return $channel_data;
            } else {
                return false;
            }
        } else {
            $channel_data = $channel_obj->find('first', array('conditions' => array('Channel.is_default' => 'Y', 'Channel.user_id' => $user_id), 'fields' => array('id', 'is_transactional'), 'recursive' => -1));
            if (isset($channel_data) && !empty($channel_data)) {
                return $channel_data;
            } else {
                return false;
            }
        }
    }




    public function GetField($model = null, $field = null, $is_equal = null) {
        $table_obj = ClassRegistry::init($model);
        $data = $table_obj->find('first', array('conditions' => array('id' => $is_equal), 'fields' => array($field), 'recursive' => -1));
        if (!empty($data) && isset($data)) {
            return $data[$model][$field];
        } else {
            return false;
        }
    }

    public function GetSubscribersCount($channel_id = null) {
        $table_obj = ClassRegistry::init('Subscriber');
        return $table_obj->find('count', array('contaion'=>false,'conditions' => array('channel_id' => $channel_id)));
    }

    public function total_people_intrested($event_id) {
        $table_obj = ClassRegistry::init('EventResponse');
        return $table_obj->find('count', array('conditions' => array('event_id' => $event_id,'response_type NOT IN'=>array('NO','SHARE')), 'contain' => false));
    }

    public function ChannelIsTransactional($channel_id = null) {
        $table_obj = ClassRegistry::init('Channel');
        $data = $table_obj->find('first', array('conditions' => array('Channel.id' => $channel_id), 'fields' => array('id', 'is_transactional'), 'recursive' => -1));
        if (isset($data) && !empty($data)) {
            return $data['Channel']['is_transactional'];
        } else {
            return false;
        }
    }



    public function get_event_enum_values()
    {
        $table = ClassRegistry::init('EventResponse');
        $query = "SELECT COLUMN_TYPE FROM information_schema.`COLUMNS` WHERE TABLE_NAME = 'event_responses' AND COLUMN_NAME = 'response_type'" ;
        $row = $table->query( $query );
        $row = $row[0]['COLUMNS'];
        $enum_list = explode(",", str_replace("'", "", substr($row['COLUMN_TYPE'], 5, (strlen($row['COLUMN_TYPE'])-6))));
        $pos = array_search('SHARE', $enum_list);
        unset($enum_list[$pos]);
        return $enum_list;
    }


    function get_bitly_short_url($url) {
        $connectURL = 'https://api-ssl.bitly.com/v3/shorten?access_token=2f64066357a40fd75971679e4f07a9dbdec4283d&longUrl=' . $url;

        $ch = curl_init($connectURL);
        //$timeout = 30;

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($data, true);
        return $result['data']['url'];
    }

    /* returns expanded url */

    function get_bitly_long_url($url, $login, $appkey, $format = 'txt') {
        $connectURL = 'http://api.bit.ly/v3/expand?login=' . $login . '&apiKey=' . $appkey . '&shortUrl=' . urlencode($url) . '&format=' . $format;
        return $connectURL;
    }

    // Send information to Google
    function Googleshortenurl($url, $shorten = true) {

        $apiURL = "https://www.googleapis.com/urlshortener/v1/url";
        // Create cURL
        $ch = curl_init();
        // If we're shortening a URL...
        if ($shorten) {
            curl_setopt($ch, CURLOPT_URL, $apiURL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("longUrl" => $url)));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        } else {
            curl_setopt($ch, CURLOPT_URL, $apiURL . '&shortUrl=' . $url);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Execute the post
        $result = curl_exec($ch);
        // Close the connection
        curl_close($ch);
        // Return the result
        $result1 = json_decode($result, true);
        return $result1['id'];
    }




    public function getTotalRemainingSms($thin_app_id,$message_type="P")
    {

        $get_app_user = ClassRegistry::init("Thinapp")->find("first", array(
            "conditions" => array(
                "Thinapp.id" => $thin_app_id
            ),
            'contain' => false,
            'fields'=>array('Thinapp.user_id')
        ));
        if(!empty($get_app_user)){
            $apkTable = ClassRegistry::init('AppSmsStatic');
            $queries = $apkTable->find('first',array(
                'conditions'=>array(
                    'AppSmsStatic.user_id'=>$get_app_user['Thinapp']['user_id']
                ),
                "contain"=>false
            ));
            if(!empty($queries)){
                if($message_type=="P"){
                    return $queries['AppSmsStatic']['total_promotional_sms'];
                }else{
                    return $queries['AppSmsStatic']['total_transactional_sms'];
                }
            }
        }else{
            return false;
        }

    }


    public function compressImage($source,$quality){

            $name = $source['name'];
            $size = $source['size'];
            $source = $source['tmp_name'];
            $info = getimagesize($source);
            $rand_name = "app".rand(100000,1000000).$name;
            $uploaddir = WWW_ROOT."uploads".DS."app".DS;
            $destination = $uploaddir.$rand_name;
            if($size > (200 * 1024)){
                if ($info['mime'] == 'image/jpeg')
                    $image = imagecreatefromjpeg($source);
                elseif ($info['mime'] == 'image/gif')
                    $image = imagecreatefromgif($source);

                elseif ($info['mime'] == 'image/png')
                    $image = imagecreatefrompng($source);
                imagejpeg($image, $destination, $quality);
                return $destination;
            }else{
                return $source;
            }
    }

    public function getFileType($file_name)
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

    public function uploadFileToAws($obj=null) {

        $tmp_filename = $obj['tmp_name'];
        $type = $obj['type'];
        $name = explode(".",$obj['name']);
        $typeArray = explode("/",$obj['type']);
        if(isset($typeArray[0]) && strtoupper($typeArray[0])=="IMAGE"){
            $key = "image/";
            //$tmp_filename = $this->compressImage($obj,100);
        }else if(isset($typeArray[0]) && strtoupper($typeArray[0])=="VIDEO"){
            $key = "video/";
        }else if(isset($typeArray[0]) && strtoupper($typeArray[0])=="AUDIO"){
            $key = "audio/";
        }else{
            $key = "other/";
        }
        $key ="";

        $ext = end($name);
        $new_file_name = $key.date('YmdHis').rand().".".$ext;
        try{
            $bucket = AWS_BUCKET;
            $option = unserialize(AWS);
            $s3 = new Aws\S3\S3Client($option);
            $result = $s3->putObject(array(
                'Bucket'       => $bucket,
                'Key'          => $new_file_name,
                'SourceFile'   => $tmp_filename,
                'LocationConstraint' => $option['region'],
                'ContentType'  => $type,
                'ACL'          => 'public-read',
                /*'StorageClass' => 'REDUCED_REDUNDANCY',*/
                'StorageClass' => 'STANDARD'

            ));
            if($result['@metadata']['statusCode']==200){
                /* remove resized image from server */
                if($obj['tmp_name'] != $tmp_filename && file_exists($tmp_filename)){
                    @chown($tmp_filename,0777);
                    @unlink($tmp_filename);
                }
                //return AWS_DEFAULT_PATH.$new_file_name;
                return $result['@metadata']['effectiveUri'];
            }else{
                return false;
            }
        }catch (Exception $e){

            return false;
        }

    }


    public function deleteFileToAws($keyname) {

        try{
            $bucket = AWS_BUCKET;
            $option = unserialize(AWS);
            $s3 = new Aws\S3\S3Client($option);
            $result = $s3->deleteObject(array(
                'Bucket' => $bucket,
                'Key'    => $keyname
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

    public function deleteMultipleFileToAws($keys =array()) {
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


    public function send_curl_request($url = null, $params = array()) {

        $username = 'MAZWM3Y2NJYZYWNGZHM2';
        $password = 'NzM3MjIzMWVmM2ZhNGQ5MGQzNGRjNzQyYjZlZjA2';
        $process = curl_init($url);
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($process, CURLOPT_HEADER, 1);
        curl_setopt($process, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $params);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);
        curl_close($process);
        echo $return;
        die;
    }




    public function RouteGetReferenceId($file_url = null) {
        $result = '';
        $url = "http://www.routevoice.com//httpApi/getAudio.php?user=" . ROUTE_VOICE_API_USERNAME . "&pwd=" . ROUTE_VOICE_API_PASSWORD . "&path=" . $file_url;
        //echo $url ;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // The maximum number of seconds to allow cURL functions to execute.
        //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        //$curl_scraped_page="success | 91123456789 | 22***************-29***************";
        //echo $curl_scraped_page;
        if (isset($result)) {
            if ($result == '21010' || $result == '21011' || $result == '21012' || $result == '21013' || $result == '21014' || $result == '21015' || $result == '23010') {
                return false;
            } else {
                $res = explode('||', $result);
                if ($res[0] == '23001') {
                    return $res[1];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public function SaveImageFromUrl($url = null, $type = null) {

        //$url = 'http://root.s3.s3.amazonaws.com/channel/ch_813.jpg';
        $extension = substr(strrchr($url, '.'), 1);
        //file_put_contents($img, file_get_contents($url));
        $img_data = file_get_contents($url);
        //echo $img_data; die;
        $new_file_name = $type . "/letout_" . time() . "." . $extension;
        $resp = $this->s3upload($img_data, $new_file_name);
        pr($resp);
        die;
    }


    public function getResponseMessage($code=null){
        if($code==-1){
            return "Invalid api key.";
        }else if($code==-2){
            return "Sorry this app is not activated.";
        }else if($code==-3){
            return "Sorry this app is not registered.";
        }
        return "Registered app";
    }



    public function CheckIsValidApp($key = null,$thin_app_id=null) {

        $app_key = APP_KEY;
        if($app_key!=$key){
            return -1;
        }
        return 1;
    }
   /* public function CheckIsValidAppForDashboard($key = null,$thin_app_id=null) {
        $app_key = Configure::read('APP_KEY');
        $thinAppModal = ClassRegistry::init('Thinapp');
        $settings = $thinAppModal->find("first", array('fields'=>array('Thinapp.status'),'contain'=>false,"conditions" => array("Thinapp.id" => $thin_app_id)));
        if($app_key!=$key){
            return -1;
        }
        else if (!empty($thin_app_id)){
            if (!empty($settings) && isset($settings)) {
                if($settings['Thinapp']['status']=='INACTIVE') {
                    return -2;
                }
            } else {
                return -3;
            }
        }
        return 1;
    }*/

    public function make_thumb($src, $dest, $extension, $desired_width) {
        /* read the source image */
        //$source_image = imagecreatefromjpeg($src);
        if ($extension == "jpg" || $extension == "jpeg" || $extension == "JPG" || $extension == "JPEG") {
            $source_image = imagecreatefromjpeg($src);
        } else if ($extension == "png" || $extension == "PNG") {
            $source_image = imagecreatefrompng($src);
        } else {
            $source_image = imagecreatefromgif($src);
        }

        $width = imagesx($source_image);
        $height = imagesy($source_image);

        /* find the "desired height" of this thumbnail, relative to the desired width  */
        $desired_height = floor($height * ($desired_width / $width));

        /* create a new, "virtual" image */
        $virtual_image = imagecreatetruecolor($desired_width, $desired_height);

        /* copy source image at a resized size */
        imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

        /* create the physical thumbnail image to its destination */
        imagejpeg($virtual_image, $dest);
    }

    public function CalculateCharge($channel_id = null, $msg_type = null, $subscriber_id = null) {
        if (isset($channel_id)) {
            $subscriber_obj = ClassRegistry::init('Subscriber');

            //$user_obj = ClassRegistry::init('User');
            //$user_obj->unbindModel(array('belongsTo' => array("Role")));
            //$subscriber_obj->bindModel(array('hasOne' => array('User' => array('foreignKey' => false, 'conditions' => array('User.mobile=Subscriber.mobile', 'Subscriber.channel_id' => 1)))));
            //$subscriber_obj->unbindModel(array('belongsTo' => array("MessageQueue")));
            if (isset($subscriber_id)) {
                $conditions = array('Subscriber.id' => $subscriber_id);
            } else {
                $conditions = array('Subscriber.channel_id' => $channel_id, 'Subscriber.status' => array("SUBSCRIBED", "INVITED"));
            }

            // print_r($subscriber_obj);
            //die("asdasd");
            $subscribers = $subscriber_obj->find('all', array(
                    'joins' =>
                        array(
                            array('table' => 'users', 'alias' => 'User', 'type' => 'left', 'foreignKey' => false,
                                'conditions' => array('User.mobile = Subscriber.mobile')
                            )
                        ),
                    'conditions' =>  $conditions,
                    'fields' => array('Subscriber.id', 'Subscriber.mobile', 'User.id', 'User.mobile', 'User.device_token'),
                    'recursive' => 0
                )
            );


            $charge = 0;
            //echo "<pre>"; print_r($subscribers);
            if (!empty($subscribers) && isset($subscribers)) {
                foreach ($subscribers as $subscriber) {
                    if ($subscriber['User']['device_token'] != '') {
                        $charge = $charge + PUSH_CHARGE;
                    } else {
                        if ($msg_type == 'audio') {
                            $charge = $charge + CALL_CHARGE;
                        } else {
                            $charge = $charge + SMS_CHARGE;
                        }
                    }
                }
            }
        }
        $charge = 0;
        return $charge;
    }

    public function getDefaultChannelUrl($channel_name = null) {

        $first_characther = strtolower(substr(ltrim($channel_name), 0, 1));
        $asciiVal=  ord($first_characther);
        if($asciiVal > 47 && $asciiVal < 123)
            return "http://root.s3.s3.amazonaws.com/channel/default/channel_" . $first_characther . ".png";
        else
            return "http://mbroadcast.in/img/channel/default/channel_default_img.png";


    }







    /* code add by mahenda 10-09-2016*/
    public function validationError($object){
        foreach ($object as $key => $obj){
            return $obj[0];
        }
    }

    /* code add by mahenda 10-09-2016*/
    public function validApp($key = null) {

        if (isset($key)) {
            $settings = ClassRegistry::init('Users')->find("first", array("conditions" => array("Users.app_key" => $key)));
            if (!empty($settings) && isset($settings)) {
                if($settings['Users']['status']=='Y') {
                    return true;
                }else{
                    return 2;
                }
            } else {
                return false;
            }
        } else {
            return 3;
        }
    }


    /* code add by mahenda 10-09-2016*/
    public function upload_file($file=null,$user_id=null) {



        if (isset($file['tmp_name']) ) {
            $filename = explode(".",$file['name']);
            $post['media_extenstion'] = $ext = end($filename);

            $mime = explode("/",$file['type'])[0];
            $post['media_type'] = ucwords($mime);

            $post['media_name'] = $name =rand()."_".date('YmdHis').$ext;
            $post['media_size'] = $file['size'];
            $post['user_id'] = $user_id;




            $url = Router::url('/uploads/media/').$name;
            $post['media_url'] = $url;
            if(move_uploaded_file($file['tmp_name'],$url)){

                if ($this->Medias->save($post)) {
                    return  $this->Medias->getLastInsertId();
                } else {
                    return false;
                }
            }else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* function add by mahendra*/
    public function checkFavourite($channel_id = null,$mobile=null) {

        $count = ClassRegistry::init('Subscriber')->find("count", array("conditions" => array(
            "Subscriber.mobile" =>$mobile,
            "Subscriber.channel_id" =>$channel_id ,
            "Subscriber.is_favourite" =>'Y'
        )));
        if ($count==0){
            return 'N';
        } else {
            return 'Y';
        }

    }

    /* function add by mahendra*/
    public function is_subscribe($channel_id = null,$mobile=null) {
        $count = ClassRegistry::init('Subscriber')->find("count", array("conditions" => array(
            "Subscriber.mobile" =>$mobile,
            "Subscriber.channel_id" =>$channel_id ,
            "Subscriber.status" =>'SUBSCRIBED'
        )));
        if ($count==0){
            return 0;
        } else {
            return 1;
        }

    }
    /* function add by mahendra*/
    public function total_subscribe($channel_id = null) {
        $count = ClassRegistry::init('Subscriber')->find("count", array("conditions" => array(
            "Subscriber.channel_id" =>$channel_id ,
            "Subscriber.status" =>'SUBSCRIBED'
        )));
        if ($count==0){
            return 0;
        } else {
            return $count;
        }

    }


    /* function add by mahendra*/
    public function check_user_permission($thin_app_id,$lable_key) {
        $is_allow = ClassRegistry::init('UserEnabledFunPermission')->find("first",
            array(
                "conditions" => array(
                    "UserEnabledFunPermission.thinapp_id" =>$thin_app_id,
                    "UserFunctionalityType.label_key" =>$lable_key,
                ),
                'contain'=>array("UserFunctionalityType"),
                'fields'=>array('UserEnabledFunPermission.permission')
            )
        );

        if(!empty($is_allow)){
            return $is_allow['UserEnabledFunPermission']['permission'];
        }else{
            return 'NO';
        }
        //return "YES";

    }


    public function check_app_enable_permission($thin_app_id,$lable_key) {
        $app_fun = ClassRegistry::init('AppFunctionalityType')->find("first",
            array(
                "conditions" => array(
                    "AppFunctionalityType.label_key" =>$lable_key,
                ),
                'contain'=>false,
            )
        );
        if(!empty($app_fun)){
            $is_allow = ClassRegistry::init('AppEnableFunctionality')->find("first",
                array(
                    "conditions" => array(
                        "AppEnableFunctionality.thinapp_id" =>$thin_app_id,
                        "AppEnableFunctionality.app_functionality_type_id" =>$app_fun['AppFunctionalityType']['id'],
                    ),
                    'contain'=>false,
                )
            );
            if(!empty($is_allow)){
                return true;
            }else{
                return false;
            }

        }else{
            return false;
        }

    }


    /* function add by mahendra*/
    public function check_messasge_liked($message_id=null,$user_id=null,$list_type) {
        $message_data =  ClassRegistry::init('MessageAction')->find('first',array('conditions'=>array(
            'MessageAction.action_by'=>$user_id,
            'MessageAction.message_id'=>$message_id,
            'MessageAction.like'=> "Y",
            'MessageAction.list_message_type'=> $list_type
        )));
        if (!empty($message_data)){
            return $message_data["MessageAction"]["like_type"];

        } else {
            return false;
        }
    }


    /* function add by mahendra*/
    public function is_collobrator_for_channel($user_id=null,$channel_id=null,$thinapp_id=null) {
        $message_data =  ClassRegistry::init('Collaborator')->find('first',array('conditions'=>array(
            'Collaborator.user_id'=>$user_id,
            'Collaborator.channel_id'=>$channel_id,
            'Collaborator.thinapp_id'=> $thinapp_id,
            'Collaborator.status'=> 'ACTIVE'
        )));
        if (!empty($message_data)){
            return $message_data["Collaborator"];
        } else {
            return false;
        }
    }

    /* function add by mahendra*/
    public function is_collobrator($user_id=null,$channel_id=null,$thinapp_id=null) {
        $message_data =  ClassRegistry::init('Collaborator')->find('first',array('conditions'=>array(
            'Collaborator.user_id'=>$user_id,
            'Collaborator.channel_id'=>$channel_id,
            'Collaborator.thinapp_id'=> $thinapp_id
        )));
        if (!empty($message_data)){
            return "YES";
        } else {
            return "NO";
        }
    }

    /* function add by mahendra*/
    public function create_topic($thin_app_id,$channel_id=null,$topic_name=null){
        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id
            )
        ));
        if (!empty($thinapp)){
            if(empty($topic_name)){
                $topic_name = $this->create_topic_name($channel_id);
            }
            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($thinapp['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $iid_token =$thinapp['User']['firebase_token'];
                $path_to_firebase_cm = "https://iid.googleapis.com/iid/v1/".$iid_token."/rel/topics/".$topic_name;
                $headers = array(
                    'Content-Type:application/json',
                    'Authorization:key='.$server_key
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($filed));
                $result = curl_exec($ch);
                /* if(curl_errno($ch)){
                    echo 'Curl error: ' . curl_error($ch);
                }*/
                //  pr(curl_getinfo($ch));
                curl_close($ch);
                return $result;
            }else{
                return false;
            }

        }else{
            return false;
        }
    }



    /* function add by mahendra*/
    public function send_topic_notification($send_array=array()){

        //$this->send_process_to_background();
        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$send_array['thinapp_id']
            ),
            'contain'=>false
        ));
        if (!empty($thinapp)){


            $topic_name = $this->get_topic_name($send_array['channel_id']);
            if(!empty($topic_name) && !empty($thinapp['Thinapp']['firebase_server_key'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'to' => '/topics/'.$topic_name,
                    'data' => $send_array,
                    'priority' =>'high'
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
                return $result;
                /* if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }*/
                //  pr(curl_getinfo($ch));

            }else{
                return false;
            }

        }else{
            return false;
        }
    }


    /* function add by mahendra*/
    public function send_notification_to_multiple_device($send_array=array(),$token_array){

        //$this->send_process_to_background();
        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$send_array['data']['thinapp_id']
            )
        ));

        if (!empty($thinapp)){

            if(!empty($thinapp['Thinapp']['firebase_server_key'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'registration_ids' => $token_array,
                    'data' => $send_array['data']
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
                curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                curl_close($ch);
                return $result;

                /* if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }*/
                //  pr(curl_getinfo($ch));

            }else{
                return false;
            }

        }else{
            return false;
        }
    }



    public function execInBackground($cmd) {


        if (substr(php_uname(), 0, 7) == "Windows"){
            //die('windows');
            shell_exec('php '.$cmd);

           // pclose(popen("start /B php". $cmd, "r"));
        }
        else {
            //die('not windows');
            exec($cmd . " > /dev/null &");
        }
    }


    /* function add by mahendra*/
    public function send_notification_to_device($send_array=array()){

        //$this->send_process_to_background();
     /*   $cmd = "SendNotificationScript.php?data=".json_encode($send_array);
        $this->execInBackground($cmd);*/

        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$send_array['thinapp_id']
            )
        ));

        if (!empty($thinapp)){
            $topic_name =  ClassRegistry::init('User')->find('first',array(
                'conditions'=>array(
                    'User.id'=>$send_array['user_id']
                ),
                'fields'=>array('User.firebase_token'),
                'contain'=>false
            ));
            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($topic_name['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'to' => $topic_name['User']['firebase_token'],
                    'data' => $send_array['data']
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
                return $result;

            }else{
                return false;
            }

        }else{
            return false;
        }


       
    }



    /* function add by mahendra this function subscribe app admin to to channel topic*/
    public function add_admin_to_topic($thin_app_id,$channel_id){


        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id
            )
        ));

        $topic_name =  ClassRegistry::init('Channel')->find('first',array(
            'conditions'=>array(
                'Channel.id'=>$channel_id,
                'Channel.app_id'=>$thin_app_id
            ),
            'fields'=>array('Channel.topic_name')
        ));

        if (!empty($thinapp)){

            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($thinapp['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                $fields = array(
                    'to' => '/topics/'.$topic_name['Channel']['topic_name'],
                    'registration_tokens' => array($thinapp['User']['firebase_token']),
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
                return $result;
                /* if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }*/
                //  pr(curl_getinfo($ch));

            }else{
                return false;
            }

        }else{
            return false;
        }
    }


    /* function add by mahendra this function subscribe app user to to channel topic*/
    public function add_multiple_subscriber_to_topic($thin_app_id,$topic_name,$user_firebase_token_array){

        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id
            )
        ));
        if (!empty($thinapp)){
            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($thinapp['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                $fields = array(
                    'to' => '/topics/'.$topic_name,
                    'registration_tokens' => $user_firebase_token_array,
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
                return $result;
                /* if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }*/
                //  pr(curl_getinfo($ch));

            }else{
                return false;
            }

        }else{
            return false;
        }
    }

    /* function add by mahendra this function subscribe app user to to channel topic*/
    public function add_subscriber_to_topic($thin_app_id,$topic_name,$user_firebase_token){

        //$this->send_process_to_background();
        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id
            )
        ));
        if (!empty($thinapp)){
            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($thinapp['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                $fields = array(
                    'to' => '/topics/'.$topic_name,
                    'registration_tokens' => array($user_firebase_token),
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
                return $result;
                /* if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }*/
                //  pr(curl_getinfo($ch));

            }else{
                return false;
            }

        }else{
            return false;
        }
    }


    /* function add by mahendra this function subscribe app user to to channel topic*/
    public function refresh_firebase_token($user_id,$mobile){

        ignore_user_abort(true);
        set_time_limit(0);
        $filed = array();
        $topic_name_list =  ClassRegistry::init('Subscriber')->find('all',array(
            'conditions'=>array(
                'Subscriber.app_user_id'=>$user_id,
                'Subscriber.mobile'=>$mobile,
                'Subscriber.status'=>'SUBSCRIBED',
                'Channel.status'=>'Y'
            ),
            'contain'=>array('Channel','AppUser','Thinapp'),
            'fields'=>array('Channel.status','Channel.topic_name','AppUser.firebase_token','Thinapp.firebase_server_key')
        ));
        //pr($topic_name_list);die;
        if (!empty($topic_name_list)){

            $mh = curl_multi_init();
            $handles = array();
            foreach ($topic_name_list as $key => $subscriber) {
                if (!empty($subscriber['Thinapp']['firebase_server_key']) && !empty($subscriber['AppUser']['firebase_token'])) {
                    $server_key = $subscriber['Thinapp']['firebase_server_key'];
                    $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                    $fields = array(
                        'to' => '/topics/' . $subscriber['Channel']['topic_name'],
                        'registration_tokens' => array($subscriber['AppUser']['firebase_token']),
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
                    curl_multi_add_handle($mh,$ch);
                }
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
            } while ($running);

            foreach($handles as $ch){
                $result = curl_multi_getcontent($ch);
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }

        }
    }


    /* function add by mahendra this function subscribe app user to to channel topic*/
    public function remove_subscriber_from_topic($thin_app_id,$topic_name,$user_firebase_token){

        $filed = array();
        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id
            )
        ));
        if (!empty($thinapp)){
            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($thinapp['User']['firebase_token'])){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchRemove';
                $fields = array(
                    'to' => '/topics/'.$topic_name,
                    'registration_tokens' => array($user_firebase_token),
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
                return $result;
                /* if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }*/
                //  pr(curl_getinfo($ch));

            }else{
                return false;
            }

        }else{
            return false;
        }
    }


    /* function add by mahendra*/
    public function send_losefound_notification($send_array=array()){
        $filed = array();

        $thinapp =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$send_array['thinapp_id']
            )
        ));

        $channel_id = $send_array['channel_id'];
        $thin_app_id  =$send_array['thinapp_id'];
        if (!empty($thinapp)){

            $firebase_token_array =  ClassRegistry::init('Subscriber')->find('list',array(
                'conditions'=>array(
                    'Subscriber.status'=>'SUBSCRIBED',
                    'Subscriber.channel_id'=>$channel_id,
                    'Subscriber.app_id'=>$thin_app_id,
                    'AppUser.firebase_token !='=>''
                ),
                'fields'=>array('AppUser.firebase_token'),
                'contain'=>'AppUser'
            ));

            if(!empty($thinapp['Thinapp']['firebase_server_key']) && !empty($firebase_token_array)){
                $server_key =$thinapp['Thinapp']['firebase_server_key'];
                $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'to' => json_encode($firebase_token_array),
                    'data' => $send_array
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
                return $result;
                /* if(curl_errno($ch)){
                   echo 'Curl error: ' . curl_error($ch);
               }*/
                //  pr(curl_getinfo($ch));

            }else{
                return false;
            }

        }else{
            return false;
        }
    }



    /*api created by mahendra*/

    public function get_channel_list($thin_app_id,$user_id){
        $mbroadcast_app_id = MBROADCAST_APP_ID;
        if($thin_app_id == $mbroadcast_app_id){
            $channel = $this->Channel->find('all',array(
                'conditions' => array(
                    'OR'=>array(
                        'Channel.user_id'=>$user_id,
                        'Channel.channel_status'=>'PUBLIC',
                    ),
                    'Channel.app_id'=>$thin_app_id,
                    'Channel.status'=>'Y'
                ),
                'contain' =>false,
                'fields'=>array('Channel.id','Channel.channel_name')
            ));
        }else{

            $channel = $this->Channel->find('all',array(
                'conditions' => array(
                    'Channel.user_id'=>$user_id,
                    'Channel.app_id'=>$thin_app_id,
                    'Channel.status'=>'Y'
                ),
                'contain' =>false,
                'fields'=>array('Channel.id','Channel.channel_name')
            ));

        }
        return $channel;
    }

    /* api created by mahendra */
    public function create_firebase_email_password($mobile,$thinapp_id)
    {
        $mobile = str_replace("+91","",$mobile);
        $email = $mobile."_".$thinapp_id."@mbroadcast.in";
        $array = array(
            'email'=>$email,
            'password'=>rand(10000,9999999999)
        );
        return $array;
    }


    /* api created by mahendra */
    public function create_topic_name($channel_id=null)
    {
        return "channel_".$channel_id."_".date('YmdHis')."_".rand(1000,99999);
    }

    /* get user channel topic list in which subscribed */
    public function get_user_subscribe_topic_name($user_id,$thin_app_id){
        $list =  ClassRegistry::init('Subscriber')->find('all',array(
            'conditions'=>array(
                'Subscriber.app_user_id'=>$user_id,
                'Subscriber.app_id'=>$thin_app_id,
            ),
            'contain' => array(
                'Channel' => array(
                    'fields' => array('topic_name')
                )
            ),
            'fields'=>array('Channel.topic_name')
        ));
        $topic_name = array();
        if(!empty($list)){
            foreach ($list as $key => $value){
                $topic_name[$key]= $value['Channel']['topic_name'];
            }
        }
        return $topic_name;
    }

    /* api created by mahendra */
    public function getUserName($user_id){

        $user =  ClassRegistry::init('User')->find('first',array(
            'conditions'=>array(
                'User.id'=>$user_id
            ),
            'fields'=>array('User.username'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['User']['username'];
        }else{
            return "";
        }

    }


    
    
    
    public function getUserbyApp($mobile,$thin_app_id){
        $user =  ClassRegistry::init('User')->find('first',array(
            'conditions'=>array(
                'User.mobile'=>$mobile,
                'User.thinapp_id'=>$thin_app_id
            ),
            'fields'=>array('User.*'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['User'];
        }else{
            return "";
        }
    }

    /* api created by mahendra */
    public function getUserFirebaseToken($user_id){

        $user =  ClassRegistry::init('User')->find('first',array(
            'conditions'=>array(
                'User.id'=>$user_id
            ),
            'fields'=>array('User.firebase_token'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['User']['firebase_token'];
        }else{
            return "";
        }

    }

    /* api created by mahendra */
    public function getChannelOwner($channel_id){

        $user =  ClassRegistry::init('Channel')->find('first',array(
            'conditions'=>array(
                'Channel.id'=>$channel_id
            ),
            'fields'=>array('Channel.user_id'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['Channel']['user_id'];
        }else{
            return 0;
        }

    }

    /* api created by mahendra */
    public function getChannelName($channel_id){

        $user =  ClassRegistry::init('Channel')->find('first',array(
            'conditions'=>array(
                'Channel.id'=>$channel_id
            ),
            'fields'=>array('Channel.channel_name'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['Channel']['channel_name'];
        }else{
            return "";
        }

    }

    public function get_topic_name($channel_id){

        $user =  ClassRegistry::init('Channel')->find('first',array(
            'conditions'=>array(
                'Channel.id'=>$channel_id
            ),
            'fields'=>array('Channel.topic_name'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['Channel']['topic_name'];
        }else{
            return false;
        }

    }


    public function getUserId($thin_app_id,$mobile){
        $user =  ClassRegistry::init('User')->find('first',array(
            'conditions'=>array(
                'User.mobile'=>$mobile,
                'User.thinapp_id'=>$thin_app_id
            ),
            'fields'=>array('User.id'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['User']['id'];
        }else{
            return 0;
        }
    }


    /* api created by mahendra */
    public function getUserRole($user_id){

        $user =  ClassRegistry::init('User')->find('first',array(
            'conditions'=>array(
                'User.id'=>$user_id
            ),
            'fields'=>array('User.role_id'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['User']['role_id'];
        }else{
            return "";
        }

    }


    public function get_user_role_id($user_id){
        $user =  ClassRegistry::init('User')->find('first',array(
            'conditions'=>array(
                'User.id'=>$user_id
            ),
            'fields'=>array('User.role_id'),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['User']['role_id'];
        }else{
            return 0;
        }

    }


    /* api created by mahendra */
    public function getTotalStatikLike($message_id){

        $return_array =array();
        $user =  ClassRegistry::init('MessageStatic')->find('first',array(
            'conditions'=>array(
                'MessageStatic.message_id'=>$message_id
            ),
            'contain'=>false
        ));
        if(!empty($user)){
            $return_array['totel_like'] = $user['MessageStatic']['total_likes'];
            $return_array['total_fb_share'] = $user['MessageStatic']['total_fb_share'];
            $return_array['total_twitter_share'] = $user['MessageStatic']['total_twitter_share'];
            $return_array['total_gplus_share'] = $user['MessageStatic']['total_gplus_share'];
            $return_array['total_whatsapp_share'] = $user['MessageStatic']['total_whatsapp_share'];
            $return_array['total_other_share'] = $user['MessageStatic']['total_other_share'];
            $return_array['total_broadcast_share'] = $user['MessageStatic']['total_broadcast_share'];
            return $return_array;
        }else{
            return 0;
        }

    }



    /* api created by mahendra */
    public function getPollChart($questionID = null){

        $questionTable = ClassRegistry::init('ActionQuestion');

        $question = $questionTable->find('first',array(
            'conditions'=>array('ActionQuestion.id'=>$questionID),
            'contain'=>array('ActionType','QuestionChoice','ActionResponse','Thinapp'),
            'fields'=>array(
                'ActionQuestion.id','ActionQuestion.question_text','ActionQuestion.participates_count','ActionQuestion.end_time','ActionQuestion.poll_duration',
                'ActionQuestion.response_count','ActionType.name','Thinapp.name'
            )
        ));

        $actionResponse = $question['ActionResponse'];
        $actionChoice = $question['QuestionChoice'];
        $actionType = $question['ActionType'];
        $thinApp = $question['Thinapp'];
        $responseCount = array();


        if(in_array($actionType['name'],array('DROPDOWN','YES/NO','RATING','SCALING','MULTIPLE CHOICES'))) {

            foreach($actionResponse as $value )
            {
                if( isset($responseCount[$value['question_choice_id']]) )
                {
                    $responseCount[ $value['question_choice_id'] ]  = $responseCount[$value['question_choice_id']] + 1;
                }
                else
                {
                    $responseCount[ $value['question_choice_id'] ]  = 1;
                }
            }
            $actionChoiceArr = array();

            foreach($actionChoice as $val)
            {
                $actionChoiceArr[$val['id']] = $val['choices'];
            }


        }
        elseif ($actionType['name'] == 'RANKING')
        {

            $totalAns = $question['ActionQuestion']['response_count'];
            $totalResCount = 0;
            foreach($actionResponse as $value )
            {

                if( isset($responseCount[$value['question_choice_id']]) )
                {
                    $responseCount[ $value['question_choice_id'] ]  = $responseCount[ $value['question_choice_id'] ] + $value['user_input_values'];
                }
                else
                {
                    $responseCount[ $value['question_choice_id'] ]  = $value['user_input_values'];
                }
                $totalResCount = $totalResCount + $value['user_input_values'];
            }

            $responseTmp = array();

            //echo $totalResCount;
            foreach($responseCount as $key => $value)
            {
                $responseTmp[$key] = round(($value/$totalResCount)*100);
            }
            $responseCount = $responseTmp;

            $actionChoiceArr = array();
            foreach($actionChoice as $val)
            {
                $actionChoiceArr[$val['id']] = $val['choices'];
            }


        }

        if(in_array($actionType['name'],array('MULTIPLE INPUTS','SHORT ANSWER','LONG ANSWER','DATE'))) {


            $questionTable = ClassRegistry::init('ActionResponse');
            $response_data = $questionTable->find('all',array(
                "conditions"=>array(
                    "ActionResponse.action_question_id"=>$questionID
                ),
                'contain'=>false,
                'fields'=>array('ActionResponse.mobile_number','ActionResponse.user_input_values')
            ));

            $responseCount = $question['ActionQuestion']['response_count'];
            $mobile="";
            if(!empty($response_data)){
                foreach($response_data as $key => $mobile)
                {
                    $actionChoiceArr[$mobile["ActionResponse"]["mobile_number"]][] = $mobile["ActionResponse"]["user_input_values"];
                }
            }

        }

        //   pr($responseCount);

        return array('responseCount'=>$responseCount,'actionChoice'=>$actionChoiceArr,'questionVal'=>$question['ActionQuestion'],'actionType'=>$actionType,'thinApp'=>$thinApp);

    }



    public function getUserChoiseOption($mobile,$questionID){

        $questionTable = ClassRegistry::init('ActionResponse');
        $response_data = $questionTable->find('all',array(
            "conditions"=>array(
                "ActionResponse.action_question_id"=>$questionID,
                "ActionResponse.mobile_number"=>$mobile
            ),
            'contain'=>false,
            'fields'=>array('ActionResponse.user_input_values')

        ));


        // pr($response_data);
        $actionChoiceArr = array();
        $mobile="";
        if(!empty($response_data)){
            foreach($response_data as $key => $mobile)
            {
                $actionChoiceArr[$key]= $mobile['ActionResponse']['user_input_values'];
            }
        }

        return  $actionChoiceArr;
    }



    public function createOpitonArray($action_type_id,$question_id,$app_id,$choice_array=array()){

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

    public function totalSubscriberForChannel($channel_id){
        $return_array =array();
        $count =  ClassRegistry::init('Subscriber')->find('count',array(
            'conditions'=>array(
                'Subscriber.channel_id'=>$channel_id
            ),
            'contain'=>false
        ));
        return $count;
    }


    public function totalSmsSubscriber($channel_id,$thin_app_id){
        $return_array =array();
        $count =  ClassRegistry::init('Subscriber')->find('count',array(
            'conditions'=>array(
                'Subscriber.channel_id'=>$channel_id,
                'Subscriber.app_id'=>$thin_app_id,
                'Subscriber.app_user_id'=>0,
                'Subscriber.status'=>"SUBSCRIBED"
            ),
            'contain'=>false
        ));
        return $count;
    }


    public function getThinAppFirebaseKey($thin_app_id){
        $return_array =array();
        $app =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id,
            ),
            'contain'=>false
        ));
        if(!empty($app)){
            return $app['Thinapp']['firebase_server_key'];
        }
        return false;
    }


    public function getThinAppData($thin_app_id){
        $return_array =array();
        $app =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id,
            ),
            'contain'=>false
        ));
        if(!empty($app)){
            return $app['Thinapp'];
        }
        return false;
    }

    public function getThinAppUrl($thin_app_id){
        $return_array =array();
        $app =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id,
            ),
            'contain'=>false
        ));
        if(!empty($app)){
            return $app['Thinapp']['apk_url'];
        }
        return false;
    }


    public function getThinAppName($thin_app_id){
        $return_array =array();
        $app =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id,
            ),
            'contain'=>false
        ));
        if(!empty($app)){
            return $app['Thinapp']['name'];
        }
        return "";
    }


    public function getThinAppAdminId($thin_app_id){
        $return_array =array();
        $app =  ClassRegistry::init('Thinapp')->find('first',array(
            'conditions'=>array(
                'Thinapp.id'=>$thin_app_id,
            ),
            'contain'=>false,
            'fields'=>array('Thinapp.user_id')
            
        ));
        if(!empty($app)){
            return $app['Thinapp']['user_id'];
        }
        return 0;
    }

    public function find_user_id_for_app($mobile,$thin_app_id){

        $user =  ClassRegistry::init('User')->find('first',array(
            'conditions'=>array(
                'User.thinapp_id'=>$thin_app_id,
                'User.mobile'=>$mobile
            ),
            'contain'=>false
        ));
        if(!empty($user)){
            return $user['User']['id'];
        }else{
            return 0;
        }

    }

    /*function add by mahendra*/
    public function create_mobile_number($number=null,$country_code="+91"){
        //$number = "1234567aa0";
        $number = (string)$number;
        $number = ltrim($number, '0');
        $number = preg_replace("/[^0-9]/", "", $number);
        $number = substr($number, -10);
        $number = $country_code.$number;

        if(preg_match('#^(\+){0,1}(91){0,1}[789](-|\s){0,1}[0-9]{9}$#', $number)){
            return $number;
        }
        return false;
    }

    public function validate_payment_mobile_number($number=null,$country_code="+91"){
        //$number = "1234567aa0";
        $number = (string)$number;
        $number = ltrim($number, '0');
        $number = preg_replace("/[^0-9]/", "", $number);
        $number = substr($number, -10);
        $number = $country_code.$number;

            return $number;
        
    }

    public function check_mobile_number($number=null){
        if(preg_match('#^(\+)[0-9]{4,13}$#', $number)){
            return $number;
        }
        return false;
    }





    public function is_app_user($thin_app_id,$mobile)
    {
        $user = ClassRegistry::init('User');
        $user_data = $user->find('first',array('conditions'=>array('User.thinapp_id'=>$thin_app_id,'User.mobile'=>$mobile)));
        if(!empty($user_data)){
            return $user_data['User'];
        }else{
            return 0;
        }

    }

    public function is_app_admin_login_first_time($thin_app_id)
    {
        $user = ClassRegistry::init('Channel');
        $user_data = $user->find('count',array('conditions'=>array('Channel.channel_status'=>"DEFAULT",'Channel.app_id'=>$thin_app_id)));
        return $user_data;
    }


    public function updateCoins($actionType=null,$ownerUserID=0,$actionTakenByUserID=0,$messageID=0,$thinappID=0,$sharedOn=''){

        $coin =  ClassRegistry::init('Coin');
        $gullak =  ClassRegistry::init('Gullak');
        $coinDistribution = unserialize(CoinDistribution);
        $coinToSave = $coinDistribution[$actionType];
        $datasource = $coin->getDataSource();
        try {
            $datasource->begin();
            $coin->create();
            $coin->set('owner_user_id', $ownerUserID);
            $coin->set('action_type', $actionType);
            $coin->set('action_taken_by_user_id', $actionTakenByUserID);
            $coin->set('coin_earned', $coinToSave);
            $coin->set('message_id', $messageID);
            $coin->set('thinapp_id', $thinappID);
            $coin->set('shared_on', $sharedOn);
            if($coin->save())
            {
                if($actionType != 'REGISTER')
                {
                    if($gullak->updateAll(array('Gullak.total_coins' => "Gullak.total_coins +".$coinToSave), array('Gullak.user_id' => $ownerUserID))){
                        $datasource->commit();
                    }else{
                        return false;
                    }
                }
                else
                {
                    $gullak->create();
                    $gullak->set('user_id', $ownerUserID);
                    $gullak->set('total_coins',$coinToSave);
                    if($gullak->save()){
                        $datasource->commit();
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }

            return true;

        } catch(Exception $e) {
            $datasource->rollback();
            return false;
        }
    }

    public function transferCoins($transferByUserId=0,$transferToUserId=0,$coins=0){
        $CoinTransfer =  ClassRegistry::init('CoinTransfer');
        $gullak =  ClassRegistry::init('Gullak');

        $userHasCoin = $gullak->find('first',array(
                'fields'=>array('Gullak.total_coins'),
                'conditions'=>array('Gullak.user_id'=>$transferByUserId),
                'contain'=>false
        ));
        $userHasCoin = $userHasCoin['Gullak']['total_coins'];
        if($coins > $userHasCoin){ return false; }

        $dataToInsert = array();
        $dataToInsert['transfer_by_user_id'] = $transferByUserId;
        $dataToInsert['transfer_to_user_id'] = $transferToUserId;
        $dataToInsert['coins'] = $coins;

        $datasource = $CoinTransfer->getDataSource();
        try {
            $datasource->begin();
            $CoinTransfer->create();
            if($CoinTransfer->save($dataToInsert))
            {
              $res1 =   $gullak->updateAll(array('Gullak.total_coins' => 'Gullak.total_coins - '.$coins), array('Gullak.user_id' => $transferByUserId));
              $res2 =   $gullak->updateAll(array('Gullak.total_coins' => 'Gullak.total_coins + '.$coins), array('Gullak.user_id' => $transferToUserId));
                if($res1 && $res2){
                    $datasource->commit();
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        } catch(Exception $e) {
            $datasource->rollback();
            return false;
        }
    }

    public function redeemCoins($redeemByUserID=0,$coins=0){
        $CoinRedeem =  ClassRegistry::init('CoinRedeem');
        $gullak =  ClassRegistry::init('Gullak');
        $constant =  ClassRegistry::init('Constant');

        $userHasCoin = $gullak->find('first',array(
                'fields'=>array('Gullak.total_coins'),
                'conditions'=>array('Gullak.user_id'=>$redeemByUserID),
                'contain'=>false,
        ));
        $userHasCoin = $userHasCoin['Gullak']['total_coins'];
        if($coins > $userHasCoin){ return false; }

        $rate = $constant->find('first',array(
            'conditions'=>array('Constant.key'=>'COIN_COST'),
            'fields'=>array('Constant.value'),
            'contain'=>false,
        ));

        $rate = $rate['Constant']['value'];

        $datasource = $CoinRedeem->getDataSource();
        try {
            $dataToInsert = array();
            $dataToInsert['redeem_by_user_id'] = $redeemByUserID;
            $dataToInsert['coin_rate'] = $rate;
            $dataToInsert['amount'] = ($rate/100)*$coins;
            $dataToInsert['coins'] = $coins;
            $datasource->begin();
            $CoinRedeem->create();
            if($CoinRedeem->save($dataToInsert))
            {
                $gullak->updateAll(array('Gullak.total_coins' => 'Gullak.total_coins - '.$coins), array('Gullak.user_id' => $redeemByUserID));
                $datasource->commit();
            }
            else
            {
                return false;
            }
            return true;

        } catch(Exception $e) {
            $datasource->rollback();
            return false;
        }
    }

    public function sendSimpleEmail($to,$subject,$message,$mailer_name='Mailer') {

        try{
            $mail = new PHPMailer;
            $send_to = SUPER_ADMIN_EMAIL;
            $mail->setFrom($to,$mailer_name);
            $mail->addAddress($send_to, 'Mbroadcast');     // Add a recipient
            $mail->addReplyTo('hr@mbroadcast.in', 'Information');
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            if(!$mail->send()){
                return false;
            }
            return true;
        }catch (Exception $e){
            return true;
        }


    }

    public function sendEmail($to,$from,$subject,$message,$mailer_name='Mailer') {

        try{
            $mail = new PHPMailer;
            $mail->setFrom($from,$mailer_name);
            $mail->addAddress($to, 'MEngage');     // Add a recipient
            $mail->addReplyTo(SUPPORT_EMAIL, 'Information');
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            if(!$mail->send()){
                return false;
            }
            return true;
        }catch (Exception $e){
            return true;
        }

    }




    public function getLeadPaymentStatus($app_url)
    {
        $user = ClassRegistry::init('Leads');
        $user_data = $user->find('first',array('conditions'=>array('Leads.org_unique_url'=>$app_url)));
        $user_image = $user_data['Leads']['app_payment'];
        return $user_image;
    }



    public function getSubscriberId($user_id,$channel_id){
        $subscriber = ClassRegistry::init('Subscribers');
        $userHasCoin = $subscriber->find('first',array(
            'fields'=>array('Subscribers.id'),
            'conditions'=>array(
                'Subscribers.channel_id'=>$channel_id,
                'Subscribers.app_user_id'=>$user_id,
                'Subscribers.status'=>'SUBSCRIBED'
            ),
            'contain'=>false,
        ));
        if(!empty($userHasCoin)){
            return $userHasCoin['Subscribers']['id'];
        }else{
            return 0;
        }

    }



    function timeElapsedString($ptime)
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



    public function isSellLike($user_id,$sell_item_id){

        $gullak = ClassRegistry::init('SellLike');
        $userHasCoin = $gullak->find('count',array(
            'conditions'=>array('SellLike.user_id'=>$user_id,'SellLike.sell_item_id'=>$sell_item_id),
            'contain'=>false,
        ));
        if($userHasCoin > 0){
            return 'YES';
        }else{
            return 'NO';
        }

    }


    public function isQuestLike($user_id,$quest_id){

        $gullak = ClassRegistry::init('QuestLike');
        $userHasCoin = $gullak->find('count',array(
            'conditions'=>array('QuestLike.user_id'=>$user_id,'QuestLike.quest_id'=>$quest_id),
            'contain'=>false,
        ));
        if($userHasCoin > 0){
            return 'YES';
        }else{
            return 'NO';
        }

    }
    public function is_thank($user_id,$quest_reply_id){

        $gullak = ClassRegistry::init('QuestReplyThank');
        $userHasCoin = $gullak->find('count',array(
            'conditions'=>array(
                'QuestReplyThank.user_id'=>$user_id,
                'QuestReplyThank.quest_reply_id'=>$quest_reply_id
            ),
            'contain'=>false,
        ));

        if($userHasCoin == 1){
            return 'YES';
        }else{
            return 'NO';
        }

    }


    public function totalUserCoins($user_id){

        $gullak = ClassRegistry::init('Gullak');
        $userHasCoin = $gullak->find('first',array(
                'fields'=>array('Gullak.total_coins'),
                'conditions'=>array('Gullak.user_id'=>$user_id),
                'contain'=>false,
        ));
        if(!empty($userHasCoin)){
            return $userHasCoin['Gullak']['total_coins'];
        }else{
            return 0;
        }
        
    }


    public function get_event_cover_image($event_id){

        $gullak = ClassRegistry::init('EventMedia');
        $userHasCoin = $gullak->find('first',array(
            'fields'=>array('EventMedia.media_path'),
            'conditions'=>array(
                'EventMedia.user_id'=>$event_id,
                'EventMedia.media_type'=>'IMAGE',
                'EventMedia.is_cover_image'=>'YES'
            ),
            'contain'=>false,
        ));
        if(!empty($userHasCoin)){
            return $userHasCoin['EventMedia']['media_path'];
        }else{
            return DEFAULT_COVER_IMAGE;
        }

    }

    public function is_default_channel($user_id)
    {
        $user = ClassRegistry::init('Channel');
        $user_data = $user->find('count',array('conditions'=>array('Channel.channel_status'=>"DEFAULT",'Channel.user_id'=>$user_id)));
        return $user_data;
    }


    public function app_has_default_channel($thin_app_id)
    {
        $user = ClassRegistry::init('Channel');
        $user_data = $user->find('count',array('conditions'=>array('Channel.channel_status'=>"DEFAULT",'Channel.app_id'=>$thin_app_id)));
        return $user_data;
    }

    /* function add by mahendra*/
    public function sendPollMessage($channel_id,$question_id,$thin_app_id,$user_id) {

        ignore_user_abort(true);
        set_time_limit(0);
        $subscriber_list = ClassRegistry::init('Subscriber')->find("all", array(
            "conditions" => array(
                "Subscriber.channel_id" =>$channel_id,
                "Subscriber.app_id" =>$thin_app_id,
                "Subscriber.app_user_id" =>0,
                "Subscriber.status" =>"SUBSCRIBED"
            ),
            'contain'=>false,
            'fields'=>array('Subscriber.id','Subscriber.mobile','Subscriber.app_id')
        ));

        if(!empty($subscriber_list)){
            $content="";
            $name = date('ymdhis')."_".rand(5000,20000).".json";
            $file_path = WWW_ROOT.DS.'uploads'.DS.'cron'.DS.$name;
            $fp = fopen($file_path,"w");
            fwrite($fp,$content);
            fclose($fp);

            $write_array =array();

            $mh = curl_multi_init();
            $handles = array();
            foreach ($subscriber_list as $key => $sub){
                if(!empty($sub['Subscriber']['mobile'])){
                    $site_url = Router::url('/polls/',true).base64_encode($sub['Subscriber']['id'])."/".base64_encode($question_id);
                    $message ="You have received a poll. Click Here:".$site_url;
                    $thin_app_id =$sub['Subscriber']['app_id'];
                    $curl_scraped_page = '';
                    $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroad&send_to=" . $sub['Subscriber']['mobile'];
                    $url .= "&msg=" . urlencode($message);
                    $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
                    $ch = curl_init();
                    $handles[] = $ch;
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_multi_add_handle($mh,$ch);
                }
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
            } while ($running);

            foreach($handles as $key => $ch){
                $curl_scraped_page = curl_multi_getcontent($ch);
                $curl_scraped_page = json_decode($curl_scraped_page,true);
                if(!empty($curl_scraped_page)){
                        $write_array[0][$key] = $curl_scraped_page["response"];
                }
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }

            if(!empty($write_array)){
                $handle = fopen($file_path, 'w');
                $data = json_encode($write_array);
                fwrite($handle, $data);
                $sms_static = ClassRegistry::init('AppSmsStatic');
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_promotional_sms' => 'AppSmsStatic.total_promotional_sms -'.count($subscriber_list)),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
                $SentSmsDetail = ClassRegistry::init('SmsQueue');
                $SentSmsDetail->create();
                $SentSmsDetail->set('channel_id', $channel_id);
                $SentSmsDetail->set('message_id',$question_id);
               // $SentSmsDetail->set('message_text',$message);
                $SentSmsDetail->set('cron_for', "POLL");
                $SentSmsDetail->set('thinapp_id', $thin_app_id);
                $file_path = Router::url('/uploads/cron/',true).$name;
                $SentSmsDetail->set('tmp_file_path', $file_path);
                $SentSmsDetail->set('sent_by', $user_id);
                $SentSmsDetail->save();
            }

        }
    }

    public function sendConferenceMessage($channel_id,$conference_id,$thin_app_id,$user_id,$title) {

        ignore_user_abort(true);
        set_time_limit(0);
        $subscriber_list = ClassRegistry::init('Subscriber')->find("all", array(
            "conditions" => array(
                "Subscriber.channel_id" =>$channel_id,
                "Subscriber.app_id" =>$thin_app_id,
                "Subscriber.app_user_id" =>0,
                "Subscriber.status" =>"SUBSCRIBED"
            ),
            'contain'=>false,
            'fields'=>array('Subscriber.id','Subscriber.mobile','Subscriber.app_id')
        ));

        if(!empty($subscriber_list)){
            $content="";
            $name = date('ymdhis')."_".rand(5000,20000).".json";
            $file_path = WWW_ROOT.DS.'uploads'.DS.'cron'.DS.$name;
            $fp = fopen($file_path,"w");
            fwrite($fp,$content);
            fclose($fp);
            $write_array =array();
            $mh = curl_multi_init();
            $handles = array();
            $conference_url = $this->getConferenceUrl();
            $site_url = $this->getThinAppUrl($thin_app_id);
            foreach ($subscriber_list as $key => $sub){
                if(!empty($sub['Subscriber']['mobile'])){
                    $link_param = urlencode(base64_encode($sub['Subscriber']['id']."-".$conference_id));
                    $message ="You are invited for text conference ".$title." . Click to start :".$conference_url.$link_param;
                    $message .= " Or Download App ".$site_url;
                    $thin_app_id =$sub['Subscriber']['app_id'];
                    $curl_scraped_page = '';
                    $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroad&send_to=" . $sub['Subscriber']['mobile'];
                    $url .= "&msg=" . urlencode($message);
                    $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
                    $ch = curl_init();
                    $handles[] = $ch;
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_multi_add_handle($mh,$ch);
                }
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
            } while ($running);

            foreach($handles as $key => $ch){
                $curl_scraped_page = curl_multi_getcontent($ch);
                $curl_scraped_page = json_decode($curl_scraped_page,true);
                if(!empty($curl_scraped_page)){
                        $write_array[0][$key] = $curl_scraped_page["response"];
                }
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }

            if(!empty($write_array)){
                $handle = fopen($file_path, 'w');
                $data = json_encode($write_array);
                fwrite($handle, $data);
                $sms_static = ClassRegistry::init('AppSmsStatic');
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_promotional_sms' => 'AppSmsStatic.total_promotional_sms -'.count($subscriber_list)),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
                $SentSmsDetail = ClassRegistry::init('SmsQueue');
                $SentSmsDetail->create();
                $SentSmsDetail->set('channel_id', $channel_id);
                $SentSmsDetail->set('message_id',$conference_id);
               // $SentSmsDetail->set('message_text',$message);
                $SentSmsDetail->set('cron_for', "POLL");
                $SentSmsDetail->set('thinapp_id', $thin_app_id);
                $file_path = Router::url('/uploads/cron/',true).$name;
                $SentSmsDetail->set('tmp_file_path', $file_path);
                $SentSmsDetail->set('sent_by', $user_id);
                $SentSmsDetail->save();
            }

        }
    }

    public function sendQuestMessage($channel_id,$quest_id,$thin_app_id,$user_id) {

        ignore_user_abort(true);
        set_time_limit(0);
        $subscriber_list = ClassRegistry::init('Subscriber')->find("all", array(
            "conditions" => array(
                "Subscriber.channel_id" =>$channel_id,
                "Subscriber.app_id" =>$thin_app_id,
                "Subscriber.app_user_id" =>0,
                "Subscriber.status" =>"SUBSCRIBED"
            ),
            'contain'=>false,
            'fields'=>array('Subscriber.id','Subscriber.mobile','Subscriber.app_id')
        ));

        if(!empty($subscriber_list)){
            $content="";
            $name = date('ymdhis')."_".rand(5000,20000).".json";
            $file_path = WWW_ROOT.DS.'uploads'.DS.'cron'.DS.$name;
            $fp = fopen($file_path,"w");
            fwrite($fp,$content);
            fclose($fp);
            $write_array =array();
            $mh = curl_multi_init();
            $handles = array();

            $quest_url = $url = Router::url('/quest/',true);
            $site_url = $this->getThinAppUrl($thin_app_id);
            foreach ($subscriber_list as $key => $sub){
                if(!empty($sub['Subscriber']['mobile'])){
                    $link_param = base64_encode($sub['Subscriber']['id'])."/".base64_encode($quest_id);
                    $message ="You are invited for quest click here to reply :".$quest_url.$link_param;
                    $message .= " Or Download App ".$site_url;
                    $thin_app_id =$sub['Subscriber']['app_id'];
                    $curl_scraped_page = '';
                    $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroad&send_to=" . $sub['Subscriber']['mobile'];
                    $url .= "&msg=" . urlencode($message);
                    $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
                    $ch = curl_init();
                    $handles[] = $ch;
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_multi_add_handle($mh,$ch);
                }
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
            } while ($running);

            foreach($handles as $key => $ch){
                $curl_scraped_page = curl_multi_getcontent($ch);
                $curl_scraped_page = json_decode($curl_scraped_page,true);
                if(!empty($curl_scraped_page)){
                    $write_array[0][$key] = $curl_scraped_page["response"];
                }
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }

            if(!empty($write_array)){
                $handle = fopen($file_path, 'w');
                $data = json_encode($write_array);
                fwrite($handle, $data);
                $sms_static = ClassRegistry::init('AppSmsStatic');
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_promotional_sms' => 'AppSmsStatic.total_promotional_sms -'.count($subscriber_list)),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
                $SentSmsDetail = ClassRegistry::init('SmsQueue');
                $SentSmsDetail->create();
                $SentSmsDetail->set('channel_id', $channel_id);
                $SentSmsDetail->set('message_id',$quest_id);
                // $SentSmsDetail->set('message_text',$message);
                $SentSmsDetail->set('cron_for', "POLL");
                $SentSmsDetail->set('thinapp_id', $thin_app_id);
                $file_path = Router::url('/uploads/cron/',true).$name;
                $SentSmsDetail->set('tmp_file_path', $file_path);
                $SentSmsDetail->set('sent_by', $user_id);
                $SentSmsDetail->save();
            }

        }
    }



    public function get_enum_values( $model,$table_name, $field )
    {
        $model = ClassRegistry::init($model);
        $type = $model->query( "SHOW COLUMNS FROM {$table_name} WHERE Field = '{$field}'" );
        preg_match("/^enum\(\'(.*)\'\)$/", $type[0]['COLUMNS']['Type'], $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }


    public function addSubscriberSendBlukSms($thin_app_id,$message,$number_array,$user_id){
        error_reporting(0);
        $write_array =array();
        /*create tmp file*/
        $site_url = $this->getThinAppUrl($thin_app_id);
        $message .= " Click Here To Download App: ".$site_url;
        if(!empty($number_array)){

            $total_message_number=SMS_QUEUE;
            $content ="";
            $name = date('ymdhis')."_".rand(5000,20000).".json";
            $file_path = WWW_ROOT.DS.'uploads'.DS.'cron'.DS.$name;
            $fp = fopen($file_path,"w");
            fwrite($fp,$content);
            fclose($fp);
            $total_sub = count($number_array);
            $total_loop = ceil($total_sub/$total_message_number);
            $array_chunk = array_chunk($number_array,$total_message_number);
            $chunk_counter=0;
            for($counter=0;$counter<$total_loop;$counter++){
                $number_string =implode(",",$array_chunk[$chunk_counter++]);
                $curl_scraped_page = '';
                $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroad&send_to=" . $number_string;
                $url .= "&msg=" . urlencode($message);
                $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
                $curl_scraped_page = json_decode($curl_scraped_page,true);
                if(!empty($curl_scraped_page)){
                    $totalNumber = count(explode(",",$number_string));
                    if($totalNumber==1){
                        $write_array[$counter][0] = $curl_scraped_page["response"];
                    }else{
                        $write_array[$counter] = $curl_scraped_page["data"]["response_messages"];
                    }
                }else{

                }
            }

            if(!empty($write_array)){
                $handle = fopen($file_path, 'w');
                $data = json_encode($write_array);
                fwrite($handle, $data);

                $sms_static = ClassRegistry::init('AppSmsStatic');
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_transactional_sms' => 'AppSmsStatic.total_transactional_sms -'.$total_sub),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
                $SentSmsDetail = ClassRegistry::init('SmsQueue');
                $SentSmsDetail->create();
                $SentSmsDetail->set('channel_id', 0);
                $SentSmsDetail->set('message_text',$message);
                $SentSmsDetail->set('thinapp_id', $thin_app_id);
                $file_path = Router::url('/uploads/cron/',true).$name;
                $SentSmsDetail->set('tmp_file_path', $file_path);
                $SentSmsDetail->set('sent_by', $user_id);
                $SentSmsDetail->save();
            }
        }
    }




    public function send_bulk_sms($thin_app_id,$message,$number_array,$user_id){
        error_reporting(0);
        $write_array =array();

        if(!empty($number_array)){

            $total_message_number=SMS_QUEUE;
            $content ="";
            $name = date('ymdhis')."_".rand(5000,20000).".json";
            $file_path = WWW_ROOT.DS.'uploads'.DS.'cron'.DS.$name;
            $fp = fopen($file_path,"w");
            fwrite($fp,$content);
            fclose($fp);
            $total_sub = count($number_array);
            $total_loop = ceil($total_sub/$total_message_number);
            $array_chunk = array_chunk($number_array,$total_message_number);
            $chunk_counter=0;
            for($counter=0;$counter<$total_loop;$counter++){
                $number_string =implode(",",$array_chunk[$chunk_counter++]);
                $curl_scraped_page = '';
                $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroad&send_to=" . $number_string;
                $url .= "&msg=" . urlencode($message);
                $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);
                $curl_scraped_page = json_decode($curl_scraped_page,true);
                if(!empty($curl_scraped_page) && strtoupper($curl_scraped_page["response"]["status"])=="SUCCESS"){
                    $totalNumber = count(explode(",",$number_string));
                    if($totalNumber==1){
                        $write_array[$counter][0] = $curl_scraped_page["response"];
                    }else{
                        $write_array[$counter] = $curl_scraped_page["data"]["response_messages"];
                    }
                }else{

                }
            }

            if(!empty($write_array)){
                $handle = fopen($file_path, 'w');
                $data = json_encode($write_array);
                fwrite($handle, $data);

                $sms_static = ClassRegistry::init('AppSmsStatic');
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_transactional_sms' => 'AppSmsStatic.total_transactional_sms -'.$total_sub),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
                $SentSmsDetail = ClassRegistry::init('SmsQueue');
                $SentSmsDetail->create();
                $SentSmsDetail->set('channel_id', 0);
                $SentSmsDetail->set('message_text',$message);
                $SentSmsDetail->set('thinapp_id', $thin_app_id);
                $file_path = Router::url('/uploads/cron/',true).$name;
                $SentSmsDetail->set('tmp_file_path', $file_path);
                $SentSmsDetail->set('sent_by', $user_id);
                $SentSmsDetail->save();
            }
        }
    }





    public function sendBulkSms($channel_id,$thin_app_id,$message,$message_id,$user_id){
        error_reporting(0);
        $write_array =array();
        /*create tmp file*/
        $total_sub =  ClassRegistry::init('Subscriber')->find('count',array(
            'conditions' => array(
                "Subscriber.channel_id"=>$channel_id,
                "Subscriber.app_id"=>$thin_app_id,
                "Subscriber.app_user_id"=>0,
                "Subscriber.status"=>"SUBSCRIBED"
            ),
            'contain' =>false
        ));

        $site_url = $this->getThinAppUrl($thin_app_id);
        $message .= "Click Here To Download App:".$site_url;
        if(!empty($total_sub)){

            $total_message_number=SMS_QUEUE;
            $content ="";
            $name = date('ymdhis')."_".rand(5000,20000).".json";
            $file_path = WWW_ROOT.DS.'uploads'.DS.'cron'.DS.$name;
            $fp = fopen($file_path,"w");
            fwrite($fp,$content);
            fclose($fp);
            $total_loop = ceil($total_sub/$total_message_number);
            for($counter=0;$counter<$total_loop;$counter++){
                $offset = $counter * $total_message_number;
                $query = "SELECT  GROUP_CONCAT(mobile) AS number FROM (SELECT  mobile FROM  subscribers  WHERE app_user_id =0 and status = 'SUBSCRIBED' and channel_id = ".$channel_id." and app_id = ".$thin_app_id." LIMIT ".$total_message_number." offset ".$offset.") as parent";
                $string = ClassRegistry::init('Subscriber')->query($query);

                    $number_string =$string[0][0]['number'];
                    $curl_scraped_page = '';

                    $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=mBroad&send_to=" . $number_string;
                    $url .= "&msg=" . urlencode($message);
                    $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $curl_scraped_page = curl_exec($ch);
                    curl_close($ch);
                    $curl_scraped_page = json_decode($curl_scraped_page,true);
                    if(!empty($curl_scraped_page) && strtoupper($curl_scraped_page["response"]["status"])=="SUCCESS"){
                        $totalNumber = count(explode(",",$number_string));
                        if($totalNumber==1){
                            $write_array[$counter][0] = $curl_scraped_page["response"];
                        }else{
                            $write_array[$counter] = $curl_scraped_page["data"]["response_messages"];
                        }
                    }else{

                    }
            }

            if(!empty($write_array)){
                $handle = fopen($file_path, 'w');
                $data = json_encode($write_array);
                fwrite($handle, $data);

                $sms_static = ClassRegistry::init('AppSmsStatic');
                $sms_static->updateAll(array(
                    'AppSmsStatic.total_transactional_sms' => 'AppSmsStatic.total_transactional_sms -'.$total_sub),
                    array('AppSmsStatic.thinapp_id' => $thin_app_id)
                );
                $SentSmsDetail = ClassRegistry::init('SmsQueue');
                $SentSmsDetail->create();
                $SentSmsDetail->set('channel_id', $channel_id);
                $SentSmsDetail->set('message_id',$message_id);
                $SentSmsDetail->set('message_text',$message);
                $SentSmsDetail->set('thinapp_id', $thin_app_id);
                $file_path = Router::url('/uploads/cron/',true).$name;
                $SentSmsDetail->set('tmp_file_path', $file_path);
                $SentSmsDetail->set('sent_by', $user_id);
                $SentSmsDetail->save();
            }
            
            
            
            
        }
    }
	
	
	
	
		public function giftRedeem($userID=null,$coins=null,$giftID=null,$thinappID=null,$type=null,$giftEmail=null,$giftMobile=null){

			$response = array();
			if(!empty($userID) && !empty($coins) && !empty($giftID) && !empty($thinappID) && !empty($type) && !( ($type == 'GIFT') && ( empty($giftMobile) || empty($giftEmail) ) ))
			{



				$gullakTable =  ClassRegistry::init('Gullak');
				$giftTable =  ClassRegistry::init('Gift');
				$redeemGiftTable =  ClassRegistry::init('RedeemGift');
				
				$gullak = $gullakTable->find('first',array('conditions'=>array('Gullak.user_id'=>$userID),'contain'=>false));
				$gift = $giftTable->find('first',array('conditions'=>array('Gift.id'=>$giftID),'contain'=>false));
				
				$currTime = strtotime("now");
				if( !( ($gift['Gift']['status'] == 'ACTIVE') && ($currTime < strtotime($gift['Gift']['end_datetime'])) && ( $gift['Gift']['redeem_count'] < $gift['Gift']['quantity'] ) ) )
				{
					$response['status'] = 0;
					$response['message'] = 'Gift is no active!';
				}
				else if( !($gullak['Gullak']['total_coins'] >= $coins) )
				{
					$response['status'] = 0;
					$response['message'] = 'Do not have enough coins!';
				}
				else
				{
					
					$datasource = $redeemGiftTable->getDataSource();
					try 
					{
						$datasource->begin();
						
						
						$inData = array();
						$inData['thinapp_id'] = $thinappID;
						$inData['gift_id'] = $giftID;
						$inData['user_id'] = $userID;
						$inData['type'] = $type;
						$inData['gift_email'] = $giftEmail;
						$inData['gift_mobile'] = $giftMobile;
						
						if($redeemGiftTable->save($inData))
						{
							$gullakTable->updateAll(array('total_coins' => '`total_coins` - $coins'), array('user_id' => $userID));
							$giftTable->updateAll(array('redeem_count' => '`redeem_count` + 1'), array('id' => $giftID));
							$response['status'] = 1;
							$response['message'] = 'Successfully redeemed gift!';
						}
						else
						{
							$datasource->rollback();
							$response['status'] = 0;
							$response['message'] = 'Sorry, Something went wrong!';
						}
						
						$datasource->commit();
					}
					 catch(Exception $e) {
						$datasource->rollback();
						$response['status'] = 0;
						$response['message'] = 'Sorry, Something went wrong!';
					}
				}



			}
			else
			{
				$response['status'] = 0;
				$response['message'] = 'Required perameters are missing!';
			}
			
			
		}





    /* function add by mahendra this function subscribe app user to to channel topic*/
    public function refresh_firebase_token_new($user_id,$mobile){

        ignore_user_abort(true);
        set_time_limit(0);
        $filed = array();
        $topic_name_list =  ClassRegistry::init('Subscriber')->find('all',array(
            'conditions'=>array(
                'Subscriber.app_user_id'=>$user_id,
                'Subscriber.mobile'=>$mobile,
                'Subscriber.status'=>'SUBSCRIBED',
                'Channel.status'=>'Y'
            ),
            'contain'=>array('Channel','AppUser','Thinapp'),
            'fields'=>array('Channel.status','Channel.topic_name','AppUser.firebase_token','Thinapp.firebase_server_key')
        ));
        //pr($topic_name_list);die;
        if (!empty($topic_name_list)){

            $mh = curl_multi_init();
            $handles = array();
            foreach ($topic_name_list as $key => $subscriber) {
                if (!empty($subscriber['Thinapp']['firebase_server_key']) && !empty($subscriber['AppUser']['firebase_token'])) {
                    $server_key = $subscriber['Thinapp']['firebase_server_key'];
                    $path_to_firebase_cm = 'https://iid.googleapis.com/iid/v1:batchAdd';
                    $fields = array(
                        'to' => '/topics/' . $subscriber['Channel']['topic_name'],
                        'registration_tokens' => array($subscriber['AppUser']['firebase_token']),
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
                    curl_multi_add_handle($mh,$ch);

                }
            }

            $running = null;
            do {
                curl_multi_exec($mh, $running);
            } while ($running);

            foreach($handles as $ch){
                $result = curl_multi_getcontent($ch);
                pr($result);
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }

        }
    }



    public function channel_message_default_param(){
        $param_array =array();
        $param_array['short_url'] =null;
        $param_array['message_status'] =null;
        $param_array['thumb_url'] = null;
        $param_array['sent_via'] =null;
        $param_array['message_type'] =null;
        $param_array['owner_user_id'] = 0;
        $param_array['is_like'] =null;
        $param_array['total_likes'] =0;
        $param_array['total_fb_share'] =0;
        $param_array['total_twitter_share']=0;
        $param_array['total_gplus_share'] =0;
        $param_array['total_whatsapp_share']=0;
        $param_array['total_other_share']=0;
        $param_array['total_broadcast_share']=0;
        $param_array['total_super_like']=0;
        $param_array['total_excellent_like']=0;
        $param_array['total_mindblowing_like']=0;
        $param_array['total_wow_like']=0;
        $param_array['like_type']=null;
        $param_array['status'] =null;
        $param_array['modified']=null;
        $param_array['title'] = null;
        $param_array['description'] = null;
        $param_array['event_date'] =null;
        $param_array['venue'] =null;
        return $param_array;
    }


    public  function message_static_param($param_array,$post_data,$model,$static,$user_id,$list_message_type){

        $param_array['msg_id'] = $post_data[$model]['id'];
        $param_array['total_likes'] = $post_data[$model][$static]['total_likes'];
        $param_array['total_super_like'] = $post_data[$model][$static]['total_super_like'];
        $param_array['total_excellent_like'] = $post_data[$model][$static]['total_excellent_like'];
        $param_array['total_mindblowing_like'] = $post_data[$model][$static]['total_mindblowing_like'];
        $param_array['total_wow_like'] = $post_data[$model][$static]['total_wow_like'];
        $param_array['total_fb_share'] = $post_data[$model][$static]['total_fb_share'];
        $param_array['total_twitter_share'] = $post_data[$model][$static]['total_twitter_share'];
        $param_array['total_gplus_share'] = $post_data[$model][$static]['total_gplus_share'];
        $param_array['total_whatsapp_share'] = $post_data[$model][$static]['total_whatsapp_share'];
        $param_array['total_other_share'] = $post_data[$model][$static]['total_other_share'];
        $param_array['total_broadcast_share'] = $post_data[$model][$static]['total_broadcast_share'];


        $like_type = $this->check_messasge_liked($post_data[$model]['id'], $user_id,$list_message_type);
        if (!empty($like_type)) {
            $param_array['is_like'] = 'Y';
            $param_array['like_type'] = $like_type;
        } else {
            $param_array['is_like'] = 'N';
            $param_array['like_type'] = $like_type;
        }
        return $param_array;
    }


    public function create_model($list_message_type){
        if($list_message_type == 'QUEST'){
            $model = "Quest";
            $static = "QuestStatic";
        }else if($list_message_type == 'BORROW'){
            $model = "Borrow";
            $static = "BorrowStatic";

        }else if($list_message_type == 'BUY'){
            $model = "Buy";
            $static = "BuyStatic";

        }else if($list_message_type == 'RENT'){
            $model = "Rent";
            $static = "RentStatic";

        }
        $ret['model']= $model;
        $ret['static']= $static;
        return $ret;

    }


    public function get_file_name($path){
        if(!empty($path)){
            $str_array = explode("/",$path);
            $count = count($str_array);
            return $str_array[$count-2]."/".end($str_array);
        }else{
            return null;
        }
    }



    public function channel_message_list_param($list_message_type,$post_data,$channel_id,$thinapp_id,$user_id){

        $param_array = array();
        $param_array = $this->channel_message_default_param();
        switch ($list_message_type) {
            case ($list_message_type=='POST' || $list_message_type=='BROADCAST'):
                $param_array = $post_data['Message'];
                $param_array['channel_id'] = $post_data['ChannelMessage']['channel_id'];
                unset($param_array['MessageStatic']);
                unset($param_array['id']);
                $param_array['list_message_type'] = $list_message_type;
                $param_array['message_type_id'] = $post_data['Message']['id'];
                $param_array['is_owner'] = ($post_data['Message']['owner_user_id']==$user_id)?"YES":"NO";
                $param_array['file_name'] = $this->get_file_name($post_data['Message']['message_file_url']);

                $param_array = $this->message_static_param($param_array,$post_data,'Message','MessageStatic',$user_id,$list_message_type);

                $is_colobrator = $this->is_collobrator_for_channel($user_id,$channel_id,$thinapp_id);
                if (!empty($is_colobrator)) {
                    $param_array['is_collobrator'] = 'Y';
                    $param_array['coll_role'] = $is_colobrator['role'];
                } else {
                    $param_array['is_collobrator'] = 'N';
                    $param_array['coll_role'] = $is_colobrator;
                }

                break;

            case ($list_message_type=='QUEST' || $list_message_type=='BORROW' || $list_message_type=='BUY' ||$list_message_type=='RENT' ):

                $obj = $this->create_model($list_message_type);
                $model = $obj['model'];
                $static = $obj['static'];
                $param_array['list_message_type'] = $list_message_type;
                $param_array['message_type_id'] = $post_data[$model]['id'];
                $param_array['channel_id'] =$channel_id;
                $param_array['thinapp_id']=$thinapp_id;
                $param_array['message']=$post_data[$model]['question'];
                $param_array['is_owner'] = ($post_data[$model]['user_id']==$user_id)?"YES":"NO";
                $param_array['file_name'] = $this->get_file_name($post_data[$model]['image']);
                if(!empty($post_data['image'])){
                    $param_array['message_type']='IMAGE';
                }
                $param_array['message_file_url'] = $post_data[$model]['image'];
                $param_array['created'] =$post_data[$model]['created'];
                $param_array = $this->message_static_param($param_array,$post_data,$model,$static,$user_id,$list_message_type);
                break;

            case ($list_message_type=='EVENT'):
                $param_array['list_message_type'] = $list_message_type;
                $param_array['message_type_id'] = $post_data['Event']['id'];
                $param_array['channel_id'] =$channel_id;
                $param_array['thinapp_id']=$thinapp_id;
                $param_array['message']=$post_data['Event']['title'];
                $param_array['message_type']='IMAGE';
                $param_array['title'] = $post_data['Event']['title'];
                $param_array['description'] =$post_data['Event']['description'];
                $param_array['venue'] =$post_data['Event']['venue'];
                $param_array['message_file_url'] = $post_data['Event']['CoverImage']['media_path'];
                $day = date('d',strtotime($post_data['Event']['start_datetime']));
                $month = date('M',strtotime($post_data['Event']['start_datetime']));
                $param_array['event_date'] =$day."##".$month;;
                $param_array['created'] =$post_data['Event']['created'];
                $param_array['is_owner'] = ($post_data['Event']['user_id']==$user_id)?"YES":"NO";
                $param_array['file_name'] = $this->get_file_name($post_data['Event']['CoverImage']['media_path']);
                $param_array = $this->message_static_param($param_array,$post_data,'Event','EventStatic',$user_id,$list_message_type);


                break;
            case ($list_message_type=='SELL'):
                $param_array['list_message_type'] = $list_message_type;
                $param_array['message_type_id'] = $post_data['Sell']['id'];
                $param_array['channel_id'] =$channel_id;
                $param_array['thinapp_id']=$thinapp_id;
                $param_array['message']=$post_data['Sell']['item_name'];
                $param_array['message_type']='IMAGE';
                $image = isset($post_data['Sell']['CoverImage']['path'])?$post_data['Sell']['CoverImage']['path']:null;
                $param_array['message_file_url'] = $image;
                $param_array['created'] =$post_data['Sell']['created'];
                $param_array['is_owner'] = ($post_data['Sell']['user_id']==$user_id)?"YES":"NO";
                $param_array['file_name'] = $this->get_file_name($image);
                $param_array = $this->message_static_param($param_array,$post_data,'Sell','SellStatic',$user_id,$list_message_type);
                break;

            case ($list_message_type=='POLL'):
                $param_array['list_message_type'] = $list_message_type;
                $param_array['message_type_id'] = $post_data['Poll']['id'];
                $param_array['channel_id'] =$channel_id;
                $param_array['thinapp_id']=$thinapp_id;
                $param_array['message']=$post_data['Poll']['question_text'];
                if(!empty($post_data['Poll']['question_file_path'])){
                    $param_array['message_type']='IMAGE';
                }
                $param_array['message_file_url'] = $post_data['Poll']['question_file_path'];
                $param_array['created'] =$post_data['Poll']['created'];
                $param_array['is_owner'] = ($post_data['Poll']['user_id']==$user_id)?"YES":"NO";
                $param_array['file_name'] = $this->get_file_name($post_data['Poll']['question_file_path']);
                $param_array = $this->message_static_param($param_array,$post_data,'Poll','PollStatic',$user_id,$list_message_type);
                break;

            case ($list_message_type=='CONFERENCE'):
                $param_array['list_message_type'] = $list_message_type;
                $param_array['message_type_id'] = $post_data['Conference']['id'];
                $param_array['channel_id'] =$channel_id;
                $param_array['thinapp_id']=$thinapp_id;
                $param_array['message']=$post_data['Conference']['title'];
                $param_array['message_file_url'] = null;
                $param_array['created'] =$post_data['Conference']['created'];
                $param_array['is_owner'] = ($post_data['Conference']['user_id']==$user_id)?"YES":"NO";
                $param_array['file_name'] = null;
                $param_array = $this->message_static_param($param_array,$post_data,'Conference','ConferenceStatic',$user_id,$list_message_type);
                break;


            case ($list_message_type=='LOSS_FOUND'):
                $param_array['list_message_type'] = $list_message_type;
                $param_array['message_type_id'] = $post_data['LoseFound']['id'];
                $param_array['channel_id'] =$channel_id;
                $param_array['thinapp_id']=$thinapp_id;
                $param_array['message']=$post_data['LoseFound']['title'];
                $param_array['message_file_url'] = $post_data['LoseFound']['image_url'];
                $param_array['created'] =$post_data['LoseFound']['created'];
                $param_array['is_owner'] = ($post_data['LoseFound']['user_id']==$user_id)?"YES":"NO";
                $param_array['file_name'] = $this->get_file_name($post_data['LoseFound']['image_url']);
                $param_array = $this->message_static_param($param_array,$post_data,'LoseFound','LoseFoundStatic',$user_id,$list_message_type);
                break;

            default:
                $data =array();
                break;
        }
        return $param_array;
    }

    
    public function hide_number($mobile){
        //$mobile = substr_replace($mobile,'*',4,1);
        //$mobile = substr_replace($mobile,'*',7,1);
        //return substr_replace($mobile,'*',10,1);
        //return substr($mobile, 0, 3) . '******' .substr($mobile, 9, 4);
        return $mobile;
    }


    public function create_cache_file($thin_app_id,$name,$data_array){
        $content=json_encode($data_array);
        $name = $this->create_cache_file_name($thin_app_id,$name).".json";
        $file_path = WWW_ROOT.DS.'cache'.DS.$name;
        $fp = fopen($file_path,"w");
        fwrite($fp,$content);
        fclose($fp);
    }

    public function read_cache_file($thin_app_id,$name){

        $name = $this->create_cache_file_name($thin_app_id,$name).".json";
        $file_path = WWW_ROOT.DS.'cache'.DS.$name;
        if(file_exists($file_path)){
            return json_decode(file_get_contents($file_path),true);
        }else{
            return false;
        }
    }


    public function create_cache_file_name($thin_app_id,$name){
        return $thin_app_id."_".str_replace(" ","_",$name);
    }



	function createJson($fileName,$data){
			$data = json_encode($data);
			$fileUrl = 'cache/'.$fileName.'.json';
			file_put_contents($fileUrl, $data);
	}




    
    public function check_staff_has_upcoming_appointment($staff_id,$service_id,$thinapp_id){
        $appointment = ClassRegistry::init('AppointmentCustomerStaffService');
        $has_appointment = $appointment->find('count',array(
            'conditions'=>array(
                'AppointmentCustomerStaffService.appointment_staff_id'=>$staff_id,
                'AppointmentCustomerStaffService.appointment_service_id'=>$service_id,
                'AppointmentCustomerStaffService.appointment_datetime <'=> date('Y-m-d'),
                'AppointmentCustomerStaffService.thinapp_id <'=> $thinapp_id,
                'OR'=>array(
                    'AppointmentCustomerStaffService.status '=> 'NEW',
                    'AppointmentCustomerStaffService.status '=> 'RESCHEDULE'
                )
            ),
            'contain'=>false,
        ));
        if($has_appointment==0){
            return true;
        }else{
            return false;
        }
    }
    
    
    public function check_service_has_upcoming_appointment($service_id,$thinapp_id){
        $appointment = ClassRegistry::init('AppointmentCustomerStaffService');
        $has_appointment = $appointment->find('count',array(
            'conditions'=>array(
                'AppointmentCustomerStaffService.appointment_service_id'=>$service_id,
                'AppointmentCustomerStaffService.appointment_datetime <= '=> date('Y-m-d'),
                'AppointmentCustomerStaffService.thinapp_id <'=> $thinapp_id,
                'OR'=>array(
                    'AppointmentCustomerStaffService.status '=> 'NEW',
                    'AppointmentCustomerStaffService.status '=> 'RESCHEDULE'
                )
            ),
            'contain'=>false,
        ));
        if($has_appointment==0){
            return false;
        }else{
            return true;
        }
    }

    public function count_service_upcoming_appointment($service_id,$thinapp_id){
        $appointment = ClassRegistry::init('AppointmentCustomerStaffService');
        $total_booking = $appointment->find('count',array(
            'conditions'=>array(
                "AppointmentCustomerStaffService.appointment_datetime <= "=>date("Y-m-d"),
                "AppointmentCustomerStaffService.appointment_service_id"=>$service_id,
                "AppointmentCustomerStaffService.thinapp_id"=>$thinapp_id,
                'OR'=>array(
                    'AppointmentCustomerStaffService.status '=> 'NEW',
                    'AppointmentCustomerStaffService.status '=> 'RESCHEDULE'
                )
            ),
            'contain'=>false,
        ));
       return $total_booking;
    }





    public function check_staff_has_upcoming_appointment_by_day($staff_id,$day_time_id,$thinapp_id){
        $appointment = ClassRegistry::init('AppointmentCustomerStaffService');
        $has_appointment = $appointment->find('count',array(
            'conditions'=>array(
                'AppointmentCustomerStaffService.appointment_staff_id'=>$staff_id,
                'AppointmentCustomerStaffService.appointment_day_time_id'=>$day_time_id,
                'AppointmentCustomerStaffService.appointment_datetime <'=> date('Y-m-d'),
                'AppointmentCustomerStaffService.thinapp_id <'=> $thinapp_id,
                'OR'=>array(
                    'AppointmentCustomerStaffService.status '=> 'NEW',
                    'AppointmentCustomerStaffService.status '=> 'RESCHEDULE'
                )
            ),
            'contain'=>false,
        ));
        if($has_appointment == 0){
            return false;
        }else{
            return true;
        }
    }




    public function check_staff_valid_hours_time($staff_id,$time_from,$time_to,$day_time_id,$thin_app_id){

        $time_from = date('h:i A',strtotime($time_from));
        $time_to = date('h:i A',strtotime($time_to));
        $return_array['status'] =true;
        $is_upcoming = $this->check_staff_has_upcoming_appointment_by_day($staff_id,$day_time_id,$thin_app_id);
        if($time_from >= $time_to ){
            $return_array['status']=false;
            $return_array['message']='Invalid hours time.';
        }else if($is_upcoming){
            $return_array['status']=false;
            $return_array['message']='Sorry this staff has upcoming appointments.';
        }
        return $return_array;
    }

    function exportExcel($fileName, $headerRow, $data) {
        ini_set('max_execution_time', 1600);
        $fileContent = implode("\t ", $headerRow)."\n";
        foreach($data as $result) {
            $fileContent .=  implode("\t ", $result)."\n";
        }
        header('Content-type: application/ms-excel');
        header('Content-Disposition: attachment; filename='.$fileName);
        echo $fileContent;
        exit;
    }


    public function googleUrlShortner($longUrl){


        // This is the URL you want to shorten
        // Get API key from : http://code.google.com/apis/console/
        $key = GOOGLE_API_KEY;
        $googer = new GoogleUrlApi($key);

        // Test: Shorten a URL
        $shortDWName = $googer->shorten("https://davidwalsh.name");
        echo $shortDWName; // returns http://goo.gl/i002

        // Test: Expand a URL
        $longDWName = $googer->expand($shortDWName);
        echo $longDWName; // returns https://davidwalsh.name

        die;


    }

    /*********ENCRYPTION STARTS**********/
    static $SALT ="COS463U_^erf_R";
    public function encodeVariable($variable) {
        $hash = sha1(rand(0, 500) . microtime() . CustomComponent::$SALT);
        $hash=substr($hash, 0, 4);
        $signature = sha1(CustomComponent::$SALT . $variable . $hash);
        $signature=substr($signature, 0, 4);

        $encodedVariable = base64_encode($signature . "-" . $variable . "-" . $hash);
        return $encodedVariable;
    }

    public function decodeVariable($variable) {
        $data = explode('-', base64_decode($variable));
        $signature=sha1(CustomComponent::$SALT . $data[1] . $data[2]);
        $signature=substr($signature, 0, 4);
        if ($data[0] !== $signature) {
            return null;
        } else {
            return $data[1];
        }
    }
    /*********ENCRYPTION ENDS**********/


}


