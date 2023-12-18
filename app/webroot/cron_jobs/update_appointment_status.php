<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
    $response = array();
    $connection = ConnectionUtil::getConnection();
    $created = Custom::created();
    $sql = "update appointment_customer_staff_services set status = ?, modified = ?  where ( status = 'NEW' OR status = 'RESCHEDULE' ) AND payment_status = 'PENDING'  and UNIX_TIMESTAMP(appointment_datetime) < UNIX_TIMESTAMP(NOW())";
    $stmt = $connection->prepare($sql);
    $status = 'EXPIRED';
    $stmt->bind_param('ss',  $status, $created);
    $sql_2 = "update appointment_customer_staff_services set status = ?, modified =? where ( status = 'CONFIRM' OR status = 'RESCHEDULE' ) AND payment_status = 'SUCCESS'  and UNIX_TIMESTAMP(appointment_datetime) < UNIX_TIMESTAMP(NOW())";
    $stmt_2 = $connection->prepare($sql_2);
    $status_2 = 'CLOSED';
    $created = Custom::created();
    $stmt_2->bind_param('ss',  $status_2, $created);
    if ($stmt->execute() && $stmt_2->execute() ) {
        $response['status'] = 1;
        $response['message'] = "Data update successfully.";
        Custom::sendResponse($response);

    } else {
        $response['status'] = 0;
        $response['message'] = "Sorry, unable to update data.";
    }
    Custom::sendResponse($response);
    exit();


