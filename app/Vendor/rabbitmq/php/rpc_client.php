<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FibonacciRpcClient {
	private $connection;
	private $channel;
	private $callback_queue;
	private $response;
	private $corr_id;

	public function __construct() {
		$this->connection = new AMQPConnection(
			'localhost', 5672, 'guest', 'guest');
		$this->channel = $this->connection->channel();
		list($this->callback_queue, ,) = $this->channel->queue_declare(
			"", false, false, true, false);
		$this->channel->basic_consume(
			$this->callback_queue, '', false, false, false, false,
			array($this, 'on_response'));
	}
	public function on_response($rep) {
		if($rep->get('correlation_id') == $this->corr_id) {
			$this->response = $rep->body;
		}
	}

	public function call($n) {
		$this->response = null;
		$this->corr_id = uniqid();

		$msg = new AMQPMessage(
			(string) $n,
			array('correlation_id' => $this->corr_id,
			      'reply_to' => $this->callback_queue,
			      )
			);
		//print_r($msg); die;
		$this->channel->basic_publish($msg, '', 'rpc_queue');
		while(!$this->response) {
			$this->channel->wait();
		}
		return intval($this->response);
	}
};

$fibonacci_rpc = new FibonacciRpcClient();
//$response = $fibonacci_rpc->call(30);
//echo " [.] Got ", $response, "\n";

$con=mysqli_connect("192.168.1.65","root","","message_app");
// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$result = mysqli_query($con,"SELECT * FROM message_queues limit 1000");
//echo "<pre>"; print_r($row);
while($row = mysqli_fetch_array($result)) {
	$data = $row['id'];
	$response = $fibonacci_rpc->call($data);
	echo " [.] Got ", $response, "\n";
}
mysqli_close($con);


?>
