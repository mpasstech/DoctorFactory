<?php
	$baseUrl = "https://mengage.in/doctor/";
	$orderId = $_POST['orderId'];
    $orderAmount = $_POST['orderAmount'];
    $referenceId = $_POST['referenceId'];
    $txStatus = $_POST['txStatus'];
    $paymentMode = $_POST['paymentMode'];
    $txMsg = $_POST['txMsg'];
    $txTime = $_POST['txTime'];
    $note = $_REQUEST['note'];

	if($txStatus == 'SUCCESS')
	{

        include_once '../webservice/WebservicesFunction.php';
        include_once '../webservice/WebservicesFunction_2_3.php';
        include_once '../webservice/ConnectionUtil.php';
        include_once '../webservice/Custom.php';
        $connection = ConnectionUtil::getConnection();
        if($note=="WALLET"){
            $data=array(
                'thin_app_id'=>$_REQUEST['thin_app_id'],
                'app_key'=>APP_KEY,
                'user_id'=>$_REQUEST['user_id'],
                'mobile'=>$_REQUEST['mobile'],
                'price'=>$orderAmount,
                'transaction_id'=>$orderId,
                'payment_status'=>'SUCCESS',
                'remark'=>$txMsg,
                'payment_type'=>'CASHFREE'
            );
            $result =  WebServicesFunction::add_user_payment_to_wallet($data,true);

        }else  if($note=="USER_PAY"){

            $data=array(
                'thin_app_id'=>$_REQUEST['thin_app_id'],
                'app_key'=>APP_KEY,
                'user_id'=>$_REQUEST['user_id'],
                'mobile'=>$_REQUEST['mobile'],
                'total_price'=>$orderAmount,
                'transaction_id'=>$orderId,
                'transaction_status'=>'SUCCESS',
                'remark'=>$txMsg,
                'payment_type'=>'CASHFREE'
            );
            $result =  json_decode(WebServicesFunction_2_3::add_user_payment($data,true),true);
            if($result['status']==1){
                if(!empty($result['send_notification'])){
                    Custom::send_process_to_background();
                    Custom::send_notification_via_token($result['send_array'], array($result['token']), $result['thin_app_id']);
                    Custom::send_single_sms($result['mobile'], $result['message'], $result['thin_app_id']);
                }

            }

        }
       $param =  base64_encode(json_encode($_POST));
       header("Location: ".$baseUrl."cashfree/success.php?PM=$param&status='success'");
	}
	else
	{
        $param =  base64_encode(json_encode($_POST));
        header("Location: ".$baseUrl."cashfree/failer.php?PM=$param&status='failure'");
    }
?>






