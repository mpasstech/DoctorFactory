<?php
include "vendor/autoload.php";
include('./config.php');
use Twilio\Jwt\ClientToken;

// put your Twilio API credentials here
$accountSid = $ACCOUNT_SID;
$authToken  = '5abe4b4cdab4c99d56ca69e3272f7c56';
$appSid = $APP_SID;
$name = $_GET['name'];

$capability = new ClientToken($accountSid, $authToken);
$capability->allowClientOutgoing($appSid);
$capability->allowClientIncoming($name);
$token = $capability->generateToken();

echo $token; die;
?>