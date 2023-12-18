<style>
img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}
</style>
<img src="redirect.gif"/>
<?php

$baseUrl = "https://mengage.in/doctor/";
  if(isset($_GET['appointment_id']))
  {
      $url = $baseUrl."booking_convenience/index.php?token=".base64_encode($_GET['appointment_id']);
      header("Location: $url");
      exit();
  }
  else
  {
      include '../webservice/ConnectionUtil.php';
      include '../webservice/Custom.php';
      $connection = ConnectionUtil::getConnection();
      $mode = "TEST";//PROD
      $baseUrl = "https://mengage.in/doctor/";
      $postUrl = "https://www.cashfree.com/checkout/post/submit";
      $secretKey = "7c09d72fe98e2c7df4384772a8bfb5237f2ff85e";
      $appId = "278553e2c5d1803aa8ce3586955872";
      if($mode=="TEST"){
          $secretKey = "899895a9f04308d36584adaa04a7b52d880c02ae"; //test
          $appId = "1461824bd90be36c6c495fd3081641"; //test
          $postUrl = "https://test.cashfree.com/billpay/checkout/post/submit";
          $baseUrl = "https://localhost/php_factory/";
      }
        $orderCurrency = 'INR';
        $thinappID = !empty($_GET['thinapp_id'])?trim($_GET['thinapp_id']):0;
        $orderNote = !empty($_GET['note'])?trim($_GET['note']):"";
        $user_id = !empty($_GET['user_id'])?trim($_GET['user_id']):"0";
        $customerName = !empty($_GET['name'])?trim($_GET['name']):"";
        $customerPhone = !empty($_GET['mobile'])?Custom::create_mobile_number($_GET['mobile']):"";
        $orderAmount = !empty($_GET['order_amount'])?trim($_GET['order_amount']):"";
        $orderId = date('Ymdhis').rand(10,999);
        $customerEmail = trim(!empty($_GET['email'])?$_GET['email']:'engage@mengage.in');
        if(empty($user_id)){
            $user_id = @Custom::get_user_by_mobile($thinappID,$customerPhone)['id'];
        }

        $RETURN_URL = $baseUrl."cashfree/result.php?user_id=$user_id&thin_app_id=$thinappID&mobile=$customerPhone&note=$orderNote";
        $NOTIFY_URL = $baseUrl."cashfree/result.php?user_id=$user_id&thin_app_id=$thinappID&mobile=$customerPhone&note=$orderNote";



      if($orderNote=="WALLET"){
            $orderId = $thinappID.'-WALLET-'.$orderId;
        }else if($orderNote=="SMSBUY"){
            $orderId = $thinappID.'-SMSBUY-'.$orderId;
        }else if($orderNote=="CLOUD"){
            $orderId = $thinappID.'-CLOUD-'.$orderId;
        }else if($orderNote=="SUBSCRIPTION"){
            $orderId = $thinappID.'-SUBSCRIPTION-'.$orderId;
        }else if($orderNote=="FEATURE"){
            $orderId = $thinappID.'-FEATURE-'.$orderId;
        }else if($orderNote=="USER_PAY"){
            $orderId = $thinappID.'-USER_PAY-'.$orderId;
        }else{
            die('Invalid Request');
        }
        $postData = array(
            "appId" => $appId,
            "orderId" => $orderId,
            "orderAmount" => $orderAmount,
            "orderCurrency" => $orderCurrency,
            "orderNote" => $orderNote,
            "customerName" => $customerName,
            "customerPhone" => $customerPhone,
            "customerEmail" => $customerEmail,
            "returnUrl" => $RETURN_URL,
            "notifyUrl" => $NOTIFY_URL,
        );
        ksort($postData);
        $signatureData = "";
        foreach ($postData as $key => $value){
            $signatureData .= $key.$value;
        }
        $signature = hash_hmac('sha256', $signatureData, $secretKey,true);
        $signature = base64_encode($signature);
        ?>

        <form id="redirectForm" method="post" action="<?php echo $postUrl; ?>">
            <input type="hidden" name="appId" value="<?php echo $appId; ?>"/>
            <input type="hidden" name="orderId" value="<?php echo $orderId; ?>"/>
            <input type="hidden" name="orderAmount" value="<?php echo $orderAmount; ?>"/>
            <input type="hidden" name="orderCurrency" value="<?php echo $orderCurrency; ?>"/>
            <input type="hidden" name="orderNote" value="<?php echo $orderNote; ?>"/>
            <input type="hidden" name="customerName" value="<?php echo $customerName; ?>"/>
            <input type="hidden" name="customerEmail" value="<?php echo $customerEmail; ?>"/>
            <input type="hidden" name="customerPhone" value="<?php echo $customerPhone; ?>"/>
            <input type="hidden" name="returnUrl" value="<?php echo $RETURN_URL; ?>"/>
            <input type="hidden" name="notifyUrl" value="<?php echo $NOTIFY_URL; ?>"/>
            <input type="hidden" name="signature" value="<?php echo $signature; ?>"/>
        </form>
        <script>
            document.getElementById("redirectForm").submit();
        </script>

<?php } ?>