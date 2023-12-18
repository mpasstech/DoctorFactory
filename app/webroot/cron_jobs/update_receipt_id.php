<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
    $response = array();
    $connection = ConnectionUtil::getConnection();
    $query = "SELECT mpo.id, (SELECT CONCAT(DATE_FORMAT(`order`.`created`,'%d%m%y'),COUNT(`id`)+'') as receipt_id FROM `medical_product_orders` AS `order` WHERE  DATE(`order`.`created`) = DATE(mpo.created) AND `order`.`id` <= mpo.id AND `order`.`thinapp_id` = mpo.thinapp_id AND `order`.`is_expense` = 'N') AS new_receipt_id FROM medical_product_orders AS mpo WHERE mpo.is_expense = 'N' AND (mpo.receipt_id ='' OR mpo.receipt_id IS NULL) ORDER BY mpo.id DESC LIMIT 1000";
    $subscriber = $connection->query($query);
    if ($subscriber->num_rows) {
        $menu_list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
        $counter=0;
        $sql = "update medical_product_orders set receipt_id=? where id = ?";
        $stmt_df = $connection->prepare($sql);
        $created = Custom::created();
        $stmt_df->bind_param('ss',$receipt_id, $order_id);
        foreach($menu_list as $key => $data){
            $value = $data['new_receipt_id'].'-'.$data['id'];
            list($receipt_id, $order_id) = explode('-', $value);
            $stmt_df->execute();
        }
        $response['status'] = 1;
        $response['message'] = "List Found";
        Custom::sendResponse($response);

    } else {
        $response['status'] = 0;
        $response['message'] = "No list found";
    }
    Custom::sendResponse($response);
    exit();


