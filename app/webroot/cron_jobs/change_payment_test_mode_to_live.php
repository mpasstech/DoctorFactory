<?php
include_once "../webservice/Custom.php";
$response = array();
/* this code cancel junk appointment */
$connection =ConnectionUtil::getConnection();
$sql = "DELETE FROM app_enable_functionalities where app_functionality_type_id = ? AND thinapp_id != 134";
$stmt = $connection->prepare($sql);
/* 61 IS PAYMENT TEST MODE PERMISSION ID */
$app_functionality_type_id = 61;
$stmt->bind_param('s',  $app_functionality_type_id);
if ($stmt->execute()) {
    $response['status'] = 1;
    $response['message'] = "Data update successfully.";
} else {
    $response['status'] = 0;
    $response['message'] = "Sorry, unable to update data.";
}

Custom::sendResponse($response);
exit();


