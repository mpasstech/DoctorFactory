<?php 
class Push
{
	public function push_notification($deviceToken, $passphrase, $message,$channel_name,$channel_id,$channel_image)
	{
		
		//$deviceToken = '2e6175f1abed2c5b024206b59d78b1191747a53660c0ec7ceca428470bfeb22b'; // Put your device token here (without spaces):
		//$passphrase = '123456'; // Put your private key's passphrase here:
		//$message = 'My first pusgjghjggkghjkgh script'; // Put your alert message here:
		
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', WWW_ROOT . 'push_file/ck.pem');
		
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		
		// Open a connection to the APNS server
		$fp = stream_socket_client( 'ssl://gateway.sandbox.push.apple.com:2195', $errno, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp)
			exit("Failed to connect: $errno $errstr");
			
		$body['aps'] = array('alert' => $message,'sound' => 'default','badge' => 0,'channel_id' => $channel_id,'channel_name' => $channel_name,'channel_image' => $channel_image); // Create the payload body
		$payload = json_encode($body); // Encode the payload as JSON
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload; // Build the binary notification
		$result = fwrite($fp, $msg, strlen($msg)); // Send it to the server
		fclose($fp); // Close the connection to the server
		//echo "<pre>"; print_r($result); die;
		if (!$result)
		{
			return false;
		}
		else
		{
			return true;
		}
		
	}
}
?>
