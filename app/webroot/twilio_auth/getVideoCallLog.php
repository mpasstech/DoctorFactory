<?php

// Update the path below to your autoload.php,
// see https://getcomposer.org/doc/01-basic-usage.md
include('./vendor/autoload.php');
include('./config.php');
use Twilio\Rest\Client;

// Find your Account Sid and Auth Token at twilio.com/console
$sid    = $ACCOUNT_SID;
$token  = "d7ab657910568de721fda42939aa2008";
$twilio = new Client($sid, $token);
$room = isset($_GET['room'])?$_GET['room']:"";

$call = $twilio->video->v1->rooms($room)
                          ->fetch();
$dataToSend = array();
$dataToSend['to_mobile'] = '';
$dataToSend['from_mobile'] = '';
$dataToSend['sid'] = $call->sid;
$dataToSend['start_time'] = $call->dateCreated;
$dataToSend['end_time'] = $call->endTime;
$dataToSend['status'] = $call->status;
$dataToSend['duration'] = $call->duration;
$dataToSend['price'] = '';
$dataToSend['price_unit'] = '';
echo json_encode($dataToSend,true); die;
?>