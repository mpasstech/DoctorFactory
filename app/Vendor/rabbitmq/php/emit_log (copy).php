<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->exchange_declare('logs', 'fanout', false, false, false);

$data = implode(' ', array_slice($argv, 1));
if(empty($data)) $data = "info: Hello World!";

/*
$msg = new AMQPMessage($data);

$channel->basic_publish($msg, 'logs');
*/

for($i = 1; $i < 10; $i++){
    $msg = new AMQPMessage("Message number : ".$i);
    $channel->basic_publish($msg, 'logs');
}

echo " [x] Sent ", $data, "\n";

$channel->close();
$connection->close();

?>

