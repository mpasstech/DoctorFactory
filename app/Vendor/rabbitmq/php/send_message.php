<?php

function send_message_mobile($mq_id = null, $mobile = null, $messge = null)
 {
    echo $mq_id."\n";
    //$username = str_replace(' ', '%20', $username);
    $mobile = trim($mobile);
    $messge = str_replace(' ', '%20', $messge);
    $curl_scraped_page = '';
    $url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?method=SendMessage&send_to=". $mobile;
    $url .= "&msg=" . $messge;
    //$url .= "&msg=hello%20team";
    $url .= "&msg_type=TEXT&userid=2000134323&auth_scheme=plain&password=abc123&v=1.1&format=json";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // The maximum number of seconds to allow cURL functions to execute.
    //curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1);
    $curl_scraped_page = curl_exec($ch);
    curl_close($ch);
    //$curl_scraped_page="success | 91123456789 | 22***************-29***************";
    //echo $curl_scraped_page;
    //echo $curl_scraped_page;
    /*
    $check_send_sms = json_decode($curl_scraped_page, TRUE);
    if ($check_send_sms["response"]["status"] == "success") {
      $status = 'SENT';
      $reason = $check_send_sms['response']['details'];
    } else {
      $status = 'FAILED';
      $reason = $check_send_sms['response']['details'];
    }
    
    $con=mysqli_connect("192.168.1.65","root","","message_app");
    // Check connection
    if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    $query = "UPDATE `message_app`.`message_queues` SET `status` = '$status', `reason` = '$reason' WHERE `message_queues`.`id` =$mq_id";
    //echo $query."\n" ;
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    */
  }
  
  
function send_message_android($device_id, $message)
{
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

?>