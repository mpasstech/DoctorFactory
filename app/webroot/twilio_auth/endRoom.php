<?php
include('./vendor/autoload.php');
include('./config.php');
use Twilio\Rest\Client;

$RoomSid= isset($_GET["RoomSid"]) ? $_GET["RoomSid"] :  "";
$twilio = new Client($ACCOUNT_SID, $TOKEN);
$room = $twilio->video->v1->rooms($RoomSid)
    ->update("completed");
echo $room->uniqueName;
