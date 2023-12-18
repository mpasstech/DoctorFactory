<?php
require_once './vendor/autoload.php';
include('./config.php');
use Twilio\Rest\Client;
$request = file_get_contents("php://input");
$data = json_decode($request, true);
$date = isset($data["date"]) ? $data["date"] :  "";
$to_number = isset($data["to_number"]) ? $data["to_number"] :  "";
$body = isset($data["body"]) ? $data["body"] :  "";
$twilio = new Client($ACCOUNT_SID, $TOKEN);
$response =array();
try{
    if(!empty($to_number) && !empty($body)){
        $message = $twilio->messages
            ->create("whatsapp:$to_number", // to
                [
                    "from" => "whatsapp:+14582415503",
                    "body" => $body
                ]
            );
        $response['sid'] = $message->sid;
        $response['status'] = $message->status;
        $response['errorCode'] = $message->errorCode;
    }else{
        $response['status'] = 0;
        $response['errorCode'] = 'Invalid mobile and message';
    }
}catch (Exception $e){
    $response['status'] = 0;
    $response['errorCode'] = $e->getMessage();
}
echo json_encode($response);


?>