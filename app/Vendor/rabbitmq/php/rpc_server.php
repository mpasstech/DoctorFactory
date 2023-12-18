<?php
include('send_message.php');
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('rpc_queue', false, false, false, false);

function fib($n) {
	if ($n == 0)
		return 0;
	if ($n == 1)
		return 1;
	return fib($n-1) + fib($n-2);
}

echo " [x] Awaiting RPC requests\n";
$callback = function($req) {
	$n = intval($req->body);
	echo " [.] $n\n";
	//echo "Simranjeet";
	$con=mysqli_connect("192.168.1.65","root","","message_app");
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	//$query = "SELECT MessageQueue.id,MessageQueue.subscriber_id,MessageQueue.channel_id, `MessageQueue`.`message_id`, `Message`.`message`, `Channel`.`channel_name`, `Channel`.`id`, `Channel`.`image`, `Subscriber`.`id`, `Subscriber`.`mobile`, `User`.`id`, `User`.`mobile`, `User`.`device_token`, `User`.`username`, `User`.`device_type` FROM `message_app`.`message_queues` AS `MessageQueue` LEFT JOIN `message_app`.`subscribers` AS `Subscriber` ON (`MessageQueue`.`subscriber_id` = `Subscriber`.`id`) LEFT JOIN `message_app`.`messages` AS `Message` ON (`MessageQueue`.`message_id` = `Message`.`id`) LEFT JOIN `message_app`.`channels` AS `Channel` ON (`MessageQueue`.`channel_id` = `Channel`.`id`) left JOIN `message_app`.`users` AS `User` ON (`User`.`mobile` = `Subscriber`.`mobile`) WHERE `MessageQueue`.`status` = 'QUEUED' and `MessageQueue`.`id` = $mq_id";
	
	$query = "SELECT `Subscriber`.`mobile` FROM `message_app`.`message_queues` AS `MessageQueue` JOIN `message_app`.`subscribers` AS `Subscriber` ON `MessageQueue`.`subscriber_id` = `Subscriber`.`id` WHERE `MessageQueue`.`status` = 'QUEUED' and `MessageQueue`.`id` = $n";
	//echo $query; 
	$result = mysqli_query($con,$query);
	$row = mysqli_fetch_assoc($result);
	//echo "<pre>"; print_r($row);
	//echo $row['mobile']."\n";
	$resp = send_message_mobile($n,$row['mobile'], 'RabbitMQ test');
	//echo $resp."\n";
	mysqli_close($con);
  
	$msg = new AMQPMessage($n,array('correlation_id' => $req->get('correlation_id')));
	$req->delivery_info['channel']->basic_publish($msg, '', $req->get('reply_to'));
	$req->delivery_info['channel']->basic_ack($req->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('rpc_queue', '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>
