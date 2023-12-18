<?php
header('Access-Control-Allow-Origin: *');

date_default_timezone_set("Asia/Kolkata");
include_once "Custom.php";
if ($_SERVER['REQUEST_METHOD'] ==='POST') {

    $request = file_get_contents("php://input");
    $data = json_decode($request, true);
    $response = array();
    $reference = isset($data['reference']) ? $data['reference'] : "";
    if (empty($reference))  {
        $response['status'] = 0;
        $response['message'] = 'Invalid  request ';
    }else {
        try {
            $param = explode("@@",base64_decode($reference));

            $to_nunber = $param[0];
            $from_number = $param[1];
            $thin_app_id = $param[2];
            $query = "select id,username, email, image, address, mobile, firebase_token  from users  where thinapp_id = $thin_app_id and mobile = '$from_number' and (role_id = 5 OR role_id = 1) limit 1";
            $connection = ConnectionUtil::getConnection();
            $message_list = $connection->query($query);
            if ($message_list->num_rows) {
                $user_data = mysqli_fetch_assoc($message_list);
                $response['status'] = 1;
                $response['message'] = "User profile found.";
                $response['from_data'] = $user_data;
            } else {
                $response['status'] = 0;
                $response['message'] = "Profile could not found.";
            }

            $query = "select id, username, email, image, address, mobile, firebase_token  from users  where thinapp_id = $thin_app_id and mobile = '$to_nunber' and (role_id = 5 OR role_id = 1) limit 1";
            $connection = ConnectionUtil::getConnection();
            $message_list1 = $connection->query($query);
            if ($message_list1->num_rows) {
                $user_data1 = mysqli_fetch_assoc($message_list1);
                $response['to_data'] = $user_data1;
            }



        } catch (Exception $e) {
            $response['status'] = 0;
            $response['message'] = "server error.";
        }
        $response['reference'] = Custom::createChatReference($to_nunber,$from_number,$thin_app_id);
        $response['mobile_to'] = $to_nunber;
        $response['mobile_from'] = $from_number;
        $response['thin_app_id'] = $thin_app_id;
    }

   echo json_encode($response);
}
exit();
