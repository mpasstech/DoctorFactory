<?php

class Deliver {

    // this function for send messages
    public function send_message($mobile = null, $messge = null, $is_transactional = null, $mask = null, $user_default_sms_api = null) {
        $mobile = trim($mobile);
        //$messge = str_replace(' ', '%20', $messge);
        $messge = urlencode($messge);

        if (isset($user_default_sms_api) && !empty($user_default_sms_api)) {
            $default_api = $user_default_sms_api;
        } else {
            $default_api = DEFAULT_SMS_API;
        }
        if ($default_api == 'GUPSHUP') {
            $curl_scraped_page = '';
            if ($is_transactional == 'N') {

                // $messge = "%E0%A4%8F%E0%A4%B8%20%E0%A4%8F%E0%A4%AE%20%E0%A4%8F%E0%A4%B8%20%E0%A4%97%E0%A4%AA%E0%A4%B6%E0%A4%AA";
                $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=" . $mobile;
                $url .= "&msg=" . $messge;
                $url .= "&msg_type=TEXT";
                $url .= "&userid=" . GUPSHUP_PROMOTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_PROMOTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
            } else {
                $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=" . $mask . "&send_to=" . $mobile;
                $url .= "&msg=" . $messge;
                $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
            }
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
            curl_close($ch);
            return $curl_scraped_page;
        }

        if ($default_api == 'ROUTE') {

            $type = 5;
            $curl_scraped_page = '';
            $url = "http://sms6.routesms.com:8080/bulksms/bulksms?username=" . ROUTE_SMS_PROMOTIONAL_API_USERNAME . "&password=" . ROUTE_SMS_PROMOTIONAL_API_PASSWORD . "&type=" . $type . "&dlr=1&destination=" . $mobile . "&source=" . $mask . "&message=" . $messge;
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // The maximum number of seconds to allow cURL functions to execute.
            //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
            $curl_scraped_page = curl_exec($ch);
            curl_close($ch);
            $resp = array();
            //echo $curl_scraped_page; die;
            $resp1 = explode('|', $curl_scraped_page);
            if ($resp1[0] == '1701') {
                $resp2 = explode(':', $resp1[1]);
                $response_id = $resp2[1];
                $resp['id'] = $response_id;
                $resp['status'] = 'success';
                $resp['details'] = '';
            } else {
                $resp['id'] = '';
                $resp['status'] = 'failed';
                $resp['details'] = '';
            }
            return json_encode(array('response' => $resp), true);
            die;
        }
        if ($default_api == 'PLIVO') {
            // code here
        }
    }
    
    public function sendTransSms($mobile = null, $message = null,$senderId = null) {
       
        if (DEFAULT_SMS_API == 'GUPSHUP') {
            $mobile = trim($mobile);
            $message = trim($message);
            
            $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&mask=".$senderId."&send_to=" . $mobile;

            $url .= "&msg=". urlencode($message) ;

            $url .= "&msg_type=TEXT&userid=" . GUPSHUP_TRANSACTIONAL_API_USER_ID . "&auth_scheme=plain&password=" . GUPSHUP_TRANSACTIONAL_API_USER_PASSWORD . "&v=1.1&format=json";
           
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $curl_scraped_page = curl_exec($ch);
          
            curl_close($ch);
            
            return $curl_scraped_page;
        }
    }

