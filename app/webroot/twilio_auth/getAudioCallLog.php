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
$callSid = isset($_GET['call_sid'])?$_GET['call_sid']:"";
$call = $twilio->calls($callSid)
               ->fetch();
$dataToSend = array();
$dataToSend['to_mobile'] = $call->toFormatted;
$dataToSend['from_mobile'] = $call->fromFormatted;
$dataToSend['sid'] = $call->sid;
$dataToSend['start_time'] = $call->startTime;
$dataToSend['end_time'] = $call->endTime;
$dataToSend['status'] = $call->status;
$dataToSend['duration'] = $call->duration;
$dataToSend['price'] = $call->price;
$dataToSend['price_unit'] = $call->priceUnit;
echo json_encode($dataToSend,true); die;
?>