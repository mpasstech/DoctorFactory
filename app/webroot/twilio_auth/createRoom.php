<?php
include('./vendor/autoload.php');
include('./config.php');
use Twilio\Rest\Client;

$room = isset($_GET["room"]) ? $_GET["room"] :  "";
$recording = isset($_GET["recording"]) ? $_GET["recording"] :  "";
$recordParticipantsOnConnect = ($recording=='YES')?true:false;
$twilio = new Client($ACCOUNT_SID, $TOKEN);
$create_room = $twilio->video->v1->rooms->create([
        "enableTurn" => true,
        "recordParticipantsOnConnect" => $recordParticipantsOnConnect,
        "statusCallback" => "https://mengage.in/doctor/services/room_callback",
        "type" => "group",
        "maxParticipants" =>2,
        "mediaRegion" =>'in1',
        "uniqueName" => $room
    ]);
echo $create_room->sid;
