<?php
date_default_timezone_set("Asia/Kolkata");
include('send_message.php');
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('logs', 'fanout', false, false, false);

list($queue_name, ,) = $channel->queue_declare("", false, false, true, false);

$channel->queue_bind($queue_name, 'logs');

echo ' [*] Waiting for logs. To exit press CTRL+C', "\n";

$callback = function($msg){
  //echo ' [x] ', $msg->body, "\n";
  $mq_id = $msg->body;
  //echo $mq_id."\n";
  $con=mysqli_connect("192.168.1.65","root","","message_app");
  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  //$query = "SELECT MessageQueue.id,MessageQueue.subscriber_id,MessageQueue.channel_id, `MessageQueue`.`message_id`, `Message`.`message`, `Channel`.`channel_name`, `Channel`.`id`, `Channel`.`image`, `Subscriber`.`id`, `Subscriber`.`mobile`, `User`.`id`, `User`.`mobile`, `User`.`device_token`, `User`.`username`, `User`.`device_type` FROM `message_app`.`message_queues` AS `MessageQueue` LEFT JOIN `message_app`.`subscribers` AS `Subscriber` ON (`MessageQueue`.`subscriber_id` = `Subscriber`.`id`) LEFT JOIN `message_app`.`messages` AS `Message` ON (`MessageQueue`.`message_id` = `Message`.`id`) LEFT JOIN `message_app`.`channels` AS `Channel` ON (`MessageQueue`.`channel_id` = `Channel`.`id`) left JOIN `message_app`.`users` AS `User` ON (`User`.`mobile` = `Subscriber`.`mobile`) WHERE `MessageQueue`.`status` = 'QUEUED' and `MessageQueue`.`id` = $mq_id";
  
  $query = "SELECT `Subscriber`.`mobile` FROM `message_app`.`message_queues` AS `MessageQueue` JOIN `message_app`.`subscribers` AS `Subscriber` ON `MessageQueue`.`subscriber_id` = `Subscriber`.`id` WHERE `MessageQueue`.`status` = 'QUEUED' and `MessageQueue`.`id` = $mq_id";
  //echo $query; 
  $result = mysqli_query($con,$query);
  $row = mysqli_fetch_assoc($result);
  //echo "<pre>"; print_r($row);
  //echo $row['mobile']."\n";
  $resp = send_message_mobile($mq_id,$row['mobile'], 'RabbitMQ test');
  //echo $resp."\n";
  mysqli_close($con);
  
};

$channel->basic_consume($queue_name, '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>