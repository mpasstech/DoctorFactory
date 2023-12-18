<?php
error_reporting(E_ALL);

//echo __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();


$channel->queue_declare('sms', false, false, false, false);

for($i = 1; $i < 10; $i++){
    $msg = new AMQPMessage("Message number : ".$i);
    $channel->basic_publish($msg, '', 'sms');
}
echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();

?>