<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();


$channel->queue_declare('task_queue', false, true, false, false);


$con=mysqli_connect("192.168.1.65","root","","message_app");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM message_queues limit 100");
//echo "<pre>"; print_r($row);
while($row = mysqli_fetch_array($result)) {
   $data = $row['id'];
   $msg = new AMQPMessage($data,
                        array('delivery_mode' => 2) # make message persistent
                      );
    
    
    


$channel->basic_publish($msg, '', 'task_queue');

}

mysqli_close($con);




echo " [x] Sent ", $data, "\n";

$channel->close();
$connection->close();

?>