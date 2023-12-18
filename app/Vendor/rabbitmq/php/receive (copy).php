<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();


//$channel->queue_declare('hello', false, false, false, false);

$channel->exchange_declare('logs', 'fanout', false, false, false);

list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

$channel->queue_bind($queue_name, 'logs');


$callback = function($msg) {
  $queue_id = $msg->body;
  echo $queue_id;
  /*
  $con=mysqli_connect("192.168.1.65","root","","message_app");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  $result = mysqli_query($con,"SELECT mobile FROM subscribers where id=$queue_id");
  $row = mysqli_fetch_row($result);
  //print_r($row);
  
  $messge = 'test rabbitmq';
  $mobile = trim($row['0']);
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
  echo $curl_scraped_page;
  
  mysqli_close($con);
  */
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>