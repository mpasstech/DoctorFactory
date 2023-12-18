<?php
date_default_timezone_set("Asia/Kolkata");
include_once "../webservice/Custom.php";
$response = $curl_response =$update_array=$installed_array = array();
$connection = ConnectionUtil::getConnection();
$connection->autocommit(false);
$file_path = LOCAL_PATH . 'app/webroot/statics_cache/';
$tmp_path = LOCAL_PATH . 'app/webroot/statics_cache/tmp_file.json';
$files_array = glob($file_path.'/*.json');
$data_array = array();
if(!empty($files_array)){
    foreach ($files_array as $key => $file_path){
        $bind_string = "";
        $query_data =array();
        if (file_exists($file_path)) {
            $file_data = explode("##",file_get_contents($file_path));
            $result = array();
            foreach ($file_data as $key2 => $data){
                $query_data =  json_decode($data,true);
                $sql = "INSERT INTO functionality_stats  (thinapp_id, mobile, user_id, module_name, module_sub_name, service_name, access_date_time) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_sub = $connection->prepare($sql);
                $stmt_sub->bind_param('sssssss', $query_data['thinapp_id'],$query_data['mobile'], $query_data['user_id'], $query_data['module_name'],$query_data['module_sub_name'],$query_data['service_name'],$query_data['access_date_time']);
                if ($stmt_sub->execute()) {
                    $result[] = true;
                }else{
                    $result[] = false;
                }
            }
            $file = explode("/",$file_path);
            $file_name = end($file);;
            if (!in_array(false,$result)) {
                $connection->commit();
                unlink($file_path);
                echo "Successfully update for ".$file_name."<br>";
            } else {
                $connection->rollback();
                echo "Fail update for ".$file_name."<br>";
            }
        }
    }
}

//Custom::sendResponse($response);
exit();


