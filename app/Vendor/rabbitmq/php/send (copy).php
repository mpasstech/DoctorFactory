<?php
error_reporting(E_ALL);

//echo __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();


//$channel->queue_declare('', false, false, false, false);
$channel->exchange_declare('logs', 'fanout', false, false, false);


$con=mysqli_connect("192.168.1.65","root","","message_app");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM subscribers limit 5");

while($row = mysqli_fetch_array($result)) {
    //echo $row['message_id'];
    //echo "<br>";
  
    $msg = new AMQPMessage($row['id']);
    $channel->basic_publish($msg,'logs');

}

mysqli_close($con);



echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();

?>