<?php

    error_reporting(0);
    ignore_user_abort(true);
    set_time_limit(0);
    include_once '../constant.php';
    $baseUrl = SITE_PATH;
	$orderId = $_POST['orderId'];
    $orderAmount = $_POST['orderAmount'];
    $referenceId = $_POST['referenceId'];
    $txStatus = $_POST['txStatus'];
    $paymentMode = $_POST['paymentMode'];
    $txMsg = $_POST['txMsg'];
    $txTime = $_POST['txTime'];
    $order_data = explode("-",$orderId);
    $thin_app_id = $order_data[0];
    $type = $order_data[1];
	if($txStatus == 'SUCCESS')
	{
        include_once '../webservice/WebservicesFunction.php';
        include_once '../webservice/WebServicesFunction_2_3.php';
        include_once '../webservice/ConnectionUtil.php';
        include_once '../webservice/Custom.php';
        if($type=='SMSBUY'){
            $app_data = Custom::get_thinapp_data($thin_app_id);
            $connection = ConnectionUtil::getConnection();
            $post['app_key'] = APP_KEY;
            $post['user_id'] =$app_data['user_id'];
            $post['thin_app_id'] =$thin_app_id;
            $post['mobile'] =$app_data['phone'];
            $post['total_price'] =$orderAmount;
            $post['total_sms'] =round($orderAmount/MESSAGE_CHARGE_RATE);
            $post['total_storage'] ='';
            $post['support_admin_id'] =0;
            $post['recharge_by'] ='APP_ADMIN';
            $post['transaction_status'] ='SUCCESS';
            $post['recharge_for'] ='SMS';
            $post['transaction_id'] =$referenceId;
            $return = WebServicesFunction_2_3::recharge_sms($post,true);
            header("Location: ".$baseUrl."qutot/dti?sms_status=success");
        }else{
            header("Location: ".$baseUrl."cashfree/success.php?order_amount=".$orderAmount."&order_id=".$orderId."&reference_id=".$referenceId."&status=".$txStatus."&payment_mode=".$paymentMode."&message=".$txMsg."&time=".$txTime);
        }

	}
	else
	{
       if($type=='SMSBUY'){
            header("Location: ".$baseUrl."qutot/dti?sms_status=fail");
        }else{
            header("Location: ".$baseUrl."cashfree/failer.php?order_amount=".$orderAmount."&order_id=".$orderId."&reference_id=".$referenceId."&status=".$txStatus."&payment_mode=".$paymentMode."&message=".$txMsg."&time=".$txTime);
        }

    }

?>