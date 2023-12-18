<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = $curl_response =$update_array=$installed_array = array();
$connection = ConnectionUtil::getConnection();
$query = "select u.id as user_id, t.id ,u.firebase_token, u.mobile, u.thinapp_id, t.package_name  from users as u join thinapps as t  on t.id =u.thinapp_id where  (u.role_id = 1 OR u.role_id = 5) and t.category_name IN('DOCTOR','HOSPITAL')  and u.app_installed_status = 'INSTALLED' and u.firebase_token!='' order by t.id asc ";
$subscriber = $connection->query($query);
if ($subscriber->num_rows) {
    $list = mysqli_fetch_all($subscriber, MYSQLI_ASSOC);
    $final_array = $result_array =  $user_id_array = array();
    foreach($list as $key => $new_list){
        $final_array[$new_list['id']][$new_list['user_id']] = $new_list['firebase_token'];
        $user_id_array[$new_list['id']][] = $new_list['user_id'];
    }
    $path_to_firebase_cm = 'https://fcm.googleapis.com/fcm/send';
    foreach($final_array as $thin_app_id =>$token){
        $thin_app_data = Custom::getThinAppData($thin_app_id);
        $server_key = $thin_app_data['firebase_server_key'];
        $package_name = $thin_app_data['package_name'];
        $array_chunk = array_chunk($token, 300);
        foreach ($array_chunk as $chunk_key => $token_array){
            $token_array = array_values($token_array);

            $send_array = array(
                'title' => "CHECK",
                'message' => "CHECK",
                'description' => "CHECK"
            );

            $fields = array(
                'priority' => 'high',
                'registration_ids' => $token_array,
                'data' => $send_array,
                'restricted_package_name' => $package_name
            );
            $headers = array(
                'Authorization:key=' . $server_key,
                'Content-Type:application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $path_to_firebase_cm);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $curl_response = json_decode(curl_exec($ch), true);
            curl_close($ch);
            $curl_res = $curl_response['results'];
            $counter=0;
            foreach($curl_res as $key => $value){
                $user_id = $user_id_array[$thin_app_id][$key];
                if(isset($curl_res[$counter]['error'])){
                    if($curl_res[$counter]['error'] == "NotRegistered" || $curl_res[$counter]['error'] == "InvalidRegistration"){
                        $result_array[] = array('user_id'=>$user_id,'token'=>$array_chunk[$chunk_key][$key],'status'=>'UNINSTALLED');
                    }
                }else{
                    if(!empty($curl_res[$counter]['message_id'])){
                        $result_array[] = array('user_id'=>$user_id,'token'=>$array_chunk[$chunk_key][$key],'status'=>'INSTALLED');
                    }
                }
                $counter++;
            }
        }
    }

    $installed_count = $update_count =0;
    if(!empty($result_array)){
        foreach($result_array as $token =>$value){
            $modified = Custom::created();
            if($value['status'] =="UNINSTALLED"){
                $status = "UNINSTALLED";
                $sql = "UPDATE  users set app_installed_status = ?,  modified = ? where id = ?";
                $stmt_sub = $connection->prepare($sql);
                $stmt_sub->bind_param('sss', $status, $modified, $value['user_id']);
                if($stmt_sub->execute()){
                    $user_data = Custom::get_user_by_id($value['user_id']);
                    $update_count++;
                    Custom::deleteUserCache($user_data['is_support_user'],$user_data['id'], $user_data['mobile'], $user_data['thinapp_id']);
                }
            }
        }
    }

    $response['status'] = 1;
    $response['message'] = "Total ".($update_count)." users uninstalled app";

} else {
    $response['status'] = 0;
    $response['message'] = "No list found";
}


$result = Custom::synchronize_active_login();


$modified = Custom::created();
$status = "INACTIVE";
$sql = "UPDATE  active_tracker_voice set status = ?,  modified = ? where status = 'ACTIVE'";
$stmt_sub = $connection->prepare($sql);
$stmt_sub->bind_param('ss', $status, $modified);
$stmt_sub->execute();
Custom::sendResponse($response);





exit();


