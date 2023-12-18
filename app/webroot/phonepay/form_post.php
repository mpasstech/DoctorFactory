<?php

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_errors', 0);
include_once '../constant.php';
include_once '../webservice/Custom.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $thin_app_id = $_POST['thin_app_id'];
    $secretKey = PHONE_PAY_SECRATE_KEY;
    $merchantId = PHONE_PAY_MERCHANT_ID;
    $postUrl = PHONE_PAY_API_URL;

    $testAppArray = Custom::getTestModeApp($thin_app_id);
    if(in_array($thin_app_id,$testAppArray)){
        $secretKey = PHONE_PAY_SECRATE_KEY_TEST;
        $postUrl = PHONE_PAY_API_URL_TEST;
        $merchantId = PHONE_PAY_MERCHANT_ID_TEST;
    }


    $merchantTransactionId = $_POST['merchantTransactionId'];
    $amount = $_POST['amount']*100;
   
    $callbackUrl = $_POST['callbackUrl'];
    $returnkUrl = $_POST['returnkUrl'];
    $mobileNumber = $_POST['mobileNumber'];
    $merchantUserId = $_POST['merchantUserId'];


    $postData = array(
        "merchantId"=>$merchantId,
        "merchantTransactionId"=> $merchantTransactionId,
        "merchantUserId"=> $merchantUserId,
        "amount"=> $amount,
        "redirectUrl"=> $returnkUrl,
        "redirectMode"=> "POST",
        "callbackUrl"=>  $callbackUrl,
        "mobileNumber"=> $mobileNumber,
        "paymentInstrument"=> array(
            "type"=> "PAY_PAGE"
        )
    );

 $payloadMain = base64_encode(json_encode($postData));
 $payload = $payloadMain."/pg/v1/pay".$secretKey;
  $Checksum = hash('sha256', $payload);
  $Checksum = $Checksum.'###1';
   $curl = curl_init();
  curl_setopt_array($curl, [
    CURLOPT_URL => $postUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode([
      'request' => $payloadMain
    ]),
    CURLOPT_HTTPHEADER => [
      "Content-Type: application/json",
      "X-VERIFY: ".$Checksum,
      "accept: application/json"
    ],
  ]);


    $response = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'cURL error: ' . curl_error($curl);die;
    }

    if ($response) {
        $response_data = json_decode($response, true);
        if ($response_data['success']==true) {
            $checkout_url = $response_data['data']['instrumentResponse']['redirectInfo']['url'];
            header("Location: $checkout_url");
            exit;
        } else {
            echo $response_data['message'];die;
        }
    } else {
        echo 'API request failed';
    }

}else{
    header("Location: https://www.mpasscheckin.com");
    exit();
}






?>