    public function send_message_android($device_id = null, $message = null, $channel_name = null, $channel_id = null, $channel_image = null, $is_mute = null, $is_push = null, $type = null, $mq_id = null, $thumb_url = null, $image = null, $is_response = null, $subscriber_id = null) {
       
       
	   $url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => array($device_id),
            'data' => array("message" => $message, 'channel_id' => $channel_id, 'channel_name' => $channel_name, 'channel_image' => $channel_image, 'is_mute' => $is_mute, 'is_push' => $is_push, 'type' => $type, 'mq_id' => $mq_id, 'thumb' => $thumb_url, 'image' => $image, 'is_response' => $is_response, 'subscriber_id' => $subscriber_id, 'msg_type' => 'MESSAGE'),
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

    public function push_notification($deviceToken = null, $message = null, $channel_name = null, $channel_id = null, $channel_image = null, $is_mute = null, $is_push = null, $type = null, $mq_id = null, $thumb_url = null, $image = null, $is_response = null, $subscriber_id = null) {
        //echo $is_mute;
        //$deviceToken = '8cbc97f89a131e8f096a500ccdce5bc14041e17eadbfd9a9c56620f2fd332a2b'; // Put your device token here (without spaces):
        //$passphrase = '123456'; // Put your private key's passphrase here:
        //$message = 'My first pusgjghjggkghjkgh script'; // Put your alert message here:
        $passphrase = '';
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', WWW_ROOT . 'push_file/ck.pem');

        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $errno, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
            exit("Failed to connect: $errno $errstr");
        }
        if ($is_mute == '0') {
            $body['aps'] = array('alert' => $message, 'sound' => 'default', 'badge' => 1, 'channel_id' => $channel_id, 'channel_name' => $channel_name, 'channel_image' => $channel_image, 'is_mute' => $is_mute, 'is_push' => $is_push, 'type' => $type, 'mq_id' => $mq_id, 'thumb' => $thumb_url, 'image' => $image, 'is_response' => $is_response, 'subscriber_id' => $subscriber_id, 'msg_type' => 'MESSAGE');
        } else {
            $body['aps'] = array('alert' => $message, 'badge' => 1, 'channel_id' => $channel_id, 'channel_name' => $channel_name, 'channel_image' => $channel_image, 'is_mute' => $is_mute, 'is_push' => $is_push, 'type' => $type, 'mq_id' => $mq_id, 'thumb' => $thumb_url, 'image' => $image, 'is_response' => $is_response, 'subscriber_id' => $subscriber_id, 'msg_type' => 'MESSAGE');
        }

        $payload = json_encode($body); // Encode the payload as JSON
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload; // Build the binary notification
        $result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
        fclose($fp); // Close the connection to the server
        //echo "<pre>"; print_r($result); die;
        if (!$result) {
            return false;
        } else {
            return true;
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
        $url = "http://sms6.routesms.com:8080/bulksms/bulksms?username=" . ROUTE_SMS_PROMOTIONAL_API_USERNAME . "&password=" . ROUTE_SMS_PROMOTIONAL_API_PASSWORD . "&type=" . $type . "&dlr=1&destination=" . $mobile . "&source=" . $mask . "&message=" . $messge;
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

    // this function for send voice messages
    public function route_voice_call_api($mobile = null, $ref_id = null) {
        //echo $mobile."<br>";
        //die('sadsadsad');
        //$username = str_replace(' ', '%20', $username);
        $mobile = trim($mobile);
        $curl_scraped_page = '';
        $url = "http://www.routevoice.com/httpApi/genCalls.php?user=" . ROUTE_VOICE_API_USERNAME . "&pwd=" . ROUTE_VOICE_API_PASSWORD . "&jobType=http-audio&dest=" . $mobile . "&msg=" . $ref_id . "&pulse=15";
        //echo $url ;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // The maximum number of seconds to allow cURL functions to execute.
        //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
        $curl_scraped_page = curl_exec($ch);
        curl_close($ch);
        //$curl_scraped_page="success | 91123456789 | 22***************-29***************";
        //echo $curl_scraped_page; die;
        return $curl_scraped_page;
    }

    // this function for send sms with plivo API
    public function plivo_sms_api($mobile = null, $messge = null, $type = null, $mask = null) {
        App::import('Vendor', 'deliver', array('file' => 'deliver/deliver.php'));
        $plivo = new RestAPI(PLIVO_AUTH_ID, PLIVO_AUTH_TOKEN);
        // Send a message
        $params = array(
            'src' => '1202323232',
            'dst' => $mobile,
            'text' => $messge,
            'type' => 'sms',
        );
        $response = $plivo->send_message($params);
        return $response;
    }

    public function send_curl_request($url = null, $params = array(), $resp_ret = true) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $resp = curl_exec($ch);
        curl_close($ch);
        if ($resp_ret) {
            echo $resp;
        }
    }

    public function push_android($device_id = null, $message = null, $channel_name = null, $channel_id = null, $channel_image = null, $status = null) {
        $url = 'https://android.googleapis.com/gcm/send';
        $data = array("message" => $message, 'channel_id' => $channel_id, 'channel_name' => $channel_name, 'channel_image' => $channel_image, 'status' => $status, 'msg_type' => 'INVITE');

        //$data='{"data":{"message":"'.$message.'", "channel_name":"'.$channel_name.'", "channel_id":"'.$channel_id.'", "channel_image":"'.$channel_image.'", "status":"'.$status.'"}}';


        $fields = array(
            'registration_ids' => array($device_id),
            'data' => $data);
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

    public function push_ios($deviceToken = null, $passphrase = null, $message = null,
            $channel_name = null, $channel_id = null, $channel_image = null, $status = null) {
        //echo $is_mute;
        //$deviceToken = '2e6175f1abed2c5b024206b59d78b1191747a53660c0ec7ceca428470bfeb22b'; // Put your device token here (without spaces):
        //$passphrase = '123456'; // Put your private key's passphrase here:
        //$message = 'My first pusgjghjggkghjkgh script'; // Put your alert message here:

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', WWW_ROOT . 'push_file/ck.pem');

        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $errno, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
            exit("Failed to connect: $errno $errstr");
        }
        $body['aps'] = array('alert' => $message, 'sound' => 'default', 'badge' => 1, 'channel_id' => $channel_id, 'channel_name' => $channel_name, 'channel_image' => $channel_image, 'status' => $status, 'msg_type' => 'INVITE'); // Create the payload body	

        $payload = json_encode($body); // Encode the payload as JSON
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload; // Build the binary notification
        $result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
        fclose($fp); // Close the connection to the server
        //echo "<pre>"; print_r($result); die;
        if (!$result) {
            return false;
        } else {
            return true;
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

    //-----------------------------------------------revised by Swapnil-----------------------------------------
    public function push_android_reply($device_token = null, $id = null, $msg_id = null, $user_id = null, $channel_id = null, $response_value = null, $response_media = null, $response_type = null, $action_type = null, $created = null, $username = null, $user_image_url = null
    ) {

        $url = 'https://android.googleapis.com/gcm/send';
        $data = array("id" => $id, 'msg_id' => $msg_id,
            'user_id' => $user_id, 'channel_id' => $channel_id,
            'message' => $response_value,
            'response_media' => $response_media,
            'response_type' => $response_type,
            'action_type' => $action_type,
            'created' => $created,
            'username' => $username,
            'user_image_url' => $user_image_url,
            'msg_type' => 'REPLY'
        );


        $fields = array(
            'registration_ids' => array($device_token),
            'data' => $data);
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
    }

    public function push_ios_reply($device_token = null, $id = null, $msg_id = null, $user_id = null,
                                   $channel_id = null, $response_value = null, $response_media = null, $response_type = null,
                                   $action_type = null, $created = null, $username = null, $user_image_url = null) {

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', WWW_ROOT . 'push_file/ck.pem');

        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $errno, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
            exit("Failed to connect: $errno $errstr");
        }
        $body['aps'] = array('alert' => $response_value,
            'sound' => 'default',
            'badge' => 1,
            "id" => $id, 'msg_id' => $msg_id,
            'user_id' => $user_id, 'channel_id' => $channel_id,
            'message' => $response_value,
            'response_media' => $response_media,
            'response_type' => $response_type,
            'action_type' => $action_type,
            'created' => $created,
            'username' => $username,
            'user_image_url' => $user_image_url,
            'msg_type' => 'REPLY'); // Create the payload body	

        $payload = json_encode($body); // Encode the payload as JSON
        $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload; // Build the binary notification
        $result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
        fclose($fp); // Close the connection to the server
        //echo "<pre>"; print_r($result); die;
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
    
    //----------------------------------------------Push For Unsubscribe----------------------------------------
    public function push_android_unsubscribe($device_token = null,  $channel_id = null,  $subscriber_id = null  ) {
        $url = 'https://android.googleapis.com/gcm/send';
        $data = array('channel_id' => $channel_id,
            'subscriber_id' => $subscriber_id,
            'msg_type' => 'UNSUBSCRIBE'
        );
        $fields = array(
            'registration_ids' => array($device_token),
            'data' => $data);
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
    }
     public function push_ios_unsubscribe($device_token = null,  $channel_id = null,  $subscriber_id = null  ) {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', WWW_ROOT . 'push_file/ck.pem');

        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

        // Open a connection to the APNS server
        $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $errno, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
            exit("Failed to connect: $errno $errstr");
        }
        $body['aps'] = array('alert' => $response_value,
            'sound' => 'default',
            'badge' => 1,
            'channel_id' => $channel_id,
            'subscriber_id' => $subscriber_id,
            'msg_type' => 'UNSUBSCRIBE'); // Create the payload body	

        $payload = json_encode($body); // Encode the payload as JSON
        $msg = chr(0) . pack('n', 32) . pack('H*', $device_token) . pack('n', strlen($payload)) . $payload; // Build the binary notification
        $result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
        fclose($fp); // Close the connection to the server
        //echo "<pre>"; print_r($result); die;
        if (!$result) {
            return false;
        } else {
            return true;
        }
    }
    //------------------------------------------------end by Swapnil--------------------------------------------
}

?>
