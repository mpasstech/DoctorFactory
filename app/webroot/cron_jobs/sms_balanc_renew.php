<?php
include_once "../webservice/Custom.php";

$connection = ConnectionUtil::getConnection();
$connection->autocommit(false);
$created = Custom::created();
$result =array();
$query = "SELECT count(tp.id) as count , tp.id as thinapp_id from  thinapps as tp join app_sms_statics as ass on ass.thinapp_id = tp.id where (tp.booking_convenience_fee > 0 OR tp.booking_convenience_fee_video > 0) and ass.total_transactional_sms <= 500";
$get_sms = $connection->query($query);
if ($get_sms->num_rows) {
    

    $list_data = mysqli_fetch_all($get_sms, MYSQLI_ASSOC);
    foreach ($list_data as $key => $val){
        $thin_app_id = $val['thinapp_id'];
    $shoot_sms_on = 5000;
    $sql3 = "UPDATE app_sms_statics set total_transactional_sms = total_transactional_sms + ?, total_sms = total_transactional_sms, shoot_sms_on = ?, modified = ? where thinapp_id = ?";
    $stmt = $connection->prepare($sql3);
    $stmt->bind_param('ssss', $shoot_sms_on, $shoot_sms_on, $created, $thin_app_id);
    if($stmt->execute()){

        // $sql2 = "INSERT INTO app_sms_recharges (thinapp_id, user_id, sms_type, total_price, recharge_for,total_sms, support_admin_id, recharge_by, transaction_id, transaction_status,charge_rate,total_cloud_storage,  created, modified) VALUES ($thin_app_id, 2, 'TRANSACTIONAL', 1,'SMS',5000, '2','SUPPORT_ADMIN','0', 'NO_TRANSACTION','0.14','', '$created','$created')";
        //  $connection->query($sql2);
        $sms_type = "TRANSACTIONAL";
        $user_id = 2;
        $total_price = 1;
        $recharge_for = "SMS";
        $support_admin_id = 2;
        $recharge_by = "SUPPORT_ADMIN";
        $transaction_id = 0;
        $transaction_status = "NO_TRANSACTION";
        $charge_rate = "0.14";
        $total_cloud_storage = "";


         $sql = "INSERT INTO app_sms_recharges (thinapp_id, user_id, sms_type, total_price, recharge_for,total_sms, support_admin_id, recharge_by, transaction_id, transaction_status,charge_rate,total_cloud_storage,  created, modified) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";
         $stmt1 = $connection->prepare($sql);
         $stmt1->bind_param('ssssssssssssss', $thin_app_id, $user_id, $sms_type, $total_price,$recharge_for,$shoot_sms_on, $support_admin_id,$recharge_by,$transaction_id, $transaction_status,$charge_rate,$total_cloud_storage, $created,$created);
         $result[] = $stmt1->execute();
   }else{
            $result[] = false;
    }
}

}


if(!empty($result) && !in_array(false,$result)){
    $connection->commit();
    return true;
}else{
    $connection->rollback();
    return false;
}










