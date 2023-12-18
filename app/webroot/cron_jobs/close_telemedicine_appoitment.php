<?php
    date_default_timezone_set("Asia/Kolkata");
    include_once "../webservice/Custom.php";
    $response = $list = $list_2 = $result=array();
    $connection = ConnectionUtil::getConnection();
    $connection->autocommit(false);
    $created = Custom::created();
    $minutes = 5;
    $status = 'CLOSED';
$query = "select acss.id from appointment_customer_staff_services as acss  join telemedicine_call_logs as tcl on tcl.appointment_customer_staff_service_id= acss.id  where acss.status IN('CONFIRM','NEW','RESCHEDULE') AND  DATE(acss.appointment_datetime)= DATE(NOW()) and tcl.connect_status ='completed'";
    $data_list = $connection->query($query);
    if ($data_list->num_rows) {
        $list = array_column(mysqli_fetch_all($data_list, MYSQLI_ASSOC),'id');
    }

$query = "select acss.id from  appointment_customer_staff_services as acss  join whatsapp_call_logs as wcl on wcl.appointment_id= acss.id  where acss.status IN('CONFIRM','NEW','RESCHEDULE') AND  DATE(acss.appointment_datetime)= DATE(NOW()) and TIMESTAMPDIFF(MINUTE,wcl.created,CONVERT_TZ(NOW(),'+00:00','+05:30')) > $minutes";
    $data_list = $connection->query($query);
    if ($data_list->num_rows) {
        $list_2 = array_column(mysqli_fetch_all($data_list, MYSQLI_ASSOC),'id');
    }

    $final_array =array_merge($list,$list_2);

    if(!empty($final_array)){
        foreach ($final_array as $key =>$appointment_id){
            $sql = "update appointment_customer_staff_services set status = ?, modified = ?  where id=$appointment_id";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param('ss',  $status, $created);
            $result[] =$stmt->execute() ;
        }
    }
   if (!in_array(false,$result) && !empty($result)) {
        $connection->commit();
        $response['status'] = 1;
        $response['message'] = "Data update successfully.";
        Custom::sendResponse($response);
    } else {
        $response['status'] = 0;
        $response['message'] = "Sorry, unable to update data.";
    }
    Custom::sendResponse($response);die;
    exit();


